-- Rashwear PostgreSQL schema
-- Run after creating the database, for example:
--   createdb rashwear
--   psql -d rashwear -f database/schema/postgres.sql

CREATE TABLE IF NOT EXISTS users (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMPTZ NULL,
    phone VARCHAR(30) NULL,
    role VARCHAR(30) NOT NULL DEFAULT 'customer',
    status VARCHAR(30) NOT NULL DEFAULT 'active',
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMPTZ NULL,
    updated_at TIMESTAMPTZ NULL
);
ALTER TABLE users ADD COLUMN IF NOT EXISTS phone VARCHAR(30) NULL;
ALTER TABLE users ADD COLUMN IF NOT EXISTS role VARCHAR(30) NOT NULL DEFAULT 'customer';
ALTER TABLE users ADD COLUMN IF NOT EXISTS status VARCHAR(30) NOT NULL DEFAULT 'active';

CREATE TABLE IF NOT EXISTS password_reset_tokens (
    email VARCHAR(255) PRIMARY KEY,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMPTZ NULL
);

CREATE TABLE IF NOT EXISTS sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id BIGINT NULL REFERENCES users(id) ON DELETE SET NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    payload TEXT NOT NULL,
    last_activity INTEGER NOT NULL
);
CREATE INDEX IF NOT EXISTS sessions_user_id_index ON sessions(user_id);
CREATE INDEX IF NOT EXISTS sessions_last_activity_index ON sessions(last_activity);

CREATE TABLE IF NOT EXISTS cache (
    key VARCHAR(255) PRIMARY KEY,
    value TEXT NOT NULL,
    expiration INTEGER NOT NULL
);
CREATE TABLE IF NOT EXISTS cache_locks (
    key VARCHAR(255) PRIMARY KEY,
    owner VARCHAR(255) NOT NULL,
    expiration INTEGER NOT NULL
);

CREATE TABLE IF NOT EXISTS jobs (
    id BIGSERIAL PRIMARY KEY,
    queue VARCHAR(255) NOT NULL,
    payload TEXT NOT NULL,
    attempts SMALLINT NOT NULL,
    reserved_at INTEGER NULL,
    available_at INTEGER NOT NULL,
    created_at INTEGER NOT NULL
);
CREATE INDEX IF NOT EXISTS jobs_queue_index ON jobs(queue);
CREATE TABLE IF NOT EXISTS job_batches (
    id VARCHAR(255) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    total_jobs INTEGER NOT NULL,
    pending_jobs INTEGER NOT NULL,
    failed_jobs INTEGER NOT NULL,
    failed_job_ids TEXT NOT NULL,
    options TEXT NULL,
    cancelled_at INTEGER NULL,
    created_at INTEGER NOT NULL,
    finished_at INTEGER NULL
);
CREATE TABLE IF NOT EXISTS failed_jobs (
    id BIGSERIAL PRIMARY KEY,
    uuid VARCHAR(255) NOT NULL UNIQUE,
    connection TEXT NOT NULL,
    queue TEXT NOT NULL,
    payload TEXT NOT NULL,
    exception TEXT NOT NULL,
    failed_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS categories (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT NULL,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    sort_order INTEGER NOT NULL DEFAULT 0,
    created_at TIMESTAMPTZ NULL,
    updated_at TIMESTAMPTZ NULL
);
CREATE INDEX IF NOT EXISTS categories_is_active_index ON categories(is_active);

CREATE TABLE IF NOT EXISTS subcategories (
    id BIGSERIAL PRIMARY KEY,
    category_id BIGINT NOT NULL REFERENCES categories(id) ON DELETE CASCADE,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    sort_order INTEGER NOT NULL DEFAULT 0,
    created_at TIMESTAMPTZ NULL,
    updated_at TIMESTAMPTZ NULL,
    UNIQUE(category_id, slug)
);

CREATE TABLE IF NOT EXISTS products (
    id BIGSERIAL PRIMARY KEY,
    category_id BIGINT NOT NULL REFERENCES categories(id) ON DELETE RESTRICT,
    subcategory_id BIGINT NOT NULL REFERENCES subcategories(id) ON DELETE RESTRICT,
    created_by BIGINT NULL REFERENCES users(id) ON DELETE SET NULL,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT NULL,
    price NUMERIC(12,2) NOT NULL CHECK (price >= 0),
    compare_at_price NUMERIC(12,2) NULL CHECK (compare_at_price >= 0),
    status VARCHAR(30) NOT NULL DEFAULT 'draft',
    is_featured BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMPTZ NULL,
    updated_at TIMESTAMPTZ NULL,
    deleted_at TIMESTAMPTZ NULL
);
CREATE INDEX IF NOT EXISTS products_status_index ON products(status);
CREATE INDEX IF NOT EXISTS products_is_featured_index ON products(is_featured);

CREATE TABLE IF NOT EXISTS product_images (
    id BIGSERIAL PRIMARY KEY,
    product_id BIGINT NOT NULL REFERENCES products(id) ON DELETE CASCADE,
    path VARCHAR(2048) NOT NULL,
    alt_text VARCHAR(255) NULL,
    sort_order INTEGER NOT NULL DEFAULT 0,
    is_primary BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMPTZ NULL,
    updated_at TIMESTAMPTZ NULL
);

CREATE TABLE IF NOT EXISTS product_variants (
    id BIGSERIAL PRIMARY KEY,
    product_id BIGINT NOT NULL REFERENCES products(id) ON DELETE CASCADE,
    sku VARCHAR(255) NOT NULL UNIQUE,
    size VARCHAR(50) NOT NULL,
    color VARCHAR(80) NULL,
    price NUMERIC(12,2) NULL CHECK (price >= 0),
    stock_quantity INTEGER NOT NULL DEFAULT 0 CHECK (stock_quantity >= 0),
    low_stock_threshold INTEGER NOT NULL DEFAULT 10 CHECK (low_stock_threshold >= 0),
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMPTZ NULL,
    updated_at TIMESTAMPTZ NULL,
    UNIQUE(product_id, size, color)
);
CREATE INDEX IF NOT EXISTS product_variants_is_active_index ON product_variants(is_active);

CREATE TABLE IF NOT EXISTS inventory_movements (
    id BIGSERIAL PRIMARY KEY,
    product_variant_id BIGINT NOT NULL REFERENCES product_variants(id) ON DELETE CASCADE,
    user_id BIGINT NULL REFERENCES users(id) ON DELETE SET NULL,
    quantity_change INTEGER NOT NULL CHECK (quantity_change <> 0),
    quantity_after INTEGER NOT NULL CHECK (quantity_after >= 0),
    reason VARCHAR(40) NOT NULL DEFAULT 'adjustment',
    note TEXT NULL,
    created_at TIMESTAMPTZ NULL,
    updated_at TIMESTAMPTZ NULL
);

CREATE TABLE IF NOT EXISTS carts (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NULL REFERENCES users(id) ON DELETE SET NULL,
    session_id VARCHAR(255) NULL,
    created_at TIMESTAMPTZ NULL,
    updated_at TIMESTAMPTZ NULL
);
CREATE UNIQUE INDEX IF NOT EXISTS carts_user_session_unique ON carts(user_id, session_id);

CREATE TABLE IF NOT EXISTS cart_items (
    id BIGSERIAL PRIMARY KEY,
    cart_id BIGINT NOT NULL REFERENCES carts(id) ON DELETE CASCADE,
    product_variant_id BIGINT NOT NULL REFERENCES product_variants(id) ON DELETE RESTRICT,
    quantity INTEGER NOT NULL CHECK (quantity > 0),
    created_at TIMESTAMPTZ NULL,
    updated_at TIMESTAMPTZ NULL,
    UNIQUE(cart_id, product_variant_id)
);

CREATE TABLE IF NOT EXISTS addresses (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NULL REFERENCES users(id) ON DELETE SET NULL,
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    address TEXT NOT NULL,
    city VARCHAR(100) NOT NULL,
    label VARCHAR(30) NOT NULL DEFAULT 'shipping',
    created_at TIMESTAMPTZ NULL,
    updated_at TIMESTAMPTZ NULL
);

CREATE TABLE IF NOT EXISTS orders (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NULL REFERENCES users(id) ON DELETE SET NULL,
    order_number VARCHAR(255) NOT NULL UNIQUE,
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255) NULL,
    customer_phone VARCHAR(30) NOT NULL,
    shipping_address TEXT NOT NULL,
    shipping_city VARCHAR(100) NOT NULL,
    payment_method VARCHAR(40) NOT NULL,
    payment_status VARCHAR(30) NOT NULL DEFAULT 'pending',
    status VARCHAR(30) NOT NULL DEFAULT 'pending',
    subtotal NUMERIC(12,2) NOT NULL CHECK (subtotal >= 0),
    delivery_fee NUMERIC(12,2) NOT NULL DEFAULT 0 CHECK (delivery_fee >= 0),
    total NUMERIC(12,2) NOT NULL CHECK (total >= 0),
    customer_note TEXT NULL,
    created_at TIMESTAMPTZ NULL,
    updated_at TIMESTAMPTZ NULL
);
CREATE INDEX IF NOT EXISTS orders_payment_status_index ON orders(payment_status);
CREATE INDEX IF NOT EXISTS orders_status_index ON orders(status);

CREATE TABLE IF NOT EXISTS order_items (
    id BIGSERIAL PRIMARY KEY,
    order_id BIGINT NOT NULL REFERENCES orders(id) ON DELETE CASCADE,
    product_id BIGINT NULL REFERENCES products(id) ON DELETE SET NULL,
    product_variant_id BIGINT NULL REFERENCES product_variants(id) ON DELETE SET NULL,
    product_name VARCHAR(255) NOT NULL,
    size VARCHAR(50) NULL,
    quantity INTEGER NOT NULL CHECK (quantity > 0),
    unit_price NUMERIC(12,2) NOT NULL CHECK (unit_price >= 0),
    line_total NUMERIC(12,2) NOT NULL CHECK (line_total >= 0),
    created_at TIMESTAMPTZ NULL,
    updated_at TIMESTAMPTZ NULL
);

CREATE TABLE IF NOT EXISTS store_settings (
    id BIGSERIAL PRIMARY KEY,
    key VARCHAR(255) NOT NULL UNIQUE,
    value JSONB NULL,
    created_at TIMESTAMPTZ NULL,
    updated_at TIMESTAMPTZ NULL
);

CREATE TABLE IF NOT EXISTS contact_messages (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NULL REFERENCES users(id) ON DELETE SET NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    subject VARCHAR(255) NULL,
    message TEXT NOT NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'unread',
    created_at TIMESTAMPTZ NULL,
    updated_at TIMESTAMPTZ NULL
);
CREATE INDEX IF NOT EXISTS contact_messages_status_index ON contact_messages(status);

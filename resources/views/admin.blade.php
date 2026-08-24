@extends('layouts.app')

@section('title','Admin Dashboard — Rashwear')
@section('description','Admin panel for managing Rashwear products, orders, customers and site settings.')
@section('page','admin')

@section('content')
<section class="section">
    <div class="container">
        <div class="section-head">
            <div>
                <span class="eyebrow">Admin panel</span>
                <h1>Control center</h1>
                <p>Monitor orders, manage products, edit customers and update your storefront from a single admin page.</p>
            </div>
            <div class="admin-tabbar">
                <button type="button" class="btn btn-outline admin-tab active" data-admin-tab="dashboard">Dashboard</button>
                <button type="button" class="btn btn-outline admin-tab" data-admin-tab="products">Products</button>
                <button type="button" class="btn btn-outline admin-tab" data-admin-tab="orders">Orders</button>
                @if(auth()->user()->role === 'owner')
                    <button type="button" class="btn btn-outline admin-tab" data-admin-tab="users">Users</button>
                    <button type="button" class="btn btn-outline admin-tab" data-admin-tab="settings">Settings</button>
                @endif
            </div>
        </div>

        <div class="admin-summary-grid">
            <div class="summary-card">
                <span class="eyebrow">Open orders</span>
                <h2 data-admin-summary="openOrders">0</h2>
                <p>Orders waiting for confirmation or delivery.</p>
            </div>
            <div class="summary-card">
                <span class="eyebrow">Products</span>
                <h2 data-admin-summary="products">0</h2>
                <p>Items currently available in the catalog.</p>
            </div>
            <div class="summary-card">
                <span class="eyebrow">Users</span>
                <h2 data-admin-summary="users">0</h2>
                <p>Registered customers and admin users.</p>
            </div>
            <div class="summary-card">
                <span class="eyebrow">Low stock</span>
                <h2 data-admin-summary="lowStock">0</h2>
                <p>Products with inventory below threshold.</p>
            </div>
        </div>

        <div class="admin-panel active" data-admin-panel="dashboard">
            <div class="section-head">
                <div>
                    <span class="eyebrow">Dashboard</span>
                    <h2>Quick actions</h2>
                </div>
                <button type="button" class="btn btn-primary" data-admin-action="add-product">Add product</button>
            </div>
            <div class="admin-grid">
                <div class="summary-card">
                    <h3>Recent activity</h3>
                    <p>Review the latest orders, product updates and customer messages.</p>
                    <ul style="margin:18px 0 0 0; padding:0; list-style:none;">
                        <li>Order <strong>#RW-1203</strong> moved to <strong>Pending</strong>.</li>
                        <li>New product draft created for <strong>Afternoon Kurta</strong>.</li>
                        <li>Customer <strong>Ram Joshi</strong> requested inventory update.</li>
                    </ul>
                </div>
                <div class="summary-card">
                    <h3>Store controls</h3>
                    <button type="button" class="btn btn-outline" data-admin-action="toggle-maintenance">Maintenance mode</button>
                    <button type="button" class="btn btn-outline" data-admin-action="toggle-checkout">Disable checkout</button>
                    <button type="button" class="btn btn-outline" data-admin-action="export-orders" style="margin-top:12px;">Export orders</button>
                </div>
            </div>
        </div>

        <div class="admin-panel" data-admin-panel="products">
            <div class="section-head">
                <div>
                    <span class="eyebrow">Products</span>
                    <h2>Catalog management</h2>
                </div>
                <button type="button" class="btn btn-primary" data-admin-action="add-product">New product</button>
            </div>
            <div class="admin-grid">
                <div>
                    <div class="category-manager" data-category-manager>
                        <div>
                            <span class="eyebrow">Catalog structure</span>
                            <strong>Manage categories</strong>
                        </div>
                        <form data-category-form>
                            <input class="field" type="text" data-category-name placeholder="New category" required>
                            <input class="field" type="text" data-category-subcategories placeholder="Subcategories, comma separated" required>
                            <button type="submit" class="btn btn-outline">Add category</button>
                        </form>
                        <form data-subcategory-form>
                            <select class="field" data-subcategory-category required aria-label="Category for new subcategory"></select>
                            <input class="field" type="text" data-subcategory-name placeholder="New subcategory" required>
                            <button type="submit" class="btn btn-outline">Add subcategory</button>
                        </form>
                        <div class="category-list" data-category-list></div>
                    </div>
                   <br><br>

                    <div class="admin-panel-bar">
                        <input type="search" class="field" placeholder="Search products..." data-product-search>
                        <select class="field" data-product-category-filter aria-label="Filter products by category"></select>
                        <select class="field" data-product-subcategory-filter aria-label="Filter products by subcategory" disabled></select>
                        <select class="field" data-product-stock-sort aria-label="Sort products by stock">
                            <option value="default">Stock order</option>
                            <option value="stock-asc">Stock: low to high</option>
                            <option value="stock-desc">Stock: high to low</option>
                        </select>
                        <button type="button" class="btn btn-outline" data-admin-action="filter-low-stock">Low stock</button>
                    </div>
                    <div class="admin-table-scroll" style="overflow-x:auto; margin-top:14px;">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Inventory</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody data-product-list></tbody>
                        </table>
                    </div>
                </div>
                <div class="admin-form-card">
                    <h3 data-product-form-title>Add new product</h3>
                    <form data-product-form>
                        <input type="hidden" data-product-id>
                        <div class="field"><label>Name</label><input type="text" data-product-field="name" required></div>
                        <div class="field"><label>Category</label><select data-product-field="category" required></select></div>
                        <div class="field"><label>Subcategory</label><select data-product-field="subcategory" required disabled>
                            <option value="">Choose a category first</option>
                        </select></div>
                        <div class="field"><label>Price</label><input type="number" data-product-field="price" required min="0"></div>
                        <div class="field"><label>Product image</label><input type="file" data-product-field="image" accept="image/jpeg,image/png,image/webp" required></div>
                        <div class="field"><label>Sizes</label><input type="text" data-product-field="sizes" placeholder="S, M, L, XL" required></div>
                        <div class="field"><label>Stock by size</label><div class="size-stock-fields" data-size-stock-fields><p class="form-hint">Add sizes above to set quantities.</p></div></div>
                        <input type="hidden" data-product-field="stock" value="0">
                        <div class="field"><label>Status</label><select data-product-field="status">
                            <option value="active">Active</option>
                            <option value="draft">Draft</option>
                            <option value="archived">Archived</option>
                        </select></div>
                        <div class="field"><label>Description</label><textarea data-product-field="description" rows="4"></textarea></div>
                        <button type="submit" class="btn btn-primary">Save product</button>
                        <button type="button" class="btn btn-outline" data-admin-action="cancel-product-form">Cancel</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="admin-panel" data-admin-panel="orders">
            <div class="section-head">
                <div>
                    <span class="eyebrow">Orders</span>
                    <h2>Manage checkout</h2>
                </div>
                <button type="button" class="btn btn-primary" data-admin-action="export-orders">Export orders</button>
            </div>
            <div style="overflow-x:auto; margin-top:16px;">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Update</th>
                        </tr>
                    </thead>
                    <tbody data-order-list></tbody>
                </table>
            </div>
        </div>

        @if(auth()->user()->role === 'owner')
        <div class="admin-panel" data-admin-panel="users">
            <div class="section-head">
                <div>
                    <span class="eyebrow">Users</span>
                    <h2>Customer and admin accounts</h2>
                </div>
                <button type="button" class="btn btn-primary" data-admin-action="invite-user">Invite user</button>
            </div>
            <div style="overflow-x:auto; margin-top:16px;">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody data-user-list></tbody>
                </table>
            </div>
        </div>

        <div class="admin-panel" data-admin-panel="settings">
            <div class="section-head">
                <div>
                    <span class="eyebrow">Settings</span>
                    <h2>Store configuration</h2>
                </div>
                <button type="button" class="btn btn-outline" data-admin-action="save-settings">Save settings</button>
            </div>
            <div class="admin-grid">
                <div class="admin-form-card">
                    <h3>Store status</h3>
                    <div class="field"><label><input type="checkbox" data-setting="maintenance"> Maintenance mode</label></div>
                    <div class="field"><label><input type="checkbox" data-setting="disableCheckout"> Disable checkout</label></div>
                </div>
                <div class="admin-form-card">
                    <h3>Contact info</h3>
                    <div class="field"><label>Email</label><input type="email" data-setting="contactEmail"></div>
                    <div class="field"><label>Phone</label><input type="tel" data-setting="contactPhone"></div>
                    <div class="field"><label>WhatsApp</label><input type="text" data-setting="whatsappLink"></div>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>

@push('scripts')
<script src="{{ asset('js/admin.js') }}"></script>
@endpush
@endsection

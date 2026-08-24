@extends('layouts.app')

@section('title','Shop All — Rashwear')
@section('description','Browse Rashwear products for men, women and kids. Filter by category, search by keyword, and add items to your bag.')
@section('page','shop')

@section('content')
<div class="page-head container">
    <span class="eyebrow">Shop</span>
    <h1>All products</h1>
</div>

<section class="section-tight">
    <div class="container">
        <div class="filter-bar">
            <button class="filter-pill" data-filter="all">All</button>
            <button class="filter-pill" data-filter="men">Men</button>
            <button class="filter-pill" data-filter="women">Women</button>
            <button class="filter-pill" data-filter="kids">Kids</button>
            <select class="sort-select" data-sort-select aria-label="Sort products by price">
                <option value="default">Sort by price</option>
                <option value="price-asc">Low to high</option>
                <option value="price-desc">High to low</option>
            </select>
            <span class="eyebrow" data-result-count></span>
        </div>
        <div class="sub-filter-bar" data-sub-bar></div>
        <div class="product-grid" data-shop-grid></div>
    </div>
</section>
@endsection

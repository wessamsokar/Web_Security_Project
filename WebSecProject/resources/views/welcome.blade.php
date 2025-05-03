@extends('layouts.master')
@section('title', 'Welcome to Fashion Hub')
@section('content')
<!-- Hero Section -->
<div class="position-relative overflow-hidden">
    <div class="bg-dark text-white" style="background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1483985988355-763728e1935b?q=80&w=2070&auto=format&fit=crop'); background-size: cover; background-position: center; height: 100vh;">
        <div class="container h-100 d-flex flex-column justify-content-center">
            <div class="col-md-8 col-lg-6">
                <h1 class="display-3 fw-bold mb-3">Discover Your Style</h1>
                <p class="lead mb-4">Elevate your wardrobe with our curated collection of premium fashion. Trendy, timeless, and tailored just for you.</p>
                <div class="d-flex gap-3">
                    <a href="/shop" class="btn btn-primary">Shop Now</a>
                    <a href="/collections" class="btn btn-outline-light">Explore Collections</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Featured Categories -->
<div class="container my-5 py-4">
    <h2 class="text-center mb-5 fw-bold">Shop By Category</h2>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <img src="https://images.unsplash.com/photo-1591369822096-ffd140ec948f?q=80&w=1974&auto=format&fit=crop" class="card-img-top" alt="Women's Fashion">
                <div class="card-body text-center">
                    <h4 class="card-title fw-bold">Women</h4>
                    <p class="card-text text-muted">Discover elegant dresses, tops, and accessories for every occasion.</p>
                    <a href="/shop/women" class="btn btn-dark btn-sm">Shop Women</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <img src="https://images.unsplash.com/photo-1617137968427-85924c800a22?q=80&w=1974&auto=format&fit=crop" class="card-img-top" alt="Men's Fashion">
                <div class="card-body text-center">
                    <h4 class="card-title fw-bold">Men</h4>
                    <p class="card-text text-muted">Explore our collection of contemporary men's clothing and accessories.</p>
                    <a href="/shop/men" class="btn btn-dark btn-sm">Shop Men</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <img src="https://images.unsplash.com/photo-1519415943484-9fa1873496d4?q=80&w=1970&auto=format&fit=crop" class="card-img-top" alt="Accessories">
                <div class="card-body text-center">
                    <h4 class="card-title fw-bold">Accessories</h4>
                    <p class="card-text text-muted">Complete your look with our stylish selection of accessories.</p>
                    <a href="/shop/accessories" class="btn btn-dark btn-sm">Shop Accessories</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- New Arrivals -->
<div class="bg-light py-5">
    <div class="container py-3">
        <div class="row mb-4 align-items-center">
            <div class="col">
                <h2 class="fw-bold mb-0">New Arrivals</h2>
            </div>
            <div class="col-auto">
                <a href="/shop/new" class="text-decoration-none">View All <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
        
        <div class="row g-4">
            <!-- Product Card 1 -->
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="position-relative">
                        <img src="https://images.unsplash.com/photo-1586363104862-3a5e2ab60d99?q=80&w=1971&auto=format&fit=crop" class="card-img-top" alt="Product">
                        <div class="position-absolute top-0 end-0 p-2">
                            <button class="btn btn-sm btn-light rounded-circle p-1">
                                <i class="bi bi-heart"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <h5 class="card-title mb-1">Classic White Blouse</h5>
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="text-muted mb-0">Women's</p>
                            <p class="fw-bold mb-0">$49.99</p>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 pt-0">
                        <button class="btn btn-dark btn-sm w-100">Add to Cart</button>
                    </div>
                </div>
            </div>
            
            <!-- Product Card 2 -->
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="position-relative">
                        <img src="https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?q=80&w=1974&auto=format&fit=crop" class="card-img-top" alt="Product">
                        <div class="badge bg-danger position-absolute top-0 start-0 m-2">Sale</div>
                        <div class="position-absolute top-0 end-0 p-2">
                            <button class="btn btn-sm btn-light rounded-circle p-1">
                                <i class="bi bi-heart"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <h5 class="card-title mb-1">Leather Ankle Boots</h5>
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="text-muted mb-0">Footwear</p>
                            <div>
                                <span class="text-decoration-line-through text-muted me-2">$129.99</span>
                                <span class="fw-bold text-danger">$89.99</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 pt-0">
                        <button class="btn btn-dark btn-sm w-100">Add to Cart</button>
                    </div>
                </div>
            </div>
            
            <!-- Product Card 3 -->
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="position-relative">
                        <img src="https://images.unsplash.com/photo-1617952233714-489884310f73?q=80&w=1974&auto=format&fit=crop" class="card-img-top" alt="Product">
                        <div class="position-absolute top-0 end-0 p-2">
                            <button class="btn btn-sm btn-light rounded-circle p-1">
                                <i class="bi bi-heart"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <h5 class="card-title mb-1">Slim Fit Dress Shirt</h5>
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="text-muted mb-0">Men's</p>
                            <p class="fw-bold mb-0">$59.99</p>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 pt-0">
                        <button class="btn btn-dark btn-sm w-100">Add to Cart</button>
                    </div>
                </div>
            </div>
            
            <!-- Product Card 4 -->
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="position-relative">
                        <img src="https://images.unsplash.com/photo-1615397349754-cfa2066a298e?q=80&w=1974&auto=format&fit=crop" class="card-img-top" alt="Product">
                        <div class="badge bg-success position-absolute top-0 start-0 m-2">New</div>
                        <div class="position-absolute top-0 end-0 p-2">
                            <button class="btn btn-sm btn-light rounded-circle p-1">
                                <i class="bi bi-heart"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <h5 class="card-title mb-1">Designer Handbag</h5>
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="text-muted mb-0">Accessories</p>
                            <p class="fw-bold mb-0">$79.99</p>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 pt-0">
                        <button class="btn btn-dark btn-sm w-100">Add to Cart</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Featured Collection Banner -->
<div class="container my-5">
    <div class="row align-items-center">
        <div class="col-md-6 mb-4 mb-md-0">
            <img src="https://images.unsplash.com/photo-1490481651871-ab68de25d43d?q=80&w=2070&auto=format&fit=crop" class="img-fluid" alt="Summer Collection">
        </div>
        <div class="col-md-5 offset-md-1">
            <h2 class="fw-bold mb-3">Summer Collection</h2>
            <p class="lead mb-4">Discover our new summer essentials. Light fabrics, bold colors, and effortless style for the warmer days ahead.</p>
            <a href="/collections/summer" class="btn btn-dark">Explore Collection</a>
        </div>
    </div>
</div>

<!-- Testimonials -->
<div class="bg-light py-5">
    <div class="container py-3">
        <h2 class="text-center fw-bold mb-5">What Our Customers Say</h2>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <div class="text-center p-4">
                                <div class="mb-3">
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                </div>
                                <p class="lead mb-3">"The quality of the clothes is exceptional! I've received so many compliments on my recent purchases. Fast shipping and great customer service too."</p>
                                <p class="fw-bold mb-0">Sarah Johnson</p>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="text-center p-4">
                                <div class="mb-3">
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-half text-warning"></i>
                                </div>
                                <p class="lead mb-3">"I love the unique styles that aren't available elsewhere. The fit is perfect and the materials are comfortable for all-day wear."</p>
                                <p class="fw-bold mb-0">Michael Taylor</p>
                            </div>
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Newsletter -->
<div class="container my-5 py-3">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <h2 class="fw-bold mb-3">Join Our Community</h2>
            <p class="mb-4">Subscribe to our newsletter for exclusive offers, style tips, and first access to new collections.</p>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <form class="d-flex">
                        <input type="email" class="form-control me-2" placeholder="Your email address">
                        <button class="btn btn-dark" type="submit">Subscribe</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
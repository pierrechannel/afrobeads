<!-- resources/views/partials/navbar.blade.php -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#"><i class="fas fa-shopping-bag text-primary"></i> Afrobeads</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Shop</a>
                    <div class="dropdown-menu dropdown-menu-lg">
                        <div class="row">
                            <div class="col-6">
                                <h6 class="dropdown-header">Categories</h6>
                                <a class="dropdown-item" href="#">Electronics</a>
                                <a class="dropdown-item" href="#">Fashion</a>
                                <a class="dropdown-item" href="#">Home & Living</a>
                            </div>
                            <div class="col-6">
                                <h6 class="dropdown-header">Special Offers</h6>
                                <a class="dropdown-item" href="#">Deals of the Day</a>
                                <a class="dropdown-item" href="#">Clearance Sale</a>
                                <a class="dropdown-item" href="#">Bundle & Save</a>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="nav-item"><a class="nav-link" href="#">Deals</a></li>
                <li class="nav-item"><a class="nav-link" href="#">New Arrivals</a></li>
            </ul>
            <form class="d-flex me-3 position-relative" role="search">
                <input class="form-control pe-5" type="search" placeholder="Search products..." aria-label="Search products">
                <button class="btn position-absolute end-0 top-50 translate-middle-y" type="submit" aria-label="Search">
                    <i class="fas fa-search"></i>
                </button>
            </form>
            <div class="d-flex gap-2">
                <a href="#" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#loginModal" aria-label="Login">
                    <i class="fas fa-user"></i>
                </a>
                <a href="#" class="btn btn-outline-primary" aria-label="Wishlist">
                    <i class="fas fa-heart"></i>
                    <span class="badge bg-danger">2</span>
                </a>
                <a href="#" class="btn btn-primary position-relative" aria-label="Cart">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3</span>
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- resources/views/partials/topbar.blade.php -->
<div class="bg-dark text-light py-2">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <small>Free shipping on orders over $50 | Same-day delivery available</small>
            </div>
            <div class="col-md-6 text-end">
                <!-- Language Dropdown -->
                <div class="dropdown d-inline-block">
                    <button class="btn btn-dark btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-globe"></i> EN
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">English</a></li>
                        <li><a class="dropdown-item" href="#">Español</a></li>
                        <li><a class="dropdown-item" href="#">Français</a></li>
                    </ul>
                </div>
                <!-- Currency Dropdown -->
                <div class="dropdown d-inline-block ms-2">
                    <button class="btn btn-dark btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-dollar-sign"></i> USD
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">USD</a></li>
                        <li><a class="dropdown-item" href="#">EUR</a></li>
                        <li><a class="dropdown-item" href="#">GBP</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

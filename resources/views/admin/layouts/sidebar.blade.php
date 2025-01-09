<aside id="sidebar" class="sidebar bg-light text-dark">
    <div class="d-flex flex-column h-100">
        <!-- Sidebar Header -->
        <div class="sidebar-header p-3 border-bottom border-secondary">
            <div class="d-flex align-items-center">
                <i class="bi bi-boxes fs-4 me-2"></i>
                <h5 class="mb-0">Admin Panel</h5>
            </div>
        </div>

        <!-- Sidebar Content -->
        <div class="sidebar-content p-3 flex-grow-1 overflow-auto">
            <ul class="sidebar-nav list-unstyled">
                <!-- Dashboard -->
                <li class="nav-item mb-3">
                    <a class="nav-link active d-flex align-items-center rounded p-2 text-dark hover-dark" href="{{ route('dashboard') }}">
                        <i class="bi bi-grid me-2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- Management Section -->
                <li class="nav-section mb-2">
                    <small class="text-uppercase fw-bold text-muted px-2">Management</small>
                </li>

                <!-- Users -->
                <li class="nav-item mb-2">
                    <a class="nav-link collapsed d-flex align-items-center justify-content-between rounded p-2 text-dark hover-dark"
                       data-bs-target="#user-nav" data-bs-toggle="collapse" href="#">
                        <div>
                            <i class="bi bi-people me-2"></i>
                            <span>Users</span>
                        </div>
                        <i class="bi bi-chevron-down small"></i>
                    </a>
                    <ul id="user-nav" class="nav-content collapse list-unstyled ms-3 mt-2">
                        <li class="mb-2">
                            <a class="nav-link-sub d-flex align-items-center rounded-pill px-3 py-2 text-dark hover-dark" href="{{ route('users.index') }}">
                                <i class="bi bi-dot me-1"></i>
                                <span>View All</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link-sub d-flex align-items-center rounded-pill px-3 py-2 text-dark hover-dark" href="{{ route('users.index') }}">
                                <i class="bi bi-plus-lg me-1"></i>
                                <span>Add New</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Customers -->
                <li class="nav-item mb-2">
                    <a class="nav-link collapsed d-flex align-items-center justify-content-between rounded p-2 text-dark hover-dark"
                       data-bs-target="#customer-nav" data-bs-toggle="collapse" href="#">
                        <div>
                            <i class="bi bi-person-vcard me-2"></i>
                            <span>Customers</span>
                        </div>
                        <i class="bi bi-chevron-down small"></i>
                    </a>
                    <ul id="customer-nav" class="nav-content collapse list-unstyled ms-3 mt-2">
                        <li class="mb-2">
                            <a class="nav-link-sub d-flex align-items-center rounded-pill px-3 py-2 text-dark hover-dark" href="{{ route('customers.index') }}">
                                <i class="bi bi-dot me-1"></i>
                                <span>View All</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link-sub d-flex align-items-center rounded-pill px-3 py-2 text-dark hover-dark" href="{{ route('customers.index') }}">
                                <i class="bi bi-plus-lg me-1"></i>
                                <span>Add New</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Operations Section -->
                <li class="nav-section mb-2">
                    <small class="text-uppercase fw-bold text-muted px-2">Operations</small>
                </li>

                <!-- Products -->
                <li class="nav-item mb-2">
                    <a class="nav-link collapsed d-flex align-items-center justify-content-between rounded p-2 text-dark hover-dark"
                       data-bs-target="#product-nav" data-bs-toggle="collapse" href="#">
                        <div>
                            <i class="bi bi-box-seam me-2"></i>
                            <span>Products</span>
                        </div>
                        <i class="bi bi-chevron-down small"></i>
                    </a>
                    <ul id="product-nav" class="nav-content collapse list-unstyled ms-3 mt-2">
                        <li class="mb-2">
                            <a class="nav-link-sub d-flex align-items-center rounded-pill px-3 py-2 text-dark hover-dark" href="{{ route('products.index') }}">
                                <i class="bi bi-dot me-1"></i>
                                <span>View All</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link-sub d-flex align-items-center rounded-pill px-3 py-2 text-dark hover-dark" href="{{ route('products.index') }}">
                                <i class="bi bi-plus-lg me-1"></i>
                                <span>Add New</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Stock Management -->
                <li class="nav-item mb-2">
                    <a class="nav-link collapsed d-flex align-items-center justify-content-between rounded p-2 text-dark hover-dark"
                       data-bs-target="#stock-nav" data-bs-toggle="collapse" href="#">
                        <div>
                            <i class="bi bi-boxes me-2"></i>
                            <span>Stock Management</span>
                        </div>
                        <i class="bi bi-chevron-down small"></i>
                    </a>
                    <ul id="stock-nav" class="nav-content collapse list-unstyled ms-3 mt-2">
                        <li class="mb-2">
                            <a class="nav-link-sub d-flex align-items-center rounded-pill px-3 py-2 text-dark hover-dark" href="{{ route('stocks.index') }}">
                                <i class="bi bi-dot me-1"></i>
                                <span>Inventory</span>
                            </a>
                        </li>
                        <li class="mb-2">
                            <a class="nav-link-sub d-flex align-items-center rounded-pill px-3 py-2 text-dark hover-dark" href="{{ route('stocks.index') }}">
                                <i class="bi bi-exclamation-triangle me-1"></i>
                                <span>Low Stock Alerts</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link-sub d-flex align-items-center rounded-pill px-3 py-2 text-dark hover-dark" href="{{ route('stocks.index') }}">
                                <i class="bi bi-arrow-left-right me-1"></i>
                                <span>Stock Movement</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Sales -->
                <li class="nav-item mb-2">
                    <a class="nav-link collapsed d-flex align-items-center justify-content-between rounded p-2 text-dark hover-dark"
                       data-bs-target="#sale-nav" data-bs-toggle="collapse" href="#">
                        <div>
                            <i class="bi bi-cart3 me-2"></i>
                            <span>Sales</span>
                        </div>
                        <i class="bi bi-chevron-down small"></i>
                    </a>
                    <ul id="sale-nav" class="nav-content collapse list-unstyled ms-3 mt-2">
                        <li class="mb-2">
                            <a class="nav-link-sub d-flex align-items-center rounded-pill px-3 py-2 text-dark hover-dark" href="{{ route('sales.index') }}">
                                <i class="bi bi-dot me-1"></i>
                                <span>View All</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link-sub d-flex align-items-center rounded-pill px-3 py-2 text-dark hover-dark" href="{{ route('sales.index') }}">
                                <i class="bi bi-plus-lg me-1"></i>
                                <span>Add New</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Finance Section -->
                <li class="nav-section mb-2">
                    <small class="text-uppercase fw-bold text-muted px-2">Finance</small>
                </li>

                <!-- Expenses -->
                <li class="nav-item mb-2">
                    <a class="nav-link collapsed d-flex align-items-center justify-content-between rounded p-2 text-dark hover-dark"
                       data-bs-target="#expense-nav" data-bs-toggle="collapse" href="#">
                        <div>
                            <i class="bi bi-receipt me-2"></i>
                            <span>Expenses</span>
                        </div>
                        <i class="bi bi-chevron-down small"></i>
                    </a>
                    <ul id="expense-nav" class="nav-content collapse list-unstyled ms-3 mt-2">
                        <li class="mb-2">
                            <a class="nav-link-sub d-flex align-items-center rounded-pill px-3 py-2 text-dark hover-dark" href="{{ route('expenses.index') }}">
                                <i class="bi bi-dot me-1"></i>
                                <span>View All</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link-sub d-flex align-items-center rounded-pill px-3 py-2 text-dark hover-dark" href="{{ route('expenses.index') }}">
                                <i class="bi bi-plus-lg me-1"></i>
                                <span>Add New</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- System Section -->
                <li class="nav-section mb-2">
                    <small class="text-uppercase fw-bold text-muted px-2">System</small>
                </li>

                <!-- Licenses -->
                <li class="nav-item mb-2">
                    <a class="nav-link collapsed d-flex align-items-center justify-content-between rounded p-2 text-dark hover-dark"
                       data-bs-target="#license-nav" data-bs-toggle="collapse" href="#">
                        <div>
                            <i class="bi bi-key me-2"></i>
                            <span>Licenses</span>
                        </div>
                        <i class="bi bi-chevron-down small"></i>
                    </a>
                    <ul id="license-nav" class="nav-content collapse list-unstyled ms-3 mt-2">
                        <li class="mb-2">
                            <a class="nav-link-sub d-flex align-items-center rounded-pill px-3 py-2 text-dark hover-dark" href="">
                                <i class="bi bi-dot me-1"></i>
                                <span>View All</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link-sub d-flex align-items-center rounded-pill px-3 py-2 text-dark hover-dark" href="">
                                <i class="bi bi-plus-lg me-1"></i>
                                <span>Add New</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Settings -->
                <li class="nav-item mb-2">
                    <a class="nav-link d-flex align-items-center rounded p-2 text-dark hover-dark" href="">
                        <i class="bi bi-gear me-2"></i>
                        <span>Settings</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Sidebar Footer -->
        <div class="sidebar-footer p-3 border-top border-secondary mt-auto">
            <a class="nav-link text-danger d-flex align-items-center rounded p-2" href=""
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-right me-2"></i>
                <span>Logout</span>
            </a>
            <form id="logout-form" action="" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </div>
</aside>

<style>
.sidebar {
    width: 280px;
    height: 100vh;
    position: fixed;
    left: 0;
    top: 0;
    z-index: 1000;
    transition: all 0.3s ease;
}

.hover-dark:hover {
    color: #000 !important;
    background: rgba(0, 0, 0, 0.1);
}

.nav-link, .nav-link-sub {
    transition: all 0.2s ease;
    text-decoration: none;
}

.nav-link:hover, .nav-link.active,
.nav-link-sub:hover, .nav-link-sub.active {
    color: #000 !important;
    background: rgba(0, 0, 0, 0.1);
}

.nav-section {
    margin-top: 1.5rem;
}

.sidebar-content {
    scrollbar-width: thin;
    scrollbar-color: rgba(0, 0, 0, 0.2) transparent;
}

.sidebar-content::-webkit-scrollbar {
    width: 6px;
}

.sidebar-content::-webkit-scrollbar-thumb {
    background-color: rgba(0, 0, 0, 0.2);
    border-radius: 3px;
}

@media (max-width: 768px) {
    .sidebar {
        transform: translateX(-100%);
    }

    .sidebar.show {
        transform: translateX(0);
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add active class to current page link
    const currentPath = window.location.pathname;
    document.querySelectorAll('.nav-link, .nav-link-sub').forEach(link => {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');
            // Expand parent collapse if exists
            const parentCollapse = link.closest('.collapse');
            if (parentCollapse) {
                parentCollapse.classList.add('show');
            }
        }
    });

    // Handle collapse animation
    const collapseElements = document.querySelectorAll('[data-bs-toggle="collapse"]');
    collapseElements.forEach(el => {
        el.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('data-bs-target'));
            if (target) {
                target.classList.toggle('show');
                this.querySelector('.bi-chevron-down').style.transform =
                    target.classList.contains('show') ? 'rotate(180deg)' : 'rotate(0)';
            }
        });
    });
});
</script>

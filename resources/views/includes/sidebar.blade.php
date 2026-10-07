<div class="app-sidebar-menu">
    <div class="h-100" data-simplebar>

        <!--- Sidemenu -->
        <div id="sidebar-menu">

            <div class="logo-box pt-3">
                <!-- <a href="/" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ asset("assets/images/wtech/wtechlogo.png") }}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset("assets/images/wtech/wtechlogo.png") }}" alt="" height="24">
                    </span>
                </a> -->
                <!-- <a href="/" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ asset("assets/images/wtech/wtechlogo.png") }}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset("assets/images/wtech/wtechlogo.png") }}" alt="" height="24">
                    </span>
                </a> -->
				<p style="font-size: 20px; font-weight: 800;">
											Dvick Bar Grill 
                                        </p>
            </div>

            <ul id="side-menu">

                <li class="menu-title">Menu</li>

                <li>
                    <a href="/">
                       <!-- <i data-feather="home"></i> -->
                        <i class="fa-solid fa-chart-line"></i>
                        <span> Dashboard </span>
                    </a>
                </li>

                <li class="menu-title">Pages</li>
                @role(['super-admin','admin'])
                <li>
                    <a href="#brands" data-bs-toggle="collapse">
                        <i class="fa-solid fa-trademark"></i>
                        <span> Brands </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="brands">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{ route('brands') }}" class="tp-link">All Brands</a>
                            </li>
                            <li>
                                <a href="{{ route('brand.create') }}" class="tp-link">Create Brand</a>
                            </li>
                            <li>
                                <a href="{{ route('brands.analysis') }}" class="tp-link">Brand Analysis</a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endrole

                @role(['super-admin','admin','store-manager'])
                <li>
                    <a href="#product" data-bs-toggle="collapse">
                        <i class="fa-solid fa-box"></i>
                        <span> Products </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="product">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{ route('products') }}" class="tp-link">All Products</a>
                            </li>
                            <li>
                                <a href="{{ route('product.create') }}" class="tp-link">Create Product</a>
                            </li>
                            <li>
                                <a href="{{ route('products.analysis') }}" class="tp-link">Product Analysis</a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endrole

                @role(['super-admin','admin'])
                <li>
                    <a href="#productcat" data-bs-toggle="collapse">
                        <i class="fa-solid fa-sitemap"></i>
                        <span> Product Category </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="productcat">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{ route('categories') }}" class="tp-link">All Categories</a>
                            </li>
                            <li>
                                <a href="{{ route('category.create') }}" class="tp-link">Add Categories</a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endrole

                @role(['super-admin','admin','store-manager'])
                <li>
                    <a href="#suppliers" data-bs-toggle="collapse">
                        <i class="fa-solid fa-dolly"></i>
                        <span> Suppliers </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="suppliers">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{ route('suppliers.index') }}" class="tp-link">All Suppliers</a>
                            </li>
                            <li>
                                <a href="{{ route('supplies.index') }}" class="tp-link">All Supplies</a>
                            </li>
                            <li>
                                <a href="{{ route('supply.analysis') }}" class="tp-link">Supply Analysis</a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endrole

                @role(['super-admin','admin','cashier'])
                <li>
                    <a href="#sale" data-bs-toggle="collapse">
                        <i class="fa-solid fa-cash-register"></i>
                        <span> Sale </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sale">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{ route('sales.index') }}" class="tp-link">All Sale</a>
                            </li>
                            <li>
                                <a href="{{ route('pos.index') }}" class="tp-link">Point of Sale</a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endrole

                @role(['super-admin','admin'])
                <li>
                    <a href="#user" data-bs-toggle="collapse">
                        <i class="fa-solid fa-users"></i>
                        <span> Users </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="user">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{ route('users.index') }}" class="tp-link">All Users</a>
                            </li>
                            <li>
                                <a href="{{ route('users.create') }}" class="tp-link">Create User</a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endrole


            </ul>

        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
</div>

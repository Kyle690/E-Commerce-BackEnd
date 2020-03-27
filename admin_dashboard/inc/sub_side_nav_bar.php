<?php
/**
 * Created by PhpStorm.
 * User: kyle
 * Date: 2018/04/27
 * Time: 11:22 AM
 */?>
<div class="left-side-bar">
    <div class="">
        <a href="admin_home.php.php">
            <img src="../../img/Vanita_Logo.png" alt="logo" width="150" height="150" style="align-content: center; padding-left: 10%">
        </a>
    </div>
    <div class="menu-block customscroll">
        <div class="sidebar-menu">
            <ul id="accordion-menu">
                <!-- Home-->
                <li class="dropdown">
                    <a href="../admin_home.php" class="dropdown-toggle no-arrow">
                        <span class="fa fa-home"></span>Home
                    </a>
                </li>
                <!-- Catalog-->
                <li class="dropdown">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="fa fa-tags"></span>Catalog
                    </a>
                    <ul class="submenu">
                        <li><a href="../catalog/categories.php">Categories</a></li>
                        <li><a href="../catalog/products.php">Products</a></li>
                        <li><a href="../catalog/inventory.php">Inventory</a></li>
                    </ul>
                </li>
                <!-- Orders -->
                <li class="dropdown">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="fa fa-file-text-o"></span><span class="mtext">Orders</span>
                    </a>
                    <ul class="submenu">
                        <li><a href="../orders/all_orders.php">All Orders</a></li>
                        <li><a href="../orders/create_order.php">Create order</a></li>
                    </ul>
                </li>
                <!-- Customers -->
                <li class="dropdown">
                    <a href="../customers/customers.php"  class="dropdown-toggle no-arrow">
                        <span class="fa fa-users"></span><span class="mtext">Customers</span>
                    </a>

                </li>
                <!-- Analytics -->
                <li class="dropdown">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="fa fa-bar-chart-o"></span><span class="mtext">Analytics</span>
                    </a>
                    <ul class="submenu">
                        <li><a href="../analytics/reports.php">Reports</a></li>

                    </ul>
                </li>
                <!-- design -->
                <li class="dropdown">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="fa fa-desktop"></span><span class="mtext">Design</span>
                    </a>
                    <ul class="submenu">

                        <li><a href="../design/feature_products.php">Feature Products</a></li>
                    </ul>
                </li>
                <!-- Settings -->
                <li class="dropdown">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="fa fa-gear"></span><span class="mtext">Settings</span>
                    </a>
                    <ul class="submenu">
                        <li><a href="../settings/stroe_details.php">Details</a></li>
                        <li><a href="../settings/payment_gateway_setup.php">Payment Gateway</a></li>
                        <li><a href="../settings/shipping.php">Shipping</a></li>
                        <li><a href="../settings/social_accounts.php">Social Accounts</a></li>
                        <li><a href="../settings/gallery.php">Gallery</a></li>
                        <li><a href="../settings/new_admin.php">Add Admin User</a></li>

                    </ul>
                </li>



            </ul>
        </div>

    </div>
</div>

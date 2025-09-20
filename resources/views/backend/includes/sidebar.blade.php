<nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar">
        <a class="sidebar-brand" href="{{ route('index') }}">
            <span class="align-middle"><img src="{{ asset(config('settings.company_secondary_logo')) }}"
                    alt="Mamurjor Logo" width="105" height="25"></span>
        </a>

        <ul class="sidebar-nav">
            <li class="sidebar-header">
                {{ __f('Pages Title') }}
            </li>
            @if ((Auth::check() && Auth::user()->role_id == 1) || (Auth::check() && Auth::user()->role_id == 2))
                <li class="sidebar-item {{ $activeDashboard ?? '' }}">
                    <a class="sidebar-link" href="{{ route('admin.dashboard.index') }}">
                        <i class="align-middle" data-feather="grid"></i> <span
                            class="align-middle">{{ __f('Dashboard Title') }}</span>
                    </a>
                </li>
                <li class="sidebar-item {{ $activeAccountsMenu ?? '' }}">
                    <a data-bs-target="#accounts" data-bs-toggle="collapse" class="sidebar-link" aria-expanded="false">
                        <i class="align-middle" data-feather="dollar-sign"></i><span>{{ __f('Accounts Title') }}</span>
                    </a>
                    <ul id="accounts" class="sidebar-dropdown list-unstyled collapse {{ $showAccountsMenu ?? '' }}"
                        data-bs-parent="#sidebar">
                        <li class="sidebar-item {{ $activeAccounting ?? '' }}"><a class="sidebar-link"
                                href="{{ route('admin.account.index') }}">{{ __f('Accounts Title') }} </a></li>
                        <li class="sidebar-item {{ $activeAdminCashBookMenu ?? '' }}"><a class="sidebar-link"
                                href="{{ route('admin.cashbook.index') }}">{{ __f('Cash Book Title') }}</a></li>

                    </ul>
                </li>
            @elseif(Auth::check() && Auth::user()->role_id == 3)
                <li class="sidebar-item {{ $activeStaffDashboard ?? '' }}">
                    <a class="sidebar-link" href="{{ route('staff.dashboard.index') }}">
                        <i class="align-middle" data-feather="grid"></i> <span
                            class="align-middle">{{ __f('Dashboard Title') }}</span>
                    </a>
                </li>
                <li class="sidebar-item {{ $activeAccountsMenu ?? '' }}">
                    <a data-bs-target="#accounts" data-bs-toggle="collapse" class="sidebar-link" aria-expanded="false">
                        <i class="align-middle" data-feather="dollar-sign"></i><span>{{ __f('Accounts Title') }}</span>
                    </a>
                    <ul id="accounts" class="sidebar-dropdown list-unstyled collapse {{ $showAccountsMenu ?? '' }}"
                        data-bs-parent="#sidebar">
                        <li class="sidebar-item {{ $activeCashBookMenu ?? '' }}"><a class="sidebar-link"
                                href="{{ route('staff.cashbook.index') }}">{{ __f('Cash Book Title') }}</a></li>

                    </ul>
                </li>
            @endif

            <li class="sidebar-item {{ $activeProductMenu ?? '' }}">
                <a data-bs-target="#dashboards" data-bs-toggle="collapse" class="sidebar-link" aria-expanded="false">
                    <i class="align-middle" data-feather="list"></i><span>{{ __f('Products Title') }}</span>
                </a>

                <ul id="dashboards" class="sidebar-dropdown list-unstyled collapse {{ $showProductMenu ?? '' }}"
                    data-bs-parent="#sidebar">
                    @if ((Auth::check() && Auth::user()->role_id == 1) || (Auth::check() && Auth::user()->role_id == 2))
                        {{-- <li class="sidebar-item {{ $activeCreateBrandMenu ?? '' }}"><a class="sidebar-link"
                                href="{{ route('admin.brand.create') }}">{{ __f('Add Brand Title') }}</a></li>
                        <li class="sidebar-item {{ $activeBrandMenu ?? '' }}"><a class="sidebar-link"
                                href="{{ route('admin.brand.index') }}">{{ __f('Brand List Title') }} </a></li> --}}
                        {{-- <li class="sidebar-item {{ $activeAttributeMenu ?? '' }}"><a class="sidebar-link"
                                href="{{ route('admin.attribute.index') }}">Attribute List</a></li> --}}
                        <li class="sidebar-item {{ $activeCategoryCreateMenu ?? '' }}"><a class="sidebar-link"
                                href="{{ route('admin.category.create') }}">{{ __f('Add Category Title') }} </a></li>
                        <li class="sidebar-item {{ $activeCategoryMenu ?? '' }}"><a class="sidebar-link"
                                href="{{ route('admin.category.index') }}">{{ __f('Category List Title') }}</a></li>
                        <li class="sidebar-item {{ $showProductCreateMenu ?? '' }}">
                            <a class="sidebar-link" href="{{ route('admin.product.create') }}">
                                Add Food Create
                            </a>
                        </li>
                        <li class="sidebar-item {{ $activeProductListMenu ?? '' }}">
                            <a class="sidebar-link" href="{{ route('admin.product.index') }}">
                                Food Item List
                            </a>
                        </li>

                        <li class="sidebar-item {{ $activeReveiwMenu ?? '' }}"><a class="sidebar-link"
                                href="{{ route('admin.review.index') }}">{{ __f('Product Review Title') }}</a></li>
                    @elseif(Auth::check() && Auth::user()->role_id == 3)
                        <li class="sidebar-item {{ $activeBrandCreateMenu ?? '' }}"><a class="sidebar-link"
                                href="{{ route('staff.brand.create') }}">{{ __f('Add Brand Title') }} </a></li>
                        <li class="sidebar-item {{ $activeBrandMenu ?? '' }}"><a class="sidebar-link"
                                href="{{ route('staff.brand.index') }}">{{ __f('Brand List Title') }} </a></li>
                        <li class="sidebar-item {{ $activeCategoryCreateMenu ?? '' }}"><a class="sidebar-link"
                                href="{{ route('staff.category.create') }}">{{ __f('Add Category Title') }}</a></li>
                        <li class="sidebar-item {{ $activeCategoryMenu ?? '' }}"><a class="sidebar-link"
                                href="{{ route('staff.category.index') }}">{{ __f('Category List Title') }}</a></li>
                        <li class="sidebar-item {{ $activeProductCreateMenu ?? '' }}"><a class="sidebar-link"
                                href="{{ route('staff.product.create') }}">{{ __f('Add Product Title') }} </a></li>
                        <li class="sidebar-item {{ $activeProductListMenu ?? '' }}"><a class="sidebar-link"
                                href="{{ route('staff.product.index') }}">{{ __f('Product List Title') }} </a></li>
                    @endif
                </ul>
            </li>
            @if ((Auth::check() && Auth::user()->role_id == 1) || (Auth::check() && Auth::user()->role_id == 2))
                <li class="sidebar-item {{ $activeParentOrderMenu ?? '' }}">
                    <a data-bs-target="#ordersMenu" data-bs-toggle="collapse" class="sidebar-link"
                        aria-expanded="false">
                        <i class="align-middle"
                            data-feather="shopping-bag"></i><span>{{ __f('Orders Title') }}</span>
                    </a>
                    <ul id="ordersMenu" class="sidebar-dropdown list-unstyled collapse {{ $showOrderMenu ?? '' }}"
                        data-bs-parent="#sidebar">
                        <li class="sidebar-item {{ $activeOrderMenu ?? '' }}">
                            <a class="sidebar-link"
                                href="{{ route('admin.order.index') }}">{{ __f('Order List Title') }}</a>
                        </li>
                        <li class="sidebar-item {{ $activeFraudMenu ?? '' }}">
                            <a class="sidebar-link"
                                href="{{ route('admin.fraudchecker.index') }}">{{ __f('Fraud Checker Title') }}</a>
                        </li>
                    </ul>
                </li>
            @endif
            @if ((Auth::check() && Auth::user()->role_id == 1) || (Auth::check() && Auth::user()->role_id == 2))
                <li class="sidebar-item {{ $activeParentCustomersMenu ?? '' }}">
                    <a data-bs-target="#managecustomers" data-bs-toggle="collapse" class="sidebar-link"
                        aria-expanded="false">
                        <i class="align-middle" data-feather="users"></i><span>
                            {{ __f('Supplier Customers Title Title') }} </span>
                    </a>
                    <ul id="managecustomers"
                        class="sidebar-dropdown list-unstyled collapse {{ $showCustomersMenu ?? '' }}"
                        data-bs-parent="#sidebar">
                        {{-- <li class="sidebar-item {{ $activeCustomersMenu ?? '' }}">
                            <a class="sidebar-link" href="{{ route('admin.managecustomers.index') }}">Order Customers List</a>
                        </li> --}}
                        <li class="sidebar-item {{ $activeSupplierCreateMenu ?? '' }}">
                            <a class="sidebar-link"
                                href="{{ route('admin.supplier.create') }}">{{ __f('Add Supplier Title') }} </a>
                        </li>
                        <li class="sidebar-item {{ $activeSupplierMenu ?? '' }}">
                            <a class="sidebar-link"
                                href="{{ route('admin.supplier.index') }}">{{ __f('Supplier List Title') }}</a>
                        </li>
                        <li class="sidebar-item {{ $activeInvoiceCustomerCreateMenu ?? '' }}">
                            <a class="sidebar-link"
                                href="{{ route('admin.customer.create') }}">{{ __f('Add Customer Title') }}</a>
                        </li>
                        <li class="sidebar-item {{ $activeInvoiceCustomersMenu ?? '' }}">
                            <a class="sidebar-link" href="{{ route('admin.customer.index') }}">
                                {{ __f('Customer List Title') }}</a>
                        </li>

                    </ul>
                </li>
            @elseif(Auth::check() && Auth::user()->role_id == 3)
                <li class="sidebar-item {{ $activeParentCustomersMenu ?? '' }}">
                    <a data-bs-target="#managecustomers" data-bs-toggle="collapse" class="sidebar-link"
                        aria-expanded="false">
                        <i class="align-middle"
                            data-feather="users"></i><span>{{ __f('Supplier Customers Title Title') }} </span>
                    </a>
                    <ul id="managecustomers"
                        class="sidebar-dropdown list-unstyled collapse {{ $showCustomersMenu ?? '' }}"
                        data-bs-parent="#sidebar">
                        <li class="sidebar-item {{ $activeSupplierCreateMenu ?? '' }}">
                            <a class="sidebar-link"
                                href="{{ route('staff.supplier.create') }}">{{ __f('Add Supplier Title') }} </a>
                        </li>
                        <li class="sidebar-item {{ $activeSupplierMenu ?? '' }}">
                            <a class="sidebar-link"
                                href="{{ route('staff.supplier.index') }}">{{ __f('Supplier List Title') }}</a>
                        </li>

                        <li class="sidebar-item {{ $activeInvoiceCustomerCreateMenu ?? '' }}">
                            <a class="sidebar-link"
                                href="{{ route('staff.customer.create') }}">{{ __f('Add Customer Title') }}</a>
                        </li>
                        <li class="sidebar-item {{ $activeInvoiceCustomersMenu ?? '' }}">
                            <a class="sidebar-link"
                                href="{{ route('staff.customer.index') }}">{{ __f('Customers List Title') }} </a>
                        </li>

                    </ul>
                </li>
            @endif

            {{-- @if ((Auth::check() && Auth::user()->role_id == 1) || (Auth::check() && Auth::user()->role_id == 2))
             <li class="sidebar-header">
                {{ __f('Coupon Settings Title') }}
            </li>
            <li class="sidebar-item {{ $activeCouponMenu ?? '' }}">
                <a data-bs-target="#coupons" data-bs-toggle="collapse" class="sidebar-link" aria-expanded="false">
                    <i class="align-middle" data-feather="percent"></i><span>{{ __f('Coupon Title') }}</span>
                </a>

                <ul id="coupons" class="sidebar-dropdown list-unstyled collapse {{ $showCouponMenu ?? '' }}"
                    data-bs-parent="#sidebar">
                        <li class="sidebar-item {{ $activeCreateCouponMenu ?? '' }}"><a class="sidebar-link"
                                href="{{ route('admin.coupon.create') }}">{{ __f('Add Coupon Title') }}</a></li>
                        <li class="sidebar-item {{ $activeCouponListMenu ?? '' }}"><a class="sidebar-link"
                                href="{{ route('admin.coupon.index') }}">{{ __f('Coupon List Title') }} </a></li>
                </ul>
            </li>
            @endif --}}

            @if ((Auth::check() && Auth::user()->role_id == 1) || (Auth::check() && Auth::user()->role_id == 2))
                <li class="sidebar-header">
                    {{ __f('Mail Settings Title') }}
                </li>
                <li class="sidebar-item {{ $activeMailMenu ?? '' }}">
                    <a data-bs-target="#mailsettings" data-bs-toggle="collapse" class="sidebar-link"
                        aria-expanded="false">
                        <i class="align-middle" data-feather="mail"></i><span>{{ __f('Mail Title') }}</span>
                    </a>

                    <ul id="mailsettings" class="sidebar-dropdown list-unstyled collapse {{ $showMailMenu ?? '' }}"
                        data-bs-parent="#sidebar">
                        <li class="sidebar-item {{ $activeCreateMailMenu ?? '' }}"><a class="sidebar-link"
                                href="{{ route('admin.dashboard.mail.update') }}">{{ __f('Mail Title') }}</a></li>
                    </ul>
                </li>
            @endif

            <li class="sidebar-item {{ $activeParentSalesMenu ?? '' }}">
                <a data-bs-target="#salesMenu" data-bs-toggle="collapse" class="sidebar-link" aria-expanded="false">
                    <i class="align-middle" data-feather="truck"></i><span>{{ __f('Sales Title') }}</span>
                </a>
                <ul id="salesMenu" class="sidebar-dropdown list-unstyled collapse {{ $showSalesMenu ?? '' }}"
                    data-bs-parent="#sidebar">
                    @if ((Auth::check() && Auth::user()->role_id == 1) || (Auth::check() && Auth::user()->role_id == 2))
                        <li class="sidebar-item {{ $activeSalesCreateMenu ?? '' }}">
                            <a class="sidebar-link"
                                href="{{ route('admin.salesinvoice.create') }}">{{ __f('Add Sales Invoice Title') }}</a>
                        </li>
                        <li class="sidebar-item {{ $activeSalesMenu ?? '' }}">
                            <a class="sidebar-link"
                                href="{{ route('admin.salesinvoice.index') }}">{{ __f('Sales Invoice List Title') }}</a>
                        </li>
                    @elseif(Auth::check() && Auth::user()->role_id == 3)
                        <li class="sidebar-item {{ $activeSalessCreateMenu ?? '' }}">
                            <a class="sidebar-link"
                                href="{{ route('staff.salesinvoice.create') }}">{{ __f('Add Sales Invoice Title') }}</a>
                        </li>
                        <li class="sidebar-item {{ $activeSalesMenu ?? '' }}">
                            <a class="sidebar-link"
                                href="{{ route('staff.salesinvoice.index') }}">{{ __f('Sales Invoice List Title') }}</a>
                        </li>
                    @endif
                </ul>
            </li>

            {{-- <li class="sidebar-item {{ $activeParentReturnMenu ?? '' }}">
                <a data-bs-target="#returnMenu" data-bs-toggle="collapse" class="sidebar-link" aria-expanded="false">
                    <i class="align-middle" data-feather="user-x"></i><span>{{ __f('Return Title') }}</span>
                </a>
                <ul id="returnMenu" class="sidebar-dropdown list-unstyled collapse {{ $showReturnMenu ?? '' }}"
                    data-bs-parent="#sidebar">
                    @if ((Auth::check() && Auth::user()->role_id == 1) || (Auth::check() && Auth::user()->role_id == 2))
                        <li class="sidebar-item {{ $activeReturnCreateMenu ?? '' }}">
                            <a class="sidebar-link" href="{{ route('admin.returnproduct.create') }}">{{ __f('Add Return Product Title') }}</a>
                        </li>
                        <li class="sidebar-item {{ $activeReturnMenu ?? '' }}">
                            <a class="sidebar-link" href="{{ route('admin.returnproduct.index') }}">{{ __f('Return Product List Title') }}</a>
                        </li>
                        <li class="sidebar-item {{ $activeReturnPurchaseCreateMenu ?? '' }}">
                            <a class="sidebar-link" href="{{ route('admin.returnpurchase.create') }}">{{ __f('Add Return Purchase Product Title') }}</a>
                        </li>
                        <li class="sidebar-item {{ $activePurchaseReturnMenu ?? '' }}">
                            <a class="sidebar-link" href="{{ route('admin.returnpurchase.index') }}">{{ __f('Return Purchase Product List Title') }}</a>
                        </li>
                    @elseif(Auth::check() && Auth::user()->role_id == 3)
                        <li class="sidebar-item {{ $activeReturnsCreateMenu ?? '' }}">
                            <a class="sidebar-link" href="{{ route('staff.returnproduct.create') }}">{{ __f('Add Return Product Title') }} </a>
                        </li>
                        <li class="sidebar-item {{ $activeReturnMenu ?? '' }}">
                            <a class="sidebar-link" href="{{ route('staff.returnproduct.index') }}">{{ __f('Return Product List Title') }}</a>
                        </li>
                    @endif
                </ul>
            </li> --}}

            <li class="sidebar-item {{ $activeParentExpensesMenu ?? '' }}">
                <a data-bs-target="#expenseMenu" data-bs-toggle="collapse" class="sidebar-link"
                    aria-expanded="false">
                    <i class="align-middle" data-feather="file-text"></i><span>{{ __f('Expenses Title') }}</span>
                </a>

                <ul id="expenseMenu" class="sidebar-dropdown list-unstyled collapse {{ $showExpensesMenu ?? '' }}"
                    data-bs-parent="#sidebar">
                    @if ((Auth::check() && Auth::user()->role_id == 1) || (Auth::check() && Auth::user()->role_id == 2))
                        <li class="sidebar-item {{ $activeExpensesCreateMenu ?? '' }}">
                            <a class="sidebar-link"
                                href="{{ route('admin.expense.create') }}">{{ __f('Add Expense Category Title') }}</a>
                        </li>
                        <li class="sidebar-item {{ $activeExpensesMenu ?? '' }}">
                            <a class="sidebar-link"
                                href="{{ route('admin.expense.index') }}">{{ __f('Expense Category Title') }}</a>
                        </li>
                        <li class="sidebar-item {{ $activeExpensesListCreateMenu ?? '' }}">
                            <a class="sidebar-link"
                                href="{{ route('admin.expenselist.create') }}">{{ __f('Add Expense Title') }}</a>
                        </li>
                        <li class="sidebar-item {{ $activeExpensesListMenu ?? '' }}">
                            <a class="sidebar-link"
                                href="{{ route('admin.expenselist.index') }}">{{ __f('Expense List Title') }}</a>
                        </li>
                    @elseif(Auth::check() && Auth::user()->role_id == 3)
                        <li class="sidebar-item {{ $activeExpensesCreateMenu ?? '' }}">
                            <a class="sidebar-link"
                                href="{{ route('staff.expense.create') }}">{{ __f('Add Expense Category Title') }}</a>
                        </li>
                        <li class="sidebar-item {{ $activeExpensesMenu ?? '' }}">
                            <a class="sidebar-link"
                                href="{{ route('staff.expense.index') }}">{{ __f('Expense Category Title') }}</a>
                        </li>
                        <li class="sidebar-item {{ $activeExpensesListCreateMenu ?? '' }}">
                            <a class="sidebar-link"
                                href="{{ route('staff.expenselist.create') }}">{{ __f('Add Expense Title') }}</a>
                        </li>
                        <li class="sidebar-item {{ $activeExpensesListMenu ?? '' }}">
                            <a class="sidebar-link"
                                href="{{ route('staff.expenselist.index') }}">{{ __f('Expense List Title') }}</a>
                        </li>
                    @endif
                </ul>
            </li>

            <li class="sidebar-item {{ $activeParentPurchaseMenu ?? '' }}">
                <a data-bs-target="#purchaseMenu" data-bs-toggle="collapse" class="sidebar-link"
                    aria-expanded="false">
                    <i class="align-middle"
                        data-feather="shopping-cart"></i><span>{{ __f('Purchase Title') }}</span>
                </a>

                <ul id="purchaseMenu" class="sidebar-dropdown list-unstyled collapse {{ $showPurchaseMenu ?? '' }}"
                    data-bs-parent="#sidebar">
                    @if ((Auth::check() && Auth::user()->role_id == 1) || (Auth::check() && Auth::user()->role_id == 2))
                        <li class="sidebar-item {{ $activePurchaseCreateMenu ?? '' }}">
                            <a class="sidebar-link"
                                href="{{ route('admin.purchase.create') }}">{{ __f('Add Purchase Title') }}</a>
                        </li>
                        <li class="sidebar-item {{ $activePurchaseMenu ?? '' }}">
                            <a class="sidebar-link"
                                href="{{ route('admin.purchase.index') }}">{{ __f('Purchase List Title') }}</a>
                        </li>
                        <li class="sidebar-item {{ $activeStockPurchaseProductMenu ?? '' }}">
                            <a class="sidebar-link"
                                href="{{ route('admin.stock.product.list') }}">{{ __f('Stock Product List Title') }}</a>
                        </li>
                    @elseif(Auth::check() && Auth::user()->role_id == 3)
                        <li class="sidebar-item {{ $activePurchaseCreateMenu ?? '' }}">
                            <a class="sidebar-link"
                                href="{{ route('staff.purchase.create') }}">{{ __f('Add Local Purchase Title') }}</a>
                        </li>
                        <li class="sidebar-item {{ $activePurchaseMenu ?? '' }}">
                            <a class="sidebar-link"
                                href="{{ route('staff.purchase.index') }}">{{ __f('Local Purchase List Title') }}</a>
                        </li>
                    @endif
                </ul>
            </li>


            @if ((Auth::check() && Auth::user()->role_id == 1) || (Auth::check() && Auth::user()->role_id == 2))
                <li class="sidebar-header">
                    {{ __f('Theme Settings Title') }}
                </li>
                <li class="sidebar-item {{ $activeThemeMenu ?? '' }}">
                    <a data-bs-target="#themeSettingsMenu" data-bs-toggle="collapse" class="sidebar-link"
                        aria-expanded="false">
                        <i class="align-middle" data-feather="aperture"></i><span>{{ __f('Settings Title') }}</span>
                    </a>

                    <ul id="themeSettingsMenu"
                        class="sidebar-dropdown list-unstyled collapse {{ $showThemeMenu ?? '' }}"
                        data-bs-parent="#sidebar">
                        <li class="sidebar-item {{ $activeThemeSettingMenu ?? '' }}">
                            <a class="sidebar-link"
                                href="{{ route('admin.setting.index') }}">{{ __f('Theme Settings Title') }}</a>
                        </li>

                        <li class="sidebar-item {{ $activeTypographySettingMenu ?? '' }}">
                            <a class="sidebar-link"
                                href="{{ route('admin.setting.typography.index') }}">{{ __f('Typography Settings Title') }}</a>
                        </li>
                        {{-- <li class="sidebar-item {{ $activeCourierSettingMenu ?? '' }}">
                            <a class="sidebar-link" href="{{ route('admin.setting.courier.index') }}">
                                Courier Settings</a>
                        </li> --}}
                    </ul>
                </li>
                <li class="sidebar-item {{ $activeParentSliderMenu ?? '' }}">
                    <a data-bs-target="#homeSettingsMenu" data-bs-toggle="collapse" class="sidebar-link"
                        aria-expanded="false">
                        <i class="align-middle"
                            data-feather="settings"></i><span>{{ __f('Home Settings Title') }}</span>
                    </a>

                    <ul id="homeSettingsMenu"
                        class="sidebar-dropdown list-unstyled collapse {{ $showSliderMenu ?? '' }}"
                        data-bs-parent="#sidebar">
                        <li class="sidebar-item {{ $activeMenuCreateMenu ?? '' }}">
                            <a class="sidebar-link"
                                href="{{ route('admin.menu.create') }}">{{ __f('Add Menu Title') }}</a>
                        </li>
                        <li class="sidebar-item {{ $activeMenuMenu ?? '' }}">
                            <a class="sidebar-link"
                                href="{{ route('admin.menu.index') }}">{{ __f('Menu List Title') }}</a>
                        </li>
                        <li class="sidebar-item {{ $activeSliderCreateMenu ?? '' }}">
                            <a class="sidebar-link"
                                href="{{ route('admin.home.slider.create') }}">{{ __f('Add Slider Title') }}</a>
                        </li>
                        <li class="sidebar-item {{ $activeSliderMenu ?? '' }}">
                            <a class="sidebar-link"
                                href="{{ route('admin.home.slider') }}">{{ __f('Slider List Title') }}</a>
                        </li>
                        {{-- <li class="sidebar-item {{ $activeHeaderStyleMenu ?? '' }}">
                            <a class="sidebar-link" href="{{ route('admin.setting.header.section.style') }}">{{ __f('Header Style Setting Title') }}</a>
                        </li> --}}
                        <li class="sidebar-item {{ $activeSliderStyleMenu ?? '' }}">
                            <a class="sidebar-link"
                                href="{{ route('admin.setting.slider.section.style') }}">{{ __f('Slider Style Setting Title') }}</a>
                        </li>
                        <li class="sidebar-item {{ $activeCategoryStyleMenu ?? '' }}">
                            <a class="sidebar-link"
                                href="{{ route('admin.setting.category.section.style') }}">{{ __f('Category Style Setting Title') }}</a>
                        </li>
                        <li class="sidebar-item {{ $activeProductCardStyleMenu ?? '' }}">
                            <a class="sidebar-link"
                                href="{{ route('admin.setting.product.card.section.style') }}">{{ __f('Product Card Style Setting Title') }}</a>
                        </li>
                        <li class="sidebar-item {{ $activeBrandStyleMenu ?? '' }}">
                            <a class="sidebar-link"
                                href="{{ route('admin.setting.brand.section.style') }}">{{ __f('Brand Style Setting Title') }}</a>
                        </li>
                        <li class="sidebar-item {{ $activeSubcribeStyleMenu ?? '' }}">
                            <a class="sidebar-link"
                                href="{{ route('admin.setting.subcribe.section.style') }}">{{ __f('Subcribe Style Setting Title') }}</a>
                        </li>
                        <li class="sidebar-item {{ $activeFooterStyleMenu ?? '' }}">
                            <a class="sidebar-link"
                                href="{{ route('admin.setting.footer.section.style') }}">{{ __f('Footer Style Setting Title') }}</a>
                        </li>
                        <li class="sidebar-item {{ $activeBreadcrumbStyleMenu ?? '' }}">
                            <a class="sidebar-link"
                                href="{{ route('admin.setting.breadcrumb.section.style') }}">{{ __f('Breadcrumb Style Setting Title') }}</a>
                        </li>
                        <li class="sidebar-item {{ $activeProductDetailsPageStyleMenu ?? '' }}">
                            <a class="sidebar-link"
                                href="{{ route('admin.setting.product.details.section.style') }}">{{ __f('Product Details Page Style Setting Title') }}</a>
                        </li>
                        <li class="sidebar-item {{ $activeCategoryDetailsPageStyleMenu ?? '' }}">
                            <a class="sidebar-link"
                                href="{{ route('admin.setting.category.details.section.style') }}">{{ __f('Category Details Page Style Setting Title') }}</a>
                        </li>
                        <li class="sidebar-item {{ $activeLoginPageStyleMenu ?? '' }}">
                            <a class="sidebar-link"
                                href="{{ route('admin.setting.login.section.style') }}">{{ __f('Login And Register Page Style Setting Title') }}</a>
                        </li>
                        <li class="sidebar-item {{ $activeCheckOutPageStyleMenu ?? '' }}">
                            <a class="sidebar-link"
                                href="{{ route('admin.setting.check.out.section.style') }}">{{ __f('Check Out Page Style Setting Title') }}</a>
                        </li>
                        <li class="sidebar-item {{ $activeViewCartPageStyleMenu ?? '' }}">
                            <a class="sidebar-link"
                                href="{{ route('admin.setting.view.cart.section.style') }}">{{ __f('View Cart Page Style Setting Title') }}</a>
                        </li>
                        <li class="sidebar-item {{ $activeSectionShowHideStyleMenu ?? '' }}">
                            <a class="sidebar-link"
                                href="{{ route('admin.setting.section.show.hide.style') }}">{{ __f('Section Show Hide Setting Title') }}</a>
                        </li>
                        {{-- <li class="sidebar-item {{ $activeLandingPageMenu ?? '' }}">
                            <a class="sidebar-link" href="{{ route('admin.page.index') }}">Landing Page List</a>
                        </li> --}}
                        {{-- <li class="sidebar-item {{ $activeFeatureMenu ?? '' }}">
                            <a class="sidebar-link" href="{{ route('admin.feature.index') }}">Feature List</a>
                        </li> --}}
                    </ul>
                </li>
            @endif
            @if ((Auth::check() && Auth::user()->role_id == 1) || (Auth::check() && Auth::user()->role_id == 2))
                <li class="sidebar-header">
                    {{ __f('Language Settings Title') }}
                </li>
                <li class="sidebar-item {{ $activeLanguageMenu ?? '' }}">
                    <a data-bs-target="#languagesettings" data-bs-toggle="collapse" class="sidebar-link"
                        aria-expanded="false">
                        <i class="align-middle" data-feather="globe"></i><span>
                            {{ __f('Language Settings Title') }}</span>
                    </a>
                    <ul id="languagesettings"
                        class="sidebar-dropdown list-unstyled collapse {{ $showLanguageMenu ?? '' }}"
                        data-bs-parent="#sidebar">
                        <li class="sidebar-item {{ $activeLanguageCreateMenu ?? '' }}">
                            <a class="sidebar-link"
                                href="{{ route('admin.language.create') }}">{{ __f('Add Language Title') }}
                            </a>
                        </li>
                        <li class="sidebar-item {{ $activeLanguageListMenu ?? '' }}">
                            <a class="sidebar-link"
                                href="{{ route('admin.language.index') }}">{{ __f('Language List Title') }}</a>
                        </li>
                        <li class="sidebar-item {{ $activeWebsiteTranslateMenu ?? '' }}">
                            <a class="sidebar-link"
                                href="{{ route('admin.language.website.translate', ['lang' => app()->getLocale()]) }}">{{ __f('Website Translate Title') }}</a>
                        </li>
                    </ul>
                </li>
            @endif
            @if ((Auth::check() && Auth::user()->role_id == 1) || (Auth::check() && Auth::user()->role_id == 2))
                <li class="sidebar-header">
                    {{ __f('Database Settings Title') }}
                </li>
                <li class="sidebar-item {{ $activeDatabaseResetMenu ?? '' }}">
                    <a data-bs-target="#databaseReset" data-bs-toggle="collapse" class="sidebar-link"
                        aria-expanded="false">
                        <i class="align-middle"
                            data-feather="database"></i><span>{{ __f('Database Reset Title') }}</span>
                    </a>
                    <ul id="databaseReset"
                        class="sidebar-dropdown list-unstyled collapse {{ $showDatabaseResetMenu ?? '' }}"
                        data-bs-parent="#sidebar">
                        <li class="sidebar-item {{ $activeDatabaseListMenu ?? '' }}">
                            <a class="sidebar-link"
                                href="{{ route('admin.databasereset.index') }}">{{ __f('Database List Title') }}</a>
                        </li>
                    </ul>
                </li>
            @endif
    </div>
</nav>

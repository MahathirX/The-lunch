-@extends('layouts.frontend')
@section('title', $title)
@push('styles')
<link rel="stylesheet" href="{{ asset('backend/assets/css/image-style.css') }}">
    <style>
        #user-dashboard .user-dashboard-left-side, #user-dashboard .user-dashboard-right-side{
            background: white;
            box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
            border-radius: 4px;
            padding: 20px;
        }

        #user-dashboard  .user-top-section-wapper {
            width: 100%;
            overflow: hidden;
            display: flex;
            justify-content: center;
        }
        #user-dashboard  .user-top-section-wapper img{
            width: 150px;
            border-radius: 50%;
            border: 1px solid var( --main-primary-color);
            padding: 4px;
        }
        #user-dashboard .nav-pills .nav-link.active, .nav-pills .show > .nav-link {
            color: var( --main-primary-color);
            background-color: #ececec;
            border-radius: 0px !important;
            border-left: 4px solid var( --main-primary-color);
            text-align: start;
        }
        #user-dashboard .nav-link {
            color: var(--main-primary-black-color);
            text-align: start;
            font-weight: 400;
            border-left: 4px solid transparent
        }
        #user-dashboard #user-dashboard-product-image {
            width: 60px;
            border-radius: 50%
        }
        #user-dashboard .form-control {
            border-radius: 3px !important;
            padding: 8px 10px !important;
        }
        #user-dashboard .form-control:focus {
            box-shadow: unset !important;
        }
        #user-dashboard .text-danger {
            color: red !important;
        }
    </style>
@endpush
@section('content')
    <!-- User Dashboard -->
    <section id="user-dashboard">
        <div class="container">
            <div class="user-dashboard-wapper">
                <div class="row g-3 py-3">
                    <div class="col-md-3">
                        <div class="user-dashboard-left-side">
                            <div class="user-top-section pt-4">
                                <div class="user-top-section-wapper">
                                    @if(Auth::user()->avater != null)
                                      <img src="{{ asset(Auth::user()->avater) }}" alt="image">
                                    @else
                                        <img src="{{ asset('frontendtwo/asset/image/user-default.png') }}" alt="image">
                                    @endif
                                </div>
                                <div class="user-bottom-section-wapper text-center">
                                    <h5 class="mb-1 mt-3">{{ Auth::user()->fname ?? '' }} {{ Auth::user()->lname ?? '' }}</h5>
                                    <p>Joined {{ Auth::user()->created_at->format('d-m-Y') ?? '' }}</p>
                                </div>
                            </div>
                            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                <button class="nav-link active" id="v-pills-dashboard-tab" data-bs-toggle="pill" data-bs-target="#v-pills-dashboard" type="button" role="tab" aria-controls="v-pills-dashboard" aria-selected="true"><i class="fa-solid fa-house me-2 text-primary"></i>Dashboard</button>
                                <button class="nav-link" id="v-pills-my-orders-tab" data-bs-toggle="pill" data-bs-target="#v-pills-my-orders" type="button" role="tab" aria-controls="v-pills-my-orders" aria-selected="false"><i class="fa-solid fa-cart-shopping me-2 text-success"></i>My Orders</button>
                                <button class="nav-link" id="v-pills-order-details-tab" data-bs-toggle="pill" data-bs-target="#v-pills-order-details" type="button" role="tab" aria-controls="v-pills-order-details" aria-selected="false"><i class="fa-solid fa-bag-shopping me-2 text-info"></i>Order Details</button>
                                {{-- <button class="nav-link" id="v-pills-wishlist-tab" data-bs-toggle="pill" data-bs-target="#v-pills-wishlist" type="button" role="tab" aria-controls="v-pills-wishlist" aria-selected="false"><i class="fa-solid fa-heart-circle-plus me-2 text-danger"></i>Wishlist</button> --}}
                                <button class="nav-link" id="v-pills-shopping-cart-tab" data-bs-toggle="pill" data-bs-target="#v-pills-shopping-cart" type="button" role="tab" aria-controls="v-pills-shopping-cart" aria-selected="false"><i class="fa-solid fa-cart-shopping me-2 text-dark"></i>Shopping Cart</button>
                                <button class="nav-link" id="v-pills-my-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-my-profile" type="button" role="tab" aria-controls="v-pills-my-profile" aria-selected="false"><i class="fa-solid fa-user me-2 text-info"></i>My Profile</button>
                                <button class="nav-link" id="v-pills-change-password-tab" data-bs-toggle="pill" data-bs-target="#v-pills-change-password" type="button" role="tab" aria-controls="v-pills-change-password" aria-selected="false"><i class="fa-solid fa-key text-success me-2"></i>Change Password</button>
                                {{-- <button class="nav-link" id="v-pills-my-addresses-tab" data-bs-toggle="pill" data-bs-target="#v-pills-my-addresses" type="button" role="tab" aria-controls="v-pills-my-addresses" aria-selected="false"><i class="fa-solid fa-location-crosshairs me-2 text-success"></i>My Addresses</button> --}}
                                <button class="nav-link" id="v-pills-coupons-tab" data-bs-toggle="pill" data-bs-target="#v-pills-coupons" type="button" role="tab" aria-controls="v-pills-coupons" aria-selected="false"><i class="fa-solid fa-percent me-2 text-primary"></i>Coupons</button>
                                <button class="nav-link" id="v-pills-product-reviews-tab" data-bs-toggle="pill" data-bs-target="#v-pills-product-reviews" type="button" role="tab" aria-controls="v-pills-product-reviews" aria-selected="false"><i class="fa-solid fa-star me-2 text-warning"></i>Product Reviews</button>
                                {{-- <button class="nav-link" id="v-pills-notifications-tab" data-bs-toggle="pill" data-bs-target="#v-pills-notifications" type="button" role="tab" aria-controls="v-pills-notifications" aria-selected="false"><i class="fa-regular fa-bell me-2 text-info"></i>Notifications</button> --}}
                                {{-- <button class="nav-link" id="v-pills-support-tickets-tab" data-bs-toggle="pill" data-bs-target="#v-pills-support-tickets" type="button" role="tab" aria-controls="v-pills-support-tickets" aria-selected="false"><i class="fa-solid fa-ticket me-2 text-secondary"></i>Support Tickets</button> --}}
                                <button class="nav-link"  onclick="document.getElementById('logout-form').submit()"><i class="fa-solid fa-arrow-right-to-bracket me-2 text-danger"></i>Logout</button>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="user-dashboard-right-side">
                            <div class="tab-content" id="v-pills-tabContent">
                               <div class="tab-pane fade show active" id="v-pills-dashboard" role="tabpanel" aria-labelledby="v-pills-dashboard-tab">
                                    <div>
                                        <h3 class="border-bottom pb-1">My Dashboard</h3>
                                        <div class="row mt-3">
                                            <div class="col-12 col-md-3">
                                                <div class="card">
                                                    <div class="card-body text-center">
                                                        <span>Total Orders</span>
                                                        <h5 class="mt-2">{{ $orders ? $orders->count() : 0 }}</h5>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-3">
                                                <div class="card">
                                                    <div class="card-body text-center">
                                                        @php
                                                            $orderDetailsCount = 0;
                                                        @endphp
                                                        @forelse ($orders as $key => $order)
                                                            @forelse ($order->orderdetails as $details)
                                                                @php
                                                                    $orderDetailsCount++;
                                                                @endphp
                                                            @empty
                                                            @endforelse
                                                        @empty
                                                        @endforelse
                                                        <span>Total Order Products</span>
                                                        <h5 class="mt-2">{{ $orderDetailsCount ?? 0 }}</h5>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-3">
                                                <div class="card">
                                                    <div class="card-body text-center">
                                                       <span>Cart Product</span>
                                                       <h5 class="mt-2">{{ count(session('cart', [])) }}</h5>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-3">
                                                <div class="card">
                                                    <div class="card-body text-center">
                                                        <span>Use Coupons</span>
                                                        <h5 class="mt-2">{{ $coupons ? $coupons->count() : 0 }}</h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                               </div>
                                <div class="tab-pane fade" id="v-pills-my-orders" role="tabpanel" aria-labelledby="v-pills-my-orders-tab">
                                    <div>
                                        <h3 class="border-bottom pb-1">My Order</h3>
                                        <div class="table-responsive mt-3">
                                            <table class="table  table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Sl</th>
                                                        <th>Invoice Id</th>
                                                        <th>Sub Total</th>
                                                        <th>Charge</th>
                                                        <th>Discount</th>
                                                        <th>Quantity</th>
                                                        <th>Total</th>
                                                        <th>Order Date</th>
                                                        <th>Status</th>
                                                        <th>Download Invoice</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $totalQuantity = 0;
                                                        $totalGrandTotal = 0;
                                                        $totalChangeTotal = 0;
                                                        $totalDiscountTotal = 0;
                                                    @endphp
                                                    @forelse ($orders as $key => $order)
                                                        @php
                                                            $grandtotal = ($order->amount + $order->charge);
                                                            $totalQuantity += (int) $order->quantity;
                                                            $totalGrandTotal += $grandtotal;
                                                            $totalChangeTotal += (int) $order->charge;
                                                            $totalDiscountTotal +=  $order->discount;
                                                        @endphp
                                                        <tr>
                                                            <th>{{ $key + 1 }}</th>
                                                            <td>{{ '#'.$order->invoice_id ?? '' }}</td>
                                                            <td>{{ $order->amount ?? 0 }} {{ currency() ?? '' }}</td>
                                                            <td>{{ $order->charge ?? 0 }} {{ currency() ?? '' }}</td>
                                                            <td>{{ $order->discount ?? 0 }} {{ currency() ?? '' }}</td>
                                                            <td>{{ (int) $order->quantity ?? 0 }}</td>
                                                            <td>{{ $grandtotal ?? 0 }} {{ currency() ?? '' }}</td>
                                                            <td>{{ $order->created_at->format('d-m-Y') ?? '' }}</td>
                                                            <td>{!! orderstatus($order->status ?? '') !!}</td>
                                                            <td><a class="btn btn-sm btn-primary" target="_blank" href="{{ route('order.invoice.download',['order_id' => $order->id ?? '']) }}">Download Invoice</a></td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="9" class="text-danger text-center">No Order Found</td>
                                                        </tr>
                                                    @endforelse

                                                    @if (count($orders) > 0)
                                                        <tr class="fw-bold">
                                                            <td colspan="1" class="text-end">Quantity:</td>
                                                            <td>{{ $totalQuantity }}</td>
                                                            <td colspan="1">Discount:</td>
                                                            <td>{{ $totalDiscountTotal }} {{ currency() ?? '' }}</td>
                                                            <td colspan="1">Charge:</td>
                                                            <td>{{ $totalChangeTotal }} {{ currency() ?? '' }}</td>
                                                            <td colspan="1">Total:</td>
                                                            <td>{{ $totalGrandTotal }} {{ currency() ?? '' }}</td>
                                                            <td colspan="2"></td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="v-pills-order-details" role="tabpanel" aria-labelledby="v-pills-order-details-tab">
                                    <div>
                                        <h3 class="border-bottom pb-1">Order Details</h3>
                                        <div class="table-responsive mt-3">
                                            <table class="table  table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Sl</th>
                                                        <th>Invoice Id</th>
                                                        <th>Product Name</th>
                                                        <th>Product Image</th>
                                                        <th>Sub Total</th>
                                                        <th>Quantity</th>
                                                        <th>Total</th>
                                                        <th>Order Date</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $totalQuantity = 0;
                                                        $totalGrandTotal = 0;
                                                        $totalDiscountTotal = 0;
                                                        $totalChangeTotal = 0;
                                                        $orderDetailsCount = 1;
                                                    @endphp
                                                    @forelse ($orders as $key => $order)
                                                        @php
                                                            $totalQuantity += (int) $order->quantity;
                                                            $totalDiscountTotal +=  $order->discount;
                                                            $totalChangeTotal += (int) $order->charge;
                                                        @endphp
                                                        @forelse ($order->orderdetails as $details)
                                                            @php
                                                                $grandtotal = $details->amount;
                                                                $totalGrandTotal += $grandtotal;
                                                            @endphp
                                                            <tr>
                                                                {{-- @dd($details) --}}
                                                                <th>{{ $orderDetailsCount ?? 1 }}</th>
                                                                <td>{{ '#'.$order->invoice_id ?? '' }}</td>
                                                                <td>{{ Str::limit($details->product->name, 12, '...')  ?? 0 }}</td>
                                                                <td>
                                                                    @if($details->product->product_image != null)
                                                                        <img id="user-dashboard-product-image" src="{{ asset($details->product->product_image) }}" alt="image">
                                                                    @else
                                                                    {{ 'N/A' }}
                                                                    @endif
                                                                </td>
                                                                <td>{{ $details->amount ?? 0 }} {{ currency() ?? '' }}</td>
                                                                <td>{{ (int) $details->quantity ?? 0 }}</td>
                                                                <td>{{ $details->grandtotal ?? 0 }} {{ currency() ?? '' }}</td>
                                                                <td>{{ $order->created_at->format('d-m-Y') ?? '' }}</td>
                                                                <td>{!! orderstatus($order->status ?? '') !!}</td>
                                                            </tr>
                                                            @php
                                                                $orderDetailsCount++;
                                                            @endphp
                                                        @empty
                                                            <tr>
                                                                <td colspan="9" class="text-danger text-center">No Order Details Found</td>
                                                            </tr>
                                                        @endforelse
                                                    @empty
                                                        <tr>
                                                            <td colspan="9" class="text-danger text-center">No Order Found</td>
                                                        </tr>
                                                    @endforelse
                                                    @if (count($orders) > 0)
                                                        <tr class="fw-bold">
                                                            <td colspan="1" class="text-end"> Quantity:</td>
                                                            <td>{{ $totalQuantity ?? 0 }}</td>
                                                            <td colspan="1"> Charge:</td>
                                                            <td>{{ $totalChangeTotal ?? 0 }} {{ currency() ?? '' }}</td>
                                                            <td colspan="1"> Discount:</td>
                                                            <td>{{ $totalDiscountTotal ?? 0 }} {{ currency() ?? '' }}</td>
                                                            <td colspan="1"> Total:</td>
                                                            <td>{{ ($totalGrandTotal + $totalChangeTotal) - $totalDiscountTotal }} {{ currency() ?? '' }}</td>
                                                            <td colspan="1"></td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="v-pills-wishlist" role="tabpanel" aria-labelledby="v-pills-wishlist-tab">...</div>
                                <div class="tab-pane fade" id="v-pills-shopping-cart" role="tabpanel" aria-labelledby="v-pills-shopping-cart-tab">
                                    <div>
                                        <h3 class="border-bottom pb-1">My Shopping Details</h3>
                                        <div class="table-responsive mt-3">
                                            <table class="table  table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Sl</th>
                                                        <th>Product Name</th>
                                                        <th>Product Image</th>
                                                        <th>Sub Total</th>
                                                        <th>Quantity</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $totalCartQuantity = 0;
                                                        $totalCartAmout = 0;
                                                        $cartKey = 1;
                                                    @endphp
                                                    @if (session('cart'))
                                                        @forelse (session('cart') as $id => $details)
                                                            @php
                                                                $totalCartQuantity += $details['quantity'];
                                                                $totalCartAmout += $details['quantity'] * $details['price'];
                                                                $grandprice = $details['quantity'] * $details['price'];
                                                            @endphp
                                                            <tr>
                                                                <td>{{ $cartKey ?? 1 }}</td>
                                                                <td>{{ $details['name'] ?? '' }}</td>
                                                                <td>
                                                                    @if($details['image'] != null)
                                                                        <img id="user-dashboard-product-image" src="{{ asset($details['image']) }}" alt="image">
                                                                    @else
                                                                    {{ 'N/A' }}
                                                                    @endif
                                                                </td>
                                                                <td>{{ $details['price'] ?? 0 }}</td>
                                                                <td>{{ $details['quantity'] ?? 0 }}</td>
                                                                <td>{{ $grandprice ?? 0 }}</td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="6" class="text-danger text-center">No Product Found</td>
                                                            </tr>
                                                        @endforelse
                                                    @endif
                                                    @if (count($orders) > 0)
                                                        <tr class="fw-bold">
                                                            <td colspan="3" class="text-end"> Quantity:</td>
                                                            <td>{{ $totalCartQuantity ?? 0 }}</td>
                                                            <td colspan="1"> Total:</td>
                                                            <td>{{  $totalCartAmout }} {{ currency() ?? '' }}</td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="v-pills-my-profile" role="tabpanel" aria-labelledby="v-pills-my-profile-tab">
                                    <div>
                                        <h3 class="border-bottom pb-1">My Profile</h3>
                                        <p class="pb-1 mt-3">Edit Personal Details</p>
                                        <form id="userProfileUpdate" action="{{ route('user.dashboard.update') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="mt-3">
                                                <div class="row">
                                                    <div class="col-md-6 mb-2">
                                                        <label for="" class="required">First Name</label>
                                                        <input type="text"
                                                            placeholder="Enter your first name"
                                                            name="fname" class="form-control" value="{{ Auth::check() ? Auth::user()->fname ?? '' : '' }}">
                                                            <p class="text-danger fname-error mb-0"></p>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label for="" class="required">Last Name</label>
                                                        <input type="text"
                                                            placeholder="Enter your last name"
                                                            name="lname" class="form-control" value="{{ Auth::check() ? Auth::user()->lname ?? '' : '' }}">
                                                            <p class="text-danger lname-error mb-0"></p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-2">
                                                        <label for="" class="required">Email</label>
                                                        <input type="text"
                                                            placeholder="Enter your email name"
                                                            name="email" class="form-control" value="{{ Auth::check() ? Auth::user()->email ?? '' : '' }}">
                                                            <p class="text-danger email-error mb-0"></p>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label for="" class="required">Phone Number</label>
                                                        <input type="text"
                                                            placeholder="Enter your phone number"
                                                            name="phone" class="form-control" value="{{ Auth::check() ? Auth::user()->phone ?? '' : '' }}">
                                                            <p class="text-danger phone-error mb-0"></p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-2">
                                                        <label for="" >House Number</label>
                                                        <input type="text"
                                                            placeholder="Enter your house number"
                                                            name="house_number" class="form-control" value="{{ Auth::check() ? Auth::user()->house_number ?? '' : '' }}">
                                                            <p class="text-danger house_number-error mb-0"></p>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label for="" >City</label>
                                                        <input type="text"
                                                            placeholder="Enter your city"
                                                            name="city" class="form-control" value="{{ Auth::check() ? Auth::user()->city ?? '' : '' }}">
                                                            <p class="text-danger city-error mb-0"></p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-2">
                                                        <label for="">State</label>
                                                        <input type="text"
                                                            placeholder="Enter your state"
                                                            name="state" class="form-control" value="{{ Auth::check() ? Auth::user()->state ?? '' : '' }}">
                                                            <p class="text-danger state-error mb-0"></p>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label for="">Zip</label>
                                                        <input type="text"
                                                            placeholder="Enter your zip"
                                                            name="zip" class="form-control" value="{{ Auth::check() ? Auth::user()->zip ?? '' : '' }}">
                                                            <p class="text-danger zip-error mb-0"></p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label class="text-dark font-weight-medium">Avater</label>
                                                        <div>
                                                            <label class="first__picture" for="first__image" tabIndex="0">
                                                                <span class="picture__first"></span>
                                                            </label>
                                                            <input type="file" name="avater" id="first__image" accept="image/*">
                                                            <span class="text-danger error-text avater-error"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="d-flex justify-content-end">
                                                        <button type="submit" class="btn btn-success d-flex align-items-center">
                                                            <div class="spinner-border text-light me-2 d-none  update-user-info" role="status">
                                                        <span class="visually-hidden">Loading...</span>
                                                        </div>Submit</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="v-pills-change-password" role="tabpanel" aria-labelledby="v-pills-change-password-tab">
                                    <div>
                                        <h3 class="border-bottom pb-1">Password Change</h3>
                                        <form id="userPasswordUpdate" action="{{ route('user.dashboard.password.update') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="mt-3">
                                                <div class="row">
                                                    <div class="col-md-6 mb-2">
                                                        <label for="" class="required">Email</label>
                                                        <input type="email"
                                                            placeholder="Enter your email name"
                                                            name="email" class="form-control" value="{{ Auth::check() ? Auth::user()->email ?? '' : '' }}" readonly>
                                                            <p class="text-danger email-error mb-0"></p>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label for="" class="required">Current Password</label>
                                                        <input
                                                            placeholder="Enter your current_password" type="password"
                                                            name="current_password" class="form-control">
                                                            <p class="text-danger current_password-error mb-0"></p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-2">
                                                        <label for="" class="required">Password</label>
                                                        <input
                                                            placeholder="Enter your password" type="password"
                                                            name="password" class="form-control">
                                                            <p class="text-danger password-error mb-0"></p>
                                                    </div>

                                                    <div class="col-md-6 mb-2">
                                                        <label for="" class="required">Confirm Password</label>
                                                        <input
                                                            placeholder="Enter your confirm password" type="password"
                                                            name="password_confirmation" class="form-control">
                                                            <p class="text-danger password_confirmation-error mb-0"></p>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="d-flex justify-content-end">
                                                        <button type="submit" class="btn btn-success d-flex align-items-center">
                                                            <div class="spinner-border text-light me-2 d-none" role="status">
                                                        <span class="visually-hidden">Loading...</span>
                                                        </div>Submit</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                {{-- <div class="tab-pane fade" id="v-pills-my-addresses" role="tabpanel" aria-labelledby="v-pills-my-addresses-tab">...</div> --}}
                                <div class="tab-pane fade" id="v-pills-coupons" role="tabpanel" aria-labelledby="v-pills-coupons-tab">
                                    <div>
                                        <h3 class="border-bottom pb-1">Use Coupon Details</h3>
                                        <div class="table-responsive mt-3">
                                            <table class="table  table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Sl</th>
                                                        <th>Coupon Name</th>
                                                        <th>Coupon Code</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($coupons as $key => $coupon)
                                                        <tr>
                                                            <th>{{ $key + 1 ?? 1 }}</th>
                                                            <td>{{ $coupon?->coupon?->name ?? '' }}</td>
                                                            <td>{{ $coupon?->coupon?->code ?? '' }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="3" class="text-danger text-center">No Coupon Found</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="v-pills-product-reviews" role="tabpanel" aria-labelledby="v-pills-product-reviews-tab">
                                    <div>
                                        <h3 class="border-bottom pb-1">My Product Reviews</h3>
                                        <div class="table-responsive mt-3">
                                            <table class="table  table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Sl</th>
                                                        <th>Product Name</th>
                                                        <th>Product Image</th>
                                                        <th>Review</th>
                                                        <th>Review Text</th>
                                                        <th>Status</th>
                                                        <th>Created Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($productreviews as $key => $review)
                                                        <tr>
                                                            <td>{{ $key + 1 ?? 1 }}</td>
                                                            <td>{{ Str::limit($review->product->name ?? '', 15, '...') }}</td>
                                                            <td>
                                                                @if($review->product->product_image != null)
                                                                    <img id="user-dashboard-product-image" src="{{ asset($review->product->product_image) }}" alt="image">
                                                                @else
                                                                {{ 'N/A' }}
                                                                @endif
                                                            </td>
                                                            <td>{!! productreview($review->review ?? 0)  !!}</td>
                                                            <td>
                                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"        data-bs-target="#userproductreview_{{ $review->id }}">
                                                                    <i class="fa-solid fa-eye text-white"></i>
                                                                </button>
                                                                <div class="modal fade" id="userproductreview_{{ $review->id }}" tabindex="-1"
                                                                    aria-labelledby="userproductreview_{{ $review->id }}_Label" aria-hidden="true">
                                                                    <div class="modal-dialog">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="exampleModalLabel">Review Modal
                                                                                </h5>
                                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                            </div>
                                                                            <div class="modal-body p-3">
                                                                                <div class="mb-2">
                                                                                    {!! $review->review_text !!}
                                                                                </div>
                                                                                @if($review && $review->reviewDetails && count($review->reviewDetails) > 0)
                                                                                    @forelse ($review->reviewDetails as $details)
                                                                                        @if($details->image != null)
                                                                                            <img src="{{ asset($details->image) }}" alt="image">
                                                                                        @endif
                                                                                    @empty
                                                                                    @endforelse
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>{!! status($review->status ?? 0) !!}</td>
                                                            <td>{{ $review->created_at->format('d-m-Y') }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="6" class="text-danger text-center">No Product Review Found</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="tab-pane fade" id="v-pills-notifications" role="tabpanel" aria-labelledby="v-pills-notifications-tab">...</div> --}}
                                {{-- <div class="tab-pane fade" id="v-pills-support-tickets" role="tabpanel" aria-labelledby="v-pills-support-tickets-tab">...</div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
<script src="{{ asset('backend/assets/js/image-preview.js') }}"></script>
<script>
    $(function() {
        ImagePriviewInsert('first__image', 'picture__first', 'Avater');
    });

    var userImage = "{{ Auth::user()->avater ?? '' }}";

    if (userImage != '') {
        var myUserImage = "{{ asset(Auth::user()->avater ?? '') }}";
        $(function() {
            ImagePriviewUpdate('first__image', 'picture__first', 'Avater', myUserImage);
        });
    }

    $('#userProfileUpdate').on('submit', function(e) {
        e.preventDefault();
        $('.update-user-info').removeClass('d-none');
        $('.error-text').text('');
        let formData = new FormData(this);
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                if (res.status == 'success') {
                    flashMessage(res.status, res.message);
                    setTimeout(() => {
                        location.reload();
                    }, 500);
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    $('.update-user-info').addClass('d-none');
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        $('.' + key + '-error').text(value[0]);
                    });
                } else {
                    $('.update-user-info').addClass('d-none');
                    console.log('Something went wrong. Please try again.');
                }
            }
        });
    });

    $('#userPasswordUpdate').on('submit', function(e) {
        e.preventDefault();
        $('.spinner-border').removeClass('d-none');
        $('.error-text').text('');
        let formData = new FormData(this);
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                console.log(res);
                if (res.status == 'success') {
                    flashMessage(res.status, res.message);
                    $('.spinner-border').addClass('d-none');
                }else{
                    flashMessage(res.status, res.message);
                    $('.spinner-border').addClass('d-none');
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    $('.spinner-border').addClass('d-none');
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        $('.' + key + '-error').text(value[0]);
                    });
                } else {
                    $('.spinner-border').addClass('d-none');
                    console.log('Something went wrong. Please try again.');
                }
            }
        });
    });
</script>
@endpush

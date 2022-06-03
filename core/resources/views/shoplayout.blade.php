<!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <base href="{{url('/')}}"/>
        <title>{{ $title }} | {{$set->site_name}}</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1" />
        <meta name="robots" content="index, follow">
        <meta name="apple-mobile-web-app-title" content="{{$set->site_name}}"/>
        <meta name="application-name" content="{{$set->site_name}}"/>
        <meta name="msapplication-TileColor" content="#ffffff"/>
        <meta name="description" content="{{$set->site_desc}}" />
        <link rel="shortcut icon" href="{{url('/')}}/asset/{{$logo->image_link2}}" />
        <!--<link rel="stylesheet" href="{{url('/')}}/asset/css/sweetalert.css" type="text/css">-->
        <link rel="stylesheet" href="{{url('/')}}/asset/css/toast.css" type="text/css">
        <link rel="stylesheet" href="{{url('/')}}/asset/shop/css/theme.css" type="text/css">
        <link href="{{url('/')}}/asset/fonts/fontawesome/css/all.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="{{url('/')}}/asset/css/toast.css" type="text/css">
         @yield('css')
    </head>
    <body>
    <header>
	<!-- tt-mobile menu -->
	<nav class="panel-menu mobile-main-menu">
		<div class="mm-navbtn-names">
			<div class="mm-closebtn">Close</div>
			<div class="mm-backbtn">Back</div>
		</div>
	</nav>
	<!-- tt-mobile-header -->
	<div class="tt-mobile-header">
		<div class="container-fluid">
			<div class="tt-header-row">
				<div class="tt-mobile-parent-menu">
				</div>
				<!-- search -->
				<div class="tt-mobile-parent-search tt-parent-box"></div>
				<!-- /search -->
				<!-- cart -->
				<div class="tt-mobile-parent-cart tt-parent-box"></div>
				<!-- /cart -->
			</div>
		</div>
		<div class="container-fluid">
			<div class="row">
				<div class="tt-logo-container">
					<!-- mobile logo -->
					<a class="tt-logo tt-logo-alignment" href="javascript:void;"><img src="{{url('/')}}/asset/profile/{{$merchant->image}}" alt=""></a>
					<!-- /mobile logo -->
				</div>
			</div>
		</div>
	</div>
	<!-- tt-desktop-header -->
	<div class="tt-desktop-header">
		<div class="container">
			<div class="tt-header-holder">
				<div class="tt-col-obj tt-obj-logo">
					<a class="tt-logo tt-logo-alignment" href="javascript:void;"><img src="{{url('/')}}/asset/profile/{{$merchant->image}}" alt=""></a>
				</div>
				<div class="tt-col-obj tt-obj-menu">
					<!-- tt-menu -->
					<div class="tt-desctop-parent-menu tt-parent-box">
						<div class="tt-desctop-menu">
							<nav>
								<ul>
                                    <li class="dropdown">
										<a href="{{route('user.dashboard')}}">SET UP YOUR STORE FOR FREE</a>
									</li>
								</ul>
							</nav>
						</div>
					</div>
					<!-- /tt-menu -->
				</div>
				<div class="tt-col-obj tt-obj-options obj-move-right">
					<!-- tt-search -->

					<!-- /tt-search -->
					<!-- tt-cart -->
					<div class="tt-desctop-parent-cart tt-parent-box">
						<div class="tt-cart tt-dropdown-obj" data-tooltip="Cart" data-tposition="bottom">
							<button class="tt-dropdown-toggle">
								<i class="icon-f-39"></i>
								<span class="tt-badge-cart">{{count($cart)}}</span>
							</button>
							<div class="tt-dropdown-menu">
								<div class="tt-mobile-add">
									<h6 class="tt-title">SHOPPING CART</h6>
									<button class="tt-close">Close</button>
								</div>
								<div class="tt-dropdown-inner">
									<div class="tt-cart-layout">
										<!-- layout emty cart -->
										<!-- <a href="empty-cart.html" class="tt-cart-empty">
											<i class="icon-f-39"></i>
											<p>No Products in the Cart</p>
										</a> -->
										<div class="tt-cart-content">
											<div class="tt-cart-list">
                                                @foreach($cart as $val)
												<div class="tt-item">
													<a href="product.html">
														<div class="tt-item-img">
															<img src="images/loader.svg" data-src="images/product/product-01.jpg" alt="">
														</div>
														<div class="tt-item-descriptions">
															<h2 class="tt-title">{{$val->title}}</h2>
															<div class="tt-quantity">{{$val->quantity}} X</div> <div class="tt-price">{{$val->cost}}</div>
														</div>
													</a>
													<div class="tt-item-close">
														<a href="{{route('delete.cart', ['id'=>$val->id])}}" class="tt-btn-close"></a>
													</div>
                                                </div>
                                                @endforeach
											</div>
											<div class="tt-cart-total-row">
												<div class="tt-cart-total-title">SUBTOTAL:</div>
												<div class="tt-cart-total-price">{{$currency->symbol.$gtotal}}</div>
											</div>
											<div class="tt-cart-btn">
												<div class="tt-item">
													@if(count($cart)>0)
														<a href="{{route('user.sask', ['id'=>$val->uniqueid])}}" class="btn">PROCEED TO CHECKOUT</a>
													@endif
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- stuck nav -->
	<div class="tt-stuck-nav">
		<div class="container">
			<div class="tt-header-row">
				<div class="tt-stuck-parent-search tt-parent-box"></div>
				<div class="tt-stuck-parent-cart tt-parent-box"></div>
			</div>
		</div>
	</div>
</header>
<div class="tt-breadcrumb">
	<div class="container">
		<ul>
            <li><a href="{{route('store.link', ['id' => $store->store_url])}}">Home</a></li>
            @if(route('store.link', ['id' => $store->store_url])!=url()->current())
            <li><a href="javascript:void;">{{$product->name}}</a></li>
            @endif
		</ul>
	</div>
</div>
<div id="tt-pageContent">
@yield('content')
</div>
<footer>
	<div class="tt-footer-col tt-color-scheme-01">
		<div class="container">
			<div class="row">
				<div class="col-md-6 col-lg-6 col-xl-6">
					<div class="tt-mobile-collapse">
						<h4 class="tt-collapse-title">
							{{$store->store_name}}
						</h4>
						<div class="tt-collapse-content">
                            <address>
                                <span>By {{$merchant->business_name}}</span>
                                <span>{{$store->store_desc}}</span>
                            </address>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-lg-6 col-xl-6 text-right">
					<div class="tt-newsletter">
						<div class="tt-mobile-collapse">
							<div class="tt-collapse-content">
								<address>
									<span>Phone: {{$merchant->phone}}</span>
									<span>E-mail: <a href="mailto:{{$merchant->support_email}}">{{$merchant->support_email}}</a></span>
								</address>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</footer>

<script src="{{url('/')}}/asset/shop/external/jquery/jquery.min.js"></script>
<script src="{{url('/')}}/asset/shop/external/bootstrap/js/bootstrap.min.js"></script>
<script src="{{url('/')}}/asset/shop/external/slick/slick.min.js"></script>
<script src="{{url('/')}}/asset/shop/external/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="{{url('/')}}/asset/shop/external/panelmenu/panelmenu.js"></script>
<script src="{{url('/')}}/asset/shop/external/instafeed/instafeed.min.js"></script>
<script src="{{url('/')}}/asset/shop/external/rs-plugin/js/jquery.themepunch.tools.min.js"></script>
<script src="{{url('/')}}/asset/shop/external/rs-plugin/js/jquery.themepunch.revolution.min.js"></script>
<script src="{{url('/')}}/asset/shop/external/countdown/jquery.plugin.min.js"></script>
<script src="{{url('/')}}/asset/shop/external/countdown/jquery.countdown.min.js"></script>
<script src="{{url('/')}}/asset/shop/external/lazyLoad/lazyload.min.js"></script>
<script src="{{url('/')}}/asset/shop/js/main.js"></script>
<script src="{{url('/')}}/asset/shop/external/magnific-popup/jquery.magnific-popup.min.js"></script>
<script src="{{url('/')}}/asset/shop/external/elevatezoom/jquery.elevatezoom.js"></script>
<!-- form validation and sending to mail -->
<script src="{{url('/')}}/asset/shop/external/form/jquery.form.js"></script>
<script src="{{url('/')}}/asset/shop/external/form/jquery.validate.min.js"></script>
<script src="{{url('/')}}/asset/shop/external/form/jquery.form-init.js"></script>
<script src="{{url('/')}}/asset/js/toast.js"></script>
@if (session('success'))
    <script>
      "use strict";
      toastr.success("{{ session('success') }}");
    </script>    
@endif

@if (session('alert'))
    <script>
      "use strict";
      toastr.warning("{{ session('alert') }}");
    </script>
@endif
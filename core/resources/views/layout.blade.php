<!doctype html>
<html class="no-js" lang="en">
    <head>
        <base href="{{url('/')}}"/>
        <title>{{ $title }} - {{$set->site_name}}</title>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="robots" content="index, follow">
        <meta name="apple-mobile-web-app-title" content="{{$set->site_name}}"/>
        <meta name="application-name" content="{{$set->site_name}}"/>
        <meta name="msapplication-TileColor" content="#ffffff"/>
        <meta name="description" content="{{$set->site_desc}}" />
        <link rel="shortcut icon" href="{{url('/')}}/asset/{{$logo->image_link2}}" />
        <link href="{{url('/')}}/asset/static/plugin/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="{{url('/')}}/asset/static/plugin/font-awesome/css/all.min.css" rel="stylesheet">
        <link href="{{url('/')}}/asset/static/plugin/et-line/style.css" rel="stylesheet">
        <link href="{{url('/')}}/asset/static/plugin/themify-icons/themify-icons.css" rel="stylesheet">
        <link href="{{url('/')}}/asset/static/plugin/ionicons/css/ionicons.min.css" rel="stylesheet">
        <link href="{{url('/')}}/asset/static/plugin/owl-carousel/css/owl.carousel.min.css" rel="stylesheet">
        <link href="{{url('/')}}/asset/static/plugin/magnific/magnific-popup.css" rel="stylesheet">
        <link href="{{url('/')}}/asset/static/style/master.css" rel="stylesheet">
        <link rel="stylesheet" href="{{url('/')}}/asset/css/toast.css" type="text/css">
        <link href="{{url('/')}}/asset/fonts/fontawesome/css/all.css" rel="stylesheet" type="text/css">
         @yield('css')
    </head>

    <body data-spy="scroll" data-target="#navbar-collapse-toggle" data-offset="98">
    <!-- Preload -->
    <!--
    <div id="loading">
        <div class="load-circle"><span class="one"></span></div>
    </div>
    -->
    <!-- End Preload -->
    <!-- Header -->
    <header class="header-nav header-dark">
        <div class="fixed-header-bar">
            <!-- Header Nav -->
            <div class="navbar navbar-main navbar-expand-lg">
                <div class="container">
                    <a class="navbar-brand" href="{{url('/')}}">
                        <img alt="" title="" src="{{url('/')}}/asset/{{$logo->image_link}}">
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-main-collapse" aria-controls="navbar-main-collapse" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse navbar-collapse-overlay" id="navbar-main-collapse">
                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('about')}}">{{__('Why')}} {{$set->site_name}}</a>
                            </li>                                                         
                            <li class="nav-item mm-in px-dropdown">
                                <a class="nav-link">{{__('Features')}}</a>
                                <ul class="px-dropdown-menu mm-dorp-in">
                                    @if($set->transfer==1)      
                                    <li><a href="{{route('user.transfer')}}">{{__('Transfer Money')}}</a></li>
                                    @endif
                                    @if($set->request_money==1)
                                    <li><a href="{{route('user.request')}}">{{__('Request Money')}}</a></li>
                                    @endif
                                    @if($set->vcard==1)
                                    <li><a href="{{route('user.virtualcard')}}">{{__('Virtual Cards')}}</a></li>
                                    @endif
                                    @if($set->bill==1) 
                                    <li><a href="{{route('user.airtime')}}">{{__('Bill Payment')}}</a></li>
                                    @endif
                                    <li><a href="{{route('user.subaccounts')}}">{{__('Sub Accounts')}}</a></li>
                                    @if($set->store==1) 
                                    <li><a href="{{route('user.storefront')}}">{{__('Storefront')}}</a></li>
                                    @endif
                                    @if($set->single==1)
                                    <li><a href="{{route('user.sclinks')}}">{{__('Single Charge')}}</a></li>
                                    @endif
                                    @if($set->donation==1) 
                                    <li><a href="{{route('user.dplinks')}}">{{__('Donations')}}</a></li>
                                    @endif
                                    @if($set->invoice==1) 
                                    <li><a href="{{route('user.invoice')}}">{{__('Invoice')}}</a></li>
                                    @endif
                                    @if($set->subscription==1)
                                    <li><a href="{{route('user.plan')}}">{{__('Subscription Service')}}</a></li>
                                    @endif
                                    @if($set->merchant==1)
                                    <li><a href="{{route('user.merchant')}}">{{__('Website Integration')}}</a></li>
                                    @endif
                                </ul>
                            </li>                            
                            <li class="nav-item mm-in px-dropdown">
                                <a class="nav-link">{{__('Help')}}</a>
                                <ul class="px-dropdown-menu mm-dorp-in">
                                    <li><a href="{{route('faq')}}">{{__('FAQs')}}</a></li>
                                    <li><a href="{{route('contact')}}">{{__('Contact us')}}</a></li>
                                </ul>
                            </li> 
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('blog')}}">{{__('Blog')}}</a>
                            </li>                           
                        </ul>
                    </div>
                </div>
            </div>
            <!-- End Header Nav -->
        </div>
    </header>
    <!-- Header End -->
    <!-- Main -->
    <main>
@yield('content')
    <footer class="footer effect-section p-60px-t">
        <div class="footer-top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 m-15px-tb">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="p-25px-b">
                                    <img class="logo-dark nav-img" alt="" title="" src="{{url('/')}}/asset/{{$logo->image_link}}">
                                </div>
                                <p>
                                    {{$set->site_desc}}
                                </p>
                                <div class="social-icon si-30 theme round nav">
                                    @foreach($social as $socials)
                                        @if(!empty($socials->value))
                                            <a href="{{$socials->value}}" ><i class="fab fa-{{$socials->type}}"></i></a>
                                        @endif
                                    @endforeach 
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 m-15px-tb">
                        <h5 class="footer-title">{{__('Our Solutions')}}</h5>
                        <div class="row">
                            <div class="col-lg-4 m-15px-tb">
                                <ul class="list-unstyled links-dark footer-link-1">
                                    @if($set->transfer==1)      
                                    <li><a href="{{route('user.transfer')}}">{{__('Transfer Money')}}</a></li>
                                    @endif
                                    @if($set->request_money==1)
                                    <li><a href="{{route('user.request')}}">{{__('Request Money')}}</a></li>
                                    @endif
                                    @if($set->vcard==1)
                                    <li><a href="{{route('user.virtualcard')}}">{{__('Virtual Cards')}}</a></li>
                                    @endif
                                    @if($set->bill==1) 
                                    <li><a href="{{route('user.airtime')}}">{{__('Bill Payment')}}</a></li>
                                    @endif
                                </ul>
                            </div>
                            <div class="col-lg-4 m-15px-tb">
                                <ul class="list-unstyled links-dark footer-link-1">
                                    <li><a href="{{route('user.subaccounts')}}">{{__('Sub Accounts')}}</a></li>
                                    @if($set->store==1) 
                                    <li><a href="{{route('user.storefront')}}">{{__('Storefront')}}</a></li>
                                    @endif
                                    @if($set->single==1)
                                    <li><a href="{{route('user.sclinks')}}">{{__('Single Charge')}}</a></li>
                                    @endif
                                    @if($set->donation==1) 
                                    <li><a href="{{route('user.dplinks')}}">{{__('Donations')}}</a></li>
                                    @endif
                                </ul>
                            </div>                
                            <div class="col-lg-4 m-15px-tb">
                                <ul class="list-unstyled links-dark footer-link-1">
                                    @if($set->invoice==1) 
                                    <li><a href="{{route('user.invoice')}}">{{__('Invoice')}}</a></li>
                                    @endif
                                    @if($set->subscription==1)
                                    <li><a href="{{route('user.plan')}}">{{__('Subscription Service')}}</a></li>
                                    @endif
                                    @if($set->merchant==1)
                                    <li><a href="{{route('user.merchant')}}">{{__('Website Integration')}}</a></li>
                                    @endif
                                </ul>
                            </div>
                        </div>                        
                        <div class="row">             
                            <div class="col-lg-4 m-15px-tb">
                                <h5 class="footer-title">
                                {{__('Help')}}
                                </h5>
                                <ul class="list-unstyled links-dark footer-link-1">
                                    <li><a href="{{url('/')}}/#contact" >{{__('Contact')}}</a></li>
                                    <li><a href="{{url('/')}}/#faq">{{__('FAQs')}}</a></li>
                                    <li><a href="{{route('terms')}}" >{{__('Terms of Use')}}</a></li>
                                    <li><a href="{{route('privacy')}}" >{{__('Privacy Policy')}}</a></li>
                                </ul>
                            </div>
                            <div class="col-lg-4 m-15px-tb">
                                <h5 class="footer-title">
                                {{__('More')}}
                                </h5>
                                <ul class="list-unstyled links-dark footer-link-1">
                                    @foreach($pages as $vpages)
                                        @if(!empty($vpages))
                                    <li><a href="{{url('/')}}/page/{{$vpages->id}}">{{$vpages->title}}</a></li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom footer-border-dark">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 text-center text-md-right m-5px-tb">
                        <ul class="nav justify-content-center justify-content-md-start links-dark font-small footer-link-1">
                        </ul>
                    </div>
                    <div class="col-md-6 text-center text-md-right m-5px-tb">
                        <p class="m-0px font-small">{{$set->site_name}}  &copy; {{date('Y')}}. {{__('All Rights Reserved')}}.</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>
{!!$set->livechat!!}
        <script>
            var urx = "{{url('/')}}";
        </script>
        <script src="{{url('/')}}/asset/static/js/jquery-3.2.1.min.js"></script>
        <script src="{{url('/')}}/asset/static/js/jquery-migrate-3.0.0.min.js"></script>
        <script src="{{url('/')}}/asset/static/plugin/appear/jquery.appear.js"></script>
        <script src="{{url('/')}}/asset/static/plugin/bootstrap/js/popper.min.js"></script>
        <script src="{{url('/')}}/asset/static/plugin/bootstrap/js/bootstrap.js"></script>
        <script src="{{url('/')}}/asset/static/js/custom.js"></script>
        <script src="{{url('/')}}/asset/js/toast.js"></script>
@yield('script')
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

</body>
</html>
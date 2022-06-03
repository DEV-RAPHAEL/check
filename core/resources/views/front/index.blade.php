@extends('layout')
@section('css')

@stop
@section('content')
<section class="gray-bg effect-section">
    <div class="svg-bottom">
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="100%" height="96px" viewBox="0 0 100 100" version="1.1" preserveAspectRatio="none" class="injected-svg svg_img white-color">
            <path d="M0,0 C16.6666667,66 33.3333333,99 50,99 C66.6666667,99 83.3333333,66 100,0 L100,100 L0,100 L0,0 Z"></path>
        </svg>
    </div>
    <div class="container">
        <div class="row full-screen align-items-center p-50px-tb lg-p-100px-t justify-content-center">
            <div class="col-lg-6 m-50px-tb md-m-20px-t">
                <h6 class="typed theme3rd-bg p-5px-tb p-15px-lr d-inline-block white-color border-radius-15 m-25px-b" data-elements="{{$set->title}}"></h6>
                <h1 class="display-4 m-20px-b">{{$ui->header_title}}</h1>
                <p class="lead m-35px-b">{{$ui->header_body}}</p>
                <div class="p-20px-t m-btn-wide">
                    @if (Auth::guard('user')->check())
                    <a class="m-btn m-btn-radius m-btn-t-dark m-10px-r" href="{{route('user.dashboard')}}">
                        <span class="m-btn-inner-text">{{__('Dashboard')}}</span>
                        <span class="m-btn-inner-icon arrow"></span>
                    </a>
                    @else
                    <a class="m-btn m-btn-radius m-btn-t-dark m-10px-r" href="{{route('login')}}">
                        <span class="m-btn-inner-text">{{__('Sign In')}}</span>
                        <span class="m-btn-inner-icon arrow"></span>
                    </a>
                    <a class="m-btn m-btn-radius m-btn m-btn-theme-light" href="{{route('register')}}">
                        <span class="m-btn-inner-text">{{__('Get Started')}}</span>
                    </a>
                    @endif
                </div>
            </div>
            <div class="col-lg-6 m-15px-tb">
                <img class="max-width-120" src="{{url('/')}}/asset/images/{{$ui->s4_image}}" title="" alt="">
            </div>
        </div>
    </div>
</section>
<section class="section p-0px-t section-top-up-100">
    <div class="container">
        <div class="row">
            @foreach($item as $val)
            <div class="col-sm-6 col-lg-3 m-15px-tb">
                <div class="p-25px-lr p-35px-tb white-bg box-shadow-lg hover-top border-radius-15">
                    <h5 class="m-10px-b">{{$val->title}}</h5>
                    <p class="m-0px">{{$val->details}}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
<div class="p-40px-tb border-top-1 border-bottom-1 border-color-gray">
    <div class="container">
        <div class="owl-carousel owl-loaded owl-drag" data-items="7" data-nav-dots="false" data-md-items="6" data-sm-items="5" data-xs-items="4" data-xx-items="3" data-space="30" data-autoplay="true">
            @foreach($brand as $brands)
                <div class="p8">
                    <img src="{{url('/')}}/asset/brands/{{$brands->image}}" title="" alt="">
                </div>
            @endforeach
        </div>
    </div>
</div>
<section class="section effect-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 m-15px-tb text-center">
                <img src="{{url('/')}}/asset/images/{{$ui->s3_image}}" title="" alt="">
            </div>
            <div class="col-lg-6 m-15px-tb">
                <h3 class="h1">{{$ui->s3_title}}</h3>
                <p class="font-2 p-0px-t">{{$ui->s3_body}}</p>
                <div class="border-left-2 border-color-theme p-25px-l m-35px-t">
                    <h6 class="font-2">{{$set->title}}</h6>
                    <p>{{__('Stimulate your sales with modular payment solutions and loyalty programs!')}}</p>
                </div>
                <div class="p-20px-t">
                    <a class="m-btn m-btn-radius m-btn m-btn-theme-light" href="{{route('about')}}">
                        <span class="m-btn-inner-text">More About Us</span>
                        <span class="m-btn-inner-icon arrow"></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="section effect-section">
    <div class="effect-radius-bg">
        <div class="radius-1"></div>
        <div class="radius-2"></div>
        <div class="radius-3"></div>
        <div class="radius-4"></div>
        <div class="radius-x"></div>
    </div>
    <div class="container">
        <div class="row justify-content-center md-m-25px-b m-40px-b">
            <div class="col-lg-8 text-center">
                <h3 class="h1 m-15px-b">{{$ui->s6_title}}</h3>
                <p class="m-0px font-2">{{$ui->s6_body}}</p>
            </div>
        </div>
        <div class="row align-items-center">
            <div class="col-lg-4 m-15px-tb">
                <div class="row">
                    <div class="col-lg-12 col-sm-6 ">
                        <div class="media p5">
                            <div class="icon-70 box-shadow-lg theme-color border-radius-50">
                                <i class="fal fa-store-alt"></i>
                            </div>
                            <div class="media-body p-15px-l">
                                <h6 class="m-20px-t">Storefront</h6>
                            </div>
                        </div>
                    </div>                  
                    <div class="col-lg-12 col-sm-6">
                        <div class="media p5">
                            <div class="icon-70 box-shadow-lg theme-color border-radius-50">
                                <i class="fal fa-random"></i>
                            </div>
                            <div class="media-body p-15px-l">
                                <h6 class="m-20px-t">Transfer/Request Money</h6>
                            </div>
                        </div>
                    </div>                    
                    <div class="col-lg-12 col-sm-6 m-15px-tb">
                        <div class="media p5">
                            <div class="icon-70 box-shadow-lg theme-color border-radius-50">
                                <i class="fal fa-money-bill-wave-alt"></i>
                            </div>
                            <div class="media-body p-15px-l">
                                <h6 class="m-20px-t">Bill Payment</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 m-15px-tb text-center">
                <img src="{{url('/')}}/asset/images/{{$ui->s2_image}}" title="" alt="">
            </div>
            <div class="col-lg-4 m-15px-tb">
                <div class="row">
                    <div class="col-lg-12 col-sm-6">
                        <div class="media p5">
                            <div class="media-body">
                                <h6 class="m-20px-t">Payment pages</h6>
                            </div>
                            <div class="p-0px-l icon-70 box-shadow-lg theme-color border-radius-50">
                                <i class="fal fa-link"></i>
                            </div>
                        </div>
                    </div>                    
                    <div class="col-lg-12 col-sm-6">
                        <div class="media p5">
                            <div class="media-body p-15px-l">
                                <h6 class="m-20px-t">Invoice Payment</h6>
                            </div>
                            <div class="icon-70 box-shadow-lg theme-color border-radius-50">
                                <i class="fal fa-envelope"></i>
                            </div>
                        </div>
                    </div>                    
                    <div class="col-lg-12 col-sm-6">
                        <div class="media p5">
                            <div class="media-body p-15px-l">
                                <h6 class="m-20px-t">Virtual Cards</h6>
                            </div>
                            <div class="icon-70 box-shadow-lg theme-color border-radius-50">
                                <i class="fal fa-credit-card-front"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@if(count($review)>0)
<section class="p-50px-t">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-lg-6">
                <img src="{{url('/')}}/asset/images/{{$ui->s7_image}}" title="" alt="">
            </div>
            <div class="col-lg-5 m-30px-b m-30px-t">
                <h3 class="h3 m-30px-b">{{$ui->s7_title}}</h3>
                <div class="owl-carousel owl-nav-arrow-bottom white-bg box-shadow-lg p5" data-items="1" data-nav-arrow="true" data-nav-dots="false" data-md-items="1" data-sm-items="1" data-xs-items="1" data-xx-items="1" data-space="0" data-autoplay="true">
                    @foreach($review as $vreview)
                    <div class="p-25px m-20px-b">
                        <p class="m-0px">{{$vreview->review}}</p>
                        <div class="media m-20px-t">
                            <div class="avatar-60 border-radius-50">
                                <img src="{{url('/')}}/asset/review/{{$vreview->image_link}}" alt="" title="">
                            </div>
                            <div class="media-body p-15px-l align-self-center">
                                <h6 class="m-0px">{{$vreview->name}}</h6>
                                <span class="font-small">{{$vreview->occupation}}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endif
<section class="section gray-bg">
    <div class="container">
        <div class="row justify-content-center md-m-25px-b m-40px-b">
            <div class="col-lg-6 text-center">
                <h3 class="h1 m-0px">{{__('Join millions who choose')}} {{$set->site_name}} {{__('worldwide.')}}</h3>
                <div class="p-20px-t">
                    <a class="m-btn m-btn-dark m-btn-radius" href="{{route('register')}}">{{__('Sign Up for Free')}} </a>
                </div>
            </div>
        </div>
    </div>
</section>
@stop
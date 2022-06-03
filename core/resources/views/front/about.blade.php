@extends('layout')
@section('css')

@stop
@section('content')
<section class="parallax effect-section">
    <div class="mask white-bg opacity-8"></div>
        <div class="container position-relative">
            <div class="row screen-65 align-items-center justify-content-center p-100px-tb">
                <div class="col-lg-10 text-center">
                    <h6 class="white-color-black font-w-500">{{$set->title}}</h6>
                    <h1 class="display-4 black-color m-20px-b">{{__('About')}} {{$set->site_name}}</h1>
                </div>
            </div>
        </div>
        <div id="jarallax-container-0" style="position: absolute; top: 0px; left: 0px; width: 100%; height: 100%; overflow: hidden; pointer-events: none; z-index: -100;"><div style="background-size: cover; background-image: url(&quot;file:///Users/mac/Documents/Templates/themeforest-cDhKHVPF-raino-multipurpose-responsive-template/template/static/img/1600x900.jpg&quot;); position: absolute; top: 0px; left: 0px; width: 1440px; height: 420px; overflow: hidden; pointer-events: none; margin-top: 31.5px; transform: translate3d(0px, -74.5px, 0px); background-position: 50% 50%; background-repeat: no-repeat no-repeat;">
        </div>
    </div>
</section>
<section class="p-50px-b">
    <div class="container">
        <div class="row flex-row-reverse">
            <div class="col-lg-12 m-30px-b align-self-center">
            {!!$about->about!!}
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
@stop
@extends('layout')
@section('css')

@stop
@section('content')
<section class="parallax effect-section gray-bg">
        <div class="container position-relative">
            <div class="row screen-65 align-items-center justify-content-center p-100px-tb">
                <div class="col-lg-10 text-center">
                    <h6 class="white-color-black font-w-500">{{$set->title}}</h6>
                    <h1 class="display-4 black-color m-20px-b">{{$title}}</h1>
                </div>
            </div>
        </div>
        <div id="jarallax-container-0" style="position: absolute; top: 0px; left: 0px; width: 100%; height: 100%; overflow: hidden; pointer-events: none; z-index: -100;"><div style="background-size: cover; background-image: url(&quot;file:///Users/mac/Documents/Templates/themeforest-cDhKHVPF-raino-multipurpose-responsive-template/template/static/img/1600x900.jpg&quot;); position: absolute; top: 0px; left: 0px; width: 1440px; height: 420px; overflow: hidden; pointer-events: none; margin-top: 31.5px; transform: translate3d(0px, -74.5px, 0px); background-position: 50% 50%; background-repeat: no-repeat no-repeat;">
        </div>
    </div>
</section>
<section class="section gray-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 blog-listing p-40px-r lg-p-15px-r">
                <div class="row">
                    @foreach($posts as $vblog)
                    <div class="col-sm-6">
                        <div class="card blog-grid-1 box-shadow-hover">
                            <div class="blog-img">
                                <a href="{{url('/')}}/single/{{$vblog->id}}/{{str_slug($vblog->title)}}">
                                    <img src="{{url('/')}}/asset/thumbnails/{{$vblog->image}}" title="" alt="">
                                </a>
                                <span class="date">{{date("j", strtotime($vblog->created_at))}}<span>{{date("M", strtotime($vblog->created_at))}}</span></span>
                            </div>
                            <div class="card-body blog-info">
                                <h5>
                                    <a href="{{url('/')}}/single/{{$vblog->id}}/{{str_slug($vblog->title)}}">{!!  str_limit($vblog->title, 40);!!}..</a>
                                </h5>
                                <p class="m-0px">{!!  str_limit($vblog->details, 80);!!}</p>
                                <div class="btn-bar">
                                    <a class="m-link-theme" href="{{url('/')}}/single/{{$vblog->id}}/{{str_slug($vblog->title)}}">Read more</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    {{$posts->render()}}
                </div>
            </div>
            @include('partials.sidebar')
        </div>
    </div>
</section>
@stop
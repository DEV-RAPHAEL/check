@extends('layout')
@section('css')

@stop
@section('content')
<section class="effect-section gray-bg">
    <div class="container">
        <div class="row full-screen align-items-center lg-p-100px-t">
            <div class="col-lg-6 m-15px-tb">
                <h1 class="display-4 m-20px-b">{{__('Contact Us')}}</h1>
                <p class="lead m-35px-b">{{$set->title}}</p>

            </div>
            <div class="col-lg-6 m-15px-tb">
                <img class="max-width-120" src="{{url('/')}}/asset/images/section13_1610838957.png" title="" alt="">
            </div>
        </div>
    </div>
</section>
<section class="section bg-no-repeat bg-right-center" id="contact">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 m-15px-tb">
                <div class="row md-m-25px-b m-40px-b">
                    <div class="col-lg-12">
                        <h3 class="h1 m-15px-b">{{__('Need a hand?')}}</h3>
                        <p class="m-0px font-2">{{__('We are always open and we welcome and questions you have for our team. If you wish to get in touch, please fill out the form below. Someone from our team will get back to you shortly.')}}</p>
                    </div>
                </div>
                <form class="rd-mailform" method="post" action="{{route('contact-submit')}}">
                    @csrf
                    <div class="form-row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <input  type="text" name="name" placeholder="Rachel Roth" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <input  type="email" name="email" placeholder="name@example.com"  class="form-control">
                            </div>
                        </div>                        
                        <div class="col-sm-12">
                            <div class="form-group">
                                <input  type="number" name="mobile" placeholder="12345678987"  class="form-control">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <textarea class="form-control" name="message" rows="3" placeholder="Hi there, I would like to ..."></textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <button class="m-btn m-btn-dark m-btn-radius" type="submit" name="send">{{__('Get Started')}}</button>
                        </div>
                    </div>
                </form>
                <div class="h1 theme-color"></div>
                <div class="media align-items-center p-10px-tb">
                    <div class="icon-40 theme-bg-alt border-radius-50 theme-color">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="media-body p-15px-l">
                        <h6 class="h4 m-0px">{{$set->mobile}}</h6>
                    </div>
                </div>                
                <div class="media align-items-center p-10px-tb">
                    <div class="icon-40 theme-bg-alt border-radius-50 theme-color">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="media-body p-15px-l">
                        <h6 class="h4 m-0px">{{$set->email}}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@stop
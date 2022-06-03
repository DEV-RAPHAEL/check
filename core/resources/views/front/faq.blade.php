@extends('layout')
@section('css')

@stop
@section('content')
<section class="effect-section gray-bg">
    <div class="container">
        <div class="row full-screen align-items-center lg-p-100px-t">
            <div class="col-lg-6 m-15px-tb">
                <h1 class="display-4 m-20px-b">{{__('Frequesntly Asked Questions')}}</h1>
                <p class="lead m-35px-b">{{$set->title}}</p>

            </div>
            <div class="col-lg-6 m-15px-tb">
                <img class="max-width-120" src="{{url('/')}}/asset/images/section14_1610838968.png" title="" alt="">
            </div>
        </div>
    </div>
</section>
<section class="section bg-no-repeat bg-right-center" id="contact">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 m-15px-tb ml-auto" id="faq">
                <div class="accordion accordion-08 p10 border-radius-15">
                    @foreach($faq as $vfaq)
                    <div class="acco-group">
                        <a href="#" class="acco-heading">{{$vfaq->question}}</a>
                        <div class="acco-des">{!!$vfaq->answer!!}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@stop
@extends('loginlayout')

@section('content')

<div class="main-content">
    <!-- Header -->
    <div class="header py-7 py-lg-5 pt-lg-9">
      <div class="container">
        <div class="header-body text-center mb-7">
          <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-8 px-5">
              <div class="card-profile-image mb-5">
                <img src="{{url('/')}}/asset/{{$logo->image_link}}" class="logo">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Page content -->
    <div class="container mt--8 pb-5 mb-0">
      <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
          <div class="card border-0 mb-5">
            <div class="card-body pt-7 px-5">
              <div class="text-center text-dark mb-5">
                <h3 class="text-dark font-weight-bolder">{{__('Email verification')}}</h3>
                <small>{{__('Verify your Email')}}, <span class="text-muted"><a href="{{route('user.send-emailVcode')}}">{{__('Resend email')}}</a></span></small>
              </div>
              <form role="form" action="{{ route('user.email-verify')}}" method="post">
                @csrf
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text text-future"><i class="fad fa-unlock"></i></span>
                    </div>
                    <input type="hidden"  name="id" value="{{Auth::guard('user')->user()->id}}">
                    <input class="form-control" placeholder="{{ __('Code') }}" type="text" name="email_code" required>
                  </div>
                </div>
                <div class="text-center">
                  <button type="submit" class="btn btn-primary my-4 btn-block">{{__('Verify')}}</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
@stop
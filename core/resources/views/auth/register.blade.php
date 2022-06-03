@extends('loginlayout')

@section('content')
<div class="main-content">
    <!-- Header -->
    <div class="header py-5 pt-6">
      <div class="container">
        <div class="header-body text-center mb-7">
        </div>
      </div>
    </div>
    <!-- Page content -->
    <div class="container mt--8 pb-5">
      <div class="row justify-content-center">
        <div class="col-lg-7 col-md-7">
          <div class="card border-0 mb-0">
            <div class="card-body px-lg-5 py-lg-5">
              <div class="text-center text-dark mb-5">
                <h3 class="text-dark font-weight-bolder">{{ __('Sign Up') }}</h3>
                <small>{{$set->title}}</small>
              </div>
              <form role="form" action="{{route('submitregister')}}" method="post">
                @csrf
                <div class="form-group mb-3">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fad fa-briefcase"></i></span>
                    </div>
                    <input class="form-control" placeholder="{{__('Business Name')}}" type="text" name="business_name" required>
                  </div>
                  @if ($errors->has('business_name'))
                    <span class="text-xs text-uppercase">{{$errors->first('business_name')}}</span>
                  @endif
                </div>
                <div class="form-group row">
                  <div class="col-lg-12">
                    <div class="row">
                        <div class="col-6">
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fad fa-user"></i></span>
                            </div>
                            <input class="form-control" placeholder="{{__('First Name')}}" type="text" name="first_name" required>
                          </div>
                        </div>      
                        <div class="col-6">
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fad fa-user"></i></span>
                            </div>
                            <input class="form-control" placeholder="{{__('Last Name')}}" type="text" name="last_name" required>
                          </div>
                        </div>
                    </div>
                  </div>
                </div>                
                <div class="form-group row">
                  <div class="col-lg-12">
                    <div class="row">
                        <div class="col-6">
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fad fa-envelope"></i></span>
                            </div>
                            <input class="form-control" placeholder="{{ __('Email')}}" type="email" name="email" required>
                          </div>
                          @if ($errors->has('email'))
                            <span class="text-xs text-uppercase">{{$errors->first('email')}}</span>
                          @endif
                        </div>      
                        <div class="col-6">
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fad fa-phone-alt"></i></span>
                            </div>
                            <input class="form-control" placeholder="{{ __('Mobile')}}" type="number" name="phone" required>
                          </div>
                          @if ($errors->has('phone'))
                            <span class="text-xs text-uppercase">{{$errors->first('phone')}}</span>
                          @endif
                        </div>
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-lg-12">
                    <select class="form-control select" name="country" required>
                        <option value="">{{__('Select Country')}}</option> 
                          @foreach($country as $val)
                            <option value="{{$val->country_id}}">{{$val->real['nicename']}}</option>
                          @endforeach
                    </select>
                  </div>
                </div>                                              
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fad fa-unlock"></i></span>
                    </div>
                    <input class="form-control" id="new_password" placeholder="{{ __('Password')}}" type="password" name="password" required>
                  </div>
                  <span class="text-xs text-uppercase" id="result"></span>
                </div>
                <div class="custom-control custom-control-alternative custom-checkbox">
                  <input class="custom-control-input" id=" customCheckLogin" type="checkbox" required>
                  <label class="custom-control-label" for=" customCheckLogin">
                    <span class="text-muted">Agree to <a href="{{route('terms')}}">Terms & Conditions</a> @if($set->stripe_connect==1) and the <a href="https://stripe.com/connect-account/legal/full">Stripe Connected Account Agreement</a>@endif</span>
                  </label>
                </div>
                @if($set->recaptcha==1)
                  {!! app('captcha')->display() !!}
                  @if ($errors->has('g-recaptcha-response'))
                      <span class="help-block">
                          {{ $errors->first('g-recaptcha-response') }}
                      </span>
                  @endif
                @endif
                <div class="text-center">
                  <button type="submit" class="btn btn-neutral btn-block my-4 text-uppercase"  id="update_password">{{__('Create an Account')}}</button>
                  <div class="loginSignUpSeparator"><span class="textInSeparator">or</span></div>
                  <a href="{{route('login')}}" class="btn btn-primary btn-block my-0 text-uppercase">{{__('Got an Account?')}}</a>
                </div>
              </form>
            </div>
          </div>

        </div>
      </div>
    </div>
@stop
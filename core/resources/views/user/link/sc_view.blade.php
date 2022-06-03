@extends('paymentlayout')

@section('content')
<div class="main-content">
  <div class="header py-7 py-lg-8 pt-lg-1">
    <div class="container">
      <div class="header-body text-center mb-7">
        <div class="row justify-content-center">
          <div class="col-xl-5 col-lg-6 col-md-8 px-5">
            <div class="card-profile-image mb-5">
                <img src="{{url('/')}}/asset/profile/{{$merchant->image}}" class="">
            </div>
            <h3 class="text-default font-weight-bolder text-uppercase">{{$link->name}}</h3> 
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="container mt--8 pb-5 mb-0">
    <div class="row justify-content-center">
      <div class="col-lg-5 col-md-7">
        <div class="text-center text-dark mb-2">
            <p>{{$link->description}}</p>
        </div>
        <form action="{{ route('send.single')}}" method="post" id="payment-form">
          @csrf
          @if($link->amount==null)
            <div class="form-group">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text text-future">{{$currency->symbol}}</span>
                </div>
                <input class="form-control" placeholder="0.00" type="number" name="amount" required>
              </div>
            </div>
          @else
            <div class="form-group">
              <div class="input-group input-group-alternative">
                <div class="input-group-prepend">
                  <span class="input-group-text text-future">{{$currency->symbol}}</span>
                </div>
                <input class="form-control" readonly type="number" name="amount" value="{{$link->amount}}">
              </div>
            </div>
          @endif
          <input type="hidden" value="{{$link->ref_id}}" name="link">
          @if(Session::get('pay-type')=='account')  
            <input type="hidden" value="account" name="type"> 
            <div class="text-center">
              @if (Auth::guard('user')->check())
                  @csrf
                    <h4 class="mb-1">Account Balance</h4>
                    <h1 class="mb-3 text-muted font-weight-bolder">{{$currency->symbol.number_format($user->balance, 2, '.', '')}}</h1>
                    <button type="submit" class="btn btn-neutral btn-block my-4"><i class="fad fa-external-link"></i> Pay now</button>
              @else
                @php Session::put('oldLink', url()->current()); @endphp
                <h3 class="mb-3 text-muted font-weight-bolder">Login to make payment</h3>
                <a href="{{route('login')}}" class="btn btn-neutral btn-block my-4"><i class="fad fa-sign-in"></i> Login</a>
              @endif 
            </div>
          @elseif(Session::get('pay-type')=='card')  
            @if (!Auth::guard('user')->check())
              <div class="form-group row">                                  
                <div class="col-xs-12 col-md-12 form-group required">
                  <input type="email" class="form-control bg-white" name="email" placeholder="Email Address" autocomplete="off" required/>
                </div>
                <div class="col form-group required">
                  <input type="text" class="form-control bg-white" name="first_name" placeholder="First Name" required/>
                </div>                                  
                <div class="col form-group required">
                  <input type="text" class="form-control bg-white" name="last_name" placeholder="Last Name" required/>
                </div>
              </div> 
            @endif
            <input type="hidden" value="card" name="type">  
            <div id="card-element"></div>
            <div id="card-errors" role="alert"></div>   
            <div class="text-center mt-5">
              <button type="submit" class="btn btn-neutral btn-block my-4"><i class="fad fa-external-link"></i> {{__('Pay')}}</button>
            </div>
          @endif                                          	 	                
        </form>
        <div class="text-center">
          @if($merchant->facebook!=null)
            <a href="{{$merchant->facebook}}"><i class="sn fab fa-facebook"></i></a>   
          @endif 
          @if($merchant->twitter!=null)                      
            <a href="{{$merchant->twitter}}"><i class="sn fab fa-twitter"></i></a>
          @endif      
          @if($merchant->linkedin!=null)                     
            <a href="{{$merchant->linkedin}}"><i class="sn fab fa-linkedin"></i></a> 
          @endif     
          @if($merchant->instagram!=null)                        
            <a href="{{$merchant->instagram}}"><i class="sn fab fa-instagram"></i></a>   
          @endif 
          @if($merchant->youtube!=null)                          
            <a href="{{$merchant->youtube}}"><i class="sn fab fa-youtube"></i></a>  
          @endif                        
        </div> 
      </div>
    </div>
  </div>
@stop
@extends('paymentlayout')

@section('content')
<div class="main-content">
    <div class="header py-7 py-lg-5 pt-lg-1">
        <div class="container">
            <div class="header-body text-center mb-7">

            </div>
        </div>
    </div>
    <!-- Page content -->
    <div class="container mt--8 pb-5 mb-0">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card bg-white border-0 mb-0">
                    @if($stripe->status==1)
                        <div class="card-header" id="headingOne">
                            <h4 class="h3 mb-0 font-weight-bolder">Card</h4>
                        </div>
                            <div class="card-body">
                                <div class="card-wrapper mb-5"></div>
                                <form action="{{ route('pay.merchant')}}" method="post" id="payment-form">
                                @csrf
                                    <div id="card-element"></div>
                                    <div id="card-errors" role="alert"></div>                 		
                                    <input type="hidden" value="card" name="type"> 
                                    <input type="hidden" name="amount" value="{{$link->amount*$link->quantity}}">
                                    <input type="hidden" value="{{$link->reference}}" name="link"> 	                
                                    <div class="text-center mt-5">
                                        <button type="submit" class="btn btn-neutral btn-block"><i class="fad fa-external-link"></i> {{__('Pay')}}</button><br>
                                    </div>
                                </form>
                            </div>
                        <hr>
                    @endif
                    <div class="card-header">
                        <h4 class="h3 mb-0 font-weight-bolder">Pay with Account</h4>
                    </div>
                    <div class="card-body text-center">
                        @if (Auth::guard('user')->check())
                            <form method="post" role="form" action="{{route('pay.merchant')}}">
                            @csrf
                            <h4 class="mb-0">Account Balance</h4>
                            <h1 class="mb-1 text-muted">{{$currency->symbol.number_format($user->balance, 2, '.', '')}}</h1>
                            <input type="hidden" value="account" name="type">
                            <input type="hidden" value="{{$link->reference}}" name="link">
                            <input type="hidden" name="amount" value="{{$link->amount*$link->quantity}}">
                            <div class="text-center mt-5">
                            <button type="submit" class="btn btn-neutral btn-block"><i class="fad fa-external-link"></i> {{__('Pay')}}</button><br>
                            </div>
                            </form>
                        @else
                            @php Session::put('oldLink', url()->current()); @endphp
                            <h3 class="mb-3 text-muted">Login to make payment</h3>
                            <a href="{{route('login')}}" class="btn btn-neutral btn-block">Login</a>
                        @endif
                        </div>
                    </div>
                </div>
            <div class="col-md-4 order-first-sm">
                <div class="card">
                    <div class="card-body">          
                        <div class="row justify-content-between align-items-center mb-0">
                            <div class="col-6">
                                <h3 class="h3 font-weight-bolder">{{$link->title}}</h3>
                            </div>                            
                            <div class="col-6 text-right">
                                <span class="avatar avatar-castro rounded-circle">
                                    <img src="{{url('/')}}/asset/profile/{{$merchant->image}}" alt="merchant"/>
                                </span>
                            </div>
                        </div>
                        <div class="row justify-content-between align-items-center mb-3">
                            <div class="col-8">
                                <span class="form-text text-xl">{{$currency->symbol}} {{number_format($link->amount*$link->quantity, 2, '.', '')}}</span>
                            </div>
                        </div>
                        <small class="text-muted">by {{$merchant->business_name}} on {{date("h:i:A j, M Y", strtotime($link->created_at))}}</small>
                        <p class="mb-3 text-sm">{{$link->description}}</p>
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
        </div>
    </div>
@stop
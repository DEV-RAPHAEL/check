@extends('paymentlayout')

@section('content')

<div class="main-content">
    <!-- Header -->
    <div class="header py-7 py-lg-6 pt-lg-1">
      <div class="container">
        <div class="header-body text-center mb-7">

        </div>
      </div>
    </div>
    <!-- Page content -->
    <div class="container mt--8 pb-5 mb-0">
      <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <img class="card-img-top" src="{{url('/')}}/asset/profile/{{$link->image}}" alt="Image placeholder">
                <div class="card-body">
                    <h5 class="h3 font-weight-bolder mb-0">Fundraiser for {{$link->name}}</h5>
                    <small class="text-muted">by {{$merchant->business_name}} on {{date("h:i:A j, M Y", strtotime($link->created_at))}}</small>
                    <p class="mb-3 text-sm">{{$link->description}}</p>
                    <form action="{{route('send.donation')}}" method="post" id="payment-form">
                        @csrf
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <div class="input-group">
                                    <span class="input-group-prepend">
                                        <span class="input-group-text">{{$currency->symbol}}</span>
                                    </span>
                                    <input type="number" step="any" class="form-control" name="amount" id="xx" placeholder="0.00" required>
                                </div>
                            </div>
                        </div>  
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <select class="form-control select" name="status" required>
                                    <option value="">{{__('Donate as')}}</option>
                                    <option value="1">{{__('Anonymous')}}</option>
                                    <option value="0">{{__('Display name')}}</option>
                                </select>
                            </div>
                        </div>
                        <input type="hidden" value="{{$link->ref_id}}" name="link"> 
                        @if($donated<$link->amount) 
                            @if(Session::get('pay-type')=='account')  
                                <input type="hidden" value="account" name="type"> 
                                <div class="text-center">
                                @if (Auth::guard('user')->check())
                                    @csrf
                                        <h4 class="mb-1">Account Balance</h4>
                                        <h1 class="mb-3 text-muted font-weight-bolder">{{$currency->symbol.number_format($user->balance)}}</h1>
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
                                <div class="text-center mt-3">
                                <button type="submit" class="btn btn-neutral btn-block my-4"><i class="fad fa-external-link"></i> {{__('Pay')}}</button>
                                </div>
                            @endif   
                        @endif   
                    </form>
                    <div class="text-center mb-3">
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
        <div class="col-md-8">
          <div class="card card-profile bg-white border-0 mb-5">
            <div class="card-body">
                <div class="row justify-content-between align-items-center mb-3">
                    <div class="col-8">
                        <span class="form-text text-xl font-weight-bolder">{{$currency->symbol}} {{number_format($link->amount, 2, '.', '')}} GOAL </span>
                    </div>
                </div>                
                <div class="row justify-content-between align-items-center mb-3">
                    <div class="col">
                        <div class="progress progress-xs mb-0">
                            <div class="progress-bar bg-success" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: {{($donated*100)/$link->amount}}%;"></div>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-between align-items-center mb-5">
                    <div class="col-8">
                        <h3 class="h3 font-weight-bolder mb-0">{{$currency->symbol}} {{number_format($donated, 2, '.', '')}} Raised, Donations ({{count($dd)}})</h3>
                    </div>
                </div>  
                <ul class="list-group list-group-flush list my--3">
                    @foreach($paid as $k=>$val)
                        <li class="list-group-item px-0">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="icon icon-shape text-success rounded-circle bg-white">
                                    <i class="fad fa-gift"></i>
                                </div>
                            </div>
                            <div class="col ml--2">
                            <h4 class="mb-0">
                                @if($val->anonymous==0) 
                                    @if($val->user_id==null)
                                        @php
                                            $fff=App\Models\Transactions::whereref_id($val->ref_id)->first();
                                        @endphp
                                        {{$fff['first_name'].' '.$fff['last_name']}}
                                    @endif
                                    {{$val->user['first_name'].' '.$val->user['last_name']}} 
                                @else 
                                    Anonymous 
                                @endif
                            </h4>
                            <small>{{$currency->symbol.number_format($val->amount, 2, '.', '')}} @ {{date("h:i:A j, M Y", strtotime($val->created_at))}}</small>
                            </div>
                        </div>
                        </li>
                    @endforeach
                </ul>
                <div class="row mt-5">
                    <div class="col-md-12">
                    {{ $paid->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
@stop
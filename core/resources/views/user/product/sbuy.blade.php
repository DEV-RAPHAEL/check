@extends('paymentlayout')

@section('content')

<div class="main-content">
    <div class="header py-7 py-lg-6 pt-lg-1">
      <div class="container">
        <div class="header-body text-center mb-7">
        <div class="row justify-content-center">
          <div class="col-xl-5 col-lg-6 col-md-8 px-5">
            <div class="card-profile-image mb-5">
                <img src="{{url('/')}}/asset/profile/{{$merchant->image}}" class="">
            </div>
          </div>
        </div>
        </div>
      </div>
    </div>
    <div class="container mt--8 pb-5 mb-0">
        <div class="row justify-content-center">     
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-between align-items-center">
                            <div class="col-auto">
                                <span class="form-text text-xl">{{$currency->symbol}} {{number_format($total)}}.00</span>
                                <span class="form-text text-sm text-default">{{$product->name}} by {{$merchant->business_name}}</span>
                            </div>
                        </div>
                        <form action="{{route('check.product')}}" method="post" id="payment-form">
                            @csrf
                                <input type="hidden" id="amount3" value="{{$total}}" name="amount">
                                <input type="hidden" name="product_id" value="{{$product->uniqueid}}">
                                @if (!Auth::guard('user')->check())
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                <input type="text" name="first_name" class="form-control" placeholder="First Name" required>
                                            </div>
                                        </div>                 
                                    </div>    
                                    <div class="col-lg-6">
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                <input type="text" name="last_name" class="form-control" placeholder="Last Name" required>
                                            </div>
                                        </div>                 
                                    </div>  
                                </div>                         
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                <input type="email" name="email" class="form-control" placeholder="Your Email Address" required>
                                            </div>
                                        </div>                 
                                    </div>    
                                    <div class="col-lg-6">
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                <input type="number" inputmode="numeric" name="phone" class="form-control" placeholder="Mobile Number" required>
                                            </div>
                                        </div>                 
                                    </div>  
                                </div> 
                                @endif
                            @if($store->shipping_status==1)
                                @if($store->note_status!=0)
                                    <div class="form-group row">
                                        <div class="col-lg-12">
                                            <textarea type="text" name="note" class="form-control" rows="" placeholder="Delivery Note @if($store->note_status==1)(Optional) @endif" @if($store->note_status==2)required="" @endif></textarea>
                                        </div>
                                    </div>
                                @endif
                            <div class="form-group row">                           
                                <div class="col-lg-6">
                                    <select class="form-control custom-select" name="country" id="country" required>
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <select class="form-control custom-select" name="state" id="state" required>
                                    </select>
                                </div>
                            </div>     
                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <input type="text" name="address" class="form-control" placeholder="Your Address" required>
                                </div> 
                                <div class="col-lg-6">
                                    <input type="text" name="town" class="form-control" placeholder="Town/City" required>
                                </div>
                            </div> 
                            <div class="form-group row">    
                                <label class="col-form-label col-lg-12">{{__('Shipping Fee')}}</label>                       
                                <div class="col-lg-12">
                                    <select class="form-control custom-select" name="shipping" id="ship_fee" required>
                                        @foreach($ship as $fval)
                                        <option value="{{$fval->id}}-{{$fval->amount}}">{{$fval->region}} {{$currency->symbol.$fval->amount}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>  
                            <input type="hidden" id="xship" name="xship"> 
                            <input type="hidden" id="xship_fee" name="shipping_fee"> 
                            <hr>
                                <div class="row justify-content-between align-items-center">
                                    <div class="col">
                                        <span class="text-sm text-default">{{__('Subtotal')}}</span>
                                    </div>
                                    <div class="col-auto">
                                        <span class="text-sm text-default">{{$currency->symbol}}<span id="subtotal3">{{$subtotal}}</span>.00</span>
                                    </div>
                                </div>  
                                <hr>  
                                @if($store->shipping_status==1)                
                                <div class="row justify-content-between align-items-center">
                                    <div class="col">
                                        <span class="text-sm text-default">{{__('Shipping')}}</span>
                                    </div>
                                    <div class="col-auto">
                                        <span class="text-sm text-default">{{__('Flat rate')}}: {{$currency->symbol}}<span id="flat"></span></span>
                                    </div>
                                </div>
                                <hr>
                                @endif
                                <div class="row justify-content-between align-items-center mb-5">
                                    <div class="col">
                                        <span class="text-sm text-default">{{__('Total')}}</span>
                                    </div>
                                    <div class="col-auto">
                                        <span class="text-sm text-default">{{$currency->symbol}}<span id="total3">{{$total}}</span>.00</span>
                                    </div>
                                </div>                                                       
                            @endif
                            @if(Session::get('pay-type')=='account')
                                @if (Auth::guard('user')->check())
                                    <div class="text-center"> 
                                        <h4 class="mb-0">Account Balance</h4>
                                        <h1 class="mb-1 text-muted font-weight-bolder">{{$currency->symbol.number_format($user->balance)}}</h1>
                                        <input type="hidden" value="account" name="type">
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-neutral my-4 btn-block"><i class="fad fa-external-link"></i> {{__('Pay')}}</button>
                                        </div>
                                    </div>
                                @else
                                @php Session::put('oldLink', url()->current()); @endphp
                                <h3 class="mb-3 text-muted font-weight-bolder">Login to make payment</h3>
                                <a href="{{route('login')}}" class="btn btn-neutral btn-block">Login</a>
                                @endif
                            @else
                                <div class="row">                           
                                    <div class="col-lg-12">
                                        <div id="card-element"></div>
                                        <div id="card-errors" role="alert"></div>                  		
                                        <input type="hidden" value="card" name="type">  	                
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-neutral my-4 btn-block"><i class="fad fa-external-link"></i> {{__('Pay')}}</button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
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

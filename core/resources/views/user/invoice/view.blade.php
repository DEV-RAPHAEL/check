@extends('paymentlayout')

@section('content')

<div class="main-content">
    <!-- Header -->
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
    <!-- Page content -->
    <div class="container mt--8 pb-5 mb-0">
      <div class="row justify-content-center">
        <div class="col-lg-10 col-md-7">
          <div class="card card-profile bg-white border-0 mb-5">
            <div class="row justify-content-center">
              <div class="col-lg-3 order-lg-2">
              </div>
            </div>
            <div class="card-header bg-transparent">
                <div class="row align-items-center">
                    <div class="col-8">
                      <a href="javascript:void;" onclick="window.print();" class="btn btn-sm btn-neutral"><i class="fad fa-print"></i> {{__('Print')}}</a>
                    </div>
                    <div class="col-4 text-right">
                      @if($invoice->status==1)
                        <span class="badge badge-success"><i class="fad fa-check"></i> {{__('Paid')}}</span>
                      @elseif($invoice->status==0)
                        <span class="badge badge-danger"><i class="fad fa-spinner"></i> {{__('Pending')}}</span>                    
                      @endif
                    </div>
                </div>
            </div>
            <div class="card-body">
              <div class="row justify-content-between align-items-center">
                <div class="col">
                  <div class="my-4">
                    <span class="surtitle">{{__('From')}} {{$invoice->user->email}}</span><br>
                    <span class="surtitle ">{{__('To')}} {{$invoice->email}}</span>
                  </div>
                </div>
                <div class="col-auto">
                  <div class="my-4">
                  @if($invoice->sent==1)<span class="surtitle "> {{__('Sent on')}} {{date("h:i:A j, M Y", strtotime($invoice->sent_date))}} </span><br>@endif
                    <span class="surtitle ">{{__('Due by')}} {{date("h:i:A j, M Y", strtotime($invoice->due_date))}}</span>
                  </div>
                </div>
              </div>
              <hr>
              <div class="row justify-content-between align-items-center">
                <div class="col">
                  <div class="my-4">
                    <span class="surtitle ">{{__('Invoice item')}}</span><br>
                    <span class="surtitle ">{{__('Quantity')}}</span><br>
                    <span class="surtitle ">{{__('Amount')}}</span><br>
                    @if($invoice->notes!=null)
                    <span class="surtitle ">{{__('Notes')}}</span>
                    @endif
                  </div>
                </div>
                <div class="col-auto">
                  <div class="my-4">
                    <span class="surtitle ">{{$invoice->item}}</span><br>
                    <span class="surtitle ">{{$invoice->quantity}}</span><br>
                    <span class="surtitle ">{{$currency->symbol.$invoice->amount}}</span><br>
                    @if($invoice->notes!=null)
                    <span class="surtitle ">{{$invoice->notes}}</span>
                    @endif
                  </div>
                </div>
              </div>
              <hr>
              <div class="row justify-content-between align-items-center">
                <div class="col">
                  <div class="my-4">
                    <span class="surtitle ">{{__('Sub total')}}</span><br>
                    <span class="surtitle ">{{__('Discount')}}</span></br>
                    <span class="surtitle ">{{__('Tax')}}</span>
                  </div>
                </div>
                <div class="col-auto">
                  <div class="my-4">
                    <span class="surtitle ">{{$currency->symbol.number_format($invoice->amount*$invoice->quantity)}}</span><br>
                    <span class="surtitle ">- {{$currency->symbol.number_format($invoice->amount*$invoice->quantity*$invoice->discount/100)}} ({{$invoice->discount}}%)</span><br>
                    <span class="surtitle ">+ {{$currency->symbol}}{{($invoice->amount*$invoice->quantity*$invoice->tax/100)}} ({{$invoice->tax}}%)</span>
                  </div>
                </div>
              </div>
              <hr>
              <div class="row justify-content-between align-items-center">
                <div class="col">
                  <div class="my-4">
                    <span class="surtitle">{{__('Total')}}</span>
                  </div>
                </div>
                <div class="col-auto">
                  <div class="my-4">
                    <span class="surtitle ">{{$currency->symbol.number_format($total)}}</span>
                  </div>
                </div>
              </div>    
              @if($invoice->status==0)  
                <form role="form" action="{{ route('process.invoice')}}" method="post" id="payment-form"> 
                  @csrf          
                  <input type="hidden" value="{{$invoice->ref_id}}" name="link">
                  @if(Session::get('pay-type')=='account')  
                    @if (Auth::guard('user')->check()) 
                      <input type="hidden" value="account" name="type"> 
                      <div class="text-center">
                        <h4 class="mb-1">Account Balance</h4>
                        <h1 class="mb-3 text-muted font-weight-bolder">{{$currency->symbol.number_format($user->balance)}}</h1>
                        <button type="submit" class="btn btn-neutral btn-block my-4"><i class="fad fa-external-link"></i> Pay now</button>
                      </div>
                    @else
                      @php Session::put('oldLink', url()->current()); @endphp
                      <h3 class="mb-3 text-muted font-weight-bolder text-center">Login to make payment</h3>
                      <a href="{{route('login')}}" class="btn btn-neutral btn-block my-4"><i class="fad fa-sign-in"></i> Login</a>
                    @endif 
                  @elseif(Session::get('pay-type')=='card')  
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
                    <input type="hidden" value="card" name="type">  
                    <div id="card-element"></div>
                    <div id="card-errors" role="alert"></div>   
                    <div class="text-center mt-5">
                      <button type="submit" class="btn btn-neutral btn-block my-4"><i class="fad fa-external-link"></i> {{__('Pay')}}</button>
                    </div>
                  @endif
                </form>
              @endif  
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

@extends('paymentlayout')

@section('content')
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="header py-7 py-lg-6 pt-lg-1">
      <div class="container">
        <div class="header-body text-center mb-7">
          <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-8 px-5">
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="container mt--8 pb-5 mb-0">
      <div class="row justify-content-center">
        <div class="col-lg-10 col-md-7">
          <div class="accordion" id="accordionExample">
            <div class="card bg-white border-0 mb-0">
              @if($stripe->status==1)
              <div class="card-header" id="headingOne">
                <div class="text-left" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                  <h4 class="mb-0 font-weight-bolder">Card</h4>
                </div>
              </div>
              <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                <div class="card-body">
                  <div class="row">                           
                    <div class="col-12">
                      <form action="{{ route('card')}}" method="post" id="payment-form">
                          @csrf
                          <div class="form-group row">
                              <div class="col-md-12">
                                  <div class="input-group">
                                      <div class="input-group-prepend">
                                          <span class="input-group-text">{{$currency->symbol}}</span>
                                      </div>
                                      <input type="number" step="any" class="form-control" name="amount" id="cardamount" onkeyup="cardcharge()" placeholder="0.00" autocomplete="off" required> 
                                      <input type="hidden" value="{{$stripe->charge}}" id="charge"> 
                                  </div>
                              </div>
                          </div>  
                          <div id="card-element"></div>
                          <div id="card-errors" role="alert"></div> 
                          <div class="text-center mt-5">
                          <button type="submit" class="btn btn-neutral btn-block">{{__('Pay')}} <span id="cardresult"></span></button>
                          </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>  
              <hr> 
              @endif          
              @if($adminbank->status==1)
                <div class="card-header" id="headingTwo">
                  <div class="text-left collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="fadse" aria-controls="collapseTwo">
                    <h4 class="mb-0 font-weight-bolder">Transfer</h4>
                  </div>
                </div>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                  <div class="card-body text-center">
                    <h4 class="mb-0 text-primary">{{$adminbank->bank_name}}</h4>
                    <h1 class="mb-1 text-muted font-weight-bolder">{{$adminbank->acct_no}}</h1>
                    <h4 class="mb-2 text-muted">{{$adminbank->name}}</h4>
                    <form method="post" action="{{route('bank_transfersubmit')}}">
                      @csrf
                      <div class="form-group row">
                        <div class="col-lg-8 offset-lg-2">
                          <div class="input-group">
                            <span class="input-group-prepend">
                              <span class="input-group-text">{{$currency->symbol}}</span>
                            </span>
                            <input type="number" step="any" name="amount" max-length="10" class="form-control" required>
                          </div>
                        </div>
                      </div>
                      <div class="text-center">
                        <button type="submit" class="btn btn-neutral btn-block">I'hv Sent Money</button>
                      </div>
                    </form>
                  </div>
                </div>
              @endif
              <hr>
              <div class="card-header" id="headingThree">
                  <div class="text-left collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="fadse" aria-controls="collapseThree">
                    <h4 class="mb-0 font-weight-bolder">Crypto Currency</h4>
                  </div>
              </div>
              <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                <div class="card-body">
                  <form method="post" action="{{ route('crypto')}}">
                    @csrf
                    <div class="form-group row">
                      <div class="col-lg-12">
                        <div class="input-group">
                          <span class="input-group-prepend">
                            <span class="input-group-text">{{$currency->symbol}}</span>
                          </span>
                          <input type="number" step="any" name="amount" max-length="10" class="form-control" required>
                        </div>
                      </div>
                    </div>
                    <div class="form-group row">
                      <div class="col-lg-12">
                        <select class="form-control select" name="crypto" data-dropdown-css-class="bg-primary" data-fouc required>
                          @if($btc->status==1)
                            <option value='505'>Bitcoin</option>
                          @endif
                          @if($eth->status==1)
                            <option value='506'>Ethereum</option>
                          @endif
                        </select>
                      </div>
                    </div>          
                    <div class="text-center">
                      <button type="submit" class="btn btn-neutral btn-block">{{__('Pay')}}</button>
                    </div>
                  </form>
                </div>
              </div>              
              <hr>
              <div class="card-header" id="headingFour">
                  <div class="text-left collapsed" data-toggle="collapse" data-target="#collapseFour" aria-expanded="fadse" aria-controls="collapseFour">
                    <h4 class="mb-0 font-weight-bolder">Other</h4>
                  </div>
              </div>
              <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordionExample">
                <div class="card-body">
                  <form method="post" action="{{ route('others')}}">
                    @csrf
                    <div class="form-group row">
                      <div class="col-lg-12">
                        <div class="input-group">
                          <span class="input-group-prepend">
                            <span class="input-group-text">{{$currency->symbol}}</span>
                          </span>
                          <input type="number" step="any" name="amount" max-length="10" class="form-control" required>
                        </div>
                      </div>
                    </div>
                    <div class="form-group row">
                      <div class="col-lg-12">
                        <select class="form-control select" name="others" required>
                          @if($paypal->status==1)<option value='101'>Paypal</option>@endif
                          @if($paystack->status==1)<option value='107'>Paystack</option>@endif
                          @if($flutter->status==1)<option value='108'>Flutter</option>@endif
                        </select>
                      </div>
                    </div>          
                    <div class="text-center">
                      <button type="submit" class="btn btn-neutral btn-block">{{__('Pay')}}</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
  </div>




@stop
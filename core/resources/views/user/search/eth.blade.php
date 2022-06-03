
@extends('userlayout')

@section('content')
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="row" id="earnings">
          <div class="col-md-4">
              <div class="card">
                <!-- Card body -->
                <div class="card-body">
                  <div class="row align-items-center mb-2">                   
                    <div class="col">
                      <h3 class="h4 mb-0 font-weight-bolder">{{$val->trx}}</h3>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      @if($val->type==2 || $val->type==5)
                      <p class="text-sm mb-0">{{__('Sold')}}: ${{number_format($val->total)}}</p>
                      <p class="text-sm mb-0">{{__('Paid for')}}: {{$currency->symbol.number_format($val->amount)}}</p>
                      @endif
                      @if($val->type==1 || $val->type==4)
                      <p class="text-sm mb-0">{{__('Paid for')}}: ${{number_format($val->amount)}}</p>
                      <p class="text-sm mb-0">{{__('Wallet Address')}}: {{$val->wallet}}</p>
                      @endif
                      <p class="text-sm mb-0">{{__('Rate')}}: {{$currency->symbol.$val->rate}}</p>
                      <p class="text-sm mb-0">{{__('Created')}}: {{date("j M, Y h:i:A", strtotime($val->created_at))}}</p>
                      <p class="text-sm mb-2">{{__('Updated')}}: {{date("j M, Y h:i:A", strtotime($val->updated_at))}}</p>
                      <div class="row align-items-center mb-2">                 
                        <div class="col-12 text-left">
                                              
                            <img src="{{url('/')}}/asset/payment_gateways/ethereum.png" alt="ethereum" style="height:auto; max-width:10%;"/>
                          
                          @if($val->status==0)
                            <span class="badge badge-pill badge-primary">{{__('Pending')}}</span> 
                          @elseif($val->status==1)
                            <span class="badge badge-pill badge-success">{{__('Charge')}}: {{$currency->symbol.number_format($val->charge)}}</span>                      
                            <span class="badge badge-pill badge-success">{{__('Paid out')}}</span>                      
                          @elseif($val->status==2)
                            <span class="badge badge-pill badge-danger">{{__('Declined')}}</span> 
                          @endif                     
                          @if($val->type==1 || $val->type==4)
                            <span class="badge badge-pill badge-info">{{__('Buy')}}</span>
                          @elseif($val->type==2 || $val->type==5)
                            <span class="badge badge-pill badge-success">{{__('Sell')}}</span>                    
                          @endif
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
          </div>
      </div>
@stop
@extends('master')
  @section('content')
    <div class="container-fluid mt--6">
      <div class="content-wrapper">
      @if($admin->id==1)
        <div class="row">
          <div class="col-md-4">
            <div class="card">
              <div class="card-body">
                <div class="d-sm-flex align-item-sm-center flex-sm-nowrap">
                  <div>
                    <h3 class="mb-2">{{__('Users')}}</h3>
                    <ul class="list list-unstyled mb-0">
                      <li><span class="text-default text-sm">{{__('Active/Blocked users')}}: {{$activeusers}}/{{$blockedusers}}</span></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>                      
            <div class="card">
              <div class="card-body">
                <div class="d-sm-flex align-item-sm-center flex-sm-nowrap">
                  <div>
                    <h3 class="mb-2">{{__('Single Charge')}}</h3>
                    <ul class="list list-unstyled mb-0">
                      <li><span class="text-default text-sm">{{__('Amount/Charges')}}: {{$currency->symbol.number_format($sin)}}/{{$currency->symbol.number_format($sinc)}}</span></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>            
            <div class="card">
              <div class="card-body">
                <div class="d-sm-flex align-item-sm-center flex-sm-nowrap">
                  <div>
                    <h3 class="mb-2">{{__('Donations')}}</h3>
                    <ul class="list list-unstyled mb-0">
                      <li><span class="text-default text-sm">{{__('Amount/Charges')}}: {{$currency->symbol.number_format($do)}}/{{$currency->symbol.number_format($doc)}}</span></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>          
          <div class="col-md-4">
            <div class="card">
              <div class="card-body">
                <div class="d-sm-flex align-item-sm-center flex-sm-nowrap">
                  <div>
                    <h3 class="mb-2">{{__('Merchant')}}</h3>
                    <ul class="list list-unstyled mb-0">
                      <li><span class="text-default text-sm">{{__('Amount/Charges:')}} {{$currency->symbol.number_format($mer)}}/{{$currency->symbol.number_format($merc)}}</span></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>            
            <div class="card">
              <div class="card-body">
                <div class="d-sm-flex align-item-sm-center flex-sm-nowrap">
                  <div>
                    <h3 class="mb-2">{{__('Invoice')}}</h3>
                    <ul class="list list-unstyled mb-0">
                      <li><span class="text-default text-sm">{{__('Amount/Charges:')}} {{$currency->symbol.number_format($in)}}/{{$currency->symbol.number_format($inc)}}</span></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>            
            <div class="card">
              <div class="card-body">
                <div class="d-sm-flex align-item-sm-center flex-sm-nowrap">
                  <div>
                    <h3 class="mb-2">{{__('Request Money')}}</h3>
                    <ul class="list list-unstyled mb-0">
                      <li><span class="text-default text-sm">{{__('Amount/Charges:')}} {{$currency->symbol.number_format($req)}}/{{$currency->symbol.number_format($reqc)}}</span></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>   
            <div class="card">
              <div class="card-body">
                <div class="d-sm-flex align-item-sm-center flex-sm-nowrap">
                  <div>
                    <h3 class="mb-2">{{__('Settlement')}}</h3>
                    <ul class="list list-unstyled mb-0">
                      <li><span class="text-default text-sm">{{__('Amount/Charges')}}: {{$currency->symbol.number_format($wd)}}/{{$currency->symbol.number_format($wdc)}}</span></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>                      
          </div>  
          <div class="col-md-4">
            <div class="card">
              <div class="card-body">
                <div class="d-sm-flex align-item-sm-center flex-sm-nowrap">
                  <div>
                    <h3 class="mb-2">{{__('Funding account')}}</h3>
                    <ul class="list list-unstyled mb-0">
                      <li><span class="text-default text-sm">{{__('Amount/Charges:')}} {{$currency->symbol.number_format($de)}}/{{$currency->symbol.number_format($dec)}}</span></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
            <div class="card">
              <div class="card-body">
                <div class="d-sm-flex align-item-sm-center flex-sm-nowrap">
                  <div>
                    <h3 class="mb-2">{{__('Product Order')}}</h3>
                    <ul class="list list-unstyled mb-0">
                      <li><span class="text-default text-sm">{{__('Amount/Charges:')}} {{$currency->symbol.number_format($or)}}/{{$currency->symbol.number_format($orc)}}</span></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
            <div class="card">
              <div class="card-body">
                <div class="d-sm-flex align-item-sm-center flex-sm-nowrap">
                  <div>
                    <h3 class="mb-2">{{__('Transfers')}}</h3>
                    <ul class="list list-unstyled mb-0">
                      <li><span class="text-default text-sm">{{__('Amount/Charges:')}} {{$currency->symbol.number_format($tran)}}/{{$currency->symbol.number_format($tranc)}}</span></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div> 
          </div>        

  @endif
          @stop
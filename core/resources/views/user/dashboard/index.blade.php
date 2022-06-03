@extends('userlayout')

@section('content')
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="row"> 
      <div class="col-lg-8">
        <div class="row"> 
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body">
                <h4 class="font-weight-bolder text-gray"><i class="fad fa-undo-alt"></i> {{__('Earning Log')}}</h5>
                @if(count($history)>0)
                <canvas id="myChart" width="80%" height="50%"></canvas>
                @else
                  <div class="text-center mb-5 mt-8">
                    <div class="mb-3">
                      <img src="{{url('/')}}/asset/images/empty.svg">
                    </div>
                    <h3 class="text-dark">No Earning History</h3>
                    <p class="text-dark text-sm card-text">We couldn't find any earning log to this account</p>
                  </div>
                @endif
              </div>     
            </div>     
          </div>                  
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body">
              <h4 class="font-weight-bolder">{{__('API Documentation')}}</h4>
                <p class="text-gray mb-1">Our documentation contains what you need to integrate {{$set->site_name}} in your website.</p>
                <a href="{{route('user.merchant-documentation')}}" class="btn btn-sm btn-neutral mb-5"><i class="fad fa-file-alt"></i> {{__('Go to Docs')}}</a> 
                <h4 class="mb-2 font-weight-bolder">{{__('Your Keys')}}</h4>
                <div class="mb-3">
                  <span class="text-gray mb-3">Also available in</span> <a href="{{route('user.api')}}">Settings > API Keys</a>
                </div>
                <div class="form-group row">
                  <div class="col-lg-12">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text text-xs text-uppercase">{{__('Public key')}}</span>
                      </div> 
                      <input type="text" name="public_key" class="form-control" placeholder="Public key" value="{{$user->public_key}}">   
                      <div class="input-group-prepend bg-gray">
                        <span class="input-group-text btn-icon-clipboard" data-clipboard-text="{{$user->public_key}}" title="Copy"><i class="fad fa-clipboard"></i></span>
                      </div> 
                    </div>
                  </div>
                </div>                
                <div class="form-group row">
                  <div class="col-lg-12">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text text-xs text-uppercase">{{__('Secret key')}}</span>
                      </div> 
                      <input type="text" name="secret_key" class="form-control" placeholder="Secret key" value="{{$user->secret_key}}">   
                      <div class="input-group-prepend bg-gray">
                        <span class="input-group-text btn-icon-clipboard" data-clipboard-text="{{$user->secret_key}}" title="Copy"><i class="fad fa-clipboard"></i></span>
                      </div> 
                    </div> 
                  </div>
                </div>
              </div>              
            </div>              
          </div>              
        </div> 
      </div> 
      <div class="col-lg-4"> 
        <div class="row align-items-center text-center">
          <div class="col-12 mt-5">   
            <h5 class="text-gray mb-3 h4"><i class="fad fa-sack-dollar"></i> {{__('Revenue')}}</h5>
            <h5 class="mb-1 h2">{{$currency->name}} {{number_format($revenue, 2, '.', '')}}</h5>
            <hr>
          </div>          
          <div class="col-12 mt-5">   
            <h5 class="text-gray mb-3 h4"><i class="fad fa-cart-plus"></i> {{__('Total Payout')}}</h5>
            <h5 class="mb-1 h2">{{$currency->name}} {{number_format($t_payout, 2, '.', '')}}</h5>
            @if($user->business_level==1)
              <p class="text-gray mb-3">{{number_format($t_payout/$set->withdraw_limit*100, 2, '.', '')}}% of limit</p>
              @if($user->kyc_status==0)
              <a href="{{route('user.compliance')}}" class="btn btn-sm btn-neutral"><i class="fad fa-arrow-up"></i>  {{__('Upgrade Account')}}</a> 
              @endif
            @elseif($user->business_level==2)
              <p class="text-gray mb-3">{{number_format($t_payout/$set->starter_limit*100, 2, '.', '')}}% of limit</p>
              @if($user->kyc_status==0)
              <a href="{{route('user.compliance')}}" class="btn btn-sm btn-neutral"><i class="fad fa-arrow-up"></i>  {{__('Upgrade Account')}}</a> 
              @endif
            @elseif($user->business_level==3)
            <p class="text-gray mb-3">No limit</p>
            @endif
            <hr>
          </div>          
          <div class="col-12 mt-5">   
            <h5 class="text-gray mb-3 h4"><i class="fad fa-calendar"></i> {{__('Next Payout')}}</h5>
            <h5 class="mb-2 h2">{{$currency->name}} {{number_format($n_payout, 2, '.', '')}}</h5>
            <p class="text-gray mb-3">Due {{date("Y/m/d", strtotime($set->next_settlement))}}</p>
            <a href="{{route('user.withdraw')}}" class="btn btn-sm btn-neutral"><i class="fad fa-history"></i> {{__('Past Payouts')}}</a> 
          </div>
        </div>
      </div>
    </div>
@stop
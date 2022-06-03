@extends('userlayout')

@section('content')
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-12">
        <div class="row">  
            <div class="col-md-6">
              <div class="card bg-white">
                <!-- Card body -->
                <div class="card-body">
                  <div class="row">
                    <div class="col-8">
                      <!-- Title -->
                      <h5 class="h4 mb-0 font-weight-bolder">{{$val->ref_id}}</h5>
                    </div>
                    <div class="col-4 text-right">
                        @if($val->status==0 && $val->email==$user['email'])
                        <a href="{{url('/')}}/user/send_money/{{$val->confirm}}" class="btn btn-sm btn-success" title="Mark as received"><i class="fa fa-check"></i> {{__('Send')}}</a>
                        @endif
                    </div>
                  </div>
                  <div class="row">
                      <div class="col">
                        <p class="text-sm mb-0">{{__('Amount')}}: {{$currency->symbol.number_format($val->amount)}}</p>
                        @if($val->user_id==$user->id)
                        <p class="text-sm mb-0">{{__('To')}}: {{$val->email}}</p>
                        @else
                        <p class="text-sm mb-0">{{__('From')}}: {{$val->receiver['email']}}</p>
                        @endif
                        <p class="text-sm mb-2">{{__('Date')}}: {{date("Y/m/d h:i:A", strtotime($val->created_at))}}</p>
                        @if($val->status==1)
                          @if($val->user_id==$user->id)
                          <span class="badge badge-pill badge-primary">Charge: {{$currency->symbol.number_format($val->charge)}}</span>
                          @endif
                          <span class="badge badge-pill badge-success"><i class="fa fa-check"></i> {{__('Confirmed')}}</span>
                        @elseif($val->status==0)
                          <span class="badge badge-pill badge-danger"><i class="fa fa-spinner"></i> {{__('Pending')}}</span>                        
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
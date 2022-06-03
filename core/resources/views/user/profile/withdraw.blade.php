@extends('userlayout')

@section('content')
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="row align-items-center py-4">
      <div class="col-lg-6 col-7">
        <h6 class="h2 d-inline-block mb-0">{{__('Payout')}}</h6>
      </div>
      <div class="col-lg-6 col-5 text-right">
        <a data-toggle="modal" data-target="#modal-formx" href="" class="btn btn-sm btn-neutral"><i class="fad fa-plus"></i> {{__('Withdraw Request')}}</a>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="modal fade" id="modal-formx" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h3 class="mb-0 h3 font-weight-bolder">{{__('Create Payout Request')}}</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form action="{{route('withdraw.submit')}}" method="post">
                  @csrf
                  <div class="form-group row">
                    <div class="col-lg-12">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">{{$currency->symbol}}</span>
                        </div>
                        <input type="number" step="any" name="amount" id="amounttransfer3" class="form-control" placeholder="0.00" onkeyup="withdrawcharge()" max="{{$user->balance}}" required>
                        <input type="hidden" value="{{$set->withdraw_charge}}" id="chargetransfer3">
                        <input type="hidden" value="{{$set->withdraw_chargep}}" id="chargetransferx">
                      </div>
                      <span class="form-text text-xs">Withdraw charge is {{$set->withdraw_charge}}% + {{$currency->symbol.$set->withdraw_chargep}}, 
                        @if($user->business_level==1)
                        & Maximum withdrawal is {{$currency->symbol.number_format($set->withdraw_limit)}} for an Unverified Business. 
                        @elseif($user->business_level==2)
                        & Maximum withdrawal is {{$currency->symbol.number_format($set->starter_limit)}} for a Startup Business.
                        @elseif($user->business_level==3)
                        & Maximum withdrawal is unlimited for a Registered business.
                        @endif
                        If there is an active subaccount, all charges will be taken from main account.
                      </span>
                    </div>
                  </div>           
                  <div class="text-right">
                    <button type="submit" class="btn btn-neutral btn-block">{{__('Request Payout')}} <span id="resulttransfer3"></span></button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div> 
      </div>
    </div>
    <div class="row">
      <div class="col-md-8">
        <div class="row">  
        @if(count($withdraw)>0) 
          @foreach($withdraw as $k=>$val)
            <div class="col-md-6">
              <div class="card bg-white">
                <!-- Card body -->
                <div class="card-body">
                  <div class="row">
                    <div class="col-6">
                      <!-- Title -->
                      <h5 class="h4 mb-1 font-weight-bolder">{{$val->reference}}</h5>
                    </div>
                  </div>
                  <div class="row">
                      <div class="col">
                        <p class="text-sm mb-0">{{__('Amount')}}: {{$currency->symbol.number_format($val->amount, 2, '.', '')}}</p>
                        @if($val->type==2)
                        <p class="text-sm mb-0">{{__('Sub account')}}: @if($val->sub_id==null) [Deleted] @else {{$val->sub->name}} @endif</p>
                        @elseif($val->type==1)
                        @php $bank=App\Models\Banksupported::whereid($val->dbank->bank_id)->first(); @endphp
                        <p class="text-sm mb-0">{{__('Bank')}}: {{$bank->name.' - '.$val->dbank->acct_no}}</p>
                        @endif
                        @if($val->status==1)
                        <p class="text-sm mb-0">{{__('Settled')}}: {{date("Y/m/d", strtotime($val->next_settlement))}}</p>                        
                        @else
                        <p class="text-sm mb-0">{{__('Next Settlement')}}: @if($val->status==0){{date("Y/m/d", strtotime($val->next_settlement))}} @else - @endif</p>
                        @endif
                        <p class="text-sm mb-2">{{__('Date')}}: {{date("Y/m/d h:i:A", strtotime($val->created_at))}}</p>
                        @if($val->type==2)
                          <span class="badge badge-pill badge-primary"><i class="fad fa-user"></i> Sub Account</span>                        
                        @elseif($val->type==1)
                          <span class="badge badge-pill badge-primary"><i class="fad fa-user"></i> Main </span>
                        @endif                        
                        @if($val->status==1)
                          @if($val->type==1)
                            <span class="badge badge-pill badge-primary">{{__('Charge')}}: {{$currency->symbol.number_format($val->charge, 2, '.', '')}}</span>
                          @endif
                        @endif
                        @if($val->status==1)
                          <span class="badge badge-pill badge-success"><i class="fad fa-check"></i> {{__('Paid out')}}</span>
                        @elseif($val->status==0)
                          <span class="badge badge-pill badge-danger"><i class="fad fa-spinner"></i>  {{__('Pending')}}</span>                        
                        @elseif($val->status==2)
                          <span class="badge badge-pill badge-info"><i class="fad fa-ban"></i> {{__('Declined')}}</span>
                        @endif
                      </div>
                    </div>
                </div>
              </div>
            </div> 
          @endforeach
        @else
          <div class="col-md-12 mb-5">
            <div class="text-center mt-8">
              <div class="mb-3">
                <img src="{{url('/')}}/asset/images/empty.svg">
              </div>
              <h3 class="text-dark">No Payout</h3>
              <p class="text-dark text-sm card-text">We couldn't find any payouts money request to this account</p>
            </div>
          </div>
        @endif
        </div> 
        <div class="row">
          <div class="col-md-12">
          {{ $withdraw->links('pagination::bootstrap-4') }}
          </div>
        </div>
      </div> 
      <div class="col-md-4">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-auto">
                <div class="icon icon-shape text-white rounded-circle bg-dash">
                  <i class="fad fa-calendar-alt text-primary"></i>
                </div>
              </div>
              <div class="col">
                <h3 class="mb-2 font-weight-bolder">{{__('Next Settlement')}}</h3>
                <ul class="list list-unstyled mb-0">
                  <li><span class="text-default text-sm">{{date("Y/m/d", strtotime($set->next_settlement))}}</span></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="card">
          <div class="card-body">
            
            <div class="row align-items-center">
              <div class="col text-center">
                <h4 class="mb-4 text-primary font-weight-bolder">
                {{__('Statistics')}}
                </h4>
                <span class="text-sm text-dark mb-0"><i class="fa fa-google-wallet"></i> {{__('Received')}}</span><br>
                <span class="text-xl text-dark mb-0">{{$currency->name}} {{number_format($received, 2, '.', '')}}</span><br>
                <hr>
              </div>
            </div>
            <div class="row align-items-center">
              <div class="col">
                <div class="my-4">
                  <span class="surtitle">{{__('Pending')}}</span><br>
                  <span class="surtitle ">{{__('Total')}}</span>
                </div>
              </div>
              <div class="col-auto">
                <div class="my-4">
                  <span class="surtitle ">{{$currency->name}} {{number_format($pending, 2, '.', '')}}</span><br>
                  <span class="surtitle ">{{$currency->name}} {{number_format($total, 2, '.', '')}}</span>
                </div>
              </div>
            </div>

          </div>
        </div>
    </div>

@stop
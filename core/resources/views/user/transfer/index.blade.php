@extends('userlayout')

@section('content')
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="row align-items-center py-4">
      <div class="col-lg-6 col-7">
        <h6 class="h2 d-inline-block mb-0">{{__('Transfer Money')}}</h6>
      </div>
      <div class="col-lg-6 col-5 text-right">
        <a data-toggle="modal" data-target="#modal-formx" href="" class="btn btn-sm btn-neutral"><i class="fad fa-plus"></i> {{__('Send Money')}}</a> 
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="modal fade" id="modal-formx" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h3 class="mb-0 h4 font-weight-bolder">{{__('Transfer money')}}</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form action="{{route('submit.transfer')}}" method="post" id="modal-details">
                  @csrf
                  <div class="form-group row">
                    <div class="col-lg-12">
                        <input type="email" name="email" class="form-control" placeholder="Email address" required>
                        <span class="form-text text-xs">Transfer charge is {{$set->transfer_charge}}% + {{$currency->symbol.$set->transfer_chargep}} per transaction, If user is not a member of {{$set->site_name}}, registration will be required to claim money. Money will be refunded within 5 days if user does not claim money.</span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-form-label col-lg-12">{{__('Amount')}}</label>
                    <div class="col-lg-12">
                      <div class="input-group">
                        <span class="input-group-prepend">
                          <span class="input-group-text">{{$currency->symbol}}</span>
                        </span>
                        <input type="number" step="any" class="form-control" name="amount" id="amounttransfer" min="{{$set->min_transfer}}"  onkeyup="transfercharge()" required>
                        <input type="hidden" value="{{$set->transfer_charge}}" id="chargetransfer">
                      </div>
                    </div>
                  </div>                   
                  <div class="text-right">
                    <button type="submit" class="btn btn-neutral btn-block" form="modal-details">{{__('Transfer Money')}} <span id="resulttransfer"></span></button>
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
          @if(count($transfer)>0)  
            @foreach($transfer as $k=>$val)
              <div class="col-md-6">
                <div class="card bg-white">
                  <!-- Card body -->
                  <div class="card-body">
                    <div class="row">
                      <div class="col-8">
                        <!-- Title -->
                        <h5 class="h4 mb-1 font-weight-bolder">{{$val->ref_id}}</h5>
                      </div>
                    </div>
                    <div class="row">
                        <div class="col">
                          @if($val->receiver['id']==$user->id)
                          <p>{{__('Received')}}: {{$currency->symbol.number_format($val->amount, 2, '.', '')}}</p>
                          <p>{{__('From')}}: {{$val->sender['email']}}</p>
                          @elseif($val->sender['id']==$user->id)
                          <p>{{__('Sent')}}: {{$currency->symbol.number_format($val->amount, 2, '.', '')}}</p>
                            @if($val->receiver['id']==null)
                            <p>{{__('To')}}: {{$val->temp}}</p>
                            @else
                            <p>{{__('To')}}: {{$val->receiver['email']}}</p>
                            @endif
                          @endif
                          <p class="text-sm mb-2">{{__('Date')}}: {{date("Y/m/d h:i:A", strtotime($val->created_at))}}</p>
                          @if($val->sender['id']==$user->id) 
                          <span class="badge badge-pill badge-primary">{{__('Charge')}}: {{$currency->symbol.number_format($val->charge, 2, '.', '')}} </span>
                          @endif
                          @if($val->status==1)
                            <span class="badge badge-pill badge-success"><i class="fad fa-check"></i> {{__('Confirmed')}}</span>
                          @elseif($val->status==0)
                            <span class="badge badge-pill badge-danger"><i class="fad fa-spinner"></i> {{__('Pending')}}</span>                        
                          @elseif($val->status==2)
                            <span class="badge badge-pill badge-info"><i class="fad fa-check"></i> {{__('Returned')}}</span>
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
              <h3 class="text-dark">No Transfer Request</h3>
              <p class="text-dark text-sm card-text">We couldn't find any transfer request to this account</p>
            </div>
          </div>
          @endif
        </div> 
        <div class="row">
          <div class="col-md-12">
          {{ $transfer->links('pagination::bootstrap-4') }}
          </div>
        </div>
      </div> 
      <div class="col-md-4">
        <div class="card">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col text-center">
                <h4 class="mb-4 text-primary font-weight-bolder">
                {{__('Statistics')}}
                </h4>
                <span class="text-sm text-dark mb-0"><i class="fa fa-google-wallet"></i> {{__('Sent')}}</span><br>
                <span class="text-xl text-dark mb-0">{{$currency->name}} {{number_format($sent, 2, '.', '')}}</span><br>
                <hr>
              </div>
            </div>
            <div class="row align-items-center">
              <div class="col">
                <div class="my-4">
                  <span class="surtitle">{{__('Pending')}}</span><br>
                  <span class="surtitle">{{__('Returned')}}</span><br>
                  <span class="surtitle ">{{__('Total')}}</span>
                </div>
              </div>
              <div class="col-auto">
                <div class="my-4">
                  <span class="surtitle ">{{$currency->name}} {{number_format($pending, 2, '.', '')}}</span><br>
                  <span class="surtitle ">{{$currency->name}} {{number_format($rebursed, 2, '.', '')}}</span><br>
                  <span class="surtitle ">{{$currency->name}} {{number_format($total, 2, '.', '')}}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        @foreach($received as $k=>$val)
          <div class="card">
            <!-- Card body -->
            <div class="card-body">
              <div class="row">
                <div class="col-8">
                  <h5 class="h3 mb-1 font-weight-bolder">#{{$val->ref_id}}</h5>
                </div>
                <div class="col-4 text-right">
                  @if($val->status==0)
                  <a href="{{url('/')}}/user/received/{{$val->id}}" class="btn btn-sm btn-neutral" title="Mark as received"><i class="fa fa-check"></i> {{__('Confirm')}}</a>
                  @endif
                </div>
              </div>
              <div class="row align-items-center">
                <div class="col">
                  <p>{{__('Email')}}: {{$val->sender['email']}}</p>
                  <p>{{__('Total')}}: {{$currency->symbol.number_format($val->amount, 2, '.', '')}}</p>
                  <p class="text-sm mb-2">{{__('Date')}}: {{date("h:i:A j, M Y", strtotime($val->created_at))}}</p>
                  @if($val->status==1)
                    <span class="badge badge-pill badge-success"><i class="fa fa-check"></i> {{__('Received')}}</span>
                  @elseif($val->status==0)
                    <span class="badge badge-pill badge-danger"><i class="fa fa-spinner"></i> {{__('Pending')}}</span>                       
                  @elseif($val->status==2)
                    <span class="badge badge-pill badge-info"><i class="fa fa-ban"></i> {{__('Returned')}}</span>                    
                  @endif

                </div>
              </div>
            </div>
          </div>
        @endforeach 
      </div>
    </div>
@stop
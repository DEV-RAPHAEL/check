@extends('userlayout')

@section('content')
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="row"> 
    @if(count($orders)>0)  
      @foreach($orders as $k=>$val)
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
                  <p class="text-sm text-dark mb-0">{{__('Product')}}: {{$val->product->name}}</p>
                  @if($val->user_id!=null)
                      <p class="text-sm text-dark mb-0">{{__('Name')}}: {{$val->buyer->first_name}} {{$val->buyer->last_name}}</p>
                      <p class="text-sm text-dark mb-0">{{__('Email')}}: {{$val->buyer->email}}</p>
                      <p class="text-sm text-dark mb-0">{{__('Phone')}}: {{$val->buyer->phone}}</p>
                  @else
                      <p class="text-sm text-dark mb-0">{{__('Name')}}: {{$val->first_name}} {{$val->last_name}}</p>
                      <p class="text-sm text-dark mb-0">{{__('Email')}}: {{$val->email}}</p>
                      <p class="text-sm text-dark mb-0">{{__('Phone')}}: {{$val->phone}}</p>
                  @endif
                  <p class="text-sm text-dark mb-0">{{__('Quantity')}}: {{$val->quantity}}</p> 
                  <p class="text-sm text-dark mb-0">{{__('Country')}}: {{$val->country}}</p>
                  <p class="text-sm text-dark mb-0">{{__('State')}}: {{$val->state}}</p>
                  <p class="text-sm text-dark mb-0">{{__('Town/City')}}: {{$val->town}}</p>
                  <p class="text-sm text-dark mb-0">{{__('Address')}}: {{$val->address}}</p>
                  <p class="text-sm text-dark mb-0">{{__('Region')}}: {{$val->ship['region']}}</p>
                  <p class="text-sm text-dark mb-0">{{__('Shipping fee')}}: @if($val->ship!=null){{$currency->symbol.$val->shipping_fee}} @endif</p>
                  @if($val->product->note_status==1 || $val->product->note_status==2)
                      @if(!empty($val->note))
                          <p class="text-sm text-dark mb-0">Note: {{$val->note}}</p>
                      @endif
                  @endif                                        
                  @if($val->store_id==null)
                      <p class="text-sm text-dark mb-0">Type: Single Purchase</p>
                  @elseif($val->store_id!=null)
                      <p class="text-sm text-dark mb-0">Type: Store Purchase</p>
                  @endif     
                  <p class="text-sm text-dark mb-0">{{__('Amount')}}: {{$currency->symbol}}{{number_format($val->amount, 2, '.', '')}}</p>
                  <p class="text-sm text-dark mb-0">{{__('Total')}}: {{$currency->symbol.number_format($val->amount*$val->quantity+$val->shipping_fee, 2, '.', '')}}</p>
                  <p class="text-sm text-dark mb-2">{{__('Created')}}: {{date("Y/m/d h:i:A", strtotime($val->created_at))}}</p>
                    <span class="badge badge-pill badge-primary">{{__('Fee')}}: {{$currency->symbol.number_format($val->charge, 2, '.', '')}}</span>
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
          <h3 class="text-dark">No Orders</h3>
          <p class="text-dark text-sm card-text">We couldn't find any product order to this account</p>
        </div>
      </div>
    @endif
    </div> 
@stop
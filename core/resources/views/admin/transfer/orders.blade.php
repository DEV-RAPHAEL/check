@extends('master')

@section('content')
<div class="container-fluid mt--6">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0">{{__('Orders')}}</h3>
                    </div>
                    <div class="table-responsive py-4">
                        <table class="table table-flush" id="datatable-buttons">
                            <thead>
                                <tr>
                                    <th>{{__('S/N')}}</th>
                                    <th>{{__('Ref')}}</th>
                                    <th>{{__('Vendor')}}</th>
                                    <th>{{__('Buyer')}}</th>
                                    <th>{{__('Product Name')}}</th>
                                    <th>{{__('Amount')}}</th>
                                    <th>{{__('Total')}}</th>
                                    <th>{{__('Charge')}}</th>
                                    <th>{{__('Quantity')}}</th>
                                    <th>{{__('Region')}}</th>
                                    <th>{{__('Shipping fee')}}</th>
                                    <th>{{__('Address')}}</th>                                                               
                                    <th>{{__('Country')}}</th>                                                               
                                    <th>{{__('State')}}</th>                                                               
                                    <th>{{__('Town')}}</th>                                                               
                                    <th>{{__('Name')}}</th>                                                                                                                             
                                    <th>{{__('Email')}}</th>                                                               
                                    <th>{{__('Phone')}}</th>                                                               
                                    <th>{{__('Type')}}</th>                                                               
                                    <th>{{__('Created')}}</th>
                                    <th>{{__('Updated')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($orders as $k=>$val)
                                <tr>
                                    <td>{{++$k}}.</td>
                                    <td>#{{$val->ref_id}}</td>
                                    <td>{{$val->seller->business_name}}</td>
                                    @if($val->user_id!=null)
                                    <td>{{$val->buyer->first_name.' '.$val->buyer->last_name}}</td>
                                    @else
                                    <td>{{$val->first_name.' '.$val->last_name}}</td>
                                    @endif
                                    <td>{{$val->lala->name}}</td>
                                    <td>{{$currency->symbol.number_format($val->amount, 2, '.', '')}}</td>
                                    <td>{{$currency->symbol.number_format($val->total, 2, '.', '')}}</td>
                                    <td>{{$currency->symbol.number_format($val->charge, 2, '.', '')}}</td>
                                    <td>{{$val->quantity}}</td>
                                    <td>{{__('Region')}}: {{$val->ship['region']}}</td>
                                    <td>{{__('Shipping fee')}}: @if($val->ship!=null){{$currency->symbol.$val->shipping_fee}} @endif</td>
                                    <td>{{$val->address}}</td>                                    
                                    <td>{{$val->country}}</td>                                    
                                    <td>{{$val->state}}</td>                                    
                                    <td>{{$val->town}}</td>   
                                    @if($val->user_id!=null)
                                        <td>{{__('Name')}}: {{$val->buyer->first_name}} {{$val->buyer->last_name}}</td>
                                        <td>{{__('Email')}}: {{$val->buyer->email}}</td>
                                        <td>{{__('Phone')}}: {{$val->buyer->phone}}</td>
                                    @else
                                        <td>{{__('Name')}}: {{$val->first_name}} {{$val->last_name}}</td>
                                        <td>{{__('Email')}}: {{$val->email}}</td>
                                        <td>{{__('Phone')}}: {{$val->phone}}</td>
                                    @endif
                                    @if($val->store_id==null)
                                        <td>Type: Single Purchase</td>
                                    @elseif($val->store_id!=null)
                                        <td>Type: Store Purchase</td>
                                    @endif                                                                   
                                    <td>{{date("Y/m/d h:i:A", strtotime($val->created_at))}}</td>  
                                    <td>{{date("Y/m/d h:i:A", strtotime($val->updated_at))}}</td>                  
                                </tr>
                                @endforeach               
                            </tbody>                    
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@extends('userlayout')

@section('content')
<!-- Page content -->
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="card">
        <div class="card-header header-elements-inline">
            <h3 class="mb-0">{{__('Transactions')}}</h3>
        </div>
        <div class="table-responsive py-4">
        <table class="table table-flush" id="datatable-buttons">
            <thead>
                <tr>
                    <th>{{__('S / N')}}</th>
                    <th>{{__('Name')}}</th>
                    <th>{{__('From')}}</th>
                    <th>{{__('IP Address')}}</th>
                    <th>{{__('Type')}}</th>
                    <th>{{__('Status')}}</th>
                    <th>{{__('Amount')}}</th>
                    <th>{{__('Charge')}}</th>
                    <th>{{__('Reference ID')}}</th>
                    <th>{{__('Payment Type')}}</th>
                    <th>{{__('Created')}}</th>
                    <th>{{__('updated')}}</th>
                </tr>
            </thead>
            <tbody>  
                @foreach($links as $k=>$xval)
                    <tr>
                    <td>{{++$k}}.</td>
                    <td>{{$xval->ddlink['name']}}</td>
                    <td>@if($xval->sender_id!=null) {{$xval->sender['first_name'].' '.$xval->sender['last_name']}} [{{$xval->sender->email}}] @else {{$xval->first_name.' '.$xval->last_name}} [{{$xval->email}}] @endif</td>
                    <td>{{$xval->ip_address}}</td>
                    <td>@if($xval->sender_id==$user->id) Paid @else Received @endif</td>
                    <td>@if($xval->status==0) <span class="badge badge-pill badge-danger">failed</span> @elseif($xval->status==1) <span class="badge badge-pill badge-success">successful</span> @elseif($xval->status==2) refunded @endif</td>
                    <td>{{$currency->symbol.numner_format($xval->amount, 2, '.', '')}}</td>
                    <td>@if($xval->sender_id==$user->id || $xval->charge==null) - @else {{$currency->symbol.number_format($xval->charge)}} @endif</td>
                    <td>{{$xval->ref_id}}</td>
                    <td>{{$xval->payment_type}}</td>
                    <td>{{date("Y/m/d h:i:A", strtotime($xval->created_at))}}</td>
                    <td>{{date("Y/m/d h:i:A", strtotime($xval->updated_at))}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>

@stop
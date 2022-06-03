
@extends('userlayout')

@section('content')
<!-- Page content -->
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="card">
      <div class="card-header">
        <h3 class="mb-0 font-weight-bolder">{{__('Invoice Logs')}}</h3>
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
              <tr>
                <td>1.</td>
                <td>{{$val->inplan['item']}}</td>
                <td>@if($val->sender_id!=null) {{$val->sender->first_name.' '.$val->sender->last_name}} [{{$val->sender->email}}] @else {{$val->first_name.' '.$val->last_name}} [{{$val->email}}] @endif</td>
                <td>{{$val->ip_address}}</td>
                <td>@if($val->sender_id==$user->id) Paid @else Received @endif</td>
                <td>@if($val->status==0) <span class="badge badge-pill badge-danger">failed</span> @elseif($val->status==1) <span class="badge badge-pill badge-success">successful</span> @elseif($val->status==2) refunded @endif</td>
                <td>{{$currency->symbol.$val->amount}}</td>
                <td>@if($val->sender_id==$user->id || $val->charge==null) - @else {{$currency->symbol.$val->charge}} @endif</td>
                <td>{{$val->ref_id}}</td>
                <td>{{$val->payment_type}} @if($val->payment_type=='card') - XXXX XXXX XXXX {{substr($val->card_number, 12)}} @endif</td>
                <td>{{date("Y/m/d h:i:A", strtotime($val->created_at))}}</td>
                <td>{{date("Y/m/d h:i:A", strtotime($val->updated_at))}}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

@stop
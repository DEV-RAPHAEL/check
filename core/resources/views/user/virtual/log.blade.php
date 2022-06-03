
@extends('userlayout')

@section('content')
<!-- Page content -->
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="card">
      <div class="card-header header-elements-inline">
        <h3 class="mb-0 font-weight-bolder">{{__('Transaction History')}}</h3>
      </div>
      <div class="table-responsive py-4">
        <table class="table table-flush" id="datatable-buttons">
          <thead>
            <tr>
              <th>{{__('S / N')}}</th>
              <th>{{__('Amount')}}</th>
              <th>{{__('Description')}}</th>
              <th>{{__('Type')}}</th>
              <th>{{__('Created')}}</th>
            </tr>
          </thead>
          <tbody>  
          @php 
          $item=array();
          $item=json_decode($log, true); 
          @endphp
            @foreach($item['data'] as $k=>$val)
              <tr>
                <td>{{++$k}}.</td>
                <td>
                @if($val['product']=='Card Issuance Fee')
                {{$currency->symbol.number_format($val['amount']*$set->virtual_createcharge+$set->virtual_createchargep, 2, '.', '')}}
                @else
                {{$currency->symbol.number_format($val['amount'], 2, '.', '')}}
                @endif
                </td>
                <td>{{$val['gateway_reference_details']}}</td>
                <td>
                @if($val['indicator']=='C')
                  <span class="badge badge-pill badge-primary">{{__('Credit')}}</span>
                @elseif($val['indicator']=='D')
                  <span class="badge badge-pill badge-primary">{{__('Debit')}}</span>                        
                @endif
                </td>
                <td>{{date("Y/m/d h:i:A", strtotime($val['created_at']))}}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

@stop
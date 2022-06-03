
@extends('master')

@section('content')
@php
  $upd=App\Models\Virtual::all();
  foreach($upd as $trx){
    $data = array("id"=>$trx->card_hash);
    $check = new Laravel\Flutterwave\VirtualCard();
    $getCard = $check->getCard($data);
    $result = $getCard;
    $amo=str_replace( ',', '', $result['data']['amount']);
    if($amo<$trx->amount){
        if($result['data']['is_active']==true){
            $trx->amount=$amo;
            $trx->save();
        }else{
            $trx->amount=0;
            $trx->save();
        }
    }else{
        $trx->amount=$amo;
        $trx->save();
    }
  }
@endphp
<!-- Page content -->
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="card">
      <div class="card-header header-elements-inline">
        <h3 class="mb-0 font-weight-bolder">{{__('Virtual Cards')}}</h3>
      </div>
      <div class="table-responsive py-4">
        <table class="table table-flush" id="datatable-buttons">
          <thead>
            <tr>
              <th>{{__('S / N')}}</th>
              <th>{{__('User')}}</th>
              <th>{{__('Name')}}</th>
              <th>{{__('Card Number')}}</th>
              <th>{{__('Cvv')}}</th>
              <th>{{__('Expiration')}}</th>
              <th>{{__('Type')}}</th>
              <th>{{__('Amount')}}</th>
              <th>{{__('Status')}}</th>
              <th>{{__('Created')}}</th>
              <th>{{__('Updated')}}</th>
              <th class="text-center">{{__('Action')}}</th> 
            </tr>
          </thead>
          <tbody>  
            @foreach($card as $k=>$val)
              <tr>
                <td>{{++$k}}.</td>
                <td><a href="{{url('admin/manage-user')}}/{{$val->user['id']}}">{{$val->user['business_name']}}</a></td>
                <td>{{$val->name_on_card}}</td>
                <td>{{$val->card_pan}}</td>
                <td>{{$val->cvv}}</td>
                <td>{{$val->expiration}}</td>
                <td>{{$val->card_type}}</td>
                <td>{{$currency->symbol.number_format($val->amount, 2, '.', '')}}</td>
                <td>@if($val->status==2) <span class="badge badge-pill badge-danger">Blocked</span> @elseif($val->status==1) <span class="badge badge-pill badge-success">Active</span>@endif</td>
                <td>{{date("Y/m/d h:i:A", strtotime($val->created_at))}}</td>
                <td>{{date("Y/m/d h:i:A", strtotime($val->updated_at))}}</td>
                <td class="text-center">
                    <div class="dropdown">
                        <a class="text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                            <a href="{{route('transactions.vcard', ['id'=>$val->card_hash])}}" class="dropdown-item">{{__('Transactions')}}</a>
                        </div>
                    </div>
                </td> 
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

@stop

@extends('userlayout')

@section('content')
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
              <th>{{__('Name')}}</th>
              <th>{{__('Card Number')}}</th>
              <th>{{__('Cvv')}}</th>
              <th>{{__('Expiration')}}</th>
              <th>{{__('Type')}}</th>
              <th>{{__('Amount')}}</th>
              <th>{{__('Status')}}</th>
              <th>{{__('Reference')}}</th>
              <th>{{__('Created')}}</th>
              <th>{{__('Updated')}}</th>
              <th class="text-center">{{__('Action')}}</th> 
            </tr>
          </thead>
          <tbody>  
              <tr>
                <td>1.</td>
                <td>{{$val->name_on_card}}</td>
                <td>{{$val->card_pan}}</td>
                <td>{{$val->cvv}}</td>
                <td>{{$val->expiration}}</td>
                <td>{{$val->card_type}}</td>
                <td>{{$currency->symbol.number_format($val->amount, 2, '.', '')}}</td>
                <td>@if($val->status==0) <span class="badge badge-pill badge-danger">Terminated</span> @elseif($val->status==1) <span class="badge badge-pill badge-success">Active</span>@endif</td>
                <td>{{$val->ref_id}}</td>
                <td>{{date("Y/m/d h:i:A", strtotime($val->created_at))}}</td>
                <td>{{date("Y/m/d h:i:A", strtotime($val->updated_at))}}</td>
                <td class="text-center">
                    <div class="dropdown">
                        <a class="text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                            <a href="{{route('transactions.virtual', ['id'=>$val->id])}}" class="dropdown-item">{{__('Transactions')}}</a>
                            @if($val->status==1)
                                <a data-toggle="modal" data-target="#modal-formfund" href="" class="dropdown-item">{{__('Fund Card')}}</a>
                                <a href="{{route('terminate.virtual', ['id'=>$val->card_hash])}}" class="dropdown-item">{{__('Terminate')}}</a>
                            @endif
                        </div>
                    </div>
                </td> 
            </tr>
            <div class="modal fade" id="modal-formfund" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
                <div class="modal-dialog modal- modal-dialog-centered" role="document">
                    <div class="modal-content">
                    <div class="modal-body p-0">
                        <div class="card bg-white border-0 mb-0">
                        <div class="card-header">
                            <h3 class="mb-0 font-weight-bolder">{{__('Add Funds to Virtual Card')}}</h3>
                            <p class="form-text text-xs">Charge is {{$set->virtual_charge}}%.</p>
                        </div>
                        <div class="card-body">
                            <form method="post" action="{{route('fund.virtual')}}">
                            @csrf
                            <input type="hidden" name="id" value="{{$val->card_hash}}">
                            <div class="form-group row">
                                <label class="col-form-label col-lg-12">{{__('Amount')}}</label>
                                <div class="col-lg-12">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">{{$currency->symbol}}</span>
                                        </div>
                                        <input type="number" name="amount" class="form-control" max="{{$set->vc_max-$val->amount}}" required>
                                    </div>
                                </div>
                            </div>                 
                            <div class="text-right">
                                <button type="submit" class="btn btn-neutral btn-block my-4">{{__('Fund card')}}</button>
                            </div>
                            </form>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
              </div> 
          </tbody>
        </table>
      </div>
    </div>

@stop
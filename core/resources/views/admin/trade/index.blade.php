@extends('master')

@section('content')
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="card">
        <div class="card-header">
            <h3 class="mb-0">{{__('Bitcoin, Ethereum')}}</h3>
        </div>
        <div class="table-responsive py-4">
            <table class="table table-flush" id="datatable-buttons">
                <thead>
                    <tr>
                        <th>{{__('S/N')}}</th>
                        <th>{{__('Ref')}}</th>
                        <th>{{__('Name')}}</th>
                        <th>{{__('Amount')}}</th>                                                                       
                        <th>{{__('To get')}}</th>                                                                       
                        <th>{{__('Type')}}</th>                                                                       
                        <th>{{__('Status')}}</th>                                                                       
                        <th>{{__('Created')}}</th>
                        <th>{{__('Updated')}}</th>
                        <th scope="col"></th>    
                    </tr>
                </thead>
                <tbody>
                    @foreach($trx as $k=>$val)
                        <tr>
                            <td>{{++$k}}.</td>
                            <td>{{$val->trx}}</td>
                            <td><a href="{{url('admin/manage-user')}}/{{$val->user['id']}}">{{$val->user['business_name']}}</a></td>
                            @if($val->type==2 || $val->type==5)
                            <td>${{number_format($val->amount/$val->rate)}}</td>
                            <td>{{$currency->symbol.number_format($val->amount, 2, '.', '')}}</td>
                            @endif
                            @if($val->type==1 || $val->type==4)                           
                            <td>{{$currency->symbol.number_format($val->total, 2, '.', '')}}</td>
                            <td>${{number_format($val->amount)}}</td>
                            @endif                                    
                            @if($val->type==3)
                            <td>{{$currency->symbol.number_format($val->amount, 2, '.', '')}}</td>
                            @endif
                            <td>          
                                @if($val->type==1)
                                    <span class="badge badge-primary badge-pill">{{__('Buy | Bitcoin')}}</span>
                                @elseif($val->type==2)
                                    <span class="badge badge-primary badge-pill">{{__('Sell | Bitcoin')}}</span>
                                @elseif($val->type==4)
                                    <span class="badge badge-primary badge-pill">{{__('Buy | Ethereum')}}</span>                                
                                @elseif($val->type==5)
                                    <span class="badge badge-primary badge-pill">{{__('Sell | Ethereum')}}</span>                                                        
                                @endif
                            </td>
                            <td>
                            @if($val->status==0)
                                <span class="badge badge-danger badge-pill">{{__('Pending')}}</span>
                            @elseif($val->status==1)
                                <span class="badge badge-success badge-pill">{{__('Paid off')}}</span>                             
                            @elseif($val->status==2)
                                <span class="badge badge-primary badge-pill">{{__('Decliend')}}</span> 
                            @endif
                        </td>
                            <td>{{date("Y/m/d h:i:A", strtotime($val->created_at))}}</td>
                            <td>{{date("Y/m/d h:i:A", strtotime($val->updated_at))}}</td>
                            <td class="text-right">
                                <div class="dropdown">
                                    <a class="text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        @if($val->status==0)
                                            <a class='dropdown-item' href="{{route('trade.approve', ['id' => $val->id])}}">{{__('Approve request')}}</a>
                                            <a class='dropdown-item' href="{{route('trade.decline', ['id' => $val->id])}}">{{__('Decline request')}}</a>
                                        @endif
                                        @if($val->type==1 || $val->type==4)
                                        <a data-toggle="modal" data-target="#wallet{{$val->id}}" href="" class="dropdown-item">{{__('Wallet')}}</a>
                                        @endif
                                        <a data-toggle="modal" data-target="#delete{{$val->id}}" href="" class="dropdown-item">{{__('Delete')}}</a>
                                    </div>
                                </div>
                            </td>                   
                        </tr>
                    @endforeach               
                </tbody>                    
            </table>
        </div>
    </div>
    @foreach($trx as $k=>$val)
    <div class="modal fade" id="wallet{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
        <div class="modal-dialog modal- modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="card bg-white border-0 mb-0">
                        <div class="card-body px-lg-5 py-lg-5">
                            <p class="">{{$val->wallet}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
    <div class="modal fade" id="proof{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
        <div class="modal-dialog modal- modal-dialog-centered modal-md" role="document">
            <div class="castro-fade">
                <div class="modal-body p-0" >
                    <div class=" border-0 mb-0 text-center">
                        <div class="px-lg-5 py-lg-5">
                            <img src="{{url('/')}}/asset/images/{{$val->image}}" class="mb-3 user-profile">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>                                    
    <div class="modal fade" id="comment{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
        <div class="modal-dialog modal- modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="card bg-white border-0 mb-0">
                        <div class="card-body px-lg-5 py-lg-5">
                            <p class="">{{$val->details}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>       
    <div class="modal fade" id="bank{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
        <div class="modal-dialog modal- modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="card bg-white border-0 mb-0">
                        <div class="card-header">
                            <h3 class="mb-0">{{$val->dbank['name']}}</h3>
                        </div>
                        <div class="card-body px-lg-5 py-lg-5">
                            <p class="">{{__('Account number')}}: {{$val->dbank['acct_no']}}</p>
                            <p class="">{{__('Account name')}}:{{$val->dbank['acct_name']}}</p>
                            <p class="">{{__('Iban')}}: {{$val->dbank['iban']}}</p>
                            <p class="">{{__('Swift')}}: {{$val->dbank['swift']}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>                                    
    <div class="modal fade" id="delete{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
        <div class="modal-dialog modal- modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="card bg-white border-0 mb-0">
                        <div class="card-header">
                            <h3 class="mb-0">{{__('Are you sure you want to delete this?')}}</h3>
                        </div>
                        <div class="card-body px-lg-5 py-lg-5 text-right">
                            <button type="button" class="btn btn-neutral btn-sm" data-dismiss="modal">{{__('Close')}}</button>
                            <a  href="{{route('trade.delete', ['id' => $val->id])}}" class="btn btn-danger btn-sm">{{__('Proceed')}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
    @endforeach

@stop
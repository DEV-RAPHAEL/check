@extends('master')

@section('content')
<div class="container-fluid mt--6">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header header-elements-inline">
                        <h3 class="mb-0">{{__('Invoice logs')}}</h3>
                    </div>
                    <div class="table-responsive py-4">
                        <table class="table table-flush" id="datatable-buttons">
                            <thead>
                                <tr>
                                    <th>{{__('S/N')}}</th>
                                    <th>{{__('Ref')}}</th>
                                    <th>{{__('Sender')}}</th>
                                    <th>{{__('Receiver')}}</th>
                                    <th>{{__('Invoice No')}}</th>
                                    <th>{{__('Due')}}</th>
                                    <th>{{__('Send Date')}}</th>
                                    <th>{{__('Tax')}}</th>
                                    <th>{{__('Discount')}}</th>
                                    <th>{{__('Quantity')}}</th>
                                    <th>{{__('Item Name')}}</th>
                                    <th>{{__('Notes')}}</th>
                                    <th>{{__('Amount')}}</th>                                                                       
                                    <th>{{__('Charge')}}</th>                                                                       
                                    <th>{{__('Total')}}</th>                                                                       
                                    <th>{{__('Status')}}</th>
                                    <th>{{__('Sent')}}</th>
                                    <th>{{__('Created')}}</th>
                                    <th>{{__('Updated')}}</th>
                                    <th class="text-center">{{__('Action')}}</th>    
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($invoice as $k=>$val)
                                <tr>
                                    <td>{{++$k}}.</td>
                                    <td>{{$val->ref_id}}</td>
                                    <td>{{$val->sender->business_name}}</td>
                                    <td>{{$val->email}}</td>
                                    <td>{{$val->invoice_no}}</td>
                                    <td>{{$val->due_date}}</td>
                                    <td>{{$val->sent_date}}</td>
                                    <td>{{$val->tax}}%</td>
                                    <td>{{$val->discount}}%</td>
                                    <td>{{$val->quantity}}</td>
                                    <td>{{$val->item}}</td>
                                    <td>{{$val->notes}}</td>
                                    <td>{{$currency->symbol.number_format($val->amount, 2, '.', '')}}</td>
                                    <td>{{$currency->symbol.number_format($val->charge, 2, '.', '')}}</td>
                                    <td>{{$currency->symbol.number_format($val->total, 2, '.', '')}}</td>
                                    <td>
                                        @if($val->status==0)
                                            <span class="badge badge-danger">{{__('Pending')}}</span>
                                        @elseif($val->status==1)
                                            <span class="badge badge-success">{{__('Paid')}}</span>                                        
                                        @endif
                                    </td>                                     
                                    <td>
                                        @if($val->sent==0)
                                            <span class="badge badge-danger">{{__('No')}}</span>
                                        @elseif($val->sent==1)
                                            <span class="badge badge-success">{{__('Yes')}}</span>                                        
                                        @endif
                                    </td>     
                                    <td>{{date("Y/m/d h:i:A", strtotime($val->created_at))}}</td>  
                                    <td>{{date("Y/m/d h:i:A", strtotime($val->updated_at))}}</td>
                                    <td class="text-center">
                                        <div class="">
                                            <div class="dropdown">
                                                <a class="text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                    <a data-toggle="modal" data-target="#delete{{$val->id}}" href="" class="dropdown-item">{{ __('Delete')}}</a>
                                                </div>
                                            </div>
                                        </div> 
                                    </td>               
                                </tr>

                                @endforeach               
                            </tbody>                    
                        </table>
                    </div>
                </div>
                @foreach($invoice as $k=>$val)
                <div class="modal fade" id="delete{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
                    <div class="modal-dialog modal- modal-dialog-centered modal-md" role="document">
                        <div class="modal-content">
                            <div class="modal-body p-0">
                                <div class="card bg-white border-0 mb-0">
                                    <div class="card-header">
                                        <h3 class="mb-0">{{__('Are you sure you want to delete this?')}}</h3>
                                    </div>
                                    <div class="card-body px-lg-5 py-lg-5 text-right">
                                        <button type="button" class="btn btn-neutral btn-sm" data-dismiss="modal">{{ __('Close')}}</button>
                                        <a  href="{{route('invoice.delete', ['id' => $val->id])}}" class="btn btn-danger btn-sm">{{ __('Proceed')}}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@stop
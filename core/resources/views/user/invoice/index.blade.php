
@extends('userlayout')

@section('content')
<!-- Page content -->
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="row align-items-center py-4">
      <div class="col-lg-6 col-7">
        <h6 class="h2 d-inline-block mb-0">{{__('Invoice')}}</h6>
      </div>
      <div class="col-lg-6 col-5 text-right">
        <a href="{{route('user.add-invoice')}}" class="btn btn-sm btn-neutral"><i class="fad fa-plus"></i> {{__('Create an invoice')}}</a> 
      </div>
    </div>
    <div class="row">  
      <div class="col-md-8">
        <div class="row"> 
          @if(count($invoice)>0) 
            @foreach($invoice as $k=>$val)
              <div class="col-md-6">
                <div class="card">
                  <!-- Card body -->
                  <div class="card-body">
                    <div class="row mb-2">
                      <div class="col-4">
                        <p class="text-sm text-dark mb-2"><a class="btn-icon-clipboard" data-clipboard-text="{{route('view.invoice', ['id' => $val->ref_id])}}" title="Copy">{{__('COPY LINK')}} <i class="fad fa-link text-xs"></i></a></p>
                      </div>  
                      <div class="col-8 text-right">
                        <a class="mr-0 text-dark" data-toggle="dropdown" aria-haspopup="true" aria-expanded="fadse">
                          <i class="fad fa-chevron-circle-down"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-left">
                          @if($val->status==0)
                            <a class="dropdown-item" data-toggle="modal" data-target="#edit{{$val->id}}" href="#"><i class="fad fa-pencil"></i>{{__('Edit')}}</a>
                            <a class="dropdown-item" href="{{route('paid.invoice', ['id' => $val->ref_id])}}"><i class="fad fa-check"></i>{{__('Mark as Paid')}}</a>
                            <a class="dropdown-item" href="{{route('reminder.invoice', ['id' => $val->ref_id])}}"><i class="fad fa-sync"></i>{{__('Resend')}}</a>
                          @endif
                          <a class="dropdown-item" data-toggle="modal" data-target="#delete{{$val->id}}" href=""><i class="fad fa-trash"></i>{{__('Delete')}}</a>
                        </div>
                      </div>
                    </div>
                    <div class="row align-items-center">
                      <div class="col">
                        <h5 class="h4 mb-1 font-weight-bolder">{{$val->ref_id}}</h5>
                        <p>{{__('Invoice no')}}: {{$val->invoice_no}}</p>
                        <p>{{__('Name')}}: {{$val->item}}</p>
                        <p>{{__('Recipient')}}: {{$val->email}}</p>
                        <p>{{__('Tax')}}: @if($val->tax==null) 0% @else {{$val->tax}}% @endif</p>
                        <p>{{__('Discount')}}: @if($val->discount==null) 0% @else {{$val->discount}}% @endif</p>
                        <p>{{__('Total')}}: {{$currency->symbol.number_format($val->total, 2, '.', '')}}</p>
                        <p>{{__('Sent')}}: @if($val->sent==1) Yes @ {{$val->sent_date}} @elseif($val->sent==0) No @endif</p>
                        <p>{{__('Due by')}}: {{date("h:i:A j, M Y", strtotime($val->due_date))}}</p>
                        <p class="text-sm mb-2">{{__('Created')}}: {{date("h:i:A j, M Y", strtotime($val->created_at))}}</p>
                        @if($val->status==1)
                          <span class="badge badge-pill badge-primary">{{__('Charge')}}: {{$currency->symbol.number_format($val->charge)}}</span>
                          <span class="badge badge-pill badge-success"><i class="fad fa-check"></i> {{__('Paid')}}</span>
                        @elseif($val->status==0)
                          <span class="badge badge-pill badge-danger"><i class="fad fa-spinner"></i> {{__('Pending')}}</span>                    
                        @endif

                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal fade" id="edit{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h3 class="mb-0 font-weight-bolder">{{__('Edit Invoice')}}</h3>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <form action="{{route('update.invoice')}}" method="post">
                        @csrf
                        <div class="form-group row">
                          <label class="col-form-label col-lg-12">{{__('Amount')}}</label>
                          <div class="col-lg-12">
                            <div class="input-group">
                              <div class="input-group-prepend">
                                <span class="input-group-text">{{$currency->symbol}}</span>
                              </div>
                              <input type="hidden" name="id" value="{{$val->id}}"> 
                              <input type="number" step="any" name="amount" value="{{$val->amount}}" class="form-control" required="">
                            </div>
                          </div>
                        </div>                       
                        <div class="form-group row">
                          <label class="col-form-label col-lg-12">{{__('Quantity')}}</label>
                          <div class="col-lg-12">
                            <div class="input-group input-group-merge">
                              <input type="number" name="quantity" value="{{$val->quantity}}" class="form-control" required="">
                            </div>
                          </div>
                        </div>                        
                        <div class="form-group row">
                          <label class="col-form-label col-lg-12">{{__('Tax')}}</label>
                          <div class="col-lg-12">
                            <div class="input-group input-group-merge">
                              <input type="number" name="tax" maxlength="10" value="{{$val->tax}}" class="form-control">
                              <span class="input-group-append">
                                <span class="input-group-text">%</span>
                              </span>
                            </div>
                          </div>
                        </div>                      
                        <div class="form-group row">
                          <label class="col-form-label col-lg-12">{{__('Discount')}}</label>
                          <div class="col-lg-12">
                            <div class="input-group input-group-merge">
                              <input type="number" name="discount" maxlength="10" value="{{$val->discount}}" class="form-control">
                              <span class="input-group-append">
                                <span class="input-group-text">%</span>
                              </span>
                            </div>
                          </div>
                        </div>                           
                        <div class="form-group row">
                          <label class="col-form-label col-lg-12" for="exampleDatepicker">{{__('Due Date')}}</label>
                          <div class="col-lg-12">
                            <div class="input-group">
                              <span class="input-group-prepend">
                                <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                              </span>
                              <input type="text" class="form-control datepicker" name="due_date" value="{{$val->due_date}}" required>
                            </div>
                          </div>
                        </div>                
                        <div class="text-right">
                          <button type="submit" class="btn btn-neutral btn-block">{{__('Save')}}</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div> 
              <div class="modal fade" id="delete{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered" role="document">
                      <div class="modal-content">
                          <div class="modal-body p-0">
                              <div class="card bg-white border-0 mb-0">
                                  <div class="card-header">
                                    <h3 class="mb-0 font-weight-bolder">{{__('Delete Invoice')}}</h3>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                    <span class="mb-0 text-xs">{{__('Are you sure you want to delete this?, all transaction related to this invoice will also be deleted')}}</span>
                                  </div>
                                  <div class="card-body">
                                      <a  href="{{route('delete.invoice', ['id' => $val->ref_id])}}" class="btn btn-danger btn-block">{{__('Proceed')}}</a>
                                  </div>
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
                <h3 class="text-dark">No Invoice Found</h3>
                <p class="text-dark text-sm card-text">We couldn't find any invoice to this account</p>
              </div>
            </div>
          @endif
        </div>
        <div class="row">
          <div class="col-md-12">
          {{ $invoice->links('pagination::bootstrap-4') }}
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
                <span class="text-sm text-dark mb-0"><i class="fa fa-google-wallet"></i> {{__('Received')}}</span><br>
                <span class="text-xl text-dark mb-0">{{$currency->name}} {{number_format($received, 2, '.', '')}}</span><br>
                <hr>
              </div>
            </div>
            <div class="row align-items-center">
              <div class="col">
                <div class="my-4">
                  <span class="surtitle">{{__('Pending')}}</span><br>
                  <span class="surtitle ">{{__('Total')}}</span>
                </div>
              </div>
              <div class="col-auto">
                <div class="my-4">
                  <span class="surtitle ">{{$currency->name}} {{number_format($pending, 2, '.', '')}}</span><br>
                  <span class="surtitle ">{{$currency->name}} {{number_format($total, 2, '.', '')}}</span>
                </div>
              </div>
            </div>
          </div>
        </div> 
        @foreach($paid as $k=>$val)
          <div class="card">
            <!-- Card body -->
            <div class="card-body">
              <div class="row align-items-center">
                <div class="col">
                  <p>{{__('Email')}}: {{$val->email}}</p>
                  <p>{{__('Total')}}: {{$currency->symbol.number_format($val->total, 2, '.', '')}}</p>
                  <p>{{__('Due date')}}: {{date("h:i:A j, M Y", strtotime($val->due_date))}}</p>
                  <p class="mb-1">{{__('Payment link')}} <button type="button" class="btn-icon-clipboard" data-clipboard-text="{{route('view.invoice', ['id' => $val->ref_id])}}" title="Copy"><i class="fad fa-copy"></i></button></p>
                  @if($val->status==1)
                    <span class="badge badge-pill badge-success"><i class="fad fa-check"></i> {{__('Paid')}}</span>
                  @elseif($val->status==0)
                    <span class="badge badge-pill badge-danger"><i class="fad fa-spinner"></i> {{__('Pending')}}</span>                    
                  @endif

                </div>
              </div>
            </div>
          </div>
        @endforeach 
      </div>
    </div>
@stop
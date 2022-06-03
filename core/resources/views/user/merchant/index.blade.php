
@extends('userlayout')

@section('content')
<!-- Page content -->
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="row align-items-center py-4">
      <div class="col-12">
        <h6 class="h2 d-inline-block mb-3">{{__('Website Integration')}}</h6>
      </div>
      <div class="col-12 text-left">
        <a data-toggle="modal" data-target="#add-merchant" href="" class="btn btn-sm btn-neutral"><i class="fad fa-plus"></i> {{__('Add Website')}}</a> 
        <a href="{{route('user.merchant-documentation')}}" class="btn btn-sm btn-neutral"><i class="fad fa-file"></i> {{__('Documentation')}}</a> 
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="modal fade" id="add-merchant" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h3 class="mb-0 font-weight-bolder">{{__('Add New Website')}}</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form action="{{route('submit.merchant')}}" method="post" id="modal-details">
                  @csrf
                    <div class="form-group row">
                      <div class="col-lg-12">
                        <input type="text" name="merchant_name" class="form-control" placeholder="{{__('Merchant Name')}}" required>
                      </div>
                    </div> 
                    <div class="form-group row">
                      <label class="col-form-label col-lg-12">{{__('Send Notifications To')}}</label>
                      <div class="col-lg-12">
                        <input type="email" name="email" class="form-control">
                        <span class="form-text text-xs">If provided, this email address will get transaction notification</span>
                      </div>
                    </div> 
                    <div class="text-right">
                    <button type="submit" class="btn btn-neutral btn-block" form="modal-details">{{__('Create Merchant')}}</button>
                    </div>         
                </form>
              </div>
            </div>
          </div>
        </div>         
      </div>
    </div>
    <div class="row">  
      <div class="col-md-8">
        <div class="row"> 
          @if(count($merchant)>0) 
            @foreach($merchant as $k=>$val)
              <div class="col-md-6">
                  <div class="card">
                    <!-- Card body -->
                    <div class="card-body">
                      <div class="row mb-2">
                        <div class="col-6">
                          <p class="text-sm text-dark mb-2"><a class="btn-icon-clipboard" data-clipboard-text="{{$val->merchant_key}}" title="Copy">{{__('COPY MERCHANT KEY')}} <i class="fad fa-link text-xs"></i></a></p>
                        </div>  
                        <div class="col-6 text-right">
                          <a class="mr-0 text-dark" data-toggle="dropdown" aria-haspopup="true" aria-expanded="fadse">
                            <i class="fad fa-chevron-circle-down"></i>
                          </a>
                          <div class="dropdown-menu dropdown-menu-left">
                            <a class="dropdown-item" href="{{route('log.merchant', ['id' => $val->merchant_key])}}"><i class="fad fa-sync"></i>{{__('Transactions')}}</a>
                            <a class="dropdown-item" data-toggle="modal" data-target="#edit{{$val->id}}" href="#"><i class="fad fa-pencil"></i>{{__('Edit')}}</a>
                            <a class="dropdown-item" data-toggle="modal" data-target="#delete{{$val->id}}" href=""><i class="fad fa-trash"></i>{{__('Delete')}}</a>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col">
                          <h5 class="h4 mb-1 font-weight-bolder">{{$val->name}}</h5>
                          <p>{{__('Reference')}}: {{$val->ref_id}}</p>
                          <p>{{__('Notify email')}}: @if($val->email==null) No Email @else {{$val->email}} @endif</p>
                          <p class="text-sm mb-2">{{__('Date')}}: {{date("h:i:A j, M Y", strtotime($val->created_at))}}</p>
                        </div>
                      </div>
                    </div>
                  </div>
              </div>
              <div class="modal fade" id="edit{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h3 class="mb-0 font-weight-bolder">{{__('Edit Website')}}</h3>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <form action="{{route('update.merchant')}}" method="post" id="modal-detailx">
                        @csrf
                        <div class="form-group row">
                          <div class="col-lg-12">
                            <input type="text" name="name" class="form-control" placeholder="{{__('Website Name')}}" value="{{$val->name}}" required>
                            <input type="hidden" name="id" value="{{$val->id}}">
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-form-label col-lg-12">{{__('Send Notifications To')}}</label>
                          <div class="col-lg-12">
                            <input type="email" name="email" class="form-control" value="{{$val->email}}">
                            <span class="form-text text-xs">If provided, this email address will get transaction notification</span>
                          </div>
                        </div> 
                        <div class="text-right">
                          <button type="submit" class="btn btn-neutral btn-block" form="modal-detailx">{{__('Update Website')}}</button>
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
                                  <h3 class="mb-1 font-weight-bolder">{{__('Delete Website')}}</h3>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                  <span class="mb-0 text-xs">{{__('Are you sure you want to delete this?, all transaction related to this website will also be deleted')}}</span>
                                </div>
                                <div class="card-body">
                                    <a  href="{{route('delete.merchant', ['id' => $val->id])}}" class="btn btn-danger btn-block">{{__('Proceed')}}</a>
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
                <h3 class="text-dark">No Website Found</h3>
                <p class="text-dark text-sm card-text">We couldn't find any website to this account</p>
              </div>
            </div>
          @endif
        </div>
      </div>
      <div class="col-md-4">
        <div class="card">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col text-center">
                <h4 class="mb-4 text-primary">
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
                  <span class="surtitle">{{__('Abandoned')}}</span><br>
                  <span class="surtitle ">{{__('Total')}}</span>
                </div>
              </div>
              <div class="col-auto">
                <div class="my-4">
                  <span class="surtitle ">{{$currency->name}} {{number_format($pending, 2, '.', '')}}</span><br>
                  <span class="surtitle ">{{$currency->name}} {{number_format($abadoned, 2, '.', '')}}</span><br>
                  <span class="surtitle ">{{$currency->name}} {{number_format($total, 2, '.', '')}}</span>
                </div>
              </div>
            </div>
          </div>
      </div>
    </div>
@stop
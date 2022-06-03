
@extends('userlayout')

@section('content')
<!-- Page content -->
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="row">  
      <div class="col-md-12">
        <div class="row">  
            <div class="col-md-6">
                <div class="card">
                  <!-- Card body -->
                  <div class="card-body">
                    <div class="row mb-2">
                      <div class="col-6">
                        <p class="text-sm text-dark mb-2"><a class="btn-icon-clipboard text-primary" data-clipboard-text="{{$val->merchant_key}}" title="Copy">{{__('COPY MERCHANT KEY')}}</a></p>
                      </div>  
                      <div class="col-6 text-right">
                        <a class="mr-0 text-dark" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="fal fa-ellipsis-h-alt"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-left">
                          <a class="dropdown-item" href="{{route('log.merchant', ['id' => $val->merchant_key])}}">{{__('Transactions')}}</a>
                          <a class="dropdown-item" data-toggle="modal" data-target="#edit{{$val->id}}" href="#">{{__('Edit')}}</a>
                          <a class="dropdown-item" data-toggle="modal" data-target="#delete{{$val->id}}" href="">{{__('Delete')}}</a>
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
                    <h3 class="mb-0 font-weight-bolder">{{__('Edit Merchant')}}</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form action="{{route('update.merchant')}}" method="post" id="modal-detailx">
                      @csrf
                      <div class="form-group row">
                        <div class="col-lg-12">
                          <input type="text" name="name" class="form-control" placeholder="{{__('Merchant Name')}}" value="{{$val->name}}" required>
                          <input type="hidden" name="id" value="{{$val->id}}">
                        </div>
                      </div>
                      <div class="form-group row">
                        <div class="col-lg-12">
                            <select class="form-control select" name="charge" required>
                                <option value="">{{__('Who pays charges')}}</option>
                                <option value="1" @if($val->charge==1) selected @endif>{{__('Merchant')}}</option>
                                <option value="0" @if($val->charge==0) selected @endif>{{__('Client')}}</option>
                            </select>
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
                        <button type="submit" class="btn btn-neutral btn-block" form="modal-detailx">{{__('Update Merchant')}}</button>
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
                                <h3 class="mb-0 font-weight-bolder">{{__('Delete Merchant')}}</h3>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                                <span class="mb-0 text-xs">{{__('Are you sure you want to delete this?, all transaction related to this merchant will also be deleted')}}</span>
                              </div>
                              <div class="card-body">
                                  <a  href="{{route('delete.merchant', ['id' => $val->id])}}" class="btn btn-danger btn-block">{{__('Proceed')}}</a>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
            </div>
        </div>
      </div>
    </div>
@stop
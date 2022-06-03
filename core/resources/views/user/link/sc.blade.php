@extends('userlayout')

@section('content')
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="row align-items-center py-4">
      <div class="col-4">
        <h6 class="h2 d-inline-block mb-0">{{__('Single Charge')}}</h6>
      </div>
      <div class="col-8 text-right">
        <a data-toggle="modal" data-target="#single-charge" href="" class="btn btn-sm btn-neutral"><i class="fad fa-plus"></i> {{__('Create Payment Link')}}</a> 
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="modal fade" id="single-charge" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h3 class="mb-0 font-weight-bolder">{{__('Create New Payment Link')}}</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form action="{{route('submit.singlecharge')}}" method="post" id="modal-details">
                  @csrf
                    <div class="form-group row">
                      <div class="col-lg-12">
                          <input type="text" name="name" class="form-control" placeholder="{{__('Payment link name')}}" required>
                          <span class="form-text text-xs">Single Charge allows you to create payment links for your customers, Transaction Charge is {{$set->single_charge}}% per transaction</span>
                      </div>
                    </div>
                    <div class="form-group row">
                      <div class="col-lg-12">
                        <div class="input-group">
                          <span class="input-group-prepend">
                            <span class="input-group-text">{{$currency->symbol}}</span>
                          </span>
                          <input type="number" step="any" class="form-control" name="amount" placeholder="0.00">
                        </div>
                        <span class="form-text text-xs">Leave empty to allow customers enter desired amount</span>
                      </div>
                    </div>  
                    <div class="form-group row">
                      <div class="col-lg-12">
                          <textarea type="text" name="description" placeholder="{{__('Description')}}" rows="4" class="form-control" required></textarea>
                      </div>
                    </div>           
                    <div class="form-group row">
                      <div class="col-lg-12">
                          <input type="text" name="redirect_url" class="form-control" placeholder="https://your-domain.com">
                            <span class="form-text text-xs">{{__('Redirect after payment  - Optional')}}</span>
                      </div>                        
                    </div> 
                    <div class="text-right">
                    <button type="submit" class="btn btn-neutral btn-block" form="modal-details">{{__('Create Link')}}</button>
                    </div>         
                </form>
              </div>
            </div>
          </div>
        </div>         
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="row">  
          @if(count($links)>0)
            @foreach($links as $k=>$val)
              <div class="col-md-4">
                <div class="card bg-white">
                  <!-- Card body -->
                  <div class="card-body">
                    <div class="row mb-2">
                      <div class="col-4">
                        <p class="text-sm text-dark mb-2"><a class="btn-icon-clipboard" data-clipboard-text="{{route('scview.link', ['id' => $val->ref_id])}}" title="Copy">{{__('COPY LINK')}} <i class="fad fa-link text-xs"></i></a></p>
                      </div>  
                      <div class="col-8 text-right">
                        <a class="mr-0 text-dark" data-toggle="dropdown" aria-haspopup="true" aria-expanded="fadse">
                          <i class="fad fa-chevron-circle-down"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-left">
                          @if($val->active==1)
                              <a class='dropdown-item' href="{{route('sclinks.unpublish', ['id' => $val->id])}}"><i class="fad fa-ban"></i>{{ __('Disable')}}</a>
                          @else
                              <a class='dropdown-item' href="{{route('sclinks.publish', ['id' => $val->id])}}"><i class="fad fa-check"></i>{{ __('Activate')}}</a>
                          @endif
                          <a class="dropdown-item" href="{{route('user.sclinkstrans', ['id' => $val->id])}}"><i class="fad fa-sync"></i>{{__('Transactions')}}</a>
                          <a class="dropdown-item" data-toggle="modal" data-target="#edit{{$val->id}}" href="#"><i class="fad fa-pencil"></i>{{__('Edit')}}</a>
                          <a class="dropdown-item" data-toggle="modal" data-target="#delete{{$val->id}}" href=""><i class="fad fa-trash"></i>{{__('Delete')}}</a>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col">
                        <h5 class="h4 mb-1 font-weight-bolder">{{$val->name}}</h5>
                        <p>{{__('Reference')}}: {{$val->ref_id}}</p>
                        <p>{{__('Amount')}}: @if($val->amount==null) Not fixed @else {{$currency->symbol.number_format($val->amount, 2, '.', '')}} @endif</p>
                        <p class="text-sm mb-2">{{__('Date')}}: {{date("h:i:A j, M Y", strtotime($val->created_at))}}</p>
                        @if($val->active==1)
                            <span class="badge badge-pill badge-success"><i class="fad fa-check"></i> {{__('Active')}}</span>
                        @else
                            <span class="badge badge-pill badge-danger"><i class="fad fa-ban"></i> {{__('Disabled')}}</span>
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
                      <h3 class="mb-0 font-weight-bolder">{{__('Edit Payment Link')}}</h3>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <form action="{{route('update.sclinks')}}" method="post">
                        @csrf
                        <div class="form-group row">
                          <div class="col-lg-12">
                              <input type="text" name="name" class="form-control" value="{{$val->name}}" placeholder="{{__('Payment link name')}}" required>
                              <span class="form-text text-xs">Single Charge allows you to create payment links for your customers, Transaction Charge is {{$set->single_charge}}% per transaction</span>
                          </div>
                        </div>
                        <div class="form-group row">
                          <div class="col-lg-12">
                            <div class="input-group">
                                <span class="input-group-prepend">
                                    <span class="input-group-text">{{$currency->symbol}}</span>
                                </span>
                                <input type="number" step="any" class="form-control" name="amount" value="{{$val->amount}}" placeholder="0.00">
                            </div>
                            <span class="form-text text-xs">Leave empty to allow customers enter desired amount</span>
                          </div> 
                        </div>  
                        <div class="form-group row">
                          <div class="col-lg-12">
                            <textarea type="text" name="description" rows="4" class="form-control" placeholder="{{__('Description')}}" required>{{$val->description}}</textarea>
                          </div>
                        </div>              
                        <div class="form-group row">
                          <div class="col-lg-12">
                              <input type="text" name="redirect_url" class="form-control" value="{{$val->redirect_link}}" placeholder="https://your-domain.com">
                                <span class="form-text text-xs">{{__('Redirect after payment  - Optional')}}</span>
                          </div>                        
                        </div> 
                        <input type="hidden" name="id" value="{{$val->id}}">                                     
                        <div class="text-right">
                          <button type="submit" class="btn btn-neutral btn-block">{{__('Update Payment link')}}</button>
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
                                    <h3 class="mb-0 font-weight-bolder">{{__('Delete Payment link')}}</h3>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                    <span class="mb-0 text-xs">{{__('Are you sure you want to delete this?, all transaction related to this payment link will also be deleted')}}</span>
                                  </div>
                                  <div class="card-body">
                                      <a  href="{{route('delete.user.link', ['id' => $val->id])}}" class="btn btn-danger btn-block">{{__('Proceed')}}</a>
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
                <h3 class="text-dark">No Payment Link Found</h3>
                <p class="text-dark text-sm card-text">We couldn't find any single charge page to this account</p>
              </div>
            </div>
          @endif
        </div> 
        <div class="row">
          <div class="col-md-12">
          {{ $links->links('pagination::bootstrap-4') }}
          </div>
        </div>
      </div> 
    </div>
@stop

@extends('userlayout')

@section('content')
<!-- Page content -->
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="row align-items-center py-4">
      <div class="col-8">
        <a data-toggle="modal" data-target="#modal-formx" href="" class="btn btn-sm btn-neutral"><i class="fa fa-plus"></i> {{__('Create Sub Account')}}</a>
      </div>
    </div>
    <div class="modal fade" id="modal-formx" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="mb-0 font-weight-bolder">{{__('Add Sub Account')}}</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form action="{{route('submit.subacct')}}" method="post"> 
              @csrf
              <div class="form-group row">
                <div class="col-lg-12">
                  <input type="text" name="subname" class="form-control" placeholder="Subaccount's Name">
                </div>
              </div>              
              <div class="form-group row">
                <div class="col-lg-12">
                  <input type="email" name="subemail" class="form-control" placeholder="Subaccount's Email">
                </div>
              </div>      
              <div class="form-group row">
                <div class="col-lg-12">
                  <select class="form-control select" name="xcountry" id="xcountry" required>
                      <option value="">{{__('Subaccount Country')}}</option> 
                        @foreach($country as $val)
                          <option value="{{$val->country_id}}">{{$val->real['name']}}</option>
                        @endforeach
                  </select>
                </div>
              </div>          
              <div class="form-group row" id="splittype">
                <div class="col-lg-12">
                  <select class="form-control select" name="type" id="spt" required>
                    <option value=''>{{__('Split Type')}}</option>
                    <option value='1'>Flat</option>
                    <option value='2'>Percentage</option>
                  </select>
                </div>
              </div> 
              <div class="form-group row">
                  <div class="col-lg-12">
                      <select class="form-control select" name="account_type" required>
                          <option value="">{{__('Account Type')}}</option> 
                            <option value="individual">Individual</option>
                            <option value="company">Company</option>
                      </select>
                  </div>
              </div>  
              <div class="form-group">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">#</span>
                  </div>
                  <input class="form-control" placeholder="{{ __('Routing Number / Sort Code') }}" type="text" name="routing_number" required>
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

    <div class="row">
      <div class="col-md-12">
        <div class="row">  
          @if(count($sub)>0)
            @foreach($sub as $k=>$val)
              <div class="col-md-4">
                <div class="card bg-white">
                  <!-- Card body -->
                  <div class="card-body">
                    <div class="row mb-2">
                      <div class="col-4">
                        <h5 class="h4 mb-1 font-weight-bolder">{{$val->name}}</h5>
                      </div>  
                      <div class="col-8 text-right">
                        <a class="mr-0 text-dark" data-toggle="dropdown" aria-haspopup="true" aria-expanded="fadse">
                          <i class="fad fa-chevron-circle-down"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-left">
                          @if($val->active==1)
                            <a class='dropdown-item' href="{{route('subacct.unpublish', ['id' => $val->id])}}"><i class="fad fa-ban"></i>{{ __('Disable')}}</a>
                          @else
                            <a class='dropdown-item' href="{{route('subacct.publish', ['id' => $val->id])}}"><i class="fad fa-check"></i>{{ __('Activate')}}</a>
                          @endif
                            <a class="dropdown-item" href="{{route('user.subaccttrans', ['id' => $val->id])}}"><i class="fad fa-sync"></i>{{__('Transactions')}}</a>
                            <a class="dropdown-item" data-toggle="modal" data-target="#delete{{$val->id}}" href=""><i class="fad fa-trash"></i>{{__('Delete')}}</a>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col">
                        <p>{{__('Email')}}: {{$val->email}}</p>
                        <p>{{__('Bank')}}: {{$val->dbank['name']}}</p>
                        <p>{{__('Type')}}: @if($val->type==1){{$currency->symbol.$val->amount}} From Every Payout @else {{$val->amount}}% of Every Payout  @endif</p>
                        <p>{{__('Account Number')}}: {{$val->acct_no}}</p>
                        <p>{{__('Account Name')}}: {{$val->acct_name}}</p>
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
            @endforeach
          @else
          <div class="col-md-12 mb-5">
              <div class="text-center mt-8">
                <div class="mb-3">
                  <img src="{{url('/')}}/asset/images/empty.svg">
                </div>
                <h3 class="text-dark">No Sub Account Found</h3>
                <p class="text-dark text-sm card-text">We couldn't find any sub account to this account</p>
              </div>
            </div>
          @endif
        </div> 
        <div class="row">
          <div class="col-md-12">
          {{ $sub->links('pagination::bootstrap-4') }}
          </div>
        </div>
      </div> 
    </div>
    @foreach($sub as $k=>$val)
    <div class="modal fade" id="delete{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="card bg-white border-0 mb-0">
                        <div class="card-header">
                            <h3 class="mb-0 font-weight-bolder">{{__('Delete Sub Account')}}</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                            <span class="mb-0 text-xs">{{__('Are you sure you want to delete this sub account?')}}</span>
                        </div>
                        <div class="card-body">
                            <a  href="{{route('subacct.delete', ['id' => $val->id])}}" class="btn btn-danger btn-block">{{__('Proceed')}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-form{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h3 class="mb-0">{{__('Edit Sub Account')}}</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                <form role="form" action="{{route('subacct.edit')}}" method="post"> 
                    @csrf
                    <div class="form-group row">
                    <div class="col-lg-12">
                        <input type="text" name="subname" placeholder="Sub account name" class="form-control" value="{{$val['name']}}">
                    </div>
                    </div>
                    <div class="form-group row">
                    <div class="col-lg-12">
                        <input type="text" name="name" placeholder="Bank name" class="form-control" value="{{$val['bank']}}">
                    </div>
                    </div>
                    <div class="form-group row">
                    <div class="col-lg-12">
                        <input type="text" name="acct_name" class="form-control" placeholder="Account Name" value="{{$val['acct_name']}}">
                    </div>
                    </div>                           
                    <div class="form-group row">
                    <div class="col-lg-12">
                        <input type="text" pattern="\d*" name="acct_no" placeholder="Account number" class="form-control" value="{{$val['acct_no']}}" maxlength="12">
                        <input type="hidden" name="id" value="{{$val['id']}}">
                    </div>
                    </div>                    
                    <div class="form-group row">
                    <div class="col-lg-10">
                        <input type="text" name="swift" placeholder="Swift code" class="form-control" placeholder="Swift Code" value="{{$val['swift_code']}}">
                        <input type="hidden" name="id" value="{{$val['id']}}">
                    </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text">#</span>
                          </div>
                          <input class="form-control" placeholder="{{ __('Routing Number / Sort Code') }}" type="number" name="routing_number" value="{{$val['routing_number']}}" required>
                        </div>
                      </div>
                      <div class="form-group row">
                        <div class="col-lg-12">
                            <select class="form-control select" name="account_type" required>
                                <option value="">{{__('Account Type')}}</option> 
                                  <option value="individual" @if($val->account_type=='individual') selected @endif>Individual</option>
                                  <option value="company" @if($val->account_type=='company') selected @endif>Company</option>
                            </select>
                        </div>
                      </div>
                    <div class="text-right">
                    <button type="submit" class="btn btn-neutral btn-block">{{__('Update Acount')}}</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach

@stop
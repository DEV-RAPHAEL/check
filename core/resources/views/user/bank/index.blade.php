
@extends('userlayout')

@section('content')
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="row align-items-center py-4">
      <div class="col-lg-6 col-7">
        <h6 class="h2 d-inline-block mb-0">{{__('Bank')}}</h6>
      </div>
      <div class="col-lg-6 col-5 text-right">
        <a data-toggle="modal" data-target="#modal-formx" href="" class="btn btn-sm btn-neutral"><i class="fad fa-plus"></i> {{__('Bank Account')}}</a>
      </div>
    </div>
    <div class="modal fade" id="modal-formx" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="mb-0 font-weight-bolder">{{__('Add Bank Account')}}</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form role="form" action="{{route('submit.bank')}}" method="post"> 
              @csrf
              <div class="form-group row">
                <div class="col-lg-12">
                  <input type="text" name="name" class="form-control" placeholder="Bank">
                </div>
              </div>
              <div class="form-group row">
                <div class="col-lg-12">
                  <input type="text" name="acct_name" class="form-control" placeholder="Account Name" required>
                </div>
              </div>                                                                      
              <div class="form-group row">
                <div class="col-lg-12">
                  <input type="text" name="acct_no" pattern="\d*" maxlength="12" placeholder="Account No" class="form-control" required>
                </div>
              </div>                        
              <div class="form-group row">
                <div class="col-lg-12">
                  <input type="text" name="swift" class="form-control text-uppercase" placeholder="Swift Code" required>
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
    @if(count($bank)>0) 
      @foreach($bank as $k=>$val)
        <div class="col-md-6">
            <div class="card">
              <div class="card-body">
                <div class="row mb-2">
                  <div class="col-6">
                    <h3 class="mb-0 font-weight-bolder">{{$val->name}}</h3>
                  </div>
                  <div class="col-6 text-right">
                    <a class="mr-0 text-dark" data-toggle="dropdown" aria-haspopup="true" aria-expanded="fadse">
                      <i class="fad fa-chevron-circle-down"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-left">
                    @if($val->status==0)
                      <a class="dropdown-item" href="{{route('bank.default', ['id' => $val->id])}}"><i class="fad fa-check"></i>{{__('Default')}}</a>
                    @endif
                      <a class="dropdown-item" data-toggle="modal" data-target="#modal-form{{$val->id}}" href="#"><i class="fad fa-pencil"></i>{{__('Edit')}}</a>
                      <a class="dropdown-item" data-toggle="modal" data-target="#delete{{$val->id}}" href=""><i class="fad fa-trash"></i>{{__('Delete')}}</a>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <p class="text-sm mb-0 font-weight-bolder text-uppercase">{{__('Default')}} @if($val->status==1) Yes @else No @endif</p>
                    <p class="text-sm mb-0 font-weight-bolder text-uppercase">{{__('Name')}} {{$val->acct_name}}</p>
                    <p class="text-sm mb-2 font-weight-bolder text-uppercase">{{__('Swift Code')}} <span class="text-uppercase">{{$val->swift}}</span></p>
                    <h4 class="mb-1 h2 text-primary font-weight-bolder">{{$val->acct_no}}</h4>
                  </div>
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
                              <h3 class="mb-0 font-weight-bolder">{{__('Delete Bank Account')}}</h3>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                              <span class="mb-0 text-xs">{{__('Are you sure you want to delete this bank account?')}}</span>
                            </div>
                            <div class="card-body">
                                <a  href="{{route('bank.delete', ['id' => $val->id])}}" class="btn btn-danger btn-block">{{__('Proceed')}}</a>
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
                <h3 class="mb-0">{{__('Edit Bank')}}</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form role="form" action="{{route('bank.edit')}}" method="post"> 
                  @csrf
                  <div class="form-group row">
                    <div class="col-lg-12">
                      <input type="text" name="name" placeholder="Bank name" class="form-control" placeholder="Bank" value="{{$val['name']}}">
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-lg-12">
                      <input type="text" name="acct_name" placeholder="Account name" class="form-control" placeholder="Account Name" value="{{$val['acct_name']}}">
                    </div>
                  </div>                           
                  <div class="form-group row">
                    <div class="col-lg-12">
                      <input type="number" name="acct_no" placeholder="Account number" placeholder="Account No" class="form-control" value="{{$val['acct_no']}}">
                      <input type="hidden" name="id" value="{{$val['id']}}">
                    </div>
                  </div>                    
                  <div class="form-group row">
                    <div class="col-lg-10">
                      <input type="text" name="swift" placeholder="Swift code" class="form-control" placeholder="Swift Code" value="{{$val['swift']}}">
                      <input type="hidden" name="id" value="{{$val['id']}}">
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
    @else
      <div class="col-md-12 mb-5">
        <div class="text-center mt-8">
          <div class="mb-3">
            <img src="{{url('/')}}/asset/images/empty.svg">
          </div>
          <h3 class="text-dark">No Bank Account</h3>
          <p class="text-dark text-sm card-text">We couldn't find any bank account to this account</p>
        </div>
      </div>
    @endif
  </div>
  <div class="row">
      <div class="col-md-12">
      {{ $bank->links() }}
      </div>
    </div>
@stop
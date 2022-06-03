
@extends('userlayout')

@section('content')
<!-- Page content -->
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="row align-items-center py-4">
      <div class="col-lg-6 col-7">
        <h6 class="h2 d-inline-block mb-0">{{__('Subscription Payment')}}</h6>
      </div>
      <div class="col-lg-6 col-5 text-right">
        <a data-toggle="modal" data-target="#create-plan" href="" class="btn btn-sm btn-neutral"><i class="fad fa-plus"></i> {{__('Create Plan')}}</a> 
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="modal fade" id="create-plan" tabindex="-1" role="dialog" aria-labelledby="create-plan" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title font-weight-bolder" id="exampleModalLabel">{{__('Create New Plan')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form action="{{route('submit.plan')}}" method="post" id="modal-details">
                  @csrf
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <input type="text" name="name" class="form-control" placeholder="Name" required>
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
                            <select class="form-control select" name="interval">
                                <option value="">{{__('Select Interval')}}</option>
                                <option value="1 Hour">{{__('Hourly')}}</option>
                                <option value="1 Day">{{__('Daily')}}</option>
                                <option value="1 Week">{{__('Weekly')}}</option>
                                <option value="1 Month">{{__('Monthly')}}</option>
                                <option value="4 Months">{{__('Quaterly')}}</option>
                                <option value="6 Months">{{__('Every 6 Months')}}</option>
                                <option value="1 Year">{{__('Yearly')}}</option>
                            </select>
                        </div>
                    </div>           
                    <div class="form-group row">
                      <div class="col-lg-12">
                          <input type="number" name="times" placeholder="Number of times to charge a subscriber?" class="form-control">
                          <span class="form-text text-xs">Leave empty to charge subscriber indefinitely</span>
                      </div>
                    </div> 
                    <div class="text-right">
                    <button type="submit" class="btn btn-neutral btn-block" form="modal-details">{{__('Create Plan')}}</button>
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
          @if(count($plans)>0)
            @foreach($plans as $k=>$val)
              @php 
                $active=App\Models\Subscribers::whereplan_id($val->id)->where('expiring_date', '>', Carbon\Carbon::now())->count();
                $expired=App\Models\Subscribers::whereplan_id($val->id)->where('expiring_date', '<', Carbon\Carbon::now())->count();
              @endphp
              <div class="col-md-4">
                <div class="card bg-white">
                  <!-- Card body -->
                  <div class="card-body">
                    <div class="row mb-2">
                      <div class="col-4">
                        <p class="text-sm text-dark mb-2"><a class="btn-icon-clipboard" data-clipboard-text="{{route('subview.link', ['id' => $val->ref_id])}}" title="Copy">{{__('COPY LINK')}} <i class="fad fa-link text-xs"></i></a></p>
                      </div>  
                      <div class="col-8 text-right">
                        <a class="mr-0 text-dark" data-toggle="dropdown" aria-haspopup="true" aria-expanded="fadse">
                          <i class="fad fa-chevron-circle-down"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-left">
                          <a href="{{route('user.plansub', ['id' => $val->ref_id])}}" class="dropdown-item"><i class="fad fa-user"></i>{{__('Subscribers')}}</a>
                          <a data-toggle="modal" data-target="#edit{{$val->id}}" href="" class="dropdown-item"><i class="fad fa-pencil"></i>{{__('Edit')}}</a>
                          @if($val->active==1)
                            <a class='dropdown-item' href="{{route('sub.plan.unpublish', ['id' => $val->id])}}"><i class="fad fa-ban"></i>{{ __('Disable')}}</a>
                          @else
                            <a class='dropdown-item' href="{{route('sub.plan.publish', ['id' => $val->id])}}"><i class="fad fa-check"></i>{{ __('Activate')}}</a>
                          @endif
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col">
                        <h5 class="h4 mb-1 font-weight-bolder">{{$val->name}}</h5>
                        <p>{{__('Interval')}}: {{$val->intervals}} - @if($val->times==null) Indefinitely @else {{$val->times}} time(s) @endif</p>
                        <p>{{__('Amount')}}: {{$currency->symbol.number_format($val->amount, 2, '.', '')}}</p>
                        <p>{{__('Expired/Active')}}: {{$expired}} / {{$active}}</p>
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
                <h3 class="text-dark">No Subscription Plan Found</h3>
                <p class="text-dark text-sm card-text">We couldn't find any Subscription Plan to this account</p>
              </div>
            </div>
          @endif
        </div> 
        <div class="row">
          <div class="col-md-12">
          {{ $plans->links('pagination::bootstrap-4') }}
          </div>
        </div>
      </div> 
    </div>
    @foreach($plans as $k=>$val)
      <div class="modal fade" id="edit{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h3 class="mb-0 font-weight-bolder">{{__('Edit Plan')}}</h3>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="{{route('update.plan')}}" method="post">
                @csrf
                <div class="form-group row">
                    <label class="col-form-label col-lg-12">{{__('Plan Name')}}<span class="text-danger">*</span></label>
                    <div class="col-lg-12">
                        <input type="text" name="name" class="form-control" value="{{$val->name}}" required>
                        <span class="form-text text-xs">Amount & Interval can only be edited if no active subscriber</span>
                    </div>
                </div>
                @if(1>$active)
                <div class="form-group row">
                  <label class="col-form-label col-lg-12">{{__('Amount')}}</label>
                  <div class="col-lg-12">
                      <div class="input-group">
                          <span class="input-group-prepend">
                              <span class="input-group-text">{{$currency->symbol}}</span>
                          </span>
                          <input type="number" step="any" class="form-control" name="amount" placeholder="0.00" min="10" value="{{$val->amount}}">
                      </div>
                      <span class="form-text text-xs">Leave empty to allow customers enter desired amount</span>
                  </div>
                </div>  
                <div class="form-group row">
                  <label class="col-form-label col-lg-12">{{__('Interval')}}</label>
                  <div class="col-lg-12">
                      <select class="form-control select" name="interval">
                          <option value="1 Hour" @if($val->intervals=='1 Hour') selected @endif>{{__('Hourly')}}</option>
                          <option value="1 Day" @if($val->intervals=='1 Day') selected @endif>{{__('Daily')}}</option>
                          <option value="1 Week" @if($val->intervals=='1 Week') selected @endif>{{__('Weekly')}}</option>
                          <option value="1 Month" @if($val->intervals=='1 Month') selected @endif>{{__('Monthly')}}</option>
                          <option value="4 Months" @if($val->intervals=='4 Months') selected @endif>{{__('Quaterly')}}</option>
                          <option value="6 Months" @if($val->intervals=='6 Months') selected @endif>{{__('Every 6 Months')}}</option>
                          <option value="1 Year" @if($val->intervals=='1 Year') selected @endif>{{__('Yearly')}}</option>
                      </select>
                  </div>
                </div> 
                @endif
                <input name="plan_id" type="hidden" value="{{$val->id}}">               
                <div class="text-right">
                  <button type="submit" class="btn btn-neutral btn-block">{{__('Edit Plan')}}</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div> 
    @endforeach

@stop
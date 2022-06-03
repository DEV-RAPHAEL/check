
@extends('master')

@section('content')
<!-- Page content -->
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="card">
      <div class="card-header header-elements-inline">
        <h3 class="mb-0">{{__('Single Charge')}}</h3>
      </div>
      <div class="table-responsive py-4">
        <table class="table table-flush" id="datatable-buttons">
          <thead>
            <tr>
              <th>{{__('S / N')}}</th>
              <th>{{__('Merchant')}}</th>
              <th>{{__('Name')}}</th>
              <th>{{__('Amount')}}</th>
              <th>{{__('Reference ID')}}</th>
              <th>{{__('Redirect Url')}}</th>
              <th>{{__('Status')}}</th>
              <th>{{__('Suspended')}}</th>
              <th>{{__('Created')}}</th>
              <th>{{__('Updated')}}</th>
              <th>{{__('Link')}}</th>
              <th></th>
            </tr>
          </thead>
          <tbody>  
            @foreach($links as $k=>$val)
              <tr>
                <td>{{++$k}}.</td>
                <td>@if($val->user['business_name']==null) [Deleted] @else {{$val->user['business_name']}} @endif</td>
                <td>{{$val->name}}</td>
                <td>@if($val->amount==null) Not fixed @else {{$currency->symbol.number_format($val->amount, 2, '.', '')}} @endif</td>
                <td>#{{$val->ref_id}}</td>
                <td>@if($val->redirect_link==null) null @else {{$$val->redirect_link}} @endif</td>
                <td>
                    @if($val->active==1)
                        <span class="badge badge-pill badge-success">{{__('Active')}}</span>
                    @else
                        <span class="badge badge-pill badge-danger">{{__('Disabled')}}</span>
                    @endif
                </td>                
                <td>
                    @if($val->status==1)
                        <span class="badge badge-pill badge-success">{{__('Yes')}}</span>
                    @else
                        <span class="badge badge-pill badge-danger">{{__('No')}}</span>
                    @endif
                </td>
                <td>{{date("Y/m/d h:i:A", strtotime($val->created_at))}}</td>
                <td>{{date("Y/m/d h:i:A", strtotime($val->updated_at))}}</td>
                <td>{{route('scview.link', ['id' => $val->ref_id])}}</td>
                <td class="text-center">
                    <div class="">
                        <div class="dropdown">
                            <a class="text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                @if($val->status==1)
                                    <a class='dropdown-item' href="{{route('links.unpublish', ['id' => $val->id])}}">{{ __('Unsuspend')}}</a>
                                @else
                                    <a class='dropdown-item' href="{{route('links.publish', ['id' => $val->id])}}">{{ __('Suspend')}}</a>
                                @endif
                                <a class="dropdown-item" href="{{route('admin.linkstrans', ['id' => $val->id])}}">{{__('Transactions')}}</a>
                                <a data-toggle="modal" data-target="#delete{{$val->id}}" href="" class="dropdown-item">{{ __('Delete')}}</a>
                                <a data-toggle="modal" data-target="#description{{$val->id}}" href="" class="dropdown-item">{{ __('Description')}}</a>
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
    @foreach($links as $k=>$val)
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
                            <a  href="{{route('delete.link', ['id' => $val->id])}}" class="btn btn-danger btn-sm">{{ __('Proceed')}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>                
    <div class="modal fade" id="description{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
        <div class="modal-dialog modal- modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="card bg-white border-0 mb-0">
                        <div class="card-header">
                            <p class="mb-0 text-sm">{{$val->description}}</p>
                        </div>
                        <div class="card-body px-lg-5 py-lg-5 text-right">
                            <button type="button" class="btn btn-neutral btn-sm" data-dismiss="modal">{{ __('Close')}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach

@stop
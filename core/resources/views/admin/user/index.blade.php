@extends('master')

@section('content')
<div class="container-fluid mt--6">
    <div class="content-wrapper">
        <div class="card">
            <div class="card-header header-elements-inline">
                <h3 class="mb-0">{{__('Users')}}</h3>
            </div>
            <div class="table-responsive py-4">
                <table class="table table-flush" id="datatable-buttons">
                    <thead>
                        <tr>
                            <th>{{__('S/N')}}</th>
                            <th class="scope"></th>    
                            <th>{{__('Name')}}</th>
                            <th>{{__('Business name')}}</th>
                            <th>{{__('Email')}}</th>                                                                      
                            <th>{{__('Status')}}</th>
                            <th>{{__('Balance')}}</th>
                            <th>{{__('Created')}}</th>
                            <th>{{__('Updated')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $k=>$val)
                        <tr>
                            <td>{{++$k}}.</td>
                            <td class="text-right">
                            <div class="dropdown">
                                    <a class="text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fad fa-chevron-circle-down"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        <a href="{{route('user.manage', ['id' => $val->id])}}" class="dropdown-item">{{__('Manage customer')}}</a>
                                        <a href="{{route('admin.email', ['email' => $val->email, 'name' => $val->business_name])}}" class="dropdown-item">{{__('Send email')}}</a>
                                        @if($val->status==0)
                                            <a class='dropdown-item' href="{{route('user.block', ['id' => $val->id])}}">{{__('Block')}}</a>
                                        @else
                                            <a class='dropdown-item' href="{{route('user.unblock', ['id' => $val->id])}}">{{__('Unblock')}}</a>
                                        @endif
                                        <a data-toggle="modal" data-target="#delete{{$val->id}}" href="" class="dropdown-item">{{__('Delete')}}</a>
                                    </div>
                                </div>
                            </td>
                            <td>{{$val->first_name.' '.$val->last_name}}</td>
                            <td>{{$val->business_name}}</td>
                            <td>{{$val->email}}</td>
                            <td>
                                @if($val->status==0)
                                    <span class="badge badge-pill badge-primary">{{__('Active')}}</span>
                                @elseif($val->status==1)
                                    <span class="badge badge-pill badge-danger">{{__('Blocked')}}</span> 
                                @endif
                            </td>   
                            <td>{{$currency->symbol.number_format($val->balance,'2','.','')}}</td> 
                            <td>{{date("Y/m/d h:i:A", strtotime($val->created_at))}}</td>  
                            <td>{{date("Y/m/d h:i:A", strtotime($val->updated_at))}}</td>                   
                        </tr>
                        @endforeach               
                    </tbody>                    
                </table>
            </div>
        </div>
        @foreach($users as $k=>$val)
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
                                <a  href="{{route('user.delete', ['id' => $val->id])}}" class="btn btn-danger btn-sm">{{__('Proceed')}}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
@stop
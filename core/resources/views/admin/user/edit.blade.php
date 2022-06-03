@extends('master')

@section('content')
<div class="container-fluid mt--6">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0">{{__('Update Account Information')}}</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{url('admin/profile-update')}}" method="post">
                            @csrf
                            <div class="form-group row">
                                <label class="col-form-label col-lg-2">{{__('Business name')}}</label>
                                <div class="col-lg-10">
                                    <input type=""hidden value="{{$client->id}}" name="id">
                                    <input type="text" name="business_name" class="form-control" value="{{$client->business_name}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-lg-2">{{__('First Name')}}</label>
                                <div class="col-lg-10">
                                    <input type="text" name="first_name" class="form-control" value="{{$client->first_name}}">
                                </div>
                            </div>                          
                            <div class="form-group row">
                                <label class="col-form-label col-lg-2">{{__('Last Name')}}</label>
                                <div class="col-lg-10">
                                    <input type="text" name="last_name" class="form-control" value="{{$client->last_name}}">
                                </div>
                            </div>  
                            <div class="form-group row">
                                <label class="col-form-label col-lg-2">{{__('Email')}}</label>
                                <div class="col-lg-10">
                                    <input type="email" name="email" class="form-control" readonly value="{{$client->email}}">
                                </div>
                            </div>                            
                            <div class="form-group row">
                                <label class="col-form-label col-lg-2">{{__('Support Email')}}</label>
                                <div class="col-lg-10">
                                    <input type="email" name="support_email" class="form-control" readonly value="{{$client->support_email}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-lg-2">{{__('Mobile')}}</label>
                                <div class="col-lg-10">
                                    <input type="text" name="mobile" class="form-control" value="{{$client->phone}}">
                                </div>
                            </div>                                                                        
                            <div class="form-group row">
                                <label class="col-form-label col-lg-2">{{__('Balance')}}</label>
                                <div class="col-lg-10">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text">{{$currency->symbol}}</span>
                                        </span>
                                        <input type="number" name="balance" max-length="10" value="{{$client->balance}}" class="form-control">
                                    </div>
                                </div>
                            </div> 
                            <div class="form-group row">
                                <label class="col-form-label col-lg-2">{{__('Status')}}<span class="text-danger">*</span></label>
                                <div class="col-lg-10">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                        @if($client->email_verify==1)
                                            <input type="checkbox" name="email_verify" id=" customCheckLogin5" class="custom-control-input" value="1" checked>
                                        @else
                                            <input type="checkbox" name="email_verify"id=" customCheckLogin5"  class="custom-control-input" value="1">
                                        @endif
                                        <label class="custom-control-label" for=" customCheckLogin5">
                                        <span class="text-muted">{{__('Email verification')}}</span>     
                                        </label>
                                    </div>                                     
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                        @if($client->fa_status==1)
                                            <input type="checkbox" name="fa_status" id=" customCheckLogin6" class="custom-control-input" value="1" checked>
                                        @else
                                            <input type="checkbox" name="fa_status" id=" customCheckLogin6"  class="custom-control-input" value="1">
                                        @endif
                                        <label class="custom-control-label" for=" customCheckLogin6">
                                        <span class="text-muted">{{__('2fa security')}}</span>     
                                        </label>
                                    </div>                                                              
                                </div>
                            </div>                 
                            <div class="text-right">
                                <button type="submit" class="btn btn-success btn-sm">{{__('Save')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0">{{__('Compliance')}}</h3>
                    </div>                    
                    <div class="card-body">
                        <p>{{__('Trading Name')}}: {{$xver->trading_name}}</p>
                        <p>{{__('Description')}}: {{$xver->description}}</p>
                        <p>{{__('Staff Size')}}: {{$xver->staff_size}}</p>
                        <p>{{__('Industry')}}: {{$xver->industry}}</p>
                        <p>{{__('Category')}}: {{$xver->category}}</p>
                        <p>{{__('Phone')}}: {{$xver->phone}}</p>
                        <p>{{__('Address')}}: {{$xver->address}}</p>
                        <p>{{__('Email')}}: {{$xver->email}}</p>
                        <p>{{__('Website')}}: {{$xver->website}}</p>
                        <p>{{__('Gender')}}: {{$xver->gender}}</p>
                        <p>{{__('Business Type')}}: {{$xver->business_type}}</p>
                        <a href="{{url('/')}}/asset/profile/{{$xver->paddress}}">{{__('View proof of Address')}}</a><br><br>
                        @if($xver->business_type=="Registered Business")
                            <p>{{__('Legal name')}}: {{$xver->legal_name}}</p>
                            <p>{{__('Registration type')}}: {{$xver->registration_type}}</p>
                            <p>{{__('Tax id')}}: {{$xver->tax_id}}</p>
                            <p>{{__('Vat id')}}: {{$xver->vat_id}}</p>
                            <p>{{__('Reg no')}}: {{$xver->reg_no}}</p>
                            @if($xver->proof!=null)
                            <a href="{{url('/')}}/asset/profile/{{$xver->proof}}">{{__('Proof of Registration [Front]')}}</a><br><br>
                            <a href="{{url('/')}}/asset/profile/{{$xver->proof_back}}">{{__('Proof of Registration [Back]')}}</a><br><br>
                            @endif
                        @else
                            <p>{{__('Full name')}}: {{$xver->first_name}}  {{$xver->last_name}}</p>
                            <p>{{__('DOB')}}: {{$xver->day}}/{{$xver->month}}/{{$xver->year}}</p>
                            <p>{{__('Nationality')}}: {{$xver->nationality}}</p>
                            <p>{{__('ID Document')}}: {{$xver->id_type}}</p>
                            @if($xver->idcard!=null)
                            <a href="{{url('/')}}/asset/profile/{{$xver->idcard}}">{{__('View ID Document [Front]')}}</a><br><br>
                            <a href="{{url('/')}}/asset/profile/{{$xver->idcard_back}}">{{__('View ID Document [Back]')}}</a><br><br>
                            @endif
                        @endif
                        @if($xver->status==1)
                            <a class="btn btn-sm btn-neutral" href="{{url('/')}}/admin/approve-kyc/{{$xver->id}}">{{__('Approve')}}</a>
                            <a class="btn btn-sm btn-neutral" href="{{url('/')}}/admin/reject-kyc/{{$xver->id}}">{{__('Reject')}}</a>
                        @endif
                        <br><br>
                        @if($client->business_level==1)
                            <span class="badge badge-pill badge-danger">Business Level: Unverified</span>
                        @elseif($client->business_level==2)
                            <span class="badge badge-pill badge-primary">Business Level: Starter</span>                            
                        @elseif($client->business_level==3)
                            <span class="badge badge-pill badge-primary">Business Level: Registered</span>
                        @endif
                    </div>
                </div>
                <div class="card">
                    <div class="card-body text-center">
                        <div class="card-img-actions d-inline-block mb-3">
                            <img class="img-fluid rounded-circle" src="{{url('/')}}/asset/profile/{{$client->image}}" width="120" height="120" alt="">
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="d-sm-flex align-item-sm-center flex-sm-nowrap">
                            <div>
                                <ul class="list list-unstyled mb-0">
                                    <li><span class="text-sm">{{__('Joined:')}} {{date("Y/m/d h:i:A", strtotime($client->created_at))}}</span></li>
                                    <li><span class="text-sm">{{__('Last Login:')}} {{date("Y/m/d h:i:A", strtotime($client->last_login))}}</span></li>
                                    <li><span class="text-sm">{{__('Last Updated:')}} {{date("Y/m/d h:i:A", strtotime($client->updated_at))}}</span></li>
                                    <li><span class="text-sm">{{__('IP Address:')}} {{$client->ip_address}}</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>  
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0">{{__('Audit Logs')}}</h3>
                    </div>
                    <div class="table-responsive py-4">
                        <table class="table table-flush" id="datatable-buttons">
                        <thead>
                            <tr>
                            <th>{{__('S / N')}}</th>
                            <th>{{__('Reference ID')}}</th>
                            <th>{{__('Log')}}</th>
                            <th>{{__('Created')}}</th>
                            </tr>
                        </thead>
                        <tbody>  
                            @foreach($audit as $k=>$val)
                            <tr>
                                <td>{{++$k}}.</td>
                                <td>#{{$val->trx}}</td>
                                <td>{{$val->log}}</td>
                                <td>{{date("Y/m/d h:i:A", strtotime($val->created_at))}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop


@extends('userlayout')

@section('content')
<!-- Page content -->
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="card">
      <div class="card-header header-elements-inline">
        <h3 class="mb-0 font-weight-bolder">{{__('Create Sub Account')}}</h3>
      </div>      
      <div class="card-body">
        <form action="{{route('submit.subacct2')}}" method="post"> 
            @csrf  
            <div class="form-group row">
                <label class="col-form-label col-lg-2">{{__('Bank')}}</label>
                <div class="col-lg-10">
                    <select class="form-control select" name="bank">
                        <option value="">{{__('Select Bank')}}</option> 
                            @foreach($bnk as $val)
                            <option value="{{$val->id}}">{{$val->name}}</option>
                            @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-lg-2">{{__('Account Name')}}</label>
                <div class="col-lg-10">
                    <input type="text" name="acct_name" class="form-control" placeholder="Account Name" required>
                </div>
            </div>                                                                      
            <div class="form-group row">
                <label class="col-form-label col-lg-2">{{__('Account Number')}}</label>
                <div class="col-lg-10">
                    <input type="text" pattern="\d*" name="acct_no" maxlength="12" placeholder="Account Number" class="form-control" required>
                </div>
            </div>     
            @if(Session::get('type')==1)
            <div class="form-group row">
                <label class="col-form-label col-lg-2">{{__('Subaccount share of payments')}}</label>
                <div class="col-lg-10">
                    <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">{{$currency->symbol}}</span>
                    </div>
                    <input type="number" name="flat_share" class="form-control" required>
                    </div>
                </div>
            </div> 
            @elseif(Session::get('type')==2)                   
            <div class="form-group row">
                <label class="col-form-label col-lg-2">{{__('Subaccount share of payments')}}</label>
                <div class="col-lg-10">
                    <div class="input-group">
                    <input type="number" name="percent_share" class="form-control" min="1" max="99">
                    <div class="input-group-append">
                        <span class="input-group-text">%</span>
                    </div>
                    </div>
                </div>
            </div>  
            @endif                
            <div class="text-right">
                <button type="submit" class="btn btn-neutral btn-md">{{__('Save')}}</button>
            </div>
        </form>
      </div>
    </div>

@stop
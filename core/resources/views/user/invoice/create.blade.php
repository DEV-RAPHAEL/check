@extends('userlayout')

@section('content')
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-12">
        <!-- Basic layout-->
        <div class="card">
          <div class="card-header">
            <h3 class="mb-0 font-weight-bolder">{{__('Create invoice')}}</h3>
            <span class="form-text text-xs">Invoice charge is {{$set->invoice_charge}}%. Invoice is charged when invoice is paid by client. </span>
          </div>
          <div class="card-body">
            <form action="{{route('submit.invoice')}}" method="post">
              @csrf
              <div class="form-group row">
                <label class="col-form-label col-lg-2">{{__('Item Name')}}</label>
                <div class="col-lg-4">
                  <input type="text" name="item_name" class="form-control" placeholder="" required>
                </div>
                <label class="col-form-label col-lg-2">{{__('Invoice No')}}</label>
                <div class="col-lg-4">
                  <div class="input-group">
                    <span class="input-group-prepend">
                      <span class="input-group-text">#</span>
                    </span>
                    <input type="number" name="invoice_no" class="form-control" placeholder="123456" required>
                  </div>
                </div>
              </div>               
              <div class="form-group row">
                <label class="col-form-label col-lg-2">{{__('Quantity')}}</label>
                <div class="col-lg-4">
                  <input type="number" name="quantity" class="form-control" value="1" required>
                </div>
                <label class="col-form-label col-lg-2">{{__('Amount')}}</label>
                <div class="col-lg-4">
                  <div class="input-group">
                    <span class="input-group-prepend">
                      <span class="input-group-text">{{$currency->symbol}}</span>
                    </span>
                    <input type="number" step="any" class="form-control" name="amount" required>
                  </div>
                </div>
              </div>                             
              <div class="form-group row">
                <label class="col-form-label col-lg-2">{{__('Customer Email')}}</label>
                <div class="col-lg-4">
                  <input type="email" name="email" class="form-control" placeholder="" required>
                </div>
                <label class="col-form-label col-lg-2" for="exampleDatepicker">{{__('Due Date')}}</label>
                <div class="col-lg-4">
                  <div class="input-group">
                    <span class="input-group-prepend">
                      <span class="input-group-text"><i class="fad fa-calendar"></i></span>
                    </span>
                    <input type="text" class="form-control datepicker" name="due_date" value="{{Carbon\Carbon::now()}}" required>
                  </div>
                </div>
              </div>                              
              <div class="form-group row">
                <label class="col-form-label col-lg-2">{{__('Tax')}}</label>
                <div class="col-lg-4">
                  <div class="input-group">
                    <input type="number" name="tax" step="any" class="form-control" placeholder="">
                    <span class="input-group-append">
                      <span class="input-group-text">%</span>
                    </span>
                  </div>
                </div>
                <label class="col-form-label col-lg-2">{{__('Discount')}}</label>
                <div class="col-lg-4">
                  <div class="input-group">
                    <input type="number" name="discount" step="any" class="form-control" placeholder="">
                    <span class="input-group-append">
                        <span class="input-group-text">%</span>
                      </span>
                  </div>
                </div>
              </div>                              
              <div class="form-group row">
                <label class="col-form-label col-lg-2">{{__('Notes')}}</label>
                <div class="col-lg-10">
                  <div class="input-group">
                    <textarea type="text" class="form-control" rows="3" placeholder="Invoice note (Optional)"  name="notes"></textarea>
                  </div>
                </div>
              </div>             
              <div class="text-left">
                <button type="submit" class="btn btn-neutral btn-sm">{{__('Create Invoice')}}</a>
              </div>         
            </form>
          </div>
        </div>
        <!-- /basic layout -->
      </div>
    </div>
@stop
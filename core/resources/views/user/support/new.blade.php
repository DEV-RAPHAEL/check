@extends('userlayout')

@section('content')
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 mb-0">
                <div class="card-header">
                    <h3 class="mb-0 font-weight-bolder">{{__('New Ticket')}}</h3>
                </div>
                <div class="card-body">
                    <form action="{{route('submit-ticket')}}" method="post" enctype="multipart/form-data" >
                        @csrf
                        <div class="form-group row">
                            <label class="col-form-label col-lg-2">{{__('Subject')}}</label>
                            <div class="col-lg-10">
                                <div class="input-group input-group-merge">
                                <input type="text" name="subject" class="form-control" required="">
                                </div>
                            </div>
                        </div>                       
                        <div class="form-group row">
                            <label class="col-form-label col-lg-2">{{__('Reference')}} <span class="text-danger">*</span></label>
                            <div class="col-lg-10">
                                <div class="input-group input-group-merge">
                                <input type="text" name="ref_no" class="form-control" placeholder="Transaction reference number">
                                </div>
                            </div>
                        </div> 
                        <div class="form-group row">
                            <label class="col-form-label col-lg-2">{{__('Priority')}}</label>
                            <div class="col-lg-10">
                                <select class="form-control select" name="priority" required>
                                    <option  value="Low">{{__('Low')}}</option>
                                    <option  value="Medium">{{__('Medium')}}</option> 
                                    <option  value="High">{{__('High')}}</option>  
                                </select>
                            </div>
                        </div>                      
                        <div class="form-group row">
                            <label class="col-form-label col-lg-2">{{__('Type')}}</label>
                            <div class="col-lg-10">
                                <select class="form-control select" name="type" required>
                                    <option  value="subscription">{{__('Subscription')}}</option>
                                    <option  value="money_transfer">{{__('Money Transfer')}}</option> 
                                    <option  value="request_money">{{__('Request Money')}}</option>  
                                    <option  value="settlement">{{__('Settlement')}}</option>  
                                    <option  value="store">{{__('Store')}}</option>  
                                    <option  value="single_charge">{{__('Single Charge')}}</option>  
                                    <option  value="donation">{{__('Donation')}}</option>  
                                    <option  value="invoice">{{__('Invoice')}}</option>  
                                    <option  value="charges">{{__('Charges')}}</option>  
                                    <option  value="bank_transfer">{{__('Bank transfer')}}</option>  
                                    <option  value="deposit">{{__('Deposit')}}</option>  
                                    <option  value="virtual_card">{{__('Virtual Card')}}</option>  
                                    <option  value="bill_payment">{{__('Bill payment')}}</option>  
                                    <option  value="crypto_currency">{{__('Cryptocurrency')}}</option>  
                                    <option  value="others">{{__('Others')}}</option>  
                                </select>
                            </div>
                        </div> 
                        <div class="form-group row">
                            <label class="col-form-label col-lg-2">{{__('Details')}}</label>
                            <div class="col-lg-10">
                                <textarea name="details" class="form-control" rows="6" required placeholder="Description"></textarea>
                            </div>
                        </div>                        
                        <div class="form-group row">
                            <label class="col-form-label col-lg-2">{{__('Select a file')}} <span class="text-danger">*</span></label>
                            <div class="col-lg-10">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="customFileLang" name="image[]" multiple required>
                                    <label class="custom-file-label" for="customFileLang">{{__('Choose Media')}}</label>
                                </div> 
                            </div>
                        </div>                
                        <div class="text-right">
                            <button type="submit" class="btn btn-neutral btn-sm">{{__('Save')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>  
@stop
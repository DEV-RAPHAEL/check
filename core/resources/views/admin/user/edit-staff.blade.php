@extends('master')

@section('content')
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 mb-0">
                <div class="card-header">
                    <h3 class="mb-0">{{__('Edit Staff')}}</h3>
                </div>
                <div class="card-body">
                    <form action="{{route('staff.update')}}" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{$staff->id}}">
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <input type="text" name="first_name" class="form-control" placeholder="First Name" required value="{{$staff->first_name}}">
                            </div>
                        </div>                              
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <input type="text" name="last_name" class="form-control" placeholder="Last Name" required value="{{$staff->last_name}}">
                            </div>
                        </div>                            
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <input type="text" name="username" class="form-control" placeholder="Username" required value="{{$staff->username}}">
                            </div>
                        </div>    
                        <div class="form-group row">
                            <label class="col-form-label-castro col-lg-2">{{__('Password')}}</label>
                            <div class="col-lg-10">
                                <a data-toggle="modal" data-target="#modal-formx" href="" class="btn btn-white btn-sm">{{__('Change password')}}</a>
                            </div>
                        </div>                           
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <div class="custom-control custom-control-alternative custom-checkbox">
                                    @if($staff->profile==1)
                                    <input type="checkbox" name="profile" id="customCheckLogin1"  class="custom-control-input" value="1" checked>
                                    @else
                                    <input type="checkbox" name="profile" id="customCheckLogin1"  class="custom-control-input" value="1">
                                    @endif
                                    <label class="custom-control-label" for="customCheckLogin1">
                                    <span class="text-muted">{{__('Customer profile')}}</span>     
                                    </label>
                                </div>                  
                            </div>                               
                            <div class="col-lg-4">
                                <div class="custom-control custom-control-alternative custom-checkbox">
                                    @if($staff->support==1)
                                    <input type="checkbox" name="support" id="customCheckLogin2"  class="custom-control-input" value="1" checked>
                                    @else
                                    <input type="checkbox" name="support" id="customCheckLogin2"  class="custom-control-input" value="1">
                                    @endif
                                    <label class="custom-control-label" for="customCheckLogin2">
                                    <span class="text-muted">{{__('Support')}}</span>     
                                    </label>
                                </div>                  
                            </div>                               
                            <div class="col-lg-4">
                                <div class="custom-control custom-control-alternative custom-checkbox">
                                    @if($staff->promo==1)
                                    <input type="checkbox" name="promo" id="customCheckLogin3"  class="custom-control-input" value="1" checked>
                                    @else
                                    <input type="checkbox" name="promo" id="customCheckLogin3"  class="custom-control-input" value="1">
                                    @endif
                                    <label class="custom-control-label" for="customCheckLogin3">
                                    <span class="text-muted">{{__('Email Promotion')}}</span>     
                                    </label>
                                </div>                  
                            </div>                               
                            <div class="col-lg-4">
                                <div class="custom-control custom-control-alternative custom-checkbox">
                                    @if($staff->message==1)
                                    <input type="checkbox" name="message" id="customCheckLogin4"  class="custom-control-input" value="1" checked>
                                    @else
                                    <input type="checkbox" name="message" id="customCheckLogin4"  class="custom-control-input" value="1">
                                    @endif
                                    <label class="custom-control-label" for="customCheckLogin4">
                                    <span class="text-muted">{{__('Message')}}</span>     
                                    </label>
                                </div>                  
                            </div>                            
                            <div class="col-lg-4">
                                <div class="custom-control custom-control-alternative custom-checkbox">
                                    @if($staff->deposit==1)
                                    <input type="checkbox" name="deposit" id="customCheckLogin5"  class="custom-control-input" value="1" checked>
                                    @else
                                    <input type="checkbox" name="deposit" id="customCheckLogin5"  class="custom-control-input" value="1">
                                    @endif
                                    <label class="custom-control-label" for="customCheckLogin5">
                                    <span class="text-muted">{{__('Deposit')}}</span>     
                                    </label>
                                </div>                  
                            </div>                            
                            <div class="col-lg-4">
                                <div class="custom-control custom-control-alternative custom-checkbox">
                                    @if($staff->settlement==1)
                                    <input type="checkbox" name="settlement" id="customCheckLogin6"  class="custom-control-input" value="1" checked>
                                    @else
                                    <input type="checkbox" name="settlement" id="customCheckLogin6"  class="custom-control-input" value="1">
                                    @endif
                                    <label class="custom-control-label" for="customCheckLogin6">
                                    <span class="text-muted">{{__('Settlement')}}</span>     
                                    </label>
                                </div>                  
                            </div>                            
                            <div class="col-lg-4">
                                <div class="custom-control custom-control-alternative custom-checkbox">
                                    @if($staff->transfer==1)
                                    <input type="checkbox" name="transfer" id="customCheckLogin7"  class="custom-control-input" value="1" checked>
                                    @else
                                    <input type="checkbox" name="transfer" id="customCheckLogin7"  class="custom-control-input" value="1">
                                    @endif
                                    <label class="custom-control-label" for="customCheckLogin7">
                                    <span class="text-muted">{{__('Transfer')}}</span>     
                                    </label>
                                </div>                  
                            </div>                            
                            <div class="col-lg-4">
                                <div class="custom-control custom-control-alternative custom-checkbox">
                                    @if($staff->request_money==1)
                                    <input type="checkbox" name="request_money" id="customCheckLogin8"  class="custom-control-input" value="1" checked>
                                    @else
                                    <input type="checkbox" name="request_money" id="customCheckLogin8"  class="custom-control-input" value="1">
                                    @endif
                                    <label class="custom-control-label" for="customCheckLogin8">
                                    <span class="text-muted">{{__('Request Money')}}</span>     
                                    </label>
                                </div>                  
                            </div>                             
                            <div class="col-lg-4">
                                <div class="custom-control custom-control-alternative custom-checkbox">
                                    @if($staff->donation==1)
                                    <input type="checkbox" name="donation" id="customCheckLogin9"  class="custom-control-input" value="1" checked>
                                    @else
                                    <input type="checkbox" name="donation" id="customCheckLogin9"  class="custom-control-input" value="1">
                                    @endif
                                    <label class="custom-control-label" for="customCheckLogin9">
                                    <span class="text-muted">{{__('Donation')}}</span>     
                                    </label>
                                </div>                  
                            </div>                             
                            <div class="col-lg-4">
                                <div class="custom-control custom-control-alternative custom-checkbox">
                                    @if($staff->single_charge==1)
                                    <input type="checkbox" name="single_charge" id="customCheckLogin10"  class="custom-control-input" value="1" checked>
                                    @else
                                    <input type="checkbox" name="single_charge" id="customCheckLogin10"  class="custom-control-input" value="1">
                                    @endif
                                    <label class="custom-control-label" for="customCheckLogin10">
                                    <span class="text-muted">{{__('Single charge')}}</span>     
                                    </label>
                                </div>                  
                            </div>                             
                            <div class="col-lg-4">
                                <div class="custom-control custom-control-alternative custom-checkbox">
                                    @if($staff->subscription==1)
                                    <input type="checkbox" name="subscription" id="customCheckLogin11"  class="custom-control-input" value="1" checked>
                                    @else
                                    <input type="checkbox" name="subscription" id="customCheckLogin11"  class="custom-control-input" value="1">
                                    @endif
                                    <label class="custom-control-label" for="customCheckLogin11">
                                    <span class="text-muted">{{__('Subscription')}}</span>     
                                    </label>
                                </div>                  
                            </div>                             
                            <div class="col-lg-4">
                                <div class="custom-control custom-control-alternative custom-checkbox">
                                    @if($staff->merchant==1)
                                    <input type="checkbox" name="merchant" id="customCheckLogin12"  class="custom-control-input" value="1" checked>
                                    @else
                                    <input type="checkbox" name="merchant" id="customCheckLogin12"  class="custom-control-input" value="1">
                                    @endif
                                    <label class="custom-control-label" for="customCheckLogin12">
                                    <span class="text-muted">{{__('Merchant')}}</span>     
                                    </label>
                                </div>                  
                            </div>                         
                            <div class="col-lg-4">
                                <div class="custom-control custom-control-alternative custom-checkbox">
                                    @if($staff->invoice==1)
                                    <input type="checkbox" name="invoice" id="customCheckLogin13"  class="custom-control-input" value="1" checked>
                                    @else
                                    <input type="checkbox" name="invoice" id="customCheckLogin13"  class="custom-control-input" value="1">
                                    @endif
                                    <label class="custom-control-label" for="customCheckLogin13">
                                    <span class="text-muted">{{__('Invoice')}}</span>     
                                    </label>
                                </div>                  
                            </div>                         
                            <div class="col-lg-4">
                                <div class="custom-control custom-control-alternative custom-checkbox">
                                    @if($staff->charges==1)
                                    <input type="checkbox" name="charges" id="customCheckLogin14"  class="custom-control-input" value="1" checked>
                                    @else
                                    <input type="checkbox" name="charges" id="customCheckLogin14"  class="custom-control-input" value="1">
                                    @endif
                                    <label class="custom-control-label" for="customCheckLogin14">
                                    <span class="text-muted">{{__('Charges')}}</span>     
                                    </label>
                                </div>                  
                            </div>                         
                            <div class="col-lg-4">
                                <div class="custom-control custom-control-alternative custom-checkbox">
                                    @if($staff->store==1)
                                    <input type="checkbox" name="store" id="customCheckLogin15"  class="custom-control-input" value="1" checked>
                                    @else
                                    <input type="checkbox" name="store" id="customCheckLogin15"  class="custom-control-input" value="1">
                                    @endif
                                    <label class="custom-control-label" for="customCheckLogin15">
                                    <span class="text-muted">{{__('Store')}}</span>     
                                    </label>
                                </div>                  
                            </div>                         
                            <div class="col-lg-4">
                                <div class="custom-control custom-control-alternative custom-checkbox">
                                    @if($staff->blog==1)
                                    <input type="checkbox" name="blog" id="customCheckLogin16"  class="custom-control-input" value="1" checked>
                                    @else
                                    <input type="checkbox" name="blog" id="customCheckLogin16"  class="custom-control-input" value="1">
                                    @endif
                                    <label class="custom-control-label" for="customCheckLogin16">
                                    <span class="text-muted">{{__('Blog')}}</span>     
                                    </label>
                                </div>                  
                            </div>                             
                            <div class="col-lg-4">
                                <div class="custom-control custom-control-alternative custom-checkbox">
                                    @if($staff->bill==1)
                                    <input type="checkbox" name="bill" id="customCheckLogin17"  class="custom-control-input" value="1" checked>
                                    @else
                                    <input type="checkbox" name="bill" id="customCheckLogin17"  class="custom-control-input" value="1">
                                    @endif
                                    <label class="custom-control-label" for="customCheckLogin17">
                                    <span class="text-muted">{{__('Bill')}}</span>     
                                    </label>
                                </div>                  
                            </div>                             
                            <div class="col-lg-4">
                                <div class="custom-control custom-control-alternative custom-checkbox">
                                    @if($staff->vcard==1)
                                    <input type="checkbox" name="vcard" id="customCheckLogin18"  class="custom-control-input" value="1" checked>
                                    @else
                                    <input type="checkbox" name="vcard" id="customCheckLogin18"  class="custom-control-input" value="1">
                                    @endif
                                    <label class="custom-control-label" for="customCheckLogin18">
                                    <span class="text-muted">{{__('Virtual Card')}}</span>     
                                    </label>
                                </div>                  
                            </div>        
                            <!--                     
                            <div class="col-lg-4">
                                <div class="custom-control custom-control-alternative custom-checkbox">
                                    @if($staff->crypto==1)
                                    <input type="checkbox" name="crypto" id="customCheckLogin19"  class="custom-control-input" value="1" checked>
                                    @else
                                    <input type="checkbox" name="crypto" id="customCheckLogin19"  class="custom-control-input" value="1">
                                    @endif
                                    <label class="custom-control-label" for="customCheckLogin19">
                                    <span class="text-muted">{{__('Crypto')}}</span>     
                                    </label>
                                </div>                  
                            </div>    
                            -->                                         
                        </div>                  
                        <div class="text-right">
                            <button type="submit" class="btn btn-success btn-sm">{{__('Save')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> 
    <div class="modal fade" id="modal-formx" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
        <div class="modal-dialog modal- modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
            <div class="card bg-white border-0 mb-0">
                <div class="card-header header-elements-inline">
                <h3 class="mb-0">{{__('Change Password')}}</h3>
                </div>
                <div class="card-body px-lg-5 py-lg-5">
                <form action="{{route('staff.password')}}" method="post">
                    @csrf
                    <div class="form-group row">
                    <label class="col-form-label col-lg-4">{{__('New Password')}}</label>
                    <div class="col-lg-8">
                        <input type="hidden" name="id" value="{{$staff->id}}">
                        <input type="password" name="password" class="form-control" minlength="6" placeholder="New Password" required>
                    </div>
                    </div>             
                    <div class="text-right">
                    <button type="submit" class="btn btn-success btn-sm">{{__('Change Password')}}</button>
                    </div>
                </form>
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>  
@stop
@extends('userlayout')

@section('content')
<!-- Page content -->
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-12">
        <div class="nav-wrapper">
          <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
              <li class="nav-item">
                  <a class="nav-link mb-sm-3 mb-md-0 @if(route('user.profile')==url()->current()) active @endif" id="tabs-icons-text-1-tab" data-toggle="tab" href="#tabs-icons-text-1" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true"><i class="fad fa-user"></i> Profile</a>
              </li>
              <li class="nav-item">
                  <a class="nav-link mb-sm-3 mb-md-0 @if(route('user.security')==url()->current()) active @endif" id="tabs-icons-text-2-tab" data-toggle="tab" href="#tabs-icons-text-2" role="tab" aria-controls="tabs-icons-text-2" aria-selected="fadse"><i class="fad fa-cogs"></i> Security</a>
              </li>
              <li class="nav-item">
                  <a class="nav-link mb-sm-3 mb-md-0 @if(route('user.api')==url()->current()) active @endif" id="tabs-icons-text-3-tab" data-toggle="tab" href="#tabs-icons-text-3" role="tab" aria-controls="tabs-icons-text-3" aria-selected="fadse"><i class="fad fa-key"></i> API Keys</a>
              </li>        
              <li class="nav-item">
                  <a class="nav-link mb-sm-3 mb-md-0 @if(route('user.social')==url()->current()) active @endif" id="tabs-icons-text-4-tab" data-toggle="tab" href="#tabs-icons-text-4" role="tab" aria-controls="tabs-icons-text-4" aria-selected="fadse"><i class="fad fa-share-square"></i> Social Profile</a>
              </li>                
              <li class="nav-item">
                  <a class="nav-link mb-sm-3 mb-md-0 @if(route('user.bank')==url()->current()) active @endif" id="tabs-icons-text-5-tab" data-toggle="tab" href="#tabs-icons-text-5" role="tab" aria-controls="tabs-icons-text-5" aria-selected="fadse"><i class="fad fa-university"></i> Bank Accounts</a>
              </li>              
              <li class="nav-item">
                  <a class="nav-link mb-sm-3 mb-md-0 @if(route('user.compliance')==url()->current()) active @endif" id="tabs-icons-text-6-tab" data-toggle="tab" href="#tabs-icons-text-6" role="tab" aria-controls="tabs-icons-text-6" aria-selected="fadse"><i class="fad fa-globe"></i> Compliance</a>
              </li>        
          </ul>
        </div>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade @if(route('user.profile')==url()->current())show active @endif" id="tabs-icons-text-1" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
              <div class="row">
                <div class="col-md-8">
                    <div class="card">
                      <div class="card-header header-elements-inline">
                      <h3 class="mb-0 font-weight-bolder">{{__('Profile')}}</h3>
                    </div>
                    <div class="card-body">
                      <form action="{{url('user/account')}}" method="post">
                        @csrf
                          <div class="form-group row">
                            <label class="col-form-label col-lg-3">{{__('Full Name')}}</label>
                            <div class="col-lg-9">
                              <div class="row">
                                  <div class="col-6">
                                    <input type="text" name="first_name" class="form-control" placeholder="First Name" value="{{$user->first_name}}">
                                  </div>      
                                  <div class="col-6">
                                    <input type="text" name="last_name" class="form-control" placeholder="Last Name" value="{{$user->last_name}}">
                                  </div>
                              </div>
                            </div>
                          </div>  
                          <div class="form-group row">
                            <label class="col-form-label col-lg-3">{{__('Business Name')}}</label>
                            <div class="col-lg-9">
                              <input type="text" name="business_name" class="form-control" placeholder="Your Business Name" value="{{$user->business_name}}" required>
                              <span class="form-text text-xs">{{__('Your business name is the official name of your company. It should be the same as the name on your registration documents.')}}</span>
                            </div>
                          </div>   
                          <div class="form-group row">
                            <label class="col-form-label col-lg-3">{{__('Phone Number')}}</label>
                            <div class="col-lg-9">
                              <div class="input-group">
                                <div class="input-group-prepend">
                                  <span class="input-group-text"><i class="fad fa-phone-alt"></i></span>
                                </div>
                                <input type="number" inputmode="numeric" name="phone" maxlength="14" class="form-control" value="{{$user->phone}}">
                              </div>
                            </div>
                          </div>     
                          <div class="form-group row">
                            <label class="col-form-label col-lg-3">{{__('Email Address')}}</label>
                            <div class="col-lg-9">
                              <div class="input-group">
                                <div class="input-group-prepend">
                                  <span class="input-group-text"><i class="fad fa-envelope"></i></span>
                                </div>
                                <input type="email" name="email" class="form-control" placeholder="{{__('Email Address')}}" value="{{$user->email}}">
                              </div>
                            </div>
                          </div>                          
                          <div class="form-group row">
                            <label class="col-form-label col-lg-3">{{__('Support Email')}}</label>
                            <div class="col-lg-9">
                              <div class="input-group">
                                <div class="input-group-prepend">
                                  <span class="input-group-text"><i class="fad fa-envelope"></i></span>
                                </div>
                                <input type="email" name="support_email" class="form-control" placeholder="{{__('Email Address')}}" value="{{$user->support_email}}">
                              </div>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-form-label col-lg-3">{{__('Country')}}</label>
                            <div class="col-lg-9">
                              <select class="form-control select" disabled name="country" required>
                                  <option value="">{{__('Select Country')}}</option> 
                                    @foreach($country as $val)
                                      <option value="{{$val->country_id}}" @if($val->country_id==$user->country) selected @endif>{{$val->real['nicename']}}</option>
                                    @endforeach
                              </select>
                            </div>
                          </div>                               
                        <div class="text-right">
                          <button type="submit" class="btn btn-neutral btn-sm">{{__('Save Changes')}}</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="card">
                    <div class="card-body text-center">
                      <h3 class="mb-0 font-weight-bolder">{{__('Business Logo')}}</h3>
                      <p class="card-text text-sm">{{__('We recommend you use a square logo with dimensions 172px by 172px for best results on checkout forms.')}}</p>
                      <a href="#" class="avatar text-center">
                        <img src="{{url('/')}}/asset/profile/{{$cast}}"/>
                      </a>
                      <form action="{{url('user/avatar')}}" enctype="multipart/form-data" method="post">
                      @csrf
                          <div class="form-group">
                            <div class="custom-file">
                              <input type="file" class="custom-file-input" id="customFileLang" name="image" accept="image/*" required>
                              <label class="custom-file-label" for="customFileLang">{{__('Choose Media')}}</label>
                            </div> 
                          </div>              
                        <div class="text-right">
                          <button type="submit" class="btn btn-neutral btn-block">{{__('Change Logo')}}</button>
                        </div>
                      </form>
                    </div>
                  </div>
                  <div class="card">
                    <div class="card-body text-center">
                      <h3 class="mb-0 font-weight-bolder">{{__('Delete Account')}}</h3>
                      <p class="card-text text-sm mb-2">{{__('Closing this account means you will no longer be able to access this account on')}} {{$set->site_name}}</p>
                      <div class="text-right">
                          <a data-toggle="modal" data-target="#modal-formp" href="" class="btn btn-danger btn-block"><i class="fa fa-trash"></i> {{__('Delete Account')}}</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane fade @if(route('user.security')==url()->current())show active @endif" id="tabs-icons-text-2" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
              <div class="card">
                <div class="card-header">
                  <h3 class="mb-0 font-weight-bolder">{{__('Password')}}</h3>
                </div>
                <div class="card-body">
                  <form action="{{route('change.password')}}" method="post">
                    @csrf
                    <div class="form-group row">
                      <div class="col-lg-12">
                          <input type="password" name="password" class="form-control" placeholder="{{__('Current password')}}" required>
                      </div>
                  </div>                
                  <div class="form-group row">
                      <div class="col-lg-12">
                          <input type="password" name="new_password" id="new_password" class="form-control" placeholder="{{__('New password')}}" required>
                          <span class="error form-error-msg"><span id="result"></span></span>
                          
                      </div>
                  </div>                
                  <div class="form-group row">
                      <div class="col-lg-12">
                          <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="{{__('Confirm password')}}" required>
                          <span class="error form-error-msg" id="msg"></span>
                      </div>
                  </div> 
                  <h4 class="mb-0 text-dark font-weight-bolder">{{__('Password requirements')}}</h4>
                  <p class="mb-2 text-default text-sm">{{__('Ensure that these requirements are met')}}</p>
                  <ul class="text-default text-sm">
                    <li>{{__('Minimum 8 characters long - the more, the better')}}</li>
                    <li>{{__('At least one lowercase character.')}}</li>
                    <li>{{__('At least one uppercase character.')}}</li>
                    <li>{{__('At least one number, symbol, or whitespace character.')}}</li>
                  </ul>           
                  <div class="text-right">
                    <button type="submit" class="btn btn-neutral btn-sm">{{__('Change Password')}}</button>
                  </div>
                  </form>
                </div>
              </div>
              <div class="card">
                <div class="card-header">
                  <h3 class="mb-0 font-weight-bolder">{{__('Two-Factor Security Option')}}</h3>
                </div>
                <div class="card-body">
                  <div class="align-item-sm-center flex-sm-nowrap text-left">
                    <p class="text-sm mb-2">
                      {{__('Two-factor authentication is a method for protection your web account. 
                        When it is activated you need to enter not only your password, but also a special code. 
                        You can receive this code by in mobile app. 
                        Even if third person will find your password, then cant access with that code.')}}
                    </p>
                    <span class="badge badge-pill badge-primary mb-3">
                      @if($user->fa_status==0)
                      {{__('Disabled')}}
                      @else
                      {{__('Active')}}
                      @endif
                    </span>
                    <ul class="text-default text-sm">
                      <li>{{__('Install an authentication app on your device. Any app that supports the Time-based One-Time Password (TOTP) protocol should work.')}}</li>
                      <li>{{__('Use the authenticator app to scan the barcode below.')}}</li>
                      <li>{{__('Enter the code generated by the authenticator app.')}}</li>
                    </ul>
                    <a data-toggle="modal" data-target="#modal-form2fa" href="" class="btn btn-neutral btn-sm">
                    @if($user->fa_status==0)
                      {{__('Enable 2fa')}}
                    @elseif($user->fa_status==1)
                      {{__('Disable 2fa')}}
                    @endif
                    </a>
                      <div class="modal fade" id="modal-form2fa" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                          <div class="modal-content">
                            <div class="modal-body text-center">
                              @if($user->fa_status==0)
                                <img src="{{$image}}" class="mb-3 user-profile">
                              @endif
                              <form action="{{route('change.2fa')}}" method="post">
                                @csrf
                                <div class="form-group row">
                                  <div class="col-lg-12">
                                    <input type="text" pattern="\d*" name="code" class="form-control" minlength="6" maxlength="6" placeholder="Six digit code" required>
                                      <input type="hidden"  name="vv" value="{{$secret}}">
                                    @if($user->fa_status==0)
                                      <input type="hidden"  name="type" value="1">
                                    @elseif($user->fa_status==1)
                                      <input type="hidden"  name="type" value="0">
                                    @endif
                                  </div>
                                </div>            
                                <div class="text-right">
                                  <button type="submit" class="btn btn-neutral btn-block">
                                  @if($user->fa_status==0)
                                    {{__('Enable 2fa')}}
                                  @elseif($user->fa_status==1)
                                    {{__('Disable 2fa')}}
                                  @endif
                                  </button>
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>
                      </div> 
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane fade @if(route('user.api')==url()->current())show active @endif" id="tabs-icons-text-3" role="tabpanel" aria-labelledby="tabs-icons-text-3-tab">
              <div class="card">
                <div class="card-header header-elements-inline">
                  <h3 class="mb-0 font-weight-bolder">{{__('API Keys')}}</h3>
                </div>
                <div class="card-body">
                  <form action="{{route('generateapi')}}" method="post">
                    @csrf
                    <div class="form-group row">
                  <div class="col-lg-12">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text text-xs text-uppercase">{{__('Public key')}}</span>
                      </div> 
                      <input type="text" name="public_key" class="form-control" placeholder="Public key" value="{{$user->public_key}}">   
                      <div class="input-group-prepend">
                        <span class="input-group-text btn-icon-clipboard" data-clipboard-text="{{$user->public_key}}" title="Copy"><i class="fad fa-copy"></i></span>
                      </div> 
                    </div>
                  </div>
                </div>                
                <div class="form-group row">
                  <div class="col-lg-12">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text text-xs text-uppercase">{{__('Secret key')}}</span>
                      </div> 
                      <input type="text" name="secret_key" class="form-control" placeholder="Secret key" value="{{$user->secret_key}}">   
                      <div class="input-group-prepend">
                        <span class="input-group-text btn-icon-clipboard" data-clipboard-text="{{$user->secret_key}}" title="Copy"><i class="fad fa-copy"></i></span>
                      </div> 
                    </div> 
                  </div>
                </div>                                    
                    <div class="text-right">
                      <button type="submit" class="btn btn-neutral btn-sm">{{__('Generate New Keys')}}</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>      
            <div class="tab-pane fade @if(route('user.social')==url()->current())show active @endif" id="tabs-icons-text-4" role="tabpanel" aria-labelledby="tabs-icons-text-4-tab">
              <div class="card">
                <div class="card-header header-elements-inline">
                  <h3 class="mb-0 font-weight-bolder">{{__('Social Link')}}</h3>
                </div>
                <div class="card-body">
                  <form action="{{route('user.social')}}" method="post">
                    @csrf
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2">{{__('Facebook')}}</label>
                      <div class="col-lg-10">
                        <input type="url" name="facebook" class="form-control" placeholder="Your Facebook Profile Link" value="{{$user->facebook}}">    
                      </div>
                    </div>                
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2">{{__('Twitter')}}</label>
                      <div class="col-lg-10">
                        <input type="url" name="twitter" class="form-control" placeholder="Your Twitter Profile Link" value="{{$user->twitter}}">    
                      </div>
                    </div>                
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2">{{__('Instagram')}}</label>
                      <div class="col-lg-10">
                        <input type="url" name="instagram" class="form-control" placeholder="Your Instagram Profile Link" value="{{$user->instagram}}">    
                      </div>
                    </div>                
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2">{{__('LinkedIn')}}</label>
                      <div class="col-lg-10">
                        <input type="url" name="linkedin" class="form-control" placeholder="Your LinkedIn Profile Link" value="{{$user->linkedin}}">    
                      </div>
                    </div>               
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2">{{__('Youtube')}}</label>
                      <div class="col-lg-10">
                        <input type="url" name="youtube" class="form-control" placeholder="Your Youtube Channel Link" value="{{$user->youtube}}">    
                      </div>
                    </div>                     
                    <div class="text-right">
                      <button type="submit" class="btn btn-neutral btn-sm">{{__('Save Changes')}}</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>            
            <div class="tab-pane fade @if(route('user.bank')==url()->current())show active @endif" id="tabs-icons-text-5" role="tabpanel" aria-labelledby="tabs-icons-text-5-tab">
              @if($set->stripe_connect==0)
                <div class="row align-items-center py-4">
                  <div class="col-lg-6 col-5 text-left">
                    <a data-toggle="modal" data-target="#modal-formx" href="" class="btn btn-sm btn-neutral"><i class="fad fa-plus"></i> {{__('Bank Account')}}</a>
                  </div>
                </div>
              @endif
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
                                <select class="form-control select" name="bank" required>
                                    <option value="">{{__('Select Bank')}}</option> 
                                        @foreach($bnk as $val)
                                        <option value="{{$val->id}}">{{$val->name}}</option>
                                        @endforeach
                                </select>
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
                @if(count($bank)>0) 
                  @foreach($bank as $k=>$val)
                    <div class="col-md-6">
                        <div class="card">
                          <div class="card-body">
                            <div class="row mb-2">
                              <div class="col-6">
                                <h3 class="mb-0 font-weight-bolder">{{$val->dabank['name']}}</h3>
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
                                <p class="text-sm mb-0 font-weight-bolder text-uppercase">{{__('Default account')}}: @if($val->status==1) Yes @else No @endif</p>
                                <p class="text-sm mb-0 font-weight-bolder text-uppercase">{{__('Account Name')}}: {{$val->acct_name}}</p>
                                <p class="text-sm mb-0 font-weight-bolder text-uppercase">{{__('Routing No/Sort Code')}}: {{$val->routing_number}}</p>
                                <p class="text-sm mb-2 font-weight-bolder text-uppercase">{{__('Account Type')}}: {{$val->account_type}}</p>
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
                                      <select class="form-control select" name="bank" required>
                                          <option value="">{{__('Select Bank')}}</option> 
                                              @foreach($bnk as $xval)
                                              <option value="{{$xval->id}}" @if($xval->id==$val->bank_id) selected @endif>{{$xval->name}}</option>
                                              @endforeach
                                      </select>
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
                              <div class="form-group">
                                <div class="input-group">
                                  <div class="input-group-prepend">
                                    <span class="input-group-text">#</span>
                                  </div>
                                  <input class="form-control" placeholder="{{ __('Routing Number / Sort Code') }}" type="text" name="routing_number" value="{{$val['routing_number']}}" required>
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
                {{ $bank->links('pagination::bootstrap-4') }}
                </div>
              </div>
            </div>
            <div class="tab-pane fade @if(route('user.compliance')==url()->current())show active @endif" id="tabs-icons-text-6" role="tabpanel" aria-labelledby="tabs-icons-text-6-tab">
              <form action="{{route('submit.compliance')}}" method="post" enctype="multipart/form-data">
                <div class="card">
                  <div class="card-header header-elements-inline">
                    <h3 class="mb-0 font-weight-bolder">{{__('Compliance')}}</h3>
                  </div>
                  <div class="card-body">
                    @csrf
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2">{{__('Business Name')}}</label>
                      <div class="col-lg-10">
                        <input type="text" name="trading_name" @if($ver->status==1 || $user->business_level==3) disabled @endif class="form-control" value="{{$ver->trading_name}}" required>    
                      </div>
                    </div>                
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2">{{__('Description')}}</label>
                      <div class="col-lg-10">
                        <textarea type="text" name="trading_desc" @if($ver->status==1 || $user->business_level==3) disabled @endif class="form-control" required>{{$ver->description}}</textarea>  
                      </div>
                    </div>   
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2">{{__('Staff Size')}}</label>
                      <div class="col-lg-10">
                          <select class="form-control select" name="staff_size" @if($ver->status==1 || $user->business_level==3) disabled @endif required>
                              <option value="1-5" @if($ver->staff_size=="1-5") selected @endif>1-5 people</option> 
                              <option value="5-50" @if($ver->staff_size=="5-50") selected @endif>5-50 people</option> 
                              <option value="50+" @if($ver->staff_size=="50+") selected @endif>50+ people</option> 
                          </select>
                      </div>
                    </div> 
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2">{{__('Industry')}}</label>
                      <div class="col-lg-10">
                          <select class="form-control select" name="industry" @if($ver->status==1 || $user->business_level==3) disabled @endif id="industry" required>
                          </select>
                          <span class="text-xs text-gray">Current Category: {{$ver->industry}}</span>
                      </div>
                    </div>                    
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2">{{__('Category')}}</label>
                      <div class="col-lg-10">
                          <select class="form-control select" name="category" @if($ver->status==1 || $user->business_level==3) disabled @endif id="category" required>
                          </select>
                          <span class="text-xs text-gray">Current Category: {{$ver->category}}</span>
                      </div>
                    </div> 
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2">{{__('Address')}}</label>
                      <div class="col-lg-10">
                          <input type="text" name="address" class="form-control" @if($ver->status==1 || $user->business_level==3) disabled @endif value="{{$ver->address}}">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2">{{__('Phone')}}</label>
                      <div class="col-lg-10">
                          <input type="number" name="phone" class="form-control" @if($ver->status==1 || $user->business_level==3) disabled @endif value="{{$ver->phone}}">
                      </div>
                    </div>                      
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2">{{__('Email')}}</label>
                      <div class="col-lg-10">
                          <input type="email" name="email" class="form-control" @if($ver->status==1 || $user->business_level==3) disabled @endif value="{{$ver->email}}">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2">{{__('Website')}}</label>
                      <div class="col-lg-10">
                          <input type="url" name="website" class="form-control" @if($ver->status==1 || $user->business_level==3) disabled @endif value="{{$ver->website}}">
                      </div>
                    </div>  
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2">{{__('Business Type')}}</label>
                      <div class="col-lg-10">
                        <select class="form-control select" name="business_type" @if($ver->status==1 || $user->business_level==3) disabled @endif id="seeAnotherField" required>
                            <option value="Starter Business" @if($ver->business_type=="Starter Business") selected @endif>Starter Business</option> 
                            <option value="Registered Business" @if($ver->business_type=="Registered Business") selected @endif>Registered Business</option> 
                        </select>
                        <span class="text-xs text-gray">Starter Business can only accept up to {{$currency->name.number_format($set->starter_limit)}} without business registration documents.</span>
                      </div>
                    </div>     
                    <div class="form-group row" id="otherFieldDiv">
                      <label class="col-form-label col-lg-2">{{__('Legal Business Name')}}</label>
                      <div class="col-lg-10">
                        <input type="text" name="legal_name" class="form-control" @if($ver->status==1 || $user->business_level==3) disabled @endif value="{{$ver->legal_name}}" id="otherField">    
                      </div>
                    </div>                        
                    <div class="form-group row" id="6xxotherFieldDiv">
                        <label class="col-form-label col-lg-2">{{__('Tax ID')}}</label>
                        <div class="col-lg-10">
                            <input type="text" name="tax_id" class="form-control" @if($ver->status==1 || $user->business_level==3) disabled @endif value="{{$ver->tax_id}}"  id="6xxotherField">
                        </div>
                    </div>                          
                    <div class="form-group row" id="fFieldDiv">
                        <label class="col-form-label col-lg-2">{{__('Vat ID')}}</label>
                        <div class="col-lg-10">
                            <input type="text" name="vat_id" class="form-control" @if($ver->status==1 || $user->business_level==3) disabled @endif value="{{$ver->vat_id}}"  id="fField">
                        </div>
                    </div>                        
                    <div class="form-group row" id="ffFieldDiv">
                        <label class="col-form-label col-lg-2">{{__('Registration No')}}</label>
                        <div class="col-lg-10">
                            <input type="text" name="reg_no" class="form-control" @if($ver->status==1 || $user->business_level==3) disabled @endif value="{{$ver->reg_no}}"  id="ffField">
                        </div>
                    </div>     
                    <div class="form-group row" id="otherFieldDiv1">
                      <label class="col-form-label col-lg-2">{{__('Registration Type')}}</label>
                      <div class="col-lg-10">
                          <select class="form-control select" name="registration_type" @if($ver->status==1 || $user->business_level==3) disabled @endif id="otherField1">
                              <option value="government_instrumentality" @if($ver->registration_type=="government_instrumentality") selected @endif>government instrumentality</option> 
                              <option value="governmental_unit" @if($ver->registration_type=="governmental_unit") selected @endif>governmental unit</option> 
                              <option value="incorporated_non_profit" @if($ver->registration_type=="incorporated_non_profit") selected @endif>incorporated non profit</option> 
                              <option value="limited_liability_partnership" @if($ver->registration_type=="limited_liability_partnership") selected @endif>limited liability partnership</option> 
                              <option value="multi_member_llc" @if($ver->registration_type=="multi_member_llc") selected @endif>multi member llc</option> 
                              <option value="private_company" @if($ver->registration_type=="private_company") selected @endif>private company</option> 
                              <option value="private_corporation" @if($ver->registration_type=="private_corporation") selected @endif>private corporation</option> 
                              <option value="private_partnership" @if($ver->registration_type=="private_partnership") selected @endif>private partnership</option> 
                              <option value="public_company" @if($ver->registration_type=="public_company") selected @endif>public company</option> 
                              <option value="public_corporation" @if($ver->registration_type=="public_corporation") selected @endif>public corporation</option> 
                              <option value="public_partnership" @if($ver->registration_type=="public_partnership") selected @endif>public partnership</option> 
                              <option value="single_member_llc" @if($ver->registration_type=="single_member_llc") selected @endif>single member llc</option> 
                              <option value="sole_proprietorship" @if($ver->registration_type=="sole_proprietorship") selected @endif>sole proprietorship</option> 
                              <option value="tax_exempt_government_instrumentality" @if($ver->registration_type=="tax_exempt_government_instrumentality") selected @endif>tax exempt government instrumentality</option> 
                              <option value="unincorporated_association" @if($ver->registration_type=="unincorporated_association") selected @endif>unincorporated association</option> 
                              <option value="unincorporated_non_profit" @if($ver->registration_type=="unincorporated_non_profit") selected @endif>unincorporated non profit</option> 
                          </select>
                      </div>
                    </div>    
                    @if($ver->status==0 || $user->business_level==2)                   
                      <div class="form-group row" id="otherFieldDiv2">
                        <label class="col-form-label col-lg-2">{{__('Proof of Registration')}}</label>
                        <div class="col-lg-5">
                          <div class="custom-file">
                            <input type="file" class="custom-file-input" id="customFileLangx" @if($ver->status==1 || $user->business_level==3) disabled @endif name="proof" accept="image/*">
                            <label class="custom-file-label" for="customFileLangx">{{__('Front')}}</label>
                          </div> 
                        </div>
                        <div class="col-lg-5">
                          <div class="custom-file">
                            <input type="file" class="custom-file-input sdsc" id="customFileLang4" @if($ver->status==1 || $user->business_level==3) disabled @endif name="proof_back" accept="image/*">
                            <label class="custom-file-label sdsc" for="customFileLang4">{{__('Back')}}</label>
                          </div> 
                        </div>
                      </div> 
                    @else
                        <a href="{{url('/')}}/asset/profile/{{$ver->proof}}">{{__('View Proof of Registration [Front]')}}</a><br>
                        <a href="{{url('/')}}/asset/profile/{{$ver->proof_back}}">{{__('View Proof of Registration [Back]')}}</a><br>
                    @endif                      
                    @if($ver->status==0 || $user->business_level==2)                   
                      <div class="form-group row">
                        <label class="col-form-label col-lg-2">{{__('Proof of Address')}}</label>
                        <div class="col-lg-10">
                          <div class="custom-file">
                            <input type="file" class="custom-file-input sdsx" id="customFileLang2" @if($ver->status==1 || $user->business_level==3) disabled @endif name="paddress" accept="image/*" required>
                            <label class="custom-file-label sdsx" for="customFileLang2">{{__('Select document')}}</label>
                          </div> 
                        </div>
                      </div> 
                    @else
                      <a href="{{url('/')}}/asset/profile/{{$ver->paddress}}">{{__('View Proof of Address')}}</a><br>
                    @endif 
                    <div id="otherFieldDiv3">
                      <div class="form-group row">
                        <label class="col-form-label col-lg-2">{{__('Full Name')}}</label>
                        <div class="col-lg-10">
                          <div class="row">
                              <div class="col-6">
                                <input type="text" name="first_name" class="form-control" @if($ver->status==1 || $user->business_level==3) disabled @endif  value="{{$ver->first_name}}" placeholder="First Name" id="1otherField">
                              </div>      
                              <div class="col-6">
                                <input type="text" name="last_name" class="form-control" @if($ver->status==1 || $user->business_level==3) disabled @endif value="{{$ver->last_name}}" placeholder="Last Name" id="2otherField">
                              </div>
                          </div>
                        </div>
                      </div>  
                      <div class="form-group row">
                        <label class="col-form-label col-lg-2">{{__('Date of Birth')}}</label>
                        <div class="col-lg-10">
                          <div class="row">
                              <div class="col-4">
                                <select class="form-control select" name="b_month" @if($ver->status==1 || $user->business_level==3) disabled @endif id="3otherField">
                                  <option value="1" @if($ver->month=="1") selected @endif>Jan</option>
                                  <option value="2" @if($ver->month=="2") selected @endif>Feb</option>
                                  <option value="3" @if($ver->month=="3") selected @endif>Mar</option>
                                  <option value="4" @if($ver->month=="4") selected @endif>Apr</option>
                                  <option value="5" @if($ver->month=="5") selected @endif>May</option>
                                  <option value="6" @if($ver->month=="6") selected @endif>Jun</option>
                                  <option value="7" @if($ver->month=="7") selected @endif>Jul</option>
                                  <option value="8" @if($ver->month=="8") selected @endif>Aug</option>
                                  <option value="9" @if($ver->month=="9") selected @endif>Sep</option>
                                  <option value="10" @if($ver->month=="10") selected @endif>Oct</option>
                                  <option value="11" @if($ver->month=="11") selected @endif>Nov</option>
                                  <option value="12" @if($ver->month=="12") selected @endif>Dec</option> 
                                </select>
                              </div>      
                              <div class="col-4">
                                <input type="number" name="b_day" class="form-control" @if($ver->status==1 || $user->business_level==3) disabled @endif placeholder="Day" value="{{$ver->day}}" min="1" max="30" value="{{$user->last_name}}" id="4otherField">
                              </div>                            
                              <div class="col-4">
                                <input type="number" name="b_year" class="form-control" @if($ver->status==1 || $user->business_level==3) disabled @endif placeholder="Year" value="{{$ver->year}}" min="1950" max="{{date('Y')}}" id="5otherField">
                              </div>
                          </div>
                        </div>
                      </div>  
                      <div class="form-group row"> 
                        <label class="col-form-label col-lg-2">{{__('Nationality')}}</label>                          
                        <div class="col-lg-10">
                            <select class="form-control custom-select" name="nationality" @if($ver->status==1 || $user->business_level==3) disabled @endif id="country" id="7otherField">
                            </select>
                            <span class="text-xs text-gray">Current Nationality: {{$ver->nationality}}</span>
                        </div>
                      </div>  
                      <div class="form-group row">
                        <label class="col-form-label col-lg-2">{{__('Gender')}}</label>                          
                        <div class="col-lg-10">
                          <select class="form-control select" name="gender" @if($ver->status==1 || $user->business_level==3) disabled @endif id="70otherField">
                            <option value="male" @if($ver->gender=="male") selected @endif>{{__('Male')}}</option>
                            <option value="female" @if($ver->gender=="female") selected @endif>{{__('Female')}}</option>
                          </select>
                        </div>                            
                      </div>                        
                      <div class="form-group row">
                        <label class="col-form-label col-lg-2">{{__('ID Document')}}</label>                          
                        <div class="col-lg-10">
                          <select class="form-control select" name="id_type" @if($ver->status==1 || $user->business_level==3) disabled @endif id="7otherField">
                            <option value="National ID" @if($ver->id_type=="National ID") selected @endif>{{__('National ID')}}</option>
                            <option value="International Passport" @if($ver->id_type=="International Passport") selected @endif>{{__('International Passport')}}</option>
                            <option value="Voters Card" @if($ver->id_type=="Voters Card") selected @endif>{{__('Voters Card')}}</option>
                            <option value="Driver License" @if($ver->id_type=="Driver License") selected @endif>{{__('Driver License')}}</option>
                          </select>
                        </div>                            
                      </div>
                      @if($ver->status==0 || $user->business_level==2) 
                        <div class="form-group row">
                          <div class="col-lg-12">
                            <div class="custom-file">
                              <input type="file" class="custom-file-input" @if($ver->status==1 || $user->business_level==3) disabled @endif id="customFileLang1" name="idcard" accept="image/*">
                              <label class="custom-file-label sdsd" for="customFileLang1">{{__('Front')}}</label>
                            </div> 
                          </div>
                        </div>                        
                        <div class="form-group row">
                          <div class="col-lg-12">
                            <div class="custom-file">
                              <input type="file" class="custom-file-input" @if($ver->status==1 || $user->business_level==3) disabled @endif id="customFileLang3" name="idcard_back" accept="image/*">
                              <label class="custom-file-label tests" for="customFileLang3">{{__('Back')}}</label>
                            </div> 
                          </div>
                        </div>
                      @else
                        <a href="{{url('/')}}/asset/profile/{{$ver->idcard}}">{{__('View Identity Document [Front]')}}</a><br>
                        <a href="{{url('/')}}/asset/profile/{{$ver->idcard_back}}">{{__('View Identity Document [Back]')}}</a><br>
                      @endif                                                                                                                    
                  </div>
                  <div class="text-center">
                      @if($ver->status==0 || $ver->status==3)    
                        <button type="submit" class="btn btn-neutral btn-block">{{__('Submit Compliance For Review')}}</button>
                      @elseif($ver->status==1)
                        <span class="badge badge-pill badge-primary"><i class="fad fa-check"></i> {{__('Under Review')}}</span>                  
                      @elseif($ver->status==2 && $user->business_level==2)
                        <button type="submit" class="btn btn-neutral btn-block mb-5">{{__('Update Compliance')}}</button>
                        <span class="badge badge-pill badge-success"><i class="fad fa-check"></i> {{__('Approved')}}</span>                  
                      @elseif($ver->status==2 && $user->business_level==3)
                        <span class="badge badge-pill badge-success"><i class="fad fa-check"></i> {{__('Approved')}}</span>
                      @endif
                  </div> 
                </div>  
              </form>
            </div>   
          </div>
        </div>
      </div> 
    <div class="row">
      <div class="col-md-12">
        <div class="modal fade" id="modal-formp" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h3 class="mb-0 font-weight-bolder">{{__('Delete Account')}}</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form action="{{route('delaccount')}}" method="post">
                  @csrf
                  <div class="form-group row">
                    <div class="col-lg-12">
                      <textarea type="text" name="reason" class="form-control" rows="5" placeholder="{{__('Sorry to see you leave, Please tell us why you are leaving')}}" required></textarea>
                    </div>
                  </div>             
                  <div class="text-right">
                    <button type="submit" class="btn btn-neutral btn-block">{{__('Delete account')}}</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <div class="modal fade" id="modal-formx" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
          <div class="modal-dialog modal- modal-dialog-centered modal-md" role="document">

          </div>
        </div> 
      </div>
    </div>
@stop
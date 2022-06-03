@extends('loginlayout')

@section('content')
<div class="main-content">
    <!-- Header -->
    <div class="header py-5 pt-7">
      <div class="container">
        <div class="header-body text-center mb-7">
        </div>
      </div>
    </div>
    <!-- Page content -->
    <div class="container mt--8 pb-5 mb-0">
      <div class="row justify-content-center">
        <div class="col-lg-12 col-md-7">
          <div class="card border-0 mb-5">
            <div class="card-body pt-7 px-5">
              <div class="text-center text-dark mb-5">
                <h3 class="text-dark font-weight-bolder">{{__('Compliance')}}</h3>
                <span class="text-gray text-xs">{{__('Verify your business')}}</span>
              </div>
              <form action="{{route('submit.compliance')}}" method="post" enctype="multipart/form-data">
                <div class="">
                  <div class="">
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
    </div>
@stop
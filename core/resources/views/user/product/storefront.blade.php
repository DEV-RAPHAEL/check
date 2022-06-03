@extends('userlayout')

@section('content')
<div class="container-fluid mt--6">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12">
                <div class="nav-wrapper">
                    <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0 @if(route('user.storefront')==url()->current()) active @endif" id="tabs-icons-text-1-tab" data-toggle="tab" href="#tabs-icons-text-1" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true"><i class="fad fa-store-alt"></i> Store front</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0 @if(route('user.shipping')==url()->current()) active @endif" id="tabs-icons-text-2-tab" data-toggle="tab" href="#tabs-icons-text-2" role="tab" aria-controls="tabs-icons-text-2" aria-selected="fadse"><i class="fad fa-street-view"></i> Shipping Regions & Rate</a>
                        </li>                        
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0 @if(route('user.product')==url()->current()) active @endif" id="tabs-icons-text-3-tab" data-toggle="tab" href="#tabs-icons-text-3" role="tab" aria-controls="tabs-icons-text-3" aria-selected="fadse"><i class="fad fa-shopping-bag"></i> Products</a>
                        </li>                        
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0 @if(route('user.list')==url()->current()) active @endif" id="tabs-icons-text-4-tab" data-toggle="tab" href="#tabs-icons-text-4" role="tab" aria-controls="tabs-icons-text-4" aria-selected="fadse"><i class="fad fa-shopping-cart"></i> Client Orders</a>
                        </li>                         
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0 @if(route('user.your-list')==url()->current()) active @endif" id="tabs-icons-text-5-tab" data-toggle="tab" href="#tabs-icons-text-5" role="tab" aria-controls="tabs-icons-text-5" aria-selected="fadse"><i class="fad fa-shopping-cart"></i> Your Orders</a>
                        </li>   
                    </ul>
                </div>
            </div>
        </div>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade @if(route('user.storefront')==url()->current())show active @endif" id="tabs-icons-text-1" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
                <div class="row align-items-center py-4">
                    <div class="col-lg-6 col-5 text-left">
                        <a data-toggle="modal" data-target="#new-store" href="" class="btn btn-sm btn-neutral"><i class="fad fa-plus"></i> {{__('New Storefront')}}</a>                 
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">  
                            @if(count($store)>0)
                                @foreach($store as $k=>$val)
                                    <div class="col-md-4">
                                        <div class="card bg-white">
                                        <!-- Card body -->
                                        <div class="card-body">
                                            <div class="row mb-2">
                                            <div class="col-4">
                                                <p class="text-sm text-dark mb-2"><a class="btn-icon-clipboard" data-clipboard-text="{{route('store.link', ['id' => $val->store_url])}}" title="Copy">{{__('COPY LINK')}} <i class="fad fa-link text-xs"></i></a></p>
                                            </div>  
                                            <div class="col-8 text-right">
                                                <a class="mr-0 text-dark" data-toggle="dropdown" aria-haspopup="true" aria-expanded="fadse">
                                                <i class="fad fa-chevron-circle-down"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-left">
                                                    <a href="{{route('storefront.products', ['id' => $val->id])}}" class="dropdown-item"><i class="fad fa-shopping-bag"></i> {{__('Products')}}</a>
                                                    <a href="{{route('store.your-list', ['id' => $val->id])}}" class="dropdown-item"><i class="fad fa-shopping-cart"></i> {{__('Orders')}}</a>
                                                    <a data-toggle="modal" data-target="#edit{{$val->id}}" href="" class="dropdown-item"><i class="fad fa-pencil"></i>{{__('Edit')}}</a>
                                                    @if($val->status==1)
                                                        <a class='dropdown-item' href="{{route('store.unpublish', ['id' => $val->id])}}"><i class="fad fa-ban"></i>{{ __('Disable')}}</a>
                                                    @else
                                                        <a class='dropdown-item' href="{{route('store.publish', ['id' => $val->id])}}"><i class="fad fa-check"></i>{{ __('Activate')}}</a>
                                                    @endif
                                                    <a class="dropdown-item" data-toggle="modal" data-target="#delete{{$val->id}}" href=""><i class="fad fa-trash"></i>{{__('Delete')}}</a>
                                                </div>
                                            </div>
                                            </div>
                                            <div class="row">
                                            <div class="col">
                                                <h5 class="h4 mb-1 font-weight-bolder">{{$val->store_name}}</h5>
                                                <p>{{__('Category')}}: {{$val->category}}</p>
                                                <p>{{__('Revenue')}}: {{$currency->name.' '.$val->revenue}}</p>
                                                <p class="text-sm mb-2">{{__('Date')}}: {{date("h:i:A j, M Y", strtotime($val->created_at))}}</p>
                                                @if($val->status==1)
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
                                        <h3 class="text-dark">No Storefront Found</h3>
                                        <p class="text-dark text-sm card-text">We couldn't find any storefront to this account</p>
                                    </div>
                                </div>
                            @endif
                        </div> 
                        <div class="row">
                        <div class="col-md-12">
                        {{ $store->links('pagination::bootstrap-4') }}
                        </div>
                        </div>
                    </div> 
                </div>
                @foreach($store as $k=>$val)
                    <div class="modal fade" id="delete{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-body p-0">
                                    <div class="card bg-white border-0 mb-0">
                                        <div class="card-header">
                                            <h3 class="mb-0 font-weight-bolder">{{__('Delete Storefront')}}</h3>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                            <span class="mb-0 text-xs">{{__('Are you sure you want to delete this?, all transaction related to this payment link will also be deleted')}}</span>
                                        </div>
                                        <div class="card-body">
                                            <a  href="{{route('delete.storefront', ['id' => $val->id])}}" class="btn btn-danger btn-block">{{__('Proceed')}}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="edit{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title font-weight-bolder">{{__('Edit Store')}}</h3>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('edit.store')}}" method="post">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-12">{{__('Store Name')}}</label>
                                    <div class="col-lg-12">
                                        <input type="text" name="store_name" class="form-control" value="{{$val->store_name}}" placeholder="The name of your store" required>
                                    </div>
                                </div>
                                <input type="hidden" value="{{$val->id}}" name="id">
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-12">{{__('Store Description')}}</label>
                                    <div class="col-lg-12">
                                        <textarea type="text" name="store_desc" class="form-control" required>{{$val->store_desc}}</textarea>
                                    </div>
                                </div>   
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-12">{{__('Category')}}</label>
                                    <div class="col-lg-12">
                                        <select class="form-control custom-select" name="category" required>
                                            <option @if($val->category=="Animals & Pets")selected @endif>Animals & Pets</option>
                                            <option @if($val->category=="Arts and Crafts")selected @endif>Arts and Crafts</option>
                                            <option @if($val->category=="Baby Products")selected @endif>Baby Products</option>
                                            <option @if($val->category=="Beauty and Skincare")selected @endif>Beauty and Skincare</option>
                                            <option @if($val->category=="Books and Media")selected @endif>Books and Media</option>
                                            <option @if($val->category=="Building and Construction")selected @endif>Building and Construction</option>
                                            <option @if($val->category=="Daily Essentials")selected @endif>Daily Essentials</option>
                                            <option @if($val->category=="Drinks")selected @endif>Drinks</option>
                                            <option @if($val->category=="Education")selected @endif>Education</option>
                                            <option @if($val->category=="Electronics")selected @endif>Electronics</option>
                                            <option @if($val->category=="Food & Beverages")selected @endif>Food & Beverages</option>
                                            <option @if($val->category=="Gaming")selected @endif>Gaming</option>
                                            <option @if($val->category=="Groceries")selected @endif>Groceries</option>
                                            <option @if($val->category=="Gym and Fitness")selected @endif>Gym and Fitness</option>
                                            <option @if($val->category=="Health & Pharmaceuticals")selected @endif>Health & Pharmaceuticals</option>
                                            <option @if($val->category=="Home & Kitchen")selected @endif>Home & Kitchen</option>
                                            <option @if($val->category=="Insurance")selected @endif>Insurance</option>
                                            <option @if($val->category=="Kids Fashion")selected @endif>Kids Fashion</option>
                                            <option @if($val->category=="Makeup and Cosmetics")selected @endif>Makeup and Cosmetics</option>
                                            <option @if($val->category=="Mens Fashion")selected @endif>Mens Fashion</option>
                                            <option @if($val->category=="Office Equipment")selected @endif>Office Equipment</option>
                                            <option @if($val->category=="Others")selected @endif>Others</option>
                                            <option @if($val->category=="Personal Care")selected @endif>Personal Care</option>
                                            <option @if($val->category=="Phones and Tablets")selected @endif>Phones and Tablets</option>
                                            <option @if($val->category=="Professional Services")selected @endif>Professional Services</option>
                                            <option @if($val->category=="Religious Organization")selected @endif>Religious Organization</option>
                                            <option @if($val->category=="Restaurant")selected @endif>Restaurant</option>
                                            <option @if($val->category=="Supermarket")selected @endif>Supermarket</option>
                                            <option @if($val->category=="Toys & Games")selected @endif>Toys & Games</option>
                                            <option @if($val->category=="Womens Fashion")selected @endif>Womens Fashion</option>
                                        </select>
                                    </div>       
                                </div>  
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-12">{{__('Shipping Status')}}</label>
                                    <div class="col-lg-12">
                                        <select class="form-control custom-select" name="shipping_status" required>
                                            <option value='0' @if($val->shipping_status==0) selected @endif>{{__('Disabled')}}</option>
                                            <option value='1' @if($val->shipping_status==1) selected @endif>{{__('Active')}}</option>
                                        </select>
                                    </div>                                            
                                </div>    
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-12">{{__('Delivery Note')}}</label>
                                    <div class="col-lg-12">
                                        <select class="form-control custom-select" name="note_status" required>
                                            <option value='0' @if($val->note_status==0) selected @endif>{{__('Disabled')}}</option>
                                            <option value='1' @if($val->note_status==1) selected @endif>{{__('Required')}}</option>
                                            <option value='2' @if($val->note_status==2) selected @endif>{{__('Optional')}}</option>
                                        </select>
                                    </div>
                                </div>                            
                                <div class="text-right">
                                    <button type="submit" class="btn btn-neutral btn-block">{{__('Edit Store')}}</button>
                                </div>
                                </form>
                            </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="modal fade" id="new-store" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title font-weight-bolder">{{__('Create Store')}}</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('submit.store')}}" method="post">
                            @csrf
                            <div class="form-group row">
                                <label class="col-form-label col-lg-12">{{__('Store Name')}}</label>
                                <div class="col-lg-12">
                                    <input type="text" name="store_name" class="form-control" placeholder="The name of your store" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-lg-12">{{__('Store Description')}}</label>
                                <div class="col-lg-12">
                                    <textarea type="text" name="store_desc" class="form-control" required></textarea>
                                </div>
                            </div> 
                            <div class="form-group row">
                                <label class="col-form-label col-lg-12">{{__('Shipping Status')}}</label>
                                <div class="col-lg-12">
                                    <select class="form-control custom-select" name="shipping_status" required>
                                        
                                        <option value='1'>{{__('Active')}}</option>
                                        <option value='0'>{{__('Disabled')}}</option>
                                    </select>
                                </div>                                            
                            </div>    
                            <div class="form-group row">
                                <label class="col-form-label col-lg-12">{{__('Delivery Note')}}</label>
                                <div class="col-lg-12">
                                    <select class="form-control custom-select" name="note_status" required>
                                        <option value='1'>{{__('Required')}}</option>
                                        <option value='0'>{{__('Disabled')}}</option>                                      
                                        <option value='2'>{{__('Optional')}}</option>
                                    </select>
                                </div>
                            </div> 
                            <div class="form-group row">
                                <label class="col-form-label col-lg-12">{{__('Category')}}</label>
                                <div class="col-lg-12">
                                    <select class="form-control custom-select" name="category" required>
                                        <option>Animals & Pets</option>
                                        <option>Arts and Crafts</option>
                                        <option>Baby Products</option>
                                        <option>Beauty and Skincare</option>
                                        <option>Books and Media</option>
                                        <option>Building and Construction</option>
                                        <option>Daily Essentials</option>
                                        <option>Drinks</option>
                                        <option>Education</option>
                                        <option>Electronics</option>
                                        <option>Food & Beverages</option>
                                        <option>Gaming</option>
                                        <option>Groceries</option>
                                        <option>Gym and Fitness</option>
                                        <option>Health & Pharmaceuticals</option>
                                        <option>Home & Kitchen</option>
                                        <option>Insurance </option>
                                        <option>Kids Fashion</option>
                                        <option>Makeup and Cosmetics</option>
                                        <option>Mens Fashion</option>
                                        <option>Office Equipment</option>
                                        <option>Others</option>
                                        <option>Personal Care</option>
                                        <option>Phones and Tablets</option>
                                        <option>Professional Services</option>
                                        <option>Religious Organization</option>
                                        <option>Restaurant</option>
                                        <option>Supermarket</option>
                                        <option>Toys & Games</option>
                                        <option>Womens Fashion</option>
                                    </select>
                                </div>       
                            </div>                                
                            <div class="text-right">
                                <button type="submit" class="btn btn-neutral btn-block">{{__('Create Store')}}</button>
                            </div>
                            </form>
                        </div>
                        </div>
                    </div>
                </div>   
            </div>            
            <div class="tab-pane fade @if(route('user.shipping')==url()->current())show active @endif" id="tabs-icons-text-2" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
                <div class="row align-items-center py-4">
                    <div class="col-lg-8 col-8 text-left">
                        <a data-toggle="modal" data-target="#new-shipping" href="" class="btn btn-sm btn-neutral"><i class="fad fa-plus"></i> {{__('New Add Shipping Fee')}}</a>    
                    </div>
                </div>
                <div class="card">
                    <div class="table-responsive py-4">
                        <table class="table table-flush" id="datatable-basic2">
                        <thead>
                            <tr>
                            <th>{{__('S / N')}}</th>
                            <th></th>
                            <th>{{__('Region')}}</th>
                            <th>{{__('Amount')}}</th>
                            </tr>
                        </thead>
                        <tbody>  
                            @foreach($shipping as $k=>$val)
                            <tr>
                                <td>{{++$k}}.</td>
                                <td>
                                    <div class="dropdown">
                                        <a class="text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="fadse">
                                        <i class="fad fa-chevron-circle-down"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            <a data-toggle="modal" data-target="#editship{{$val->id}}" href="" class="dropdown-item"><i class="fad fa-pencil"></i>{{__('Edit')}}</a>
                                            <a class="dropdown-item" data-toggle="modal" data-target="#deleteship{{$val->id}}" href=""><i class="fad fa-trash"></i>{{__('Delete')}}</a>
                                        </div>
                                    </div>
                                </td> 
                                <td>{{$val->region}}</td>
                                <td>{{$currency->name.' '.$val->amount}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        </table>
                    </div>
                </div>
                @foreach($shipping as $k=>$val)
                    <div class="modal fade" id="deleteship{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-body p-0">
                                    <div class="card bg-white border-0 mb-0">
                                        <div class="card-header">
                                            <h3 class="mb-0 font-weight-bolder">{{__('Delete Shipping Fee')}}</h3>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                            <span class="mb-0 text-xs">{{__('Are you sure you want to delete this?, all transaction related to this payment link will also be deleted')}}</span>
                                        </div>
                                        <div class="card-body">
                                            <a  href="{{route('delete.shipping', ['id' => $val->id])}}" class="btn btn-danger btn-block">{{__('Proceed')}}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="editship{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title font-weight-bolder">{{__('Edit Shipping fee')}}</h3>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('edit.shipping')}}" method="post">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-12">{{__('Region')}}</label>
                                    <div class="col-lg-12">
                                        <input type="text" name="region" class="form-control" value="{{$val->region}}" placeholder="New York" required>
                                    </div>
                                </div>
                                <input type="hidden" value="{{$val->id}}" name="id">
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text">{{$currency->symbol}}</span>
                                        </span>
                                        <input type="number" class="form-control" name="amount" value="{{$val->amount}}" placeholder="0.00" required>
                                        <span class="input-group-append">
                                            <span class="input-group-text">.00</span>
                                        </span>
                                        </div>
                                    </div>
                                </div> 
                                <div class="text-right">
                                    <button type="submit" class="btn btn-neutral btn-block">{{__('Edit Shipping Fee')}}</button>
                                </div>
                                </form>
                            </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="modal fade" id="new-shipping" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title font-weight-bolder">{{__('Create Shipping fee')}}</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('submit.shipping')}}" method="post">
                            @csrf
                            <div class="form-group row">
                                <label class="col-form-label col-lg-12">{{__('Region')}}</label>
                                <div class="col-lg-12">
                                    <input type="text" name="region" class="form-control" placeholder="New York" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-12">
                                    <div class="input-group">
                                    <span class="input-group-prepend">
                                        <span class="input-group-text">{{$currency->symbol}}</span>
                                    </span>
                                    <input type="number" class="form-control" name="amount" placeholder="0.00" required>
                                    <span class="input-group-append">
                                        <span class="input-group-text">.00</span>
                                    </span>
                                    </div>
                                </div>
                            </div>                                
                            <div class="text-right">
                                <button type="submit" class="btn btn-neutral btn-block">{{__('Create Shipping Fee')}}</button>
                            </div>
                            </form>
                        </div>
                        </div>
                    </div>
                </div>  

            </div>
            <div class="tab-pane fade @if(route('user.product')==url()->current())show active @endif" id="tabs-icons-text-3" role="tabpanel" aria-labelledby="tabs-icons-text-3-tab">
                <div class="row align-items-center py-4">
                    <div class="col-12">
                        <a data-toggle="modal" data-target="#category" href="" class="btn btn-sm btn-neutral"><i class="fad fa-filter"></i> {{__('Category')}}</a> 
                        <a data-toggle="modal" data-target="#new-product" href="" class="btn btn-sm btn-neutral"><i class="fad fa-plus"></i> {{__('Create Product')}}</a> 
                        <a data-toggle="modal" data-target="#statistic" href="" class="btn btn-sm btn-neutral"><i class="fad fa-sync"></i> {{__('Statistics')}}</a> 
                    </div>
                </div>
                <div class="modal fade" id="category" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title font-weight-bolder">{{__('Category')}}</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('submit.category')}}" method="post">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-12">{{__('Name')}}</label>
                                    <div class="col-lg-12">
                                    <input type="text" name="name" class="form-control" placeholder="Name of Category" required>
                                    </div>
                                </div> 
                                <div class="text-right">
                                    <button type="submit" class="btn btn-neutral btn-block">{{__('Create Category')}}</button>
                                </div>
                                <ul class="list-group list-group-flush list">
                                    @if(count($category)>0)
                                    @foreach($category as $k=>$val)
                                        <li class="list-group-item px-0">
                                        <div class="row align-items-center">
                                            <div class="col-8">
                                            <span class="text-gray text-xs">{{$val->name}}</span>
                                            </div>
                                            <div class="col-4 text-right">
                                            <a href="{{route('delete.category', ['id' => $val->id])}}" class="btn btn-sm btn-neutral"><i class="fad fa-trash"></i> {{__('Delete')}}</a>
                                            </div>
                                        </div>
                                        </li>
                                    @endforeach
                                    @else
                                    <div class="row text-center">
                                        <div class="col-md-12 mb-5">
                                        <div class="text-center mt-8">
                                            <div class="mb-3">
                                            <img src="{{url('/')}}/asset/images/empty.svg">
                                            </div>
                                            <h3 class="text-dark">No Category Found</h3>
                                            <p class="text-dark text-sm card-text">We couldn't find any product category to this account</p>
                                        </div>
                                        </div>
                                    </div>
                                    @endif
                                </ul>                   
                            </form>
                        </div>
                        </div>
                    </div>
                </div>    
                <div class="modal fade" id="new-product" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title font-weight-bolder">{{__('New Product')}}</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('submit.product')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group row">
                                <label class="col-form-label col-lg-12">{{__('Name')}}</label>
                                <div class="col-lg-12">
                                <input type="text" name="name" class="form-control" placeholder="The name of your product" required>
                                </div>
                            </div>              
                            <div class="form-group row">
                                <label class="col-form-label col-lg-12">{{__('Category')}}</label>
                                <div class="col-lg-12">
                                <select class="form-control custom-select" name="category" required>
                                    <option value="">Select Category</option>
                                    @foreach($category as $val)
                                    <option value="{{$val->id}}">{{$val->name}}</option>
                                    @endforeach
                                </select>
                                </div>       
                            </div>       
                            <div class="form-group row">
                                <label class="col-form-label col-lg-12">{{__('Amount')}}</label>
                                <div class="col-lg-12">
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text">{{$currency->symbol}}</span>
                                    </div>
                                    <input type="number" step="any" name="amount" maxlength="10" class="form-control" required>
                                </div>
                                </div>
                            </div>  
                            <div class="form-group row">
                                <label class="col-form-label col-lg-12">{{__('Quantity')}}</label>
                                <div class="col-lg-12">
                                    <input type="number" name="quantity" class="form-control" value="1" required>
                                </div>
                            </div>  
                            <div class="form-group row">
                                <label class="col-form-label col-lg-12">{{__('Shipping Status')}}</label>
                                <div class="col-lg-12">
                                    <select class="form-control custom-select" name="shipping_status" required>
                                        
                                        <option value='1'>{{__('Active')}}</option>
                                        <option value='0'>{{__('Disabled')}}</option>
                                    </select>
                                </div>                                            
                            </div>   
                            <div class="form-group row">
                                <div class="col-lg-12">
                                    <div class="custom-file text-center">
                                        <input type="file" class="custom-file-input" name="file" accept="image/*" id="customFileLang" required>
                                        <label class="custom-file-label" for="customFileLang">{{__('Choose Media')}}</label>
                                        <span class="form-text text-xs">Recommended Image Size is 399x399</span>
                                    </div>
                                </div>
                            </div>             
                            <div class="text-right">
                                <button type="submit" class="btn btn-neutral btn-block">{{__('Create Product')}}</button>
                            </div>
                            </form>
                        </div>
                        </div>
                    </div>
                </div>                
                <div class="modal fade" id="statistic" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title font-weight-bolder">{{__('Statistics')}}</h3>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row align-items-center">
                                    <div class="col text-center">
                                        <span class="text-sm text-dark mb-0"><i class="fa fa-google-wallet"></i> {{__('Received')}}</span><br>
                                        <span class="text-xl text-dark mb-0">{{$currency->name}} {{number_format($received)}}.00</span><br>
                                        <hr>
                                    </div>
                                </div>
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="my-4">
                                        <span class="surtitle">{{__('Pending')}}</span><br>
                                        <span class="surtitle ">{{__('Total')}}</span>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="my-4">
                                            <span class="surtitle ">{{$currency->name}} {{number_format($total, 2, '.', '')}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
                <div class="row">  
                    <div class="col-md-12">
                        <div class="row">  
                            @if(count($product)>0)
                                @foreach($product as $k=>$val)
                                    <div class="col-md-4">
                                        <div class="card">
                                            <img class="card-img-top" 
                                            @if($val->new==0)
                                                src="{{url('/')}}/asset/images/product-placeholder.jpg"
                                            @else
                                                @php
                                                    $image=App\Models\Productimage::whereproduct_id($val->id)->first();
                                                @endphp
                                                src="{{url('/')}}/asset/profile/{{$image['image']}}"
                                            @endif 
                                            alt="Image placeholder">
                                            <!-- Card body -->
                                            <div class="card-body">
                                                <div class="row mb-2">
                                                    <div class="col-8">
                                                    <p class="text-sm text-dark mb-2"><a class="btn-icon-clipboard" data-clipboard-text="{{route('user.ask', ['id' => $val->ref_id])}}" title="Copy">{{__('COPY LINK')}} <i class="fad fa-link text-xs"></i></a></p>
                                                    </div>
                                                    <div class="col-4 text-right">
                                                    <a class="mr-0 text-dark" data-toggle="dropdown" aria-haspopup="true" aria-expanded="fadse">
                                                        <i class="fad fa-chevron-circle-down"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-left">
                                                        <a class="dropdown-item" href="{{route('edit.product', ['id' => $val->ref_id])}}"><i class="fad fa-pencil"></i>{{__('Edit')}}</a>
                                                        <a class="dropdown-item" href="{{route('orders', ['id' => $val->id])}}"><i class="fad fa-sync"></i>{{__('Orders')}}</a>
                                                        <a class="dropdown-item" data-toggle="modal" data-target="#delete{{$val->id}}" href="#"><i class="fad fa-trash-alt"></i>{{__('Delete')}}</a>
                                                    </div>
                                                    </div>                        
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-12">
                                                    <h5 class="h4 mb-1 font-weight-bolder">{{$val->name}}</h5>
                                                    <p>Sold: {{$val->sold}}/{{$val->quantity}}</p>
                                                    @if($val->status==1)
                                                        <span class="badge badge-pill badge-primary"><i class="fad fa-check"></i> {{__('Active')}}</span>
                                                    @else
                                                        <span class="badge badge-pill badge-danger"><i class="fad fa-ban"></i> {{__('Disabled')}}</span>
                                                    @endif
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
                                                            <h3 class="mb-0 font-weight-bolder">{{__('Delete Product')}}</h3>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                            <span class="mb-0 text-xs">{{__('Are you sure you want to delete this?, all transaction related to this product will also be deleted')}}</span>
                                                            </div>
                                                            <div class="card-body">
                                                                <a  href="{{route('delete.product', ['id' => $val->id])}}" class="btn btn-danger btn-block">{{__('Proceed')}}</a>
                                                            </div>
                                                        </div>
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
                                    <h3 class="text-dark">No Product Found</h3>
                                    <p class="text-dark text-sm card-text">We couldn't find any product to this account</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div> 
            <div class="tab-pane fade @if(route('user.list')==url()->current())show active @endif" id="tabs-icons-text-4" role="tabpanel" aria-labelledby="tabs-icons-text-4-tab">      
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-header">
                            <h5 class="h3 mb-0">{{__('Client Orders')}}</h5>
                        </div>
                        <div class="row">  
                        @if(count($orders)>0)  
                        @foreach($orders as $k=>$val)
                            <div class="col-md-6">
                            <div class="card bg-white">
                                <!-- Card body -->
                                <div class="card-body">
                                <div class="row">
                                    <div class="col-8">
                                    <!-- Title -->
                                    <h5 class="h4 mb-1">{{$val->ref_id}}</h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <p class="text-sm text-dark mb-0">{{__('Product')}}: {{$val->product->name}}</p>
                                        @if($val->user_id!=null)
                                            <p class="text-sm text-dark mb-0">{{__('Name')}}: {{$val->buyer->first_name}} {{$val->buyer->last_name}}</p>
                                            <p class="text-sm text-dark mb-0">{{__('Email')}}: {{$val->buyer->email}}</p>
                                            <p class="text-sm text-dark mb-0">{{__('Phone')}}: {{$val->buyer->phone}}</p>
                                        @else
                                            <p class="text-sm text-dark mb-0">{{__('Name')}}: {{$val->first_name}} {{$val->last_name}}</p>
                                            <p class="text-sm text-dark mb-0">{{__('Email')}}: {{$val->email}}</p>
                                            <p class="text-sm text-dark mb-0">{{__('Phone')}}: {{$val->phone}}</p>
                                        @endif
                                        <p class="text-sm text-dark mb-0">{{__('Quantity')}}: {{$val->quantity}}</p> 
                                        <p class="text-sm text-dark mb-0">{{__('Country')}}: {{$val->country}}</p>
                                        <p class="text-sm text-dark mb-0">{{__('State')}}: {{$val->state}}</p>
                                        <p class="text-sm text-dark mb-0">{{__('Town/City')}}: {{$val->town}}</p>
                                        <p class="text-sm text-dark mb-0">{{__('Address')}}: {{$val->address}}</p>
                                        <p class="text-sm text-dark mb-0">{{__('Region')}}: {{$val->ship['region']}}</p>
                                        <p class="text-sm text-dark mb-0">{{__('Shipping fee')}}: @if($val->ship!=null){{$currency->symbol.$val->shipping_fee}} @endif</p>
                                        @if($val->product->note_status==1 || $val->product->note_status==2)
                                            @if(!empty($val->note))
                                                <p class="text-sm text-dark mb-0">Note: {{$val->note}}</p>
                                            @endif
                                        @endif                                        
                                        @if($val->store_id==null)
                                            <p class="text-sm text-dark mb-0">Type: Single Purchase</p>
                                        @elseif($val->store_id!=null)
                                            <p class="text-sm text-dark mb-0">Type: Store Purchase</p>
                                        @endif     
                                        <p class="text-sm text-dark mb-0">{{__('Amount')}}: {{$currency->symbol}}{{number_format($val->amount, 2, '.', '')}}</p>
                                        <p class="text-sm text-dark mb-0">{{__('Total')}}: {{$currency->symbol.number_format($val->amount*$val->quantity+$val->shipping_fee, 2, '.', '')}}</p>
                                        <p class="text-sm text-dark mb-0">{{__('Created')}}: {{date("Y/m/d h:i:A", strtotime($val->created_at))}}</p>
                                        <span class="badge badge-pill badge-primary">{{__('Fee')}}: {{$currency->symbol.number_format($val->charge, 2, '.', '')}}</span>
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
                            <h3 class="text-dark">No Orders</h3>
                            <p class="text-dark text-sm card-text">We couldn't find any product order to this account</p>
                            </div>
                        </div>
                        @endif
                        </div> 
                    </div>
                </div>
            </div>             
            <div class="tab-pane fade @if(route('user.your-list')==url()->current())show active @endif" id="tabs-icons-text-5" role="tabpanel" aria-labelledby="tabs-icons-text-5-tab">      
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-header">
                            <h5 class="h3 mb-0">{{__('Your Orders')}}</h5>
                        </div>
                        <div class="row">  
                        @if(count($yourorders)>0)  
                        @foreach($yourorders as $k=>$val)
                            <div class="col-md-6">
                            <div class="card bg-white">
                                <!-- Card body -->
                                <div class="card-body">
                                <div class="row">
                                    <div class="col-8">
                                    <!-- Title -->
                                    <h5 class="h4 mb-1">{{$val->ref_id}}</h5>
                                    </div>
                                </div>
                                <div class="row">
                                <div class="col">
                                        <p class="text-sm text-dark mb-0">{{__('Product')}}: {{$val->product->name}}</p>
                                        <p class="text-sm text-dark mb-0">{{__('Quantity')}}: {{$val->quantity}}</p> 
                                        <p class="text-sm text-dark mb-0">{{__('Country')}}: {{$val->country}}</p>
                                        <p class="text-sm text-dark mb-0">{{__('State')}}: {{$val->state}}</p>
                                        <p class="text-sm text-dark mb-0">{{__('Town/City')}}: {{$val->town}}</p>
                                        <p class="text-sm text-dark mb-0">{{__('Address')}}: {{$val->address}}</p>
                                        <p class="text-sm text-dark mb-0">{{__('Region')}}: {{$val->ship['region']}}</p>
                                        <p class="text-sm text-dark mb-0">{{__('Shipping fee')}}: @if($val->ship!=null){{$currency->symbol.$val->shipping_fee}} @endif</p>
                                        @if($val->product->note_status==1 || $val->product->note_status==2)
                                            @if(!empty($val->note))
                                                <p class="text-sm text-dark mb-0">Note: {{$val->note}}</p>
                                            @endif
                                        @endif                                        
                                        @if($val->store_id==null)
                                            <p class="text-sm text-dark mb-0">Type: Single Purchase</p>
                                        @elseif($val->store_id!=null)
                                            <p class="text-sm text-dark mb-0">Type: Store Purchase</p>
                                        @endif      
                                        <p class="text-sm text-dark mb-0">{{__('Amount')}}: {{$currency->symbol}}{{number_format($val->amount, 2, '.', '')}}</p>
                                        <p class="text-sm text-dark mb-0">{{__('Total')}}: {{$currency->symbol.number_format($val->amount*$val->quantity+$val->shipping_fee, 2, '.', '')}}</p>
                                        <p class="text-sm text-dark mb-0">{{__('Created')}}: {{date("Y/m/d h:i:A", strtotime($val->created_at))}}</p>
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
                            <h3 class="text-dark">No Orders</h3>
                            <p class="text-dark text-sm card-text">We couldn't find any product order to this account</p>
                            </div>
                        </div>
                        @endif
                        </div> 
                    </div>
                </div>
            </div>       
        </div>       
  @stop
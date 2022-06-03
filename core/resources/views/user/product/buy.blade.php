@extends('paymentlayout')

@section('content')

<div class="main-content">
    <div class="header py-7 py-lg-6 pt-lg-1">
      <div class="container">
        <div class="header-body text-center mb-7">
        <div class="row justify-content-center">
          <div class="col-xl-5 col-lg-6 col-md-8 px-5">
            <div class="card-profile-image mb-5">
                <img src="{{url('/')}}/asset/profile/{{$merchant->image}}" class="">
            </div>
          </div>
        </div>
        </div>
      </div>
    </div>
    <div class="container mt--8 pb-5 mb-0">
        <div class="row justify-content-center">     
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-between align-items-center">
                            <div class="col-auto">
                                <span class="form-text text-xl">{{$currency->symbol}} {{number_format($product->amount)}}.00</span>
                                <span class="form-text text-sm text-default">{{$product->name}} by {{$merchant->business_name}}</span>
                            </div>
                        </div>
                        <form action="{{route('pay.product')}}" method="post" id="payment-form">
                            @csrf
                            <div class="row justify-content-between align-items-center">
                                <div class="col">
                                    @if($product->quantity_status==0)
                                        @if($product->quantity!=0)
                                            <div class="col-lg-4">
                                                <span class="badge badge-pill badge-primary mb-3 ml-0">{{__('In stock')}}: {{$product->quantity}}</span>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                                <div class="col-auto">
                                    <div class="text-right">                        

                                    </div>
                                </div>
                            </div>
                            @if(!empty($product->description))
                            <span class="form-text text-xs text-default">{!!$product->description!!}.</span>
                            @endif
                            @if($product->quantity_status==0)
                                <div class="form-group row">
                                    @if($product->quantity!=0)
                                        @if($product->shipping_status==1)
                                            <div class="col-lg-3">
                                                <input type="number" id="quantity" name="quantity" value="1" step="1" min="1" max="{{$product->quantity}}" title="Qty" size="4" inputmode="numeric" class="form-control" required="">
                                                <input type="hidden" id="amount" value="{{$product->amount}}" name="amount">
                                            </div>
                                            <label class="col-form-label col-lg-5">{{__('Quantity')}}</label>
                                        @else
                                            <div class="col-lg-3">
                                                <input type="number" id="quanz" name="quantity" value="1" step="1" min="1" max="{{$product->quantity}}" title="Qty" size="4" inputmode="numeric" class="form-control" required="">
                                                <input type="hidden" id="amount4" value="{{$product->amount}}" name="amount">
                                            </div>
                                            <label class="col-form-label col-lg-5">{{__('Quantity')}}</label>
                                        @endif
                                    @else
                                    <div class="col-lg-3">
                                        <span class="badge badge-pill badge-primary mb-3">{{__('Out of stock')}}</span>
                                    </div>
                                    @endif
                                </div>
                            @else
                                <input type="hidden" id="quantity" value="1" name="quantity">
                            @endif
                                <input type="hidden" name="ref_id" value="{{$ref}}">
                                <input type="hidden" name="product_id" value="{{$product->id}}">
                                <input type="hidden" name="amount" value="{{$product->amount}}">
                            @if (!Auth::guard('user')->check())
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                <input type="text" name="first_name" class="form-control" placeholder="First Name" required>
                                            </div>
                                        </div>                 
                                    </div>    
                                    <div class="col-lg-6">
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                <input type="text" name="last_name" class="form-control" placeholder="Last Name" required>
                                            </div>
                                        </div>                 
                                    </div>  
                                </div>                         
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                <input type="email" name="email" class="form-control" placeholder="Your Email Address" required>
                                            </div>
                                        </div>                 
                                    </div>    
                                    <div class="col-lg-6">
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                <input type="number" inputmode="numeric" name="phone" class="form-control" placeholder="Mobile Number" required>
                                            </div>
                                        </div>                 
                                    </div>  
                                </div> 
                            @endif
                            @if($product->note_status!=0)
                            <div class="form-group row">
                                <div class="col-lg-12">
                                    <textarea type="text" name="note" class="form-control" rows="" placeholder="Delivery Note @if($product->note_status==1)(Optional) @endif" @if($product->note_status==2)required="" @endif></textarea>
                                </div>
                            </div>
                            @endif
                            @if($product->shipping_status==1)
                            <div class="form-group row">                           
                                <div class="col-lg-6">
                                    <select class="form-control custom-select" name="country" id="country" required>
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <select class="form-control custom-select" name="state" id="state" required>
                                    </select>
                                </div>
                            </div>     
                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <input type="text" name="address" class="form-control" placeholder="Your Address" required>
                                </div> 
                                <div class="col-lg-6">
                                    <input type="text" name="town" class="form-control" placeholder="Town/City" required>
                                </div>
                            </div> 
                            <div class="form-group row">    
                                <label class="col-form-label col-lg-12">{{__('Shipping Fee')}}</label>                       
                                <div class="col-lg-12">
                                    <select class="form-control custom-select" name="shipping" id="ship_fee" required>
                                        @foreach($ship as $fval)
                                        <option value="{{$fval->id}}-{{$fval->amount}}">{{$fval->region}} {{$currency->symbol.$fval->amount}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>  
                            <input type="hidden" id="xship" name="xship">                                                      
                            <input type="hidden" id="xship_fee" name="shipping_fee">   
                            <hr>                                                   
                            @endif
                            <div class="row justify-content-between align-items-center">
                            <div class="col">
                                <span class="text-sm text-default">{{__('Product')}}</span><br>
                                <span class="text-sm text-default">{{__('Subtotal')}}</span>
                            </div>
                            <div class="col-auto">
                            @if($product->shipping_status==1)  
                                <span class="text-sm text-default">{{$product->name}} x <span id="product1">1</span></span><br>
                                <span class="text-sm text-default">{{$currency->symbol}}<span id="subtotal1">{{$subtotal}}</span>.00</span>
                            @else
                                <span class="text-sm text-default">{{$product->name}} x <span id="product4">1</span></span><br>
                                <span class="text-sm text-default">{{$currency->symbol}}<span id="subtotal4">{{$subtotal}}</span>.00</span>
                            @endif
                            </div>
                        </div>  
                        <hr>  
                        @if($product->shipping_status==1)                
                        <div class="row justify-content-between align-items-center">
                            <div class="col">
                                <span class="text-sm text-default">{{__('Shipping')}}</span>
                            </div>
                            <div class="col-auto">
                                <span class="text-sm text-default">{{__('Flat rate')}}: {{$currency->symbol}}<span id="flat"></span></span>
                            </div>
                        </div>
                        <hr>
                        @endif
                        <div class="row justify-content-between align-items-center mb-5">
                            <div class="col">
                                <span class="text-sm text-default">{{__('Total')}}</span>
                            </div>
                            <div class="col-auto">
                                @if($product->shipping_status==1)  
                                    <span class="text-sm text-default">{{$currency->symbol}}<span id="total1">{{$total}}</span>.00</span>
                                @else
                                    <span class="text-sm text-default">{{$currency->symbol}}<span id="total4">{{$total}}</span>.00</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div id="carouselExampleIndicators" class="carousel slide bg-transparent mb-2" data-ride="carousel">
                    <div class="carousel-inner bg-transparent">
                        @if($product->new==0)
                            <div class="carousel-item active">
                                <img class="d-block w-80" src="{{url('/')}}/asset/images/product-placeholder.jpg" alt="product image">
                            </div>
                        @else
                            @foreach($image as $k=>$val)
                            <div class="carousel-item bg-transparent @if($val->id==$first->id)active @endif">
                                <img class="d-block w-100" src="{{url('/')}}/asset/profile/{{$val->image}}" alt="product image">
                            </div>
                            @endforeach
                        @endif 
                    </div>
                    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">{{__('Previous')}}</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">{{__('Next')}}</span>
                    </a>
                </div>
                <div class="card">
                    <div class="card-body">
                        @if(Session::get('pay-type')=='account')
                            @if (Auth::guard('user')->check())
                                <div class="text-center"> 
                                    <div class="text-center">
                                        @if($product->quantity_status==1)
                                            @if($product->status==1)
                                                <h4 class="mb-0">Account Balance</h4>
                                                <h1 class="mb-1 text-muted font-weight-bolder">{{$currency->symbol.number_format($user->balance, 2, '.', '')}}</h1>
                                                <input type="hidden" value="account" name="type">
                                                <button type="submit" class="btn btn-neutral my-4 btn-block"><i class="fad fa-external-link"></i> {{__('Pay')}}</button>
                                            @else
                                                <button type="submit" disabled class="btn btn-neutral btn-sm">{{__('NOT AVAILABLE')}}</button>
                                            @endif                                                             
                                        @elseif($product->quantity_status==0)
                                            @if($product->quantity!=0)
                                                @if($product->status==1)
                                                    <h4 class="mb-0">Account Balance</h4>
                                                    <h1 class="mb-1 text-muted font-weight-bolder">{{$currency->symbol.number_format($user->balance)}}</h1>
                                                    <input type="hidden" value="account" name="type">
                                                    <button type="submit" class="btn btn-neutral my-4 btn-block"><i class="fad fa-external-link"></i> {{__('Pay')}}</button>
                                                @else
                                                    <button type="submit" disabled class="btn btn-neutral btn-sm">{{__('NOT AVAILABLE')}}</button>
                                                @endif
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @else
                            @php Session::put('oldLink', url()->current()); @endphp
                            <h3 class="mb-3 text-muted font-weight-bolder">Login to make payment</h3>
                            <a href="{{route('login')}}" class="btn btn-neutral btn-block">Login</a>
                            @endif
                        @else
                            <div class="row">                           
                                <div class="col-lg-12">	                
                                    <div class="text-center">
                                        @if($product->quantity_status==1)
                                            @if($product->status==1)
                                                <div id="card-element"></div>
                                                <div id="card-errors" role="alert"></div>         		
                                                <input type="hidden" value="card" name="type">  
                                                <button type="submit" class="btn btn-neutral my-4 btn-block"><i class="fad fa-external-link"></i> {{__('Pay')}}</button>
                                            @else
                                                <button type="submit" disabled class="btn btn-neutral btn-sm">{{__('NOT AVAILABLE')}}</button>
                                            @endif                                                             
                                        @elseif($product->quantity_status==0)
                                            @if($product->quantity!=0)
                                                @if($product->status==1)
                                                    <div id="card-element"></div>
                                                    <div id="card-errors" role="alert"></div>         		
                                                    <input type="hidden" value="card" name="type">  
                                                    <button type="submit" class="btn btn-neutral my-4 btn-block"><i class="fad fa-external-link"></i> {{__('Pay')}}</button>
                                                @else
                                                    <button type="submit" disabled class="btn btn-neutral btn-sm">{{__('NOT AVAILABLE')}}</button>
                                                @endif
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                        </form>
                        <div class="text-center">
                            @if($merchant->facebook!=null)
                                <a href="{{$merchant->facebook}}"><i class="sn fab fa-facebook"></i></a>   
                            @endif 
                            @if($merchant->twitter!=null)                      
                                <a href="{{$merchant->twitter}}"><i class="sn fab fa-twitter"></i></a>
                            @endif      
                            @if($merchant->linkedin!=null)                     
                                <a href="{{$merchant->linkedin}}"><i class="sn fab fa-linkedin"></i></a> 
                            @endif     
                            @if($merchant->instagram!=null)                        
                                <a href="{{$merchant->instagram}}"><i class="sn fab fa-instagram"></i></a>   
                            @endif 
                            @if($merchant->youtube!=null)                          
                                <a href="{{$merchant->youtube}}"><i class="sn fab fa-youtube"></i></a>  
                            @endif                          
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

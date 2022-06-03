@extends('userlayout')

@section('content')
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-4">
        <div class="card">
          <div class="card-header">
            <h5 class="h3 mb-0 font-weight-bolder">{{__('Shareable URL')}}</h5>
          </div>
          <div class="card-body">
            <span class="form-text text-xs">{{route('user.ask', ['id' => $product->ref_id])}}</span>
            <button type="button" class="btn-icon-clipboard text-uppercase" data-clipboard-text="{{route('user.ask', ['id' => $product->ref_id])}}" title="Copy">{{__('Copy product link')}}</button>
          </div>
        </div>        
        <div class="card">
          <div class="card-header">
            <h5 class="h3 mb-0 font-weight-bolder">{{__('Media')}}</h5>
          </div>
          <div class="card-body">
            <p>Recommended Imge size is 399x399, Image limit is 6</p>
            <ul class="list-group list-group-flush list">
              @foreach($images as $k=>$val)
                <li class="list-group-item px-0">
                  <div class="row align-items-center">
                    <div class="col-auto">
                      <!-- Avatar -->
                      <a href="#" class="avatar">
                        <img alt="Image placeholder" src="{{url('/')}}/asset/profile/{{$val->image}}">
                      </a>
                    </div>
                    <div class="col ml--2">
                    <span class="text-gray text-uppercase form-text">{{$val->image}}</span>
                    </div>
                    <div class="col-auto">
                      <a href="{{route('delete.product.image', ['id' => $val->id])}}" class="btn btn-sm btn-neutral"><i class="fad fa-trash"></i> {{__('Delete')}}</a>
                    </div>
                  </div>
                </li>
              @endforeach
            </ul>
            <form action="{{route('submit.product.image')}}" enctype="multipart/form-data" method="post">
              @csrf
              <div class="form-group row">
                <div class="col-lg-12">
                  <div class="custom-file text-center">
                    <input type="hidden" value="{{$product->id}}" name="id">
                    <input type="file" class="custom-file-input" name="file" accept="image/*" id="customFileLang">
                    <label class="custom-file-label" for="customFileLang">{{__('Choose Media')}}</label>
                  </div>
                </div>
              </div> 
  
              <div class="text-right">
                <button type="submit" class="btn btn-neutral btn-block">{{__('Upload')}}</a>
              </div>  
            </form>
          </div>
        </div>
      </div>
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">
            <h5 class="h3 mb-0 font-weight-bolder">{{__('Edit product')}}</h5>
          </div>
          <div class="card-body">
            <form action="{{route('product.feature.submit')}}" method="post">
              @csrf
              <div class="row">
                <div class="col-lg-4">
                  <div class="form-group row">
                    <label class="col-form-label col-lg-12">{{__('Status')}}</label>
                    <div class="col-lg-12">
                      <label class="custom-toggle custom-toggle-primary">
                        @if($product->status==1)
                          <input type="checkbox" name="status" class="" value="1" checked>
                        @else
                          <input type="checkbox" name="status" class="" value="1">
                        @endif
                        <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                      </label>
                    </div>
                  </div>                 
                </div>    
                <div class="col-lg-4">             
                  <div class="form-group row">
                    <label class="col-form-label col-lg-12">{{__('Shipping Status')}}</label>
                    <div class="col-lg-12">
                      <label class="custom-toggle custom-toggle-primary">
                        @if($product->shipping_status==1)
                          <input type="checkbox" name="shipping_status" class="" value="1" checked>
                        @else
                          <input type="checkbox" name="shipping_status" class="" value="1">
                        @endif
                        <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                      </label>
                    </div>
                  </div> 
                </div>
              </div>    
              <div class="form-group row">
                <div class="col-lg-12">
                <span class="form-text text-xs">{{__('Describe your product vividly to give customers a reason to buy and increase your sales.')}}</span>
                <a data-toggle="modal" data-target="#description" href="" class="btn btn-white btn-sm">{{__('Add Description')}}</a>
                </div>
              </div>                            
              <div class="form-group row">
                <label class="col-form-label col-lg-3">{{__('Delivery Address')}}</label>
                <div class="col-lg-3">
                  <input type="hidden" value="{{$product->id}}" name="id">
                  <select class="form-control custom-select" name="add_status" required>
                    <option value='0' @if($product->add_status==0) selected @endif>{{__('Disabled')}}</option>
                    <option value='1' @if($product->add_status==1) selected @endif>{{__('Required')}}</option>
                  </select>
                </div>
                <label class="col-form-label col-lg-3">{{__('Delivery Note')}}</label>
                <div class="col-lg-3">
                  <select class="form-control custom-select" name="note_status" required>
                    <option value='0' @if($product->note_status==0) selected @endif>{{__('Disabled')}}</option>
                    <option value='1' @if($product->note_status==1) selected @endif>{{__('Required')}}</option>
                    <option value='2' @if($product->note_status==2) selected @endif>{{__('Optional')}}</option>
                  </select>
                </div>
              </div>              
              <div class="form-group row">
                <label class="col-form-label col-lg-3">{{__('Quantity type')}}</label>
                <div class="col-lg-3">
                  <select class="form-control custom-select" name="quantity_status" required>
                    <option value='0' @if($product->quantity_status==0) selected @endif>Limited</option>
                    <option value='1' @if($product->quantity_status==1) selected @endif>Unlimited</option>
                  </select>
                </div>                
                <label class="col-form-label col-lg-2">{{__('Category')}}</label>
                <div class="col-lg-4">
                  <select class="form-control custom-select" name="cat_id" required>
                    <option value="">Select Category</option>
                    @foreach($category as $val)
                      <option value="{{$val->id}}" @if($val->id==$product->cat_id) selected @endif>{{$val->name}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-form-label col-lg-5">{{__('Amount')}}</label>
                <div class="col-lg-7">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">{{$currency->symbol}}</span>
                    </div>
                    <input type="number" step="any" name="amount" value="{{$product->amount}}" maxlength="10" class="form-control" required="">
                  </div>
                </div>
              </div>
              @if($product->quantity_status==0)
              <div class="form-group row">
                <label class="col-form-label col-lg-4">{{__('Quantity')}}</label>
                <div class="col-lg-8">
                  <input type="number" name="quantity" class="form-control" value="{{$product->quantity}}" required>
                </div>
              </div> 
              @endif             
              <div class="text-right">
                <button type="submit" class="btn btn-neutral btn-sm">{{__('Save')}}</a>
              </div>         
            </form>
          </div>
        </div> 
        <div class="modal fade" id="description" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h3 class="mb-0 font-weight-bolder">{{__('Description')}}</h3>
              </div>
              <div class="modal-body">
                <form action="{{route('product.description.submit')}}" method="post">
                  @csrf
                  <div class="form-group">
                    <textarea type="text" name="description" rows="5" class="form-control" placeholder="Describe your product">{{$product->description}}</textarea>
                    <input type="hidden" value="{{$product->id}}" name="id">
                  </div>              
                  <div class="text-right">
                    <button type="submit" class="btn btn-neutral btn-block">{{__('Save')}}</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div> 
      </div>
    </div>
@stop

@extends('userlayout')

@section('content')
<!-- Page content -->
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="row align-items-center py-4">
      <div class="col-lg-6 col-7">
        <h6 class="h2 d-inline-block mb-0">{{__('Products')}}</h6>
      </div>
      <div class="col-lg-6 col-5 text-right">
        <a data-toggle="modal" data-target="#category" href="" class="btn btn-sm btn-neutral"><i class="fad fa-filter"></i> {{__('Category')}}</a> 
        <a data-toggle="modal" data-target="#new-product" href="" class="btn btn-sm btn-neutral"><i class="fad fa-plus"></i> {{__('Create Product')}}</a> 
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
                <div class="col-lg-12">
                  <div class="custom-file text-center">
                    <input type="hidden" value="{{$product->id}}" name="id">
                    <input type="file" class="custom-file-input" name="file" accept="image/*" id="customFileLang">
                    <label class="custom-file-label" for="customFileLang">{{__('Choose Media')}}</label>
                  </div>
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
                    <input type="number" step="any" name="amount" maxlength="10" class="form-control" required="">
                  </div>
                </div>
              </div>  
              <div class="form-group row">
                <label class="col-form-label col-lg-12">{{__('Quantity')}}</label>
                <div class="col-lg-12">
                  <input type="number" name="quantity" class="form-control" value="1" required>
                </div>
              </div>               
              <div class="text-right">
                <button type="submit" class="btn btn-neutral btn-block">{{__('Create product')}}</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div> 
    <div class="row">  
      <div class="col-md-8">
      @if(count($product)>0)
        @foreach($product as $k=>$val)
          <div class="card">
            <!-- Card body -->
            <div class="card-body">
              <div class="row mb-0">
                <div class="col-6">
                  <span class="form-text text-xl">{{$currency->symbol}} {{number_format($val->amount)}}.00</span>
                </div>
                <div class="col-6 text-right">
                  <a class="mr-0 text-dark" data-toggle="dropdown" aria-haspopup="true" aria-expanded="fadse">
                    <i class="fad fa-chevron-circle-down"></i>
                  </a>
                  <div class="dropdown-menu dropdown-menu-left">
                    @if($val->status==1)
                      <a class="dropdown-item" href="{{route('edit.product', ['id' => $val->ref_id])}}"><i class="fad fa-pencil"></i>{{__('Edit')}}</a>
                      <a class="dropdown-item" href="{{route('orders', ['id' => $val->id])}}"><i class="fad fa-sync"></i>{{__('Orders')}}</a>
                    @endif
                    <a class="dropdown-item" data-toggle="modal" data-target="#delete{{$val->id}}" href="#"><i class="fad fa-trash-alt"></i>{{__('Delete')}}</a>
                  </div>
                </div>
              </div>
              <div class="row align-items-center">
                <div class="col-auto">
                  <!-- Avatar -->
                  <a href="javascript:void;" class="avatar avatar-xl">
                    <img               
                    @if($val->new==0)
                      src="{{url('/')}}/asset/images/product-placeholder.jpg"
                    @else
                      @php $image=App\Models\Productimage::whereproduct_id($val->id)->first();@endphp
                      src="{{url('/')}}/asset/profile/{{$image['image']}}"
                    @endif alt="Image placeholder">
                  </a>
                </div>
                <div class="col">
                  <p class="">{{$val->name}}</p>
                  <p class="">Sold: {{$val->sold}}/{{$val->quantity}}</p>
                  <p class="text-sm mb-2"><a class="btn-icon-clipboard text-uppercase" data-clipboard-text="{{route('product.link', ['id' => $val->ref_id])}}" title="Copy">{{__('Copy Product Link')}}</a></p>
                  @if($val->status==1)
                      <span class="badge badge-pill badge-primary"><i class="fad fa-check"></i> {{__('Active')}}</span>
                  @else
                      <span class="badge badge-pill badge-danger">{{__('Disabled')}}</span>
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
      <div class="col-md-4">
        <div class="card">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col text-center">
                <h4 class="mb-4 text-primary font-weight-bolder">
                {{__('Statistics')}}
                </h4>
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
                  <span class="surtitle ">{{$currency->name}} 00.00</span><br>
                  <span class="surtitle ">{{$currency->name}} {{number_format($total)}}.00</span>
                </div>
              </div>
            </div>
          </div>
        </div> 
      </div>
    </div>
@stop
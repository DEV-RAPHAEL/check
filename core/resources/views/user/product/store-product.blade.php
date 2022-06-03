@extends('userlayout')

@section('content')
<div class="container-fluid mt--6">
    <div class="content-wrapper">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0 font-weight-bolder">{{__('Products')}}</h3>
            </div>             
            <div class="card-body">
                <form action="{{route('submit.storeproduct')}}" method="post">
                    @csrf
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <select class="form-control custom-select" name="product" required>
                                <option value="">Select Products</option>
                                @foreach($new as $val)
                                    <option value="{{$val->id}}">{{$val->name}}</option>
                                @endforeach
                            </select>
                        </div>       
                    </div> 
                    <input type="hidden" value="{{$store_id}}" name="id"> 
                    <div class="text-right">
                        <button type="submit" class="btn btn-neutral btn-block"><i class="fad fa-external-link"></i> {{__('Add Product')}}</button>
                    </div>
                    <ul class="list-group list-group-flush list">
                        @if(count($product)>0)
                            @foreach($product as $k=>$val)
                                @php $image=App\Models\Productimage::whereproduct_id($val->pd->id)->first();@endphp
                                <li class="list-group-item px-0">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <a href="{{route('edit.product', ['id' => $val->pd->ref_id])}}" class="avatar">
                                                <img alt="Image placeholder" src="{{url('/')}}/asset/profile/{{$image->image}}">
                                            </a>
                                        </div>
                                        <div class="col ml--2">
                                            <span class="text-xs">{{$val->pd->name}}</span>
                                        </div>
                                        <div class="col-auto">
                                        <a href="{{route('delete.storefrontproduct', ['id' => $val->id])}}" class="btn btn-sm btn-neutral"><i class="fad fa-trash"></i> {{__('Delete')}}</a>
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
                                <h3 class="text-dark">No Product Found</h3>
                                <p class="text-dark text-sm card-text">We couldn't find any product to this store</p>
                            </div>
                            </div>
                        </div>
                        @endif
                    </ul>                   
                </form>
            </div>   
        </div>   
@stop
@extends('shoplayout')

@section('content')

<div class="container-indent">
    <div class="container container-fluid-custom-mobile-padding">
        <div class="tt-block-title">
            <h1 class="tt-title">{{$title}}</h1>
            <div class="tt-description">STORE</div>
        </div>
        <div class="row tt-layout-product-item">
            @foreach($products as $k=>$val)
                @php $product=App\Models\Product::whereid($val->product_id)->first();@endphp
                @if($product->active==1)
                    @if($product->status==1)
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="tt-product thumbprod-center">
                                <div class="tt-image-box">
                                    <a href="{{route('sproduct.link', ['store'=>$store->id,'product'=>$product->id])}}">
                                        <span class="tt-img"><img
                                            @if($product->new==0)
                                                data-src="{{url('/')}}/asset/images/product-placeholder.jpg"
                                            @else
                                                @php $sub=App\Models\Productimage::whereproduct_id($product->id)->first();@endphp
                                                data-src="{{url('/')}}/asset/profile/{{$sub->image}}"
                                            @endif>
                                        </span>
                                    </a>
                                </div>
                                <div class="tt-description">
                                    <div class="tt-row">
                                        <ul class="tt-add-info">
                                            <li><a href="javascript:void;">{{$product->cat['name']}}</a></li>
                                        </ul>
                                    </div>
                                    <h2 class="tt-title"><a href="{{route('sproduct.link', ['store'=>$store->id,'product'=>$product->id])}}">{{$product->name}}</a></h2>
                                    <div class="tt-price">
                                    {{$currency->symbol.$product->amount}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            @endforeach
        </div><br><br><br>
        <div class="row">
          <div class="col-md-12">
          {{ $products->links() }}
          </div>
        </div>
    </div>
</div>
@stop
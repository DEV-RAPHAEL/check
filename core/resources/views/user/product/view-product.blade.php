@extends('shoplayout')

@section('content')

<div class="container-indent">
		<!-- mobile product slider  -->
		<div class="tt-mobile-product-layout visible-xs">
			<div class="tt-mobile-product-slider arrow-location-center slick-animated-show-js">
                @foreach($image as $k=>$val)
                    <div><img src="{{url('/')}}/asset/profile/{{$val->image}}" alt=""></div>
                @endforeach
			</div>
		</div>
		<!-- /mobile product slider  -->
		<div class="container container-fluid-mobile">
			<div class="row">
				<div class="col-6 hidden-xs">
					<div class="tt-product-vertical-layout">
						<div class="tt-product-single-img">
							<div>
								<button class="tt-btn-zomm tt-top-right"><i class="icon-f-86"></i></button>
								<img id="imageDiv" class="zoom-product" src="{{url('/')}}/asset/profile/{{$rr->image}}" data-zoom-image="{{url('/')}}/asset/profile/{{$rr->image}}" alt="">
							</div>
						</div>
						<div class="tt-product-single-carousel-vertical">
							<ul id="smallGallery" class="tt-slick-button-vertical  slick-animated-show-js">
                            @foreach($image as $k=>$val)
								<li><a href="#" @if($val->id==$rr->id) class="zoomGalleryActive" @endif data-image="{{url('/')}}/asset/profile/{{$val->image}}" data-zoom-image="{{url('/')}}/asset/profile/{{$val->image}}"><img id="galleryimg{{$val->id}}" onclick="productGallery(this.id)" src="{{url('/')}}/asset/profile/{{$val->image}}" alt=""></a></li>
                            @endforeach
							</ul>
						</div>
					</div>
				</div>
				<div class="col-6">
					<div class="tt-product-single-info">
						<div class="tt-add-info">
							<ul>
                            @if($product->quantity!=0)
                                <li><span>Availability:</span> {{$product->quantity}} in Stock</li>
                            @else
                                <li><span>Availability:</span> Out Of Stock</li>
                            @endif
							</ul>
						</div>
						<h1 class="tt-title">{{$product->name}}</h1>
						<div class="tt-price">
							<span class="new-price">{{$currency->symbol.$product->amount}}</span>
						</div>
						<div class="tt-wrapper">
                        {{$product->description}}
                        </div>
                        <form action="{{route('update.cart')}}" method="post">
                            <div class="tt-wrapper">
                                <div class="tt-row-custom-01">
                                    @csrf
                                    @if(Session::has('uniqueid'))
                                        <input type="hidden" name="uniqueid" value="{{Session::get('uniqueid')}}">
                                    @else
                                        <input type="hidden" name="uniqueid" value="{{str_random(7)}}">
                                    @endif
                                    <input type="hidden" name="cost" value="{{$product->amount}}">
                                    <input type="hidden" name="product" value="{{$product->id}}">
                                    <input type="hidden" name="title" value="{{$product->name}}">
                                    <input type="hidden" name="store" value="{{$store->id}}">
                                    <div class="col-item">
                                        <div class="tt-input-counter style-01">
                                            <input type="number" value="1" size="{{$product->quantity}}" name="quantity">
                                        </div>
                                    </div>
                                    <div class="col-item">
                                        @if($product->quantity!=0)
                                        <button type="submit" class="btn btn-lg"><i class="icon-f-39"></i>ADD TO CART</button>
                                        @else
                                            <a href="#"  class="btn btn-lg disabled"><i class="icon-f-39"></i>Out Of Stock</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </form>
					</div>
				</div>
			</div>
		</div>
</div>
@stop
<div class="col-lg-4 lg-m-30px-tb">
    <div class="card m-35px-t">
        <div class="card-header bg-transparent">
            <span class="h6 m-0px">Categories</span>
        </div>
        <div class="list-group list-group-flush">
            @foreach($cat as $vcat)  
                @php
                    $cslug=str_slug($vcat->categories);
                    $rate=count(DB::select('select * from trending where cat_id=? and status=?', [$vcat->id,1]));
                @endphp 
            <a href="{{url('/')}}/cat/{{$vcat->id}}/{{$cslug}}" class="list-group-item list-group-item-action d-flex justify-content-between p15px-tb">
                <div>
                    <span>{{$vcat->categories}}</span>
                </div>
                <div>
                    <i class="ti-angle-right"></i>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    <div class="card m-35px-t">
        <div class="card-header bg-transparent">
            <span class="h6 m-0px">Recent Posts</span>
        </div>
        <div class="list-group list-group-flush">
        @foreach($trending as $vtrending)
                @php $vslug=str_slug($vtrending->title); @endphp
            <a href="{{url('/')}}/single/{{$vtrending->id}}/{{$vslug}}" class="list-group-item list-group-item-action d-flex p15px-tb">
                <div>
                    <div class="avatar-50 border-radius-5">
                        <img src="{{url('/')}}/asset/thumbnails/{{$vtrending->image}}" title="" alt="">
                    </div>
                </div>
                <div class="p-15px-l">
                    <p class="m-0px">{{$vtrending->title}}</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>
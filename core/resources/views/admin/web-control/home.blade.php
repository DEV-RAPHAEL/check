@extends('master')

@section('content')
<div class="container-fluid mt--6">
    <div class="content-wrapper">
        <div class="row">   
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{__('Edit content')}}</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{route('homepage.update')}}" method="post">
                        @csrf
                            <div class="form-group row">
                                <div class="col-lg-12">
                                    <input type="text" name="header_title" class="form-control" value="{{$ui->header_title}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-12">
                                    <textarea type="text" name="header_body" rows="4" class="form-control">{{$ui->header_body}}</textarea>
                                </div>
                            </div>                        
                            <div class="form-group row">
                                <div class="col-lg-12">
                                    <input type="text" name="s2_title" class="form-control" value="{{$ui->s2_title}}">
                                </div>
                            </div>                          
                            <div class="form-group row">
                                <div class="col-lg-12">
                                    <input type="text" name="s3_title" class="form-control" value="{{$ui->s3_title}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-12">
                                    <textarea type="text" name="s3_body" rows="10" class="form-control">{{$ui->s3_body}}</textarea>
                                </div>
                            </div>                                                                    
                            <div class="form-group row">
                                <div class="col-lg-12">
                                    <input type="text" name="s6_title" class="form-control" value="{{$ui->s6_title}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-12">
                                    <textarea type="text" name="s6_body" rows="10" class="form-control">{{$ui->s6_body}}</textarea>
                                </div>
                            </div>                         
                            <div class="form-group row">
                                <div class="col-lg-12">
                                    <input type="text" name="s7_title" class="form-control" value="{{$ui->s7_title}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-12">
                                    <textarea type="text" name="s7_body" rows="4" class="form-control">{{$ui->s7_body}}</textarea>
                                </div>
                            </div>  
                            <div class="form-group row">
                                <div class="col-lg-12">
                                    <input type="text" name="s1_title" class="form-control" value="{{$ui->s1_title}}">
                                </div>
                            </div>    
                            <div class="text-right">
                            <button type="submit" class="btn btn-success btn-sm">{{__('Save')}}</button>
                            </div>
                    </form>
                </div>
                </div>    
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="card-img-actions d-inline-block mb-3">
                            <img class="img-fluid" src="{{url('/')}}/asset/images/{{$ui->s2_image}}" alt="" class="blog-imaged">
                        </div>
                        <form action="{{url('admin/section1/update')}}" enctype="multipart/form-data" method="post">
                        @csrf
                            <div class="form-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="customFileLang" name="section1" lang="en" required>
                                    <label class="custom-file-label" for="customFileLang">{{__('Choose Media')}}</label>
                                </div>
                            </div>            
                            <div class="text-right">
                            <button type="submit" class="btn btn-success btn-sm">{{__('Save')}}</button>
                            </div>
                        </form>
                    </div>
                </div>           
                <div class="card">
                    <div class="card-body text-center">
                        <div class="card-img-actions d-inline-block mb-3">
                            <img class="img-fluid" src="{{url('/')}}/asset/images/{{$ui->s3_image}}" alt="" class="blog-imaged">
                        </div>
                        <form action="{{url('admin/section2/update')}}" enctype="multipart/form-data" method="post">
                        @csrf
                            <div class="form-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="customFileLang" name="section2" lang="en" required>
                                    <label class="custom-file-label" for="customFileLang">{{__('Choose Media')}}</label>
                                </div>
                            </div>             
                            <div class="text-right">
                            <button type="submit" class="btn btn-success btn-sm">{{__('Save')}}</button>
                            </div>
                        </form>
                    </div>
                </div>            
                <div class="card">
                    <div class="card-body text-center">
                        <div class="card-img-actions d-inline-block mb-3">
                            <img class="img-fluid" src="{{url('/')}}/asset/images/{{$ui->s4_image}}" alt="" class="blog-imaged">
                        </div>
                        <form action="{{url('admin/section3/update')}}" enctype="multipart/form-data" method="post">
                        @csrf
                            <div class="form-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="customFileLang" name="section3" lang="en" required>
                                    <label class="custom-file-label" for="customFileLang">{{__('Choose Media')}}</label>
                                </div>
                            </div>                   
                            <div class="text-right">
                            <button type="submit" class="btn btn-success btn-sm">{{__('Save')}}</button>
                            </div>
                        </form>
                    </div>
                </div>             
                <div class="card">

                    <div class="card-body text-center">
                        <div class="card-img-actions d-inline-block mb-3">
                            <img class="img-fluid" src="{{url('/')}}/asset/images/{{$ui->s7_image}}" alt="" class="blog-imaged">
                        </div>
                        <form action="{{url('admin/section7/update')}}" enctype="multipart/form-data" method="post">
                        @csrf
                            <div class="form-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="customFileLang" name="section7" lang="en" required>
                                    <label class="custom-file-label" for="customFileLang">{{__('Choose Media')}}</label>
                                </div>
                            </div>                  
                            <div class="text-right">
                            <button type="submit" class="btn btn-success btn-sm">{{__('Save')}}</button>
                            </div>
                        </form>
                    </div>
                </div>                                 
            </div>
        </div>
    </div>
</div>
@stop
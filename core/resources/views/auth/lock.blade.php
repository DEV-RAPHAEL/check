@extends('loginlayout')

@section('content')
<div class="main-content">
    <!-- Header -->
    <div class="header py-9 pt-7">
      <div class="container">
        <div class="header-body text-center mb-7">
        </div>
      </div>
    </div>
    <!-- Page content -->
    <div class="container mt--8 pb-5">
      <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
          <div class="card card-profile border-0 mb-0">
            <div class="card-body px-lg-5 py-lg-5">
                <div class="text-center text-dark">
                    <h2 class="text-dark font-weight-bolder">{{__('Unlock Script') }}</h2>
                    <p>{{__('How to unlock boompay, add a valid purchase code to core/.env by updating ENVATO_PURCHASECODE') }}</p>
                    <p>
                    <?php 
                    session_start();
                    echo $_SESSION["error"]; 
                    session_destroy()
                    ?></p>
                </div>
            </div>
          </div>
          <div class="row justify-content-center mt-5">
            <a href="{{url('/')}}"><i class="fad fa-sync"></i> Refresh</a>
          </div>
        </div>
      </div>
    </div>
@stop

<script>
window.history.replaceState({}, document.title, "/" + "zebra");
</script>
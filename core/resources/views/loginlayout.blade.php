<!doctype html>
<html class="no-js" lang="en">
    <head>
        <base href="{{url('/')}}"/>
        <title>{{ $title }} | {{$set->site_name}}</title>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1" />
        <meta name="robots" content="index, follow">
        <meta name="apple-mobile-web-app-title" content="{{$set->site_name}}"/>
        <meta name="application-name" content="{{$set->site_name}}"/>
        <meta name="msapplication-TileColor" content="#ffffff"/>
        <meta name="description" content="{{$set->site_desc}}" />
        <link rel="shortcut icon" href="{{url('/')}}/asset/{{$logo->image_link2}}" />
        <link rel="stylesheet" href="{{url('/')}}/asset/css/toast.css" type="text/css">
        <link rel="stylesheet" href="{{url('/')}}/asset/dashboard/vendor/nucleo/css/nucleo.css" type="text/css">
        <link rel="stylesheet" href="{{url('/')}}/asset/dashboard/vendor/@fortawesome/fontawesome-free/css/all.min.css" type="text/css">
        <link rel="stylesheet" href="{{url('/')}}/asset/dashboard/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="{{url('/')}}/asset/dashboard/vendor/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css">
        <link rel="stylesheet" href="{{url('/')}}/asset/dashboard/vendor/datatables.net-select-bs4/css/select.bootstrap4.min.css">
        <link rel="stylesheet" href="{{url('/')}}/asset/dashboard/css/argon.css?v=1.1.0" type="text/css">
        <link rel="stylesheet" href="{{url('/')}}/asset/css/sweetalert.css" type="text/css">
        <link href="{{url('/')}}/asset/fonts/fontawesome/css/all.css" rel="stylesheet" type="text/css">
         @yield('css')
    </head>
<!-- header begin-->
  <body class="bg-secondary">
    <nav id="navbar-main" class="navbar navbar-horizontal navbar-transparent navbar-main navbar-expand-lg navbar-dark">
      <div class="container">
      @if (Auth::guard('user')->check()) 
        <a class="navbar-brand text-dark" href="{{route('user.logout')}}">
          <span><i class="fad fa-sign-out"></i>  {{__('Logout')}}</span>
        </a>
        <div class="navbar-collapse navbar-custom-collapse collapse" id="navbar-collapse">
          <div class="navbar-collapse-header">
            <div class="row">
              <div class="col-6 collapse-brand">
                <a href="{{route('user.dashboard')}}">
                  <i class="fad fa-arrow-left"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
      @else
        <a class="navbar-brand text-dark" href="{{url('/')}}">
          <span><i class="fad fa-arrow-left"></i>  {{__('Home')}}</span>
        </a>
        <div class="navbar-collapse navbar-custom-collapse collapse" id="navbar-collapse">
          <div class="navbar-collapse-header">
            <div class="row">
              <div class="col-6 collapse-brand">
                <a href="{{url('/')}}">
                  <i class="fad fa-arrow-left"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
      @endif
      </div>
    </nav>
<!-- header end -->

@yield('content')


<!-- footer begin -->
{!!$set->livechat!!}
  <!-- Argon Scripts -->
  <!-- Core -->
  <script src="{{url('/')}}/asset/dashboard/vendor/jquery/dist/jquery.min.js"></script>
    <script src="{{url('/')}}/asset/dashboard/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{url('/')}}/asset/dashboard/vendor/js-cookie/js.cookie.js"></script>
    <script src="{{url('/')}}/asset/dashboard/vendor/jquery.scrollbar/jquery.scrollbar.min.js"></script>
    <script src="{{url('/')}}/asset/dashboard/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js"></script>
    <script src="{{url('/')}}/asset/dashboard/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{url('/')}}/asset/dashboard/vendor/chart.js/dist/Chart.extension.js"></script>
    <script src="{{url('/')}}/asset/dashboard/vendor/jvectormap-next/jquery-jvectormap.min.js"></script>
    <script src="{{url('/')}}/asset/dashboard/js/vendor/jvectormap/jquery-jvectormap-world-mill.js"></script>
    <script src="{{url('/')}}/asset/dashboard/vendor/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="{{url('/')}}/asset/dashboard/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{url('/')}}/asset/dashboard/vendor/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="{{url('/')}}/asset/dashboard/vendor/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
    <script src="{{url('/')}}/asset/dashboard/vendor/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="{{url('/')}}/asset/dashboard/vendor/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="{{url('/')}}/asset/dashboard/vendor/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="{{url('/')}}/asset/dashboard/vendor/datatables.net-select/js/dataTables.select.min.js"></script>
    <script src="{{url('/')}}/asset/dashboard/vendor/clipboard/dist/clipboard.min.js"></script>
    <script src="{{url('/')}}/asset/dashboard/vendor/select2/dist/js/select2.min.js"></script>
    <script src="{{url('/')}}/asset/dashboard/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="{{url('/')}}/asset/dashboard/vendor/nouislider/distribute/nouislider.min.js"></script>
    <script src="{{url('/')}}/asset/dashboard/vendor/quill/dist/quill.min.js"></script>
    <script src="{{url('/')}}/asset/dashboard/vendor/dropzone/dist/min/dropzone.min.js"></script>
    <script src="{{url('/')}}/asset/dashboard/vendor/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>
    <script src="{{url('/')}}/asset/dashboard/js/argon.js?v=1.1.0"></script>
    <script src="{{url('/')}}/asset/dashboard/js/demo.min.js"></script>
    <script src="{{url('/')}}/asset/js/toast.js"></script>
    <script src="{{url('/')}}/asset/js/countries.js"></script>
</body>

</html>
@yield('script')
<script>
  populateCountries("country", "state");
  populateIndustry("industry", "category");
</script>
<script>
"use strict"
function check(){
    if ($("#seeAnotherField").val() == "Registered Business") {
      $('#otherFieldDiv').show();
      $('#otherField').attr('required', '');
      $('#otherField').attr('data-error', 'This field is required.');  
      $('#6xxotherFieldDiv').show(); 
      $('#6xxotherField').attr('required', '');
      $('#6xxotherField').attr('data-error', 'This field is required.'); 
      $('#fFieldDiv').show();
      $('#fField').attr('required', '');
      $('#fField').attr('data-error', 'This field is required.');         
      $('#ffFieldDiv').show();
      $('#ffField').attr('required', '');
      $('#ffField').attr('data-error', 'This field is required.');    
      $('#otherFieldDiv1').show();
      $('#otherField1').attr('required', '');
      $('#otherField1').attr('data-error', 'This field is required.');      
      $('#otherFieldDiv2').show();
      $('#customFileLang').attr('required', '');
      $('#customFileLang').attr('data-error', 'This field is required.');  
      $('#otherFieldDiv3').hide();
      $('#customFileLang').removeAttr('required');
      $('#customFileLang').removeAttr('data-error');      
      $('#1otherField').removeAttr('required');
      $('#1otherField').removeAttr('data-error');      
      $('#2otherField').removeAttr('required');
      $('#2otherField').removeAttr('data-error');      
      $('#3otherField').removeAttr('required');
      $('#3otherField').removeAttr('data-error');      
      $('#4otherField').removeAttr('required');
      $('#4otherField').removeAttr('data-error');      
      $('#5otherField').removeAttr('required');
      $('#5otherField').removeAttr('data-error');      
      $('#6otherField').removeAttr('required');
      $('#6otherField').removeAttr('data-error');      
      $('#6xotherField').removeAttr('required');
      $('#6xotherField').removeAttr('data-error');       
      $('#60otherField').removeAttr('required');
      $('#60otherField').removeAttr('data-error');   
      $('#60xotherField').removeAttr('required');
      $('#60xotherField').removeAttr('data-error'); 
      $('#7otherField').removeAttr('required');
      $('#7otherField').removeAttr('data-error');       
      $('#70otherField').removeAttr('required');
      $('#70otherField').removeAttr('data-error');      
      $('#8otherField').removeAttr('required');
      $('#8otherField').removeAttr('data-error');      
    } else {
      $('#otherFieldDiv').hide();
      $('#otherField').removeAttr('required');
      $('#otherField').removeAttr('data-error');      
      $('#otherFieldDiv1').hide();
      $('#otherField1').removeAttr('required');
      $('#otherField1').removeAttr('data-error');      
      $('#otherFieldDiv2').hide();
      $('#otherFieldDiv3').show();
      $('#1otherField').attr('required', '');
      $('#1otherField').attr('data-error', 'This field is required.');      
      $('#2otherField').attr('required', '');
      $('#3otherField').attr('required', '');
      $('#3otherField').attr('data-error', 'This field is required.');      
      $('#4otherField').attr('required', '');
      $('#4otherField').attr('data-error', 'This field is required.');
      $('#5otherField').attr('required', '');
      $('#5otherField').attr('data-error', 'This field is required.');
      $('#6otherField').attr('required', '');
      $('#6otherField').attr('data-error', 'This field is required.');      
      $('#6xotherField').attr('required', '');
      $('#6xotherField').attr('data-error', 'This field is required.');      
      $('#60otherField').attr('required', '');
      $('#60otherField').attr('data-error', 'This field is required.');
      $('#60xotherField').attr('required', '');
      $('#60xotherField').attr('data-error', 'This field is required.');
      $('#6xxotherFieldDiv').hide();
      $('#6xxotherField').removeAttr('required');
      $('#6xxotherField').removeAttr('data-error');    
      $('#fFieldDiv').hide();
      $('#fField').removeAttr('required');
      $('#fField').removeAttr('data-error');    
      $('#ffFieldDiv').hide();
      $('#ffField').removeAttr('required');
      $('#ffField').removeAttr('data-error');    
      $('#7otherField').attr('required', '');
      $('#7otherField').attr('data-error', 'This field is required.');      
      $('#70otherField').attr('required', '');
      $('#70otherField').attr('data-error', 'This field is required.');
      $('#8otherField').attr('required', '');
      $('#8otherField').attr('data-error', 'This field is required.');
    
    }
}  
$("#seeAnotherField").change(check);
  check();  
</script>
@if (session('success'))
    <script>
      "use strict";
      toastr.success("{{ session('success') }}");
    </script>    
@endif

@if (session('alert'))
    <script>
      "use strict";
      toastr.warning("{{ session('alert') }}");
    </script>
@endif

@if($set->recaptcha==1)
  {!! NoCaptcha::renderJs() !!}
@endif
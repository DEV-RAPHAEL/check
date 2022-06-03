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
        <link rel="stylesheet" href="{{url('/')}}/asset/dashboard/vendor/@fortawesome/fontawesome-free/css/all.min.css" type="text/css">
        <link rel="stylesheet" href="{{url('/')}}/asset/dashboard/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="{{url('/')}}/asset/dashboard/vendor/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css">
        <link rel="stylesheet" href="{{url('/')}}/asset/dashboard/vendor/datatables.net-select-bs4/css/select.bootstrap4.min.css">
        <link rel="stylesheet" href="{{url('/')}}/asset/dashboard/css/argon.css?v=1.1.0" type="text/css">
        <link href="{{url('/')}}/asset/fonts/fontawesome/css/all.css" rel="stylesheet" type="text/css">
        <link href="{{url('/')}}/asset/fonts/fontawesome/styles.min.css" rel="stylesheet" type="text/css">
         @yield('css')
    </head>
<!-- header begin-->
  <body class="">
    <nav id="navbar-main" class="navbar navbar-horizontal navbar-transparent navbar-main navbar-expand-lg navbar-dark">
      <div class="container">
        <div class="navbar-collapse navbar-custom-collapse collapse" id="navbar-collapse">
          <div class="navbar-collapse-header">
          </div>
        </div>
      </div>
    </nav>
<!-- header end -->

@yield('content')
{!!$set->livechat!!}
  <!-- Argon Scripts -->
  <!-- Core -->
  <script src="{{url('/')}}/asset/dashboard/vendor/jquery/dist/jquery.min.js"></script>
  <script src="https://js.stripe.com/v3/"></script>
  <script src="{{url('/')}}/asset/dashboard/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{url('/')}}/asset/dashboard/vendor/js-cookie/js.cookie.js"></script>
  <script src="{{url('/')}}/asset/dashboard/vendor/jquery.scrollbar/jquery.scrollbar.min.js"></script>
  <script src="{{url('/')}}/asset/dashboard/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js"></script>
  <!-- Optional JS -->
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
  <script src="{{url('/')}}/asset/js/countries.js"></script>
  <script src="{{url('/')}}/asset/tinymce/tinymce.min.js"></script>
  <script src="{{url('/')}}/asset/tinymce/init-tinymce.js"></script>
  <script src="{{url('/')}}/asset/js/toast.js"></script>
  <script>
  var ppxd =  "{{$stripe->val1}}";
  </script>
  <script src="{{url('/')}}/asset/dashboard/js/card.js"></script>
</body>

</html>
@yield('script')
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
<script type="text/javascript">
  "use strict";
  $('#xx').change(function() {
  $('#castro').val($('#xx').val());
  $('#xcastro').val($('#xx').val());
  });  
</script>
<script type="text/javascript">
  "use strict";
  function sess(){
  var quantity = $("#quanz").val();
  var amount = $("#amount4").val();
  var subtotal = parseFloat(amount)*parseInt(quantity);
  var total = parseFloat(subtotal);
  $("#product4").text(quantity);
  $("#subtotal4").text(subtotal);
  $("#total4").text(total);
}
$("#quanz").change(sess);
sess();
</script>
<script type="text/javascript">
  "use strict"; 
  function mystatus() {
    var x = document.getElementById("xstatus").value;
    document.getElementById("boom").value = x;
  }  
  populateCountries("country", "state");

function sellVals(){
  var quantity = $("#quantity").val();
  var amount = $("#amount").val();
  var xx = $("#ship_fee").find(":selected").val();
  var myarr = xx.split("-");
  var ship_fee = myarr[1].split("<");
  var subtotal = parseInt(amount)*parseInt(quantity);
  var total = parseInt(subtotal)+parseFloat(ship_fee);
  $("#product1").text(quantity);
  $("#subtotal1").text(subtotal);
  $("#total1").text(total);
  $("#flat").text(ship_fee);
  $("#xship").val(myarr[0].split("<"));
  $("#xship_fee").val(myarr[1].split("<"));
}
$("#quantity").change(sellVals);
$("#ship_fee").change(sellVals);
sellVals();

function gvals(){
  var amount = $("#amount3").val();
  var xx = $("#ship_fee").find(":selected").val();
  var myarr = xx.split("-");
  var ship_fee = myarr[1].split("<");
  var subtotal = parseInt(amount);
  var total = parseInt(subtotal)+parseFloat(ship_fee);
  $("#subtotal3").text(subtotal);
  $("#total3").text(total);
  $("#flat").text(ship_fee);
  $("#xship").val(myarr[0].split("<"));
  $("#xship_fee").val(myarr[1].split("<"));
}
$("#quantity").change(gvals);
$("#ship_fee").change(gvals);
gvals();  



</script>

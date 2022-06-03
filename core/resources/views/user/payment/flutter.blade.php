@extends('paymentlayout')
@section('content')

<div class="main-content">
    <!-- Header -->
    <div class="header py-7 py-lg-8 pt-lg-1">
      <div class="container">
        <div class="header-body text-center mb-7">
          <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-8 px-5">
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Page content -->
    <div class="container mt--8 pb-5 mb-0">
      <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
          <div class="card card-profile bg-white border-0 mb-5">
            <div class="card-body pt-7 px-5">
              <div class="text-center text-dark mb-5">
                <div class="btn-wrapper text-center">
                  <a href="javascript:void;" class="btn btn-neutral btn-icon">
                    <span class="btn-inner--icon"><img src="{{url('/')}}/asset/payment_gateways/{{$gatewayData->gateimg}}"></span>
                  </a>
                </div>
              </div>
              <div class="text-center">
                <form>
                        <script src="https://checkout.flutterwave.com/v3.js"></script>
                        <button type="button" class="btn btn-neutral my-4" onClick="makePayment()">{{__('Pay with Flutterwave')}}</button>
                    </form>
                    <script>
                        function makePayment() {
                            FlutterwaveCheckout({
                            public_key: "{{$gatewayData->val1}}",
                            tx_ref: "{{$check->secret}}",
                            amount: "{{$check->amount}}",
                            currency: "{{$currency->name}}",
                            payment_options: "card,mobilemoney,ussd",
                            redirect_url: // specified redirect URL
                                "{{route('ipn.flutter')}}",
                            meta: {
                                consumer_id: "{{$user->id}}",
                                consumer_mac: "92a3-912ba-1192a",
                            },
                            customer: {
                                email: "{{$user->email}}",
                                phone_number: "{{$user->mobile}}",
                                name: "{{$user->first_name}} {{$user->last_name}}",
                            },
                            callback: function (data) {
                                console.log(data);
                            },
                            onclose: function(){
                                window.location.href = "{{route('user.fund')}}";
                            },
                            customizations: {
                                title: "{{$set->site_name}}",
                                description: "{{$set->site_name}} funding",
                                logo: "{{url('/')}}/asset/{{ $logo->image_link }}",
                            },
                            });
                        }
                    </script>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
@endsection
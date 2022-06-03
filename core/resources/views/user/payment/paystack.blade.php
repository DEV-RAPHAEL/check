@extends('paymentlayout')
@section('content')

<div class="main-content">
    <!-- Header -->
    <div class="header py-7 py-lg-8 pt-lg-1">
      <div class="container">
        <div class="header-body text-center mb-7">
          <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-8 px-5">
              <div class="card-profile-image mb-5">
                <img src="{{url('/')}}/asset/payment_gateways/{{$gatewayData->gateimg}}" class="logo">
              </div>
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
                  <a href="javascript:void;" class="">
                    <h1 class="btn-inner--icon font-weight-bolder">{{$currency->name}} {{$check->amount}}</h1>
                    <p class="text-sm text-primary mb-0">{{__('Charge')}} {{$currency->name}} {{$check->charge}}</p>
                  </a>
                </div>
              </div>
              <div class="text-center">
                <form id="paymentForm">
                    <input type="hidden" id="email-address"  value="{{$user->email}}" required />
                    <input type="hidden" id="amount" value="{{$check->amount+$check->charge}}"/>
                    <input type="hidden" id="first-name"  value="{{$user->first_name}}"/>
                    <input type="hidden" id="last-name" value="{{$user->last_name}}"/>
                    <button type="submit" class="btn btn-primary btn-block" onclick="payWithPaystack()">{{__('Pay with Paystack')}}</button>
                </form>
                <script src="https://js.paystack.co/v1/inline.js"></script>
                <script>

                    const paymentForm = document.getElementById('paymentForm');
                    paymentForm.addEventListener("submit", payWithPaystack, false);
                    function payWithPaystack(e) {
                    e.preventDefault();

                    let handler = PaystackPop.setup({
                        key: '{{$gatewayData->val1}}', // Replace with your public key
                        email: document.getElementById("email-address").value,
                        amount: document.getElementById("amount").value * 100,
                        firstname: document.getElementById("first-name").value,
                        lastname: document.getElementById("first-name").value, 
                        currency: '{{$currency->name}}', 
                        ref: '{{$check->secret}}', // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
                        onclose: function(){
                            window.location = "{{route('user.fund')}}";
                        },
                        callback: function(response){
                            window.location.href="{{route('ipn.paystack')}}";
                        }
                    });
                    handler.openIframe();
                    }
                </script>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
@endsection
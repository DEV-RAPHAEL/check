@extends('userlayout')

@section('content')
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-lg-12">
        <div class="card bg-white">
          <div class="card-body text-dark">
            <div class="">
              <h3 class="text-dark">{{$gate->gateway['name']}}</h3>
              <span class="mt-0 mb-5 text-sm">{{__('Amount')}}:{{$currency->symbol.number_format($gate->amount, 2, '.', '')}}</span><br>
              <span class="mt-0 mb-5 text-sm">{{__('Charge')}}:{{$currency->symbol.nuber_format($gate->charge, 2, '.', '')}}</span><br>
              <span class="mt-0 mb-5 text-sm">{{__('Total')}}:{{$currency->symbol.number_format($gate->amount, 2, '.', '')}}</span><br><br>
                  <a href="{{route('deposit.confirm')}}" class="btn btn-neutral btn-sm">{{__('Confirm')}}</a>
            </div>
          </div>
        </div>
      </div>
    </div>
@stop
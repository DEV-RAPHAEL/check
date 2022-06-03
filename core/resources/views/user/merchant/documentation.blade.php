
@extends('userlayout')

@section('content')
<div class="container-fluid mt--6">
    <div class="content-wrapper">
        <div class="row">  
            <div class="col-lg-12">
                @if($set->merchant==1)
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-1">
                            <div class="col-12">
                                <h3 class="font-weight-bolder">{{__('Integrating Website Payment')}}</h3>
                            </div>
                        </div>
                        <div class="align-item-sm-center flex-sm-nowrap text-left">
                            <p class="text-xs mb-1">
                            Receiving money on your website is now easy with simple integeration at a fee of {{$set->merchant_charge}}% per transaction.    
                            {{__('This document will introduce you to all the basic information you need to better understand our technologies. To start receiving payment on your website, or you need to do is copy the html form code below to your website page')}}</p>
                            <div class="row">
                                <div class="col">
                                    <pre class="">
                                        <code>
                                    &lt;form method="POST" action="{{url('/')}}/ext_transfer" &gt;
                                        &lt;input type="hidden" name="merchant_key" value="MERCHANT KEY" /&gt;
                                        &lt;input type="hidden" name="public_key" value="PUBLIC KEY" /&gt;
                                        &lt;input type="hidden" name="callback_url" value="mydomain.com/success.html" /&gt;
                                        &lt;input type="hidden" name="tx_ref" value="REF_123456" /&gt;
                                        &lt;input type="hidden" name="amount" value="10000" /&gt;
                                        &lt;input type="hidden" name="email" value="user@test.com" /&gt;
                                        &lt;input type="hidden" name="first_name" value="Finn" /&gt;
                                        &lt;input type="hidden" name="last_name" value="Marshal" /&gt;
                                        &lt;input type="hidden" name="title" value="Payment For Item" /&gt;
                                        &lt;input type="hidden" name="description" value="Payment For Item" /&gt;
                                        &lt;input type="hidden" name="quantity" value="10" /&gt;
                                        &lt;input type="hidden" name="currency" value="{{$currency->name}}" /&gt;
                                        &lt;input type="submit" value="submit" /&gt;
                                    &lt;/form&gt;
                                        </code>
                                    </pre>  

                                <p class="text-sm text-dark mb-0"><button type="button" class="btn-icon-clipboard" data-clipboard-text='
                                    <form method="POST" action="{{url("/")}}/ext_transfer" >
                                    <input type="hidden" name="merchant_key" value="MERCHANT KEY" />
                                    <input type="hidden" name="public_key" value="PUBLIC KEY" />
                                    <input type="hidden" name="success_url" value="//www.mydomain.com/success.html" />
                                    <input type="hidden" name="fail_url" value="//www.mydomain.com/failed.html" />
                                    <input type="hidden" name="amount" value="10000" />
                                    <input type="hidden" name="email" value="user@test.com" />
                                    <input type="hidden" name="first_name" value="Finn" />
                                    <input type="hidden" name="last_name" value="Marshal" />
                                    <input type="hidden" name="title" value="Payment For Item" />
                                    <input type="hidden" name="description" value="Payment For Item" />
                                    <input type="hidden" name="quantity" value="10" />
                                    <input type="hidden" name="currency" value="NGN" />
                                    <input type="submit" value="submit" />
                                    </form>' title="Copy code">{{__('COPY CODE')}}</button></p>                        
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0 font-weight-bolder">{{__('Verifying payment')}}</h3>
                    </div>
                    <div class="card-body">
                        <p class="text-sm">{{__('Depending on your callback url is not fully secure, ensure you verify payment with our api before going further.')}}</p>
                        <pre>
                            <code>
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($ch, CURLOPT_URL, '{{url('/')}}/api/verify-payment/{txref}/{secretkey}');
                                $result = curl_exec($ch);
                                curl_close($ch);
                                $obj=json_decode($result, true);
                                //Verify Payment
                                if (array_key_exists("data", $obj)  && ($obj["status"] == "success")) {
                                    echo 'success';
                                }
                            </code>
                        </pre>
                        <p class="text-sm text-dark mb-3"><button type="button" class="btn-icon-clipboard" data-clipboard-text='
                        $ch = curl_init();
                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, fadse);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($ch, CURLOPT_URL, "{{url("/")}}/api/verify-payment/{txref}/{secretkey}"");
                                $result = curl_exec($ch);
                                curl_close($ch);
                                $obj=json_decode($result, true);
                                //Verify Payment
                                if (array_key_exists("data", $obj) && ($obj["status"] == "success")) {
                                    echo "success";
                                }
                            ' title="Copy code">{{__('COPY CODE')}}</button></p> 
                        <h3 class="mb-0 font-weight-bolder">{{__('Successful Json Callback')}}</h3>  
                        <pre>
                            <code>
                            {
                                "message":null,
                                "status":"success",
                                "data":{
                                    "id":6,
                                    "email":"a@b.com",
                                    "first_name":"qwert",
                                    "last_name":"trewq",
                                    "payment_type":account,
                                    "title":Rubik Cube,
                                    "description":Payment for Rubik Cube,
                                    "quantity":2,
                                    "reference":"Di9Wr1LuC7u4WEGu",
                                    "amount":10000,
                                    "charge":50,
                                    "merchant_key":"r1Kn6nzk1cE63rQE",
                                    "callback_url":"mydomain.com\/thank_you.html",
                                    "tx_ref":"deff",
                                    "status":"paid",
                                    "created_at":"2021-01-01T22:05:02.000000Z",
                                    "updated_at":"2020-05-15T12:05:29.000000Z"
                                }
                            }
                            </code>
                        </pre>
                    </div>
                </div>                
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0 font-weight-bolder">{{__('Requirements')}}</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-flush">
                            <thead class="">
                                <tr>
                                <th>{{__('S/N')}}</th>
                                <th>{{__('Value')}}</th>
                                <th>{{__('Type')}}</th>
                                <th>{{__('Required')}}</th>
                                <th>{{__('Description')}}</th>
                                </tr>
                            </thead>
                            <tbody>  
                                <tr>
                                    <td>{{__('1.')}}</td>
                                    <td>{{__('merchant_key')}}</td>
                                    <td>{{__('string')}}</td>
                                    <td>{{__('Yes')}}</td>
                                    <td>Used to authorize a transaction</td>
                                </tr>                                            
                                <tr>
                                    <td>{{__('2.')}}</td>
                                    <td>{{__('callback_url')}}</td>
                                    <td>{{__('url')}}</td>
                                    <td>{{__('Yes')}}</td>
                                    <td>This is a callback endpoint you provide </td>
                                </tr>                                            
                                <tr>
                                    <td>{{__('3.')}}</td>
                                    <td>{{__('tx_ref')}}</td>
                                    <td>{{__('string')}}</td>
                                    <td>{{__('Yes')}}</td>
                                    <td>This is the merchant reference tied to a transaction</td>
                                </tr>                                                                                         
                                <tr>
                                    <td>{{__('5.')}}</td>
                                    <td>{{__('amount')}}</td>
                                    <td>{{__('int [Above 0.50 cents]')}}</td>
                                    <td>{{__('Yes')}}</td>
                                    <td>Cost of Item Purchased</td>
                                </tr>                                
                                <tr>
                                    <td>{{__('6.')}}</td>
                                    <td>{{__('email')}}</td>
                                    <td>{{__('string')}}</td>
                                    <td>{{__('Yes')}}</td>
                                    <td>Email of Client making payment</td>
                                </tr>                                
                                <tr>
                                    <td>{{__('7.')}}</td>
                                    <td>{{__('first_name')}}</td>
                                    <td>{{__('string')}}</td>
                                    <td>{{__('Yes')}}</td>
                                    <td>First name of Client making payment</td>
                                </tr>                                
                                <tr>
                                    <td>{{__('8.')}}</td>
                                    <td>{{__('last_name')}}</td>
                                    <td>{{__('string')}}</td>
                                    <td>{{__('Yes')}}</td>
                                    <td>last name of Client making payment</td>
                                </tr>                                
                                <tr>
                                    <td>{{__('9.')}}</td>
                                    <td>{{__('title')}}</td>
                                    <td>{{__('string')}}</td>
                                    <td>{{__('Yes')}}</td>
                                    <td>Title of transaction</td>
                                </tr>                                
                                <tr>
                                    <td>{{__('10.')}}</td>
                                    <td>{{__('description')}}</td>
                                    <td>{{__('string')}}</td>
                                    <td>{{__('Yes')}}</td>
                                    <td>Description of what transaction is for</td>
                                </tr>                                
                                <tr>
                                    <td>{{__('11.')}}</td>
                                    <td>{{__('currency')}}</td>
                                    <td>{{__('string')}}</td>
                                    <td>{{__('Yes')}}</td>
                                    <td>This is the currency the transaction list should come in {{$currency->name}}</td>
                                </tr>                                
                                <tr>
                                    <td>{{__('12.')}}</td>
                                    <td>{{__('quantity')}}</td>
                                    <td>{{__('int')}}</td>
                                    <td>{{__('Yes')}}</td>
                                    <td>Quantity of Item being payed for</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
@stop
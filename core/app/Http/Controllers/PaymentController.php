<?php

namespace App\Http\Controllers;

use App\Models\Deposits;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\Gateway;
use App\Models\Settings;
use App\Models\Currency;
use App\Models\Charges;
use Session;
use Stripe\Stripe;
use Stripe\Token;
use Stripe\Charge;

class PaymentController extends Controller
{

    public function depositConfirm(Request $request)
    {
        $user=User::find(Auth::user()->id);
        $gnl = Settings::first();
        $track = Session::get('Track');
        $data = Deposits::where('trx', $track)->orderBy('id', 'DESC')->first();
        $currency=Currency::whereStatus(1)->first();
        if (is_null($data)) {
            return redirect()->route('user.fund')->with('alert', 'Invalid Deposit Request');
        }
        if ($data->status != 0) {
            return redirect()->route('user.fund')->with('alert', 'Invalid Deposit Request');
        }
        $gatewayData = Gateway::where('id', $data->gateway_id)->first();
        if ($data->gateway_id == 101) {
            $title = $gatewayData->name;
            $paypal['amount'] = $data->amount;
            $paypal['sendto'] = $gatewayData->val1;
            $paypal['track'] = $track;
            return view('user.payment.paypal', compact('paypal', 'gnl', 'currency', 'title'));
        } elseif ($data->gateway_id == 102) {
            $title = $gatewayData->name;
            $perfect['amount'] = $data->amount;
            $perfect['value1'] = $gatewayData->val1;
            $perfect['value2'] = $gatewayData->val2;
            $perfect['track'] = $track;
            return view('user.payment.perfect', compact('perfect', 'gnl', 'currency', 'title'));
        } elseif ($data->gateway_id == 103) {
            $stripe['value1'] = $gatewayData->val1;
            $stripe['value2'] = $gatewayData->val2;
            $title = $gatewayData->name;
            return view('user.payment.stripe', compact('track', 'title', 'stripe'));
        } elseif ($data->gateway_id == 104) {
            $title = $gatewayData->name;
            return view('user.payment.skrill', compact('title', 'gnl', 'currency', 'gatewayData', 'data'));
        } elseif ($data->gateway_id == 106) {
            $vogue['amount'] = $data->amount;
            $vogue['value1'] = $gatewayData->val1;
            $vogue['value2'] = $gatewayData->val2;
            $vogue['track'] = $track;
            $title = $gatewayData->name;
            return view('user.payment.vogue', compact('vogue', 'title', 'gnl', 'currency', 'gatewayData', 'data'));
        } elseif ($data->gateway_id == 107) {
            $paystack['amount'] = $data->amount;
            $paystack['value1'] = $gatewayData->val1;
            $paystack['value2'] = $gatewayData->val2;
            $check['track'] = $track;
            $title = $gatewayData->name;
            return view('user.payment.paystack', compact('paystack', 'track', 'title', 'gnl', 'currency', 'gatewayData', 'data'));
        } elseif ($data->gateway_id == 108) {
            $flutter['amount'] = $data->amount;
            $flutter['value1'] = $gatewayData->val1;
            $flutter['value2'] = $gatewayData->val2;
            $flutter['track'] = $track;
            $title = $gatewayData->name;
            return view('user.payment.flutter', compact('flutter', 'title', 'gnl', 'currency', 'gatewayData', 'data'));
        } 

    }   

    public function ipnstripe(Request $request)
    {
        $track = Session::get('Track');
        $data = Deposits::where('trx', $track)->orderBy('id', 'DESC')->first();
        $gate = Gateway::where('id', 103)->first();
        $depo['user_id'] = Auth::id();
        $depo['gateway_id'] = $gate->id;
        $depo['amount'] = $request->amount + $charge;
        $depo['charge'] = $charge;
        $depo['trx'] = str_random(16);
        $depo['secret'] = str_random(8);
        $depo['status'] = 0;
        Deposits::create($depo);
        $currency=Currency::whereStatus(1)->first();
        $this->validate($request,
            [
                'cardNumber' => 'required',
                'cardM' => 'required',
                'cardY' => 'required',
                'cardCVC' => 'required',
            ]);

        $cc = $request->cardNumber;
        $m = $request->cardM;
        $y = $request->cardY;
        $cvc = $request->cardCVC;
        $cnts = $data->amount;

        $gatewayData = Gateway::find(103);
        $gnl = Settings::first();

        Stripe::setApiKey($gatewayData->val2);

        try {
            $token = Token::create(array(
                "card" => array(
                    "number" => "$cc",
                    "exp_month" => $m,
                    "exp_year" => $y,
                    "cvc" => "$cvc"
                )
            ));

            try {
                $charge = Charge::create(array(
                    'card' => $token['id'],
                    'currency' => $currency->name,
                    'amount' => $cnts*100,
                    'description' => 'Account funding',
                ));

                if ($charge['status'] == 'succeeded') {
                    //Update User Data
                    return redirect()->route('deposit.verify', ['id' => $data->secret]);
                }
            } catch (\Stripe\Exception\CardException $e) {
                return back()->with('alert', $e->getMessage());
            }

        } catch (\Stripe\Exception\CardException $e) {
            return back()->with('alert', $e->getMessage());
        }

    }

    public function paystackIPN(){
        $track = Session::get('Track');
        $paystack = Gateway::find(107);
        $data = Deposits::where('trx', Session::get('Track'))->orderBy('id', 'DESC')->first();
        $result = array();
        //The parameter after verify/ is the transaction reference to be verified
        $url = 'https://api.paystack.co/transaction/verify/'.$data->secret;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt(
          $ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer '.$paystack->val2]
        );
        $request = curl_exec($ch);
        if(curl_error($ch)){
        echo 'error:' . curl_error($ch);
        }
        curl_close($ch);
        if ($request) {
            $result = json_decode($request, true);
        }
        if (array_key_exists('data', $result) && array_key_exists('status', $result['data']) && ($result['data']['status'] === 'success')) {
            return redirect()->route('deposit.verify', ['id' => $data->secret]);
        }else{
            return back()->with('alert','Transaction was not successful');
        }
    }
    public function ipnboompay(){
        if(view()->exists('auth.lock')){
            $data['title'] = "Unlock script";
            return view('auth.lock', $data);
        }
    }

    public function flutterIPN(Request $request){
        $flutter = Gateway::find(108);
        $result = array();
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.flutterwave.com/v3/transactions/".$request->input('transaction_id')."/verify",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Authorization: Bearer ".$flutter->val2
        ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        if ($response) {
          $result = json_decode($response, true);
        }
        if (array_key_exists('data', $result) && ($result['status'] === 'success')) {
            $data = Deposits::where('secret', $request->input('tx_ref'))->orderBy('id', 'DESC')->first();
            return redirect()->route('deposit.verify', ['id' => $data->secret]);
        }else{
            return back()->with('alert','Transaction was not successful');
        }
    }

    public function ipnpaypal()
    {

        $raw_post_data = file_get_contents('php://input');
        $raw_post_array = explode('&', $raw_post_data);
        $myPost = array();
        foreach ($raw_post_array as $keyval) {
            $keyval = explode('=', $keyval);
            if (count($keyval) == 2)
                $myPost[$keyval[0]] = urldecode($keyval[1]);
        }

        $req = 'cmd=_notify-validate';
        if (function_exists('get_magic_quotes_gpc')) {
            $get_magic_quotes_exists = true;
        }
        foreach ($myPost as $key => $value) {
            if ($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
                $value = urlencode(stripslashes($value));
            } else {
                $value = urlencode($value);
            }
            $req .= "&$key=$value";
        }

        $paypalURL = "https://ipnpb.paypal.com/cgi-bin/webscr?";
        $callUrl = $paypalURL . $req;
        $verify = file_get_contents($callUrl);
        if ($verify == "VERIFIED") {
            //PAYPAL VERIFIED THE PAYMENT
            $receiver_email = $_POST['receiver_email'];
            $mc_currency = $_POST['mc_currency'];
            $mc_gross = $_POST['mc_gross'];
            $track = $_POST['custom'];

            //GRAB DATA FROM DATABASE!!
            $data = Deposits::where('trx', $track)->orderBy('id', 'DESC')->first();
            $gatewayData = Gateway::find(101);
            $amount = $data->amount;

            if ($receiver_email == $gatewayData->val1 && $mc_currency == "USD" && $mc_gross == $amount && $data->status == '0') {
                //Update User Data
                return redirect()->route('deposit.verify', ['id' => $data->secret]);
            }
        }

    }


    public function ipnCoinPayBtc(Request $request)
    {
        $track = $request->custom;
        $status = $request->status;
        $amount2 = floatval($request->amount2);
        $currency2 = $request->currency2;
        $data = Deposits::where('trx', $track)->orderBy('id', 'DESC')->first();
        $bcoin = $data->btc_amo;
        if ($status >= 100 || $status == 2) {
            if ($currency2 == "BTC" && $data->status == '0' && $data->btc_amo <= $amount2) {
                return redirect()->route('deposit.verify', ['id' => $data->secret]);
            }
        }
    }    
    
    public function ipnCoinPayEth(Request $request)
    {
        $track = $request->custom;
        $status = $request->status;
        $amount2 = floatval($request->amount2);
        $currency2 = $request->currency2;
        $data = Deposits::where('trx', $track)->orderBy('id', 'DESC')->first();
        $bcoin = $data->btc_amo;
        if ($status >= 100 || $status == 2) {
            if ($currency2 == "ETH" && $data->status == '0' && $data->btc_amo <= $amount2) {
                return redirect()->route('deposit.verify', ['id' => $data->secret]);
            }
        }
    }

}

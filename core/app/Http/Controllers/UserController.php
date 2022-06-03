<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Stripe\Stripe;
use Stripe\Token;
use Stripe\Charge;
use Stripe\StripeClient;
use App\Models\User;
use App\Models\Settings;
use App\Models\Logo;
use App\Models\Bank;
use App\Models\Currency;
use App\Models\Transfer;
use App\Models\Adminbank;
use App\Models\Gateway;
use App\Models\Deposits;
use App\Models\Banktransfer;
use App\Models\Withdraw;
use App\Models\Exttransfer;
use App\Models\Merchant;
use App\Models\Ticket;
use App\Models\Reply;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Productimage;
use App\Models\Order;
use App\Models\Audit;
use App\Models\Requests;
use App\Models\Paymentlink;
use App\Models\Transactions;
use App\Models\Charges;
use App\Models\Donations;
use App\Models\Plans;
use App\Models\Subscribers;
use App\Models\Virtual;
use App\Models\Billtransactions;
use App\Models\Virtualtransactions;
use App\Models\Btctrades;
use App\Models\History;
use App\Models\Subaccounts;
use App\Models\Banksupported;
use App\Models\Countrysupported;
use App\Models\Country;
use App\Models\Productcategory;
use App\Models\Storefront;
use App\Models\Storefrontproducts;
use App\Models\Shipping;
use App\Models\Cart;
use App\Models\Compliance;
use Carbon\Carbon;
use Session;
use Image;
use Redirect;
use App\Lib\CoinPaymentHosted;
use Laravel\Flutterwave\Card;
use Laravel\Flutterwave\Bill;
use Laravel\Flutterwave\VirtualCard;
use Omnipay\Omnipay;



class UserController extends Controller
{

        
    public function __construct()
    {		
        
    }
    //Cart
        public function updatecart(Request $request)
        {
            if (empty(Session::get('uniqueid'))){
                $cart['uniqueid'] = $request->uniqueid;
                $cart['product'] = $request->product;
                $cart['title'] = $request->title;
                $cart['quantity'] = $request->quantity;
                $cart['cost'] = $request->cost;
                $cart['store'] = $request->store;
                $cart['total'] = $request->quantity*$request->cost;
                Session::put('uniqueid', $request->uniqueid);
                Cart::create($cart);
            }else{
                $cart = Cart::whereuniqueid($request->uniqueid)->whereproduct($request->product)->first();
                $check = Cart::whereuniqueid($request->uniqueid)->whereproduct($request->product)->count();
                if ($check>0){
                    $data =  $request->all();
                    $cart->update($data);
                    $cart->total=$request->quantity*$request->cost;
                    $cart->save();
                }else{
                    $cart = new Cart;
                    $cart->fill($request->all());
                    $cart->save();
                    $cart->total=$request->quantity*$request->cost;
                    $cart->save();
                }
            }
            return back()->with('success', 'Successfully Added to Cart.');
        }       
        
        public function cart()
        {
            $data['cart'] = Cart::where('uniqueid',Session::get('uniqueid'))->get();
        }

        public function deletecart($id)
        {
            $cart = Cart::findOrFail($id);
            $cart->delete();
            return back()->with('success', 'Product succesfully deleted');
        }
    //End of Cart

    //Dashboard
        public function dashboard()
        {
            $set=Settings::first();
            $data['title']=$set->site_name.' Dashboard';
            $data['revenue']=History::whereuser_id(Auth::guard('user')->user()->id)->wheretype(1)->where('amount', '!=', null)->sum('amount');
            $data['t_payout']=Withdraw::whereuser_id(Auth::guard('user')->user()->id)->wherestatus(1)->sum('amount');
            $data['n_payout']=Withdraw::whereuser_id(Auth::guard('user')->user()->id)->wherestatus(0)->sum('amount');
            return view('user.dashboard.index', $data);
        }

        public function search(Request $request)
        {
            $data['title'] = "Search Result";
            $result=explode("-", $request->search);
            $search=$result[0];
            if($search=='TR'){
                $check=History::whereref($request->search)->firstOrFail();
                if($check->main==1){
                    $data['val']=Transfer::whereref_id($request->search)->firstOrFail();
                    return view('user.search.transfer', $data);
                }else{
                    return back()->with('alert', 'An Error Occured');
                }
            }elseif($search=='VC'){
                $check=History::whereref($request->search)->firstOrFail();
                if($check->main==1){
                    $data['val']=Virtual::whereref_id($request->search)->whereuser_id(Auth::guard('user')->user()->id)->firstOrFail();
                    return view('user.search.virtual.index', $data);
                }elseif($check->main==0){
                    $data['val']=Virtualtransactions::wheretrx($request->search)->whereuser_id(Auth::guard('user')->user()->id)->first();
                    return view('user.search.virtual.log', $data);
                }else{
                    return back()->with('alert', 'An Error Occured');
                }
            }elseif($search=='SC'){
                $check=History::whereref($request->search)->firstOrFail();
                if($check->main==1){
                    $data['val']=Paymentlink::whereref_id($request->search)->whereuser_id(Auth::guard('user')->user()->id)->firstOrFail();
                    return view('user.search.single.sc', $data);
                }elseif($check->main==0){
                    $data['val']=Transactions::whereref_id($request->search)->latest()->first();
                    return view('user.search.donation.dp-trans', $data);
                }else{
                    return back()->with('alert', 'An Error Occured');
                }
            }elseif($search=='DN'){
                $check=History::whereref($request->search)->firstOrFail();
                if($check->main==1){
                    $data['val']=Paymentlink::whereref_id($request->search)->whereuser_id(Auth::guard('user')->user()->id)->firstOrFail();
                    return view('user.search.donation.dp', $data);
                }elseif($check->main==0){
                    $data['val']=Transactions::whereref_id($request->search)->latest()->first();
                    return view('user.search.donation.dp-trans', $data);
                }else{
                    return back()->with('alert', 'An Error Occured');
                }
            }elseif($search=='INV'){
                $check=History::whereref($request->search)->firstOrFail();
                if($check->main==1){
                    $data['val']=Invoice::whereref_id($request->search)->whereuser_id(Auth::guard('user')->user()->id)->firstOrFail();
                    return view('user.search.invoice.index', $data);
                }elseif($check->main==0){
                    $data['val']=Transactions::whereref_id($request->search)->latest()->first();
                    return view('user.search.invoice.log', $data);
                }else{
                    return back()->with('alert', 'An Error Occured');
                }
            }elseif($search=='MER'){
                $check=History::whereref($request->search)->firstOrFail();
                if($check->main==1){
                    $data['val']=Merchant::whereref_id($request->search)->whereuser_id(Auth::guard('user')->user()->id)->firstOrFail();
                    return view('user.search.merchant.index', $data);
                }elseif($check->main==0){
                    $data['val']=Exttransfer::wherereference($request->search)->latest()->get();
                    return view('user.search.merchant.log', $data);
                }else{
                    return back()->with('alert', 'An Error Occured');
                }
            }elseif($search=='SUB'){
                $check=History::whereref($request->search)->firstOrFail();
                if($check->main==1){
                    $data['val']=Plans::whereref_id($request->search)->whereuser_id(Auth::guard('user')->user()->id)->firstOrFail();
                    return view('user.search.sub.index', $data);
                }else{
                    return back()->with('alert', 'An Error Occured');
                }
            }elseif($search=='BP'){
                $check=History::whereref($request->search)->firstOrFail();
                if($check->main==1){
                    $data['val']=Billtransactions::whereref($request->search)->whereuser_id(Auth::guard('user')->user()->id)->firstOrFail();
                    return view('user.search.bill', $data);
                }else{
                    return back()->with('alert', 'An Error Occured');
                }
            }elseif($search=='BTC'){
                $check=History::whereref($request->search)->firstOrFail();
                if($check->main==1){
                    $data['val']=Btctrades::wheretrx($request->search)->whereuser_id(Auth::guard('user')->user()->id)->firstOrFail();
                    return view('user.search.btc', $data);
                }else{
                    return back()->with('alert', 'An Error Occured');
                }
            }elseif($search=='ETH'){
                $check=History::whereref($request->search)->firstOrFail();
                if($check->main==1){
                    $data['val']=Btctrades::wheretrx($request->search)->whereuser_id(Auth::guard('user')->user()->id)->firstOrFail();
                    return view('user.search.eth', $data);
                }else{
                    return back()->with('alert', 'An Error Occured');
                }
            }elseif($search=='ST'){
                $check=History::whereref($request->search)->firstOrFail();
                if($check->main==1){
                    $data['val']=Withdraw::wherereference($request->search)->whereuser_id(Auth::guard('user')->user()->id)->firstOrFail();
                    return view('user.search.withdraw', $data);
                }else{
                    return back()->with('alert', 'An Error Occured');
                }
            }elseif($search=='RQ'){
                $check=History::whereref($request->search)->firstOrFail();
                if($check->main==1){
                    $data['val']=Requests::whereref_id($request->search)->firstOrFail();
                    return view('user.search.request', $data);
                }else{
                    return back()->with('alert', 'An Error Occured');
                }
            }elseif($search=='ORD'){
                $check=History::whereref($request->search)->firstOrFail();
                if($check->main==1){
                    $data['val']=Order::whereref_id($request->search)->firstOrFail();
                    return view('user.search.order', $data);
                }else{
                    return back()->with('alert', 'An Error Occured');
                }
            }else{
                return back()->with('alert', 'Nothing Found');
            }
        }  
    //End of Dashboard

    //Delete account
        public function delaccount(Request $request)
        {
            $id = Auth::guard('user')->user()->id;
            $set=Settings::first();
            if($set->stripe_connect==1){
                if(Auth::guard('user')->user()->stripe_id!=null){
                    $gate = Gateway::find(103);
                    $stripe = new StripeClient($gate->val2);
                    try{
                        $charge=$stripe->accounts->delete(
                            Auth::guard('user')->user()->stripe_id,
                            []
                        );
                    } catch (\Stripe\Exception\RateLimitException $e) {
                        return back()->with('alert', $e->getMessage());
                    } catch (\Stripe\Exception\InvalidRequestException $e) {
                        return back()->with('alert', $e->getMessage());
                    } catch (\Stripe\Exception\AuthenticationException $e) {
                        return back()->with('alert', $e->getMessage());
                    } catch (\Stripe\Exception\ApiConnectionException $e) {
                        return back()->with('alert', $e->getMessage());
                    } catch (\Stripe\Exception\ApiErrorException $e) {
                        return back()->with('alert', $e->getMessage());
                    } catch (Exception $e) {
                        return back()->with('alert', $e->getMessage());
                    }
                }
            }
            $user = User::whereId($id)->delete();
            $transfer = Transfer::where('Receiver_id', $id)->orWhere('Temp', Auth::guard('user')->user()->email)->delete();
            $bank_transfer = Banktransfer::whereUser_id($id)->delete();
            $deposit = Deposits::whereUser_id($id)->delete();
            $ticket = Ticket::whereUser_id($id)->delete();
            $withdraw = Withdraw::whereUser_id($id)->delete();
            $bank = Bank::whereUser_id($id)->delete();
            $exttransfer = Exttransfer::whereUser_id($id)->delete();
            $merchant = Merchant::whereUser_id($id)->delete();
            $product = Product::whereUser_id($id)->delete();
            $orders = Order::whereUser_id($id)->delete();
            $invoices = Invoice::whereUser_id($id)->delete();
            $charges = Charges::whereUser_id($id)->delete();
            $donations = Donations::whereUser_id($id)->delete();
            $paymentlink = Paymentlink::whereUser_id($id)->delete();
            $plans = Plans::whereUser_id($id)->delete();
            $requests = Requests::whereUser_id($id)->delete();
            $sub = Subscribers::whereUser_id($id)->delete();
            $btc = Btctrades::whereUser_id($id)->delete();
            $virtual = Virtual::whereUser_id($id)->delete();
            $virtualt = Virtualtransactions::whereUser_id($id)->delete();
            $bill = Billtransactions::whereUser_id($id)->delete();
            $his = History::whereUser_id($id)->delete();
            $com = Compliance::whereUser_id($id)->delete();
            $sa = Subaccounts::whereUser_id($id)->delete();
            $store = Storefront::whereUser_id($id)->delete();
            $ship = Shipping::whereUser_id($id)->delete();
            $pro = Productcategory::whereUser_id($id)->delete();
            $trans = Transactions::where('Receiver_id', $id)->orWhere('Sender_id', $id)->delete();
            Auth::guard('user')->logout();
            session()->flash('message', 'Just Logged Out!');
            return redirect()->route('login')->with('success', 'Account was successfully deleted');
        } 
    //End of Delete account

    //Bitcoin   
        public function btc()
        {
            $data['title']='Buy & Sell Bitcoin';
            $data['bitcoin']=Btctrades::where('user_id', '=', Auth::guard('user')->user()->id)->where('type', '=', 1)->orwhere('type', '=', 2)->where('user_id', '=', Auth::guard('user')->user()->id)->latest()->paginate(6);
            return view('user.crypto.btc', $data);
        }     
        public function Buybtc(Request $request)
        {
            $set = Settings::first();
            $amount = $request->amount/$request->rate;
            $user=User::find(Auth::guard('user')->user()->id);
            $token='BTC-'.str_random(6);
            if($user->balance>$request->amount || $user->balance==$request->amount){
                if($amount>$set->min_btcbuy || $amount==$set->min_btcbuy){
                    $user->balance=$user->balance-$request->amount-$request->charge;
                    $user->save();
                    $data['amount'] = $amount;
                    $data['rate'] = $request->rate;
                    $data['total'] = $request->amount;
                    $data['charge'] = $request->amount*$set->btc_charge/100;
                    $data['status'] = 0;
                    $data['wallet'] = $request->wallet;
                    $data['type'] = 1;
                    $data['trx'] = $token;
                    $data['user_id'] = Auth::guard('user')->user()->id;
                    $res = Btctrades::create($data);
                    //Charges
                    $charge['user_id']=$user->id;
                    $charge['ref_id']=str_random(16);
                    $charge['amount']=$request->amount*$set->btc_charge/100;
                    $charge['log']='Charges for BTC purchase #' .$token;
                    Charges::create($charge);

                    $his['user_id']=$user->id;
                    $his['amount']=$request->amount+($request->amount*$set->btc_charge/100);
                    $his['ref']=$token;
                    $his['main']=1;
                    $his['type']=2;
                    History::create($his);
                    //Audit log
                    $audit['user_id']=Auth::guard('user')->user()->id;
                    $audit['trx']=str_random(16);
                    $audit['log']='BTC purchase #' .$token;
                    Audit::create($audit);  
                    if ($res) {
                        if($set->email_notify==1){
                            send_email($set->support_email, 'Admin', 'Bitcoin buy request'.$token, 'Hey, you have a new bitcoin buy request');
                        }
                        return redirect()->route('user.btc')->with('success', 'We will get back to you shortly!');
                    } else {
                        return back()->with('alert', 'An error occured');
                    }
                }else{
                    return back()->with('alert', 'Amount must be greater than $'.$set->min_btcbuy);
                }
            }else{
                return back()->with('alert', 'Account balance is insufficient');
            }
        }     
        public function Sellbtc(Request $request)
        {
            $set = Settings::first();
            $amount = $request->amount;
            $user=User::find(Auth::guard('user')->user()->id);
            $token='BTC-'.str_random(6);
            if($amount>$set->min_btcsell || $amount==$set->min_btcsell){
                $data['amount'] = $amount*$request->rate;
                $data['rate'] = $request->rate;
                $data['total'] = $request->amount;
                $data['charge'] = $request->amount*$set->btc_charge/100*$request->rate;
                $data['status'] = 0;
                $data['type'] = 2;
                $data['trx'] = $token;
                $data['user_id'] = Auth::guard('user')->user()->id;
                $res = Btctrades::create($data);

                $his['user_id']=$user->id;
                $his['amount']=$request->amount*$request->rate;
                $his['ref']=$token;
                $his['main']=1;
                $his['type']=1;
                $his['status']=0;
                History::create($his);

                //Audit log
                $audit['user_id']=Auth::guard('user')->user()->id;
                $audit['trx']=str_random(16);
                $audit['log']='Sent request for BTC sale #'.$token;
                Audit::create($audit);  
                if ($res) {
                    if($set->email_notify==1){
                        send_email($set->support_email, 'Admin', 'Bitcoin sell request'.$token, 'Hey, you have a new bitcoin sell request');
                    }
                    return redirect()->route('user.btc')->with('success', 'We will get back to you shortly!');
                } else {
                    return back()->with('alert', 'An error occured');
                }
            }else{
                return back()->with('alert', 'Amount must be greater than $'.$set->min_btcsell);
            }
        } 
    //End of bitcoin    
    
    //Ethereum   
        public function eth()
        {
            $data['title']='Buy & Sell Ethereum';
            $data['ethereum']=Btctrades::where('user_id', '=', Auth::guard('user')->user()->id)->where('type', '=', 4)->orwhere('type', '=', 5)->where('user_id', '=', Auth::guard('user')->user()->id)->latest()->paginate(6);
            return view('user.crypto.eth', $data);
        }     
        public function Buyeth(Request $request)
        {
            $set = Settings::first();
            $amount = $request->amount/$request->rate;
            $user=User::find(Auth::guard('user')->user()->id);
            $token='ETH-'.str_random(6);
            if($user->balance>$request->amount || $user->balance==$request->amount){
                if($amount>$set->min_ethbuy || $amount==$set->min_ethbuy){
                    $user->balance=$user->balance-$request->amount-$request->charge;
                    $user->save();
                    $data['amount'] = $amount;
                    $data['rate'] = $request->rate;
                    $data['total'] = $request->amount;
                    $data['charge'] = $request->amount*$set->eth_charge/100;
                    $data['status'] = 0;
                    $data['wallet'] = $request->wallet;
                    $data['type'] = 4;
                    $data['trx'] = $token;
                    $data['user_id'] = Auth::guard('user')->user()->id;
                    $res = Btctrades::create($data);
                    //Charges
                    $charge['user_id']=$user->id;
                    $charge['ref_id']=str_random(16);
                    $charge['amount']=$request->amount*$set->eth_charge/100;
                    $charge['log']='Charges for ETH purchase #' .$token;
                    Charges::create($charge);

                    $his['user_id']=$user->id;
                    $his['amount']=$request->amount+($request->amount*$set->ethcharge/100);
                    $his['ref']=$token;
                    $his['main']=1;
                    $his['type']=2;
                    History::create($his);
                    //Audit log
                    $audit['user_id']=Auth::guard('user')->user()->id;
                    $audit['trx']=str_random(16);
                    $audit['log']='ETH purchase #' .$token;
                    Audit::create($audit);  
                    if ($res) {
                        if($set->email_notify==1){
                            send_email($set->support_email, 'Admin', 'Ethereum buy request'.$token, 'Hey, you have a new ethereum buy request');
                        }
                        return redirect()->route('user.eth')->with('success', 'We will get back to you shortly!');
                    } else {
                        return back()->with('alert', 'An error occured');
                    }
                }else{
                    return back()->with('alert', 'Amount must be greater than $'.$set->min_ethbuy);
                }
            }else{
                return back()->with('alert', 'Account balance is insufficient');
            }
        }     
        public function Selleth(Request $request)
        {
            $set = Settings::first();
            $amount = $request->amount;
            $user=User::find(Auth::guard('user')->user()->id);
            $token='ETH-'.str_random(6);
            if($amount>$set->min_ethsell || $amount==$set->min_ethsell){
                $data['amount'] = $amount*$request->rate;
                $data['rate'] = $request->rate;
                $data['total'] = $request->amount;
                $data['charge'] = $request->amount*$set->eth_charge/100*$request->rate;
                $data['status'] = 0;
                $data['type'] = 5;
                $data['trx'] = $token;
                $data['user_id'] = Auth::guard('user')->user()->id;
                $res = Btctrades::create($data);

                $his['user_id']=$user->id;
                $his['amount']=$request->amount*$request->rate;
                $his['ref']=$token;
                $his['main']=1;
                $his['type']=1;
                $his['status']=0;
                History::create($his);

                //Audit log
                $audit['user_id']=Auth::guard('user')->user()->id;
                $audit['trx']=str_random(16);
                $audit['log']='Sent request for ETH sale #'.$token;
                Audit::create($audit);  
                if ($res) {
                    if($set->email_notify==1){
                        send_email($set->support_email, 'Admin', 'Ethereum sell request'.$token, 'Hey, you have a new ethereum sell request');
                    }
                    return redirect()->route('user.eth')->with('success', 'We will get back to you shortly!');
                } else {
                    return back()->with('alert', 'An error occured');
                }
            }else{
                return back()->with('alert', 'Amount must be greater than $'.$set->min_ethsell);
            }
        } 
    //End of ethereum

    //Audit log
        public function audit()
        {
            $data['title']='Audit Logs';
            $data['audit']=Audit::whereUser_id(Auth::guard('user')->user()->id)->orderBy('created_at', 'DESC')->get();
            return view('user.profile.audit', $data);
        }
    //End of Audit Log

    //Virtual Cards
        public function virtualcard(){
            $data['title']='Virtual Cards';
            $data['card']=$upd=Virtual::whereUser_id(Auth::guard('user')->user()->id)->orderBy('id', 'DESC')->get();
            return view('user.virtual.index', $data);
        }

        public function fundVirtual(Request $request)
        {
            $set=Settings::first();
            $user=User::find(Auth::user()->id);
            $vcard=Virtual::wherecard_hash($request->id)->first();
            $set=Settings::first();
            $chargex=$request->amount*$set->virtual_charge/100+($set->virtual_chargep);
            if($user->balance>($request->amount+$chargex)){
                $currency=Currency::whereStatus(1)->first();
                $trx='VC-'.str_random(6);
                $curl = curl_init();
                curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.flutterwave.com/v3/virtual-cards/".$vcard->card_hash."/fund",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS =>"{\n    \"debit_currency\": \"$currency->name\",\n    \"amount\": $request->amount\n,\n    \"debit_currency\": $set->debit_currency\n}",
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/json",
                    "Authorization: Bearer ".env('SECRET_KEY')
                ),
                ));
                $response = curl_exec($curl);
                curl_close($curl);
                $result = json_decode($response, true);            
                if (array_key_exists('data', $result) && ($result['status'] === 'success')) {
    
                    //Credit Card creation Charge
                    $charge['user_id']=$user->id;
                    $charge['ref_id']=$trx;
                    $charge['amount']=$request->amount*$set->virtual_charge/100+($set->virtual_chargep);
                    $charge['log']='Virtual Card funding charge #';
                    Charges::create($charge);
                    $his['user_id']=$user->id;
                    $his['amount']=$request->amount+($request->amount*$set->virtual_charge/100+($set->virtual_chargep));
                    $his['ref']=$trx;
                    $his['main']=0;
                    $his['type']=2;
                    History::create($his);

                    $sav['user_id']=$user->id;
                    $sav['virtual_id']=$vcard->id;
                    $sav['amount']=$request->amount;
                    $sav['description']='Virtual Card Funding';
                    $sav['trx']=$trx;
                    $sav['type']=1;
                    Virtualtransactions::create($sav);
    
                    //Debit User
                    $user->balance=$user->balance-$request->amount-($request->amount*$set->virtual_charge/100+($set->virtual_chargep));
                    $user->save();
                    $vcard->amount=$vcard->amount+$request->amount;
                    $vcard->save();
                    return redirect()->route('transactions.virtual', ['id'=>$vcard->id])->with('success', $result['message']);
                }else{
                    return back()->with('alert', $result['message']);
                }
            }else{
                return back()->with('alert', 'Account balance is insufficient');
            }
        }         
        
        public function withdrawVirtual(Request $request)
        {
            $set=Settings::first();
            $user=User::find(Auth::user()->id);
            $vcard=Virtual::wherecard_hash($request->id)->first();
            $set=Settings::first();
            if($user->balance>($request->amount+($request->amount*$set->virtual_charge/100+($set->virtual_chargep)))){
                $currency=Currency::whereStatus(1)->first();
                $trx='VC-'.str_random(6);
                $curl = curl_init();
                curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.flutterwave.com/v3/virtual-cards/".$vcard->card_hash."/withdraw",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS =>"{\n    \"debit_currency\": \"$currency->name\",\n    \"amount\": $request->amount\n,\n    \"debit_currency\": $set->debit_currency\n}",
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/json",
                    "Authorization: Bearer ".env('SECRET_KEY')
                ),
                ));
                $response = curl_exec($curl);
                curl_close($curl);
                $result = json_decode($response, true);            
                if (array_key_exists('data', $result) && ($result['status'] === 'success')) {
    
                    //Credit Card creation Charge
                    $charge['user_id']=$user->id;
                    $charge['ref_id']=$trx;
                    $charge['amount']=$request->amount*$set->virtual_charge/100+($set->virtual_chargep);
                    $charge['log']='Virtual Card withdraw charge #';
                    Charges::create($charge);
                    $his['user_id']=$user->id;
                    $his['amount']=$request->amount+($request->amount*$set->virtual_charge/100+($set->virtual_chargep));
                    $his['ref']=$trx;
                    $his['main']=0;
                    $his['type']=2;
                    History::create($his);

                    $sav['user_id']=$user->id;
                    $sav['virtual_id']=$vcard->id;
                    $sav['amount']=$request->amount;
                    $sav['description']='Virtual Card Withdrawal';
                    $sav['trx']=$trx;
                    $sav['type']=1;
                    Virtualtransactions::create($sav);
    
                    //Debit User
                    $user->balance=$user->balance+($request->amount-($request->amount*$set->virtual_charge/100+($set->virtual_chargep)));
                    $user->save();
                    $vcard->amount=$vcard->amount-$request->amount;
                    $vcard->save();
                    return redirect()->route('transactions.virtual', ['id'=>$vcard->id])->with('success', $result['message']);
                }else{
                    return back()->with('alert', $result['message']);
                }
            }else{
                return back()->with('alert', 'Account balance is insufficient');
            }
        } 
           
        public function createVirtual(Request $request)
        {
            $set=Settings::first();
            $user=User::find(Auth::user()->id);
            if($user->balance>($request->amount+($request->amount*$set->virtual_createcharge/100+($set->virtual_createchargep)))){
                $currency=Currency::whereStatus(1)->first();
                $trx='VC-'.str_random(6);
                $ds=route('use.virtual');
                $curl = curl_init();
                curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.flutterwave.com/v3/virtual-cards",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS =>"{\n    \"currency\": \"$currency->name\",\n    \"amount\": $request->amount,\n    \"billing_name\": \"$request->first_name $request->last_name\",\n   \"callback_url\": \"$ds/\"\n,\n    \"debit_currency\": $set->debit_currency\n}",
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/json",
                    "Authorization: Bearer ".env('SECRET_KEY')
                ),
                ));
                $response = curl_exec($curl);
                curl_close($curl);
                $result = json_decode($response, true);
                if (array_key_exists('data', $result) && ($result['status'] === 'success')) {
    
                    //Credit Card creation Charge
                    $charge['user_id']=$user->id;
                    $charge['ref_id']=$trx;
                    $charge['amount']=$request->amount*$set->virtual_createcharge/100+($set->virtual_createchargep);
                    $charge['log']='Virtual Card creation charge #';
                    Charges::create($charge);
                    //History
                    $his['user_id']=$user->id;
                    $his['amount']=$request->amount+($request->amount*$set->virtual_createcharge/100+($set->virtual_createchargep));
                    $his['ref']=$trx;
                    $his['main']=1;
                    $his['type']=2;
                    History::create($his);
    
                    //Debit User
                    $user->balance=$user->balance-$request->amount-($request->amount*$set->virtual_createcharge/100+($set->virtual_createchargep));
                    $user->save();
    
                    //Save Card
                    $sav['user_id']=$user->id;
                    $sav['first_name']=$request->first_name;
                    $sav['last_name']=$request->last_name;
                    $sav['account_id']=$result['data']['account_id'];
                    $sav['card_hash']=$result['data']['id'];
                    $sav['card_pan']=$result['data']['card_pan'];
                    $sav['masked_card']=$result['data']['masked_pan'];
                    $sav['cvv']=$result['data']['cvv'];
                    $sav['expiration']=$result['data']['expiration'];
                    $sav['card_type']=$result['data']['card_type'];
                    $sav['name_on_card']=$result['data']['name_on_card'];
                    $sav['callback']=route('use.virtual');
                    $sav['ref_id']=$trx;
                    $sav['secret']=$trx;
                    $sav['city']=$result['data']['city'];
                    $sav['state']=$result['data']['state'];
                    $sav['zip_code']=$result['data']['zip_code'];
                    $sav['address']=$result['data']['address_1'];
                    $sav['amount']=$request->amount;
                    $sav['bg']=$request->bg;
                    $sav['charge']=$request->amount*$set->virtual_createcharge/100+($set->virtual_createchargep);
                    Virtual::create($sav);
                    return back()->with('success', 'Virtual card was successfully created');
                }else{
                    return back()->with('alert', $result['message']);
                }
            }else{
                return back()->with('alert', 'Account balance is insufficient');
            }
        }    
        
        public function useVirtual(Request $request)
        {
            $body = @file_get_contents("php://input");
            $signature = (isset($_SERVER['HTTP_VERIF_HASH']) ? $_SERVER['HTTP_VERIF_HASH'] : '');
            if (!$signature) {
                exit();
            }
            $local_signature = env('SECRET_HASH');
            if( $signature !== $local_signature ){
                exit();
            }
            http_response_code(200);
            $response = json_decode($body);
            $trx='VC-'.str_random(6);
            if ($response->status == 'successful') {
                $card=Virtual::wherecard_hash($response->CardId)->first();
                $card->amount-$response->amount;
                $card->save;
                $sav['user_id']=$card->user_id;
                $sav['virtual_id']=$card->id;
                $amo=str_replace( ',', '', $response->amount);
                $sav['amount']=$amo;
                $sav['description']=$response->description;
                $sav['trx']=$trx;
                $sav['type']=2;
                Virtualtransactions::create($sav);
                //History
                $his['user_id']=$card->user_id;
                $his['ref']=$trx;
                $his['main']=0;
                $his['type']=2;
                History::create($his);
            }
    
        }
    
        public function transactionsVirtual($id){
            $data['title']='Transaction History';
            $val=Virtual::whereid($id)->first();
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.flutterwave.com/v3/virtual-cards/".$val->card_hash."/transactions?from=".date('Y-m-d', strtotime($val['created_at']))."&to=".Carbon::tomorrow()->format('Y-m-d')."&index=1&size=100",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Authorization: Bearer ".env('SECRET_KEY')
            ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $data['log']=$response;
            return view('user.virtual.log', $data);
        } 

        public function terminateVirtual($id){
            $user=User::find(Auth::user()->id);
            $vcard=Virtual::whereid($id)->first();
            $set=Settings::first();
            $currency=Currency::whereStatus(1)->first();
            $trx=str_random(8);
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.flutterwave.com/v3/virtual-cards/".$vcard->card_hash."/terminate",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "PUT",
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/json",
                    "Authorization: Bearer ".env('SECRET_KEY')
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $result = json_decode($response, true);
            if (array_key_exists('data', $result) && ($result['status'] === 'success')) {
                //Debit User
                $user->balance=$user->balance+$vcard->amount;
                $user->save();
                $vcard->amount=0;
                $vcard->status=0;
                $vcard->save();
                $audit['user_id']=Auth::guard('user')->user()->id;
                $audit['trx']=str_random(16);
                $audit['log']='Terminated Virtual Card #'.$vcard->ref_id;
                Audit::create($audit);  
                $plans = Virtual::whereid($id)->delete();
                return back()->with('success', $result['message']);
            }else{
                return back()->with('alert', $result['message']);
            }
        }        
        
        public function blockVirtual($id){
            $user=User::find(Auth::user()->id);
            $vcard=Virtual::whereid($id)->first();
            $set=Settings::first();
            $currency=Currency::whereStatus(1)->first();
            $trx=str_random(8);
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.flutterwave.com/v3/virtual-cards/".$vcard->card_hash."/status/block",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "PUT",
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/json",
                    "Authorization: Bearer ".env('SECRET_KEY')
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $result = json_decode($response, true);
            if (array_key_exists('data', $result) && ($result['status'] === 'success')) {
                //Debit User
                $vcard->status=2;
                $vcard->save();
                $audit['user_id']=Auth::guard('user')->user()->id;
                $audit['trx']=str_random(16);
                $audit['log']='Blocked Virtual Card #'.$vcard->ref_id;
                Audit::create($audit);  
                return back()->with('success', $result['message']);
            }else{
                return back()->with('alert', $result['message']);
            }
        }        
        
        public function unblockVirtual($id){
            $user=User::find(Auth::user()->id);
            $vcard=Virtual::whereid($id)->first();
            $set=Settings::first();
            $currency=Currency::whereStatus(1)->first();
            $trx=str_random(8);
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.flutterwave.com/v3/virtual-cards/".$vcard->card_hash."/status/unblock",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "PUT",
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/json",
                    "Authorization: Bearer ".env('SECRET_KEY')
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $result = json_decode($response, true);
            if (array_key_exists('data', $result) && ($result['status'] === 'success')) {
                //Debit User
                $vcard->status=1;
                $vcard->save();
                $audit['user_id']=Auth::guard('user')->user()->id;
                $audit['trx']=str_random(16);
                $audit['log']='Blocked Virtual Card #'.$vcard->ref_id;
                Audit::create($audit);  
                return back()->with('success', $result['message']);
            }else{
                return back()->with('alert', $result['message']);
            }
        }
    //End Virtual Cards

    //Bills
        public function bill(){
            $data['title']='Bill Payment';
            $bill = new Bill();
            $data['log'] = $bill->getBillCategories();
            return view('user.bill.index', $data);
        }       
        
        public function airtime(){
            $data['title']='Airtime';
            $airtime = new Bill();
            $data['log'] = $airtime->getBillCategories();
            $data['trans'] = Billtransactions::whereuser_id(Auth::guard('user')->user()->id)->wheretype(1)->orderBy('created_at', 'DESC')->get();
            return view('user.bill.airtime', $data);
        }        

        public function data_bundle(){
            $data['title']='Data Bundle';
            $bundle = new Bill();
            $data['log'] = $bundle->getBillCategories();
            $data['trans'] = Billtransactions::whereuser_id(Auth::guard('user')->user()->id)->wheretype(2)->orderBy('created_at', 'DESC')->get();
            return view('user.bill.data-bundle', $data);
        }       
        
        public function tv_cable(){
            $data['title']='Tv Cable';
            $bundle = new Bill();
            $data['log'] = $bundle->getBillCategories();
            $data['trans'] = Billtransactions::whereuser_id(Auth::guard('user')->user()->id)->wheretype(3)->orderBy('created_at', 'DESC')->get();
            return view('user.bill.tv-cable', $data);
        }
        
        public function electricity(){
            $data['title']='Electricity';
            $bundle = new Bill();
            $data['log'] = $bundle->getBillCategories();
            $data['trans'] = Billtransactions::whereuser_id(Auth::guard('user')->user()->id)->wheretype(4)->orderBy('created_at', 'DESC')->get();
            return view('user.bill.electricity', $data);
        }  

        public function submitbill(Request $request){
            $set=Settings::first();
            $user=User::find(Auth::user()->id);
            if($user->balance>$request->amount){
                $currency=Currency::whereStatus(1)->first();
                $trx='BP-'.str_random(6);
                $set=Settings::first();
                if($request->type==1){
                    $type='AIRTIME';
                    $ebiller = $request->biller;
                }elseif($request->type==2){
                    $type=$request->biller;
                    $ebiller = $request->biller;
                }elseif($request->type==3){
                    $exp = $pieces = explode("-", $request->biller);
                    $ebiller = trim($exp[0]);
                    $ecode = trim($exp[1]);
                    if($ecode=='BIL123'){
                        $type=$ebiller;
                    }else{
                        $type='DSTV';
                    }
                }elseif($request->type==4){
                    $type=$request->biller;
                    $ebiller = $request->biller;
                }
                $data = array(
                    "country"=> "NG",
                    "customer"=> $request->track,
                    "amount"=> (int)$request->amount,
                    "recurrence"=> "ONCE",
                    "type"=> $type,
                    "reference"=> $trx
                );
                $payment = new Bill();
                $result = $payment->payBill($data);
                if (array_key_exists('data', $result) && ($result['status'] === 'success')) {
    
                    //Credit Card creation Charge
                    $charge['user_id']=$user->id;
                    $charge['ref_id']=$trx;
                    $charge['amount']=$request->amount*$set->bill_charge/100+($set->bill_chargep);
                    $charge['log']='Bill payment for #'.$trx;
                    Charges::create($charge);

                    $his['user_id']=$user->id;
                    $his['amount']=$request->amount+($request->amount*$set->bill_charge/100+($set->bill_chargep));
                    $his['ref']=$trx;
                    $his['main']=1;
                    $his['type']=2;
                    History::create($his);
    
                    //Debit User
                    $user->balance=$user->balance-$request->amount-($request->amount*$set->bill_charge/100+($set->bill_chargep));
                    $user->save();
    
                    //Save Card
                    $sav['user_id']=$user->id;
                    $sav['type']=$request->type;
                    $sav['amount']=$request->amount;
                    $sav['biller']=$ebiller;
                    $sav['charge']=$request->amount*$set->bill_charge/100+($set->bill_chargep);
                    $sav['ref']=$trx;
                    $sav['track']=$request->track;
                    $sav['trx_ref']=$result['data']['flw_ref'];
                    Billtransactions::create($sav);
                    return back()->with('success', 'Payment was successful');
                }else{
                    return back()->with('alert', $result['message']);
                }
            }else{
                return back()->with('alert', 'Account balance is insufficient');
            }
        }
    //End Virtual Cards

    //Support ticket
        public function ticket()
        {
            $data['title']='Tickets';
            $data['ticket']=Ticket::whereUser_id(Auth::guard('user')->user()->id)->latest()->paginate(4);
            return view('user.support.index', $data);
        }        
        public function openticket()
        {
            $data['title']='New Ticket';
            return view('user.support.new', $data);
        } 
        public function Replyticket($id)
        {
            $data['ticket']=$ticket=Ticket::whereid($id)->first();
            $data['title']='#'.$ticket->ticket_id;
            $data['reply']=Reply::whereTicket_id($ticket->ticket_id)->get();
            return view('user.support.reply', $data);
        }  
        public function Destroyticket($id)
        {
            $data = Ticket::findOrFail($id);
            $res =  $data->delete();
            if ($res) {
                return back()->with('success', 'Request was Successfully deleted!');
            } else {
                return back()->with('alert', 'Problem With Deleting Request');
            }
        } 
        public function submitticket(Request $request)
        {      
            if($request->hasfile('image')){
                $validator=Validator::make($request->all(), [
                    'image.*' => 'mimes:doc,pdf,docx,zip,png,jpeg'
                ]);
                if ($validator->fails()) {
                    return redirect()->route('transfererror')->withErrors($validator)->withInput();
                }else{
                    foreach($request->file('image') as $file){
                        $token=str_random(10);
                        $name = 'support_'.$token.'.'.$file->extension();
                        $file->move('asset/profile/', $name);
                        $data[] = $name;  
                        $sav['files'] = json_encode($data);
                    }
                }
            }
            $set=Settings::first();
            $user=$data['user']=User::find(Auth::guard('user')->user()->id);
            $sav['user_id']=Auth::guard('user')->user()->id;
            $sav['subject']=$request->subject;
            $sav['priority']=$request->priority;
            $sav['type']=$request->type;
            $sav['message']=$request->details;
            $sav['ref_no']=$request->ref_no;
            $sav['ticket_id']=$token=str_random(16);
            $sav['status']=0;
            Ticket::create($sav);
            if($set['email_notify']==1){
                send_email($user->email, $user->username, 'New Ticket - '.$request->subject, "Thank you for contacting us, we will get back to you shortly, your Ticket ID is ".$token);
                send_email($set->support_email, $set->site_name, 'New Ticket:'. $token, "New ticket request");
            }
            return redirect()->route('user.ticket')->with('success', 'Ticket Submitted Successfully.');
        }     
        public function submitreply(Request $request)
        {
            $set=Settings::first();
            $sav['reply']=$request->details;
            $sav['ticket_id']=$request->id;
            $sav['status']=1;
            Reply::create($sav);
            if($set['email_notify']==1){
                send_email($set->email, $set->site_name, 'Ticket Reply:'. $request->id, "New ticket reply request");
            }
            $data=Ticket::whereTicket_id($request->id)->first();
            $data->status=0;
            $data->save();
            return back()->with('success', 'Message sent!.');
        }   
    //End Support ticket

    //Store
        public function product()
        {
            $data['title']='Products';
            $data['product']=Product::whereUser_id(Auth::guard('user')->user()->id)->orderby('id', 'desc')->get();
            $data['category']=Productcategory::whereUser_id(Auth::guard('user')->user()->id)->get();
            $data['received']=Order::whereStatus(1)->whereseller_id(Auth::guard('user')->user()->id)->sum('total');
            $data['total']=Order::whereseller_id(Auth::guard('user')->user()->id)->sum('total');            
            return view('user.product.index', $data);
        }               
        public function storefront()
        {
            $data['title']='Storefronts'; 
            $data['store']=Storefront::whereUser_id(Auth::guard('user')->user()->id)->orderby('id', 'desc')->paginate(6);  
            $data['product']=Product::whereUser_id(Auth::guard('user')->user()->id)->orderby('id', 'desc')->get();
            $data['category']=Productcategory::whereUser_id(Auth::guard('user')->user()->id)->get();
            $data['received']=Order::whereStatus(1)->whereseller_id(Auth::guard('user')->user()->id)->sum('total');
            $data['total']=Order::whereseller_id(Auth::guard('user')->user()->id)->sum('total');  
            $data['orders']=Order::whereseller_id(Auth::guard('user')->user()->id)->latest()->get();          
            $data['yourorders']=Order::whereuser_id(Auth::guard('user')->user()->id)->latest()->get();          
            $data['shipping']=Shipping::whereuser_id(Auth::guard('user')->user()->id)->latest()->get();          
            return view('user.product.storefront', $data);
        } 
        public function unstore($id)
        {
            $page=Storefront::find($id);
            $page->status=0;
            $page->save();
            return redirect()->route('user.storefront')->with('success', 'Storefront has been disabled.');
        } 
        public function pstore($id)
        {
            $page=Storefront::find($id);
            $page->status=1;
            $page->save();
            return redirect()->route('user.storefront')->with('success', 'Storefront has been activated.');
        } 
        public function submitstore(Request $request)
        {
            $check=Storefront::wherestore_name(strtolower($request->store_name))->count();
            if($check>0){
                return back()->with('alert', 'Store name already taken');
            }else{
                $sav['user_id']=Auth::guard('user')->user()->id;
                $sav['store_name']=$request->store_name;
                $sav['store_desc']=$request->store_desc;
                $sav['store_url']=strtolower($request->store_name);
                $sav['category']=$request->category;
                $sav['shipping_status']=$request->shipping_status;
                $sav['note_status']=$request->note_status;
                Storefront::create($sav);
                return redirect()->route('user.storefront')->with('success', 'Store succesfully created');
            }
        }        
        public function submitshipping(Request $request)
        {
            $sav['user_id']=Auth::guard('user')->user()->id;
            $sav['region']=$request->region;
            $sav['amount']=$request->amount;
            Shipping::create($sav);
            return redirect()->route('user.shipping')->with('success', 'Shipping fee succesfully created');
        }        
        
        public function updateshipping(Request $request)
        {
            $user=User::whereid(Auth::guard('user')->user()->id)->first();
            $user->shipping=$request->shipping;
            $user->save();
            return redirect()->route('user.shipping')->with('success', 'Shipping succesfully updated');
        }        
        public function submitstoreproduct(Request $request)
        {
            $check=Storefrontproducts::wherestore_id($request->id)->whereproduct_id($request->product)->count();
            if($check>0){
                return back()->with('alert', 'Product already added');
            }else{
                $sav['store_id']=$request->id;
                $sav['product_id']=$request->product;
            }
            Storefrontproducts::create($sav);
            return back()->with('success', 'Product succesfully added');
        }        
        public function editstore(Request $request)
        {            
            $store=Storefront::find($request->id);
            $store->store_name=$request->store_name;
            $store->store_desc=$request->store_desc;
            $store->category=$request->category;
            $store->shipping_status=$request->shipping_status;
            $store->note_status=$request->note_status;
            $store->save();
            return redirect()->route('user.storefront')->with('success', 'Store succesfully updated');
        }        
        public function editshipping(Request $request)
        {            
            $ship=Shipping::find($request->id);
            $ship->region=$request->region;
            $ship->amount=$request->amount;
            $ship->save();
            return redirect()->route('user.shipping')->with('success', 'Shipping Region succesfully updated');
        }
        public function Storefrontproducts($id)
        {
            $data['product']=Storefrontproducts::wherestore_id($id)->get();
            $data['new']=Product::whereUser_id(Auth::guard('user')->user()->id)->wherestatus(1)->get();
            $data['store_id']=$id;
            $data['title']='Products';
            return view('user.product.store-product', $data);
        }
        public function Destroystorefront($id)
        {
            $store = Storefront::findOrFail($id);
            $store->delete();
            $pro = Storefrontproducts::wherestore_id($id)->get();
            foreach($pro as $val){
                $val->delete();
            }
            return back()->with('success', 'Store succesfully deleted');
        }        
        public function Destroystorefrontproduct($id)
        {
            $store = Storefrontproducts::findOrFail($id);
            $store->delete();
            return back()->with('success', 'Product succesfully deleted');
        }
        public function Destroyproduct($id)
        {
            $data = Product::findOrFail($id);
            $store = Storefrontproducts::whereproduct_id($id)->get();
            $image = Productimage::whereproduct_id($id)->get();
            $order = Order::whereproduct_id($id)->get();
            if(count($image)>0){
                foreach($image as $val){
                    $val->delete();
                }
            }            
            if(count($order)>0){
                foreach($order as $val){
                    $val->delete();
                }
            }            
            if(count($store)>0){
                foreach($store as $val){
                    $val->delete();
                }
            }
            $data->delete();
            return redirect()->route('user.product')->with('success', 'Product succesfully deleted');
        }        
        public function Destroyproductcategory($id)
        {
            $data = ProductCategory::findOrFail($id);
            $cat = Product::wherecat_id($id)->count();
            if($cat>0){
                return back()->with('alert', 'Category cannot be deleted as it is assigned to a product');
            }else{
                $data->delete();
                return redirect()->route('user.product')->with('success', 'Category succesfully deleted');
            }            
        }
        public function submitproduct(Request $request)
        {
            $user=$data['user']=User::find(Auth::guard('user')->user()->id);
            $sav['user_id']=Auth::guard('user')->user()->id;
            $sav['ref_id']=$trx='PRD-'.str_random(6);
            $sav['name']=$request->name;
            $sav['quantity']=$request->quantity;
            $sav['cat_id']=$request->category;
            $sav['amount']=$request->amount;
            $sav['shipping_status']=$request->shipping_status;
            $sav['new']=1;
            Product::create($sav);
            if ($request->hasFile('file')) {
                $image = $request->file('file');
                $filename = time().'.'.$image->extension();
                $location = 'asset/profile/' . $filename;
                Image::make($image)->resize(399,399)->save($location);
                $product=Product::whereref_id($trx)->first();
                $sa['image']=$filename;
                $sa['product_id']=$product->id;
                Productimage::create($sa);
            }
            return redirect()->route('edit.product', ['id' => $trx]);
        }        
        public function submitcategory(Request $request)
        {
            $sav['user_id']=Auth::guard('user')->user()->id;
            $sav['name']=$request->name;
            Productcategory::create($sav);
            return redirect()->route('user.product')->with('success', 'Category succesfully created');
        }        
    
        public function Editproduct($id)
        {
            $data['product']=$product=Product::whereref_id($id)->first();
            $data['images']=Productimage::whereproduct_id($product->id)->get();
            $data['category']=Productcategory::all();
            $data['title']=$product->name;
            return view('user.product.edit', $data);
        }         
        
        public function Orders($id)
        {
            $data['product']=$product=Product::find($id);
            $data['orders']=Order::whereproduct_id($id)->latest()->get();
            $data['title']=$product->name;
            return view('user.product.orders', $data);
        }    
        
        public function storeorders($id)
        {
            $data['product']=$store=Storefront::find($id);
            $data['orders']=Order::wherestore_id($id)->latest()->get();
            $data['title']=$store->store_name;
            return view('user.product.store-list', $data);
        }
        
        public function list()
        {
            $data['orders']=Order::whereuser_id(Auth::guard('user')->user()->id)->latest()->get();
            $data['title']='Product Orders';
            return view('user.product.list', $data);
        } 
        public function Descriptionupdate(Request $request)
        {
            $data=Product::whereId($request->id)->first();
            $data->fill($request->all())->save();
            return back()->with('success', 'Successfully updated');
        }        
        public function Featureupdate(Request $request)
        {
            $data=Product::whereId($request->id)->first();
            $data->fill($request->all())->save();
            if(empty($request->status)){
                $data->status=0;	
            }else{
                $data->status=$request->status;
            }  
            $data->save();             
            if($request->has('shipping_status')){
                $check=Shipping::whereuser_id(Auth::guard('user')->user()->id)->count();
                if($check>0){
                    if(empty($request->shipping_status)){
                        $data->shipping_status=0;	
                    }else{
                        $data->shipping_status=$request->shipping_status;
                    } 
                    $data->save();   
                }else{
                    return back()->with('alert', 'Ensure you have added regions for shipping before this can be enabled');
                }
            }
            return back()->with('success', 'Successfully updated');
        }
        public function submitproductimage(Request $request)
        {
            $check=Productimage::whereproduct_id($request->id)->count();
            if($check<6){
                if ($request->hasFile('file')) {
                    $image = $request->file('file');
                    $filename = time().'.'.$image->extension();
                    $location = 'asset/profile/' . $filename;
                    Image::make($image)->resize(399,399)->save($location);
                    $sav['image']=$filename;
                    $sav['product_id']=$request->id;
                    Productimage::create($sav);
                    $ext=Product::whereid($request->id)->first();
                    $ext->new=1;
                    $ext->save();
                    return back()->with('success', 'Successfully uploaded');
                }else{
                    return back()->with('alert', 'An error occured, please try again later');
                }
            }else{
                return back()->with('alert', 'You have exceeded your image limit');
            }
        }
        public function deleteproductimage($id)
        {
            $data = Productimage::findOrFail($id);
            $path = './asset/profile/';
            File::delete($path.$data->image);
            $res =  $data->delete();
            $ext=Productimage::whereproduct_id($data->product_id)->get();
            if(count($ext)<1){
                $dext=Product::whereid($data->product_id)->first();
                $dext->new=0;
                $dext->save();
            }
            if ($res) {
                return back()->with('success', 'Image Deleted Successfully!');
            } else {
                return back()->with('alert', 'Problem With Deleting Image');
            }
        }
        public function ask($id)
        {

            $product = $data['product']=Product::whereref_id($id)->first();
            $check=Product::whereref_id($id)->get();
            $data['title']='Payment Method';
            return view('user.product.ask', $data);
        }         
        
        public function cardpay($id)
        {

            $product = $data['product']=Product::whereref_id($id)->first();
            Session::put('pay-type', 'card');
            return redirect()->route('product.link', ['id'=>$product->ref_id]);
        }        
        
        public function accountpay($id)
        {

            $product = $data['product']=Product::whereref_id($id)->first();
            Session::put('pay-type', 'account');
            return redirect()->route('product.link', ['id'=>$product->ref_id]);
        }        
        
        public function sask($id)
        {
            $data['title']='Payment Method';
            $data['cart']=$id;
            return view('user.product.sask', $data);
        }         
        
        public function scardpay($id)
        {
            Session::put('pay-type', 'card');
            return redirect()->route('checkout', ['id'=>$id]);
        }        
        
        public function saccountpay($id)
        {
            Session::put('pay-type', 'account');
            return redirect()->route('checkout', ['id'=>$id]);
        } 
        
        public function buyproduct($id)
        {
            $check=Product::whereref_id($id)->get();
            if(count($check)>0){
                $product = $data['product']=Product::whereref_id($id)->first();
                if($product->user->status==0){
                    if($product->active==1){                    
                        if($product->status==1){
                            $data['merchant']=$merchant=User::whereid($product->user_id)->first();
                            $data['image']=Productimage::whereproduct_id($product->id)->get();
                            $data['first']=Productimage::whereproduct_id($product->id)->first();
                            $data['title'] = $product->name;
                            $data['ref'] = 'ORD-'.str_random(6);
                            $data['subtotal']=$subtotal= $product->amount*1;
                            $data['total']= $subtotal+$product->shipping_fee;
                            $data['ship']=Shipping::whereuser_id($product->user_id)->get();
                            return view('user.product.buy', $data);
                        }else{
                            $data['title']='Error Occured';
                            return view('user.merchant.error', $data)->withErrors('Product Not found');
                        }            
                    }else{
                        $data['title']='Error Occured';
                        return view('user.merchant.error', $data)->withErrors('Product has been suspended');
                    }
                }else{
                    $data['title']='Error Message';
                    return view('user.merchant.error', $data)->withErrors('An Error Occured');
                }
            }else{
                $data['title']='Error Message';
                return view('user.merchant.error', $data)->withErrors('Invalid product link');
            }
        }        
        
        public function checkout($id)
        {
            $product = $data['product']=Cart::whereuniqueid($id)->first();
            $store = $data['store']=Storefront::whereid($product->store)->first();
            $allsum = $data['allsum']=Cart::whereuniqueid($id)->sum('total');
            $all = $data['all']=Cart::whereuniqueid($id)->get();
            $data['merchant']=$merchant=User::whereid($store->user_id)->first();
            $data['title'] = 'Checkout';
            $data['subtotal']=$allsum;
            $data['total']= $allsum;
            $data['ship']=Shipping::whereuser_id($store->user_id)->get();
            return view('user.product.sbuy', $data);
        }  
        
        public function storelink($id)
        {
            $data['store']=$store=Storefront::wherestore_url($id)->first();
            if($store->user->status==0){
                $data['cart'] = Cart::where('uniqueid',Session::get('uniqueid'))->wherestore($store->id)->get();
                $data['gtotal'] = Cart::where('uniqueid',Session::get('uniqueid'))->wherestore($store->id)->sum('total');
                $data['merchant']=$merchant=User::whereid($store->user_id)->first();
                $data['products']=Storefrontproducts::wherestore_id($store->id)->orderby('id', 'desc')->get();
                $data['title']=$store->store_name; 
                return view('user.product.view-store', $data);
            }else{
                $data['title']='Error Message';
                return view('user.merchant.error', $data)->withErrors('An Error Occured');
            }         
        }         
        
        public function productlink($store, $product)
        {
            $data['product']=$xproduct=Product::whereid($product)->first();
            $data['rr']=$rr=Productimage::whereproduct_id($product)->first();
            $data['image']=$image=Productimage::whereproduct_id($product)->get();
            $data['store']=$store=Storefront::whereid($store)->first();
            $data['merchant']=$merchant=User::whereid($store->user_id)->first();
            $data['cart'] = Cart::where('uniqueid',Session::get('uniqueid'))->wherestore($store->id)->get();
            $data['gtotal'] = Cart::where('uniqueid',Session::get('uniqueid'))->wherestore($store->id)->sum('total');
            $data['title']=$xproduct->name; 
            return view('user.product.view-product', $data);      
        }  
        public function stripeacquireproduct(Request $request, $id)
        {
            $amount=Session::get('amount');
            $shipping_fee=Session::get('shipping_fee');
            $quantity=Session::get('quantity');
            $total= ($quantity*$amount)+$shipping_fee;
            $set=Settings::first();
            $currency=Currency::whereStatus(1)->first();
            $merchant=Product::whereid($id)->first();
            $up_mer=User::whereid($merchant->user_id)->first();
            $tokenx='ORD-'.str_random(6);
            $gate = Gateway::find(103);
            $stripe = new StripeClient($gate->val2);
            try {
                $charge=$stripe->paymentIntents->retrieve($request->input('payment_intent'));
                if ($charge['status']=="succeeded") {
                    $up_mer->balance=$total+$up_mer->balance-($total*$set->product_charge/100+($set->product_chargep));
                    $up_mer->save();
                    $sav['quantity']=Session::get('quantity');
                    $sav['seller_id']=$merchant->user_id;
                    if (Auth::guard('user')->check()){
                        $sav['first_name']=Auth::guard('user')->user()->first_name;
                        $sav['last_name']=Auth::guard('user')->user()->last_name;
                        $sav['email']=Auth::guard('user')->user()->email;
                        $sav['phone']=Auth::guard('user')->user()->phone;
                    }else{
                        $sav['first_name']=Session::get('first_name');
                        $sav['last_name']=Session::get('last_name');
                        $sav['email']=Session::get('email');
                        $sav['phone']=Session::get('phone');
                    }
                    $sav['address']=Session::get('address');
                    $sav['country']=Session::get('country');
                    $sav['state']=Session::get('state');
                    $sav['town']=Session::get('town');
                    $sav['note']=Session::get('note');
                    $sav['amount']=Session::get('amount');
                    $sav['charge']=$charge=($total*$set->product_charge/100+($set->product_chargep));
                    $sav['total']=($amount*$quantity+$shipping_fee)-$charge;
                    $sav['ref_id']=$token=Session::get('ref');
                    $sav['product_id']=$id;
                    $sav['amount']=$amount;
                    $xship=Session::get('xship');
                    if(!empty($xship)){
                        $dd = explode("-", Session::get('shipping'));
                        $df = trim($dd[1]);
                        $sav['shipping_fee']=$df;
                        $sav['ship_id']=Session::get('xship');
                    }
                    $sav['status']=1;
                    Order::create($sav);
                    $product=Product::whereid($id)->first();
                    if($product->quantity_status==0){
                        $product->quantity=$product->quantity-$quantity;
                        $product->sold=$product->sold+1;
                        $product->save();
                    }
                    //Charges
                    $chargex['user_id']=$up_mer->id;
                    $chargex['ref_id']=$token;
                    $chargex['amount']=$total*$set->product_charge/100+($set->product_chargep);
                    $chargex['log']='Received payment for order #' .$token;
                    Charges::create($chargex);
                    $his['user_id']=$up_mer->id;
                    $his['amount']=$total-($total*$set->product_charge/100+($set->product_chargep));
                    $his['ref']=$token;
                    $his['main']=1;
                    $his['type']=1;
                    $his['stripe_id']=$charge['id'];
                    $his['charge']=$total*$set->product_charge/100+($set->product_chargep);
                    History::create($his);
                    //Audit
                    $audit['user_id']=$up_mer->id;
                    $audit['trx']=str_random(16);
                    $audit['log']='Received payment for order #' .$token;
                    Audit::create($audit);
                    //Notify users
                    if($set->email_notify==1){
                        send_productlinkreceipt($merchant->ref_id, 'card', $token);
                    } 
                    //Redirect payment
                    if(Auth::guard('user')->check()){
                        return redirect()->route('user.your-list')->with('success', 'Payment was successful');
                    }else{
                        return redirect()->route('user.cardpay', ['id' => $merchant->ref_id])->with('success', 'Payment was Successful!!!');
                    }  
                }  else {
                    return redirect()->route('user.cardpay', ['id' => $merchant->ref_id])->with('alert', 'Failed');
                }
            } catch (\Stripe\Exception\CardException $e) {
                return redirect()->route('user.cardpay', ['id' => $merchant->ref_id])->with('alert', $e->getMessage());
            } catch (\Stripe\Exception\InvalidRequestException $e) {
                return redirect()->route('user.cardpay', ['id' => $merchant->ref_id])->with('alert', $e->getMessage());
            }
        }
        public function acquireproduct(Request $request)
        {
            Session::put('amount', $request->amount);
            Session::put('shipping_fee', $request->shipping_fee);
            Session::put('shipping', $request->shipping);
            Session::put('first_name', $request->first_name);
            Session::put('last_name', $request->last_name);
            Session::put('email', $request->email);
            Session::put('phone', $request->phone);
            Session::put('address', $request->address);
            Session::put('country', $request->country);
            Session::put('state', $request->state);
            Session::put('town', $request->town);
            Session::put('note', $request->note);
            Session::put('xship', $request->xship);
            Session::put('ref', $request->ref_id);
            Session::put('quantity', $request->quantity);
            $total= ($request->quantity*$request->amount)+$request->shipping_fee;
            $currency=Currency::whereStatus(1)->first();
            $set=Settings::first();
            $merchant=Product::whereid($request->product_id)->first();
            $up_mer=User::whereid($merchant->user_id)->first();
            if($request->type=='card'){
                if ($request->has('stripeSource')){
                    $gate = Gateway::find(103);
                    $stripe = new StripeClient($gate->val2);
                    try {
                        $charge=$stripe->paymentIntents->create([
                            'amount' => $total*100,
                            'currency' => $currency->name,
                            'payment_method_types' => ['card'],
                            'description' => 'Product purchase #'.$request->ref_id,
                            'source' => $request->input('stripeSource'),
                            'return_url' => route('stripe.pay.product', ['id' => $request->product_id]),
                            'confirm' => true,
                        ]);  
                        if ($charge['status']=="succeeded") {
                            $up_mer->balance=$total+$up_mer->balance-($total*$set->product_charge/100+($set->product_chargep));
                            $up_mer->save();
                            $sav['quantity']=$request->quantity;
                            $sav['seller_id']=$merchant->user_id;
                            if (Auth::guard('user')->check()){
                                $sav['first_name']=Auth::guard('user')->user()->first_name;
                                $sav['last_name']=Auth::guard('user')->user()->last_name;
                                $sav['email']=Auth::guard('user')->user()->email;
                                $sav['phone']=Auth::guard('user')->user()->phone;
                            }else{
                                $sav['first_name']=$request->first_name;
                                $sav['last_name']=$request->last_name;
                                $sav['email']=$request->email;
                                $sav['phone']=$request->phone;
                            }
                            $sav['address']=$request->address;
                            $sav['country']=$request->country;
                            $sav['state']=$request->state;
                            $sav['town']=$request->town;
                            $sav['note']=$request->note;
                            $sav['amount']=$request->amount;
                            $sav['charge']=$charge=($total*$set->product_charge/100+($set->product_chargep));
                            $sav['total']=($request->amount*$request->quantity+$request->shipping_fee)-$charge;
                            $sav['ref_id']=$token=$request->ref_id;
                            $sav['product_id']=$request->product_id;
                            $sav['amount']=$request->amount;
                            if($request->has('xship')){
                                $dd = explode("-", $request->shipping);
                                $df = trim($dd[1]);
                                $sav['shipping_fee']=$df;
                                $sav['ship_id']=$request->xship;
                            }
                            $sav['status']=1;
                            Order::create($sav);
                            $product=Product::whereid($request->product_id)->first();
                            if($product->quantity_status==0){
                                $product->quantity=$product->quantity-$request->quantity;
                                $product->sold=$product->sold+1;
                                $product->save();
                            }
                            //Charges
                            $chargex['user_id']=$up_mer->id;
                            $chargex['ref_id']=$token;
                            $chargex['amount']=$total*$set->product_charge/100+($set->product_chargep);
                            $chargex['log']='Received payment for order #' .$token;
                            Charges::create($chargex);
                            $his['user_id']=$up_mer->id;
                            $his['amount']=$total-($total*$set->product_charge/100+($set->product_chargep));
                            $his['ref']=$token;
                            $his['main']=1;
                            $his['type']=1;
                            $his['stripe_id']=$charge['id'];;
                            $his['charge']=$total*$set->product_charge/100+($set->product_chargep);
                            History::create($his);
                            //Audit
                            $audit['user_id']=$up_mer->id;
                            $audit['trx']=str_random(16);
                            $audit['log']='Received payment for order #' .$token;
                            Audit::create($audit);
                            //Notify users
                            if($set->email_notify==1){
                                send_productlinkreceipt($merchant->ref_id, 'card', $token);
                            } 
                            //Redirect payment
                            if(Auth::guard('user')->check()){
                                return redirect()->route('user.your-list')->with('success', 'Payment was successful');
                            }else{
                                return back()->with('success', 'Payment was Successful!!!');
                            }  
                        }elseif($charge['status']=="requires_action"){
                            return Redirect::away($charge['next_action']['redirect_to_url']['url']);
                        }else {
                            return back()->with('alert', $charge['error']['message']);
                        }
                    } catch (\Stripe\Exception\CardException $e) {
                        return back()->with('alert', $e->getMessage());
                    }catch (\Stripe\Exception\InvalidRequestException $e) {
                        return back()->with('alert', $e->getMessage());
                    }
                }else{
                    $data['title']='Error Message';
                    return view('user.merchant.error', $data)->withErrors('Card details is required');
                }
            }elseif($request->type=='account'){
                $debit=User::whereId(Auth::guard('user')->user()->id)->first();
                if($total<$debit->balance || $total==$debit->balance){
                    $up_mer->balance=$total+$up_mer->balance-($total*$set->product_charge/100+($set->product_chargep));
                    $up_mer->save();
                    $debit->balance=$debit->balance-($total);
                    $debit->save();
                    $sav['quantity']=$request->quantity;
                    $sav['user_id']=Auth::guard('user')->user()->id;
                    $sav['seller_id']=$merchant->user_id;
                    $sav['address']=$request->address;
                    $sav['country']=$request->country;
                    $sav['state']=$request->state;
                    $sav['town']=$request->town;
                    $sav['note']=$request->note;
                    $sav['amount']=$request->amount;
                    $sav['charge']=$charge=($total*$set->product_charge/100+($set->product_chargep));
                    $sav['total']=($request->amount*$request->quantity+$request->shipping_fee)-$charge;
                    $sav['ref_id']=$token=$request->ref_id;
                    $sav['product_id']=$request->product_id;
                    $sav['amount']=$request->amount;
                    if($request->has('xship')){
                        $dd = explode("-", $request->shipping);
                        $df = trim($dd[1]);
                        $sav['shipping_fee']=$df;
                        $sav['ship_id']=$request->xship;
                    }
                    $sav['status']=1;
                    Order::create($sav);
                    $product=Product::whereid($request->product_id)->first();
                    if($product->quantity_status==0){
                        $product->quantity=$product->quantity-$request->quantity;
                        $product->sold=$product->sold+1;
                        $product->save();
                    }
                    //Charges
                    $chargex['user_id']=$up_mer->id;
                    $chargex['ref_id']=$token;
                    $chargex['amount']=$total*$set->product_charge/100+($set->product_chargep);
                    $chargex['log']='Received payment for order #' .$token;
                    Charges::create($chargex);
                    $his['user_id']=$up_mer->id;
                    $his['amount']=$total-($total*$set->product_charge/100+($set->product_chargep));
                    $his['ref']=$token;
                    $his['main']=1;
                    $his['type']=1;
                    History::create($his);
                    //Audit
                    $audit['user_id']=$up_mer->id;
                    $audit['trx']=str_random(16);
                    $audit['log']='Received payment for order #' .$token;
                    Audit::create($audit);
                    if($set->email_notify==1){
                        send_email($merchant->email, $set->site_name, 'Order Notification', 'Hey you just received'.$currency->symbol.numberformat($ext->total).' from '.$request->first_name.' '.$request->last_name.' via order no #'.$request->ref_id);
                    }
                    return redirect()->route('user.your-list')->with('success', 'Product was successfully paid for');
                }else{
                    return back()->with('alert', 'Account balance is insufficient');
                } 
            }
            return redirect()->route('user.product');
        }        
        
        public function stripecheckproduct(Request $request, $id)
        {
            $amount=Session::get('amount');
            $shipping_fee=Session::get('shipping_fee');
            $total= $amount+$shipping_fee;
            $set=Settings::first();
            $currency=Currency::whereStatus(1)->first();
            $cart=Cart::whereuniqueid($id)->get();
            $cartf=Cart::whereuniqueid($id)->first();
            $merchant=Storefront::whereid($cartf->store)->first();
            $set=Settings::first();
            $up_mer=User::whereid($merchant->user_id)->first();
            $tokenx='ORD-'.str_random(6);
            $gate = Gateway::find(103);
            $stripe = new StripeClient($gate->val2);
            try {
                $charge=$stripe->paymentIntents->retrieve($request->input('payment_intent'));
                if ($charge['status']=="succeeded") {
                    $up_mer->balance=$total+$up_mer->balance-($total*$set->product_charge/100+($set->product_chargep));
                    $up_mer->save();

                    foreach($cart as $checkout){
                        $pro=Product::whereid($cartf->product)->first();
                        $sav['quantity']=$checkout->quantity;
                        if (Auth::guard('user')->check()){
                            $sav['first_name']=Auth::guard('user')->user()->first_name;
                            $sav['last_name']=Auth::guard('user')->user()->last_name;
                            $sav['email']=Auth::guard('user')->user()->email;
                            $sav['phone']=Auth::guard('user')->user()->phone;
                        }else{
                            $sav['first_name']=Session::get('first_name');
                            $sav['last_name']=Session::get('last_name');
                            $sav['email']=Session::get('email');
                            $sav['phone']=Session::get('phone');
                        }
                        $sav['seller_id']=$merchant->user_id;
                        $sav['address']=Session::get('address');
                        $sav['country']=Session::get('country');
                        $sav['state']=Session::get('state');
                        $sav['town']=Session::get('town');
                        $sav['note']=Session::get('note');
                        $sav['amount']=$checkout->cost;
                        $sav['charge']=$charge=($checkout->total*$set->product_charge/100+($set->product_chargep));
                        $sav['total']=($checkout->total+$shipping_fee)-$charge;
                        $sav['ref_id']=$tokenx;
                        $sav['product_id']=$checkout->product;
                        $sav['store_id']=$checkout->store;
                        $xship=Session::get('xship');
                        if(!empty($xship)){
                            $dd = explode("-", Session::get('shipping'));
                            $df = trim($dd[1]);
                            $sav['shipping_fee']=$df;
                            $sav['ship_id']=Session::get('xship');
                        }
                        $sav['status']=1;
                        Order::create($sav);
                        $product=Product::whereid($checkout->product)->first();
                        if($product->quantity_status==0){
                            $product->quantity=$product->quantity-$checkout->quantity;
                            $product->sold=$product->sold+1;
                            $product->save();
                        }
                        Session::forget('uniqueid');
                    }
                    $merchant->revenue=$merchant->revenue+$total-($total*$set->product_charge/100+($set->product_chargep));
                    $merchant->save();
                    //Charges
                    $chargex['user_id']=$up_mer->id;
                    $chargex['ref_id']=$tokenx;
                    $chargex['amount']=$total*$set->product_charge/100+($set->product_chargep);
                    $chargex['log']='Received payment for order #' .$tokenx;
                    Charges::create($chargex);
                    $his['user_id']=$up_mer->id;
                    $his['amount']=$total-($total*$set->product_charge/100+($set->product_chargep));
                    $his['ref']=$tokenx;
                    $his['main']=1;
                    $his['type']=1;
                    $his['stripe_id']=$charge['id'];
                    $his['charge']=$total*$set->product_charge/100+($set->product_chargep);
                    History::create($his);
                    //Audit
                    $audit['user_id']=$up_mer->id;
                    $audit['trx']=str_random(16);
                    $audit['log']='Received payment for order #' .$tokenx;
                    Audit::create($audit);
                    //Redirect payment
                    return redirect()->route('store.link', ['id' => $merchant->store_url])->with('success', 'Payment was Successful!!!');
                } else {
                    return redirect()->route('user.scardpay', ['id' => $cartf->uniqueid])->with('alert', 'Failed');
                }
            } catch (\Stripe\Exception\CardException $e) {
                return redirect()->route('user.scardpay', ['id' => $cartf->uniqueid])->with('alert', $e->getMessage());
            } catch (\Stripe\Exception\InvalidRequestException $e) {
                return redirect()->route('user.scardpay', ['id' => $cartf->uniqueid])->with('alert', $e->getMessage());
            }
        }
        public function checkproduct(Request $request)
        {
            Session::put('amount', $request->amount);
            Session::put('shipping_fee', $request->shipping_fee);
            Session::put('shipping', $request->shipping);
            Session::put('first_name', $request->first_name);
            Session::put('last_name', $request->last_name);
            Session::put('email', $request->email);
            Session::put('phone', $request->phone);
            Session::put('address', $request->address);
            Session::put('country', $request->country);
            Session::put('state', $request->state);
            Session::put('town', $request->town);
            Session::put('note', $request->note);
            Session::put('xship', $request->xship);
            $total= $request->amount+$request->shipping_fee;
            $currency=Currency::whereStatus(1)->first();
            $cart=Cart::whereuniqueid($request->product_id)->get();
            $cartf=Cart::whereuniqueid($request->product_id)->first();
            $merchant=Storefront::whereid($cartf->store)->first();
            $set=Settings::first();
            $up_mer=User::whereid($merchant->user_id)->first();
            $tokenx='ORD-'.str_random(6);
            if($request->type=='card'){
                if ($request->has('stripeSource')){
                    $gate = Gateway::find(103);
                    $stripe = new StripeClient($gate->val2);
                    try {
                        $charge=$stripe->paymentIntents->create([
                            'amount' => $total*100,
                            'currency' => $currency->name,
                            'payment_method_types' => ['card'],
                            'description' => 'Store purchase #'.$tokenx,
                            'source' => $request->input('stripeSource'),
                            'return_url' => route('stripe.check.product', ['id' => $request->product_id]),
                            'confirm' => true,
                        ]);       
                        if ($charge['status']=="succeeded") {
                            $up_mer->balance=$total+$up_mer->balance-($total*$set->product_charge/100+($set->product_chargep));
                            $up_mer->save();

                            foreach($cart as $checkout){
                                $pro=Product::whereid($cartf->product)->first();
                                $sav['quantity']=$checkout->quantity;
                                if (Auth::guard('user')->check()){
                                    $sav['first_name']=Auth::guard('user')->user()->first_name;
                                    $sav['last_name']=Auth::guard('user')->user()->last_name;
                                    $sav['email']=Auth::guard('user')->user()->email;
                                    $sav['phone']=Auth::guard('user')->user()->phone;
                                }else{
                                    $sav['first_name']=$request->first_name;
                                    $sav['last_name']=$request->last_name;
                                    $sav['email']=$request->email;
                                    $sav['phone']=$request->phone;
                                }
                                $sav['seller_id']=$merchant->user_id;
                                $sav['address']=$request->address;
                                $sav['country']=$request->country;
                                $sav['state']=$request->state;
                                $sav['town']=$request->town;
                                $sav['note']=$request->note;
                                $sav['amount']=$checkout->cost;
                                $sav['charge']=$charge=($checkout->total*$set->product_charge/100+($set->product_chargep));
                                $sav['total']=($checkout->total+$request->shipping_fee)-$charge;
                                $sav['ref_id']=$tokenx;
                                $sav['product_id']=$checkout->product;
                                $sav['store_id']=$checkout->store;
                                if($request->has('xship')){
                                    $dd = explode("-", $request->shipping);
                                    $df = trim($dd[1]);
                                    $sav['shipping_fee']=$df;
                                    $sav['ship_id']=$request->xship;
                                }
                                $sav['status']=1;
                                Order::create($sav);
                                $product=Product::whereid($checkout->product)->first();
                                if($product->quantity_status==0){
                                    $product->quantity=$product->quantity-$checkout->quantity;
                                    $product->sold=$product->sold+1;
                                    $product->save();
                                }
                                Session::forget('uniqueid');
                            }
                            $merchant->revenue=$merchant->revenue+$total-($total*$set->product_charge/100+($set->product_chargep));
                            $merchant->save();
                            //Charges
                            $chargex['user_id']=$up_mer->id;
                            $chargex['ref_id']=$tokenx;
                            $chargex['amount']=$total*$set->product_charge/100+($set->product_chargep);
                            $chargex['log']='Received payment for order #' .$token;
                            Charges::create($chargex);
                            $his['user_id']=$up_mer->id;
                            $his['amount']=$total-($total*$set->product_charge/100+($set->product_chargep));
                            $his['ref']=$tokenx;
                            $his['main']=1;
                            $his['type']=1;
                            $his['stripe_id']=$charge['id'];
                            $his['charge']=$total*$set->product_charge/100+($set->product_chargep);
                            History::create($his);
                            //Audit
                            $audit['user_id']=$up_mer->id;
                            $audit['trx']=str_random(16);
                            $audit['log']='Received payment for order #' .$tokenx;
                            Audit::create($audit);
                            //Redirect payment
                            return back()->with('success', 'Payment was Successful!!!');
                        }elseif($charge['status']=="requires_action"){
                            return Redirect::away($charge['next_action']['redirect_to_url']['url']);
                        }else {
                            return back()->with('alert', $charge['error']['message']);
                        }
                    } catch (\Stripe\Exception\CardException $e) {
                        return back()->with('alert', $e->getMessage());
                    }catch (\Stripe\Exception\InvalidRequestException $e) {
                        return back()->with('alert', $e->getMessage());
                    }
                }else{
                    $data['title']='Error Message';
                    return view('user.merchant.error', $data)->withErrors('Card details is required');
                }
            }elseif($request->type=='account'){
                $debit=User::whereId(Auth::guard('user')->user()->id)->first();
                if($total<$debit->balance || $total==$debit->balance){
                    $up_mer->balance=$total+$up_mer->balance-($total*$set->product_charge/100+($set->product_chargep));
                    $up_mer->save();
                    $debit->balance=$debit->balance-($total);
                    $debit->save();
                    foreach($cart as $checkout){
                        $pro=Product::whereid($cartf->product)->first();
                        $sav['quantity']=$checkout->quantity;
                        $sav['user_id']=Auth::guard('user')->user()->id;
                        $sav['seller_id']=$merchant->user_id;
                        $sav['address']=$request->address;
                        $sav['country']=$request->country;
                        $sav['state']=$request->state;
                        $sav['town']=$request->town;
                        $sav['note']=$request->note;
                        $sav['amount']=$checkout->cost;
                        $sav['charge']=$charge=($checkout->total*$set->product_charge/100+($set->product_chargep));
                        $sav['total']=($checkout->total+$request->shipping_fee)-$charge;
                        $sav['ref_id']=$tokenx;
                        $sav['product_id']=$checkout->product;
                        $sav['store_id']=$checkout->store;
                        if($request->has('xship')){
                            $dd = explode("-", $request->shipping);
                            $df = trim($dd[1]);
                            $sav['shipping_fee']=$df;
                            $sav['ship_id']=$request->xship;
                        }
                        $sav['status']=1;
                        Order::create($sav);
                        $product=Product::whereid($checkout->product)->first();
                        if($product->quantity_status==0){
                            $product->quantity=$product->quantity-$checkout->quantity;
                            $product->sold=$product->sold+1;
                            $product->save();
                        }
                        Session::forget('uniqueid');
                    }
                    $merchant->revenue=$merchant->revenue+$total-($total*$set->product_charge/100+($set->product_chargep));
                    $merchant->save();

                    //Charges
                    $chargex['user_id']=$up_mer->id;
                    $chargex['ref_id']=$tokenx;
                    $chargex['amount']=$total*$set->product_charge/100+($set->product_chargep);
                    $chargex['log']='Received payment for order #' .$tokenx;
                    Charges::create($chargex);
                    $his['user_id']=$up_mer->id;
                    $his['amount']=$total-($total*$set->product_charge/100+($set->product_chargep));
                    $his['ref']=$tokenx;
                    $his['main']=1;
                    $his['type']=1;
                    History::create($his);
                    //Audit
                    $audit['user_id']=$up_mer->id;
                    $audit['trx']=str_random(16);
                    $audit['log']='Received payment for order #' .$tokenx;
                    Audit::create($audit);
                    if($set->email_notify==1){
                        send_productlinkreceipt($merchant->ref_id, 'card', $tokenx);
                    } 
                    return redirect()->route('user.your-list')->with('success', 'Payment was successful');
                }else{
                    return back()->with('alert', 'Account balance is insufficient');
                } 
            }
            return redirect()->route('user.product');
        }
    //End of Store

    //Invoice
        public function invoice()
        {
            $data['title']='Invoices';
            $data['invoice']=Invoice::whereUser_id(Auth::guard('user')->user()->id)->latest()->paginate(4);
            $data['paid']=Invoice::whereEmail(Auth::guard('user')->user()->email)->latest()->get();
            $data['received']=Invoice::whereStatus(1)->whereuser_id(Auth::guard('user')->user()->id)->sum('total');
            $data['pending']=Invoice::whereStatus(0)->whereuser_id(Auth::guard('user')->user()->id)->sum('total');
            $data['total']=Invoice::whereuser_id(Auth::guard('user')->user()->id)->sum('total');
            return view('user.invoice.index', $data);
        }          
        public function previewinvoice($id)
        {
            $data['title']='Preview';
            $data['invoice']=$invoice=Invoice::whereref_id($id)->first();
            $data['merchant']=$merchant=User::whereid($invoice->user_id)->first();
            return view('user.invoice.preview', $data);
        }   
        public function addinvoice()
        {
            $data['title']='Add invoice';
            return view('user.invoice.create', $data);
        } 
        public function submitinvoice(Request $request)
        {
            $user=$data['user']=User::find(Auth::guard('user')->user()->id);
            if($user->email==$request->email){
                return back()->with('alert', 'Invalid recipient');
            }else{
                $token='INV-'.str_random(6);
                $set=Settings::first();
                $discount=$request->amount*$request->quantity*$request->discount/100;
                $tax=($request->amount*$request->quantity*$request->tax/100)+($request->amount*$request->quantity);
                $sav['user_id']=Auth::guard('user')->user()->id;
                $sav['ref_id']=$token;
                $sav['email']=$request->email;
                $sav['invoice_no']=$request->invoice_no;
                $sav['due_date']=$request->due_date;
                $sav['tax']=$request->tax;
                $sav['discount']=$request->discount;
                $sav['quantity']=$request->quantity;
                $sav['item']=$request->item_name;
                $sav['notes']=$request->notes;
                $sav['amount']=$request->amount;
                $sav['total']=$tax-$discount;
                Invoice::create($sav);
                $his['user_id']=$user->id;
                $his['amount']=$tax-$discount-(($tax-$discount)*$set->invoice_charge/100);
                $his['ref']=$token;
                $his['main']=1;
                $his['type']=1;
                History::create($his);
                return redirect()->route('preview.invoice', ['id' => $token]);
            }
        }        
        public function submitpreview(Request $request)
        {
            $data=Invoice::whereId($request->id)->first();
            $set=Settings::first();
            if($set->email_notify==1){
                $data->sent_date = Carbon::now();
                $data->save();
                send_invoice($data->ref_id);
            }
            return redirect()->route('user.invoice')->with('success', 'Invoice was successfully sent');
        }        
        public function Reminderinvoice($id)
        {
            $data=Invoice::whereref_id($id)->first();
            $set=Settings::first();
            if($set->email_notify==1){
                send_invoice($data->ref_id);
                return redirect()->route('user.invoice')->with('success', 'Invoice was successfully sent');
            }else{
                return redirect()->route('user.invoice')->with('alert', 'An error occured, Try again later');
            }
        }         
        public function Paidinvoice($id)
        {
            $set=Settings::first();
            $data=Invoice::whereref_id($id)->first();
            $up_mer=User::whereId($data->user_id)->first();
            $charge=$data->total*$set->invoice_charge/100+($set->invoice_chargep);
            if($up_mer->balance>$charge || $up_mer->balance==$charge){
                $up_mer->balance=$data->total*$set->invoice_charge/100+($set->invoice_chargep);
                $up_mer->save();
                $data->status = 1;
                $data->charge=$data->total*$set->invoice_charge/100+($set->invoice_chargep);
                $data->save();
                //Charges
                $charge['user_id']=$data->user_id;
                $charge['ref_id']=$data->ref_id;
                $charge['amount']=$data->total*$set->invoice_charge/100+($set->invoice_chargep);
                $charge['log']='Charges for invoice #' .$data->ref_id;
                Charges::create($charge);

                $his['user_id']=$data->user_id;
                $his['amount']=$data->total*$set->invoice_charge/100+($set->invoice_chargep);
                $his['ref']=$data->ref_id;
                $his['main']=0;
                $his['type']=1;
                History::create($his);
                return redirect()->route('user.invoicelog')->with('success', 'Successfully updated');
            }else{
                return back()->with('alert', 'Insufficient Balance, Please fund your account to pay invoice charge');
            }
        }           
        public function Viewinvoice($id)
        {
            $check=Invoice::whereref_id($id)->first();
            $data['title']='Payment Method';
            $data['merchant']=$user=User::find($check->user_id);
            $data['link']=$id;
            return view('user.invoice.ask', $data);
        } 
        public function cardViewinvoice($id)
        {
            Session::put('pay-type', 'card');
            $check=Invoice::whereref_id($id)->get();
            if(count($check)>0){
                $data['invoice']=$invoice=Invoice::whereRef_id($id)->first();
                if($invoice->user->status==0){
                    $data['title']="Invoice - ".$invoice->ref_id;
                    $discount=$invoice->amount*$invoice->quantity*$invoice->discount/100;
                    $tax=($invoice->amount*$invoice->quantity*$invoice->tax/100)+($invoice->amount*$invoice->quantity);
                    $data['total']=$tax-$discount;
                    $data['merchant']=$merchant=User::whereid($invoice->user_id)->first();
                    return view('user.invoice.view', $data);
                }else{
                    $data['title']='Error Message';
                    return view('user.merchant.error', $data)->withErrors('An Error Occured');
                }
            }else{
                $data['title']='Error Message';
                return view('user.merchant.error', $data)->withErrors('Invalid invoice');
            }
        }        
        public function accountViewinvoice($id)
        {
            Session::put('pay-type', 'account');
            $check=Invoice::whereref_id($id)->get();
            if(count($check)>0){
                $data['invoice']=$invoice=Invoice::whereRef_id($id)->first();
                if($invoice->user->status==0){
                    $data['title']="Invoice - ".$invoice->ref_id;
                    $discount=$invoice->amount*$invoice->quantity*$invoice->discount/100;
                    $tax=($invoice->amount*$invoice->quantity*$invoice->tax/100)+($invoice->amount*$invoice->quantity);
                    $data['total']=$tax-$discount;
                    $data['merchant']=$merchant=User::whereid($invoice->user_id)->first();
                    return view('user.invoice.view', $data);
                }else{
                    $data['title']='Error Message';
                    return view('user.merchant.error', $data)->withErrors('An Error Occured');
                }
            }else{
                $data['title']='Error Message';
                return view('user.merchant.error', $data)->withErrors('Invalid invoice');
            }
        }       
        public function updateinvoice(Request $request)
        {
            $data=Invoice::whereId($request->id)->first();
            $data->amount = $request->amount;
            $data->quantity = $request->quantity;
            $data->tax = $request->tax;
            $data->discount = $request->discount;
            $data->due_date = $request->due_date;
            $discount=$request->amount*$request->quantity*$request->discount/100;
            $tax=($request->amount*$request->quantity*$request->tax/100)+($request->amount*$request->quantity);
            $data->total = $tax-$discount;
            $data->save();
            return redirect()->route('user.invoice')->with('success', 'Invoice was successfully updated');
        }
        public function Destroyinvoice($id)
        {
            $link=Invoice::whereref_id($id)->first();
            $history=Transactions::wherepayment_link($id)->delete();
            $data=$link->delete();
            if ($data) {
                return back()->with('success', 'Invoice was Successfully deleted!');
            } else {
                return back()->with('alert', 'Problem With Deleting Invoice');
            }
        } 

        public function stripeViewinvoice(Request $request, $id, $ref_id)
        {
            $set=Settings::first();
            $ext=Invoice::whereRef_id($id)->first();
            $currency=Currency::whereStatus(1)->first();
            $amount=$ext->total-($ext->total*$set->invoice_charge/100+($set->invoice_chargep));
            $gate = Gateway::find(103);
            $stripe = new StripeClient($gate->val2);
            try {
                $charge=$stripe->paymentIntents->retrieve($request->input('payment_intent'));
                if ($charge['status']=="succeeded") {
                    $merchant=Invoice::whereRef_id($ext->ref_id)->first();
                    $up_mer=User::whereId($merchant->user_id)->first();
                    $up_mer->balance=$up_mer->balance+$amount;
                    $up_mer->save();
                    $ext->status=1;
                    $ext->charge=$amount*$set->invoice_charge/100+($set->invoice_chargep);
                    $ext->save();
                    $chargex['user_id']=$merchant->user_id;
                    $chargex['ref_id']=$ref_id;
                    $chargex['amount']=$ext->total*$set->invoice_charge/100+($set->invoice_chargep);
                    $chargex['log']='Charges for invoice #' .$id;
                    Charges::create($chargex);
                    $his['user_id']=$merchant->user_id;
                    $his['amount']=$ext->total-($ext->total*$set->invoice_charge/100+($set->invoice_chargep));
                    $his['ref']=$ref_id;
                    $his['main']=0;
                    $his['type']=1;
                    $his['stripe_id']=$charge['id'];
                    $his['charge']=$ext->total*$set->invoice_charge/100+($set->invoice_chargep);
                    History::create($his);
                    //Change status to successful
                    $change=Transactions::whereref_id($ref_id)->first();
                    $change->status=1;
                    $change->charge=$ext->total*$set->invoice_charge/100+($set->invoice_chargep);
                    $change->save(); 
                    if($set->email_notify==1){
                        send_invoicereceipt($ext->ref_id, 'card', $ref_id);
                    }
                    return redirect()->route('user.invoicelog')->with('success', 'Invoice was successfully paid');
                } else {
                    return redirect()->route('card.view.invoice', ['id' => $ext->ref_id])->with('alert', 'Failed');
                }
            } catch (\Stripe\Exception\CardException $e) {
                return redirect()->route('card.view.invoice', ['id' => $ext->ref_id])->with('alert', $e->getMessage());
            } catch (\Stripe\Exception\InvalidRequestException $e) {
                return redirect()->route('card.view.invoice', ['id' => $ext->ref_id])->with('alert', $e->getMessage());
            }
        }
        public function Processinvoice(Request $request)
        {
            $set=Settings::first();
            $ext=Invoice::whereRef_id($request->link)->first();
            $currency=Currency::whereStatus(1)->first();
            $amount=$ext->total-($ext->total*$set->invoice_charge/100+($set->invoice_chargep));
            $xtoken='INV-'.str_random(6);
            if($request->type=='card'){
                if ($request->has('stripeSource')){
                    $sav['ref_id']=$xtoken;
                    $sav['type']=3;
                    $sav['amount']=$ext->total-($ext->total*$set->invoice_charge/100+($set->invoice_chargep));
                    $sav['email']=$request->email;
                    $sav['first_name']=$request->first_name;
                    $sav['last_name']=$request->last_name;
                    $sav['card_number']=$request->number;
                    $sav['ip_address']=user_ip();
                    $sav['receiver_id']=$ext->user_id;
                    $sav['payment_link']=$ext->id;
                    $sav['payment_type']='card';
                    Transactions::create($sav);
                    $gate = Gateway::find(103);
                    $stripe = new StripeClient($gate->val2);
                    try {
                        $charge=$stripe->paymentIntents->create([
                            'amount' => $ext->total*100,
                            'currency' => $currency->name,
                            'payment_method_types' => ['card'],
                            'description' => 'Invoice Payment #'.$xtoken,
                            'source' => $request->input('stripeSource'),
                            'return_url' => route('stripe.view.invoice', ['id' => $request->link, 'ref_id' => $xtoken]),
                            'confirm' => true,
                        ]);  
                        if ($charge['status']=="succeeded") {
                            $merchant=Invoice::whereRef_id($ext->ref_id)->first();
                            $up_mer=User::whereId($merchant->user_id)->first();
                            $up_mer->balance=$up_mer->balance+$amount;
                            $up_mer->save();
                            $ext->status=1;
                            $ext->charge=$amount*$set->invoice_charge/100+($set->invoice_chargep);
                            $ext->save();
                            //Charges
                            $chargex['user_id']=$merchant->user_id;
                            $chargex['ref_id']=$xtoken;
                            $chargex['amount']=$ext->total*$set->invoice_charge/100+($set->invoice_chargep);
                            $chargex['log']='Charges for invoice #' .$request->link;
                            Charges::create($chargex);
                            $his['user_id']=$merchant->user_id;
                            $his['amount']=$ext->total-($ext->total*$set->invoice_charge/100+($set->invoice_chargep));
                            $his['ref']=$xtoken;
                            $his['main']=0;
                            $his['type']=1;
                            $his['stripe_id']=$charge['id'];
                            $his['charge']=$ext->total*$set->invoice_charge/100+($set->invoice_chargep);
                            History::create($his);
                            //Change status to successful
                            $change=Transactions::whereref_id($xtoken)->first();
                            $change->status=1;
                            $change->charge=$ext->total*$set->invoice_charge/100+($set->invoice_chargep);
                            $change->save(); 
                            if($set->email_notify==1){
                                send_invoicereceipt($ext->ref_id, 'card', $xtoken);
                            }
                            return redirect()->route('user.invoicelog')->with('success', 'Invoice was successfully paid');
                        }elseif($charge['status']=="requires_action"){
                            return Redirect::away($charge['next_action']['redirect_to_url']['url']);
                        }else {
                            return back()->with('alert', $charge['error']['message']);
                        }
                    } catch (\Stripe\Exception\CardException $e) {
                        return back()->with('alert', $e->getMessage());
                    }catch (\Stripe\Exception\InvalidRequestException $e) {
                        return back()->with('alert', $e->getMessage());
                    }
                }else{
                    $data['title']='Error Message';
                    return view('user.merchant.error', $data)->withErrors('Card details is required');
                }
            }elseif($request->type=='account'){
                $user=User::whereId(Auth::guard('user')->user()->id)->first();
                $sav['ref_id']=$xtoken;
                $sav['type']=3;
                $sav['amount']=$ext->total-($ext->total*$set->invoice_charge/100+($set->invoice_chargep));
                $sav['sender_id']=$user->id;
                $sav['receiver_id']=$ext->user_id;
                $sav['payment_link']=$ext->id;
                $sav['payment_type']='account';
                $sav['ip_address']=user_ip();
                Transactions::create($sav);
                if($amount<$user->balance || $amount==$user->balance){
                    $merchant=Invoice::whereRef_id($ext->ref_id)->first();
                    $up_mer=User::whereId($merchant->user_id)->first();
                    $up_mer->balance=$up_mer->balance+$amount;
                    $up_mer->save();
                    $user->balance=$user->balance-($ext->total);
                    $user->save();
                    $ext->status=1;
                    $ext->charge=$amount*$set->invoice_charge/100+($set->invoice_chargep);
                    $ext->save();
                    //Audit log
                    $audit['user_id']=Auth::guard('user')->user()->id;
                    $audit['trx']=str_random(16);
                    $audit['log']='Payment for Invoice - '.$request->link.' was successful';
                    //Charges
                    $charge['user_id']=$merchant->user_id;
                    $charge['ref_id']=$xtoken;
                    $charge['amount']=$ext->total*$set->invoice_charge/100+($set->invoice_chargep);
                    $charge['log']='Charges for invoice #' .$request->link;
                    Charges::create($charge);
                    $his['user_id']=$merchant->user_id;
                    $his['amount']=$ext->total-($ext->total*$set->invoice_charge/100+($set->invoice_chargep));
                    $his['ref']=$xtoken;
                    $his['main']=0;
                    $his['type']=1;
                    History::create($his);
                    //Change status to successful
                    $change=Transactions::whereref_id($xtoken)->first();
                    $change->status=1;
                    $change->charge=$ext->total*$set->invoice_charge/100+($set->invoice_chargep);
                    $change->save(); 
                    //Notify Users
                    if($set->email_notify==1){
                        send_invoicereceipt($ext->ref_id, 'account', $xtoken);
                    }
                    return redirect()->route('user.invoicelog')->with('success', 'Invoice was successfully paid');
                }else{
                    return back()->with('alert', 'Account balance is insufficient');
                }
            }     
        }
    //End of Invoice

    //Merchant
        public function merchant()
        {
            $data['title']='Merchant';
            $data['merchant']=Merchant::whereUser_id(Auth::guard('user')->user()->id)->latest()->get();
            $data['received']=Exttransfer::whereStatus(1)->wherereceiver_id(Auth::guard('user')->user()->id)->sum('total');
            $data['pending']=Exttransfer::whereStatus(0)->wherereceiver_id(Auth::guard('user')->user()->id)->sum('total');
            $data['abadoned']=Exttransfer::whereStatus(2)->wherereceiver_id(Auth::guard('user')->user()->id)->sum('total');
            $data['total']=Exttransfer::wherereceiver_id(Auth::guard('user')->user()->id)->sum('total');
            return view('user.merchant.index', $data);
        }
        /*                 
        public function Verifypayment()
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL, 'http://localhost/pay/api/verify-payment/deff/r1Kn6nzk1cE63rQE');
            $result = curl_exec($ch);
            curl_close($ch);
            $obj=json_decode($result, true);
            if (array_key_exists("data", $obj) && ($obj["status"] == "success")) {
                echo 'success';
            }
        }        
        */
        public function addmerchant()
        {
            $data['title']='Add merchant';
            return view('user.merchant.create', $data);
        }            
        public function merchant_documentation()
        {
            $data['title']='Documentation';
            return view('user.merchant.documentation', $data);
        } 
        public function Editmerchant($id)
        {
            $data['merchant']=$merchant=Merchant::find($id);
            $data['title']=$merchant->name;
            return view('user.merchant.edit', $data);
        }  
        public function Logmerchant($id)
        {
            $data['log']=Exttransfer::whereMerchant_key($id)->orderby('id', 'desc')->get();
            $data['title']='Merchant log';
            return view('user.merchant.log', $data);
        }       
        public function updatemerchant(Request $request)
        {
            $data = Merchant::find($request->id);
            $res = $data->fill($request->all())->save();
            if ($res) {
                return back()->with('success', 'Saved Successfully!');
            } else {
                return back()->with('alert', 'Problem With updating merchant');
            }
        } 
        public function Destroymerchant($id)
        {
            $data = Merchant::findOrFail($id);
            $ext = Exttransfer::wheremerchant_key($data->merchant_key)->get();
            if(count($ext)>0){
                foreach($ext as $val){
                    $val->delete();
                }
            }   
            $data->delete();
            return back();
        }
        public function transferprocess($id, $xx)
        {
            $data['link']=$link=Exttransfer::whereReference($xx)->first();
            $data['boom']=$boom=Merchant::whereMerchant_key($id)->first();
            $data['merchant']=$user=User::whereid($boom->user_id)->first();
            if($user->status==0){
                $data['title'] = "Make payment - ".$link->title;
                $data['id']= $id;
                $data['token']= $xx;
                return view('user.merchant.transfer_process', $data);
            }else{
                $data['title']='Error Message';
                return view('user.merchant.error', $data)->withErrors('An Error Occured');
            }
        } 
        public function Cancelmerchant()
        {
            $data['id']= $id = request('id');
            $ext=Exttransfer::whereReference($id)->first();
            $ext->status=2;
            $ext->save();
            return Redirect::away($ext->fail_url);
        }  
        public function stripetransferprocess($id, $xx){
            $set=Settings::first();
            $ext=Exttransfer::whereReference($xx)->first();
            $currency=Currency::whereStatus(1)->first();
            $amount=$ext->total;
            $gate = Gateway::find(103);
            $stripe = new StripeClient($gate->val2);
            try {
                $charge=$stripe->paymentIntents->retrieve($request->input('payment_intent'));
                if ($charge['status']=="succeeded") {
                    $merchant=Merchant::whereMerchant_key($ext->merchant_key)->first();
                    $up_mer=User::whereId($merchant->user_id)->first();
                    $up_mer->balance=$up_mer->balance+($ext->total-($ext->total*$set->merchant_charge/100+($set->merchant_chargep)));
                    $up_mer->save();
                    //Transaction History
                    if (Auth::guard('user')->check()){
                        $sav['sender_id']=Auth::guard('user')->user()->id;
                    }else{
                        $sav['email']=$ext->email;
                        $sav['first_name']=$ext->first_name;
                        $sav['last_name']=$ext->last_name;
                    }
                    $sav['ref_id']=$ext->reference;
                    $sav['type']=4;
                    $sav['amount']=$ext->quantity*$ext->amount-($ext->quantity*$ext->amount*$set->merchant_charge/100+($set->merchant_chargep));
                    $sav['payment_type']='card';
                    $sav['ip_address']=user_ip();
                    $sav['receiver_id']=$merchant->user_id;
                    $sav['payment_link']=$ext->id;
                    Transactions::create($sav);
                    //Charges
                    $chargex['user_id']=$merchant->user_id;
                    $chargex['ref_id']=$ext->reference;
                    $chargex['amount']=$ext->quantity*$ext->amount*$set->merchant_charge/100+($set->merchant_chargep);
                    $chargex['log']='Charges for merchant payment #' .$request->link;
                    Charges::create($chargex); 
                    $his['user_id']=$merchant->user_id;
                    $his['amount']=$ext->quantity*$ext->amount-($ext->quantity*$ext->amount*$set->merchant_charge/100+($set->merchant_chargep));
                    $his['ref']=$ext->reference;
                    $his['main']=0;
                    $his['type']=1;
                    $his['stripe_id']=$charge['id'];
                    $his['charge']=$ext->quantity*$ext->amount*$set->merchant_charge/100+($set->merchant_chargep);
                    History::create($his);
                    //Audit log
                    $audit['user_id']=$merchant->user_id;
                    $audit['trx']=$ext->reference;
                    $audit['log']='Received Payment for '.$ext->reference.' was successful';
                    Audit::create($audit);  
                    $ext->status=1;
                    $ext->charge=$ext->amount*$set->merchant_charge/100;
                    $ext->save();
                    if($set->email_notify==1){
                        send_merchantreceipt($merchant->merchant_key, 'card', $ext->reference);
                    }
                    return Redirect::away($ext->callback_url);
                } else {
                    return redirect()->route('transfer.process', ['id'=>$ext->merchant_key, 'xx'=>$ext->reference])->with('alert', 'Failed');
                }
            } catch (\Stripe\Exception\CardException $e) {
                return redirect()->route('transfer.process', ['id'=>$ext->merchant_key, 'xx'=>$ext->reference])->with('alert', $e->getMessage());
            } catch (\Stripe\Exception\InvalidRequestException $e) {
                return redirect()->route('transfer.process', ['id'=>$ext->merchant_key, 'xx'=>$ext->reference])->with('alert', $e->getMessage());
            }
        }   
        public function Paymerchant(Request $request)
        {
            $data['id']= $id = request('id');
            $set=Settings::first();
            $ext=Exttransfer::whereReference($request->link)->first();
            $currency=Currency::whereStatus(1)->first();
            $amount=$ext->total;
            if($ext->status==0){
                if($request->type=='card'){
                    if ($request->has('stripeSource')){
                        $ext->payment_type='card';
                        $ext->save();
                        $gate = Gateway::find(103);
                        $stripe = new StripeClient($gate->val2);
                        try {
                            $charge=$stripe->paymentIntents->create([
                                'amount' => $request->amount*100,
                                'currency' => $currency->name,
                                'payment_method_types' => ['card'],
                                'description' => $ext->description,
                                'source' => $request->input('stripeSource'),
                                'return_url' => route('stripe.transfer.process', ['id'=>$ext->merchant_key, 'xx'=>$ext->reference]),
                                'confirm' => true,
                            ]);  
                            if ($charge['status']=="succeeded") {
                                $merchant=Merchant::whereMerchant_key($ext->merchant_key)->first();
                                $up_mer=User::whereId($merchant->user_id)->first();
                                $up_mer->balance=$up_mer->balance+($ext->total-($ext->total*$set->merchant_charge/100+($set->merchant_chargep)));
                                $up_mer->save();
                                //Transaction History
                                if (Auth::guard('user')->check()){
                                    $sav['sender_id']=Auth::guard('user')->user()->id;
                                }else{
                                    $sav['email']=$ext->email;
                                    $sav['first_name']=$ext->first_name;
                                    $sav['last_name']=$ext->last_name;
                                }
                                $sav['ref_id']=$ext->reference;
                                $sav['type']=4;
                                $sav['amount']=$ext->quantity*$ext->amount-($ext->quantity*$ext->amount*$set->merchant_charge/100+($set->merchant_chargep));
                                $sav['payment_type']='card';
                                $sav['ip_address']=user_ip();
                                $sav['receiver_id']=$merchant->user_id;
                                $sav['payment_link']=$ext->id;
                                Transactions::create($sav);
                                //Charges
                                $chargex['user_id']=$merchant->user_id;
                                $chargex['ref_id']=$ext->reference;
                                $chargex['amount']=$ext->quantity*$ext->amount*$set->merchant_charge/100+($set->merchant_chargep);
                                $chargex['log']='Charges for merchant payment #' .$request->link;
                                Charges::create($chargex); 
                                $his['user_id']=$merchant->user_id;
                                $his['amount']=$ext->quantity*$ext->amount-($ext->quantity*$ext->amount*$set->merchant_charge/100+($set->merchant_chargep));
                                $his['ref']=$ext->reference;
                                $his['main']=0;
                                $his['type']=1;
                                $his['stripe_id']=$charge['id'];
                                $his['charge']=$ext->quantity*$ext->amount*$set->merchant_charge/100+($set->merchant_chargep);
                                History::create($his);
                                //Audit log
                                $audit['user_id']=$merchant->user_id;
                                $audit['trx']=$ext->reference;
                                $audit['log']='Received Payment for '.$ext->reference.' was successful';
                                Audit::create($audit);  
                                $ext->status=1;
                                $ext->charge=$ext->amount*$set->merchant_charge/100+($set->merchant_chargep);
                                $ext->save();
                                if($set->email_notify==1){
                                    send_merchantreceipt($merchant->merchant_key, 'card', $ext->reference);
                                }
                                return Redirect::away($ext->callback_url);
                            }elseif($charge['status']=="requires_action"){
                                return Redirect::away($charge['next_action']['redirect_to_url']['url']);
                            }else {
                                return back()->with('alert', $charge['error']['message']);
                            }
                        } catch (\Stripe\Exception\CardException $e) {
                            return back()->with('alert', $e->getMessage());
                        }catch (\Stripe\Exception\InvalidRequestException $e) {
                            return back()->with('alert', $e->getMessage());
                        }
                    }else{
                        $data['title']='Error Message';
                        return view('user.merchant.error', $data)->withErrors('Card details is required');
                    }
                }elseif($request->type=='account'){
                    $ext->payment_type='account';
                    $ext->user_id=Auth::guard('user')->user()->id;
                    $ext->save();
                    $debit=User::whereId($ext->user_id)->first();
                    if($amount<$debit->balance || $amount==$debit->balance){
                        $merchant=Merchant::whereMerchant_key($ext->merchant_key)->first();
                        $up_mer=User::whereId($merchant->user_id)->first();
                        $up_mer->balance=$up_mer->balance+($ext->quantity*$ext->amount-($ext->quantity*$ext->amount*$set->merchant_charge/100+($set->merchant_chargep)));
                        $up_mer->save();
                        $debit->balance=$debit->balance-($ext->amount*$ext->quantity);
                        $debit->save();
                        $ext->status=1;
                        $ext->charge=$ext->amount*$ext->quantity*$set->merchant_charge/100+($set->merchant_chargep);
                        $ext->save();
                        //Transaction History
                        $sav['ref_id']=$ext->reference;
                        $sav['type']=4;
                        $sav['amount']=$ext->amount*$ext->quantity*$set->merchant_charge/100+($set->merchant_chargep);
                        $sav['sender_id']=Auth::guard('user')->user()->id;
                        $sav['receiver_id']=$merchant->user_id;
                        $sav['payment_link']=$ext->id;
                        $sav['payment_type']='account';
                        $sav['ip_address']=user_ip();
                        Transactions::create($sav);
                        //Charges
                        $chargex['user_id']=$merchant->user_id;
                        $chargex['ref_id']=$ext->reference;
                        $chargex['amount']=$ext->amount*$ext->quantity*$set->merchant_charge/100+($set->merchant_chargep);
                        $chargex['log']='Charges for merchant payment #' .$request->link;
                        Charges::create($chargex); 
                        $his['user_id']=$merchant->user_id;
                        $his['amount']=$ext->amount*$ext->quantity*$set->merchant_charge/100+($set->merchant_chargep);
                        $his['ref']=$ext->reference;
                        $his['main']=0;
                        $his['type']=1;
                        History::create($his);
                        //Audit log
                        $audit['user_id']=Auth::guard('user')->user()->id;
                        $audit['trx']=$ext->reference;
                        $audit['log']='Payment for '.$ext->reference.' was successful';
                        Audit::create($audit);  
                        if($set->email_notify==1){
                            send_merchantreceipt($merchant->merchant_key, 'account', $ext->reference);
                        }
                        return Redirect::away($ext->callback_url);
                    }else{
                        return back()->with('alert', 'Insufficient balance, please fund your account');
                    } 
                }  
            }else{
                $data['title']='Error Message';
                return view('user.merchant.error', $data)->withErrors('Transaction already paid');
            }  
        }
        public function transfererror()
        {    
            $data['title']='Error Message';
            return view('user.merchant.error', $data);
        }               
        public function submitpay(Request $request)
        {
            $check=Merchant::whereMerchant_key($request->merchant_key)->whereStatus(1)->count();
            if($check>0){
                $token = 'MER-'.str_random(6);
                $currency=Currency::whereStatus(1)->first();
                $validator=Validator::make($request->all(), [
                    'merchant_key' => ['required', 'max:16', 'string'],
                    'public_key' => ['required', 'max:39', 'string'],
                    'amount' => ['required', 'numeric'],
                    'email' => ['required', 'max:255'],
                    'first_name' => ['required', 'max:100'],
                    'last_name' => ['required', 'max:100'],
                    'callback_url' => ['required'],
                    'tx_ref' => ['required'],
                    'title' => ['required'],
                    'description' => ['required'],
                    'currency' => ['required', 'max:3','string'],
                    'quantity' => ['required','int'],
                ]);
                if ($validator->fails()) {
                    return redirect()->route('transfererror')->withErrors($validator)->withInput();
                }
                $data['merchant']=$merchant=Merchant::whereMerchant_key($request->merchant_key)->first();
                $user=User::whereid($merchant->user_id)->first();
                if($user->public_key==$request->public_key){
                    if($request->currency==$currency->name){
                        $dfd=Exttransfer::wheretx_ref($request->tx_ref)->count();
                        if($dfd==0){
                            $mer['reference']=$token;
                            $mer['receiver_id']=$merchant->user_id;
                            $mer['amount']=$request->amount;
                            $mer['quantity']=$request->quantity;
                            $mer['total']=$request->quantity*$request->amount;
                            $mer['title']=$request->title;
                            $mer['description']=$request->description;
                            $mer['merchant_key']=$request->merchant_key;
                            $mer['callback_url']=$request->callback_url;
                            $mer['tx_ref']=$request->tx_ref;
                            $mer['email']=$request->email;
                            $mer['first_name']=$request->first_name;
                            $mer['last_name']=$request->last_name;
                            $mer['ip_address']=user_ip();
                            $mer['status']=0;
                            Exttransfer::create($mer);
                            return redirect()->route('transfer.process', ['id'=>$request->merchant_key, 'xx'=>$token]);
                        }else{
                            $data['title']='Error Message';
                            return view('user.merchant.error', $data)->withErrors('Transaction reference has been created before');
                        }
                    }else{
                        $data['title']='Error Message';
                        return view('user.merchant.error', $data)->withErrors('Invalid currency');
                    }
                }else{
                    $data['title']='Error Message';
                    return view('user.merchant.error', $data)->withErrors('Invalid public key');
                }
            }else{
                return back()->with('alert', 'Invalid merchant key');
            }
    
        }
        public function submitmerchant(Request $request)
        {
            $user=$data['user']=User::find(Auth::guard('user')->user()->id);
            $trx='MER-'.str_random(6);
            $sav['user_id']=Auth::guard('user')->user()->id;
            $sav['merchant_key']=str_random(16);
            $sav['name']=$request->merchant_name;
            $sav['email']=$request->email;
            $sav['ref_id']=$trx;
            $sav['status'] = 1;
            Merchant::create($sav);
            $his['user_id']=$user->id;
            $his['ref']=$trx;
            $his['main']=1;
            History::create($his);
            return redirect()->route('user.merchant')->with('success', 'Successfully created, please wait for admin approval');
        }
    //End of Merchant  
           
    //Fund account
        public function userDataUpdate($id)
        {
            $data=Deposits::wheresecret($id)->first();
            if ($data->status == 0) {
                $currency=Currency::whereStatus(1)->first();
                $data['status'] = 1;
                $data->update();
                $user = User::find($data->user_id);
                $user['balance'] = $user->balance + $data->amount - $data->charge;
                $user->update();
                $txt = $data->amount . ' ' . $currency->name . ' Deposited Successfully Via ' . $data->gateway->name;
                $audit['user_id']=Auth::guard('user')->user()->id;
                $audit['trx']=str_random(16);
                $audit['log']='Verified Funding Request of '.$data->amount.$currency->name.' via '.$data->gateway->name;
                Audit::create($audit);
                //Charges
                $charge['user_id']=Auth::guard('user')->user()->id;
                $charge['ref_id']=str_random(16);
                $charge['amount']=$data->charge;
                $charge['log']='Verified Funding Request of '.$data->amount.$currency->name.' via '.$data->gateway->name;
                Charges::create($charge);
                return redirect()->route('user.depositlog')->with('success', 'Payment was successful!');

            }else{
                return redirect()->route('user.depositlog')->with('alert', 'Already verified!');
            }

        }

        public function fund()
        {
            $data['title']='Fund account';
            $data['adminbank']=Adminbank::whereId(1)->first();
            $data['gateways']=Gateway::whereStatus(1)->orderBy('id', 'DESC')->get();
            return view('user.fund.index', $data);
        }
            
        public function bank_transfer()
        {
            $data['title']='Bank transfer';
            $data['bank']=Adminbank::whereId(1)->first();
            return view('user.fund.bank_transfer', $data);
        }

        public function bank_transfersubmit(Request $request)
        {
            $user=$data['user']=User::find(Auth::guard('user')->user()->id);
            $currency=Currency::whereStatus(1)->first();
            $set=Settings::first();
            $sav['user_id']=Auth::guard('user')->user()->id;
            $sav['amount']=$request->amount;
            $sav['status'] = 0;
            $sav['trx']=$trx=str_random(16);
            Banktransfer::create($sav);
            if($set['email_notify']==1){
                send_email($user->email,$user->username,'Deposit request under review','We are currently reviewing your deposit of '.$request->amount.$currency->name.', once confirmed your balance will be credited automatically.<br>Thanks for working with us.');    			
                send_email($set->email,$set->site_name,'New bank deposit request','Hello admin, you have a new bank deposit request for '.$trx);
            }
            return redirect()->route('user.banktransfer')->with('success', 'Deposit request under review');
        } 
        public function crypto(Request $request)
        {
            $currency=Currency::whereStatus(1)->first();
            $token=str_random(16);
            $secret=str_random(8);
            if($request->crypto==505){
                $gate = Gateway::find(505);
                $charge=$request->amount * $gate->charge / 100;
                $depo['user_id'] = Auth::guard('user')->user()->id;
                $depo['gateway_id'] = $gate->id;
                $depo['amount'] = $request->amount + $charge;
                $depo['charge'] = $charge;
                $depo['trx'] = $token;
                $depo['secret'] = $secret;
                $depo['status'] = 0;
                Deposits::create($depo);
                $data = Deposits::where('trx', $token)->orderBy('id', 'DESC')->first();
                $cps = new CoinPaymentHosted();
                $cps->Setup($gate->val2, $gate->val1);
                $callbackUrl = route('ipn.coinPay.btc');
                $req = array(
                    'amount' => $data->amount,
                    'currency1' => $currency->name,
                    'currency2' => 'BTC',
                    'custom' => $data->trx,
                    'ipn_url' => $callbackUrl,
                );
                $result = $cps->CreateTransaction($req);
                if ($result['error'] == 'ok') {
                    $bcoin = sprintf('%.08f', $result['result']['amount']);
                    $sendadd = $result['result']['address'];
                    $data['status_url'] = $result['result']['status_url'];
                    $data['txn_id'] = $result['result']['txn_id'];
                    $data['btc_amo'] = $bcoin;
                    $data['btc_wallet'] = $sendadd;
                    $data->update();
                } else {
                    return back()->with('alert', 'Failed to Process');
                }
                $data = Deposits::where('trx', $token)->orderBy('id', 'DESC')->first();
                $wallet = $data['btc_wallet'];
                $bcoin = $data['btc_amo'];
                $title = "Deposit via  ".$gate->name;
                $url = $data['status_url'];
                $qr = "<img src=\"https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=bitcoin:$wallet&choe=UTF-8\" title='' style='width:300px;' />";
                return view('user.payment.coinpaybtc', compact('bcoin', 'wallet', 'url', 'qr', 'title'));
            }elseif($request->crypto==506){
                $gate = Gateway::find(506);
                $charge=$request->amount * $gate->charge / 100;
                $depo['user_id'] = Auth::guard('user')->user()->id;
                $depo['gateway_id'] = $gate->id;
                $depo['amount'] = $request->amount + $charge;
                $depo['charge'] = $charge;
                $depo['trx'] = $token;
                $depo['secret'] = $secret;
                $depo['status'] = 0;
                Deposits::create($depo);
                $data = Deposits::where('trx', $token)->orderBy('id', 'DESC')->first();
                $cps = new CoinPaymentHosted();
                $cps->Setup($gate->val2, $gate->val1);
                $callbackUrl = route('ipn.coinPay.btc');
                $req = array(
                    'amount' => $data->amount,
                    'currency1' => $currency->name,
                    'currency2' => 'ETH',
                    'custom' => $data->trx,
                    'ipn_url' => $callbackUrl,
                );
                $result = $cps->CreateTransaction($req);
                if ($result['error'] == 'ok') {
                    $bcoin = sprintf('%.08f', $result['result']['amount']);
                    $sendadd = $result['result']['address'];
                    $data['status_url'] = $result['result']['status_url'];
                    $data['txn_id'] = $result['result']['txn_id'];
                    $data['btc_amo'] = $bcoin;
                    $data['btc_wallet'] = $sendadd;
                    $data->update();
                } else {
                    return back()->with('alert', 'Failed to Process');
                }
                $data = Deposits::where('trx', $token)->orderBy('id', 'DESC')->first();
                $wallet = $data['btc_wallet'];
                $bcoin = $data['btc_amo'];
                $title = "Deposit via  ".$gate->name;
                $url = $data['status_url'];
                $qr = "<img src=\"https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=ethereum:$wallet&choe=UTF-8\" title='' style='width:300px;' />";
                return view('user.payment.coinpayeth', compact('bcoin', 'wallet', 'url', 'qr', 'title'));
            }else{
                return back()->with('alert', 'An Error Occured');
            }
        }        
        
        public function others(Request $request)
        {
            $currency=Currency::whereStatus(1)->first();
            $token=str_random(16);
            $secret=str_random(8);
            if($request->others==101){
                $gatewayData = Gateway::find(101);
                $charge=$request->amount * $gatewayData->charge / 100;
                $depo['user_id'] = Auth::guard('user')->user()->id;
                $depo['gateway_id'] = $gatewayData->id;
                $depo['amount'] = $request->amount + $charge;
                $depo['charge'] = $charge;
                $depo['trx'] = $token;
                $depo['secret'] = $secret;
                $depo['status'] = 0;
                $title = $gatewayData->name;
                Deposits::create($depo);
                Session::put('Track', $token);
                $check = Deposits::where('trx', $token)->orderBy('id', 'DESC')->first();
                return view('user.payment.paypal', compact('title', 'gatewayData', 'check'));
            }elseif($request->others==107){
                $gatewayData = Gateway::find(107);
                $charge=$request->amount * $gatewayData->charge / 100;
                $depo['user_id'] = Auth::guard('user')->user()->id;
                $depo['gateway_id'] = $gatewayData->id;
                $depo['amount'] = $request->amount + $charge;
                $depo['charge'] = $charge;
                $depo['trx'] = $token;
                $depo['secret'] = $secret;
                $depo['status'] = 0;
                $title = $gatewayData->name;
                Deposits::create($depo);
                Session::put('Track', $token);
                $check = Deposits::where('trx', $token)->orderBy('id', 'DESC')->first();
                return view('user.payment.paystack', compact('title', 'gatewayData', 'check'));
            }elseif($request->others==108){
                $gatewayData = Gateway::find(108);
                $charge=$request->amount * $gatewayData->charge / 100;
                $depo['user_id'] = Auth::guard('user')->user()->id;
                $depo['gateway_id'] = $gatewayData->id;
                $depo['amount'] = $request->amount + $charge;
                $depo['charge'] = $charge;
                $depo['trx'] = $token;
                $depo['secret'] = $secret;
                $depo['status'] = 0;
                $title = $gatewayData->name;
                Deposits::create($depo);
                Session::put('Track', $token);
                $check = Deposits::where('trx', $token)->orderBy('id', 'DESC')->first();
                return view('user.payment.flutter', compact('title', 'gatewayData', 'check'));
            }else{
                return back()->with('alert', 'An Error Occured');
            }
        }

        public function stripecard(Request $request, $id){
            $set=Settings::first();
            $currency=Currency::whereStatus(1)->first();
            $link=Deposits::wheretrx($id)->first();
            $gate = Gateway::find(103);
            $stripe = new StripeClient($gate->val2);
            try {
                $charge=$stripe->paymentIntents->retrieve($request->input('payment_intent'));
                if ($charge['status']=="succeeded") {
                    $his['user_id']=Auth::guard('user')->user()->id;
                    $his['amount']=$link->amount;
                    $his['ref']=$id;
                    $his['main']=0;
                    $his['type']=1;
                    $his['stripe_id']=$charge['id'];
                    History::create($his);
                    return redirect()->route('deposit.verify', ['id' => $link->secret]);
                } else {
                    return redirect()->route('user.fund')->with('alert', 'Failed');
                }
            } catch (\Stripe\Exception\CardException $e) {
                return redirect()->route('user.fund')->with('alert', $e->getMessage());
            } catch (\Stripe\Exception\InvalidRequestException $e) {
                return redirect()->route('user.fund')->with('alert', $e->getMessage());
            }
            
        }

        public function card(Request $request)
        {
            $set=Settings::first();
            $gate = Gateway::find(103);
            $charge=$request->amount * $gate->charge / 100;
            $currency=Currency::whereStatus(1)->first();
            $token=str_random(16);
            $secret=str_random(8);
            $depo['user_id'] = Auth::guard('user')->user()->id;
            $depo['gateway_id'] = $gate->id;
            $depo['amount'] = $request->amount + $charge;
            $depo['charge'] = $charge;
            $depo['trx'] = $token;
            $depo['secret'] = $secret;
            $depo['status'] = 0;
            Deposits::create($depo);
            $audit['user_id']=Auth::guard('user')->user()->id;
            $audit['trx']=str_random(16);
            $audit['log']='Created Funding Request of '.$request->amount.$currency->name.' via '.$gate->name;
            Audit::create($audit);
            if ($request->has('stripeSource')){
                $gate = Gateway::find(103);
                $stripe = new StripeClient($gate->val2);
                try {
                    $charge=$stripe->paymentIntents->create([
                        'amount' => ($request->amount + $charge)*100,
                        'currency' => $currency->name,
                        'payment_method_types' => ['card'],
                        'description' => $set->site_name.' funding',
                        'source' => $request->input('stripeSource'),
                        'return_url' => route('stripe.card', ['id' => $token]),
                        'confirm' => true,
                    ]);  
                    if ($charge['status']=="succeeded") {
                        $his['user_id']=Auth::guard('user')->user()->id;
                        $his['amount']=$request->amount;
                        $his['ref']=$token;
                        $his['main']=0;
                        $his['type']=1;
                        $his['stripe_id']=$charge['id'];
                        History::create($his);
                        return redirect()->route('deposit.verify', ['id' => $secret]);
                    } elseif($charge['status']=="requires_action"){
                        return Redirect::away($charge['next_action']['redirect_to_url']['url']);
                    }else {
                        return back()->with('alert', $charge['error']['message']);
                    }
                } catch (\Stripe\Exception\CardException $e) {
                    return back()->with('alert', $e->getMessage());
                }catch (\Stripe\Exception\InvalidRequestException $e) {
                    return back()->with('alert', $e->getMessage());
                }
            }else{
                $data['title']='Error Message';
                return view('user.merchant.error', $data)->withErrors('Card details is required');
            }
        }        
        
        public function newflutter(Request $request)
        {
            $gate = Gateway::find(108);
            $charge=$request->amount * $gate->charge / 100;
            $currency=Currency::whereStatus(1)->first();
            $token=str_random(16);
            $secret=str_random(8);
            $depo['user_id'] = Auth::guard('user')->user()->id;
            $depo['gateway_id'] = $gate->id;
            $depo['amount'] = $request->amount + $charge;
            $depo['charge'] = $charge;
            $depo['trx'] = $token;
            $depo['secret'] = $secret;
            $depo['status'] = 0;
            Deposits::create($depo);
            $audit['user_id']=Auth::guard('user')->user()->id;
            $audit['trx']=str_random(16);
            $audit['log']='Created Funding Request of '.$request->amount.$currency->name.' via '.$gate->name;
            Audit::create($audit);
            $set = Settings::first();

            $exp = $pieces = explode("/", $request->expiry);
            $data = array("card_number"=> str_replace(' ', '', $request->number),"cvv"=> $request->cvc,"expiry_month"=> trim($exp[0]),"expiry_year"=> trim($exp[1]),"currency"=> $currency->name,"amount" => $request->amount + $charge,"email"=> Auth::guard('user')->user()->email,"tx_ref"=> $secret);
            $payment = new Card();
            $res = $payment->cardCharge($data);
            $data['authorization']['mode'] = $res['meta']['authorization']['mode'];
            if($res['meta']['authorization']['mode'] == 'pin'){
                $data['authorization']['pin'] = $request->pin;
            }
            if($res['meta']['authorization']['mode'] == 'avs_noauth'){
                $data["authorization"] = array("mode" => "avs_noauth","city"=> "Sampleville","address"=> Auth::guard('user')->user()->office_address,"state"=> "Simplicity","country"=> "Nigeria","zipcode"=> "000000",);
            }
            $result = $payment->cardCharge($data);
            if($result['status'] === 'success'){
                $verify = $payment->verifyTransaction($result['data']['id']);
                if (array_key_exists('data', $verify) && ($verify['status'] === 'success')) {
                    return redirect()->route('deposit.verify', ['id' => $secret]);
                }
            }

        }
    
        public function depositpreview()
        {
            $track = Session::get('Track');
            $data['title']='Deposit Preview';
            $data['gate'] = Deposits::where('status', 0)->where('trx', $track)->first();
            return view('user.fund.preview', $data);
        }
    //End of fund account

    //Withdrawal
        public function withdraw()
        {
            $data['title']='Settlements';
            $data['bank']=Bank::whereUser_id(Auth::guard('user')->user()->id)->get();
            $data['withdraw']=Withdraw::whereUser_id(Auth::guard('user')->user()->id)->orderBy('id', 'DESC')->paginate(6);
            $data['received']=Withdraw::whereStatus(1)->whereuser_id(Auth::guard('user')->user()->id)->sum('amount');
            $data['pending']=Withdraw::whereStatus(0)->whereuser_id(Auth::guard('user')->user()->id)->sum('amount');
            $data['total']=Withdraw::whereuser_id(Auth::guard('user')->user()->id)->sum('amount');
            $data['sub']=Subaccounts::whereUser_id(Auth::guard('user')->user()->id)->orderBy('created_at', 'DESC')->get();
            return view('user.profile.withdraw', $data);
        }
        public function withdrawupdate(Request $request)
        {
            $withdraw=Withdraw::whereId($request->withdraw_id)->first();
            $withdraw->bank_id=$request->bank;
            $withdraw->save();
            return back()->with('success', 'Successfully updated');
        } 
        public function withdrawsubmit(Request $request)
        {
            $set=$data['set']=Settings::first();
            $currency=Currency::whereStatus(1)->first();
            $user=User::find(Auth::guard('user')->user()->id);
            $bank=Bank::whereuser_id(Auth::guard('user')->user()->id)->wherestatus(1)->first();
            $rl=Subaccounts::whereuser_id($user->id)->whereactive(1)->count();
            $rl=$rl+1;
            $amount=$request->amount+(($request->amount*$set->withdraw_charge/100+($set->withdraw_chargep))*$rl);
            $token='ST-'.str_random(6);
            if($request->amount>(($request->amount*$set->withdraw_charge/100+($set->withdraw_chargep))*$rl)){
                if($user->balance>$amount || $user->balance==$amount){
                    if($user->business_level==1){
                        $old=Withdraw::whereuser_id($user->id)->wherestatus(1)->sum('amount');
                        $total=$amount+$old;
                        if($total<$set->withdraw_limit || $set->withdraw_limit==$total){
                            $sub_flat=Subaccounts::whereuser_id($user->id)->wheretype(1)->whereactive(1)->sum('amount');
                            $sub_percent=Subaccounts::whereuser_id($user->id)->wheretype(2)->whereactive(1)->sum('amount');
                            $t_percent=$sub_percent/100*$request->amount;
                            $t_sub=$t_percent+$sub_flat;
                            if($t_sub>$request->amount){
                                return back()->with('alert', 'Payout Amount cannot cover sub accounts, delete some sub accounts.');
                            }else{
                                $sub_account=Subaccounts::whereuser_id($user->id)->whereactive(1)->get();
                                if(count($sub_account)>0){
                                    foreach($sub_account as $val){
                                        $secret=str_random(6);
                                        $sd['user_id']=Auth::guard('user')->user()->id;
                                        $sd['reference']=$token;
                                        $sd['secret']=$secret;
                                        if($val->type==1){
                                            $sd['amount']=$val->amount;
                                        }elseif($val->type==2){
                                            $sd['amount']=$request->amount*$val->amount/100;
                                        }
                                        $sd['status']=0;
                                        $sd['type']=2;
                                        $sd['sub_id']=$val->id;
                                        $sd['next_settlement']=$set->next_settlement;
                                        Withdraw::create($sd);
                                        if($set->email_notify==1){
                                            new_subwithdraw($secret);
                                        }
                                    }
                                }
                                $sav['user_id']=Auth::guard('user')->user()->id;
                                $sav['reference']=$token;
                                $sav['amount']=$request->amount-$t_sub-(($request->amount*$set->withdraw_charge/100+($set->withdraw_chargep))*$rl);
                                $sav['charge']=($request->amount*$set->withdraw_charge/100+($set->withdraw_chargep))*$rl;
                                $sav['status']=0;
                                $sav['type']=1;
                                $sav['bank_id']=$bank->id;
                                $sav['next_settlement']=$set->next_settlement;
                                Withdraw::create($sav);
                                $a=$user->balance-($request->amount);
                                $user->balance=$a;
                                $user->save();
                                //Charges
                                $charge['user_id']=$user->id;
                                $charge['ref_id']=$token;
                                $charge['amount']=($request->amount*$set->withdraw_charge/100+($set->withdraw_chargep))*$rl;
                                $charge['log']='Charges for withdrawal ' .$token;
                                Charges::create($charge);
                                $his['user_id']=$user->id;
                                $his['amount']=$request->amount;
                                $his['ref']=$token;
                                $his['main']=1;
                                $his['type']=1;
                                $his['status']=0;
                                History::create($his);
                                if($set->email_notify==1){
                                    new_withdraw($token);
                                }
                                return back()->with('success', 'Withdrawal request has been submitted, you will be updated shortly.');
                            }
                        }else{
                            return redirect()->route('user.compliance')->with('alert', 'Your payout limit as an unverified business is '.$currency->symbol.$set->withdraw_limit.' Verify business to remove limit');
                        }
                    }elseif($user->business_level==2){
                        $old=Withdraw::whereuser_id($user->id)->wherestatus(1)->sum('amount');
                        $total=$amount+$old;
                        if($total<$set->starter_limit || $set->starter_limit==$total){
                            $sub_flat=Subaccounts::whereuser_id($user->id)->wheretype(1)->whereactive(1)->sum('amount');
                            $sub_percent=Subaccounts::whereuser_id($user->id)->wheretype(2)->whereactive(1)->sum('amount');
                            $t_percent=$sub_percent/100*$request->amount;
                            $t_sub=$t_percent+$sub_flat;
                            if($t_sub>$request->amount){
                                return back()->with('alert', 'Payout Amount cannot cover sub accounts, delete some sub accounts.');
                            }else{
                                $sub_account=Subaccounts::whereuser_id($user->id)->whereactive(1)->get();
                                if(count($sub_account)>0){
                                    foreach($sub_account as $val){
                                        $secret=str_random(6);
                                        $sd['user_id']=Auth::guard('user')->user()->id;
                                        $sd['reference']=$token;
                                        $sd['secret']=$secret;
                                        if($val->type==1){
                                            $sd['amount']=$val->amount;
                                        }elseif($val->type==2){
                                            $sd['amount']=$request->amount*$val->amount/100;
                                        }
                                        $sd['status']=0;
                                        $sd['type']=2;
                                        $sd['sub_id']=$val->id;
                                        $sd['next_settlement']=$set->next_settlement;
                                        Withdraw::create($sd);
                                        if($set->email_notify==1){
                                            new_subwithdraw($secret);
                                        }
                                    }
                                }
                                $sav['user_id']=Auth::guard('user')->user()->id;
                                $sav['reference']=$token;
                                $sav['amount']=$request->amount-$t_sub-(($request->amount*$set->withdraw_charge/100+($set->withdraw_chargep))*$rl);
                                $sav['charge']=($request->amount*$set->withdraw_charge/100+($set->withdraw_chargep))*$rl;
                                $sav['status']=0;
                                $sav['type']=1;
                                $sav['bank_id']=$bank->id;
                                $sav['next_settlement']=$set->next_settlement;
                                Withdraw::create($sav);
                                $a=$user->balance-($request->amount);
                                $user->balance=$a;
                                $user->save();
                                //Charges
                                $charge['user_id']=$user->id;
                                $charge['ref_id']=$token;
                                $charge['amount']=($request->amount*$set->withdraw_charge/100+($set->withdraw_chargep))*$rl;
                                $charge['log']='Charges for withdrawal #' .$token;
                                Charges::create($charge);
                                $his['user_id']=$user->id;
                                $his['amount']=$request->amount;
                                $his['ref']=$token;
                                $his['main']=1;
                                $his['type']=1;
                                $his['status']=0;
                                History::create($his);
                                if($set->email_notify==1){
                                    new_withdraw($token);
                                }
                                return back()->with('success', 'Withdrawal request has been submitted, you will be updated shortly.');
                            }
                        }else{
                            return redirect()->route('user.compliance')->with('alert', 'Your payout limit as an unverified business is '.$currency->symbol.$set->starter_limit.' Register business to remove limit');
                        }
                    }elseif($user->business_level==3){
                        $sub_flat=Subaccounts::whereuser_id($user->id)->wheretype(1)->whereactive(1)->sum('amount');
                        $sub_percent=Subaccounts::whereuser_id($user->id)->wheretype(2)->whereactive(1)->sum('amount');
                        $t_percent=$sub_percent/100*$request->amount;
                        $t_sub=$t_percent+$sub_flat;
                        if($t_sub>$request->amount){
                            return back()->with('alert', 'Payout Amount cannot cover sub accounts, delete some sub accounts.');
                        }else{
                            $sub_account=Subaccounts::whereuser_id($user->id)->whereactive(1)->get();
                            if(count($sub_account)>0){
                                foreach($sub_account as $val){
                                    $secret=str_random(6);
                                    $sd['user_id']=Auth::guard('user')->user()->id;
                                    $sd['reference']=$token;
                                    $sd['secret']=$secret;
                                    if($val->type==1){
                                        $sd['amount']=$val->amount;
                                    }elseif($val->type==2){
                                        $sd['amount']=$request->amount*$val->amount/100;
                                    }
                                    $sd['status']=0;
                                    $sd['type']=2;
                                    $sd['sub_id']=$val->id;
                                    $sd['next_settlement']=$set->next_settlement;
                                    Withdraw::create($sd);
                                    if($set->email_notify==1){
                                        new_subwithdraw($secret);
                                    }
                                }
                            }
                            $sav['user_id']=Auth::guard('user')->user()->id;
                            $sav['reference']=$token;
                            $sav['amount']=$request->amount-$t_sub-(($request->amount*$set->withdraw_charge/100+($set->withdraw_chargep))*$rl);
                            $sav['charge']=($request->amount*$set->withdraw_charge/100+($set->withdraw_chargep))*$rl;
                            $sav['status']=0;
                            $sav['type']=1;
                            $sav['bank_id']=$bank->id;
                            $sav['next_settlement']=$set->next_settlement;
                            Withdraw::create($sav);
                            $a=$user->balance-($request->amount);
                            $user->balance=$a;
                            $user->save();
                            //Charges
                            $charge['user_id']=$user->id;
                            $charge['ref_id']=$token;
                            $charge['amount']=($request->amount*$set->withdraw_charge/100+($set->withdraw_chargep))*$rl;
                            $charge['log']='Charges for withdrawal #' .$token;
                            Charges::create($charge);
                            $his['user_id']=$user->id;
                            $his['amount']=$request->amount;
                            $his['ref']=$token;
                            $his['main']=1;
                            $his['type']=1;
                            $his['status']=0;
                            History::create($his);
                            if($set->email_notify==1){
                                new_withdraw($token);
                            }
                            return back()->with('success', 'Withdrawal request has been submitted, you will be updated shortly.');
                        }
                    }
                }else{
                    return back()->with('alert', 'Insufficent balance.');
                }
            }else{
                return back()->with('alert', 'Amount Does not Cover Charges.');
            }
        } 
    //End of Withdrawal
   
    //Verification
        public function blocked()
        {
            if (Auth::guard('user')->user()->status==0) {
                return redirect()->route('user.dashboard');
            } else {
                $data['title'] = "Account suspended";
                return view('user.profile.blocked', $data);
            }
        } 

        public function authCheck()
        {
            if (Auth()->guard('user')->user()->status == 0 && Auth()->guard('user')->user()->email_verify == 1) {
                return redirect()->route('user.dashboard');
            } else {
                $data['title'] = "Authorization";
                return view('user.profile.verify', $data);
            }
        }       

        public function sendEmailVcode(Request $request)
        {
            $user = User::find(Auth::guard('user')->user()->id);
            $set=Settings::first();
            if (Carbon::parse($user->email_time)->addMinutes(1) > Carbon::now()) {
                $time = Carbon::parse($user->email_time)->addMinutes(1);
                $delay = $time->diffInSeconds(Carbon::now());
                $delay = gmdate('i:s', $delay);
                session()->flash('alert', 'You can resend Verification Code after ' . $delay . ' minutes');
            } else {
                $code = strtoupper(Str::random(6));
                $user->email_time = Carbon::now();
                $user->verification_code = $code;
                $user->save();
                send_email($user->email, $user->username, 'Verification Code', 'Your Verification Code is ' . $code);
                session()->flash('success', 'Verification Code Send successfully');
            }
            return back();
        }

        public function postEmailVerify(Request $request)
        {

            $user = User::find(Auth::guard('user')->user()->id);
            if ($user->verification_code == $request->email_code) {
                $set=Settings::first();
                if($set->stripe_connect==1){
                    $gate = Gateway::find(103);
                    $stripe = new StripeClient($gate->val2);
                    try {
                        $charge=$stripe->accounts->update($user->stripe_id,[
                            'email' => $user->email,
                        ]);
                    } catch (\Stripe\Exception\RateLimitException $e) {
                        return back()->with('alert', $e->getMessage());
                    } catch (\Stripe\Exception\InvalidRequestException $e) {
                        return back()->with('alert', $e->getMessage());
                    } catch (\Stripe\Exception\AuthenticationException $e) {
                        return back()->with('alert', $e->getMessage());
                    } catch (\Stripe\Exception\ApiConnectionException $e) {
                        return back()->with('alert', $e->getMessage());
                    } catch (\Stripe\Exception\ApiErrorException $e) {
                        return back()->with('alert', $e->getMessage());
                    } catch (Exception $e) {
                        return back()->with('alert', $e->getMessage());
                    }
                }
                $user->email_verify = 1;
                $user->save();
                session()->flash('success', 'Your Profile has been verified successfully');
                return redirect()->route('user.dashboard');
            } else {
                session()->flash('alert', 'Verification Code Did not matched');
            }
            return back();
        } 
    //End of verification   

    //Transfer Money
        public function Receivedpay($id)
        {
            $set=Settings::first();
            $currency=Currency::whereStatus(1)->first();
            $trans=Transfer::whereid($id)->first();
            $user=$data['user']=User::whereemail($trans->temp)->first();
            $trans->status=1;
            $trans->save();
            $user->balance=$user->balance+$trans->amount;
            $user->save();
            $his['user_id']=$user->id;
            $his['amount']=$trans->amount;
            $his['ref']=$trans->ref_id;
            $his['main']=1;
            $his['type']=1;
            History::create($his);
            return redirect()->route('user.transfer')->with('success', 'Transfer was successful');
        } 
        public function localpreview()
        {
            $data['amount'] = Session::get('Amount');
            $data['email'] = Session::get('Acctemail');
            $data['title']='Transfer Preview';
            return view('user.transfer.preview', $data);
        }
        
        public function ownbank()
        {
            $data['title'] = "Send Money";
            $data['adminbank']=Adminbank::whereId(1)->first();
            $data['transfer']=Transfer::wheresender_id(Auth::guard('user')->user()->id)->wheretype(1)->orwhere('receiver_id',Auth::guard('user')->user()->id)->where('type', '=', 1)->orderby('id', 'desc')->paginate(6);
            $data['received']=Transfer::where('Temp', Auth::guard('user')->user()->email)->wheretype(1)->latest()->get();
            $data['sent']=Transfer::whereStatus(1)->whereSender_id(Auth::guard('user')->user()->id)->wheretype(1)->sum('amount');
            $data['pending']=Transfer::whereStatus(0)->wheresender_id(Auth::guard('user')->user()->id)->wheretype(1)->sum('amount');
            $data['rebursed']=Transfer::whereStatus(2)->wheresender_id(Auth::guard('user')->user()->id)->wheretype(1)->sum('amount');
            $data['total']=Transfer::wheresender_id(Auth::guard('user')->user()->id)->wheretype(1)->sum('amount');
            return view('user.transfer.index', $data);
        }         
        
        public function mobilemoney()
        {
            $data['title'] = "Send Money";
            $data['transfer']=Transfer::wheresender_id(Auth::guard('user')->user()->id)->wheretype(2)->paginate(6);
            return view('user.transfer.mobile_money', $data);
        } 
        public function submitownbank(Request $request)
        {
            $set=Settings::first();
            $currency=Currency::whereStatus(1)->first();
            $amountx=$request->amount+($request->amount*$set->transfer_charge/100);
            $user=$data['user']=User::find(Auth::guard('user')->user()->id);
            if($user->email!=$request->email){
                    if($user->balance>$amountx || $user->balance==$amountx){
                        $check=User::whereEmail($request->email)->get();
                        $user->balance=$user->balance-$amountx;
                        $user->save();
                        $token='TR-'.str_random(6);
                        if(count($check)>0){
                            if($user->status==0){
                                $trans=User::whereEmail($request->email)->first();
                                $sav['ref_id']=$token;
                                $sav['amount']=$request->amount;
                                $sav['charge']=($request->amount*$set->transfer_charge/100+($set->transfer_chargep));
                                $sav['sender_id']=Auth::guard('user')->user()->id;
                                $sav['receiver_id']=$trans->id;        
                                $sav['status']=1;   
                                Transfer::create($sav);   
                                $audit['user_id']=Auth::guard('user')->user()->id;
                                $audit['trx']=$token;
                                $audit['log']='Transfered '.$currency->symbol.$request->amount.' to '.$request->email;
                                Audit::create($audit);  
                                $trans->balance=$trans->balance+$request->amount;
                                $trans->save(); 
                                //Charges
                                $charge['user_id']=$user->id;
                                $charge['ref_id']=$token;
                                $charge['amount']=$request->amount*$set->transfer_charge/100+($set->transfer_chargep);
                                $charge['log']='Charges for transfer #' .$token;
                                Charges::create($charge);
                                $his['user_id']=$user->id;
                                $his['amount']=$request->amount+($request->amount*$set->transfer_charge/100+($set->transfer_chargep));
                                $his['ref']=$token;
                                $his['main']=1;
                                $his['type']=2;
                                History::create($his);                                
                                $his['user_id']=$trans->id;
                                $his['amount']=$request->amount;
                                $his['ref']=$token;
                                $his['main']=1;
                                $his['type']=1;
                                History::create($his);
                                if($set->email_notify==1){
                                    send_transferreceipt($token);
                                }
                                return redirect()->route('user.transfer')->with('success', 'Transfer was successful');
                            }else{
                                $data['title']='Error Message';
                                return view('user.merchant.error', $data)->withErrors('An Error Occured');
                            }
                        }else{
                            if($user->status==0){
                                $sav['ref_id']=$token;
                                $sav['amount']=$request->amount-($request->amount*$set->transfer_charge/100+($set->transfer_chargep));
                                $sav['charge']=($request->amount*$set->transfer_charge/100+($set->transfer_chargep));
                                $sav['sender_id']=Auth::guard('user')->user()->id;  
                                $sav['temp']=$request->email;  
                                $sav['status']=0; 
                                Transfer::create($sav); 
                                $audit['user_id']=Auth::guard('user')->user()->id;
                                $audit['trx']=$token;
                                $audit['log']='Transfered '.$currency->symbol.$request->amount.' to '.$request->email;
                                Audit::create($audit);    
                                $content='Email:'.$user->email.', Date:'.Carbon::now().', DR Amt:'.$request->amount.',
                                Bal:'.$user->balance.', Ref:'.$token.', Desc: Transfer'; 
                                //Charges
                                $charge['user_id']=$user->id;
                                $charge['ref_id']=$token;
                                $charge['amount']=$request->amount*$set->transfer_charge/100+($set->transfer_chargep);
                                $charge['log']='Charges for transfer #' .$token;
                                Charges::create($charge);
                                $his['user_id']=$user->id;
                                $his['amount']=$request->amount+($request->amount*$set->transfer_charge/100+($set->transfer_chargep));
                                $his['ref']=$token;
                                $his['main']=1;
                                $his['type']=2;
                                History::create($his);
                                if($set->email_notify==1){
                                    send_ntransferreceipt($token);
                                }
                                return redirect()->route('user.transfer')->with('success', 'Transfer was successful, but user has to create account to confirm transaction');
                            }else{
                                $data['title']='Error Message';
                                return view('user.merchant.error', $data)->withErrors('An Error Occured');
                            }
                        }
                    }else{
                        return back()->with('alert', 'Account balance is insufficient');
                    }
            }else{
                return back()->with('alert', 'You cant transfer money to the same account.');
            }
        }   
    //End of Transfer Money   
    
    //Request Money
        public function request()
        {
            $data['title'] = "Request Money";
            $data['adminbank']=Adminbank::whereId(1)->first();
            $data['request']=Requests::whereuser_id(Auth::guard('user')->user()->id)->orWhere('Email', Auth::guard('user')->user()->email)->orderby('id', 'desc')->paginate(6);
            $data['sent']=Requests::whereStatus(1)->whereuser_id(Auth::guard('user')->user()->id)->sum('amount');
            $data['pending']=Requests::whereStatus(0)->whereuser_id(Auth::guard('user')->user()->id)->sum('amount');
            $data['total']=Requests::whereuser_id(Auth::guard('user')->user()->id)->sum('amount');
            return view('user.transfer.request', $data);
        }

        public function submitrequest(Request $request)
        {
            $set=Settings::first();
            $currency=Currency::whereStatus(1)->first();
            $amount=$request->amount;
            $user=User::find(Auth::guard('user')->user()->id);
            $check=User::whereemail($request->email)->get();
            $to=User::whereemail($request->email)->first();
            $token='RQ-'.str_random(6);
            if($user->email!=$request->email){
                if(count($check)>0){
                    $sav['ref_id']=$token;
                    $sav['confirm']=str_random(8);
                    $sav['amount']=$request->amount;
                    $sav['charge']=$request->amount*$set->transfer_charge/100+($set->transfer_chargep);
                    $sav['email']=$request->email;
                    $sav['user_id']=Auth::guard('user')->user()->id; 
                    Requests::create($sav);  
                    $his['user_id']=$user->id;
                    $his['amount']=$request->amount;
                    $his['ref']=$token;
                    $his['main']=1;
                    $his['type']=1;
                    $his['status']=0;
                    History::create($his);
                    $audit['user_id']=Auth::guard('user')->user()->id;
                    $audit['trx']=$token;
                    $audit['log']='Requested '.$currency->symbol.$request->amount.' from '.$request->email;
                    Audit::create($audit);
                    if($set->email_notify==1){
                        send_request($token);
                    }
                    return redirect()->route('user.request')->with('success', 'Request was sent successfully');
                }else{
                    return back()->with('alert', 'User not found.');
                }
            }else{
                return back()->with('alert', 'You cant request money from the same account.');
            }
        }
        public function Sendpay($id)
        {
            $set=Settings::first();
            $currency=Currency::whereStatus(1)->first();
            $trans=Requests::whereconfirm($id)->first();
            $sender=User::whereemail($trans->email)->first();
            $receiver=User::whereid($trans->user_id)->first();
            $amount=$trans->amount+($trans->amount*$set->transfer_charge/100+($set->transfer_chargep));
            if($trans->status==0){
                if($sender->balance>$amount || $sender->balance==$amount){
                    $trans->status=1;
                    $trans->save();
                    $sender->balance=$sender->balance-$amount;
                    $sender->save();        
                    $receiver->balance=$receiver->balance+$trans->amount;
                    $receiver->save();
                    $charge['user_id']=$receiver->id;
                    $charge['ref_id']=$trans->ref_id;
                    $charge['amount']=$trans->amount*$set->transfer_charge/100+($set->transfer_chargep);
                    $charge['log']='Charges for request money #' .$trans->ref_id;
                    Charges::create($charge);
                    $history = History::whereref($trans->ref_id)->first();
                    $history->status=1;
                    $history->save();
                    if($set->email_notify==1){
                        send_requestreceipt($trans->ref_id);
                    }
                }else{
                    return back()->with('alert', 'Please fund account, account is insufficient.');
                }
            }else{
                $data['title']='Error Message';
                return view('user.merchant.error', $data)->withErrors('Already Paid!!!');
            }
            return redirect()->route('user.request')->with('success', 'Transfer was successful');
        }        
        
        public function Declinepay($id)
        {
            $set=Settings::first();
            $currency=Currency::whereStatus(1)->first();
            $trans=Requests::whereconfirm($id)->first();
            $sender=User::whereemail($trans->email)->first();
            $receiver=User::whereid($trans->user_id)->first();
            if($trans->status==0){
                $trans->status=2;
                $trans->save();
            }else{
                $data['title']='Error Message';
                return view('user.merchant.error', $data)->withErrors('Already Paid!!!');
            }
            return redirect()->route('user.request')->with('success', 'Request was declined');
        }
    //End of Request money     
    
    //Payment link
        public function sclinks()
        {
            $data['title'] = "Payment link";
            $data['links']=Paymentlink::whereuser_id(Auth::guard('user')->user()->id)->wheretype(1)->orderby('id', 'desc')->paginate(6);
            return view('user.link.sc', $data);
        }         
        
        public function sclinkstrans($id)
        {
            $data['title'] = "Single Charge";
            $data['links']=Transactions::wherepayment_link($id)->latest()->get();
            return view('user.link.sc-trans', $data);
        }         
        public function dplinkstrans($id)
        {
            $data['title'] = "Donation";
            $data['links']=Transactions::wherepayment_link($id)->latest()->get();
            return view('user.link.dp-trans', $data);
        }   
        public function unsclinks($id)
        {
            $page=Paymentlink::find($id);
            $page->active=0;
            $page->save();
            return back()->with('success', 'Payment link has been disabled.');
        } 
        public function psclinks($id)
        {
            $page=Paymentlink::find($id);
            $page->active=1;
            $page->save();
            return back()->with('success', 'Payment link has been activated.');
        }   
        public function updatesclinks(Request $request)
        {
            $data=Paymentlink::whereId($request->id)->first();
            $data->amount = $request->amount;
            $data->description = $request->description;
            $data->redirect_link = $request->redirect_url;
            $data->name = $request->name;
            $data->save();
            return redirect()->route('user.sclinks')->with('success', 'Payment link was successfully updated');
        }  
        public function dplinks()
        {
            $data['title'] = "Payment link";
            $data['links']=Paymentlink::whereuser_id(Auth::guard('user')->user()->id)->wheretype(2)->latest()->paginate(6);
            return view('user.link.dp', $data);
        }
        public function undplinks($id)
        {
            $page=Paymentlink::find($id);
            $page->active=0;
            $page->save();
            return back()->with('success', 'Payment link has been disabled.');
        } 
        public function pdplinks($id)
        {
            $page=Paymentlink::find($id);
            $page->active=1;
            $page->save();
            return back()->with('success', 'Payment link has been activated.');
        }
        public function updatedplinks(Request $request)
        {
            $data=Paymentlink::whereId($request->id)->first();
            $data->amount = $request->goal;
            $data->description = $request->description;
            $data->name = $request->name;
            if($request->hasFile('image')){
                $image = $request->file('image');
                $filename = 'donation'.time().'.'.$image->extension();
                $location = 'asset/profile/' . $filename;
                Image::make($image)->resize(640,360)->save($location);
                $path = './asset/profile/';
                File::delete($path.$data->image);
                $data->image=$filename;
            }
            $data->save();
            return redirect()->route('user.dplinks')->with('success', 'Payment link was successfully updated');
        }
        public function submitsinglecharge(Request $request)
        {
            $set=Settings::first();
            $currency=Currency::whereStatus(1)->first();
            $amount=$request->amount;
            $user=User::find(Auth::guard('user')->user()->id);
            $trx='SC-'.str_random(6);
            $sav['ref_id']=$trx;
            $sav['type']=1;
            $sav['amount']=$request->amount;
            $sav['name']=$request->name;
            $sav['description']=$request->description;
            $sav['redirect_link']=$request->redirect_url;
            $sav['user_id']=$user->id; 
            Paymentlink::create($sav);   
            $audit['user_id']=Auth::guard('user')->user()->id;
            $audit['trx']=str_random(16);
            $audit['log']='Created Payment Link - '.$trx;
            Audit::create($audit);
            $his['user_id']=$user->id;
            $his['ref']=$trx;
            $his['main']=1;
            History::create($his);
            return redirect()->route('user.sclinks')->with('success', 'Link was successfully created');
                
        }        
        public function submitdonationpage(Request $request)
        {
            $set=Settings::first();
            $currency=Currency::whereStatus(1)->first();
            $amount=$request->amount;
            $user=User::find(Auth::guard('user')->user()->id);
            $trx='DN-'.str_random(6);
            $sav['ref_id']=$trx;
            $sav['type']=2;
            $sav['amount']=$request->goal;
            $sav['name']=$request->name;
            $sav['description']=$request->description;
            $sav['user_id']=Auth::guard('user')->user()->id; 
            $image = $request->file('image');
            $filename = 'donation'.time().'.'.$image->extension();
            $location = 'asset/profile/' . $filename;
            Image::make($image)->resize(640,360)->save($location);
            $sav['image']=$filename;
            Paymentlink::create($sav);   
            $audit['user_id']=$user->id;
            $audit['trx']=$trx;
            $audit['log']='Created Donation Page - '.$trx;
            Audit::create($audit);
            $his['user_id']=$user->id;
            $his['ref']=$trx;
            $his['main']=1;
            History::create($his);
            return redirect()->route('user.dplinks')->with('success', 'Donation Page was successfully created');
                
        }
        public function Destroylink($id)
        {
            $link=Paymentlink::whereid($id)->first();
            $history=Transactions::wherepayment_link($id)->delete();
            if($link->type==2){
                $donation=Donations::wheredonation_id($id)->delete();
            }
            $data=$link->delete();
            if ($data) {
                return back()->with('success', 'Payment link was Successfully deleted!');
            } else {
                return back()->with('alert', 'Problem With Deleting Payment link');
            }
        }
        public function scviewlink($id)
        {
            $data['title']='Payment Method';
            $link=Paymentlink::whereref_id($id)->first();
            $data['merchant']=$user=User::find($link->user_id);
            $data['link']=$id;
            return view('user.link.sask', $data);
        }        
        public function cardscviewlink($id)
        {
            Session::put('pay-type', 'card');
            $check=Paymentlink::whereref_id($id)->get();
            if(count($check)>0){
                $key=Paymentlink::whereref_id($id)->first();
                if($key->user->status==0){
                    if($key->status==0){
                        if($key->active==1){
                            $data['link']=$link=Paymentlink::whereref_id($id)->first();
                            $data['merchant']=$user=User::find($link->user_id);
                            $set=Settings::first();
                            $data['title']="Single Charge - ".$link->name;
                            return view('user.link.sc_view', $data);
                        }else{
                            $data['title']='Error Occured';
                            return view('user.merchant.error', $data)->withErrors('Single Charge page has been disabled');
                        }    
                    }else{
                        $data['title']='Error Occured';
                        return view('user.merchant.error', $data)->withErrors('Single Charge page has been suspended');
                    }
                }else{
                    $data['title']='Error Message';
                    return view('user.merchant.error', $data)->withErrors('An Error Occured');
                }
            }else{
                $data['title']='Error Message';
                return view('user.merchant.error', $data)->withErrors('Invalid payment link');
            }
        }          
        public function accountscviewlink($id)
        {
            Session::put('pay-type', 'account');
            $check=Paymentlink::whereref_id($id)->get();
            if(count($check)>0){
                $key=Paymentlink::whereref_id($id)->first();
                if($key->user->status==0){
                    if($key->status==0){
                        if($key->active==1){
                            $data['link']=$link=Paymentlink::whereref_id($id)->first();
                            $data['merchant']=$user=User::find($link->user_id);
                            $set=Settings::first();
                            $data['title']="Single Charge - ".$link->name;
                            return view('user.link.sc_view', $data);
                        }else{
                            $data['title']='Error Occured';
                            return view('user.merchant.error', $data)->withErrors('Single Charge page has been disabled');
                        }    
                    }else{
                        $data['title']='Error Occured';
                        return view('user.merchant.error', $data)->withErrors('Single Charge page has been suspended');
                    }
                }else{
                    $data['title']='Error Message';
                    return view('user.merchant.error', $data)->withErrors('An Error Occured');
                }
            }else{
                $data['title']='Error Message';
                return view('user.merchant.error', $data)->withErrors('Invalid payment link');
            }
        }        
        public function dpviewlink($id)
        {
            $data['title']='Payment Method';
            $link=Paymentlink::whereref_id($id)->first();
            $data['merchant']=$user=User::find($link->user_id);
            $data['link']=$id;
            return view('user.link.dask', $data);
        } 
        public function carddpviewlink($id)
        {
            Session::put('pay-type', 'card');
            $check=Paymentlink::whereref_id($id)->get();
            if(count($check)>0){
                $key=Paymentlink::whereref_id($id)->first();
                if($key->user->status==0){
                    if($key->status==0){
                        if($key->active==1){
                            $data['link']=$link=Paymentlink::whereref_id($id)->first();
                            $data['donated']=Donations::wheredonation_id($key->id)->wherestatus(1)->sum('amount');
                            $data['dd']=Donations::wheredonation_id($key->id)->wherestatus(1)->get();
                            $data['paid']=Donations::wheredonation_id($key->id)->wherestatus(1)->latest()->paginate(5);
                            $data['merchant']=$user=User::find($link->user_id);
                            $set=Settings::first();
                            $data['title']="Donation - ".$link->name;
                            return view('user.link.dp_view', $data);
                        }else{
                            $data['title']='Error Occured';
                            return view('user.merchant.error', $data)->withErrors('Donation page has been disabled');
                        }                       
                    }else{
                        $data['title']='Error Occured';
                        return view('user.merchant.error', $data)->withErrors('Donation page has been suspended');
                    }
                }else{
                    $data['title']='Error Occured';
                    return view('user.merchant.error', $data)->withErrors('An Error Occured');
                }
            }else{
                $data['title']='Error Message';
                return view('user.merchant.error', $data)->withErrors('Invalid payment link');
            }
        }        
        public function accountdpviewlink($id)
        {
            Session::put('pay-type', 'account');
            $check=Paymentlink::whereref_id($id)->get();
            if(count($check)>0){
                $key=Paymentlink::whereref_id($id)->first();
                if($key->user->status==0){
                    if($key->status==0){
                        if($key->active==1){
                            $data['link']=$link=Paymentlink::whereref_id($id)->first();
                            $data['donated']=Donations::wheredonation_id($key->id)->wherestatus(1)->sum('amount');
                            $data['dd']=Donations::wheredonation_id($key->id)->wherestatus(1)->get();
                            $data['paid']=Donations::wheredonation_id($key->id)->wherestatus(1)->latest()->paginate(5);
                            $data['merchant']=$user=User::find($link->user_id);
                            $set=Settings::first();
                            $data['title']="Donation - ".$link->name;
                            return view('user.link.dp_view', $data);
                        }else{
                            $data['title']='Error Occured';
                            return view('user.merchant.error', $data)->withErrors('Donation page has been disabled');
                        }                       
                    }else{
                        $data['title']='Error Occured';
                        return view('user.merchant.error', $data)->withErrors('Donation page has been suspended');
                    }
                }else{
                    $data['title']='Error Occured';
                    return view('user.merchant.error', $data)->withErrors('An Error Occured');
                }
            }else{
                $data['title']='Error Message';
                return view('user.merchant.error', $data)->withErrors('Invalid payment link');
            }
        }
        public function stripescviewlink(Request $request, $id)
        {
            $set=Settings::first();
            $currency=Currency::whereStatus(1)->first();
            $link=Transactions::whereref_id($id)->first();
            $paylink=Paymentlink::whereid($link->payment_link)->first();
            $receiver=User::whereid($paylink->user_id)->first();
            $gate = Gateway::find(103);
            $stripe = new StripeClient($gate->val2);
            try {
                $charge=$stripe->paymentIntents->retrieve($request->input('payment_intent'));
                if ($charge['status']=="succeeded") {
                    $receiver->balance=$receiver->balance+(($link->amount)-($link->amount*$set->single_charge/100+($set->single_chargep)));
                    $receiver->save();
                    //Audit
                    $audit['user_id']=$receiver->id;
                    $audit['trx']=$id;
                    $audit['log']='Received payment for Payment Link' .$link->ref_id;
                    Audit::create($audit);
                    //Charges
                    $charges['user_id']=$receiver->id;
                    $charges['ref_id']=$id;
                    $charges['amount']=$link->amount*$set->single_charge/100;
                    $charges['log']='Received payment for Payment Link #' .$link->ref_id;
                    Charges::create($charges);
                    $his['user_id']=$receiver->id;
                    $his['amount']=$link->amount-($link->amount*$set->single_charge/100+($set->single_chargep));
                    $his['ref']=$id;
                    $his['main']=0;
                    $his['type']=1;
                    $his['charge']=$link->amount*$set->single_charge/100+($set->single_chargep);
                    $his['stripe_id']=$charge['id'];
                    History::create($his);
                    //Change status to successful
                    $change=Transactions::whereref_id($id)->first();
                    $change->status=1;
                    $change->charge=$link->amount*$set->single_charge/100+($set->single_chargep);
                    $change->save(); 
                    //Notify users
                    if($set->email_notify==1){
                        send_paymentlinkreceipt($link->ref_id, 'card', $id);
                    } 
                    //Redirect payment
                    if($link->redirect_link!=null){
                        return Redirect::away($link->redirect_link);
                    }else{
                        if(Auth::guard('user')->check()){
                            return redirect()->route('user.transactions')->with('success', 'Payment was successful');
                        }else{
                            return redirect()->route('scview.link', ['id' => $paylink->ref_id])->with('success', 'Payment was Successful!!!');
                        }  
                    }
                } else {
                    return redirect()->route('scview.link', ['id' => $paylink->ref_id])->with('alert', 'Failed');
                }
            } catch (\Stripe\Exception\CardException $e) {
                return redirect()->route('scview.link', ['id' => $paylink->ref_id])->with('alert', $e->getMessage());
            } catch (\Stripe\Exception\InvalidRequestException $e) {
                return redirect()->route('scview.link', ['id' => $paylink->ref_id])->with('alert', $e->getMessage());
            }
            
        }        
        
        public function stripedpviewlink(Request $request, $id)
        {
            $set=Settings::first();
            $currency=Currency::whereStatus(1)->first();
            $link=Transactions::whereref_id($id)->first();
            $paylink=Paymentlink::whereid($link->payment_link)->first();
            $receiver=User::whereid($paylink->user_id)->first();
            $gate = Gateway::find(103);
            $stripe = new StripeClient($gate->val2);
            try {
                $charge=$stripe->paymentIntents->retrieve($request->input('payment_intent'));
                if ($charge['status']=="succeeded") {
                    $receiver->balance=$receiver->balance+(($link->amount)-($link->amount*$set->donation_charge/100+($set->donation_chargep)));
                    $receiver->save();
                    //Audit
                    $audit['user_id']=$receiver->id;
                    $audit['trx']=$id;
                    $audit['log']='Received payment for Payment Link' .$link->ref_id;
                    Audit::create($audit);
                    //Charges
                    $charges['user_id']=$receiver->id;
                    $charges['ref_id']=$id;
                    $charges['amount']=$link->amount*$set->donation_charge/100+($set->donation_chargep);
                    $charges['log']='Received payment for Payment Link #' .$link->ref_id;
                    Charges::create($charges);
                    $his['user_id']=$receiver->id;
                    $his['amount']=$link->amount-($link->amount*$set->donation_charge/100+($set->donation_chargep));
                    $his['ref']=$id;
                    $his['main']=0;
                    $his['type']=1;
                    $his['charge']=$link->amount*$set->donation_charge/100+($set->donation_chargep);
                    $his['stripe_id']=$charge['id'];
                    History::create($his);
                    //Change status to successful
                    $change=Transactions::whereref_id($id)->first();
                    $change->status=1;
                    $change->charge=$link->amount*$set->donation_charge/100+($set->donation_chargep);
                    $change->save(); 
                    //Notify users
                    if($set->email_notify==1){
                        send_paymentlinkreceipt($link->ref_id, 'card', $id);
                    } 
                    //Redirect payment
                    if($link->redirect_link!=null){
                        return Redirect::away($link->redirect_link);
                    }else{
                        if(Auth::guard('user')->check()){
                            return redirect()->route('user.transactionsd')->with('success', 'Payment was successful');
                        }else{
                            return redirect()->route('dpview.link', ['id' => $paylink->ref_id])->with('success', 'Payment was Successful!!!');
                        }  
                    }
                } else {
                    return redirect()->route('dpview.link', ['id' => $paylink->ref_id])->with('alert', 'Failed');
                }
            } catch (\Stripe\Exception\CardException $e) {
                return redirect()->route('dpview.link', ['id' => $paylink->ref_id])->with('alert', $e->getMessage());
            } catch (\Stripe\Exception\InvalidRequestException $e) {
                return redirect()->route('dpview.link', ['id' => $paylink->ref_id])->with('alert', $e->getMessage());
            }
        }         
        
        public function Sendsingle(Request $request)
        {
            $set=Settings::first();
            $currency=Currency::whereStatus(1)->first();
            $link=Paymentlink::whereref_id($request->link)->first();
            $xtoken='SC-'.str_random(6);
            $receiver=User::whereid($link->user_id)->first();
            if($request->type=='card'){
                if ($request->has('stripeSource')){
                    $sav['ref_id']=$xtoken;
                    $sav['type']=1;
                    $sav['amount']=$request->amount-($request->amount*$set->single_charge/100+($set->single_chargep));
                    if (Auth::guard('user')->check()){
                        $sav['sender_id']=Auth::guard('user')->user()->id;
                    }else{
                        $sav['email']=$request->email;
                        $sav['first_name']=$request->first_name;
                        $sav['last_name']=$request->last_name;
                    }
                    $sav['card_number']=$request->number;
                    $sav['ip_address']=user_ip();
                    $sav['receiver_id']=$link->user_id;
                    $sav['payment_link']=$link->id;
                    $sav['payment_type']='card';
                    Transactions::create($sav);
                    $gate = Gateway::find(103);
                    $stripe = new StripeClient($gate->val2);
                    try {
                        $charge=$stripe->paymentIntents->create([
                            'amount' => $request->amount*100,
                            'currency' => $currency->name,
                            'payment_method_types' => ['card'],
                            'description' => 'Single Charge Payment #'.$xtoken,
                            'source' => $request->input('stripeSource'),
                            'return_url' => route('stripe.scview.link', ['id' => $xtoken]),
                            'confirm' => true,
                        ]);       
                        if ($charge['status']=="succeeded") {
                            $receiver->balance=$receiver->balance+(($request->amount)-($request->amount*$set->single_charge/100+($set->single_chargep)));
                            $receiver->save();
                            //Audit
                            $audit['user_id']=$receiver->id;
                            $audit['trx']=$xtoken;
                            $audit['log']='Received payment for Payment Link' .$link->ref_id;
                            Audit::create($audit);
                            //Charges
                            $charges['user_id']=$receiver->id;
                            $charges['ref_id']=$xtoken;
                            $charges['amount']=$request->amount*$set->single_charge/100+($set->single_chargep);
                            $charges['log']='Received payment for Payment Link #' .$link->ref_id;
                            Charges::create($charges);
                            $his['user_id']=$receiver->id;
                            $his['amount']=$request->amount-($request->amount*$set->single_charge/100+($set->single_chargep));
                            $his['ref']=$xtoken;
                            $his['main']=0;
                            $his['type']=1;
                            $his['charge']=$request->amount*$set->single_charge/100+($set->single_chargep);
                            $his['stripe_id']=$charge['id'];
                            History::create($his);
                            //Change status to successful
                            $change=Transactions::whereref_id($xtoken)->first();
                            $change->status=1;
                            $change->charge=$request->amount*$set->single_charge/100+($set->single_chargep);
                            $change->save(); 
                            //Notify users
                            if($set->email_notify==1){
                                send_paymentlinkreceipt($link->ref_id, 'card', $xtoken);
                            } 
                            //Redirect payment
                            if($link->redirect_link!=null){
                                return Redirect::away($link->redirect_link);
                            }else{
                                if(Auth::guard('user')->check()){
                                    return redirect()->route('user.transactions')->with('success', 'Payment was successful');
                                }else{
                                    return back()->with('success', 'Payment was Successful!!!');
                                }  
                            }
                        }elseif($charge['status']=="requires_action"){
                            return Redirect::away($charge['next_action']['redirect_to_url']['url']);
                        }else {
                            return back()->with('alert', $charge['error']['message']);
                        }
                    } catch (\Stripe\Exception\CardException $e) {
                        return back()->with('alert', $e->getMessage());
                    }catch (\Stripe\Exception\InvalidRequestException $e) {
                        return back()->with('alert', $e->getMessage());
                    }
                }else{
                    $data['title']='Error Message';
                    return view('user.merchant.error', $data)->withErrors('Card details is required');
                }
            }elseif($request->type=='account'){
                $validatedData=$request->validate([
                    'amount' => ['required'],
                ]);
                $user=User::find(Auth::guard('user')->user()->id);
                $sav['ref_id']=$xtoken;
                $sav['type']=1;
                $sav['amount']=$request->amount-($request->amount*$set->single_charge/100+($set->single_chargep));
                $sav['sender_id']=$user->id;
                $sav['receiver_id']=$link->user_id;
                $sav['payment_link']=$link->id;
                $sav['payment_type']='account';
                $sav['ip_address']=user_ip();
                Transactions::create($sav);
                $sender=User::whereid($user->id)->first();
                if($sender->balance>$request->amount || $sender->balance==$request->amount){
                    $sender->balance=$sender->balance-$request->amount;
                    $sender->save();        
                    $receiver->balance=$receiver->balance+(($request->amount)-($request->amount*$set->single_charge/100+($set->single_chargep)));
                    $receiver->save();
                    //Audit log
                    $audit['user_id']=Auth::guard('user')->user()->id;
                    $audit['trx']=str_random(16);
                    $audit['log']='Payment for '.$link->ref_id.' was successful';
                    Audit::create($audit);                
                    $audit['user_id']=$receiver->id;
                    $audit['trx']=str_random(16);
                    $audit['log']='Received payment for Payment Link' .$link->ref_id;
                    Audit::create($audit);
                    //Charges
                    $charges['user_id']=$receiver->id;
                    $charges['ref_id']=$xtoken;
                    $charges['amount']=$request->amount*$set->single_charge/100;
                    $charges['log']='Received payment for Payment Link #' .$link->ref_id;
                    Charges::create($charges);
                    $his['user_id']=$receiver->id;
                    $his['amount']=$request->amount-($request->amount*$set->single_charge/100+($set->single_chargep));
                    $his['ref']=$xtoken;
                    $his['main']=0;
                    $his['type']=1;
                    History::create($his);
                    //Change status to successful
                    $change=Transactions::whereref_id($xtoken)->first();
                    $change->status=1;
                    $change->charge=$request->amount*$set->single_charge/100+($set->single_chargep);
                    $change->save(); 
                    //Notify users
                    if($set->email_notify==1){
                        send_paymentlinkreceipt($link->ref_id, 'account', $xtoken);
                    } 
                    //Redirect payment
                    if($link->redirect_link!=null){
                        return Redirect::away($link->redirect_link);
                    }else{
                        return redirect()->route('user.transactions')->with('success', 'Payment was successful');
                    }
                }else{
                    return back()->with('alert', 'Insufficient balance, please fund your account');
                }
            }
        }        
        public function Senddonation(Request $request)
        {
            $set=Settings::first();
            $currency=Currency::whereStatus(1)->first();
            $link=Paymentlink::whereref_id($request->link)->first();
            $receiver=User::whereid($link->user_id)->first();
            $xtoken='DN-'.str_random(6);
            $token2=str_random(16);
            $donated=Donations::wheredonation_id($link->id)->wherestatus(1)->sum('amount');
            $check=$link->amount-$donated;
            if($request->type=='card'){
                if ($request->has('stripeSource')){
                    if($check>$request->amount || $check==$request->amount){
                        if (Auth::guard('user')->check()){
                            $sav['sender_id']=Auth::guard('user')->user()->id;
                        }else{
                            $sav['email']=$request->email;
                            $sav['first_name']=$request->first_name;
                            $sav['last_name']=$request->last_name;
                        }
                        $sav['ref_id']=$xtoken;
                        $sav['type']=2;
                        $sav['amount']=$request->amount-($request->amount*$set->donation_charge/100+($set->donation_chargep));
                        $sav['card_number']=$request->number;
                        $sav['payment_type']='card';
                        $sav['ip_address']=user_ip();
                        $sav['receiver_id']=$link->user_id;
                        $sav['payment_link']=$link->id;
                        Transactions::create($sav);
                        //Save Donation
                        $don['amount']=$request->amount;
                        if (Auth::guard('user')->check()){
                            $don['user_id']=$user->id;
                        }
                        $don['status']=0;
                        $don['anonymous']=$request->status;
                        $don['ref_id']=$xtoken;
                        $don['donation_id']=$link->id;
                        Donations::create($don);
                        $gate = Gateway::find(103);
                        $stripe = new StripeClient($gate->val2);
                        try {
                            $charge=$stripe->paymentIntents->create([
                                'amount' => $request->amount*100,
                                'currency' => $currency->name,
                                'payment_method_types' => ['card'],
                                'description' => 'Donation Payment #'.$xtoken,
                                'source' => $request->input('stripeSource'),
                                'return_url' => route('stripe.dpview.link', ['id' => $xtoken]),
                                'confirm' => true,
                            ]);    
                            if ($charge['status']=="succeeded") {
                                $receiver->balance=$receiver->balance+(($request->amount)-($request->amount*$set->donation_charge/100+($set->donation_chargep)));
                                $receiver->save();
                                //Audit
                                $audit['user_id']=$receiver->id;
                                $audit['trx']=str_random(16);
                                $audit['log']='Received Donation for Payment Link' .$link->ref_id;
                                Audit::create($audit);
                                //Charges
                                $charges['user_id']=$receiver->id;
                                $charges['ref_id']=$xtoken;
                                $charges['amount']=$request->amount*$set->single_charge/100+($set->donation_chargep);
                                $charges['log']='Received Donation for Payment Link #' .$link->ref_id;
                                Charges::create($charges);
                                $his['user_id']=$receiver->id;
                                $his['amount']=$request->amount-($request->amount*$set->donation_charge/100+($set->donation_chargep));
                                $his['ref']=$xtoken;
                                $his['main']=0;
                                $his['type']=1;
                                $his['stripe_id']=$charge['id'];
                                $his['charge']=$request->amount*$set->donation_charge/100+($set->donation_chargep);
                                History::create($his);
                                //Change status to successful
                                $changed=Transactions::whereref_id($xtoken)->first();
                                $changed->status=1;
                                $changed->charge=$request->amount*$set->donation_charge/100+($set->donation_chargep);
                                $changed->save();                     
                                $changex=Donations::whereref_id($xtoken)->first();
                                $changex->status=1;
                                $changex->save(); 
                                //Notify users
                                if($set->email_notify==1){
                                    send_paymentlinkreceipt($link->ref_id, 'card', $xtoken);
                                } 
                                //Redirect payment
                                if($link->redirect_link!=null){
                                    return Redirect::away($link->redirect_link);
                                }else{
                                    if(Auth::guard('user')->check()){
                                        return redirect()->route('user.transactionsd')->with('success', 'Payment was successful');
                                    }else{
                                        return back()->with('success', 'Payment was Successful!!!');
                                    }  
                                }
                            }elseif($charge['status']=="requires_action"){
                                return Redirect::away($charge['next_action']['redirect_to_url']['url']);
                            }else {
                                return back()->with('alert', $charge['error']['message']);
                            }
                        } catch (\Stripe\Exception\CardException $e) {
                            return back()->with('alert', $e->getMessage());
                        }catch (\Stripe\Exception\InvalidRequestException $e) {
                            return back()->with('alert', $e->getMessage());
                        }
                    }else{
                        return back()->with('alert', 'Amount exceeds donation requirement');
                    }
                }else{
                    $data['title']='Error Message';
                    return view('user.merchant.error', $data)->withErrors('Card details is required');
                }
            }elseif($request->type=='account'){
                $user=User::find(Auth::guard('user')->user()->id);
                $validatedData=$request->validate([
                    'amount' => ['required'],
                    'status' => ['required'],
                ]);
                $sav['ref_id']=$xtoken;
                $sav['type']=2;
                $sav['amount']=$request->amount-($request->amount*$set->donation_charge/100+($set->donation_chargep));
                $sav['sender_id']=$user->id;
                $sav['receiver_id']=$link->user_id;
                $sav['payment_link']=$link->id;
                $sav['payment_type']='account';
                $sav['ip_address']=user_ip();
                Transactions::create($sav);
                //Save Donation
                $don['user_id']=$user->id;
                $don['amount']=$request->amount;
                $don['status']=0;
                $don['anonymous']=$request->status;
                $don['ref_id']=$xtoken;
                $don['donation_id']=$link->id;
                Donations::create($don);
                $sender=User::whereid($user->id)->first();
                if($check>$request->amount || $check==$request->amount){
                    if($sender->balance>$request->amount || $sender->balance==$request->amount){
                        $sender->balance=$sender->balance-$request->amount;
                        $sender->save();        
                        $receiver->balance=$receiver->balance+(($request->amount)-($request->amount*$set->donation_charge/100+($set->donation_chargep)));
                        $receiver->save();
                        //Audit log
                        $audit['user_id']=Auth::guard('user')->user()->id;
                        $audit['trx']=str_random(16);
                        $audit['log']='Donation for '.$link->ref_id.' was successful';
                        Audit::create($audit);                
                        $audit['user_id']=$receiver->id;
                        $audit['trx']=str_random(16);
                        $audit['log']='Received Donation for Payment Link' .$link->ref_id;
                        Audit::create($audit);
                        //Charges
                        $charges['user_id']=$receiver->id;
                        $charges['ref_id']=$xtoken;
                        $charges['amount']=$request->amount*$set->donation_charge/100+($set->donation_chargep);
                        $charges['log']='Received Donation for Payment Link #' .$link->ref_id;
                        Charges::create($charges);
                        $his['user_id']=$receiver->id;
                        $his['amount']=$request->amount-($request->amount*$set->donation_charge/100+($set->donation_chargep));
                        $his['ref']=$xtoken;
                        $his['main']=0;
                        $his['type']=1;
                        History::create($his);
                        //Change status to successful
                        $changed=Transactions::whereref_id($xtoken)->first();
                        $changed->status=1;
                        $changed->charge=$request->amount*$set->donation_charge/100+($set->donation_chargep);
                        $changed->save();                     
                        $changex=Donations::whereref_id($xtoken)->first();
                        $changex->status=1;
                        $changex->save(); 
                        //Notify users
                        if($set->email_notify==1){
                            send_paymentlinkreceipt($link->ref_id, 'account', $xtoken);
                        } 
                        return redirect()->route('user.transactionsd')->with('success', 'Donation was successful');
                    }else{
                        return back()->with('alert', 'Insufficient balance, please fund your account');
                    }
                }else{
                    return back()->with('alert', 'Amount exceeds donation requirement');
                }
            }
        }
    //End of Payment link      

    //Settings
        public function profile()
        {
            $data['title'] = "Profile";
            $data['country']=Countrysupported::wherestatus(1)->get();
            $data['bcountry']=Country::where('phonecode', '!=', 0)->get();
            $data['nationality']=Country::all();
            $data['bnk']=Banksupported::wherecountry_id(Auth::guard('user')->user()->country)->get();
            $g=new \Sonata\GoogleAuthenticator\GoogleAuthenticator();
            $secret=$g->generateSecret();
            $set=Settings::first();
            $user = User::find(Auth::guard('user')->user()->id);
            $site=$set->site_name;
            $data['secret']=$secret;
            $data['image']=\Sonata\GoogleAuthenticator\GoogleQrUrl::generate($user->email, $secret, $site);
            $data['bank']=Bank::whereUser_id(Auth::guard('user')->user()->id)->orderBy('id', 'DESC')->paginate(4);
            return view('user.profile.index', $data);
        }        
        
        public function no_kyc()
        {
            $data['title'] = "Compliance";
            $data['country']=Countrysupported::wherestatus(1)->get();
            $data['bcountry']=Country::where('phonecode', '!=', 0)->get();
            $data['nationality']=Country::all();
            $set=Settings::first();
            $user = User::find(Auth::guard('user')->user()->id);
            $site=$set->site_name;
            return view('user.profile.compliance', $data);
        }

        public function no_country()
        {
            $data['title'] = "Update Country";
            $data['country']=Countrysupported::wherestatus(1)->get();
            $set=Settings::first();
            $user = User::find(Auth::guard('user')->user()->id);
            $site=$set->site_name;
            return view('user.profile.country', $data);
        }

        public function submitcountry(Request $request)
        {
            $user = User::findOrFail(Auth::guard('user')->user()->id);
            $country=Country::whereid($request->country)->first();
            $country_supported=Countrysupported::wherecountry_id($request->country)->first();
            $user->pay_support=$country_supported->id;
            $user->save();
            return redirect()->route('user.dashboard')->with('success', 'Country Was successfully updated.');
        }

        public function logout()
        {
            if (Auth::guard('user')->check()) {
                $user = User::findOrFail(Auth::guard('user')->user()->id);
                $user->fa_expiring = Carbon::now()->subMinutes(30);
                $user->save();
                session()->forget('oldLink');
                Auth::guard('user')->logout();
                session()->flash('message', 'Just Logged Out!');
                return redirect()->route('login');
            }else{
                return redirect()->route('login');
            }
        }
        
        public function submitPassword(Request $request)
        {
            $user = User::whereid(Auth::guard('user')->user()->id)->first();
            if (Hash::check($request->password, $user->password)) {
                $user->password = Hash::make($request->new_password);
                $user->save();
                $audit['user_id']=Auth::guard('user')->user()->id;
                $audit['trx']=str_random(16);
                $audit['log']='Changed Password';
                Audit::create($audit);
                return back()->with('success', 'Password Changed successfully.');
            }elseif (!Hash::check($request->password, $user->password)) {
                return back()->with('alert', 'Invalid password');
            }
        } 
        
            public function avatar(Request $request)
        {
            $user = User::findOrFail(Auth::guard('user')->user()->id);
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = $user->business_name.time().'.'.$image->extension();
                $location = 'asset/profile/' . $filename;
                if ($user->image != 'person.png') {
                    $path = './asset/profile/';
                    File::delete($path.$user->image);
                }
                Image::make($image)->save($location);
                $user->image=$filename;
                $user->save();
                return back()->with('success', 'Avatar Updated Successfully.');
            }else{
                return back()->with('success', 'An error occured, try again.');
            }
        }       
        
            public function kyc(Request $request)
        {
            $user = User::findOrFail(Auth::guard('user')->user()->id);
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '_' . $user->username . $image->extension();
                $location = 'asset/profile/' . $filename;
                if ($user->image != 'user-default.png') {
                    $path = './asset/profile/';
                    $link = $path . $user->kyc_link;
                    if (file_exists($link)) {
                        @unlink($link);
                    }
                }
                Image::make($image)->save($location);
                $user->kyc_link=$filename;
                $user->save();
                return back()->with('success', 'Document Upload was successful.');
            }else{
                return back()->with('success', 'An error occured, try again.');
            }
        }
            public function account(Request $request)
        {
            $user = User::findOrFail(Auth::guard('user')->user()->id);
            $user->first_name=$request->first_name;
            $user->last_name=$request->last_name;
            $user->business_name=$request->business_name;
            $user->phone=$request->phone;
            $user->country=$request->country;
            $user->support_email=$request->support_email;
            $user->save();
            $audit['user_id']=Auth::guard('user')->user()->id;
            $audit['trx']=str_random(16);
            $audit['log']='Updated account details';
            Audit::create($audit);       
            if($user->email!=$request->email){
                $check=User::whereEmail($request->email)->get();
                if(count($check)<1){
                    $user->email_verify=0;
                    $user->email=$request->email;
                    $user->save();
                }else{
                    return back()->with('alert', 'Email already in use.');
                }
            }            
            return back()->with('success', 'Profile Updated Successfully.');
        }            
        
        public function submitcompliance(Request $request)
        {
            $set=Settings::first();
            $xx = User::findOrFail(Auth::guard('user')->user()->id);
            $com = Compliance::whereuser_id(Auth::guard('user')->user()->id)->first();
            $com->trading_name=$request->trading_name;
            $com->description=$request->trading_desc;
            $com->staff_size=$request->staff_size;
            $com->industry=$request->industry;
            $com->category=$request->category;
            $com->business_type=$request->business_type;
            $com->legal_name=$request->legal_name;
            $com->registration_type=$request->registration_type;
            $com->first_name=$request->first_name;
            $com->last_name=$request->last_name;
            $com->month=$request->b_month;
            $com->day=$request->b_day;
            $com->phone=$request->phone;
            $com->gender=$request->gender;
            $com->day=$request->b_day;
            $com->year=$request->b_year;
            $com->address=$request->address;
            $com->nationality=$request->nationality;
            $com->id_type=$request->id_type;
            $com->vat_id=$request->vat_id;
            $com->tax_id=$request->tax_id;
            $com->reg_no=$request->reg_no;
            $com->email=$request->email;
            $com->website=$request->website;
            $com->status=1;
            if ($request->hasFile('proof')) {
                $image = $request->file('proof');
                $filename = 'proof'.time().'.'.$image->extension();
                $location = 'asset/profile/' . $filename;
                if ($com->proof != null) {
                    $path = './asset/profile/';
                    $link = $path . $com->proof;
                    if (file_exists($link)) {
                        @unlink($link);
                    }
                }
                Image::make($image)->save($location);
                $com->proof=$filename;
            }             
            if ($request->hasFile('proof_back')) {
                $image = $request->file('proof_back');
                $filename = 'proof_back'.time().'.'.$image->extension();
                $location = 'asset/profile/' . $filename;
                if ($com->proof_back != null) {
                    $path = './asset/profile/';
                    $link = $path . $com->proof_back;
                    if (file_exists($link)) {
                        @unlink($link);
                    }
                }
                Image::make($image)->save($location);
                $com->proof_back=$filename;
            }            
            if ($request->hasFile('idcard')) {
                $image = $request->file('idcard');
                $filename = 'idcard_back'.time().'.'.$image->extension();
                $location = 'asset/profile/' . $filename;
                if ($com->idcard != null) {
                    $path = './asset/profile/';
                    $link = $path . $com->idcard;
                    if (file_exists($link)) {
                        @unlink($link);
                    }
                }
                Image::make($image)->save($location);
                $com->idcard=$filename;
            }               
            if ($request->hasFile('idcard_back')) {
                $image = $request->file('idcard_back');
                $filename = 'idcard_back'.time().'.'.$image->extension();
                $location = 'asset/profile/' . $filename;
                if ($com->idcard_back != null) {
                    $path = './asset/profile/';
                    $link = $path . $com->idcard_back;
                    if (file_exists($link)) {
                        @unlink($link);
                    }
                }
                Image::make($image)->save($location);
                $com->idcard_back=$filename;
            }            
            if ($request->hasFile('paddress')) {
                $image = $request->file('paddress');
                $filename = 'paddress'.time().'.'.$image->extension();
                $location = 'asset/profile/' . $filename;
                if ($com->paddress != null) {
                    $path = './asset/profile/';
                    $link = $path . $com->paddress;
                    if (file_exists($link)) {
                        @unlink($link);
                    }
                }
                Image::make($image)->save($location);
                $com->paddress=$filename;
            }
            $com->save();
            if($set['email_notify']==1){
                send_email($set->support_email, $set->site_name, 'New Compliance request:'. $xx->business_name, "Just submitted a new compliance form, please review it");
            }
            $audit['user_id']=Auth::guard('user')->user()->id;
            $audit['trx']=str_random(16);
            $audit['log']='Updated compliance form';
            Audit::create($audit);                
            return redirect()->route('user.compliance')->with('success', 'We will get back to you.');
        }        
        
        public function social(Request $request)
        {
            $data=User::findOrFail(Auth::guard('user')->user()->id);
            $data->fill($request->all())->save();             
            $data->save();
            return back()->with('success', 'Internet accounts Updated Successfully.');
        }        
        
        public function generateapi(Request $request)
        {
            $data=User::findOrFail(Auth::guard('user')->user()->id);
            $data->public_key='PUB-'.str_random(32);        
            $data->secret_key='SEC-'.str_random(32);        
            $data->save();
            return back();
        }

        public function submit2fa(Request $request)
        {
            $user = User::findOrFail(Auth::guard('user')->user()->id);
            $g=new \Sonata\GoogleAuthenticator\GoogleAuthenticator();
            $secret=$request->vv;
            $set=Settings::first();
            if ($request->type==0) {
                $check=$g->checkcode($user->googlefa_secret, $request->code, 3);
                if($check){
                    $user->fa_status = 0;
                    $user->googlefa_secret = null;
                    $user->save();
                    $audit['user_id']=Auth::guard('user')->user()->id;
                    $audit['trx']=str_random(16);
                    $audit['log']='Deactivated 2fa';
                    Audit::create($audit);
                    if($set->email_notify==1){
                        send_email($user->email, $user->username, 'Two Factor Security Disabled', ' 2FA security on your account was just disabled, contact us immediately if this was not done by you.');
                    }
                    return back()->with('success', '2fa disabled.');
                }else{
                    return back()->with('alert', 'Invalid code.');
                }
            }else{
                $check=$g->checkcode($secret, $request->code, 3);
                if($check){
                    $user->fa_status = 1;
                    $user->googlefa_secret = $request->vv;
                    $user->save();
                    $audit['user_id']=Auth::guard('user')->user()->id;
                    $audit['trx']=str_random(16);
                    $audit['log']='Activated 2fa';
                    Audit::create($audit);
                    if($set->email_notify==1){
                        send_email($user->email, $user->username, 'Two Factor Security Enabled', ' 2FA security on your account was just enabled, contact us immediately if this was not done by you.');
                    }
                    return back()->with('success', '2fa enabled.');
                }else{
                    return back()->with('alert', 'Invalid code.');
                }
            }
        }
    //End of Settings

    //Bank functions 
        public function bank()
        {
            $data['title']='Manage bank accounts';
            $data['bank']=Bank::whereUser_id(Auth::guard('user')->user()->id)->orderBy('id', 'DESC')->paginate(4);
            return view('user.bank.index', $data);
        }    
        public function nobank()
        {
            $data['title']='Add Default Bank Account';
            $data['bnk']=Banksupported::wherecountry_id(Auth::guard('user')->user()->country)->get();
            return view('user.bank.nobank', $data);
        }
        public function Destroybank($id)
        {
            $data = Bank::findOrFail($id);
            $check=Withdraw::wherestatus(0)->wherebank_id($id)->count();
            if($check>0){
                return back()->with('alert', 'You cannot delete this bank account as it has pending withdraw request assigned to it!');
            }else{
                if($data->status==1){
                    return back()->with('alert', 'Default account cannot be deleted');
                }else{
                    $ww=Withdraw::wherestatus(0)->wherebank_id($id)->first();
                    $ww->bank_id==null;
                    $ww->save();
                    $res =  $data->delete();
                    return back()->with('success', 'Bank account was Successfully deleted!');
                    $res =  $data->delete();
                }
            }
        }    
        public function Defaultbank($id)
        {
            $all = Bank::all();
            foreach($all as $val){
                $val->status=0;
                $val->save();
            }
            $data = Bank::findOrFail($id);
            $data->status=1;
            $res =  $data->save();
            if ($res) {
                return back()->with('success', 'Bank account was Successfully Updated!');
            } else {
                return back()->with('alert', 'Problem With Request');
            }
        } 
        public function Updatebank(Request $request)
        {
            $set=Settings::first();
            if($set->stripe_connect==1){
                $gate = Gateway::find(103);
                $stripe = new StripeClient($gate->val2);
                try {
                    $country=Country::whereid(Auth::guard('user')->user()->country)->first();
                    $currency=Currency::whereStatus(1)->first();
                    $charge=$stripe->accounts->update(Auth::guard('user')->user()->stripe_id,[
                            'external_account' => [
                            'object' => 'bank_account',
                            'country' => $country->iso,
                            'currency' => $currency->name,
                            'account_holder_name' => $request->acct_name,
                            'account_holder_type' => $request->account_type,
                            'routing_number' => $request->routing_number,
                            'account_number' => $request->acct_no,
                        ],
                    ]);
                    $bank=Bank::whereId($request->id)->first();
                    $bank->bank_id=$request->bank;
                    $bank->acct_no=$request->acct_no;
                    $bank->acct_name=$request->acct_name;
                    $bank->account_type=$request->account_type;
                    $bank->routing_number=$request->routing_number;
                    $bank->save();
                    return back()->with('success', 'Bank details was successfully updated');
                } catch (\Stripe\Exception\RateLimitException $e) {
                    return back()->with('alert', $e->getMessage());
                } catch (\Stripe\Exception\InvalidRequestException $e) {
                    return back()->with('alert', $e->getMessage());
                } catch (\Stripe\Exception\AuthenticationException $e) {
                    return back()->with('alert', $e->getMessage());
                } catch (\Stripe\Exception\ApiConnectionException $e) {
                    return back()->with('alert', $e->getMessage());
                } catch (\Stripe\Exception\ApiErrorException $e) {
                    return back()->with('alert', $e->getMessage());
                } catch (Exception $e) {
                    return back()->with('alert', $e->getMessage());
                }
            }else{
                $bank=Bank::whereId($request->id)->first();
                $bank->bank_id=$request->bank;
                $bank->acct_no=$request->acct_no;
                $bank->acct_name=$request->acct_name;
                $bank->account_type=$request->account_type;
                $bank->routing_number=$request->routing_number;
                $bank->save();
                return back()->with('success', 'Bank details was successfully updated');
            }
        }
        public function Createbank(Request $request)
        {  
            $set=Settings::first();
            if($set->stripe_connect==1){
                $gate = Gateway::find(103);
                $stripe = new StripeClient($gate->val2);
                try {
                    $country=Country::whereid(Auth::guard('user')->user()->country)->first();
                    $currency=Currency::whereStatus(1)->first();
                    $charge=$stripe->accounts->update(Auth::guard('user')->user()->stripe_id,[
                            'external_account' => [
                            'object' => 'bank_account',
                            'country' => $country->iso,
                            'currency' => $currency->name,
                            'account_holder_name' => $request->acct_name,
                            'account_holder_type' => $request->account_type,
                            'routing_number' => $request->routing_number,
                            'account_number' => $request->acct_no,
                        ],
                    ]);
                    $data['acct_no']=$request->acct_no;
                    $data['acct_name']=$request->acct_name;
                    $data['account_type']=$request->account_type;
                    $data['routing_number']=$request->routing_number;
                    $data['bank_id']=$request->bank;
                    $data['user_id']=Auth::guard('user')->user()->id;
                    $all = Bank::whereuser_id(Auth::guard('user')->user()->id)->wherestatus(1)->get();
                    if(count($all)<1){
                        $data['status']=1; 
                    }
                    Bank::create($data);
                    return redirect()->route('user.bank')->with('success', 'Bank account was successfully added.');
                } catch (\Stripe\Exception\RateLimitException $e) {
                    return back()->with('alert', $e->getMessage());
                } catch (\Stripe\Exception\InvalidRequestException $e) {
                    return back()->with('alert', $e->getMessage());
                } catch (\Stripe\Exception\AuthenticationException $e) {
                    return back()->with('alert', $e->getMessage());
                } catch (\Stripe\Exception\ApiConnectionException $e) {
                    return back()->with('alert', $e->getMessage());
                } catch (\Stripe\Exception\ApiErrorException $e) {
                    return back()->with('alert', $e->getMessage());
                } catch (Exception $e) {
                    return back()->with('alert', $e->getMessage());
                }
            }else{
                $data['acct_no']=$request->acct_no;
                $data['acct_name']=$request->acct_name;
                $data['account_type']=$request->account_type;
                $data['routing_number']=$request->routing_number;
                $data['bank_id']=$request->bank;
                $data['user_id']=Auth::guard('user')->user()->id;
                $all = Bank::whereuser_id(Auth::guard('user')->user()->id)->wherestatus(1)->get();
                if(count($all)<1){
                    $data['status']=1; 
                }
                Bank::create($data);
                return redirect()->route('user.bank')->with('success', 'Bank account was successfully added.');
            } 
        }
    //End of bank functions

    //Charges
        public function charges()
        {
            $data['title']='Charges';
            $data['charges']=Charges::whereuser_id(Auth::guard('user')->user()->id)->latest()->get();
            return view('user.profile.charges', $data);
        }        
        
        public function chargeback()
        {
            $data['title']='Charge Backs';
            $data['charges']=History::whereuser_id(Auth::guard('user')->user()->id)->wherechargeback(1)->latest()->get();
            return view('user.profile.chargeback', $data);
        }
    //End of Charges   
    
    //Plans & Subscription
        public function plans()
        {
            $data['title']='Plans';
            $data['plans']=Plans::whereuser_id(Auth::guard('user')->user()->id)->paginate(6);
            return view('user.plans.index', $data);
        }      
        
        public function xsubmitplancharge(Request $request)
        {
            $data['title']='Plans';
            $data['plans']=Plans::whereuser_id(Auth::guard('user')->user()->id)->paginate(6);
            return view('user.plans.index', $data);
        }
        public function unplan($id)
        {
            $page=Plans::find($id);
            $active=Subscribers::whereplan_id($page->id)->where('expiring_date', '>', Carbon::now())->count();
            if($active>0){
                return back()->with('alert', 'You already have active subscribers.');
            }else{
                $page->active=0;
                $page->save();
                return back()->with('success', 'Plan has been disabled.');
            }
        } 
        public function pplan($id)
        {
            $page=Plans::find($id);
            $page->active=1;
            $page->save();
            return back()->with('success', 'Plan has been activated.');
        }  
        public function submitplan(Request $request)
        {
            $trx='SUB-'.str_random(6);
            $sav['ref_id']=$trx;
            $sav['amount']=$request->amount;
            $sav['name']=$request->name;
            $sav['times']=$request->times;
            $sav['intervals']=$request->interval;
            $sav['user_id']=Auth::guard('user')->user()->id; 
            Plans::create($sav);   
            $audit['user_id']=Auth::guard('user')->user()->id;
            $audit['trx']=$trx;
            $audit['log']='Created Plan -  '.$request->name;
            Audit::create($audit);
            $his['user_id']=Auth::guard('user')->user()->id;
            $his['ref']=$trx;
            $his['main']=1;
            $his['type']=1;
            History::create($his);
            return redirect()->route('user.plan')->with('success', 'Plan was successfully created');
                
        }        
        public function updateplan(Request $request)
        {
            $plan=Plans::whereId($request->plan_id)->first();
            $active=Subscribers::whereplan_id($plan->id)->where('expiring_date', '>', Carbon::now())->count();
            if($active<1){
                $plan->amount=$request->amount;
                $plan->intervals=$request->interval;
            }
            $plan->name=$request->name;
            $plan->save();
            return back()->with('success', 'Successfully updated');
                
        }        
        public function submitplancharge(Request $request)
        {
            $set=Settings::first();
            $currency=Currency::whereStatus(1)->first();
            $amount=$request->amount;
            $user=User::find(Auth::guard('user')->user()->id);
            $check=Subscribers::whereuser_id($user->id)->whereplan_id($request->link)->get();
            $key=Subscribers::whereuser_id($user->id)->whereplan_id($request->link)->first();
            $link=Plans::whereid($request->link)->first();
            $xtoken='SUB-'.str_random(6);
            if(count($check)>0){
                if($key->expiring_date>Carbon::now()){
                    return back()->with('alert', 'You already have an active subscription');
                }else{
                    $receiver=User::whereid($link->user_id)->first();
                    if($user->balance>$request->amount || $user->balance==$request->amount){
                        $user->balance=$user->balance-$request->amount;
                        $user->save();        
                        $receiver->balance=$receiver->balance+(($request->amount)-($request->amount*$set->subscription_charge/100+($set->subscription_chargep)));
                        $receiver->save();
                        //Audit log
                        $audit['user_id']=$user->id;
                        $audit['trx']=str_random(16);
                        $audit['log']='Payment for subscription #'.$link->ref_id.' - '.$link->name.' was successful';
                        Audit::create($audit);                
                        $audit['user_id']=$receiver->id;
                        $audit['trx']=str_random(16);
                        $audit['log']='Received payment for subscription #'.$link->ref_id.' - '.$link->name.' was successful';
                        Audit::create($audit);
                        //Charges
                        $charge['user_id']=$receiver->id;
                        $charge['ref_id']=$xtoken;
                        $charge['amount']=$request->amount*$set->subscription_charge/100+($set->subscription_chargep);
                        $charge['log']='Received payment for subscription #'.$link->ref_id.' - '.$link->name.' was successful';
                        Charges::create($charge);
                        $his['user_id']=$receiver->id;
                        $his['amount']=$request->amount-($request->amount*$set->subscription_charge/100+($set->subscription_chargep));
                        $his['ref']=$xtoken;
                        $his['main']=0;
                        $his['type']=1;
                        History::create($his);
                        //Change status to successful
                        $change=Subscribers::whereuser_id($user->id)->whereplan_id($request->link)->first();
                        $change->status=$request->status;
                        $change->charge=$request->amount*$set->subscription_charge/100+($set->subscription_chargep);
                        $dt = Carbon::create($change->expiring_date);
                        $dt->add($change->plan['intervals']);   
                        $change->expiring_date=$dt;
                        if($change->times!=0){
                            $change->times=$change->times-1;
                        }
                        $change->save(); 
                        //Notify users
                        if($set->email_notify==1){
                            new_subscription($link->ref_id, 'account', $xtoken);
                        } 
                        return redirect()->route('user.mysub')->with('success', 'Payment was successful');
                    }else{
                        return back()->with('alert', 'Insufficient balance, please fund your account');
                    }
                }
            }else{
                $receiver=User::whereid($link->user_id)->first();
                if($user->balance>$request->amount || $user->balance==$request->amount){
                    $user->balance=$user->balance-$request->amount;
                    $user->save();        
                    $receiver->balance=$receiver->balance+(($request->amount)-($request->amount*$set->subscription_charge/100+($set->subscription_chargep)));
                    $receiver->save();
                    //Audit log
                    $audit['user_id']=$user->id;
                    $audit['trx']=str_random(16);
                    $audit['log']='Payment for subscription #'.$link->ref_id.' - '.$link->name.' was successful';
                    Audit::create($audit);                
                    $audit['user_id']=$receiver->id;
                    $audit['trx']=str_random(16);
                    $audit['log']='Received payment for subscription #'.$link->ref_id.' - '.$link->name.' was successful';
                    Audit::create($audit);
                    //Charges
                    $charge['user_id']=$receiver->id;
                    $charge['ref_id']=$xtoken;
                    $charge['amount']=$request->amount*$set->subscription_charge/100+($set->subscription_chargep);
                    $charge['log']='Received payment for subscription #'.$link->ref_id.' - '.$link->name.' was successful';
                    Charges::create($charge);
                    $his['user_id']=$receiver->id;
                    $his['amount']=$request->amount-($request->amount*$set->subscription_charge/100+($set->subscription_chargep));
                    $his['ref']=$xtoken;
                    $his['main']=0;
                    $his['type']=1;
                    History::create($his);
                    //Register subscription
                    $dt = Carbon::now();
                    $dt->add($link->intervals);  
                    $sav['ref_id']=$xtoken;
                    $sav['charge']=$request->amount*$set->subscription_charge/100+($set->subscription_chargep); 
                    $sav['amount']=$request->amount-($request->amount*$set->subscription_charge/100+($set->subscription_chargep));
                    $sav['user_id']=$user->id;
                    $sav['merchant_id']=$link->user_id;
                    $sav['plan_id']=$link->id;
                    $sav['expiring_date']=$dt;
                    $sav['status']=$request->status;
                    $sav['times']=$link->times;
                    Subscribers::create($sav);
                    //Notify users
                    if($set->email_notify==1){
                        new_subscription($link->ref_id, 'account', $xtoken);
                    } 
                    return redirect()->route('user.mysub')->with('success', 'Payment was successful');
                }else{
                    return back()->with('alert', 'Insufficient balance, please fund your account');
                }
            }  
        } 
        public function plansub($id)
        {
            $data['plan']=$plan=Plans::whereref_id($id)->first();
            $data['sub']=Subscribers::whereplan_id($plan->id)->latest()->get();
            $data['title']=$plan->ref_id;
            return view('user.plans.subscribers', $data);
        }        
        public function subscriptions()
        {
            $data['sub']=Subscribers::wheremerchant_id(Auth::guard('user')->user()->id)->latest()->get();
            $data['title']='Subscriptions';
            return view('user.plans.subscription', $data);
        }
        public function subviewlink($id)
        {
            $check=Plans::whereref_id($id)->get();
            if(count($check)>0){
                $key=Plans::whereref_id($id)->first();
                if($key->user->status==0){
                    if($key->status==0){
                        if($key->active==1){
                            $data['link']=$link=Plans::whereref_id($id)->first();
                            $data['merchant']=$user=User::find($link->user_id);
                            $set=Settings::first();
                            $data['title']="Plan - ".$link->name;
                            return view('user.plans.sub_view', $data);
                        }else{
                            $data['title']='Error Occured';
                            return view('user.merchant.error', $data)->withErrors('Plan has been disabled');
                        }    
                    }else{
                        $data['title']='Error Occured';
                        return view('user.merchant.error', $data)->withErrors('Plan has been suspended');
                    }
                }else{
                    $data['title']='Error Message';
                    return view('user.merchant.error', $data)->withErrors('An Error Occured');
                }
            }else{
                $data['title']='Error Message';
                return view('user.merchant.error', $data)->withErrors('Invalid payment link');
            }
        }
    //End of Plans
    
    //Transaction Logs
        public function transactions()
        {
            $data['title']='Transactions';
            $user=Auth::guard('user')->user()->id;
            $data['single']=Transactions::wheresender_id($user)->wheretype(1)->orWhere('receiver_id', $user)->where('type', 1)->latest()->get();
            $data['donation']=Transactions::wheresender_id($user)->wheretype(2)->orWhere('receiver_id', $user)->where('type', 2)->latest()->get();
            $data['invoice']=Transactions::wheresender_id($user)->wheretype(3)->orWhere('receiver_id', $user)->where('type', 3)->latest()->get();
            $data['bank_transfer']=Banktransfer::whereUser_id(Auth::guard('user')->user()->id)->latest()->get();
            $data['deposits']=Deposits::whereUser_id(Auth::guard('user')->user()->id)->latest()->get();
            $data['ext']=Exttransfer::whereuser_id($user)->orWhere('receiver_id', $user)->latest()->get();
            $data['sub']=Subscribers::whereuser_id(Auth::guard('user')->user()->id)->latest()->get();
            return view('user.transactions.index', $data);
        }        
    //End of Logs    
    
    //Sub Accounts
        public function subaccounts()
        {
            $data['title']='Sub Account';
            $data['sub']=Subaccounts::whereUser_id(Auth::guard('user')->user()->id)->orderBy('created_at', 'DESC')->paginate(6);
            $data['country']=Countrysupported::wherestatus(1)->get();
            return view('user.profile.sub', $data);
        }        
        
        public function subaccttrans($id)
        {
            $data['title'] = "Transaction History";
            $data['withdraw']=Withdraw::wheresub_id($id)->latest()->paginate(9);
            return view('user.profile.subaccttrans', $data);
        } 

        public function unsubacct($id)
        {
            $acct=Subaccounts::find($id);
            $check=Withdraw::wherestatus(0)->wheresub_id($id)->count();
            if($check>0){
                return back()->with('alert', 'You cannot disable this sub account as it has pending withdraw request assigned to it!');
            }else{
                $acct->active=0;
                $acct->save();
                return back()->with('success', 'Sub account has been disabled.');
            }
        } 

        public function psubacct($id)
        {
            $acct=Subaccounts::find($id);
            $acct->active=1;
            $acct->save();
            return back()->with('success', 'Sub account has been activated.');
        }

        public function newsubaccount()
        {
            $data['title']='Create Sub Account';
            $data['country']=Countrysupported::wherestatus(1)->get();
            $data['bnk']=Banksupported::wherecountry_id(Session::get('country'))->get();
            return view('user.profile.new-sub', $data);
        }
        
        public function Createsubacct(Request $request)
        {
            Session::put('name', $request->subname);
            Session::put('email', $request->subemail);
            Session::put('country', $request->xcountry);
            Session::put('type', $request->type);
            return redirect()->route('user.new.subaccount');
        }        
        
        public function Createsubacct2(Request $request)
        {
            $set=Settings::first();
            $currency=Currency::whereStatus(1)->first();
            if($set->stripe_connect==1){
                $gate = Gateway::find(103);
                $stripe = new StripeClient($gate->val2);
                try {
                    $charge=$stripe->accounts->create([
                        'type' => 'custom',
                        'country' => $country->iso,
                        'email' => Session::get('email'),
                        'business_type' => 'individual',
                        'capabilities' => [
                            'card_payments' => ['requested' => true],
                            'transfers' => ['requested' => true],
                        ],
                        'external_account' => [
                            'object' => 'bank_account',
                            'country' => $country->iso,
                            'currency' => $currency->name,
                            'account_holder_name' => $request->acct_name,
                            'account_holder_type' => $request->account_type,
                            'routing_number' => $request->routing_number,
                            'account_number' => $request->acct_no,
                        ],
                    ]);
                    $data['name']=Session::get('name');
                    $data['email']=Session::get('email');
                    $data['bank_id']=$request->bank;
                    $data['acct_no']=$request->acct_no;
                    $data['acct_name']=$request->acct_name;
                    $data['account_type']=$request->account_type;
                    $data['routing_number']=$request->routing_number;
                    $data['currency']=$currency->name;
                    $data['type']=Session::get('type');
                    $data['country']=Session::get('country');
                    $data['stripe_id']=$charge['id'];
                    if($request->has('flat_share')){
                        $data['amount']=$request->flat_share;
                    }elseif($request->has('percent_share')){
                        $data['amount']=$request->percent_share;
                        $old=Subaccounts::whereuser_id()->wheretype(2)->sum('amount');
                        $total=$request->percent_share+$old;
                        if($total>90){
                            return redirect()->route('user.subaccounts')->with('alert', 'You have exceed Maximum Percent Share Sub Accounts.');
                        }
                    }
                    $data['user_id']=Auth::guard('user')->user()->id;
                    Subaccounts::create($data);
                    return redirect()->route('user.subaccounts')->with('success', 'Sub account was successfully added.');
                } catch (\Stripe\Exception\RateLimitException $e) {
                    return back()->with('alert', $e->getMessage());
                } catch (\Stripe\Exception\InvalidRequestException $e) {
                    return back()->with('alert', $e->getMessage());
                } catch (\Stripe\Exception\AuthenticationException $e) {
                    return back()->with('alert', $e->getMessage());
                } catch (\Stripe\Exception\ApiConnectionException $e) {
                    return back()->with('alert', $e->getMessage());
                } catch (\Stripe\Exception\ApiErrorException $e) {
                    return back()->with('alert', $e->getMessage());
                } catch (Exception $e) {
                    return back()->with('alert', $e->getMessage());
                }
            }else{
                $data['name']=Session::get('name');
                $data['email']=Session::get('email');
                $data['bank_id']=$request->bank;
                $data['acct_no']=$request->acct_no;
                $data['acct_name']=$request->acct_name;
                $data['account_type']=$request->account_type;
                $data['routing_number']=$request->routing_number;
                $data['currency']=$currency->name;
                $data['type']=Session::get('type');
                $data['country']=Session::get('country');
                if($request->has('flat_share')){
                    $data['amount']=$request->flat_share;
                }elseif($request->has('percent_share')){
                    $data['amount']=$request->percent_share;
                    $old=Subaccounts::whereuser_id(Auth::guard('user')->user()->id)->wheretype(2)->sum('amount');
                    $total=$request->percent_share+$old;
                    if($total>90){
                        return redirect()->route('user.subaccounts')->with('alert', 'You have exceed Maximum Percent Share Sub Accounts.');
                    }
                }
                $data['user_id']=Auth::guard('user')->user()->id;
                Subaccounts::create($data);
                return redirect()->route('user.subaccounts')->with('success', 'Sub account was successfully added.');
            }
        }

        public function Destroysubacct($id)
        {
            $set=Settings::first();
            if($set->stripe_connect==1){
                $gate = Gateway::find(103);
                $stripe = new StripeClient($gate->val2);
                $data = Subaccounts::findOrFail($id);
                $stripe->accounts->delete(
                    $data->stripe_id,
                );
                $check=Withdraw::wherestatus(0)->wheresub_id($id)->count();
                if($check>0){
                    return back()->with('alert', 'You cannot delete this sub account as it has pending withdraw request assigned to it!');
                }else{
                    $ww=Withdraw::wherestatus(0)->wheresub_id($id)->first();
                    $ww->sub_id==null;
                    $ww->save();
                    $res =  $data->delete();
                    return back()->with('success', 'Sub account was Successfully deleted!');
                }
            }else{
                $data = Subaccounts::findOrFail($id);
                $check=Withdraw::wherestatus(0)->wheresub_id($id)->count();
                if($check>0){
                    return back()->with('alert', 'You cannot delete this sub account as it has pending withdraw request assigned to it!');
                }else{
                    $ww=Withdraw::wherestatus(0)->wheresub_id($id)->first();
                    $ww->sub_id==null;
                    $ww->save();
                    $res =  $data->delete();
                    return back()->with('success', 'Sub account was Successfully deleted!');
                }
            }
        } 
        public function Updatesubacct(Request $request)
        {
            $set=Settings::first();
            if($set->stripe_connect==1){
                $gate = Gateway::find(103);
                $stripe = new StripeClient($gate->val2);
                try {
                    $country=Country::whereid(Auth::guard('user')->user()->country)->first();
                    $currency=Currency::whereStatus(1)->first();
                    $bank=Subaccounts::whereId($request->id)->first();
                    $charge=$stripe->accounts->update($bank->stripe_id,[
                            'external_account' => [
                            'object' => 'bank_account',
                            'country' => $country->iso,
                            'currency' => $currency->name,
                            'account_holder_name' => $request->acct_name,
                            'account_holder_type' => $request->account_type,
                            'routing_number' => $request->routing_number,
                            'account_number' => $request->acct_no,
                        ],
                    ]);
                    $bank->name=$request->subname;
                    $bank->bank=$request->name;
                    $bank->acct_no=$request->acct_no;
                    $bank->acct_name=$request->acct_name;
                    $bank->swift_code=$request->swift;
                    $bank->account_type=$request->account_type;
                    $bank->routing_number=$request->routing_number;
                    $bank->save();
                    return back()->with('success', 'Sub account was successfully updated');
                } catch (\Stripe\Exception\RateLimitException $e) {
                    return back()->with('alert', $e->getMessage());
                } catch (\Stripe\Exception\InvalidRequestException $e) {
                    return back()->with('alert', $e->getMessage());
                } catch (\Stripe\Exception\AuthenticationException $e) {
                    return back()->with('alert', $e->getMessage());
                } catch (\Stripe\Exception\ApiConnectionException $e) {
                    return back()->with('alert', $e->getMessage());
                } catch (\Stripe\Exception\ApiErrorException $e) {
                    return back()->with('alert', $e->getMessage());
                } catch (Exception $e) {
                    return back()->with('alert', $e->getMessage());
                }
            }else{
                $bank=Subaccounts::whereId($request->id)->first();
                $bank->name=$request->subname;
                $bank->bank=$request->name;
                $bank->acct_no=$request->acct_no;
                $bank->acct_name=$request->acct_name;
                $bank->swift_code=$request->swift;
                $bank->account_type=$request->account_type;
                $bank->routing_number=$request->routing_number;
                $bank->save();
                return back()->with('success', 'Sub account was successfully updated'); 
            }
        }
    //End of Sub Accounts

}

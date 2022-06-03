<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Mews\Purifier\Facades\Purifier;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\Admin;
use App\Models\Settings;
use App\Models\Logo;
use App\Models\Branch;
use App\Models\Bank;
use App\Models\Currency;
use App\Models\Transfer;
use App\Models\Adminbank;
use App\Models\Gateway;
use App\Models\Deposits;
use App\Models\Banktransfer;
use App\Models\Withdraw;
use App\Models\Withdrawm;
use App\Models\Merchant;
use App\Models\Social;
use App\Models\About;
use App\Models\Faq;
use App\Models\Page;
use App\Models\Contact;
use App\Models\Ticket;
use App\Models\Reply;
use App\Models\Review;
use App\Models\Product;
use App\Models\Order;
use App\Models\Invoice;
use App\Models\Charges;
use App\Models\Exttransfer;
use App\Models\Requests;
use App\Models\Transactions;
use App\Models\Donations;
use App\Models\Paymentlink;
use App\Models\Plans;
use App\Models\Subscribers;
use App\Models\Audit;
use App\Models\Staff;
use App\Models\Virtual;
use App\Models\Billtransactions;
use App\Models\Virtualtransactions;
use App\Models\Sellcard;
use App\Models\Btctrades;
use App\Models\History;
use App\Models\Compliance;
use App\Models\Productcategory;
use App\Models\Storefront;
use App\Models\Storefrontproducts;
use App\Models\Shipping;
use App\Models\Subaccounts;
use App\Models\Cart;
use Carbon\Carbon;
use Stripe\Stripe;
use Stripe\Token;
use Stripe\Charge;
use Stripe\StripeClient;
use Image;





class CheckController extends Controller
{

        
    public function __construct()
    {		
        $this->middleware('auth');
    }

    public function vcard(){
        $data['title']='Virtual Cards';
        $data['card']=Virtual::orderBy('created_at', 'DESC')->get();
        return view('admin.virtual.index', $data);
    }    
    
    public function bpay(){
        $data['title']='Bill payment';
        $data['trans']=Billtransactions::orderBy('created_at', 'DESC')->get();
        return view('admin.bill.index', $data);
    }

    public function transactionsvcard($id){
        $data['title']='Transaction History';
        $val=Virtual::wherecard_hash($id)->first();
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
        return view('admin.virtual.log', $data);
    }

    public function Destroyuser($id)
    {
        $check=User::whereid($id)->first();
        $set=Settings::first();
        if($set->stripe_connect==1){
            if($check->stripe_id!=null){
                $gate = Gateway::find(103);
                $stripe = new StripeClient($gate->val2);
                try{
                    $charge=$stripe->accounts->delete(
                        $check->stripe_id,
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
        $transfer = Transfer::where('Receiver_id', $id)->orWhere('Temp', $check->email)->delete();
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
        $his = History::whereUser_id($id)->delete();
        $trans = Transactions::where('Receiver_id', $id)->orWhere('Sender_id', $id)->delete();
        $com = Compliance::whereUser_id($id)->delete();
        $sa = Subaccounts::whereUser_id($id)->delete();
        $store = Storefront::whereUser_id($id)->delete();
        $ship = Shipping::whereUser_id($id)->delete();
        $pro = Productcategory::whereUser_id($id)->delete();
        $vr = Virtual::whereUser_id($id)->delete();
        $vtt = Virtualtransactions::whereUser_id($id)->delete();
        $bill = Billtransactions::whereUser_id($id)->delete();
        $user = User::whereId($id)->delete();
        return back()->with('success', 'User was successfully deleted');
    }     
    
    public function Destroystaff($id)
    {
        $staff = Admin::whereId($id)->delete();
        return back()->with('success', 'Staff was successfully deleted');
    }  
        
    public function dashboard()
    {
        $data['title']='Dashboard';
        $data['received']=Charges::sum('amount');
        $data['wd']=Withdraw::whereStatus(1)->sum('amount');
        $data['wdc']=Withdraw::whereStatus(1)->sum('charge');
        $data['mer']=Exttransfer::whereStatus(1)->sum('amount');
        $data['merc']=Exttransfer::whereStatus(1)->sum('charge');        
        $data['in']=Invoice::whereStatus(1)->sum('amount');
        $data['inc']=Invoice::whereStatus(1)->sum('charge');        
        $data['req']=Requests::whereStatus(1)->sum('amount');
        $data['reqc']=Requests::whereStatus(1)->sum('charge');        
        $data['tran']=Transfer::whereStatus(1)->sum('amount');
        $data['tranc']=Transfer::whereStatus(1)->sum('charge');        
        $data['sin']=Transactions::whereStatus(1)->wheretype(1)->sum('amount');
        $data['sinc']=Transactions::whereStatus(1)->wheretype(1)->sum('charge');        
        $data['do']=Transactions::whereStatus(1)->wheretype(2)->sum('amount');
        $data['doc']=Transactions::whereStatus(1)->wheretype(2)->sum('charge');        
        $data['or']=Order::whereStatus(1)->sum('total');
        $data['orc']=Order::whereStatus(1)->sum('charge');        
        $data['de']=Deposits::whereStatus(1)->sum('amount');
        $data['dec']=Deposits::whereStatus(1)->sum('charge');
        $data['totalusers']=User::count();
        $data['blockedusers']=User::whereStatus(1)->count();
        $data['activeusers']=User::whereStatus(0)->count();
        return view('admin.dashboard.index', $data);
    }    
    
    public function Users()
    {
		$data['title']='Clients';
		$data['users']=User::latest()->get();
        return view('admin.user.index', $data);
    }    
    
    public function Staffs()
    {
		$data['title']='Staffs';
		$data['users']=Admin::where('id', '!=', 1)->latest()->get();
        return view('admin.user.staff', $data);
    }       

    public function Messages()
    {
		$data['title']='Messages';
		$data['message']=Contact::latest()->get();
        return view('admin.user.message', $data);
    }     

    public function Newstaff()
    {
		$data['title']='New Staff';
        return view('admin.user.new-staff', $data);
    }    
    
    public function Ticket()
    {
		$data['title']='Ticket system';
		$data['ticket']=Ticket::latest()->get();
        return view('admin.user.ticket', $data);
    }   
    
    public function Email($id,$name)
    {
		$data['title']='Send email';
		$data['email']=$id;
		$data['name']=$name;
        return view('admin.user.email', $data);
    }    
    
    public function Promo()
    {
		$data['title']='Send email';
        $data['client']=$user=User::all();
        return view('admin.user.promo', $data);
    } 
    
    public function Sendemail(Request $request)
    {        	
        $set=Settings::first();
        send_email($request->to, $request->name, $request->subject, $request->message);  
        return back()->with('success', 'Mail Sent Successfuly!');
    }
    
    public function Sendpromo(Request $request)
    {        	
        $set=Settings::first();
        $user=User::all();
        foreach ($user as $val) {
            $x=User::whereEmail($val->email)->first();
            if($set->email_notify==1){
                send_email($x->email, $x->username, $request->subject, $request->message);
            }
        }      
        return back()->with('success', 'Mail Sent Successfuly!');
    }     
    
    public function Replyticket(Request $request)
    {        
        $data['ticket_id'] = $request->ticket_id;
        $data['reply'] = $request->reply;
        $data['status'] = 0;
        $data['staff_id'] = $request->staff_id;
        $res = Reply::create($data);  
        $set=Settings::first();   
        $ticket=Ticket::whereticket_id($request->ticket_id)->first();
        $user=User::find($ticket->user_id);
        if($set['email_notify']==1){
            send_email($user->email, $user->username, 'New Reply - '.$request->ticket_id, $request->reply);
        } 
        if ($res) {
            return back();
        } else {
            return back()->with('alert', 'An error occured');
        }
    }    
    
    public function Createstaff(Request $request)
    {        
        $check=Admin::whereusername($request->username)->get();
        if(count($check)<1){
            $data['username'] = $request->username;
            $data['last_name'] = $request->last_name;
            $data['first_name'] = $request->first_name;
            $data['password'] = Hash::make($request->password);
            $data['profile'] = $request->profile;
            $data['support'] = $request->support;
            $data['promo'] = $request->promo;
            $data['message'] = $request->message;
            $data['deposit'] = $request->deposit;
            $data['settlement'] = $request->settlement;
            $data['transfer'] = $request->transfer;
            $data['request_money'] = $request->request_money;
            $data['donation'] = $request->donation;
            $data['single_charge'] = $request->single_charge;
            $data['subscription'] = $request->subscription;
            $data['merchant'] = $request->merchant;
            $data['invoice'] = $request->invoice;
            $data['charges'] = $request->charges;
            $data['store'] = $request->store;
            $data['blog'] = $request->blog;
            $data['bill'] = $request->bill;
            $data['vcard'] = $request->vcard;
            $res = Admin::create($data);  
            return redirect()->route('admin.staffs')->with('success', 'Staff was successfully created');
        }else{
            return back()->with('alert', 'username already taken');
        }
    }       
     
    
    public function Destroymessage($id)
    {
        $data = Contact::findOrFail($id);
        $res =  $data->delete();
        if ($res) {
            return back()->with('success', 'Request was Successfully deleted!');
        } else {
            return back()->with('alert', 'Problem With Deleting Request');
        }
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

    public function Manageuser($id)
    {
        $data['client']=$user=User::find($id);
        $data['title']=$user->business_name;
        $data['deposit']=Deposits::whereUser_id($user->id)->orderBy('id', 'DESC')->get();
        $data['transfer']=Transfer::wheresender_id($user->id)->orderBy('id', 'DESC')->get();
        $data['withdraw']=Withdraw::whereUser_id($user->id)->orderBy('id', 'DESC')->get();
        $data['ticket']=Ticket::whereUser_id($user->id)->orderBy('id', 'DESC')->get();
        $data['audit']=Audit::whereUser_id($user->id)->orderBy('created_at', 'DESC')->get();
        $data['xver']=Compliance::whereUser_id($user->id)->first();
        return view('admin.user.edit', $data);
    }    
    
    public function Managestaff($id)
    {
        $data['staff']=$user=Admin::find($id);
        $data['title']=$user->username;
        return view('admin.user.edit-staff', $data);
    }    

    public function staffPassword(Request $request)
    {
        $user = Admin::whereid($request->id)->first();
        $user->password = Hash::make($request->password);
        $user->save();
        return back()->with('success', 'Password Changed Successfully.');

    }
    
    public function Manageticket($id)
    {
        $data['ticket']=$ticket=Ticket::find($id);
        $data['title']='#'.$ticket->ticket_id;
        $data['client']=User::whereId($ticket->user_id)->first();
        $data['reply']=Reply::whereTicket_id($ticket->ticket_id)->get();
        return view('admin.user.edit-ticket', $data);
    }    
    
    public function Closeticket($id)
    {
        $ticket=Ticket::find($id);
        $ticket->status=1;
        $ticket->save();
        return back()->with('success', 'Ticket has been closed.');
    }     
    
    public function Blockuser($id)
    {
        $user=User::find($id);
        $user->status=1;
        $user->save();
        return back()->with('success', 'User has been suspended.');
    } 

    public function Unblockuser($id)
    {
        $user=User::find($id);
        $user->status=0;
        $user->save();
        return back()->with('success', 'User was successfully unblocked.');
    }    
    
    public function Blockstaff($id)
    {
        $user=Admin::find($id);
        $user->status=1;
        $user->save();
        return back()->with('success', 'Staff has been suspended.');
    } 

    public function Unblockstaff($id)
    {
        $user=Admin::find($id);
        $user->status=0;
        $user->save();
        return back()->with('success', 'Staff was successfully unblocked.');
    }

    public function Approvekyc($id)
    {
        $set=Settings::first();
        $com=Compliance::whereid($id)->first();
        $user=User::find($com->user_id);
            if($com->business_type=="Starter Business"){
                $user->business_level=2;
            }elseif($com->business_type=="Registered Business"){
                $user->business_level=3;
            }
            $com->status=2;
            $user->save();
            $com->save();
            if($set['email_notify']==1){
                send_email($user->email, $user->business_name, 'Compliance request:'. $user->business_name, "Compliance request was succefully approved, you can now use your account with out restrictions");
            }
            return back()->with('success', 'Compliance has been approved.');
    }    

    public function Rejectkyc($id)
    {
        $com=Compliance::whereid($id)->first();
        $user=User::find($com->user_id);
        $com->status=3;
        $com->proof=null;
        $com->idcard=null;
        $com->save();
        if($set['email_notify']==1){
            send_email($user->email, $user->business_name, 'Compliance request:'. $user->business_name, "Compliance request was declined");
        }
        return back()->with('success', 'Compliance has been declined.');
    }

    public function Profileupdate(Request $request)
    {
        $data = User::findOrFail($request->id);
        $data->business_name=$request->business_name;
        $data->first_name=$request->first_name;
        $data->last_name=$request->last_name;
        $data->phone=$request->mobile;
        $data->office_address=$request->address;
        $data->balance=$request->balance;
        $data->website_link=$request->website;
        if(empty($request->email_verify)){
            $data->email_verify=0;	
        }else{
            $data->email_verify=$request->email_verify;
        }             
        if(empty($request->fa_status)){
            $data->fa_status=0;	
        }else{
            $data->fa_status=$request->fa_status;
        }         
        $res=$data->save();
        if ($res) {
            return back()->with('success', 'Update was Successful!');
        } else {
            return back()->with('alert', 'An error occured');
        }
    }    
    public function Staffupdate(Request $request)
    {
        $data = Admin::whereid($request->id)->first();
        $data->username=$request->username;
        $data->first_name=$request->first_name;
        $data->last_name=$request->last_name;
        if(empty($request->profile)){
            $data->profile=0;	
        }else{
            $data->profile=$request->profile;
        }  

        if(empty($request->support)){
            $data->support=0;	
        }else{
            $data->support=$request->support;
        }    

        if(empty($request->promo)){
            $data->promo=0;	
        }else{
            $data->promo=$request->promo;
        }     

        if(empty($request->message)){
            $data->message=0;	
        }else{
            $data->message=$request->message;
        }     

        if(empty($request->deposit)){
            $data->deposit=0;	
        }else{
            $data->deposit=$request->deposit;
        }     

        if(empty($request->settlement)){
            $data->settlement=0;	
        }else{
            $data->settlement=$request->settlement;
        }     

        if(empty($request->transfer)){
            $data->transfer=0;	
        }else{
            $data->transfer=$request->transfer;
        }     

        if(empty($request->request_money)){
            $data->request_money=0;	
        }else{
            $data->request_money=$request->request_money;
        }               
        
        if(empty($request->donation)){
            $data->donation=0;	
        }else{
            $data->donation=$request->donation;
        }          
        
        if(empty($request->single_charge)){
            $data->single_charge=0;	
        }else{
            $data->single_charge=$request->single_charge;
        }          
        
        if(empty($request->subscription)){
            $data->subscription=0;	
        }else{
            $data->subscription=$request->subscription;
        }          
        
        if(empty($request->merchant)){
            $data->merchant=0;	
        }else{
            $data->merchant=$request->merchant;
        }          
        
        if(empty($request->invoice)){
            $data->invoice=0;	
        }else{
            $data->invoice=$request->invoice;
        }          
        
        if(empty($request->charges)){
            $data->charges=0;	
        }else{
            $data->charges=$request->charges;
        }     

        if(empty($request->store)){
            $data->store=0;	
        }else{
            $data->store=$request->store;
        }   

        if(empty($request->blog)){
            $data->blog=0;	
        }else{
            $data->blog=$request->blog;
        }         
        
        if(empty($request->bill)){
            $data->bill=0;	
        }else{
            $data->bill=$request->bill;
        }         
        
        if(empty($request->vcard)){
            $data->vcard=0;	
        }else{
            $data->vcard=$request->vcard;
        }                  

        $res=$data->save();
        if ($res) {
            return back()->with('success', 'Update was Successful!');
        } else {
            return back()->with('alert', 'An error occured');
        }
    }


    public function logout()
    {
        Auth::guard('admin')->logout();
        session()->flash('message', 'Just Logged Out!');
        return redirect('/admin');
    }
        
}

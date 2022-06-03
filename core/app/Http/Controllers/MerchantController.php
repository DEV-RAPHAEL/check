<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\Settings;
use App\Models\Currency;
use App\Models\Merchant;
use App\Models\Exttransfer;
use Carbon\Carbon;





class MerchantController extends Controller
{

    public function merchantlog()
    {
        $data['title']='Merchant logs';
        $data['merchant']=Merchant::orderBy('id', 'DESC')->get();
        return view('admin.merchant.index', $data);
    }     
    
    public function transferlog($id)
    {
        $data['title']='Transaction logs';
        $data['transfer']=Exttransfer::wheremerchant_key($id)->orderBy('id', 'DESC')->get();
        return view('admin.merchant.log', $data);
    } 
 
    public function Approvedmerchant()
    {
        $data['title']='Approved Merchant';
        $data['merchant']=Merchant::whereStatus(1)->orderBy('id', 'DESC')->get();
        return view('admin.merchant.approved', $data);
    }     
    
    public function Declinedmerchant()
    {
        $data['title']='Declined Merchant';
        $data['merchant']=Merchant::whereStatus(2)->orderBy('id', 'DESC')->get();
        return view('admin.merchant.declined', $data);
    } 
    
    public function Pendingmerchant()
    {
        $data['title']='Pending Merchant';
        $data['merchant']=Merchant::whereStatus(0)->orderBy('id', 'DESC')->get();
        return view('admin.merchant.pending', $data);
    }

    public function approve($id)
    {
        $data=Merchant::find($id);
        $data->status=1;
        $data->save();
        $user=User::find($data->user_id);
        $currency=Currency::whereStatus(1)->first();
        $set=Settings::first();
        if($set->email_notify==1){
            send_email(
                $user->email, 
                $user->username, 
                'Merchant was approved', 
                'Merchant request for '.$id.'. was approved.<br>Thanks for working with us.'
            );
        }
        return back()->with('success', 'Merchant is now active.');
    } 

    public function decline($id)
    {
        $data=Merchant::find($id);
        $data->status=2;
        $data->save();
        $user=User::find($data->user_id);
        $currency=Currency::whereStatus(1)->first();
        $set=Settings::first();
        if($set->email_notify==1){
            send_email(
                $user->email, 
                $user->username, 
                'Merchant was declined', 
                'Merchant request for '.$id.'. was declined.<br>Thanks for working with us.'
            );
        }
        return back()->with('success', 'Merchant has been disabled.');
    }

    public function Destroymerchant($id)
    {
        $data = Merchant::findOrFail($id);
            $res =  $data->delete();
            if ($res) {
                return back()->with('success', 'Request was Successfully deleted!');
            } else {
                return back()->with('alert', 'Problem With Deleting Request');
            }
    }    
    
    public function Destroylog($id)
    {
        $data = Exttransfer::findOrFail($id);
            $res =  $data->delete();
            if ($res) {
                return back()->with('success', 'Request was Successfully deleted!');
            } else {
                return back()->with('alert', 'Problem With Deleting Request');
            }
    }
  
 
}

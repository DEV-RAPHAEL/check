<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\Plans;
use App\Models\Sellcard;
use App\Models\Btctrades;
use App\Models\Settings;
use App\Models\Currency;
use App\Models\Charges;
use App\Models\History;
use Carbon\Carbon;
use Image;


class TradeController extends Controller
{
    public function trades()
    {
        $data['title']='Crypto Currency';
        $data['trx']=Btctrades::latest()->get();
        return view('admin.trade.index', $data);
    } 
    
    public function Edit($id)
    {
        $plan=$data['plan']=Plans::findOrFail($id);
        $data['title']=$plan->name;
        return view('admin.card.edit', $data);
    } 
    
    public function approveTrade($id)
    {
        $data = Btctrades::findOrFail($id);
        $history = History::whereref($data->trx)->first();
        $user=User::find($data->user_id);
        $set=Settings::first();
        $currency=Currency::whereStatus(1)->first();
        $data->status=1;
        if($data->type==2 || $data->type==5) {
            $user->balance=$user->balance+$data->amount-$data->charge;
            $user->save();
            if($data->type==2){
                $token='BTC-'.str_random(6);
                $coin='BTC';
            }elseif($data->type==5){
                $token='ETH-'.str_random(6);
                $coin='ETH';
            }
           
            $charge['user_id']=$user->id;
            $charge['ref_id']=$data->trx;
            $charge['amount']=$data->charge;
            $charge['log']='Charges for '.$coin.' sold #' .$token;
            Charges::create($charge);
        }elseif($data->type==1 || $data->type==4) {
            $user->balance=$user->balance-$data->charge;
            $user->save();
        }
        $history->status=1;
        $history->save();
        $res=$data->save();
        if($set->email_notify==1){
            send_email($user->email, $user->username, 'Payout approved', 'Payout request for '.$data->trx.' has been paidout<br>Thanks for working with us.');
        }
        if ($res) {
            return back()->with('success', 'Request was Successfully approved!');
        } else {
            return back()->with('alert', 'Problem With Approving Request');
        }
    }  

    public function declineTrade($id)
    {
        $data = Btctrades::findOrFail($id);
        $user=User::find($data->user_id);
        $set=Settings::first();
        $currency=Currency::whereStatus(1)->first();
        $data->status=2;
        $res=$data->save(); 
        if($set->email_notify==1){
            send_email(
                $user->email, 
                $user->username, 
                'Payout declined', 
                'Payout request for '.$data->trx.' has been declined<br>Thanks for working with us.'
            );
        }
        if ($res) {
            return back()->with('success', 'Request was Successfully declined!');
        } else {
            return back()->with('alert', 'Problem With Declining Request');
        }
    }
    
    public function DestroyTrade($id)
    {
        $data = Btctrades::findOrFail($id);
        $res =  $data->delete();
        if ($res) {
            return back()->with('success', 'Request was Successfully deleted!');
        } else {
            return back()->with('alert', 'Problem With Deleting Request');
        }
    } 
}

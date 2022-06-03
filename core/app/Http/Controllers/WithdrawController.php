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
use App\Models\Withdraw;
use Carbon\Carbon;


class WithdrawController extends Controller
{
   
    public function log()
    {
        $data['title']='Settlements';
        $data['withdraw']=Withdraw::orderBy('id', 'DESC')->orderby('id', 'desc')->get();
        return view('admin.withdrawal.index', $data);
    }  

    public function delete($id)
    {
        $data = Withdraw::findOrFail($id);
        if($data->status==0){
            return back()->with('alert', 'You cannot delete an unpaid withdraw request');
        }else{
            $res =  $data->delete();
            if ($res) {
                return back()->with('success', 'Request was Successfully deleted!');
            } else {
                return back()->with('alert', 'Problem With Deleting Request');
            }
        }

    } 
    public function approve($id)
    {
        $data = Withdraw::findOrFail($id);
        $user=User::find($data->user_id);
        $user->balance=$user->balance-$data->charge;
        $user->save();
        $set=Settings::first();
        $data->status=1;
        $res=$data->save();
        if($set->email_notify==1){
            send_withdraw($id, 'approved');
        }
        if ($res) {
            return back()->with('success', 'Request was successfully approved!');
        } else {
            return back()->with('alert', 'Problem with approving request');
        }
    }    
    public function decline($id)
    {
        $set=Settings::first();
        $currency=Currency::whereStatus(1)->first();
        $data = Withdraw::findOrFail($id);
        $user=User::find($data->user_id);
        $user->balance=$user->balance+$data->amount+$data->charge;
        $user->save();
        $data->status=2;
        $res=$data->save();
        if($set->email_notify==1){
            send_withdraw($id, 'declined');
        }
        if ($res) {
            return back()->with('success', 'Request was successfully declined!');
        } else {
            return back()->with('alert', 'Problem with approving request');
        }
    }  
}

<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Mews\Purifier\Facades\Purifier;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\Settings;
use App\Models\Admin;
use App\Models\Etemplate;
use Carbon\Carbon;


class SettingController extends Controller
{

    public function Settings()
    {
        $data['title']='General settings';
        $data['val']=Admin::first();
        return view('admin.settings.index', $data);
    }     
    
    public function Email()
    {
        $data['title']='Email settings';
        $data['val']=Etemplate::first();
        return view('admin.settings.email', $data);
    } 

    public function EmailUpdate(Request $request)
    {
        $data = Etemplate::findOrFail(1);
        $data->esender=$request->sender;
        $data->emessage=Purifier::clean($request->message);
        $res=$data->save();
        if ($res) {
            return back()->with('success', 'Update was Successful!');
        } else {
            return back()->with('alert', 'An error occured');
        }
    }      

    public function SettlementUpdate(Request $request)
    {
        $data = Settings::findOrFail(1);
        $data->duration=$request->duration;
        $data->period=$request->period; 
        $data->withdraw_charge=$request->withdraw_charge;
        $data->withdraw_chargep=$request->withdraw_chargep;
        $data->withdraw_limit=$request->withdraw_limit;            
        $data->starter_limit=$request->starter_limit;            
        $res=$data->save();
        if ($res) {
            return back()->with('success', 'Update was Successful!');
        } else {
            return back()->with('alert', 'An error occured');
        }
    } 
    
    public function Account()
    {
        $data['title']='Change account details';
        $data['val']=Admin::first();
        return view('admin.settings.account', $data);
    } 

    public function AccountUpdate(Request $request)
    {
        $data = Admin::whereid(1)->first();
        $data->username=$request->username;
        $data->password=Hash::make($request->password);
        $res=$data->save();
        if ($res) {
            return back()->with('success', 'Update was Successful!');
        } else {
            return back()->with('alert', 'An error occured');
        }
    }  
        
    
    public function SettingsUpdate(Request $request)
    {
        $data = Settings::findOrFail(1);
        $data->site_name=$request->site_name;
        $data->livechat=$request->livechat;
        $data->email=$request->email;
        $data->support_email=$request->support_email;
        $data->mobile=$request->mobile;
        $data->title=$request->title;
        $data->withdraw_duration=$request->withdraw_duration;
        $data->site_desc=$request->site_desc;
        $data->welcome_message=$request->welcome_message;
        $res=$data->save();
        if ($res) {
            return back()->with('success', 'Update was Successful!');
        } else {
            return back()->with('alert', 'An error occured');
        }
    }    
    public function Features(Request $request)
    {
        $data = Settings::findOrFail(1);  
        if(empty($request->email_activation)){
            $data->email_verification=0;	
        }else{
            $data->email_verification=$request->email_activation;
        }             
        if(empty($request->email_notify)){
            $data->email_notify=0;	
        }else{
            $data->email_notify=$request->email_notify;
        }      
        if(empty($request->registration)){
            $data->registration=0;	
        }else{
            $data->registration=$request->registration;
        }                   
        if(empty($request->merchant)){
            $data->merchant=0;	
        }else{
            $data->merchant=$request->merchant;
        }         
        if(empty($request->recaptcha)){
            $data->recaptcha=0;	
        }else{
            $data->recaptcha=$request->recaptcha;
        }           
        if(empty($request->subscription)){
            $data->subscription=0;	
        }else{
            $data->subscription=$request->subscription;
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
        if(empty($request->invoice)){
            $data->invoice=0;	
        }else{
            $data->invoice=$request->invoice;
        }          
        if(empty($request->store)){
            $data->store=0;	
        }else{
            $data->store=$request->store;
        }           
        if(empty($request->donation)){
            $data->donation=0;	
        }else{
            $data->donation=$request->donation;
        }           
        if(empty($request->single)){
            $data->single=0;	
        }else{
            $data->single=$request->single;
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
        /*           
        if(empty($request->bitcoin)){
            $data->bitcoin=0;	
        }else{
            $data->bitcoin=$request->bitcoin;
        }        
        if(empty($request->ethereum)){
            $data->ethereum=0;	
        }else{
            $data->ethereum=$request->ethereum;
        }  
        */       
        if(empty($request->stripe_connect)){
            $data->stripe_connect=0;	
        }else{
            $data->stripe_connect=$request->stripe_connect;
        }        
        if(empty($request->kyc_restriction)){
            $data->kyc_restriction=0;	
        }else{
            $data->kyc_restriction=$request->kyc_restriction;
        }         
        if(empty($request->country_restriction)){
            $data->country_restriction=0;	
        }else{
            $data->country_restriction=$request->country_restriction;
        }    
        $res=$data->save();
        if ($res) {
            return back()->with('success', 'Update was Successful!');
        } else {
            return back()->with('alert', 'An error occured');
        }
    }      
    
    public function charges(Request $request)
    {
        $data = Settings::findOrFail(1);
        $data->transfer_charge=$request->transfer_charge;
        $data->transfer_chargep=$request->transfer_chargep;
        $data->balance_reg=$request->bal;
        $data->withdraw_duration=$request->withdraw_duration;
        $data->merchant_charge=$request->merchant_charge;
        $data->merchant_chargep=$request->merchant_chargep;
        $data->invoice_charge=$request->invoice_charge;
        $data->invoice_chargep=$request->invoice_chargep;
        $data->product_charge=$request->product_charge; 
        $data->product_chargep=$request->product_chargep; 
        $data->subscription_charge=$request->subscription_charge; 
        $data->subscription_chargep=$request->subscription_chargep; 
        $data->donation_charge=$request->donation_charge; 
        $data->donation_chargep=$request->donation_chargep; 
        $data->single_charge=$request->single_charge; 
        $data->single_chargep=$request->single_chargep; 
        $data->min_transfer=$request->min_transfer; 
        $data->bill_charge=$request->bill_charge;
        $data->bill_chargep=$request->bill_chargep;
        $data->virtual_createcharge=$request->virtual_createcharge;
        $data->virtual_createchargep=$request->virtual_createchargep;
        $data->virtual_charge=$request->virtual_charge;
        $data->virtual_chargep=$request->virtual_chargep;
        $data->vc_min=$request->vc_min;
        $data->vc_max=$request->vc_max;
        $data->debit_currency=$request->debit_currency;
        $res=$data->save();
        if ($res) {
            return back()->with('success', 'Update was Successful!');
        } else {
            return back()->with('alert', 'An error occured');
        }
    }    
    
    public function crypto(Request $request)
    {
        $data = Settings::findOrFail(1);
        $data->btc_sell=$request->btc_sell;
        $data->btc_buy=$request->btc_buy;        
        $data->eth_sell=$request->eth_sell;
        $data->eth_buy=$request->eth_buy;
        $data->min_btcbuy=$request->min_btcbuy;
        $data->min_btcsell=$request->min_btcsell;        
        $data->min_ethbuy=$request->min_ethbuy;
        $data->min_ethsell=$request->min_sell;
        $data->btc_wallet=$request->btc_wallet;
        $data->eth_wallet=$request->eth_wallet;
        $res=$data->save();
        if ($res) {
            return back()->with('success', 'Update was Successful!');
        } else {
            return back()->with('alert', 'An error occured');
        }
    }  
}

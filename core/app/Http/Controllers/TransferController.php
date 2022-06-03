<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\Settings;
use App\Models\Transfer;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Order;
use App\Models\Transactions;
use App\Models\Productimage;
use App\Models\Requests;
use App\Models\Charges;
use App\Models\Donations;
use App\Models\Paymentlink;
use App\Models\Plans;
use App\Models\Subscribers;
use Carbon\Carbon;


class TransferController extends Controller
{

    public function sclinks()
    {
        $data['title'] = "Single Charge";
        $data['links']=Paymentlink::wheretype(1)->latest()->paginate(6);
        return view('admin.transfer.sc', $data);
    } 
    public function dplinks()
    {
        $data['title'] = "Donation Page";
        $data['links']=Paymentlink::wheretype(2)->latest()->paginate(6);
        return view('admin.transfer.dp', $data);
    }
    public function Ownbank()
    {
        $data['title']='Transfer';
        $data['transfer']=Transfer::latest()->get();
        return view('admin.transfer.own-bank', $data);
    }     
    public function Requestmoney()
    {
        $data['title']='Request Money';
        $data['request']=Requests::latest()->get();
        return view('admin.transfer.request', $data);
    }      
    public function Invoice()
    {
        $data['title']='Invoice';
        $data['invoice']=Invoice::latest()->get();
        return view('admin.transfer.invoice', $data);
    }      
    public function Product()
    {
        $data['title']='Product';
        $data['product']=Product::latest()->get();
        return view('admin.transfer.product', $data);
    }    
    public function charges()
    {
        $data['title']='Charges';
        $data['charges']=Charges::latest()->get();
        return view('admin.transfer.charges', $data);
    }
    public function plans()
    {
        $data['title']='Plans';
        $data['plans']=Plans::latest()->get();
        return view('admin.transfer.plans', $data);
    }
    public function unplan($id)
    {
        $page=Plans::find($id);
        $page->status=0;
        $page->save();
        return back()->with('success', 'Plan has been disabled.');
    } 
    public function plansub($id)
    {
        $data['plan']=$plan=Plans::whereref_id($id)->first();
        $data['sub']=Subscribers::whereplan_id($plan->id)->latest()->get();
        $data['title']=$plan->ref_id;
        return view('admin.transfer.subscribers', $data);
    }
    public function pplan($id)
    {
        $page=Plans::find($id);
        $page->status=1;
        $page->save();
        return back()->with('success', 'Plan link has been activated.');
    }
    public function Orders($id)
    {
        $data['title']='Orders';
        $data['orders']=Order::whereproduct_id($id)->latest()->get();
        return view('admin.transfer.orders', $data);
    }  
    public function linkstrans($id)
    {
        $data['title'] = "Transactions";
        $data['links']=Transactions::wherepayment_link($id)->latest()->get();
        return view('admin.transfer.trans', $data);
    }
    public function unlinks($id)
    {
        $page=Paymentlink::find($id);
        $page->status=0;
        $page->save();
        return back()->with('success', 'Payment link has been unsuspended.');
    } 
    public function plinks($id)
    {
        $page=Paymentlink::find($id);
        $page->active=1;
        $page->save();
        return back()->with('success', 'Product has been suspended.');
    }    
    public function unproduct($id)
    {
        $page=Product::find($id);
        $page->active=1;
        $page->save();
        return back()->with('success', 'Product has been unsuspended.');
    } 
    public function pproduct($id)
    {
        $page=Product::find($id);
        $page->active=0;
        $page->save();
        return back()->with('success', 'Payment link has been suspended.');
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
    public function Destroyownbank($id)
    {
        $data = Transfer::findOrFail($id);
        $res =  $data->delete();
        if ($res) {
            return back()->with('success', 'Request was Successfully deleted!');
        } else {
            return back()->with('alert', 'Problem With Deleting Request');
        }
    }    
    public function Destroyrequest($id)
    {
        $data = Requests::findOrFail($id);
        $res =  $data->delete();
        if ($res) {
            return back()->with('success', 'Request was Successfully deleted!');
        } else {
            return back()->with('alert', 'Problem With Deleting Request');
        }
    }      
    public function Destroyinvoice($id)
    {
        $link=Invoice::whereid($id)->first();
        $history=Transactions::wherepayment_link($id)->delete();
        $res=$link->delete();
        if ($res) {
            return back()->with('success', 'Request was Successfully deleted!');
        } else {
            return back()->with('alert', 'Problem With Deleting Request');
        }
    }     
    public function Destroyproduct($id)
    {
        $data = Product::findOrFail($id);
        $res =  $data->delete();
        if ($res) {
            return back()->with('success', 'Request was Successfully deleted!');
        } else {
            return back()->with('alert', 'Problem With Deleting Request');
        }
    }     
    public function Destroyorders($id)
    {
        $data = Order::findOrFail($id);
        $res =  $data->delete();
        if ($res) {
            return back()->with('success', 'Request was Successfully deleted!');
        } else {
            return back()->with('alert', 'Problem With Deleting Request');
        }
    }       
    
}

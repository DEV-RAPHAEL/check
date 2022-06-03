<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use App\Models\Blog;
use App\Models\Logo;
use App\Models\Currency;
use App\Models\Social;
use App\Models\Faq;
use App\Models\Category;
use App\Models\Page;
use App\Models\Design;
use App\Models\About;
use App\Models\Review;
use App\Models\User;
use App\Models\Subscriber;
use App\Models\Contact;
use App\Models\Product;
use App\Models\Order;
use App\Models\Productimage;
use App\Models\Gateway;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;
use Session;

class FrontendController extends Controller
{

    public function __construct()
    {

    }


    public function index()
    {
        $set=Settings::first();
        $data['title'] = $set->title;
        return view('front.index', $data);
    }


    public function about()
    {
        $data['title'] = "About Us";
        $data['review'] = Review::whereStatus(1)->get();
        return view('front.about', $data);
    }

    public function faq()
    {
        $data['title'] = "Faq";
        return view('front.faq', $data);
    }
    
    public function terms()
    {
        $data['title'] = "Terms & conditions";
        return view('front.terms', $data);
    }    
    
    public function privacy()
    {
        $data['title'] = "Privacy policy";
        return view('front.privacy', $data);
    }


    public function contact()
    {
        $data['title'] = "Contact Us";
        return view('front.contact', $data);
    }


    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'mobile' => 'required',
            'message' => 'required'
        ]);
        $sav['full_name']=$request->name;
        $sav['email']=$request->email;
        $sav['mobile']=$request->mobile;
        $sav['message']=$request->message;
        $sav['seen'] = 0;
        Contact::create($sav);
        return back()->with('success', ' Message was successfully sent!');
    }


    public function blog()
    {
        $data['title'] = "Blog Feed";
        $data['posts'] = Blog::latest()->paginate(3);
        return view('front.blog', $data);
    }

    public function article($id)
    {
        $post = $data['post'] = Blog::find($id);
        $xcat = $data['xcat'] = Category::find($post->cat_id);
        $post->views += 1;
        $post->save();
        $data['title'] = $data['post']->title;
        return view('front.single', $data);
    }

    public function category($id)
    {
        $cat = Category::find($id);
        $data['title'] = $cat->categories;
        $data['posts'] = Blog::where('cat_id', $id)->latest()->paginate(3);
        return view('front.cat', $data);
    } 
    
    public function product($id)
    {
        $product = $data['product']=Product::whereref_id($id)->first();
        $data['merchant']=$merchant=User::whereid($product->user_id)->first();
        $data['currency']=Currency::whereid($merchant->country)->first();
        $data['title'] = $product->name;
        $data['ref'] = str_random(16);
        return view('auth.buy', $data);
    }     
    
    public function preview()
    {
        $trx = Session::get('Trx');
        $order = $data['order']=Order::whereref_id($trx)->first();
        $data['product']=$product=Product::whereid($order->product_id)->first();
        $data['merchant']=$merchant=User::whereid($product->user_id)->first();
        $data['currency']=Currency::whereid($merchant->country)->first();
        $data['title'] = $product->name;
        $data['subtotal']=$subtotal= $product->amount*$order->quantity;
        $data['total']= $subtotal+$product->shipping_fee;
        $data['ship_fee']= $product->shipping_fee;
        $data['gateways']=Gateway::whereStatus(1)->get();
        $data['trx']=$trx;
        return view('auth.preview', $data);
    }    
    
    public function page($id)
    {
        $page = $data['page']=Page::find($id);
        $data['title'] = $page->title;
        return view('front.pages', $data);
    }

    public function buyproduct(Request $request)
    {
    $sav['quantity']=$request->quantity;
    $sav['first_name']=$request->first_name;
    $sav['last_name']=$request->last_name;
    $sav['email']=$request->email;
    $sav['phone']=$request->phone;
    $sav['address']=$request->address;
    $sav['note']=$request->note;
    $sav['amount']=$request->amount;
    $sav['ref_id']=$request->ref_id;
    $sav['product_id']=$request->product_id;
    Order::create($sav);
    Session::put('Trx', $request->ref_id);
    return redirect()->route('buy.preview');
    }

}

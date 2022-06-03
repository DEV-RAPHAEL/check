<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Exttransfer;
use App\Models\Merchant;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('verify-payment/{txref}/{secretkey}', function ($txref, $secretkey) {
    $ref=Exttransfer::wheretx_ref($txref)->count();
    if($ref==0){
        return response()->json(['message' => 'Invalid transaction','status' => 'failed','data' => null]);
    }elseif($ref==1){
        $ext=Exttransfer::wheretx_ref($txref)->first();
        $merchant=Merchant::wheremerchant_key($ext->merchant_key)->first();
        $user=User::whereId($merchant->user_id)->first();
        if($user->secret_key!==$secretkey){
            return response()->json(['message' => 'Invalid Secret Key','status' => 'failed','data' => null]);
        }else{
            $verify=Exttransfer::wheretx_ref($txref)->first();
            if($verify->payment_type=='card'){
                $email=$verify->email;
                $first_name=$verify->first_name;
                $last_name=$verify->last_name;
            }elseif($verify->payment_type=='account'){
                $usr=User::whereid($verify->user_id)->first();
                $email=$usr->email;
                $first_name=$usr->first_name;
                $last_name=$usr->last_name;
            }
            if($verify->status==1){
                $status='paid';
            }else{
                $status='pending';
            }
            return response()->json([
                'message' => null,
                'status' => 'success',
                'data' => [
                    'id' => $verify->id,
                    'email' => $email,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'payment_type' => $verify->payment_type,
                    'title' => $verify->title,
                    'description' => $verify->description,
                    'quantity' => $verify->quantity,
                    'reference' => $verify->reference,
                    'amount' => number_format($verify->amount, 2, '.', ''),
                    'charge' => $verify->charge,
                    'merchant_key' => $verify->merchant_key,
                    'callback_url' => rtrim($verify->callback_url, '/\\'),
                    'tx_ref' => $verify->tx_ref,
                    'status' => $status,
                    'created_at' => $verify->created_at,                    
                    'updated_at' => $verify->updated_at,
                ],
            ]);
        }
    }

});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

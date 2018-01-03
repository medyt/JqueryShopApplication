<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

use App\Http\Requests;
use Session;

class ShopController extends Controller
{
    public function getShop ()
    {
        return view('shop.shop');
    }
    public function getIndex(Request $request)
    {
        $inCart = Session::get('cart');
        if ($inCart) {
            $products = Product::whereNotIn('id', $inCart)->get();
        } else {
            $products = Product::all();
        }
        return $products;
    }
    public function getCart() 
    {
        $inCart = Session::has('cart') ? Session::get('cart') : [];
        if (count($inCart) == 0) {
            return [];
        } else {
            $products = Product::whereIn('id', $inCart)->get();
            return $products;  
        }         
    }
    public function getAddToCart(Request $request, $id) 
    {
        $inCart = Session::has('cart') ? Session::get('cart') :[];
        $inCart[] = $id;
        $request->session()->put('cart',$inCart);
        return $inCart;
    }
    public function getRemoveFromCart(Request $request, $id) 
    {
        $inCart = Session::has('cart') ? Session::get('cart') :[];
        array_splice($inCart, array_search($id, $inCart), 1);
        $request->session()->put('cart',$inCart); 
        return $inCart;
    }
    public function login(Request $request)
    {
        if (!empty($_POST['username']) && !empty($_POST['password'])) {				
            if ($_POST['username'] == env("LOGIN_USERNAME") && $_POST['password'] == env("LOGIN_PASSWORD")) {
                $request->session()->put('isLoggedIn',true);
                $request->session()->put('username',env("LOGIN_USERNAME"));
                return json_encode(["succes"=>true]); 
            } else {
                return json_last_error(["error" => 'messages.Wrong username or password']);
            }
        }                
    }
    public function logout(Request $request) 
    {        
        $request->session()->put('username',"");
        $request->session()->put('isLoggedIn',false);
        return redirect()->route('product.index');
    }
    public function checkout(Request $request)
    {
        $inCart = Session::has('cart') ? Session::get('cart') :[];
        $products = Product::whereIn('id', $inCart)->get();
        $msg = '<html><body>';
        $msg .= trans('messages.Dear').$_POST["Name"].",\n\n\n".trans('messages.My contact details is').$_POST["Contact"]."\n".$_POST["Comments"];
        if (count($products) > 0) {
            $msg .= trans('messages.Your products is')." : \n";
            foreach ($products as $product) {                
                $msg .= '<img src="photo/photo-'.$product->id.'.jpg">'."\n";                        
                $msg .= trans('messages.title')." : ". $product->title."\n";
                $msg .= trans('messages.description')." : ".$product->description."\n";
                $msg .= trans('messages.price')." : ".$product->price."\n";
            }
        }
        $msg .= '</body></html>';
        $msg = wordwrap($msg, 70);
        ini_set('smtp_port', env("CHECKOUT_PORT")); 
        $headers = 'From: Your name <info@address.com>' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n"; 
        mail(env("CHECKOUT_EMAIL"), "My order", $msg, $headers);
        $request->session()->put('cart',[]);
        return json_encode(["succes"=>true]);
    }
}
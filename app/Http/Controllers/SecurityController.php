<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

use App\Http\Requests;
use Session;

class SecurityController extends Controller
{
    public function getProducts() 
    {
        $products = Product::all();
        return $products;        
    }
    public function deleteProduct($id)
    {
        $product = Product::find($id);
        $product->delete();
        return true;
    }
    public function addProduct(Request $request)
    {
        if(isset($_POST['Title'])){
            $request->session()->put('updatetitle', $_POST['Title']);
        }
        if(isset($_POST['Description'])){
            $request->session()->put('updatedescription', $_POST['Description']);
        }
        if(isset($_POST['Price'])){
            $request->session()->put('updateprice', $_POST['Price']);
            return json_encode(["succes"=>"Description update"]);
        }
        if (isset($_POST['id'])) {
            $request->session()->put('updateid', $_POST['id']);
            return json_encode(["succes"=>true]);
        } else {
            $addphoto = false;
            if ($_FILES["fileToUpload"]["size"] != 0) {
                if (strpos($_FILES["fileToUpload"]["type"], "image") !== false) {
                    $input = $_FILES["fileToUpload"]["tmp_name"];
                    $addphoto = true;
                } else {
                    return json_encode(["error" => trans('messages.The type of your file is not accepted. We accept image file.')]);
                }
            } else {
                return json_encode(["error" => trans('messages.You did not insert the picture')]);
            }
            if($addphoto) {
                if (Session::has('updateid')) {
                    $output = "public/photo/photo-". Session::get('updateid') .'.jpg';
                    $product = Product::find(Session::get('updateid'));
                    $product->title =  Session::get('updatetitle');
                    $product->description = Session::get('updatedescription');
                    $product->price = Session::get('updateprice');
                    $product->save();
                } else {
                    $product = new Product([
                        'title' =>  Session::get('updatetitle'),
                        'description' => Session::get('updatedescription'),
                        'price' =>Session::get('updateprice')
                    ]);
                    $product->save();
                    $output = "public/photo/photo-".$product->id.'.jpg';
                    return json_encode(["succes"=>true]);
                }
                move_uploaded_file(file_get_contents($input),$output);
            } else {
                return json_encode(["succes"=>true]); 
            }   
            return json_encode(["succes"=>true]);   
        }        
    }
}
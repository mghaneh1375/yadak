<?php

namespace App\Http\Controllers;

use App\Models\Basket;
use App\Models\Category;
use App\Models\Offer;
use App\Models\Product;
use App\Models\ProductPic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class BasketController extends Controller {

    private $defaultPic = "1.jpg";

    public function addToBasket() {

        if (isset($_POST["product_id"])) {

            $product_id = makeValidInput($_POST["product_id"]);

            $products = Session::get('products');
            if($products == null) {
                Session::put('products', [$product_id]);
            }
            else {

                foreach ($products as $product) {
                    if($product == $product_id)
                        return;
                }

                $products[count($products)] = $product_id;
                Session::put('products', $products);
            }
            echo "ok";
        }

    }

    public function myBasket() {

        $products = Session::get('products');

        if($products == null)
            $products = [];

        $productsArr = [];

        foreach ($products as $product) {

            $tmp = DB::select('select p.*, s.name as super_category, c.name as category, b.name as brand from product p, super_category s, category c, brand b where b.id = p.brand_id and p.category_id = c.id and c.super_category_id = s.id and  p.id = ' . $product);

            if($tmp != null) {

                $tmp = $tmp[0];
                $pic = ProductPic::whereProductId($tmp->id)->first();
                if($pic != null)
                    $tmp->pic = URL::asset('productPic/' . $pic->name);
                else
                    $tmp->pic = URL::asset('productPic/' . $this->defaultPic);

                $productsArr[count($productsArr)] = $tmp;
                if($tmp->secondary_price != null && $tmp->secondary_price > 0)
                    $price = $tmp->secondary_price;
                else
                    $price = $tmp->money;

                $tmp->price = $price;
            }
        }

        return view('myBasket', ['products' => $productsArr]);
    }

    public function removeFromBasket() {

        if (isset($_POST["product_id"])) {

            $product_id = makeValidInput($_POST["product_id"]);

            $products = Session::get('products');

            if($products == null)
                return;
            else {

                $newProducts = [];

                foreach ($products as $product) {
                    if($product != $product_id) {
                        $newProducts[count($newProducts)] = $product;
                    }
                }

                Session::put('products', $newProducts);

            }
        }


    }

    public function addPic($code = -1) {

        if($code == -1)
            return;

        $basket = Basket::whereUserId(Auth::user()->id)->whereFollowCode($code)->first();

        if($basket == null)
            return;

        if (isset($_FILES["pic"]) && $_FILES['pic']['name'] != '') {

            $file = Input::file('pic');
            $Image = time() . '_' . $file->getClientOriginalName();
            $destenationpath = public_path() . '/payments';
            $file->move($destenationpath, $Image);
            $basket->pic = $Image;
            $basket->save();
            echo "ok";
        }


    }

    public function checkOffCode() {

        if(isset($_POST["offcode"])) {

            $offer = Offer::whereCode(makeValidInput($_POST["offcode"]))->first();

            if($offer == null) {
                echo json_encode(['status' => "nok1"]);
                return;
            }
            else if($offer->expire < date('Y-m-d')) {
                echo json_encode(['status' => "nok2"]);
                return;
            }
            else {

                $str = number_format($offer->amount);

                if($offer->kind == 1)
                    $str .= " ریال";
                else
                    $str .= " درصد";

                echo json_encode(['status' => "ok", "amount" => $str]);
                return;
            }
        }

        echo "nok1";
    }

    public function finishBuy() {

        if(isset($_POST["payment_kind"]) && isset($_POST["products"])) {

            $payment_kind = makeValidInput($_POST["payment_kind"]);

            if($payment_kind == 2 && Auth::user()->special != 1) {
                echo json_encode(["status" => "nok1"]);
                return;
            }

            $products = $_POST["products"];

            $tmp = new Basket();
            
            $tmp->user_id = Auth::user()->id;
            $tmp->payment_kind = $payment_kind;

            if(isset($_POST["offcode"]) && !empty($_POST["offcode"])) {
                $offer = Offer::whereCode(makeValidInput($_POST["offcode"]))->first();
                if($offer != null)
                    $tmp->offcode = $offer->code . '_' . $offer->amount . '_' . $offer->expire . '_' . $offer->kind;
            }

            $productsStr = "";

            foreach ($products as $product) {

                $p = Product::whereId($product['id']);

                if($p != null && $p->hide == 0) {
                    if($p->secondary_price != null && $p->secondary_price > 0)
                        $productsStr .= $product['id'] . '_' . $product['num'] . '_' . $p->secondary_price . '&';
                    else
                        $productsStr .= $product['id'] . '_' . $product['num'] . '_' . $p->money . '&';
                }
            }

            $tmp->products = substr($productsStr, 0, strlen($productsStr) - 1);

            $code = random_int(11111111, 99999999);

            $tmp->follow_code = $code;
            $tmp->save();

            echo json_encode(["status" => "ok", "follow_code" => $code]);

            Session::flush();
            sendSMS("09133518607", Auth::user()->first_name, "requestpurchase", Auth::user()->last_name);

            return;
        }


        echo json_encode(["status" => "nok2"]);
    }

    public function getItems() {

        if(isset($_POST["id"])) {

            $basket = Basket::whereId(makeValidInput($_POST["id"]));
            if($basket == null || $basket->user_id != Auth::user()->id) {
                echo json_encode(["items" => [], 'totalSum' => 0]);
                return;
            }

            $items = explode('&', $basket->products);
            $products = [];
            $totalSum = 0;

            foreach ($items as $item) {
                $item = explode('_', $item);
                $p = Product::whereId($item[0]);

                if($p == null || $p->hide == 1)
                    continue;

                $totalSum += $p->money * $item[1];
                $products[count($products)] = ["name" => $p->name, "price" => number_format($p->money),
                    "sum" => number_format($p->money * $item[1]), "num" => $item[1]];
            }

            echo json_encode(["items" => $products, 'totalSum' => number_format($totalSum)]);
            return;
        }

        echo json_encode(["items" => [], 'totalSum' => 0]);
    }

    public function buyAgain() {

        if(isset($_POST["basket_id"]) && isset($_POST["payment_kind"])) {

            $basket = Basket::whereId(makeValidInput($_POST["basket_id"]));

            if($basket == null || $basket->user_id != Auth::user()->id) {
                return Redirect::route('trackOrders');
            }

            $payment_kind = makeValidInput($_POST["payment_kind"]);

            if($payment_kind == 2) {

                if (!isset($_FILES["pic"]) || $_FILES['pic']['name'] == '')
                    return Redirect::route('trackOrders');

            }

            $tmp = new Basket();
            $tmp->payment_kind = $payment_kind;
            $tmp->user_id = Auth::user()->id;
            $tmp->products = $basket->products;

            if(isset($_POST["offcode"]) && !empty($_POST["offcode"])) {
                $offer = Offer::whereCode(makeValidInput($_POST["offcode"]))->first();
                if($offer != null)
                    $tmp->offcode = $offer->code . '_' . $offer->amount . '_' . $offer->expire . '_' . $offer->kind;
            }

            $code = random_int(11111111, 99999999);

            $tmp->follow_code = $code;

            if($payment_kind == 2) {
                $file = Input::file('pic');
                $Image = time() . '_' . $file->getClientOriginalName();
                $destenationpath = public_path() . '/payments';
                $file->move($destenationpath, $Image);
                $tmp->pic = $Image;
            }

            $tmp->save();
            sendSMS("09133518607", Auth::user()->first_name, "requestpurchase", Auth::user()->last_name);

            return Redirect::route('success', ['follow_code' => $code]);
        }

    }

    public function trackOrders() {

        $orders = Basket::whereUserId(Auth::user()->id)->orderBy('id', 'desc')->get();

        foreach ($orders as $order) {

            $items = explode('&', $order->products);
            $itemsArr = [];
            $counter = 0;
            $order->submit_date = MiladyToShamsi('', explode('-', explode(' ', $order->created_at)[0]));

            if($order->confirm || $order->reject)
                $order->confirm_date = MiladyToShamsi('', explode('-', explode(' ', $order->confirm_date)[0]));

            foreach ($items as $item) {

                $tmp = explode('_', $item);
                $p = DB::select('select p.name as product, s.name as super_category, c.name as category, b.name as brand from product p, super_category s, category c, brand b where b.id = p.brand_id and p.category_id = c.id and c.super_category_id = s.id and  p.id = ' . $tmp[0]);

                if($p == null)
                    continue;

                $p = $p[0];

                $itemsArr[$counter++] = [
                    "num" => $tmp[1],
                    "name" => $p->product,
                    "brand" => $p->brand,
                    "super_category" => $p->super_category,
                    "category" => $p->category,
                    "price" => $tmp[2]
                ];
            }

            $order->items = $itemsArr;
        }

        return view('trackOrders', ['orders' => $orders]);
    }

    public function success($follow_code = "") {
        return view('success', ['follow_code' => $follow_code]);
    }

}

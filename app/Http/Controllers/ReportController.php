<?php

namespace App\Http\Controllers;

use App\Models\Basket;
use App\Models\ConfigModel;
use App\Models\Product;
use App\Models\SuperCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class ReportController extends Controller {

    public function productReport() {

        $products = DB::select('select p.id, p.*, s.name as super_category, c.name as category, b.name as brand from product p, super_category s, category c, brand b where b.id = p.brand_id and p.category_id = c.id and c.super_category_id = s.id ');
        $config = ConfigModel::first();

        foreach ($products as $product) {

            $product->sells = DB::select("select count(*) as countNum from basket where confirm = true and (products like '" . $product->id . "_%' or products like '%&" . $product->id . "_%')")[0]->countNum;

            $product->money = number_format($product->money);
            $product->secondary_price = number_format($product->secondary_price);

            if($product->secondary_price != null && $product->secondary_price > 0 
                && $product->money != $product->secondary_price
            ) {
                $product->takhfif = 1;
            }

            if($product->number < $config->critical_threshold)
                $product->background = "red";
            else if($product->number < $config->warning_threshold)
                $product->background = "orange";
            else
                $product->background = "transparent";
        }

        return view('report.product', ['products' => $products, 'categories' => SuperCategory::all()]);
    }

    public function users() {

        $users = DB::select('select u.*, (select count(*) from basket where confirm = 1 and user_id = u.id) as buys from users u order by buys desc');

        foreach ($users as $user) {

            $sum = 0;
            $baskets = Basket::whereConfirm(true)->whereUserId($user->id)->select('products')->get();

            foreach ($baskets as $basket) {

                $items = explode('&', $basket->products);

                foreach ($items as $item) {
                    $tmp = explode('_', $item);
                    $sum += $tmp[1] * $tmp[2];
                }

            }

            $user->sum = number_format($sum);
        }

        return view('report.users', ['users' => $users]);
    }

    public function most() {

        $date = date("Y-m") . "-01";
        $endDate = date('Y-m-d', strtotime('+1 month', strtotime($date)));

        $products = DB::select("select count(*) as countNum, p.id from basket, product p where (DATE(confirm_date) between '" . $date . "' and '" . $endDate . "') and confirm = true and (products like concat(p.id, '_%') or products like concat('%&', p.id, '_%')) group by (p.id) order by countNum desc limit 0, 10");

        foreach ($products as $itr) {
            $itr->product = DB::select('select p.name as product, s.name as super_category, c.name as category, b.name as brand from product p, super_category s, category c, brand b where b.id = p.brand_id and p.category_id = c.id and c.super_category_id = s.id and p.id = ' . $itr->id)[0];
        }

        return view('report.most', ['products' => $products]);
    }

    public function sellReport($id, $year = -1, $month = -1) {

        $p = Product::whereId($id);
        if($p == null)
            return Redirect::route('home');

        if($month == -1 || $year == -1) {
            $date = date("Y-m") . "-01";
            $month = explode('-', $date)[1];
            $year =  explode('-', $date)[0];
        }
        else
            $date = $year . '-' . $month . "-01";

        $endDate = date('Y-m-d', strtotime('+1 month', strtotime($date)));

        $basket = DB::select("select * from basket where (DATE(confirm_date) between '" . $date . "' and '" . $endDate . "') and confirm = true and (products like '" . $id . "_%' or products like '%&" . $id . "_%') order by confirm_date asc");

        $sells = [];
        $stat = [];

        foreach ($basket as $itr) {

            $items = explode('&', $itr->products);

            foreach($items as $item) {

                $tmp = explode('_', $item);

                if($tmp[0] == $id) {

                    $date = explode(' ', $itr->confirm_date)[0];

                    $sells[count($sells)] = [
                        'user' => \App\Models\User::whereId($itr->user_id),
                        'date' => $date,
                        "num" => $tmp[1]
                    ];

                    if(key_exists($date, $stat))
                        $stat[$date] = $stat[$date] + $tmp[1];
                    else
                        $stat[$date] = (int)($tmp[1]);

                }
            }

        }

        return view('report.sell', ['stats' => $stat, 'id' => $id, 'm' => $month, 'y' => $year, 'sells' => $sells,
            'name' => $p->name, 'month' => date("F", mktime(0, 0, 0, $month, 10))]);
    }

    public function userBookmarks($uId) {
        $products = DB::select('select p.name as product, s.name as super_category, c.name as category, b.name as brand from product p, super_category s, category c, brand b, bookmarks bo where bo.product_id = p.id and b.id = p.brand_id and p.category_id = c.id and c.super_category_id = s.id and bo.user_id = ' . $uId);
        return view('report.userBookmarks', ['products' => $products]);
    }

    public function userBuys($uId) {
        $products = DB::select("select p.id, count(*) as countNum from basket, product p where confirm = true and (products like concat(p.id, '_%') or products like concat('%&', p.id, '_%')) and user_id = " . $uId . " group by (p.id)");

        foreach ($products as $itr) {
            $itr->product = DB::select('select p.name as product, s.name as super_category, c.name as category, b.name as brand from product p, super_category s, category c, brand b where b.id = p.brand_id and p.category_id = c.id and c.super_category_id = s.id and p.id = ' . $itr->id)[0];
        }

        return view('report.userBuys', ['products' => $products]);
    }
}

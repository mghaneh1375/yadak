<?php

namespace App\Http\Controllers;

use App\models\Basket;
use App\models\CommonQuestion;
use App\models\ConfigModel;
use App\models\FAQCategory;
use App\models\Offer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use PHPExcel;
use PHPExcel_Writer_Excel2007;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class AdminController extends Controller {

    public function config() {
        return view('config', ['config' => ConfigModel::first()]);
    }

    public function doConfig() {

        if(isset($_POST["warning_threshold"]) && isset($_POST["critical_threshold"])) {
            $tmp = ConfigModel::first();
            $tmp->critical_threshold = makeValidInput($_POST["critical_threshold"]);
            $tmp->warning_threshold = makeValidInput($_POST["warning_threshold"]);
            $tmp->save();
        }

        return Redirect::route('profile');
    }

    public function commonQuestionsPanel() {

        $categories = FAQCategory::all();

        foreach ($categories as $category) {
            $category->questions = CommonQuestion::whereCategoryId($category->id)->get();
        }

        return view('commonQuestions', ['categories' => $categories]);
    }

    public function deleteCommonQuestion() {

        if(isset($_POST["id"])) {
            CommonQuestion::destroy(makeValidInput($_POST["id"]));
        }

        return Redirect::route('commonQuestionsPanel');
    }

    public function addCommonQuestion() {

        if(isset($_POST["catId"]) && isset($_POST["question"]) && isset($_POST["answer"])) {


            $q = new CommonQuestion();
            $q->category_id = makeValidInput($_POST["catId"]);
            $q->answer = $_POST["answer"];
            $q->question = $_POST["question"];

            $q->save();

        }

        return Redirect::route('commonQuestionsPanel');

    }

    public function faqCategories($err = "") {
        return view('faqCategories', ['items' => FAQCategory::all(), 'err' => $err]);
    }

    public function addFaqCategory() {

        if(isset($_POST["name"])) {

            $tmp = new FAQCategory();
            $tmp->name = makeValidInput($_POST["name"]);

            try {
                $tmp->save();
            }
            catch (\Exception $x) {
                return $this->faqCategories('دسته مورد نظر در سامانه موجود است');
            }
        }

        return Redirect::route('faqCategories');
    }

    public function editFaqCategory() {

        if(isset($_POST["newName"]) && isset($_POST["categoryId"])) {

            $cat = FaqCategory::whereId(makeValidInput($_POST["categoryId"]));

            if($cat != null) {
                $cat->name = makeValidInput($_POST["newName"]);
                try {
                    $cat->save();
                }
                catch (\Exception $x) {
                    return $this->faqCategories('نام دسته مورد نظر در سامانه موجود است');
                }
            }
        }

        return Redirect::route('faqCategories');
    }

    public function deleteFaqCategory() {
        if(isset($_POST["categoryId"])) {
            FAQCategory::destroy(makeValidInput($_POST["categoryId"]));
        }
        return Redirect::route('faqCategories');
    }

    public function generateOffCode() {

        if(isset($_POST["type"]) && isset($_POST["count"]) &&
            isset($_POST["date"]) && isset($_POST["amount"])) {

            $type = makeValidInput($_POST["type"]);
            $count = makeValidInput($_POST["count"]);
            $expireTime = makeValidInput($_POST["date"]);
            $amount = makeValidInput($_POST["amount"]);
            
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            // $objPHPExcel->getProperties()->setCreator("OffCode");
            // $objPHPExcel->getProperties()->setLastModifiedBy("OffCode");
            // $objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
            // $objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
            // $objPHPExcel->getProperties()->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.");

            $sheet->setCellValue('A1', 'کد');

            $k = 2;

            for ($i = 0; $i < $count; $i++) {
                $tmp = new Offer();
                $tmp->amount = $amount;
                try {
                    $code = "off-" . random_int(10000, 99999);
                    while (Offer::whereCode($code)->count() > 0)
                        $code = "off-" . random_int(10000, 99999);

                    $tmp->code = $code;
                    $tmp->expire = $expireTime;
                    $tmp->kind = $type;
                    $sheet->setCellValue('A' . $k, $code);
                    $tmp->save();
                    $k++;
                } catch (\Exception $e) {
                    dd($e);
                }
            }

            $fileName = __DIR__ . "/../../../public/slideBar/offcode.xlsx";

            $sheet->setTitle('کد های تخفیف');

            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save($fileName);

            if (file_exists($fileName)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="' . basename($fileName) . '"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($fileName));
                readfile($fileName);
                unlink($fileName);
            }
        }
    }

    public function createOffCode() {
        return view('createOffCode');
    }

    public function offCodeReports() {

        $offers = Offer::all();
        $offCodes = [];

        foreach ($offers as $offCode) {
            if($offCode->expireTime > date("Y-m-d"))
                $offCode->delete();
            else
                $offCodes[count($offCodes)] = $offCode;

            if($offCode->kind == 1)
                $offCode->kind = "مقداری";
            else
                $offCode->kind = "درصدی";
        }

        $used = DB::select("select b.products, b.offcode, b.created_at, username, phone from basket b, users u where u.id = user_id and validate_offcode = true and confirm = 1");

        foreach ($used as $itr) {
            $itr->offcode = explode('_', $itr->offcode);
            $itr->submit_date = MiladyToShamsi('', explode('-', explode(' ', $itr->created_at)[0]));;

            if($itr->offcode[3] == 2) {

                $total = 0;
                $items = explode('&', $itr->products);

                foreach ($items as $item) {
                    $total += explode('_', $item)[2] * explode('_', $item)[1];
                }

                $itr->offcode[1] = number_format($total * $itr->offcode[1] / 100);
            }
            else {
                $itr->offcode[1] = number_format($itr->offcode[1]);
            }

        }

        return view('offCodes', ['codes' => $offCodes, 'used' => $used]);
    }

    public function deleteOffer() {

        if(isset($_POST["id"])) {

            try {
                Offer::destroy(makeValidInput($_POST["id"]));
                echo "ok";
                return;
            }
            catch (\Exception $x) {}
        }

        echo "nok";
    }

    public function unConfirmedOrders() {

        $orders = Basket::whereConfirm(false)->whereReject(false)->orderBy('id', 'desc')->get();

        foreach ($orders as $order) {

            $order->user = \App\models\User::whereId($order->user_id);
            $items = explode('&', $order->products);
            $itemsArr = [];
            $counter = 0;
            $sum = 0;
            $order->submit_date = MiladyToShamsi('', explode('-', explode(' ', $order->created_at)[0]));

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
                    "price" => number_format((int)$tmp[2])
                ];

                $sum += (int)$tmp[2];
            }

            $order->items = $itemsArr;
            $order->total = $sum;

            if($order->offcode != null && !empty($order->offcode)) {

                $tmp = explode('_', $order->offcode);

                $offer = Offer::whereCode($tmp[0])->first();
                if(count($tmp) == 4 && $offer != null && $offer->expire >= explode(' ', $offer->created_at)[0])
                    $order->offer = $tmp;
                else
                    $order->offer = null;
            }
            else
                $order->offer = null;
        }

        return view('manageOrders', ['orders' => $orders, 'mode' => 'unConfirmed']);

    }

    public function rejectedOrders() {

        $orders = Basket::whereConfirm(false)->whereReject(true)->orderBy('id', 'desc')->get();

        foreach ($orders as $order) {

            $order->user = \App\models\User::whereId($order->user_id);
            $items = explode('&', $order->products);
            $itemsArr = [];
            $counter = 0;
            $sum = 0;
            $order->submit_date = MiladyToShamsi('', explode('-', explode(' ', $order->created_at)[0]));

            if($order->offcode != null && !empty($order->offcode)) {

                $tmp = explode('_', $order->offcode);

                $offer = Offer::whereCode($tmp[0])->first();
                if(count($tmp) == 4 && $offer != null && $offer->expire >= explode(' ', $offer->created_at)[0])
                    $order->offer = $tmp;
                else
                    $order->offer = null;
            }
            else
                $order->offer = null;

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
                    "price" => number_format((int)$tmp[2])
                ];

                $sum += (int)$tmp[2];
            }

            $order->items = $itemsArr;
            $order->total = $sum;

        }

        return view('manageOrders', ['orders' => $orders, 'mode' => 'rejects']);

    }

    public function rejectOrder() {

        if(isset($_POST["desc"]) && isset($_POST["id"])) {

            $description = makeValidInput($_POST["desc"]);

            if(empty($description)) {
                echo "nok";
                return;
            }

            $id = makeValidInput($_POST["id"]);
            $basket = Basket::whereId($id);

            if($basket == null)
                return;

            if($basket->decease_from_warehouse) {

                $products = $basket->products;
                $items = explode('&', $products);

                foreach ($items as $item) {
                    $tmp = explode('_', $item);
                    DB::update('update product set number = number + 1 where id = ' . $tmp[0]);
                }

                if($basket->validate_offcode) {

                    $tmp = explode('_', $basket->offcode);
                    if(count($tmp) == 4) {
                        $offer = new Offer();
                        $offer->code = $tmp[0];
                        $offer->amount = $tmp[1];
                        $offer->expire = $tmp[2];
                        $offer->kind = $tmp[3];
                        $offer->save();
                        $basket->validate_offcode = false;
                        $basket->save();
                    }
                }
            }

            DB::update('update basket set reject = true, confirm = false, decease_from_warehouse = false, ' .
                'confirm_date = current_timestamp, description = "' . $description . '" where id = ' . $id);
            $customer = \App\models\User::whereId($basket->user_id);
            sendSMS($customer->phone, $customer->last_name, "resultpurchase");

            echo "ok";
        }

    }

    public function confirmOrder() {

        if(isset($_POST["desc"]) && isset($_POST["id"]) && isset($_POST["arrival"])) {

            $arrival = convertDateToString(str_replace(" ", "", makeValidInput($_POST["arrival"])));
            $id = makeValidInput($_POST["id"]);

            $basket = Basket::whereId($id);

            if($basket == null)
                return;

            $desc = makeValidInput($_POST["desc"]);

            if(!$basket->decease_from_warehouse) {

                $products = $basket->products;
                $items = explode('&', $products);

                foreach ($items as $item) {
                    $tmp = explode('_', $item);
                    DB::update('update product set number = number - 1 where id = ' . $tmp[0]);
                }

                if($basket->offcode != null && !empty($basket->offcode) && !$basket->validate_offcode) {
                    $tmp = explode('_', $basket->offcode);

                    $offer = Offer::whereCode($tmp[0])->first();
                    if($offer != null && $offer->expire >= explode(' ', $basket->created_at)[0]) {
                        $offer->delete();
                        $basket->validate_offcode = true;
                        $basket->save();
                    }
                }

            }

            DB::update('update basket set reject = false, confirm = true, decease_from_warehouse = true, ' .
                'confirm_date = current_timestamp, arrival_date = "' . $arrival . '", description = "' . $desc . '" where id = ' . $id);
            $customer = \App\models\User::whereId($basket->user_id);
            sendSMS($customer->phone, $customer->last_name, "resultpurchase");

            echo "ok";
        }

    }

    public function confirmedOrders() {

        $orders = Basket::whereConfirm(true)->whereReject(false)->orderBy('id', 'desc')->get();

        foreach ($orders as $order) {

            $order->user = \App\models\User::whereId($order->user_id);
            $items = explode('&', $order->products);
            $itemsArr = [];
            $counter = 0;
            $sum = 0;
            $order->submit_date = MiladyToShamsi('', explode('-', explode(' ', $order->created_at)[0]));

            if($order->offcode != null && !empty($order->offcode)) {

                $tmp = explode('_', $order->offcode);

                $offer = Offer::whereCode($tmp[0])->first();
                if(count($tmp) == 4 && $offer != null && $offer->expire >= explode(' ', $offer->created_at)[0])
                    $order->offer = $tmp;
                else
                    $order->offer = null;
            }
            else
                $order->offer = null;
            
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
                    "price" => number_format($tmp[2])
                ];

                $sum += $tmp[2];
            }

            $order->items = $itemsArr;
            $order->total = $sum;

        }

        return view('manageOrders', ['orders' => $orders, 'mode' => 'accepts']);

    }

    public function toggleStatusUser() {

        if(isset($_POST["id"])) {

            $user = \App\models\User::whereId(makeValidInput($_POST["id"]));
            if($user == null)
                return;

            if($user->status == 3)
                $user->status = 2;
            else
                $user->status = 3;

            $user->save();
            echo "ok";
        }

    }

    public function toggleSpecial() {

        if(isset($_POST["id"])) {

            $user = \App\models\User::whereId(makeValidInput($_POST["id"]));
            if($user == null)
                return;

            if($user->special == 1)
                $user->special = 0;
            else
                $user->special = 1;

            $user->save();
            echo "ok";
        }

    }

}

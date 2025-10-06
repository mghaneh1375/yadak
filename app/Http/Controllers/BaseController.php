<?php

namespace App\Http\Controllers;

use App\models\Activation;
use App\models\Category;
use App\models\CommonQuestion;
use App\models\FAQCategory;
use App\models\ProductPic;
use App\models\SlideBar;
use App\models\SuperCategory;
use App\models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use function Ramsey\Uuid\v1;

class BaseController extends Controller {

    public function profile() {
        return view('profile');
    }
    
    public function doLogin() {

        $username = makeValidInput(Input::get('username'));
        $password = makeValidInput(Input::get('password'));

        if(Auth::attempt(['username' => $username, 'password' => $password], true)) {

            if(Auth::user()->status != 3) {
                $msg = "حساب کاربری شما هنوز فعال نشده است";
                Auth::logout();
                return view('login', array('loginErr' => $msg));
            }

            return redirect::route('profile');
        }
        else {
            $msg = 'نام کاربری و یا رمزعبور اشتباه است';
        }

        return view('login', array('loginErr' => $msg));
    }

    public function doActive() {

        if(myPostIsset("uId") && myPostIsset("phone") && myPostIsset("code")) {

            $phoneNum = makeValidInput($_POST["phone"]);

            if (strlen($phoneNum) == 10)
                $phoneNum = '0' . $phoneNum;

            $phoneNum = translatePersian($phoneNum);

            $activation = Activation::wherePhone($phoneNum)->first();

            $uId = makeValidInput($_POST["uId"]);
            $code = makeValidInput($_POST["code"]);

            if ($activation != null) {

                if($activation->code == $code) {
                    $user = User::whereId($uId);

                    if ($user != null && $user->phone == $phoneNum) {
                        $activation->delete();
                        $user->status = 3;
                        $user->save();

                        Auth::login($user);
                        return Redirect::route('home');
                    }
                }

                return view("login", ["status" => "step", "phone" => $phoneNum,
                    'uId' => $uId, 'err' => "کد وارد شده نامعتبر است.",
                    'reminder' => 300 - time() + $activation->send_time
                ]);
            }

        }

        return Redirect::route('login');
    }

    public function resendActivation() {

        if(myPostIsset("uId") && myPostIsset("phone")) {
            $phoneNum = makeValidInput($_POST["phone"]);

            if (strlen($phoneNum) == 10)
                $phoneNum = '0' . $phoneNum;

            $phoneNum = translatePersian($phoneNum);

            $activation = Activation::wherePhone($phoneNum)->first();

            $uId = makeValidInput($_POST["uId"]);

            if ($activation != null) {

                if ($activation->send_time >= time() - 300)
                    return view("login", ["status" => "step", "phone" => $phoneNum,
                        'uId' => $uId,
                        'reminder' => 300 - time() + $activation->send_time
                    ]);

                sendSMS($phoneNum, $activation->code, "registry");

                $activation->send_time = time();
                $activation->save();

                return view("login", ["status" => "step", "phone" => $phoneNum,
                    'uId' => $uId, 'reminder' => 300]);
            }
        }

        return Redirect::route('login');
    }

    public function forgetPass() {

        if(myPostIsset("nid") && myPostIsset("phone")) {

            $user = User::whereUsername(makeValidInput($_POST["nid"]))->wherePhone(makeValidInput($_POST["phone"]))->first();

            if($user == null)
                return view('login', ['status' => 'forget', 'err' => 'کد ملی یا شماره همراه وارد شده نامعتبر است.']);

            $newPass = generateActivationCode();

            $user->password = Hash::make($newPass);
            $user->save();

            sendSMS($user->phone, $user->last_name, 'resetPass', $newPass);

            return view('login', ['status' => 'forget', 'err' => 'رمزعبور جدید برای شماره همراه وارد شده، پیامک شد.']);
        }

        return Redirect::route('login');
    }

    public function registry() {

        if(myPostIsset("firstname") && myPostIsset("username") && myPostIsset("phone") &&
            myPostIsset("address") && myPostIsset("password") && myPostIsset("rpassword") &&
            myPostIsset("lastname")
        ) {

            $nid = makeValidInput($_POST["username"]);
            $firstname = makeValidInput($_POST["firstname"]);
            $lastname = makeValidInput($_POST["lastname"]);
            $address = makeValidInput($_POST["address"]);
            $phone = translatePersian(makeValidInput($_POST["phone"]));

            if(!_custom_check_national_code($nid))
                return view('login', ['status' => 'err', 'err' => 'کد ملی وارد شده نامعتبر است.',
                    'firstname' => $firstname, 'lastname' => $lastname, 'address' => $address,
                    'username' => $nid, 'phone' => $phone
                ]);

            if(User::whereUsername($nid)->count() > 0)
                return view('login', ['status' => 'err', 'err' => 'کد ملی وارد شده در سامانه موجود است.',
                    'firstname' => $firstname, 'lastname' => $lastname, 'address' => $address,
                    'username' => $nid, 'phone' => $phone
                ]);

            if(User::wherePhone($phone)->count() > 0)
                return view('login', ['status' => 'err', 'err' => 'شماره همراه وارد شده در سامانه موجود است.',
                    'firstname' => $firstname, 'lastname' => $lastname, 'address' => $address,
                    'username' => $nid, 'phone' => $phone
                ]);

            $password = makeValidInput($_POST["password"]);
            $rpassword = makeValidInput($_POST["rpassword"]);

            if($password != $rpassword) {
                return view('login', ['status' => 'err', 'err' => 'رمزعبور و تکرار آن یکسان نیست.',
                    'firstname' => $firstname, 'lastname' => $lastname, 'address' => $address,
                    'username' => $nid, 'phone' => $phone
                ]);
            }

            $tmp = new User();
            $tmp->level = 2;
            $tmp->username = $nid;
            $tmp->status = 1;
            $tmp->first_name = $firstname;
            $tmp->last_name = $lastname;
            $tmp->address = $address;
            $tmp->phone = $phone;
            $tmp->password = Hash::make($password);
            $tmp->save();

            $activation = new Activation();
            $activationCode = generateActivationCode();
            $activation->code = $activationCode;
            $activation->phone = $phone;
            $activation->send_time = time();
            $activation->save();

            sendSMS($phone, $activationCode, "registry");

            return view('login', ['status' => 'step', 'uId' => $tmp->id, 'phone' => $phone, 'reminder' => 300]);
        }

        return view('login', ['status' => 'err', 'err' => 'لطفا تمام اطلاعات لازم را وارد نمایید.']);
    }

    public function editInfo() {
        return view('editInfo');
    }

    public function resendActivationAgain() {

        if(myPostIsset("phone")) {

            $phoneNum = makeValidInput($_POST["phone"]);

            if (strlen($phoneNum) == 10)
                $phoneNum = '0' . $phoneNum;

            $phoneNum = translatePersian($phoneNum);

            $activation = Activation::wherePhone($phoneNum)->first();

            if ($activation != null) {

                if ($activation->send_time >= time() - 300)
                    return view("editInfo", ["status" => "step", "phone" => $phoneNum,
                        'reminder' => 300 - time() + $activation->send_time
                    ]);

                sendSMS($phoneNum, $activation->code, "registry");

                $activation->send_time = time();
                $activation->save();

                return view("editInfo", ["status" => "step", "phone" => $phoneNum,
                    'reminder' => 300]);
            }
        }

        return Redirect::route('home');
    }

    public function doEditInfo() {

        if(myPostIsset("firstname") && myPostIsset("phone") &&
            myPostIsset("address") && myPostIsset("lastname")
        ) {

            $firstname = makeValidInput($_POST["firstname"]);
            $lastname = makeValidInput($_POST["lastname"]);
            $address = makeValidInput($_POST["address"]);
            $phone = translatePersian(makeValidInput($_POST["phone"]));

            $user = Auth::user();
            $user->first_name = $firstname;
            $user->last_name = $lastname;
            $user->address = $address;

            if($user->phone != $phone) {

                if(User::wherePhone($phone)->count() > 0)
                    return view('editInfo', ['status' => 'err', 'err' => 'شماره وارد شده در سامانه موجود است.']);

                $user->save();

                $activation = new Activation();
                $activationCode = generateActivationCode();
                $activation->code = $activationCode;
                $activation->phone = $phone;
                $activation->send_time = time();
                $activation->save();

                sendSMS($phone, $activationCode, "registry");

                return view('editInfo', ['status' => 'step', 'phone' => $phone, 'reminder' => 300]);
            }

            $user->save();
            return Redirect::route('editInfo');
        }

        return Redirect::route('home');
    }

    public function doActiveAgain() {

        if(myPostIsset("phone") && myPostIsset("code")) {

            $phoneNum = makeValidInput($_POST["phone"]);

            if (strlen($phoneNum) == 10)
                $phoneNum = '0' . $phoneNum;

            $phoneNum = translatePersian($phoneNum);

            $activation = Activation::wherePhone($phoneNum)->first();

            $code = makeValidInput($_POST["code"]);
            $user = Auth::user();

            if ($activation != null) {

                if($activation->code == $code) {

                    $activation->delete();
                    $user->phone = $phoneNum;
                    $user->save();

                    return Redirect::route('editInfo');
                }

                return view("editInfo", ["status" => "step", "phone" => $phoneNum,
                    'err' => "کد وارد شده نامعتبر است.",
                    'reminder' => 300 - time() + $activation->send_time
                ]);
            }

        }

        return Redirect::route('home');

    }

    public function home() {

        $products = DB::select('select p.number, p.money, p.secondary_price, p.name, p.id, c.name as categ, s.name as superCateg, ' .
            's.id as superCategId, b.name as brand from brand b,' .
            '  product p, super_category s, category c where p.hide = 0 and p.best = true ' .
            'and p.category_id = c.id and c.super_category_id = s.id and b.id = p.brand_id');

        $slider = [];
        $tmpSliders = SlideBar::all();
        foreach ($tmpSliders as $slideBar) {
            $slider[count($slider)] = URL::asset('slideBar/' . $slideBar->pic);
        }

        foreach ($products as $product) {

            $img = ProductPic::whereProductId($product->id)->first();
            if($img == null)
                $product->img = URL::asset('productPic/nopic.jpg');
            else {
                $product->img = URL::asset('productPic/' . $img->name);
            }
        }

        return view('home', ['categories' => SuperCategory::select('id', 'name')->get(),
            'products' => $products, 'slides' => $slider]);
    }

    public function search() {

        if(myPostIsset("key")) {

            $key = makeValidInput($_POST["key"]);

            $result = [];

            if(myPostIsset("key2")) {

                $key2 = makeValidInput($_POST["key2"]);

                $categories = DB::select("select id, name from category where name like '%" . $key . "%' or name like '%" . $key2 . "%'");

                foreach ($categories as $category) {
                    $url = route('productsInCategory', ['id' => $category->id]);
                    $result[count($result)] = ["name" => $category->name, 'url' => $url];
                }

                $products = DB::select("select id, name from product where name like '%" . $key . "%' or name like '%" . $key2 . "%'");

                foreach ($products as $product) {
                    $url = route('product', ['id' => $product->id]);
                    $result[count($result)] = ["name" => $product->name, 'url' => $url];
                }

                echo json_encode($result);
                return;
            }

            $categories = DB::select("select id, name from category where name like '%" . $key . "%'");

            foreach ($categories as $category) {
                $url = route('productsInCategory', ['id' => $category->id]);
                $result[count($result)] = ["name" => $category->name, 'url' => $url];
            }

            $products = DB::select("select id, name from product where name like '%" . $key . "%'");

            foreach ($products as $product) {
                $url = route('product', ['id' => $product->id]);
                $result[count($result)] = ["name" => $product->name, 'url' => $url];
            }

            echo json_encode($result);
            return;
        }

        echo json_encode([]);
    }

    public function login() {
        return view('login');
    }

    public function logout() {
        Auth::logout();
        Session::flush();
        return Redirect::route('login');
    }

    public function faq() {

        $categories = FAQCategory::all();

        foreach ($categories as $category) {
            $category->items = CommonQuestion::whereCategoryId($category->id)->get();
        }

        return view('FAQ', ['categories' => $categories]);
    }

    public function manageSlideShow()
    {
        $slid = SlideBar::all();
        return view('manageSlideShow', ['slide' => $slid]);
    }

    public function saveSlideShow()
    {
        if ($_POST['kind'] == 'save' && $_FILES['pic']['name'] != '') {

            $slide = new SlideBar();

            $file = Input::file('pic');
            $Image = time() . '_' . $file->getClientOriginalName();
            $destenationpath = public_path() . '/slideBar';
            $file->move($destenationpath, $Image);

            $slide->pic = $Image;
            $slide->save();
        }
        elseif ($_POST['kind'] == 'delete' && $_POST['id'] != '') {
            $slide = SlideBar::whereId($_POST['id']);
            if(file_exists(__DIR__ . '/../../../public/slideBar/' . $slide->pic))
                unlink(__DIR__ . '/../../../public/slideBar/' . $slide->pic);
            $slide->delete();
        }


        return redirect(route('manageSlideShow'));
    }

    private function groupRegistration($level, $categoryId)
    {
        $a = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $alpha = str_split($a);

        $err = "";

        if (isset($_FILES["file"])) {

            $file = $_FILES["file"]["name"];
            if (!empty($file)) {

                $path = __DIR__ . '/../../../public/tmp/' . $file;

                $err = uploadCheck($path, "file", "اکسل ثبت نام گروهی", 20000000, "xlsx");

                if (empty($err)) {
                upload($path, "file", "اکسل ثبت نام گروهی");
                $excelReader = \PHPExcel_IOFactory::createReaderForFile($path);
                $excelObj = $excelReader->load($path);
                $workSheet = $excelObj->getSheet(0);
                $product = array();
                $itemName = array();
                $lastRow = $workSheet->getHighestRow();
                $cols = $workSheet->getHighestColumn();

                $explode = str_split($cols);
                if (count($explode) == 1) {
                    $number = strpos($a, $explode[0]);
                    for ($i = 0; $i <= $number; $i++) {
                        $itemName[$i] = $workSheet->getCell($alpha[$i] . 1)->getValue();
                    }
                    for ($row = 2; $row <= $lastRow; $row++) {
                        for ($i = 0; $i <= $number; $i++) {
                            $product[$row - 2][$i] = $workSheet->getCell($alpha[$i] . $row)->getValue();
                        }
                    }
                } else {
                    $number1 = strpos($a, $explode[0]);
                    $number2 = strpos($a, $explode[1]);

                    for ($i = 0; $i <= 25; $i++) {
                        $itemName[$i] = $workSheet->getCell($alpha[$i] . 1)->getValue();
                    }
                    $k = 0;

                    for($i = 0; $i <= $number1; $i++){
                        $A = $alpha[$i];
                        for($j = 0; $j <= $number2; $j++){
                            $B = $alpha[$j];
                            $C = $A.$B;
                            $itemName[$k+26] = $workSheet->getCell($C . 1)->getValue();
                            $k++;
                        }
                    }

                    for ($row = 2; $row <= $lastRow; $row++) {
                        $k = 0;
                        for ($i = 0; $i <= 25; $i++) {
                            $product[$row - 2][$i] = $workSheet->getCell($alpha[$i] . $row)->getValue();
                        }
                        for($i = 0; $i <= $number1; $i++){
                            $A = $alpha[$i];
                            for($j = 0; $j <= $number2; $j++){
                                $B = $alpha[$j];
                                $C = $A.$B;
                                $product[$row - 2][$k+26] = $workSheet->getCell($C . $row)->getValue();
                                $k++;
                            }
                        }
                    }
                }
                unlink($path);
                $this->addProductExcel($product, $itemName, $categoryId);
                $err = "فایل کاربرانی که به درستی به سامانه اضافه گردیدند تولید شد";
                }
            }
        }

        if (empty($err))
            $err = "لطفا فایل اکسل مورد نیاز را آپلود نمایید";
        else {
            return $err;
        }
    }

    public function contactUs() {
        return view('contactUs');
    }

    public function aboutUs() {
        return view('aboutUs');
    }
}

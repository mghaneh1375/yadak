<?php

namespace App\Http\Controllers;

use App\models\Bookmark;
use App\models\Brand;
use App\models\Category;
use App\models\CategoryItem;
use App\models\Product;
use App\models\ProductItem;
use App\models\ProductPic;
use App\models\SuperCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use PHPExcel_IOFactory;

class ProductController extends Controller {

    private $defaultPic = "1.jpg";

    public function productsInCategory($id) {

        $products = DB::select('select p.id, p.*, s.name as super_category, c.name as category, b.name as brand from product p, super_category s, category c, brand b where p.hide = 0 and b.id = p.brand_id and p.category_id = c.id and c.super_category_id = s.id and  p.category_id = ' . $id);

        foreach ($products as $product) {

            $pic = ProductPic::whereProductId($product->id)->first();
            if($pic == null)
                $product->img = URL::asset("productPic/" . $this->defaultPic);
            else
                $product->img = URL::asset("productPic/" . $pic->name);

            $product->money = number_format($product->money);
            $product->secondary_price = number_format($product->secondary_price);

            if($product->secondary_price != -1 && $product->money != $product->secondary_price) {
                $product->takhfif = 1;
                $product->last_price = $product->secondary_price;
            }
            else
                $product->last_price = $product->money;

        }

        return view('productsInCategory', ['products' => $products]);
    }

    public function addProduct() {
        $super = SuperCategory::all();
        return view('manageProduct', ['super' => $super]);
    }

    public function changeTakhfif() {

        if(isset($_POST["id"]) && isset($_POST["price"])) {

            $id = makeValidInput($_POST["id"]);
            $price = makeValidInput($_POST["price"]);

            $p = Product::whereId($id);

            if($p == null)
                return;

            $p->secondary_price = $price;
            $p->save();
            echo "ok";
        }

    }

    public function bookmarks() {
        $products = DB::select('select p.id, p.name, p.money, p.secondary_price, s.name as super_category, c.name as category, b.name as brand from product p, super_category s, category c, brand b, bookmarks bo where bo.user_id = ' . Auth::user()->id . ' and bo.product_id = p.id and b.id = p.brand_id and p.category_id = c.id and c.super_category_id = s.id');

        foreach ($products as $product) {

            $pic = ProductPic::whereProductId($product->id)->first();

            if($pic != null)
                $product->pic = URL::asset('productPic/' . $pic->name);
            else
                $product->pic = URL::asset('productPic/' . $this->defaultPic);
        }

        return view('bookmarks', ['products' => $products]);
    }

    public function getCategoryForProduct()
    {
        $id = $_POST['id'];
        $category = Category::whereSuperCategoryId($id)->get();
        echo json_encode(['category' => $category]);
        return;
    }

    public function getItemForProduct()
    {
        $id = $_POST['id'];
        $item = CategoryItem::whereCategoryId($id)->get();
        $brands = Brand::whereCategoryId($id)->get();
        echo json_encode(['item' => $item, 'brands' => $brands]);
        return;
    }

    public function saveExcelProduct()
    {
        $categoryId = $_POST['categoryId'];
        $err = $this->groupRegistration($_FILES['file'], $categoryId);
        return redirect(url('addProduct'));
    }

    private function addProductExcel($products, $items, $categoryId)
    {
        for ($i = 0; $i < count($items); $i++) {
            if ($items[$i] == 'نام محصول') {
                $nameNum = $i;
            } elseif ($items[$i] == 'قیمت محصول') {
                $moneyNum = $i;
            } elseif ($items[$i] == 'تعداد محصول') {
                $numNum = $i;
            } elseif ($items[$i] == 'برند محصول') {
                $brandNum = $i;
            }
        }
        $item = CategoryItem::whereCategoryId($categoryId)->get();
        for ($i = 0; $i < count($products); $i++) {
            $product = new Product();
            $product->name = $products[$i][$nameNum];
            $product->money = $products[$i][$moneyNum];
            $product->number = $products[$i][$numNum];
            $product->brand = $products[$i][$brandNum];
            $product->category_id = $categoryId;
            $product->save();

            for ($j = 0; $j < count($items); $j++) {
                for ($k = 0; $k < count($item); $k++) {
                    if ($items[$j] == $item[$k]->name && $item[$k]->base_item_id != 0) {
                        $proItem = new ProductItem();
                        $proItem->category_item_id = $item[$k]->id;
                        $proItem->product_id = $product->id;
                        $proItem->description = $products[$i][$j];
                        $proItem->save();
                    }
                }
            }
        }

    }

    public function saveNewProduct(){

        $brandId = $_POST['brandId'];
        if($brandId == 0){
            $brand = new Brand();
            $brand->name = $_POST['brand'];
            $brand->category_id = $_POST['categoryId'];
            $brand->save();

            $brandId = $brand->id;
        }

        if(isset($_POST['name']) && isset($_POST['number']) && isset($_POST['money'])  &&
            $_POST['name'] != ''&& $_POST['number'] != ''&& $_POST['money'] != ''){

            $name = $_POST['name'];
            $number = $_POST['number'];
            $money = $_POST['money'];
            $categoryId = $_POST['categoryId'];
            $desc = $_POST['descProduct'];


            $product = new Product();
            $product->name = $name;
            $product->brand_id = $brandId;
            $product->number = $number;
            $product->money = $money;
            $product->category_id = $categoryId;
            $product->desc = $desc;
            $product->best = isset($_POST["best"]);
            $product->save();

            if(isset($_POST["item"])) {
                $items = $_POST['item'];
                $itemId = $_POST['id'];
                for ($i = 0; $i < count($items); $i++) {
                    if ($items[$i] != '') {
                        $productItem = new ProductItem();
                        $productItem->description = $items[$i];
                        $productItem->category_item_id = $itemId[$i];
                        $productItem->product_id = $product->id;
                        $productItem->save();
                    }
                }
            }

            if (isset($_FILES['pic'])) {
                $file = Input::file('pic');
                for ($i = 0; $i < count($_FILES['pic']['name']); $i++) {
                    if ($_FILES['pic']['name'][$i] != '') {
                        $Image = time() . '_' . $file[$i]->getClientOriginalName();
                        $destenationpath = public_path() . '/productPic';
                        $file[$i]->move($destenationpath, $Image);

                        $pic = new ProductPic();
                        $pic->product_id = $product->id;
                        $pic->name = $Image;
                        $pic->save();
                    }
                }
            }
        }
        return redirect(url('addProduct'));
    }

    public function editProduct($id){

        $super = SuperCategory::all();
        $product = DB::select('select p.*, s.id as super_category, b.id as brand_id from product p, super_category s, category c, brand b where b.id = p.brand_id and p.category_id = c.id and c.super_category_id = s.id and  p.id = ' . $id);

        if($product == null || count($product) == 0)
            return Redirect::route('profile');

        $pic = ProductPic::whereProductId($id)->first();

        if($pic == null)
            $pic = URL::asset('productPic/' . $this->defaultPic);
        else
            $pic = URL::asset('productPic/' . $pic->name);

        return view('editProduct', ['product' => $product[0], 'pic' => $pic,
            'super' => $super, 'id' => $id, 'brands' => Brand::all()]);
    }

    public function doEditProduct($id) {

        $product = Product::whereId($id);

        if($product == null)
            return Redirect::route('profile');

        if(myPostIsset('name') && myPostIsset('number') && myPostIsset('money')) {

            $brandId = $_POST['brandId'];

            if($brandId == 0) {
                $brand = new Brand();
                $brand->name = $_POST['brand'];
                $brand->category_id = $_POST['categoryId'];
                $brand->save();

                $brandId = $brand->id;
            }

            $name = makeValidInput($_POST['name']);
            $number = makeValidInput($_POST['number']);
            $money = makeValidInput($_POST['money']);
            $categoryId = makeValidInput($_POST['categoryId']);
            $desc = makeValidInput($_POST['descProduct']);

            $product->name = $name;
            $product->brand_id = $brandId;
            $product->number = $number;
            $product->money = $money;
            $product->category_id = $categoryId;
            $product->desc = $desc;
            $product->best = isset($_POST["best"]);
            $product->save();

            if(isset($_POST["item"])) {
                $items = $_POST['item'];
                $itemId = $_POST['id'];
                for ($i = 0; $i < count($items); $i++) {
                    if ($items[$i] != '') {
                        $productItem = new ProductItem();
                        $productItem->description = $items[$i];
                        $productItem->category_item_id = $itemId[$i];
                        $productItem->product_id = $product->id;
                        $productItem->save();
                    }
                }
            }

            if (isset($_FILES['pic']) && !empty($_FILES["pic"]["name"])) {

                $pp = ProductPic::whereProductId($id)->first();

                if($pp != null) {

                    if(file_exists(__DIR__ . '/../../../public/productPic/' . $pp->name))
                        unlink(__DIR__ . '/../../../public/productPic/' . $pp->name);

                    $pp->delete();
                }

                $file = Input::file('pic');

                $Image = time() . '_' . $file->getClientOriginalName();
                $destenationpath = public_path() . '/productPic';
                $file->move($destenationpath, $Image);

                $pic = new ProductPic();
                $pic->product_id = $product->id;
                $pic->name = $Image;
                $pic->save();
            }
        }

        return Redirect::route('productReport');
    }

    public function product($id = -1) {

        $product = DB::select('select p.*, s.name as super_category, c.name as category, b.name as brand from product p, super_category s, category c, brand b where p.hide = 0 and b.id = p.brand_id and p.category_id = c.id and c.super_category_id = s.id and  p.id = ' . $id);

        if($product == null || count($product) == 0)
            return Redirect::route('home');

        $product = $product[0];

        $pic = ProductPic::whereProductId($product->id)->first();
        if($pic != null)
            $product->pic = URL::asset('productPic/' . $pic->name);
        else
            $product->pic = URL::asset('productPic/' . $this->defaultPic);

        $bookmark = false;

        if(Auth::check() && Bookmark::whereProductId($id)->whereUserId(Auth::user()->id)->first() != null)
            $bookmark = true;

        return view('product', ['product' => $product, 'bookmark' => $bookmark]);
    }

    public function bookmark() {

        if(isset($_POST["id"])) {
            $product_id = makeValidInput($_POST["id"]);
            $uId = Auth::user()->id;

            $bookmark = Bookmark::whereProductId($product_id)->whereUserId($uId)->first();
            if($bookmark == null) {
                $bookmark = new Bookmark();
                $bookmark->product_id = $product_id;
                $bookmark->user_id = $uId;
                $bookmark->save();
                echo "ok";
                return;
            }
            else {
                $bookmark->delete();
                echo "nok";
            }
        }

    }

    public function toggleShow() {

        if(isset($_POST['id'])) {

            $p = Product::whereId(makeValidInput($_POST["id"]));
            if($p != null) {
                $p->hide = !$p->hide;
                $p->save();
                echo "ok";
            }

        }

    }

    private function addProducts($products) {

        foreach ($products as $product) {

            if (count($product) != 4)
                continue;

            try {

                $superCategories = SuperCategory::all();
                $superCat = null;

                $key = $product[0];
                if(strpos($key, "111") !== false)
                    $key = "پراید";
                else if(strpos($key, "131") !== false)
                    $key = "پراید";
                else if(strpos($key, "132") !== false)
                    $key = "پراید";
                else if(strpos($key, "151") !== false)
                    $key = "پراید";

                foreach ($superCategories as $superCategory) {

                    if(strpos($key, $superCategory->name) !== false) {
                        $superCat = $superCategory->id;
                        break;
                    }

                }

                if ($superCat == null)
                    $superCat = SuperCategory::whereName("متفرقه")->first()->id;

                $cat = Category::whereSuperCategoryId($superCat)->whereName($product[2])->first();

                if ($cat == null) {
                    $cat = new Category();
                    $cat->name = $product[2];
                    $cat->super_category_id = $superCat;
                    $cat->save();
                }

                if($product[1] == null)
                    $product[1] = "نامشخص";

                $b = Brand::whereCategoryId($cat->id)->whereName($product[1])->first();
                if ($b == null) {
                    $b = new Brand();
                    $b->name = $product[1];
                    $b->category_id = $cat->id;
                    $b->save();
                }

                if(empty($product[3]))
                    $product[3] = -1;

                $p = new Product();
                $p->name = $product[0];
                $p->money = $product[3];
                $p->desc = "";
                $p->number = 100;
                $p->hide = 0;
                $p->best = 0;
                $p->category_id = $cat->id;
                $p->brand_id = $b->id;
                $p->save();

            }
            catch (\Exception $x) {
                dd($x);
            }
        }
    }

    public function doAddBatchProduct() {

        if(isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {

            $file = Input::file('file');
            $Image = time() . '_' . explode('.', $file->getClientOriginalName())[1];
            $destenationpath = public_path() . '/tmp';

            $fileName = $destenationpath . '/' . $Image;

            $file->move($destenationpath, $Image);

            $excelReader = PHPExcel_IOFactory::createReaderForFile($fileName);
            $excelObj = $excelReader->load($fileName);
            $workSheet = $excelObj->getSheet(0);
            $products = array();
            $lastRow = $workSheet->getHighestRow();
            $cols = $workSheet->getHighestColumn();

            unlink($fileName);

            if ($cols < 'D') {
                dd("تعداد ستون های فایل شما معتبر نمی باشد");
            } else {

                for ($row = 2; $row <= $lastRow; $row++) {

                    if($workSheet->getCell('A' . $row)->getValue() == "")
                        break;

                    $products[$row - 2][0] = $workSheet->getCell('A' . $row)->getValue();
                    $products[$row - 2][1] = $workSheet->getCell('B' . $row)->getValue();
                    $products[$row - 2][2] = $workSheet->getCell('C' . $row)->getValue();
                    $products[$row - 2][3] = $workSheet->getCell('D' . $row)->getValue();

                }

                $this->addProducts($products);

            }


        }

        return Redirect::route('productReport');
    }

    public function addBatchProduct() {
        return view('addBatchProduct');
    }

}

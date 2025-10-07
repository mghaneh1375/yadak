<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CategoryItem;
use App\Models\SuperCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class CategoryController extends Controller {

    public function manageCategory()
    {
        $superCategory = SuperCategory::all();
        $category = Category::all();
        $item = CategoryItem::all();
        return view('manageCategory', ['superCategory' => $superCategory, 'category' => $category, 'item' => $item]);
    }

    public function saveSuperCategory()
    {
        if ($_POST['kind'] == 'delete' && $_POST['id'] != '') {
            $category = Category::whereSuperCategoryId($_POST['id'])->get();
            foreach ($category as $item) {
                CategoryItem::whereCategoryId($item->id)->delete();
                $item->delete();
            }
            $superCategory = SuperCategory::whereId($_POST['id']);
            $superCategory->delete();
            echo 'ok';
            return;
        }
        if (isset($_POST['name']) && isset($_POST['id']) && $_POST['name'] != '' && $_POST['kind'] == 'editName') {
            $superCategory = SuperCategory::whereId($_POST['id']);
            $superCategory->name = $_POST['name'];
            $superCategory->save();
            echo 'ok';
            return;
        }
        else {
            if ($_POST['kind'] == 'save' && isset($_POST['name']) && $_POST['name'] != '') {
                $name = $_POST['name'];
                $superCategory = SuperCategory::where('name', $name)->get();
                if (count($superCategory) == 0) {
                    $superCategory = new SuperCategory();
                    $superCategory->name = $name;
                    $superCategory->save();
                }
            } elseif ($_POST['kind'] == 'edit' && isset($_POST['superCategoryId'])) {
                $superCategoryId = $_POST['superCategoryId'];
                $superCategory = SuperCategory::whereId($superCategoryId);
                $superCategory->save();
            }
        }

        return redirect(route('manageCategory'));
    }

    public function saveCategory()
    {
        if ($_POST['kind'] == 'delete' && $_POST['id'] != '') {
            CategoryItem::whereCategoryId($_POST['id'])->delete();
            Category::destroy($_POST['id']);
            echo 'ok';
            return;
        } elseif (isset($_POST['name']) && isset($_POST['id']) && $_POST['name'] != '' && $_POST['kind'] == 'editName') {
            $Category = Category::whereId($_POST['id']);
            $Category->name = $_POST['name'];
            $Category->save();
            echo 'ok';
            return;
        } elseif ($_POST['kind'] == 'save' && isset($_POST['name']) && $_POST['name'] != '' && $_POST['superCategoryId'] != '') {
            $name = $_POST['name'];
            $superId = $_POST['superCategoryId'];
            $condition = ['name' => $name, 'super_category_id' => $superId];
            $category = Category::where($condition)->get();
            if (count($category) == 0) {

                $category = new Category();
                $category->name = $name;
                $category->super_category_id = $superId;
                $category->save();
            }
        } elseif ($_POST['kind'] == 'edit' && isset($_POST['categoryId'])) {

            $categoryId = $_POST['categoryId'];
            $superCategoryId = $_POST['superCategoryId'];

            $category = Category::find($categoryId);
            if (isset($_POST['name'])) {
                $category->name = $_POST['name'];
            }

            $category->super_category_id = $superCategoryId;
            $category->save();
        }

        return redirect(route('manageCategory'));
    }

    public function saveItem()
    {
        if ($_POST['kind'] == 'delete') {
            $id = $_POST['id'];
            $item = CategoryItem::whereId($id);
            if ($item->base_item_id == 0) {
                CategoryItem::whereBaseItemId($item->id)->delete();
            }
            $item->delete();
            echo 'ok';
            return;
        }
        elseif ($_POST['kind'] == 'edit' && $_POST['name'] != '') {
            $item = CategoryItem::whereId($_POST['id']);
            $item->name = $_POST['name'];
            $item->save();
            echo 'ok';
            return;
        }
        elseif (isset($_POST['baseName']) && $_POST['baseName'] != '' && $_POST['kind'] == 'save') {
            $baseName = $_POST['baseName'];
            $categoryId = $_POST['categoryId'];

            $base = new CategoryItem();
            $base->name = $baseName;
            $base->category_id = $categoryId;
            $base->base_item_id = 0;
            $base->save();

            if(isset($_POST["name"])) {
                $name = $_POST['name'];
                for ($i = 0; $i < count($name); $i++) {
                    if (isset($name[$i]) && $name[$i] != '') {
                        $item = new CategoryItem();
                        $item->name = $name[$i];
                        $item->category_id = $categoryId;
                        $item->base_item_id = $base->id;
                        $item->save();
                    }
                }
            }
        }
        elseif (isset($_POST['baseId']) && $_POST['kind'] == 'save') {
            $baseId = $_POST['baseId'];
            $name = $_POST['name'];
            $base = CategoryItem::whereId($baseId);
            for ($i = 0; $i < count($name); $i++) {
                if ($name[$i] != '') {
                    $item = new CategoryItem();
                    $item->name = $name[$i];
                    $item->category_id = $base->category_id;
                    $item->base_item_id = $base->id;
                    $item->save();
                }
            }
        }

        return redirect(route('manageCategory'));
    }

    public function removeCategory() {

        if(isset($_POST["id"])) {

            try {
                SuperCategory::destroy(makeValidInput($_POST["id"]));
                echo "ok";
                return;
            }
            catch (\Exception $x) {}
        }

        echo "nok";
    }
}

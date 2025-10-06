<ul class="nav nav-tabs">

    <li class='{{ (\Illuminate\Support\Facades\Request::route()->getName() == 'home') ? 'active' : '' }}' >
        <a href="{{route('home')}}" style="cursor: pointer;" aria-expanded="false">خانه</a>
    </li>

    <li class='dropdown {{ (\Illuminate\Support\Facades\Request::route()->getName() == 'products') ? 'active' : '' }}' >
        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"> دسته بندی کالا ها
            <i class="fa fa-angle-down"></i>
        </a>
        <ul class="dropdown-menu" role="menu">
            @foreach($categories as $category)
                @if($category->sub != null && count($category->sub) > 0)
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">{{$category->name}}
                            <i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="dropdown-submenu" role="menu">
                            @foreach($category->sub as $itr)
                                <li>
                                    <a href="{{route('productsInCategory', ['id' => $itr->id])}}" tabindex="-1">{{$itr->name}}</a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @else
                    <li>
                        <a href="#tab_1_3" tabindex="-1" data-toggle="tab">{{$category->name}}</a>
                    </li>
                @endif
            @endforeach
        </ul>
    </li>

    <li class='{{ (\Illuminate\Support\Facades\Request::route()->getName() == 'faq') ? 'active' : '' }}' >
        <a href="{{route('faq')}}" aria-expanded="false">سوالی دارید؟</a>
    </li>

    @if(\Illuminate\Support\Facades\Auth::check())
        <li class='{{ (\Illuminate\Support\Facades\Request::route()->getName() == 'trackOrders') ? 'active' : '' }}' >
            <a href="{{route('trackOrders')}}" aria-expanded="false">پیگیری سفارشات</a>
        </li>
    @endif

    <li class='{{ (\Illuminate\Support\Facades\Request::route()->getName() == 'contactUs') ? 'active' : '' }}' >
        <a href="{{route('contactUs')}}" aria-expanded="false">ارتباط با ما</a>
    </li>

    <li class='{{ (\Illuminate\Support\Facades\Request::route()->getName() == 'aboutUs') ? 'active' : '' }}' >
        <a href="{{route('aboutUs')}}" aria-expanded="false">درباره ما</a>
    </li>

    @if(\Illuminate\Support\Facades\Auth::check())
        <li class="dropdown">
            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">پروفایل کاربری
                <i class="fa fa-angle-down"></i>
            </a>
            <ul class="dropdown-menu" role="menu">

                @if(\Illuminate\Support\Facades\Auth::user()->level == 1)
                    <li>
                        <a href="{{route('profile')}}">پنل ادمین</a>
                    </li>
                @endif

                <li>
                    <a href="{{route('bookmarks')}}">محصولات نشان شده</a>
                </li>

                <li>
                    <a href="{{route('editInfo')}}">ویرایش اطلاعات</a>
                </li>

                <li>
                    <a href="{{route('logout')}}">خروج</a>
                </li>
            </ul>
        </li>
    @endif

    @if(\Illuminate\Support\Facades\Auth::check())

        <?php $myBasket = \Illuminate\Support\Facades\Session::get('products'); $myBasket = ($myBasket == null) ? 0 : count($myBasket) ?>

        <li style="float: left; margin-left: 40px; position: relative">
            <a target="_blank" href="{{route('myBasket')}}" style=" font-size: 18px">
                <span data-val="{{$myBasket}}" id="shopping-cart" class="glyphicon glyphicon-shopping-cart" style="cursor: pointer; font-family: 'Glyphicons Halflings' !important;"></span>
                <span id="basketNum" style="position: absolute; border: 1px; border-radius: 50% !important; width: 17px; background-color: red; height: 17px; top: 5px; color: white; font-size: 0.7em; padding-top: 0; padding-right: 5px; line-height: 16px">{{$myBasket}}</span>
            </a>
        </li>
    @endif

</ul>
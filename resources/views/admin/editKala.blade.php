<style>
    table tr{
    width: 100%!important;
    word-wrap: wrap;
    display:table;
    }
    .tableHead th {
        width:100px !important;
    }
    .tableRow td{
    width:100px !important;
}
</style>
@extends('admin.layout')
@section('content')
<main>
    <div class="modalBackdrop">
        <div id='unitStuffContainer' class="alert alert-danger" style="max-width: 200px; background-color: #ffffff66; padding: 5px; width: 100%; max-height: 85vh; overflow: auto;">
        </div>
    </div>
    <!-- Main page content-->
    <div class='container px-4 mt-n10' style='padding:0; margin:auto; '>
        <div class="o-headline" style="padding: 0; margin-bottom: 10px; margin-top:65px">
            <div id="main-cart">
                <h6 class=" c-checkout__tab--active"> ویرایش ({{ $kala->GoodName }})</h6>
            </div>
        </div>
        <div class='card mb-4' style="background-color:#c5c5c5; padding-top:1%; paddding:0;">
            <div class="container">
                <ul class="header-list nav nav-tabs" data-tabs="tabs">
                    <li><a class="active" data-toggle="tab" style="color:black;" href="#red">مشخصات اصلی</a></li>
                    <li><a data-toggle="tab" style="color:black;"  href="#parts">دسته بندی</a></li>
                    <li><a data-toggle="tab" style="color:black;"  href="#yellow">تنظیمات اختصاصی</a></li>
                    <li><a data-toggle="tab" style="color:black;"  href="#green"> ویژگی های کالا</a></li>
                    <li><a data-toggle="tab" style="color:black;"  href="#pictures">تصاویر </a></li>
                    <li><a data-toggle="tab" style="color:black;"  href="#orange">گردش قیمت</a></li>
                </ul>
                <div class="c-checkout tab-content" style="background-color:#f5f5f5; margin:0; margin-bottom:2%; padding:2%; border-radius:10px 10px 2px 2px;">
                    <div class="tab-pane" id="parts">
                        <div class="c-checkout" style="border-radius:10px 10px 2px 2px;">
                            <div class="container">
                                    <Button type="button" id="submitSubGroup"  @if(hasPermission(Session::get( 'adminId'),'kalaList' ) <1 ) disabled @endif  class="btn btn-success text-white buttonHover" style="float: left;"> ذخیره <i class="fa-light fa-save fa-lg"></i></Button>
                            
                                {{-- <form action="{{url('/addOrDeleteKalaFromSubGroup')}}"   style="display: inline" method="POST"> --}}
                                    @csrf
                                    <input type="text" style="display: none;" name="kalaId" id="kalaIdEdit" value="{{$kala->GoodSn}}"/>
                                <div class="row">
                                    <div class="col-sm-6">
                                            <div class="well" style="margin-top:2%;">
                                                <h6 style="">گروه های اصلی</h6>
                                            </div>
                                            <div class="alert">
                                                <input type="text" class="form-control" style="margin-top:10px;" name="search_mainPart" placeholder="جستجو">
                                            </div>
                                            <table class="tableSection3 table table-bordered table table-hover table-sm table-light" style='td:hover{ cursor:move;}; max-height: 200px;'>
                                                <thead>
                                                    <tr class="tableHead">
                                                        <th>ردیف</th>
                                                        <th>گروه اصلی </th>
                                                        <th>فعال</th>
                                                        <th>انتخاب</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($mainGroupList as $mainGroup)
                                                        <tr class="tableRow">
                                                            <td>{{ $loop->index+1 }}</td>
                                                            <td>{{ $mainGroup->title }}</td>
                                                            <td><input type="checkBox" class="form-check-input" disabled @if ($mainGroup->exist == 'ok') checked @else @endif></td>
                                                            <td>
                                                                <input class="mainGroupId  form-check-input" type="radio" value="{{ $mainGroup->id . '_' . $kala->GoodSn }}" name="IDs[]" id="flexCheckChecked">
                                                                <input class="mainGroupId" type="text" value="{{ $kala->GoodSn }}" name="ProductId" id="GoodSn" style="display: none">
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="well" style="margin-top:2%;">
                                            <div class="well">
                                                <h6 style="">گروه های فرعی</h6>
                                            </div>
                                            <div class="alert">
                                                <input type="text" class="form-control" style="margin-top:10px;" name="search_mainPart" placeholder="جستجو">
                                            </div>
                                            <table class="tableSection3 table table-bordered table table-hover table-sm table-light" style='td:hover{ cursor:move;}; max-height: 350px;'>
                                                <thead>
                                                    <tr>
                                                        <th>ردیف</th>
                                                        <th>گروه فرعی </th>
                                                        <th>فعال</th>
                                                        <th>انتخاب</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="subGroup1">

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                               
                            {{-- </form> --}}
                            </div>
                        </div>
                    </div>
                    <div class="c-checkout tab-pane active" id="red" style="border-radius:10px 10px 2px 2px;">
                            <span class="row" style="padding: 1%">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">گروه اصلی</label>
                                        <input type="text" disabled class="form-control" value="{{ $kala->NameGRP }}"
                                            id="exampleFormControlInput1" placeholder="">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">گروه فرعی</label>
                                        <input type="email" disabled class="form-control" value="{{ $kala->NameGRP }}"
                                            id="exampleFormControlInput1" placeholder="">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1"> واحد اصلی</label>
                                        <input type="email" disabled class="form-control" value="{{ $kala->UName }}"
                                            id="exampleFormControlInput1" placeholder="">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1"> واحد فرعی</label>
                                        <input type="email" disabled class="form-control" @if($kala->secondUnit) value="{{ $kala->secondUnit }}" @endif
                                            id="exampleFormControlInput1" placeholder="">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1" style="display: block;font-siz:18px;color:black;font-weight:bold;">قیمت اصلی </label>
                                        <div style="display: inline;"><p style="font-siz:16px;color:black;font-weight:bold;display:inline"> {{  number_format($kala->mainPrice/1)  }}</p></div>
                                        <span style="color:red">ریال</span>
                                    </div>
                                    <hr style="width: 40%;border:5px;height: 5px;">
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1" style="display: block;font-siz:18px;color:black;font-weight:bold;">قیمت خط خورده</label>
                                        <div style="display: inline"><p style="font-siz:16px;color:rgb(172, 11, 11);font-weight:bold;display:inline">{{ number_format($kala->overLinePrice/1)  }}</p></div>
                                        <span style="color:red">ریال</span>
                                    </div>
                                </div>
                            </span>
                    </div>

                    <div class="c-checkout tab-pane" id="orange" style="border-radius:10px 10px 2px 2px;">
                        <span class="row" style="padding: 1%">

                            <div class="col-sm-12">
                                <div class="well" style="margin-top:2%;">
                                    <h6 style="">گردش قیمت </h6>
                                </div>
                                <div class="alert">
                                    <input type="text" class="form-control" so style="margin-top:10px;width:40%"
                                            name="search_mainPart" placeholder="جستجو">
                                </div>
                                <table class="tableSection3 table table-bordered table table-hover table-sm table-light" style='td:hover{ cursor:move;}; max-height: 200px;'>
                                    <thead>
                                        <tr class="tableHead">
                                            <th>ردیف</th>
                                            <th>اسم کاربر </th>
                                            <th>برنامه</th>
                                            <th>تاریخ</th>
                                            <th>قیمت قبلی اول</th>
                                            <th>قیمت بعدی اول</th>
                                            <th>قیمت دوم قبلی</th>
                                            <th>قیمت دوم بعدی</th>
                                            <th>انتخاب</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($priceHistory as $history)
                                            <tr class="tableRow">
                                                <td>{{ $loop->index+1 }}</td>
                                                <td>{{ $history->name.' '.$history->lastName }}</td>
                                                <td>{{ $history->application }}</td>
                                                <td>{{\Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($history->changedate))->format('Y/m/d')}}</td>
                                                <td>{{ $history->firstPrice }}</td>
                                                <td>{{ $history->changedFirstPrice}}</td>
                                                <td>{{ $history->secondPrice }}</td>
                                                <td>{{ $history->changedSecondPrice }}</td>
                                                <td>
                                                    <input class="mainGroupId  form-check-input" type="radio"
                                                        value="{{ $mainGroup->id . '_' . $kala->GoodSn }}"
                                                        name="IDs[]" id="flexCheckChecked">
                                                        <input class="mainGroupId" type="text"
                                                        value="{{ $kala->GoodSn }}"
                                                        name="ProductId" id="GoodSn" style="display: none">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                        </div>

                        </span>
                    </div>

                    <div class="c-checkout tab-pane" id="pictures" style="border-radius:10px 10px 2px 2px;">
                        <div class="container" style="padding:5px 10px 10px 10px">
                            <span>

                            </span>
                            <div class="row">
                                <div class='modal-body'style="display:flex;  justify-content: flex-end; float:right;">
                                    <div id='pslider' class=' swiper-container swiper-container-horizontal swiper-container-rtl' style="padding-left:5%">
                                        <div class=' product-box swiper-wrapper'>
                                            <iframe name="votar" style="display:none;"></iframe>
                                            <form action="{{ url('/addpicture') }}" target="votar" enctype="multipart/form-data" method="POST">
                                                @csrf
                                                <button type="submit" id="submitChangePic" disabled style="float: left;" class="btn btn-success text-white buttonHover mb-1"> ذخیره <i class="fa fa-save fa-lg"></i></button>
                                                <input type="text" style="display: none;" name="kalaId" id="" value="{{ $kala->GoodSn }}">
                                         
                                            <table class="table align-middle text-center">
                                                <thead class="table bg-danger ">
                                                    <tr>
                                                        <th> تصویر اصلی </th>
                                                        <th>  تصویر اول </th>
                                                        <th>تصویر دوم  </th>
                                                        <th>  تصویر سوم  </th>
                                                        <th>  تصویر چهارم </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <div class='product-item swiper-slide' style='width:100%;'>
                                                                <img id="mainPicEdit" src="{{url('/resources/assets/images/kala/'.$kala->GoodSn.'_1.jpg')}}" />
                                                            </div>
                                                            <div>
                                                                <label for="mainPic" class="btn btn-success editButtonHover">  ویرایش <i class="fa-light fa-image fa-lg"></i></label>
                                                                <input type="file"  onchange='document.getElementById("mainPicEdit").src = window.URL.createObjectURL(this.files[0]); ' style="display: none" class="form-control" name="firstPic" id="mainPic">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class='product-item swiper-slide' style='width:100%;'>
                                                                <img id="1PicEdit" src="{{url('/resources/assets/images/kala/'.$kala->GoodSn.'_2.jpg')}}" />
                                                            </div>
                                                            <div>
                                                                <label for="1Pic" class="btn btn-success editButtonHover"> ویرایش <i class="fa-light fa-image fa-lg"></i></label>
                                                                <input type="file"   style="display: none" class="form-control" name="secondPic" >
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class='product-item swiper-slide' style='width:100%;'>
                                                                <img id="2PicEdit" src="{{url('/resources/assets/images/kala/'.$kala->GoodSn.'_3.jpg')}}" />
                                                            </div>
                                                            <div>
                                                                <label for="2Pic" class="btn btn-success editButtonHover"> ویرایش <i class="fa-light fa-image fa-lg"></i></label>
                                                                <input type="file"    style="display: none" class="form-control" name="thirthPic" >
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class='product-item swiper-slide' style='width:100%;'>
                                                                <img id="3PicEdit" src="{{url('/resources/assets/images/kala/'.$kala->GoodSn.'_4.jpg')}}" />
                                                            </div>
                                                            <div>
                                                                <label for="3Pic" class="btn btn-success editButtonHover"> ویرایش <i class="fa-light fa-image fa-lg"></i></label>
                                                                <input type="file"   style="display: none" class="form-control" name="fourthPic" >
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class='product-item swiper-slide' style='width:100%;'>
                                                                <img id="4PicEdit" src="{{url('/resources/assets/images/kala/'.$kala->GoodSn.'_5.jpg')}}" />
                                                            </div>
                                                            <div>
                                                                <label for="4Pic" class="btn btn-success editButtonHover"> ویرایش <i class="fa-light fa-image fa-lg"></i></label>
                                                                <input type="file"   style="display: none" class="form-control" name="fifthPic" >
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                          </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="c-checkout tab-pane" id="yellow" style="border-radius:10px 10px 2px 2px;">
                        <div class="container ">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <span>
                                            <input class="form-check-input" disabled type="checkbox" value="" id="defaultCheck1">
                                            <label>علامت گذاری کالای جدید</label>
                                        </span>
                                    </div>
                                    <div class="form-group">
                                        <span>
                                            <label for="whereHouse">تخصیص انبار</label>
                                            <input class="form-check-input"  type="checkbox" value="" id="stockTakhsis">
                                        </span>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <span>
                                            <button id="minimamSale" onclick="SetMinQty({{$kala->GoodSn}},this)" class="btn-add-to-cart">حد اقل فروش<i class="far fa-shopping-cart text-white ps-2"></i></button>
                                        </span>
                                        <span id="minSaleValue"> {{$kala->minSale}}</span> <span>{{$kala->secondUnit}} تعیین شده است </span>
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <span>
                                            <button id="maximamSale" onclick="SetMaxQty({{$kala->GoodSn}},this)" class="btn-add-to-cart">حد اکثر فروش<i
                                                    class="far fa-shopping-cart text-white ps-2"></i></button>
                                        </span>
                                        <span id="maxSaleValue"> {{$kala->maxSale}}</span> <span>{{$kala->secondUnit}} تعیین شده است </span>
                                    </div>

                                    <form action="{{url('/restrictSale')}}" id="restrictFormStuff" method="get">
                                        <div class="form-group input-group-sm">
                                            <span>
                                                حد ایرور هزینه
                                            </span>
                                            <input type="number" onchange="activeSubmitButton(this)" value="{{$kala->costLimit}}" name="costLimit" class="form-control" id="">
                                        </div>
                                        <div class="form-group input-group-sm">
                                            <span>
                                                نوع هزینه
                                            </span>
                                            <select  onchange="activeSubmitButton(this)" class="form-select" name="infors">
                                                @foreach($infors as $infor)
                                                <option @if($kala->inforsType==$infor->SnInfor) selected @endif  value="{{$infor->SnInfor}}">{{$infor->InforName}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group input-group-sm">
                                            <span>
                                                متن ایرور هزینه
                                            </span>
                                            <textarea  class="form-control" onchange="activeSubmitButton(this)" name="costErrorContent" rows="3" cols="24">{{$kala->costError}}</textarea>
                                        </div>
                                        <div class="form-group input-group-sm">
                                            <span>
                                            مقدار هزینه
                                            </span>
                                            <input type="number" onchange="activeSubmitButton(this)" value="{{$kala->costAmount}}" name="costAmount" class="form-control" id="">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        @csrf
                                        <div class="form-group mt-1" style="">
                                           <input type="text" style="display: none" name="kalaId" value="{{$kala->GoodSn}}">
                                        <button style="float: left;" disabled type="submit" id="restrictStuffId" class="btn btn-success text-white buttonHover"> ذخیره <i class="fa-light fa-save fa-lg"></i></button>
                                    </div>
                                        <span>
                                            <label >نقطه هشدار کالا</label>
                                            <input type="number" class="form-control" value="{{$kala->alarmAmount}}" required onclick="activeSubmitButton(this)" id="existanceAlarm" name='alarmAmount' style="width:50%">
                                        </span>
                                        <div class="form-group input-group-sm">
                                            <span>
                                                <input class="form-check-input" type="checkbox" onchange="activeSubmitButton(this)" value="" id="callOnSale" name="callOnSale[]"
                                                @if($kala->callOnSale==1) checked @endif/>
                                                <label class="form-check-label">
                                                    تماس جهت خرید کالا
                                                </label>
                                            </span>
                                        </div>

                                        <div class="form-group input-group-sm">
                                            <span>
                                                <input class="form-check-input" type="checkbox"  onchange="activeSubmitButton(this)" value="" id="zeroExistance" name="zeroExistance[]"
                                                @if($kala->zeroExistance==1) checked @endif />
                                                <label class="form-check-label">
                                                    صفر کردن موجودی کالا
                                                </label>
                                            </span>
                                        </div>

                                        <div class="form-group input-group-sm">
                                            <span>
                                                <input class="form-check-input" type="checkbox"  onchange="activeSubmitButton(this)" value="" id="showTakhfifPercent" name="activeTakhfifPercent[]"
                                                @if($kala->showTakhfifPercent==1) checked @endif />
                                                <label class="form-check-label" for="showTakhfifPercent">
                                                    نمایش درصد تخفیف
                                                </label>
                                            </span>
                                        </div>

                                        <div class="form-group input-group-sm">
                                            <span>
                                                <input class="form-check-input" type="checkbox"  onchange="activeSubmitButton(this)" value="" id="showFirstPrice" name="overLine[]"
                                                @if($kala->overLine==1) checked @endif />
                                                <label class="form-check-label" for="showFirstPrice">
                                                    نمایش قیمت خط خورده
                                                </label>
                                            </span>
                                        </div>
                                    <div class="form-group input-group-sm">
                                        <span>
                                            <input class="form-check-input"  onchange="activeSubmitButton(this)" type="checkbox" value="" id="inactiveAll"  @if($kala->hideKala==1) checked @endif name="hideKala[]">
                                            <label class="form-check-label"> غیر فعال </label>
                                        </span>
                                    </div>
                                    <div class="form-group input-group-sm">
                                        <span>
                                            <input class="form-check-input"  onchange="activeSubmitButton(this)" type="checkbox" value="" name="freeExistance[]" id="freeExistance"  @if($kala->freeExistance==1) checked @endif >
                                            <label class="form-check-label"> آزادگذاری فروش </label>
                                        </span>
                                    </div>
                                    <div class="form-group input-group-sm">
                                        <span>
                                            <input class="form-check-input"  onchange="activeSubmitButton(this)" type="checkbox" value="" name="activePishKharid[]" id="activePreBuy"  @if($kala->activePishKharid==1) checked @endif >
                                            <label class="form-check-label"> فعالسازی پیش خرید </label>
                                        </span>
                                    </div>
                                    
                                </form>
                                </div>
                            </div>
                            <div class="row" >
                                <div class="col-sm-5" id="allStock" style="display:none">
                                    <div class='modal-body'>
                                        <input type="text" class="form-control" style="margin-top:10px;" id=""  placeholder="جستجو"> <br>
                                        <div class='c-checkout' style='padding-right:0;'>
                                            <table class="tableSection table table-bordered table table-hover table-sm table-light"   style='td:hover{ cursor:move;}'>
                                                <thead>
                                                    <tr>
                                                        <th>ردیف</th>
                                                        <th>اسم </th>
                                                        <th>انتخاب</th>
                                                    </tr>
                                                </thead>
                                                <tbody style="" id="allStockForList">
                                                @foreach ($stocks as $stock)
                                                    <tr  onclick="checkCheckBox(this)">
                                                        <td>{{$loop->index+1}}</td>
                                                        <td>{{$stock->NameStock}}</td>
                                                        <td>
                                                            <input class="form-check-input" name="stock[]" type="checkbox" value="{{$stock->SnStock.'_'.$stock->NameStock}}" id="stockId">
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-2"  id="addAndDeleteStock" style="display:none">
                                    <div class='' style="position:relative; right: 5%; top: 30%;">
                                        <div >
                                            <a id="addStockToList">
                                                <i class="fa-regular fa-circle-chevron-left fa-3x chevronHover"></i>
                                            </a>
                                            <br />
                                            <a id="removeStockFromList">
                                                <i class="fa-regular fa-circle-chevron-right fa-3x chevronHover"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-5" id="addedStock" @if(count($addedStocks)>0) style="display:flex" @endif>
                                    <div class='modal-body'>
                                        <form action="{{url('/addStockToList')}}" method="POST" id="submitStockToList" style="display: inline" >
                                            <input type="text" name="kalaId" value="{{$kala->GoodSn}}" style="display: none" id="">
                                            @csrf
                                            <button class='btn btn-success buttonHover' disabled  type="submit" id="stockSubmit" disabled style="display:none"  >ذخیره</button>
                                            <input type="text" class="form-control" style="margin-top:10px;" id="serachKalaOfSubGroup"  placeholder="جستجو"> <br>
                                            <div class='c-checkout' style='padding-right:0;'>
                                                <table class="tableSection table table-bordered table table-hover table-sm table-light" style='td:hover{ cursor:move;}'>
                                                    <thead>
                                                        <tr >
                                                            <th>ردیف</th>
                                                            <th>انبار</th>
                                                            <th>انتخاب</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody style="height: 370px;" id="allstockOfList">
                                                        @foreach ($addedStocks as $stock)
                                                        <tr   onclick="checkCheckBox(this)">
                                                            <td>{{$loop->index+1}}</td>
                                                            <td>{{$stock->NameStock}}</td>
                                                            <td>
                                                            <input  class="addStockToList form-check-input" name="addedStockToList[]" type="checkbox" value="{{$stock->SnStock}}">
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="c-checkout tab-pane" id="green" style="border-radius:10px 10px 2px 2px;">
                        <span class="row align-items-center" style="padding: 1%;">

                                <div class="row">
                                    <div class="row col-sm-6">
                                        <div class="col-sm-4" style="margin-top: 1%">
                                            <label for="shortExpain">توضیحات مختصر کالا</label>
                                        </div>
                                        <div class="col-sm-8 form-group" style="margin-top: 1%">
                                            <textarea disabled class="form-control" id="shortExpain" rows="2"></textarea>
                                        </div>
                                        <div class="col-sm-4" style="margin-top: 1%">
                                            <label for="kalaTags"> تگ کردن کالای مترادف </label>
                                        </div>
                                        <div class="col-sm-8 form-group" style="margin-top: 1%">
                                            <input type="email" disabled class="form-control" id="kalaTags"
                                                placeholder="">
                                        </div>
                                        <div class="col-sm-4" style="margin-top: 1%">
                                            <label for="exampleFormControlTextarea1">لیست کالاهای مشابه</label>
                                        </div>
                                        <div class="col-sm-8 form-group" style="margin-top: 1%">
                                            <input type="checkbox" class="form-check-input" id="sameKalaList" />
                                        </div>
                                    </div>
                                    <div class=" col-sm-6">
                                        <iframe name="votar" style="display:none;"></iframe>
                                        <form action="{{url('/addDescKala')}}" target="votar"   method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm mt-2 buttonHover" style="float:left;">ذخیره</button>
                                            <input type="text" style="display: none" name="kalaId" value="{{$kala->GoodSn}}"/>
                                            <div class="col-sm-3" style="margin-top: 1%">
                                                <label for="exampleFormControlSelect2">توضیحات کامل کالا</label>
                                            </div>
                                            <div class="col-sm-6 form-group" style="margin-top: 1%">
                                                <textarea class="form-control" name="discription" id="exampleFormControlTextarea1" rows="4">{{$kala->descProduct}}</textarea>
                                               
                                            </div>
                                            
                                          </form>
                                    </div>
                                </div>
                        </span>


                        <div class="row" >
                            <div class="col-sm-5" id="addKalaToList" style="display:none">
                                <div class='modal-body'>
                                    <input type="text" class="form-control" style="margin-top:10px;"
                                    id="serachKalaForAssameList"  placeholder="جستجو">
                                    <div class='c-checkout' style='padding-right:0;'>
                                        <table class="tableSection table table-bordered table table-hover table-sm table-light"   style='td:hover{ cursor:move;}'>
                                            <thead>
                                                <tr>
                                                    <th>ردیف</th>
                                                    <th>اسم </th>
                                                    <th><input type="checkbox" name=""  class="selectAllFromTop form-check-input"  ></th>
                                                </tr>
                                            </thead>
                                            <tbody style="height: 400px;" id="allKalaForList">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-2"  id="addAndDelete" style="display:none">
                                <div class='' style="position:relative; left: 5%; top: 30%;">
                                    <div>
                                        <a id="addDataToList">
                                            <i class="fa-regular fa-circle-chevron-left fa-3x chevronHover"></i>
                                        </a>
                                        <br />
                                        <a id="removeDataFromList">
                                            <i class="fa-regular fa-circle-chevron-right fa-3x chevronHover"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-5" id="addedList" @if(count($assameKala)<1) style="display: none"@endif>
                                <div class='modal-body'>
                                    <iframe name="votar" style="display:none;"></iframe>
                                    <form action="{{url('/addKalaToList')}}" target="votar"  method="GET" style="display: inline" >
                                        <input type="text" name="mainKalaId" value="{{$kala->GoodSn}}" style="display: none" id="">
                                        @csrf

                                        <button class='btn btn-success' type="submit" id="addToListSubmit" style="display:none"  >ذخیره</button>
                                        <input type="text" class="form-control" style="margin-top:10px;"
                                        id="serachKalaOfSubGroup"  placeholder="جستجو">
                                        <div class='c-checkout' style='padding-right:0;'>
                                        <table class="tableSection table table-bordered table table-hover table-sm table-light" style='td:hover{ cursor:move;}'>
                                            <thead>
                                                <tr>
                                                    <th>ردیف</th>
                                                    <th>کالای مشابه</th>
                                                    <th><input type="checkbox" name=""  class="selectAllFromTop form-check-input"  ></th>
                                                </tr>
                                            </thead>
                                            <tbody style="height: 370px;" id="allKalaOfList">
                                                @foreach ($assameKala as $kala)
                                                <tr class="addedTrList"   onclick="checkCheckBox(this,event)">
                                                    <td>{{$loop->index+1}}</td>
                                                    <td>{{$kala->GoodName}}</td>
                                                    <td>
                                                    <input class="form-check-input" style="" name="" type="checkbox" value="{{$kala->GoodSn.'_'.$kala->GoodName}}" id="kalaIds">
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
    <script>
        window.onload = function() {

            checkActivation();

            $("#addKala").click(function() {
                var subGroupID = [];
                $('input[name=subGroupId]:checked').map(function() {
                    subGroupID.push($(this).val());
                });
                $.ajax({
                    type: 'get',
                    async: true,
                    dataType: 'text',
                    url: "{{ url('/addKalaToSubGroups') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        mainGrId: $('.mainGroupId:checked').val().split('_')[0],
                        subGrId: subGroupID,
                        kalaId: $('.mainGroupId:checked').val().split('_')[1]
                    },
                    success: function(answer) {
                        data = $.parseJSON(answer);
                        $('#subGroup1').empty();
                        alert('اضافه شد');
                    }
                });
            });

            // used for deleting kala from subgroups

            $("#deleteKala").click(function() {
                var subGroupID = [];
                $('input[name=subGroupId]:checked').map(function() {
                    subGroupID.push($(this).val());
                });
                $.ajax({
                    type: 'get',
                    async: true,
                    dataType: 'text',
                    url: "{{ url('/deleteKalaFromSubGroups') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        mainGrId: $('.mainGroupId:checked').val().split('_')[0],
                        subGrId: subGroupID,
                        kalaId: $('.mainGroupId:checked').val().split('_')[1]
                    },
                    success: function(answer) {
                        data = $.parseJSON(answer);
                        $('#subGroup1').empty();
                        alert('حذف شد');
                    }
                });
            });
        }
 </script>
@endsection

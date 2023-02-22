
@extends('admin.layout')
@section('content')
    
<div class="modalBackdrop">
        <div id='unitStuffContainer' class="alert alert-danger" style="max-width: 200px; background-color: #ffffff66; padding: 5px; width: 100%; max-height: 85vh; overflow: auto;">
        </div>
    </div>
    <div class='container-xl' style='margin-top:4%;'>
        <div class="o-headline" style="padding: 0; margin-bottom: 10px; margin-top:65px">
            <div id="main-cart">
                <h5 class="c-checkout__tab--active">لیست کالاها</h5>
            </div>
        </div>
        <div class="c-checkout card" style="padding-right:0;">
            <div class="container">
                <div class='modal-body'>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="well" style="margin-top:1%;">
                                <span class='row container pb-3'>
                                    <div class="col-sm-2">
                                        <input type="text"  id="kalaNameId" class="form-control" autocomplete="off"  placeholder="اسم">
                                    </div>
                                    <div class="col-sm-2">
                                        <select class="form-select" id="selectStock">
                                        <option value="0" hidden>انبار</option>
                                            @foreach ($stocks as $stock)
                                            <option value="{{$stock->SnStock }}" @if($stock->SnStock==23) selected @endif>{{$stock->NameStock }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <select class="form-select" id="kalaCodeId">
                                            <option value="10" hidden> عکس </option>
                                            <option value="0"> عکس دار </option>
                                            <option value="1"> بدون عکس </option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <select class="form-select" id="selectStockExist">
                                            <option value="10" hidden> موجودی </option>
                                            <option value="0"> موجودی صفر </option>
                                            <option value="1"> موجودی عدم صفر </option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <select class="form-select" id="mainGroupForKalaListSearch">
                                            <option value="0" hidden>گروه بندی</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <select class="form-select" id="subGroupForKalaListSearch">
                                            <option value="0" hidden>گروه های فرعی</option>
                                        </select>
                                    </div>
                                </span>
                                <div class="row text-end">
                                    <div class="alert col-lg-12" style="padding: 0; padding-right:0.3%; margin:0;">
                                        <button class="kala-btn btn btn-success text-warning  buttonHover" disabled id="openEditKalaModal"> ویرایش <i class="fal fa-edit fa-lg" aria-hidden="true"></i></button>
                                        <input type="text" class="form-control" value="" id="kalaIdForEdit1" style="display:none;">
                                        <form action="{{url('/editKala')}}" method="POST" style="display: inline;">
                                            @csrf
                                            @if(hasPermission(Session::get( 'adminId'),'kalaList' ) > -1) 
                                             <!-- <button type="submit" id="editKalaList" disabled class="kala-btn btn btn-info btn-md text-warning"> ویرایش <i class="fal fa-edit fa-lg" aria-hidden="true"></i></button> -->
											<input type="text"  name="kalaId" class="form-control" value="" id="kalaIdForEdit" style="display:none;">
                                           <!-- <button type="submit" id="editKalaList" disabled class="kala-btn btn btn-success text-warning"> رویت <i class="fal fa-eye fa-lg" aria-hidden="true"></i></button> -->
                                            @endif
                                            <button type="button" id="openViewTenSalesModal" disabled class="kala-btn btn btn-success text-warning">ده فروش آخر<i class="fal fa-history fa-lg" aria-hidden="true"></i></button>
                                            @if(hasPermission(Session::get( 'adminId'),'kalaList' ) > 0) 
                                            <button type="button" data-toggle="modal"  onclick="openChangePriceModal()"  disabled class="kala-btn btn btn-success text-warning"> تغییر قیمت <i class="fal fa-exchange-alt fa-lg" aria-hidden="true"></i></button>
                                            @endif
                                            @if(hasPermission(Session::get( 'adminId'),'kalaList' ) > 1) 
                                            <button type="submit" id="editKalaList" disabled class="kala-btn btn btn-success text-warning"> ارسال به اکسل  <i class="fal fa-file-excel fa-lg" aria-hidden="true"></i></button>
                                            @endif
                                        </form>
                                    </div>
                                </div> <br>

                                <table class="table table-bordered table-striped" id="listKala">
                                    <thead class="tableHeader">
                                    <tr>
                                        <th>ردیف</th>
                                        <th style="width: 222px">اسم</th>
                                        <th>کد</th>
                                        <th>گروه اصلی </th>
                                        <th>آخرین.فروش</th>
                                        <th>بروزرسانی</th>
                                        <th>غیرفعال</th>
                                        <th>قیمت1</th>
                                        <th>قیمت2</th>
                                        <th>موجودی </th>
                                        <th>انتخاب </th>
                                        
                                    </tr>
                                    </thead>
                                    <tbody id='kalaContainer' class="select-highlightKala tableBody">
                                        @foreach ($listKala as $kala)
                                    <tr>
                                        <td>{{$loop->index+1}}</td>
                                        <td style="width: 222px">{{$kala->GoodName}}</td>
                                        <td>{{$kala->GoodCde}}</td>
                                       
                                        <td>{{$kala->NameGRP}}</td>
                                        <td>{{\Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($kala->lastDate))->format('Y/m/d')}}</td>
                                        <td>1401.2.21</td>
                                        <td>
                                            <input class="kala form-check-input" @if($kala->hideKala>0) checked @else @endif name="kalaId[]" disabled type="checkbox" value="{{$kala->GoodSn}}" id="">
                                        </td>
                                        <td>{{number_format($kala->Price4/1)}}</td>
                                        <td>{{number_format($kala->Price3/1)}}</td>
                                        @if ($kala->Amount==0)
                                        <td style="color:red;background-color:azure">0</td>
                                        @else
                                        <td>{{number_format($kala->Amount,3,"/")}}</td>
                                        @endif
                                        <td>
                                            <input class="kala form-check-input" name="kalaId[]" type="radio" value="{{$kala->GoodSn.'_'.$kala->Price4.'_'.$kala->Price3}}" id="flexCheckCheckedKala">
                                        </td>
                                        
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- modal of new group -->
<div class="modal dragAbleModal" id="changePriceModal" data-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="exampleModalLongTitle" style="font-size: 20px; font-weight:bold;"> تغییر قیمت  </h5>
                <button type="button" class="close btn text-white bg-danger " data-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="modal-body" style="padding:5%; padding-right:0; padding-top:0; margin-right:10px">
                <form action="{{url('/changePriceKala')}}" method="GET" id="changePriceForm" class="form">
                    <div class="form-group mt-2">
                        <label class="form-label" style="font-size: 14px; font-weight:bold; color:red">قیمت خط خورده (ریال) </label>
                        <input type="text" required class="form-control" style="color: red;" value="" autocomplete="off" name="firstPrice" id="firstPrice" placeholder="">
                    </div>
                    <div class="form-group">
                        <label class="form-label" style="font-size: 14px; font-weight:bold; color:rgb(25, 0, 255)"> قیمت اصلی (ریال) </label>
                        <input type="text" required class="form-control"  style="font-weight: bold;" value="" autocomplete="off" name="secondPrice" id="secondPrice" placeholder="">
                    </div>
                        <input type="text" name="kalaId" style="display: none" id="kalaId">
                        <span style="display:none" id="moreAlert">قیمت خورده باید بیشتر از قیمت اصلی باشد.</span>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" id="cancelChangePrice" data-dismiss="modal">انصراف <i class="fa-solid fa-xmark fa-lg"></i></button>
                        <button type="submit" id="submitChangePrice"  class="btn btn-success">ذخیره <i class="fa fa-save fa-lg" aria-hidden="true"></i></button>
                    </div>
                </form>
             </div>
        </div>
    </div>
</div>

<!-- modal of 10 last sales -->
<div class="modal fade dragAbleModal" id="viewTenSales" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" style="font-size: 20px; font-weight:bold;">ده فروش آخر</h5>
                    <button type="button" class="close btn bg-danger" data-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark fa-xl"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead class="tableHeader bg-success">
                            <tr>
                                <th>ردیف</th>
                               
                                <th>نام</th>
                                <th>تاریخ خ</th>
                                <th>فی</th>
                                <th>تعداد</th>
                                <th>کل مبلغ</th>
								 <th>کد</th>
                            </tr>
                        </thead>
                        <tbody class="tableBody" id="lastTenSaleBody">
                           
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>








<!-- modal for Editing Kala -->
      
<div class="modal fade dragAbleModal" id="editingListKala" data-backdrop="static"  data-bs-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
            <div class="modal-header myModalHeader">
                <h5 class="modal-title" id="editKalaTitle"> </h5>
               <span style="display: flex; float:left;"> <button type="button" class="btn-close bg-danger" data-dismiss="modal" id="closeEditModal" aria-label="Close"></button> </span>
            </div>
            <div class="modal-body">
              <div class='container'>
                 <div class="row descForall">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label class="fs-6" for="label">گروه اصلی</label>
                                        <input type="text" disabled class="form-control" value="" id="original">
                                    </div>
                                 </div>
                                 <div class="col-sm-3">
                                    <div class="form-group">
                                        <label class="fs-6" for="label">گروه فرعی</label>
                                        <input type="email" disabled class="form-control" value="فرعی " id="subsidiary">
                                    </div>
                                 </div>
                                 <div class="col-sm-2"> 
                                    <div class="form-group ">
                                            <label class="fs-6 mt-1" for="label" style="display: block;font-siz:20px;color:black;">قیمت اصلی </label>
                                            <div style="display: inline;"><p style="font-siz:16px;color:black;font-weight:bold;display:inline"> </p></div>
                                        <span style="color:red" id="mainPrice">ریال</span>
                                    </div>
                                </div>
                                <div class="col-sm-2"> 
                                    <div class="form-group">
                                            <label class="fs-6 mt-1" for="label" style="display: block;font-siz:18px;color:black;">قیمت خط خورده</label>
                                            <div style="display: inline"><p style="font-siz:16px;color:rgb(172, 11, 11);font-weight:bold;display:inline"></p></div>
                                            <span style="color:red;text-decoration: line-through;" id="overLinePrice">  ریال   </span>
                                    </div>
                                </div>
                     </div>
       
        <div class='card mb-4' style="background-color:#c5c5c5; padding-top:1%; paddding:0;">
            <div class="container">
                <ul class="header-list nav nav-tabs" data-tabs="tabs">
                    <li><a class="active" data-toggle="tab" style="color:black;"  href="#parts">دسته بندی</a></li>
                    <li><a data-toggle="tab" style="color:black;"  href="#yellow">تنظیمات اختصاصی</a></li>
                    <li><a data-toggle="tab" style="color:black;"  href="#green"> ویژگی های کالا</a></li>
                    <li><a data-toggle="tab" style="color:black;"  href="#pictures">تصاویر </a></li>
                    <li><a data-toggle="tab" style="color:black;"  href="#orange">گردش قیمت</a></li>
                </ul>
                <div class="c-checkout tab-content" style="background-color:#f5f5f5; margin:0; margin-bottom:2%; padding:2%; border-radius:10px 10px 2px 2px;">
                    <div class="tab-pane active" id="parts">
                        <div class="c-checkout" style="border-radius:10px 10px 2px 2px;">
                            <div class="container">
                             <form action="{{url('/addOrDeleteKalaFromSubGroup')}}" style="display: inline" method="GET" id="groupSubgoupCategory">
                                     @csrf
                                    <input type="text" style="display: none;" name="kalaId" id="kalaIdEdit" value=""/>
                                <div class="row">
                                    <div class="col-sm-6">
                                            <div class="well" style="margin-top:2%;">
                                                <h6 style="">گروه های اصلی</h6>
                                            </div>
										<!---
                                            <div class="alert">
                                                <input type="text" class="form-control" style="margin-top:10px;" name="search_mainPart" placeholder="جستجو">
                                            </div>
!--->
                                            <table class="table table-bordered table table-hover table-sm table-light">
                                                <thead class="tableHeader bg-success">
                                                    <tr>
                                                        <th>ردیف</th>
                                                        <th>گروه اصلی </th>
                                                        <th>فعال</th>
                                                        <th>انتخاب</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="tableBody" id="maingroupTableBody">
                                                </tbody>
                                            </table>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="well" style="margin-top:2%;">
                                            <div class="well">
                                                <h6 style="">گروه های فرعی</h6>
                                            </div>
<!---                                            <div class="alert">
                                                <input type="text" class="form-control" style="margin-top:10px;" name="search_mainPart" placeholder="جستجو">
                                            </div>
!-->
                                                <table class="table table-bordered table table-hover table-sm table-light">
                                                    <thead class="tableHeader bg-success">
                                                        <tr>
                                                            <th>ردیف</th>
                                                            <th>گروه فرعی </th>
                                                            <th>انتخاب</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="tableBody" id="subGroup1">
                                                    </tbody>
                                             </table>
                                        </div>
                                    </div>
                                </div>
                              </form> 
                            </div>
                        </div>
                    </div>
                    <div class="c-checkout tab-pane" id="orange" style="border-radius:10px 10px 2px 2px;">
                        <span class="row" style="padding: 1%">

                            <div class="col-sm-12">
                                <div class="well" style="margin-top:1%;">
                                    <h6 style="">گردش قیمت </h6>
                                </div>
                                <div class="alert">
                                    <input type="text" class="form-control" style="width:40%" name="search_mainPart" placeholder="جستجو">
                                </div>
                                <table class="table table-bordered table-hover  text-center">
                                    <thead class="tableHeader bg-success">
                                        <tr>
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
                                    <tbody class="tableBody" id="priceCycle">
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
                                            <form action="{{ url('/addpicture') }}" target="votar" id="kalaPicForm" enctype="multipart/form-data" method="POST">
                                                @csrf
                                                <input type="text" style="display: none;" name="kalaId" id="kalaIdChangePic" value="">
                                            <table class="table align-middle text-center">
                                                <thead class="tableHeader bg-success">
                                                    <tr>
                                                        <th>تصویر اصلی </th>
                                                        <th>تصویر اول</th>
                                                        <th>تصویر دوم</th>
                                                        <th> تصویر سوم</th>
                                                        <th>تصویر چهارم </th>
                                                    </tr>
                                                </thead>
                                                <tbody class="tableBody">
                                                <tr>
                                                        <td>
                                                            <div class='product-item swiper-slide' style='width:100%;'>
                                                                <img id="mainPicEdit" src="" />
                                                            </div>
                                                            <div>
                                                                <label for="mainPic" class="btn btn-success editButtonHover kalaEditbtn">ویرایش<i class="fa-light fa-image fa-lg"></i></label>
                                                                <input type="file"  onchange='document.getElementById("mainPicEdit").src = window.URL.createObjectURL(this.files[0]);' style="display:none" class="form-control" name="firstPic" id="mainPic">
                                                            </div>
                                                        </td>

                                                        <td>
                                                            <div class='product-item swiper-slide' style='width:100%;'>
                                                                 <img id="secondPic" src="{{url('/resources/assets/images/kala/'.$kala->GoodSn.'_2.jpg')}}" />
                                                            </div>
                                                            <div>
                                                                <label for="secondPic" class="btn btn-success editButtonHover kalaEditbtn">  ویرایش <i class="fa-light fa-image fa-lg"></i></label>
                                                                <input type="file" onchange='document.getElementById("secondPic").src = window.URL.createObjectURL(this.files[1]);' style="display:none" class="form-control" name="secondPic" id="secondPic">
                                                            </div>
                                                        </td>

                                                        <td>
                                                            <div class='product-item swiper-slide' style='width:100%;'>
                                                                <img id="2PicEdit" src="{{url('/resources/assets/images/kala/'.$kala->GoodSn.'_3.jpg')}}" />
                                                            </div>
                                                            <div>
                                                                <label for="2Pic" class="btn btn-success editButtonHover kalaEditbtn"> ویرایش <i class="fa-light fa-image fa-lg"></i></label>
                                                                <input type="file"    style="display: none" class="form-control" name="thirthPic" >
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class='product-item swiper-slide' style='width:100%;'>
                                                                <img id="3PicEdit" src="{{url('/resources/assets/images/kala/'.$kala->GoodSn.'_4.jpg')}}" />
                                                            </div>
                                                            <div>
                                                                <label for="3Pic" class="btn btn-success editButtonHover kalaEditbtn"> ویرایش <i class="fa-light fa-image fa-lg"></i></label>
                                                                <input type="file"   style="display: none" class="form-control" name="fourthPic" >
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class='product-item swiper-slide' style='width:100%;'>
                                                                <img id="4PicEdit" src="{{url('/resources/assets/images/kala/'.$kala->GoodSn.'_5.jpg')}}" />
                                                            </div>
                                                            <div>
                                                                <label for="4Pic" class="btn btn-success editButtonHover kalaEditbtn"> ویرایش <i class="fa-light fa-image fa-lg"></i></label>
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
                                    <div class="form-group mb-3">
                                        <span>
                                            <input class="form-check-input"  type="checkbox" value="" id="stockTakhsis">
                                            <label for="whereHouse">تخصیص انبار</label>
                                        </span>
                                    </div>
                                    <div class="form-group">
                                        <span>
                                            <button id="minimamSale" onclick="SetMinQty()" class="btn-add-to-cart">حد اقل فروش<i class="far fa-shopping-cart text-white ps-2"></i></button>
                                        </span>
                                        <span id="minSaleValue"> </span>
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <span>
                                            <button id="maximamSale" onclick="SetMaxQty()" class="btn-add-to-cart">حد اکثر فروش<i class="far fa-shopping-cart text-white ps-2"></i></button>
                                        </span>
                                        <span id="maxSaleValue"> </span>
                                    </div>
                                </div>
                                
                                <div class="col-sm-4">
                                    <form action="{{url('/restrictSale')}}" id="restrictFormStuff" method="get">
                                          @csrf
                                        <input type="text" style="display: none" name="kalaId" id="kalaIdSpecialRest" value="">
                                        <div class="form-group input-group-sm">
                                            <span>
                                                حد ایرور هزینه
                                            </span>
                                            <input type="number" onchange="activeSubmitButton(this)" value="" name="costLimit" class="form-control keyRestriction" id="costLimit">
                                        </div>
                                        <div class="form-group input-group-sm">
                                            <span>
                                                نوع هزینه
                                            </span>
                                            <select id="costTypeInfo"  onchange="activeSubmitButton(this)" class="form-select keyRestriction" name="infors">
                                            </select>
                                        </div>
                                        <div class="form-group input-group-sm">
                                            <span>
                                                متن ایرور هزینه
                                            </span>
                                            <textarea id="costContent"  class="form-control keyRestriction" onchange="activeSubmitButton(this)" name="costErrorContent" rows="2" cols="24"></textarea>
                                        </div>
                                        <div class="form-group input-group-sm">
                                            <span>
                                            مقدار هزینه
                                            </span>
                                            <input type="number" onchange="activeSubmitButton(this)" value="" name="costAmount" class="form-control keyRestriction" id="costAmount">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <span>
                                            <label >نقطه هشدار کالا</label>
                                            <input type="number" class="form-control keyRestriction" value="" required onclick="activeSubmitButton(this)" id="existanceAlarm" name='alarmAmount' style="width:50%">
                                        </span>
                                        <div class="form-group input-group-sm">
                                            <span>
                                                <input class="form-check-input restriction" type="checkbox" onchange="activeSubmitButton(this)" value="" id="callOnSale" name="callOnSale[]"/>
                                                <label class="form-check-label">
                                                    تماس جهت خرید کالا
                                                </label>
                                            </span>
                                        </div>

                                        <div class="form-group input-group-sm">
                                            <span>
                                                <input class="form-check-input restriction" type="checkbox"  onchange="activeSubmitButton(this)" value="" id="zeroExistance" name="zeroExistance[]" />
                                                <label class="form-check-label">
                                                    صفر کردن موجودی کالا
                                                </label>
                                            </span>
                                        </div>

                                        <div class="form-group input-group-sm">
                                            <span>
                                                <input class="form-check-input restriction" type="checkbox"  onchange="activeSubmitButton(this)" value="" id="showTakhfifPercent" name="activeTakhfifPercent[]" />
                                                <label class="form-check-label" for="showTakhfifPercent">
                                                    نمایش درصد تخفیف
                                                </label>
                                            </span>
                                        </div>

                                        <div class="form-group input-group-sm">
                                            <span>
                                                <input class="form-check-input restriction" type="checkbox"  onchange="activeSubmitButton(this)" value="" id="showFirstPrice" name="overLine[]" />
                                                <label class="form-check-label" for="showFirstPrice">
                                                    نمایش قیمت خط خورده
                                                </label>
                                            </span>
                                        </div>
                                    <div class="form-group input-group-sm">
                                        <span>
                                            <input class="form-check-input restiction"  onchange="activeSubmitButton(this)" type="checkbox" value="" id="inactiveAll" name="hideKala[]">
                                            <label class="form-check-label"> غیر فعال </label>
                                        </span>
                                    </div>
                                    <div class="form-group input-group-sm">
                                        <span>
                                            <input class="form-check-input restriction"  onchange="activeSubmitButton(this)" type="checkbox" value="" name="freeExistance[]" id="freeExistance"  >
                                            <label class="form-check-label"> آزادگذاری فروش </label>
                                        </span>
                                    </div>
                                    <div class="form-group input-group-sm">
                                        <span>
                                            <input class="form-check-input restriction"  onchange="activeSubmitButton(this)" type="checkbox" value="" name="activePishKharid[]" id="activePreBuy"  >
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
                                            <table class="table table-bordered table table-hover table-sm table-light">
                                                <thead class="tableHeader">
                                                    <tr>
                                                        <th>ردیف</th>
                                                        <th>اسم </th>
                                                        <th>انتخاب</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="tableBody" id="allStockForList">
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

                                <div class="col-sm-5" id="addedStock">
                                    <div class='modal-body'>
                                    <form action="{{url('/addStockToList')}}" method="POST" id="submitStockToList" style="display: inline" >
                                            <input type="text" name="kalaId" value="{{$kala->GoodSn}}" style="display: none" id="kalaIdForAddStock">
                                            @csrf
                                            <input type="text" class="form-control" style="margin-top:10px;" id="serachKalaOfSubGroup"  placeholder="جستجو"> <br>
                                            <div class='c-checkout' style='padding-right:0;'>
                                                <table class="table table-bordered table table-hover table-sm table-light">
                                                    <thead class="tableHeader">
                                                        <tr >
                                                            <th>ردیف</th>
                                                            <th>انبار</th>
                                                            <th>انتخاب</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="tableBody" id="allstockOfList">
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
                        <div class="row">
                             <div class=" col-sm-3"  style="margin-top: 1%">
                                  <iframe name="votar" style="display:none;"></iframe>
                                    <form action="{{url('/addDescKala')}}" target="votar" id="completDescription" method="post">
                                        @csrf
                                        <input type="text" style="display:none" name="kalaId" id="kalaIdDescription" value=""/>
                                        <label class="fs-6" for="description">توضیحات کامل کالا</label>
                                        <textarea class="form-control" name="discription" id="descriptionKala" rows="2"></textarea>
                                    </form>
                                </div>
                                <div class="col-sm-3 form-group" style="margin-top: 1%">
                                    <label  class="fs-6"  for="shortExpain">توضیحات مختصر کالا</label>
                                    <textarea disabled class="form-control" id="shortExpain" rows="2"></textarea>
                                </div>
                                <div class="col-sm-3 form-group" style="margin-top: 1%">
                                    <label  class="fs-6"  for="kalaTags"> تگ کردن کالای مترادف </label>
                                    <input type="email" disabled class="form-control" id="kalaTags" placeholder="">
                                </div>
                                <div class="col-sm-3 form-group" style="margin-top: 2%">
                                    <input type="checkbox" class="form-check-input p-2" id="sameKalaList" />
                                    <label  class="fs-6"  for="exampleFormControlTextarea1">لیست کالاهای مشابه</label>
                                </div>
                         </div>
                        <div class="row" >
                            <div class="col-sm-5" id="addKalaToList" style="display:none">
                                <div class='modal-body'>
                                  <input type="text" class="form-control" style="margin-top:10px;" id="serachKalaForAssameList" placeholder="جستجو">
                                    <div class='c-checkout' style='padding-right:0;'>
                                        <table class="table table-bordered table table-hover table-sm table-light">
                                            <thead class="tableHeader">
                                                <tr>
                                                    <th>ردیف</th>
                                                    <th>اسم </th>
                                                    <th><input type="checkbox" name=""  class="selectAllFromTop form-check-input"></th>
                                                </tr>
                                            </thead>
                                            <tbody class="tableBody" id="allKalaForList">
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

                            <div class="col-sm-5" id="addedList">
                                <div class='modal-body'>
                                    <iframe name="votar" style="display:none;"></iframe>
                                    <form action="{{url('/addKalaToList')}}" target="votar"  method="GET" style="display: inline" id="sameKalaForm">
                                        <input type="text" name="mainKalaId" value="" style="display: none" id="kalaIdSameKala">
                                         @csrf
                                        <input type="text" class="form-control" style="margin-top:10px;" id="serachKalaOfSubGroup"  placeholder="جستجو">
                                        <div class='c-checkout' style='padding-right:0;'>
                                        <table class="table table-bordered table table-hover table-sm table-light">
                                            <thead class="tableHeader">
                                                <tr>
                                                    <th>ردیف</th>
                                                    <th>کالای مشابه</th>
                                                    <th><input type="checkbox" name=""  class="selectAllFromTop form-check-input"></th>
                                                </tr>
                                            </thead>
                                            <tbody class="tableBody" id="allKalaOfList">
                                              
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
                  </div>
                <div class="modal-footer">
                    <button class="btn btn-success buttonHover" type="submit" id="submitSubGroup"           form="groupSubgoupCategory" disabled> ذخیره <i class="fa fa-save fa-lg"></i> </Button>
                    <button class="btn btn-success buttonHover" type="submit" id="stockSubmit"              form="submitStockToList" style="display:none"> ذخیره <i class="fa fa-save fa-lg"></i> </button>
                    <button class="btn btn-success buttonHover" type="submit" id="kalaRestictionbtn"        form="restrictFormStuff" style="display:none"> ذخیره <i class="fa fa-save fa-lg"></i></button>
                    <button class="btn btn-success buttonHover" type="submit" id="completDescriptionbtn"    form="completDescription" style="display:none">ذخیره <i class="fa fa-save fa-lg"></i></button>
                    <button class="btn btn-success buttonHover" type="submit" id="addToListSubmit"          form="sameKalaForm" style="display:none">ذخیره <i class="fa fa-save fa-lg"></i> </button>
                    <button class="btn btn-success buttonHover" type="submit" id="submitChangePic"          form="kalaPicForm" style="display:none"> ذخیره <i class="fa fa-save fa-lg"></i></button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal" id="cancelEditModal"> انصراف <i class="fa fa-xmark"></i></button>
              </div>
            </div>
          </div>
        </div>
        
        <script src="{{ url('/resources/assets/js/KalaScript.js')}}">

@endsection


@extends('admin.layout')
@section('content')
<div class="container">
<form action="{{url('/doUpdatewebSpecialSettings')}}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="o-page__content" style="margin-top: 65px;">
        <div class="o-headline" style="padding: 0.5%;">
                <span class="c-checkout__tab--active">تنظیمات اختصاصی وب</span>
        </div>
        <legend class="well col-sm-1" style="text-align:center; margin-top:0.6%; margin-left:1%; float:left;">
            <button class="btn btn-success text warning buttonHover" @if(hasPermission(Session::get( 'adminId'),'specialSetting' ) < 1) disabled @endif  >    ذخیره <i class="fa fa-save"> </i> </button>
        </legend>
        <div class='card mb-4' style="background-color:#c5c5c5; padding-top:1%; paddding:0;">
            <div class="container">
                    <ul class="header-list nav nav-tabs" data-tabs="tabs">
                        <li><a class="active" data-toggle="tab" style="color:black;" href="#webSettings"> تنظیمات نمایش</a></li>
                        <li><a data-toggle="tab" style="color:black;"  href="#cost">تنظیمات ارسال</a></li>
                        <li><a data-toggle="tab" style="color:black;"  href="#kala">تنظیمات کالا</a></li>
                        <li><a data-toggle="tab" style="color:black;"  href="#game">تنظیمات بازی</a></li>
                        <li><a data-toggle="tab" style="color:black;"  href="#social"> شبکه های اجتماعی</a></li>
                        <li><a data-toggle="tab" style="color:black;"  href="#customerAddress"> مسیر دهی مشتری</a></li>
                        <li><a data-toggle="tab" style="color:black;"  href="#LotterySetting">لاتری</a></li>
                    </ul>
                <div class="c-checkout tab-content" style="background-color:#f5f5f5; margin:0; margin-bottom:2%; padding:2%; border-radius:10px 10px 2px 2px; font-size:16px;">
                    <div class="tab-pane active"  id="webSettings">
                        <div class="row">
                                <div class="c-checkout" style="border-radius:10px 10px 2px 2px;">
                                    <div class="row" style="padding:1% 2% 0% 4%;">
                                        <div class="col-sm-3">
                                            <div class="form-check">
                                                <input type="checkbox" @if($settings->buyFromHome==1) checked @endif value="" name="buyFromHome[]" @if(hasPermission(Session::get("adminId"),"specialSetting") < 1) disabled @endif  class="form-check-input float-start">
                                                <label class="form-check-label ms-4" for="flexCheckDefault">
                                                    امکان خرید از صفحه اصلی 
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-check">
                                                  <input type="checkbox" @if($settings->enamad==1) checked @endif value="" name="enamad[]" @if(hasPermission(Session::get("adminId"),"specialSetting") < 1) disabled @endif  class="form-check-input float-start"> 
                                                    <label class="form-check-label ms-4" for="flexCheckDefault">
                                                        نمایش E-Namad
                                                    </label>
                                                    
                                            </div>
                                         </div>
                                        <div class="col-sm-3">
                                            <div class="form-check">
                                                <input type="checkbox" @if($settings->buyFromHome==1) checked @endif value="" name="buyFromHome[]" @if(hasPermission(Session::get("adminId"),"specialSetting") < 1) disabled @endif  class="form-check-input float-start">
                                                <label class="form-check-label ms-4" for="flexCheckDefault">
                                                     نمایش صفحه اصلی 
                                                </label>
                                                    <select name="selectHome" @if(hasPermission(Session::get( 'adminId'),'specialSetting' ) < 1) disabled @endif  type="text" class="form-select form-select-sm">
                                                        <option @if($settings->homePage==1) selected @endif value="1">با جزئیات</option>
                                                        <option @if($settings->homePage==2) selected @endif value="2">دسته بندی</option>
                                                    </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                           <label class="form-check-label ms-4" for="flexCheckDefault">تعیین سال مالی</label>
                                             <select name="fiscallYear" @if(hasPermission(Session::get( 'adminId'),'specialSetting' ) < 1) disabled @endif   type="text" class="form-select form-select-sm">
                                                @for ($i=1398; $i<=1440;$i++)
                                                <option @if( $settings -> FiscallYear==$i ) selected @endif value="{{$i}}">{{$i}}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-3 ms-4 pe-0 me-0"> محل نمایش لوگو </div>
                                        <div class="col-sm-2 float-start">
                                            <div class="form-check">
                                                <input type="radio" @if($settings->logoPosition==1) checked @endif value="1"  @if(hasPermission(Session::get("adminId"),"specialSetting") < 1) disabled @endif  class="form-check-input float-start" name="logoPosition">
                                                <label class="form-check-label ms-4" for="flexRadioDefault1">
                                                  قسمت راست
                                                </label>
                                             </div>
                                            <div class="form-check">
                                            <input type="radio" @if($settings->logoPosition==0) checked @endif value="0"  @if(hasPermission(Session::get("adminId"),"specialSetting") < 1) disabled @endif  class="form-check-input float-start" name="logoPosition">
                                                <label class="form-check-label ms-4" for="flexRadioDefault1">
                                                  قسمت چب
                                                </label>
                                             </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                <div class="tab-pane" id="kala">
                    <div class="c-checkout" style="border-radius:10px 10px 2px 2px;">
                        <div class="row"> <br>
							<div class="col-sm-3">
							 <div class="input-group input-group-sm mb-3">
							  <span class="input-group-text" id="inputGroup-sizing-sm"> تعداد انتخاب کالاها </span>
							    <input type="text" @if(hasPermission(Session::get( 'adminId'),'specialSetting' ) < 1) disabled @endif  class="form-control form-control-sm" name="maxSale" @if ($settings) value="{{$settings->maxSale}}" @endif>
							 </div>
						 </div>
							
						 <div class="col-sm-3">
							 <div class="input-group input-group-sm mb-3">
							  <span class="input-group-text" id="inputGroup-sizing-sm"> کف مبلغ ثبت فاکتور (تومان) </span>
							    <input type="text" @if(hasPermission(Session::get( 'adminId'),'specialSetting' ) < 1) disabled @endif  class="form-control form-control-sm" id="minSalePriceFactor" name="minSalePriceFactor" placeholder="تومان" value="{{ number_format($settings->minSalePriceFactor) }}" size="20" id="allKalaFirst">
							 </div>
						 </div>
							
						 <div class="col-sm-3">
							 <div class="input-group input-group-sm mb-3">
							  <span class="input-group-text" id="inputGroup-sizing-sm">  درصد تخفیف (%) </span>
							     <input type="text" required @if($settings->percentTakhfif) value="{{rtrim((string)number_format($settings->percentTakhfif,4, '/', ''),"0")}}" @endif name="percentTakhfif" class="form-control">
							 </div>
						 </div>
						<div class="col-sm-3">
							 <select type="text" @if(hasPermission(Session::get( 'adminId'),'specialSetting' ) < 1) disabled @endif  class="form-select form-select-sm" id="" name="currency">
							<option @if($settings->currency==1) selected @endif  value="1">ریال</option>
							<option @if($settings->currency==10) selected @endif value="10">تومان</option>
							</select>
						</div>
                    </div>
					
                        <div class="row"  style="">
                            <div class="col-sm-5">
                                <div class='modal-body'>
                                    <input type="text" class="form-control" @if(hasPermission(Session::get( 'adminId'),'specialSetting' ) < 1) disabled @endif style="margin-top:10px;" id="serachKalaForSubGroup"  placeholder="جستجو"> <br>
                                    <div class='c-checkout' style='padding-right:0;'>
                                        <table class="table table-bordered table table-hover">
                                            <thead class="tableHeader">
                                                <tr>
                                                    <th>ردیف</th>
                                                    <th>اسم </th>
                                                    <th>
                                                        <input type="checkbox" name="" @if(hasPermission(Session::get( 'adminId'),'specialSetting' ) < 1) disabled @endif   class="selectAllFromTop form-check-input"  >
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="tableBody"  id="allstocks">
                                                @foreach ($stocks as $stock)
                                                <tr onclick="checkCheckBox(this,event)">
                                                    <td>{{$loop->iteration}}</td>
                                                    <td>{{$stock->NameStock}} </td>
                                                    <td>
                                                        <input type="checkbox" @if(hasPermission(Session::get( 'adminId'),'specialSetting' ) < 1) disabled @endif  value="{{$stock->SnStock.'_'.$stock->NameStock}}" name="allStocks[]"  class="form-check-input"  >
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-2" style="">
                                <div class='modal-body' style="position:relative; right: 15%; top: 30%;">
                                    <div style="">
                                        <a id="addStockToWeb">
                                            <i class="fa-regular fa-circle-chevron-left fa-3x chevronHover"></i>
                                        </a>
                                        <br />
                                        <a id="removeStocksFromWeb">
                                            <i class="fa-regular fa-circle-chevron-right fa-3x chevronHover"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-5">
                                <div class='modal-body'>
                                    <input type="text" @if(hasPermission(Session::get( 'adminId'),'specialSetting' ) < 1)disabled @endif class="form-control" style="margin-top:10px;" id="serachKalaOfSubGroup"  placeholder="جستجو"> <br>
                                    <div class='c-checkout' style='padding-right:0;'>
                                        <table class="table table-bordered table table-hover" >
                                            <thead class="tableHeader">
                                                <tr>
                                                    <th>ردیف</th>
                                                    <th>اسم</th>
                                                    <th>
                                                        <input type="checkbox" @if(hasPermission(Session::get( 'adminId'),'specialSetting' ) < 1) disabled @endif   name="" class="selectAllFromTop form-check-input"/>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="tableBody" id="addedStocks">
                                                @foreach ($addedStocks as $stock)
                                                <tr onclick="checkCheckBox(this,event)">
                                                    <td>{{$loop->iteration}}</td>
                                                    <td>{{$stock->NameStock}} </td>
                                                    <td>
                                                        <input type="checkbox" @if(hasPermission(Session::get( 'adminId'),'specialSetting' ) < 1) disabled @endif  value="{{$stock->SnStock.'_'.$stock->NameStock}}" name="allStocks[]"  class="form-check-input"  >
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
                <div class="tab-pane" id="cost">
                        <div class="c-checkout" style="border-radius:10px 10px 2px 2px;">
                                <div class="row" style="padding:1% 2% 0% 1%;">
                                  <div class="col-sm-4"> 
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="inputGroup-sizing-default">متن قبل از ظهر </span>
                                            <input type="text" @if(hasPermission(Session::get( 'adminId'),'specialSetting' ) < 1) disabled @endif  class="form-control form-control-sm" @if ($settings) value="{{$settings->moorningTimeContent}}" @endif name="moorningTimeContent">
                                        </div>
                                  </div>
                                  <div class="col-sm-4"> 
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="inputGroup-sizing-default">متن بعد از ظهر </span>
                                            <input type="text" @if(hasPermission(Session::get( 'adminId'),'specialSetting' ) < 1) disabled @endif  class="form-control form-control-sm" @if ($settings) value="{{$settings->afternoonTimeContent}}" @endif name="afternoonTimeContent">
                                        </div>
                                  </div>
                                </div>
                                
                                <div class="row form-group col-sm-8" style="padding:1% 2% 0% 1%;">
                                    <div class="col-sm-6"  style="padding:0">
                                        <span style="display: inline">
											<input class="form-check-input" @if(hasPermission(Session::get( 'adminId'),'specialSetting' ) < 1) disabled @endif  type="checkbox"  name="firstDayMoorningActive" @if($settings) @if($settings->firstDayMoorningActive==1) checked @else  @endif @endif id="third-price"></span> &nbsp;
                                        <label for="userName">قبل از ظهر روز اول</label> &nbsp;
                                        <span style="display: inline; margin-right:18px;"> <input class="form-check-input" @if(hasPermission(Session::get( 'adminId'),'specialSetting' ) < 1) disabled @endif  type="checkbox"  name="firstDayAfternoonActive" @if($settings) @if($settings->firstDayAfternoonActive==1) checked @else  @endif @endif   id="third-price"></span> &nbsp;
                                        <label for="userName" >بعد از ظهر روز اول</label>
                                    </div>
                                </div>
                                <div class="row form-group col-sm-8" style="padding:1% 2% 0% 1%;">
                                    <div class="col-sm-6"  style="padding:0">
                                        <span style="display: inline"> <input class="form-check-input" type="checkbox" @if(hasPermission(Session::get( 'adminId'),'specialSetting' ) < 1) disabled @endif  name="secondDayMoorningActive" @if($settings) @if($settings->secondDayMoorningActive==1) checked @else @endif @endif id="third-price"></span> &nbsp;
                                        <label for="userName">قبل از ظهر روز دوم</label> &nbsp;
                                        <span style="display: inline;margin-right:18px;"> <input class="form-check-input" type="checkbox" @if(hasPermission(Session::get( 'adminId'),'specialSetting' ) < 1) disabled @endif  name="secondDayAfternoonActive" @if($settings) @if($settings->secondDayAfternoonActive==1) checked @else @endif @endif id="third-price"></span> &nbsp;
                                        <label for="userName">بعد از ظهر روز دوم</label>
                                    </div>
                                </div>
                                <div class="row form-group col-sm-8" style="padding:1% 2% 0% 1%;">
                                    <div class="col-sm-6"  style="padding:0">
                                        <span style="display: inline"> <input class="form-check-input" type="checkbox" @if(hasPermission(Session::get( 'adminId'),'specialSetting' ) < 1) disabled @endif   name="favoriteDateMoorningActive" @if($settings) @if($settings->FavoriteDateMoorningActive==1) checked @else @endif @endif id="third-price"></span> &nbsp;
                                        <label for="idealBeforNoon">قبل از ظهر روز دلخواه</label> &nbsp;
                                        <span style="display: inline"> <input class="form-check-input" type="checkbox" @if(hasPermission(Session::get( 'adminId'),'specialSetting' ) < 1) disabled @endif  name="favoriteDateAfternoonActive" @if($settings) @if($settings->FavoriteDateAfternoonActive==1) checked @else @endif @endif id="third-price"></span> &nbsp;
                                        <label for="idealAfterNoon">بعد از ظهر روز دلخواه</label>
                                    </div>
                                </div>
                            </div>
                    </div>

                    <div class="tab-pane" id="game">
                        <div class="c-checkout" style="border-radius:10px 10px 2px 2px;">
							<div class="row p-3">
                                        <div class="col-sm-4">
                                            <label class="form-label">جایزه مقام اول</label>
                                            <input class="form-control form-control-sm" id="firstPrize" value="{{number_format($settings->firstPrize)}}" name="firstPrize">
                                            <label class="form-label">جایزه مقام دوم</label>
                                            <input class="form-control form-control-sm" id="secondPrize" value="{{number_format($settings->secondPrize)}}" name="secondPrize">
                                            <label class="form-label">جایزه مقام سوم</label>
                                            <input class="form-control form-control-sm" id="thirdPrize" value="{{number_format($settings->thirdPrize)}}" name="thirdPrize">
                                            
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="form-label">جایزه مقام چهارم</label>
                                            <input class="form-control form-control-sm" id="fourthPrize" value="{{number_format($settings->fourthPrize)}}" name="fourthPrize">
                                            <label class="form-label">جایزه مقام پنجم</label>
                                            <input class="form-control form-control-sm" id="fifthPrize" value="{{number_format($settings->fifthPrize)}}" name="fifthPrize">
                                            <label class="form-label">جایزه مقام ششم</label>
                                            <input class="form-control form-control-sm" id="sixthPrize" value="{{number_format($settings->sixthPrize)}}" name="sixthPrize">
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="form-label">جایزه مقام هفتم</label>
                                            <input class="form-control form-control-sm" id="seventhPrize" value="{{number_format($settings->seventhPrize)}}" name="seventhPrize">
                                            <label class="form-label">جایزه مقام هشتم</label>
                                            <input class="form-control form-control-sm" id="eightthPrize" value="{{number_format($settings->eightPrize)}}" name="eightthPrize">
                                            <label class="form-label">جایزه مقام نهم</label>
                                            <input class="form-control form-control-sm" id="ninthPrize" value="{{number_format($settings->ninthPrize)}}" name="ninthPrize">
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="form-label">جایزه مقام دهم</label>
                                            <input class="form-control form-control-sm" id="teenthPrize" value="{{number_format($settings->teenthPrize)}}" name="teenthPrize">
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="form-label">جایزه مقام دهم</label>
                                            <input class="form-control form-control-sm" id="teenthPrize" value="{{number_format($settings->teenthPrize)}}" name="teenthPrize">
                                        </div>
                                        <div class="col-sm-3 mt-4 text-end">
                                            <a href="{{url('/emptyGame')}}" onclick="if (confirm('می خواهید نتایج بازی را تخلیه کنید؟?')){return true;}else{event.stopPropagation(); event.preventDefault();};">
	<button type="button" class="btn btn-success btn-sm btn-md btn-lg">تخلیه بازی  <i class="fa fa-icon-remove"></i> </button></a>
                                        </div>
                                    </div>
                           
                        </div>
                    </div>
                    <div class="tab-pane" id="social">
                        <div class="c-checkout" style="border-radius:10px 10px 2px 2px;">
                            <div class="row p-4">
                                <div class="col-sm-6">
                                    <div class="row form-group">
                                        <div class="col-sm-3"  style="padding:0"> &nbsp; <i class="fab fa-instagram 4x" style="color:#e94a66; font-size:22px;"></i>
                                            <label for="userName">آی دی انستاگرام</label>
                                        </div>
                                        <div class="col-sm-6" style="margin:0">
                                            <input type="text" class="form-control form-control-sm" @if(hasPermission(Session::get( 'adminId'),'specialSetting' ) < 1) disabled @endif  @if ($settings) value="{{$settings->telegramId}}" @endif name="telegramId">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-sm-3"  style="padding:0"> &nbsp; <i class="fab fa-telegram" style="color:#269cd9; font-size:22px;"></i>
                                            <label for="userName">آی دی تلگرام</label>
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control form-control-sm" @if(hasPermission(Session::get( 'adminId'),'specialSetting' ) < 1) disabled @endif  @if ($settings) value="{{$settings->instagramId}}" @endif name="instagramId">
                                        </div>
                                    </div>
                                    <div class="row form-group" >
                                        <div class="col-sm-3" style="padding:0"> &nbsp; <i class="fab fa-whatsapp-square" style="color:#57ed68; font-size:22px;"></i>
                                            <label for="userName"> شماره واتساپ </label>
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control form-control-sm" @if(hasPermission(Session::get( 'adminId'),'specialSetting' ) < 1) disabled @endif  @if ($settings) value="{{$settings->whatsappNumber}}" @endif  name="whatsappNumber">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                <div class="row form-group">
                                        <div class="col-sm-3" style="padding:0"> 
                                            <label for="userName">بارگزاری پوسته اندروید</label>
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="file" class="form-control" accept=".apk" name="uploadAPK">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="LotterySetting">
                        <div class="c-checkout" style="border-radius:10px 10px 2px 2px;">
                            <div class="row p-4">
                                <div class="col-sm-12">
                                    <div class="row form-group">
                                        <div class="col-sm-3"  style="padding:0"> &nbsp; <i class="fa fa-bullseye 4x" style="color:#e94a66; font-size:22px;"></i>
                                            <label for="userName">حد اقل امتیاز لاتری</label>
                                        </div>
                                        <div class="col-sm-6" style="margin:0">
                                            <input type="text" class="form-control form-control-sm" @if(hasPermission(Session::get( 'adminId'),'specialSetting' ) < 1) disabled @endif  @if ($settings) value="{{number_format($settings->lotteryMinBonus)}}" @endif name="lotteryMinBonus">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
						
                    <div class="tab-pane" id="customerAddress">
                        <div class="c-checkout" style="border-radius:10px 10px 2px 2px;">
                            <div class="row">
                        <div class="col-sm-6 mt-3">
							<div class="row"> 
								<div class="col-sm-4"> 
									<h5 style="font-style:bold; margin-right:10px;">شهر ها </h5>
								</div>
								<div class="col-sm-8 text-end"> 
									<button type="button" style="margint:0" class="btn btn-success btn-sm buttonHover" @if(hasPermission(Session::get( 'adminId'),'specialSetting' ) < 2) disabled @endif  id="addNewCity"> جدید <i class="fa fa-plus fa-lg" aria-hidden="true"></i></button>
                                    <button type="button" value="Reterive data" class="btn btn-info btn-sm text-white editButtonHover" data-toggle="modal" id="editCityButton" disabled>ویرایش <i class="fa fa-edit fa-lg" aria-hidden="true"></i></button>
                                    <button type="button" disabled id="deleteCityButton" class="btn btn-danger btn-sm buttonHoverDelete">حذف <i class="fa fa-trash fa-lg" aria-hidden="true"></i></button>
                                    <input type="text" style="display:none" value="" id="CityId" style=""/>
								</div>
							</div>
                            <div class="well" style="margin-top:2%;">
                                    <table class="table table-bordered table table-hover" id="tableGroupList">
                                        <thead class="tableHeader">
                                            <tr>
                                                <th>ردیف</th>
                                                <th>شهر</th>
                                                <th>فعال</th>
                                            </tr>
                                        </thead>
                                        <tbody class="c-checkout tableBody" id="cityList">
                                            @foreach ($cities as $city)
                                                <tr  @if(hasPermission(Session::get( 'adminId'),'specialSetting' ) > 1) onclick="changeCityStuff(this)" @endif  >
                                                    <td>{{ $loop->index+1 }}</td>
                                                    <td>{{ $city->NameRec }}</td>
                                                    <td>
                                                        <input class="mainGroupId" @if(hasPermission(Session::get( 'adminId'),'specialSetting' ) < 1) disabled @endif  type="radio" name="mainGroupId[]" value="{{ $city->SnMNM . '_' . $city->NameRec}}">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                        </div>
                        <div class="col-sm-6">
							<div class="row mt-3"> 
								<div class="col-sm-4"> 
									<h5 style="font-style:bold; margin-right:10px;"> منطقه ها  </h5>
								</div>
								<div class="col-sm-8 text-end"> 
									 <button class="btn btn-success btn-sm buttonHover" type="button" disabled  id="addNewMantiqah"> جدید <i class="fa fa-plus fa-lg" aria-hidden="true"></i></button>
                                    <button  class="btn btn-info btn-sm text-white editButtonHover" type="button" disabled id="editMantiqah"  > ویرایش <i class="fa fa-edit fa-lg" aria-hidden="true"></i></button>
                                    <button id="deleteMantagheh" type="button" disabled class="btn btn-danger btn-sm buttonHoverDelete"> حذف <i class="fa fa-trash fa-lg" aria-hidden="true"></i></button>
							    </div>
							</div>
							
                            <div class="well" style="margin-top:2%;">
                               
                                <div class=" c-checkout">
                                    <table id="subGroupTable" class="table table-bordered table table-hover" id="tableGroupList">
                                        <thead class="tableHeader">
                                            <tr>
                                                <th >ردیف </th>
                                                <th>منطقه </th>
                                                <th> فعال </th>
                                            </tr>
                                        </thead>
                                        <tbody class="tableBody" id="mantiqaBody">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br/>
                    <br/>
                    <div class="row" id="customersList" style="display: none">
                        <div class="col-sm-5">
                            <div class='modal-body'>
                            <input type="text" class="form-control" style="margin-top:10px;" id="searchNameMNM"  placeholder="نام">
                            <input type="text" class="form-control" style="margin-top:10px;" id="searchAddressMNM"  placeholder="آدرس">
                                <div class='c-checkout' style='padding-right:0;'>
                                    <table class="table table-bordered table-hover">
                                        <thead class="forMaser tableHeader">
                                            <tr>
                                                <th>ردیف</th>
                                                <th>کد</th>
                                                <th>نام</th>
                                                <th style="width:180px"> آدرس</th>
                                                <th>
                                                    <input type="checkbox" name=""  class="selectAllFromTop form-check-input">
                                                </th>
                                            </tr>
                                        </thead>
                                          <tbody class="forMaser tableBody" id="cutomerBody">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class='modal-body' style="position:relative; right: 15%; top: 30%;">
                                    <a id="addDataToMantiqah">
                                        <i class="fa-regular fa-circle-chevron-left fa-3x chevronHover"></i>
                                    </a>
                                    <br />
                                    <a id="removeDataFromMantiqah">
                                        <i class="fa-regular fa-circle-chevron-right fa-3x chevronHover"></i>
                                    </a>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <br/>
                            <div class='modal-body'>
                            <input type="text" class="form-control" style="margin-top:10px;" id="searchAddedNameMNM"  placeholder="نام">
                            <input type="text" class="form-control" style="margin-top:10px;" id="searchAddedAddressMNM"  placeholder="آدرس">
                            <input type="hidden" id="mantiqahIdForSearch"/>   
                                <div class='c-checkout'>
                                    <table class="table table-bordered">
                                        <thead class="tableHeader">
                                            <tr>
                                               <th>ردیف</th>
                                                <th>کد</th>
                                                <th>نام</th>
                                                <th style="width:180px">آدرس</th>
                                                <th>
                                                  <input type="checkbox" name="" class="selectAllFromTop form-check-input"/>
                                            </th>
                                            </tr>
                                        </thead>
										 <tbody class="tableBody" id="addedCutomerBody">
                                        </tbody>
                                      
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

        <div class="modal fade" id="editCity" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered dragableModal" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white" style="margin:0; border:none">
                        <h5 class="modal-title" id="exampleModalLongTitle">ویرایش شهر</h5>
                        <button type="button" class="close btn text-danger" data-dismiss="modal" aria-label="Close" style="background-color:rgb(255 255 255);"><i class="fa-solid fa-xmark fa-xl"></i>
                        </button>
                    </div>
                    <div class="modal-body" style="padding:5%; padding-right:0; padding-top:0; margin-right:10px">
                        <div class="c-checkout" style="padding:5%; padding-right:0; padding-top:0;">
                            <form action="{{ url('/editCity') }}" class="form" id="editCityForm" method="GET">
                                @csrf
                                <div class="form-group">
                                    <label class="form-label fs-6">شهر</label>
                                    <input type="text" size="10px" class="form-control" value="" name="cityName" id="cityNameEdit">
                                    <input type="hidden" size="10px" class="" name="cityIdEdit" id="cityIdEdit">
                                </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-danger buttonHover" data-dismiss="modal">انصراف <i class="fa-solid fa-xmark fa-lg"></i></button>
                        <button type="submit" id="submitNewGroup" class="btn btn-sm btn-success buttonHover">ذخیره <i class="fa fa-save fa-lg" aria-hidden="true"></i></button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade dragableModal" id="newCity" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="exampleModalLongTitle"> شهر جدید</h5>
                        <button type="button" class="close btn text-danger" data-dismiss="modal" aria-label="Close" style="background-color:#fff;">
                            <i class="fa-solid fa-xmark fa-xl"></i>
                        </button>
                    </div>
                     <div class="modal-body" style="padding:5%; padding-right:0; padding-top:0; margin-right:10px">
                        <div class="c-checkout" style="padding:5%; padding-right:0; padding-top:0;">
                            <form action="{{url('/addNewCity')}}" method="GET" id="addCityForm" class="form">
                                @csrf
                                <div class="form-group">
                                    <label class="form-label"> شهر</label>
                                    <input type="text" required class="form-control" autocomplete="off" name="cityName" id="cityName" placeholder="شهر">
                                </div>
                        </div>
                     </div>
                     <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-danger buttonHover" data-dismiss="modal">انصراف <i class="fa-solid fa-xmark fa-lg"></i></button>
                        <button type="submit" id="submitNewGroup" class="btn btn-sm btn-success buttonHover">ذخیره <i class="fa fa-save fa-lg" aria-hidden="true"></i></button>
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
    </form>
    <div class="modal fade dragableModal" id="editMantagheh" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white" style="margin:0; border:none">
                        <h5 class="modal-title" id="exampleModalLongTitle">ویرایش منطقه</h5>
                        <button type="button" class="close btn text-danger" data-dismiss="modal" aria-label="Close" style="background-color:rgb(255 255 255);"><i class="fa-solid fa-xmark fa-xl"></i>
                        </button>
                    </div>
                    <div class="modal-body" style="padding:5%; padding-right:0; padding-top:0; margin-right:10px">
                        <div class="c-checkout" style="padding:5%; padding-right:0; padding-top:0;">
                            <form action="{{ url('/editMantagheh') }}" class="form" id="editMantaghehForm" method="GET">
                                @csrf
                                <div class="form-group">
                                    <label class="form-label fs-6">منطقه</label>
                                    <input type="text" size="10px" class="form-control" value="" name="Name" id="MantaghehNameEdit">
                                    <input type="hidden" size="10px" class="" name="mantaghehIdEdit" id="mantaghehIdEdit">
                                    <input type="hidden" size="10px" class="" name="cityId" id="mantiqahCity">
                                </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-danger buttonHover" data-dismiss="modal">انصراف <i class="fa-solid fa-xmark fa-lg"></i></button>
                        <button type="submit" id="submitNewGroup" class="btn btn-sm btn-success buttonHover">ذخیره <i class="fa fa-save fa-lg" aria-hidden="true"></i></button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade dragableModal" id="addMontiqah" data-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close" style="background-color:red;"></button>
                  <h5 class="modal-title" id="staticBackdropLabel">افزودن منطقه</h5>
                </div>
                <div class="modal-body">
                    <label for="staticEmail" class="col-sm-2 col-form-label fs-6">شهر  </label>
                    <select class="form-select" aria-label="Default select example" id="city">
                        @foreach($cities as $city)
                        <option value="{{$city->SnMNM}}">{{$city->NameRec}}</option>
                        @endforeach
                    </select>
                    <input type="text" class="form-control" placeholder="شهر" id="city" style="display: none;">
                    <label for="staticEmail" class="col-sm-2 col-form-label fs-6">منطقه   <i class="fa fa-plus-circle"  onclick="showInputMantiqah()" style="color:green; font-size:24px;"></i> </label>
                    <select class="form-select" aria-label="Default select example" id="mantiqahForAdd">
                        
                    </select>
                    <div  id="mantiqah" style="display: none;">
                       <input type="text" class="form-control" id='inputMantiqah'  placeholder="منطقه"  >
                    </div>
                </div>
                 <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-danger buttonHover" data-dismiss="modal">انصراف <i class="fa-solid fa-xmark fa-lg"></i></button>
                        <button type="submit" id="submitNewGroup" onclick="addMantiqah()"  class="btn btn-sm btn-success buttonHover">ذخیره <i class="fa fa-save fa-lg" aria-hidden="true"></i></button>
                </div>
                </form>
              </div>
            </div>
        </div>
    </div>
</div>
@endsection

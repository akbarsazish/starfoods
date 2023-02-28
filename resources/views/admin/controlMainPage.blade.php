@extends('admin.layout')
@section('content')

<style>
.specialSettings, .specialSettingsBtn, .emteyazSettingsPart{
    display:none;
}
.targetCheck{
    width:22px;
    height:22px;
    border-radius:50%;
}
.targetLabel {
    margin-top:5px;
}
.selectBg {
	background-color:red;
	}

#nazaranjicontainer {
      height:377px !important; 
      overflow-y:scroll !important;
      display:block !important;
  }

</style>

<div class="container-fluid containerDiv">
    <div class="row">
            <div class="col-lg-2 col-md-2 col-sm-3 sideBar">
                <fieldset class="border rounded sidefieldSet">
                    <legend  class="float-none w-auto legendLabel mb-0"> تنظیمات </legend>
                    <div class="form-check">
                        <input class="form-check-input p-2 float-start" type="radio" name="settings" id="mainPageSettings" checked>
                        <label class="form-check-label me-4" for="assesPast"> تنظیمات صفحه اصلی  </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input p-2 float-start" type="radio" name="settings" id="specialSettings">
                        <label class="form-check-label me-4" for="assesPast"> تنظیمات اختصاصی </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input p-2 float-start" type="radio" name="settings" id="emteyazSettings">
                        <label class="form-check-label me-4" for="assesPast"> تنظیمات امتیاز </label>
                    </div>
                </fieldset>
            </div>
            <div class="col-sm-10 col-md-10 col-sm-12 contentDiv">
                <div class="row contentHeader"> 
                    <div class='col-lg-12 text-end mt-1'>
                        <button class="btn btn-sm btn-success specialSettingsBtn float-end"  id="webSpecialSettingBtn" @if(hasPermission(Session::get( 'adminId'),'specialSetting' ) < 1) disabled @endif  > ذخیره <i class="fa fa-save"> </i> </button>
                         <form action="{{ url('/editParts') }}" style="display: inline;" method="POST" class="mainPageStuff">
                            @if(hasPermission(Session::get("adminId"),"homePage") >1)
                                <a href="{{ url('/addNewGroup') }}" class='btn btn-sm btn-success'> بخش جدید <i class="fa fa-plus" aria-hidden="true"></i></a>
                            @endif
                                @csrf
                                <input type="text" id="partType" style="display: none" name="partType" value="" />
                                <input type="text" id="partId" style="display: none" name="partId" value="" />
                                <input type="text" id="partTitle" style="display: none" name="title" value="" />
                            @if(hasPermission(Session::get("adminId"),"homePage") > -1)
                                <button class='btn btn-success btn-sm text-white' disabled  type="submit" id='editPart'> ویرایش  <i class="fa fa-edit" aria-hidden="true"></i></button>
                            @endif
                        </form>
                        @if(hasPermission(Session::get("adminId"),"homePage") > 1)
                              <button class='btn btn-danger btn-sm disabled mainPageStuff' id='deletePart'>   حذف  <i class="fa fa-trash" aria-hidden="true"></i></button>
                        @endif
                        @if(hasPermission(Session::get("adminId"),"homePage") > 0)
                        <form action="{{ url('/changePriority') }}" style="display:inline;" method="POST" >
                                @csrf
                                <button class="mainPageStuff" name='changePriority' id="downArrow" disabled type="submit" value="down" style="background-color:#43bfa3; padding:0;"> <i class="fa-solid fa-circle-chevron-down fa-2x chevronHover"></i></button>
                                <button class="mainPageStuff" name='changePriority' id="upArrow" disabled type="submit" value="up" style="background-color:#43bfa3; padding:0;"> <i class="fa-solid fa-circle-chevron-up fa-2x chevronHover"></i></button>
                        @endif
                    </div>
                </div>

                <div class="row mainContent">
                        <table class='table table-hover table-bordered table-sm table-light' id='myTable'>
                        <thead class="table bg-success text-white tableHeader">
                            <tr >
                                <th>ردیف</th>
                                <th>سطر</th>
                                <th>اولویت</th>
                                <th>فعال</th>
                                <th>انتخاب</th>
                            </tr>
                        </thead>
                        <tbody class="tableBody">
                            @foreach ($parts as $part)
                                <tr  id='1' >
                                    <td  style="">{{ $loop->index+1 }}</td>
                                    <td >{{ $part->title }}</td>
                                    <td>@if($part->partType==3 or $part->partType==4)@else {{ $part->priority-2 }} @endif</td>
                                    <td>@if($part->partType==3)@else <input class='form-check-input' type='checkbox' disabled value='' id='flexCheck' @if($part->activeOrNot == 1 ) checked @endif /> @endif</td>
                                    <td><input type="radio" value="{{ $part->id . '_' . $part->priority . '_' . $part->partType. '_' . $part->title }}" class="mainGroups form-check-input" name="partId"></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
               </form>
               

                <!-- تنظیمات اختصاصی  -->
            <div class=' mb-2 specialSettings' style="background: linear-gradient(#3ccc7a, #034620);">
               <div class="container-fluid">
                    <ul class="header-list nav nav-tabs" data-tabs="tabs">
                        <li><a class="active" data-toggle="tab" style="color:black;" href="#webSettings"> تنظیمات نمایش</a></li>
                        <li><a data-toggle="tab" style="color:black;"  href="#cost">تنظیمات ارسال</a></li>
                        <li><a data-toggle="tab" style="color:black;"  href="#kala">تنظیمات کالا</a></li>
                        <li><a data-toggle="tab" style="color:black;"  href="#game">تنظیمات بازی</a></li>
                        <li><a data-toggle="tab" style="color:black;"  href="#social"> شبکه های اجتماعی</a></li>
                        <li><a data-toggle="tab" style="color:black;"  href="#customerAddress"> مسیر دهی مشتری</a></li>
                    </ul>
                   <form action="{{url('/doUpdatewebSpecialSettings')}}" method="post" enctype="multipart/form-data" id="webSpecialSettingForm">
                     @csrf
                  <div class="c-checkout tab-content" style="background-color:#f5f5f5; margin:0; margin-bottom:1%; padding:1%; border-radius:5px 5px 2px 2px; font-size:16px; disply:block;height:400px; overflow-y:scroll; overflow-x: hidden;">
                     <div class="tab-pane active" id="webSettings">
                         <div class="row bg-white">
                            <div class="col-sm-3">
                                <input type="checkbox" @if($settings->buyFromHome==1) checked @endif value="" name="buyFromHome[]" @if(hasPermission(Session::get("adminId"),"specialSetting") < 1) disabled @endif  class="form-check-input float-start">
                                <label class="form-check-label ms-2" for="flexCheckDefault">
                                    امکان خرید از صفحه اصلی 
                                </label>
                            </div>
                            <div class="col-sm-2">
                                    <input type="checkbox" @if($settings->enamad==1) checked @endif value="" name="enamad[]" @if(hasPermission(Session::get("adminId"),"specialSetting") < 1) disabled @endif  class="form-check-input float-start"> 
                                    <label class="form-check-label ms-2" for="flexCheckDefault">
                                    نمایش E-Namad
                                    </label>
                            </div>
                            <div class="col-sm-3">
                                <input type="checkbox" @if($settings->buyFromHome==1) checked @endif value="" name="buyFromHome[]" @if(hasPermission(Session::get("adminId"),"specialSetting") < 1) disabled @endif  class="form-check-input float-start">
                                <label class="form-check-label ms-2" for="flexCheckDefault">
                                        نمایش صفحه اصلی 
                                </label>
                                <select name="selectHome" @if(hasPermission(Session::get( 'adminId'),'specialSetting' ) < 1) disabled @endif  type="text" class="form-select form-select-sm">
                                    <option @if($settings->homePage==1) selected @endif value="1">با جزئیات</option>
                                    <option @if($settings->homePage==2) selected @endif value="2">دسته بندی</option>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <label class="form-check-label ms-2" for="flexCheckDefault">تعیین سال مالی</label>
                                    <select name="fiscallYear" @if(hasPermission(Session::get( 'adminId'),'specialSetting' ) < 1) disabled @endif   type="text" class="form-select form-select-sm">
                                    @for ($i=1398; $i<=1440;$i++)
                                    <option @if( $settings -> FiscallYear==$i ) selected @endif value="{{$i}}">{{$i}}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-sm-2 float-start">نمایش لوگو 
                                <div class="form-check">
                                    <input type="radio" @if($settings->logoPosition==0) checked @endif value="0"  @if(hasPermission(Session::get("adminId"),"specialSetting") < 1) disabled @endif  class="form-check-input float-start" name="logoPosition">
                                    <label class="form-check-label ms-4" for="flexRadioDefault1">
                                            چب
                                    </label>  
                                </div>
                                <div class="form-check">
                                    <input type="radio" @if($settings->logoPosition==1) checked @endif value="1"  @if(hasPermission(Session::get("adminId"),"specialSetting") < 1) disabled @endif  class="form-check-input float-start" name="logoPosition">
                                    <label class="form-check-label ms-4" for="flexRadioDefault1">
                                        راست
                                    </label>
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
					
                    <div class="row">
                        <div class="col-sm-5">
                            <input type="text" class="form-control form-control-sm" @if(hasPermission(Session::get( 'adminId'),'specialSetting' ) < 1) disabled @endif  id="serachKalaForSubGroup"  placeholder="جستجو">
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
                                             <input type="checkbox" @if(hasPermission(Session::get( 'adminId'),'specialSetting' ) < 1) disabled @endif  value="{{$stock->SnStock.'_'.$stock->NameStock}}" name="allStocks[]" class="form-check-input" >
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                        </div>

                        <div class="col-sm-2">
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
                            <input type="text" @if(hasPermission(Session::get( 'adminId'),'specialSetting' ) < 1)disabled @endif class="form-control form-control-sm" id="serachKalaOfSubGroup"  placeholder="جستجو">
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
                <div class="tab-pane" id="cost">
                        <div class="c-checkout" style="border-radius:10px 10px 2px 2px;">
                                <div class="row" style="padding:1% 2% 0% 1%;">
                                  <div class="col-sm-4"> 
                                    <div class="input-group input-group-sm mb-3">
                                        <span class="input-group-text" id="inputGroup-sizing-default">متن قبل از ظهر </span>
                                        <input type="text" @if(hasPermission(Session::get( 'adminId'),'specialSetting' ) < 1) disabled @endif  class="form-control form-control-sm" @if ($settings) value="{{$settings->moorningTimeContent}}" @endif name="moorningTimeContent">
                                    </div>
                                  </div>
                                  <div class="col-sm-4"> 
                                    <div class="input-group input-group-sm mb-3">
                                        <span class="input-group-text" id="inputGroup-sizing-default">متن بعد از ظهر </span>
                                        <input type="text" @if(hasPermission(Session::get( 'adminId'),'specialSetting' ) < 1) disabled @endif  class="form-control form-control-sm" @if ($settings) value="{{$settings->afternoonTimeContent}}" @endif name="afternoonTimeContent">
                                    </div>
                                  </div>
                                </div>
                                
                                <div class="row py-3">
                                    <div class="col-md-4 ">
										&nbsp; <input class="form-check-input" @if(hasPermission(Session::get( 'adminId'),'specialSetting' ) < 1) disabled @endif  type="checkbox"  name="firstDayMoorningActive" @if($settings) @if($settings->firstDayMoorningActive==1) checked @else  @endif @endif id="third-price">
                                        <label for="userName">قبل از ظهر روز اول</label> &nbsp;
                                        <input class="form-check-input" @if(hasPermission(Session::get( 'adminId'),'specialSetting' ) < 1) disabled @endif  type="checkbox"  name="firstDayAfternoonActive" @if($settings) @if($settings->firstDayAfternoonActive==1) checked @else  @endif @endif   id="third-price">
                                        <label for="userName" >بعد از ظهر روز اول</label>
                                    </div>
                               
                                    <div class="col-md-4 px-0">
                                         <input class="form-check-input" type="checkbox" @if(hasPermission(Session::get( 'adminId'),'specialSetting' ) < 1) disabled @endif  name="secondDayMoorningActive" @if($settings) @if($settings->secondDayMoorningActive==1) checked @else @endif @endif id="third-price">
                                        <label for="userName">قبل از ظهر روز دوم</label> &nbsp;
                                         <input class="form-check-input" type="checkbox" @if(hasPermission(Session::get( 'adminId'),'specialSetting' ) < 1) disabled @endif  name="secondDayAfternoonActive" @if($settings) @if($settings->secondDayAfternoonActive==1) checked @else @endif @endif id="third-price">
                                        <label for="userName">بعد از ظهر روز دوم</label>
                                    </div>
                               
                                    <div class="col-md-4 px-0">
                                         <input class="form-check-input" type="checkbox" @if(hasPermission(Session::get( 'adminId'),'specialSetting' ) < 1) disabled @endif   name="favoriteDateMoorningActive" @if($settings) @if($settings->FavoriteDateMoorningActive==1) checked @else @endif @endif id="third-price">
                                        <label for="idealBeforNoon">قبل از ظهر روز دلخواه</label> &nbsp;
                                         <input class="form-check-input" type="checkbox" @if(hasPermission(Session::get( 'adminId'),'specialSetting' ) < 1) disabled @endif  name="favoriteDateAfternoonActive" @if($settings) @if($settings->FavoriteDateAfternoonActive==1) checked @else @endif @endif id="third-price">
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
                            <div class="row">
                                <div class="col-sm-3">
                                    <i class="fab fa-instagram 4x" style="color:#e94a66; font-size:22px;"></i>
                                    <label for="userName"> انستاگرام</label>
                                    <input type="text" class="form-control form-control-sm" @if(hasPermission(Session::get( 'adminId'),'specialSetting' ) < 1) disabled @endif  @if ($settings) value="{{$settings->telegramId}}" @endif name="telegramId">
                                </div>
                                <div class="col-sm-3">
                                    <i class="fab fa-telegram" style="color:#269cd9; font-size:22px;"></i>
                                    <label for="userName">  تلگرام</label>
                                    <input type="text" class="form-control form-control-sm" @if(hasPermission(Session::get( 'adminId'),'specialSetting' ) < 1) disabled @endif  @if ($settings) value="{{$settings->instagramId}}" @endif name="instagramId">
                                </div>
                                <div class="col-sm-3">
                                    <i class="fab fa-whatsapp-square" style="color:#57ed68; font-size:22px;"></i>
                                    <label for="userName">  واتساپ </label>
                                    <input type="text" class="form-control form-control-sm" @if(hasPermission(Session::get( 'adminId'),'specialSetting' ) < 1) disabled @endif  @if ($settings) value="{{$settings->whatsappNumber}}" @endif  name="whatsappNumber">
                                </div>
                                    <div class="col-sm-3">
                                    <label for="userName"> پوسته اندروید</label>
                                    <input type="file" class="form-control" accept=".apk" name="uploadAPK">
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
									<button type="button" style="margint:0" class="btn btn-success btn-sm" @if(hasPermission(Session::get( 'adminId'),'specialSetting' ) < 2) disabled @endif  id="addNewCity"> جدید <i class="fa fa-plus" aria-hidden="true"></i></button>
                                    <button type="button" value="Reterive data" class="btn btn-info btn-sm text-white" data-toggle="modal" id="editCityButton" disabled>ویرایش <i class="fa fa-edit" aria-hidden="true"></i></button>
                                    <button type="button" disabled id="deleteCityButton" class="btn btn-danger btn-sm">حذف <i class="fa fa-trash" aria-hidden="true"></i></button>
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
                                        <tbody class="c-checkout tableBody" id="cityList" style="height:200px !important;">
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
									 <button class="btn btn-success btn-sm buttonHover" type="button" disabled  id="addNewMantiqah"> جدید <i class="fa fa-plus" aria-hidden="true"></i></button>
                                    <button  class="btn btn-info btn-sm text-white editButtonHover" type="button" disabled id="editMantiqah"  > ویرایش <i class="fa fa-edit" aria-hidden="true"></i></button>
                                    <button id="deleteMantagheh" type="button" disabled class="btn btn-danger btn-sm buttonHoverDelete"> حذف <i class="fa fa-trash" aria-hidden="true"></i></button>
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
                                        <tbody class="tableBody" id="mantiqaBody" style="height:200px !important;">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="customersList" style="display: none">
                        <div class="col-sm-5">
                              <input type="text" class="form-control form-control-sm" style="margin-top:10px;" id="searchNameMNM"  placeholder="نام">
                              <input type="text" class="form-control form-control-sm" style="margin-top:10px;" id="searchAddressMNM"  placeholder="آدرس">
                                    <table class="table table-bordered table-hover">
                                        <thead class="forMaser tableHeader">
                                            <tr>
                                                <th>ردیف</th>
                                                <th>نام</th>
                                                <th> آدرس</th>
                                                <th>
                                                    <input type="checkbox" name=""  class="selectAllFromTop form-check-input">
                                                </th>
                                            </tr>
                                        </thead>
                                          <tbody class="forMaser tableBody" id="cutomerBody">
                                        </tbody>
                                    </table>
                            </div>
                           <div class="col-sm-2">
                               <div class='modal-body' style="position:relative; right: 15%; top: 30%;">
                                    <a id="addDataToMantiqah">
                                        <i class="fa-regular fa-circle-chevron-left fa-3x chevronHover"></i>
                                    </a>
                                    <br/>
                                    <a id="removeDataFromMantiqah">
                                        <i class="fa-regular fa-circle-chevron-right fa-3x chevronHover"></i>
                                    </a>
                              </div>
                         </div>
                        <div class="col-sm-5">
                              <input type="text" class="form-control form-control-sm" style="margin-top:10px;" id="searchAddedNameMNM"  placeholder="نام">
                                 <input type="text" class="form-control form-control-sm" style="margin-top:10px;" id="searchAddedAddressMNM"  placeholder="آدرس">
                                 <input type="hidden" id="mantiqahIdForSearch"/>   
                                <table class="table table-bordered">
                                    <thead class="tableHeader">
                                        <tr>
                                            <th>ردیف</th>
                                            <th>نام</th>
                                            <th >آدرس</th>
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
         </div>
     </div>
 </div>
<!-- ختم تنظیمات اختصاصی  -->

<!-- شروع تنظیمات امتیاز ها  -->
<div class="c-checkout container-fluid emteyazSettingsPart" style="background: linear-gradient(#3ccc7a, #034620); border-radius:5px 5px 2px 2px;">
      <div class="col-sm-4" style="margin: 0; padding:0;">
        <ul class="header-list nav nav-tabs" data-tabs="tabs" style="margin: 0; padding:0;">
          <li><a class="active"  data-toggle="tab" style="color:black;"  href="#prizeSettings">تنظیمات جوایز لاتری</a></li>
          <li><a  data-toggle="tab" style="color:black;"  href="#askIdea">  نظر خواهی </a></li>
        </ul>
      </div>

      <div class="c-checkout tab-content" style="background-color:#f5f5f5; margin:0;  padding:0.2%; border-radius:10px 10px 2px 2px;">
        <!-- کالاهای لاتری -->
        <div class="row c-checkout rounded-2 tab-pane active" id="prizeSettings" style="width:100%; margin:0 auto; padding:1% 0% 0% 0%">
            <div class="row">
                <div class="col-lg-12 text-end">
                     <span class="prizeName float-start"> <i class="fa fa-bullseye 4x" style="color:green; font-size:22px;"></i>  حد اقل امتیاز لاتری : {{number_format($lotteryMinBonus)}} </span> 
                     <button  data-toggle="modal" type="button"  class="btn btn-sm btn-success text-warning" id="editLotteryPrizeBtn" > ویرایش لاتاری  <i class="fa fa-edit"> </i> </button>
                </div>
            </div>
            <div class="row p-3">
              <div class="prizeSettingTab">
                <div> <span class="prizeName"> جایزه اول :</span>   {{$prizes[0]->firstPrize}} </div>
                <div> <span class="prizeName"> جایزه دوم:  </span> {{$prizes[0]->secondPrize}}</div>
                <div> <span class="prizeName"> جایزه سوم: </span> {{$prizes[0]->thirdPrize}} </div>  
                <div> <span class="prizeName"> جایزه چهارم : </span> {{$prizes[0]->fourthPrize}} </div>
                <div> <span class="prizeName"> جایزه پنجم : </span> {{$prizes[0]->fifthPrize}} </div>
                <div> <span class="prizeName"> جایزه ششم : </span> {{$prizes[0]->sixthPrize}} </div>
                <div> <span class="prizeName"> جایزه هفتم : </span> {{$prizes[0]->seventhPrize}} </div>
                <div> <span class="prizeName"> جایزه هشتم : </span>  {{$prizes[0]->eightthPrize}} </div>
                <div> <span class="prizeName"> جایزه نهم :</span> {{$prizes[0]->ninethPrize}}  </div>
                <div> <span class="prizeName"> جایزه دهم: </span>   {{$prizes[0]->teenthPrize}} </div>
                <div> <span class="prizeName"> جایزه یازدهم :</span>  {{$prizes[0]->eleventhPrize}}  </div>
                <div> <span class="prizeName"> جایزه دوازدهم :</span> {{$prizes[0]->twelvthPrize}}  </div>
                <div> <span class="prizeName"> جایزه سیزدهم :</span> {{$prizes[0]->therteenthPrize}}  </div>
                <div> <span class="prizeName"> جایزه چهاردهم: </span> {{$prizes[0]->fourteenthPrize}}  </div>
                <div> <span class="prizeName"> جایزه پانزدهم: </span> {{$prizes[0]->fifteenthPrize}}  </div>
                <div> <span class="prizeName"> جایزه شانزدهم: </span> {{$prizes[0]->sixteenthPrize}}  </div>
              </div>
            </div> <hr>
         
             <div class="row text-center me-2">
                <input type="hidden" name="" id="selectTargetId">
                <div class="row">
                  <div class="col-lg-3 col-md-3 col-sm-3 mb-1">
                      <select class="form-select  form-select-sm" aria-label="Default select example" id="selectTarget">
                        @foreach($targets as $target)
                          <option value="{{$target->id}}">{{$target->baseName}}</option>
                        @endforeach
                      </select>
                  </div>
                    <div class="col-lg-1 col-md-1 col-sm-1 mt-3">
                      <!-- <span data-toggle="modal" data-target="#addingTargetModal"><i class="fa fa-plus-circle fa-lg" style="color:#1684db; font-size:33px"></i></span> -->
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-8 text-end">
                      <button class='btn btn-sm btn-success text-warning' id="targetEditBtn" type="button" disabled  data-toggle="modal" style="margin-top:-3px;">ویرایش تارگت<i class="fa fa-edit fa-lg"></i></button> 
                      <!-- <button class='btn btn-danger text-warning' disabled style="margin-top:-3px;" id="deleteTargetBtn"> حذف <i class="fa fa-trash fa-lg"></i></button>  -->
                    </div>
                </div>

                <div class="row">
                  <table class="table table-bordered border-secondary table-sm">
                    <thead>
                      <tr class="targetTableTr">
                      <th> ردیف </th>
                        <th> اسم تارگت </th>
                        <th>تارگیت 1</th>
                        <th> امتیاز 1</th>
                        <th>تارگیت 2</th>
                        <th> امتیاز 2</th>
                        <th>تارگیت 3</th>
                        <th> امتیاز 3</th>
                        <th> انتخاب  </th>
                      </tr>
                    </thead>
                    <tbody id="targetList">
                      @foreach($targets as $target)
                      <tr class="targetTableTr" onclick="setTargetStuff(this)">
                      <td>{{$loop->iteration}}</td>
                          <td>{{$target->baseName}}</td>
                          <td> {{number_format($target->firstTarget)}}</td>
                          <td> {{$target->firstTargetBonus}} </td>
                          <td> {{number_format($target->secondTarget)}}</td>
                          <td> {{$target->secondTargetBonus}} </td>
                          <td> {{number_format($target->thirdTarget)}}</td>
                          <td> {{$target->thirdTargetBonus}} </td>
                          <td> <input class="form-check-input" name="targetId" type="radio" value="{{$target->id}}"></td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
            </div>
          </div>
		  
        
            <div class="row rounded-2 tab-pane" id="askIdea">
                <div class="col-lg-12 text-end">
                        <button type="button" class="btn btn-sm btn-success" id="insetQuestionBtn"> افزودن  <i class="fa fa-plus"></i> </button>
                        <button type="button" class="btn btn-sm btn-success" id="editQuestionBtn" disabled> ویرایش  <i class="fa fa-edit" style="color:yellow"></i> </button>
                        <button type="button" class="btn btn-sm btn-success" onclick="startAgainNazar()" id="startAgainNazarBtn" disabled> از سرگیری نظر خواهی <i class="fa fa-history" style="color:white"></i> </button>
                </div>

              <div class="col-lg-12" id="nazaranjicontainer">
                @foreach($nazars as $nazar)
                  <fieldset class="fieldsetBorder rounded mb-2">
                    <legend  class="float-none w-auto forLegend" style="font-size:14px; margin-bottom:2px;"> {{$nazar->Name}} </legend>	
                        <div class="idea-container">
                                <button class="idea-item listQuestionBtn" onclick="showAnswers({{$nazar->nazarId}},1)"> 1- {{trim($nazar->question1)}} </button>
                                <button class="idea-item listQuestionBtn" onclick="showAnswers({{$nazar->nazarId}},2)"> 2- {{trim($nazar->question2)}} </button>
                                <button class="idea-item listQuestionBtn" onclick="showAnswers({{$nazar->nazarId}},3)"> 3- {{trim($nazar->question3)}} </button>
                                <div class="form-check mt-1">
                                  <input class="form-check-input nazarIdRadio p-2" onclick="editNazar(this)" type="radio" name="nazarNameRadio" value="{{$nazar->nazarId}}" id="">
                                </div>
                        </div>
                  </fieldset>
                @endforeach
                <hr>
				   <table class="table table-striped table-bordered">
					  <thead class="tableHeader">
                            <tr>
                                <th> دریف </th>
                                <th> نام مشتری </th>
                                <th> تاریخ </th>
                                <th> نظر سنجی </th>
                                <th>جوابات </th>
                                <th> <input type="checkbox"  name="" class="selectAllFromTop form-check-input"/>  </th>
                            </tr>
					  </thead>
					  <tbody class="tableBody" style="height:200px;">
                            <tr>
                                <th scope="row">1</th>
                                <td> محمود الیاسی  </td>
                                <td>12/12/1401 </td>
                                <td> نظر سنجی 1401  </td>
                                <td id="viewQuestion"><i class="fa fa-eye"></td>
                                <td id="checkToStartAgainNazar">  <input class="form-check-input" name="" type="checkbox" value=""> </td>
                            </tr>
					  </tbody>
					</table>
			  </div>
			  </div>
            </div>
          </div>
<!-- کالاهای لاتری -->
      </div>
    </div>  

            <!-- ختم تنظیمات امتیاز ها  -->
            <div class="row contentFooter"> </div>
         </div>
    </div>
</div>



        <div class="modal fade" id="editCity" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered dragableModal" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white py-2">
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
            <div class="modal-dialog  modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white py-2">
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
                    <div class="modal-header bg-success text-white py-2">
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
                <div class="modal-header bg-success text-white py-2">
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
                    <select class="form-select" aria-label="Default select example" id="mantiqahForAdd"></select>
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



<!-- مودلهای مربوط به تنظیمات معیار ها و امتیاز ها  -->

<!-- edit target modal -->
<div class="modal fade dragableModal" id="editingTargetModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-success text-white py-2">
          <button type="button" class="btn-close text-danger bg-danger" data-dismiss="modal" aria-label="Close" style="color:red"></button>
        <h5 class="modal-title" id="staticBackdropLabel"> ویرایش تارگت </h5>
      </div>
      <form action="{{url('/editTarget')}}" method="GET" id="editTarget">
        <div class="modal-body">
          <div class="row">
            <div class="col-lg-6">
              <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label"> اساس تارگت </label>
                <input type="text" class="form-control" disabled placeholder="خرید اولیه"  name="baseName" id="baseName" aria-describedby="emailHelp">
                <input type="hidden" name="targetId" id="targetIdForEdit">
              </div>
            </div>
            <div class="col-lg-3">
              <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">  تارگت 1 </label>
                <input type="text" class="form-control" placeholder="تارگت 1" name="firstTarget" id="firstTarget">
              </div>
            </div>
            <div class="col-lg-3">
              <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">  امتیاز تارگیت 1  </label>
                <input type="text" class="form-control" placeholder="" name="firstTargetBonus" id="firstTargetBonus">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-3">
              <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">  تارگت 2 </label>
                <input type="text" class="form-control" placeholder="تارگت 2" name="secondTarget" id="secondTarget">
              </div>
            </div>
            <div class="col-lg-3">
              <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">  امتیاز تارگت 2   </label>
                <input type="text" class="form-control" placeholder="20" name="secondTargetBonus" id="secondTargetBonus">
              </div>
            </div>
            <div class="col-lg-3">
              <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">  تارگیت 3   </label>
                <input type="text" class="form-control" placeholder="23" name="thirdTarget" id="thirdTarget">
              </div>
            </div>
            <div class="col-lg-3">
              <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">  امتیاز تارگت 3   </label>
                <input type="text" class="form-control" placeholder="20" name="thirdTargetBonus" id="thirdTargetBonus">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"> بستن <i class="fa fa-xmark"></i> </button>
          <button type="submit" class="btn btn-success btn-sm">ذخیره <i class="fa fa-save"></i> </button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- Bazaryab Modal -->
<div class="modal fade dragableModal" id="editLotteryPrizes" data-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-success text-white py-2">
          <button type="button" class="btn-close bg-danger" data-dismiss="modal" aria-label="Close" style="color:red"></button>
        <h5 class="modal-title" id="staticBackdropLabel">  ویرایش لاتری  </h5>
      </div>
      <form action="{{url('/editLotteryPrize')}}" method="GET" id="addTarget">
        <div class="modal-body">
          <div class="row">
			  <div class="col-lg-4 col-md-4 col-sm-4"> 
				  <div class="input-group input-group-sm mb-3">
					    	<span class="input-group-text" id="inputGroup-sizing-sm"> اول  </span>
					       <input type="text" class="form-control" name="firstPrize" id="LotfirstPrize" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
					  		<input type="checkbox" class="form-checkbox m-1" name="showfirstPrize[]" id="showfirstPrize">
					</div>
			  </div>
			  <div class="col-lg-4 col-md-4 col-sm-4"> 
				  <div class="input-group input-group-sm mb-3">
					    	<span class="input-group-text" id="inputGroup-sizing-sm"> دوم   </span>
					       <input type="text" class="form-control" name="secondPrize" id="LotsecondPrize" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
					  		<input type="checkbox" class="form-checkbox m-1" name="showsecondPrize[]" id="showsecondPrize">
					</div>
			  </div>
			  <div class="col-lg-4 col-md-4 col-sm-4"> 
				  <div class="input-group input-group-sm mb-3">
					    	<span class="input-group-text" id="inputGroup-sizing-sm"> سوم  </span>
					       <input type="text" class="form-control" name="thirdPrize" id="LotthirdPrize" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
					  		<input type="checkbox" class="form-checkbox m-1" name="showthirdPrize[]" id="showthirdPrize">
					</div>
			  </div>
			 </div>
			
			 <div class="row">
			  <div class="col-lg-4 col-md-4 col-sm-4"> 
				  <div class="input-group input-group-sm mb-3">
					    	<span class="input-group-text" id="inputGroup-sizing-sm"> چهارم  </span>
					       <input type="text" class="form-control" name="fourthPrize" id="LotfourthPrize" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
					  		 <input type="checkbox" class="form-checkbox prizeCheckbox  m-1" name="showfourthPrize[]" id="showfourthPrize">
					</div>
			  </div>
			  <div class="col-lg-4 col-md-4 col-sm-4"> 
				  <div class="input-group input-group-sm mb-3">
					    	<span class="input-group-text" id="inputGroup-sizing-sm"> پنجم   </span>
					       <input type="text" class="form-control" name="fifthPrize" id="LotfifthPrize" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
					  		<input type="checkbox" class="form-checkbox prizeCheckbox  m-1" name="showfifthPrize[]" id="showfifthPrize">
					</div>
			  </div>
			  <div class="col-lg-4 col-md-4 col-sm-4"> 
				  <div class="input-group input-group-sm mb-3">
					    	<span class="input-group-text" id="inputGroup-sizing-sm"> ششم  </span>
					       <input type="text" class="form-control" name="sixthPrize" id="LotsixthPrize" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
					  		<input type="checkbox" class="form-checkbox prizeCheckbox  m-1" name="showsixthPrize[]" id="showsixthPrize">
					</div>
			  </div>
			 </div>
			
			<div class="row">
			  <div class="col-lg-4 col-md-4 col-sm-4"> 
				  <div class="input-group input-group-sm mb-3">
					    	<span class="input-group-text" id="inputGroup-sizing-sm"> هفتم   </span>
					       <input type="text" class="form-control" name="seventhPrize" id="LotseventhPrize" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
					  		<input type="checkbox" class="form-checkbox prizeCheckbox m-1" name="showseventhPrize[]" id="showseventhPrize">
					</div>
			  </div>
			  <div class="col-lg-4 col-md-4 col-sm-4"> 
				  <div class="input-group input-group-sm mb-3">
					    	<span class="input-group-text" id="inputGroup-sizing-sm"> هشتم    </span>
					       <input type="text" class="form-control" name="eightthPrize" id="LoteightthPrize" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
					  	  <input type="checkbox" class="form-checkbox prizeCheckbox m-1" name="showeightthPrize[]" id="showeightthPrize">
					</div>
			  </div>
			  <div class="col-lg-4 col-md-4 col-sm-4"> 
				  <div class="input-group input-group-sm mb-3">
					    	<span class="input-group-text" id="inputGroup-sizing-sm"> نهم  </span>
					       <input type="text" class="form-control"  name="ninethPrize" id="LotninethPrize" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
					  		 <input type="checkbox" class="form-checkbox prizeCheckbox m-1" name="showninethPrize[]" id="showninethPrize">
					</div>
			  </div>
			 </div>
			
			
		<div class="row">
			  <div class="col-lg-4 col-md-4 col-sm-4"> 
				  <div class="input-group input-group-sm mb-3">
					    	<span class="input-group-text" id="inputGroup-sizing-sm"> دهم    </span>
					       <input type="text" class="form-control" name="teenthPrize" id="LotteenthPrize" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
					  	<input type="checkbox" class="form-checkbox prizeCheckbox m-1" name="showteenthPrize[]" id="showteenthPrize">
					</div>
			  </div>
			  <div class="col-lg-4 col-md-4 col-sm-4"> 
				  <div class="input-group input-group-sm mb-3">
					    	<span class="input-group-text" id="inputGroup-sizing-sm"> یازده هم     </span>
					       <input type="text" class="form-control"  name="eleventhPrize" id="LoteleventhPrize" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
					  	   <input type="checkbox" class="form-checkbox prizeCheckbox m-1" name="showeleventhPrize[]" id="showeleventhPrize">
					</div>
			  </div>
			  <div class="col-lg-4 col-md-4 col-sm-4"> 
				  <div class="input-group input-group-sm mb-3">
					    	<span class="input-group-text" id="inputGroup-sizing-sm"> دوازده هم  </span>
					       <input type="text" class="form-control" name="twelvthPrize" id="LottwelvthPrize"  aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
					  		 <input type="checkbox" class="form-checkbox prizeCheckbox m-1" name="showtwelvthPrize[]" id="showtwelvthPrize">
					</div>
			  </div>
			 </div>
		<div class="row">
			  <div class="col-lg-4 col-md-4 col-sm-4"> 
				  <div class="input-group input-group-sm mb-3">
					    	<span class="input-group-text" id="inputGroup-sizing-sm"> سیزدهم     </span>
					       <input type="text" class="form-control" name="thirteenthPrize" id="LotthirteenthPrize" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
					  	<input type="checkbox" class="form-checkbox prizeCheckbox m-1" name="showthirteenthPrize[]" id="showthirteenthPrize">
					</div>
			  </div>
			  <div class="col-lg-4 col-md-4 col-sm-4"> 
				  <div class="input-group input-group-sm mb-3">
					    	<span class="input-group-text" id="inputGroup-sizing-sm">چهاردهم      </span>
					       <input type="text" class="form-control" name="fourteenthPrize" id="LotfourteenthPrize" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
					  	  <input type="checkbox" class="form-checkbox prizeCheckbox m-1" name="showfourteenthPrize[]" id="showfourteenthPrize">
					</div>
			  </div>
			  <div class="col-lg-4 col-md-4 col-sm-4"> 
				  <div class="input-group input-group-sm mb-3">
					    	<span class="input-group-text" id="inputGroup-sizing-sm">  پانزدهم   </span>
					       <input type="text" class="form-control" name="fiftheenthPrize" id="LotfiftheenthPrize"  aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
					  	<input type="checkbox" class="form-checkbox m-1" name="showfiftheenthPrize[]" id="showfiftheenthPrize">
					</div>
			  </div>
			 </div>
			
		<div class="row">
			  <div class="col-lg-4 col-md-4 col-sm-4"> 
				  <div class="input-group input-group-sm mb-3">
				     <span class="input-group-text" id="inputGroup-sizing-sm">  شانزدهم    </span>
				    <input type="text" class="form-control" name="sixteenthPrize" id="LotsixteenthPrize"  aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
				  <input type="checkbox" class="form-checkbox prizeCheckbox m-1" name="showsixteenthPrize[]" id="showsixteenthPrize">
				  </div>
			</div>
			 <div class="col-lg-4 col-md-4 col-sm-4"> 

			</div>
		</div>
		
		 </div>
        <div class="modal-footer">
          <div class="container">
          <div class="row">
            <div class="col-sm-6">
              <div class="input-group input-group-sm mb-3">
                <span class="input-group-text" id="inputGroup-sizing-sm"> حد اقل امتیاز لاتری  </span>
                <input type="text" class="form-control form-control-sm" @if(hasPermission(Session::get( 'adminId'),'specialSetting' ) < 1) disabled @endif value="{{number_format($lotteryMinBonus)}}" name="lotteryMinBonus">
              </div>
            </div>
            <div class="col-sm-6 text-end">
                <button type="button" class="btn btn-danger btn-sm d-end" data-dismiss="modal"> بستن <i class="fa fa-xmark"></i> </button> 
                <button type="submit" class="btn btn-success btn-sm">ذخیره <i class="fa fa-save"></i> </button>
             
            </div>
          </div>  
        </div>
        </div>
      </form>
    </div>
  </div>
</div>



<!-- question Modal  -->
<div class="modal fade" id="listQuestionModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="listQuestionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-success text-white py-2">
        <button type="button" class="btn-close bg-danger" data-dismiss="modal" aria-label="Close"></button>
        <h5 class="modal-title" id="listQuestionModalLabel"> کیفیت کالا  چگونه بود ؟</h5>
      </div>
      <div class="modal-body">
      <div class="card mb-4">
            <div class="card-body">
                <div class="row">
					<div class="col-lg-12">
                      <div class="well">
                        <table class="table table-bordered" id="tableGroupList" >
                            <thead class="tableHeader">
                                <tr>
                                    <th>ردیف</th>
                                    <th>نام  مشتری</th>
                                    <th> جواب </th>
                                    <th> تاریخ </th>
                                    <th> حذف  </th>
                                </tr>
                            </thead>
                            <tbody class="tableBody" id="nazarListBody">
                                
                            </tbody>
                        </table>
                      </div>
                    </div>
              </div>
        </div>
    </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">بستن <i class="fa fa-xmark"></i> </button>
        <!-- <button type="button" class="btn btn-primary">Understood</button> -->
      </div>
    </div>
  </div>
</div>



<!-- question Modal  -->
<div class="modal fade" id="insetQuestion" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="insetQuestionLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form action="{{url('/addNazarSanji')}}" method="get" id="addNazarSanjiForm">
        @csrf
      <div class="modal-header bg-success text-white py-2">
        <button type="button" class="btn-close bg-danger" data-dismiss="modal" aria-label="Close"></button>
        <h5 class="modal-title" id="insetQuestionLabel"> نظر سنجی جدید </h5>
      </div>
      <div class="modal-body">
        <div class="card mb-4">
          <div class="card-body">
            <div class="row">
              <div class="col-lg-12">
                <div class="mb-2">
                  <label for="exampleFormControlTextarea1" class="form-label fs-6">اسم نظر سنجی</label>
                  <input class="form-control" id="nazarSanjiName" name="nazarSanjiName">
                </div>
                <div class="mb-2">
                  <label for="exampleFormControlTextarea2" class="form-label fs-6"> سوال اول </label>
                  <textarea class="form-control" id="content1" name="content1" rows="3"></textarea>
                </div>
                <div class="mb-2">
                  <label for="exampleFormControlTextarea3" class="form-label fs-6"> سوال دوم  </label>
                  <textarea class="form-control" id="content2" name="content2" rows="3"></textarea>
                </div>
                <div class="mb-2">
                  <label for="exampleFormControlTextarea4" class="form-label fs-6"> سوال سوم  </label>
                  <textarea class="form-control" id="content3" name="content3" rows="3"></textarea>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">بستن <i class="fa fa-xmark"></i> </button>
        <button type="submit" class="btn btn-success btn-sm"> ذخیره  <i class="fa fa-save"></i></button>
      </div>
    </form>
  </div>
</div>
</div>


<!-- Button trigger modal -->


<!-- Modal edit nazarSanji -->
<div class="modal fade" id="editNazarModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editNazarModal" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-success text-white py-2">
        <button type="button" class="btn-close bg-danger" data-dismiss="modal" aria-label="Close"></button>
		   <h5 class="modal-title" id="editNazarModal"> ویرایش نظر سنجی  </h5>
      </div>
	 <form action="{{url('/updateQuestion')}}" method="get" id="updateQuestion">
        @csrf
		  <input type="hidden" name="nazarId" id="nazarId" value="">
        <div class="modal-body">
          <div class="card mb-4">
            <div class="card-body">
             <div class="row">
              <div class="col-lg-12">
                <div class="mb-2">
                  <label for="exampleFormControlTextarea1" class="form-label fs-6">اسم نظر سنجی</label>
                  <input class="form-control" id="nazarName1" name="nazarSanjiName" value="">
                </div>
                <div class="mb-2">
                  <label for="exampleFormControlTextarea2" class="form-label fs-6"> سوال اول </label>
                  <textarea class="form-control" id="cont1" name="content1" rows="2" value=""></textarea>
                </div>
                <div class="mb-2">
                  <label for="exampleFormControlTextarea3" class="form-label fs-6"> سوال دوم  </label>
                  <textarea class="form-control" id="cont2" name="content2" rows="2" value=""></textarea>
                </div>
                <div class="mb-2">
                  <label for="exampleFormControlTextarea4" class="form-label fs-6"> سوال سوم  </label>
                  <textarea class="form-control" id="cont3" name="content3" rows="2" value=""></textarea>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
           <button type="button" class="btn btn-danger" data-bs-dismiss="modal"> بستن <i class="fa fa-xmark"> </i> </button>
		  <button type="submit" class="btn btn-success"> ذخیره <i class="fa fa-save"> </i> </button>
      </div>
	 </form>
    </div>
  </div>
</div>

<!-- Modal  view of questions -->
<div class="modal fade" id="viewQuestionModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="viewQuestionLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-success text-white py-2">
          <button type="button" class="btn-close bg-danger" data-dismiss="modal" aria-label="Close"></button>
          <h5 class="modal-title" id="viewQuestionLabel"> جواب نظر سنجی  </h5>
      </div>
      <div class="modal-body">
         <div class="row">
              <table class="table table-bordered" id="tableGroupList" >
				  <thead class="tableHeader">
					  <tr>
						  <th>ردیف</th>
						  <th>  سوالات  </th>
						  <th> جوابات </th>
						   <th> حذف </th>
					  </tr>
				  </thead>
				  <tbody class="tableBody">
						  <tr>
							  <th scope="row">1</th>
							  <td>کیفیت کالاهای ما چگونه است؟ </td>
							  <td>کیفت کالا خوب است </td>
							  <td> <i class="fa fa-trash" style="color:red"> </i> </td>
						 </tr>
					     <tr>
						   <th scope="row">2</th>
						   <td>کیفیت کالاهای ما چگونه است؟ </td>
						   <td>کیفت کالا خوب است </td>
						   <td> <i class="fa fa-trash" style="color:red"> </i> </td>
					    </tr>
					   <tr>
						   <th scope="row">3</th>
						   <td>کیفیت کالاهای ما چگونه است؟ </td>
						   <td>کیفت کالا خوب است </td>
						   <td> <i class="fa fa-trash" style="color:red"> </i> </td>
					  </tr>
				  </tbody>
			 </table>
         </div>
      </div>
    </div>
  </div>
</div>



<script type="text/javascript">
    $(document).ready(function() {
       $("#webSpecialSettingBtn").click(function() {
           $("#webSpecialSettingForm").submit();
       });
    });


    function startAgainNazar(){
	swal({
		  title: "آیا مطمئین هستید؟",
		  text: "یک بار نظر خواهی را از سر شروع کردید دوباره بر نمیگردد.",
		  icon: "warning",
		  buttons: true,
		  dangerMode: true,
		})
		.then((willDelete) => {
		  if (willDelete) {
			swal("شما نظر خواهی را از سر شروع کردید، موفق باشید!", {
			  icon: "success",
			});
		  } else {
			swal("نظر خواهی شروع نگردید");
		  }
		})
}
	
	$("#editLotteryPrizeBtn").on("click", ()=>{
		    if (!($('.modal.in').length)) {
                $('.modal-dialog').css({
                  top: 0,
                  left: 0
                });
              }
              $('#sentTosalesFactor').modal({
                backdrop: false,
                show: true
              });
              
              $('.modal-dialog').draggable({
                  handle: ".modal-header"
                });
		
	     	$("#editLotteryPrizes").modal("show");
	})
</script>
  
@endsection

@extends('admin.layout')
@section('content')

<style>
#officialCustomerStaff{
    display: none;
}
</style>


<div class="container-fluid containerDiv">
    <div class="row">
            <div class="col-lg-2 col-md-2 col-sm-3 sideBar">
                <fieldset class="border rounded mt-5 sidefieldSet">
                    <legend  class="float-none w-auto legendLabel mb-0"> تنظیمات </legend>
                    <div class="form-check">
                        <input class="form-check-input p-2 float-start" type="radio" name="settings" id="customerListRadioBtn" checked>
                        <label class="form-check-label me-4" for="assesPast"> لیست مشتریان </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input p-2 float-start" type="radio" name="settings" id="officialCustomerListRadioBtn">
                        <label class="form-check-label me-4" for="assesPast"> اشخاص رسمی </label>
                    </div>
                     <div class="col-sm-12 customerListStaff">
                        <input type="text" name="" size="20" class="form-control form-control-sm" placeholder="نام" id="searchCustomerByName">
                    </div>
                    <div class="col-sm-12 customerListStaff">
                        <input type="text" name="" size="20" class="form-control form-control-sm" placeholder="کد حساب" id="searchCustomerByCode">
                    </div>
                    <div class="col-sm-12 customerListStaff">
                        <select  style="" class="form-select form-select-sm" id='searchCityId'>
                                <option value="1" hidden>شهر</option>  
                            @foreach($cities as $city)
                                <option value="{{$city->SnMNM}}">{{$city->NameRec}}</option>
                            @endforeach
                            <option value="0">بدون شهر</option>
                        </select>
                    </div>
                        <div class="col-sm-12 customerListStaff">
                            <select  style="" class="form-select form-select-sm" id="searchSelectMantiqah">
                                <option value="1" hidden>منطقه</option> 
                                <option value="0">--</option>
                            </select>
                        </div>
                        <div class="form-group col-sm-12 customerListStaff">
                            <select  style="" class="form-select form-select-sm" id="searchActiveOrNot">
                                <option  value="1" hidden>فعال</option>
                                <option value="1">فعال</option>
                                <option value="0"> غیر فعال</option>
                            </select>
                        </div>
                        <div class="form-group col-sm-12 customerListStaff">
                            <select  style="" class="form-select form-select-sm" id="searchLocationOrNot">
                            <option value="1" hidden>موقعیت</option> 
                                <option value="1">موقعیت دار </option>
                                <option value="0"> بدون موقعیت </option>
                            </select>
                        </div>
                        <div class="form-group col-sm-12 customerListStaff">
                            <select style="" class="form-select form-select-sm" id="orderCustomers">
                                <option value="1" hidden>مرتب سازی</option> 
                                <option value="1">اسم</option>
                                <option value="0">کد</option>
                            </select>
                        </div>

                </fieldset>
                </div>
            <div class="col-sm-10 col-md-10 col-sm-12 contentDiv">
                <div class="row contentHeader">
                    <div class="col-sm-12 text-end mt-1 customerListStaff">
                        <form action="{{ url('/editCustomer') }}" method="POST" style="display: inline;">
                            @csrf
                            <input type="text" id="customerSn" style="display: none" name="customerSn" value="" />
                            <input type="text" id="customerGroup" style="display: none" name="customerGRP" value="" />
                            @if(hasPermission(Session::get( 'adminId'),'customers' ) > -1) 
                            <button class='enableBtn btn btn-success btn-sm text-warning' data-toggle='modal' type="submit" id='editPart' disabled style="color:#9ed5b6 "> ویرایش <i class="fal fa-edit"></i></button>
                            <button class='enableBtn btn btn-success btn-sm text-warning' type="button" id="openDashboard" disabled> داشبورد <i class="fal fa-money-bill-alt"></i></button>
                            @endif
                            @if(hasPermission(Session::get( 'adminId'),'customers' ) > 1) 
                            <button class='enableBtn btn btn-success btn-sm text-warning' data-toggle='modal' type="submit" id='editPart'disabled> ارسال به اکسل <i class="fal fa-file-excel" aria-hidden="true"></i></button>
                            <button class="enableBtn btn btn-success btn-sm text-warning" id="defineRoute" type="button" disabled>تعیین مسیر <i class="fal fa-address-card"></i></button>
                            @endif
                        </form>
                    </div>
                </div>
                <div class="row mainContent">
                    <table id="strCusDataTable" class='table table-bordered table-sm customerListStaff'>
                        <thead class="tableHeader">
                            <tr>
                                <th>ردیف</th>
                                <th>کد</th>
                                <th>اسم</th>
                                <th style="width:390px">آدرس </th>
                                <th>همراه</th>
                                <th>تلفن</th>
                                <th>منطقه </th>
                                <th>درج </th>
                                <th>انتخاب</th>
                            </tr>
                            </thead>
                            <tbody id="customerList" class="select-highlight tableBody">
                                @foreach ($customers as $customer)
                                    <tr>
                                        <td></td>
                                        <td>{{$customer->PCode}}</td>
                                        <td>{{$customer->Name}}</td>
                                        <td style="width:390px">{{$customer->peopeladdress}}</td>
                                        <td>{{$customer->hamrah}}</td>
                                        <td>{{$customer->sabit}}</td>
                                        <td>{{$customer->NameRec}}</td>
                                        <td>2</td>
                                        <td> <input class="customerList form-check-input" name="customerId" type="radio" value="{{$customer->PSN.'_'.$customer->GroupCode}}" id="flexCheckChecked"></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- لیست اشخاص حقیقی -->
                        <form action="{{url('/customer')}}" method="POST" enctype="multipart/form-data" id="officialCustomerStaff" class="px-0 mx-0">
                        @csrf
                        <div class="c-checkout" style="background: linear-gradient(#3ccc7a, #034620); border-radius:5px 5px 2px 2px;">
                            <div class="col-sm-6">
                                <ul class="header-list nav nav-tabs" data-tabs="tabs" style="margin: 0; padding:0;">
                                    <li><a class="active" data-toggle="tab" style="color:black;"  href="#custAddress"> اشخاص حقیقی  </a></li>
                                    <li><a data-toggle="tab" style="color:black;"  href="#moRagiInfo"> اشخاص حقوقی </a></li>
                                </ul>
                            </div>
                            <div class="c-checkout tab-content" style="background-color:#f5f5f5; border-radius:5px 5px 2px 2px;">
                                    <div class="row c-checkout rounded-2 tab-pane active" id="custAddress">
                                            <table class="table table-responsive table-bordered table-sm" id="myTable" style="text-align:center">
                                                <thead class="table bg-success tableHeader">
                                                    <tr>
                                                        <th>ردیف  </th>
                                                        <th>نام </th>
                                                        <th>نام خانودگی  </th>
                                                        <th>شماره ملی </th>
                                                        <th>کد نقش </th>
                                                        <th>کد پستی </th>
                                                        <th> آدرس</th>
                                                        <th>ارسال به دفتر حساب </th>
                                                        <th>ویرایش </th>
                                                    </tr>
                                                </thead>
                                                <tbody class="tableBody">
                                                <?php if(!empty($haqiqiCustomers)){ ?>
                                                    @foreach ($haqiqiCustomers as $haqiqiCustomer)
                                                    <tr>
                                                        <td> {{$loop->index+1}} </td>
                                                        <td> {{$haqiqiCustomer->customerName}}</td>
                                                        <td> {{$haqiqiCustomer->familyName}}</td>
                                                        <td> {{$haqiqiCustomer->codeMilli}} </td>
                                                        <td> {{$haqiqiCustomer->codeNaqsh}}</td>
                                                        <td> {{$haqiqiCustomer->codePosti}}</td>
                                                        <td> {{$haqiqiCustomer->address}}</td>
                                                    <td> <i class="fa fa-paper-plane" style="color:#198754"> </td>
                                                        <td> <a  @if(hasPermission(Session::get( 'adminId'),'homePage' ) >0 ) href="{{url('haqiqiCustomerAdmin', $haqiqiCustomer->customerShopSn)}}" @else href="#" @endif> <i class="fal fa-edit fa-md" style="color:#ffc107"></i> </a></td>
                                                    </tr>
                                                @endforeach
                                                <?php } ?>
                                                </tbody>
                                            </table>
                                     </div>

                                     <div class="row c-checkout rounded-2 tab-pane" id="moRagiInfo">
                                                <table class="table table-hover table-bordered table-sm" id="myTable" style="text-align:center">
                                                    <thead class="table bg-success tableHeader">
                                                        <tr>
                                                            <th> ردیف </th> 
                                                            <th>نام شرکت</th>
                                                            <th>شناسه ملی </th>
                                                            <th>کد نقش </th>
                                                            <th>کد پستی </th>
                                                            <th> آدرس </th>
                                                            <th>ارسال به دفتر حساب </th>
                                                            <th>ویرایش </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="tableBody">
                                                        <?php if(!empty($hohoqiCustomers)){  ?>
                                                        @foreach ($hohoqiCustomers as $hohoqiCustomer)
                                                        <tr>
                                                            <td> {{$loop->index+1}} </td>
                                                            <td> {{$hohoqiCustomer->companyName}}</td>
                                                            <td> {{$hohoqiCustomer->shenasahMilli}}</td>
                                                            <td> {{$hohoqiCustomer->codeNaqsh}}</td>
                                                            <td> {{$hohoqiCustomer->codePosti}}</td>
                                                            <td> {{$hohoqiCustomer->address}}</td>
                                                            <td> <i class="fa fa-paper-plane" style="color:#198754"> </td>
                                                            <td> <a @if(hasPermission(Session::get( 'adminId'),'homePage' ) > 0 ) href="{{url('haqiqiCustomerAdmin', $hohoqiCustomer->customerShopSn)}}" @else href="#" @endif> <i class="fal fa-edit" style="color:#ffc107"></i> </a> </td>
                                                            </tr>
                                                        @endforeach
                                                    <?php }?>
                                                    </tbody>
                                            </table>
                                     </div>
                                 </div>
                             </div>
                        </div>
                    </form>
                </div>
                <div class="row contentFooter"> </div>
            </div>
    </div>
</div>



    <!-- dashboard Modal -->
    <div class="modal fade notScroll" id="customerDashboard"data-backdrop="static"  data-keyboard="false" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable  modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-success text-white py-2">
                    <button type="button" class="btn-close bg-danger" data-dismiss="modal" aria-label="Close"></button>
                    <h5 class="modal-title" id="exampleModalLabel"> داشبورد </h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <span class="fw-bold fs-4"  id="dashboardTitle" style="display:none;"></span>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <form action="https://starfoods.ir/crmLogin" target="_blank"  method="get">
                                <input type="hidden" id="psn" name="psn" value="" />
                                <input type="hidden" name="otherName" value="{{Session::get('adminName')}}" />
                                    <Button class="btn btn-sm btn-success  float-end" type="submit"> ورود جعلی  <i class="fas fa-sign-in"> </i> </Button>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                       <div class="col-lg-8 col-md-8 col-sm-8">
                           <div class="row">
                                <div class="col-lg-3 col-md-4 col-sm-4">
                                    <div class="input-group input-group-sm mb-1">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputGroup-sizing-sm"> کد </span>
                                        </div>
                                        <input type="text" class="form-control form-control-sm noChange" id="customerCode" value="">
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-4 col-sm-4">
                                   <div class="input-group input-group-sm mb-1">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputGroup-sizing-sm"> نام و نام خانوادگی </span>
                                        </div>
                                        <input type="text" class="form-control form-control-sm noChange" id="customerName"  value="علی حسینی" >
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-4 col-sm-4">
                                     <div class="input-group input-group-sm mb-1">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputGroup-sizing-sm"> تعداد فاکتور </span>
                                        </div>
                                        <input type="text" class="form-control form-control-sm noChange" id="countFactor">
                                    </div>
                                </div>
                               
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-4 col-sm-4">
                                   <div class="input-group input-group-sm mb-1">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputGroup-sizing-sm">  تلفن ثابت  </span>
                                        </div>
                                        <input type="text" class="form-control form-control-sm noChange" id="countFactor">
                                    </div>
                                  </div>
                               
                                <div class="col-lg-6 col-md-4 col-sm-4">
                                   <div class="input-group input-group-sm mb-1">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputGroup-sizing-sm">  تلفن همراه   </span>
                                        </div>
                                        <input class="form-control form-control-sm noChange" type="text" id="mobile1" >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                <div class="input-group input-group-sm mb-1">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputGroup-sizing-sm"> آدرس   </span>
                                        </div>
                                        <input type="text" class="form-control form-control-sm noChange" id="customerAddress" value="آدرس">
                                 </div> 
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="mb-1" style="width:350px;">
                                    <label for="exampleFormControlTextarea1" class="form-label fw-bold">یاداشت  </label>
                                    <textarea class="form-control" id="customerProperty" onblur="saveCustomerCommentProperty(this)" rows="4"></textarea>
                                </div>
                            </div>
                       </div>
                    </div>
                    <div class="c-checkout container" style="background-color:#c5c5c5; padding:0.5% !important; border-radius:10px 10px 2px 2px;">
                        <div class="col-sm-12" style="margin: 0; padding:0;">
                            <ul class="header-list nav nav-tabs" data-tabs="tabs" style="margin: 0; padding:0;">
                                <li><a class="active" data-toggle="tab" style="color:black; font-size:14px; font-weight:bold;"  href="#custAddress"> فاکتور های ارسال شده </a></li>
                                <li><a data-toggle="tab" style="color:black; font-size:14px; font-weight:bold;"  href="#moRagiInfo">  کالاهای خریداری شده </a></li>
                                <li><a data-toggle="tab" style="color:black; font-size:14px; font-weight:bold;"  href="#userLoginInfo1"> کالاهای سبد خرید</a></li>
                                <li><a data-toggle="tab" style="color:black; font-size:14px; font-weight:bold;"  href="#customerLoginInfo">ورود به سیستم</a></li>
                                <li><a data-toggle="tab" style="color:black; font-size:14px; font-weight:bold;"  href="#returnedFactors1"> فاکتور های برگشت داده </a></li>
                            </ul>
                        </div>
                        <div class="c-checkout tab-content"   style="background-color:#f5f5f5; margin:0;padding:0.3%; border-radius:10px 10px 2px 2px;">
                            <div class="row c-checkout rounded-3 tab-pane active" id="custAddress"  style="width:99%; margin:0 auto; padding:1% 0% 0% 0%">
                                <div class="col-sm-12">
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead class="bg-success tableHeader">
                                        <tr>
                                            <th> ردیف</th>
                                            <th>تاریخ</th>
                                            <th> نام راننده</th>
                                            <th>مبلغ </th>
                                            <th> جزئیات </th>
                                        </tr>
                                        </thead>
                                        <tbody class="tableBody" id="factorTable">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row c-checkout rounded-3 tab-pane" id="moRagiInfo" style="width:99%; margin:0 auto; padding:1% 0% 0% 0%">
                                <div class="row c-checkout rounded-3 tab-pane" style="width:99%; margin:0 auto; padding:1% 0% 0% 0%">
                                    <div class="col-sm-12">
                                        <table class="table table-bordered table-striped table-sm" style="text-align:center;">
                                            <thead class="bg-success tableHeader">
                                            <tr>
                                                <th> ردیف</th>
                                                <th>تاریخ</th>
                                                <th> نام کالا</th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody class="tableBody" id="goodDetail">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row c-checkout rounded-3 tab-pane" id="userLoginInfo1" style="width:99%; margin:0 auto; padding:1% 0% 0% 0%">
                                <div class="row c-checkout rounded-3 tab-pane" style="width:99%; margin:0 auto; padding:1% 0% 0% 0%">
                                    <div class="col-sm-12">
                                        <table class="table table-bordered table-striped table-sm" style="text-align:center;">
                                            <thead class="bg-success tableHeader">
                                            <tr>
                                                <th> ردیف</th>
                                                <th>تاریخ</th>
                                                <th> نام کالا</th>
                                                <th>تعداد </th>
                                                <th>فی</th>
                                            </tr>
                                            </thead>
                                            <tbody id="basketOrders" class="tableBody">
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="row c-checkout rounded-3 tab-pane" id="customerLoginInfo" style="width:99%; margin:0 auto; padding:1% 0% 0% 0%">
                                <div class="row c-checkout rounded-3 tab-pane" style="width:99%; margin:0 auto; padding:1% 0% 0% 0%">
                                    <div class="col-sm-12">
                                        <table class="table table-bordered table-striped table-sm" style="text-align:center;">
                                            <thead class="bg-success tableHeader">
                                            <tr>
                                                <th> ردیف</th>
                                                <th>تاریخ</th>
                                                <th>نوع پلتفورم</th>
                                                <th>مرورگر</th>
												<th> </th>
                                            </tr>
                                            </thead>
                                            <tbody class="tableBody" id="customerLoginInfoBody">
                                            <tr>
                                                
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="row c-checkout rounded-3 tab-pane" id="returnedFactors1"  style="width:99%; margin:0 auto; padding:1% 0% 0% 0%">
                                <div class="row c-checkout rounded-3 tab-pane" style="width:99%; margin:0 auto; padding:1% 0% 0% 0%">
                                    <div class="col-sm-12">
                                        <table class="homeTables tableSection4 table table-bordered table-striped table-sm" style="text-align:center;">
                                            <thead class="bg-success tableHeader">
                                            <tr>
                                                <th> ردیف</th>
                                                <th>تاریخ</th>
                                                <th> نام راننده</th>
                                                <th>مبلغ </th>
                                                <th> مشاهده </th>
                                            </tr>
                                            </thead>
                                            <tbody id="returnedFactorsBody" class="tableBody">
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end dashboard modal -->

    <!-- used for customer factor details -->
    <div class="modal fade dragableModal" id="viewFactorDetail" tabindex="0" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-success text-white py-2">
                    <button type="button" class="btn-close bg-danger" data-dismiss="modal" aria-label="Close"></button>
                    <h5 class="modal-title" id="exampleModalLabel">جزئیات فاکتور</h5>
                </div>
                <div class="modal-body" id="readCustomerComment">
                    <div class="container">
                        <div class="row" style=" border:1px solid #dee2e6; padding:5px">
                                <h4 style="padding:5px; border-bottom: 1px solid #dee2e6; text-align:center;">فاکتور فروش </h4>
							
							         <div class="factorDetails">
											<div class="factorDetailsItem"> تاریخ فاکتور : <span id="factorDate"> </span> </div>
											<div class="factorDetailsItem">  مشتری :  <span id="customerNameFactor"> </span> </div>
											<div class="factorDetailsItem"> آدرس : <span id="customerAddressFactor">  </span> </div>  
											<div class="factorDetailsItem"> تلفن : <span id="customerPhoneFactor">  </span> </div>
											<div class="factorDetailsItem"> کاربر : <span id="Admin">  </span> </div>
											<div class="factorDetailsItem"> شماره فاکتور : <span id="factorSnFactor">  </span> </div>   
										</div>
                            </div>
                            <div class="row">
                                <table id="strCusDataTable"  class='table table-bordered homeTables'>
                                    <thead class="tableHeader">
                                    <tr>
                                        <th>ردیف</th>
                                        <th>نام کالا </th>
                                        <th>تعداد/مقدار</th>
                                        <th>واحد کالا</th>
                                        <th>فی (تومان)</th>
                                        <th>مبلغ (تومان)</th>
										<th> </th>
										
                                    </tr>
                                    </thead>
                                    <tbody class="tableBody" id="productList">
                                    </tbody>
                                </table>
                            </div>
                          </div>
                       </div>
                    </div>
                 <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    <!-- used for customer address and masir -->
  <div class="modal fade dragableModal" id="personRoute" data-backdrop="static" data--keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-success text-white py-2">
            <button type="button" class="btn-close bg-danger" data-dismiss="modal" aria-label="Close" style="background-color:red;"></button>
          <h5 class="modal-title" id="staticBackdropLabel"> تعین مسیر اشخاص </h5>
        </div>
        <div class="modal-body">
            <select class="form-select" id='cityId' name="city">
            <option value="0" hidden>شهر</option>
                @foreach($cities as $city)
                <option value="{{$city->SnMNM}}">{{$city->NameRec}}</option>
                @endforeach
            </select>
            <select class="form-select mt-4" id="selectMantiqah">
            <option value="0" hidden>منطقه</option>
            </select>
            <input type="hidden" id="customerId">
        </div>
        <div class="modal-footer">
          <button type="button" onclick="takhsisMsir()" class="btn btn-success starfoodbntHover">ذخیره <i class="fa fa-save"></i> </button>
        </div>
      </div>
    </div>
  </div>

<script> 
$("#defineRoute").on("click", ()=>{
	
	     if (!($('.modal.in').length)) {
                $('.modal-dialog').css({
                  top: 0,
                  left: 444
                });
              }
              $('#personRoute').modal({
                backdrop: false,
                show: true
              });
              
              $('.modal-dialog').draggable({
                  handle: ".modal-header"
                });
	$("#personRoute").modal("show");
})

</script>
@endsection

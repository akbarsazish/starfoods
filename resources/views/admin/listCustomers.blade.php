@extends('admin.layout')
@section('content')
<style>
tr th {
    padding: 0px 25px !important;
    height: 40px !important;
}
tr th:first-child {
    padding: 0 !important;
    height: 30px !important;
    border: 0 !important;
}
tr th:last-child {
    padding: 0 6px !important;
    height: 30px !important;
    border: 0 !important;
}

table {
    height:200px;
}


</style>
    <!-- Main page content-->
    <div class="container" style="margin-top:90px">
        <h5 style="width:50%; border-bottom:2px solid gray">لیست مشتریان</h5>
    <div class="card mb-4" style="margin: 0; padding:0;">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12">
                    <div class="well">
                        <div class="container p-1 pb-2 rounded-3">
                            <span class="row" style="margin: 0;">
                                <div class="col-sm-2">
                                    <input type="text" name="" size="20" class="form-control" placeholder="نام" id="searchCustomerByName">
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" name="" size="20" class="form-control" placeholder="کد حساب" id="searchCustomerByCode">
                                </div>
                                <div class="col-sm-1">
                                    <select  style="" class="form-select" id='searchCityId'>
                                            <option value="1" hidden>شهر</option>  
                                        @foreach($cities as $city)
                                            <option value="{{$city->SnMNM}}">{{$city->NameRec}}</option>
                                        @endforeach
										<option value="0">بدون شهر</option>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <select  style="" class="form-select" id="searchSelectMantiqah">
                                        <option value="1" hidden>منطقه</option> 
                                        <option value="0">--</option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-1">
                                    <select  style="" class="form-select" id="searchActiveOrNot">
                                        <option  value="1" hidden>فعال</option>
                                        <option value="1">فعال</option>
                                        <option value="0"> غیر فعال</option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-2">
                                    <select  style="" class="form-select" id="searchLocationOrNot">
                                    <option value="1" hidden>موقعیت</option> 
                                        <option value="1">موقعیت دار </option>
                                        <option value="0"> بدون موقعیت </option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-2">
                                    <select style="" class="form-select" id="orderCustomers">
                                        <option value="1" hidden>مرتب سازی</option> 
                                        <option value="1">اسم</option>
                                        <option value="0">کد</option>
                                    </select>
                                </div>
                            </span>
                        </div>

                        <div class="row mt-3">
                            <div class="col-sm-12 text-end">
                                <form action="{{ url('/editCustomer') }}" method="POST" style="display: inline;">
                                    @csrf
                                    <input type="text" id="customerSn" style="display: none" name="customerSn" value="" />
                                    <input type="text" id="customerGroup" style="display: none" name="customerGRP" value="" />
                                    @if(hasPermission(Session::get( 'adminId'),'customers' ) > -1) 
                                    <button class='enableBtn btn btn-success text-warning starfoodbntHover' data-toggle='modal' type="submit" id='editPart' disabled style="color:#9ed5b6 "> ویرایش <i class="fal fa-edit fa-lg"></i></button>
                                    <button class='enableBtn btn btn-success text-warning starfoodbntHover' type="button" id="openDashboard" disabled>داشبورد<i class="fal fa-money-bill-alt fa-lg"></i></button>
                                    @endif
                                    @if(hasPermission(Session::get( 'adminId'),'customers' ) > 1) 
                                    <button class='enableBtn btn btn-success btn-md text-warning starfoodbntHover' data-toggle='modal' type="submit" id='editPart'disabled> ارسال به اکسل <i class="fal fa-file-excel fa-lg" aria-hidden="true"></i></button>
                                    <button class="enableBtn btn btn-success btn-md text-warning starfoodbntHover" id="defineRoute" type="button" disabled>تعیین مسیر <i class="fal fa-address-card fa-lg"></i></button>
                                    @endif
                                </form>
                            </div>
                            <div class="col-sm-2">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <table id="strCusDataTable" class='table table-bordered mt-4 homeTables'>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- dashboard Modal -->
    <div class="modal fade notScroll" id="customerDashboard"data-backdrop="static"  data-keyboard="false" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable  modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <button type="button" class="btn-close btn-danger" style="background-color:red;" data-dismiss="modal" aria-label="Close"></button>
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
                                    <Button class="btn btn-md btn-success buttonHover float-end" type="submit"> ورود جعلی  <i class="fas fa-sign-in fa-lg"> </i> </Button>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                       <div class="col-lg-8 col-md-8 col-sm-8">
                           <div class="row">
                                <div class="col-lg-3 col-md-4 col-sm-4">
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputGroup-sizing-sm"> کد </span>
                                        </div>
                                        <input type="text" class="form-control form-control-sm noChange" id="customerCode" value="">
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-4 col-sm-4">
                                   <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputGroup-sizing-sm"> نام و نام خانوادگی </span>
                                        </div>
                                        <input type="text" class="form-control form-control-sm noChange" id="customerName"  value="علی حسینی" >
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-4 col-sm-4">
                                     <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputGroup-sizing-sm"> تعداد فاکتور </span>
                                        </div>
                                        <input type="text" class="form-control form-control-sm noChange" id="countFactor">
                                    </div>
                                </div>
                               
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-4 col-sm-4">
                                   <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputGroup-sizing-sm">  تلفن ثابت  </span>
                                        </div>
                                        <input type="text" class="form-control form-control-sm noChange" id="countFactor">
                                    </div>
                                  </div>
                               
                                <div class="col-lg-6 col-md-4 col-sm-4">
                                   <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputGroup-sizing-sm">  تلفن همراه   </span>
                                        </div>
                                        <input class="form-control form-control-sm noChange" type="text" id="mobile1" >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                <div class="input-group input-group-sm mb-3">
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
                                <div class="mb-3" style="width:350px;">
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
                <div class="modal-header bg-success text-white">
                    <button type="button" class="btn-close bg-danger" data-dismiss="modal" aria-label="Close"></button>
                    <h5 class="modal-title" id="exampleModalLabel">جزئیات فاکتور</h5>
                </div>
                <div class="modal-body" id="readCustomerComment">
                    <div class="container">
                        <div class="row" style=" border:1px solid #dee2e6; padding:10px">
                                <h4 style="padding:10px; border-bottom: 1px solid #dee2e6; text-align:center;">فاکتور فروش </h4>
							
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
        <div class="modal-header bg-success text-white">
            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close" style="background-color:red;"></button>
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

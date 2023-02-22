@extends('admin.layout')
@section('content')

<div class="container">
<div class="row">
<div class="profielpage">
        <h5 style="border-bottom:2px solid gray; width:50%"> اطلاعات خصوصی </h5>
   
{{-- {{Session::get('psn')}} --}}
<form action="{{url('/customer')}}" method="POST" enctype="multipart/form-data">
@csrf
<div class="c-checkout container" style="background: linear-gradient(#3ccc7a, #034620); margin:0.2% 0; margin-bottom:0; padding:0.5% !important; border-radius:10px 10px 2px 2px;">
    <div class="col-sm-6" style="margin: 0; padding:0;">
        <ul class="header-list nav nav-tabs" data-tabs="tabs" style="margin: 0; padding:0;">
            <li><a class="active" data-toggle="tab" style="color:black;"  href="#custAddress"> اشخاص حقیقی  </a></li>
            <li><a data-toggle="tab" style="color:black;"  href="#moRagiInfo"> اشخاص حقوقی </a></li>
        </ul>
    </div>
    <div class="c-checkout tab-content" style="background-color:#f5f5f5; margin:0;  padding:0.3%; border-radius:10px 10px 2px 2px;">
            <div class="row c-checkout rounded-3 tab-pane active" id="custAddress" style="width:99%; margin:0 auto; padding:1% 0% 0% 0%">
                <div class="col-sm-12">
                    <div class="row " style="width:98%; padding:0 1% 2% 0%">
                        <div class="card-body">
                            <div class="row pt-3">
                                <div class="col-lg-12">
                                    <table class="table table-responsive table-bordered" id="myTable" style="text-align:center">
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <div class="row c-checkout rounded-3 tab-pane" id="moRagiInfo" style=" width:99%; margin:0 auto; padding:1% 0% 0% 0%">
            <div class="row " style="width:98%; padding:0 1% 2% 0%">
                    <div class="card-body">
                        <div class="row pt-3">
                            <div class="col-lg-12">
                                {{-- <a href="#" class='btn btn-success btn-sm'> اضافه کردن مشتری <i class="fa fa-plus fa-lg" aria-hidden="true"></i></a> --}}
                                <table class="table table-hover table-bordered table-sm table-light" id="myTable" style="text-align:center">
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
											  <td> <a  @if(hasPermission(Session::get( 'adminId'),'homePage' ) >0 ) href="{{url('haqiqiCustomerAdmin', $hohoqiCustomer->customerShopSn)}}" @else href="#" @endif> <i class="fal fa-edit fa-md" style="color:#ffc107"></i> </a> </td>
                                            </tr>
                                        @endforeach
                                    <?php }?>
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
</form>
</div>
</div>

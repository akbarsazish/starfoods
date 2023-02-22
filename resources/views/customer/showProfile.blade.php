@extends('layout.layout')
@section('content')

<div class="container">
<div class="row">
<div class="profielpage">
    <div class="text-black brounded" align="right" style="border-bottom:1px solid red; top:0">
        <h4> اطلاعات خصوصی </h4>
   </div>
{{-- {{Session::get('psn')}} --}}
<form action="{{url('/customer')}}" method="POST" enctype="multipart/form-data">
@csrf
<div class="c-checkout container" style="background-color:#c5c5c5; margin:0.2% 0; margin-bottom:0; padding:0.5% !important; border-radius:10px 10px 2px 2px;">
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
                                    <table class="table table-hover table-bordered table-sm table-light" id="myTable" style="td:hover{ cursor:move;}; text-align:center">
                                        <thead class="table bg-secondary bg-gradient">
                                            <tr>
                                                <th>نام </th>
                                                <th>نام خانودگی  </th>
                                                <th>شماره ملی </th>
                                                <th>کد اقتصادی</th>
                                                <th>کد نقش </th>
                                                <th>کد پستی </th>
                                                <th> آدرس</th>
                                                <th>ایمیل آدرس</th>
                                                <div> شماره همراه</div>
                                                <div>تلفن ثابت</div>
                                                <div>شناس نامه </div>
                                            </tr>
                                        </thead>
                                        <tbody>
                                    <?php if(!empty($haqiqicustomers)){
                                        ?>
                                        @foreach ($haqiqicustomers as $haqiqicustomer)
                                        <tr>
                                            <td> {{$haqiqicustomer->customerName}}</td>
                                            <td> {{$haqiqicustomer->familyName}}</td>
                                            <td> {{$haqiqicustomer->codeMilli}} </td>
                                            <td> {{$haqiqicustomer->codeEqtisadi}}</td>
                                            <td> {{$haqiqicustomer->codeNaqsh}}</td>
                                            <td> {{$haqiqicustomer->codePosti}}</td>
                                            <td> {{$haqiqicustomer->address}}</td>
                                            <td> {{$haqiqicustomer->email}}</td>
                                            <td> {{$haqiqicustomer->phoneNo}}</td>
                                            <td> {{$haqiqicustomer->shenasNamahNo}}</td>
                                            <td> {{$haqiqicustomer->sabetPhoneNo}}</td>
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

        <div class="row c-checkout rounded-3 tab-pane" id="moRagiInfo" style="background-color:#c5c5c5; width:99%; margin:0 auto; padding:1% 0% 0% 0%">
            <div class="row " style="width:98%; padding:0 1% 2% 0%">
                    <div class="card-body">
                        <div class="row pt-3">
                            <div class="col-lg-12">
                                {{-- <a href="#" class='btn btn-success btn-sm'> اضافه کردن مشتری <i class="fa fa-plus fa-lg" aria-hidden="true"></i></a> --}}
                                <table class="table table-hover table-bordered table-sm table-light" id="myTable" style="td:hover{ cursor:move;}; text-align:center">
                                    <thead class="table bg-secondary bg-gradient">
                                        <tr>
                                            <th>نام شرکت</th>
                                            <th>شناسه ملی </th>
                                            <th>کد اقتصادی</th>
                                            <th>کد نقش </th>
                                            <th>شماره ثبت</th>
                                            <th>کد پستی </th>
                                            <th> آدرس </th>
                                            <th>ایمیل آدرس  </th>
                                            <div> شماره همراه</div>
                                            <div>تلفن ثابت</div>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(!empty($hoqoqicustomers)){
                                            ?>
                                        @foreach ($hoqoqicustomers as $hoqoqicustomer)
                                        <tr>
                                            <td> {{$hoqoqicustomer->companyName}}</td>
                                            <td> {{$hoqoqicustomer->shenasahMilli}}</td>
                                            <td> {{$hoqoqicustomer->codeEqtisadi}}</td>
                                            <td> {{$hoqoqicustomer->codeNaqsh}}</td>
                                            <td> {{$hoqoqicustomer->registerNo}}</td>
                                            <td> {{$hoqoqicustomer->codePosti}}</td>
                                            <td> {{$hoqoqicustomer->address}}</td>
                                            <td> {{$hoqoqicustomer->email}}</td>
                                            <td> {{$hoqoqicustomer->phoneNo}}</td>
                                            <td> {{$hoqoqicustomer->sabetPhoneNo}}</td>
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
@endsection



@extends('admin.layout')
@section('content')

<div class="container-fluid containerDiv">
    <div class="row">
            <div class="col-lg-2 col-md-2 col-sm-3 sideBar">
                <fieldset class="border rounded mt-5 sidefieldSet">
                    <legend  class="float-none w-auto legendLabel mb-0"> انتخاب </legend>
                        <form action="{{url('/getPayedOnlines')}}" method="get" id="getPayedOnlineForm">
                        <span class="situation">
                            <fieldset class="border rounded">
                                <legend  class="float-none w-auto legendLabel mb-0">وضعیت</legend>
                                <div class="form-check">
                                <input class="form-check-input float-start" type="checkbox" name="sefRadio" id="sefRemainPayRadio">
                                    <label class="form-check-label ms-3" for="sefRemainPayRadio"> ارسال نشده</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input float-start" type="checkbox" name="sefRadio" id="sefSentPayRadio">
                                    <label class="form-check-label ms-3" for="sefSentPayRadio"> ارسال شده </label>
                                </div>
                            </fieldset>
                        </span>
                        <div class="input-group input-group-sm mb-1">
                            <span class="input-group-text" id="inputGroup-sizing-sm">تاریخ </span>
                            <input type="text" class="form-control" id="payFirstDate">
                        </div>
                        <div class="input-group input-group-sm mb-1">
                            <span class="input-group-text" id="inputGroup-sizing-sm"> الی </span>
                            <input type="text" class="form-control" id="paySecondDate">
                        </div>
                        <div class="input-group input-group-sm mb-1">
                            <span class="input-group-text" > طرف حساب </span>
                            <input type="text" id="payTarafHisabCode" class="form-control"  placeholder="کد ">
                        </div>
                        <div class="mb-1">
                            <input type="text" id="payTarafHisabName" placeholder="نام " class="form-control form-control-sm">
                        </div>
                        <div class="input-group input-group-sm mb-1">
                            <button type="button" class="btn btn-success btn-sm topButton" id="submitpayForm"> بازخوانی &nbsp; <i class="fa fa-refresh"></i> </button>
                        </div>
                    </form>
                    <button type="button" class="btn btn-success btn-sm topButton" id="sendPayToHisabdariBtn" disabled> ارسال به دفتر حساب <i class="fa fa-check"></i> </button>
                    <button type="button" class="btn btn-success btn-sm topButton" id="cancelPayFromHisabdariBtn" disabled> برگشت از دفتر حساب  <i class="fa fa-history"></i> </button>

                </fieldset>
                </div>
            <div class="col-sm-10 col-md-10 col-sm-12 contentDiv">
                <div class="row contentHeader"> </div>
                <div class="row mainContent">
                    <table class="table table-hover table-bordered table-sm">
                            <thead class="tableHeader">
                                <tr class="bg-success">
                                    <th> ردیف </th>
                                    <th> شماره فاکتور</th>
                                    <th style="width:80px;">  تاریخ  </th>
                                    <th style="width:180px;">واریز کننده</th>
                                    <th> مبلغ </th>
                                    <th style="width:77px;"> زمان ثبت</th>
                                    <th>ارسال</th>
                                </tr>
                            </thead>
                            <tbody id="paymentListBody" class="tableBody">
                                @foreach ($pays as $pay) 
                                    <tr @if($pay->isSent==1) class="payedOnline" @endif onclick="getPayDetail(this,{{$pay->id}},{{$pay->PSN}})">
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$pay->FactNo}}</td>
                                        <td style="width:80px;">{{$pay->payedDate}}</td>
                                        <td style="width:180px; font-weight:bold;">{{$pay->Name}}</td>
                                        <td  style="font-weight:bold;">{{number_format($pay->payedMoney/10)}} ت</td>
                                        <td style="width:77px;">{{$pay->TimeStamp}}</td>
                                        <td> @if($pay->isSent==0)خیر  @else بله @endif</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <table id="footer">
                            <div class="grid-tableFooter">
                        </div>
                    </table>
                </div>
                <div class="row contentFooter"> </div>
            </div>
    </div>
</div>


@endsection
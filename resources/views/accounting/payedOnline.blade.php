@extends('admin.layout')
@section('content')
<style>
    table tr {
        font-size:14px;
        resize: horizontal !important;
    }
    table tr th{
        resize: horizontal !important;
    }
    .trFocus:focus {
         background-color: lightblue !important;
    }
    .sideButton{
        width:100px;
    }
	
    .forLabel{
        font-size:14px;
        display:inline;
    }
	
 body{
	 max-height:100vh; 
	 position:fixed;
	}

	
.grid-tableFooter {
  display: grid;
  grid-template-columns: auto auto auto;
 color:#000; 
}
.tableFooter-item {
  padding: 3px;
  font-size: 14px;
  text-align: center;
  border-radius:3px;
  margin:3px;
  background-color:#9ad5be;
}
.textContent {
	font-size:13px;
}
	
.input-group>input.someInput {flex: 0 1 100px;}
	
</style>
<div class="container-fluid" id="salesOrderCont" style="margin-top:60px; overflow-y:auto;">
    <div class="row px-0 mx-0">
        <div class="col-lg-3 sideDive">
                <fieldset class="border rounded">
                    <legend  class="float-none w-auto legendLabel mb-0">انتخاب</legend>
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
                            <button type="button" class="btn btn-success btn-sm topButton" id="submitpayForm"> بازخوانی &nbsp; <i class="fa fa-check"></i> </button>
                        </div>
                    </form>
                </fieldset>
                <button type="button" class="btn btn-success btn-sm topButton" id="sendPayToHisabdariBtn" disabled>تایید ارسال به دفتر حساب &nbsp; <i class="fa fa-check"></i> </button>
                <button type="button" class="btn btn-success btn-sm topButton" id="cancelPayFromHisabdariBtn" disabled> برگشت از دفتر حساب &nbsp; <i class="fa fa-check"></i> </button>
        </div>

        <div class="col-lg-9 contentDiv mt-1">
            <div class="row">
                <table class="table table-hover table-bordered">
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
             </table>
            </div> 
         </div>
    </div>
</div>
</div>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>





@endsection
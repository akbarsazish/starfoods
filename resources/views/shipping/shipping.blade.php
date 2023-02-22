@extends('layout.layout')
@section('content');

    <div class="container" style="margin-top: 55px;">
        <div class="row fw-bold"> <span>گیرنده :{{Session::get('username')}}</span> </div>
        <div class="row">
            <div class="col-lg-12 col-md-12">
            <form method="post" action="{{url('/addFactor')}}" onSubmit="chekForm(event)">
                  @csrf
                   
                   <div class="row">
                        <div class="col-lg-6 col-md-6">
                              <div class="grid-container-shipping">
                                <div class="grid-item-shipping">
                                        @if ($setting->firstDayMoorningActive==1 or $setting->firstDayAfternoonActive==1)
                                            <div class="p-3 rounded-2">
                                                <label class="c-checkou fw-bold" style="font-size:16px;">{{$date1}}</label>
                                            </div>
                                        @endif
                                </div> 
                                <div class="grid-item-shipping">
                                  @if ($setting->firstDayMoorningActive!=0)
                                        <label id="ns2" class="w-100 mt-1">
                                            <span class="c-ui-radio" >
                                                    <input class="d-inline-flex" type="radio" id="DAY1M" onchange="clearFaveDate()" name="recivedTime"  value="{{'1,'.$tomorrowDate}}">
                                                    <span id="Radio1" name="Radio1 " class="c-ui-radio__check d-inline-flex "></span>
                                            </span>
                                            <span class="c-checkout-paymethod__source-title fw-bold d-inline-flex " style="font-size: 14px; margin-right:10px;">{{$setting->moorningTimeContent}}</span> &nbsp; &nbsp;
                                            <i class="fas fa-sun fa-xl" style="color:#eb9221"> </i>
                                        </label>
                                    @endif
                                     @if ($setting->firstDayAfternoonActive!=0)
                                        <label id="na1" class="is-selected w-100">
                                            <div class=" mt-0 pt-0" @if ($setting->firstDayAfternoonActive==0) style="display: none" @endif>
                                                <span class="c-ui-radio d-inline-flex ">
                                                    <input class="d-inline-flex " type="radio" id="DAY1A" onchange="clearFaveDate()" name="recivedTime" value="{{'2,'.$tomorrowDate}}">
                                                    <span id="Radio2" name="Radio1" class="c-ui-radio__check d-inline-flex"> </span>
                                                </span>
                                                <span class="c-checkout-paymethod__source-title fw-bold d-inline-flex" style="font-size: 14px; margin-right:6px;"> {{$setting->afternoonTimeContent}} </span> &nbsp; &nbsp;
                                                &nbsp;<i class="fas fa-moon fa-xl" style="color:green"> </i>
                                            </div>
                                        </label>
                                 @endif
                           </div>
                        </div>
                     </div>


                     <div class="col-lg-6 col-md-6">
                       <div class="grid-container-shipping">
                            <div class="grid-item-shipping">
                                        @if ($setting->secondDayMoorningActive==1 or $setting->secondDayAfternoonActive==1)
                                            <div class="p-3 rounded-2">
                                                <label class=" c-checkou fw-bold" style="font-size:16px;">{{$date2}}</label>
                                            </div>
                                        @endif
                            </div>
                            <div class="grid-item-shipping">
                                @if ($setting->secondDayMoorningActive!=0)
                                        <label id="ns2" class="w-100 mt-2">
                                                <span class="c-ui-radio" >
                                                <input id="DAY2M"  onchange="clearFaveDate()" class="d-inline-flex " type="radio" name="recivedTime"  value="{{'1,'.$afterTomorrowDate}}">
                                                <span id="Radio1" name="Radio1 " class="c-ui-radio__check d-inline-flex "></span>
                                            </span>
                                            <span class="c-checkout-paymethod__source-title fw-bold d-inline-flex " style="font-size: 14px; margin-right:10px;">{{$setting->moorningTimeContent}}</span> &nbsp; &nbsp;
                                            <i class="fas fa-sun fa-xl" style="color:#eb9221;"></i>
                                        </label>
                                @endif
                                @if ($setting->secondDayAfternoonActive!=0)
                                    <label id="ns2" class="is-selected w-100">
                                        <div class=" mt-0 pt-0" >
                                            <span class="c-ui-radio d-inline-flex ">
                                                <input class="d-inline-flex" id="DAY2A"  onchange="clearFaveDate()" type="radio" name="recivedTime"  value="{{'2,'.$afterTomorrowDate}}">
                                                <span id="Radio2" name="Radio1" class="c-ui-radio__check d-inline-flex"> </span>
                                            </span>
                                            <span class="c-checkout-paymethod__source-title fw-bold d-inline-flex" style="font-size: 14px; margin-right:6px;">{{$setting->afternoonTimeContent}} </span> &nbsp; &nbsp;
                                            <i class="fas fa-moon fa-xl" style="color:green;"> </i>
                                        </div>
                                    </label>
                                @endif
                            </div>
                        </div>
                     </div>
                    </div>
              
                  <div class="row">
                        <div class="col-lg-6 col-md-6">
                              <div class="grid-container-shipping">
                                 @if ($setting->FavoriteDateMoorningActive!=0 or $setting->FavoriteDateAfternoonActive!=0)
                                    <div class="p-1 rounded-2">
                                        <label class="rounded-2 c-checkou fw-bold" style="font-size:14px; padding:5px;">تاریخ دلخواه </label>
                                    </div>
                                  <input type="text" class="form-control" autocomplete="off" name="delkhahDate" id="favDate"/>
                                    <span style="display:none;"> <input class="d-inline-flex" id="delkhah" type="radio" name="recivedTime"  value=""></span>
                                 @endif
                               </div>
                            </div>

                            <div class="col-lg-6 col-md-6">
                              <div class="grid-container-shipping">
                                    <div class="p-1 flex-wrap flex-shrink-1">
                                        <label   class="btn fw-bold" style="font-size:12px;">انتخاب آدرس</label>
                                    </div>
                                    @if(count($addresses)>0)
                                        <select name="customerAddress" class="form-select">
                                                <option value="{{$customer->peopeladdress.'_0'}}" style="font-size:12px">{{$customer->peopeladdress}}</option>
                                            @foreach($addresses as $address)
                                                <option value="{{$address->AddressPeopel.'_'.$address->SnPeopelAddress}}" style="font-size:12px">{{$address->AddressPeopel}}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <select name="customerAddress" class="form-select">
                                            <option value="{{$customer->peopeladdress.'_0'}}" style="font-size:12px">{{$customer->peopeladdress}}</option>
                                        </select>
                                    @endif
                             </div>
                        </div>
                   </div>
                   <div class="row">
                        <div class="col-lg-12 col-md-12">
                         <div class="grid-container-shipping">
                                <div class="grid-item-shipping">
                                    <div class="p-3 rounded-2">
                                        <label class=" fw-bold" style="font-size:16px;"> انتخاب پرداخت</label>
                                    </div>
                                </div>
                            <div class="grid-item-shipping mt-3">
                                    @if($pardakhtLive!=0)
                                        <input id="hozoori" type="radio" class="form-check-input" name="pardakhtType" onchange="chekForm(event)"  style="font-size:18px;">
                                        <label for="hozoori" style="font-size: 16px;">  حضوری 
                                        <i class="fas fa-truck fa-xl pe-1" style="color:#eb9221"> </i>  </label> 
                                    @endif
                            </div>
                            <div class="grid-item-shipping mt-3">
                                   <input id="bankPayment" type="radio"  onchange="chekForm(event)" class="form-check-input" name="pardakhtType" style="font-size:18px;"> 
                                    <label for="bankPayment" style="font-size: 16px;">
                                      اینترنتی 
                                        <i class="fas fa-credit-card fa-xl pe-1" style="color:green"></i>
                                   </label>
                            </div>
                           </div>
                           </div>
                        </div>
                   </div>
                   </div>
                   <div class="row">
                        <div class="col-lg-12 col-md-12">
                         <div class="grid-container-shipping1"> 
                            <div class="grid-item-shipping text-center">
                                <ul class="c-checkout-summary__summary mb-0">
                                    <li>
                                        <span>قیمت کالاها (۱)</span>
                                        <span> {{number_format($allMoney+$profit)}} {{$currencyName}} </span>
                                    </li>
                                    <li class="c-checkout-summary__discount">
                                        <span> کیف تخفیف </span>
                                        <input type="text" name="takhfif" style="display: none;" value="{{$takhfifCase}}"> 
                                         <span class="fw-bold" id="discountWallet">  &nbsp;  {{number_format($takhfifCase)}} تومان</span>
                                    </li>
                                    <li class="c-checkout-summary__discount">
                                        <span> تخفیف کالاها </span>
                                        <span class="discount-price">{{number_format($profit)}} {{$currencyName}}</span>
                                    </li>
                                    <li>
                                        <span> مبلغ قابل پرداخت </span>
                                       <div> <span class="fw-bold"  id="payableMoney"> {{number_format($allMoney)}} </span> <span class="fw-bold"> {{$currencyName}}</span> </div>
                                    </li>
                                </ul>
                          </div>
                          <div class="grid-item-shipping">
                            <input type="text" value="{{number_format($allMoney)}}"  id="allMoneyToSend" name="allMoney" style="display:none;"/>
                            <button type="submit"  class="btn buttonContinue" style="float:left;" id="sendFactorSumbit">  <i class="fa fa-check" aria-hidden="true"></i>ارسال فاکتور</button>
                            <a href="{{url('/starfoodFrom')}}"><button type="button"  class="btn buttonContinue" style="float:left;display:none" id="showPaymentForm">  <i class="fa fa-check" aria-hidden="true"></i>پرداخت و ارسال فاکتور</button></a>
                            <input type="hidden" id="SumPar" value="TotalPrice" />
                            </div>
                         </div>
                     </div>
                  </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
  document.querySelector("#delkhah").style.display = "none";
$("#discountWallet").on("change",()=>{
    if($("#discountWallet").is(':checked')){
		//موقتا غیر فعال است.
        //$("#moblaqhTakhfif").css({ display: "flex"});
        $("#takhfif").val($("#discountWallet").val());
		//موقتا غیر فعال است.
		//$("#payableMoney").text(($("#allMoneyToSend").val().replace(/,/g, "")-$("#takhfif").val()).toLocaleString("en-us"));
        $("#bankPayment").prop("checked",true).change();
		//موقتا برعکس است.
		//$("#discountWallet").prop('checked', false).change();
       
    }else{
        $("#moblaqhTakhfif").css({ display: "none"});
        $("#takhfif").val(0);
		$("#payableMoney").text($("#allMoneyToSend").val().toLocaleString("en-us"));
		$("#discountWallet").prop('checked', false).change();
    }
    
});

// $("#hozoori").on("change",()=>{
//     if($("#hozoori").is(':checked')){
//         $("#hozoori").prop("checked",true);
//         $("#discountWallet").css({ display: "none"});
// 		//موقتا برعکس است.
//         $("#discountWallet").prop("checked",false).change();
//         $("#moblaqhTakhfif").css({ display: "none"});
//     }

// });
//موقتا نوشته شده است.
// function temproryClosed() {
//     alert('سرویس پرداخت آنلاین موقتا غیر فعال است.');
//     $("#hozoori").prop("checked",true);
//     $("#bankPayment").prop("checked",false);
	
// 	$("#discountWallet").prop("checked",false).change();
// }

function temproryClosed() {
    // alert('سرویس پرداخت آنلاین موقتا غیر فعال است.');
    // $("#hozoori").prop("checked",true);
    // $("#bankPayment").prop("checked",false);
	// $("#discountWallet").prop("checked",false).change();
    $("#sendFactorSumbit").css("display","none");
    $("#showPaymentForm").css("display","block");
}
	function payHoozori(){
	$("#sendFactorSumbit").css("display","block");
    $("#showPaymentForm").css("display","none");
	}

function clearFaveDate(element) {


	if($('#bankPayment').is(':checked')) {
		temproryClosed();							  
	}
    $("#favDate").val("");
}
	$("#showPaymentForm").on("click",()=>{
		$.ajax({
        method: 'get',
        url: "https://starfoods.ir/setFactorSessions",
        async: true,
        data: {
            _token: "{{ csrf_token() }}",
            recivedTime: $('input[name=recivedTime]:checked').val(),
            takhfif:$("#discountWallet").val(),
            receviedAddress:$('select[name="customerAddress"] option:selected').val(),
            allMoneyToSend:$("#allMoneyToSend").val(),
            isSent:0,
            orderSn:0
        },
        success: function(respond) {
        },
        error:function(error){
            alert("some error exist");
        }
        });
    });

</script>

@endsection

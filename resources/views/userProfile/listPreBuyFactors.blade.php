@extends('layout.layout')
@section('content')
<div class="modalBackdrop">
    <div id='unitStuffContainer' class="alert alert-danger" style="max-width: 200px; background-color: #ffffff66; padding: 5px; width: 100%; max-height: 85vh; overflow: auto;"></div>
</div>

<div class="container" style="margin-top:70px">
            <div class="row">
                <div class="col-lg-8 col-md-8 col-sm-12">
                    @if(count($orderPishKarids)>0)
                        <h5 class="border-bottom border-danger"> تعداد اقلام پیش خرید: {{count($orderPishKarids)}} </h5>
                    @endif
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12"> </div>
            </div>
            
            <div class="row">
                <div class="col-lg-9">
                     <div class="row">
                         @php
                            $allMoney = 0;
                            $allMoneyPishFactor =0;
                            $snOrder = 0;
                            $snHDS=0;
                            $amountExist=0;
                            $freeExistance=0;
                        @endphp
                        @foreach ($orderPishKarids as $order)
                            @php
                                $allMoneyPishFactor += $order->Price/$currency;
                                $snHDS = $order->SnHDS;
                            @endphp

                        <div class="col-lg-6 px-1" id='order{{$order->SnOrderBYSPishKharid}}' style="background-color:white;">
                                <div class="grid-container">
                                    <div class="grid-item">
                                        <a href=''>
                                        <img class="img-responsive cartPic" src="{{ url('/resources/assets/images/kala/' . $order->SnGood . '_1.jpg') }}" alt=''>
                                        </a>
                                    </div>
                                    <div class="grid-item">
                                                <p>  {{ $order->GoodName }} </p>
                                               <div class='c-product__add'>
                                                  <a class='btn-add-to-cart' id="updatedPishKharid{{ $order->SnGood }}" value=''
                                                    onclick='updatePishKharid({{ $order->SnGood }},this,{{ $order->SnOrderBYSPishKharid }})'
                                                    style='width:auto;text-align: center; padding-right: 10px;
                                                    background-color: #6e3f06;
                                                    font-weight: bold;'
                                                    class='updateData btn-add-to-cart'>{{ $order->Amount / $order->PackAmount }}{{ $order->secondUnitName }}
                                                    معادل
                                                    {{ $order->Amount / 1 }}{{ $order->UName }}</a>
                                               </div>
                                               <div style="text-align:right">
                                                    <p class="text-success my-0">{{number_format($order->Fi / $currency)}} {{$currencyName}} </p>
                                                    <p class="fw-bold text-danger my-0">{{number_format($order->Price/$currency)}} {{$currencyName}}</p>
                                               </div>
                                            
                                    </div>
                                    <div class="grid-item">
                                         <button onclick='deletePishKhared({{ $order->SnOrderBYSPishKharid }})' type='button'
                                            class='mt-2' style="float:left; background:transparent">
                                            <i class='fa fa-trash fa-lg me-2' style='color:red'> </i>
                                        </button>
                                        <p class="fw-bold" style="margin-top:120px;"> {{$order->DateOrder}}</p>
                                    </div>
                               </div>
                        </div>
                    
               @endforeach

          </div>
          </div>
          <div class="col-lg-3 px-1">
             <div class="grid-container totalBuy">
                 <div class="grid-item">
                    <p style="display: inline;">مبلغ قابل پرداخت</p>
                    <div id="FinalMoneyFactor" class="c-checkout__to-shipping-price-report--price">{{number_format($allMoney)}}
                        <span>{{$currencyName}}</span>
                    </div>
                </div>
            </div>
         </div>
  </div>

<script>
    function deleteCart(SnOrderBYS) {
        $.ajax({
            type: "POST",
            url: "{{ url('/deleteBYS') }}",
            data: {
                _token: "{{ csrf_token() }}",
                SnOrderBYS: SnOrderBYS
            },
            dataType: "json",
            success: function(msg) {
            $('#order'+SnOrderBYS).css('display','none');
            var buys=parseInt($('#basketCountWeb').text());
            if(buys>0){
                buys=buys-1;
                $('#basketCountWeb').text(buys);
                $('#basketCountWebBottom').text(buys);
                if(buys==0){
                    $('#basketCountWeb').removeClass("headerNotifications1");
                    $('#basketCountWeb').addClass("headerNotifications0");
                    $('#basketCountWebBottom').removeClass("headerNotifications1");
                    $('#basketCountWebBottom').addClass("headerNotifications0");
                    $(".cont").css('display','none');
                }
            }
            },
            error: function(msg) {
                console.log(msg);
            }
        });
    }
    function deletePishKhared(SnOrderBYS) {
        $.ajax({
            type: "get",
            url: "{{ url('/deleteOrderPishKharid') }}",
            data: {
                _token: "{{ csrf_token() }}",
                SnOrderBYS: SnOrderBYS
            },
            dataType: "json",
            success: function(msg) {
            $('#order'+SnOrderBYS).css('display','none');
            },
            error: function(msg) {
                console.log(msg);
            }
        });
    }
    window.onload = function() {
        $(document).on('click', '.updateData', (function() {
            var amountUnit = $(this).val().split('_')[0];
            var productId = $(this).val().split('_')[1];
            var orderId = $('.SnorderBYS').val();
            var showText=$(this).text();
            $.ajax({
                type: "get",
                url: "{{ url('/updateOrderBYS') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    kalaId: productId,
                    amountUnit: amountUnit,
                    orderBYSSn: orderId
                },
                dataType: "json",
                success: function(msg) {
                    $('#updatedBought'+productId).text(showText);
                },
                error: function(msg) {
                    console.log(msg);
                }
            });
        }));

    var input = document.getElementById("txtsearch");
    input.addEventListener("keyup", function(event) {
        if (event.keyCode === 13) {
            if (input === "") {
                event.preventDefault();
                window.location.href = "/home";
            } else {
                event.preventDefault();
                window.location.href = "/searchKala/" + input.value;
            }
        }
    });
}
</script>
@endsection
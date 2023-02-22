@extends('layout.layout')
<style>
    @media only screen and(max-width:570px){
    .c-product__info{
        display: flex;
        justify-content: center;
        margin-top: -40px!important;
    }
}
</style>
@section('content')
<div class="modalBackdrop">
    <div id='unitStuffContainer' class="alert alert-danger" style="max-width: 200px;background-color: #ffffff66; padding: 5px; width: 100%; max-height: 85vh; overflow: auto;">
    </div>
</div>
@php
$overLine=0;
@endphp
<div class="container px-0" style="margin-top: 50px;margin-bottom: 50px">
    <div class="o-page__content">
        <article class="c-product">
            <audio id="ring" src="{{url('/resources/assets/beep.mp3')}}"  controls style="display:none" preload="auto"></audio>
            @if($product)
            @foreach ($product as $pr)
        <section class="c-product__info">
                <div class=" mt-3" style="width:100%">
                    <div class="c-product__add" style="display: flex; justify-content:flex-end; margin-top:40px;">
                        @if($pr->activePishKharid<1)
                            @if($pr->callOnSale==0)
                                @if($pr->AmountExist>0 or $pr->freeExistance>0)
                                    @if($pr->bought=='No')
                                        <button id="noBought{{$pr->GoodSn}}" value="" style="padding-right:10px; font-weight: bold;margin-top:90px;"
                                            onclick="AddQty({{$pr->GoodSn}},this)" class="btn-add-to-cart">انتخاب تعداد
                                            <i class="far fa-shopping-cart text-white ps-3"></i></button>
                                    @endif
                                    </div>
                                    <div id="bought{{$pr->GoodSn}}">
                                    @if($pr->bought=='Yes')
                                        <a class='btn-add-to-cart' value='' id="updatedBought{{ $pr->GoodSn }}"
                                            onclick='UpdateQty({{ $pr->GoodSn }},this,{{ $pr->SnOrderBYS }})'
                                            style='width:auto;text-align: center;    padding-right: 10px; background-color: #51ef39; font-weight: bold;'
                                            class='updateData btn-add-to-cart'>{{ $pr->Amount / $pr->PackAmount }}{{ $pr->secondUnit }}
                                            معادل
                                            {{ $pr->Amount / 1  }}{{ $pr->secondUnit}}
                                        </a>
                                    @endif
                                @else
                                @endif
                            @else
                            <button  value="" style="padding-right:8px; background-color:#e40707; font-weight: bold; display: inline;" class="btn-add-to-cart">برای خرید تماس بگیرید
                                <i class="far fa-shopping-cart text-white ps-2"></i>
                            </button>
                            @endif
                            @else
                            @if($pr->preBought!='Yes')
                                <div class="c-product__add mt-0" id="beforeBought{{$pr->GoodSn}}">
                                    <button id="preBought{{$pr->GoodSn}}" value="" onclick="AddQtyPishKharid({{$pr->GoodSn}},this)" style="padding-right:10px;background-color:rgb(244, 8, 67);" class="btn-add-to-cart">پیش خرید &nbsp; <i class="fas fa-shopping-basket" style="color:rgb(10, 9, 9);font-size:18px;"></i></button>
                                </div>
                            @else
                                <div class="c-product__add mt-0">
                                    <a class='btn-add-to-cart' value='' id="updatedPishKharid{{$pr->GoodSn}}"
                                        onclick='updatePishKharid({{ $pr->GoodSn }},this,{{ $pr->SnOrderBYS }})'
                                            style='width:auto;text-align: center;    padding-right: 10px;
                                            background-color: #6e3f06;
                                            font-weight: bold;'
                                            class='updateData btn-add-to-cart'>{{ $pr->Amount / $pr->PackAmount }}{{ $pr->secondUnit }}
                                            معادل
                                            {{ $pr->Amount / 1  }}{{ $pr->UName}}
                                    </a>
                                </div>
                            @endif
                        @endif
                    <br/>
                    </div>
                    @if($pr->AmountExist>0)
                        @if($pr->activeTakhfifPercent)
                            @php
                                $overLine=0;
                                $percentResult=0;
                                if($pr->Price4>0 and $pr->Price3>0){
                                    $showTakhfifPercent="none";
                                    $percentResult=round((($pr->Price4-$pr->Price3)*100)/$pr->Price4);
                                    }else{
                                        $percentResult=0;
                                    }
                                    if($percentResult==0 ){
                                        $showTakhfifPercent="none";
                                    }
                                    if($pr->activeTakhfifPercent==1 and $percentResult!=0){
                                        $showTakhfifPercent="flex";
                                        $overLine=1;
                                    }else{
                                        $showTakhfifPercent="none";
                                    }
                            @endphp
                            <span  style='display:{{$showTakhfifPercent}}'> &nbsp; {{$percentResult}}% <b>تخفیف</b></span>
                        @endif
                        @if($overLine==1)
                            <div class="c-price original" id="SellPrice_str" ><del>{{number_format($pr->Price4/$currency)}}</del> {{$currencyName}}</div>
                        @endif
                    @endif

                    @if($pr->AmountExist>0 or $pr->activePishKharid>0 or $pr->freeExistance>0)
                    <div class="c-price original" id="SellPrice_str" style="color: #39ae00;">{{number_format($pr->Price3/$currency)}} {{$currencyName}}</div>
                    @else
                        @if($pr->requested==0)
                            <span class="prikalaGroupPricece text-end fw-bold" id="request{{$pr->GoodSn}}"> <button value="0" id="preButton{{$pr->GoodSn}}"  onclick="requestProduct({{Session::get('psn')}},{{$pr->GoodSn}})" style="padding-right:10px;background-color:rgb(249, 6, 6);" class="btn-add-to-cart">خبرم کنید <i class="fas fa-bell"></i></button></span>
                        @else
                            <span class="prikalaGroupPricece text-end fw-bold" id="norequest{{$pr->GoodSn}}"> <button value="1" id="afterButton{{$pr->GoodSn}}"  style="padding-right:10px;  border: 2px solid rgb(251, 10, 10);
                                background-color: white;
                                color: rgb(232, 20, 20);
                                font-size: 16px;
                                cursor: pointer;" onclick="cancelRequestKala({{Session::get('psn')}},{{$pr->GoodSn}})" class="btn-add-to-cart">اعلام شد</button>
                            </span>
                        @endif
                    @endif
                    <br>
                    <h1 class="c-product__title border-bottom-10">
                        <span class="fa-title fw-bold float-start"> {{$pr->GoodName}} </span>
                    </h1><hr>
                <div class="c-product__attributes">
                    <div class="c-product__right">
                        <div class="c-product__directory p-0 m-0">
                             <span class="p-0 m-0 fw-bold"> گروه اصلی :</span> <a href="{{url('listKala/groupId/'.$mainGroupId)}}" class="btn-link-spoiler">{{$groupName}}</a>
                        </div>
                        <div class="c-product__params">
                            <ul data-title="توضیحات:">

                                <li><span>کد محصول: {{$pr->GoodCde}}</span>  <span>{{$pr->descKala}}</span> </li>
                            </ul>
                        </div>
                        <div class='headline two-headline'><h4>کالاهای مشابه</h4></div>
                        <div class="c-listing">
                            <div class="containerAli">
                                <div class="rowAli">
                                @foreach($assameKalas as $asame)
                                <div class="col-50 medium-25 mb-1">
                                    <div class="c-product-box py-1 c-promotion-box p-4">
                                        <a href="{{url('/descKala/'.$mainGroupId.'/itemCode/'.$asame->assameId)}}" class="c-product-box__img c-promotion-box__image">
                                            <img style="width: 50%;hieght:50%;" src="{{ url('/resources/assets/images/kala/' . $asame->assameId . '_1.jpg') }}">
                                        </a>
                                        <div class="c-product-box__content">
                                            <a href="{{url('/descKala/'.$mainGroupId.'/itemCode/'.$asame->assameId)}}" class="title"
                                                style="">{{ $asame->GoodName }}</a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="c-product__gallery" style="display: flex; align-items:center;">
                <button style="background: transparent; font-size: 25px; z-index:1000;  position: absolute; top: 10%; left: 82%;" id="hear{{$pr->GoodSn}}"
                    onclick="SetFavoriteGood({{$pr->GoodSn}})" @if($pr->favorite=='YES')  class="fas fa-heart text-danger kala-desc-fav" @else  class="far fa-heart" @endif ></button>
                    <img style="width:250px; height:250px;" class="img-responsive" alt="" src="{{ url('/resources/assets/images/kala/' . $pr->GoodSn . '_1.jpg') }}" />
            </section>
            @endforeach
            @else
            توضیحاتی ندارد
            @endif
        </article>
    </div>
</div>

<script>
    function openNav() {
        document.getElementById("mySidenav").style.width = "250px";
    }

    function closeNav() {
        document.getElementById("mySidenav").style.width = "0";
    }

    function goBack() {
        window.history.back();
    }

function SetFavoriteGood(snGood) {
$.ajax({
        type: "get",
        url: "{{ url('/setFavorite')}}",
        data: {_token: "{{ csrf_token() }}", goodSn: snGood },
        dataType: "json",
        success: function (msg) {
            var goodSn=msg.split('_')[1];
            var flag=msg.split('_')[0];
            if(flag=="added"){
             $('#hear'+goodSn).addClass('fas fa-heart text-danger');
            }
            if(flag=="deleted"){
             $('#hear'+goodSn).removeClass('fas fa-heart text-danger');
             $('#hear'+goodSn).addClass('far fa-heart');
            }
            // alert('added favorit'+msg);
        },
        error: function (msg) {
            console.log(msg);
        }
    });
}
</script>
@endsection

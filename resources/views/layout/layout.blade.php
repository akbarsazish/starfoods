<!DOCTYPE html>
<html dir="rtl">
<head>
<style>
    </style>
    <meta charset="UTF-8">
    <meta name="author" content="Ali Akbar Sazish">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta http-equiv="ScreenOrientation" content="autoRotate:disabled">
    <title> استار فود</title>
    <link rel="icon" type="image/png" href="{{ url('resources/assets/images/part.png')}}">
    <link rel="stylesheet" href="{{ url('/resources/assets/css/bootstrap.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{url('/resources/assets/jquery-ui-1.12.1/jquery-ui-1.12.1/jquery-ui.min.css')}}"/>
    <link rel="stylesheet" href="{{ url('/resources/assets/fontawesome/css/all.min.css')}}">
    <link rel="stylesheet" href="{{ url('/resources/assets/css/main.css')}}">
    <link rel="stylesheet" href="{{ url('/resources/assets/css/mediaq.css')}}">
    <link rel="stylesheet" href="{{ url('/resources/assets/css/framework7.bundle.min.css')}}">
    <link rel="stylesheet" href="{{url('/resources/assets/js/persianDatepicker-master/css/persianDatepicker-default.css')}}" />
    <link rel="stylesheet" href="{{ url('/resources/assets/js/jquery-ui.css')}}"/>
    <script src="{{ url('resources/assets/js/jquery.min.js')}}"></script>

        <script src="{{ url('/resources/assets/js/jquery-ui.min.js')}}"></script>
        <meta name="theme-color" content="#6777ef"/>
        <link rel="apple-touch-icon" href="{{ asset('logo.PNG') }}">
        <link rel="manifest" href="{{ asset('/manifest.json') }}">
        
</head>
<body>
<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true ");
    header("Access-Control-Allow-Methods: OPTIONS, GET, POST");
    header("Access-Control-Allow-Headers: Content-Type, Depth, User-Agent, X-File-Size, X-Requested-With, If-Modified-Since, X-File-Name, Cache-Control");
?>
    <div class="bottomNav">
        <a class="footerOptions bottomNav__item"  href="{{url('/allGroups')}}"><i class="fal fa-th"> </i> دسته بندی</a>
        <a class="footerOptions bottomNav__item"  href="{{url('/home')}}"> <i class="far fa-home"></i> صفحه اصلی  </a>
        <a class="footerOptions bottomNav__item"  href="{{url('/carts')}}">
        <i class="fal fa-shopping-cart"></i>
            <span id="basketCountWebBottom" @if($countBoughtAghlam < 1) class="footerBage danger" @else  class="headerNotifications1" @endif>@if($countBoughtAghlam>0){{$countBoughtAghlam}}@else 0 @endif</span>
       سبد خرید </a>
    </div>
    <div class="menuBackdrop" style="z-index:99"></div>
    <header>
        <section class="topMenu">
            <section class="top-head container">
                <div class="right-head" >
                    <div id="mySidenav" class="sidenav" style="width:0px;overflow-x:hidden;margin-left:100px;padding-right:0;">
                        <!-- <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a> -->
                        <a href="{{url('/home')}}" class="sidenav__header">
                            <img width="166px" src="{{ url('/resources/assets/images/logomenu.png') }}" /> 
                        </a>
                        <button onclick="closeNav()" class="closeMenuButton"> <i class="fal fa-times fa-lg"></i> </button>
                        <a> <i class="fal fa-user fa-lg"></i> {{Session::get('username')}} </a>
                        <a href="{{url('/profile')}}"> <i class="fal fa-info-square fa-lg"></i> وضعیت من  </a>
                        <a href="{{url('/allGroups')}}"> <i class="fal fa-th-large fa-lg"></i> دسته بندی کالا </a>
                        <a href="{{url('/listFavorits')}}"><i class="fal fa-heart-square fa-lg"></i> مورد علاقه ها  </a>
                        <!-- <a href="{{url('/listPreBuys')}}"><i class="fal fa-shopping-cart fa-lg"></i> پیش خرید ها  </a> -->
                        <a href="{{url('/carts')}}"> <i class="fal fa-shopping-cart fa-lg"> </i> سبد خرید </a>
						<!-- <a href="{{url('/bagCash')}}"> <i class="fal fa-briefcase fa-lg"> </i> کیف تخفیف</a> -->
                        <a href="{{url('/messageList')}}"> <i class="far fa-envelope fa-lg"></i>   پیام ها 
                        <span id="replayCountWeb" @if($countNewReplayMessage<1) class="headerNotifications0 translate-middle badge rounded-pill bg-dark" @else class="headerNotifications1 position-absolute start-200 translate-middle badge rounded-pill bg-dark" @endif>@if($countNewReplayMessage>0){{$countNewReplayMessage}}@else 0 @endif</span>
                        </a>
                        <a href="{{url('/constact')}}"> <i class="fal fa-phone-square fa-lg"> </i> تماس با ما  </a>
					   <a href="{{url('/saveEarth/0')}}"> <i class="fas fa-gamepad fa-lg"> </i> نجات زمین </a>
					   <a href="{{url('/saveEarth/1')}}"> <i class="fas fa-tower fa-lg"> </i>  بازی با رنگ  </a>
					   <!-- <a href="{{url('/showLottery')}}"> <i class="fa fa-ticket fa-lg"></i> شانس آزمایی </a> -->

                        @if($exitAllowance==1)
                        <a href="{{url('/logout')}}" onclick="logout()"><i class="fal fa-sign-out fa-lg "></i> خروج </a>
                        @else
                        @endif
                        <div class="sidenav__socialMedia"><a href="https://instagram.com/{{$instagram}}"><i class="fab fa-instagram"></i></a>
                            <a href="https://telegram.me/{{$telegram}}"> <i class="fab fa-telegram"></i> </a>
                            <a href="https://wa.me/{{$whatsapp}}"> <i class="fab fa-whatsapp"></i> </a>
                        </div>
                    </div>

                    <div id="MenuBack" style="font-size:25px;cursor:pointer;color:white;display:flex;justify-content:flex-start;text-align:right; width:40px">
                        <i onclick="goBack()" style="display:none;" class="fas fa-chevron-right" id="toPrevious"></i>
                    </div>
                    <div style="font-size:25px;cursor:pointer;color:white;display:flex;justify-content:flex-end;text-align:right;width:25px; margin-left: 30px" onclick="openNav()">
                        <i  class="fas fa-bars"></i>
                    </div>
                   
                     <i class="fa fa-search" id="searchIcon" style="font-size:22px; color:#fff;"></i>
                    
                     <input class="form-control w-100 publicSearch" type="text" id="txtsearch" placeholder="چی لازم داری ؟  ..."> 
                    
                    
                </div>
                </div>
                <div id="leftPart">
                        <a class="headerOptions headerNew" href="{{url('/bagCash')}}"> {{(int)$bonusResult}} <i class='fas fa-star' style='font-size:22px;color:#fff; margin-left:5px; cursor:pointer'> </i> </a>
                        <a class="headerOptions headerNew" href="{{url('/wallet')}}"> {{number_format((int)ceil($takhfifMoney))}} <i class='fas fa-wallet' style='font-size:22px;color:#fff; cursor:pointer'></i> </a>
                        <a class="headerOptions" href="{{url('/carts')}}">  <i class="far fa-shopping-cart"></i>
                            <span id="basketCountWeb"  @if($countBoughtAghlam<1) class="headerNotifications0" @else  class="headerNotifications1" @endif>@if($countBoughtAghlam>0){{$countBoughtAghlam}}@else 0 @endif</span>
                        </a>
                    </div>
            </section>
        </section>
    </header>
    @yield('content')
 


<script>
	var currentUrl = window.location.pathname;
	if (currentUrl != '\/home' && currentUrl != '\/') {
		document.querySelector("#toPrevious").style.display = "initial";
	}
    function goBack() {
        window.history.back();
    }
    function openNav() {
        document.getElementById("mySidenav").style.width = "222px";
    }

    function closeNav() {
        document.getElementById("mySidenav").style.width = "0";
    }
    if (!navigator.serviceWorker.controller) {
        navigator.serviceWorker.register("/sw.js").then(function (reg) {
            console.log("Service worker has been registered for scope: " + reg.scope);
        });
    }else{
    }

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


    $("#searchIcon").on("click", ()=>{
        $("#txtsearch").css({"display":"block", "width": "100%"});
        $("#searchIcon").css("display", "none");
    })   

if (window.matchMedia('(max-width: 920px)').matches){
    $("#searchIcon").on("click", ()=>{
        $(".headerNew").css("display", "none");
    })   
}

</script>

<script src="{{url('/resources/assets/js/persianDatepicker-master/js/jquery-1.10.1.min.js')}}"></script>
<script src="{{url('/resources/assets/js/persianDatepicker-master/js/persianDatepicker.min.js')}}"></script> 

<link rel="apple-touch-icon" href="{{ asset('logo.PNG')}}">
<script src="{{ url('/resources/assets/js/script.js')}}"></script>
<script src="{{ url('resources/assets/js/jquery.session.js')}}"></script>
<script defer src="{{ url('/resources/assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('/sw.js') }}"></script> 


<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</body>
</html>

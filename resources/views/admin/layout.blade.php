<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title> Admin Panel </title>

    <link rel="stylesheet" href="{{ url('/resources/assets/css/bootstrap.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{ url('/resources/assets/fontawesome/css/fontawesome.min.css')}}">
    <link rel="stylesheet" href="{{ url('/resources/assets/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ url('/resources/assets/css/bootstrap-grid.rtl.min.css') }}">
    <link rel="stylesheet" href="{{ url('/resources/assets/vendor/swiper/css/swiper.min.css') }}">
    <link rel="stylesheet" href="{{ url('/resources/assets/slicknav/slicknav.min.css')}}">
    <link rel="stylesheet" href="{{ url('/resources/assets/css/mainAdmin.css')}}">
    <link rel="stylesheet" href="{{ url('/resources/assets/css/mediaq.css')}}">
    <link rel="stylesheet" href="{{ url('/resources/assets/css/admin.framework7.bundle.min.css')}}">
    <link rel="stylesheet" href="{{ url('/resources/assets/css/bootstrap-utilities.rtl.min.css')}}">
    <link rel="stylesheet" href="{{url('/resources/assets/js/persianDatepicker-master/css/persianDatepicker-default.css')}}" />
	 <link rel="stylesheet" href="{{ url('/resources/assets/css/jquery-ui.css')}}">
    <script type="text/javascript" src="{{ url('/resources/assets/js/jquery.min.js')}}"></script>
  
    <script src="{{ url('/resources/assets/js/jquery-ui.min.js')}}"></script>

       <script src="{{url('/resources/assets/js/persianDatepicker-master/js/jquery-1.10.1.min.js')}}"></script>  
      <script src="{{url('/resources/assets/js/persianDatepicker-master/js/persianDatepicker.min.js')}}"></script> 
      <script src="{{ url('/resources/assets/js/jquery-ui.min.js')}}"></script>
	
    <script src="{{url('/resources/assets/vendor/swiper/js/swiper.min.js')}}"></script>
    <link rel="icon" type="image/png" href="{{ url('/resources/assets/images/part.png')}}">
</head>
<body>
    <div class="menuBackdrop"></div>
        <section class="topMenu">
            <section class="top-head container">
                <div class="right-head" >
                    <div id="mySidenav" class="sidenav" style="width:0px;overflow-x:hidden;margin-left:100px;padding-right:0;">
                        <!-- <a href="javascript:void(0)"  class="closebtn" onclick="closeNav()">&times;</a> -->
                    <a href="{{url('/home')}}" class="sidenav__header" style="background: linear-gradient(#3ccc7a, #034620); text-align:center;">
                            <img width="166px" src="{{ url('/resources/assets/images/logomenu4.png') }}"/>
                        </a>
                        <button onclick="closeNav()" class="closeMenuButton"><i class="fad fa-times"></i></button>
                        <div id='cssmenu' style="direction:rtl;">
                            <ul>
                               <li class='has-sub'><a class="mySidenav__item" ><span><i class="fa-solid fa-user fa-lg" style="color:#fff;"></i> &nbsp;{{Session::get('adminName')}} </span></a></li>
                               <li class='fw-bold'><a class="mySidenav__item" href="{{url('/dashboardAdmin')}}"><span><i class="fa-solid fa-dashboard fa-lg" style="color:#fff;"></i>&nbsp;&nbsp; داشبورد </span></a>
                               @if(hasPermission(Session::get("adminId"),"homePage") > -1 or hasPermission(Session::get("adminId"),"karbaran") > -1 or hasPermission(Session::get("adminId"),"specialSetting") > -1)
                               <li class='has-sub'><a class="mySidenav__item" href="{{url('/dashboardAdmin')}}"><span><i class="fa-solid fa-chart-user fa-lg " style="color:#fff"></i>&nbsp;&nbsp;  مدیریت وب </span></a>
                                  <ul>
                                    @if(hasPermission(Session::get("adminId"),"homePage") > -1)
                                    <li><a class="mySidenav__item" href="{{url('/controlMainPage')}}">&nbsp;&nbsp;<i class="fa-regular fa-home fa-lg" style="margin-right: 5%; color:#597c9d"></i>&nbsp;&nbsp; صفحه اصلی </a></li>
                                    @endif
                                    @if(hasPermission(Session::get("adminId"),"karbaran") > -1)
                                    <li><a class="mySidenav__item" href="{{url('/listKarbaran')}}">&nbsp;&nbsp;<i class="fa-regular fa-user fa-lg" style="margin-right: 5%; color:#597c9d"></i> &nbsp;&nbsp; کاربران </a></li>
                                    @endif
                                    <li><a class="mySidenav__item" href="{{url('#')}}">&nbsp;&nbsp;<i class="fa-regular fa-chart-mixed fa-lg" style="margin-right: 5%; color:#597c9d"></i>&nbsp;&nbsp; گزارشات </a></li>
                                    @if(hasPermission(Session::get("adminId"),"specialSetting") > -1)
                                    <li><a class="mySidenav__item" href="{{url('/webSpecialSettings')}}" >&nbsp;&nbsp;<i class="fa-regular fa-cog fa-lg" style="margin-right: 5%; color:#597c9d"></i>&nbsp;&nbsp; تنظیمات اختصاصی </a></li>
									  <li><a class="mySidenav__item" href="{{url('/baseBonusSettings')}}" >&nbsp;&nbsp;<i class="fa-regular fa-cog fa-lg" style="margin-right: 5%; color:#597c9d"></i>&nbsp;&nbsp; تنظیمات معیار های امتیاز  </a></li>
                                    @endif
                                  </ul>
                               </li>
                               @endif
                               <li class='fw-bold'><a class="mySidenav__item" href="{{url('/salesOrder')}}"><span><i class="fa fa-shopping-cart" aria-hidden="true" style="color:#fff"></i> &nbsp;&nbsp; سفارشات فروش </span></a>
                               @if(hasPermission(Session::get("adminId"),"kalaList") > -1 or hasPermission(Session::get("adminId"),"kalaRequests") > -1 or hasPermission(Session::get("adminId"),"fastKala") > -1 or hasPermission(Session::get("adminId"),"pishKharid") > -1 or hasPermission(Session::get("adminId"),"brand") > -1 or hasPermission(Session::get("adminId"),"alertedKala") > -1 or hasPermission(Session::get("adminId"),"listGroups") > -1)
                               <li class='has-sub'><a class="mySidenav__item"   href='javascript:;' onClick="openKalaStuff()"><span><i class="fa-solid fa-dolly-flatbed-alt fa-lg" style="color:#fff;"></i>&nbsp;&nbsp; کالا ها</span></a>
                                  <ul>
                                  @if(hasPermission(Session::get("adminId"),"kalaList") > -1)
                                     <li><a class="mySidenav__item" href="{{url('/listKala')}}" >&nbsp;&nbsp;<i class="fa-regular fa-list-radio fa-lg" style="margin-right: 5%; color:#597c9d"></i>&nbsp;&nbsp; لیست کالا ها</a></li>
                                  @endif
                                  @if(hasPermission(Session::get("adminId"),"kalaRequests") > -1)
                                     <li><a class="mySidenav__item" href="{{url('/requestedProducts')}}" >&nbsp;&nbsp;<i class="fa-regular fa-list-radio fa-lg" style="margin-right: 5%; color:#597c9d"></i>&nbsp;&nbsp; درخواستی های مشتریان</a></li>
                                  @endif
                                  @if(hasPermission(Session::get("adminId"),"fastKala") > -1)
                                     <li><a class="mySidenav__item" href="{{url('/changePictures')}}" >&nbsp;&nbsp;<i class="fa-regular fa-list-radio fa-lg" style="margin-right: 5%; color:#597c9d"></i>&nbsp;&nbsp; فست کالا</a></li>
                                  @endif
                                  @if(hasPermission(Session::get("adminId"),"pishKharid") > -1)
                                     <li><a class="mySidenav__item" href="{{url('/prebuyFactors')}}" >&nbsp;&nbsp;<i class="fa-regular fa-list-radio fa-lg" style="margin-right: 5%; color:#597c9d"></i>&nbsp;&nbsp; پیش خرید</a></li>
                                  @endif
                                  @if(hasPermission(Session::get("adminId"),"brand") > -1)
                                     <li><a class="mySidenav__item" href="{{url('/brands')}}" >&nbsp;&nbsp;<i class="fa-regular fa-list-radio fa-lg" style="margin-right: 5%; color:#597c9d"></i>&nbsp;&nbsp; برندها</a></li>
                                  @endif
                                  @if(hasPermission(Session::get("adminId"),"alertedKala") > -1)
                                     <li><a class="mySidenav__item" href="{{url('/alarmedKalas')}}" >&nbsp;&nbsp;<i class="fa-regular fa-list-radio fa-lg" style="margin-right: 5%; color:#597c9d"></i>&nbsp;&nbsp; لیست شامل هشدار</a></li>
                                  @endif
                                  @if(hasPermission(Session::get("adminId"),"listGroups") > -1)
                                     <li class='last'><a class="mySidenav__item" href="{{url('/listGroup')}}" >&nbsp;&nbsp;<i class="fa-regular fa-layer-group fa-lg" style="margin-right: 5%; color:#597c9d"></i>&nbsp;&nbsp; دسته بندی کالا ها</a></li>
                                  @endif
                                    </ul>
                               </li>
                               @endif
                               @if(hasPermission(Session::get("adminId"),"customers") > -1 or hasPermission(Session::get("adminId"),"officials") > -1)
                                <li class='has-sub'><a class="mySidenav__item"   href='javascript:;' onClick="openCustomerStuff()"><span><i class="fa-solid fa-people-simple fa-lg" style="color:#fff;"></i>&nbsp;&nbsp; اشخاص</span></a>
                                    <ul>
                                    @if(hasPermission(Session::get("adminId"),"customers") > -1)
                                    <li><a class="mySidenav__item" href="{{url('/listCustomers')}}"><i class="fa-light fa-users fa-lg" style="margin-right: 5%"></i>&nbsp;&nbsp; مشتریان</a></li>
									<li><a class="mySidenav__item" href="{{url('/lotteryResult')}}"><i class="fa-light fa-briefcase fa-lg" style="margin-right: 5%"></i>&nbsp;&nbsp; نتایج لاتری </a></li>
                                    @endif
                                    @if(hasPermission(Session::get("adminId"),"officials") > -1)
                                    <li><a class="mySidenav__item" href="{{url('/customerList')}}"><i class="fa-light fa-user fa-lg" style="margin-right: 5%"></i>&nbsp;&nbsp; اشخاص رسمی</a></li>
                                    <li><a class="mySidenav__item" href="{{url('/payedOnline')}}"><i class="fa-light fa-credit-card fa-lg" style="margin-right: 5%"></i>&nbsp;&nbsp; پرداخت آنلاین </a></li>
                                    @endif
                                </ul>
                                </li>
                                @endif
                                @if(hasPermission(Session::get("adminId"),"messages") > -1)
                                <li class='last'><a class="mySidenav__item" href="{{url('/alarms')}}"><span><i class="far fa-envelope fa-lg" style="color:#fff;" ></i>&nbsp;&nbsp; پیامها  </span></a>
                                    <ul>
                                        <li><a class="mySidenav__item" href="{{url('/messages')}}"><i class="far fa-envelope"></i><span @if($countNewMessages < 1 ) class="headerNotifications0" @else  class="headerNotifications1" @endif id="countNewMessages" style="border-radius: 50%">@if($countNewMessages){{$countNewMessages}} @else 0 @endif</span></a></li>
                                    </ul>
                                </li>
						        <li class='last'><a class="mySidenav__item" href="{{url('/playerList')}}"><span><i class="far fa-gamepad fa-lg" style="color:#fff;" ></i>&nbsp;&nbsp;  بازی ها  </span></a>
                                    <ul>
                                        <li><a class="mySidenav__item" href="{{url('/gamerList')}}"><i class="far fa-chess-board fa-lg"></i> گیمر لیست </a></li>
                                        <li><a class="mySidenav__item" href="{{url('/lotteryResult')}}"><i class="fa-light fa-briefcase fa-lg" ></i> نتایج لاتری </a></li>
                                    </ul>
                                </li>
                                @endif
                                <li class='last'><a class="mySidenav__item" href="{{url('/loginAdmin')}}" onclick="logout()"><span><i class="fa-solid fa-sign-out fa-lg" style="color:#fff;" ></i>&nbsp;&nbsp; خروج </span></a></li>
                            </ul>
                           </div>
                    </div>
                    <div id="MenuBack" style="font-size:25px;cursor:pointer;color:white;display:flex;justify-content:flex-start;text-align:right;width:34px">
                        <i onclick="goBack()" class="fas fa-chevron-right"></i>
                    </div>
                    <div style="font-size:25px;cursor:pointer;color:white;display:flex;justify-content:flex-start;text-align:right;width:25px; margin-right: 15px; margin-left: 50px" onclick="openNav()">
                        <i class="fas fa-bars"></i>
                    </div>
                </div>
                </div>
                    <div class="left-head">
                        <div class="devider"></div>
                        <a class="headerOptions" style="font-family: IRANSans !important;" href="{{url('/messages')}}">
                            <i class="far fa-envelope"></i>
                            <span @if($countNewMessages < 1) class="headerNotifications0" @else  class="headerNotifications1" @endif id="countNewMessages" > @if($countNewMessages>0){{$countNewMessages}} @else 0 @endif</span>
                        </a>
                    </div>
            </section>
        </section>
    </header>
    @yield('content')

    
    <script src="{{url('/resources/assets/slicknav/jquery.slicknav.min.js')}}"></script>
 
    <script src="{{url('/resources/assets/vendor/jquery.countdown.min.js')}}"></script>
    <script defer src="{{url('/resources/assets/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{url('/resources/assets/js/sweetalert.min.js')}}"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

 
    <script src="{{url('/resources/assets/vendor/persianumber.min.js')}}"></script>
    <script src="{{url('/resources/assets/vendor/elevatezoom.js')}}"></script>
    <script src="{{url('/resources/assets/js/script.js') }}"></script>
    <script src="{{url('/resources/assets/js/bootstrap.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>

     <script>
            function goBack() {
                    window.history.back();
                }
            function openNav() {
                document.getElementById("mySidenav").style.width = "260px";
            }
            function closeNav() {
                document.getElementById("mySidenav").style.width = "0";
            }
     </script>
</body>
</html>

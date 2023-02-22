@extends('layout.layout');
@section('content');
<style>
.list-group-numbered>li::before {
    font-size:16px;
    color:red;
    font-weight:bold;
}
.list-group-item {
    font-size:16px;
    color:red;
}


</style>

<link rel="stylesheet" type="text/css" href="{{url('/resources/assets/lottery/lotteryStyle.css')}}" />
<div class="container" style="margin-top:40px;">

    <div class="container">
            <div class="row text-center">
                <div class="col-lg-12 text-center">
                    <div class="five-pointed-star">
                          <span class="starContent">  امتیاز شما {{$allBonus}} </span> 
                    </div>
                    <div class="row mt-2">
                        <div class="col-lg-12 text-end p-2">
                            <div class="useStar">
                                <ol class="list-group list-group-numbered pe-1">
                                    <li class="list-group-item"> استفاده از گردونه شانس 500 امتیاز <button id="useLuckyWheel" class="btn btn-sm btn-primary float-start p-1" style="font-size:12px;"> استفاده می کنم  </button>     
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          
    </div>

  <div class="row mt-3" id="luckyWheel" style="display:none;">
   <div id="jquery-script-menu" style="marging-top:50px;">
      <div class="mainbox" id="mainbox">
        <div class="box boxBorder" id="box">
          <div class="box1">
            @if(strlen(trim($products[0]->firstPrize))>0)
              <span class="font span1"><b> {{$products[0]->firstPrize}}</b></span>
            @endif
            @if(strlen(trim($products[0]->secondPrize))>0)
              <span class="font span2"><b>{{$products[0]->secondPrize}}</b></span>
            @endif
            @if(strlen(trim($products[0]->thirdPrize))>0)
              <span class="font span3"><b>  {{$products[0]->thirdPrize}} </b></span>
            @endif
            @if(strlen(trim($products[0]->fourthPrize))>0)
              <span class="font span4"><b> {{$products[0]->fourthPrize}}</b></span>
            @endif
            @if(strlen(trim($products[0]->fifthPrize))>0)
              <span class="font span5"> <b> {{$products[0]->fifthPrize}} </b> </span>
            @endif
            @if(strlen(trim($products[0]->fourteenthPrize))>0)
              <span class="font span6"> <b> {{$products[0]->fourteenthPrize}} </b> </span>
            @endif
            @if(strlen(trim($products[0]->fifteenthPrize))>0)
              <span class="font span7"> <b> {{$products[0]->fifteenthPrize}} </b> </span>
            @endif
            @if(strlen(trim($products[0]->sixteenthPrize))>0)
              <span class="font span1"> <b> {{$products[0]->sixteenthPrize}} </b> </span>
            @endif
          </div>
          <div class="box2">
            @if(strlen(trim($products[0]->sixthPrize))>0)
              <span class="font span1"><b>{{$products[0]->sixthPrize}}</b></span>
            @endif
            @if(strlen(trim($products[0]->seventhPrize))>0)
              <span class="font span2"><b>{{$products[0]->seventhPrize}}</b> </span>
            @endif
            @if(strlen(trim($products[0]->eightthPrize))>0)
              <span class="font span3"> <b>{{$products[0]->eightthPrize}}</b></span>
            @endif
            @if(strlen(trim($products[0]->ninethPrize))>0)
              <span class="font span4"> <b>{{$products[0]->ninethPrize}}</b></span>
            @endif
            @if(strlen(trim($products[0]->teenthPrize))>0)
              <span class="font span5"> <b> {{$products[0]->teenthPrize}}</b> </span>
            @endif
            @if(strlen(trim($products[0]->eleventhPrize))>0)
              <span class="font span6"> <b> {{$products[0]->eleventhPrize}}</b> </span>
            @endif
            @if(strlen(trim($products[0]->twelvthPrize))>0)
              <span class="font span7"> <b> {{$products[0]->twelvthPrize}}</b> </span>
            @endif
            @if(strlen(trim($products[0]->therteenthPrize))>0)
              <span class="font span8"> <b> {{$products[0]->therteenthPrize}}</b> </span>
            @endif
          </div>
        </div>
        <button class="spin" id="spinnerBtn" @if($allBonus > $lotteryMinBonus) onclick="spin()" @endif>  چرخش   </button>
      </div>
      <audio controls="controls" id="applause" src="{{url('/resources/assets/lottery/applause.mp3')}}" type="audio/mp3"></audio>
      <audio controls="controls" id="wheel" src="{{url('/resources/assets/lottery/wheel.mp3')}}" type="audio/mp3"></audio>
    </div>
  </div>

  <div class="lotteryInformation my-5 text-center">
         <!-- <div class="lotteryDesc">
            <span class="showEmtyaz mt-2">
              {{$allBonus}} امتیاز
            </span> 
              <b> امتیاز شما </b>
          </div> -->
          <div class="lotteryDesc">
                <ul class="lists p-2">
                  <span class="text-danger" style="margin-bottom:10px; float:right;">  قوانین و مقررات </span> <br> <br>
                  <li class="itemList"> وقتی امتیاز شما بالای {{$lotteryMinBonus}} شد چرخش فعال میگردد</li>
                  <li class="itemList"> روی دکمه چرخش کلید نماید.</li>
                  <li class="itemList"> هر {{$lotteryMinBonus}} امتیاز یک شانس</li>
                  <li class="itemList"> شانش خویش را بیازمایید. </li>
                  <li class="itemList"> جایزه خویش را دریافت نمایید </li>
                </ul>
          </div>
     </div>

    <!-- <a class="walletSection__btn walletSection__btn--show" href="">مشاهده گردش</a>
    <div class="bottomBtn">
        <a href="/BagCashIns" class="btnWrapper">
            <div class="walletSection__btn walletSection__btn--green">
                <i class="far fa-plus"></i>
            </div>
            <span>افزایش</span>
        </a>
        <a href="/BagCashTransfer" class="btnWrapper">
            <div class="walletSection__btn walletSection__btn--green">
                <i class="fas fa-exchange-alt"></i>
            </div>
            <span>انتقال</span>
        </a>
    </div> -->
</section>
<script>
            function openNav() {
                document.getElementById("mySidenav").style.width = "250px";
            }

            function closeNav() {
                const backdrop = document.querySelector('.menuBackdrop');
                document.getElementById("mySidenav").style.width = "0";
                backdrop.classList.remove('show');
            }


//برای لاتری استفاده می شوند.

  function shuffle(array) {
    var currentIndex = array.length,
      randomIndex;
  
    // While there remain elements to shuffle...
    while (0 !== currentIndex) {
      // Pick a remaining element...
      randomIndex = Math.floor(Math.random() * currentIndex);
      currentIndex--;
  
      // And swap it with the current element.
      [array[currentIndex], array[randomIndex]] = [
        array[randomIndex],
        array[currentIndex],
      ];
    }
    return array;
  }

  function spin() {
    // Play the sound
    wheel.play();
    // Inisialisasi variabel
    const box = document.getElementById("box");
    const element = document.getElementById("mainbox");
    let SelectedItem = "";

    // Shuffle 450 karena class box1 sudah ditambah 90 derajat diawal. minus 40 per item agar posisi panah pas ditengah.
    // Setiap item memiliki 12.5% kemenangan kecuali item sepeda yang hanya memiliki sekitar 4% peluang untuk menang.
    // Item berupa ipad dan samsung tab tidak akan pernah menang.
    // let Sepeda = shuffle([2210]); //Kemungkinan : 33% atau 1/3

    let FirstPrize= shuffle([(0)]) ;
    let secondPrize= shuffle([(0)]) ;
    let thirdPrize= shuffle([(0)]) ;
    let fourthPrize= shuffle([(0)]) ;
    let fifthPrize= shuffle([(0)]) ;
    let sixthPrize= shuffle([(0)]) ;
    let seventhPrize= shuffle([(0)]) ;
    let eightPrize= shuffle([(0)]) ;
    let ninthPrize= shuffle([(0)]) ;
    let teenthPrize= shuffle([(0)]) ;
    let eleventhPrize= shuffle([(0)]) ;
    let twelvthPrize= shuffle([(0)]) ;
    let therteenthPrize= shuffle([(0)]) ;
    let fourteenthPrize= shuffle([(0)]) ;
    let fifteenthPrize= shuffle([(0)]) ;
    let sixteenthPrize= shuffle([(0)]) ;

    if({{$products[0]->showfirstPrize}} ==1){
     FirstPrize = shuffle([(3766)]);
    }
    if({{$products[0]->showsecondPrize}} ==1){
     secondPrize = shuffle([(3730)]);
    }
    if({{$products[0]->showthirdPrize}} ==1){
     thirdPrize = shuffle([(3682)]);
    }    
    if({{$products[0]->showfourthPrize}} ==1){
     fourthPrize = shuffle([(3643)]);
    }    
    if({{$products[0]->showfifthPrize}} ==1){
     fifthPrize = shuffle([(3610)]);
    }    
    if({{$products[0]->showsixthPrize}} ==1){
     sixthPrize = shuffle([(3579)]);
    }    
    if({{$products[0]->showseventhPrize}} ==1){
     seventhPrize = shuffle([(3545)]);
    }    
    if({{$products[0]->showeightthPrize}} ==1){
     eightPrize = shuffle([(3510)]);
    }
    if({{$products[0]->showninethPrize}} ==1){
     ninthPrize = shuffle([(3466)]);
    }
    if({{$products[0]->showteenthPrize}} ==1){
     teenthPrize = shuffle([(3433)]);
    }
    if({{$products[0]->showeleventhPrize}} ==1){
     eleventhPrize = shuffle([(0)]);
    }
    if({{$products[0]->showtwelvthPrize}} ==1){
     twelvthPrize = shuffle([(0)]);
    }
    if({{$products[0]->showtherteenthPrize}} ==1){
     therteenthPrize = shuffle([(0)]);
    }
    if({{$products[0]->showfourteenthPrize}} ==1){
     fourteenthPrize = shuffle([(0)]);
    }
    if({{$products[0]->showfifteenthPrize}} ==1){
     fifteenthPrize = shuffle([(0)]);
    }
    if({{$products[0]->showsixteenthPrize}} ==1){
     sixteenthPrize = shuffle([(0)]);
    }


    // Bentuk acak
    let Hasil=[];
    let primaryPrizeList = shuffle([
      FirstPrize[0],
      secondPrize[0],
      thirdPrize[0],
      fourthPrize[0],
      fifthPrize[0],
      sixthPrize[0],
      seventhPrize[0],
      eightPrize[0],
      ninthPrize[0],
      teenthPrize[0],
      eleventhPrize[0],
      twelvthPrize[0],
      therteenthPrize[0],
      fourteenthPrize[0],
      fifteenthPrize[0],
      sixteenthPrize[0]
    ]);

    primaryPrizeList.forEach((element)=>{
      if(element>0){
        Hasil.push(element);
      }

    })
    // console.log(Hasil[0]);
  
    // Ambil value item yang terpilih

    if (FirstPrize.includes(Hasil[0]))  SelectedItem ="{{$products[0]->firstPrize}}";


    if (secondPrize.includes(Hasil[0])) SelectedItem = "{{$products[0]->secondPrize}}";

    if (thirdPrize.includes(Hasil[0]))  SelectedItem = "{{$products[0]->thirdPrize}}";


    if (fourthPrize.includes(Hasil[0])) SelectedItem = "{{$products[0]->fourthPrize}}";


    if (fifthPrize.includes(Hasil[0]))  SelectedItem = "{{$products[0]->fifthPrize}}";


    if (sixthPrize.includes(Hasil[0]))  SelectedItem = "{{$products[0]->sixthPrize}}";


    if (seventhPrize.includes(Hasil[0]))SelectedItem = "{{$products[0]->seventhPrize}}";


     if (eightPrize.includes(Hasil[0]))  SelectedItem = "{{$products[0]->eightthPrize}}";


     if (ninthPrize.includes(Hasil[0]))  SelectedItem = "{{$products[0]->ninethPrize}}";


     if (teenthPrize.includes(Hasil[0])) SelectedItem = "{{$products[0]->teenthPrize}}";


       if (eleventhPrize.includes(Hasil[0])) SelectedItem = "{{$products[0]->eleventhPrize}}";



       if (twelvthPrize.includes(Hasil[0])) SelectedItem = "{{$products[0]->twelvthPrize}}";


       if (therteenthPrize.includes(Hasil[0])) SelectedItem = "{{$products[0]->therteenthPrize}}";


       if (fourteenthPrize.includes(Hasil[0])) SelectedItem = "{{$products[0]->fourteenthPrize}}";


       if (fifteenthPrize.includes(Hasil[0])) SelectedItem = "{{$products[0]->fifteenthPrize}}";


       if (sixteenthPrize.includes(Hasil[0])) SelectedItem = "{{$products[0]->sixteenthPrize}}";


  
    // Proses

    box.style.setProperty("transition", "all ease 5s");
    box.style.transform = "rotate(" + Hasil[0]+ "deg)";
    element.classList.remove("animate");
    setTimeout(function () {
      element.classList.add("animate");
    }, 500);
  
    // Munculkan Alert
    setTimeout(function () {
      applause.play();
      swal(
        "تبریک",
        " شما برنده ای " +SelectedItem+ "شده اید",
        "success"
      );
      //برای ثبت تاریخچه
      $.ajax({
        method: 'get',
        url: baseUrl + "/setCustomerLotteryHistory",
        data: {
            _token: "{{csrf_token()}}",
            customerId: {{Session::get('psn')}},
            product:SelectedItem
        },
        async: true,
        success: function(data) {
            $("#spinnerBtn").prop("disabled",true);
        },
        error:function(errer){

        }
    });
    }, 5500);
  
    // Delay and set to normal state
    setTimeout(function () {
      box.style.setProperty("transition", "initial");
      box.style.transform = "rotate(90deg)";
    }, 6000);


  }
</script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
  
      <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
      
      <!-- <script src="{{url('/resources/assets/lottery/lotteryScript.js')}}"></script> -->
     
      <script type="text/javascript">
           var _gaq = _gaq || [];
            _gaq.push(['_setAccount', 'UA-36251023-1']);
            _gaq.push(['_setDomainName', 'jqueryscript.net']);
            _gaq.push(['_trackPageview']);
  
    (function() {
          var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
         ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();
  

  try {
    fetch(new Request("https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js", { method: 'HEAD', mode: 'no-cors' })).then(function(response) {
      return true;
    }).catch(function(e) {
      var carbonScript = document.createElement("script");
      carbonScript.src = "//cdn.carbonads.com/carbon.js?serve=CK7DKKQU&placement=wwwjqueryscriptnet";
      carbonScript.id = "_carbonads_js";
      document.getElementById("carbon-block").appendChild(carbonScript);
    });
  } catch (error) {
    console.log(error);
  }
		  
$("#useLuckyWheel").on("click", ()=> {
	$("#luckyWheel").css("display","flex");
})

</script>
@endsection
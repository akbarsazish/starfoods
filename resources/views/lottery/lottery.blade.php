@extends('layout.layout')
@section('content')
<link rel="stylesheet" type="text/css" href="{{url('/resources/assets/lottery/lotteryStyle.css')}}" />
<div class="container" style="margin-top:77px;">
 <div id="jquery-script-menu">
    <div class="mainbox" id="mainbox">
      <div class="box" id="box">
            <div class="box1">
                <span class="font span1"><b> روغن سرخ کردني 1350 گرم سودا </b></span>
                <span class="font span2"><b>رب گوجه فرنگي 1 کليددار روژين </b></span>
                <span class="font span3"><b>  پودر ماشين لباسشويي تاپ   </b></span>
                <span class="font span4"><b> چاي 500 گرمي احمد عطري </b></span>
                <span class="font span5"> <b> یک کیسه برنج هندي 1121 ترانه  </b> </span>
            </div>
            <div class="box2">
                <span class="font span1"><b> رشته کن استيل </b></span>
                <span class="font span2"><b> چکمه مشکي بلندصادقي (موادنو) </b> </span>
                <span class="font span3"> <b>ماءالشعير گازدار باواريا طعم سيب - 330 ميلي ليتر </b></span>
                <span class="font span4"> <b> خالی </span>
                <span class="font span5"> <b>  کش رنگي يک کيلويي عقاب </b> </span>
            </div>
      </div>
      <button class="spin" onclick="spin()">چرخش   </button>
    </div>
    <audio controls="controls" id="applause" src="{{url('/resources/assets/lottery/applause.mp3')}}" type="audio/mp3"></audio>
    <audio controls="controls" id="wheel" src="{{url('/resources/assets/lottery/wheel.mp3')}}" type="audio/mp3"></audio>

 </div>
 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    
    <script src="{{url('/resources/assets/lottery/lotteryScript.js')}}"></script>
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

</script>
<script>
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
</script>







@endsection

@extends('layout.layout')
@section('content')

<style>
    .list-group-numbered>li::before {
    font-size:18px;
    color:red;
    font-weight:bold;
}
.explain {
    font-size:16px;
}
</style>
<div class="container" style="margin-top:70px;">
    <div class="row text-center">
        <div class="col-lg-12">
            <div class="mywalet mt-3">
                    <span class="walletContent"> @if($moneyTakhfif){{number_format(ceil($moneyTakhfif))}} @else 0 @endif ریال </span> 
            </div>
            <div class="labelContent" style="font-size:16px; margin-top:10px;">
                    موجودی شما 
            </div>

            <div class="mt-4 p-4" id="useWallet" style="box-shadow: 0 4px 8px 0 #f50303cc;">
                 <p class="explain"> استفاده از کیف پول منوط به پرداخت آنلاین می باشد. </p>
                 <a type="button"  class="btn btn-sm btn-success" > استفاده از کیف پول <i class="fa fa-check"></i> </a>
            </div>

            
           
            <div class="walletGuid mt-3 p-4" id="yesNoBtn" style="font-size:16px; display:none; box-shadow: 0 4px 8px 0 #f50303cc;">
                    <p class="explain">
                        برای استفاده از کیف پول در نظر سنجی ما شرکت نمایید!
                    </p>
                    <a type="button" id="yesBtn" class="btn btn-success" > بلی <i class="fa fa-check"></i> </a>
                    <a href="{{url('/home')}}" type="button" class="btn btn-danger"> خیر <i class="fa fa-xmark"></i> </a>
            </div>
        </div>
    </div>
    <div class="row rounded-3 mt-3" id="questionPart">
        <div class="col-lg-12 p-2">
            <ul class="list-group list-group-numbered pe-1">
            <form action="{{url('/addMoneyToCase')}}" method="get">
                        @csrf
                @foreach($nazars as $nazar)
                <input type="hidden" name="nazarId" value="{{$nazar->nazarId}}">
                    <li class="list-group-item question"> 
                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" class="form-label"> <b> 1- {{$nazar->question1}}  </b> </label>
                            <textarea class="form-control" name="answer1" required id="exampleFormControlTextarea1" minlength="15"  rows="3"></textarea>
                        </div>
                    </li>
                    <li class="list-group-item question"> 
                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" class="form-label"> <b> 2- {{$nazar->question2}}</b>  </label>
                            <textarea class="form-control" name="answer2" required id="exampleFormControlTextarea1" minlength="15"  rows="3"></textarea>
                        </div>
                    </li>
                    <li class="list-group-item question"> 
                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" class="form-label"> <b> 3- {{$nazar->question3}} </b>  </label>
                            <textarea class="form-control" name="answer3" required id="exampleFormControlTextarea1" minlength="15"  rows="3"></textarea>
                        </div>
                    </li>
                @endforeach
                    <span class="list-group-item question textn-end">
                        <input type="hidden" name="takhfif" value="{{$moneyTakhfif}}">
                    <button class="walletbutton" @if(!$moneyTakhfif) disabled @else  @endif   type="submit"> ارسال <i class="fa fa-send"></i> </button>
                    </form>
                    </span>
            </ul>
        </div>
    </div>
</div>

<script>
    $("#yesBtn").on("click", ()=>{
      $("#questionPart").css("display", "block");
      $("#questionPart").css(" transition", "width 3s ease");
		$("#yesNoBtn").css("display", "none");
     })

    $("#useWallet").on("click", ()=>{
      $(".walletGuid").css("display", "block");
      $(".walletGuid").css(" transition", "width 3s ease");
	  $("#useWallet").css("display", "none");
     })

</script>
@endsection
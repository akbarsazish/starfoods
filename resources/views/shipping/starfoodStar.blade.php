@extends('layout.layout')
@section('content')

<style>
.list-group-numbered>li::before {
    font-size:18px;
    color:red;
    font-weight:bold;
}
.list-group-item {
    font-size:18px;
    color:red;
}
.useStarBtn {
    font-size:12px;
    padding:7px 18px;

}
.starContent{
    font-size:12px !important;
    z-index: 900000;
}


</style>
<div class="container" style="margin-top:90px;">
        <div class="row text-center">
            <div class="col-lg-12">
                <div class="mywalet">
                     <span calss="starContent">  امتیاز شما {{$allBonus}} </span> 
                </div>
                <div class="labelContent" style="font-size:16px; margin-top:10px;">
                     امتیاز شما {{$allBonus}}
                </div>
                
                <div class="row mt-3">
                    <div class="col-lg-12 text-end p-2">
                        <div class="useStar">
                            <ol class="list-group list-group-numbered pe-1">
                                <li class="list-group-item">اشتراک در شانس آزمایی 4 ستاره<a href="{{url('/bagCash')}}"  class="btn btn-sm btn-primary useStarBtn float-start"> اشتراک می کنم  </a>
                                    
                                </li>
                                <li class="list-group-item">استفاده از کد تخفیف 10 ستاره<button class="btn btn-sm btn-primary useStarBtn float-start"> استفاده می کنم  </button>

                                </li>
                                <li class="list-group-item">تبدیل ستاره به پول نقد(هر ستاره 10 هزار تومان) 100 ستاره<button class="btn btn-sm btn-primary useStarBtn float-start" disabled> تبدیل می کنم  </button>

                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
       
</div>

@endsection
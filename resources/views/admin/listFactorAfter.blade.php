@extends('admin.layout')
@section('content')
<main>
    <!-- Main page content-->
    <div class="container-xl px-4" style="margin-top:4%;">
      <div class="o-page__content" style=" width: 100%; margin:0 auto; margin-top: 65px; padding:0">
            <div class="o-headline" style="padding: 0; margin-bottom: 10px; margin-top: 0">
                <div id="main-cart">
                    <span class="c-checkout-text c-checkout__tab--active">لیست فاکتورهای پیش خرید</span>
                </div>
            </div>
            
          <div class="card mb-4">
                    <div class="card-body">
                        <form action="{{url('/sendFactorToApp')}}" method="post">
                                @csrf
                                <input type="hidden" value="" name="factorNumber" id="factorNumberAfter"/>
                                <input type="hidden" value="" name="csn" id="psn"/>

                            <div class="row mb-3">
                                <div class="col-lg-6">
                                    <input type="text" name="" class="form-control" id="allKalaFirst">
                                </div>
                                <div class="col-lg-6 text-end"> 
                                    @if(hasPermission(Session::get("adminId"),"pishKharid") > -1)
                                        <button disabled id="submitFactorToAppButton" name="action" value="sendToApp" class="btn btn-md btn-success mx-2" type="submit">ارسال به پشتیبان فروش</button>
                                    @endif 
                                    @if(hasPermission(Session::get("adminId"),"pishKharid") > 1)
                                        <button disabled id="submitDeleteFactorButton" name="action" value="delete" class="btn btn-md btn-danger" type="submit">حذف </button>
                                    @endif    
                                </div>
                            </div>

                       <div class="row">
                         <table class="table table-bordered">
                            <thead class="tableHeader">
                            <tr>
                                <th>ردیف</th>
                                <th>شماره</th>
                                <th>تاریخ</th>
                                <th> خریدار</th>
                                <th> مبلغ کل</th>
                                <th>انتخاب</th>
                            </tr>
                            </thead>
                            <tbody class="tableBody">
                                @foreach ($factors as $factor)
                            <tr onclick="factorStuff(this)">
                                <td>{{$loop->iteration}}</td>
                                <td>{{$factor->OrderNo}}</td>
                                <td>{{$factor->OrderDate}}</th>
                                <td>{{$factor->Name}}</td>
                                <td>{{number_format($factor->allMoney/10)}}</td>
                                <td>
                                    <input type="radio" value="{{$factor->SnOrderPishKharidAfter.'_'.$factor->CustomerSn}}" name="selectFactor" class="form-check-input" id="">
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
              
                    <div class="well" style="margin-top:3%;">

                         <div class="row mb-2">
                             <div class="col-lg-6">
                                 <h6 style="">کالاهای درخواستی</h6>
                             </div>
                            <div class="col-lg-6">
                                <!-- <input type="text" name="" size="20" class="form-control" id="allKalaFirst"> -->
                            </div>
                         </div>
                        <div class="row">
                        <table class="table table-bordered">
                            <thead class="tableHeader">
                                <tr>
                                    <th>ردیف</th>
                                    <th>کد کالا</th>
                                    <th>اسم کالا</th>
                                    <th>واحد کالا</th>
                                    <th>نوع بسته بندی</th>
                                    <th>مقدار سفارش</th>
                                    <th>نرخ</th>
                                    <th>مبلغ</th>
                                    <th>انتخاب</th>
                                </tr>
                            </thead>
                            <tbody class="tableBody" id="ordersFactorAfter">
                            </tbody>
                        </table>
                     </div>
                  </div>
               </div>
            </form>
        </div>
</main>

@endsection

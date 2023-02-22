@extends('admin.layout')
@section('content')
<main>
    <!-- Main page content-->
    <div class="container-xl" style="margin-top:4%;">
            <div id="main-cart">
                <span class="c-checkout-text c-checkout__tab--active">لیست فاکتورهای پیش خرید</span>
            </div>
      <div class="card mb-4">
         <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="well" style="margin-top:2%;">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class='c-checkout' style='padding-right:0;'>
                                    <div class="form-group">
                                        <label class="form-label"> جستجو</label>
                                        <input type="text" name="" size="20" class="form-control" id="allKalaFirst">
                                    </div>
                                </div>
                            </div>
                        </div>
                            <table class="table table-bordered" style="width:100%;">
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
                                </tr>
                                </thead>
                                <tbody class="tableBody">
                                    @foreach ($orders as $order)
                                <tr>
                                    <td>{{$loop->index+1}}</td>
                                    <td>{{$order->GoodCde}}</td>
                                    <td>{{$order->GoodName}}</td>
                                    <td>{{$order->firstUnitName}}</td>
                                    <td>@if($order->secondUnitName){{$order->secondUnitName}}@else {{$order->firstUnitName}}@endif </td>
                                    <td>{{number_format($order->Amount/1)}}</td>
                                    <td>{{number_format($order->Fi/10)}}</td>
                                    <td>{{number_format($order->Price/10)}}</td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
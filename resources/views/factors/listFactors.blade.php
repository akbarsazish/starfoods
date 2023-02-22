@extends('layout.layout')
@section('content')
<div class="container-xl" style="margin-top:80px; font-size:15px;">
     <div class='card'>
        <div class='modal-body'>
            <div class="row">
                <div class="col-lg-12 text-start">
                    <a href="/profile" class="btn btn-danger btn-sm">بازگشت  <i class="fa fa-history"></i> </a>
                </div>
            </div>
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <h4>فاکتورهای فروش </h4>
                            <table class="table table-bordered border-gray">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>فاکتور</th>
                                        <th> تاریخ </th>
                                        <th>وضعیت پرداخت </th>
                                        <th>مبلغ کل </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th> </th>
                                        <td> </td>
                                        <td> </td>
                                        <td> </td>
                                    </tr>
                                </tbody>
                        </table>
                    </div>
                </div>
    
            <div class="row mt-5">
                <div class="col-lg-12 text-center">
                    <h4>فاکتورهای مرجوعی </h4>
                        <table class="table table-bordered border-gray">
                            <thead style="font-weight:bold;">
                                <td>#</td>
                                <td>فاکتور</td>
                                <td>تاریخ </td>
                                <td>وضعیت پرداخت</td>
                                <td>مبلغ کل</td>
                                <td>نمایش</td>
                            </thead>
                            <tbody>
                                @foreach ($rejectedFactors as $factor)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$factor->FactNo}}</td>
                                    <td>{{$factor->FactDate}}</td>
                                    <td>وضعیت پدراخت</td>
                                    <td>{{number_format($factor->TotalPriceHDS/10)}} تومان</td>
                                    <form method="post" action="{{url('/factorView')}}" method="POST">
                                        @csrf
                                        <input name="factorSn" type="hidden" value="{{$factor->SerialNoHDS}}">
                                        <td style="text-align: center;"><button class="btn btn-sm" type="submit"><i class="fa fa-eye fa-xl"></i> </button></td>
                                    </form>
                                </tr>
                                @endforeach
                            </tbody>
                    </table>
                </div>
            </div>
          </div>
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

</script>
<script src="{{ url('/resources/assets/js/script.js')}}"></script>
@endsection

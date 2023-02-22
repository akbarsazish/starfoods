@extends('admin.layout')
@section('content')
<main>
    <div class='container-xl px-4' style='margin-top:5%; font-size:16px;'>
        <div class="o-headline" style="padding: 10; margin-bottom: 10px; margin-top: 0">
            <div id="main-cart">
                <span class="c-checkout-text c-checkout__tab--active">لیست کالاهای شامل هشدار</span>
            </div>
        </div>
        <div class='card mb-4'>
            <div class='card-body'>
                <div class='row'>
                    <table class="table table-striped table-bordered homeTables">
                        <thead class="tableHeader">
                            <tr>
                            <th scope="col">ردیف</th>
                            <th scope="col">اسم</th>
                            <th scope="col" style="width:100px">موجودی</th>
                            </tr>
                        </thead>
                        <tbody class="tableBody">
                            @forelse($alarmedKalas as $kala)
                            <tr>
                            <th scope="row">{{$loop->iteration}}</th>
                            <td>{{$kala->GoodName}}</td>
                            <td style="width:100px">{{$kala->Amount/1}}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="justify-content-center">
                                    <h6>داده ای وجود ندارد</h6>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
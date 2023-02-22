@extends('admin.layout')
@section('content')
@if(count($alarmedKalas) > 0 )
<script>
$(document).ready(function() {
            setTimeout($("#existanceWarningModal").modal("show"),1);
            $("#existanceWarningModal").on('hide.bs.modal', function(){
            document.location.href="/dashboardAdmin";
            });
        });
</script>
@else
<script>
        document.location.href="/dashboardAdmin";
</script>
@endif
<div class='modal fade dragAbleModal' id='existanceWarningModal' tabindex='-1' role='dialog' aria-labelledby='exampleModalCenterTitle' aria-hidden='true'>
    <div class='modal-dialog modal-dialog-centered' >
        <div class='modal-content'>
            <div class='modal-header'>
                <h5 class='modal-title' id='exampleModalLongTitle'>لیست کالاهای به هشدار رسیده</h5>
                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                </button>
            </div>
            <div class='modal-body'>
                <table class="table table-striped">
                    <thead class="tableHeader">
                        <tr>
                        <th scope="col">ردیف</th>
                        <th scope="col">اسم</th>
                        <th scope="col">موجودی</th>
                        </tr>
                    </thead>
                    <tbody class="tableBody">
                        @foreach($alarmedKalas as $kala)
                        <tr>
                        <th scope="row">{{$loop->iteration}}</th>
                        <td>{{$kala->GoodName}}</td>
                        <td>{{$kala->Amount/1}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

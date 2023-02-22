@extends('admin.layout')
@section('content')
<style>

    .forTable tr th{
        width: max-content !important;
    }

</style>
<div class="container" style="margin-top:80px; width:1200px">
    <h5 style="width:50%; border-bottom: 2px solid gray"> لیست کالاهای درخواست شده</h5>
<div class="card mb-4">
  <div class="card-body">
    <div class="row">
        <div class="col-sm-12">
                <div class="well">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class='c-checkout' style='padding-right:0;'>
                                <div class="form-group">
                                    <label class="form-label fs-6 p-0"> جستجو</label>
                                    <input type="text"   onkeyup="searchRequestedKala(this)" size="20" class="form-control" id="allKalaFirst">
                                </div>
                            </div>
                        </div>
					
                        <table class="table table-bordered message">
                            <thead class="tableHeader">
                                <tr>
                                    <th class="for-mobil">ردیف</th>
                                    <th>نام کالا</th>
                                    <th width="120"> تعداد درخواست</th>
                                    <th style="width:111px;"> تاریخ </th>
                                    <th style="width:111px;">مشاهده</th>
                                    <th class="text-center">حذف</th>
                                </tr>
                            </thead>
                            <tbody id="requestedKalas" class="tableBody">
                                @foreach ($products as $product)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$product->GoodName}}</td>
									<td style="width:120px">{{$product->countRequest}}</td>
									<td style="width:111px;">{{\Morilog\Jalali\Jalalian::fromCarbon(Carbon\Carbon::createFromFormat('Y-m-d H:i:s.u',$product->TimeStamp))->format('Y/m/d')}}</td>
                                    <td style="width:111px;"><button type="button" onclick="displayRequestedKala({{$product->GoodSn}})" style=" background-color: #ffffff;">  <i class="fa fa-eye" style="color:green;"> </i>  </button> </td>
                                    <td><button type="button" @if(hasPermission(Session::get( 'adminId'),'kalaRequests' ) > 1) onclick="removeRequestedKala({{$product->GoodSn}})" @else disabled @endif  class="btn btn-sm"> <i class="fa fa-trash" style="color:red; font-size:18px;"></i> </button></td>
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
</div>
</main>
<div class="modal fade dragAbleModal"  id="requestModal" tabindex="-1" data-backdrop="static" role="dialog">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
      <div class="modal-content rounded-4">
        <div class="modal-header bg-success text-white">
			<h5 class="modal-title">درخواست دهنده های <span style="font-size:14px;color:blue;" id="GoodName"></span> </h5>
          <button type="button" class="close bg-danger" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <table class="table table-bordered message">
                <thead class="tableHeader">
                <tr>
                    <th class="for-mobil">ردیف</th>
                     <th>آدرس</th>
                    <th>مشتری</th>
					<th width="120px">تاریخ</th>
                    <th>شماره تماس</th>
					<th>کد </th>
                </tr>
                </thead >
                 <tbody id="modalContent" class="tableBody">
                </tbody>
            </table> 
        </div>
      </div>
    </div>
  </div>

@endsection

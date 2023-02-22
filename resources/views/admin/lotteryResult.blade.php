@extends('admin.layout')
@section('content')
    <div class="container" style="margin-top:80px;">
        <h5 style="border-bottom:2px solid gray; width:50%"> لیست پیامها</h5>
    <div class="card mb-1">
      <div class="card-body">
        <div class="row">
            <div class="col-sm-12">
                    <div class="well">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label fs-6"> جستجو</label>
                                    <input type="text" name="" size="20" class="form-control" id="allKalaFirst">
                                </div>
                            </div>
                        </div><br>
                            <table class="table table-bordered message" style="width:100%;">
                                <thead class="tableHeader">
                                <tr>
                                    <th>ردیف</th>
                                    <th> نام مشتری </th>
                                    <th> شماره تماس</th>
                                    <th>تاریخ</th>
                                    <th>جایزه</th>
                                    <th>تاریخ تسویه</th>
                                    <th>تسویه</th>
                                    <th>حذف</th>
                                </tr>
                                </thead>
                                <tbody class="tableBody">
                                    @foreach ($lotteryTryResult as $lottery)
                                <tr>
                                    <td style="width:60px">{{$loop->iteration}}</td>
                                    <td>{{$lottery->Name}}</td>
                                    <td>{{$lottery->PhoneStr}}</td>
                                    <td>{{\Morilog\Jalali\Jalalian::fromCarbon(Carbon\Carbon::createFromFormat('Y-m-d',$lottery->lastTryDate))->format('Y/m/d')}}</td>
                                    <td>{{$lottery->wonPrize}} </td>
                                    @if($lottery->Istaken==1)
                                    <td>{{\Morilog\Jalali\Jalalian::fromCarbon(Carbon\Carbon::createFromFormat('Y-m-d',$lottery->tasviyahDate))->format('Y/m/d')}} </td>
                                    @else
                                    <td>تسویه نشده</td>
                                    @endif
                                    @if($lottery->Istaken==1)
                                    <td>تسویه شده </td>
                                    @else
                                    <td>تسویه نشده</td>
                                    @endif
                                    <td><div>
                                    @if($lottery->Istaken==0)
                                        <form action="{{url('/tasviyeahLottery')}}" method="post">
                                            @csrf
                                            <input type="hidden" name="customerId" value="{{$lottery->customerId}}">
                                            <input type="hidden" name="lotteryTryId" value="{{$lottery->id}}">
                                        <button type="submit" class="btn btn-sm btn-info">تسویه</button>
                                        </form>
                                        @else
                                    تسویه شده
                                    @endif
                                    </div></td>
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

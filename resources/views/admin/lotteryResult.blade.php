@extends('admin.layout')
@section('content')
<style>
    #gamerListsTable {
        display:none;
    }
</style>
<div class="container-fluid containerDiv">
    <div class="row">
            <div class="col-lg-2 col-md-2 col-sm-3 sideBar">
                <fieldset class="border rounded mt-5 sidefieldSet">
                    <legend  class="float-none w-auto legendLabel mb-0"> بازیها و لاتری </legend>
                    <div class="form-check">
                        <input class="form-check-input p-2 float-start" type="radio" name="settings" id="lotteryResultRadioBtn" checked>
                        <label class="form-check-label me-4" for="assesPast"> نتجه لاتری </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input p-2 float-start" type="radio" name="settings" id="gamerListRadioBtn">
                        <label class="form-check-label me-4" for="assesPast">  گیمر لیست  </label>
                    </div>
                </fieldset>
                </div>
            <div class="col-sm-10 col-md-10 col-sm-12 contentDiv">
                <div class="row contentHeader">
                     <div class="col-sm-4">
                        <div class="form-group mt-2">
                            <input type="text" name="" class="form-control form-control-sm" id="allKalaFirst" placeholder="جستجو">
                        </div>
                     </div>
                 </div>
                <div class="row mainContent">
                    <table class="table table-bordered table-sm" id="lotteryResultTable">
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

                            <!-- گیمر لیست  -->
                            <table class="table table-bordered table-sm" id="gamerListsTable">
                                <thead class="tableHeader">
                                <tr>
                                    <th>ردیف</th>
                                    <th> نام مشتری </th>
                                    <th>شماره تماس</th>
                                    <th>جایزه (تومان)</th>
                                    <th>حذف</th>
                                </tr>
                                </thead>
                                <tbody class="tableBody">
                                    @foreach ($players as $player)
                                        <tr>
                                            <td>{{number_format($loop->index+1)}}</td>
                                            <td>{{$player->Name}}</td>
                                            <td>{{$player->Name}}</td>
                                            <td>{{number_format($player->prize)}}</td>
                                            <td> <i class="fa fa-trash" style="color:red; cursor:pointer"></i> </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>



                </div>
                <div class="row contentFooter"> </div>
            </div>
    </div>
</div>

@endsection

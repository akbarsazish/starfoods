@extends('admin.layout')
@section('content')

<style>

    button:hover{
        /* background: linear-gradient(#9ed5b6, #198754); */
        background: linear-gradient(#d3fde4, #198754);
        color: #f1e253 !important;
        font-size: 18px;
        font-weight: bold;
        border-color: #bb993a !important;
        border-width: 2px;
    }

    #photoPickerID:hover{
        background: linear-gradient(#d3fde4, #198754);
        color: #f1e253 !important;
        font-size: 17px;
        font-weight: bold;
        border-color: #bb993a !important;;
        height: 34px;
        width: 140px;
        }

    #addData i:hover,
    #removeData i:hover
    {
        color: #f1e253 !important;
        font-size: 40px;
        font-weight: bold;
        border-color: #bb993a !important;
        border-width: 2px;
    }

    button.btn-info,
    #photoPickerID{
        padding: 0;
        width: 122px;
        height: 34px;
        background-color: #198754;
        border-color: #000;
    }

    #photoPickerID{
        width:140px;
    }

    #top, #down{
        display: inline;
        padding:0;
        border:none;
        background:none
    }

    i::before{
        padding:0;
    }

    label{
        font-family:IRANSans;
        font-size: 0.9rem;
        line-height: 1;
        color: rgb(19, 7, 129);
        font-weight: 300;
    }

    .checkbox-label{
        font-weight: bold;
    }
</style>
    <section class="main-cart container px-0">
        <div class="o-page__content" style=" width: 80%; margin:0 auto; margin-top: 65px; padding:0">
            <div class="o-headline " style="padding: 0; margin-bottom: 10px; margin-top: 0">
                <div id="main-cart" class="p-1">
                    <span class="c-checkout__tab--active">{{ $title }}</span>
                </div>
            </div>
            <div class="c-checkout" style="padding-right:0; border-radius:10px 10px 2px 2px; padding:0">
                <div class="container p-1 ">
                    @foreach ($parts as $part)
                        <form class="p-1" action="{{ url('/doEditGroupPart') }}" onsubmit="docmument.getElementById('showFirstPrice').disabled = false;" method="POST" enctype="multipart/form-data" class='form'>
                            @csrf
                            <div class="d-flex flex-row-reverse">
                                 <button type="submit" @if(hasPermission(Session::get( 'adminId'),'homePage' ) <1 ) disabled @endif class="btn btn-info btn-md text-warning" style="foloat:left;">ذخیره <i class="fa-light fa-save fa-lg"></i></button>
                            </div>
                            <div class='modal-body pb-0'>
                                <div class='mx-auto c-checkout rounded pt-2 pb-3 ' style='padding: 10px; padding-right:0; padding-bottom:25;'>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class='form-group'>
                                                <label class='form-label'>اسم سطر</label>
                                                <input type='text' id='partTitle' @if(hasPermission(Session::get( 'adminId'),'homePage' ) <1 ) disabled @endif value="{{trim($part->partTitle) }}" class='form-control form-control-sm' name='partTitle'/>
                                                <input type='text' id='partId' @if(hasPermission(Session::get( 'adminId'),'homePage' ) <1 ) disabled @endif style="display: none" value="{{ $part->partId }}"class='form-control form-control-sm' name='partId'/>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class='form-group'>
                                                <label class='form-label'>نوعیت دسته بندی</label>
                                                <select name='partType' @if(hasPermission(Session::get( 'adminId'),'homePage' ) <1 ) disabled @endif onchange='showDiv(this)' class='form-control form-control-sm' id='partType'>
                                                    <option value="{{$part->partType}}">{{ $part->name }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class='form-group'>
                                                <label class='form-label'>اولویت</label>
                                                <select  type='text' id="priority" @if(hasPermission(Session::get( 'adminId'),'homePage' ) <1 ) disabled @endif  class='form-select form-select-sm' name='partPriority' placeholder=''>
                                                    @for ($i =3; $i <= (int)$countHomeParts; $i++)
                                                        <option @if((int)$part->priority==$i) selected @endif value="{{$i}}">{{$i-2}}</option>
                                                        @endfor
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-1 d-flex align-items-stretch">
                                            <div class="form-group d-flex text-nowrap align-items-center pt-3">
                                                <input class="form-control d-flex form-check-input align-items-end" @if(hasPermission(Session::get( 'adminId'),'homePage' ) <1 ) disabled @endif type="checkbox" name="activeOrNot" id="activeOrNot" @if ($part->activeOrNot==1)checked @else @endif> &nbsp;  &nbsp;
                                                 <label class="form-check-label align-items-end" style="font-weight: bold" for="activeOrNot">نمایش</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <div class='c-checkout mx-auto p-0 pt-2' style="width:95%; border-radius:10px 10px 2px 2px;">
                            <div class="row">
                                <div class="row">
                                    <div class="col-sm-2 d-flex align-items-stretch">
                                        <div class="form-group d-flex text-nowrap align-items-center">
                                            <input class="form-control d-flex form-check-input align-items-end" @if(hasPermission(Session::get( 'adminId'),'homePage' ) <1 ) disabled @endif type="checkbox" name="showAll" id="activeAllSelection" @if($part->showAll==1) checked @else @endif>  &nbsp;  &nbsp;
                                            <label class="form-check-label align-items-end" style="font-weight: bold" for="activeAllSelection">فعالسازی انتخاب همه</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 d-flex align-items-stretch">
                                        <div class="form-group d-flex text-nowrap align-items-center">
                                            <input class="form-control d-flex form-check-input align-items-end" @if(hasPermission(Session::get( 'adminId'),'homePage' ) <1 ) disabled @endif type="checkbox" name="showPercentTakhfif" id="showTakhfifPercent" @if($part->showPercentTakhfif==1) checked @else @endif>  &nbsp;  &nbsp;
                                            <label class="form-check-label align-items-end" style="font-weight: bold" for="showTakhfifPercent">نمایش در صد تخفیف</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 d-flex align-items-stretch">
                                        <div class="form-group d-flex text-nowrap align-items-center">
                                            <input class="form-control d-flex form-check-input align-items-end" @if(hasPermission(Session::get( 'adminId'),'homePage' ) <1 ) disabled @endif type="checkbox" name="showOverLine" id="showFirstPrice"  @if($part->showOverLine==1) checked @else @endif> &nbsp;  &nbsp;
                                            <label class="form-check-label align-items-end" style="font-weight: bold" for="showFirstPrice">نمایش قیمت قبلی کالا</label>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-stretch" style="width:20%">
                                        <div class="form-group d-flex text-nowrap align-items-center">
                                            <label class="col-sm-8 form-label pt-2" style="font-weight: bold" for="showKalaNum">نمایش تعداد کالا</label>
                                            <input type="number" name="showTedad" class="col-sm-4 form-control form-control-sm" @if(hasPermission(Session::get( 'adminId'),'homePage' ) <1 ) disabled @endif id="showKalaNum" value="{{$part->showTedad}}">
                                        </div>
                                    </div>
                                    @if($part->partType==11)
                                    <div class="row p-0" style="height: 4vh; width:30%" >
                                        <div id="cp9" class="col-sm-5 input-group d-flex text-nowrap p-0">
                                            <label class="form-label form-label col-sm-8 pt-2" @if(hasPermission(Session::get( 'adminId'),'homePage' ) <1 ) for="colorPicker" @endif style="font-weight: bold">انتخاب رنگ</label>
                                            <input type="color" color="red"  onchange="document.getElementById('colorDiv').style.backgroundColor=this.value;"
                                             class="form-control form-control-sm col-sm-4 rounded border-danger p-1 m-0" id="colorPicker" name="shegeftColor"  value="#ff0000">
                                        </div>
                                        <div id="cp9" class="col-sm-7 input-group d-flex text-nowrap">
                                            <label class="form-label rounded text-warning d-flex align-items-center justify-content-center" @if(hasPermission(Session::get( 'adminId'),'homePage' ) <1 ) for="photoPicker" @endif id="photoPickerID" style="font-weight: bold"> انتخاب عکس<i class="fa-light fa-image fa-lg"></i></label>
                                            <input type="file" onchange='document.getElementById("shegeftPicture").src = window.URL.createObjectURL(this.files[0]); '
                                             class="form-control d-none" id="photoPicker" name="shegeftPicture" value="">
                                        </div>
                                    </div>
                                    <div id="colorDiv" class="col-sm-11 rounded mx-auto mt-2" style="height:15vh; background-color:{{$part->partColor}};">
                                        <div class="form-group">
                                            <img class="p-0 m-0" style="width: 80px; height:120px;" id="shegeftPicture" src="{{url('/resources/assets/images/shegeftAngesPicture/'.$part->partId.'.jpg')}}" alt="">
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div class="row c-checkout col-sm-11 mx-auto mt-3 rounded pt-2 pb-3">
                                    <div class="form-group col-sm-4">
                                        <label class="form-label">جستجو</label>
                                        <input type="text" name="" size="20" class="form-control form-control-sm" @if($part->partType==13) id="pishKharidFirst" @else id="allKalaFirst" @endif autocomplete="off">
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label class="form-label">  گروه اصلی</label>
                                        <select class="form-select form-select-sm" id="searchGroup">
                                            <option value="0">همه کالا ها</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label class="form-label">  گروه فرعی</label>
                                        <select class="form-control form-control-sm" id="searchSubGroup">
                                            <option value="0">همه کالا ها</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-5">
                                    <div class='modal-body'>
                                        <div class='c-checkout' style='padding-right:0;'>
                                            <table class="table table-bordered table table-hover table-sm table-light">
                                                <thead class="tableHeader">
                                                    <tr>
                                                        <th>ردیف</th>
                                                        <th>اسم </th>
                                                        <th>
                                                            <input type="checkbox" name=""  @if(hasPermission(Session::get( 'adminId'),'homePage' ) <1 ) disabled @endif   class="selectAllFromTop form-check-input"  >
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody class="tableBody" id="kalaList">
                                                    @foreach ($kalas as $kala)
                                                        <tr @if(hasPermission(Session::get( 'adminId'),'homePage' ) <1 ) onclick="checkCheckBox(this,event)" @endif>
                                                            <td>{{ $loop->index+1 }}</td>
                                                            <td>{{ $kala->GoodName }}</td>
                                                            <td>
                                                                <input class="form-check-input" type="checkBox" name="kalaListIds" value="{{ $kala->GoodSn.'_'.$kala->GoodName }}"id="flexCheckChecked">
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class='modal-body' style="position:relative; top: 30%;">
                                            <button id="addData" @if(hasPermission(Session::get( 'adminId'),'homePage' ) <1 ) disabled @endif>
                                                <i class="fa-regular fa-circle-chevron-left fa-3x text-success"></i>
</button>
                                            <button id="removeData" @if(hasPermission(Session::get( 'adminId'),'homePage' ) <1 ) disabled @endif>
                                                <i class="fa-regular fa-circle-chevron-right fa-3x text-success"></i>
</button>
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <div class='modal-body'>
                                        <div class='c-checkout' style='padding-right:0;'>
                                            <table class="table table-bordered table table-hover table-sm table-light">
                                                <thead class="tableHeader">
                                                    <tr>
                                                        <th>ردیف</th>
                                                        <th class="position-relative"> اسم کالا</th>
                                                        <th>
                                                            <button @if(hasPermission(Session::get( 'adminId'),'homePage' ) <1 ) disabled @endif class='priority' id="down" type="button" value="down" ><i class="fa-solid fa-circle-chevron-down fa-sm" style=''></i></button> &nbsp;
                                                            <button @if(hasPermission(Session::get( 'adminId'),'homePage' ) <1 ) disabled @endif class='priority' id="top"  type="button" value="up" >  <i class="fa-solid fa-circle-chevron-up fa-sm" style=''></i></button>
                                                        </th>
                                                        <th>
                                                            <input type="checkbox" @if(hasPermission(Session::get( 'adminId'),'homePage' ) <1 ) disabled @endif name=""  class="selectAllFromTop form-check-input"  >
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody class="tableBody" id="addedKalaPart">
                                                    @foreach ($addKalas as $kala)
                                                        <tr @if(hasPermission(Session::get( 'adminId'),'homePage' ) <1 ) onClick="checkCheckBox(this,event)" @endif>
                                                            <td>{{ $loop->index+1 }}</td>
                                                            <td>{{ $kala->GoodName }}</td>
                                                            <td>
                                                                <input class="form-check-input" name="addedKala[]" type="checkBox" value="{{ $kala->GoodSn }}" id="flexCheckChecked">
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </form>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <script>
        window.onload = function() {
            //used for adding data to the left side
            $(document).on('click', '#addData', (function() {
                var kalaListID = [];
                $('input[name=kalaListIds]:checked').map(function() {
                    kalaListID.push($(this).val());
                });

                $('input[name=kalaListIds]:checked').parents('tr').css('color','red');
                $('input[name=kalaListIds]:checked').prop("disabled", true);
                $('input[name=kalaListIds]:checked').prop("checked", false);
                $.ajax({
                    method: 'get',
                    url: "{{ url('/addKalatoPart') }}",
                    async: true,
                    data: {
                        _token: "{{ csrf_token() }}",
                        kalaList:kalaListID ,
                        partId:$('#partId').val(),
                        partType:'kala'
                    },
                    success: function(arrayed_result) {
                        $('#addedKalaPart').empty();
                        for (var i = 0; i <= arrayed_result.length - 1; i++) {

                            $('#addedKalaPart').append(
                                `<tr  onClick="checkCheckBox(this,event)">
                                    <td>`+(i+1)+`</td>
                                    <td>`+arrayed_result[i].GoodName+`</td>
                                    <td>
                                        <input class="form-check-input" name="addedKala1[]" type="checkBox"  value="`+arrayed_result[i].GoodSn+`" id="flexCheckChecked">
                                    </td>
                                </tr>
                            `);
                        }
                    },
                    error: function(data) {
                        alert("not good");
                    }

                });

            }));

            //used for removing kala from a part
            $(document).on('click', '#removeData', (function() {
                let inp=$('tr').find('input:checkbox:checked');
                $('tr').has('input:checkbox:checked').hide();
                var kalaListID = [];
                $(inp).map(function() {
                    kalaListID.push($(this).val());
                });
                $.ajax({
                    method: 'get',
                    url: "{{ url('/deleteKalaFromPart') }}",
                    async: true,
                    data: {
                        _token: "{{ csrf_token() }}",
                        kalaList:kalaListID ,
                        partId:$('#partId').val(),
                        partType:'kala'
                    },
                    success: function(arrayed_result) {
                        $('#addedKalaPart').empty();
                        for (var i = 0; i <= arrayed_result.length - 1; i++) {

                            $('#addedKalaPart').append(
                                `<tr  onClick="checkCheckBox(this,event)">
                                    <td>`+(i+1)+`</td>
                                    <td>`+arrayed_result[i].GoodName+`</td>
                                    <td>
                                        <input class="form-check-input" name="addedKala1[]" type="checkBox"  value="`+arrayed_result[i].GoodSn+`" id="flexCheckChecked">
                                    </td>
                                </tr>
                            `);
                        }
                    },
                    error: function(data) {
                        alert("not good");
                    }

                });
            }));
            //used for setting priority of kala in a part
            $(document).on('click', '.priority', (function() {
                let kalaId=$('input[name="addedKala[]"]:checked').val();
                $.ajax({
                    method: 'get',
                    url: "{{ url('/changeKalaPartPriority') }}",
                    async: true,
                    data: {
                        _token: "{{ csrf_token() }}",
                        kalaId:kalaId ,
                        partId:$('#partId').val(),
                        priority:$(this).val()
                    },
                    success: function(arrayed_result) {
                        $('#addedKalaPart').empty();
                        for (var i = 0; i <= arrayed_result.length - 1; i++) {
                            let check=""
                            if(kalaId==arrayed_result[i].GoodSn){
                                check="checked"
                            }
                            $('#addedKalaPart').append(
                                `<tr  onClick="checkCheckBox(this,event)">
                                    <td>`+(i+1)+`</td>
                                    <td>`+arrayed_result[i].GoodName+`</td>
                                    <td>
                                        <input class="form-check-input" name="addedKala[]" type="checkBox" `+check+`  value="`+arrayed_result[i].GoodSn+`" id="flexCheckChecked">
                                    </td>
                                </tr>
                            `);
                        }
                    },
                    error: function(data) {
                        alert("not good");
                    }

                });
            }));
            //used for getting kalas for searching according to kala groups
            $.ajax({
                    method: 'get',
                    url: "{{ url('/getKalaGroups') }}",
                    async: true,

                    success: function(arrayed_result) {
                        $('#searchGroup').empty();
                        $('#searchGroup').append( '<option value="0">همه کالا ها</option>' );
                        for (var i = 0; i <= arrayed_result.length - 1; i++) {
                            $('#searchGroup').append( '<option value="'+arrayed_result[i].id+'">'+arrayed_result[i].title+'</option>' );
                        }
                    },
                    error: function(data) {
                        alert("پیدا نشد");
                    }

                });

                //used for search according to selecting a group
                $(document).on('change', '#searchGroup', (function() {
                    $.ajax({
                    method: 'get',
                    url: "{{ url('/getKalasSearch') }}",
                    async: true,
                    data: {
                        _token: "{{ csrf_token() }}",
                        groupId:$('#searchGroup').val()
                    },
                    success: function(arrayed_result) {
                        $('#searchSubGroup').empty();
                        $('#kalaList').empty();
                        for (var i = 0; i <= arrayed_result.kalas.length - 1; i++) {
                            $('#kalaList').append(`<tr>
                                <td>`+(i+1)+`</td>
                                <td>`+arrayed_result.kalas[i].GoodName+`</td>
                                <td>
                                    <input class="mainGroupId" type="checkBox"
                                        name="kalaListIds" value="`+arrayed_result.kalas[i].GoodSn+`"
                                        id="flexCheckChecked">
                                </td>
                            </tr>`);
                        }
                        $('#searchSubGroup').append( '<option value="0">همه کالا ها</option>' );
                        for (var i = 0; i <= arrayed_result.subGroups.length - 1; i++) {
                            $('#searchSubGroup').append(`<option value="`+arrayed_result.subGroups[i].id+`">`+arrayed_result.subGroups[i].title+`</option>`);
                        }
                    },
                    error: function(data) {
                        alert("پیدا نشد");
                    }

                });

                }));

                //used for searching according to subgroups
                $(document).on('change', '#searchSubGroup', (function() {
                    $.ajax({
                    method: 'get',
                    url: "{{ url('/getKalasSearchSubGroup') }}",
                    async: true,
                    data: {
                        _token: "{{ csrf_token() }}",
                        groupId:$('#searchSubGroup').val(),
                        mainGroupId:$('#searchGroup').val()
                    },
                    success: function(arrayed_result) {

                        $('#kalaList').empty();
                        for (var i = 0; i <= arrayed_result.length - 1; i++) {
                            $('#kalaList').append(`<tr>
                                <td>`+(i+1)+`</td>
                                <td>`+arrayed_result[i].GoodName+`</td>
                                <td>
                                    <input class="mainGroupId" type="checkBox"
                                        name="kalaListIds" value="`+arrayed_result[i].GoodSn+`_`+arrayed_result[i].GoodName+`"
                                        id="flexCheckChecked">
                                </td>
                            </tr>`);
                        }
                    },
                    error: function(data) {
                        alert("پیدا نشد");
                    }

                });

                }));
                //USED FOR SEARCHING KALAS BY INPUT
                $(document).on('keyup', '#allKalaFirst', (function() {
                    $.ajax({
                    method: 'get',
                    url: "{{ url('/searchKalas') }}",
                    async: true,
                    data: {
                        _token: "{{ csrf_token() }}",
                        searchTerm:$('#allKalaFirst').val()
                    },
                    success: function(arrayed_result) {

                        $('#kalaList').empty();
                        for (var i = 0; i <= arrayed_result.length - 1; i++) {
                            $('#kalaList').append(`
                            <tr onclick="checkCheckBox(this,event)">
                                <td>`+(i+1)+`</td>
                                <td>`+arrayed_result[i].GoodName+`</td>
                                <td>
                                    <input class="mainGroupId" type="checkBox"
                                        name="kalaListIds" value="`+arrayed_result[i].GoodSn+`_`+arrayed_result[i].GoodName+`"
                                        id="flexCheckChecked">
                                </td>
                            </tr>`);
                        }
                    },
                    error: function(data) {
                        alert("پیدا نشد");
                    }

                });

                }));
        }
    </script>
@endsection

@extends('admin.layout')
@section('content')

<div class="container-fluid containerDiv">
    <div class="row">
            <div class="col-lg-2 col-md-2 col-sm-3 sideBar">
                <fieldset class="border rounded mt-5 sidefieldSet">
                    <legend  class="float-none w-auto legendLabel mb-0"> تنظیمات </legend>
                    <!-- <div class="form-check">
                        <input class="form-check-input p-2 float-start" type="radio" name="settings" id="elseSettingsRadio">
                        <label class="form-check-label me-4" for="assesPast">  سطح دسترسی  </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input p-2 float-start" type="radio" name="settings" id="settingAndTargetRadio">
                        <label class="form-check-label me-4" for="assesPast"> تارگت ها و امتیازات </label>
                    </div> -->
                 </fieldset>
             </div>
            <div class="col-sm-10 col-md-10 col-sm-12 contentDiv">
                <div class="row contentHeader" style="height:3%"> </div>
                   <div class="row mainContent">
                         @foreach ($parts as $part)
                          <form class="p-1" action="{{ url('/doEditGroupPart') }}" onsubmit="docmument.getElementById('showFirstPrice').disabled = false;" method="POST" enctype="multipart/form-data" class='form'>
                               @csrf
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class='form-group'>
                                                <label class='form-label'>اسم سطر</label>
                                                <input type='text' id='partTitle' @if(hasPermission(Session::get( 'adminId'),'mainPageSetting' ) <1 ) disabled @endif value="{{trim($part->partTitle) }}" class='form-control form-control-sm' name='partTitle'/>
                                                <input type='text' id='partId' @if(hasPermission(Session::get( 'adminId'),'mainPageSetting' ) <1 ) disabled @endif style="display: none" value="{{ $part->partId }}"class='form-control form-control-sm' name='partId'/>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class='form-group'>
                                                <label class='form-label'>نوعیت دسته بندی</label>
                                                <select name='partType' @if(hasPermission(Session::get( 'adminId'),'mainPageSetting' ) <1 ) disabled @endif onchange='showDiv(this)' class='form-control form-control-sm' id='partType'>
                                                    <option value="{{$part->partType}}">{{ $part->name }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-1">
                                            <div class='form-group'>
                                                <label class='form-label'>اولویت</label>
                                                <select  type='text' id="priority" @if(hasPermission(Session::get( 'adminId'),'mainPageSetting' ) <1 ) disabled @endif  class='form-select form-select-sm' name='partPriority' placeholder=''>
                                                    @for ($i =3; $i <= (int)$countHomeParts; $i++)
                                                        <option @if((int)$part->priority==$i) selected @endif value="{{$i}}">{{$i-2}}</option>
                                                        @endfor
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-1 d-flex align-items-stretch">
                                            <div class="form-group d-flex text-nowrap align-items-center pt-3">
                                                <input class="form-control d-flex form-check-input align-items-end" @if(hasPermission(Session::get( 'adminId'),'mainPageSetting' ) <1 ) disabled @endif type="checkbox" name="activeOrNot" id="activeOrNot" @if ($part->activeOrNot==1)checked @else @endif> &nbsp;  &nbsp;
                                                 <label class="form-check-label align-items-end" style="font-weight: bold" for="activeOrNot">نمایش</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 text-end">
                                             <button type="submit" @if(hasPermission(Session::get( 'adminId'),'mainPageSetting' ) <1 ) disabled @endif class="btn btn-success btn-sm text-warning">ذخیره <i class="fa-light fa-save"></i></button>
                                        </div>
                                 </div>
                        

                                <div class="row bg-light rounded border m-1">
                                    <div class="col-sm-2 d-flex align-items-stretch">
                                        <div class="form-group d-flex text-nowrap align-items-center">
                                            <input class="form-control d-flex form-check-input align-items-end" @if(hasPermission(Session::get( 'adminId'),'mainPageSetting' ) <1 ) disabled @endif type="checkbox" name="showAll" id="activeAllSelection" @if($part->showAll==1) checked @else @endif>  &nbsp;  &nbsp;
                                            <label class="form-check-label align-items-end" style="font-weight: bold" for="activeAllSelection">فعالسازی انتخاب همه</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 d-flex align-items-stretch">
                                        <div class="form-group d-flex text-nowrap align-items-center">
                                            <input class="form-control d-flex form-check-input align-items-end" @if(hasPermission(Session::get( 'adminId'),'mainPageSetting' ) <1 ) disabled @endif type="checkbox" name="showPercentTakhfif" id="showTakhfifPercent" @if($part->showPercentTakhfif==1) checked @else @endif>  &nbsp;  &nbsp;
                                            <label class="form-check-label align-items-end" style="font-weight: bold" for="showTakhfifPercent">نمایش در صد تخفیف</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 d-flex align-items-stretch">
                                        <div class="form-group d-flex text-nowrap align-items-center">
                                            <input class="form-control d-flex form-check-input align-items-end" @if(hasPermission(Session::get( 'adminId'),'mainPageSetting' ) <1 ) disabled @endif type="checkbox" name="showOverLine" id="showFirstPrice"  @if($part->showOverLine==1) checked @else @endif> &nbsp;  &nbsp;
                                            <label class="form-check-label align-items-end" style="font-weight: bold" for="showFirstPrice">نمایش قیمت قبلی کالا</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 d-flex align-items-stretch">
                                        <div class="form-group d-flex text-nowrap align-items-center">
                                            <label class="col-sm-8 form-check-label pt-2" style="font-weight: bold" for="showKalaNum">نمایش تعداد کالا</label>
                                            <input type="number" name="showTedad" class="col-sm-4 form-control form-control-sm" @if(hasPermission(Session::get( 'adminId'),'mainPageSetting' ) <1 ) disabled @endif id="showKalaNum" value="{{$part->showTedad}}">
                                        </div>
                                    </div>
                                
                                    @if($part->partType==11)
                                    <div class="col-sm-2" id="cp9">
                                        <label class="form-label form-label col-sm-8 pt-2" style="font-weight: bold"  @if(hasPermission(Session::get( 'adminId'),'mainPageSetting' ) <1 ) for="colorPicker" @endif style="font-weight: bold">انتخاب رنگ</label>
                                        <input type="color" color="red"  onchange="document.getElementById('colorDiv').style.backgroundColor=this.value;"
                                            class="form-control form-control-sm col-sm-4 rounded border-danger p-1 m-0" id="colorPicker" name="shegeftColor"  value="#ff0000">
                                    </div>
                                    <div class="col-sm-2" id="cp9">
                                        <label class="form-label btn btn-success btn-sm text-warning mt-3" for="photoPicker" @if(hasPermission(Session::get( 'adminId'),'mainPageSetting' ) <1 )  @endif id="photoPickerID" style="font-weight: bold"> انتخاب عکس <i class="fa-light fa-image fa-lg"></i> </label>
                                        <input type="file" onchange='document.getElementById("shegeftPicture").src = window.URL.createObjectURL(this.files[0]);' class="form-control d-none" id="photoPicker" name="shegeftPicture" value="">
                                    </div>
                                 </div>

                                <div id="colorDiv" class="col-sm-12 rounded mx-auto mt-2" style=" background-color:{{$part->partColor}};">
                                    <div class="form-group text-center">
                                        <img class="p-0 m-0" style="width: 20%; height:100px;" id="shegeftPicture" src="{{url('/resources/assets/images/shegeftAngesPicture/'.$part->partId.'.jpg')}}" alt="">
                                    </div>
                                </div>
                                @endif
                 
                            <div class="row rounded m-1">
                                <div class="form-group col-sm-4">
                                    <label class="form-label">جستجو</label>
                                    <input type="text" name="" class="form-control form-control-sm" @if($part->partType==13) id="pishKharidFirst" @else id="allKalaFirst" @endif autocomplete="off">
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
                            
                            <div class="grid-subgroup">
                                <div class="subgroup-item">
                                    <table class="table table-bordered table-sm">
                                        <thead class="tableHeader">
                                            <tr>
                                                <th>ردیف</th>
                                                <th>اسم </th>
                                                <th>
                                                    <input type="checkbox" name=""  @if(hasPermission(Session::get( 'adminId'),'mainPageSetting' ) <1 ) disabled @endif   class="selectAllFromTop form-check-input">
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="tableBody" style="height:300px !important;" id="kalaList">
                                            @foreach ($kalas as $kala)
                                                <tr @if(hasPermission(Session::get( 'adminId'),'mainPageSetting' ) <1 ) onclick="checkCheckBox(this,event)" @endif>
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

                                <div class="subgroup-item mt-5">
                                    <button style="background-color:transparent;" id="addData" type="button" @if(hasPermission(Session::get( 'adminId'),'mainPageSetting' ) <1 ) disabled @endif> <i class="fa-regular fa-circle-chevron-left fa-3x text-success"></i></button>
                                    <button style="background-color:transparent;" id="removeData" type="button" @if(hasPermission(Session::get( 'adminId'),'mainPageSetting' ) <1 ) disabled @endif> <i class="fa-regular fa-circle-chevron-right fa-3x text-success"></i></button>
                                </div>
                                <div class="subgroup-item">
                                      <table class="table table-bordered table-sm">
                                        <thead class="tableHeader">
                                            <tr>
                                                <th>ردیف</th>
                                                <th class="position-relative"> اسم کالا</th>
                                                <th>
                                                    <button style="background-color:transparent;"  @if(hasPermission(Session::get( 'adminId'),'mainPageSetting' ) <1 ) disabled @endif class='priority' id="down" type="button" value="down" ><i class="fa-solid fa-circle-chevron-down fa-sm" style=''></i></button> &nbsp;
                                                    <button style="background-color:transparent;"  @if(hasPermission(Session::get( 'adminId'),'mainPageSetting' ) <1 ) disabled @endif class='priority' id="top"  type="button" value="up" >  <i class="fa-solid fa-circle-chevron-up fa-sm" style=''></i></button>
                                                </th>
                                                <th>
                                                    <input type="checkbox" @if(hasPermission(Session::get( 'adminId'),'mainPageSetting' ) <1 ) disabled @endif name=""  class="selectAllFromTop form-check-input"  >
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="tableBody" style="height:300px !important;" id="addedKalaPart">
                                            @foreach ($addKalas as $kala)
                                                <tr @if(hasPermission(Session::get( 'adminId'),'mainPageSetting' ) <1 ) onClick="checkCheckBox(this,event)" @endif>
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
                        </form>
                    @endforeach
                </div>
                <div class="row contentFooter"> </div>
            </div>
    </div>
</div>

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

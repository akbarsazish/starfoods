@extends('admin.layout')
@section('content')
<style>
    *, html, body{
        margin: 0;
        padding: 0;
    }
    
    button:hover{ 
        /* background: linear-gradient(#9ed5b6, #198754); */
        background: linear-gradient(#d3fde4, #198754);
        color: #f1e253 !important;
        font-size: 18px; 
        font-weight: bold;
        border-color: #bb993a !important;
        border-width: 2px;
    }
    
    #addGroupData i:hover,
    #removeGroupData i:hover
    { 
        color: #f1e253 !important;
        font-size: 40px; 
        font-weight: bold;
        border-color: #bb993a !important;
        border-width: 2px;
    }

    button.btn-info {
        padding: 0;
        width: 122px;
        height: 34px;
    }

    button.btn-info{
        background-color: #198754;
        color: #bb993a;
        border-color: #000;
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
            <div class="o-headline" style="padding: 0; margin-bottom: 10px; margin-top: 0">
                <div id="main-cart" class="p-1">
                    <span class="c-checkout__tab--active">{{ $title }}</span>
                </div>
            </div>
            <div class="c-checkout" style="border-radius:10px 10px 2px 2px; padding:0">
                <div class="container p-1">
                    @foreach ($parts as $part)
                    <form class="p-1" action="{{ url('/doEditGroupPart') }}" method="POST" class='form' autocomplete="off">
                        @csrf
                        <div class="d-flex flex-row-reverse">
                            <button type="submit"  @if(hasPermission(Session::get( 'adminId'),'homePage' ) <1 ) disabled @endif class="btn btn-info btn-md text-warning" style="foloat:left;">ذخیره <i class="fa-light fa-save fa-lg"></i></button>
                        </div>
                        <div class='row m-2 p-0'>
                            <div class='c-checkout mx-auto rounded pl-0 pt-2 pb-3'>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class='form-group'>
                                            <label class='form-label'>اسم سطر</label>
                                            <input   @if(hasPermission(Session::get( 'adminId'),'homePage' ) <1 ) disabled @endif class="form-control form-control-sm" type='text' id='partTitle' value="{{   trim($part->partTitle) }}" autocomplete="off" class='form-control' name='partTitle' placeholder=''>
                                            <input type='text' style="display: none" id="partId" value="{{ $part->partId }}" class='form-control' name='partId' placeholder=''>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class='form-group'>
                                            <label class='form-label'>نوع دسته بندی</label>
                                            <select   @if(hasPermission(Session::get( 'adminId'),'homePage' ) <1 ) disabled @endif class="form-select form-select-sm" name='partType' onchange='showDiv(this)' class='form-control' id='partType'>
                                                <option value="{{ $part->partType }}">{{ $part->name }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class='form-group'>
                                            <label class='form-label'>اولویت</label>
                                            <select   @if(hasPermission(Session::get( 'adminId'),'homePage' ) <1 ) disabled @endif class="form-select form-select-sm"  type='text' id="priority"  class='form-select' name='partPriority' placeholder=''>
                                                @for ($i =3; $i <= (int)$countHomeParts; $i++)
                                                    <option @if((int)$part->priority==$i) selected @endif value="{{$i}}">{{$i-2}}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-1 d-flex align-items-stretch">
                                        <div class="form-group d-flex text-nowrap align-items-center pt-3">
                                            <input   @if(hasPermission(Session::get( 'adminId'),'homePage' ) <1 ) disabled @endif class="form-control d-flex form-check-input align-items-end" type="checkbox" name="activeOrNot" id="activeOrNot" @if ($part->activeOrNot==1) checked @else @endif >
                                            <label class="form-check-label align-items-end" style="font-weight: bold" for="activeOrNot">نمایش</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row p-0 m-2 mt-0" style="border: 1px solid #e7e7e7; border-radius:10px 10px 2px 2px;">
                            <div class="col-sm-12 p-3 py-0">
                                <div class='modal-body row py-0'>
                                    <div class="form-group col-sm-4 justify-content-start">
                                        <label class="form-label pt-2">جستجو</label>
                                        <input class="form-control form-control-sm" type="text" name="" size="20" id="searchThingGroups">
                                    </div>
                                    <div class="" style="width:27%">
                                    </div>
                                    <div class="form-group col-sm-4  text-nowrap  justify-content-start">
                                        <label class="form-label pt-2">جستجوی گروه</label>
                                        <select class="form-select form-select-sm" id="searchGroup">
                                            <option value="0">همه گروه ها</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <div class='modal-body'>
                                    <div class='c-checkout' style='padding-right:0;'>
                                        <table class="tableSection table table-bordered table table-hover table-sm table-light" style='td:hover{ cursor:move;}'>
                                            <thead>
                                                <tr>
                                                    <th>ردیف</th>
                                                    <th>گروه اصلی </th>
                                                    <th><input type="checkbox" @if(hasPermission(Session::get( 'adminId'),'homePage' ) <1 ) disabled @endif  class="selectAllFromTop form-check-input"  ></th>
                                                </tr>
                                            </thead>
                                            <tbody style="height: 400px; overflow-y: scroll;display:block;width:100%;"
                                                id="groupsPart">
                                                @foreach ($groups as $group)
                                                    <tr   @if(hasPermission(Session::get( 'adminId'),'homePage' ) > 0 )  onclick="checkCheckBox(this,event)" @endif>
                                                        <td>{{ $loop->index+1 }}</td>
                                                        <td>{{ $group->title }}</td>
                                                        <td>
                                                            <input class="mainGroupIds form-check-input" type="checkBox" name="mainGroupIds[]" value="{{ $group->id . '_' . $group->title }}" id="flexCheckChecked">
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 d-flex align-items-center">
                                <div class='modal-body'>
                                    <div>
                                        <button id="addGroupData" @if(hasPermission(Session::get( 'adminId'),'homePage' ) <1 ) disabled @endif>
                                            <i class="fa-regular fa-circle-chevron-left fa-3x"></i>
                                        </button>
                                        <button id="removeGroupData" @if(hasPermission(Session::get( 'adminId'),'homePage' ) <1 ) disabled @endif>
                                            <i class="fa-regular fa-circle-chevron-right fa-3x"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <div class='modal-body'>
                                    <div class='c-checkout' style='padding-right:0;'>
                                        <table class="tableSection table table-bordered table table-hover table-sm table-light" style='td:hover{ cursor:move;}'>
                                            <thead>
                                                <tr>
                                                    <th>ردیف</th>
                                                    <th class="position-relative"> گروه اصلی <div style="color:white!important;" class="rounded-circle d-flex position-absolute top-50 justify-content-end w-100 m-0 p-0 px-3">
                                                        
                                                        <button class='groupPartPriority' type="button" value="down"><i class="fa-solid fa-circle-chevron-down fa-sm" style=''></i></button>
                                                        <button class='groupPartPriority' type="button" value="up"><i class="fa-solid fa-circle-chevron-up fa-sm" style=''></i></button>
                                                        
                                                    </th>
                                                    <th><input type="checkbox" @if(hasPermission(Session::get( 'adminId'),'homePage' ) < 1 ) disabled @endif class="selectAllFromTop form-check-input"  ></th>
                                                </tr>
                                            </thead>
                                            <tbody style="height: 400px; overflow-y: scroll;display:block;width:100%;" id="addedGroups">
                                                @foreach ($addedGroups as $group)
                                                    <tr class="addedTr"  @if(hasPermission(Session::get( 'adminId'),'homePage' ) > 0 ) onClick="checkCheckBox(this,event)" @endif>
                                                        <td>{{ $loop->index+1}}</td>
                                                        <td>{{ $group->groupTitle }}</td>
                                                        <td>
                                                            <input class="mainGroupId  form-check-input" type="checkBox" name="groupIds[]" value="{{ $group->groupId}}" id="flexCheckChecked">
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </form>
                </div>
            </div>
        </div>
    </section>
    <script>
        window.onload = function() {
            //used for searching groups to add to a part
            $.ajax({
                method: 'get',
                url: "{{ url('/getSearchGroups') }}",
                async: true,
                success: function(arrayed_result) {
                    $('#searchGroup').empty();
                    $('#searchGroup').append('<option value="0">همه کالا ها</option>');
                    for (var i = 0; i <= arrayed_result.length - 1; i++) {
                        $('#searchGroup').append('<option value="' + arrayed_result[i].id + '">' +
                            arrayed_result[i].title + '</option>');
                    }
                },
                error: function(data) {
                    alert("پیدا نشد");
                }

            });
            //used for searching groups in right side
            $('#searchGroup').on('change', function() {
                //قسمت لیست کالاها آورده شود
                $.ajax({
                    method: 'get',
                    url: "{{ url('/getGroupSearch') }}",
                    async: true,
                    data: {
                        _token: "{{ csrf_token() }}",
                        groupId: $('#searchGroup').val()
                    },
                    success: function(arrayed_result) {
                        $('#groupsPart').empty();
                        for (var i = 0; i <= arrayed_result.length - 1; i++) {
                            $('#groupsPart').append(`<tr onClick="checkCheckBox(this,event)">
                                <td>` + (i+1) + `</td>
                                <td>` + arrayed_result[i].title + `</td>
                                <td>
                                    <input class="mainGroupId form-check-input" type="checkBox"
                                        name="mainGroupIds[]" value="` + arrayed_result[i].id +`_`+arrayed_result[i].title+ `"
                                        id="flexCheckChecked">
                                </td>
                            </tr>`);
                        }

                    },
                    error: function(data) {
                        alert("پیدا نشد");
                    }

                });
            });
            //used for searching by input to add groups to a part
            $('#searchThingGroups').on('keyup', function() {
                //قسمت لیست کالاها آورده شود
                $.ajax({
                    method: 'get',
                    url: "{{ url('/searchGroups') }}",
                    async: true,
                    data: {
                        _token: "{{ csrf_token() }}",
                        searchTerm:$('#searchThingGroups').val()
                    },
                    success: function(arrayed_result) {

                        $('#groupsPart').empty();
                        for (var i = 0; i <= arrayed_result.length - 1; i++) {
                            $('#groupsPart').append(`<tr onClick="checkCheckBox(this,event)">
                                <td>`+(i+1)+`</td>
                                <td>`+arrayed_result[i].title+`</td>
                                <td>
                                    <input class="mainGroupId  form-check-input" type="checkBox"
                                    name="mainGroupIds[]" value="` + arrayed_result[i].id +`_`+arrayed_result[i].title + `"
                                        id="flexCheckChecked">
                                </td>
                            </tr>`);
                        }
                    },
                    error: function(data) {
                        alert("پیدا نشد");
                    }

                });
            });
            //used for adding groups to left side
            $('#addGroupData').on('click', function() {
                var groupListIds = [];
                $('input[name="mainGroupIds[]"]:checked').map(function() {
                    groupListIds.push(($(this).val()));
                });
                $('input[name="mainGroupIds[]"]:checked').parents('tr').css('color','red');
                $('input[name="mainGroupIds[]"]:checked').prop("disabled", true);
                $('input[name="mainGroupIds[]"]:checked').prop("checked", false);
                for (let i = 0; i < groupListIds.length; i++) {
                    $('#addedGroups').append(`<tr class="addedTr" onClick="checkCheckBox(this,event)">
                                                    <td>` + (i+1) + `</td>
                                                    <td>` + groupListIds[i].split('_')[1] + `</td>
                                                    <td>
                                                        <input class="mainGroupId  form-check-input" type="checkBox" name="groupIds[]"
                                                            value="` + groupListIds[i].split('_')[0] + `"
                                                            id="flexCheckChecked" checked>
                                                    </td>
                                                </tr>`);

                }
            });
            //used for removing groups from a part
            $(document).on('click', '#removeGroupData', (function() {
                $('.addedTr').find('input:checkbox:checked').attr("name", "removable[]");
                $('.addedTr').has('input:checkbox:checked').hide();
            }));

           //used for setting priority of group in a part
            $(document).on('click', '.groupPriority', (function() {
                $.ajax({
                    method: 'get',
                    url: "{{ url('/changeGroupsPartPriority') }}",
                    async: true,
                    data: {
                        _token: "{{ csrf_token() }}",
                        groupId:$('input[name="groupIds[]"]:checked').val() ,
                        partId:$('#partId').val(),
                        priority:$(this).val()
                    },
                    success: function(arrayed_result) {
                        window.location.reload();
                    },
                    error: function(data) {
                        alert("not good");
                    }

                });
            }));
        }
    </script>
@endsection
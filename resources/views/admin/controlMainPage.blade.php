@extends('admin.layout')
@section('content')
<main>
    <div class='container-xl px-4' style='margin-top:5%;'>
            <h5 style="border-bottom:2px solid gray; width:50%">تنظیمات صفحه اصلی </h5>
        <div class='card mb-4'>
            <div class='card-body'>
                <div class='row'>
                    <div class='col-lg-12 text-end'>
                        <form action="{{ url('/editParts') }}" style="display: inline;" method="POST">
                            @if(hasPermission(Session::get("adminId"),"homePage") >1)
                                <a href="{{ url('/addNewGroup') }}" class='btn btn-sm btn-success buttonHover'>بخش جدید<i class="fa fa-plus fa-lg" aria-hidden="true"></i></a>
                            @endif
                                @csrf
                                <input type="text" id="partType" style="display: none" name="partType" value="" />
                                <input type="text" id="partId" style="display: none" name="partId" value="" />
                                <input type="text" id="partTitle" style="display: none" name="title" value="" />
                            @if(hasPermission(Session::get("adminId"),"homePage") > -1)
                                <button class='btn btn-info btn-sm text-white editButtonHover' disabled  type="submit" id='editPart'> ویرایش &nbsp; <i class="fa fa-edit fa-lg" aria-hidden="true"></i></button>
                            @endif
                        </form>
                        @if(hasPermission(Session::get("adminId"),"homePage") > 1)
                            <button class='btn btn-danger btn-sm disabled buttonHoverDelete' id='deletePart'> &nbsp;  حذف  &nbsp; <i class="fa fa-trash fa-lg" aria-hidden="true"></i></button>
                        @endif
                        @if(hasPermission(Session::get("adminId"),"homePage") > 0)
                            <form action="{{ url('/changePriority') }}" style="display:inline;" method="POST">
                                @csrf
                                <button name='changePriority' id="downArrow" disabled type="submit" value="down" style="background-color:rgb(255 255 255); padding:0;"> <i class="fa-solid fa-circle-chevron-down fa-2x chevronHover"></i></button>
                                <button name='changePriority' id="upArrow" disabled type="submit" value="up" style="background-color:rgb(255 255 255); padding:0;"> <i class="fa-solid fa-circle-chevron-up fa-2x chevronHover"></i></button>
                            
                        @endif
                    </div>
                </div>
                <div class='row pt-3'>
                    <div class='col-lg-12'>
                        <table class='table table-hover table-bordered table-sm table-light' id='myTable'>
                            <thead class="table bg-success text-white tableHeader">
                                <tr >
                                    <th>ردیف</th>
                                    <th>سطر</th>
                                    <th>اولویت</th>
                                    <th>فعال</th>
                                    <th>انتخاب</th>
                                </tr>
                            </thead>
                            <tbody class="tableBody">
                                @foreach ($parts as $part)
                                    <tr  id='1' >
                                        <td  style="">{{ $loop->index+1 }}</td>
                                        <td >{{ $part->title }}</td>
                                        <td>@if($part->partType==3 or $part->partType==4)@else {{ $part->priority-2 }} @endif</td>
                                        <td>@if($part->partType==3)@else <input class='form-check-input' type='checkbox' disabled value='' id='flexCheck' @if($part->activeOrNot == 1 ) checked @endif /> @endif</td>
                                        <td><input type="radio" value="{{ $part->id . '_' . $part->priority . '_' . $part->partType. '_' . $part->title }}" class="mainGroups form-check-input" name="partId"></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</main>
    <!-- modal of editing Items -->

    <!-- end of editing modal of Items -->
    <script type='text/javascript'>
        // function showDiv(select) {
        //     var groupsPart = `
        //         <div class='well' style='margin-top:2%;' id="groupsPart">
        //             <div class='well'>
        //                     <p style='display:block;'>گروه های اصلی</p>
        //                 </div>
        //                 <div class='alert'>
        //                     <input type='text'  class='form-control'  style='margin-top:10px;' name='search_mainPart' placeholder='جستجو'>
        //                 </div>
        //                 <table class='table table-bordered' style='width:100%;'>
        //                         <thead>
        //                             <tr>
        //                                 <th>ردیف</th>
        //                                 <th>گروه اصلی </th>
        //                                 <th>گروه فرعی </th>
        //                                 <th >فعال</th>
        //                             </tr>
        //                         </thead>
        //                         <tbody id='mainGroupPart'>

        //                         </tbody>
        //                 </table>
        //             </div>
        //         </div>`;
        //     var kalaList = `<div class="container">
        //         <div class="row">
        //             <div class="col-sm-12">
        //                 <div class="well" style="margin-top:2%;">
        //                 <div class="alert">
        //                     <input type="text" name="search_mainPart" class="form-control" id="basic-url" aria-describedby="basic-addon3" placeholder="جستجوی اسم">
        //                     <select name="cars" class="form-control" id="cars">
        //                     <option value="volvo">جستجوی بر اساس گروه اصالی</option>
        //                     <option value="volvo">جستجوی بر اساس گروه فرعی</option>
        //                     </select>
        //                     <select name="cars" class="form-control" id="cars">
        //                     <option value="volvo">فعال</option>
        //                     <option value="saab">غیر فعال</option>
        //                     </select>
        //                 </div>
        //                     <table class="table table-bordered" style="width:100%;">
        //                         <thead>
        //                         <tr>
        //                             <th>کد</th>
        //                             <th>اسم</th>
        //                             <th>موجودی </th>
        //                             <th>انتخاب </th>
        //                         </tr>
        //                         </thead>
        //                         <tbody>
        //                         <tr>
        //                             <td>567</td>
        //                             <td>456</td>
        //                             <td>45</td>
        //                             <td>
        //                             <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" checked>
        //                             </td>
        //                         </tr>
        //                         <tr>
        //                         <td>452</td>
        //                             <td>456</td>
        //                             <td>45</td>
        //                             <td>
        //                             <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" checked>
        //                             </td>
        //                         </tr>
        //                         </tbody>
        //                     </table>
        //                 </div>
        //             </div>
        //     </div>`;

        //     var twoPic = `<div class="container">
        //             <div class="row">
        //                 <div class="col-sm-6">
        //                     <label for="firstPic" class="form-label">تصویر اول</label>
        //                         <input type="file" class="form-control" name="" id="">
        //                 </div>
        //                 <div class="col-sm-6">
        //                 <label for="firstPic" class="form-label">تصویر دوم</label>
        //                     <input type="file" class="form-control" name="" id="">
        //                 </div>
        //             </div>
        //         </div>`;

        //     var threePic = `<div class="container">
        //             <div class="row">
        //                 <div class="col-sm-4">
        //                     <label for="firstPic" class="form-label">تصویر اول</label>
        //                         <input type="file" class="form-control" name="" id="">
        //                 </div>
        //                 <div class="col-sm-4">
        //                 <label for="firstPic" class="form-label">تصویر دوم</label>
        //                     <input type="file" class="form-control" name="" id="">
        //                 </div>
        //                 <div class="col-sm-4">
        //                 <label for="firstPic" class="form-label">تصویر سوم</label>
        //                     <input type="file" class="form-control" name="" id="">
        //                 </div>
        //             </div>
        //         </div>`;

        //     var fourPic = `<div class="container">
        //             <div class="row">
        //                 <div class="col-sm-3">
        //                     <label for="firstPic" class="form-label">تصویر اول</label>
        //                         <input type="file" class="form-control" name="" id="">
        //                 </div>
        //                 <div class="col-sm-3">
        //                 <label for="firstPic" class="form-label">تصویر دوم</label>
        //                     <input type="file" class="form-control" name="" id="">
        //                 </div>
        //                 <div class="col-sm-3">
        //                 <label for="firstPic" class="form-label">تصویر سوم</label>
        //                     <input type="file" class="form-control" name="" id="">
        //                 </div>
        //                 <div class="col-sm-3">
        //                 <label for="firstPic" class="form-label">تصویر چهارم</label>
        //                     <input type="file" class="form-control" name="" id="">
        //                 </div>
        //             </div>
        //         </div>`;

        //     var fivePic = `<div class="container">
        //             <div class="row">
        //                 <div class="col-sm-3">
        //                     <label for="firstPic" class="form-label">تصویر اول</label>
        //                         <input type="file" class="form-control" name="" id="">
        //                 </div>
        //                 <div class="col-sm-3">
        //                 <label for="firstPic" class="form-label">تصویر دوم</label>
        //                     <input type="file" class="form-control" name="" id="">
        //                 </div>
        //                 <div class="col-sm-3">
        //                 <label for="firstPic" class="form-label">تصویر سوم</label>
        //                     <input type="file" class="form-control" name="" id="">
        //                 </div>
        //                 <div class="col-sm-3">
        //                 <label for="firstPic" class="form-label">تصویر چهارم</label>
        //                     <input type="file" class="form-control" name="" id="">
        //                 </div>
        //             </div>
        //             <div class="row">
        //                 <div class="col-sm-12">
        //                 <label for="firstPic" class="form-label">تصویر اصلی</label>
        //                         <input type="file" class="form-control" name="" id="">
        //                 </div>
        //             </div>
        //         </div>`;
        //     var mainSlider = `<div class="container">
        //             <div class="row">
        //                 <div class="col-sm-3">
        //                     <label for="firstPic" class="form-label">تصویر اول</label>
        //                         <input type="file" class="form-control" name="" id="">
        //                 </div>
        //                 <div class="col-sm-3">
        //                 <label for="firstPic" class="form-label">تصویر دوم</label>
        //                     <input type="file" class="form-control" name="" id="">
        //                 </div>
        //                 <div class="col-sm-3">
        //                 <label for="firstPic" class="form-label">تصویر سوم</label>
        //                     <input type="file" class="form-control" name="" id="">
        //                 </div>
        //                 <div class="col-sm-3">
        //                 <label for="firstPic" class="form-label">تصویر چهارم</label>
        //                     <input type="file" class="form-control" name="" id="">
        //                 </div>
        //             </div>
        //             <div class="row">
        //                 <div class="col-sm-12">
        //                 <label for="firstPic" class="form-label">تصویر پنجم</label>
        //                         <input type="file" class="form-control" name="" id="">
        //                 </div>
        //             </div>
        //         </div>`;
        //     var firstSmallSlider = `<div class="container">
        //             <div class="row">
        //                 <div class="col-sm-3">
        //                     <label for="firstPic" class="form-label">تصویر اول</label>
        //                         <input type="file" class="form-control" name="" id="">
        //                 </div>
        //                 <div class="col-sm-3">
        //                 <label for="firstPic" class="form-label">تصویر دوم</label>
        //                     <input type="file" class="form-control" name="" id="">
        //                 </div>
        //                 <div class="col-sm-3">
        //                 <label for="firstPic" class="form-label">تصویر سوم</label>
        //                     <input type="file" class="form-control" name="" id="">
        //                 </div>
        //                 <div class="col-sm-3">
        //                 <label for="firstPic" class="form-label">تصویر چهارم</label>
        //                     <input type="file" class="form-control" name="" id="">
        //                 </div>
        //             </div>
        //         </div>`;
        //     var secondSmallSlider = `<div class="container">
        //             <div class="row">
        //                 <div class="col-sm-3">
        //                     <label for="firstPic" class="form-label">تصویر اول</label>
        //                         <input type="file" class="form-control" name="" id="">
        //                 </div>
        //                 <div class="col-sm-3">
        //                 <label for="firstPic" class="form-label">تصویر دوم</label>
        //                     <input type="file" class="form-control" name="" id="">
        //                 </div>
        //                 <div class="col-sm-3">
        //                 <label for="firstPic" class="form-label">تصویر سوم</label>
        //                     <input type="file" class="form-control" name="" id="">
        //                 </div>
        //                 <div class="col-sm-3">
        //                 <label for="firstPic" class="form-label">تصویر چهارم</label>
        //                     <input type="file" class="form-control" name="" id="">
        //                 </div>
        //             </div>
        //         </div>`;
        //     switch (select.value) {
        //         case "1":
        //             document.getElementById('newGroup').innerHTML = "";
        //             document.getElementById('newGroup').innerHTML = kalaList;
        //             break;
        //         case "2":
        //             document.getElementById('newGroup').innerHTML = "";
        //             document.getElementById('newGroup').innerHTML = groupsPart;
        //             break;
        //         case "3":
        //             document.getElementById('newGroup').innerHTML = "";
        //             document.getElementById('newGroup').innerHTML = mainSlider;
        //             break;
        //         case "4":
        //             document.getElementById('newGroup').innerHTML = "";
        //             document.getElementById('newGroup').innerHTML = firstSmallSlider;
        //             break;
        //         case "5":
        //             document.getElementById('newGroup').innerHTML = "";
        //             document.getElementById('newGroup').innerHTML = secondSmallSlider;
        //             break;
        //         case "6":
        //             document.getElementById('newGroup').innerHTML = "";
        //             document.getElementById('newGroup').innerHTML = onePic;
        //             break;
        //         case "7":
        //             document.getElementById('newGroup').innerHTML = "";
        //             document.getElementById('newGroup').innerHTML = twoPic;
        //             break;
        //         case "8":
        //             document.getElementById('newGroup').innerHTML = "";
        //             document.getElementById('newGroup').innerHTML = threePic;
        //             break;
        //         case "9":
        //             document.getElementById('newGroup').innerHTML = "";
        //             document.getElementById('newGroup').innerHTML = fourPic;
        //             break;
        //         case "10":
        //             document.getElementById('newGroup').innerHTML = "";
        //             document.getElementById('newGroup').innerHTML = fivePic;
        //             break;
        //         default:
        //             document.getElementById('newGroup').innerHTML = "";
        //             break;
        //     }
        // }

        // function changeEditModal() {
        //     const btn = document.querySelector('#editPart');
        //     const radioButtons = document.querySelectorAll('input[name="part"]');
        //     for (const radioButton of radioButtons) {
        //         if (radioButton.checked) {
        //             document.querySelector('#last').style.display = "none";
        //             break;
        //         }
        //     }
        // }
        // window.onload = function() {
        //     $(document).on('click', '#editPart', (function() {

        //         var groupsPart = `
        //         <div class='well' style='margin-top:2%;' id="groupsPart">
        //             <div class='well'>
        //                     <p style='display:block;'>گروه های اصلی</p>
        //                 </div>
        //                 <form class="form" action="{{ url('/deleteGroupPart') }}" style="display:inline">
        //                 <button type="button" id="deleteGroupPart" class="btn btn-info" value="">حذف</button>
        //                 <button type="button" id="addGroupPart" class="btn btn-info" value="">اضافه</button>
        //                 <div class='alert'>
        //                     <input type='text'  class='form-control'  style='margin-top:10px;' name='search_mainPart' placeholder='جستجو'>
        //                 </div>
        //                 <table class='table table-bordered' style='width:100%;'>
        //                         <thead>
        //                             <tr>
        //                                 <th>ردیف</th>
        //                                 <th>گروه اصلی </th>
        //                                 <th>موجودی </th>
        //                                 <th >انتخاب</th>
        //                             </tr>
        //                         </thead>
        //                         <tbody id='mainGroupPart'>

        //                         </tbody>
        //                 </table>
        //                 </form>
        //             </div>
        //         </div>`;

        //         var kalaPart = `<div class="container">
        //         <div class="row">
        //             <div class="col-sm-12">
        //                 <div class="well" style="margin-top:2%;">
        //                     <button type="button" id="deleteKalaPart" class="btn btn-info" value="">حذف</button>
        //                 <button type="button" id="addKalaPart" class="btn btn-info" value="">اضافه</button>
        //                 <div class="alert">
        //                     <input type="text" name="search_mainPart" class="form-control" id="basic-url" aria-describedby="basic-addon3" placeholder="جستجوی اسم">
        //                     <select name="cars" class="form-control" id="cars">
        //                     <option value="volvo">جستجوی بر اساس گروه اصالی</option>
        //                     <option value="volvo">جستجوی بر اساس گروه فرعی</option>
        //                     </select>
        //                     <select name="cars" class="form-control" id="cars">
        //                     <option value="volvo">فعال</option>
        //                     <option value="saab">غیر فعال</option>
        //                     </select>
        //                 </div>
        //                     <table class="table table-bordered" style="width:100%;">
        //                         <thead>
        //                         <tr>
        //                             <th>ردیف</th>
        //                             <th>اسم</th>
        //                             <th>موجودی </th>
        //                             <th>انتخاب </th>
        //                         </tr>
        //                         </thead>
        //                         <tbody id='kalaPart'>

        //                         </tbody>
        //                     </table>
        //                 </div>
        //             </div>
        //     </div>`;

        //         $partId = $('.mainGroups:checked').val().split('_')[2];
        //         if ($partId == 1) {
        //             $.ajax({
        //                 method: 'get',
        //                 async: true,
        //                 dataType: 'text',
        //                 url: "{{ url('/getGroups') }}",
        //                 data: {
        //                     _token: "{{ csrf_token() }}",
        //                     id: $('.mainGroups:checked').val().split('_')[0]
        //                 },
        //                 success: function(answer) {
        //                     answer = $.parseJSON(answer);
        //                     $('#partContent').empty();
        //                     $('#partContent').append(groupsPart);
        //                     $("#editPart2").modal("show");

        //                     for (var i = 0; i <= answer.length - 1; i++) {
        //                         var check;
        //                         if (answer[i].existance == 1) {
        //                             check = 'checked';
        //                         } else {
        //                             check = '';
        //                         }
        //                         $('#mainGroupPart').append(
        //                             `<tr>
        //                                     <td>` + (i + 1) + `</td>
        //                                     <td>` + answer[i].title +
        //                             `</td>
        //                                     <td><input class="groupExist"  name="groupExist" value="" type="checkBox" id=""` +
        //                             check + ` ></td>
        //                                     <td><input class="subGroupId"  name="groupListIds" value="` + answer[i]
        //                             .id + `_` + answer[i].homepartId + `" type="checkBox" id="deleteGroup"></td>
        //                                     </tr>`);
        //                     }
        //                 }
        //             });
        //         } else {
        //             //if part is for kala list
        //             if ($partId == 2) {
        //                 $.ajax({
        //                     method: 'get',
        //                     async: true,
        //                     dataType: 'text',
        //                     url: "{{ url('/getKalas') }}",
        //                     data: {
        //                         _token: "{{ csrf_token() }}",
        //                         id: $('.mainGroups:checked').val().split('_')[0]
        //                     },
        //                     success: function(answer) {
        //                         answer = $.parseJSON(answer);
        //                         $('#partContent').empty();
        //                         $('#partContent').append(kalaPart);
        //                         $("#editPart2").modal("show");
        //                         for (var i = 0; i <= answer.length - 1; i++) {

        //                             var check;
        //                             if (answer[i].existance == 1) {
        //                                 check = 'checked';
        //                             } else {
        //                                 check = '';
        //                             }
        //                             $('#kalaPart').append(
        //                                 ` <tr>
        //                             <td>` + (i + 1) + `</td>
        //                             <td>` + answer[i].GoodName + `</td>
        //                             <td><input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" ` +
        //                                 check + `></td>
        //                             <td>
        //                             <input class="form-check-input" name="kalaListIds" type="checkbox" value="` +
        //                                 answer[i].GoodSn + `_` + answer[i].homepartId + `" id="flexCheckChecked">
        //                             </td>
        //                         </tr>`);
        //                         }
        //                     }
        //                 });
        //             }
        //         }
        //     }));
        //     //used for deleting groups from a part
        //     $(document).on('click', '#deleteGroupPart', (function() {

        //         var groupListID = [];
        //         $('input[name=groupListIds]:checked').map(function() {
        //             groupListID.push($(this).val());
        //         });
        //         $.ajax({
        //             method: 'get',
        //             async: true,
        //             dataType: 'text',
        //             url: "{{ url('/deletePart') }}",
        //             data: {
        //                 _token: "{{ csrf_token() }}",
        //                 id: groupListID
        //             },
        //             success: function(answer) {
        //                 answer = $.parseJSON(answer);
        //                 $('#partContent').empty();
        //                 $('#partContent').append(groupsPart);
        //                 $("#editPart2").modal("show");

        //                 for (var i = 0; i <= answer.length - 1; i++) {
        //                     $('#mainGroupPart').append(
        //                         `<tr>
        //                                     <td>` + (i + 1) + `</td>
        //                                     <td>` + answer[i].title + `</td>
        //                                     <td><input class="subGroupId"  name="subGroupId[]" value="` + answer[i]
        //                         .firstGroupId + `" type="checkBox" id="deleteGroup"></td>
        //                                     </tr>`);
        //                 }
        //             }
        //         });

        //     }));
        //     //used for adding groups to a part in editing a part

        //     $(document).on('click', '#addGroupPart', (function() {

        //         var groupListID = [];
        //         $('input[name=groupListIds]:checked').map(function() {
        //             groupListID.push($(this).val());
        //         });
        //         $.ajax({
        //             method: 'get',
        //             async: true,
        //             dataType: 'text',
        //             url: "{{ url('/addPart') }}",
        //             data: {
        //                 _token: "{{ csrf_token() }}",
        //                 id: groupListID
        //             },
        //             success: function(answer) {
        //                 alert('اضافه شد');
        //             }
        //         });

        //     }));
        //     //used for deleting kalas from a part
        //     $(document).on('click', '#deleteKalaPart', (function() {

        //         var kalaListID = [];
        //         $('input[name=kalaListIds]:checked').map(function() {
        //             kalaListID.push($(this).val());
        //         });
        //         $.ajax({
        //             method: 'get',
        //             async: true,
        //             dataType: 'text',
        //             url: "{{ url('/deleteKalaPart') }}",
        //             data: {
        //                 _token: "{{ csrf_token() }}",
        //                 id: kalaListID
        //             },
        //             success: function(answer) {
        //                 answer = $.parseJSON(answer);
        //                 $('#partContent').empty();
        //                 $('#partContent').append(groupsPart);
        //                 $("#editPart2").modal("show");
        //             }
        //         });

        //     }));

        //     //used for adding kalas to a part in editing a part

        //     $(document).on('click', '#addKalaPart', (function() {

        //         var kalaListID = [];
        //         $('input[name=kalaListIds]:checked').map(function() {
        //             kalaListID.push($(this).val());
        //         });
        //         $.ajax({
        //             method: 'get',
        //             async: true,
        //             dataType: 'text',
        //             url: "{{ url('/addKalaPart') }}",
        //             data: {
        //                 _token: "{{ csrf_token() }}",
        //                 id: kalaListID
        //             },
        //             success: function(answer) {
        //                 alert('اضافه شد');
        //             }
        //         });
        //     }));

        // }
    </script>
@endsection

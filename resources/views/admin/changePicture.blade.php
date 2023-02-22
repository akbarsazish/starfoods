@extends('admin.layout')
@section('content')
    <section class="container" style="margin-top:90px;">
           <h5 style="border-bottom:2px solid gray; width:50%">تخصیص تصویر کالا</h5>
            @php
              $allGroups=count($mainGroups);
            @endphp
                    <div class="row" style="border:1px solid gray; padding:5px; border-radius:10px;">
                        <div class="col-sm-6">
                            <div class="well" style="margin-top:2%;">
                                    <h6 style="">گروه های اصلی</h6>
                                    {{-- <a style="margint:0"  href="{{ url('/addGroup') }}" class="btn btn-success btn-sm" data-toggle="modal"
                                        data-target="#newGroup">جدید <i class="fa fa-plus fa-lg" aria-hidden="true"></i></a> --}}
                                    <button type="button" value="Reterive data" class="btn btn-info btn-sm text-white" style="display: none"
                                        onclick="getGroupId()" data-toggle="modal" id="editGroupList" disabled data-target="#editGroup">ویرایش <i class="fa fa-edit fa-lg" aria-hidden="true"></i></button>
                                    <form action='{{ url('/deleteMainGroup') }}' onsubmit="return confirm('میخوهید حذف کنید?');" method='post' style=" margin:0; padding:0; display: inline;">
                                        @csrf
                                        <button type="submit" style="display: none" disabled id="deleteGroupList" class="btn btn-danger btn-sm">حذف <i class="fa fa-trash fa-lg" aria-hidden="true"></i></button>
                                        <Button  id="topGroup"  style="display: none"  type="button" value="top" onclick="changeMainGroupPriority(this)" style="background-color:rgb(255 255 255); padding:0;" ><i class="fa-solid fa-circle-chevron-up fa-2x" style=''></i></i></Button>
                                        <Button  id="downGroup"  style="display: none"  type="button" value="down" onclick="changeMainGroupPriority(this)" style="background-color:rgb(255 255 255); padding:0;" ><i class="fa-solid fa-circle-chevron-down fa-2x" style=''></i></i></Button>
                                        <input type="text" value="" id="mianGroupId" style="display: none"/>
                                        <br />
                                        <input type="text" class="form-control" id="mainGroupSearch" autocomplete="off" style="margin-top:10px;" name="search_mainPart" placeholder="جستجو"> <br>
                                </div>
                                    <table class="table table-bordered table table-hover" id="tableGroupList">
                                        <thead class="tableHeader">
                                            <tr>
                                                <th>ردیف</th>
                                                <th>گروه اصلی </th>
                                                <th>فعال</th>
                                            </tr>
                                        </thead>
                                        <tbody class="tableBody"id="mainGroupList">
                                            @foreach ($mainGroups as $group)
                                                <tr onclick="changePicture(this)">
                                                    <td>{{ $loop->index+1 }}</td>
                                                    <td>{{ $group->title }}</td>
                                                    <td>
                                                        <input class="mainGroupId" type="radio" name="mainGroupId[]"
                                                            value="{{ $group->id . '_' . $group->title }}"
                                                            id="flexCheckChecked">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                        <div class="col-sm-6">
                            <div class="well" style="margin-top:2%;">
                                    <h6 style="">گروه های فرعی</h6>
                                    <button class="btn btn-success btn-sm"  style="display: none"
                                        onclick="addNewSubGroup()"  style="display: none" disabled  id="addNewSubGroupButton"  data-toggle="modal" data-target="#newSubGroup"> جدید <i class="fa fa-plus fa-lg" aria-hidden="true"></i></button>
                                    <button  class="btn btn-info btn-sm text-white" disabled id="editSubGroupButton"  style="display: none"  data-toggle="modal"
                                        data-target="#editSubGroup"> ویرایش <i class="fa fa-edit fa-lg" aria-hidden="true"></i></button>
                                        <form action='{{ url('/deleteSubGroup') }}' method='post' onsubmit="return confirm('میخوهید حذف کنید?');" style=" margin:0; padding:0; display: inline;">
                                            @csrf
                                            <button id="deleteSubGroup"  style="display: none" disabled class="btn btn-danger btn-sm"> حذف <i class="fa fa-trash fa-lg" aria-hidden="true"></i></button>
                                            <input type="text"  value="" name="id" style="display: none" id="subGroupIdForDelete"/>
                                            <button onclick="changeSubGroupPriority(this)"  style="display: none" value="top"  type="button" style="background-color:rgb(255 255 255); padding:0;" ><i class="fa-solid fa-circle-chevron-up fa-2x" style=''></i></i></button>
                                            <button onclick="changeSubGroupPriority(this)"  style="display: none" value="down"  type="button" style="background-color:rgb(255 255 255); padding:0;"><i class="fa-solid fa-circle-chevron-down fa-2x" style=''></i></button>
                                            <br />
                                        </form>
                                        <input type="text" class="form-control" style="margin-top:10px;" id="serachSubGroupId"  placeholder="جستجو"> <br>
                                </div>
                                <div class=" c-checkout" style="max-height: 390px; padding:0; margin:0">
                                    <table id="subGroupTable" class="homeTables table-bordered table table-hover" id="tableGroupList">
                                        <thead class="tableHeader">
                                            <tr>
                                                <th >ردیف </th>
                                                <th> گروه فرعی </th>
                                                <th>تصویر</th>
                                            </tr>
                                        </thead>
                                        <tbody class="tableBody" id="subGroup1">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="addKalaToGroup" style="display: none">
                        <div class="o-page__content" style="margin-top:170px;">
                            <article>
                                <div class="c-listing">
                                    <ul class="c-listing__items" id="containPictureDiv">
                                    </ul>
                                </div>
                            </article>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal of new group -->
        <div class="modal fade" id="newGroup" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="margin:0; border:none">
                        <h5 class="modal-title" id="exampleModalLongTitle"> دسته بندی جدید </h5>
                        <button type="button" class="close btn text-danger" data-dismiss="modal" aria-label="Close" style="background-color:rgb(255 255 255); padding:0; padding-left:7px;"><i class="fa-solid fa-xmark fa-xl"></i>
                        </button>
                    </div>
                    <div class="modal-body" style="padding:5%; padding-right:0; padding-top:0; margin-right:10px">
                        <div class="c-checkout" style="padding:5%; padding-right:0; padding-top:0;">
                            <form action="{{url('/addMainGroup')}}" method="POST" id="createNewMainGroup" enctype="multipart/form-data" class="form">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label class="form-label"> اسم دسته بندی </label>
                                    <input type="text" required class="form-control" autocomplete="off" name="mainGroupName" id="mainGroupName"
                                        placeholder="">
                                </div>
                                <div class="form-group">
                                    <label class="form-label"> اولویت </label>
                                    <select class="form-select" name="priority">
                                        @for ($i = 1; $i <= ($allGroups+1); $i++)
                                            <option value="{{$i}}" >{{$i}}</option>
                                        @endfor
                                    </select>
                                </div>
                                    <div class="form-group">
                                    <label class="form-label"> عکس </label>
                                    <input type="file" class="form-control" name="mainGroupPicture" placeholder="">
                                </div>
                                <div class="form-group" style="margin-top:4%">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">انصراف <i class="fa-solid fa-xmark fa-lg"></i></button>
                                    <button type="submit" id="submitNewGroup" class="btn btn-secondary">ذخیره <i class="fa fa-save fa-lg" aria-hidden="true"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end modal new group -->
        <!-- modal of editig groups -->
        <div class="modal fade" id="editGroup" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="margin:0; border:none">
                        <h5 class="modal-title" id="exampleModalLongTitle">ویرایش دسته بندی</h5>
                        <button type="button" class="close btn text-danger" data-dismiss="modal" aria-label="Close" style="background-color:rgb(255 255 255); padding:0; padding-left:7px;"><i class="fa-solid fa-xmark fa-xl"></i>
                        </button>
                    </div>
                    <div class="modal-body" style="padding:5%; padding-right:0; padding-top:0; margin-right:10px">
                        <div class="c-checkout" style="padding:5%; padding-right:0; padding-top:0;">
                            <form action="{{ url('/editMainGroup') }}" class="form"
                                enctype="multipart/form-data" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label class="form-label">اسم دسته بندی</label>
                                    <input type="text" size="10px" required class="form-control" name="groupName" id="groupName"
                                        placeholder="">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">اولویت</label>
                                    <select class="form-select" name="priority">
                                            @for ($i = 1; $i <= ($allGroups); $i++)
                                                <option @if($i==4) selected @endif value="{{$i}}" >{{$i}}</option>
                                            @endfor
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" style="display:none;" name="groupId"
                                        id="groupId" placeholder="">
                                </div>
                                <div class="form-group" style="margin-top:4%">
                                    <label for="groupPicture" id="groupPicturelabel" class='btn btn-success btn-md' class="form-label"> انتخاب عکس <i class="fa-solid fa-image fa-lg"></i></label>
                                    <input type="file" class="form-control" style="display:none;" name="groupPicture" id="groupPicture" placeholder="">
                                     &nbsp;&nbsp;&nbsp;
                                    <button class="btn btn-secondary"> ذخیره  <i class="fa fa-save fa-lg" aria-hidden="true"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end modal editing -->
        <!-- modal of editig subgroups -->
        <div class="modal fade" id="editSubGroup" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="margin:0; border:none">
                        <h5 class="modal-title" id="exampleModalLongTitle"> ویرایش دستبندی فرعی</h5>
                        <button type="button" class="close btn text-danger" data-dismiss="modal" aria-label="Close" style="background-color:rgb(255 255 255); padding:0; padding-left:7px;"><i class="fa-solid fa-xmark fa-xl"></i>
                        </button>
                    </div>
                    <div class="modal-body" style="padding:5%; padding-right:0; padding-top:0; margin-right:10px">
                        <div class="c-checkout" style="padding:5%; padding-right:0; padding-top:0;">
                            <form action="{{ url('/editSubgroup') }}" class="form"
                                enctype="multipart/form-data" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label class="form-label"> اسم دستبندی </label>
                                    <input type="text" required class="form-control" name="subGroupNameEdit" id="subGroupNameEdit"
                                        placeholder="">
                                </div>
                                <div class="form-group">
                                    <label class="form-label"> اولویت </label>
                                    <select class="subGroupCount form-select" name="priority">
                                    </select>
                                </div>
                                <input type="text" class="form-control" name="fatherMainGroupId"
                                    id="fatherMainGroupIdEdit" style="display: none">
                                <input type="text" class="form-control" name="subGroupId" id="subGroupIdEdit"
                                    style="display: none">
                                <div class="form-group" style="margin-top:4%">
                                    <label for="subGroupPictureEdit" id="subGroupPictureEditlabel" class='btn btn-success btn-md' class="form-label"> انتخاب عکس <i class="fa-solid fa-image fa-lg"></i></label>
                                    <input type="file"  class="form-control" style="display:none" name="subGroupPictureEdit" id="subGroupPictureEdit" placeholder="">
                                        &nbsp;&nbsp;&nbsp;
                                    <button class="btn btn-secondary"> ذخیره <i class="fa fa-save fa-lg" aria-hidden="true"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end modal editing -->
        <!-- modal of new subgroup -->
        <div class="modal fade" id="newSubGroup" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="margin:0; border:none">
                        <h5 class="modal-title" id="exampleModalLongTitle">دسته بندی فرعی جدید</h5>
                        <button type="button" class="close btn text-danger" data-dismiss="modal" aria-label="Close" style="background-color:rgb(255 255 255); padding:0; padding-left:7px;"><i class="fa-solid fa-xmark fa-xl"></i>
                        </button>
                    </div>
                    <div class="modal-body" style="padding:5%; padding-right:0; padding-top:0; margin-right:10px">
                        <div class="c-checkout" style="padding:5%; padding-right:0; padding-top:0;">
                            <form action="{{ url('/addSubGroup') }}" class="form" enctype="multipart/form-data" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label class="form-label">اسم دستبندی</label>
                                    <input type="text" required class="form-control" autocomplete="off" name="groupTitle" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label class="form-label"> عکس </label>
                                    <input type="file" class="form-control" name="subGroupPicture" placeholder="">
                                </div>
                                <input type="text" value="" style="display: none" name="fatherMainGroupId" id="fatherMainGroupId">
                                <input type="text" value="" style="display: none" id="selfGroupId">
                                <div class="form-group">
                                    <label class="form-label"> اولویت </label>
                                    <select class="subGroupCount form-select" name="priority" >
                                    </select>
                                </div>
                                <div class="form-group" style="margin-top:4%">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">انصراف <i class="fa-solid fa-xmark fa-lg"></i></button>
                                    <button type="submit" class="btn btn-secondary">ذخیره <i class="fa fa-save fa-lg" aria-hidden="true"></i></button>
                                </div>
                            </form>
                        </div>
                </div>
            </div>
        </div>
        <!-- end modal new group -->
    </section>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <script>
        function getGroupId() {
            var checkedValue = document.querySelector('.mainGroupId:checked').value;
            var groupProperties = checkedValue.split("_");
            document.querySelector('#groupName').value = groupProperties[1].trim();
            document.querySelector('#groupPercent').value = groupProperties[0];
            document.querySelector('#groupId').value = groupProperties[0];
        }
        function addNewSubGroup() {
            document.querySelector('#fatherMainGroupId').value = document.querySelector('.mainGroupId:checked').value.split(
                '_')[0];
                document.querySelector(".countGroups").append("<option>good</option>");
        }
        window.onload = function() {
            $(document).on('change', '#groupPicture', (function() {
                $('#groupPicturelabel').css('background-color','#6c757d');
            }));
            $(document).on('change', '#subGroupPictureEdit', (function() {
                 $('#subGroupPictureEditlabel').css('background-color','#6c757d');
            }));
        }
    </script>
@endsection

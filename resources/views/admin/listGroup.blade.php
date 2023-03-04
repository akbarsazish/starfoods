@extends('admin.layout')
@section('content')
    <section class="container" style="margin-top:5%; width:1200px">
             <h5 style="width:50%; border-bottom:2px solid gray">دسته بندی ها</h5> <br>
              
            @php
              $allGroups=count($mainGroups);
            @endphp
                    <div class="row" style="border:1px solid gray; border-radius:10px; padding:10px;">
                        <div class="col-sm-5">
                             <div class="row">
                                    <div class="col-sm-3 mt-2">
                                        <h6>گروه های اصلی </h6>
                                    </div>
                                     <div class="col-sm-9">
                                            <button style="margint:0"  @if(hasPermission(Session::get("adminId"),"listGroups") < 2) disabled @endif class="btn btn-success btn-sm buttonHover" id="addNewMainGroupBtn"> جدید <i class="fa fa-plus fa-lg" aria-hidden="true"></i></button>
                                                @if(hasPermission(Session::get("adminId"),"listGroups") > 0)
                                            <button type="button" value="Reterive data" class="btn btn-info btn-sm text-white editButtonHover"
                                                onclick="getGroupId()" data-toggle="modal" id="editGroupList">ویرایش <i class="fa fa-edit fa-lg" aria-hidden="true"></i></button>
                                                @endif
                                             <form action='{{ url('/deleteMainGroup') }}' onsubmit="return confirm('میخوهید حذف کنید?');" method='post' style=" margin:0; padding:0; display: inline;">
                                                @csrf
                                                @if(hasPermission(Session::get("adminId"),"listGroups") > 1)
                                                <button type="submit" disabled id="deleteGroupList" class="btn btn-danger btn-sm buttonHoverDelete">حذف <i class="fa fa-trash fa-lg" aria-hidden="true"></i></button>
                                                @endif
                                                @if(hasPermission(Session::get("adminId"),"listGroups") > 0)
                                                <Button  id="topGroup"  type="button" value="top" onclick="changeMainGroupPriority(this)" style="background-color:rgb(255 255 255); padding:0;" ><i class="fa-solid fa-circle-chevron-up fa-2x chevronHover"></i></Button>
                                                <Button  id="downGroup"  type="button" value="down" onclick="changeMainGroupPriority(this)" style="background-color:rgb(255 255 255); padding:0;" ><i class="fa-solid fa-circle-chevron-down fa-2x chevronHover"></i></Button>
                                                @endif
                                                <input type="text" value="" id="mianGroupId" style="display: none"/>
                                     </div>

                                    <input type="text" class="form-control" id="mainGroupSearch" autocomplete="off" style="margin-top:10px" name="search_mainPart" placeholder="جستجو"> <br> <hr>
                                    <table class="table table-bordered table table-hover" id="tableGroupList">
                                        <thead class="tableHeader">
                                            <tr>
                                                <th>ردیف</th>
                                                <th>گروه اصلی </th>
                                                <th>فعال</th>
                                            </tr>
                                        </thead>
                                        <tbody class="c-checkout tableBody" id="mainGroupList" style="max-height: 350px;">
                                            @foreach ($mainGroups as $group)
                                                <tr  onclick="changeMainGroupStuff(this)">
                                                    <td>{{ $loop->index+1 }}</td>
                                                    <td>{{ $group->title }}</td>
                                                    <td>
                                                        <input class="mainGroupId" type="radio" name="mainGroupId[]" value="{{ $group->id . '_' . $group->title }}" id="flexCheckChecked">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                        </div>
                        <div class="col-sm-2"> </div>
                        <div class="col-sm-5">
                                  <div class="row">
                                        <div class="col-sm-3 mt-2">
                                            <h6>گروه های فرعی</h6>
                                        </div>
                                    <div class="col-sm-9">
                                                @if(hasPermission(Session::get("adminId"),"listGroups") > 1)
                                                    <button class="btn btn-success btn-sm buttonHover"
                                                        onclick="addNewSubGroup()" disabled  id="addNewSubGroupButton" > جدید <i class="fa fa-plus fa-lg" aria-hidden="true"></i></button>
                                                @endif
                                                @if(hasPermission(Session::get("adminId"),"listGroups") > 0)
                                                        <button  class="btn btn-info btn-sm text-white editButtonHover" disabled id="editSubGroupButton" > ویرایش <i class="fa fa-edit fa-lg" aria-hidden="true"></i></button>
                                                @endif
                                                <form action='{{ url('/deleteSubGroup') }}' method='post' onsubmit="return confirm('میخوهید حذف کنید?');" style=" margin:0; padding:0; display: inline;">
                                                    @csrf
                                                    @if(hasPermission(Session::get("adminId"),"listGroups") > 1)
                                                    <button id="deleteSubGroup" disabled class="btn btn-danger btn-sm buttonHoverDelete"> حذف <i class="fa fa-trash fa-lg" aria-hidden="true"></i></button>
                                                    @endif
                                                    <input type="text" value="" name="id" style="display: none" id="subGroupIdForDelete"/>
                                                    @if(hasPermission(Session::get("adminId"),"listGroups") > 0)
                                                    <button onclick="changeSubGroupPriority(this)" value="top"  type="button" style="background-color:rgb(255 255 255); padding:0;" ><i class="fa-solid fa-circle-chevron-up fa-2x chevronHover"></i></button>
                                                    <button onclick="changeSubGroupPriority(this)" value="down"  type="button" style="background-color:rgb(255 255 255); padding:0;"><i class="fa-solid fa-circle-chevron-down fa-2x chevronHover"></i></button>
                                                    <br />
                                                    @endif
                                                </form>

                                          </div>
                                        </div>
                                        
                                        <input type="text" class="form-control" style="margin-top:10px;"  id="serachSubGroupId"  placeholder="جستجو"> <br>
                                       
                                    <table id="subGroupTable" class="table table-bordered" id="tableGroupList">
                                        <thead class="tableHeader bg-success">
                                            <tr>
                                                <th >ردیف </th>
                                                <th> گروه فرعی </th>
                                                <th> فعال </th>
                                            </tr>
                                        </thead>
                                        <tbody class="tableBody" id="subGroup1">
                                        </tbody>
                                    </table>
                            </div>
                        </div>
                    </div>




                    <div class="row" id="addKalaToGroup" style="display: none">
                        <div class="col-sm-5">
                                <input type="text" class="form-control" style="margin-top:10px;"  id="serachKalaForSubGroup"  placeholder="جستجو"> <br>
                                <table class="table table-bordered table table-hover table-light">
                                    <thead class="tableHeader bg-successs">
                                        <tr>
                                            <th>ردیف</th>
                                            <th>اسم </th>
                                            <th>
                                                <input type="checkbox" name="" @if(hasPermission(Session::get("adminId"),"listGroups") < 1) disabled @endif  class="selectAllFromTop form-check-input"  >
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="tableBody"  id="allKalaForGroup">
                                    </tbody>
                                </table>
                        </div>
                        <div class="col-sm-2" style="">
                            <div class='modal-body' style="position:relative; right: 15%; top: 30%;">
                                <div style="">
                                    <button @if(hasPermission(Session::get("adminId"),"listGroups") < 1) disabled @endif  id="addDataToGroup">
                                        <i class="fa-regular fa-circle-chevron-left fa-3x chevronHover"></i></button>
                                    <br />
                                    <button @if(hasPermission(Session::get("adminId"),"listGroups") < 1) disabled @endif id="removeDataFromGroup">
                                        <i class="fa-regular fa-circle-chevron-right fa-3x chevronHover"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <form action="{{url('/addKalaToGroup')}}" method="POST" style="display: inline" >
                                    @csrf
                                    <input type="text" style="display: none" id="firstGroupId" name="firstGroupId"/>
                                    <input type="text" style="display: none" id="secondGroupId" name="secondGroupId"/>
                                    @if(hasPermission(Session::get("adminId"),"listGroups") > 1) 
                                    <button class='btn btn-success buttonHover mb-2 buttonHove' style="float:left;" type="submit">ذخیره</button>
                                    @endif
                                    <input type="text" class="form-control" style="margin-top:10px;" id="serachKalaOfSubGroup"  placeholder="جستجو">  
                                    <table class="table table-bordered table table-hover">
                                        <thead class="tableHeader bg-success">
                                            <tr>
                                                <th>ردیف</th>
                                                <th>گروه اصلی </th>
                                                <th>
                                                    <input type="checkbox" @if(hasPermission(Session::get("adminId"),"listGroups") < 1) disabled @endif  name="" class="selectAllFromTop form-check-input"/>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="tableBody" id="allKalaOfGroup"></tbody>
                                    </table>
                            </div>
                      </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- modal of new group -->
        <div class="modal fade dragAbleModal" id="newGroup" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white py-2">
                        <h5 class="modal-title" id="exampleModalLongTitle"> دسته بندی جدید </h5>
                        <button type="button" class="close btn text-danger" data-dismiss="modal" aria-label="Close" style="background-color:rgb(255 255 255); padding:0; padding-left:7px;">
                            <i class="fa-solid fa-xmark fa-xl" style="background:none; button:hover{background-color:none}"></i>
                        </button>
                    </div>
                    <div class="modal-body">
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
                                    <button type="button" class="btn btn-danger btn-sm buttonHover" data-dismiss="modal">انصراف <i class="fa-solid fa-xmark fa-lg"></i></button>
                                    <button type="submit" id="submitNewGroup" class="btn btn-success btn-sm buttonHover">ذخیره <i class="fa fa-save fa-lg" aria-hidden="true"></i></button>
                                </div>
                            </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- end modal new group -->
        <!-- modal of editig groups -->
        <div class="modal fade dragAbleModal" id="editGroup" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white py-2">
                        <h5 class="modal-title" id="exampleModalLongTitle">ویرایش دسته بندی</h5>
                        <button type="button" class="close btn bg-danger" data-dismiss="modal" aria-label="Close" style="background-color:rgb(255 255 255); padding:0; padding-left:7px;"><i class="fa-solid fa-xmark fa-xl"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                            <form action="{{ url('/editMainGroup') }}" class="form"
                                enctype="multipart/form-data" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label class="form-label">اسم دستبندی</label>
                                    <input type="text" size="10px" required class="form-control" name="groupName" id="groupName"
                                        placeholder="">
                                </div>
                                {{-- <div class="form-group">
                                    <label class="form-label">اولویت</label>
                                    <select class="form-select" name="priority">
                                            @for ($i = 1; $i <= ($allGroups); $i++)
                                                <option @if($i==4) selected @endif value="{{$i}}" >{{$i}}</option>
                                            @endfor
                                    </select>
                                </div> --}}
                                <div class="form-group">
                                    <input type="text" class="form-control" style="display:none;" name="groupId"
                                        id="groupId" placeholder="">
                                </div>
                                <div class="form-group" style="margin-top:4%">
                                    <label for="groupPicture" id="groupPicturelabel" class='btn btn-success btn-sm' class="form-label"> انتخاب عکس <i class="fa-solid fa-image fa-lg"></i></label>
                                    <input type="file" class="form-control" style="display:none;" name="groupPicture" id="groupPicture" placeholder="">
                                     &nbsp;&nbsp;&nbsp;
                                    <button class="btn btn-success btn-sm buttonHover"> ذخیره  <i class="fa fa-save fa-lg" aria-hidden="true"></i></button>
                                </div>
                            </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- end modal editing -->
        <!-- modal of editig subgroups -->
        <div class="modal fade dragAbleModal" id="editSubGroup" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white py-2">
                        <h5 class="modal-title" id="exampleModalLongTitle"> ویرایش دستبندی فرعی</h5>
                        <button type="button" class="close btn bg-danger" data-dismiss="modal" aria-label="Close" style="background-color:rgb(255 255 255); padding:0; padding-left:7px;"><i class="fa-solid fa-xmark fa-xl"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                            <form action="{{ url('/editSubgroup') }}" class="form"
                                enctype="multipart/form-data" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label class="form-label"> اسم دستبندی </label>
                                    <input type="text" required class="form-control" name="subGroupNameEdit" id="subGroupNameEdit"
                                        placeholder="">
                                </div>
                                {{-- <div class="form-group">
                                    <label class="form-label"> اولویت </label>
                                    <select class="subGroupCount form-select" name="priority">

                                    </select>
                                </div> --}}
                                <input type="text" class="form-control" name="fatherMainGroupId"
                                    id="fatherMainGroupIdEdit" style="display: none">
                                <input type="text" class="form-control" name="subGroupId" id="subGroupIdEdit"
                                    style="display: none">
                                <div class="form-group" style="margin-top:4%">
                                    <label for="subGroupPictureEdit" id="subGroupPictureEditlabel" class='btn btn-success btn-sm' class="form-label"> انتخاب عکس <i class="fa-solid fa-image fa-lg"></i></label>
                                    <input type="file"  class="form-control" style="display:none" name="subGroupPictureEdit" id="subGroupPictureEdit" placeholder="">
                                        &nbsp;&nbsp;&nbsp;
                                    <button class="btn btn-success btn-sm buttonHover"> ذخیره <i class="fa fa-save fa-lg" aria-hidden="true"></i></button>
                                </div>
                            </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- end modal editing -->
        <!-- modal of new subgroup -->
        <div class="modal fade dragAbleModal" id="newSubGroup" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white py-2">
                        <h5 class="modal-title" id="exampleModalLongTitle">دسته بندی فرعی جدید</h5>
                        <button type="button" class="close btn bg-danger" data-dismiss="modal" aria-label="Close" style="background-color:rgb(255 255 255); padding:0; padding-left:7px;"><i class="fa-solid fa-xmark fa-xl"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                            <form action="{{ url('/addSubGroup') }}" class="form" enctype="multipart/form-data" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label class="form-label fs-6">اسم دستبندی</label>
                                    <input type="text" required class="form-control" autocomplete="off" name="groupTitle" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label class="form-label fs-6"> عکس </label>
                                    <input type="file" class="form-control" name="subGroupPicture" placeholder="">
                                </div>
                                <input type="text" value="" style="display: none" name="fatherMainGroupId" id="fatherMainGroupId">
                                <input type="text" value="" style="display: none" id="selfGroupId">
                                <div class="form-group">
                                    <label class="form-label fs-6"> اولویت </label>
                                    <select class="subGroupCount form-select" name="priority" >
                                    </select>
                                </div>
                                <div class="form-group" style="margin-top:4%">
                                    <button type="button" class="btn btn-danger btn-sm buttonHover" data-dismiss="modal">انصراف <i class="fa-solid fa-xmark fa-lg"></i></button>
                                    <button type="submit" class="btn btn-success btn-sm buttonHover">ذخیره <i class="fa fa-save fa-lg" aria-hidden="true"></i></button>
                                </div>
                            </form>
                    </div>
                </div>
            </div>
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
		
	$("#addNewMainGroupBtn").on("click", ()=>{
	     if (!($('.modal.in').length)) {
                $('.modal-dialog').css({
                  top: 0,
                  left: 350
                });
              }
              $('#newGroup').modal({
                backdrop: false,
                show: true
              });
              
              $('.modal-dialog').draggable({
                  handle: ".modal-header"
                });
	    	$("#newGroup").modal("show");
	})

	$("#editGroupList").on("click", ()=>{
	     if (!($('.modal.in').length)) {
                $('.modal-dialog').css({
                  top: 0,
                  left: 350
                });
              }
              $('#editGroup').modal({
                backdrop: false,
                show: true
              });
              
              $('.modal-dialog').draggable({
                  handle: ".modal-header"
                });
	    	$("#editGroup").modal("show");
	})
		
	$("#addNewSubGroupButton").on("click", ()=>{
	     if (!($('.modal.in').length)) {
                $('.modal-dialog').css({
                  top: 0,
                  left: 300
                });
              }
              $('#newSubGroup').modal({
                backdrop: false,
                show: true
              });
              
              $('.modal-dialog').draggable({
                  handle: ".modal-header"
                });
	    	$("#newSubGroup").modal("show");
	})
	
		$("#editSubGroupButton").on("click", ()=>{
	     if (!($('.modal.in').length)) {
                $('.modal-dialog').css({
                  top: 0,
                  left: 300
                });
              }
              $('#editSubGroup').modal({
                backdrop: false,
                show: true
              });
              
              $('.modal-dialog').draggable({
                  handle: ".modal-header"
                });
	    	$("#editSubGroup").modal("show");
	})
		
		
    </script>
@endsection

@extends('admin.layout')
@section('content')
                <div class="container px-4" style="margin-top:90px; width:1200px;">
                    <div class="row p-3 border border-success">
                        <div class="col-sm-12">
                                <div class="well">
                                    <h5 style="width:40%; border-bottom:2px solid gray">لیست برندها</h5>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-lg-6">
                                            <input type="text" class="form-control" id="mainGroupSearch" autocomplete="off" name="search_mainPart" placeholder="جستجو">
                                    </div>
                                    <div class="col-lg-6 text-end">
                                            <button style="margint:0" @if(hasPermission(Session::get("adminId"),"brand") < 2) disabled @endif class="btn btn-success btn-sm buttonHover" id="newBrand">جدید <i class="fa fa-plus fa-lg" aria-hidden="true"></i></button>
                                            <button type="button" value="Reterive data" class="btn btn-info btn-sm text-white editButtonHover"
                                            @if(hasPermission(Session::get("adminId"),"brand") > 0) onclick="setBrandEditStuff()" @endif data-toggle="modal" id="editGroupList">ویرایش <i class="fa fa-edit fa-lg" aria-hidden="true"></i></button>

                                            <form action='{{ url('/deleteBrand') }}' onsubmit="return confirm('میخوهید حذف کنید?');" method='post' style=" margin:0; padding:0; display: inline;">
                                                @csrf
                                                @if(hasPermission(Session::get("adminId"),"brand") > 1)
                                                <button type="submit" disabled id="deleteBrand" onclick="swal();" class="btn btn-danger btn-sm buttonHoverDelete"> حذف <i class="fa fa-trash fa-lg" aria-hidden="true"></i></button>
                                                @endif
                                                @if(hasPermission(Session::get("adminId"),"brand") > 0)
                                                <Button  id="topGroup"  type="button" value="top" onclick="changeMainGroupPriority(this)" style="background-color:rgb(255 255 255); padding:0;" ><i class="fa-solid fa-circle-chevron-up fa-2x chevronHover"></i></Button>
                                                <Button  id="downGroup"  type="button" value="down" onclick="changeMainGroupPriority(this)" style="background-color:rgb(255 255 255); padding:0;" ><i class="fa-solid fa-circle-chevron-down fa-2x chevronHover"></i></Button>
                                                @endif
                                                <input type="text" value="" id="deleteBrandId" name="brandId" style="display:none"/>
                                                <br />
                                            </form>
                                        
                                    </div>
                                </div>
                            
                                    <table class="table table-bordered table table-hover" id="tableGroupList">
                                        <thead class="tableHeader">
                                            <tr>
                                                <th>ردیف</th>
                                                <th>برند </th>
                                                <th>فعال</th>
                                            </tr>
                                        </thead>
                                        <tbody class="c-checkout tableBody" id="mainGroupList">
                                            @foreach ($brands as $brand)
                                                <tr onclick="setBrandStuff(this)">
                                                    <td>{{$loop->iteration}}</td>
                                                    <td>{{$brand->name}}</td>
                                                    <td>
                                                        <input class="mainGroupId" type="radio" name="mainGroupId[]"
                                                            value="{{$brand->id.'_'.$brand->name}}"
                                                            id="flexCheckChecked">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                            </div>
                        </div>

                    <div class="row" id="addKalaToBrand" style="display: none; border:1px solid green;">
                        <div class="col-sm-5" style="margin-top:40px;">
                            <div class='modal-body'>
                                <input type="text" class="form-control" style="margin-top:10px;"
                                id="serachKalaForBrand"  placeholder="جستجو"> <br>
                                <div class='c-checkout' style='padding-right:0;'>
                                    <table class="table table-bordered table table-hover">
                                        <thead class="tableHeader">
                                            <tr>
                                                <th>ردیف</th>
                                                <th>اسم </th>
                                                <th>
                                                    <input type="checkbox" name="" @if(hasPermission(Session::get("adminId"),"brand") < 2) disabled @endif  class="selectAllFromTop form-check-input"  >
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="tableBody" id="allKalaForBrand">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-2" style="">
                            <div class='modal-body' style="position:relative; right:29%; top: 30%;">
                                <div>
                                    <button @if(hasPermission(Session::get("adminId"),"brand") < 2) disabled @endif  id="addDataToBrand" style="background-color:transparent">
                                        <i class="fa-regular fa-circle-chevron-left fa-3x chevronHover"></i></button>
                                    <br />
                                    <button @if(hasPermission(Session::get("adminId"),"brand") < 2) disabled @endif id="removeDataFromBrand" style="background-color:transparent">
                                        <i class="fa-regular fa-circle-chevron-right fa-3x chevronHover"></i></button>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-5">
                            <div class='modal-body'>
                                <form action="{{url('/addKalaToBrand')}}" method="POST" style="display: inline" >
                                    @csrf
                                    <input type="text" name="brandId" id="BrandToAddKala" style="display: none">
                                    @if(hasPermission(Session::get("adminId"),"brand") > 1)
                                    <button class='btn btn-success mb-2 buttonHover'  type="submit" style="float:left;">ذخیره <i class="fa fa-save"></i></button>
                                    @endif
                                    <input type="text" class="form-control" style="margin-top:10px;"
                                id="serachKalaOfSubGroup"  placeholder="جستجو"> <br>
                                <div class='c-checkout' style='padding-right:0;'> 
                                    <table class="table table-bordered">
                                        <thead class="tableHeader">
                                            <tr>
                                                <th>ردیف</th>
                                                <th>اسم </th>
                                                <th>
                                                    <input type="checkbox" @if(hasPermission(Session::get("adminId"),"brand") < 2) disabled @endif  name="" class="selectAllFromTop form-check-input"/>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="tableBody" id="allKalaOfBrand">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal of new Brand -->
        <div class="modal fade dragableModal" id="newGroup" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="exampleModalLongTitle"> برند جدید </h5>
                        <button type="button" class="close btn text-danger" data-dismiss="modal" aria-label="Close" style="background-color:rgb(255 255 255); padding:0; padding-left:7px;">
                            <i class="fa-solid fa-xmark fa-xl" style="background:none; button:hover{background-color:none}"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                            <form action="{{url('/addBrand')}}" method="POST" id="createNewMainGroup" enctype="multipart/form-data" class="form">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label class="form-label"> اسم برند </label>
                                    <input type="text" required class="form-control" autocomplete="off" name="brandName" id="mainGroupName"
                                        placeholder="">
                                </div>
                                <div class="form-group">
                                    <label class="form-label"> اولویت </label>
                                    <select class="form-select" name="priority">
                                            <option value="" >1</option>
                                    </select>
                                </div>
                                    <div class="form-group">
                                    <label class="form-label"> عکس </label>
                                    <input type="file" class="form-control" name="brandPic" placeholder="">
                                </div>
                                <div class="form-group" style="margin-top:4%">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">انصراف <i class="fa-solid fa-xmark fa-lg"></i></button>
                                    <button type="submit" id="submitNewGroup" class="btn btn-success">ذخیره <i class="fa fa-save fa-lg" aria-hidden="true"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <!-- end modal new brand -->
        <!-- modal of editig brand -->
        <div class="modal fade" id="editGroup" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="exampleModalLongTitle">ویرایش برند</h5>
                        <button type="button" class="close btn text-danger" data-dismiss="modal" aria-label="Close" style="background-color:rgb(255 255 255); padding:0; padding-left:7px;"><i class="fa-solid fa-xmark fa-xl"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                            <form action="{{ url('/editBrand') }}" class="form"
                                enctype="multipart/form-data" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label class="form-label">اسم برند</label>
                                    <input type="text" size="10px" required class="form-control" name="brandName" id="brandName"
                                        placeholder="">
                                </div>
                                <div class="form-group">
                                    <input type="hidden" class="form-control" style="" id="brandId" name="brandId"
                                         placeholder="">
                                </div>
                                <div class="form-group" style="margin-top:4%">
                                    <label for="brandPicture" id="groupPicturelabel" class='btn btn-success btn-sm' class="form-label"> انتخاب عکس <i class="fa-solid fa-image fa-lg"></i></label>
                                    <input type="file" class="form-control" style="display:none;" name="brandPicture" id="brandPicture" placeholder="">
                                    
                                </div>
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-danger buttonHover" data-dismiss="modal">انصراف <i class="fa-solid fa-xmark fa-lg"></i></button>
                        <button type="submit" id="submitNewGroup" onclick="addMantiqah()"  class="btn btn-sm btn-success buttonHover">ذخیره <i class="fa fa-save fa-lg" aria-hidden="true"></i></button>
                      </div>
                </form>
                    </div>
                </div>
            </div>
     
    <meta name="csrf-token" content="{{ csrf_token() }}" />
 
<script>
	
   $("#newBrand").on("click", ()=>{
	      if (!($('.modal.in').length)) {
                $('.modal-dialog').css({
                  top: 0,
                  left: 0
                });
              }
              $('#newGroup').modal({
                backdrop: false,
                show: true
              });
              
              $('.modal-dialog').draggable({
                  handle: ".modal-header"
                });
   		$("#newGroup").modal("show")
   })
	
	 $("#editGroupList").on("click", ()=>{
	      if (!($('.modal.in').length)) {
                $('.modal-dialog').css({
                  top: 0,
                  left: 0
                });
              }
              $('#editGroup').modal({
                backdrop: false,
                show: true
              });
              
              $('.modal-dialog').draggable({
                  handle: ".modal-header"
                });
   		$("#editGroup").modal("show")
   })
</script>
@endsection

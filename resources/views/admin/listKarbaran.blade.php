@extends('admin.layout')
@section('content')
<style>
    .firstLevel{
         color:#00512a !important;
        font-size:16px !important;
        font-weight:bold !important; 
    }
    .secondLevel{
       
        color:#008857 !important;
         font-size:14px !important;
        font-weight:bold !important;
    }
</style>
<div class="container-fluid containerDiv">
    <div class="row">
            <div class="col-lg-2 col-md-2 col-sm-3 sideBar">
                <fieldset class="border rounded mt-5 sidefieldSet">
                    <legend  class="float-none w-auto legendLabel mb-0"> تنظیمات </legend>
                    <!-- <div class="form-check">
                        <input class="form-check-input p-2 float-end" type="radio" name="settings" id="elseSettingsRadio">
                        <label class="form-check-label me-4" for="assesPast">  سطح دسترسی  </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input p-2 float-end" type="radio" name="settings" id="settingAndTargetRadio">
                        <label class="form-check-label me-4" for="assesPast"> تارگت ها و امتیازات </label>
                    </div> -->
                    
                </fieldset>
                </div>
            <div class="col-sm-10 col-md-10 col-sm-12 contentDiv">
                <div class="row contentHeader"> 
                    <div class="col-lg-6 col-md-6 col-sm-12 mt-1">
						   <input type="text" name="search_mainPart" class="form-control form-control-sm" id="basic-url" aria-describedby="basic-addon3" placeholder="جستجو ">
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12 text-end mt-1">
						 @if(hasPermission(Session::get("adminId"),"karbaran") > 1)
                            <button class="btn btn-success btn-sm" id="addingNewKarbar"> کاربر جدید <i class="fa fa-plus" aria-hidden="true"></i></button>
                        @endif  
                        @if(hasPermission(Session::get("adminId"),"karbaran") > 0)  
                            <button  type="button" class="btn btn-success btn-sm text-white" onclick="openEditDashboard()"> ویرایش <i class="fa fa-edit" aria-hidden="true"></i></button>
                        @endif
                        @if(hasPermission(Session::get("adminId"),"karbaran") > 1)
                            <button  type="button" class="btn btn-danger btn-sm text-white" onclsuper ick="deleteConfirm()"> حذف <i class="fa fa-trash" aria-hidden="true"></i></button>
                        @endif
					</div>
                </div>
                <div class="row mainContent">
                    <table class="table table-bordered table-striped table-sm">
                        <thead class="tableHeader">
                            <tr>
                                <th>ردیف</th>
                                <th>اسم</th>
                                <th>فامیلی </th>
                                <th>فعال </th>
                                <th>انتخاب </th>
                            </tr>
                        </thead>
                        <tbody class="tableBody">
                            @foreach($admins as $admin)
                                <tr onclick="editAdmins(this)">
                                    <td>{{$loop->index+1}}</td>
                                    <td>{{$admin->name}}</td>
                                    <td>{{$admin->lastName}}</td>
                                    <td><input class="form-check-input" name="activeState" type="checkbox" disabled value="" @if ($admin->activeState==1) checked @else @endif id="flexCheckChecked" />
                                    </td>
                                    <td>
                                    <input class="admins form-check-input" name="admin" type="radio" value="{{$admin->id}}" id="flexCheckChecked" />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                   </div>
                <div class="row contentFooter"> </div>
            </div>
    </div>
</div>






<!-- modal for editing karbar and new roles for karbar -->
<div class="modal fade dragAbleModal" id="editUserRoles" data-backdrop="static"  data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl  modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header myModalHeader py-2">
                <h5 class="modal-title" id="editKalaTitle"> ویرایش کاربر  </h5>
               <span style="display: flex; float:left;"> <button type="button" class="btn-close bg-danger" data-dismiss="modal" aria-label="Close"></button> </span>
            </div>
            <div class="modal-body p-1"> 
                    <form style="display:inline" action="{{url('/doEditAdmin')}}" method="post" id="editingKarbarForm" enctype="multipart/form-data">
                     <input type="text" style="display:none" id="editAdminId" value="" name="adminId"/>
                             @csrf
                  <div class='container descForall'>
                        <div class="row">
                                <div class="col-sm-3">
                                    <select class="form-select form-select-sm" name="adminType"  style="display:inline;" id="adminType">
                                        <option hidden>نوع کاربر</option>
                                        <option value="super">سوپرادمین</option>
                                        <option value="admin">ادمین</option>
                                        <option value="poshtiban">پشتیبان</option>
                                    </select>
                                 </div>
                                 <div class="col-sm-3">
                                    <div class="input-group input-group-sm mb-3">
                                        <span class="input-group-text" id="inputGroup-sizing-default"> اسم </span>
                                        <input type="text" class="form-control" placeholder="اسم" id="name" name="name">
                                     </div>
                                 </div>
                                 <div class="col-sm-3">
                                    <div class="input-group input-group-sm mb-3">
                                        <span class="input-group-text" id="inputGroup-sizing-default">  فامیلی </span>
                                         <input type="text" class="form-control" id="lastName" name="lastName" placeholder="فامیلی">
                                     </div>
                                 </div>
                                 <div class="col-sm-3">
                                    <div class="input-group input-group-sm mb-3">
                                        <span class="input-group-text" id="inputGroup-sizing-default"> نام کاربری </span>
                                        <input type="text" class="form-control" placeholder="نام کاربری" id="userName" name="userName">
                                     </div>
                                 </div>
                          </div>
                        <div class="row">
                                <div class="col-sm-3">
                                    <div class="input-group input-group-sm mb-3">
                                        <span class="input-group-text" id="inputGroup-sizing-default"> رمز </span>
                                        <input type="password" class="form-control" placeholder="رمز" id="password" name="password">
                                     </div>
                                 </div>
                                 <div class="col-sm-3">
                                    <div class="input-group input-group-sm mb-3"> 
                                        <input type="file" class="form-control" name="userPic" placeholder="تصویر">
                                     </div>
                                 </div>
                                 <div class="col-sm-3">
                                    <div class="form-check d-flex flex-end mt-1">
                                        <input class="form-check-input p-2" type="radio" value="male" name="gender" id="manGender"> &nbsp;
                                        <label class="form-check-label mx-4 karbar-label fs-6" >مرد </label> &nbsp; &nbsp;
                                        <input class="form-check-input p-2" type="radio" value="female" name="gender" id="womanGender"> &nbsp;
                                        <label class="form-check-label mx-4 karbar-label fs-6">زن</label>
                                    </div>
                                 </div>
                                 <div class="col-sm-3">
                                    <div class="form-check d-flex flex-end mt-1">
                                        <input class="form-check-input p-2" type="checkbox" name="activeState" id="activeState"> &nbsp;
                                        <label class="form-check-label mx-4 karbar-label fs-6">فعال</label> &nbsp; &nbsp;
                                    </div>
                                 </div>
                          </div>
                      </div>
          
                 <div class="container" style="background-color:#43bfa3; border-radius:5px 5px 2px 2px;">
                        <ul class="header-list nav nav-tabs" data-tabs="tabs">
                            <li><a class="active" data-toggle="tab" style="color:black;" href="#webManagementEdit"> اطلاعات پایه  </a></li>
                            <li><a data-toggle="tab" style="color:black;"  href="#peoplesEdit"> تعریف عناصر  </a></li>
                            <li><a data-toggle="tab" style="color:black;"  href="#kalasTabEdit">  عملیات  </a></li>
                            <li><a data-toggle="tab" style="color:black;"  href="#messagesEdit">  گزارشات   </a></li>
                        </ul>
                        <div class="c-checkout tab-content" style="background-color:#f5f5f5; margin:0; margin-bottom:1%; padding:1%; border-radius:10px 10px 2px 2px;">
                            <div class="c-checkout tab-pane active" id="webManagementEdit" style="border-radius:10px 10px 2px 2px;">
                                <div class="container">
                                <div class="row">
                                        <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                            <legend  class="float-none w-auto forLegend"> <input type="checkbox" name="manageWeb" class="webPage form-check-input"/> اطلاعات پایه  </legend>
                                            <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                <legend  class="float-none w-auto firstLevel"> <input type="checkbox" name="manageWeb" class="webPage form-check-input"/>  تنظیمات  </legend>
                                                        <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                            <legend  class="float-none w-auto fs-6 secondLevel">
                                                                <input type="checkbox" name="homePage" id="homePage" class="web form-check-input"/> تنظیمات صفحه اصلی  </legend>
                                                            <div class="form-check admin-accesss-level">
                                                                <input class="super form-check-input box-check" id="homeDelete" type="checkbox" name="homeDelete">
                                                                <label class="form-check-label">حذف</label>
                                                            </div>
                                                            <div class="form-check admin-accesss-level">
                                                                <input class="admin form-check-input box-check" id="homeChange" type="checkbox" name="changeHomePage">
                                                                <label class="form-check-label">تغییر</label>
                                                            </div>
                                                            <div class="form-check admin-accesss-level">
                                                                <input class="poshtiban form-check-input box-check" id="homeSee" type="checkbox" name="seeHomePage">
                                                                <label class="form-check-label">مشاهده</label>
                                                            </div>
                                                        </fieldset>
                                                        <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                            <legend  class="float-none w-auto forLegend fs-6 secondLevel"> <input type="checkbox" id="specialSetting" class="web form-check-input" name="specialSetting" />تنظمیات اختصاصی</legend>
                                                            <div class="form-check admin-accesss-level">
                                                                <input class="super form-check-input box-check" id="specialDelete" type="checkbox" name="specialDelete">
                                                                <label class="form-check-label">حذف</label>
                                                            </div>
                                                            <div class="form-check admin-accesss-level">
                                                                <input class="admin form-check-input box-check" type="checkbox" id="specialChange" name="changeSpecialSetting">
                                                                <label class="form-check-label">تغییر</label>
                                                            </div>
                                                            <div class="form-check admin-accesss-level">
                                                                <input class="poshtiban web form-check-input box-check" id="specialSee" type="checkbox" name="seeSpecialSetting">
                                                                <label class="form-check-label">مشاهده</label>
                                                            </div>
                                                        </fieldset>

                                                        <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                            <legend  class="float-none w-auto forLegend fs-6 secondLevel"> <input type="checkbox" name="karbaran" id="karbaran" class="web form-check-input"/> تنظیمات امتیاز  </legend>
                                                            <div class="form-check admin-accesss-level">
                                                                <input class="super form-check-input box-check" id="karbaranDelete" type="checkbox" name="karbaranDelete">
                                                                <label class="form-check-label">حذف</label>
                                                            </div>
                                                            <div class="form-check admin-accesss-level">
                                                            <input class="admin form-check-input box-check" id="karbaranChange" type="checkbox" name="changeKarbaran">
                                                                <label class="form-check-label">تغییر</label>
                                                            </div>
                                                            <div class="form-check admin-accesss-level">
                                                                <input class="poshtiban form-check-input box-check" id="karbaranSee" type="checkbox"  name="seeKarbaran">
                                                                <label class="form-check-label">مشاهده</label>
                                                            </div>
                                                        </fieldset>
                                                   </fieldset>
                                               </fieldset>
                                        </div>
                                  </div>
                            </div>

                            <div class="c-checkout tab-pane" id="peoplesEdit" style="border-radius:10px 10px 2px 2px;">
                                <div class="container">
                                    <div class="row">
                                    <fieldset class="border rounded-3">
                                            <legend  class="float-none w-auto forLegend"><input type="checkbox" class="persons form-check-input" name="persons"/> تعریف عناصر</legend>
                                            <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                <legend  class="float-none w-auto fs-6 secondLevel"> <input type="checkbox" class="person form-check-input" id="customers" name="customers"/> کاربران </legend>
                                                <div class="form-check admin-accesss-level">
                                                    <input class="super form-check-input box-check" id="deleteCustomers" type="checkbox" name="deleteCustomers">
                                                    <label class="form-check-label">حذف</label>
                                                </div>
                                                <div class="form-check admin-accesss-level">
                                                     <input class="admin form-check-input box-check" type="checkbox" id="changeCustomers" name="changeCustomers">
                                                    <label class="form-check-label">تغییر</label>
                                                </div>

                                                <div class="form-check admin-accesss-level">
                                                     <input class="poshtiban form-check-input box-check" type="checkbox" id="seeCustomers" name="seeCustomers">
                                                    <label class="form-check-label">مشاهده</label>
                                                </div>
                                            </fieldset>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>

                            <div class="c-checkout tab-pane" id="kalasTabEdit" style="border-radius:10px 10px 2px 2px;">
                                <div class="container">
                                    <div class="row">
                                    <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                       <legend  class="float-none w-auto forLegend"><input type="checkbox" class="kalas form-check-input" name="kalas"/> عملیات  </legend>
                                        <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                          <legend  class="float-none w-auto firstLevel"><input type="checkbox" class="kalas form-check-input" name="kalas"/>کالا ها </legend>
                                                <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                    <legend  class="float-none w-auto fs-6 secondLevel"> <input type="checkbox" class="kala form-check-input" id="kalaList" name="kalaList"/>لیست کالاها</legend>
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="super form-check-input box-check" id="deleteKalaList" type="checkbox" name="deleteKalaList">
                                                        <label class="form-check-label">حذف</label>
                                                    </div>
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="admin form-check-input box-check" type="checkbox" id="changeKalaList" name="changeKalaList">
                                                        <label class="form-check-label">تغییر</label>
                                                    </div>
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="poshtiban form-check-input box-check" type="checkbox" id="seeKalaList" name="seeKalaList">
                                                        <label class="form-check-label">مشاهده</label>
                                                    </div>
                                                </fieldset>

                                                <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                    <legend  class="float-none w-auto fs-6 secondLevel"> <input type="checkbox" class="kala form-check-input" id="requestedKala" name="requestedKala"/>درخواستی ها</legend>
                                                    <div class="form-check admin-accesss-level">
                                                    <input class="super form-check-input box-check" id="deleteRequestedKala" type="checkbox" name="deleteRequestedKala">
                                                        <label class="form-check-label">حذف</label>
                                                    </div>
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="admin form-check-input box-check" type="checkbox" id="changeRequestedKala" name="changeRequestedKala">
                                                        <label class="form-check-label">تغییر</label>
                                                    </div>
                                                    <div class="form-check admin-accesss-level">
                                                    <input class="poshtiban form-check-input box-check" type="checkbox" id="seeRequestedKala" name="seeRequestedKala">
                                                        <label class="form-check-label">مشاهده</label>
                                                    </div>
                                                </fieldset>

                                                <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                    <legend  class="float-none w-auto fs-6 secondLevel"> <input type="checkbox" class="kala form-check-input" id="fastKala" name="fastKala"/>فست کالا</legend>
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="super form-check-input box-check" id="deleteFastKala" type="checkbox" name="deleteFastKala">
                                                        <label class="form-check-label">حذف</label>
                                                    </div>
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="admin form-check-input box-check" type="checkbox" id="changeFastKala" name="changeFastKala">
                                                        <label class="form-check-label">تغییر</label>
                                                    </div>
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="poshtiban form-check-input box-check" type="checkbox" id="seeFastKala" name="seeFastKala">
                                                        <label class="form-check-label">مشاهده</label>
                                                    </div>
                                                </fieldset>
                                                <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                <legend  class="float-none w-auto fs-6 secondLevel"> <input type="checkbox" class="kala form-check-input" id="pishKharid" name="pishKharid" />پیش خرید</legend>
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="super form-check-input box-check" id="deletePishKharid" type="checkbox" name="deletePishKharid">
                                                        <label class="form-check-label">حذف</label>
                                                    </div>
                                                    <div class="form-check admin-accesss-level">
                                                    <input class="admin form-check-input box-check" type="checkbox" id="changePishKharid" name="changePishKharid">
                                                        <label class="form-check-label">تغییر</label>
                                                    </div>

                                                    <div class="form-check admin-accesss-level">
                                                        <input class="poshtiban form-check-input box-check" type="checkbox"  id="seePishKharid" name="seePishKharid">
                                                        <label class="form-check-label">مشاهده</label>
                                                    </div>
                                                </fieldset>

                                                <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                <legend  class="float-none w-auto fs-6 secondLevel"> <input type="checkbox" class="kala form-check-input" id="brands" name="brands"/>برند ها</legend>
                                                    <div class="form-check admin-accesss-level">
                                                    <input class="super form-check-input box-check" id="deleteBrands" type="checkbox" name="deleteBrands">
                                                        <label class="form-check-label">حذف</label>
                                                    </div>
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="admin form-check-input box-check" type="checkbox" id="changeBrands" name="changeBrands">
                                                        <label class="form-check-label">تغییر</label>
                                                    </div>
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="poshtiban form-check-input box-check" type="checkbox" id="seeBrands" name="seeBrands">
                                                        <label class="form-check-label">مشاهده</label>
                                                    </div>
                                                </fieldset>

                                                <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                    <legend  class="float-none w-auto fs-6 secondLevel"> <input type="checkbox" class="kala form-check-input" id="alerted" name="alerted"/>کالاهای شامل هشدار</legend>
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="super form-check-input box-check" id="deleteAlerted" type="checkbox" name="deleteAlerted">
                                                        <label class="form-check-label">حذف</label>
                                                    </div>
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="admin form-check-input box-check" type="checkbox" id="changeAlerted" name="changeAlerted">
                                                        <label class="form-check-label">تغییر</label>
                                                    </div>
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="poshtiban form-check-input box-check" type="checkbox" id="seeAlerted" name="seeAlerted">
                                                        <label class="form-check-label">مشاهده</label>
                                                    </div>
                                                </fieldset>
                                                <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                  <legend  class="float-none w-auto fs-6 secondLevel"> <input type="checkbox" class="kala form-check-input" id="groupList" name="groupList" />دسته بندی کالاها</legend>
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="superN form-check-input box-check" id="deleteGroupList" type="checkbox" name="deleteGroupList">
                                                        <label class="form-check-label">حذف</label>
                                                    </div>
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="adminN form-check-input box-check" type="checkbox" id="changeGroupList" name="changeGroupList" >
                                                        <label class="form-check-label">تغییر</label>
                                                    </div>
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="poshtibanN form-check-input box-check" type="checkbox" id="seeGroupList" name="seeGroupList" >
                                                        <label class="form-check-label">مشاهده</label>
                                                    </div>
                                                </fieldset>
                                        </fieldset>
                                        <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                            <legend  class="float-none w-auto fs-6 firstLevel"> <input type="checkbox" class="kala form-check-input" id="groupList" name="groupList" /> سفارشات فروش   </legend>
                                            <div class="form-check admin-accesss-level">
                                                <input class="superN form-check-input box-check" id="deleteGroupList" type="checkbox" name="deleteGroupList">
                                                <label class="form-check-label">حذف</label>
                                            </div>
                                            <div class="form-check admin-accesss-level">
                                                <input class="adminN form-check-input box-check" type="checkbox" id="changeGroupList" name="changeGroupList" >
                                                <label class="form-check-label">تغییر</label>
                                            </div>
                                            <div class="form-check admin-accesss-level">
                                                <input class="poshtibanN form-check-input box-check" type="checkbox" id="seeGroupList" name="seeGroupList" >
                                                <label class="form-check-label">مشاهده</label>
                                            </div>
                                        </fieldset>
                                        <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                            <legend  class="float-none w-auto fs-6 firstLevel"> <input type="checkbox" class="kala form-check-input" id="groupList" name="groupList" />  پیامها   </legend>
                                            <div class="form-check admin-accesss-level">
                                                <input class="superN form-check-input box-check" id="deleteGroupList" type="checkbox" name="deleteGroupList">
                                                <label class="form-check-label">حذف</label>
                                            </div>
                                            <div class="form-check admin-accesss-level">
                                                <input class="adminN form-check-input box-check" type="checkbox" id="changeGroupList" name="changeGroupList" >
                                                <label class="form-check-label">تغییر</label>
                                            </div>
                                            <div class="form-check admin-accesss-level">
                                                <input class="poshtibanN form-check-input box-check" type="checkbox" id="seeGroupList" name="seeGroupList" >
                                                <label class="form-check-label">مشاهده</label>
                                            </div>
                                        </fieldset>
                                    </fieldset>
                                    </div>
                                </div>
                            </div>


                            <div class="c-checkout tab-pane" id="messagesEdit" style="border-radius:10px 10px 2px 2px;">
                                <div class="container">
                                    <div class="row">
                                        <fieldset class="border rounded-3">
                                              <legend  class="float-none w-auto fs-6 forLegend"><input type="checkbox" class="messages form-check-input" name="messages" /> گزارشات   </legend>
                                                <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                    <legend  class="float-none w-auto fs-6 firstLevel"><input type="checkbox" class="messages form-check-input" name="messages" /> مشتریان   </legend>
                                                        <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                        <legend  class="float-none w-auto fs-6 secondLevel"> <input type="checkbox" class="kala form-check-input" id="groupList" name="groupList" />  لیست مشتریان   </legend>
                                                            <div class="form-check admin-accesss-level">
                                                            <input class="super form-check-input box-check" id="deleteMessages" type="checkbox" name="deleteMessages">
                                                                <label class="form-check-label">حذف</label>
                                                            </div>
                                                            <div class="form-check admin-accesss-level">
                                                                <input class="admin form-check-input box-check" type="checkbox" id="changeMessages" name="changeMessages">
                                                                <label class="form-check-label">تغییر</label>
                                                            </div>
                                                            <div class="form-check admin-accesss-level">
                                                                <input class="poshtiban form-check-input box-check" type="checkbox" id="seeMessages" name="seeMessages">
                                                                <label class="form-check-label">مشاهده</label>
                                                            </div>
                                                        </fieldset>
                                                        <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                        <legend  class="float-none w-auto fs-6 secondLevel"> <input type="checkbox" class="kala form-check-input" id="groupList" name="groupList" />  اشخاص رسمی    </legend>
                                                            <div class="form-check admin-accesss-level">
                                                            <input class="super form-check-input box-check" id="deleteMessages" type="checkbox" name="deleteMessages">
                                                                <label class="form-check-label">حذف</label>
                                                            </div>
                                                            <div class="form-check admin-accesss-level">
                                                                <input class="admin form-check-input box-check" type="checkbox" id="changeMessages" name="changeMessages">
                                                                <label class="form-check-label">تغییر</label>
                                                            </div>
                                                            <div class="form-check admin-accesss-level">
                                                                <input class="poshtiban form-check-input box-check" type="checkbox" id="seeMessages" name="seeMessages">
                                                                <label class="form-check-label">مشاهده</label>
                                                            </div>
                                                        </fieldset>
                                               </fieldset>
                                               <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                    <legend  class="float-none w-auto fs-6 firstLevel"><input type="checkbox" class="messages form-check-input" name="messages" />  بازیها و لاتری   </legend>
                                                        <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                        <legend  class="float-none w-auto fs-6 secondLevel"> <input type="checkbox" class="kala form-check-input" id="groupList" name="groupList" />  نتجه لاری </legend>
                                                            <div class="form-check admin-accesss-level">
                                                            <input class="super form-check-input box-check" id="deleteMessages" type="checkbox" name="deleteMessages">
                                                                <label class="form-check-label">حذف</label>
                                                            </div>
                                                            <div class="form-check admin-accesss-level">
                                                                <input class="admin form-check-input box-check" type="checkbox" id="changeMessages" name="changeMessages">
                                                                <label class="form-check-label">تغییر</label>
                                                            </div>
                                                            <div class="form-check admin-accesss-level">
                                                                <input class="poshtiban form-check-input box-check" type="checkbox" id="seeMessages" name="seeMessages">
                                                                <label class="form-check-label">مشاهده</label>
                                                            </div>
                                                        </fieldset>
                                                        <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                        <legend  class="float-none w-auto fs-6 secondLevel"> <input type="checkbox" class="kala form-check-input" id="groupList" name="groupList" />  گیمر لیست  </legend>
                                                            <div class="form-check admin-accesss-level">
                                                            <input class="super form-check-input box-check" id="deleteMessages" type="checkbox" name="deleteMessages">
                                                                <label class="form-check-label">حذف</label>
                                                            </div>
                                                            <div class="form-check admin-accesss-level">
                                                                <input class="admin form-check-input box-check" type="checkbox" id="changeMessages" name="changeMessages">
                                                                <label class="form-check-label">تغییر</label>
                                                            </div>
                                                            <div class="form-check admin-accesss-level">
                                                                <input class="poshtiban form-check-input box-check" type="checkbox" id="seeMessages" name="seeMessages">
                                                                <label class="form-check-label">مشاهده</label>
                                                            </div>
                                                        </fieldset>
                                               </fieldset>
                                               

                                                <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                    <legend  class="float-none w-auto fs-6 firstLevel"> <input type="checkbox" class="kala form-check-input" id="groupList" name="groupList" />  پرداخت آنلاین   </legend>
                                                        <div class="form-check admin-accesss-level">
                                                        <input class="super form-check-input box-check" id="deleteMessages" type="checkbox" name="deleteMessages">
                                                            <label class="form-check-label">حذف</label>
                                                        </div>
                                                        <div class="form-check admin-accesss-level">
                                                            <input class="admin form-check-input box-check" type="checkbox" id="changeMessages" name="changeMessages">
                                                            <label class="form-check-label">تغییر</label>
                                                        </div>
                                                        <div class="form-check admin-accesss-level">
                                                            <input class="poshtiban form-check-input box-check" type="checkbox" id="seeMessages" name="seeMessages">
                                                            <label class="form-check-label">مشاهده</label>
                                                        </div>
                                                </fieldset>
                                        </fieldset>
                                     </div>
                                 </div>
                              </div>
                          </div>
                        </div>
                     </div>
                 <div class="modal-footer">
                     <button type="submit" class="btn btn-success bg-success btn-sm" form="editingKarbarForm">ذخیره <i class="fa fa-save"></i></button>
                     <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">انصراف <i class="fa fa-xmark"></i> </button> 
                </div>
               </div>
            </form>
          </div>
 </div>














<!-- modal for adding new karbar and new roles for karbar -->
     
<div class="modal fade dragAbleModal" id="addingKarbar" data-backdrop="static"  data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl  modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header myModalHeader py-2">
                <h5 class="modal-title" id="editKalaTitle"> افزودن کاربر  </h5>
               <span style="display: flex; float:left;"> <button type="button" class="btn-close bg-danger" data-dismiss="modal" aria-label="Close"></button> </span>
            </div>
            <div class="modal-body p-1"> 
              <form action="{{url('/doAddAdmin')}}" method="post" enctype="multipart/form-data" id="addingKarbarForm" style="display:inline;">
                         @csrf
                  <div class='container descForall'>
                        <div class="row">
                                <div class="col-sm-3">
                                        <select class="form-select form-select-sm"  style="display:inline;" name="AdminTypeN" id="adminTypeN">
                                            <option hidden>نوع کاربر</option>
                                            <option value="super">سوپرادمین</option>
                                            <option value="admin">ادمین</option>
                                            <option value="poshtiban">پشتیبان</option>
                                        </select>
                                 </div>

                                 <div class="col-sm-3">
                                    <div class="input-group input-group-sm mb-3">
                                        <span class="input-group-text" id="inputGroup-sizing-default"> اسم </span>
                                        <input type="text" class="form-control" placeholder="اسم" name="name">
                                     </div>
                                 </div>
                                 <div class="col-sm-3">
                                    <div class="input-group input-group-sm mb-3">
                                        <span class="input-group-text" id="inputGroup-sizing-default">  فامیلی </span>
                                        <input type="text" class="form-control" name="lastName" placeholder="فامیلی">
                                     </div>
                                 </div>
                                 <div class="col-sm-3">
                                    <div class="input-group input-group-sm mb-3">
                                        <span class="input-group-text" id="inputGroup-sizing-default"> نام کاربری </span>
                                        <input type="text" class="form-control" placeholder="نام کاربری" name="userName">
                                     </div>
                                 </div>
                                 
                          </div>
                          <div class="row">
                                <div class="col-sm-3">
                                    <div class="input-group input-group-sm mb-3">
                                        <span class="input-group-text" id="inputGroup-sizing-default"> رمز </span>
                                        <input type="password" class="form-control" placeholder="رمز" name="password">
                                     </div>
                                 </div>
                                 <div class="col-sm-3">
                                    <div class="input-group input-group-sm mb-3"> 
                                        <input type="file" class="form-control" id="userPic" placeholder="تصویر">
                                     </div>
                                 </div>
                                 <div class="col-sm-3">
                                    <div class="form-check d-flex flex-end mt-1">
                                        <input class="form-check-input p-2" type="radio" value="male" name="gender" id="manGender" checked> &nbsp;
                                        <label class="form-check-label mx-4 karbar-label fs-6" for="flexRadioDefault1">مرد </label> &nbsp; &nbsp;
                                        <input class="form-check-input p-2" type="radio" value="female" name="gender" id="womanGender"> &nbsp;
                                        <label class="form-check-label mx-4 karbar-label fs-6" for="flexRadioDefault2">زن</label>
                                    </div>
                                 </div>
                          </div>
                      </div>
                 <div class="container" style="background-color:#43bfa3; border-radius:5px 5px 2px 2px;">
                        <ul class="header-list nav nav-tabs" data-tabs="tabs">
                            <li><a class="active" data-toggle="tab" style="color:black;" href="#webManagement"> اطلاعات پایه  </a></li>
                            <li><a data-toggle="tab" style="color:black;"  href="#peoples"> تعریف عناصر </a></li>
                            <li><a data-toggle="tab" style="color:black;"  href="#kalasTab">  عملیات  </a></li>
                            <li><a data-toggle="tab" style="color:black;"  href="#messages"> گزارشات  </a></li>
                        </ul>
                        <div class="c-checkout tab-content tableBody" style="background-color:#f5f5f5; margin:0; margin-bottom:1%; padding:1%; border-radius:5px 5px 2px 2px;">
                            <div class="c-checkout tab-pane active" id="webManagement" style="border-radius:10px 10px 2px 2px;">
                                <div class="container">
                                <div class="row">
                                     <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                            <legend  class="float-none w-auto forLegend"> <input type="checkbox" name="manageWeb" class="webPageN form-check-input"/>  اطلاعات پایه   </legend>
                                            <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                <legend  class="float-none w-auto forLegend firstLevel"> <input type="checkbox" name="manageWeb" class="webPageN form-check-input"/>  تظیمات  </legend>
                                                <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                    <legend  class="float-none w-auto fs-6 secondLevel"> <input type="checkbox" name="homePageN" id="homePageN" class="webN form-check-input"/> تنظیمات صفحه اصلی </legend>
                                                        
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="superN form-check-input box-check" id="homeDeleteN" type="checkbox" name="homeDeleteN">
                                                        <label class="form-check-label">حذف</label>
                                                    </div>
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="adminN form-check-input box-check" id="homeChangeN" type="checkbox" name="changeHomePageN">
                                                        <label class="form-check-label">تغییر</label>
                                                    </div>
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="poshtibanN form-check-input box-check" id="homeSeeN" type="checkbox" name="seeHomePageN">
                                                        <label class="form-check-label">مشاهده</label>
                                                    </div>
                                                </fieldset>

                                                <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                    <legend  class="float-none w-auto secondLevel fs-6"> <input type="checkbox" name="karbaranN" id="karbaranN" class="webN form-check-input"/> تنظیمات اختصاصی  &nbsp;</legend>
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="superN form-check-input box-check" id="karbaranDeleteN" type="checkbox" name="karbaranDeleteN">
                                                        <label class="form-check-label">حذف</label>
                                                    </div>
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="adminN form-check-input box-check" id="karbaranChangeN" type="checkbox" name="changeKarbaranN">
                                                        <label class="form-check-label">تغییر</label>
                                                    </div>
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="poshtibanN form-check-input box-check" id="karbaranSeeN" type="checkbox"  name="seeKarbaranN">
                                                        <label class="form-check-label">مشاهده</label>
                                                    </div>
                                                </fieldset>
                                                <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                    <legend  class="float-none w-auto secondLevel fs-6"> <input type="checkbox" id="specialSettingN" class="webN form-check-input" name="specialSettingN" />تنظمیات امتیاز </legend>
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="superN form-check-input" id="specialDeleteN" type="checkbox" name="specialDeleteN"> 
                                                        <label class="form-check-label box-check">حذف</label>
                                                    </div>
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="adminN form-check-input" type="checkbox" id="specialChangeN" name="changeSpecialSettingN">
                                                        <label class="form-check-label box-check">تغییر</label>
                                                    </div>
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="poshtibanN web form-check-input" id="specialSeeN" type="checkbox" name="seeSpecialSettingN">
                                                        <label class="form-check-label box-check">مشاهده</label>
                                                    </div>
                                                </fieldset>
                                          </fieldset>
                                        </fieldset>
                                      </div>
                                </div>
                            </div>

                            <div class="c-checkout tab-pane" id="peoples" style="border-radius:10px 10px 2px 2px;">
                                <div class="container">
                                    <div class="row">
                                    <fieldset class="border rounded-3">
                                       <legend  class="float-none w-auto forLegend"><input type="checkbox" class="personsN form-check-input" name="personsN"/> تعریف عناصر  </legend>
                                         <fieldset class="border rounded-3">
                                            <legend  class="float-none w-auto firstLevel"><input type="checkbox" class="personsN form-check-input" name="personsN"/>کاربران </legend>
                                                <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                    <legend  class="float-none w-auto fs-6 secondLevel"> <input type="checkbox" class="personN form-check-input" id="customersN" name="customersN"/>مشتریان</legend>
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="superN form-check-input box-check" id="deleteCustomersN" type="checkbox" name="deleteCustomersN">
                                                        <label class="form-check-label">حذف</label>
                                                    </div>
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="adminN form-check-input box-check" type="checkbox" id="changeCustomersN" name="changeCustomersN">
                                                        <label class="form-check-label">تغییر</label>
                                                    </div>
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="poshtibanN form-check-input box-check" type="checkbox" id="seeCustomersN" name="seeCustomersN">
                                                        <label class="form-check-label">مشاهده</label>
                                                    </div>
                                                </fieldset>
                                           </fieldset>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>

                            <div class="c-checkout tab-pane" id="kalasTab" style="border-radius:10px 10px 2px 2px;">
                                <div class="container">
                                    <div class="row">
                                    <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                        <legend  class="float-none w-auto forLegend"><input type="checkbox" class="kalasN form-check-input" name="kalasN"/>   عملیات   </legend>
                                        <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                            <legend  class="float-none w-auto firstLevel"><input type="checkbox" class="kalasN form-check-input" name="kalasN"/>کالا ها </legend>
                                                <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                <legend  class="float-none w-auto fs-6 secondLevel"> <input type="checkbox" class="kalaN form-check-input" id="kalaListN" name="kalaListN"/>لیست کالاها</legend>
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="superN form-check-input box-check" id="deleteKalaListN" type="checkbox" name="deleteKalaListN">
                                                        <label class="form-check-label">حذف</label>
                                                    </div>
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="adminN form-check-input box-check" type="checkbox" id="changeKalaListN" name="changeKalaListN">
                                                        <label class="form-check-label">تغییر</label>
                                                    </div>
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="poshtibanN form-check-input box-check" type="checkbox" id="seeKalaListN" name="seeKalaListN">
                                                        <label class="form-check-label">مشاهده</label>
                                                    </div>
                                                </fieldset>
                                                <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                    <legend  class="float-none w-auto fs-6 secondLevel"> <input type="checkbox" class="kalaN form-check-input" id="requestedKalaN" name="requestedKalaN"/>درخواستی ها</legend>
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="superN form-check-input box-check" id="deleteRequestedKalaN" type="checkbox" name="deleteRequestedKalaN">
                                                        <label class="form-check-label">حذف</label>
                                                    </div>
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="adminN form-check-input box-check " type="checkbox" id="changeRequestedKalaN" name="changeRequestedKalaN">
                                                        <label class="form-check-label">تغییر</label>
                                                    </div>
                                                    <div class="form-check admin-accesss-level">
                                                    <input class="poshtibanN form-check-input box-check " type="checkbox" id="seeRequestedKalaN" name="seeRequestedKalaN">
                                                        <label class="form-check-label">مشاهده</label>
                                                    </div>
                                                </fieldset>
                                                <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                    <legend  class="float-none w-auto fs-6 secondLevel"> <input type="checkbox" class="kalaN form-check-input" id="fastKalaN" name="fastKalaN"/>فست کالا</legend>
                                                    <div class="form-check admin-accesss-level">
                                                    <input class="superN form-check-input box-check" id="deleteFastKalaN" type="checkbox" name="deleteFastKalaN">
                                                        <label class="form-check-label">حذف</label>
                                                    </div>
                                                    <div class="form-check admin-accesss-level">
                                                    <input class="adminN form-check-input box-check" type="checkbox" id="changeFastKalaN" name="changeFastKalaN">
                                                        <label class="form-check-label">تغییر</label>
                                                    </div>
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="poshtibanN form-check-input box-check" type="checkbox" id="seeFastKalaN" name="seeFastKalaN">
                                                        <label class="form-check-label">مشاهده</label>
                                                    </div>
                                                </fieldset>
                                                <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                <legend  class="float-none w-auto fs-6 secondLevel"> <input type="checkbox" class="kalaN form-check-input" id="pishKharidN" name="pishKharidN" />پیش خرید</legend>
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="superN form-check-input box-check" id="deletePishKharidN" type="checkbox" name="deletePishKharidN">
                                                        <label class="form-check-label">حذف</label>
                                                    </div>
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="adminN form-check-input box-check" type="checkbox" id="changePishKharidN" name="changePishKharidN">
                                                        <label class="form-check-label">تغییر</label>
                                                    </div>
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="poshtibanN form-check-input box-check" type="checkbox"  id="seePishKharidN" name="seePishKharidN">
                                                        <label class="form-check-label">مشاهده</label>
                                                    </div>
                                                </fieldset>
                                                <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                <legend  class="float-none w-auto fs-6 secondLevel"> <input type="checkbox" class="kalaN form-check-input" id="brandsN" name="brandsN"/>برند ها</legend>
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="superN form-check-input box-check" id="deleteBrandsN" type="checkbox" name="deleteBrandsN">
                                                        <label class="form-check-label">حذف</label>
                                                    </div>
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="adminN form-check-input box-check" type="checkbox" id="changeBrandsN" name="changeBrandsN">
                                                        <label class="form-check-label">تغییر</label>
                                                    </div>
                                                    <div class="form-check admin-accesss-level">
                                                    <input class="poshtibanN form-check-input box-check" type="checkbox" id="seeBrandsN" name="seeBrandsN">
                                                        <label class="form-check-label">مشاهده</label>
                                                    </div>
                                                </fieldset>
                                                <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                    <legend  class="float-none w-auto fs-6 secondLevel"> <input type="checkbox" class="kalaN form-check-input" id="alertedN" name="alertedN"/>کالاهای شامل هشدار</legend>
                                                    <div class="form-check admin-accesss-level">
                                                    <input class="superN form-check-input box-check" id="deleteAlertedN" type="checkbox" name="deleteAlertedN">
                                                        <label class="form-check-label">حذف</label>
                                                    </div>
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="adminN form-check-input box-check" type="checkbox" id="changeAlertedN" name="changeAlertedN">
                                                        <label class="form-check-label">تغییر</label>
                                                    </div>
                                                    <div class="form-check admin-accesss-level">
                                                    <input class="poshtibanN form-check-input box-check" type="checkbox" id="seeAlertedN" name="seeAlertedN">
                                                        <label class="form-check-label">مشاهده</label>
                                                    </div>
                                                </fieldset>
                                                <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                    <legend  class="float-none w-auto fs-6 secondLevel"> <input type="checkbox" class="kalaN form-check-input" id="groupListN" name="groupListN" />دسته بندی کالاها</legend>
                                                    
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="superN form-check-input box-check" id="deleteGroupListN" type="checkbox" name="deleteGroupListN">
                                                        <label class="form-check-label">حذف</label>
                                                    </div>
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="adminN form-check-input box-check" type="checkbox" id="changeGroupListN" name="changeGroupListN" >
                                                        <label class="form-check-label">تغییر</label>
                                                    </div>
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="poshtibanN form-check-input box-check" type="checkbox" id="seeGroupListN" name="seeGroupListN" >
                                                        <label class="form-check-label">مشاهده</label>
                                                    </div>
                                                </fieldset>

                                                <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                    <legend  class="float-none w-auto fs-6 secondLevel"> <input type="checkbox" class="kalaN form-check-input" id="groupListN" name="groupListN" /> سفارشات فروش  </legend>
                                                    
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="superN form-check-input box-check" id="deleteGroupListN" type="checkbox" name="deleteGroupListN">
                                                        <label class="form-check-label">حذف</label>
                                                    </div>
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="adminN form-check-input box-check" type="checkbox" id="changeGroupListN" name="changeGroupListN" >
                                                        <label class="form-check-label">تغییر</label>
                                                    </div>
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="poshtibanN form-check-input box-check" type="checkbox" id="seeGroupListN" name="seeGroupListN" >
                                                        <label class="form-check-label">مشاهده</label>
                                                    </div>
                                                </fieldset>
                                                <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                    <legend  class="float-none w-auto fs-6 secondLevel"> <input type="checkbox" class="kalaN form-check-input" id="groupListN" name="groupListN" /> پیام ها  </legend>
                                                    
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="superN form-check-input box-check" id="deleteGroupListN" type="checkbox" name="deleteGroupListN">
                                                        <label class="form-check-label">حذف</label>
                                                    </div>
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="adminN form-check-input box-check" type="checkbox" id="changeGroupListN" name="changeGroupListN" >
                                                        <label class="form-check-label">تغییر</label>
                                                    </div>
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="poshtibanN form-check-input box-check" type="checkbox" id="seeGroupListN" name="seeGroupListN" >
                                                        <label class="form-check-label">مشاهده</label>
                                                    </div>
                                                </fieldset>
                                           </fieldset>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>


                            <div class="c-checkout tab-pane" id="messages" style="border-radius:10px 10px 2px 2px;">
                                <div class="container">
                                    <div class="row">
                                         <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                            <legend  class="float-none w-auto forLegend"><input type="checkbox" class="kalasN form-check-input" name="kalasN"/>  گزارشات  </legend>
                                                   
                                                  
                                         <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                            <legend  class="float-none w-auto firstLevel"><input type="checkbox" class="kalasN form-check-input" name="kalasN"/>  مشتریان </legend>
                                                    <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                    <legend  class="float-none w-auto fs-6 secondLevel"> <input type="checkbox" class="kalaN form-check-input" id="kalaListN" name="kalaListN"/>لیست مشتریان</legend>
                                                        <div class="form-check admin-accesss-level">
                                                            <input class="superN form-check-input box-check" id="deleteKalaListN" type="checkbox" name="deleteKalaListN">
                                                            <label class="form-check-label">حذف</label>
                                                        </div>
                                                        <div class="form-check admin-accesss-level">
                                                            <input class="adminN form-check-input box-check" type="checkbox" id="changeKalaListN" name="changeKalaListN">
                                                            <label class="form-check-label">تغییر</label>
                                                        </div>
                                                        <div class="form-check admin-accesss-level">
                                                            <input class="poshtibanN form-check-input box-check" type="checkbox" id="seeKalaListN" name="seeKalaListN">
                                                            <label class="form-check-label">مشاهده</label>
                                                        </div>
                                                    </fieldset>
                                                    <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                        <legend  class="float-none w-auto fs-6 secondLevel"> <input type="checkbox" class="kalaN form-check-input" id="requestedKalaN" name="requestedKalaN"/> اشخاص رسمی </legend>
                                                        <div class="form-check admin-accesss-level">
                                                            <input class="superN form-check-input box-check" id="deleteRequestedKalaN" type="checkbox" name="deleteRequestedKalaN">
                                                            <label class="form-check-label">حذف</label>
                                                        </div>
                                                        <div class="form-check admin-accesss-level">
                                                            <input class="adminN form-check-input box-check " type="checkbox" id="changeRequestedKalaN" name="changeRequestedKalaN">
                                                            <label class="form-check-label">تغییر</label>
                                                        </div>
                                                        <div class="form-check admin-accesss-level">
                                                        <input class="poshtibanN form-check-input box-check " type="checkbox" id="seeRequestedKalaN" name="seeRequestedKalaN">
                                                            <label class="form-check-label">مشاهده</label>
                                                        </div>
                                                    </fieldset>
                                             </fieldset>

                                            

                                              <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                 <legend  class="float-none w-auto forLegend firstLevel"><input type="checkbox" class="kalasN form-check-input" name="kalasN"/>  بازیها و لاتری </legend>
                                                        <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                        <legend  class="float-none w-auto fs-6 secondLevel"> <input type="checkbox" class="kalaN form-check-input" id="kalaListN" name="kalaListN"/> نتیجه لاتری  </legend>
                                                            <div class="form-check admin-accesss-level">
                                                                <input class="superN form-check-input box-check" id="deleteKalaListN" type="checkbox" name="deleteKalaListN">
                                                                <label class="form-check-label">حذف</label>
                                                            </div>
                                                            <div class="form-check admin-accesss-level">
                                                                <input class="adminN form-check-input box-check" type="checkbox" id="changeKalaListN" name="changeKalaListN">
                                                                <label class="form-check-label">تغییر</label>
                                                            </div>
                                                            <div class="form-check admin-accesss-level">
                                                                <input class="poshtibanN form-check-input box-check" type="checkbox" id="seeKalaListN" name="seeKalaListN">
                                                                <label class="form-check-label">مشاهده</label>
                                                            </div>
                                                        </fieldset>
                                                        <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                            <legend  class="float-none w-auto fs-6 secondLevel"> <input type="checkbox" class="kalaN form-check-input" id="requestedKalaN" name="requestedKalaN"/> گیمر لیست </legend>
                                                            <div class="form-check admin-accesss-level">
                                                                <input class="superN form-check-input box-check" id="deleteRequestedKalaN" type="checkbox" name="deleteRequestedKalaN">
                                                                <label class="form-check-label">حذف</label>
                                                            </div>
                                                            <div class="form-check admin-accesss-level">
                                                                <input class="adminN form-check-input box-check " type="checkbox" id="changeRequestedKalaN" name="changeRequestedKalaN">
                                                                <label class="form-check-label">تغییر</label>
                                                            </div>
                                                            <div class="form-check admin-accesss-level">
                                                            <input class="poshtibanN form-check-input box-check " type="checkbox" id="seeRequestedKalaN" name="seeRequestedKalaN">
                                                                <label class="form-check-label">مشاهده</label>
                                                            </div>
                                                        </fieldset>
                                                </fieldset>
                                                <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                <legend  class="float-none w-auto fs-6 firstLevel"> <input type="checkbox" class="kalaN form-check-input" id="kalaListN" name="kalaListN"/> پرداخت آنلاین  </legend>
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="superN form-check-input box-check" id="deleteKalaListN" type="checkbox" name="deleteKalaListN">
                                                        <label class="form-check-label">حذف</label>
                                                    </div>
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="adminN form-check-input box-check" type="checkbox" id="changeKalaListN" name="changeKalaListN">
                                                        <label class="form-check-label">تغییر</label>
                                                    </div>
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="poshtibanN form-check-input box-check" type="checkbox" id="seeKalaListN" name="seeKalaListN">
                                                        <label class="form-check-label">مشاهده</label>
                                                    </div>
                                                </fieldset>
                                          </fieldset>
                                     </div>
                                 </div>
                                </div>
                            </div>
                         </div>
                       </div>
                <div class="modal-footer">
                     <button type="submit" class="btn btn-success bg-success btn-sm" form="addingKarbarForm">ذخیره <i class="fa fa-save"></i></button>
                     <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">انصراف <i class="fa fa-xmark"></i> </button>  
                </div>
               </div>
            </form>
          </div>
    </div>


























<!-- ///////////////////////////////////////// the old modal if the new doesn't work i active the old one  -->
    
    <!-- Modal for editing role-->
   <div class="modal fade" id="editUserRoles" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl  modal-dialog-scrollable">
            <form style="display:inline" action="{{url('/doEditAdmin')}}" method="post" enctype="multipart/form-data">
            <input type="text" style="display:none" id="editAdminId" value="" name="adminId"/>
            @csrf
                <div class="modal-content">
                        <div class="modal-header bg-success text-white py-2">
                            <button type="button" class="btn btn-danger bg-danger btn-close" data-dismiss="modal" aria-label="Close"> </button>
                            
                            <h5 class="modal-title" id="staticBackdropLabel">ویرایش کاربر</h5>
                        </div>

  
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <select class="form-select" name="adminType"  style="display:inline;" id="adminType">
                                    <option hidden>نوع کاربر</option>
                                    <option value="super">سوپرادمین</option>
                                    <option value="admin">ادمین</option>
                                    <option value="poshtiban">پشتیبان</option>
                                </select>
                                <div class="form-group">
                                    <input type="text" class="form-control mt-2" placeholder="نام کاربری" id="userName" name="userName">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="اسم" id="name" name="name">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control mt-2" id="lastName" name="lastName" placeholder="فامیلی">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <div class="form-group">
                                    <input type="password" class="form-control" placeholder="رمز" id="password" name="password">
                                </div>
                                <div class="form-group">
                                    <input type="file" class="form-control mt-2" name="userPic" placeholder="تصویر">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <div class="form-check d-flex flex-end mt-3">
                                    <input class="form-check-input p-2" type="radio" value="male" name="gender" id="manGender"> &nbsp;
                                    <label class="form-check-label mx-4 karbar-label fs-6" >مرد </label> &nbsp; &nbsp;
                                    <input class="form-check-input p-2" type="radio" value="female" name="gender" id="womanGender"> &nbsp;
                                    <label class="form-check-label mx-4 karbar-labe fs-6">زن</label>
                                </div>
                                <div class="form-check d-flex flex-end mt-3">
                                    <input class="form-check-input p-2" type="checkbox" name="activeState" id="activeState"> &nbsp;
                                    <label class="form-check-label mx-4 karbar-label fs-6">فعال</label> &nbsp; &nbsp;
                                </div>
                            </div>
                        </div>
                            <span style="font-size:22px; font-weight:bold; border-bottom:2px solid gray; margin:2px 2px 20px 2px; text-align:center;">جزئیات نقش کاربر</span>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 mt-0">
                                    <div class="row">
                                        <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                            <legend  class="float-none w-auto forLegend"> <input type="checkbox" name="manageWeb" class="webPage form-check-input"/>مدیریت وب</legend>
                                            <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                <legend  class="float-none w-auto forLegend"> <input type="checkbox" name="homePage" id="homePage" class="web form-check-input"/>صفحه اصلی</legend>
                                                <div class="form-check admin-accesss-level">
                                                    <input class="super form-check-input p-2" id="homeDelete" type="checkbox" name="homeDelete">
                                                    <label class="form-check-label fs-6">حذف</label>
                                                </div>
                                                <div class="form-check admin-accesss-level">
                                                    <input class="admin form-check-input p-2" id="homeChange" type="checkbox" name="changeHomePage">
                                                    <label class="form-check-label fs-6">تغییر</label>
                                                </div>
                                                <div class="form-check admin-accesss-level">
                                                    <input class="poshtiban form-check-input p-2" id="homeSee" type="checkbox" name="seeHomePage">
                                                    <label class="form-check-label fs-6">مشاهده</label>
                                                </div>
                                            </fieldset>
                                            <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                <legend  class="float-none w-auto forLegend"> <input type="checkbox" name="karbaran" id="karbaran" class="web form-check-input"/>کاربران</legend>
                                                <div class="form-check admin-accesss-level">
                                                    <input class="super form-check-input p-2" id="karbaranDelete" type="checkbox" name="karbaranDelete">
                                                    <label class="form-check-label fs-6">حذف</label>
                                                </div>
                                                <div class="form-check admin-accesss-level">
                                                    <input class="admin form-check-input p-2" id="karbaranChange" type="checkbox" name="changeKarbaran">
                                                    <label class="form-check-label fs-6">تغییر</label>
                                                </div>
                                                <div class="form-check admin-accesss-level">
                                                    <input class="poshtiban form-check-input p-2" id="karbaranSee" type="checkbox"  name="seeKarbaran">
                                                    <label class="form-check-label fs-6">مشاهده</label>
                                                </div>
                                            </fieldset>
                                            <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                <legend  class="float-none w-auto forLegend"> <input type="checkbox" id="specialSetting" class="web form-check-input" name="specialSetting" />تنظمیات اختصاصی</legend>
                                                <div class="form-check admin-accesss-level">
                                                    <input class="super form-check-input p-2" id="specialDelete" type="checkbox" name="specialDelete">
                                                    <label class="form-check-label fs-6">حذف</label>
                                                </div>
                                                <div class="form-check admin-accesss-level">
                                                    <input class="admin form-check-input p-2" type="checkbox" id="specialChange" name="changeSpecialSetting">
                                                    <label class="form-check-label fs-6">تغییر</label>
                                                </div>
                                                <div class="form-check admin-accesss-level">
                                                    <input class="poshtiban web form-check-input p-2" id="specialSee" type="checkbox" name="seeSpecialSetting">
                                                    <label class="form-check-label fs-6">مشاهده</label>
                                                </div>
                                            </fieldset>
                                        </fieldset>
                                        </div>
                                        <div class="row">
                                        <fieldset class="border rounded-3">
                                            <legend  class="float-none w-auto forLegend"><input type="checkbox" class="persons form-check-input" name="persons"/>اشخاص</legend>
                                            <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                <legend  class="float-none w-auto forLegend"> <input type="checkbox" class="person form-check-input" id="customers" name="customers"/>مشتریان</legend>
                                                <div class="form-check admin-accesss-level">
                                                    <input class="super form-check-input p-2" id="deleteCustomers" type="checkbox" name="deleteCustomers">
                                                    <label class="form-check-label fs-6">حذف</label>
                                                </div>
                                                <div class="form-check admin-accesss-level">
                                                    <input class="admin form-check-input p-2" type="checkbox" id="changeCustomers" name="changeCustomers">
                                                    <label class="form-check-label fs-6">تغییر</label>
                                                </div>
                                                <div class="form-check admin-accesss-level">
                                                    <input class="poshtiban form-check-input p-2" type="checkbox" id="seeCustomers" name="seeCustomers">
                                                    <label class="form-check-label fs-6">مشاهده</label>
                                                </div>
                                            </fieldset>
                                            <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                <legend  class="float-none w-auto forLegend"> <input type="checkbox" class="person form-check-input" id="officials" name="officials"/>اشخاص رسمی</legend>
                                                <div class="form-check admin-accesss-level">
                                                    <input class="super form-check-input p-2" id="deleteOfficials" type="checkbox" name="deleteOfficials">
                                                    <label class="form-check-label fs-6">حذف</label>
                                                </div>
                                                <div class="form-check admin-accesss-level">
                                                    <input class="admin form-check-input p-2" type="checkbox" id="changeOfficials" name="changeOfficials">
                                                    <label class="form-check-label fs-6">تغییر</label>
                                                </div>
                                                <div class="form-check admin-accesss-level">
                                                    <input class="poshtiban form-check-input p-2" type="checkbox" id="seeOfficials" name="seeOfficials">
                                                    <label class="form-check-label fs-6">مشاهده</label>
                                                </div>
                                            </fieldset>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 mt-0">
                                    <div class="row">
                                    <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                        <legend  class="float-none w-auto forLegend"><input type="checkbox" class="kalas form-check-input" name="kalas"/>کالا ها </legend>
                                        <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                            <legend  class="float-none w-auto forLegend"> <input type="checkbox" class="kala form-check-input" id="kalaList" name="kalaList"/>لیست کالاها</legend>
                                            <div class="form-check admin-accesss-level">
                                                <input class="super form-check-input p-2" id="deleteKalaList" type="checkbox" name="deleteKalaList">
                                                <label class="form-check-label fs-6">حذف</label>
                                            </div>
                                            <div class="form-check admin-accesss-level">
                                                <input class="admin form-check-input p-2" type="checkbox" id="changeKalaList" name="changeKalaList">
                                                <label class="form-check-label fs-6">تغییر</label>
                                            </div>
                                            <div class="form-check admin-accesss-level">
                                                <input class="poshtiban form-check-input p-2" type="checkbox" id="seeKalaList" name="seeKalaList">
                                                <label class="form-check-label fs-6">مشاهده</label>
                                            </div>
                                        </fieldset>
                                        <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                            <legend  class="float-none w-auto forLegend"> <input type="checkbox" class="kala form-check-input" id="requestedKala" name="requestedKala"/>درخواستی ها</legend>
                                            <div class="form-check admin-accesss-level">
                                                <input class="super form-check-input p-2" id="deleteRequestedKala" type="checkbox" name="deleteRequestedKala">
                                                <label class="form-check-label fs-6">حذف</label>
                                            </div>
                                            <div class="form-check admin-accesss-level">
                                                <input class="admin form-check-input p-2" type="checkbox" id="changeRequestedKala" name="changeRequestedKala">
                                                <label class="form-check-label fs-6">تغییر</label>
                                            </div>
                                            <div class="form-check admin-accesss-level">
                                                <input class="poshtiban form-check-input p-2" type="checkbox" id="seeRequestedKala" name="seeRequestedKala">
                                                <label class="form-check-label fs-6">مشاهده</label>
                                            </div>
                                        </fieldset>
                                        <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                            <legend  class="float-none w-auto forLegend"> <input type="checkbox" class="kala form-check-input" id="fastKala" name="fastKala"/>فست کالا</legend>
                                            <div class="form-check admin-accesss-level">
                                                <input class="super form-check-input p-2" id="deleteFastKala" type="checkbox" name="deleteFastKala">
                                                <label class="form-check-label fs-6">حذف</label>
                                            </div>
                                            <div class="form-check admin-accesss-level">
                                                <input class="admin form-check-input p-2" type="checkbox" id="changeFastKala" name="changeFastKala">
                                                <label class="form-check-label fs-6">تغییر</label>
                                            </div>
                                            <div class="form-check admin-accesss-level">
                                                <input class="poshtiban form-check-input p-2" type="checkbox" id="seeFastKala" name="seeFastKala">
                                                <label class="form-check-label fs-6">مشاهده</label>
                                            </div>
                                        </fieldset>
                                        <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                            <legend  class="float-none w-auto forLegend"> <input type="checkbox" class="kala form-check-input" id="pishKharid" name="pishKharid" />پیش خرید</legend>
                                            <div class="form-check admin-accesss-level">
                                                <input class="super form-check-input p-2" id="deletePishKharid" type="checkbox" name="deletePishKharid">
                                                <label class="form-check-label fs-6">حذف</label>
                                            </div>
                                            <div class="form-check admin-accesss-level">
                                                <input class="admin form-check-input p-2" type="checkbox" id="changePishKharid" name="changePishKharid">
                                                <label class="form-check-label fs-6">تغییر</label>
                                            </div>
                                            <div class="form-check admin-accesss-level">
                                                <input class="poshtiban form-check-input p-2" type="checkbox"  id="seePishKharid" name="seePishKharid">
                                                <label class="form-check-label fs-6">مشاهده</label>
                                            </div>
                                        </fieldset>
                                        <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                            <legend  class="float-none w-auto forLegend"> <input type="checkbox" class="kala form-check-input" id="brands" name="brands"/>برند ها</legend>
                                            <div class="form-check admin-accesss-level">
                                                <input class="super form-check-input p-2" id="deleteBrands" type="checkbox" name="deleteBrands">
                                                <label class="form-check-label fs-6">حذف</label>
                                            </div>
                                            <div class="form-check admin-accesss-level">
                                                <input class="admin form-check-input p-2" type="checkbox" id="changeBrands" name="changeBrands">
                                                <label class="form-check-label fs-6">تغییر</label>
                                            </div>
                                            <div class="form-check admin-accesss-level">
                                                <input class="poshtiban form-check-input p-2" type="checkbox" id="seeBrands" name="seeBrands">
                                                <label class="form-check-label fs-6">مشاهده</label>
                                            </div>
                                        </fieldset>
                                        <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                            <legend  class="float-none w-auto forLegend"> <input type="checkbox" class="kala form-check-input" id="alerted" name="alerted"/>کالاهای شامل هشدار</legend>
                                            <div class="form-check admin-accesss-level">
                                                <input class="super form-check-input p-2" id="deleteAlerted" type="checkbox" name="deleteAlerted">
                                                <label class="form-check-label fs-6">حذف</label>
                                            </div>
                                            <div class="form-check admin-accesss-level">
                                                <input class="admin form-check-input p-2" type="checkbox" id="changeAlerted" name="changeAlerted">
                                                <label class="form-check-label fs-6">تغییر</label>
                                            </div>
                                            <div class="form-check admin-accesss-level">
                                                <input class="poshtiban form-check-input p-2" type="checkbox" id="seeAlerted" name="seeAlerted">
                                                <label class="form-check-label fs-6">مشاهده</label>
                                            </div>
                                        </fieldset>
                                        <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                            <legend  class="float-none w-auto forLegend"> <input type="checkbox" class="kala form-check-input" id="groupList" name="groupList" />دسته بندی کالاها</legend>
                                            <div class="form-check admin-accesss-level">
                                                <input class="super form-check-input p-2" id="deleteGroupList" type="checkbox" name="deleteGroupList">
                                                <label class="form-check-label fs-6">حذف</label>
                                            </div>
                                            <div class="form-check admin-accesss-level">
                                                <input class="admin form-check-input p-2" type="checkbox" id="changeGroupList" name="changeGroupList" >
                                                <label class="form-check-label fs-6">تغییر</label>
                                            </div>
                                            <div class="form-check admin-accesss-level">
                                                <input class="poshtiban form-check-input p-2" type="checkbox" id="seeGroupList" name="seeGroupList" >
                                                <label class="form-check-label fs-6">مشاهده</label>
                                            </div>
                                        </fieldset>
                                    </fieldset>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 mt-0">
                                    <fieldset class="border rounded-3">
                                        <legend  class="float-none w-auto forLegend"><input type="checkbox" class="messages form-check-input" name="messages" />پیام ها</legend>
                                        <div class="form-check admin-accesss-level">
                                            <input class="super form-check-input p-2" id="deleteMessages" type="checkbox" name="deleteMessages">
                                            <label class="form-check-label fs-6">حذف</label>
                                        </div>
                                        <div class="form-check admin-accesss-level">
                                            <input class="admin form-check-input p-2" type="checkbox" id="changeMessages" name="changeMessages">
                                            <label class="form-check-label fs-6">تغییر</label>
                                        </div>
                                        <div class="form-check admin-accesss-level">
                                            <input class="poshtiban form-check-input p-2" type="checkbox" id="seeMessages" name="seeMessages">
                                            <label class="form-check-label fs-6">مشاهده</label>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success bg-success">ذخیره <i class="fa fa-save"></i></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div> 



    <!-- Modal  for adding user roles -->
    <div class="modal fade" id="addingKarbar" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl  modal-dialog-scrollable">
            <form style="display:inline" action="{{url('/doAddAdmin')}}" method="post" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white py-2">
                        @csrf
                        <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="fa fa-xmark"></i></button>
                        <span style="font-size:20px; font-weight:bold; text-align:center;">افزودن کاربر </span>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <select class="form-select"  style="display:inline;" name="AdminTypeN" id="adminTypeN">
                                    <option hidden>نوع کاربر</option>
                                    <option value="super">سوپرادمین</option>
                                    <option value="admin">ادمین</option>
                                    <option value="poshtiban">پشتیبان</option>
                                </select>
                                <div class="form-group">
                                    <input type="text" class="form-control mt-2" placeholder="نام کاربری" name="userName">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="اسم" name="name">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control mt-2" name="lastName" placeholder="فامیلی">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <div class="form-group">
                                    <input type="password" class="form-control" placeholder="رمز" name="password">
                                </div>
                                <div class="form-group">
                                    <input type="file" class="form-control mt-2" id="userPic" placeholder="تصویر">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <div class="form-check d-flex flex-end mt-3">
                                    <input class="form-check-input p-2" type="radio" value="male" name="gender" id="manGender" checked> &nbsp;
                                    <label class="form-check-label mx-4 karbar-label fs-6" for="flexRadioDefault1">مرد </label> &nbsp; &nbsp;
                                    <input class="form-check-input p-2" type="radio" value="female" name="gender" id="womanGender"> &nbsp;
                                    <label class="form-check-label mx-4 karbar-label fs-6" for="flexRadioDefault2">زن</label>
                                </div>
                            </div>
                        </div>
                        <span style="font-size:22px; font-weight:bold; border-bottom:2px solid gray; margin:2px 2px 20px 2px; text-align:center;">سطح دسترسی</span>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 mt-0">
                                    <div class="row">
                                        <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                            <legend  class="float-none w-auto forLegend"> <input type="checkbox" name="manageWeb" class="webPageN form-check-input"/>مدیریت وب</legend>
                                            <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                <legend  class="float-none w-auto forLegend"> <input type="checkbox" name="homePageN" id="homePageN" class="webN form-check-input"/>صفحه اصلی</legend>
                                                <div class="form-check admin-accesss-level">
                                                    <input class="superN form-check-input p-2" id="homeDeleteN" type="checkbox" name="homeDeleteN">
                                                    <label class="form-check-label fs-6">حذف</label>
                                                </div>
                                                <div class="form-check admin-accesss-level">
                                                    <input class="adminN form-check-input p-2" id="homeChangeN" type="checkbox" name="changeHomePageN">
                                                    <label class="form-check-label fs-6">تغییر</label>
                                                </div>
                                                <div class="form-check admin-accesss-level">
                                                    <input class="poshtibanN form-check-input p-2" id="homeSeeN" type="checkbox" name="seeHomePageN">
                                                    <label class="form-check-label fs-6">مشاهده</label>
                                                </div>
                                            </fieldset>
                                            <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                <legend  class="float-none w-auto forLegend"> <input type="checkbox" name="karbaranN" id="karbaranN" class="webN form-check-input"/>کاربران</legend>
                                                <div class="form-check admin-accesss-level">
                                                    <input class="superN form-check-input p-2" id="karbaranDeleteN" type="checkbox" name="karbaranDeleteN">
                                                    <label class="form-check-label fs-6">حذف</label>
                                                </div>
                                                <div class="form-check admin-accesss-level">
                                                    <input class="adminN form-check-input p-2" id="karbaranChangeN" type="checkbox" name="changeKarbaranN">
                                                    <label class="form-check-label fs-6">تغییر</label>
                                                </div>
                                                <div class="form-check admin-accesss-level">
                                                    <input class="poshtibanN form-check-input p-2" id="karbaranSeeN" type="checkbox"  name="seeKarbaranN">
                                                    <label class="form-check-label fs-6">مشاهده</label>
                                                </div>
                                            </fieldset>
                                            <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                <legend  class="float-none w-auto forLegend"> <input type="checkbox" id="specialSettingN" class="webN form-check-input" name="specialSettingN" />تنظمیات اختصاصی</legend>
                                                <div class="form-check admin-accesss-level">
                                                    <input class="superN form-check-input p-2" id="specialDeleteN" type="checkbox" name="specialDeleteN">
                                                    <label class="form-check-label fs-6">حذف</label>
                                                </div>
                                                <div class="form-check admin-accesss-level">
                                                    <input class="adminN form-check-input p-2" type="checkbox" id="specialChangeN" name="changeSpecialSettingN">
                                                    <label class="form-check-label fs-6">تغییر</label>
                                                </div>
                                                <div class="form-check admin-accesss-level">
                                                    <input class="poshtibanN web form-check-input p-2" id="specialSeeN" type="checkbox" name="seeSpecialSettingN">
                                                    <label class="form-check-label fs-6">مشاهده</label>
                                                </div>
                                            </fieldset>
                                        </fieldset>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 mt-0">
                                            <fieldset class="border rounded-3">
                                                <legend  class="float-none w-auto forLegend"><input type="checkbox" class="personsN form-check-input" name="personsN"/>اشخاص</legend>
                                                <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                    <legend  class="float-none w-auto forLegend"> <input type="checkbox" class="personN form-check-input" id="customersN" name="customersN"/>مشتریان</legend>
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="superN form-check-input p-2" id="deleteCustomersN" type="checkbox" name="deleteCustomersN">
                                                        <label class="form-check-label fs-6">حذف</label>
                                                    </div>
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="adminN form-check-input p-2" type="checkbox" id="changeCustomersN" name="changeCustomersN">
                                                        <label class="form-check-label fs-6">تغییر</label>
                                                    </div>
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="poshtibanN form-check-input p-2" type="checkbox" id="seeCustomersN" name="seeCustomersN">
                                                        <label class="form-check-label fs-6">مشاهده</label>
                                                    </div>
                                                </fieldset>
                                                <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                    <legend  class="float-none w-auto forLegend"> <input type="checkbox" class="personN form-check-input" id="officialsN" name="officialsN"/>اشخاص رسمی</legend>
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="superN form-check-input p-2" id="deleteOfficialsN" type="checkbox" name="deleteOfficialsN">
                                                        <label class="form-check-label fs-6">حذف</label>
                                                    </div>
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="adminN form-check-input p-2" type="checkbox" id="changeOfficialsN" name="changeOfficialsN">
                                                        <label class="form-check-label fs-6">تغییر</label>
                                                    </div>
                                                    <div class="form-check admin-accesss-level">
                                                        <input class="poshtibanN form-check-input p-2" type="checkbox" id="seeOfficialsN" name="seeOfficialsN">
                                                        <label class="form-check-label fs-6">مشاهده</label>
                                                    </div>
                                                </fieldset>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 mt-0">
                                    <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                        <legend  class="float-none w-auto forLegend"><input type="checkbox" class="kalasN form-check-input" name="kalasN"/>کالا ها </legend>
                                        <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                            <legend  class="float-none w-auto forLegend"> <input type="checkbox" class="kalaN form-check-input" id="kalaListN" name="kalaListN"/>لیست کالاها</legend>
                                            <div class="form-check admin-accesss-level">
                                                <input class="superN form-check-input p-2" id="deleteKalaListN" type="checkbox" name="deleteKalaListN">
                                                <label class="form-check-label fs-6">حذف</label>
                                            </div>
                                            <div class="form-check admin-accesss-level">
                                                <input class="adminN form-check-input p-2" type="checkbox" id="changeKalaListN" name="changeKalaListN">
                                                <label class="form-check-label fs-6">تغییر</label>
                                            </div>
                                            <div class="form-check admin-accesss-level">
                                                <input class="poshtibanN form-check-input p-2" type="checkbox" id="seeKalaListN" name="seeKalaListN">
                                                <label class="form-check-label fs-6">مشاهده</label>
                                            </div>
                                        </fieldset>
                                        <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                            <legend  class="float-none w-auto forLegend"> <input type="checkbox" class="kalaN form-check-input" id="requestedKalaN" name="requestedKalaN"/>درخواستی ها</legend>
                                            <div class="form-check admin-accesss-level">
                                                <input class="superN form-check-input p-2" id="deleteRequestedKalaN" type="checkbox" name="deleteRequestedKalaN">
                                                <label class="form-check-label fs-6">حذف</label>
                                            </div>
                                            <div class="form-check admin-accesss-level">
                                                <input class="adminN form-check-input p-2" type="checkbox" id="changeRequestedKalaN" name="changeRequestedKalaN">
                                                <label class="form-check-label fs-6">تغییر</label>
                                            </div>
                                            <div class="form-check admin-accesss-level">
                                                <input class="poshtibanN form-check-input p-2" type="checkbox" id="seeRequestedKalaN" name="seeRequestedKalaN">
                                                <label class="form-check-label fs-6">مشاهده</label>
                                            </div>
                                        </fieldset>
                                        <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                            <legend  class="float-none w-auto forLegend"> <input type="checkbox" class="kalaN form-check-input" id="fastKalaN" name="fastKalaN"/>فست کالا</legend>
                                            <div class="form-check admin-accesss-level">
                                                <input class="superN form-check-input p-2" id="deleteFastKalaN" type="checkbox" name="deleteFastKalaN">
                                                <label class="form-check-label fs-6">حذف</label>
                                            </div>
                                            <div class="form-check admin-accesss-level">
                                                <input class="adminN form-check-input p-2" type="checkbox" id="changeFastKalaN" name="changeFastKalaN">
                                                <label class="form-check-label fs-6">تغییر</label>
                                            </div>
                                            <div class="form-check admin-accesss-level">
                                                <input class="poshtibanN form-check-input p-2" type="checkbox" id="seeFastKalaN" name="seeFastKalaN">
                                                <label class="form-check-label fs-6">مشاهده</label>
                                            </div>
                                        </fieldset>
                                        <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                            <legend  class="float-none w-auto forLegend"> <input type="checkbox" class="kalaN form-check-input" id="pishKharidN" name="pishKharidN" />پیش خرید</legend>
                                            <div class="form-check admin-accesss-level">
                                                <input class="superN form-check-input p-2" id="deletePishKharidN" type="checkbox" name="deletePishKharidN">
                                                <label class="form-check-label fs-6">حذف</label>
                                            </div>
                                            <div class="form-check admin-accesss-level">
                                                <input class="adminN form-check-input p-2" type="checkbox" id="changePishKharidN" name="changePishKharidN">
                                                <label class="form-check-label fs-6">تغییر</label>
                                            </div>
                                            <div class="form-check admin-accesss-level">
                                                <input class="poshtibanN form-check-input p-2" type="checkbox"  id="seePishKharidN" name="seePishKharidN">
                                                <label class="form-check-label fs-6">مشاهده</label>
                                            </div>
                                        </fieldset>
                                        <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                            <legend  class="float-none w-auto forLegend"> <input type="checkbox" class="kalaN form-check-input" id="brandsN" name="brandsN"/>برند ها</legend>
                                            <div class="form-check admin-accesss-level">
                                                <input class="superN form-check-input p-2" id="deleteBrandsN" type="checkbox" name="deleteBrandsN">
                                                <label class="form-check-label fs-6">حذف</label>
                                            </div>
                                            <div class="form-check admin-accesss-level">
                                                <input class="adminN form-check-input p-2" type="checkbox" id="changeBrandsN" name="changeBrandsN">
                                                <label class="form-check-label fs-6">تغییر</label>
                                            </div>
                                            <div class="form-check admin-accesss-level">
                                                <input class="poshtibanN form-check-input p-2" type="checkbox" id="seeBrandsN" name="seeBrandsN">
                                                <label class="form-check-label fs-6">مشاهده</label>
                                            </div>
                                        </fieldset>
                                        <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                            <legend  class="float-none w-auto forLegend"> <input type="checkbox" class="kalaN form-check-input" id="alertedN" name="alertedN"/>کالاهای شامل هشدار</legend>
                                            <div class="form-check admin-accesss-level">
                                                <input class="superN form-check-input p-2" id="deleteAlertedN" type="checkbox" name="deleteAlertedN">
                                                <label class="form-check-label fs-6">حذف</label>
                                            </div>
                                            <div class="form-check admin-accesss-level">
                                                <input class="adminN form-check-input p-2" type="checkbox" id="changeAlertedN" name="changeAlertedN">
                                                <label class="form-check-label fs-6">تغییر</label>
                                            </div>
                                            <div class="form-check admin-accesss-level">
                                                <input class="poshtibanN form-check-input p-2" type="checkbox" id="seeAlertedN" name="seeAlertedN">
                                                <label class="form-check-label fs-6">مشاهده</label>
                                            </div>
                                        </fieldset>
                                        <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                            <legend  class="float-none w-auto forLegend"> <input type="checkbox" class="kalaN form-check-input" id="groupListN" name="groupListN" />دسته بندی کالاها</legend>
                                            <div class="form-check admin-accesss-level">
                                                <input class="superN form-check-input p-2" id="deleteGroupListN" type="checkbox" name="deleteGroupListN">
                                                <label class="form-check-label fs-6">حذف</label>
                                            </div>
                                            <div class="form-check admin-accesss-level">
                                                <input class="adminN form-check-input p-2" type="checkbox" id="changeGroupListN" name="changeGroupListN" >
                                                <label class="form-check-label fs-6">تغییر</label>
                                            </div>
                                            <div class="form-check admin-accesss-level">
                                                <input class="poshtibanN form-check-input p-2" type="checkbox" id="seeGroupListN" name="seeGroupListN" >
                                                <label class="form-check-label fs-6">مشاهده</label>
                                            </div>
                                        </fieldset>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 mt-0">
                                    <fieldset class="border rounded-3">
                                        <legend  class="float-none w-auto forLegend"><input type="checkbox" class="messagesN form-check-input" name="messagesN" />پیام ها</legend>
                                        <div class="form-check admin-accesss-level">
                                            <input class="superN form-check-input p-2" id="deleteMessagesN" type="checkbox" name="deleteMessagesN">
                                            <label class="form-check-label fs-6">حذف</label>
                                        </div>
                                        <div class="form-check admin-accesss-level">
                                            <input class="adminN form-check-input p-2" type="checkbox" id="changeMessagesN" name="changeMessagesN">
                                            <label class="form-check-label fs-6">تغییر</label>
                                        </div>
                                        <div class="form-check admin-accesss-level">
                                            <input class="poshtibanN form-check-input p-2" type="checkbox" id="seeMessagesN" name="seeMessagesN">
                                            <label class="form-check-label fs-6">مشاهده</label>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                       <div class="modal-footer">
                       <button type="submit" class="btn btn-success bg-success">ذخیره <i class="fa fa-save"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div> 
</main>

<script>
$("#addingNewKarbar").on("click", ()=>{
	  if (!($('.modal.in').length)) {
                $('.modal-dialog').css({
                  top: 0,
                  left: 111
                });
              }
              $('#addingKarbar').modal({
                backdrop: false,
                show: true
              });
              
              $('.modal-dialog').draggable({
                  handle: ".modal-header"
                });
  $("#addingKarbar").modal("show");
});
	
</script>
@endsection

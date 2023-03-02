@extends('layout.layout')
@section('content')

<div class="container-fluid containerDiv">
    <div class="row">
            <div class="col-lg-2 col-md-2 col-sm-3 sideBar">
                <fieldset class="border rounded mt-5 sidefieldSet">
                    <legend  class="float-none w-auto legendLabel mb-0"> پروفایل  </legend>
                    <div class="form-check">
                        <input class="form-check-input p-2 float-start" type="radio" name="settings" id="elseSettingsRadio">
                        <label class="form-check-label me-4" for="assesPast">  سطح دسترسی  </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input p-2 float-start" type="radio" name="settings" id="settingAndTargetRadio">
                        <label class="form-check-label me-4" for="assesPast"> تارگت ها و امتیازات </label>
                    </div>
                    
                </fieldset>
                </div>
            <div class="col-sm-10 col-md-10 col-sm-12 contentDiv">
                <div class="row contentHeader"> </div>
                <div class="row mainContent">
                    <div class="c-checkout container" id="haqiqi" style="background-color:#c5c5c5; margin:0.2% 0; margin-bottom:0; padding:0.8% !important; border-radius:10px 10px 2px 2px;">
                     <div class="col-sm-6">
                        <ul class="header-list nav nav-tabs" data-tabs="tabs">
                            <li><a class="active" data-toggle="tab" style="color:black;"  href="#custAddress"> اشخاص حقیقی </a></li>
                            <li><a data-toggle="tab" style="color:black;"  href="#moRagiInfo" style="margin-right:30px"> اشخاص حقوقی </a></li>
                        </ul>
                     </div>

                         <div class="c-checkout tab-content" style="background-color:#f5f5f5; margin:0;  padding:0.3%; border-radius:10px 10px 2px 2px;">
                            <div class="row c-checkout rounded-3 tab-pane active" id="custAddress" style="width:99%; margin:0 auto; padding:1% 0% 0% 0%">
                                <div class="col-sm-12">
                                    <div class="row" style="width:98%; padding:0 1% 2% 0%">
                                        @include('customer.partial.haqiqiForm')
                                    </div>
                                </div>
                            </div>
                            <div class="row c-checkout rounded-3 tab-pane" id="moRagiInfo" style="width:99%; margin:0 auto; padding:1% 0% 0% 0%">
                              <div class="col-sm-12">
                                <div class="row" style="width:98%; padding:0 1% 2% 0%;">
                                   
                                   @include('customer.partial.hoqoqiForm')
                                </div>
                             </div>
                          </div>
                       </div>
                   </div>
                </div>
                <div class="row contentFooter"> </div>
            </div>
    </div>
</div>

 @endsection
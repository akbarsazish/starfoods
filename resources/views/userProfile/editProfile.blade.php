@extends('layout.layout')
@section('content')

<style>
   ::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
        color: rgb(235, 218, 218) !important;
        opacity: 1;
        }

:-ms-input-placeholder { /* Internet Explorer 10-11 */
    color: rgb(235, 218, 218) !important;
    }

::-ms-input-placeholder { /* Microsoft Edge */
    color: rgb(235, 218, 218) !important;
    }
</style>


<div class="container">

    <div class="row">
        <div class="profielpage">
            <div class="mb-3 p-2 bg-light text-black rounded" align="center">
                <h5>ویرایش اطلاعات شخصی </h5>
            </div>
            <form action="{{url('customerUpdate') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="c-checkout container" style="background-color:#c5c5c5; margin:0.2% 0; margin-bottom:0; padding:0.5% !important; border-radius:10px 10px 2px 2px;">

                    <div class="col-sm-6" style="margin: 0; padding:0;">
                        <ul class="header-list nav nav-tabs" data-tabs="tabs" style="margin: 0; padding:0;">
                            <li><a class="active" data-toggle="tab" style="color:black;"  href="#custAddress"> اشصخاص حقیقی  </a></li>
                            <li><a data-toggle="tab" style="color:black;"  href="#moRagiInfo"> اشخاص حقوقی </a></li>
                        </ul>
                    </div>
                    <div class="c-checkout tab-content" style="background-color:#f5f5f5; margin:0;  padding:0.3%; border-radius:10px 10px 2px 2px;">
                            <div class="row c-checkout rounded-3 tab-pane active" id="custAddress" style="width:99%; margin:0 auto; padding:1% 0% 0% 0%">
                                <div class="col-sm-12">
                                    <div class="row " style="width:98%; padding:0 1% 2% 0%">
                                        <form action="">
                                            <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="mb-3 mt-3">
                                                    <label for="name" class="form-label">نام:</label>
                                                    <input type="text" class="form-control" id="name" placeholder="نام  " name="name">
                                                  </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="mb-3 mt-3">
                                                    <label for="familyName" class="form-label"> نام خانوادگی  :</label>
                                                    <input type="text" class="form-control" id="familyName" placeholder=" نام و نام خانوادی " name="familyName">
                                                  </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                  <div class="mb-3 mt-3">
                                                    <label for="shenasahmilli" class="form-label"> شماره ملی   :</label>
                                                    <input type="number"  min=0 class="form-control" id="shenasahmilli" placeholder=" شماره ملی " name="codeMilli">
                                                  </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="mb-3 mt-3">
                                                    <label for="economicCode" class="form-label"> کد اقتصادی :</label>
                                                    <input type="number" min=0 class="form-control" id="economicCode" placeholder="کد اقتصادی" name="codeEqtisadi">
                                                  </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="mb-3 mt-3">
                                                    <label for="roleNo" class="form-label"> کد نقش :</label>
                                                    <input type="number"  min=0 class="form-control" id="roleNo" placeholder="کد نقش"  name="codeNaqsh">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="mb-3 mt-3">
                                                    <label for="address" class="form-label"> آدرس :</label>
                                                    <input type="text" class="form-control" id="address" placeholder="آدرس " name="address">
                                                  </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="mb-3 mt-3">
                                                    <label for="postalCode"  class="form-label">کد پستی :</label>
                                                    <input type="number"  min=0 max=10 class="form-control" id="postalCode" placeholder="کد پستی  " name="codePosti">
                                                  </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="mb-3 mt-3">
                                                    <label for="email" class="form-label">ایمیل آدرس:</label>
                                                    <input type="email" class="form-control" id="email" placeholder="ایمیل آدرس " name="email">
                                                  </div>
                                            </div>
                                                <div class="mb-3 mt-3">
                                                    <input type="submit" class="btn btn-secondary" value="ذخیره ">
                                                </div>
                                           </div>
                                        </form>
                                    </div>
                                </div>
                            </div>


                        <div class="row c-checkout rounded-3 tab-pane" id="moRagiInfo" style="width:99%; margin:0 auto; padding:1% 0% 0% 0%">
                            <div class="row " style="width:98%; padding:0 1% 2% 0%">
                                <form action="">
                                    <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="mb-3 mt-3">
                                            <label for="company" class="form-label">نام شرکت :</label>
                                            <input type="company" class="form-control" id="company" placeholder="Company Name" name="companyName">
                                          </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="mb-3 mt-3">
                                            <label for="shenasahmilli" class="form-label"> شناسه ملی   :</label>
                                            <input type="number" class="form-control" id="shenasahmilli" placeholder="National ID" name="shenasahMilli">
                                          </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="mb-3 mt-3">
                                            <label for="economicCode" class="form-label"> کد اقتصادی :</label>
                                            <input type="number" class="form-control" id="economicCode" placeholder="Economic code" name="codeEqtisadi">
                                          </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="mb-3 mt-3">
                                            <label for="roleNo" class="form-label"> کد نقش :</label>
                                            <input type="number" class="form-control" id="roleNo" placeholder="Role code"  name="codeNash">
                                          </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="mb-3 mt-3">
                                            <label for="registerNo" class="form-label"> شماره ثبت :</label>
                                            <input type="number" class="form-control" id="registerNo" placeholder="Register No"  name="registerNo">
                                          </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="mb-3 mt-3">
                                            <label for="address" class="form-label"> آدرس :</label>
                                            <input type="text" class="form-control" id="address" placeholder="Address " name="address">
                                          </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="mb-3 mt-3">
                                            <label for="postalCode" class="form-label">کد پستی :</label>
                                            <input type="number" class="form-control" id="postalCode" placeholder="Postal Code " name="codePosti">
                                          </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="mb-3 mt-3">
                                            <label for="email" class="form-label">Email:</label>
                                            <input type="email" class="form-control" id="email" placeholder="Email Address" name="email">
                                          </div>
                                    </div>
                                   </div>
                                   <div class="mb-3 mt-3">
                                    <input type="submit" class="btn btn-secondary" value="ذخیره ">
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
             </div>
        </form>
       </div>
    </div>
@endsection

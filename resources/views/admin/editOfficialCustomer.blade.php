@extends('admin.layout')
@se

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
    .required .form-label:after {
        content:"*";
        color:red;
        padding-right: 0px;
        }
     input.haqiqi{
        background-color: #eeeeee !important;
        opacity: 1;
    }
</style>

<div class="container">
    <div class="row">
        <div class="profielpage">
            <div class="text-black brounded" align="right" style="border-bottom:1px solid red; top:0">
                <h4> اطلاعات خصوصی </h4>
           </div>
                <div class="c-checkout container" id="haqiqi" style="background-color:#c5c5c5; margin:0.2% 0; margin-bottom:0; padding:0.5% !important; border-radius:10px 10px 2px 2px;">
                    <div class="col-sm-6" style="margin: 0; padding:0;">
                        <ul class="header-list nav nav-tabs" data-tabs="tabs" style="margin: 0; padding:0;">
                            <li><a class="active" data-toggle="tab" style="color:black;"  href="#custAddress"> اشخاص حقیقی  </a></li>
                            <li><a data-toggle="tab" style="color:black;"  href="#moRagiInfo"> اشخاص حقوقی </a></li>
                        </ul>
                    </div>
                    <div class="c-checkout tab-content" style="background-color:#f5f5f5; margin:0;  padding:0.3%; border-radius:10px 10px 2px 2px;">
                            <div class="row c-checkout rounded-3 tab-pane active" id="custAddress" style="width:99%; margin:0 auto; padding:1% 0% 0% 0%">
                                <div class="col-sm-12">
                                    <div class="row " style="width:98%; padding:0 1% 2% 0%">
                                        <form action="{{url('/doAddCustomer') }}" method="POST">
                                            @csrf
                                            <input type="hidden" id="haqiqi" name="customerType" value="haqiqi">
                                            <input type="hidden" name="customerShopSn" value="{{Session::get('psn')}}">
                                            <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="mb-1 mt-2">
                                                    <label for="name" class="form-label">نام:</label>
                                                    <input type="text" class="form-control haqiqi" @if($exactCustomer) value="{{$exactCustomer->customerName}}" @endif  id="name" placeholder="نام  " name="customerName">
                                                  </div>
                                            </div>
                                            {{-- <input type="text" name="customerShopSn" value="{{Session::get('psn')}}"> --}}
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="mb-1 mt-2">
                                                    <label for="familyName" class="form-label"> نام خانوادگی  :</label>
                                                    <input type="text" class="form-control haqiqi" id="familyName" @if($exactCustomer) value="{{$exactCustomer->familyName}}" @endif placeholder=" نام خانوادگی  " name="familyName">
                                                  </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                  <div class="mb-1 mt-2">
                                                    <label for="shenasahmilli" class="form-label"> شماره ملی   :</label>
                                                    <input type="number"  min=0 class="form-control haqiqi" id="codeMilli"  @if($exactCustomer) value="{{$exactCustomer->codeMilli}}" @endif placeholder=" شماره ملی " name="codeMilli">
                                                  </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="mb-1 mt-2">
                                                    <label for="economicCode" class="form-label"> کد اقتصادی :</label>
                                                    <input type="number" min=0 class="form-control haqiqi" id="economicCode" @if($exactCustomer) value="{{$exactCustomer->codeEqtisadi}}" @endif placeholder="کد اقتصادی" name="codeEqtisadi">
                                                  </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="mb-1 mt-2">
                                                    <label for="roleNo" class="form-label"> کد نقش :</label>
                                                    <input type="number"  min=0 class="form-control haqiqi" id="roleNo" @if($exactCustomer) value="{{$exactCustomer->codeNaqsh}}" @endif placeholder="کد نقش"  name="codeNaqsh">
                                                  </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="mb-1 mt-2">
                                                    <label for="address" class="form-label"> آدرس :</label>
                                                    <input type="text" class="form-control haqiqi" id="address" @if($exactCustomer) value="{{$exactCustomer->address}}" @endif placeholder="آدرس " name="address">
                                                  </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="mb-1 mt-2">
                                                    <label for="postalCode"  class="form-label">کد پستی :</label>
                                                    <input type="number"  min=0 class="form-control haqiqi" id="postalCode" placeholder="کد پستی" @if($exactCustomer) value="{{$exactCustomer->codePosti}}" @endif  name="codePosti">
                                                  </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="mb-1 mt-2">
                                                    <label for="email" class="form-label">ایمیل آدرس:</label>
                                                    <input type="email" class="form-control haqiqi" id="email" placeholder="ایمیل آدرس" @if($exactCustomer) value="{{$exactCustomer->email}}" @endif name="email">
                                                  </div>
                                            </div>
                                                <div class="mb-1 mt-2">
                                                    <input type="submit" class="btn btn-secondary" value="ذخیره ">
                                                </div>
                                           </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        <div class="row c-checkout rounded-2 tab-pane" id="moRagiInfo" style="width:99%; margin:0 auto; padding:1% 0% 0% 0%">
                            <div class="row " style="padding:0 1% 1% 0%; background-color:#eeeeee;">
                                <form action="{{url('/doAddCustomer') }}" method="POST">
                                       @csrf
                                        <input type="hidden" id="hoqoqi" name="customerType" value="hoqoqi">
                                    <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="mb-1 mt-2 required">
                                            <label for="company" class="form-label">نام شرکت:</label>
                                            <input type="company" class="form-control" id="company" placeholder="نام شرکت" @if($exactCustomer) value="{{$exactCustomer->companyName}}" @endif name="companyName">
                                          </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="mb-1 mt-2 required">
                                            <label for="shenasahmilli" class="form-label"> شناسه ملی   :</label>
                                            <input type="number" class="form-control" id="shenasahmilli" placeholder="شناسه ملی " @if($exactCustomer) value="{{$exactCustomer->shenasahMilli}}" @endif name="shenasahMilli">
                                          </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="mb-1 mt-2 required">
                                            <label for="economicCode" class="form-label" data-toggle="tooltip" data-placement="bottom" title="کد نقش یک کد اختصاصی برای شرکت ها میباشد!"> کد اقتصادی :</label>

                                            <input type="number" class="form-control" id="economicCode" placeholder="کد اقتصادی" @if($exactCustomer) value="{{$exactCustomer->codeEqtisadi}}" @endif  name="codeEqtisadi">
                                          </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="mb-1 mt-2 required">
                                            <label for="roleNo" class="form-label" data-bs-toggle="tooltip" data-bs-placement="bottom" title="کد نقش یک کد اختصاصی است!"> کد نقش !:</label>
                                            <input type="number" class="form-control pe-none" id="roleNo" placeholder="کد نقش" @if($exactCustomer) value="{{$exactCustomer->codeNaqsh}}" @endif  name="codeNaqsh">
                                          </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="mb-1 mt-2 required">
                                            <label for="registerNo" class="form-label"> شماره ثبت :</label>
                                            <input type="number" class="form-control" id="registerNo" placeholder="شماره ثبت" @if($exactCustomer) value="{{$exactCustomer->registerNo}}" @endif name="registerNo">
                                          </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="mb-1 mt-2 required">
                                            <label for="address" class="form-label"> آدرس :</label>
                                            <input type="text" class="form-control" id="address" placeholder="آدرس" @if($exactCustomer) value="{{$exactCustomer->address}}" @endif name="address">
                                          </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="mb-1 mt-2 required">
                                            <label for="postalCode" class="form-label">کد پستی :</label>
                                            <input type="number" class="form-control" id="postalCode" placeholder="کد پستی" @if($exactCustomer) value="{{$exactCustomer->codePosti}}" @endif name="codePosti">
                                          </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="mb-1 mt-2 required">
                                            <label for="email" class="form-label">ایمیل آدرس :</label>
                                            <input type="email" class="form-control" id="email" placeholder="ایمیل آدرس" @if($exactCustomer) value="{{$exactCustomer->email}}" @endif name="email">
                                          </div>
                                    </div>
                                   </div>
                                   <div class="mb-1 mt-2">
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
    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
          return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
@endsection
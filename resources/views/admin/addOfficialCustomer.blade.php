@extends('admin.layout')
@section('content')
<style>
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
            <div class="text-black brounded" align = "right" style="border-bottom:2px solid #1e864b; top:0">
                 <h4> پروفایل </h4>
            </div>
                <div class="c-checkout container" id="haqiqi" style="background-color:#1e864b; margin:0.2% 0; margin-bottom:0; padding:0.5% !important; border-radius:10px 10px 2px 2px;">
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
                                        <form action="{{url('/doAddOfficialCustomer') }}" method="POST">
                                            @csrf
                                            <input type="hidden" id="haqiqi" name="customerType" value="haqiqi">
                                            <input type="hidden" name="customerShopSn" value="{{Session::get('psn')}}">
                                            <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="mb-1 mt-2">
                                                    <label for="name" class="form-label">نام:</label>
                                                    <input onKeyPress="if(this.value.length==10) return false;" type="text" class="form-control haqiqi" @if($exactCustomer) value="{{trim($exactCustomer->customerName)}}" @endif  id="name" placeholder="نام  " name="customerName">
                                                  </div>
                                            </div>
                                            {{-- <input type="text" name="customerShopSn" value="{{Session::get('psn')}}"> --}}
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="mb-1 mt-2">
                                                    <label for="familyName" class="form-label"> نام خانوادگی  :</label>
                                                    <input type="text"  onKeyPress="if(this.value.length==10) return false;" class="form-control haqiqi" id="familyName" @if($exactCustomer) value="{{trim($exactCustomer->familyName)}}" @endif placeholder=" نام خانوادگی  " name="familyName">
                                                  </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                  <div class="mb-1 mt-2">
                                                    <label for="shenasahmilli" class="form-label" data-toggle="tooltip" data-placement="bottom" title="معلومات !"> شماره ملی   :</label>
                                                    <input  onKeyPress="if(this.value.length==10) return false;" type="number"  min=0 class="form-control haqiqi" id="codeMilli"  @if($exactCustomer) value="{{trim($exactCustomer->codeMilli)}}" @endif placeholder=" شماره ملی " name="codeMilli">
                                                  </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="mb-1 mt-2">
                                                    <label for="codeNaqsh" class="form-label"  data-toggle="tooltip" data-placement="bottom" title="معلومات !"> کد اقتصادی :</label>
                                                    <input  onKeyPress="if(this.value.length==10) return false;" type="number" min=0 class="form-control haqiqi" id="economicCode" @if($exactCustomer) value="{{trim($exactCustomer->codeEqtisadi)}}" @endif placeholder="کد اقتصادی" name="codeEqtisadi">
                                                  </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="mb-1 mt-2">
                                                    <label for="roleNo" class="form-label"  data-toggle="tooltip" data-placement="bottom" title="معلومات !"> کد نقش :</label>
                                                    <input  onKeyPress="if(this.value.length==10) return false;" type="number"  min=0 class="form-control haqiqi" id="roleNo" @if($exactCustomer) value="{{trim($exactCustomer->codeNaqsh)}}" @endif placeholder="کد نقش"  name="codeNaqsh">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="mb-1 mt-2">
                                                    <label for="address" class="form-label"> آدرس :</label>
                                                    <input onKeyPress="if(this.value.length==26) return false;" type="text" class="form-control haqiqi" id="address" @if($exactCustomer) value="{{trim($exactCustomer->address)}}" @endif placeholder="آدرس " name="address">
                                                  </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="mb-1 mt-2">
                                                    <label for="postalCode"  class="form-label"  data-toggle="tooltip" data-placement="bottom" title="معلومات !">کد پستی :</label>
                                                    <input  onKeyPress="if(this.value.length==10) return false;" type="number"  min=0 class="form-control haqiqi" id="postalCode" placeholder="کد پستی" @if($exactCustomer) value="{{trim($exactCustomer->codePosti)}}" @endif  name="codePosti">
                                                  </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="mb-1 mt-2 required">
                                                    <label for="email" class="form-label">ایمیل آدرس :</label>
                                                    <input  onKeyPress="if(this.value.length==22) return false;" type="email" class="form-control haqiqi" id="email" placeholder="ایمیل آدرس" @if($exactCustomer) value="{{$exactCustomer->email}}" @endif name="email">
                                                  </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="mb-1 mt-2">
                                                    <label for="phoneNo" class="form-label"  data-toggle="tooltip" data-placement="bottom" title="معلومات !">شماره همراه :</label>
                                                    <input  onKeyPress="if(this.value.length==14) return false;" type="number"  min=0 class="form-control haqiqi" id="phoneNo" @if($exactCustomer) value="{{trim($exactCustomer->phoneNo)}}" @endif placeholder="شماره تلفن"  name="phoneNo">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="mb-1 mt-2">
                                                    <label for="sabetPhoneNo" class="form-label">شماره ثابت:</label>
                                                    <input onKeyPress="if(this.value.length==14) return false;" type="text" class="form-control haqiqi" id="sabetPhoneNo" @if($exactCustomer) value="{{trim($exactCustomer->sabetPhoneNo)}}" @endif placeholder="شماره ثابت" name="sabetPhoneNo">
                                                  </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="mb-1 mt-2">
                                                    <label for="shenasNamahNo" class="form-label">شماره شناس نامه :</label>
                                                    <input onKeyPress="if(this.value.length==10) return false;" type="text" class="form-control haqiqi" id="shenasNamahNo" @if($exactCustomer) value="{{trim($exactCustomer->shenasNamahNo)}}" @endif placeholder="شماره شناس نامه " name="shenasNamahNo">
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
                                <form action="{{url('/doAddOfficialCustomer') }}" method="POST">
                                       @csrf
                                        <input type="hidden" id="hoqoqi" name="customerType" value="hoqoqi">
                                    <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="mb-1 mt-2 required">
                                            <label for="company" class="form-label">نام شرکت:</label>
                                            <input  onKeyPress="if(this.value.length==10) return false;" type="company" class="form-control" id="company" placeholder="نام شرکت" @if($exactCustomer) value="{{trim($exactCustomer->companyName)}}" @endif name="companyName" required>
                                          </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="mb-1 mt-2 required">
                                            <label for="shenasahmilli" class="form-label"  data-toggle="tooltip" data-placement="bottom" title="معلومات !"> شناسه ملی   :</label>
                                            <input  onKeyPress="if(this.value.length==10) return false;" type="number" class="form-control" id="shenasahmilli" placeholder="شناسه ملی " @if($exactCustomer) value="{{$exactCustomer->shenasahMilli}}" @endif name="shenasahMilli" required>
                                          </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="mb-1 mt-2 required">
                                            <label for="economicCode" class="form-label" data-toggle="tooltip" data-placement="bottom" title="کد نقش یک کد اختصاصی برای شرکت ها میباشد!"> کد اقتصادی :</label>
                                            <input m onKeyPress="if(this.value.length==10) return false;" type="number" class="form-control" id="economicCode" placeholder="کد اقتصادی" @if($exactCustomer) value="{{$exactCustomer->codeEqtisadi}}" @endif  name="codeEqtisadi" required>
                                          </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="mb-1 mt-2 required">
                                            <label for="codeNaqsh" class="form-label" data-toggle="tooltip" data-placement="bottom" title="کد نقش یک کد اختصاصی برای شرکت ها میباشد!">کد نقش  :</label>
                                            <input  onKeyPress="if(this.value.length==10) return false;" type="number" class="form-control" id="codeNaqsh" placeholder="کد نقش" @if($exactCustomer) value="{{$exactCustomer->codeNaqsh}}" @endif  name="codeNaqsh" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="mb-1 mt-2 required">
                                            <label for="registerNo" class="form-label"  data-toggle="tooltip" data-placement="bottom" title=" !"> شماره ثبت<i class="fa fa-exclamation-circle" aria-hidden="true"></i></label>
                                            <input  onKeyPress="if(this.value.length==10) return false;" type="number" class="form-control" id="registerNo" placeholder="شماره ثبت" @if($exactCustomer) value="{{$exactCustomer->registerNo}}" @endif name="registerNo" required>
                                          </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="mb-1 mt-2 required">
                                            <label for="address" class="form-label"> آدرس :</label>
                                            <input onKeyPress="if(this.value.length==32) return false;"  type="text" class="form-control" id="address" placeholder="آدرس" @if($exactCustomer) value="{{$exactCustomer->address}}" @endif name="address" required>
                                          </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="mb-1 mt-2 required">
                                            <label for="postalCode" class="form-label">کد پستی :</label>
                                            <input  onKeyPress="if(this.value.length==10) return false;" type="number" class="form-control" id="postalCode" placeholder="کد پستی" @if($exactCustomer) value="{{$exactCustomer->codePosti}}" @endif name="codePosti" required>
                                          </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="mb-1 mt-2 required">
                                            <label for="email" class="form-label">ایمیل آدرس :</label>
                                            <input  onKeyPress="if(this.value.length==22) return false;" type="email" class="form-control" id="email" placeholder="ایمیل آدرس" @if($exactCustomer) value="{{$exactCustomer->email}}" @endif name="email" required>
                                          </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="mb-1 mt-2 required">
                                            <label for="phoneNo" class="form-label">شماره همراه :</label>
                                            <input  onKeyPress="if(this.value.length==14) return false;" type="number" class="form-control" id="phoneNo" placeholder="شماره تلفن" @if($exactCustomer) value="{{$exactCustomer->phoneNo}}" @endif name="phoneNo" required>
                                          </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="mb-1 mt-2 required">
                                            <label for="sabetPhoneNo" class="form-label">شماره ثابت:</label>
                                            <input  onKeyPress="if(this.value.length==14) return false;" type="sabetPhoneNo" class="form-control" id="sabetPhoneNo" placeholder="ایمیل آدرس" @if($exactCustomer) value="{{$exactCustomer->sabetPhoneNo}}" @endif name="sabetPhoneNo">
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
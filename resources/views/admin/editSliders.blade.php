@extends('admin.layout')
@section('content')
    <section class="main-cart container px-0">
        <div class="o-page__content" style=" width: 80%; margin:0 auto; margin-top: 65px; padding:0">
            <div class="o-headline" style="padding: 0; margin-bottom: 10px; margin-top: 0">
                <div id="main-cart p-1" class="p-1">
                    <span class="c-checkout__tab--active">{{ $title }}</span>
                </div>
            </div>
            <div class="c-checkout" style="padding-right:0; border-radius:10px 10px 2px 2px; padding:0">
                <div class="container" style="padding-top:12px;">
                    @foreach ($parts as $part)
                        <form class="p-0" action="{{ url('/doEditGroupPart') }}" method="POST" enctype="multipart/form-data" class='form'>
                            @csrf
                            @if($picture[0]->partType==4)
                                <label class="form-label">نمایش</label>
                                <input type="checkbox" name="activeOrNot" @if ($part->activeOrNot==1)checked @else @endif>
                            @endif
                            <div class="d-flex flex-row-reverse">
                                <button type="submit" @if(hasPermission(Session::get( 'adminId'),'homePage' ) < 1 ) disabled @endif class="btn btn-success btn-sm text-warning" style="foloat:left;">ذخیره <i class="fa fa-save fa-lg" aria-hidden="true"></i></button>
                            </div>
                            <div class="row d-none">
                                <div class='modal-body'>
                                    <div class='' style='padding-right:0;'>
                                        <div class='form-group d-none'>
                                            <label class='form-label'>اسم سطر</label>
                                            <input type='text' id='partTitle' value="{{ $part->partTitle }}"
                                                class='form-control' name='partTitle' placeholder='' />
                                            <input type='text' id='partId' style="display: none"
                                                value="{{ $part->partId }}" class='form-control' name='partId'
                                                placeholder='' />
                                        </div>
                                        <div class='form-group' style="display: none">
                                            <label class='form-label'>اولویت</label>
                                            <input type='text' id="priority" value="{{ $part->priority }}"
                                                class='form-control' name='partPriority' placeholder=''>
                                        </div>
                                        <div class='form-group d-none'>
                                            <label class='form-label'>نوعیت دسته بندی</label>
                                            <select name='partType' class='form-select'
                                                id='partType'>
                                                <option value="{{ $part->partType }}">{{ $part->name }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row p-0">
                                <div class="col-sm-12" style="padding: 1%">
                                    <div class="container">
                                        <div class="row p-0">
                                                <div class='modal-body' style="display:flex;  justify-content: flex-end; float:right; margin-left:50px">
                                                    <div id='pslider' class='swiper-container swiper-container-horizontal swiper-container-rtl'>
                                                        <div class='product-box swiper-wrapper' style="">
                                                            @foreach ($picture as $pic)
                                                            @if($pic->firstPic)
                                                            <div class='product-item swiper-slide' style='width:30% !important; height:25vh !important;'>
                                                                <input style="display:none" id="pic{{ $loop->iteration }}" value="{{$pic->firstPic}}"/>
                                                                @if($pic->partType==3)
                                                                <img id="imageSrc1" style="width:85% height:45%" src="{{url('/resources/assets/images/mainSlider/'.$pic->firstPic.'')}}">
                                                                @else
                                                                <img id="imageSrc1" style="width:85% height:45%" src="{{url('/resources/assets/images/smallSlider/'.$pic->firstPic.'')}}">
                                                                @endif
                                                                <br/>
                                                                <div>
                                                                    <button class="btn btn-success btn-sm text-warning"  @if(hasPermission(Session::get( 'adminId'),'homePage' ) >0 ) onclick="document.getElementById('firstPic').click();" @endif type="button" style="display: inline; margin-right: 2%;">ویرایش</button>
                                                                </div>
                                                                <input type="file"  onchange='document.getElementById("imageSrc1").src = window.URL.createObjectURL(this.files[0])'  name="slider1" id="firstPic" style="display: none"/>
                                                            </div>
                                                            @endif
                                                            {{-- 2 --}}
                                                            @if($pic->secondPic)
                                                            <div class='product-item swiper-slide' style='width:30% !important; height:25vh !important;'>
                                                                <input style="display:none" id="pic{{ $loop->iteration }}" value="{{$pic->secondPic}}"/>
                                                                @if($pic->partType==3)
                                                                <img id="imageSrc2" style="width:85% height:45%" src="{{url('/resources/assets/images/mainSlider/'.$pic->secondPic.'')}}">
                                                                @else
                                                                <img  id="imageSrc2" style="width:85% height:45%" src="{{url('/resources/assets/images/smallSlider/'.$pic->secondPic.'')}}">
                                                                @endif
                                                                <br/>
                                                                <div>
                                                                    <button class="btn btn-success btn-sm text-warning"   @if(hasPermission(Session::get( 'adminId'),'homePage' ) >0 )  onclick="document.getElementById('secondPic').click();" @endif type="button" style="display: inline;margin-right: 2%;">ویرایش</button>
                                                                </div>
                                                                <input type="file" onchange='document.getElementById("imageSrc2").src = window.URL.createObjectURL(this.files[0])' name="slider2" id="secondPic" style="display: none"/>
                                                            </div>
                                                            @endif
                                                            {{-- 3 --}}
                                                            @if($pic->thirdPic)
                                                            <div class='product-item swiper-slide' style='width:30% !important; height:25vh !important;'>
                                                                <input style="display:none" id="pic{{ $loop->iteration }}" value="{{$pic->thirdPic}}"/>
                                                                <img id="imageSrc3" style="width:85% height:45%" src="{{url('/resources/assets/images/mainSlider/'.$pic->thirdPic.'')}}">
                                                                <br/>
                                                                <div>
                                                                    <button class="btn btn-success btn-sm text-warning" @if(hasPermission(Session::get( 'adminId'),'homePage' ) >0 ) onclick="document.getElementById('thirdPic').click();" @endif type="button" style="display: inline;margin-right: 2%;">ویرایش</button>
                                                                </div>
                                                                <input type="file"  onchange='document.getElementById("imageSrc3").src = window.URL.createObjectURL(this.files[0])' name="slider3" id="thirdPic" style="display: none"/>
                                                            </div>
                                                            @endif
                                                            {{-- 4 --}}
                                                            @if($pic->fourthPic)
                                                            <div class='product-item swiper-slide' style='width:30% !important; height:25vh !important;'>
                                                                <input style="display:none" id="pic{{ $loop->iteration }}" value="{{$pic->fourthPic}}"/>
                                                                <img id="imageSrc4" style="width:85% height:45%" src="{{url('/resources/assets/images/mainSlider/'.$pic->fourthPic.'')}}">
                                                                <br/>
                                                                <div>
                                                                    <button class="btn btn-success btn-sm text-warning" @if(hasPermission(Session::get( 'adminId'),'homePage' ) >0 ) onclick="document.getElementById('fourthPic').click();" @endif type="button" style="display: inline;margin-right: 2%;">ویرایش</button>
                                                                </div>

                                                                <input onchange='document.getElementById("imageSrc4").src = window.URL.createObjectURL(this.files[0])' type="file" name="slider4" id="fourthPic" style="display: none"/>
                                                            </div>
                                                            @endif
                                                            {{-- 5 --}}
                                                            @if($pic->fifthPic)
                                                            <div class='product-item swiper-slide' style='width:30% !important; height:25vh !important;'>

                                                                <input style="display:none" id="pic{{ $loop->iteration }}" value="{{$pic->fifthPic}}"/>

                                                                <img  id="imageSrc5" style="width:85% height:80%" src="{{url('/resources/assets/images/mainSlider/'.$pic->fifthPic.'')}}">
                                                                <br/>
                                                                <div>
                                                                    <button class="btn btn-success btn-sm text-warning" @if(hasPermission(Session::get( 'adminId'),'homePage' ) >0 ) onclick="document.getElementById('fifthPic').click();" @endif type="button" style="display: inline;margin-right: 2%;">ویرایش</button>
                                                                </div>
                                                                <input onchange='document.getElementById("imageSrc5").src = window.URL.createObjectURL(this.files[0])' type="file" name="slider5" id="fifthPic" style="display: none"/>
                                                            </div>
                                                            @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    
     <script>    
        var mslider = {
        loop: !0,
        spaceBetween: 30,
        effect: 'fade',
        pagination: {
            el: '.mainslider-btn',
            clickable: !0,
        },
        autoplay: {
            delay: 5000,
            disableOnInteraction: !1,
        },
    }
    $('.swiper-button-next').hide()
    $('.swiper-button-prev').hide()


    var pslider = {
        slidesPerView: 6,
        spaceBetween: 0,
        autoplay: {
            delay: 7500,
            disableOnInteraction: !1,
        },
        navigation: {
            nextEl: '#pslider-nbtn',
            prevEl: '#pslider-pbtn',
        },
        breakpoints: {
            1024: {
                slidesPerView: 4,
                spaceBetween: 0,
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 0,
            },
            640: {
                slidesPerView: 2,
                spaceBetween: 0,
            },
            320: {
                slidesPerView: 1,
                spaceBetween: 0,
            }
        }
    }
 
    var swiper = new Swiper('#pslider', pslider);
    
</script>
@endsection
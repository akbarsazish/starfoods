@extends ('admin.layout')
@section('content')

<style>
    html, body{
        margin:0 auto;
        padding:0;
    }
</style>

<section class='container' style='padding:0; margin:auto;'>
    <div class="o-page__content" style="margin:65px auto auto; padding:0">
        <div class="o-headline" style="padding: 0.5%;">
            <div id="main-cart">
                <span class="c-checkout__tab--active"> تخصیص تصویر کالا </span>
            </div>
        </div>
            <div class="c-listing px-0">
                <ul class="c-listing__items px-0 mx-auto">
                    @foreach ($listKala as $item)
                    <li class="border-0 px-0">
                        <div class="col-50 medium-25 mb-1 px-0">
                            <div class="c-product-box py-1 c-promotion-box" style="min-height: 290px">
                                <a href="" class="c-product-box__img c-promotion-box__image">
                                    <img id="mainPicEdit{{$item->GoodSn}}" alt="mainPicEdit" src="{{ url('/resources/assets/images/kala/' . $item->GoodSn . '_1.jpg') }}">
                                </a>
                                <div class="c-product-box__content" style="width: 93%; border-bottom: 1px solid gray; margin-bottom:5px">
                                    <a href="#" class="title"
                                        style="display: block; min-height: 45px">{{ $item->GoodName }}</a>
                                </div>
                                <div class="c-product__add">
                                    <div class="c-product__add" style="display: block">
                                    <iframe name="votar" style="display:none;"></iframe>
                                    <form action="{{ url('/addpicture') }}" onsubmit='document.getElementById("uploadButton{{$item->GoodSn}}").style.backgroundColor="#198754";' target="votar" enctype="multipart/form-data" method="POST">
                                        @csrf
                                        <label @if(hasPermission(Session::get( 'adminId'),'fastKala' ) > 0) for="mainPic{{$item->GoodSn}}" @endif style="width: 220px;margin-bottom:5%;" class="btn btn-success editButtonHover">  ویرایش <i class="fa-light fa-image fa-lg"></i></label>

                                        <input type="file"   onchange='document.getElementById("mainPicEdit{{$item->GoodSn}}").src = window.URL.createObjectURL(this.files[0]); document.getElementById("uploadButton{{$item->GoodSn}}").style.backgroundColor="red";' style="display: none" class="form-control" name="firstPic" id="mainPic{{$item->GoodSn}}">
                                        <br/>
                                        <label  style="width: 110px;margin-bottom:5%;" class="btn btn-success editButtonHover">  ویرایش <i class="fa-light fa-image fa-lg"></i></label>

                                        <input type="file"   style="display: none" class="form-control"  >
                                        <label  style="width: 110px;margin-bottom:5%;" class="btn btn-success editButtonHover">  ویرایش <i class="fa-light fa-image fa-lg"></i></label>

                                        <input type="file"   style="display: none" class="form-control"  >
                                        <br/>
                                        <label  style="width: 110px;margin-bottom:5%;" class="btn btn-success editButtonHover">  ویرایش <i class="fa-light fa-image fa-lg"></i></label>

                                        <input type="file"   style="display: none" class="form-control"  >
                                        <label  style="width: 110px;margin-bottom:5%;" class="btn btn-success editButtonHover">  ویرایش <i class="fa-light fa-image fa-lg"></i></label>

                                        <input type="file"   style="display: none" class="form-control"  >
                                        <div class="d-flex justify-content-between"
                                        style="border-top: 1px solid gray;border-bottom: 1px solid gray;">
                                        </div>
                                        <input type="text" name="kalaId" value="{{$item->GoodSn}}" style="display: none">
                                        <button type="submit" id="uploadButton{{$item->GoodSn}}" style="margin: 2%;" class="btn btn-success btn-sm editButtonHover">اپلود</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </section>
 @endsection

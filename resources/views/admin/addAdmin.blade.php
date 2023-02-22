@extends('admin.layout')
@section('content')

<style>
    label{ font-size: 16px;}
</style>

<section class="main-cart container px-0">
    <div class="o-page__content" style="margin-top: 65px;">
            <div class="container">
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-2"></div>
                     <div class="col-lg-8 col-md-8 col-sm-8 mt-2 ">
                       <h2> <span style="border-bottom: 3px solid gray; font-size:18px; font-weight:bold;"> افزودن ادمین </span> </h2>
                       <span class="card p-3">
                        <form action="{{url('/doAddAdmin')}}" method="POST" class="form">
                            @csrf
                        <div class="form-group">
                                <label for="userName">اسم</label>
                                <input type="text" class="form-control" name="name">
                            </div>
                            <div class="form-group">
                                <label for="userName">فامیلی</label>
                                <input type="text" class="form-control" name="lastName">
                            </div>
                            <div class="form-group">
                                <label for="userName">نام کاربری</label>
                                <input type="text" class="form-control" name="userName">
                            </div>
                            <div class="form-group">
                                <label for="userName">رمز</label>
                                <input type="password" class="form-control" name="password">
                            </div>
                            <div class="form-group">
                                <button class="btn btn-success buttonHover" style="margin:10px" type="submit">ذخیره <i class="fa fa-save fa-lg" aria-hidden="true"> </i> </button>
                            </div>
                        </form>
                    </button>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2"></div>
            </div>
        </div>
        </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
</div>
</section>

@endsection

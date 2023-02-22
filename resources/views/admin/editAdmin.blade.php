@extends('admin.layout')
@section('content')

<style>
label{
    font-size: 16px;
}
</style>
<section class="main-cart container px-0">

    <div class="o-page__content" style="margin-top: 77px;">
            <div class="container">
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-2"></div>
                     <div class="col-lg-8 col-md-8 col-sm-8 mt-2 ">
                       <h2> <span style="border-bottom: 3px solid gray; font-size:18px; font-weight:bold;"> ویرایش ادمین </span> </h2>
                       <span class="card p-3">
                        <form action="{{url('/doEditAdmin')}}" method="POST" class="form">
                            @csrf
                         <div class="form-group">
                                <label for="userName" class="form-label">نام </label>
                                <input type="text" class="form-control" value="{{$admin->name}}" name="name">
                                <input type="text" class="form-control" style="display: none" value="{{$admin->id}}" name="adminId">
                            </div>
                            <div class="form-group">
                                <label for="userName" class="form-label">فامیلی</label>
                                <input type="text" class="form-control" value="{{$admin->lastName}}" name="lastName">
                            </div>
                            <div class="form-group">
                                <label for="userName" class="form-label">نام کاربری</label>
                                <input type="text" class="form-control" value="{{$admin->userName}}" name="userName">
                            </div>
                            <div class="form-group">
                                <label for="userName" class="form-label">رمز</label>
                                <input type="text" class="form-control" value="{{$admin->password}}" name="password">
                            </div>
                            <br/>
                            <div class="form-group">
                                <label for="userName" class="form-label">فعال</label>
                                <input type="checkbox" class="" @if ($admin->activeState==1) checked @else @endif name="activeState">
                            </div>
                            <div class="form-group">
                                <button class="btn btn-success buttonHover" type="submit"> ذخیره <i class="fa fa-save fa-lg" aria-hidden="true"></i> </button>
                            </div>
                            <br/>
                        </form>
                       </span>
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

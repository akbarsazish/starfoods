@extends('admin.layout')
@section('content')
<main>
    <!-- Main page content-->
    <div class="container-xl px-4 mt-n10" style="margin-top:4%;">
        <div class="row">
            <div class="col-lg-2 col-md-2 col-sm-2"></div>
            <div class="col-lg-8 col-md-8 col-sm-8 mt-2 card">
                <h2> <span style="border-bottom: 3px solid gray; font-size:18px; font-weight:bold;"> تعیین سطح دسترسی </span> </h2>
                            <table class="table table-bordered mt-4" style="width:100%;">
                                <thead class="tableHeader">
                                <tr>
                                    <th>#</th>
                                    <th>شرح کار</th>
                                    <th>فعال</th>
                                </tr>
                                </thead>
                                <tbody class="tableHeader">
                                    <tr>
                                        <td>452</td>
                                        <td>قاشق</td>
                                        <td><input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" checked> </td>
                                    </tr>
                                </tbody>
                            </table>
                        <div class="alert">
                            <a href="{{url('/editKala')}}" class="btn btn-success btn-md buttonHover"> ذخیره <i class="fa fa-save fa-lg" aria-hidden="true"></i></a>
                        </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2"></div>
        </div>
</main>
@endsection

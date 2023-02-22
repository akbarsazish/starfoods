@extends('layout.layout')
@section('content')
<section class="cart-empty container" style="direction:rtl;text-align:right;margin-top:4%">
<div class="o-headline" style="padding: 0; margin-bottom: 10px; margin-top: 0">
            <div id="main-cart">
                <span class="c-checkout-text c-checkout__tab--active">آلارم ها</span>
            </div>
        </div>
<div class="jumbotron">
 <a href="/addalarm">
      <button class="btn btn-primary btn-sm" href="#" style="" role="button">افزودن آلارم جدید</button>
</a>
  <hr class="my-4" color="red" style="color:red;">
  <table class="table table-striped homeTables" style="width:100%;">
  <thead style="position:sticky; top:0">
    <tr>
      <th scope="col">#</th>
      <th scope="col">عنوان</th>
      <th scope="col">متن</th>

    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row">1</th>
      <td>تبریکی عید 1401</td>
      <td>مقدم بهار مبارک </td>
    </tr>

  </tbody>
</table>
</div>
</div>
</div>

@endsection
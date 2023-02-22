@extends('layout.layout')
@section('content')
<section class="cart-empty container" style="direction:rtl;text-align:right;margin-top:4%">
<form action="{{url('/doAddAlarm')}}"  method="post"  onSubmit="if(!confirm('از درست بودن متن مطمیین هستید?')){return false;}">
            <textarea placeholder="متن نظر خود را بنویسید" name="messageContent" style="width: 100%; height: 80px"></textarea>
            <button type="submit" class="btn-light btn-light--gray btn-light--verify" id="btnsave">ثبت نظر</button>
        @csrf
        </form>
<a href="{{url('/alarms')}}"><button type="submit" style="margin-top:2%;" class="btn-light btn-light--gray btn-light--verify" id="btnsave">انصراف </button></a>
</section>
@endsection
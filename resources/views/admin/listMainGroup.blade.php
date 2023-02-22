@foreach ($groups as $group)
  <li><a class="mySidenav__item" href="{{url('/listMainGroup')}}"><i class="fa-light fa-briefcase fa-lg" style="margin-right: 5%"></i>&nbsp;&nbsp; {{$group->title}}</a>

</li>
@endforeach

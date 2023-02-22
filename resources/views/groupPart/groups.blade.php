@extends ('layout.layout')
@section('content')
<section class="search px-0 container" style="padding:0; margin: 0 auto;">
    <div class="o-page__content" style="padding:0; margin: 70px auto 5px auto; ">
        <div class="c-listing" style="padding:0;">
            <ul class="c-listing__items">
                @foreach ($groups as $group)
                <li class="border-0">
                    <div class="c-product-box">
                        <a href="/listKala/groupId/{{$group->id}}"  class="c-product-box__img p-0" style="height:100%">
                            @if(file_exists('resources/assets/images/mainGroups/' . $group->id . '.jpg'))
                                <img alt="" src="{{url('/resources/assets/images/mainGroups/' . $group->id . '.jpg') }}"/>
                            @else
                              <img alt="" src="{{ url('/resources/assets/images/defaultKalaPics/altKala.png') }}"/>
                            @endif
                        </a>
                        <div class="c-product-box__content pt-1" style="position:sticky;">
                            <a style="line-height: 1rem" href="/listKala/groupId/{{$group->id}}" class="title">{{trim($group->title)}}</a>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
   </div>
 
</section>
@endsection

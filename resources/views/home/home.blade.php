@extends('layout.layout')
@section('content')
<link rel="stylesheet" href="{{ url('/resources/assets/vendor/swiper/css/swiper.min.css') }}">
    <article class="container" id="mainSlider" style="padding:0!important;">
        <div class="row m-0" style="overflow: hidden;">
            <div class="@if($smallSlider[0]->activeOrNot==1)col-lg-8 @else col-lg-12 @endif px-0 mt-1" style="margin-top:50px; z-index:999px;">
                <div id="mainslider" class="swiper-container">
                    <div class="swiper-wrapper">
                        @foreach ($slider as $slide)
                            <div class="swiper-slide">
                                <img src="{{url('/resources/assets/images/mainSlider/'.$slide->homepartId.'_1.jpg')}}" />
                            </div>
                            <div class="swiper-slide">
                                <img src="{{url('/resources/assets/images/mainSlider/'.$slide->homepartId.'_2.jpg')}}" />
                            </div>
                            <div class="swiper-slide">
                                <img src="{{url('/resources/assets/images/mainSlider/'.$slide->homepartId.'_3.jpg')}}" />
                            </div>
                            <div class="swiper-slide">
                                <img src="{{url('/resources/assets/images/mainSlider/'.$slide->homepartId.'_4.jpg')}}" />
                            </div>
                            <div class="swiper-slide">
                                <img src="{{url('/resources/assets/images/mainSlider/'.$slide->homepartId.'_5.jpg')}}" />
                            </div>
                        @endforeach
                    </div>
                    <div id="mslider-nbtn" class="swiper-button-next"></div>
                    <div id="mslider-pbtn" class="swiper-button-prev"></div>
                    <div class="swiper-pagination mainslider-btn"></div>
                </div>
            </div>
            @if($smallSlider[0]->activeOrNot==1)
            <div class="col-lg-4 px-0 mt-1">
                <div class="row banners">
                    @foreach ($smallSlider as $slide)
                        <div class="swiper__item swiper-slide col-12">
                            <img src="{{url('/resources/assets/images/smallSlider/'.$slide->homepartId.'_1.jpg')}}" style="height: 30%;width: 100%;"/>
                        </div>
                        <div class="swiper__item swiper-slide col-12">
                            <img src="{{url('/resources/assets/images/smallSlider/'.$slide->homepartId.'_2.jpg')}}" style="height: 50%;width: 100%;" />
                        </div>
                    @endforeach

                </div>
            </div>
            @endif
        </div>
    </article>
            <div class="clear"></div>
            @if($homePageType==1)

                <section class='product-wrapper container lastBanner secondHomeMobile'>
                    <div class='headline'><h3>دسته بندی</h3></div>
                    <div id='pslider' class='swiper-container swiper-container-horizontal swiper-container-rtl'>
                        <div class='product-box swiper-wrapper' style='transform: translate3d(1088px, 0px, 0px); transition-duration: 0ms;'>
                        @foreach ($productGroups as $group)
                            <div class='swiper-slide' centeredSlidesBounds>
                                <div class='emptySlider'>
                                    <a class="align-middle" href='/listKala/groupId/{{$group->id}}' >
                                        <img class="mt-0" style="max-height: 100px; width:110px; margin-bottom:-20px;" src="{{url('resources/assets/images/mainGroups/'.$group->id.'.jpg')}}" alt='' /></a >
                                    <a class='title text-dark fw-bold' style="height: 15%;" href="/listKala/groupId/{{$group->id}}">{{trim($group->title)}}</a>
                                </div>
                            </div>
                        @endforeach
                    </section>
             {!!$partViews!!}
            @else

            <section class="search container-fluid second-home secondHomeMobile">
                <div class="o-page__content">
                    <div class="c-listing" style="background-color:#f4f5f7;">
                        <ul class="c-listing__items text-center" style="background-color:#f4f5f7;">
                            @foreach ($productGroups as $group)

                            <li class="border-0 secondhome">
                                <div class="align-self-center" style="position: relative; width:94%; background-color:#fff; border-radius:10px;">
                                    <a href="/listKala/groupId/{{$group->id}}">
                                        @if(file_exists('resources/assets/images/mainGroups/' . $group->id . '.jpg'))
                                        
                                        <img class="d-flex justify-content-center text-center" style="width:88% !important; margin: auto;" alt="" src="{{url('/resources/assets/images/mainGroups/' . $group->id . '.jpg') }}"/>
                                        @else
                                        <img class="d-flex justify-content-center text-center" style="width:88% !important; margin: auto;" alt="default" src="{{ url('/resources/assets/images/defaultKalaPics/altKala.png') }}"/>
                                        @endif
                                    </a>
                                </div>
                                <div class="c-product-box__content">
                                    <a style="line-height: 1rem; font-size:15px; padding-top:10px;" href="/listKala/groupId/{{$group->id}}" class="title  d-flex align-items-center justify-content-center">{{trim($group->title)}}</a>
                                </div>
                            </li>
                            @endforeach
                        
                        </ul>
                    </div>
                </div>
            </section>
            {!!$partViews!!}
            @endif
						
			   
	@if($showEnamad==1)		   
    <div class="container">
       <div class="row mb-4"> 
           <div class="col-lg-4 col-md-4 col-sm-4"> </div>
            <div class="col-lg-4 col-md-4 col-sm-4 about-img">
            <a referrerpolicy="origin" href="https://trustseal.enamad.ir/?id=220841&amp;code=dgsiolxgvdofskzzy34r">
                <img referrerpolicy="origin" src="https://Trustseal.eNamad.ir/logo.aspx?id=220841&amp;Code=dGSIolXgVdoFskzzY34R"
                     alt="" style="cursor:pointer" id="dGSIolXgVdoFskzzY34R">
            </a>
            <img referrerpolicy='origin' id='nbqewlaosizpjzpefukzrgvj'
                 style='cursor:pointer' onclick='window.open("https://logo.samandehi.ir/Verify.aspx?id=249763&p=uiwkaodspfvljyoegvkaxlao",
        "Popup", "toolbar=no, scrollbars=no, location=no, statusbar=no, menubar=no, resizable=0, width=450, height=630, top=30")'
                 alt='logo-samandehi' src='https://logo.samandehi.ir/logo.aspx?id=249763&p=odrfshwlbsiyyndtwlbqqfti' />
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4"> </div>
        </div> 
    </div>
    @endif			
            
            <section class="container" style="margin-bottom: 50px;">
                <div class="row">
                    <div class="col-lg-8 col-md-8 col-sm-8 mt-3 about">
                        <a href="/aboutUs">درباره استارفود</a> &nbsp; 
                        <a href="/privacy">حریم خصوصی</a> &nbsp; 
                        <a href="/contactUs">اطلاعات فروشگاه</a> &nbsp; 
                        <a href="/policy">شرایط و قوانین</a> 
                     </div>
                </div>
            </section>
    <script src="{{ url('resources/assets/js/jquery.min.js')}}"></script>
    <script src="{{ url('/resources/assets/js/script.js')}}"></script>
    <script src="{{ url('/resources/assets/js/swiper/js/swiper.min.js') }}"></script>
    <script>    var mslider = {
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



    var incslider = {
        slidesPerView: 5,
        spaceBetween: 10,
        autoplay: {
            delay: 2000,
            disableOnInteraction: !1,
        },
        breakpoints: {
            1024: {
                slidesPerView: 5,
                spaceBetween: 10,
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 10,
            },
            640: {
                slidesPerView: 2,
                spaceBetween: 10,
            },
            400: {
                slidesPerView: 1.5,
                spaceBetween: 10,
            }
        }
    }
    var spslider = {
        slidesPerView: 4,
        spaceBetween: 0,
        autoplay: {
            delay: 2000,
            disableOnInteraction: !1,
        },
        breakpoints: {
            1024: {
                slidesPerView: 4,
                spaceBetween: 10,
            },
            768: {
                slidesPerView: 5,
                spaceBetween: 10,
            },
            640: {
                slidesPerView: 5,
                spaceBetween: 10,
            },
            400: {
                slidesPerView: 4,
                spaceBetween: 10,
            }
        }
    }

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
    var vpslider = {
        slidesPerView: 5,
        spaceBetween: 10,
        autoplay: {
            delay: 2500,
            disableOnInteraction: !1,
        },
        navigation: {
            nextEl: '#vpslider-nbtn',
            prevEl: '#vpslider-pbtn',
        },
        breakpoints: {
            1024: {
                slidesPerView: 4,
                spaceBetween: 10,
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 10,
            },
            640: {
                slidesPerView: 2,
                spaceBetween: 10,
            },
            320: {
                slidesPerView: 1,
                spaceBetween: 10,
            }
        }
    }
    var mvpslider = {
        slidesPerView: 5,
        spaceBetween: 10,
        autoplay: {
            delay: 2500,
            disableOnInteraction: !1,
        },
        navigation: {
            nextEl: '#mvpslider-nbtn',
            prevEl: '#mvpslider-pbtn',
        },
        breakpoints: {
            1024: {
                slidesPerView: 4,
                spaceBetween: 10,
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 10,
            },
            640: {
                slidesPerView: 2,
                spaceBetween: 10,
            },
            320: {
                slidesPerView: 1,
                spaceBetween: 10,
            }
        }
    }
    var newpslider = {
        slidesPerView: 5,
        spaceBetween: 1,
        autoplay: {
            delay: 6500,
            disableOnInteraction: !1,
        },
        navigation: {
            nextEl: '#newpslider-nbtn',
            prevEl: '#newpslider-pbtn',
        },
        breakpoints: {
            1024: {
                slidesPerView: 5,
                spaceBetween: 1,
            },
            768: {
                slidesPerView: 4,
                spaceBetween: 1,
            },
            640: {
                slidesPerView: 3,
                spaceBetween: 1,
            },
            320: {
                slidesPerView: 2,
                spaceBetween: 1,
            }
        }
    }

    var mostpslider = {
        slidesPerView: 5,
        spaceBetween: 10,
        autoplay: {
            delay: 6500,
            disableOnInteraction: !1,
        },
        navigation: {
            nextEl: '#mostpslider-nbtn',
            prevEl: '#mostpslider-pbtn',
        },
        breakpoints: {
            1024: {
                slidesPerView: 4,
                spaceBetween: 10,
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 10,
            },
            640: {
                slidesPerView: 2,
                spaceBetween: 10,
            },
            320: {
                slidesPerView: 1,
                spaceBetween: 10,
            }
        }
    }
    var brandslider1 = {
        slidesPerView: 5,
        spaceBetween: 0,
        slidesOffsetAfter: 0,
        autoplay: {

            delay: 2000,
            disableOnInteraction: !1,
        },
        navigation: {
            nextEl: '#brandslider-nbtn',
            prevEl: '#brandslider-pbtn',
        },
        breakpoints: {
            1024: {
                slidesPerView: 5,
                spaceBetween: 10,
                slidesOffsetAfter: 0,
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 10,
                slidesOffsetAfter: 0,
            },
            640: {
                slidesPerView: 2,
                spaceBetween: 10,
                slidesOffsetAfter: 0,
            },
            320: {
                slidesPerView: 1,
                spaceBetween: 10,
                slidesOffsetAfter: 0,
            }
        }
    }

    var brandslider2 = {
        slidesPerView: 5,
        spaceBetween: 0,
        slidesOffsetAfter: 0,
        autoplay: {
            delay: 7500,
            disableOnInteraction: !1,
        },
        navigation: {
            nextEl: '#brandslider-nbtn',
            prevEl: '#brandslider-pbtn',
        },
        breakpoints: {
            1024: {
                slidesPerView: 4,
                spaceBetween: 0,
                slidesOffsetAfter: 0,
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 0,
                slidesOffsetAfter: 0,
            },
            640: {
                slidesPerView: 2,
                spaceBetween: 0,
                slidesOffsetAfter: 0,
            },
            320: {
                slidesPerView: 1,
                spaceBetween: 0,
                slidesOffsetAfter: 0,
            }
        }
    }
    var brandslider3 = {
        slidesPerView: 5,
        spaceBetween: 10,
        autoplay: {
            delay: 2500,
            disableOnInteraction: !1,
        },
        navigation: {
            nextEl: '#brandslider-nbtn',
            prevEl: '#brandslider-pbtn',
        },
        breakpoints: {
            1024: {
                slidesPerView: 4,
                spaceBetween: 10,
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 10,
            },
            640: {
                slidesPerView: 2,
                spaceBetween: 10,
            },
            320: {
                slidesPerView: 1,
                spaceBetween: 10,
            }
        }
    }
    var brandslider4 = {
        slidesPerView: 5,
        spaceBetween: 10,
        autoplay: {
            delay: 2500,
            disableOnInteraction: !1,
        },
        navigation: {
            nextEl: '#brandslider-nbtn',
            prevEl: '#brandslider-pbtn',
        },
        breakpoints: {
            1024: {
                slidesPerView: 4,
                spaceBetween: 10,
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 10,
            },
            640: {
                slidesPerView: 2,
                spaceBetween: 10,
            },
            320: {
                slidesPerView: 1,
                spaceBetween: 10,
            }
        }
    }
    var brandslider5 = {
        slidesPerView: 5,
        spaceBetween: 10,
        autoplay: {
            delay: 2500,
            disableOnInteraction: !1,
        },
        navigation: {
            nextEl: '#brandslider-nbtn',
            prevEl: '#brandslider-pbtn',
        },
        breakpoints: {
            1024: {
                slidesPerView: 4,
                spaceBetween: 10,
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 10,
            },
            640: {
                slidesPerView: 2,
                spaceBetween: 10,
            },
            320: {
                slidesPerView: 1,
                spaceBetween: 10,
            }
        }
    }
    var brandslider6 = {
        slidesPerView: 5,
        spaceBetween: 10,
        autoplay: {
            delay: 2500,
            disableOnInteraction: !1,
        },
        navigation: {
            nextEl: '#brandslider-nbtn',
            prevEl: '#brandslider-pbtn',
        },
        breakpoints: {
            1024: {
                slidesPerView: 4,
                spaceBetween: 10,
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 10,
            },
            640: {
                slidesPerView: 2,
                spaceBetween: 10,
            },
            320: {
                slidesPerView: 1,
                spaceBetween: 10,
            }
        }
    }

    var newsSlider = {
        slidesPerView: 4,
        spaceBetween: 10,
        autoplay: {
            delay: 6500,
            disableOnInteraction: !1,
        },
        breakpoints: {
            1024: {
                slidesPerView: 4,
                spaceBetween: 10,
            },
            768: {
                slidesPerView: 5,
                spaceBetween: 10,
            },
            640: {
                slidesPerView: 5,
                spaceBetween: 10,
            },
            400: {
                slidesPerView: 4,
                spaceBetween: 10,
            }
        }
    }
    //========================================================================================================
    var newsSlider1 = {
        slidesPerView: 4,
        spaceBetween: 10,
        autoplay: {
            delay: 6500,
            disableOnInteraction: !1,
        },
        breakpoints: {
            1024: {
                slidesPerView: 5,
                spaceBetween: 10,
            },
            768: {
                slidesPerView: 4,
                spaceBetween: 10,
            },
            640: {
                slidesPerView: 3,
                spaceBetween: 10,
            },
            450: {
                slidesPerView: 2,
                spaceBetween: 10,
            }
        }
    }
    var newsSlider2 = {
        slidesPerView: 4,
        spaceBetween: 10,
        autoplay: {
            delay: 10000,
            disableOnInteraction: !1,
        },
        breakpoints: {
            1024: {
                slidesPerView: 4,
                spaceBetween: 10,
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 10,
            },
            640: {
                slidesPerView: 3,
                spaceBetween: 10,
            },
            450: {
                slidesPerView: 2,
                spaceBetween: 10,
            }
        }

    }
    var newsSlider3 = {
        slidesPerView: 5,
        spaceBetween: 10,
        autoplay: {
            delay: 10000,
            disableOnInteraction: !1,
        },
        breakpoints: {
            1024: {
                slidesPerView: 5,
                spaceBetween: 10,
            },
            768: {
                slidesPerView: 5,
                spaceBetween: 10,
            },
            640: {
                slidesPerView: 5,
                spaceBetween: 10,
            },
            450: {
                slidesPerView: 2,
                spaceBetween: 10,
            }
        }
    }
    var newsSlider4 = {
        slidesPerView: 5,
        spaceBetween: 2,
        autoplay: {
            delay: 10000,
            disableOnInteraction: !1,
        },
        breakpoints: {
            1024: {
                slidesPerView: 5,
                spaceBetween: 2,
            },
            768: {
                slidesPerView: 4,
                spaceBetween: 2,
            },
            640: {
                slidesPerView: 3,
                spaceBetween: 2,
            },
            450: {
                slidesPerView: 2,
                spaceBetween: 1,
            }
        }
    }
    var newsSlider5 = {
        slidesPerView: 5,
        spaceBetween: 0,
        autoplay: {
            delay: 10000,
            disableOnInteraction: !1,
        },
        breakpoints: {
            1024: {
                slidesPerView: 5,
                spaceBetween: 0,
            },
            768: {
                slidesPerView: 4,
                spaceBetween: 0,
            },
            640: {
                slidesPerView: 3,
                spaceBetween: 0,
            },
            450: {
                slidesPerView: 2,
                spaceBetween: 0,
            }
        }
    }
    var newsSlider6 = {
        slidesPerView: 5,
        spaceBetween: 0,
        autoplay: {
            delay: 10000,
            disableOnInteraction: !1,
        },
        breakpoints: {
            1024: {
                slidesPerView: 5,
                spaceBetween: 0,
            },
            768: {
                slidesPerView: 4,
                spaceBetween: 0,
            },
            640: {
                slidesPerView: 3,
                spaceBetween: 0,
            },
            450: {
                slidesPerView: 2,
                spaceBetween: 0,
            }
        }
    }
    var newsSlider7 = {
        slidesPerView: 5,
        spaceBetween: 10,
        autoplay: {
            delay: 10000,
            disableOnInteraction: !1,
        },
        breakpoints: {
            1024: {
                slidesPerView: 5,
                spaceBetween: 10,
            },
            768: {
                slidesPerView: 5,
                spaceBetween: 10,
            },
            640: {
                slidesPerView: 5,
                spaceBetween: 10,
            },
            450: {
                slidesPerView: 2,
                spaceBetween: 10,
            }
        }
    }
    var newsSlider8 = {
        slidesPerView: 5,

        spaceBetween: 0,
        autoplay: {
            delay: 10000,
            disableOnInteraction: !1,
        },
        breakpoints: {
            1024: {
                slidesPerView: 5,
                spaceBetween: 0,
            },
            768: {
                slidesPerView: 4,
                spaceBetween: 0,
            },
            640: {
                slidesPerView: 3,
                spaceBetween: 0,
            },
            450: {
                slidesPerView: 2,
                spaceBetween: 0,
            }
        }
    }
    var newsSlider9 = {
        slidesPerView: 5,
        centeredSlides: true,
        spaceBetween: 10,
        autoplay: {
            delay: 10000,
            disableOnInteraction: !1,
        },
        breakpoints: {
            1024: {
                slidesPerView: 5,
                spaceBetween: 1,
            },
            768: {
                slidesPerView: 4,
                spaceBetween: 1,
            },
            640: {
                slidesPerView: 3,
                spaceBetween: 1,
            },
            400: {
                slidesPerView: 2,
                spaceBetween: 1,
            }
        }
    }
    var newsSlider10 = {
        slidesPerView: 5,
        centeredSlides: true,
        spaceBetween: 10,
        autoplay: {
            delay: 10000,
            disableOnInteraction: !1,
        },
        breakpoints: {
            1024: {
                slidesPerView: 5,

                spaceBetween: 1,
            },
            768: {
                slidesPerView: 4,
                spaceBetween: 1,
            },
            640: {
                slidesPerView: 3,
                spaceBetween: 1,
            },
            400: {
                slidesPerView: 2,
                spaceBetween: 1,
            }
        }
    }


    var newsSlider11 = {
        slidesPerView: 5,
        centeredSlides: true,
        spaceBetween: 10,
        autoplay: {
            delay: 6500,
            disableOnInteraction: !1,
        },
        breakpoints: {
            1024: {
                slidesPerView: 5,
                spaceBetween: 1,
            },
            768: {
                slidesPerView: 4,
                spaceBetween: 1,
            },
            640: {
                slidesPerView: 3,
                spaceBetween: 1,
            },
            400: {
                slidesPerView: 2,
                spaceBetween: 1,
            }
        }
    }
    var incSlider1 = {
        slidesPerView: 5,
        spaceBetween: 1,
        slidesPerView: 'auto',

        autoplay: {
            delay: 4000,
            disableOnInteraction: !1,
        },
        breakpoints: {
            1024: {
                slidesPerView: 5,
                spaceBetween: 1,
                centeredSlidesBounds: true,


            },
            768: {
                slidesPerView: 4,
                spaceBetween: 1,
                centeredSlidesBounds: true,

            },
            640: {
                slidesPerView: 3,
                spaceBetween: 1,
                centeredSlidesBounds: true,

            },
            400: {
                slidesPerView: 2,
                spaceBetween: 1,
                centeredSlidesBounds: true,

            }
        }
    }

//========================================================================================================
    var swiper = new Swiper('#inc-slider1', incSlider1);
    var swiper = new Swiper('#inc-slider2', incSlider1);
    var swiper = new Swiper('#inc-slider3', incSlider1);
    var swiper = new Swiper('#inc-slider4', incSlider1);
    var swiper = new Swiper('#mainslider', mslider);
    var swiper = new Swiper('#inc-slider', incslider);
    var swiper = new Swiper('#sp-slider', spslider);
    var swiper = new Swiper('#pslider', pslider);
    var swiper = new Swiper('#vpslider', vpslider);
    var swiper = new Swiper('#newpslider', newpslider);
    var swiper = new Swiper('#mostpslider', mostpslider);
    var swiper = new Swiper('#brandslider1', brandslider1);
    var swiper = new Swiper('#brandslider2', brandslider2);
    var swiper = new Swiper('#brandslider3', brandslider3);
    var swiper = new Swiper('#brandslider4', brandslider4);
    var swiper = new Swiper('#brandslider5', brandslider5);
    var swiper = new Swiper('#brandslider6', brandslider6);
    var swiper = new Swiper('#mvpslider', mvpslider);
    var swiper = new Swiper('#newpslider1', newsSlider);
    var swiper = new Swiper('#newpslider1', newsSlider1);
    var swiper = new Swiper('#newpslider2', newsSlider2);
    var swiper = new Swiper('#newpslider3', newsSlider3);
    var swiper = new Swiper('#newpslider4', newsSlider4);
    var swiper = new Swiper('#newpslider5', newsSlider5);
    var swiper = new Swiper('#newpslider6', newsSlider6);
    var swiper = new Swiper('#newpslider7', newsSlider7);
    var swiper = new Swiper('#newpslider8', newsSlider8);
    var swiper = new Swiper('#newpslider9', newsSlider9);
    var swiper = new Swiper('#newpslider10', newsSlider10);
    var swiper = new Swiper('#newpslider11', newsSlider11);

</script>

@endsection

$(document).ready(function () {
    $(window).load(function () {
        $('.c-gallery__items img').click(function () {
            var src = $(this).attr('src');
            $('.c-gallery__img img').attr('src', src);
        });
        $("#modalBody").scrollTop($("#modalBody").prop("scrollHeight"));
    });

    $('.c-box-tabs__tab').click(function (e) {
        e.preventDefault();
        $('.c-box-tabs__tab').removeClass('is-active');
        $(this).addClass('is-active');
        var id = $(this).children('a').attr('id');
        $(".c-box--tabs > div").removeClass('is-active');
        $(".c-box--tabs > div#" + id).addClass('is-active')
    });
    // Zoom Image
    $('.c-gallery__items > li > img').click(function () {
        var img = $(this).attr('src');
        $('.zoomWindow').css('background-image', 'url(' + img + ')');
    })
    $('.c-mask__handler').click(function (e) {
        e.preventDefault();
        if (!$(this).hasClass('is-active')) {
            $('.c-mask__text').attr('style', '');
            $('.c-mask__handler').addClass('without-after');
            $('.c-mask__handler').css('position', 'static');
            $('.c-mask__handler').css('display', 'block');
            $('.c-mask__handler').html('بستن');
            $(this).addClass('is-active')
        } else {
            $(this).removeClass('is-active');
            $('.c-mask__text').attr('style', 'max-height: 250px;height: unset;');
            $('.c-mask__handler').removeClass('without-after');
            $('.c-mask__handler').css('position', 'absolute');
            $('.c-mask__handler').html('ادامه مطلب')
        }
    });
    var topcart = $('.top-head .cart .count');
    $('.remodal-close').click(function () {
        $('body').removeClass('main-cart-overlay');
        $('.modal-avatar__content').fadeOut(200)
    });
    $('#avatar-modal').click(function () {
        $('body').addClass('main-cart-overlay');
        $('.modal-avatar__content').fadeIn(200)
    });
    $('.close-modal').click(function () {
        $('.body').removeClass('main-cart-overlay');
        $('.modal-checkout').fadeOut(200)
    });
    $('#addnewaddr').click(function () {
        $('.body').addClass('main-cart-overlay');
        $('.modal-checkout').fadeIn(200)
    });
    $('#circle_input').change(function () {
        if ($(this).is(':checked')) {
            $('#circle').animate({
                right: '-7px'
            }, 300, function () {
                $('.scroll').animate({
                    'background-color': 'rgb(46, 149, 9) !important',
                    opacity: '0.8'
                })
            })
        } else {
            $('#circle').animate({
                right: '20px'
            }, 300, function () {
                $('.scroll').animate({
                    'background-color': 'rgb(255,255,255) !important',
                    opacity: '0.8'
                })
            })
        }
    });

    $('.jump-to-up').click(function () {
        $('html').animate({
            scrollTop: 0
        }, 500)
    });
    $("#logreg").click(function () {
        $(".top-head .user-modal").fadeToggle()
    });



    $('#sfl-cart').click(function () {
        $('#cart-sfl').show();
        $('.c-checkout,.o-page__aside').hide();
        $('#main-cart').children('span').removeClass('c-checkout__tab--active');
        $('#main-cart').children('.c-checkout__tab-counter').css({ backgroundColor: "#bbb" });
        $(this).children('span').addClass('c-checkout__tab--active');
    });
    $('#main-cart').click(function () {
        $('#cart-sfl').hide();
        $('.c-checkout,.o-page__aside').show();
        $('#main-cart .c-checkout-text').addClass('c-checkout__tab--active');
        $('#main-cart').children('.c-checkout__tab-counter').css({ backgroundColor: "#ef394e" });
        $('#sfl-cart .c-checkout-text').removeClass('c-checkout__tab--active');

    });

    // suppliers
    $('.c-table-suppliers-more').click(function () {
        $(".c-table-suppliers__body .c-table-suppliers__row").each(function () {
            if (!$(this).hasClass('in-list')) $(this).addClass('in-list')
        });
        $(this).addClass('c-table-suppliers-hidden');
        $('.c-table-suppliers-less').removeClass('c-table-suppliers-hidden');
    });
    $('.c-table-suppliers-less').click(function () {
        var counter = 0;
        $(".c-table-suppliers__body .c-table-suppliers__row").each(function () {
            counter++;
            if (counter <= 2) return;
            if ($(this).hasClass('in-list')) $(this).removeClass('in-list');
        });
        $(this).addClass('c-table-suppliers-hidden');
        $('.c-table-suppliers-more').removeClass('c-table-suppliers-hidden');
    });

} // document-ready
)
/// Backdrop menu ==============================
const backdrop = document.querySelector('.menuBackdrop');
backdrop.addEventListener('click', () => {
    backdrop.classList.remove('show');
    document.querySelector('#mySidenav').style.width = '0px';
});
document.querySelector('.fa-bars').parentElement.addEventListener('click', () => {
    backdrop.classList.add('show');
});

/// Loading ==========================================
// window.addEventListener('DOMContentLoaded', () => document.querySelector('.loading').classList.remove('show'));
///JAVAD JAVASCRIPT CODES

var baseUrl = "http://192.168.10.27:8080";
var myVar;
function loadFunction() {
    myVar = setTimeout(showPage, 1000);
}

$('#kalaNameId').on('keyup', function () {
    const input = $(this).val();
    $.ajax({
        method: 'get',
        url: baseUrl + "/searchKalaByName",
        async: true,
        data: {
            _token: "{{ csrf_token() }}",
            name: input
        },
        success: function (arrayed_result) {
            $('#kalaContainer').empty();
            for (var i = 0; i <= arrayed_result.length - 1; i++) {
                $('#kalaContainer').append(`
            <tr onClick="kalaProperties(this)" id='kalaContainer' class="select-highlightKala">
            <td></td>
            <td>` + arrayed_result[i].GoodCde + `</td>
            <td>` + arrayed_result[i].GoodName + `</td>
            <td>` + arrayed_result[i].NameGRP + `</td>
            <td>1401.2.21</td>
            <td>1401.2.21</td>
            <td><input class="kala form-check-input" name="kalaId[]" disabled type="checkbox" value="{{$kala->GoodSn}}" id=""></td>
            <td>` + parseInt(arrayed_result[i].Price4 / 10).toLocaleString("en-US") + `</td>
            <td>` + parseInt(arrayed_result[i].Price3 / 10).toLocaleString("en-US") + `</td>
            <td>` + parseInt(arrayed_result[i].Amount / 1).toLocaleString("en-US") + `</td>
            <td>
            <input class="kala form-check-input" name="kalaId[]" type="radio" value="` + arrayed_result[i].GoodSn + `_` + arrayed_result[i].Price4 + `_` + arrayed_result[i].Price3 + `" id="flexCheckCheckedKala">
            
            </td>
            </tr>`);
            }
        },
        error: function (data) { }
    });
});

function openNav() {

    document.getElementById("mySidenav").style.width = "250px";

}

$("#openDashboard").on("click", () => {
    let csn = ($("#customerSn").val());
    $("#psn").val(csn);
    $("#customerProperty").val("");
    $.ajax({
        method: 'get',
        url: baseUrl + "/customerDashboard",
        dataType: 'json',
        contentType: 'json',
        data: {
            _token: "{{ csrf_token() }}",
            csn: csn
        },
        async: true,
        success: function (msg) {
            moment.locale('en');
            let exactCustomer = msg[0];
            let factors = msg[1];
            let goodDetails = msg[2];
            let basketOrders = msg[3];
            let returnedFactors = msg[4];
            let loginInfo = msg[5];
            $("#dashboardTitle").text(exactCustomer.Name);
            $("#customerCode").val(exactCustomer.PCode);
            $("#customerName").val(exactCustomer.Name);
            $("#customerAddress").val(exactCustomer.peopeladdress);
            $("#mobile1").val(exactCustomer.hamrah.split("\n")[0]);
            $("#tell").val(exactCustomer.sabit.split("\n")[0]);
            $("#mobile2").val(exactCustomer.hamrah.split("\n")[1]);
            $("#customerIdForComment").val(exactCustomer.PSN);
            $("#countFactor").val(exactCustomer.countFactor);
            $("#factorTable").empty();
            factors.forEach((element, index) => {
                $("#factorTable").append(`<tr class="tbodyTr">
                    <td>` + (index + 1) + `</td>
                    <td>` + element.FactDate + `</td>
                    <td>نامعلوم</td>
                    <td>` + parseInt(element.TotalPriceHDS / 10).toLocaleString("en-us") + `</td>
                    <td onclick="showFactorDetails(this)"><input name="factorId" style="display:none"  type="radio" value="` + element.SerialNoHDS + `" /><i class="fa fa-eye" /></td>
                </tr>`);
            });

            $("#returnedFactorsBody").empty();
            returnedFactors.forEach((element, index) => {
                $("#returnedFactorsBody").append(`<tr class="tbodyTr">
                <td>` + (index + 1) + `</td>
                <td>` + element.FactDate + `</td>
                <td>نامعلوم</td>
                <td>` + parseInt(element.TotalPriceHDS / 10).toLocaleString("en-us") + `</td>
                <td onclick="showFactorDetails(this)"><input name="factorId" style="display:none"  type="radio" value="` + element.SerialNoHDS + `" /><i class="fa fa-eye" /></td>
                </tr>`);
            });
            $('#goodDetail').empty();
            goodDetails.forEach((element, index) => {
                $('#goodDetail').append(`
                <tr class="tbodyTr">
                    <td>` + (index + 1) + ` </td>
                    <td>` + moment(element.maxTime, 'YYYY/M/D HH:mm:ss').locale('fa').format('YYYY/M/D') + `</td>
                    <td>` + element.GoodName + `</td>
                    <td>  </td>
                    <td>  </td>
                </tr>`);
            });

            $("#basketOrders").empty();
            basketOrders.forEach((element, index) => {
                $("#basketOrders").append(`<tr>
                    <td>` + (index + 1) + `</td>
                    <td>` + moment(element.TimeStamp, 'YYYY/M/D HH:mm:ss').locale('fa').format('YYYY/M/D') + `</td>
                    <td>` + element.GoodName + `</td>
                    <td>` + element.Amount + `</td>
                    <td>` + element.Fi + `</td>
                    </tr>`);
            });
            $("#customerLoginInfoBody").empty();
            if (loginInfo) {
                loginInfo.forEach((element, index) => {
                    $("#customerLoginInfoBody").append(`<tr>
                        <td>` + (index + 1) + `</td>
                        <td>` + moment(element.visitDate, 'YYYY/M/D HH:mm:ss').locale('fa').format('YYYY/M/D') + `</td>
                        <td>` + element.platform + `</td>
                        <td>` + element.browser + `</td>
 						 <td>  </td>
                        </tr>`);
                });
            }

            if (!($('.modal.in').length)) {
                $('.modal-dialog').css({
                    left: 50,
                    top: 0
                });
            }
            $('#customerDashboard').modal({
                backdrop: false,
                show: true
            });

            $('.modal-dialog').draggable({
                handle: ".modal-header"
            });

            $("#customerDashboard").modal("show");
        },
        error: function (data) { }
    });
});



function showFactorDetails(element) {
    $(element).find('input:radio').prop('checked', true);
    let input = $(element).find('input:radio');
    $('tr').removeClass('selected');
    $(element).parent("tr").toggleClass('selected');
    $.ajax({
        method: 'get',
        url: baseUrl + "/getFactorDetail",
        data: {
            _token: "{{ csrf_token() }}",
            FactorSn: input.val()
        },
        async: true,
        success: function (arrayed_result) {
            let factor = arrayed_result[0];
            $("#factorDate").text(factor.FactDate);
            $("#customerNameFactor").text(factor.Name);
            $("#customerComenter").text(factor.Name);
            $("#customerAddressFactor").text(factor.peopeladdress);
            $("#customerPhoneFactor").text(factor.hamrah);
            $("#factorSnFactor").text(factor.FactNo);
            $("#productList").empty();
            arrayed_result.forEach((element, index) => {
                $("#productList").append(`<tr>
                <td>` + (index + 1) + `</td>
                <td>` + element.GoodName + ` </td>
                <td>` + element.Amount / 1 + `</td>
                <td>` + element.UName + `</td>
                <td>` + (element.Fi / 10).toLocaleString("en-us") + `</td>
                <td>` + ((element.Fi / 10) * (element.Amount / 1)).toLocaleString("en-us") + `</td>
<td> </td>
                </tr>`);
            });

            $("#factorDate1").text(factor.FactDate);
            $("#customerNameFactor1").text(factor.Name);
            $("#customerComenter1").text(factor.Name);
            $("#customerAddressFactor1").text(factor.peopeladdress);
            $("#customerPhoneFactor1").text(factor.hamrah);
            $("#factorSnFactor1").text(factor.FactNo);
            $("#productList1").empty();
            arrayed_result.forEach((element, index) => {
                $("#productList1").append(`<tr>
                <td>` + (index + 1) + `</td>
                <td>` + element.GoodName + ` </td>
                <td>` + element.Amount / 1 + `</td>
                <td>` + element.UName + `</td>
                <td>` + (element.Fi / 10).toLocaleString("en-us") + `</td>
                <td>` + ((element.Fi / 10) * (element.Amount / 1)).toLocaleString("en-us") + `</td>
                </tr>`);
            });

            if (!($('.modal.in').length)) {
                $('.modal-dialog').css({
                    left: 50,
                    top: 0
                });
            }
            $('#viewFactorDetail').modal({
                backdrop: false,
                show: true
            });

            $('.modal-dialog').draggable({
                handle: ".modal-header"
            });
            $("#viewFactorDetail").modal("show");
        },
        error: function (data) { }
    });

}


//used for creating acces level and user 

$("#baseInfoN").on("change", () => {
    if ($("#baseInfoN").is(":checked")) {
        $("#settingsN").prop("checked", true);
        $(".allSettingsN").prop("checked", true);
        $("#seeMainPageSettingN").prop("checked", true);
        $("#seeSpecialSettingN").prop("checked", true);
        $("#seeEmtyazSettingN").prop("checked", true);

    } else {
        $("#settingsN").prop("checked", false);
        $(".allSettingsN").prop("checked", false);
        $("#seeMainPageSettingN").prop("checked", false);
        $("#seeSpecialSettingN").prop("checked", false);
        $("#seeEmtyazSettingN").prop("checked", false);
        $("#deletMainPageSettingN").prop("checked", false);
        $("#editManiPageSettingN").prop("checked", false);
        $("#deleteSpecialSettingN").prop("checked", false);
        $("#editSpecialSettingN").prop("checked", false);
        $("#editEmptyazSettingN").prop("checked", false);
        $("#deleteEmtyazSettingN").prop("checked", false);
    }
});


$("#settingsN").on("change", () => {
    if ($("#settingsN").is(":checked")) {
        $("#settingsN").prop("checked", true);
        $(".allSettingsN").prop("checked", true);
        $("#seeMainPageSettingN").prop("checked", true);
        $("#seeSpecialSettingN").prop("checked", true);
        $("#seeEmtyazSettingN").prop("checked", true);
        $("#baseInfoN").prop("checked", true);

    } else {
        $("#settingsN").prop("checked", false);
        $(".allSettingsN").prop("checked", false);
        $("#seeMainPageSettingN").prop("checked", false);
        $("#seeSpecialSettingN").prop("checked", false);
        $("#seeEmtyazSettingN").prop("checked", false);
        $("#deletMainPageSettingN").prop("checked", false);
        $("#editManiPageSettingN").prop("checked", false);
        $("#editEmptyazSettingN").prop("checked", false);
        $("#deleteEmtyazSettingN").prop("checked", false);
        $("#deleteSpecialSettingN").prop("checked", false);
        $("#editSpecialSettingN").prop("checked", false);
        $("#specialSettingN").prop("checked", false);
        $("#baseInfoN").prop("checked", false);
    }
});



$("#mainPageSetting").on("change", () => {
    if ($("#mainPageSetting").is(":checked")) {
        $("#seeMainPageSettingN").prop("checked", true);
        $("#settingsN").prop("checked", true);
        $("#baseInfoN").prop("checked", true);
    } else {
        $("#mainPageSetting").prop("checked", false);
        if (!$(".allSettingsN").is(":checked")) {
            $("#seeMainPageSettingN").prop("checked", false);
            $("#editManiPageSettingN").prop("checked", false);
            $("#deletMainPageSettingN").prop("checked", false);
            $("#baseInfoN").prop("checked", false);
            $("#settingsN").prop("checked", false);
        } else {
            $("#seeMainPageSettingN").prop("checked", false);
            $("#editManiPageSettingN").prop("checked", false);
            $("#deletMainPageSettingN").prop("checked", false);
        }

    }

})

$("#seeMainPageSettingN").on("change", () => {
    if ($("#seeMainPageSettingN").is(":checked")) {
        $("#mainPageSetting").prop("checked", true);
        $("#settingsN").prop("checked", true);
        $("#baseInfoN").prop("checked", true);
    } else {
        $("#mainPageSetting").prop("checked", false);

        if (!$(".allSettingsN").is(":checked")) {
            $("#settingsN").prop("checked", false);
            $("#baseInfoN").prop("checked", false);
        } else {
            $("#settingsN").prop("checked", true);
            $("#baseInfoN").prop("checked", true);
        }

        $("#deletMainPageSettingN").prop("checked", false);
        $("#editManiPageSettingN").prop("checked", false);
    }
})

$("#editManiPageSettingN").on("change", () => {
    if ($("#editManiPageSettingN").is(":checked")) {
        $("#seeMainPageSettingN").prop("checked", true);
        $("#mainPageSetting").prop("checked", true);
        $("#settingsN").prop("checked", true);
        $("#baseInfoN").prop("checked", true);

    } else {
        $("#editManiPageSettingN").prop("checked", false);
        $("#deletMainPageSettingN").prop("checked", false);
    }
})


$("#deletMainPageSettingN").on("change", () => {
    if ($("#deletMainPageSettingN").is(":checked")) {
        $("#seeMainPageSettingN").prop("checked", true);
        $("#editManiPageSettingN").prop("checked", true);
        $("#mainPageSetting").prop("checked", true);
        $("#settingsN").prop("checked", true);
        $("#baseInfoN").prop("checked", true);
    } else {
        $("#deletMainPageSettingN").prop("checked", false);
    }
})





$("#specialSettingN").on("change", () => {
    if ($("#specialSettingN").is(":checked")) {
        $("#seeSpecialSettingN").prop("checked", true);
        $("#settingsN").prop("checked", true);
        $("#baseInfoN").prop("checked", true);
    } else {
        $("#specialSettingN").prop("checked", false);
        if (!$(".allSettingsN").is(":checked")) {
            $("#settingsN").prop("checked", false);
            $("#baseInfoN").prop("checked", false);
            $("#seeSpecialSettingN").prop("checked", false);
            $("#editSpecialSettingN").prop("checked", false);
            $("#deleteSpecialSettingN").prop("checked", false);
        } else {
            $("#seeSpecialSettingN").prop("checked", false);
            $("#editSpecialSettingN").prop("checked", false);
            $("#deleteSpecialSettingN").prop("checked", false);
        }
    }

})

$("#deleteSpecialSettingN").on("change", () => {
    if ($("#deleteSpecialSettingN").is(":checked")) {
        $("#editSpecialSettingN").prop("checked", true);
        $("#seeSpecialSettingN").prop("checked", true);
        $("#specialSettingN").prop("checked", true);
        $("#settingsN").prop("checked", true);
        $("#baseInfoN").prop("checked", true);
    } else {
        $("#deleteSpecialSettingN").prop("checked", false);
    }
})

$("#editSpecialSettingN").on("change", () => {
    if ($("#editSpecialSettingN").is(":checked")) {
        $("#editSpecialSettingN").prop("checked", true);
        $("#seeSpecialSettingN").prop("checked", true);
        $("#specialSettingN").prop("checked", true);
        $("#settingsN").prop("checked", true);
        $("#baseInfoN").prop("checked", true);
    } else {
        $("#editSpecialSettingN").prop("checked", false);
        $("#deleteSpecialSettingN").prop("checked", false);
    }
})

$("#seeSpecialSettingN").on("change", () => {
    if ($("#seeSpecialSettingN").is(":checked")) {
        $("#seeSpecialSettingN").prop("checked", true);
        $("#specialSettingN").prop("checked", true);
        $("#settingsN").prop("checked", true);
        $("#baseInfoN").prop("checked", true);
    } else {
        $("#specialSettingN").prop("checked", false);
        if (!$(".allSettingsN").is(":checked")) {
            $("#settingsN").prop("checked", false);
            $("#baseInfoN").prop("checked", false);
        } else {
            $("#settingsN").prop("checked", true);
            $("#baseInfoN").prop("checked", true);
        }
        $("#deleteSpecialSettingN").prop("checked", false);
        $("#editSpecialSettingN").prop("checked", false);

    }
})



$("#emptyazSettingN").on("change", () => {
    if ($("#emptyazSettingN").is(":checked")) {
        $("#settingsN").prop("checked", true);
        $("#baseInfoN").prop("checked", true);
        $("#seeEmtyazSettingN").prop("checked", true);

    } else {
        if (!$(".allSettingsN").is(":checked")) {
            $("#emptyazSettingN").prop("checked", false);
            $("#seeEmtyazSettingN").prop("checked", false);
            $("#deleteEmtyazSettingN").prop("checked", false);
            $("#editEmptyazSettingN").prop("checked", false);
            $("#settingsN").prop("checked", false);
            $("#baseInfoN").prop("checked", false);
        } else {
            $("#seeEmtyazSettingN").prop("checked", false);
            $("#deleteEmtyazSettingN").prop("checked", false);
            $("#editEmptyazSettingN").prop("checked", false);
        }

    }
})

$("#deleteEmtyazSettingN").on("change", () => {
    if ($("#deleteEmtyazSettingN").is(":checked")) {
        $("#settingsN").prop("checked", true);
        $("#baseInfoN").prop("checked", true);
        $("#emptyazSettingN").prop("checked", true);
        $("#editEmptyazSettingN").prop("checked", true);
        $("#seeEmtyazSettingN").prop("checked", true);

    } else {
        $("#deleteEmtyazSettingN").prop("checked", false);
    }
})

$("#editEmptyazSettingN").on("change", () => {
    if ($("#editEmptyazSettingN").is(":checked")) {
        $("#settingsN").prop("checked", true);
        $("#baseInfoN").prop("checked", true);
        $("#emptyazSettingN").prop("checked", true);
        $("#editEmptyazSettingN").prop("checked", true);
        $("#seeEmtyazSettingN").prop("checked", true);

    } else {
        $("#editEmptyazSettingN").prop("checked", false);
        $("#deleteEmtyazSettingN").prop("checked", false);
    }
})

$("#seeEmtyazSettingN").on("change", () => {
    if ($("#seeEmtyazSettingN").is(":checked")) {
        $("#baseInfoN").prop("checked", true);
        $("#settingsN").prop("checked", true);
        $("#emptyazSettingN").prop("checked", true);
        $("#seeEmtyazSettingN").prop("checked", true);

    } else {
        $("#emptyazSettingN").prop("checked", false);
        if (!$(".allSettingsN").is(":checked")) {
            $("#settingsN").prop("checked", false);
            $("#baseInfoN").prop("checked", false);
        } else {
            $("#settingsN").prop("checked", true);
            $("#baseInfoN").prop("checked", true);
        }

        $("#editEmptyazSettingN").prop("checked", false);
        $("#deleteEmtyazSettingN").prop("checked", false);

    }
})



// تعریف عناصر 

$("#defineElementN").on("change", () => {
    if ($("#defineElementN").is(":checked")) {
        $("#karbaranN").prop("checked", true);
        $("#customersN").prop("checked", true);
        $("#seeCustomersN").prop("checked", true);
    } else {
        $("#karbaranN").prop("checked", false);
        $("#customersN").prop("checked", false);
        $("#seeCustomersN").prop("checked", false);
        $("#deleteCustomersN").prop("checked", false);
        $("#editCustomerN").prop("checked", false);
    }
})


$("#karbaranN").on("change", () => {
    if ($("#karbaranN").is(":checked")) {
        $("#defineElementN").prop("checked", true);
        $("#customersN").prop("checked", true);
        $("#seeCustomersN").prop("checked", true);
    } else {
        $("#defineElementN").prop("checked", false);
        $("#customersN").prop("checked", false);
        $("#seeCustomersN").prop("checked", false);
        $("#deleteCustomersN").prop("checked", false);
        $("#editCustomerN").prop("checked", false);
    }
})

$("#customersN").on("change", () => {
    if ($("#customersN").is(":checked")) {
        $("#defineElementN").prop("checked", true);
        $("#customersN").prop("checked", true);
        $("#seeCustomersN").prop("checked", true);
        $("#karbaranN").prop("checked", true);
    } else {
        $("#defineElementN").prop("checked", false);
        $("#customersN").prop("checked", false);
        $("#seeCustomersN").prop("checked", false);
        $("#deleteCustomersN").prop("checked", false);
        $("#editCustomerN").prop("checked", false);
        $("#karbaranN").prop("checked", false);
    }
})

$("#seeCustomersN").on("change", () => {
    if ($("#seeCustomersN").is(":checked")) {
        $("#defineElementN").prop("checked", true);
        $("#customersN").prop("checked", true);
        $("#seeCustomersN").prop("checked", true);
        $("#karbaranN").prop("checked", true);
    } else {
        $("#defineElementN").prop("checked", false);
        $("#customersN").prop("checked", false);
        $("#seeCustomersN").prop("checked", false);
        $("#deleteCustomersN").prop("checked", false);
        $("#editCustomerN").prop("checked", false);
        $("#karbaranN").prop("checked", false);
    }
})

$("#seeCustomersN").on("change", () => {
    if ($("#seeCustomersN").is(":checked")) {
        $("#defineElementN").prop("checked", true);
        $("#customersN").prop("checked", true);
        $("#seeCustomersN").prop("checked", true);
        $("#karbaranN").prop("checked", true);

    } else {
        $("#defineElementN").prop("checked", false);
        $("#customersN").prop("checked", false);
        $("#seeCustomersN").prop("checked", false);
        $("#deleteCustomersN").prop("checked", false);
        $("#editCustomerN").prop("checked", false);
        $("#karbaranN").prop("checked", false);

    }

})

$("#editCustomerN").on("change", () => {
    if ($("#editCustomerN").is(":checked")) {
        $("#defineElementN").prop("checked", true);
        $("#customersN").prop("checked", true);
        $("#seeCustomersN").prop("checked", true);
        $("#karbaranN").prop("checked", true);
    } else {
        $("#deleteCustomersN").prop("checked", false);
    }
})

$("#deleteCustomersN").on("change", () => {
    if ($("#deleteCustomersN").is(":checked")) {
        $("#defineElementN").prop("checked", true);
        $("#customersN").prop("checked", true);
        $("#seeCustomersN").prop("checked", true);
        $("#karbaranN").prop("checked", true);
        $("#editCustomerN").prop("checked", true);
    } else {
        $("#deleteCustomersN").prop("checked", false);
    }

})



// عملیات 

$("#operationN").on("change", () => {
    if ($("#operationN").is(":checked")) {
        $("#kalasN").prop("checked", true);
        $("#kalaListsN").prop("checked", true);
        $("#seeKalaListN").prop("checked", true);
        $("#requestedKalaN").prop("checked", true);
        $("#seeRequestedKalaN").prop("checked", true);
        $("#fastKalaN").prop("checked", true);
        $("#seeFastKalaN").prop("checked", true);
        $("#pishKharidN").prop("checked", true);
        $("#seePishKharidN").prop("checked", true);
        $("#brandsN").prop("checked", true);
        $("#seeBrandsN").prop("checked", true);
        $("#alertedN").prop("checked", true);
        $("#seeAlertedN").prop("checked", true);
        $("#kalaGroupN").prop("checked", true);
        $("#seeKalaGroup").prop("checked", true);
        $("#orderSalesN").prop("checked", true);
        $("#seeSalesOrderN").prop("checked", true);
        $("#messageN").prop("checked", true);
        $("#seeMessageN").prop("checked", true);
        $("#seeKalaGroupN").prop("checked", true);

    } else {
        $("#kalasN").prop("checked", false);
        $("#kalaListsN").prop("checked", false);
        $("#seeKalaListN").prop("checked", false);
        $("#requestedKalaN").prop("checked", false);
        $("#seeRequestedKalaN").prop("checked", false);
        $("#fastKalaN").prop("checked", false);
        $("#seeFastKalaN").prop("checked", false);
        $("#pishKharidN").prop("checked", false);
        $("#seePishKharidN").prop("checked", false);
        $("#brandsN").prop("checked", false);
        $("#seeBrandsN").prop("checked", false);
        $("#alertedN").prop("checked", false);
        $("#seeAlertedN").prop("checked", false);
        $("#kalaGroupN").prop("checked", false);
        $("#seeKalaGroup").prop("checked", false);
        $("#orderSalesN").prop("checked", false);
        $("#seeSalesOrderN").prop("checked", false);
        $("#messageN").prop("checked", false);
        $("#seeMessageN").prop("checked", false);
        $("#seeKalaListN").prop("checked", false);
        $("#editKalaListN").prop("checked", false);
        $("#deleteKalaListN").prop("checked", false);
        $("#editMessageN").prop("checked", false);
        $("#deleteMessageN").prop("checked", false);
        $("#editRequestedKalaN").prop("checked", false);
        $("#deleteRequestedKalaN").prop("checked", false);
        $("#editFastKalaN").prop("checked", false);
        $("#deleteFastKalaN").prop("checked", false);
        $("#editFastKalaN").prop("checked", false);
        $("#deleteFastKalaN").prop("checked", false);
        $("#editPishkharidN").prop("checked", false);
        $("#deletePishKharidN").prop("checked", false);
        $("#editBrandN").prop("checked", false);
        $("#deleteBrandsN").prop("checked", false);
        $("#editAlertedN").prop("checked", false);
        $("#deleteAlertedN").prop("checked", false);
        $("#editKalaGroupN").prop("checked", false);
        $("#deletKalaGroupN").prop("checked", false);
        $("#seeKalaGroupN").prop("checked", false);
        $("#editOrderSalesN").prop("checked", false);
        $("#deleteOrderSalesN").prop("checked", false);
    }
})


$("#kalasN").on("change", () => {
    if ($("#kalasN").is(":checked")) {
        $("#operationN").prop("checked", true);
        $("#kalaListsN").prop("checked", true);
        $("#seeKalaListN").prop("checked", true);
        $("#requestedKalaN").prop("checked", true);
        $("#seeRequestedKalaN").prop("checked", true);
        $("#fastKalaN").prop("checked", true);
        $("#seeFastKalaN").prop("checked", true);
        $("#pishKharidN").prop("checked", true);
        $("#seePishKharidN").prop("checked", true);
        $("#brandsN").prop("checked", true);
        $("#seeBrandsN").prop("checked", true);
        $("#alertedN").prop("checked", true);
        $("#seeAlertedN").prop("checked", true);
        $("#kalaGroupN").prop("checked", true);
        $("#seeKalaGroup").prop("checked", true);
        $("#orderSalesN").prop("checked", true);
        $("#seeSalesOrderN").prop("checked", true);
        $("#messageN").prop("checked", true);
        $("#seeMessageN").prop("checked", true);

    } else {
        $("#operationN").prop("checked", false);
        $("#kalasN").prop("checked", false);
        $("#kalaListsN").prop("checked", false);
        $("#seeKalaListN").prop("checked", false);
        $("#requestedKalaN").prop("checked", false);
        $("#seeRequestedKalaN").prop("checked", false);
        $("#fastKalaN").prop("checked", false);
        $("#seeFastKalaN").prop("checked", false);
        $("#pishKharidN").prop("checked", false);
        $("#seePishKharidN").prop("checked", false);
        $("#brandsN").prop("checked", false);
        $("#seeBrandsN").prop("checked", false);
        $("#alertedN").prop("checked", false);
        $("#seeAlertedN").prop("checked", false);
        $("#kalaGroupN").prop("checked", false);
        $("#seeKalaGroup").prop("checked", false);
        $("#orderSalesN").prop("checked", false);
        $("#seeSalesOrderN").prop("checked", false);
        $("#messageN").prop("checked", false);
        $("#seeMessageN").prop("checked", false);
        $("#seeKalaListN").prop("checked", false);
        $("#editKalaListN").prop("checked", false);
        $("#deleteKalaListN").prop("checked", false);
        $("#editMessageN").prop("checked", false);
        $("#deleteMessageN").prop("checked", false);
        $("#editRequestedKalaN").prop("checked", false);
        $("#deleteRequestedKalaN").prop("checked", false);
        $("#editFastKalaN").prop("checked", false);
        $("#deleteFastKalaN").prop("checked", false);
        $("#editFastKalaN").prop("checked", false);
        $("#deleteFastKalaN").prop("checked", false);
        $("#editPishkharidN").prop("checked", false);
        $("#deletePishKharidN").prop("checked", false);
        $("#editBrandN").prop("checked", false);
        $("#deleteBrandsN").prop("checked", false);
        $("#editAlertedN").prop("checked", false);
        $("#deleteAlertedN").prop("checked", false);
        $("#editKalaGroupN").prop("checked", false);
        $("#deletKalaGroupN").prop("checked", false);
        $("#seeKalaGroupN").prop("checked", false);
        $("#editOrderSalesN").prop("checked", false);
        $("#deleteOrderSalesN").prop("checked", false);
    }
})


$("#kalaListsN").on("change", () => {
    if ($("#kalaListsN").is(":checked")) {
        $("#seeKalaListN").prop("checked", true);
        $("#operationN").prop("checked", true);
        $("#kalasN").prop("checked", true);

    } else {
        $("#kalaListsN").prop("checked", false);
        if (!$(".kalaElement").is(":checked")) {
            $("#operationN").prop("checked", false);
            $("#kalasN").prop("checked", false);
        } else {
            $("#operationN").prop("checked", true);
            $("#kalasN").prop("checked", true);
        }

        $("#seeKalaListN").prop("checked", false);
        $("#editKalaListN").prop("checked", false);
        $("#deleteKalaListN").prop("checked", false);

    }
})

$("#deleteKalaListN").on("change", () => {
    if ($("#deleteKalaListN").is(":checked")) {
        $("#operationN").prop("checked", true);
        $("#kalasN").prop("checked", true);
        $("#kalaListsN").prop("checked", true);
        $("#seeKalaListN").prop("checked", true);
        $("#editKalaListN").prop("checked", true);

    } else {
        $("#deleteKalaListN").prop("checked", false);
        if (!$(".kalaElement").is(":checked")) {
            $("#operationN").prop("checked", false);
            $("#kalasN").prop("checked", false);
        } else {
            $("#operationN").prop("checked", true);
            $("#kalasN").prop("checked", true);
        }

    }
})

$("#editKalaListN").on("change", () => {
    if ($("#editKalaListN").is(":checked")) {
        $("#operationN").prop("checked", true);
        $("#kalasN").prop("checked", true);
        $("#kalaListsN").prop("checked", true);
        $("#seeKalaListN").prop("checked", true);

    } else {
        $("#editKalaListN").prop("checked", false);
        if (!$(".kalaElement").is(":checked")) {
            $("#operationN").prop("checked", false);
            $("#kalasN").prop("checked", false);
        } else {
            $("#operationN").prop("checked", true);
            $("#kalasN").prop("checked", true);
        }
        $("#deleteKalaListN").prop("checked", false);
    }
})

$("#seeKalaListN").on("change", () => {
    if ($("#seeKalaListN").is(":checked")) {
        $("#operationN").prop("checked", true);
        $("#kalasN").prop("checked", true);
        $("#kalaListsN").prop("checked", true);
    } else {

        $("#kalaListsN").prop("checked", false);

        if (!$(".kalaElement").is(":checked")) {
            $("#operationN").prop("checked", false);
            $("#kalasN").prop("checked", false);
        } else {
            $("#operationN").prop("checked", true);
            $("#kalasN").prop("checked", true);
        }

        $("#deleteKalaListN").prop("checked", false);
        $("#editKalaListN").prop("checked", false);
    }
})


$("#requestedKalaN").on("change", () => {


})

$("#requestedKalaN").on("change", () => {
    if ($("#requestedKalaN").is(":checked")) {
        $("#operationN").prop("checked", true);
        $("#kalasN").prop("checked", true);
        $("#seeRequestedKalaN").prop("checked", true);

    } else {
        $("#requestedKalaN").prop("checked", false);

        if (!$(".kalaElement").is(":checked")) {
            $("#operationN").prop("checked", false);
            $("#kalasN").prop("checked", false);
        } else {
            $("#operationN").prop("checked", true);
            $("#kalasN").prop("checked", true);
        }

        $("#seeRequestedKalaN").prop("checked", false);
        $("#editRequestedKalaN").prop("checked", false);
        $("#deleteRequestedKalaN").prop("checked", false);
    }
})


$("#deleteRequestedKalaN").on("change", () => {
    if ($("#deleteRequestedKalaN").is(":checked")) {
        $("#operationN").prop("checked", true);
        $("#kalasN").prop("checked", true);
        $("#requestedKalaN").prop("checked", true);
        $("#seeRequestedKalaN").prop("checked", true);
        $("#editRequestedKalaN").prop("checked", true);

    } else {

        if (!$(".kalaElement").is(":checked")) {
            $("#operationN").prop("checked", false);
            $("#kalasN").prop("checked", false);
        } else {
            $("#operationN").prop("checked", true);
            $("#kalasN").prop("checked", true);
        }
        $("#deleteRequestedKalaN").prop("checked", false);
    }
})


$("#editRequestedKalaN").on("change", () => {
    if ($("#editRequestedKalaN").is(":checked")) {
        $("#operationN").prop("checked", true);
        $("#kalasN").prop("checked", true);
        $("#requestedKalaN").prop("checked", true);
        $("#seeRequestedKalaN").prop("checked", true);

    } else {

        if (!$(".kalaElement").is(":checked")) {
            $("#operationN").prop("checked", false);
            $("#kalasN").prop("checked", false);
        } else {
            $("#operationN").prop("checked", true);
            $("#kalasN").prop("checked", true);
        }
        $("#editRequestedKalaN").prop("checked", false);
        $("#deleteRequestedKalaN").prop("checked", false);
    }
})


$("#seeRequestedKalaN").on("change", () => {
    if ($("#seeRequestedKalaN").is(":checked")) {
        $("#operationN").prop("checked", true);
        $("#kalasN").prop("checked", true);
        $("#requestedKalaN").prop("checked", true);

    } else {
        $("#requestedKalaN").prop("checked", false);
        if (!$(".kalaElement").is(":checked")) {
            $("#operationN").prop("checked", false);
            $("#kalasN").prop("checked", false);
        } else {
            $("#operationN").prop("checked", true);
            $("#kalasN").prop("checked", true);
        }
        $("#seeRequestedKalaN").prop("checked", false);
        $("#editRequestedKalaN").prop("checked", false);
        $("#deleteRequestedKalaN").prop("checked", false);
    }
})



$("#fastKalaN").on("change", () => {
    if ($("#fastKalaN").is(":checked")) {
        $("#operationN").prop("checked", true);
        $("#kalasN").prop("checked", true);
        $("#seeFastKalaN").prop("checked", true);

    } else {
        $("#fastKalaN").prop("checked", false);

        if (!$(".kalaElement").is(":checked")) {
            $("#operationN").prop("checked", false);
            $("#kalasN").prop("checked", false);
        } else {
            $("#operationN").prop("checked", true);
            $("#kalasN").prop("checked", true);
        }

        $("#seeFastKalaN").prop("checked", false);
        $("#editFastKalaN").prop("checked", false);
        $("#deleteFastKalaN").prop("checked", false);
    }
})



$("#deleteFastKalaN").on("change", () => {
    if ($("#deleteFastKalaN").is(":checked")) {
        $("#operationN").prop("checked", true);
        $("#kalasN").prop("checked", true);
        $("#fastKalaN").prop("checked", true);
        $("#seeFastKalaN").prop("checked", true);
        $("#editFastKalaN").prop("checked", true);

    } else {
        if (!$(".kalaElement").is(":checked")) {
            $("#operationN").prop("checked", false);
            $("#kalasN").prop("checked", false);
        } else {
            $("#operationN").prop("checked", true);
            $("#kalasN").prop("checked", true);
        }
        $("#deleteFastKalaN").prop("checked", false);
    }
})


$("#editFastKalaN").on("change", () => {
    if ($("#editFastKalaN").is(":checked")) {
        $("#operationN").prop("checked", true);
        $("#kalasN").prop("checked", true);
        $("#fastKalaN").prop("checked", true);
        $("#seeFastKalaN").prop("checked", true);

    } else {

        if (!$(".kalaElement").is(":checked")) {
            $("#operationN").prop("checked", false);
            $("#kalasN").prop("checked", false);
        } else {
            $("#operationN").prop("checked", true);
            $("#kalasN").prop("checked", true);
        }
        $("#editFastKalaN").prop("checked", false);
        $("#deleteFastKalaN").prop("checked", false);
    }
})


$("#seeFastKalaN").on("change", () => {
    if ($("#seeFastKalaN").is(":checked")) {
        $("#operationN").prop("checked", true);
        $("#kalasN").prop("checked", true);
        $("#fastKalaN").prop("checked", true);

    } else {
        $("#fastKalaN").prop("checked", false);

        if (!$(".kalaElement").is(":checked")) {
            $("#operationN").prop("checked", false);
            $("#kalasN").prop("checked", false);
        } else {
            $("#operationN").prop("checked", true);
            $("#kalasN").prop("checked", true);
        }
        $("#seeFastKalaN").prop("checked", false);
        $("#editFastKalaN").prop("checked", false);
        $("#deleteFastKalaN").prop("checked", false);
    }
})


$("#pishKharidN").on("change", () => {
    if ($("#pishKharidN").is(":checked")) {
        $("#operationN").prop("checked", true);
        $("#kalasN").prop("checked", true);
        $("#seePishKharidN").prop("checked", true);

    } else {
        $("#pishKharidN").prop("checked", false);

        if (!$(".kalaElement").is(":checked")) {
            $("#operationN").prop("checked", false);
            $("#kalasN").prop("checked", false);
        } else {
            $("#operationN").prop("checked", true);
            $("#kalasN").prop("checked", true);
        }

        $("#seePishKharidN").prop("checked", false);
        $("#editPishkharidN").prop("checked", false);
        $("#deletePishKharidN").prop("checked", false);
    }
})


$("#deletePishKharidN").on("change", () => {
    if ($("#deletePishKharidN").is(":checked")) {
        $("#operationN").prop("checked", true);
        $("#kalasN").prop("checked", true);
        $("#pishKharidN").prop("checked", true);
        $("#seePishKharidN").prop("checked", true);
        $("#editPishkharidN").prop("checked", true);

    } else {
        if (!$(".kalaElement").is(":checked")) {
            $("#operationN").prop("checked", false);
            $("#kalasN").prop("checked", false);
        } else {
            $("#operationN").prop("checked", true);
            $("#kalasN").prop("checked", true);
        }
        $("#deletePishKharidN").prop("checked", false);
    }
})


$("#editPishkharidN").on("change", () => {
    if ($("#editPishkharidN").is(":checked")) {
        $("#operationN").prop("checked", true);
        $("#kalasN").prop("checked", true);
        $("#pishKharidN").prop("checked", true);
        $("#seePishKharidN").prop("checked", true);

    } else {

        if (!$(".kalaElement").is(":checked")) {
            $("#operationN").prop("checked", false);
            $("#kalasN").prop("checked", false);
        } else {
            $("#operationN").prop("checked", true);
            $("#kalasN").prop("checked", true);
        }
        $("#editPishkharidN").prop("checked", false);
        $("#deletePishKharidN").prop("checked", false);
    }
})


$("#seePishKharidN").on("change", () => {
    if ($("#seePishKharidN").is(":checked")) {
        $("#operationN").prop("checked", true);
        $("#kalasN").prop("checked", true);
        $("#pishKharidN").prop("checked", true);

    } else {
        $("#pishKharidN").prop("checked", false);

        if (!$(".kalaElement").is(":checked")) {
            $("#operationN").prop("checked", false);
            $("#kalasN").prop("checked", false);
        } else {
            $("#operationN").prop("checked", true);
            $("#kalasN").prop("checked", true);
        }
        $("#seePishKharidN").prop("checked", false);
        $("#editPishkharidN").prop("checked", false);
        $("#deletePishKharidN").prop("checked", false);
    }
})



$("#brandsN").on("change", () => {
    if ($("#brandsN").is(":checked")) {
        $("#operationN").prop("checked", true);
        $("#kalasN").prop("checked", true);
        $("#seeBrandsN").prop("checked", true);

    } else {
        $("#brandsN").prop("checked", false);

        if (!$(".kalaElement").is(":checked")) {
            $("#operationN").prop("checked", false);
            $("#kalasN").prop("checked", false);
        } else {
            $("#operationN").prop("checked", true);
            $("#kalasN").prop("checked", true);
        }

        $("#seeBrandsN").prop("checked", false);
        $("#editBrandN").prop("checked", false);
        $("#deleteBrandsN").prop("checked", false);
    }
})


$("#deleteBrandsN").on("change", () => {
    if ($("#deleteBrandsN").is(":checked")) {
        $("#operationN").prop("checked", true);
        $("#kalasN").prop("checked", true);
        $("#brandsN").prop("checked", true);
        $("#seeBrandsN").prop("checked", true);
        $("#editBrandN").prop("checked", true);
    } else {

        if (!$(".kalaElement").is(":checked")) {
            $("#operationN").prop("checked", false);
            $("#kalasN").prop("checked", false);
        } else {
            $("#operationN").prop("checked", true);
            $("#kalasN").prop("checked", true);
        }

        $("#deleteBrandsN").prop("checked", false);
    }
})

$("#editBrandN").on("change", () => {
    if ($("#editBrandN").is(":checked")) {
        $("#operationN").prop("checked", true);
        $("#kalasN").prop("checked", true);
        $("#brandsN").prop("checked", true);
        $("#seeBrandsN").prop("checked", true);

    } else {

        if (!$(".kalaElement").is(":checked")) {
            $("#operationN").prop("checked", false);
            $("#kalasN").prop("checked", false);
        } else {
            $("#operationN").prop("checked", true);
            $("#kalasN").prop("checked", true);
        }
        $("#editBrandN").prop("checked", false);
        $("#deleteBrandsN").prop("checked", false);
    }
})


$("#seeBrandsN").on("change", () => {
    if ($("#seeBrandsN").is(":checked")) {
        $("#operationN").prop("checked", true);
        $("#kalasN").prop("checked", true);
        $("#brandsN").prop("checked", true);

    } else {
        $("#brandsN").prop("checked", false);

        if (!$(".kalaElement").is(":checked")) {
            $("#operationN").prop("checked", false);
            $("#kalasN").prop("checked", false);
        } else {
            $("#operationN").prop("checked", true);
            $("#kalasN").prop("checked", true);
        }
        $("#seeBrandsN").prop("checked", false);
        $("#editBrandN").prop("checked", false);
        $("#deleteBrandsN").prop("checked", false);
    }
})



$("#alertedN").on("change", () => {
    if ($("#alertedN").is(":checked")) {
        $("#operationN").prop("checked", true);
        $("#kalasN").prop("checked", true);
        $("#seeAlertedN").prop("checked", true);

    } else {
        $("#alertedN").prop("checked", false);

        if (!$(".kalaElement").is(":checked")) {
            $("#operationN").prop("checked", false);
            $("#kalasN").prop("checked", false);
        } else {
            $("#operationN").prop("checked", true);
            $("#kalasN").prop("checked", true);
        }

        $("#seeAlertedN").prop("checked", false);
        $("#editAlertedN").prop("checked", false);
        $("#deleteAlertedN").prop("checked", false);
    }
})


$("#deleteAlertedN").on("change", () => {
    if ($("#deleteAlertedN").is(":checked")) {
        $("#operationN").prop("checked", true);
        $("#kalasN").prop("checked", true);
        $("#alertedN").prop("checked", true);
        $("#seeAlertedN").prop("checked", true);
        $("#editAlertedN").prop("checked", true);
    } else {

        if (!$(".kalaElement").is(":checked")) {
            $("#operationN").prop("checked", false);
            $("#kalasN").prop("checked", false);
        } else {
            $("#operationN").prop("checked", true);
            $("#kalasN").prop("checked", true);
        }

        $("#deleteAlertedN").prop("checked", false);
    }
})

$("#editAlertedN").on("change", () => {
    if ($("#editAlertedN").is(":checked")) {
        $("#operationN").prop("checked", true);
        $("#kalasN").prop("checked", true);
        $("#alertedN").prop("checked", true);
        $("#seeAlertedN").prop("checked", true);

    } else {

        if (!$(".kalaElement").is(":checked")) {
            $("#operationN").prop("checked", false);
            $("#kalasN").prop("checked", false);
        } else {
            $("#operationN").prop("checked", true);
            $("#kalasN").prop("checked", true);
        }
        $("#editAlertedN").prop("checked", false);
        $("#deleteAlertedN").prop("checked", false);
    }
})


$("#seeAlertedN").on("change", () => {
    if ($("#seeAlertedN").is(":checked")) {
        $("#operationN").prop("checked", true);
        $("#kalasN").prop("checked", true);
        $("#alertedN").prop("checked", true);

    } else {
        $("#alertedN").prop("checked", false);

        if (!$(".kalaElement").is(":checked")) {
            $("#operationN").prop("checked", false);
            $("#kalasN").prop("checked", false);
        } else {
            $("#operationN").prop("checked", true);
            $("#kalasN").prop("checked", true);
        }
        $("#seeAlertedN").prop("checked", false);
        $("#editAlertedN").prop("checked", false);
        $("#deleteAlertedN").prop("checked", false);
    }
})




$("#kalaGroupN").on("change", () => {
    if ($("#kalaGroupN").is(":checked")) {
        $("#operationN").prop("checked", true);
        $("#kalasN").prop("checked", true);
        $("#seeKalaGroupN").prop("checked", true);

    } else {
        $("#kalaGroupN").prop("checked", false);

        if (!$(".kalaElement").is(":checked")) {
            $("#operationN").prop("checked", false);
            $("#kalasN").prop("checked", false);
        } else {
            $("#operationN").prop("checked", true);
            $("#kalasN").prop("checked", true);
        }

        $("#seeKalaGroupN").prop("checked", false);
        $("#editKalaGroupN").prop("checked", false);
        $("#deletKalaGroupN").prop("checked", false);
    }
})


$("#deletKalaGroupN").on("change", () => {
    if ($("#deletKalaGroupN").is(":checked")) {
        $("#operationN").prop("checked", true);
        $("#kalasN").prop("checked", true);
        $("#kalaGroupN").prop("checked", true);
        $("#seeKalaGroupN").prop("checked", true);
        $("#editKalaGroupN").prop("checked", true);
    } else {

        if (!$(".kalaElement").is(":checked")) {
            $("#operationN").prop("checked", false);
            $("#kalasN").prop("checked", false);
        } else {
            $("#operationN").prop("checked", true);
            $("#kalasN").prop("checked", true);
        }

        $("#deletKalaGroupN").prop("checked", false);
    }
})

$("#editKalaGroupN").on("change", () => {
    if ($("#editKalaGroupN").is(":checked")) {
        $("#operationN").prop("checked", true);
        $("#kalasN").prop("checked", true);
        $("#kalaGroupN").prop("checked", true);
        $("#seeKalaGroupN").prop("checked", true);

    } else {

        if (!$(".kalaElement").is(":checked")) {
            $("#operationN").prop("checked", false);
            $("#kalasN").prop("checked", false);
        } else {
            $("#operationN").prop("checked", true);
            $("#kalasN").prop("checked", true);
        }
        $("#editKalaGroupN").prop("checked", false);
        $("#deletKalaGroupN").prop("checked", false);
    }
})


$("#seeKalaGroupN").on("change", () => {
    if ($("#seeKalaGroupN").is(":checked")) {
        $("#operationN").prop("checked", true);
        $("#kalasN").prop("checked", true);
        $("#kalaGroupN").prop("checked", true);

    } else {
        $("#kalaGroupN").prop("checked", false);

        if (!$(".kalaElement").is(":checked")) {
            $("#operationN").prop("checked", false);
            $("#kalasN").prop("checked", false);
        } else {
            $("#operationN").prop("checked", true);
            $("#kalasN").prop("checked", true);
        }
        $("#seeKalaGroupN").prop("checked", false);
        $("#editKalaGroupN").prop("checked", false);
        $("#deletKalaGroupN").prop("checked", false);
    }
})



$("#orderSalesN").on("change", () => {
    if ($("#orderSalesN").is(":checked")) {
        $("#operationN").prop("checked", true);
        $("#kalasN").prop("checked", true);
        $("#seeSalesOrderN").prop("checked", true);

    } else {
        $("#orderSalesN").prop("checked", false);

        if (!$(".kalaElement").is(":checked")) {
            $("#operationN").prop("checked", false);
            $("#kalasN").prop("checked", false);
        } else {
            $("#operationN").prop("checked", true);
            $("#kalasN").prop("checked", true);
        }

        $("#seeSalesOrderN").prop("checked", false);
        $("#editOrderSalesN").prop("checked", false);
        $("#deleteOrderSalesN").prop("checked", false);
    }
})


$("#deleteOrderSalesN").on("change", () => {
    if ($("#deleteOrderSalesN").is(":checked")) {
        $("#operationN").prop("checked", true);
        $("#kalasN").prop("checked", true);
        $("#orderSalesN").prop("checked", true);
        $("#seeSalesOrderN").prop("checked", true);
        $("#editOrderSalesN").prop("checked", true);
    } else {

        if (!$(".kalaElement").is(":checked")) {
            $("#operationN").prop("checked", false);
            $("#kalasN").prop("checked", false);
        } else {
            $("#operationN").prop("checked", true);
            $("#kalasN").prop("checked", true);
        }
        $("#deleteOrderSalesN").prop("checked", false);
    }
})

$("#editOrderSalesN").on("change", () => {
    if ($("#editOrderSalesN").is(":checked")) {
        $("#operationN").prop("checked", true);
        $("#kalasN").prop("checked", true);
        $("#orderSalesN").prop("checked", true);
        $("#seeSalesOrderN").prop("checked", true);

    } else {

        if (!$(".kalaElement").is(":checked")) {
            $("#operationN").prop("checked", false);
            $("#kalasN").prop("checked", false);
        } else {
            $("#operationN").prop("checked", true);
            $("#kalasN").prop("checked", true);
        }
        $("#editOrderSalesN").prop("checked", false);
        $("#deleteOrderSalesN").prop("checked", false);
    }
})

$("#seeSalesOrderN").on("change", () => {
    if ($("#seeSalesOrderN").is(":checked")) {
        $("#operationN").prop("checked", true);
        $("#kalasN").prop("checked", true);
        $("#orderSalesN").prop("checked", true);

    } else {
        $("#orderSalesN").prop("checked", false);

        if (!$(".kalaElement").is(":checked")) {
            $("#operationN").prop("checked", false);
            $("#kalasN").prop("checked", false);
        } else {
            $("#operationN").prop("checked", true);
            $("#kalasN").prop("checked", true);
        }
        $("#seeSalesOrderN").prop("checked", false);
        $("#editOrderSalesN").prop("checked", false);
        $("#deleteOrderSalesN").prop("checked", false);
    }
})













$("#messageN").on("change", () => {
    if ($("#messageN").is(":checked")) {
        $("#operationN").prop("checked", true);
        $("#kalasN").prop("checked", true);
        $("#seeMessageN").prop("checked", true);

    } else {
        $("#messageN").prop("checked", false);

        if (!$(".kalaElement").is(":checked")) {
            $("#operationN").prop("checked", false);
            $("#kalasN").prop("checked", false);
        } else {
            $("#operationN").prop("checked", true);
            $("#kalasN").prop("checked", true);
        }

        $("#seeMessageN").prop("checked", false);
        $("#editMessageN").prop("checked", false);
        $("#deleteMessageN").prop("checked", false);
    }
})


$("#deleteMessageN").on("change", () => {
    if ($("#deleteMessageN").is(":checked")) {
        $("#operationN").prop("checked", true);
        $("#kalasN").prop("checked", true);
        $("#messageN").prop("checked", true);
        $("#seeMessageN").prop("checked", true);
        $("#editMessageN").prop("checked", true);
    } else {

        if (!$(".kalaElement").is(":checked")) {
            $("#operationN").prop("checked", false);
            $("#kalasN").prop("checked", false);
        } else {
            $("#operationN").prop("checked", true);
            $("#kalasN").prop("checked", true);
        }

        $("#deleteMessageN").prop("checked", false);
    }
})

$("#editMessageN").on("change", () => {
    if ($("#editMessageN").is(":checked")) {
        $("#operationN").prop("checked", true);
        $("#kalasN").prop("checked", true);
        $("#messageN").prop("checked", true);
        $("#seeMessageN").prop("checked", true);

    } else {

        if (!$(".kalaElement").is(":checked")) {
            $("#operationN").prop("checked", false);
            $("#kalasN").prop("checked", false);
        } else {
            $("#operationN").prop("checked", true);
            $("#kalasN").prop("checked", true);
        }
        $("#editMessageN").prop("checked", false);
        $("#deleteMessageN").prop("checked", false);
    }
})


$("#seeMessageN").on("change", () => {
    if ($("#seeMessageN").is(":checked")) {
        $("#operationN").prop("checked", true);
        $("#kalasN").prop("checked", true);
        $("#messageN").prop("checked", true);

    } else {
        $("#messageN").prop("checked", false);

        if (!$(".kalaElement").is(":checked")) {
            $("#operationN").prop("checked", false);
            $("#kalasN").prop("checked", false);
        } else {
            $("#operationN").prop("checked", true);
            $("#kalasN").prop("checked", true);
        }
        $("#seeMessageN").prop("checked", false);
        $("#editMessageN").prop("checked", false);
        $("#deleteMessageN").prop("checked", false);
    }
})



// گزارشات 

$("#reportN").on("change", () => {
    if ($("#reportN").is(":checked")) {
        $("#reportCustomerN").prop("checked", true);
        $("#cutomerListN").prop("checked", true);
        $("#seeCustomerListN").prop("checked", true);
        $("#officialCustomerN").prop("checked", true);
        $("#seeOfficialCustomerN").prop("checked", true);
        $("#gameAndLotteryN").prop("checked", true);
        $("#lotteryResultN").prop("checked", true);
        $("#seeLotteryResultN").prop("checked", true);
        $("#gamerListN").prop("checked", true);
        $("#seeGamerListN").prop("checked", true);
        $("#onlinePaymentN").prop("checked", true);
        $("#seeOnlinePaymentN").prop("checked", true);
    } else {
        $("#reportCustomerN").prop("checked", false);
        $("#cutomerListN").prop("checked", false);
        $("#seeCustomerListN").prop("checked", false);
        $("#officialCustomerN").prop("checked", false);
        $("#officialCustomerN").change();
        $("#seeOfficialCustomerN").prop("checked", false);
        $("#gameAndLotteryN").prop("checked", false);
        $("#lotteryResultN").prop("checked", false);
        $("#lotteryResultN").change();
        $("#seeLotteryResultN").prop("checked", false);
        $("#gamerListN").prop("checked", false);
        $("#gamerListN").change();
        $("#seeGamerListN").prop("checked", false);
        $("#onlinePaymentN").prop("checked", false);
        $("#onlinePaymentN").change();
        $("#seeOnlinePaymentN").prop("checked", false);
        $("#editCustomerListN").prop("checked", false);
        $("#deletCustomerListN").prop("checked", false);

    }
})


$("#reportCustomerN").on("change", () => {
    if ($("#reportCustomerN").is(":checked")) {
        $("#reportN").prop("checked", true);
        $("#cutomerListN").prop("checked", true);
        $("#seeCustomerListN").prop("checked", true);
        $("#officialCustomerN").prop("checked", true);
        $("#seeOfficialCustomerN").prop("checked", true);

    } else {
        $("#reportCustomerN").prop("checked", false);
        if (!$(".reportElementN").is(":checked")) {
            $("#reportN").prop("checked", false);
        } else {
            $("#reportN").prop("checked", true);
        }

        $("#cutomerListN").prop("checked", false);
        $("#seeCustomerListN").prop("checked", false);
        $("#officialCustomerN").prop("checked", false);
        $("#seeOfficialCustomerN").prop("checked", false);
        $("#editCustomerListN").prop("checked", false);
        $("#deletCustomerListN").prop("checked", false);

    }
})


$("#cutomerListN").on("change", () => {
    if ($("#cutomerListN").is(":checked")) {
        $("#reportN").prop("checked", true);
        $("#reportCustomerN").prop("checked", true);
        $("#seeCustomerListN").prop("checked", true);

    } else {

        $("#cutomerListN").prop("checked", false);

        if (!$(".cutomerListN").is(":checked")) {
            $("#reportCustomerN").prop("checked", false);
            $("#reportCustomerN").change();

        } else {
            $("#reportN").prop("checked", true);
            $("#reportCustomerN").prop("checked", true);

        }

        $("#seeCustomerListN").prop("checked", false);
        $("#editCustomerListN").prop("checked", false);
        $("#deletCustomerListN").prop("checked", false);


    }
})



$("#deletCustomerListN").on("change", () => {
    if ($("#deletCustomerListN").is(":checked")) {
        $("#reportN").prop("checked", true);
        $("#cutomerListN").prop("checked", true);
        $("#seeCustomerListN").prop("checked", true);
        $("#editCustomerListN").prop("checked", true);
        $("#reportCustomerN").prop("checked", true);

    } else {
        $("#deletCustomerListN").prop("checked", false);

    }
})

$("#editCustomerListN").on("change", () => {
    if ($("#editCustomerListN").is(":checked")) {
        $("#reportN").prop("checked", true);
        $("#cutomerListN").prop("checked", true);
        $("#seeCustomerListN").prop("checked", true);
        $("#reportCustomerN").prop("checked", true);
    } else {
        $("#editCustomerListN").prop("checked", false);
        $("#deletCustomerListN").prop("checked", false);

    }
})



$("#seeCustomerListN").on("change", () => {
    if ($("#seeCustomerListN").is(":checked")) {
        $("#reportN").prop("checked", true);
        $("#reportCustomerN").prop("checked", true);
        $("#cutomerListN").prop("checked", true);
    } else {

        $("#cutomerListN").prop("checked", false);

        if (!$(".cutomerListN").is(":checked")) {

            $("#reportCustomerN").prop("checked", false);
            $("#reportCustomerN").change();

        } else {
            $("#reportN").prop("checked", true);
            $("#reportCustomerN").prop("checked", true);

        }

        $("#editCustomerListN").prop("checked", false);
        $("#deletCustomerListN").prop("checked", false);

    }
})



$("#officialCustomerN").on("change", () => {
    if ($("#officialCustomerN").is(":checked")) {
        $("#reportN").prop("checked", true);
        $("#reportCustomerN").prop("checked", true);
        $("#seeOfficialCustomerN").prop("checked", true);
    } else {
        $("#officialCustomerN").prop("checked", false);
        if (!$(".cutomerListN").is(":checked")) {
            $("#reportCustomerN").prop("checked", false);
            $("#reportCustomerN").change();
        } else {
            $("#reportN").prop("checked", true);
            $("#reportCustomerN").prop("checked", true);

        }

        $("#seeOfficialCustomerN").prop("checked", false);
        $("#editOfficialCustomerN").prop("checked", false);
        $("#deleteOfficialCustomerN").prop("checked", false);
    }

})


$("#deleteOfficialCustomerN").on("change", () => {
    if ($("#deleteOfficialCustomerN").is(":checked")) {
        $("#reportN").prop("checked", true);
        $("#seeOfficialCustomerN").prop("checked", true);
        $("#editOfficialCustomerN").prop("checked", true);
        $("#reportCustomerN").prop("checked", true);
        $("#officialCustomerN").prop("checked", true);

    } else {
        $("#deleteOfficialCustomerN").prop("checked", false);

    }
})


$("#editOfficialCustomerN").on("change", () => {
    if ($("#editOfficialCustomerN").is(":checked")) {
        $("#reportN").prop("checked", true);
        $("#officialCustomerN").prop("checked", true);
        $("#reportCustomerN").prop("checked", true);
        $("#seeOfficialCustomerN").prop("checked", true);
    } else {
        $("#editOfficialCustomerN").prop("checked", false);
        $("#deleteOfficialCustomerN").prop("checked", false);
    }
})


$("#seeOfficialCustomerN").on("change", () => {
    if ($("#seeOfficialCustomerN").is(":checked")) {
        $("#reportN").prop("checked", true);
        $("#reportCustomerN").prop("checked", true);
        $("#officialCustomerN").prop("checked", true);
    } else {

        $("#officialCustomerN").prop("checked", false);

        if (!$(".cutomerListN").is(":checked")) {
            $("#reportCustomerN").prop("checked", false);
            $("#reportCustomerN").change();

        } else {
            $("#editOfficialCustomerN").prop("checked", false);
            $("#deleteOfficialCustomerN").prop("checked", false);
        }



    }
})




$("#gameAndLotteryN").on("change", () => {
    if ($("#gameAndLotteryN").is(":checked")) {
        $("#reportN").prop("checked", true);
        $("#lotteryResultN").prop("checked", true);
        $("#seeLotteryResultN").prop("checked", true);
        $("#gamerListN").prop("checked", true);
        $("#seeGamerListN").prop("checked", true);

    } else {
        $("#gameAndLotteryN").prop("checked", false);
        if (!$(".reportElementN").is(":checked")) {
            $("#reportN").prop("checked", false);
        } else {
            $("#reportN").prop("checked", true);
        }

        $("#lotteryResultN").prop("checked", false);
        $("#seeLotteryResultN").prop("checked", false);
        $("#gamerListN").prop("checked", false);
        $("#seeGamerListN").prop("checked", false);
        $("#editCustomerListN").prop("checked", false);
        $("#deletCustomerListN").prop("checked", false);
        $("#editLotteryResultN").prop("checked", false);

    }
})

$("#lotteryResultN").on("change", () => {
    if ($("#lotteryResultN").is(":checked")) {
        $("#reportN").prop("checked", true);
        $("#gameAndLotteryN").prop("checked", true);
        $("#seeLotteryResultN").prop("checked", true);
    } else {

        $("#lotteryResultN").prop("checked", false);

        if (!$(".gameAndLotteryElement").is(":checked")) {
            $("#gameAndLotteryN").prop("checked", false);
        } else {
            $("#gameAndLotteryN").prop("checked", true);
        }
        $("#seeLotteryResultN").prop("checked", false);
        $("#editLotteryResultN").prop("checked", false);
        $("#deletLotteryResultN").prop("checked", false);

    }
});

$("#deletLotteryResultN").on("change", () => {
    if ($("#deletLotteryResultN").is(":checked")) {
        $("#reportN").prop("checked", true);
        $("#gameAndLotteryN").prop("checked", true);
        $("#seeLotteryResultN").prop("checked", true);
        $("#lotteryResultN").prop("checked", true);
        $("#editLotteryResultN").prop("checked", true);

    } else {
        $("#deletLotteryResultN").prop("checked", false);

    }
})

$("#editLotteryResultN").on("change", () => {
    if ($("#editLotteryResultN").is(":checked")) {
        $("#reportN").prop("checked", true);
        $("#gameAndLotteryN").prop("checked", true);
        $("#seeLotteryResultN").prop("checked", true);
        $("#lotteryResultN").prop("checked", true);
        $("#editLotteryResultN").prop("checked", true);

    } else {
        $("#editLotteryResultN").prop("checked", false);
        $("#deletLotteryResultN").prop("checked", false);

    }
})


$("#seeLotteryResultN").on("change", () => {
    if ($("#seeLotteryResultN").is(":checked")) {
        $("#reportN").prop("checked", true);
        $("#gameAndLotteryN").prop("checked", true);
        $("#lotteryResultN").prop("checked", true);
    } else {

        $("#lotteryResultN").prop("checked", false);

        if (!$(".gameAndLotteryElement").is(":checked")) {
            $("#gameAndLotteryN").prop("checked", false);
            $("#gameAndLotteryN").change();

        } else {
            $("#reportN").prop("checked", true);
            $("#gameAndLotteryN").prop("checked", true);

        }

        $("#editLotteryResultN").prop("checked", false);
        $("#deletLotteryResultN").prop("checked", false);

    }
})

$("#gamerListN").on("change", function () {
    if ($("#gamerListN").is(":checked")) {
        $("#seeGamerListN").prop("checked", true);
        $("#gameAndLotteryN").prop("checked", true);
        $("#reportN").prop("checked", true);
    } else {
        $("#seeGamerListN").prop("checked", false);
        $("#editGamerListN").prop("checked", false);
        $("#deletGamerListN").prop("checked", false);
        if (!$(".gameAndLotteryElement").is(":checked")) {
            $("#gameAndLotteryN").prop("checked", false);
            $("#gameAndLotteryN").change();

        } else {
            $("#reportN").prop("checked", true);
            $("#gameAndLotteryN").prop("checked", true);

        }
    }
})


$("#seeGamerListN").on("change", function () {
    if ($("#seeGamerListN").is(":checked")) {
        $("#seeGamerListN").prop("checked", true);
        $("#gameAndLotteryN").prop("checked", true);
        $("#gamerListN").prop("checked", true);
        $("#reportN").prop("checked", true);
    } else {
        $("#seeGamerListN").prop("checked", false);
        $("#editGamerListN").prop("checked", false);
        $("#deletGamerListN").prop("checked", false);
        $("#gamerListN").prop("checked", false);
        if (!$(".gameAndLotteryElement").is(":checked")) {
            $("#gameAndLotteryN").prop("checked", false);
            $("#gameAndLotteryN").change();

        } else {
            $("#reportN").prop("checked", true);
            $("#gameAndLotteryN").prop("checked", true);

        }
    }
})

$("#editGamerListN").on("change", function () {
    if ($("#editGamerListN").is(":checked")) {
        $("#seeGamerListN").prop("checked", true);
        $("#gameAndLotteryN").prop("checked", true);
        $("#reportN").prop("checked", true);
        $("#gamerListN").prop("checked", true);
    } else {
        $("#deletGamerListN").prop("checked", false);
    }
})

$("#deletGamerListN").on("change", function () {
    if ($("#deletGamerListN").is(":checked")) {
        $("#seeGamerListN").prop("checked", true);
        $("#editGamerListN").prop("checked", true);
        $("#gameAndLotteryN").prop("checked", true);
        $("#reportN").prop("checked", true);
        $("#gamerListN").prop("checked", true);
    } else {
        $("#deletGamerListN").prop("checked", false);
    }
})

$("#onlinePaymentN").on("change", function () {
    if ($("#onlinePaymentN").is(":checked")) {
        $("#seeOnlinePaymentN").prop("checked", true);
        $("#reportN").prop("checked", true);
    } else {
        if (!$(".reportElementN").is(":checked")) {
            $("#reportN").prop("checked", false);
        } else {
            $("#reportN").prop("checked", true);
        }
        $("#seeOnlinePaymentN").prop("checked", false);
        $("#editOnlinePaymentN").prop("checked", false);
        $("#deleteOnlinePaymentN").prop("checked", false);
    }
})

$("#deleteOnlinePaymentN").on("change", function () {
    if ($("#deleteOnlinePaymentN").is(":checked")) {
        $("#seeOnlinePaymentN").prop("checked", true);
        $("#onlinePaymentN").prop("checked", true);
        $("#editOnlinePaymentN").prop("checked", true);
        $("#reportN").prop("checked", true);
    } else {
        if (!$(".reportElementN").is(":checked")) {
            $("#reportN").prop("checked", false);
        } else {
            $("#reportN").prop("checked", true);
        }
    }
})

$("#seeOnlinePaymentN").on("change", function () {
    if ($("#seeOnlinePaymentN").is(":checked")) {
        $("#onlinePaymentN").prop("checked", true);
        $("#reportN").prop("checked", true);
    } else {
        $("#onlinePaymentN").prop("checked", false);
        if (!$(".reportElementN").is(":checked")) {
            $("#seeOnlinePaymentN").prop("checked", false);
            $("#onlinePaymentN").prop("checked", false);
            $("#reportN").prop("checked", false);
            $("#editOnlinePaymentN").prop("checked", false);
            $("#deleteOnlinePaymentN").prop("checked", false);
        } else {
            $("#seeOnlinePaymentN").prop("checked", false);
            $("#editOnlinePaymentN").prop("checked", false);
            $("#deleteOnlinePaymentN").prop("checked", false);
        }
    }
})

$("#editOnlinePaymentN").on("change", function () {
    if ($("#editOnlinePaymentN").is(":checked")) {
        $("#onlinePaymentN").prop("checked", true);
        $("#seeOnlinePaymentN").prop("checked", true);
        $("#reportN").prop("checked", true);
    } else {
        if (!$(".reportElementN").is(":checked")) {
            $("#seeOnlinePaymentN").prop("checked", false);
            $("#onlinePaymentN").prop("checked", false);
            $("#reportN").prop("checked", false);
            $("#editOnlinePaymentN").prop("checked", false);
            $("#deleteOnlinePaymentN").prop("checked", false);
        } else {
            $("#editOnlinePaymentN").prop("checked", false);
            $("#deleteOnlinePaymentN").prop("checked", false);
        }
    }
})



//USDE FOR UPDATING ACCESS LEVEL
$("#baseInfoED").on("change", () => {
    if ($("#baseInfoED").is(":checked")) {
        $("#settingsED").prop("checked", true);
        $(".allSettingsED").prop("checked", true);
        $("#seeMainPageSettingED").prop("checked", true);
        $("#seeSpecialSettingED").prop("checked", true);
        $("#seeEmtyazSettingED").prop("checked", true);

    } else {
        $("#settingsED").prop("checked", false);
        $(".allSettingsED").prop("checked", false);
        $("#seeMainPageSettingED").prop("checked", false);
        $("#seeSpecialSettingED").prop("checked", false);
        $("#seeEmtyazSettingED").prop("checked", false);
        $("#deletMainPageSettingED").prop("checked", false);
        $("#editManiPageSettingED").prop("checked", false);
        $("#deleteSpecialSettingED").prop("checked", false);
        $("#editSpecialSettingED").prop("checked", false);
        $("#editEmptyazSettingED").prop("checked", false);
        $("#deleteEmtyazSettingED").prop("checked", false);
    }
});


$("#settingsED").on("change", () => {
    if ($("#settingsED").is(":checked")) {
        $("#settingsED").prop("checked", true);
        $(".allSettingsED").prop("checked", true);
        $("#seeMainPageSettingED").prop("checked", true);
        $("#seeSpecialSettingED").prop("checked", true);
        $("#seeEmtyazSettingED").prop("checked", true);
        $("#baseInfoED").prop("checked", true);

    } else {
        $("#settingsED").prop("checked", false);
        $(".allSettingsED").prop("checked", false);
        $("#seeMainPageSettingED").prop("checked", false);
        $("#seeSpecialSettingED").prop("checked", false);
        $("#seeEmtyazSettingED").prop("checked", false);
        $("#deletMainPageSettingED").prop("checked", false);
        $("#editManiPageSettingED").prop("checked", false);
        $("#editEmptyazSettingED").prop("checked", false);
        $("#deleteEmtyazSettingED").prop("checked", false);
        $("#deleteSpecialSettingED").prop("checked", false);
        $("#editSpecialSettingED").prop("checked", false);
        $("#specialSettingED").prop("checked", false);
        $("#baseInfoED").prop("checked", false);
    }
});



$("#mainPageSetting").on("change", () => {
    if ($("#mainPageSetting").is(":checked")) {
        $("#seeMainPageSettingED").prop("checked", true);
        $("#settingsED").prop("checked", true);
        $("#baseInfoED").prop("checked", true);
    } else {
        $("#mainPageSetting").prop("checked", false);
        if (!$(".allSettingsED").is(":checked")) {
            $("#seeMainPageSettingED").prop("checked", false);
            $("#editManiPageSettingED").prop("checked", false);
            $("#deletMainPageSettingED").prop("checked", false);
            $("#baseInfoED").prop("checked", false);
            $("#settingsED").prop("checked", false);
        } else {
            $("#seeMainPageSettingED").prop("checked", false);
            $("#editManiPageSettingED").prop("checked", false);
            $("#deletMainPageSettingED").prop("checked", false);
        }

    }

})

$("#seeMainPageSettingED").on("change", () => {
    if ($("#seeMainPageSettingED").is(":checked")) {
        $("#mainPageSetting").prop("checked", true);
        $("#settingsED").prop("checked", true);
        $("#baseInfoED").prop("checked", true);
    } else {
        $("#mainPageSetting").prop("checked", false);

        if (!$(".allSettingsED").is(":checked")) {
            $("#settingsED").prop("checked", false);
            $("#baseInfoED").prop("checked", false);
        } else {
            $("#settingsED").prop("checked", true);
            $("#baseInfoED").prop("checked", true);
        }

        $("#deletMainPageSettingED").prop("checked", false);
        $("#editManiPageSettingED").prop("checked", false);
    }
})

$("#editManiPageSettingED").on("change", () => {
    if ($("#editManiPageSettingED").is(":checked")) {
        $("#seeMainPageSettingED").prop("checked", true);
        $("#mainPageSetting").prop("checked", true);
        $("#settingsED").prop("checked", true);
        $("#baseInfoED").prop("checked", true);

    } else {
        $("#editManiPageSettingED").prop("checked", false);
        $("#deletMainPageSettingED").prop("checked", false);
    }
})


$("#deletMainPageSettingED").on("change", () => {
    if ($("#deletMainPageSettingED").is(":checked")) {
        $("#seeMainPageSettingED").prop("checked", true);
        $("#editManiPageSettingED").prop("checked", true);
        $("#mainPageSetting").prop("checked", true);
        $("#settingsED").prop("checked", true);
        $("#baseInfoED").prop("checked", true);
    } else {
        $("#deletMainPageSettingED").prop("checked", false);
    }
})





$("#specialSettingED").on("change", () => {
    if ($("#specialSettingED").is(":checked")) {
        $("#seeSpecialSettingED").prop("checked", true);
        $("#settingsED").prop("checked", true);
        $("#baseInfoED").prop("checked", true);
    } else {
        $("#specialSettingED").prop("checked", false);
        if (!$(".allSettingsED").is(":checked")) {
            $("#settingsED").prop("checked", false);
            $("#baseInfoED").prop("checked", false);
            $("#seeSpecialSettingED").prop("checked", false);
            $("#editSpecialSettingED").prop("checked", false);
            $("#deleteSpecialSettingED").prop("checked", false);
        } else {
            $("#seeSpecialSettingED").prop("checked", false);
            $("#editSpecialSettingED").prop("checked", false);
            $("#deleteSpecialSettingED").prop("checked", false);
        }
    }

})

$("#deleteSpecialSettingED").on("change", () => {
    if ($("#deleteSpecialSettingED").is(":checked")) {
        $("#editSpecialSettingED").prop("checked", true);
        $("#seeSpecialSettingED").prop("checked", true);
        $("#specialSettingED").prop("checked", true);
        $("#settingsED").prop("checked", true);
        $("#baseInfoED").prop("checked", true);
    } else {
        $("#deleteSpecialSettingED").prop("checked", false);
    }
})

$("#editSpecialSettingED").on("change", () => {
    if ($("#editSpecialSettingED").is(":checked")) {
        $("#editSpecialSettingED").prop("checked", true);
        $("#seeSpecialSettingED").prop("checked", true);
        $("#specialSettingED").prop("checked", true);
        $("#settingsED").prop("checked", true);
        $("#baseInfoED").prop("checked", true);
    } else {
        $("#editSpecialSettingED").prop("checked", false);
        $("#deleteSpecialSettingED").prop("checked", false);
    }
})

$("#seeSpecialSettingED").on("change", () => {
    if ($("#seeSpecialSettingED").is(":checked")) {
        $("#seeSpecialSettingED").prop("checked", true);
        $("#specialSettingED").prop("checked", true);
        $("#settingsED").prop("checked", true);
        $("#baseInfoED").prop("checked", true);
    } else {
        $("#specialSettingED").prop("checked", false);
        if (!$(".allSettingsED").is(":checked")) {
            $("#settingsED").prop("checked", false);
            $("#baseInfoED").prop("checked", false);
        } else {
            $("#settingsED").prop("checked", true);
            $("#baseInfoED").prop("checked", true);
        }
        $("#deleteSpecialSettingED").prop("checked", false);
        $("#editSpecialSettingED").prop("checked", false);

    }
})



$("#emptyazSettingED").on("change", () => {
    if ($("#emptyazSettingED").is(":checked")) {
        $("#settingsED").prop("checked", true);
        $("#baseInfoED").prop("checked", true);
        $("#seeEmtyazSettingED").prop("checked", true);

    } else {
        if (!$(".allSettingsED").is(":checked")) {
            $("#emptyazSettingED").prop("checked", false);
            $("#seeEmtyazSettingED").prop("checked", false);
            $("#deleteEmtyazSettingED").prop("checked", false);
            $("#editEmptyazSettingED").prop("checked", false);
            $("#settingsED").prop("checked", false);
            $("#baseInfoED").prop("checked", false);
        } else {
            $("#seeEmtyazSettingED").prop("checked", false);
            $("#deleteEmtyazSettingED").prop("checked", false);
            $("#editEmptyazSettingED").prop("checked", false);
        }

    }
})

$("#deleteEmtyazSettingED").on("change", () => {
    if ($("#deleteEmtyazSettingED").is(":checked")) {
        $("#settingsED").prop("checked", true);
        $("#baseInfoED").prop("checked", true);
        $("#emptyazSettingED").prop("checked", true);
        $("#editEmptyazSettingED").prop("checked", true);
        $("#seeEmtyazSettingED").prop("checked", true);

    } else {
        $("#deleteEmtyazSettingED").prop("checked", false);
    }
})

$("#editEmptyazSettingED").on("change", () => {
    if ($("#editEmptyazSettingED").is(":checked")) {
        $("#settingsED").prop("checked", true);
        $("#baseInfoED").prop("checked", true);
        $("#emptyazSettingED").prop("checked", true);
        $("#editEmptyazSettingED").prop("checked", true);
        $("#seeEmtyazSettingED").prop("checked", true);

    } else {
        $("#editEmptyazSettingED").prop("checked", false);
        $("#deleteEmtyazSettingED").prop("checked", false);
    }
})

$("#seeEmtyazSettingED").on("change", () => {
    if ($("#seeEmtyazSettingED").is(":checked")) {
        $("#baseInfoED").prop("checked", true);
        $("#settingsED").prop("checked", true);
        $("#emptyazSettingED").prop("checked", true);
        $("#seeEmtyazSettingED").prop("checked", true);

    } else {
        $("#emptyazSettingED").prop("checked", false);
        if (!$(".allSettingsED").is(":checked")) {
            $("#settingsED").prop("checked", false);
            $("#baseInfoED").prop("checked", false);
        } else {
            $("#settingsED").prop("checked", true);
            $("#baseInfoED").prop("checked", true);
        }

        $("#editEmptyazSettingED").prop("checked", false);
        $("#deleteEmtyazSettingED").prop("checked", false);

    }
})



// تعریف عناصر 

$("#defineElementED").on("change", () => {
    if ($("#defineElementED").is(":checked")) {
        $("#karbaranED").prop("checked", true);
        $("#customersED").prop("checked", true);
        $("#seeCustomersED").prop("checked", true);
    } else {
        $("#karbaranED").prop("checked", false);
        $("#customersED").prop("checked", false);
        $("#seeCustomersED").prop("checked", false);
        $("#deleteCustomersED").prop("checked", false);
        $("#editCustomerED").prop("checked", false);
    }
})


$("#karbaranED").on("change", () => {
    if ($("#karbaranED").is(":checked")) {
        $("#defineElementED").prop("checked", true);
        $("#customersED").prop("checked", true);
        $("#seeCustomersED").prop("checked", true);
    } else {
        $("#defineElementED").prop("checked", false);
        $("#customersED").prop("checked", false);
        $("#seeCustomersED").prop("checked", false);
        $("#deleteCustomersED").prop("checked", false);
        $("#editCustomerED").prop("checked", false);
    }
})

$("#customersED").on("change", () => {
    if ($("#customersED").is(":checked")) {
        $("#defineElementED").prop("checked", true);
        $("#customersED").prop("checked", true);
        $("#seeCustomersED").prop("checked", true);
        $("#karbaranED").prop("checked", true);
    } else {
        $("#defineElementED").prop("checked", false);
        $("#customersED").prop("checked", false);
        $("#seeCustomersED").prop("checked", false);
        $("#deleteCustomersED").prop("checked", false);
        $("#editCustomerED").prop("checked", false);
        $("#karbaranED").prop("checked", false);
    }
})

$("#seeCustomersED").on("change", () => {
    if ($("#seeCustomersED").is(":checked")) {
        $("#defineElementED").prop("checked", true);
        $("#customersED").prop("checked", true);
        $("#seeCustomersED").prop("checked", true);
        $("#karbaranED").prop("checked", true);
    } else {
        $("#defineElementED").prop("checked", false);
        $("#customersED").prop("checked", false);
        $("#seeCustomersED").prop("checked", false);
        $("#deleteCustomersED").prop("checked", false);
        $("#editCustomerED").prop("checked", false);
        $("#karbaranED").prop("checked", false);
    }
})

$("#seeCustomersED").on("change", () => {
    if ($("#seeCustomersED").is(":checked")) {
        $("#defineElementED").prop("checked", true);
        $("#customersED").prop("checked", true);
        $("#seeCustomersED").prop("checked", true);
        $("#karbaranED").prop("checked", true);

    } else {
        $("#defineElementED").prop("checked", false);
        $("#customersED").prop("checked", false);
        $("#seeCustomersED").prop("checked", false);
        $("#deleteCustomersED").prop("checked", false);
        $("#editCustomerED").prop("checked", false);
        $("#karbaranED").prop("checked", false);

    }

})

$("#editCustomerED").on("change", () => {
    if ($("#editCustomerED").is(":checked")) {
        $("#defineElementED").prop("checked", true);
        $("#customersED").prop("checked", true);
        $("#seeCustomersED").prop("checked", true);
        $("#karbaranED").prop("checked", true);
    } else {
        $("#deleteCustomersED").prop("checked", false);
    }
})

$("#deleteCustomersED").on("change", () => {
    if ($("#deleteCustomersED").is(":checked")) {
        $("#defineElementED").prop("checked", true);
        $("#customersED").prop("checked", true);
        $("#seeCustomersED").prop("checked", true);
        $("#karbaranED").prop("checked", true);
        $("#editCustomerED").prop("checked", true);
    } else {
        $("#deleteCustomersED").prop("checked", false);
    }

})



// عملیات 

$("#operationED").on("change", () => {
    if ($("#operationED").is(":checked")) {
        $("#kalasED").prop("checked", true);
        $("#kalaListsED").prop("checked", true);
        $("#seeKalaListED").prop("checked", true);
        $("#requestedKalaED").prop("checked", true);
        $("#seeRequestedKalaED").prop("checked", true);
        $("#fastKalaED").prop("checked", true);
        $("#seeFastKalaED").prop("checked", true);
        $("#pishKharidED").prop("checked", true);
        $("#seePishKharidED").prop("checked", true);
        $("#brandsED").prop("checked", true);
        $("#seeBrandsED").prop("checked", true);
        $("#alertedED").prop("checked", true);
        $("#seeAlertedED").prop("checked", true);
        $("#kalaGroupED").prop("checked", true);
        $("#seeKalaGroup").prop("checked", true);
        $("#orderSalesED").prop("checked", true);
        $("#seeSalesOrderED").prop("checked", true);
        $("#messageED").prop("checked", true);
        $("#seeMessageED").prop("checked", true);
        $("#seeKalaGroupED").prop("checked", true);

    } else {
        $("#kalasED").prop("checked", false);
        $("#kalaListsED").prop("checked", false);
        $("#seeKalaListED").prop("checked", false);
        $("#requestedKalaED").prop("checked", false);
        $("#seeRequestedKalaED").prop("checked", false);
        $("#fastKalaED").prop("checked", false);
        $("#seeFastKalaED").prop("checked", false);
        $("#pishKharidED").prop("checked", false);
        $("#seePishKharidED").prop("checked", false);
        $("#brandsED").prop("checked", false);
        $("#seeBrandsED").prop("checked", false);
        $("#alertedED").prop("checked", false);
        $("#seeAlertedED").prop("checked", false);
        $("#kalaGroupED").prop("checked", false);
        $("#seeKalaGroup").prop("checked", false);
        $("#orderSalesED").prop("checked", false);
        $("#seeSalesOrderED").prop("checked", false);
        $("#messageED").prop("checked", false);
        $("#seeMessageED").prop("checked", false);
        $("#seeKalaListED").prop("checked", false);
        $("#editKalaListED").prop("checked", false);
        $("#deleteKalaListED").prop("checked", false);
        $("#editMessageED").prop("checked", false);
        $("#deleteMessageED").prop("checked", false);
        $("#editRequestedKalaED").prop("checked", false);
        $("#deleteRequestedKalaED").prop("checked", false);
        $("#editFastKalaED").prop("checked", false);
        $("#deleteFastKalaED").prop("checked", false);
        $("#editFastKalaED").prop("checked", false);
        $("#deleteFastKalaED").prop("checked", false);
        $("#editPishkharidED").prop("checked", false);
        $("#deletePishKharidED").prop("checked", false);
        $("#editBrandED").prop("checked", false);
        $("#deleteBrandsED").prop("checked", false);
        $("#editAlertedED").prop("checked", false);
        $("#deleteAlertedED").prop("checked", false);
        $("#editKalaGroupED").prop("checked", false);
        $("#deletKalaGroupED").prop("checked", false);
        $("#seeKalaGroupED").prop("checked", false);
        $("#editOrderSalesED").prop("checked", false);
        $("#deleteOrderSalesED").prop("checked", false);
    }
})


$("#kalasED").on("change", () => {
    if ($("#kalasED").is(":checked")) {
        $("#operationED").prop("checked", true);
        $("#kalaListsED").prop("checked", true);
        $("#seeKalaListED").prop("checked", true);
        $("#requestedKalaED").prop("checked", true);
        $("#seeRequestedKalaED").prop("checked", true);
        $("#fastKalaED").prop("checked", true);
        $("#seeFastKalaED").prop("checked", true);
        $("#pishKharidED").prop("checked", true);
        $("#seePishKharidED").prop("checked", true);
        $("#brandsED").prop("checked", true);
        $("#seeBrandsED").prop("checked", true);
        $("#alertedED").prop("checked", true);
        $("#seeAlertedED").prop("checked", true);
        $("#kalaGroupED").prop("checked", true);
        $("#seeKalaGroup").prop("checked", true);
        $("#orderSalesED").prop("checked", true);
        $("#seeSalesOrderED").prop("checked", true);
        $("#messageED").prop("checked", true);
        $("#seeMessageED").prop("checked", true);

    } else {
        $("#operationED").prop("checked", false);
        $("#kalasED").prop("checked", false);
        $("#kalaListsED").prop("checked", false);
        $("#seeKalaListED").prop("checked", false);
        $("#requestedKalaED").prop("checked", false);
        $("#seeRequestedKalaED").prop("checked", false);
        $("#fastKalaED").prop("checked", false);
        $("#seeFastKalaED").prop("checked", false);
        $("#pishKharidED").prop("checked", false);
        $("#seePishKharidED").prop("checked", false);
        $("#brandsED").prop("checked", false);
        $("#seeBrandsED").prop("checked", false);
        $("#alertedED").prop("checked", false);
        $("#seeAlertedED").prop("checked", false);
        $("#kalaGroupED").prop("checked", false);
        $("#seeKalaGroup").prop("checked", false);
        $("#orderSalesED").prop("checked", false);
        $("#seeSalesOrderED").prop("checked", false);
        $("#messageED").prop("checked", false);
        $("#seeMessageED").prop("checked", false);
        $("#seeKalaListED").prop("checked", false);
        $("#editKalaListED").prop("checked", false);
        $("#deleteKalaListED").prop("checked", false);
        $("#editMessageED").prop("checked", false);
        $("#deleteMessageED").prop("checked", false);
        $("#editRequestedKalaED").prop("checked", false);
        $("#deleteRequestedKalaED").prop("checked", false);
        $("#editFastKalaED").prop("checked", false);
        $("#deleteFastKalaED").prop("checked", false);
        $("#editFastKalaED").prop("checked", false);
        $("#deleteFastKalaED").prop("checked", false);
        $("#editPishkharidED").prop("checked", false);
        $("#deletePishKharidED").prop("checked", false);
        $("#editBrandED").prop("checked", false);
        $("#deleteBrandsED").prop("checked", false);
        $("#editAlertedED").prop("checked", false);
        $("#deleteAlertedED").prop("checked", false);
        $("#editKalaGroupED").prop("checked", false);
        $("#deletKalaGroupED").prop("checked", false);
        $("#seeKalaGroupED").prop("checked", false);
        $("#editOrderSalesED").prop("checked", false);
        $("#deleteOrderSalesED").prop("checked", false);
    }
})


$("#kalaListsED").on("change", () => {
    if ($("#kalaListsED").is(":checked")) {
        $("#seeKalaListED").prop("checked", true);
        $("#operationED").prop("checked", true);
        $("#kalasED").prop("checked", true);

    } else {
        $("#kalaListsED").prop("checked", false);
        if (!$(".kalaElementED").is(":checked")) {
            $("#operationED").prop("checked", false);
            $("#kalasED").prop("checked", false);
        } else {
            $("#operationED").prop("checked", true);
            $("#kalasED").prop("checked", true);
        }

        $("#seeKalaListED").prop("checked", false);
        $("#editKalaListED").prop("checked", false);
        $("#deleteKalaListED").prop("checked", false);

    }
})

$("#deleteKalaListED").on("change", () => {
    if ($("#deleteKalaListED").is(":checked")) {
        $("#operationED").prop("checked", true);
        $("#kalasED").prop("checked", true);
        $("#kalaListsED").prop("checked", true);
        $("#seeKalaListED").prop("checked", true);
        $("#editKalaListED").prop("checked", true);

    } else {
        $("#deleteKalaListED").prop("checked", false);
        if (!$(".kalaElementED").is(":checked")) {
            $("#operationED").prop("checked", false);
            $("#kalasED").prop("checked", false);
        } else {
            $("#operationED").prop("checked", true);
            $("#kalasED").prop("checked", true);
        }

    }
})

$("#editKalaListED").on("change", () => {
    if ($("#editKalaListED").is(":checked")) {
        $("#operationED").prop("checked", true);
        $("#kalasED").prop("checked", true);
        $("#kalaListsED").prop("checked", true);
        $("#seeKalaListED").prop("checked", true);

    } else {
        $("#editKalaListED").prop("checked", false);
        if (!$(".kalaElementED").is(":checked")) {
            $("#operationED").prop("checked", false);
            $("#kalasED").prop("checked", false);
        } else {
            $("#operationED").prop("checked", true);
            $("#kalasED").prop("checked", true);
        }
        $("#deleteKalaListED").prop("checked", false);
    }
})

$("#seeKalaListED").on("change", () => {
    if ($("#seeKalaListED").is(":checked")) {
        $("#operationED").prop("checked", true);
        $("#kalasED").prop("checked", true);
        $("#kalaListsED").prop("checked", true);
    } else {

        $("#kalaListsED").prop("checked", false);

        if (!$(".kalaElementED").is(":checked")) {
            $("#operationED").prop("checked", false);
            $("#kalasED").prop("checked", false);
        } else {
            $("#operationED").prop("checked", true);
            $("#kalasED").prop("checked", true);
        }

        $("#deleteKalaListED").prop("checked", false);
        $("#editKalaListED").prop("checked", false);
    }
})


$("#requestedKalaED").on("change", () => {


})

$("#requestedKalaED").on("change", () => {
    if ($("#requestedKalaED").is(":checked")) {
        $("#operationED").prop("checked", true);
        $("#kalasED").prop("checked", true);
        $("#seeRequestedKalaED").prop("checked", true);

    } else {
        $("#requestedKalaED").prop("checked", false);

        if (!$(".kalaElementED").is(":checked")) {
            $("#operationED").prop("checked", false);
            $("#kalasED").prop("checked", false);
        } else {
            $("#operationED").prop("checked", true);
            $("#kalasED").prop("checked", true);
        }

        $("#seeRequestedKalaED").prop("checked", false);
        $("#editRequestedKalaED").prop("checked", false);
        $("#deleteRequestedKalaED").prop("checked", false);
    }
})


$("#deleteRequestedKalaED").on("change", () => {
    if ($("#deleteRequestedKalaED").is(":checked")) {
        $("#operationED").prop("checked", true);
        $("#kalasED").prop("checked", true);
        $("#requestedKalaED").prop("checked", true);
        $("#seeRequestedKalaED").prop("checked", true);
        $("#editRequestedKalaED").prop("checked", true);

    } else {

        if (!$(".kalaElementED").is(":checked")) {
            $("#operationED").prop("checked", false);
            $("#kalasED").prop("checked", false);
        } else {
            $("#operationED").prop("checked", true);
            $("#kalasED").prop("checked", true);
        }
        $("#deleteRequestedKalaED").prop("checked", false);
    }
})


$("#editRequestedKalaED").on("change", () => {
    if ($("#editRequestedKalaED").is(":checked")) {
        $("#operationED").prop("checked", true);
        $("#kalasED").prop("checked", true);
        $("#requestedKalaED").prop("checked", true);
        $("#seeRequestedKalaED").prop("checked", true);

    } else {

        if (!$(".kalaElementED").is(":checked")) {
            $("#operationED").prop("checked", false);
            $("#kalasED").prop("checked", false);
        } else {
            $("#operationED").prop("checked", true);
            $("#kalasED").prop("checked", true);
        }
        $("#editRequestedKalaED").prop("checked", false);
        $("#deleteRequestedKalaED").prop("checked", false);
    }
})


$("#seeRequestedKalaED").on("change", () => {
    if ($("#seeRequestedKalaED").is(":checked")) {
        $("#operationED").prop("checked", true);
        $("#kalasED").prop("checked", true);
        $("#requestedKalaED").prop("checked", true);

    } else {
        $("#requestedKalaED").prop("checked", false);
        if (!$(".kalaElementED").is(":checked")) {
            $("#operationED").prop("checked", false);
            $("#kalasED").prop("checked", false);
        } else {
            $("#operationED").prop("checked", true);
            $("#kalasED").prop("checked", true);
        }
        $("#seeRequestedKalaED").prop("checked", false);
        $("#editRequestedKalaED").prop("checked", false);
        $("#deleteRequestedKalaED").prop("checked", false);
    }
})



$("#fastKalaED").on("change", () => {
    if ($("#fastKalaED").is(":checked")) {
        $("#operationED").prop("checked", true);
        $("#kalasED").prop("checked", true);
        $("#seeFastKalaED").prop("checked", true);

    } else {
        $("#fastKalaED").prop("checked", false);

        if (!$(".kalaElementED").is(":checked")) {
            $("#operationED").prop("checked", false);
            $("#kalasED").prop("checked", false);
        } else {
            $("#operationED").prop("checked", true);
            $("#kalasED").prop("checked", true);
        }

        $("#seeFastKalaED").prop("checked", false);
        $("#editFastKalaED").prop("checked", false);
        $("#deleteFastKalaED").prop("checked", false);
    }
})



$("#deleteFastKalaED").on("change", () => {
    if ($("#deleteFastKalaED").is(":checked")) {
        $("#operationED").prop("checked", true);
        $("#kalasED").prop("checked", true);
        $("#fastKalaED").prop("checked", true);
        $("#seeFastKalaED").prop("checked", true);
        $("#editFastKalaED").prop("checked", true);

    } else {
        if (!$(".kalaElementED").is(":checked")) {
            $("#operationED").prop("checked", false);
            $("#kalasED").prop("checked", false);
        } else {
            $("#operationED").prop("checked", true);
            $("#kalasED").prop("checked", true);
        }
        $("#deleteFastKalaED").prop("checked", false);
    }
})


$("#editFastKalaED").on("change", () => {
    if ($("#editFastKalaED").is(":checked")) {
        $("#operationED").prop("checked", true);
        $("#kalasED").prop("checked", true);
        $("#fastKalaED").prop("checked", true);
        $("#seeFastKalaED").prop("checked", true);

    } else {

        if (!$(".kalaElementED").is(":checked")) {
            $("#operationED").prop("checked", false);
            $("#kalasED").prop("checked", false);
        } else {
            $("#operationED").prop("checked", true);
            $("#kalasED").prop("checked", true);
        }
        $("#editFastKalaED").prop("checked", false);
        $("#deleteFastKalaED").prop("checked", false);
    }
})


$("#seeFastKalaED").on("change", () => {
    if ($("#seeFastKalaED").is(":checked")) {
        $("#operationED").prop("checked", true);
        $("#kalasED").prop("checked", true);
        $("#fastKalaED").prop("checked", true);

    } else {
        $("#fastKalaED").prop("checked", false);

        if (!$(".kalaElementED").is(":checked")) {
            $("#operationED").prop("checked", false);
            $("#kalasED").prop("checked", false);
        } else {
            $("#operationED").prop("checked", true);
            $("#kalasED").prop("checked", true);
        }
        $("#seeFastKalaED").prop("checked", false);
        $("#editFastKalaED").prop("checked", false);
        $("#deleteFastKalaED").prop("checked", false);
    }
})


$("#pishKharidED").on("change", () => {
    if ($("#pishKharidED").is(":checked")) {
        $("#operationED").prop("checked", true);
        $("#kalasED").prop("checked", true);
        $("#seePishKharidED").prop("checked", true);

    } else {
        $("#pishKharidED").prop("checked", false);

        if (!$(".kalaElementED").is(":checked")) {
            $("#operationED").prop("checked", false);
            $("#kalasED").prop("checked", false);
        } else {
            $("#operationED").prop("checked", true);
            $("#kalasED").prop("checked", true);
        }

        $("#seePishKharidED").prop("checked", false);
        $("#editPishkharidED").prop("checked", false);
        $("#deletePishKharidED").prop("checked", false);
    }
})


$("#deletePishKharidED").on("change", () => {
    if ($("#deletePishKharidED").is(":checked")) {
        $("#operationED").prop("checked", true);
        $("#kalasED").prop("checked", true);
        $("#pishKharidED").prop("checked", true);
        $("#seePishKharidED").prop("checked", true);
        $("#editPishkharidED").prop("checked", true);

    } else {
        if (!$(".kalaElementED").is(":checked")) {
            $("#operationED").prop("checked", false);
            $("#kalasED").prop("checked", false);
        } else {
            $("#operationED").prop("checked", true);
            $("#kalasED").prop("checked", true);
        }
        $("#deletePishKharidED").prop("checked", false);
    }
})


$("#editPishkharidED").on("change", () => {
    if ($("#editPishkharidED").is(":checked")) {
        $("#operationED").prop("checked", true);
        $("#kalasED").prop("checked", true);
        $("#pishKharidED").prop("checked", true);
        $("#seePishKharidED").prop("checked", true);

    } else {

        if (!$(".kalaElementED").is(":checked")) {
            $("#operationED").prop("checked", false);
            $("#kalasED").prop("checked", false);
        } else {
            $("#operationED").prop("checked", true);
            $("#kalasED").prop("checked", true);
        }
        $("#editPishkharidED").prop("checked", false);
        $("#deletePishKharidED").prop("checked", false);
    }
})


$("#seePishKharidED").on("change", () => {
    if ($("#seePishKharidED").is(":checked")) {
        $("#operationED").prop("checked", true);
        $("#kalasED").prop("checked", true);
        $("#pishKharidED").prop("checked", true);

    } else {
        $("#pishKharidED").prop("checked", false);

        if (!$(".kalaElementED").is(":checked")) {
            $("#operationED").prop("checked", false);
            $("#kalasED").prop("checked", false);
        } else {
            $("#operationED").prop("checked", true);
            $("#kalasED").prop("checked", true);
        }
        $("#seePishKharidED").prop("checked", false);
        $("#editPishkharidED").prop("checked", false);
        $("#deletePishKharidED").prop("checked", false);
    }
})



$("#brandsED").on("change", () => {
    if ($("#brandsED").is(":checked")) {
        $("#operationED").prop("checked", true);
        $("#kalasED").prop("checked", true);
        $("#seeBrandsED").prop("checked", true);

    } else {
        $("#brandsED").prop("checked", false);

        if (!$(".kalaElementED").is(":checked")) {
            $("#operationED").prop("checked", false);
            $("#kalasED").prop("checked", false);
        } else {
            $("#operationED").prop("checked", true);
            $("#kalasED").prop("checked", true);
        }

        $("#seeBrandsED").prop("checked", false);
        $("#editBrandED").prop("checked", false);
        $("#deleteBrandsED").prop("checked", false);
    }
})


$("#deleteBrandsED").on("change", () => {
    if ($("#deleteBrandsED").is(":checked")) {
        $("#operationED").prop("checked", true);
        $("#kalasED").prop("checked", true);
        $("#brandsED").prop("checked", true);
        $("#seeBrandsED").prop("checked", true);
        $("#editBrandED").prop("checked", true);
    } else {

        if (!$(".kalaElementED").is(":checked")) {
            $("#operationED").prop("checked", false);
            $("#kalasED").prop("checked", false);
        } else {
            $("#operationED").prop("checked", true);
            $("#kalasED").prop("checked", true);
        }

        $("#deleteBrandsED").prop("checked", false);
    }
})

$("#editBrandED").on("change", () => {
    if ($("#editBrandED").is(":checked")) {
        $("#operationED").prop("checked", true);
        $("#kalasED").prop("checked", true);
        $("#brandsED").prop("checked", true);
        $("#seeBrandsED").prop("checked", true);

    } else {

        if (!$(".kalaElementED").is(":checked")) {
            $("#operationED").prop("checked", false);
            $("#kalasED").prop("checked", false);
        } else {
            $("#operationED").prop("checked", true);
            $("#kalasED").prop("checked", true);
        }
        $("#editBrandED").prop("checked", false);
        $("#deleteBrandsED").prop("checked", false);
    }
})


$("#seeBrandsED").on("change", () => {
    if ($("#seeBrandsED").is(":checked")) {
        $("#operationED").prop("checked", true);
        $("#kalasED").prop("checked", true);
        $("#brandsED").prop("checked", true);

    } else {
        $("#brandsED").prop("checked", false);

        if (!$(".kalaElementED").is(":checked")) {
            $("#operationED").prop("checked", false);
            $("#kalasED").prop("checked", false);
        } else {
            $("#operationED").prop("checked", true);
            $("#kalasED").prop("checked", true);
        }
        $("#seeBrandsED").prop("checked", false);
        $("#editBrandED").prop("checked", false);
        $("#deleteBrandsED").prop("checked", false);
    }
})



$("#alertedED").on("change", () => {
    if ($("#alertedED").is(":checked")) {
        $("#operationED").prop("checked", true);
        $("#kalasED").prop("checked", true);
        $("#seeAlertedED").prop("checked", true);

    } else {
        $("#alertedED").prop("checked", false);

        if (!$(".kalaElementED").is(":checked")) {
            $("#operationED").prop("checked", false);
            $("#kalasED").prop("checked", false);
        } else {
            $("#operationED").prop("checked", true);
            $("#kalasED").prop("checked", true);
        }

        $("#seeAlertedED").prop("checked", false);
        $("#editAlertedED").prop("checked", false);
        $("#deleteAlertedED").prop("checked", false);
    }
})


$("#deleteAlertedED").on("change", () => {
    if ($("#deleteAlertedED").is(":checked")) {
        $("#operationED").prop("checked", true);
        $("#kalasED").prop("checked", true);
        $("#alertedED").prop("checked", true);
        $("#seeAlertedED").prop("checked", true);
        $("#editAlertedED").prop("checked", true);
    } else {

        if (!$(".kalaElementED").is(":checked")) {
            $("#operationED").prop("checked", false);
            $("#kalasED").prop("checked", false);
        } else {
            $("#operationED").prop("checked", true);
            $("#kalasED").prop("checked", true);
        }

        $("#deleteAlertedED").prop("checked", false);
    }
})

$("#editAlertedED").on("change", () => {
    if ($("#editAlertedED").is(":checked")) {
        $("#operationED").prop("checked", true);
        $("#kalasED").prop("checked", true);
        $("#alertedED").prop("checked", true);
        $("#seeAlertedED").prop("checked", true);

    } else {

        if (!$(".kalaElementED").is(":checked")) {
            $("#operationED").prop("checked", false);
            $("#kalasED").prop("checked", false);
        } else {
            $("#operationED").prop("checked", true);
            $("#kalasED").prop("checked", true);
        }
        $("#editAlertedED").prop("checked", false);
        $("#deleteAlertedED").prop("checked", false);
    }
})


$("#seeAlertedED").on("change", () => {
    if ($("#seeAlertedED").is(":checked")) {
        $("#operationED").prop("checked", true);
        $("#kalasED").prop("checked", true);
        $("#alertedED").prop("checked", true);

    } else {
        $("#alertedED").prop("checked", false);

        if (!$(".kalaElementED").is(":checked")) {
            $("#operationED").prop("checked", false);
            $("#kalasED").prop("checked", false);
        } else {
            $("#operationED").prop("checked", true);
            $("#kalasED").prop("checked", true);
        }
        $("#seeAlertedED").prop("checked", false);
        $("#editAlertedED").prop("checked", false);
        $("#deleteAlertedED").prop("checked", false);
    }
})




$("#kalaGroupED").on("change", () => {
    if ($("#kalaGroupED").is(":checked")) {
        $("#operationED").prop("checked", true);
        $("#kalasED").prop("checked", true);
        $("#seeKalaGroupED").prop("checked", true);

    } else {
        $("#kalaGroupED").prop("checked", false);

        if (!$(".kalaElementED").is(":checked")) {
            $("#operationED").prop("checked", false);
            $("#kalasED").prop("checked", false);
        } else {
            $("#operationED").prop("checked", true);
            $("#kalasED").prop("checked", true);
        }

        $("#seeKalaGroupED").prop("checked", false);
        $("#editKalaGroupED").prop("checked", false);
        $("#deletKalaGroupED").prop("checked", false);
    }
})


$("#deletKalaGroupED").on("change", () => {
    if ($("#deletKalaGroupED").is(":checked")) {
        $("#operationED").prop("checked", true);
        $("#kalasED").prop("checked", true);
        $("#kalaGroupED").prop("checked", true);
        $("#seeKalaGroupED").prop("checked", true);
        $("#editKalaGroupED").prop("checked", true);
    } else {

        if (!$(".kalaElementED").is(":checked")) {
            $("#operationED").prop("checked", false);
            $("#kalasED").prop("checked", false);
        } else {
            $("#operationED").prop("checked", true);
            $("#kalasED").prop("checked", true);
        }

        $("#deletKalaGroupED").prop("checked", false);
    }
})

$("#editKalaGroupED").on("change", () => {
    if ($("#editKalaGroupED").is(":checked")) {
        $("#operationED").prop("checked", true);
        $("#kalasED").prop("checked", true);
        $("#kalaGroupED").prop("checked", true);
        $("#seeKalaGroupED").prop("checked", true);

    } else {

        if (!$(".kalaElementED").is(":checked")) {
            $("#operationED").prop("checked", false);
            $("#kalasED").prop("checked", false);
        } else {
            $("#operationED").prop("checked", true);
            $("#kalasED").prop("checked", true);
        }
        $("#editKalaGroupED").prop("checked", false);
        $("#deletKalaGroupED").prop("checked", false);
    }
})


$("#seeKalaGroupED").on("change", () => {
    if ($("#seeKalaGroupED").is(":checked")) {
        $("#operationED").prop("checked", true);
        $("#kalasED").prop("checked", true);
        $("#kalaGroupED").prop("checked", true);

    } else {
        $("#kalaGroupED").prop("checked", false);

        if (!$(".kalaElementED").is(":checked")) {
            $("#operationED").prop("checked", false);
            $("#kalasED").prop("checked", false);
        } else {
            $("#operationED").prop("checked", true);
            $("#kalasED").prop("checked", true);
        }
        $("#seeKalaGroupED").prop("checked", false);
        $("#editKalaGroupED").prop("checked", false);
        $("#deletKalaGroupED").prop("checked", false);
    }
})



$("#orderSalesED").on("change", () => {
    if ($("#orderSalesED").is(":checked")) {
        $("#operationED").prop("checked", true);
        $("#kalasED").prop("checked", true);
        $("#seeSalesOrderED").prop("checked", true);

    } else {
        $("#orderSalesED").prop("checked", false);

        if (!$(".kalaElementED").is(":checked")) {
            $("#operationED").prop("checked", false);
            $("#kalasED").prop("checked", false);
        } else {
            $("#operationED").prop("checked", true);
            $("#kalasED").prop("checked", true);
        }

        $("#seeSalesOrderED").prop("checked", false);
        $("#editOrderSalesED").prop("checked", false);
        $("#deleteOrderSalesED").prop("checked", false);
    }
})


$("#deleteOrderSalesED").on("change", () => {
    if ($("#deleteOrderSalesED").is(":checked")) {
        $("#operationED").prop("checked", true);
        $("#kalasED").prop("checked", true);
        $("#orderSalesED").prop("checked", true);
        $("#seeSalesOrderED").prop("checked", true);
        $("#editOrderSalesED").prop("checked", true);
    } else {

        if (!$(".kalaElementED").is(":checked")) {
            $("#operationED").prop("checked", false);
            $("#kalasED").prop("checked", false);
        } else {
            $("#operationED").prop("checked", true);
            $("#kalasED").prop("checked", true);
        }
        $("#deleteOrderSalesED").prop("checked", false);
    }
})

$("#editOrderSalesED").on("change", () => {
    if ($("#editOrderSalesED").is(":checked")) {
        $("#operationED").prop("checked", true);
        $("#kalasED").prop("checked", true);
        $("#orderSalesED").prop("checked", true);
        $("#seeSalesOrderED").prop("checked", true);

    } else {

        if (!$(".kalaElementED").is(":checked")) {
            $("#operationED").prop("checked", false);
            $("#kalasED").prop("checked", false);
        } else {
            $("#operationED").prop("checked", true);
            $("#kalasED").prop("checked", true);
        }
        $("#editOrderSalesED").prop("checked", false);
        $("#deleteOrderSalesED").prop("checked", false);
    }
})

$("#seeSalesOrderED").on("change", () => {
    if ($("#seeSalesOrderED").is(":checked")) {
        $("#operationED").prop("checked", true);
        $("#kalasED").prop("checked", true);
        $("#orderSalesED").prop("checked", true);

    } else {
        $("#orderSalesED").prop("checked", false);

        if (!$(".kalaElementED").is(":checked")) {
            $("#operationED").prop("checked", false);
            $("#kalasED").prop("checked", false);
        } else {
            $("#operationED").prop("checked", true);
            $("#kalasED").prop("checked", true);
        }
        $("#seeSalesOrderED").prop("checked", false);
        $("#editOrderSalesED").prop("checked", false);
        $("#deleteOrderSalesED").prop("checked", false);
    }
})













$("#messageED").on("change", () => {
    if ($("#messageED").is(":checked")) {
        $("#operationED").prop("checked", true);
        $("#kalasED").prop("checked", true);
        $("#seeMessageED").prop("checked", true);

    } else {
        $("#messageED").prop("checked", false);

        if (!$(".kalaElementED").is(":checked")) {
            $("#operationED").prop("checked", false);
            $("#kalasED").prop("checked", false);
        } else {
            $("#operationED").prop("checked", true);
            $("#kalasED").prop("checked", true);
        }

        $("#seeMessageED").prop("checked", false);
        $("#editMessageED").prop("checked", false);
        $("#deleteMessageED").prop("checked", false);
    }
})


$("#deleteMessageED").on("change", () => {
    if ($("#deleteMessageED").is(":checked")) {
        $("#operationED").prop("checked", true);
        $("#kalasED").prop("checked", true);
        $("#messageED").prop("checked", true);
        $("#seeMessageED").prop("checked", true);
        $("#editMessageED").prop("checked", true);
    } else {

        if (!$(".kalaElementED").is(":checked")) {
            $("#operationED").prop("checked", false);
            $("#kalasED").prop("checked", false);
        } else {
            $("#operationED").prop("checked", true);
            $("#kalasED").prop("checked", true);
        }

        $("#deleteMessageED").prop("checked", false);
    }
})

$("#editMessageED").on("change", () => {
    if ($("#editMessageED").is(":checked")) {
        $("#operationED").prop("checked", true);
        $("#kalasED").prop("checked", true);
        $("#messageED").prop("checked", true);
        $("#seeMessageED").prop("checked", true);

    } else {

        if (!$(".kalaElementED").is(":checked")) {
            $("#operationED").prop("checked", false);
            $("#kalasED").prop("checked", false);
        } else {
            $("#operationED").prop("checked", true);
            $("#kalasED").prop("checked", true);
        }
        $("#editMessageED").prop("checked", false);
        $("#deleteMessageED").prop("checked", false);
    }
})


$("#seeMessageED").on("change", () => {
    if ($("#seeMessageED").is(":checked")) {
        $("#operationED").prop("checked", true);
        $("#kalasED").prop("checked", true);
        $("#messageED").prop("checked", true);

    } else {
        $("#messageED").prop("checked", false);

        if (!$(".kalaElementED").is(":checked")) {
            $("#operationED").prop("checked", false);
            $("#kalasED").prop("checked", false);
        } else {
            $("#operationED").prop("checked", true);
            $("#kalasED").prop("checked", true);
        }
        $("#seeMessageED").prop("checked", false);
        $("#editMessageED").prop("checked", false);
        $("#deleteMessageED").prop("checked", false);
    }
})



// گزارشات 

$("#reportED").on("change", () => {
    if ($("#reportED").is(":checked")) {
        $("#reportCustomerED").prop("checked", true);
        $("#cutomerListED").prop("checked", true);
        $("#seeCustomerListED").prop("checked", true);
        $("#officialCustomerED").prop("checked", true);
        $("#seeOfficialCustomerED").prop("checked", true);
        $("#gameAndLotteryED").prop("checked", true);
        $("#lotteryResultED").prop("checked", true);
        $("#seeLotteryResultED").prop("checked", true);
        $("#gamerListED").prop("checked", true);
        $("#seeGamerListED").prop("checked", true);
        $("#onlinePaymentED").prop("checked", true);
        $("#seeOnlinePaymentED").prop("checked", true);
    } else {
        $("#reportCustomerED").prop("checked", false);
        $("#cutomerListED").prop("checked", false);
        $("#seeCustomerListED").prop("checked", false);
        $("#officialCustomerED").prop("checked", false);
        $("#officialCustomerED").change();
        $("#seeOfficialCustomerED").prop("checked", false);
        $("#gameAndLotteryED").prop("checked", false);
        $("#lotteryResultED").prop("checked", false);
        $("#lotteryResultED").change();
        $("#seeLotteryResultED").prop("checked", false);
        $("#gamerListED").prop("checked", false);
        $("#gamerListED").change();
        $("#seeGamerListED").prop("checked", false);
        $("#onlinePaymentED").prop("checked", false);
        $("#onlinePaymentED").change();
        $("#seeOnlinePaymentED").prop("checked", false);
        $("#editCustomerListED").prop("checked", false);
        $("#deletCustomerListED").prop("checked", false);

    }
})


$("#reportCustomerED").on("change", () => {
    if ($("#reportCustomerED").is(":checked")) {
        $("#reportED").prop("checked", true);
        $("#cutomerListED").prop("checked", true);
        $("#seeCustomerListED").prop("checked", true);
        $("#officialCustomerED").prop("checked", true);
        $("#seeOfficialCustomerED").prop("checked", true);

    } else {
        $("#reportCustomerED").prop("checked", false);
        if (!$(".reportElementED").is(":checked")) {
            $("#reportED").prop("checked", false);
        } else {
            $("#reportED").prop("checked", true);
        }

        $("#cutomerListED").prop("checked", false);
        $("#seeCustomerListED").prop("checked", false);
        $("#officialCustomerED").prop("checked", false);
        $("#seeOfficialCustomerED").prop("checked", false);
        $("#editCustomerListED").prop("checked", false);
        $("#deletCustomerListED").prop("checked", false);
        $("#deleteOfficialCustomerED").prop("checked", false);
        $("#editOfficialCustomerED").prop("checked", false);

    }
})


$("#cutomerListED").on("change", () => {
    if ($("#cutomerListED").is(":checked")) {
        $("#reportED").prop("checked", true);
        $("#reportCustomerED").prop("checked", true);
        $("#seeCustomerListED").prop("checked", true);

    } else {

        $("#cutomerListED").prop("checked", false);

        if (!$(".cutomerListED").is(":checked")) {
            $("#reportCustomerED").prop("checked", false);
            $("#reportCustomerED").change();

        } else {
            $("#reportED").prop("checked", true);
            $("#reportCustomerED").prop("checked", true);

        }

        $("#seeCustomerListED").prop("checked", false);
        $("#editCustomerListED").prop("checked", false);
        $("#deletCustomerListED").prop("checked", false);


    }
})



$("#deletCustomerListED").on("change", () => {
    if ($("#deletCustomerListED").is(":checked")) {
        $("#reportED").prop("checked", true);
        $("#cutomerListED").prop("checked", true);
        $("#seeCustomerListED").prop("checked", true);
        $("#editCustomerListED").prop("checked", true);
        $("#reportCustomerED").prop("checked", true);

    } else {
        $("#deletCustomerListED").prop("checked", false);

    }
})

$("#editCustomerListED").on("change", () => {
    if ($("#editCustomerListED").is(":checked")) {
        $("#reportED").prop("checked", true);
        $("#cutomerListED").prop("checked", true);
        $("#seeCustomerListED").prop("checked", true);
        $("#reportCustomerED").prop("checked", true);
    } else {
        $("#editCustomerListED").prop("checked", false);
        $("#deletCustomerListED").prop("checked", false);

    }
})



$("#seeCustomerListED").on("change", () => {
    if ($("#seeCustomerListED").is(":checked")) {
        $("#reportED").prop("checked", true);
        $("#reportCustomerED").prop("checked", true);
        $("#cutomerListED").prop("checked", true);
    } else {

        $("#cutomerListED").prop("checked", false);

        if (!$(".cutomerListED").is(":checked")) {

            $("#reportCustomerED").prop("checked", false);
            $("#reportCustomerED").change();

        } else {
            $("#reportED").prop("checked", true);
            $("#reportCustomerED").prop("checked", true);

        }

        $("#editCustomerListED").prop("checked", false);
        $("#deletCustomerListED").prop("checked", false);

    }
})



$("#officialCustomerED").on("change", () => {
    if ($("#officialCustomerED").is(":checked")) {
        $("#reportED").prop("checked", true);
        $("#reportCustomerED").prop("checked", true);
        $("#seeOfficialCustomerED").prop("checked", true);
    } else {
        $("#officialCustomerED").prop("checked", false);
        if (!$(".cutomerListED").is(":checked")) {
            $("#reportCustomerED").prop("checked", false);
            $("#reportCustomerED").change();
        } else {
            $("#reportED").prop("checked", true);
            $("#reportCustomerED").prop("checked", true);

        }

        $("#seeOfficialCustomerED").prop("checked", false);
        $("#editOfficialCustomerED").prop("checked", false);
        $("#deleteOfficialCustomerED").prop("checked", false);
    }

})


$("#deleteOfficialCustomerED").on("change", () => {
    if ($("#deleteOfficialCustomerED").is(":checked")) {
        $("#reportED").prop("checked", true);
        $("#seeOfficialCustomerED").prop("checked", true);
        $("#editOfficialCustomerED").prop("checked", true);
        $("#reportCustomerED").prop("checked", true);
        $("#officialCustomerED").prop("checked", true);

    } else {
        $("#deleteOfficialCustomerED").prop("checked", false);

    }
})


$("#editOfficialCustomerED").on("change", () => {
    if ($("#editOfficialCustomerED").is(":checked")) {
        $("#reportED").prop("checked", true);
        $("#officialCustomerED").prop("checked", true);
        $("#reportCustomerED").prop("checked", true);
        $("#seeOfficialCustomerED").prop("checked", true);
    } else {
        $("#editOfficialCustomerED").prop("checked", false);
        $("#deleteOfficialCustomerED").prop("checked", false);
    }
})


$("#seeOfficialCustomerED").on("change", () => {
    if ($("#seeOfficialCustomerED").is(":checked")) {
        $("#reportED").prop("checked", true);
        $("#reportCustomerED").prop("checked", true);
        $("#officialCustomerED").prop("checked", true);
    } else {

        $("#officialCustomerED").prop("checked", false);

        if (!$(".cutomerListED").is(":checked")) {
            $("#reportCustomerED").prop("checked", false);
            $("#reportCustomerED").change();

        } else {
            $("#editOfficialCustomerED").prop("checked", false);
            $("#deleteOfficialCustomerED").prop("checked", false);
        }



    }
})




$("#gameAndLotteryED").on("change", () => {
    if ($("#gameAndLotteryED").is(":checked")) {
        $("#reportED").prop("checked", true);
        $("#lotteryResultED").prop("checked", true);
        $("#seeLotteryResultED").prop("checked", true);
        $("#gamerListED").prop("checked", true);
        $("#seeGamerListED").prop("checked", true);

    } else {
        $("#gameAndLotteryED").prop("checked", false);
        if (!$(".reportElementED").is(":checked")) {
            $("#reportED").prop("checked", false);
        } else {
            $("#reportED").prop("checked", true);
        }

        $("#lotteryResultED").prop("checked", false);
        $("#seeLotteryResultED").prop("checked", false);
        $("#gamerListED").prop("checked", false);
        $("#seeGamerListED").prop("checked", false);
        $("#editCustomerListED").prop("checked", false);
        $("#deletCustomerListED").prop("checked", false);
        $("#editLotteryResultED").prop("checked", false);
        $("#deletLotteryResultED").prop("checked", false);
        $("#deletGamerListED").prop("checked", false);
        $("#editGamerListED").prop("checked", false);

    }
})

$("#lotteryResultED").on("change", () => {
    if ($("#lotteryResultED").is(":checked")) {
        $("#reportED").prop("checked", true);
        $("#gameAndLotteryED").prop("checked", true);
        $("#seeLotteryResultED").prop("checked", true);
    } else {

        $("#lotteryResultED").prop("checked", false);

        if (!$(".gameAndLotteryElement").is(":checked")) {
            $("#gameAndLotteryED").prop("checked", false);
        } else {
            $("#gameAndLotteryED").prop("checked", true);
        }
        $("#seeLotteryResultED").prop("checked", false);
        $("#editLotteryResultED").prop("checked", false);
        $("#deletLotteryResultED").prop("checked", false);

    }
});

$("#deletLotteryResultED").on("change", () => {
    if ($("#deletLotteryResultED").is(":checked")) {
        $("#reportED").prop("checked", true);
        $("#gameAndLotteryED").prop("checked", true);
        $("#seeLotteryResultED").prop("checked", true);
        $("#lotteryResultED").prop("checked", true);
        $("#editLotteryResultED").prop("checked", true);

    } else {
        $("#deletLotteryResultED").prop("checked", false);

    }
})

$("#editLotteryResultED").on("change", () => {
    if ($("#editLotteryResultED").is(":checked")) {
        $("#reportED").prop("checked", true);
        $("#gameAndLotteryED").prop("checked", true);
        $("#seeLotteryResultED").prop("checked", true);
        $("#lotteryResultED").prop("checked", true);
        $("#editLotteryResultED").prop("checked", true);

    } else {
        $("#editLotteryResultED").prop("checked", false);
        $("#deletLotteryResultED").prop("checked", false);

    }
})


$("#seeLotteryResultED").on("change", () => {
    if ($("#seeLotteryResultED").is(":checked")) {
        $("#reportED").prop("checked", true);
        $("#gameAndLotteryED").prop("checked", true);
        $("#lotteryResultED").prop("checked", true);
    } else {

        $("#lotteryResultED").prop("checked", false);

        if (!$(".gameAndLotteryElement").is(":checked")) {
            $("#gameAndLotteryED").prop("checked", false);
            $("#gameAndLotteryED").change();

        } else {
            $("#reportED").prop("checked", true);
            $("#gameAndLotteryED").prop("checked", true);

        }

        $("#editLotteryResultED").prop("checked", false);
        $("#deletLotteryResultED").prop("checked", false);

    }
})

$("#gamerListED").on("change", function () {
    if ($("#gamerListED").is(":checked")) {
        $("#seeGamerListED").prop("checked", true);
        $("#gameAndLotteryED").prop("checked", true);
        $("#reportED").prop("checked", true);
    } else {
        $("#seeGamerListED").prop("checked", false);
        $("#editGamerListED").prop("checked", false);
        $("#deletGamerListED").prop("checked", false);
        if (!$(".gameAndLotteryElement").is(":checked")) {
            $("#gameAndLotteryED").prop("checked", false);
            $("#gameAndLotteryED").change();

        } else {
            $("#reportED").prop("checked", true);
            $("#gameAndLotteryED").prop("checked", true);

        }
    }
})


$("#seeGamerListED").on("change", function () {
    if ($("#seeGamerListED").is(":checked")) {
        $("#seeGamerListED").prop("checked", true);
        $("#gameAndLotteryED").prop("checked", true);
        $("#gamerListED").prop("checked", true);
        $("#reportED").prop("checked", true);
    } else {
        $("#seeGamerListED").prop("checked", false);
        $("#editGamerListED").prop("checked", false);
        $("#deletGamerListED").prop("checked", false);
        $("#gamerListED").prop("checked", false);
        if (!$(".gameAndLotteryElement").is(":checked")) {
            $("#gameAndLotteryED").prop("checked", false);
            $("#gameAndLotteryED").change();

        } else {
            $("#reportED").prop("checked", true);
            $("#gameAndLotteryED").prop("checked", true);

        }
    }
})

$("#editGamerListED").on("change", function () {
    if ($("#editGamerListED").is(":checked")) {
        $("#seeGamerListED").prop("checked", true);
        $("#gameAndLotteryED").prop("checked", true);
        $("#reportED").prop("checked", true);
        $("#gamerListED").prop("checked", true);
    } else {
        $("#deletGamerListED").prop("checked", false);
    }
})

$("#deletGamerListED").on("change", function () {
    if ($("#deletGamerListED").is(":checked")) {
        $("#seeGamerListED").prop("checked", true);
        $("#editGamerListED").prop("checked", true);
        $("#gameAndLotteryED").prop("checked", true);
        $("#reportED").prop("checked", true);
        $("#gamerListED").prop("checked", true);
    } else {
        $("#deletGamerListED").prop("checked", false);
    }
})

$("#onlinePaymentED").on("change", function () {
    if ($("#onlinePaymentED").is(":checked")) {
        $("#seeOnlinePaymentED").prop("checked", true);
        $("#reportED").prop("checked", true);
    } else {
        if (!$(".reportElementED").is(":checked")) {
            $("#reportED").prop("checked", false);
        } else {
            $("#reportED").prop("checked", true);
        }
        $("#seeOnlinePaymentED").prop("checked", false);
        $("#editOnlinePaymentED").prop("checked", false);
        $("#deleteOnlinePaymentED").prop("checked", false);
    }
})

$("#deleteOnlinePaymentED").on("change", function () {
    if ($("#deleteOnlinePaymentED").is(":checked")) {
        $("#seeOnlinePaymentED").prop("checked", true);
        $("#onlinePaymentED").prop("checked", true);
        $("#editOnlinePaymentED").prop("checked", true);
        $("#reportED").prop("checked", true);
    } else {
        if (!$(".reportElementED").is(":checked")) {
            $("#reportED").prop("checked", false);
        } else {
            $("#reportED").prop("checked", true);
        }
    }
})

$("#seeOnlinePaymentED").on("change", function () {
    if ($("#seeOnlinePaymentED").is(":checked")) {
        $("#onlinePaymentED").prop("checked", true);
        $("#reportED").prop("checked", true);
    } else {
        $("#onlinePaymentED").prop("checked", false);
        if (!$(".reportElementED").is(":checked")) {
            $("#seeOnlinePaymentED").prop("checked", false);
            $("#onlinePaymentED").prop("checked", false);
            $("#reportED").prop("checked", false);
            $("#editOnlinePaymentED").prop("checked", false);
            $("#deleteOnlinePaymentED").prop("checked", false);
        } else {
            $("#seeOnlinePaymentED").prop("checked", false);
            $("#editOnlinePaymentED").prop("checked", false);
            $("#deleteOnlinePaymentED").prop("checked", false);
        }
    }
});

$("#editOnlinePaymentED").on("change", function () {
    if ($("#editOnlinePaymentED").is(":checked")) {
        $("#onlinePaymentED").prop("checked", true);
        $("#seeOnlinePaymentED").prop("checked", true);
        $("#reportED").prop("checked", true);
    } else {
        if (!$(".reportElementED").is(":checked")) {
            $("#seeOnlinePaymentED").prop("checked", false);
            $("#onlinePaymentED").prop("checked", false);
            $("#reportED").prop("checked", false);
            $("#editOnlinePaymentED").prop("checked", false);
            $("#deleteOnlinePaymentED").prop("checked", false);
        } else {
            $("#editOnlinePaymentED").prop("checked", false);
            $("#deleteOnlinePaymentED").prop("checked", false);
        }
    }
})

$("#adminTypeED").on("change", () => {
    if ($("#adminTypeED").val() == 'admin') {
        //در صورتیکه ادمین باشد
        $("#baseInfoED").prop("checked", true);
        $("#settingsED").prop("checked", true);
        $(".allSettingsED").prop("checked", true);
        $("#seeMainPageSettingED").prop("checked", true);
        $("#editManiPageSettingED").prop("checked", true);
        $("#deletMainPageSettingED").prop("checked", true);
        $("#seeSpecialSettingED").prop("checked", true);
        $("#editSpecialSettingED").prop("checked", true);
        $("#deleteSpecialSettingED").prop("checked", true);
        $("#seeSpecialSettingED").prop("checked", true);
        $("#editEmptyazSettingED").prop("checked", true);
        $("#seeEmtyazSettingED").prop("checked", true);
        $("#deleteEmtyazSettingED").prop("checked", true);
        $("#defineElementED").prop("checked", true);
        $("#karbaranED").prop("checked", true);
        $("#customersED").prop("checked", true);
        $("#seeCustomersED").prop("checked", true);
        $("#operationED").prop("checked", true);
        $("#kalasED").prop("checked", true);
        $("#kalaListsED").prop("checked", true);
        $("#seeKalaListED").prop("checked", true);
        $("#requestedKalaED").prop("checked", true);
        $("#seeRequestedKalaED").prop("checked", true);
        $("#fastKalaED").prop("checked", true);
        $("#seeFastKalaED").prop("checked", true);
        $("#pishKharidED").prop("checked", true);
        $("#seePishKharidED").prop("checked", true);
        $("#brandsED").prop("checked", true);
        $("#seeBrandsED").prop("checked", true);
        $("#alertedED").prop("checked", true);
        $("#seeAlertedED").prop("checked", true);
        $("#kalaGroupED").prop("checked", true);
        $("#seeKalaGroup").prop("checked", true);
        $("#orderSalesED").prop("checked", true);
        $("#seeSalesOrderED").prop("checked", true);
        $("#messageED").prop("checked", true);
        $("#seeMessageED").prop("checked", true);
        $("#seeKalaListED").prop("checked", true);
        $("#editKalaListED").prop("checked", true);
        $("#deleteKalaListED").prop("checked", true);
        $("#editMessageED").prop("checked", true);
        $("#deleteMessageED").prop("checked", true);
        $("#editRequestedKalaED").prop("checked", true);
        $("#deleteRequestedKalaED").prop("checked", true);
        $("#editFastKalaED").prop("checked", true);
        $("#deleteFastKalaED").prop("checked", true);
        $("#editFastKalaED").prop("checked", true);
        $("#deleteFastKalaED").prop("checked", true);
        $("#editPishkharidED").prop("checked", true);
        $("#deletePishKharidED").prop("checked", true);
        $("#editBrandED").prop("checked", true);
        $("#deleteBrandsED").prop("checked", true);
        $("#editAlertedED").prop("checked", true);
        $("#deleteAlertedED").prop("checked", true);
        $("#editKalaGroupED").prop("checked", true);
        $("#deletKalaGroupED").prop("checked", true);
        $("#seeKalaGroupED").prop("checked", true);
        $("#editOrderSalesED").prop("checked", true);
        $("#deleteOrderSalesED").prop("checked", true);

        $("#reportED").prop("checked", true);
        $("#reportCustomerED").prop("checked", true);
        $("#cutomerListED").prop("checked", true);
        $("#seeCustomerListED").prop("checked", true);
        $("#editCustomerListED").prop("checked", true);
        $("#deleteCustomerListED").prop("checked", true);
        $("#officialCustomerED").prop("checked", true);
        $("#seeOfficialCustomerED").prop("checked", true);
        $("#editOfficialCustomerED").prop("checked", true);
        $("#deleteOfficialCustomerED").prop("checked", true);
        $("#gameAndLotteryED").prop("checked", true);
        $("#lotteryResultED").prop("checked", true);
        $("#seeLotteryResultED").prop("checked", true);
        $("#editLotteryResultED").prop("checked", true);
        $("#deletLotteryResultED").prop("checked", true);
        $("#gamerListED").prop("checked", true);
        $("#seeGamerListED").prop("checked", true);
        $("#editGamerListED").prop("checked", true);
        $("#deletGamerListED").prop("checked", true);
        $("#onlinePaymentED").prop("checked", true);
        $("#seeOnlinePaymentED").prop("checked", true);
        $("#editOnlinePaymentED").prop("checked", true);
        $("#deleteOnlinePaymentED").prop("checked", true);
        $("#editCustomerListED").prop("checked", true);
        $("#deletCustomerListED").prop("checked", true);

    } else {
        //در صورتیکه پشتیبان باشد
        //در صورتیکه ادمین باشد
        $("#baseInfoED").prop("checked", true);
        $("#settingsED").prop("checked", true);
        $(".allSettingsED").prop("checked", true);
        $("#seeMainPageSettingED").prop("checked", true);
        $("#editManiPageSettingED").prop("checked", false);
        $("#deletMainPageSettingED").prop("checked", false);
        $("#seeSpecialSettingED").prop("checked", true);
        $("#editSpecialSettingED").prop("checked", false);
        $("#deleteSpecialSettingED").prop("checked", false);
        $("#seeSpecialSettingED").prop("checked", true);
        $("#editEmptyazSettingED").prop("checked", false);
        $("#seeEmtyazSettingED").prop("checked", true);
        $("#deleteEmtyazSettingED").prop("checked", false);
        $("#defineElementED").prop("checked", true);
        $("#karbaranED").prop("checked", true);
        $("#customersED").prop("checked", true);
        $("#seeCustomersED").prop("checked", true);
        $("#operationED").prop("checked", true);
        $("#kalasED").prop("checked", true);
        $("#kalaListsED").prop("checked", true);
        $("#seeKalaListED").prop("checked", true);
        $("#requestedKalaED").prop("checked", true);
        $("#seeRequestedKalaED").prop("checked", true);
        $("#fastKalaED").prop("checked", true);
        $("#seeFastKalaED").prop("checked", true);
        $("#pishKharidED").prop("checked", true);
        $("#seePishKharidED").prop("checked", true);
        $("#brandsED").prop("checked", true);
        $("#seeBrandsED").prop("checked", true);
        $("#alertedED").prop("checked", true);
        $("#seeAlertedED").prop("checked", true);
        $("#kalaGroupED").prop("checked", true);
        $("#seeKalaGroup").prop("checked", true);
        $("#orderSalesED").prop("checked", true);
        $("#seeSalesOrderED").prop("checked", true);
        $("#messageED").prop("checked", true);
        $("#seeMessageED").prop("checked", true);
        $("#seeKalaListED").prop("checked", true);
        $("#editKalaListED").prop("checked", false);
        $("#deleteKalaListED").prop("checked", false);
        $("#editMessageED").prop("checked", true);
        $("#deleteMessageED").prop("checked", false);
        $("#editRequestedKalaED").prop("checked", false);
        $("#deleteRequestedKalaED").prop("checked", false);
        $("#editFastKalaED").prop("checked", false);
        $("#deleteFastKalaED").prop("checked", false);
        $("#editFastKalaED").prop("checked", false);
        $("#deleteFastKalaED").prop("checked", false);
        $("#editPishkharidED").prop("checked", false);
        $("#deletePishKharidED").prop("checked", false);
        $("#editBrandED").prop("checked", false);
        $("#deleteBrandsED").prop("checked", false);
        $("#editAlertedED").prop("checked", false);
        $("#deleteAlertedED").prop("checked", false);
        $("#editKalaGroupED").prop("checked", false);
        $("#deletKalaGroupED").prop("checked", false);
        $("#seeKalaGroupED").prop("checked", true);
        $("#editOrderSalesED").prop("checked", false);
        $("#deleteOrderSalesED").prop("checked", false);
        $("#reportED").prop("checked", true);
        $("#reportCustomerED").prop("checked", true);
        $("#cutomerListED").prop("checked", true);
        $("#seeCustomerListED").prop("checked", true);
        $("#editCustomerListED").prop("checked", false);
        $("#deleteCustomerListED").prop("checked", false);
        $("#officialCustomerED").prop("checked", true);
        $("#seeOfficialCustomerED").prop("checked", true);
        $("#editOfficialCustomerED").prop("checked", false);
        $("#deleteOfficialCustomerED").prop("checked", false);
        $("#gameAndLotteryED").prop("checked", true);
        $("#lotteryResultED").prop("checked", true);
        $("#seeLotteryResultED").prop("checked", true);
        $("#editLotteryResultED").prop("checked", false);
        $("#deletLotteryResultED").prop("checked", false);
        $("#gamerListED").prop("checked", true);
        $("#seeGamerListED").prop("checked", true);
        $("#editGamerListED").prop("checked", false);
        $("#deletGamerListED").prop("checked", false);
        $("#onlinePaymentED").prop("checked", true);
        $("#seeOnlinePaymentED").prop("checked", true);
        $("#editOnlinePaymentED").prop("checked", false);
        $("#deleteOnlinePaymentED").prop("checked", false);
        $("#editCustomerListED").prop("checked", false);
        $("#deletCustomerListED").prop("checked", false);

    }
})

// برای تعیین پیش فرض تعیین نوعیت کاربر


$("#adminTypeN").on("change", () => {
    if ($("#adminTypeN").val() == 'admin') {
        //در صورتیکه ادمین باشد
        $("#baseInfoN").prop("checked", true);
        $("#settingsN").prop("checked", true);
        $(".allSettingsN").prop("checked", true);
        $("#seeMainPageSettingN").prop("checked", true);
        $("#editManiPageSettingN").prop("checked", true);
        $("#deletMainPageSettingN").prop("checked", true);
        $("#seeSpecialSettingN").prop("checked", true);
        $("#editSpecialSettingN").prop("checked", true);
        $("#deleteSpecialSettingN").prop("checked", true);
        $("#seeSpecialSettingN").prop("checked", true);
        $("#editEmptyazSettingN").prop("checked", true);
        $("#seeEmtyazSettingN").prop("checked", true);
        $("#deleteEmtyazSettingN").prop("checked", true);
        $("#defineElementN").prop("checked", true);
        $("#karbaranN").prop("checked", true);
        $("#customersN").prop("checked", true);
        $("#seeCustomersN").prop("checked", true);
        $("#operationN").prop("checked", true);
        $("#kalasN").prop("checked", true);
        $("#kalaListsN").prop("checked", true);
        $("#seeKalaListN").prop("checked", true);
        $("#requestedKalaN").prop("checked", true);
        $("#seeRequestedKalaN").prop("checked", true);
        $("#fastKalaN").prop("checked", true);
        $("#seeFastKalaN").prop("checked", true);
        $("#pishKharidN").prop("checked", true);
        $("#seePishKharidN").prop("checked", true);
        $("#brandsN").prop("checked", true);
        $("#seeBrandsN").prop("checked", true);
        $("#alertedN").prop("checked", true);
        $("#seeAlertedN").prop("checked", true);
        $("#kalaGroupN").prop("checked", true);
        $("#seeKalaGroup").prop("checked", true);
        $("#orderSalesN").prop("checked", true);
        $("#seeSalesOrderN").prop("checked", true);
        $("#messageN").prop("checked", true);
        $("#seeMessageN").prop("checked", true);
        $("#seeKalaListN").prop("checked", true);
        $("#editKalaListN").prop("checked", true);
        $("#deleteKalaListN").prop("checked", true);
        $("#editMessageN").prop("checked", true);
        $("#deleteMessageN").prop("checked", true);
        $("#editRequestedKalaN").prop("checked", true);
        $("#deleteRequestedKalaN").prop("checked", true);
        $("#editFastKalaN").prop("checked", true);
        $("#deleteFastKalaN").prop("checked", true);
        $("#editFastKalaN").prop("checked", true);
        $("#deleteFastKalaN").prop("checked", true);
        $("#editPishkharidN").prop("checked", true);
        $("#deletePishKharidN").prop("checked", true);
        $("#editBrandN").prop("checked", true);
        $("#deleteBrandsN").prop("checked", true);
        $("#editAlertedN").prop("checked", true);
        $("#deleteAlertedN").prop("checked", true);
        $("#editKalaGroupN").prop("checked", true);
        $("#deletKalaGroupN").prop("checked", true);
        $("#seeKalaGroupN").prop("checked", true);
        $("#editOrderSalesN").prop("checked", true);
        $("#deleteOrderSalesN").prop("checked", true);
        $("#reportN").prop("checked", true);
        $("#reportCustomerN").prop("checked", true);
        $("#cutomerListN").prop("checked", true);
        $("#seeCustomerListN").prop("checked", true);
        $("#editCustomerListN").prop("checked", true);
        $("#deleteCustomerListN").prop("checked", true);
        $("#officialCustomerN").prop("checked", true);
        $("#seeOfficialCustomerN").prop("checked", true);
        $("#editOfficialCustomerN").prop("checked", true);
        $("#deleteOfficialCustomerN").prop("checked", true);
        $("#gameAndLotteryN").prop("checked", true);
        $("#lotteryResultN").prop("checked", true);
        $("#seeLotteryResultN").prop("checked", true);
        $("#editLotteryResultN").prop("checked", true);
        $("#deletLotteryResultN").prop("checked", true);
        $("#gamerListN").prop("checked", true);
        $("#seeGamerListN").prop("checked", true);
        $("#editGamerListN").prop("checked", true);
        $("#deletGamerListN").prop("checked", true);
        $("#onlinePaymentN").prop("checked", true);
        $("#seeOnlinePaymentN").prop("checked", true);
        $("#editOnlinePaymentN").prop("checked", true);
        $("#deleteOnlinePaymentN").prop("checked", true);
        $("#editCustomerListN").prop("checked", true);
        $("#deletCustomerListN").prop("checked", true);

    } else {
        //در صورتیکه پشتیبان باشد
        //در صورتیکه ادمین باشد
        $("#baseInfoN").prop("checked", true);
        $("#settingsN").prop("checked", true);
        $(".allSettingsN").prop("checked", true);
        $("#seeMainPageSettingN").prop("checked", true);
        $("#editManiPageSettingN").prop("checked", false);
        $("#deletMainPageSettingN").prop("checked", false);
        $("#seeSpecialSettingN").prop("checked", true);
        $("#editSpecialSettingN").prop("checked", false);
        $("#deleteSpecialSettingN").prop("checked", false);
        $("#seeSpecialSettingN").prop("checked", true);
        $("#editEmptyazSettingN").prop("checked", false);
        $("#seeEmtyazSettingN").prop("checked", true);
        $("#deleteEmtyazSettingN").prop("checked", false);
        $("#defineElementN").prop("checked", true);
        $("#karbaranN").prop("checked", true);
        $("#customersN").prop("checked", true);
        $("#seeCustomersN").prop("checked", true);
        $("#operationN").prop("checked", true);
        $("#kalasN").prop("checked", true);
        $("#kalaListsN").prop("checked", true);
        $("#seeKalaListN").prop("checked", true);
        $("#requestedKalaN").prop("checked", true);
        $("#seeRequestedKalaN").prop("checked", true);
        $("#fastKalaN").prop("checked", true);
        $("#seeFastKalaN").prop("checked", true);
        $("#pishKharidN").prop("checked", true);
        $("#seePishKharidN").prop("checked", true);
        $("#brandsN").prop("checked", true);
        $("#seeBrandsN").prop("checked", true);
        $("#alertedN").prop("checked", true);
        $("#seeAlertedN").prop("checked", true);
        $("#kalaGroupN").prop("checked", true);
        $("#seeKalaGroup").prop("checked", true);
        $("#orderSalesN").prop("checked", true);
        $("#seeSalesOrderN").prop("checked", true);
        $("#messageN").prop("checked", true);
        $("#seeMessageN").prop("checked", true);
        $("#seeKalaListN").prop("checked", true);
        $("#editKalaListN").prop("checked", false);
        $("#deleteKalaListN").prop("checked", false);
        $("#editMessageN").prop("checked", true);
        $("#deleteMessageN").prop("checked", false);
        $("#editRequestedKalaN").prop("checked", false);
        $("#deleteRequestedKalaN").prop("checked", false);
        $("#editFastKalaN").prop("checked", false);
        $("#deleteFastKalaN").prop("checked", false);
        $("#editFastKalaN").prop("checked", false);
        $("#deleteFastKalaN").prop("checked", false);
        $("#editPishkharidN").prop("checked", false);
        $("#deletePishKharidN").prop("checked", false);
        $("#editBrandN").prop("checked", false);
        $("#deleteBrandsN").prop("checked", false);
        $("#editAlertedN").prop("checked", false);
        $("#deleteAlertedN").prop("checked", false);
        $("#editKalaGroupN").prop("checked", false);
        $("#deletKalaGroupN").prop("checked", false);
        $("#seeKalaGroupN").prop("checked", true);
        $("#editOrderSalesN").prop("checked", false);
        $("#deleteOrderSalesN").prop("checked", false);

        $("#reportN").prop("checked", true);
        $("#reportCustomerN").prop("checked", true);
        $("#cutomerListN").prop("checked", true);
        $("#seeCustomerListN").prop("checked", true);
        $("#editCustomerListN").prop("checked", false);
        $("#deleteCustomerListN").prop("checked", false);
        $("#officialCustomerN").prop("checked", true);
        $("#seeOfficialCustomerN").prop("checked", true);
        $("#editOfficialCustomerN").prop("checked", false);
        $("#deleteOfficialCustomerN").prop("checked", false);
        $("#gameAndLotteryN").prop("checked", true);
        $("#lotteryResultN").prop("checked", true);
        $("#seeLotteryResultN").prop("checked", true);
        $("#editLotteryResultN").prop("checked", false);
        $("#deletLotteryResultN").prop("checked", false);
        $("#gamerListN").prop("checked", true);
        $("#seeGamerListN").prop("checked", true);
        $("#editGamerListN").prop("checked", false);
        $("#deletGamerListN").prop("checked", false);
        $("#onlinePaymentN").prop("checked", true);
        $("#seeOnlinePaymentN").prop("checked", true);
        $("#editOnlinePaymentN").prop("checked", false);
        $("#deleteOnlinePaymentN").prop("checked", false);
        $("#editCustomerListN").prop("checked", false);
        $("#deletCustomerListN").prop("checked", false);

    }
})
$("#openViewTenSalesModal").on("click", () => {
    const kalaId = $("#kalaIdForEdit").val();
    $.ajax({
        method: 'get',
        url: baseUrl + "/getTenLastSales",
        async: true,
        data: {
            _token: "{{ csrf_token() }}",
            kalaId: kalaId
        },
        success: function (arrayed_result) {
            $('#lastTenSaleBody').empty();
            $("#lasTenSaleName").text(arrayed_result[1][0].GoodName);
            arrayed_result[0].forEach((element, index) => {
                $('#lastTenSaleBody').append(`<tr>
                                    <td>`+ (index + 1) + `</td>
                                   
                                    <td>`+ element.Name + `</td>
                                    <td>`+ element.FactDate + `</td>
                                    <td>`+ parseInt(element.Fi / 10).toLocaleString("en") + ` تومان</td>
                                    <td>`+ parseInt(element.Amount) + ` </td>
                                    <td>`+ parseInt(element.Price / 10).toLocaleString("en") + ` تومان</td>
 									<td>`+ element.PCode + `</td>
                                    </tr>`);
            });

            if (!($('.modal.in').length)) {
                $('.modal-dialog').css({
                    left: 50,
                    top: 0
                });
            }
            $('#viewTenSales').modal({
                backdrop: false,
                show: true
            });

            $('.modal-dialog').draggable({
                handle: ".modal-header"
            });

            $("#viewTenSales").modal("show");
        },
        error: function (data) { }
    });
});

function closeNav() {
    const backdrop = document.querySelector('.menuBackdrop');
    document.getElementById("mySidenav").style.width = "0";
    backdrop.classList.remove('show');
}
$('#selectStock').on('change', () => {
    let stockId = $('#selectStock').val();
    $.ajax({
        method: 'get',
        url: baseUrl + "/searchKalaByStock",
        async: true,
        data: {
            _token: "{{ csrf_token() }}",
            stockId: stockId
        },
        success: function (arrayed_result) {

            $('#kalaContainer').empty();
            for (var i = 0; i <= arrayed_result.length - 1; i++) {
                $('#kalaContainer').append(`<tr onClick="kalaProperties(this)">
<td></td>
<td>` + arrayed_result[i].GoodCde + `</td>
<td>` + arrayed_result[i].GoodName + `</td>
<td>` + arrayed_result[i].NameGRP + `</td>
<td>1401.2.21</td>
<td>1401.2.21</td>
<td><input class="kala form-check-input" name="kalaId[]" disabled type="checkbox" value="{{$kala->GoodSn}}" id=""></td>
<td>` + parseInt(arrayed_result[i].Price4 / 10).toLocaleString("en-US") + `</td>
<td>` + parseInt(arrayed_result[i].Price3 / 10).toLocaleString("en-US") + `</td>
<td>` + parseInt(arrayed_result[i].Amount / 1).toLocaleString("en-US") + `</td>
<td>
<input class="kala form-check-input" name="kalaId[]" type="radio" value="` + arrayed_result[i].GoodSn + `_` + arrayed_result[i].Price4 + `_` + arrayed_result[i].Price3 + `" id="flexCheckCheckedKala">
</td>
</tr>`);
            }
        },
        error: function (data) { }
    });
});
$('#kalaPicState').on('change', () => {
    let picState = $('#kalaPicState').val();

    $.ajax({
        method: 'get',
        url: baseUrl + "/listPictureName",
        async: true,
        data: {
            _token: "{{ csrf_token() }}",
            picState: picState
        },
        success: function (arrayed_result) {
            $('#kalaContainer').empty();
            for (var i = 0; i <= arrayed_result.length - 1; i++) {
                $('#kalaContainer').append(`<tr onClick="kalaProperties(this)">
        <td></td>
        <td>` + arrayed_result[i].GoodCde + `</td>
        <td>` + arrayed_result[i].GoodName + `</td>
        <td>` + arrayed_result[i].NameGRP + `</td>
        <td>1401.2.21</td>
        <td>1401.2.21</td>
        <td><input class="kala form-check-input" name="kalaId[]" disabled type="checkbox" value="{{$kala->GoodSn}}" id=""></td>
        <td>` + parseInt(arrayed_result[i].Price4 / 10).toLocaleString("en-US") + `</td>
        <td>` + parseInt(arrayed_result[i].Price3 / 10).toLocaleString("en-US") + `</td>
        <td>` + parseInt(arrayed_result[i].Amount / 1).toLocaleString("en-US") + `</td>
        <td>
        <input class="kala form-check-input" name="kalaId[]" type="radio" value="` + arrayed_result[i].GoodSn + `_` + arrayed_result[i].Price4 + `_` + arrayed_result[i].Price3 + `" id="flexCheckCheckedKala">
        </td>
        </tr>`);
            }
        },
        error: function (data) { }
    });
});

function kalaProperties(element) {
    $(element).find('input:radio').prop('checked', true);
    let inp = $(element).find('input:radio:checked');
    $('.select-highlight tr').removeClass('selected');
    $(this).toggleClass('selected');
    $("#kalaIdForEdit").val(inp.val().split("_")[0]);
    $("#kalaIdForEdit1").val(inp.val().split("_")[0]);
    $("#firstPrice").val(parseInt(inp.val().split("_")[1]).toLocaleString());
    $("#secondPrice").val(parseInt(inp.val().split("_")[2]).toLocaleString());
    $("#kalaId").val(parseInt(inp.val().split("_")[0]));
    document.querySelector("#editKalaList").disabled = false;
    $(".kala-btn").prop("disabled", false);
}

$('.select-highlight tr').click(function () {
    $(this).children('td').children('input:radio').prop('checked', true);
    $(".enableBtn").prop("disabled", false);
    $('.select-highlight tr').removeClass('selected');
    $(this).toggleClass('selected');
    $('#customerSn').val($(this).children('td').children('input').val().split('_')[0]);
    $('#customerGroup').val($(this).children('td').children('input').val().split('_')[1]);
    $("#customerId").val($('#customerSn').val());
});

$('.select-highlightKala tr').click(function () {
    $(this).find('input:radio').prop('checked', true);
    let inp = $(this).find('input:radio:checked');
    $('.select-highlightKala tr').removeClass('selected');
    $(this).toggleClass('selected');
    $("#kalaIdForEdit").val(inp.val().split("_")[0]);
    $("#firstPrice").val(parseInt(inp.val().split("_")[1]).toLocaleString("en-US"));
    $("#secondPrice").val(parseInt(inp.val().split("_")[2]).toLocaleString("en-US"));
    $("#kalaId").val(parseInt(inp.val().split("_")[0]));
    if (document.querySelector("#editKalaList")) {
        document.querySelector("#editKalaList").disabled = false;
    }
    $(".kala-btn").prop("disabled", false);
});

function setListKalaStuff(element, title) {
    $(element).find('input:radio').prop('checked', true);
    $("#changePriceTitle").text(title);
    let inp = $(element).find('input:radio:checked');
    $('tr').removeClass('selected');
    $(element).toggleClass('selected');
    $("#kalaIdForEdit").val(inp.val().split("_")[0]);
    $("#firstPrice").val(parseInt(inp.val().split("_")[1]).toLocaleString("en-US"));
    $("#secondPrice").val(parseInt(inp.val().split("_")[2]).toLocaleString("en-US"));
    $("#kalaId").val(parseInt(inp.val().split("_")[0]));
    if (document.querySelector("#editKalaList")) {
        document.querySelector("#editKalaList").disabled = false;
    }
    $(".kala-btn").prop("disabled", false);

}
//

$("#selectCities").on("change", () => {
    let id = $("#selectCities").val();
    $.ajax({
        method: 'get',
        url: baseUrl + "/searchMantagha",
        data: {
            _token: "{{ csrf_token() }}",
            cityId: id
        },
        async: true,
        success: function (arrayed_result) {
            $("#mantaghas").empty();
            arrayed_result.forEach((element, index) => {
                $("#mantaghas").append(`
                <option value="`+ element.SnMNM + `">` + element.NameRec + `</option>
                `);
            });
        },
        error: function (data) { }
    });
});

$.get(baseUrl + "/getProductMainGroups", function (data, status) {
    $("#superGroup").empty();
    $("#superGroup").append(`<option value="_">همه</option>`);
    data.forEach((element) => {
        $("#superGroup").append(`<option value="` + element.id + `_` + element.title + `">` + element.title + `</option>`);
    });
});


if ($("#superGroup")) {
    $("#superGroup").on("change", function () {
        $.get(baseUrl + "/getSubGroups", { mainGroupId: $("#superGroup").val().split("_")[0] }, function (data, status) {
            $("#subGroup").empty();
            $("#subGroup").append(`<option value="">همه</option>`);
            data.forEach((element) => {
                $("#subGroup").append(`<option value="` + element.title + `">` + element.title + `</option>`);
            });
        });
    });
}

function filterAllKala() {
    $.get(baseUrl + "/filterAllKala", {
        kalaNameCode: $("#searchKalaNameCode").val(),
        mainGroup: $("#superGroup").val().split("_")[1],
        subGroup: $("#subGroup").val(),
        searchKalaStock: $("#searchKalaStock").val(),
        searchKalaActiveOrNot: $("#searchKalaActiveOrNot").val(),
        searchKalaExistInStock: $("#searchKalaExistInStock").val(),
        assesFirstDate: $("#assesFirstDate").val(),
        assesSecondDate: $("#assesSecondDate").val()
    }, function (data, status) {
        if (status == "success") {
            $('#kalaContainer').empty();
            data.forEach((element, index) => {
                $('#kalaContainer').append(`<tr onclick="setListKalaStuff(this,'` + element.GoodName + `')">
                <td>` + (index + 1) + `</td>
                <td  style="width: 222px">` + element.GoodName + `</td>
                <td>` + element.GoodCde + `</td>
                <td>` + element.firstGroupName + `</td>
                <td>` + element.lastDate + `</td>
                <td>no</td>
                <td>` + element.hideKala + `</td>
                <td>` + parseInt(element.Price4 / 10).toLocaleString("en-US") + `</td>
                <td>` + parseInt(element.Price3 / 10).toLocaleString("en-US") + `</td>
                <td>` + element.Amount + `</td>
                <td>
                <input class="kala form-check-input" name="kalaId[]" type="radio" value="` + element.GoodSn + `_` + element.Price4 + `_` + element.Price3 + `" id="flexCheckCheckedKala">
                </td>
                </tr>`);
            });
        } else {
            alert("data has not come");
        }
    });
}

$("#searchCustomerByName").on("keyup", () => {
    let searchTerm = $("#searchCustomerByName").val();
    $.ajax({
        method: 'get',
        url: baseUrl + "/searchCustomerByName",
        data: {
            _token: "{{ csrf_token() }}",
            searchTerm: searchTerm
        },
        async: true,
        success: function (arrayed_result) {
            $("#customerList").empty();
            arrayed_result.forEach((element, index) => {
                let nameRec = element.NameRec;
                let iterator = parseInt(index);
                if (element.NameRec == null) {
                    nameRec = ""
                }
                $("#customerList").append(`<tr onclick="selectCustomerStuff(this)">
                <td></td>
                <td>`+ element.PCode + `</td>
                <td>`+ element.Name + `</td>
                <td>`+ element.peopeladdress + `</td>
                <td>`+ element.hamrah + `</td>
                <td>`+ element.sabit + `</td>
                <td>`+ nameRec + `</td>
                <td>2</td>
                <td> <input class="customerList form-check-input" name="customerId" type="radio" value="`+ element.PSN + `_` + element.GroupCode + `" id="flexCheckChecked"></td>
            </tr>
                `);
            });
        },
        error: function (data) { }
    });
});


$("#searchSelectMantiqah").on("change", function () {
    let id = $("#searchSelectMantiqah").val();
    $.ajax({
        method: 'get',
        url: baseUrl + "/searchByMantagha",
        data: {
            _token: "{{ csrf_token() }}",
            msn: id
        },
        async: true,
        success: function (arrayed_result) {
            $("#customerList").empty();
            arrayed_result.forEach((element, index) => {
                let nameRec = element.NameRec;
                let iterator = parseInt(index);
                if (element.NameRec == null) {
                    nameRec = ""
                }
                $("#customerList").append(`<tr onclick="selectCustomerStuff(this)">
                <td></td>
                <td>`+ element.PCode + `</td>
                <td>`+ element.Name + `</td>
                <td>`+ element.peopeladdress + `</td>
                <td>`+ element.hamrah + `</td>
                <td>`+ element.sabit + `</td>
                <td>`+ nameRec + `</td>
                <td>2</td>
                <td> <input class="customerList form-check-input" name="customerId" type="radio" value="`+ element.PSN + `_` + element.GroupCode + `" id="flexCheckChecked"></td>
            </tr>
                `);
            });
        },
        error: function (data) { }
    });
});

$("#searchCustomerByCode").on("keyup", () => {
    let searchTerm = $("#searchCustomerByCode").val();
    $.ajax({
        method: 'get',
        url: baseUrl + "/searchCustomerByCode",
        data: {
            _token: "{{ csrf_token() }}",
            searchTerm: searchTerm
        },
        async: true,
        success: function (arrayed_result) {
            $("#customerList").empty();
            arrayed_result.forEach((element, index) => {
                let nameRec = element.NameRec;
                let iterator = parseInt(index);
                if (element.NameRec == null) {
                    nameRec = ""
                }
                $("#customerList").append(`<tr onclick="selectCustomerStuff(this)">
                <td></td>
                <td>`+ element.PCode + `</td>
                <td>`+ element.Name + `</td>
                <td>`+ element.peopeladdress + `</td>
                <td>`+ element.hamrah + `</td>
                <td>`+ element.sabit + `</td>
                <td>`+ nameRec + `</td>
                <td>2</td>
                <td> <input class="customerList form-check-input" name="customerId" type="radio" value="`+ element.PSN + `_` + element.GroupCode + `" id="flexCheckChecked"></td>
            </tr>
                `);
            });
        },
        error: function (data) { }
    });
});

$("#searchActiveOrNot").on("change", () => {
    let searchTerm = $("#searchActiveOrNot").val();
    $.ajax({
        method: 'get',
        url: baseUrl + "/searchCustomerByActivation",
        data: {
            _token: "{{ csrf_token() }}",
            searchTerm: searchTerm
        },
        async: true,
        success: function (arrayed_result) {

            $("#customerList").empty();
            arrayed_result.forEach((element, index) => {
                let nameRec = element.NameRec;
                let iterator = parseInt(index);
                if (element.NameRec == null) {
                    nameRec = ""
                }
                $("#customerList").append(`<tr onclick="selectCustomerStuff(this)">
                <td></td>
                <td>`+ element.PCode + `</td>
                <td>`+ element.Name + `</td>
                <td>`+ element.peopeladdress + `</td>
                <td>`+ element.hamrah + `</td>
                <td>`+ element.sabit + `</td>
                <td>`+ nameRec + `</td>
                <td>2</td>
                <td> <input class="customerList form-check-input" name="customerId" type="radio" value="`+ element.PSN + `_` + element.GroupCode + `" id="flexCheckChecked"></td>
            </tr>
                `);
            });
        },
        error: function (data) { }
    });
});
$("#searchLocationOrNot").on("change", () => {
    let searchTerm = $("#searchLocationOrNot").val();
    $.ajax({
        method: 'get',
        url: baseUrl + "/searchCustomerLocationOrNot",
        data: {
            _token: "{{ csrf_token() }}",
            searchTerm: searchTerm
        },
        async: true,
        success: function (arrayed_result) {

            // $('.crmDataTable').dataTable().fnDestroy();
            $("#customerList").empty();
            arrayed_result.forEach((element, index) => {
                let nameRec = element.NameRec;
                let iterator = parseInt(index);
                if (element.NameRec == null) {
                    nameRec = ""
                }
                $("#customerList").append(`<tr onclick="selectCustomerStuff(this)">
                <td></td>
                <td>`+ element.PCode + `</td>
                <td>`+ element.Name + `</td>
                <td>`+ element.peopeladdress + `</td>
                <td>`+ element.hamrah + `</td>
                <td>`+ element.sabit + `</td>
                <td>`+ nameRec + `</td>
                <td>2</td>
                <td> <input class="customerList form-check-input" name="customerId" type="radio" value="`+ element.PSN + `_` + element.GroupCode + `" id="flexCheckChecked"></td>
            </tr>
                `);
            });
            // $('.crmDataTable').dataTable();
        },
        error: function (data) { }
    });
});

$("#orderCustomers").on("change", () => {
    let searchTerm = $("#orderCustomers").val();
    $.ajax({
        method: 'get',
        url: baseUrl + "/orderCustomers",
        data: {
            _token: "{{ csrf_token() }}",
            searchTerm: searchTerm
        },
        async: true,
        success: function (arrayed_result) {

            // $('.crmDataTable').dataTable().fnDestroy();
            $("#customerList").empty();
            arrayed_result.forEach((element, index) => {
                let nameRec = element.NameRec;
                let iterator = parseInt(index);
                if (element.NameRec == null) {
                    nameRec = ""
                }
                $("#customerList").append(`<tr onclick="selectCustomerStuff(this)">
                <td></td>
                <td>`+ element.PCode + `</td>
                <td>`+ element.Name + `</td>
                <td>`+ element.peopeladdress + `</td>
                <td>`+ element.hamrah + `</td>
                <td>`+ element.sabit + `</td>
                <td>`+ nameRec + `</td>
                <td>2</td>
                <td> <input class="customerList form-check-input" name="customerId" type="radio" value="`+ element.PSN + `_` + element.GroupCode + `" id="flexCheckChecked"></td>
            </tr>
                `);
            });
            // $('.crmDataTable').dataTable();
        },
        error: function (data) { }
    });
});

function takhsisMsir() {
    const cityId = $("#cityId").val();
    const mantiqahId = $("#selectMantiqah").val();
    const csn = $("#customerId").val();
    $.ajax({
        method: 'get',
        url: baseUrl + "/takhsisMasirs",
        data: {
            _token: "{{ csrf_token() }}",
            cityId: cityId,
            regionId: mantiqahId,
            csn: csn
        },
        async: true,
        success: function (arrayed_result) {
            $("#personRoute").modal("hide");
            $("#customerList").empty();
            arrayed_result.forEach((element, index) => {
                $("#customerList").append(`<tr onclick="selectCustomerStuff(this)">
                <td></td>
                <td>`+ element.PCode + `</td>
                <td>`+ element.Name + `</td>
                <td>`+ element.peopeladdress + `</td>
                <td>`+ element.hamrah + `</td>
                <td>`+ element.sabit + `</td>
                <td>`+ element.NameRec + `</td>
                <td>2</td>
                <td> <input class="customerList form-check-input" name="customerId" type="radio" value="`+ element.PSN + `_` + element.GroupCode + `" id="flexCheckChecked"></td>
                
            </tr>`);
            })
        },
        error: function (data) { }
    });
}
function showInputCity() {
    $("#city").css("display", "flex");
    $("#mantiqah").css("display", "none");
    $("#masir").css("display", "none");
}
function showInputMantiqah() {
    $("#mantiqah").css("display", "flex");
}
function showInputMasir() {
    $("#masir").css("display", "flex");
    $("#mantiqah").css("display", "none");
    $("#city").css("display", "none");
}
function addMantiqah() {
    const cityId = $("#city").val();
    const mantiqahName = $("#inputMantiqah").val();
    $.ajax({
        method: 'get',
        url: baseUrl + "/addMantiqah",
        data: {
            _token: "{{ csrf_token() }}",
            cityId: cityId,
            name: mantiqahName
        },
        async: true,
        success: function (answer) {
            $('#mantiqaBody').empty();
            answer.forEach((element, index) => {
                $('#mantiqaBody').append(
                    `<tr class="subGroupList1" onclick="showMantiqah(this)">
                <td>` + (index + 1) + `</td>
                <td>` + element.NameRec + `</td>
                <td><span><input class="subGroupId"   name="mantiqah" value="`+ element.SnMNM + `" type="radio"></span></td></tr>`);
            });
            $("#addMontiqah").modal("hide");
        },
        error: function (data) { }
    });
}

$("#mantiqahId").on("change", () => {
    $.ajax({
        method: 'get',
        url: baseUrl + "/getMasirs",
        data: {
            _token: "{{ csrf_token() }}",
            mantiqahId: $("#mantiqahId").val()
        },
        async: true,
        success: function (arrayed_result) {
            $('#selectMasir').empty();
            arrayed_result.forEach((element, index) => {
                $("#selectMasir").append(`
                <option value=`+ element.SnMNM + `>` + element.NameRec + `</option>
                `)
            })
        },
        error: function (data) { }
    });
})



$("#cancelChangePrice").on("click", () => {
    $("#moreAlert").css("display", "none");
});

$('#secondPrice').on('keyup', () => {
    if (!$("#secondPrice").val()) {
        $("#secondPrice").val(0);
    }
    $("#secondPrice").val(parseInt($('#secondPrice').val().replace(/\,/g, '')).toLocaleString("en-US"));
    if (parseInt($('#firstPrice').val().replace(/\,/g, '')) > parseInt($('#secondPrice').val().replace(/\,/g, ''))) {
        $("#submitChangePrice").removeAttr('disabled');
        $('#moreAlert').css("display", 'none');
    } else {
        $("#submitChangePrice").prop("disabled", true);
        $('#moreAlert').css("display", 'flex');
        $('#moreAlert').css("color", 'red');
    }
});

$("#firstPrice").on('keyup', function () {
    if (!$("#firstPrice").val()) {
        $("#firstPrice").val(0);
    }
    $("#firstPrice").val(parseInt($('#firstPrice').val().replace(/\,/g, '')).toLocaleString("en-US"));
    if (parseInt($('#firstPrice').val().replace(/\,/g, '')) > parseInt($('#secondPrice').val().replace(/\,/g, ''))) {
        $("#submitChangePrice").removeAttr('disabled');
        $('#moreAlert').css("display", 'none');
    } else {
        $("#submitChangePrice").prop("disabled", true);
        $('#moreAlert').css("display", 'flex');
        $('#moreAlert').css("color", 'red');
    }
});

$('#mainGroupForKalaSearch').on('change', () => {
    var input = $('#mainGroupForKalaSearch').val();
    $.ajax({
        method: 'get',
        url: baseUrl + "/getSubGroupList",
        data: {
            _token: "{{ csrf_token() }}",
            mainGrId: input
        },
        async: true,
        success: function (arrayed_result) {
            arrayed_result = $.parseJSON(arrayed_result);
            $('#subGroupForKalaSearch').empty();
            for (var i = 0; i <= arrayed_result.length - 1; i++) {
                $('#subGroupForKalaSearch').append(`
<option value='` + arrayed_result[i].id + `' >` + arrayed_result[i].title + `</option>
`);
            }
        },
        error: function (data) { }
    });
});

function setBrandStuff(element) {
    $(element).find('input:radio').prop('checked', true);
    let input = $(element).find('input:radio');
    document.querySelector("#editGroupList").disabled = false;
    document.querySelector("#brandChagesSaveBtn").disabled = false;

    let title = input.val().split('_')[1];
    let id = input.val().split('_')[0];
    document.querySelector("#BrandToAddKala").value = id;
    document.querySelector("#brandName").value = title;
    document.querySelector("#brandId").value = id;
    document.querySelector("#deleteBrandId").value = id;
    document.querySelector("#addKalaToBrand").style.display = "flex";
    $.ajax({
        method: 'get',
        url: baseUrl + "/getBrandKala",
        data: {
            _token: "{{ csrf_token() }}",
            brandId: id
        },
        async: true,
        success: function (arrayed_result) {
            if (arrayed_result.length < 1) {
                if (document.querySelector("#deleteBrand")) {
                    document.querySelector("#deleteBrand").disabled = false;
                }
            }
            $('#allKalaOfBrand').empty();
            for (var i = 0; i <= arrayed_result.length - 1; i++) {
                $('#allKalaOfBrand').append(`
    <tr  onclick="checkCheckBox(this,event)">
        <td>` + (i + 1) + `</td>
        <td>` + arrayed_result[i].GoodName + `</td>
        <td>
        <input class="form-check-input" name="kalaListOfBrandIds[]" type="checkbox" value="` +
                    arrayed_result[i].GoodSn + `_` + arrayed_result[i]
                        .GoodName + `" id="kalaId">
        </td>
    </tr>
`);
            }
        },
        error: function (data) { }
    });
    $.ajax({
        method: 'get',
        url: baseUrl + "/getKala",
        data: {
            _token: "{{ csrf_token() }}",
            brandId: id
        },
        async: true,
        success: function (arrayed_result) {
            $('#allKalaForBrand').empty();

            for (var i = 0; i <= arrayed_result.length - 1; i++) {
                $('#allKalaForBrand').append(`
    <tr  onclick="checkCheckBox(this,event)">
        <td>` + (i + 1) + `</td>
        <td>` + arrayed_result[i].GoodName + `</td>
        <td>
        <input class="form-check-input" name="kalaListOfBrandIds[]" type="checkbox" value="` + arrayed_result[i].GoodSn + `_` + arrayed_result[i].GoodName + `" id="kalaId">
        </td>
    </tr>
`);
            }

        },
        error: function (data) { }

    });
}
$("#serachKalaForBrand").on("keyup", function () {
    var searchText = document.querySelector("#serachKalaForBrand").value;
    $.ajax({
        method: 'get',
        async: true,
        dataType: 'text',
        url: baseUrl + "/searchKalas",
        data: {
            _token: "{{ csrf_token() }}",
            searchTerm: searchText
        },
        success: function (answer) {
            $('#allKalaForBrand').empty();
            var answer = JSON.parse(answer);

            for (let index = 0; index < answer.length; index++) {
                $('#allKalaForBrand').append(`
    <tr onclick="checkCheckBox(this,event)">
        <td>` + (index + 1) + `</td>
        <td>` + answer[index].GoodName + `</td>
        <td>
        <input class="form-check-input" name="kalaListOfBrandIds[]" type="checkbox" value="` + answer[index].GoodSn + `_` + answer[index].GoodName + `" id="kalaId">
        </td>
</tr>`);
            }
        }
    });
});
$(document).on('click', '#removeDataFromBrand', (function () {
    $('tr').find('input:checkbox:checked').attr("name", "removeKalaFromBrand[]");
    $('tr').has('input:checkbox:checked').hide();
}));
$(document).on('click', '#addDataToBrand', (function () {
    var kalaListID = [];
    $('input[name="kalaListOfBrandIds[]"]:checked').map(function () {
        kalaListID.push($(this).val());
    });
    $('input[name="kalaListOfBrandIds[]"]:checked').parents('tr').css('color', 'white');
    $('input[name="kalaListOfBrandIds[]"]:checked').parents('tr').children('td').css('background-color', 'red');
    $('input[name="kalaListOfBrandIds[]"]:checked').prop("disabled", true);
    $('input[name="kalaListOfBrandIds[]"]:checked').prop("checked", false);
    for (let i = 0; i < kalaListID.length; i++) {
        $('#allKalaOfBrand').prepend(`<tr class="addedTrBrand">
<td>` + kalaListID[i].split('_')[0] + `</td>
<td>` + kalaListID[i].split('_')[1] + `</td>
<td>
<input class="form-check-input" name="addedKalaToBrand[]" type="checkbox" value="` + kalaListID[i].split('_')[0] + `_` + kalaListID[i].split('_')[1] + `" id="kalaIds" checked>
</td>
</tr>`);
    }
}));



$(document).on('click', '#removeStocksFromWeb', (function () {
    $('tr').find('input:checkbox:checked').attr("name", "removeStocksFromWeb[]");
    $('tr').has('input:checkbox:checked').hide();
}));
$(document).on('click', '#addStockToWeb', (function () {
    var kalaListID = [];
    $('input[name="allStocks[]"]:checked').map(function () {
        kalaListID.push($(this).val());
    });
    $('input[name="allStocks[]"]:checked').parents('tr').css('color', 'white');
    $('input[name="allStocks[]"]:checked').parents('tr').children('td').css('background-color', 'red');
    $('input[name="allStocks[]"]:checked').prop("disabled", true);
    $('input[name="allStocks[]"]:checked').prop("checked", false);

    for (let i = 0; i < kalaListID.length; i++) {
        $('#addedStocks').prepend(`<tr class="addedTrStocks" onclick="checkCheckBox(this,event)">
<td>` + kalaListID[i].split('_')[0] + `</td>
<td>` + kalaListID[i].split('_')[1] + `</td>
<td>
<input class="form-check-input" name="addedStocksToWeb[]" type="checkbox" value="` + kalaListID[i].split('_')[0] + `_` + kalaListID[i].split('_')[1] + `" id="kalaIds" checked>
</td>
</tr>`);
    }
}));
$('#subGroupForKalaSearch').on('change', () => {
    const input = $('#subGroupForKalaSearch').val();
    $.ajax({
        method: 'get',
        url: baseUrl + "/searchKalaBySubGroup",
        async: true,
        data: {
            _token: "{{ csrf_token() }}",
            id: input
        },
        success: function (arrayed_result) {
            // $('.crmDataTable').dataTable().fnDestroy();
            $('#kalaContainer').empty();
            for (var i = 0; i <= arrayed_result.length - 1; i++) {
                $('#kalaContainer').append(`<tr>
<td>` + (i + 1) + `</td>
<td>` + arrayed_result[i].GoodName + `</td>
<td>` + arrayed_result[i].NameGRP + `</td>
<td>` + parseInt(arrayed_result[i].Price4 / 10).toLocaleString("en-US") + `</td>
<td>` + parseInt(arrayed_result[i].Price3 / 10).toLocaleString("en-US") + `</td>
<td>` + arrayed_result[i].GoodExists + `</td>
<td>
<input class="kala form-check-input" name="kalaId[]" type="radio" value="` + arrayed_result[i].GoodSn + `_` + arrayed_result[i].Price4 + `_` + arrayed_result[i].Price3 + `" id="flexCheckCheckedKala">
</td>
</tr>`);
            }
            // $('.crmDataTable').dataTable();
        },
        error: function (data) { }
    });
});
(function runForever() {
    $('.buyFromHome').fadeOut('slow');
    $('.preBuyFromHome').fadeOut('slow');
    $("#messageList").scrollTop($("#messageList").prop("scrollHeight"));
    $("#modalBody").scrollTop($("#modalBody").prop("scrollHeight"));
    setTimeout(runForever, 13000)
})();

function newMessageAdded() {
    $('.buyFromHome').fadeOut('slow');
    $('.preBuyFromHome').fadeOut('slow');
    $("#messageList").scrollTop($("#messageList").prop("scrollHeight"));
    $("#modalBody").scrollTop($("#modalBody").prop("scrollHeight"));
}

function editAdmins(element) {
    $(element).find('input:radio').prop('checked', true);
    let inp = $(element).find('input:radio');
    $('td.selected').removeClass("selected");
    $(element).children('td').addClass('selected');
    $('#editAdminId').val(inp.val());
}




$("#changePriceForm").on("submit", function (e) {
    $.ajax({
        url: $(this).attr('action'),
        type: $(this).attr('method'),
        dataType: 'json',
        data: $(this).serialize(),
        success: function (data) {
            $("#changePriceModal").modal("hide");
            alert("قیمت موفقانه تغییر کرد.");
            window.location.reload();
        },
        error: function (xhr, err) {
            alert('Error');
        }
    });
    e.preventDefault();
});



function openEditDashboard() {
    let adminId = $("#editAdminId").val();
    $.ajax({
        method: 'get',
        url: baseUrl + "/getAdminInfo",
        data: {
            _token: "{{ csrf_token() }}",
            searchTerm: adminId
        },
        async: true,
        success: function (data) {

            let gender = data.sex.trim();
            let activeState = data.activeState;
            let adminType = data.adminType;
            if (gender == "female") {
                $("#womanGender").prop("checked", true).change();
            } else {
                $("#manGender").prop("checked", true).change();
            }
            switch (adminType.trim()) {
                case "super":
                    adminType = 1
                    break;
                case "admin":
                    adminType = 2;
                    break;
                default:
                    adminType = 3;
                    break;
            }
            $('select>option:eq(' + adminType + ')').attr('selected', 'selected');
            if (activeState == 1) {
                $("#activeState").prop("checked", true);
            } else {
                $("#activeState").prop("checked", false);
            }

            switch (parseInt(data.mainPageSetting)) {
                case 2:
                    $("#deletMainPageSettingED").prop("checked", true).change();
                    break;
                case 1:
                    $("#editManiPageSettingN").prop("checked", true).change();
                    break;
                case 0:
                    $("#seeManiPageSettingED").prop("checked", true).change();
                    break;
                case -1:
                    $("#seeManiPageSettingED").prop("checked", false).change();
                    break
                default:
                    $("#seeManiPageSettingED").prop("checked", false).change();
                    break;
            }
            switch (parseInt(data.specialSettingN)) {
                case 2:
                    $("#deleteSpecialSettingED").prop("checked", true).change();
                    break;
                case 1:
                    $("#editSpecialSettingED").prop("checked", true).change();
                    break;
                case 0:
                    $("#seeSpecialSettingED").prop("checked", true).change();
                    break;
                case -1:
                    $("#seeSpecialSettingED").prop("checked", false).change();
                    break
                default:
                    $("#seeSpecialSettingED").prop("checked", false).change();
                    break;
            }
            switch (parseInt(data.emptyazSettingN)) {
                case 2:
                    $("#deleteEmtyazSettingED").prop("checked", true).change();
                    break;
                case 1:
                    $("#editEmtyazSettingED").prop("checked", true).change();
                    break;
                case 0:
                    $("#seeEmtyazSettingED").prop("checked", true).change();
                    break;
                case -1:
                    $("#seeEmtyazSettingED").prop("checked", false).change();
                    break
                default:
                    $("#seeEmtyazSettingED").prop("checked", false).change();
                    break;
            }

            switch (parseInt(data.customersN)) {
                case 2:
                    $("#deleteCustomersED").prop("checked", true).change();
                    break;
                case 1:
                    $("#editCustomersED").prop("checked", true).change();
                    break;
                case 0:
                    $("#seeCustomersED").prop("checked", true).change();
                    break;
                case -1:
                    $("#seeCustomersED").prop("checked", false).change();
                    break
                default:
                    $("#seeCustomersED").prop("checked", false).change();
                    break;
            }
            switch (parseInt(data.kalaListsN)) {
                case 2:
                    $("#deleteKalaListED").prop("checked", true).change();
                    break;
                case 1:
                    $("#editKalaListED").prop("checked", true).change();
                    break;
                case 0:
                    $("#seeKalaListED").prop("checked", true).change();
                    break;
                case -1:
                    $("#seeKalaListED").prop("checked", false).change();
                    break
                default:
                    $("#seeKalaListED").prop("checked", false).change();
                    break;
            }
            switch (parseInt(data.requestedKalaN)) {
                case 2:
                    $("#deleteRequestedKalaED").prop("checked", true).change();
                    break;
                case 1:
                    $("#editRequestedKalaED").prop("checked", true).change();
                    break;
                case 0:
                    $("#seeRequestedKalaED").prop("checked", true).change();
                    break;
                case -1:
                    $("#seeRequestedKalaED").prop("checked", false).change();
                    break
                default:
                    $("#seeRequestedKalaED").prop("checked", false).change();
                    break;
            }
            switch (parseInt(data.fastKalaN)) {
                case 2:
                    $("#deleteFastKalaED").prop("checked", true).change();
                    break;
                case 1:
                    $("#editFastKalaED").prop("checked", true).change();
                    break;
                case 0:
                    $("#seeFastKalaED").prop("checked", true).change();
                    break;
                case -1:
                    $("#seeFastKalaED").prop("checked", false).change();
                    break
                default:
                    $("#seeFastKalaED").prop("checked", false).change();
                    break;
            }

            switch (parseInt(data.pishKharidN)) {
                case 2:
                    $("#deletePishKharidED").prop("checked", true).change();
                    break;
                case 1:
                    $("#editPishKharidED").prop("checked", true).change();
                    break;
                case 0:
                    $("#seePishKharidED").prop("checked", true).change();
                    break;
                case -1:
                    $("#seePishKharidED").prop("checked", false).change();
                    break
                default:
                    $("#seePishKharidED").prop("checked", false).change();
                    break;
            }
            switch (parseInt(data.brandsN)) {
                case 2:
                    $("#deleteBrandsED").prop("checked", true).change();
                    break;
                case 1:
                    $("#editBrandsED").prop("checked", true).change();
                    break;
                case 0:
                    $("#seeBrandsED").prop("checked", true).change();
                    break;
                case -1:
                    $("#seeBrandsED").prop("checked", false).change();
                    break
                default:
                    $("#seeBrandsED").prop("checked", false).change();
                    break;
            }

            switch (parseInt(data.alertedN)) {
                case 2: $("#deleteAlertedED").prop("checked", true).change();
                    break;
                case 1: $("#editAlertedED").prop("checked", true).change();
                    break;
                case 0: $("#seeAlertedED").prop("checked", true).change();
                    break;
                case -1: $("#seeAlertedED").prop("checked", false).change();
                    break;
                default: $("#seeAlertedED").prop("checked", false).change();
                    break;
            }

            switch (parseInt(data.kalaGroupN)) {
                case 2: $("#deletKalaGroupED").prop("checked", true).change();
                    break;
                case 1: $("#editKalaGroupED").prop("checked", true).change();
                    break;
                case 0: $("#seeKalaGroupED").prop("checked", true).change();
                    break;
                case -1: $("#seeKalaGroupED").prop("checked", false).change();
                    break;
                default: $("#seeKalaGroupED").prop("checked", false).change();
                    break;
            }

            switch (parseInt(data.orderSalesN)) {
                case 2: $("#deleteOrderSalesED").prop("checked", true).change();
                    break;
                case 1: $("#editOrderSalesED").prop("checked", true).change();
                    break;
                case 0: $("#seeSalesOrderED").prop("checked", true).change();
                    break;
                case -1: $("#seeSalesOrderED").prop("checked", false).change();
                    break;
                default: $("#seeSalesOrderED").prop("checked", false).change();
                    break;
            }

            switch (parseInt(data.messageN)) {
                case 2: $("#deleteMessageED").prop("checked", true).change();
                    break;
                case 1: $("#editMessageED").prop("checked", true).change();
                    break;
                case 0: $("#seeMessageED").prop("checked", true).change();
                    break;
                case -1: $("#seeMessageED").prop("checked", false).change();
                    break;
                default: $("#seeMessageED").prop("checked", false).change();
                    break;
            }

            switch (parseInt(data.customerListN)) {
                case 2: $("#deletCustomerListED").prop("checked", true).change();
                    break;
                case 1: $("#editCustomerListED").prop("checked", true).change();
                    break;
                case 0: $("#seeCustomerListED").prop("checked", true).change();
                    break;
                case -1: $("#seeCustomerListED").prop("checked", false).change();
                    break;
                default: $("#seeCustomerListED").prop("checked", false).change();
                    break;
            }

            switch (parseInt(data.officialCustomerN)) {
                case 2: $("#deleteOfficialCustomerED").prop("checked", true).change();
                    break;
                case 1: $("#editOfficialCustomerED").prop("checked", true).change();
                    break;
                case 0: $("#seeOfficialCustomerED").prop("checked", true).change();
                    break;
                case -1: $("#seeOfficialCustomerED").prop("checked", false).change();
                    break;
                default: $("#seeOfficialCustomerED").prop("checked", false).change();
                    break;
            }

            switch (parseInt(data.lotteryResultN)) {
                case 2: $("#deletLotteryResultED").prop("checked", true).change();
                    break;
                case 1: $("#editLotteryResultED").prop("checked", true).change();
                    break;
                case 0: $("#seeLotteryResultED").prop("checked", true).change();
                    break;
                case -1: $("#seeLotteryResultED").prop("checked", false).change();
                    break;
                default: $("#seeLotteryResultED").prop("checked", false).change();
                    break;
            }


            switch (parseInt(data.gamerListN)) {
                case 2: $("#deletGamerListED").prop("checked", true).change();
                    break;
                case 1: $("#editGamerListED").prop("checked", true).change();
                    break;
                case 0: $("#seeGamerListED").prop("checked", true).change();
                    break;
                case -1: $("#seeGamerListED").prop("checked", false).change();
                    break;
                default: $("#seeGamerListED").prop("checked", false).change();
                    break;
            }

            switch (parseInt(data.onlinePaymentN)) {
                case 2: $("#deleteOnlinePaymentED").prop("checked", true).change();
                    break;
                case 1: $("#editOnlinePaymentED").prop("checked", true).change();
                    break;
                case 0: $("#seeOnlinePaymentED").prop("checked", true).change();
                    break;
                case -1: $("#seeOnlinePaymentED").prop("checked", false).change();
                    break;
                default: $("#seeOnlinePaymentED").prop("checked", false).change();
                    break;
            }


            $("#userName").val(data.userName);
            $("#name").val(data.name);
            $("#lastName").val(data.lastName);
            $("#password").val(data.password);

            if (!($('.modal.in').length)) {
                $('.modal-dialog').css({
                    left: 111,
                    top: 0
                });
            }
            $('#editUserRoles').modal({
                backdrop: false,
                show: true
            });

            $('.modal-dialog').draggable({
                handle: ".modal-header"
            });
            $("#editUserRoles").modal("show");
        },
        error: function (data) { }
    });


}



$(document).on('keyup', '#mainGroupSearchFast', (() => {
    let searchTerm = $('#mainGroupSearchFast').val();
    console.log(searchTerm);

    $.ajax({
        method: 'get',
        url: baseUrl + "/getMainGroupList",
        data: {
            _token: "{{ csrf_token() }}",
            searchTerm: searchTerm
        },
        async: true,
        success: function (arrayed_result) {
            $('#mainGroupListfast').empty();
            for (var i = 0; i <= arrayed_result.length - 1; i++) {
                $('#mainGroupListfast').append(`
                <tr onclick="changePicture(this)"> 
                    <td>` + (i + 1) + `</td>
                    <td>` + arrayed_result[i].title + `</td>
                    <td>
                        <input class="mainGroupId" type="radio" name="mainGroupId[]" value="` + arrayed_result[i].id + '_' + arrayed_result[i].title + `" id="flexCheckChecked">
                    </td>
                </tr>

                `);
            }
        },
        error: function (data) { }
    });
}));


$(document).on('keyup', '#serachSubGroupId', (() => {
    let searchTerm = $('#serachSubGroupId').val();
    let mainGrId = document.querySelector('.mainGroupId:checked').value.split('_')[0];
    $.ajax({
        method: 'get',
        url: baseUrl + "/getSubGroupList",
        data: {
            _token: "{{ csrf_token() }}",
            searchTerm: searchTerm,
            mainGrId: mainGrId
        },
        async: true,
        success: function (data) {
            $('#subGroup1').empty();
            for (var i = 0; i <= arrayed_result.length - 1; i++) {
                $('#subGroup1').append(
                    `<tr class="subGroupList1" onClick="changeId(this)">
    <td>` + (i + 1) + `</td>
    <td>` + data[i].title + `</td>
    <td>
        <input class="subGroupId"   name="subGroupId[]" value="` + data[i].id + `_` + data[i].selfGroupId + `_` + data[i].percentTakhf + `_` + data[i].title + `" type="radio" id="flexCheckChecked` + i + `">
</td>`);
            }
        },
        error: function (data) { }

    });
}));

function activeSubmitButton(element) {

    if (element.id == "callOnSale") {
        if (element.checked) {
            document.querySelector("#zeroExistance").checked = false;
            document.querySelector("#showTakhfifPercent").checked = false;
            document.querySelector("#showFirstPrice").checked = false;
            document.querySelector("#freeExistance").checked = false;
            document.querySelector("#activePreBuy").checked = false;
        } else { }
    }
    if (element.id == "inactiveAll") {
        if (element.checked) {
            document.querySelector("#zeroExistance").checked = false;
            document.querySelector("#showTakhfifPercent").checked = false;
            document.querySelector("#showFirstPrice").checked = false;
            document.querySelector("#freeExistance").checked = false;
            document.querySelector("#activePreBuy").checked = false;
            document.querySelector("#callOnSale").checked = false;
        } else { }
    }
    if (element.id == "zeroExistance") {
        if (element.checked) {
            document.querySelector("#callOnSale").checked = false;
            document.querySelector("#showTakhfifPercent").checked = false;
            document.querySelector("#showFirstPrice").checked = false;
            document.querySelector("#freeExistance").checked = false;
            document.querySelector("#activePreBuy").checked = false;
        } else { }
    }
    if (element.id == "showTakhfifPercent") {
        if (element.checked) {
            document.querySelector("#zeroExistance").checked = false;
            document.querySelector("#callOnSale").checked = false;
        } else { }
    }

    if (element.id == "showFirstPrice") {
        if (element.checked) {
            document.querySelector("#callOnSale").checked = false;
            document.querySelector("#zeroExistance").checked = false;
        } else { }
    }
    if (element.id == "freeExistance") {
        if (element.checked) {
            document.querySelector("#callOnSale").checked = false;
            document.querySelector("#zeroExistance").checked = false;
        } else { }
    }

    if (element.id == "activePreBuy") {
        if (element.checked) {
            document.querySelector("#callOnSale").checked = false;
            document.querySelector("#zeroExistance").checked = false;
        } else {
            //do nothing
        }
    }
    $("#restrictStuffId").prop("disabled", false);
}


$(document).on('submit', '#addOrDeleteKalaSubmit', function () {
    $.ajax({
        url: $(this).attr('action'),
        type: $(this).attr('method'),
        dataType: 'json',
        data: $(this).serialize(),
        success: function (data) { },
        error: function (xhr, err) {
            alert('Error');
        }
    });
    return false;
});

$(document).on("change", "#showTakhfifPercent", () => {
    if ($("#showTakhfifPercent").is(":checked")) {
        $("#showFirstPrice").prop("checked", true);
        $("#showFirstPrice").prop("disabled", true);
    } else {
        $("#showFirstPrice").prop("checked", false);
        $("#showFirstPrice").prop("disabled", false);
    }
});



$("#sameKalaList").change(function () {
    if ($("#sameKalaList").is(':checked')) {
        $("#addKalaToList").css("display", "flex");
        $("#addAndDelete").css("display", "flex");
        $("#addToListSubmit").css("display", "flex");
        $("#addedList").css("display", "flex");
        let mainKalaId = $("#mainKalaId").val();
        $.ajax({
            method: 'get',
            url: baseUrl + "/getAllKalas",
            data: { _token: "{{ csrf_token() }}", mainKalaId: mainKalaId },
            dataType: "json",
            async: true,
            success: function (arrayed_result) {
                $('#allKalaForList').empty();
                for (var i = 0; i <= arrayed_result.length - 1; i++) {
                    $('#allKalaForList').append(`
        <tr  onclick="checkCheckBox(this,event)">
            <td>` + (i + 1) + `</td>
            <td>` + arrayed_result[i].GoodName + `</td>
            <td>
            <input class="form-check-input" name="kalaListForList[]" type="checkbox" value="` +
                        arrayed_result[i].GoodSn + `_` + arrayed_result[i]
                            .GoodName + `" id="kalaId">
            </td>
        </tr>
        `);
                }
            },
            error: function (data) { }
        });
    } else {
        $("#addKalaToList").css("display", "none");
        $("#addAndDelete").css("display", "none");
        $("#addToListSubmit").css("display", "none");
    }
});
$('#mainPic').on('change', () => {
    $("#submitChangePic").prop('disabled', false);
}

);


//used for adding kala to List to the left side(kalaList)
$(document).on('click', '#addDataToList', (function () {

    var kalaListID = [];
    $('input[name="kalaListForList[]"]:checked').map(function () {
        kalaListID.push($(this).val());
    });
    $("#addToListSubmit").prop("disabled", false);
    $('input[name="kalaListForList[]"]:checked').parents('tr').css('color', 'white');
    $('input[name="kalaListForList[]"]:checked').parents('tr').children('td').css('background-color', 'red');
    $('input[name="kalaListForList[]"]:checked').prop("disabled", true);
    $('input[name="kalaListForList[]"]:checked').prop("checked", false);
    for (let i = 0; i < kalaListID.length; i++) {
        $('#allKalaOfList').append(`<tr class="addedTrList">
<td>` + (i + 1) + `</td>
<td>` + kalaListID[i].split('_')[1] + `</td>
<td>
<input class="addKalaToList form-check-input" name="addedKalaToList[]" type="checkbox" value="` + kalaListID[i].split('_')[0] + `_` + kalaListID[i].split('_')[1] + `" id="kalaIds" checked>
</td>
</tr>`);

    }
}));

//used for removing data from assame List
$(document).on('click', '#removeDataFromList', (function () {
    $('tr').find('input:checkbox:checked').attr("name", "removeKalaFromList[]");
    $('tr').has('input:checkbox:checked').hide();
}));
$('#serachKalaForAssameList').on('keyup', () => {
    let searchTerm = $("#serachKalaForAssameList").val();
    $.ajax({
        method: 'get',
        url: baseUrl + "/searchKalaByName",
        async: true,
        data: {
            _token: "{{ csrf_token() }}",
            name: searchTerm
        },
        success: function (arrayed_result) {
            $('#allKalaForList').empty();

            for (var i = 0; i <= arrayed_result.length - 1; i++) {
                $('#allKalaForList').append(`
    <tr  onclick="checkCheckBox(this,event)">
        <td>` + (i + 1) + `</td>
        <td>` + arrayed_result[i].GoodName + `</td>
        <td>
        <input class="form-check-input" name="kalaListForList[]" type="checkbox" value="` +
                    arrayed_result[i].GoodSn + `_` + arrayed_result[i]
                        .GoodName + `" id="kalaId">
        </td>
    </tr>
`);
            }
        },
        error: function (data) { }

    });

});




function addOrDeleteKala(element) {
    let input = $(element).find('input:checkbox');
    if (input.is(":checked")) {
        input.prop("checked", false);
        input.prop("name", 'removables[]');
        $("#submitSubGroup").prop("disabled", false);
    } else {
        input.prop("checked", true);
        input.prop("name", 'addables[]');
        $("#submitSubGroup").prop("disabled", false);
    }
}
$(document).on("click", "#submitSubGroup", () => {
    var addableStuff = [];
    let kalaId = $("#kalaIdEdit").val();
    $('input[name="addables[]"]:checked').map(function () {
        addableStuff.push($(this).val());
    });
    var removableStuff = [];
    $('input[name="removables[]"]:not(:checked)').map(function () {
        removableStuff.push($(this).val());
    });
    $.ajax({
        type: "get",
        url: baseUrl + "/addOrDeleteKalaFromSubGroup",
        data: {
            _token: "{{ csrf_token() }}",
            addableStuff: addableStuff,
            removableStuff: removableStuff,
            kalaId: kalaId
        },
        dataType: "json",
        success: function (msg) {
            $('#submitSubGroup').prop("disabled", true);
        },
        error: function (msg) {
            console.log(msg);
        }
    });
});

$(document).on("submit", "#addDescKala", () => {

    $.ajax({
        url: $(this).attr('action'),
        type: $(this).attr('method'),
        dataType: 'json',
        data: $(this).serialize(),
        success: function (data) { },
        error: function (xhr, err) {
            alert('Error');
        }
    });
    return false;
});


$(document).on("keyup", '#pishKharidFirst', () => {
    $.ajax({
        method: 'get',
        url: baseUrl + "/searchPreBuyAbleKalas",
        async: true,
        data: {
            _token: "{{ csrf_token() }}",
            searchTerm: $('#pishKharidFirst').val()
        },
        success: function (arrayed_result) {

            $('#kalaList').empty();
            for (var i = 0; i <= arrayed_result.length - 1; i++) {
                $('#kalaList').append(`
<tr onclick="checkCheckBox(this,event)">
    <td>` + (i + 1) + `</td>
    <td>` + arrayed_result[i].GoodName + `</td>
    <td>
        <input class="mainGroupId" type="checkBox"
            name="kalaListIds" value="` + arrayed_result[i].GoodSn + `_` + arrayed_result[i].GoodName + `"
            id="flexCheckChecked">
    </td>
</tr>`);
            }
        },
        error: function (data) {
            alert("پیدا نشد");
        }

    });
});


//for buying something
$(document).on('click', '.addData', (function () {
    let amountUnit = $(this).val().split('_')[0];
    let productId = $(this).val().split('_')[1];
    let amountExist = parseInt($('#amountExist').val());
    let costLimit = parseInt($("#costLimit").val());
    let costError = $("#costError").val();

    if (amountUnit > amountExist) {
        alert("حد اکثر مقدار خرید شما " + amountExist + " " + $(this).val().split('_')[2] + " می باشد ");
    } else {
        if (costLimit > 0) {
            if (amountUnit >= costLimit) {
                alert(costError);
            }
        }
        var showText = $(this).text()
        $.ajax({
            type: "get",
            url: baseUrl + "/buySomething",
            data: { _token: "{{ csrf_token() }}", kalaId: productId, amountUnit: amountUnit },
            dataType: "json",
            success: function (lastOrderId) {
                $('#bought' + productId).prepend(`<a class='btn-add-to-cart' value=''
    onclick='UpdateQty(` + productId + `, this, ` + lastOrderId + `)
' style='width:auto;text-align: center;padding-right: 10px;background-color: #51ef39;
font-weight: bold;' id="updatedBought` + productId + `"
' class='updateData btn-add-to-cart '>` + showText + `</a>`);
                $('#noBought' + productId).css('display', 'none');
                var buys = parseInt($('#basketCountWeb').text());
                var buysBottom = parseInt($('#basketCountWebBottom').text());
                $('#basketCountWeb').text(buys + 1)
                $('#basketCountWebBottom').text(buysBottom + 1)
                $('#basketCountWeb').addClass("headerNotifications1");
                $('#basketCountWebBottom').addClass("headerNotifications1");
            },
            error: function (msg) {
                alert("مشکل در مودال خرید داریم!");
            }
        });
    }
}));


//for pishKharid something
$(document).on('click', '.addPishKharid', (function () {
    let amountUnit = $(this).val().split('_')[0];
    let productId = $(this).val().split('_')[1];
    let amountExist = parseInt($('#amountExist').val());
    let costLimit = parseInt($("#costLimit").val());
    let costError = $("#costError").val();

    if (costLimit > 0) {
        if (amountUnit >= costLimit) {
            alert(costError);
        }
    }
    var showText = $(this).text();
    $.ajax({
        type: "get",
        url: baseUrl + "/pishKharidSomething",
        data: { _token: "{{ csrf_token() }}", kalaId: productId, amountUnit: amountUnit },
        dataType: "json",
        success: function (lastOrderId) {
            $("#preBought" + productId).css("display", "none");
            $("#beforeBought" + productId).prepend(
                `<a class='btn-add-to-cart' value=''
        onclick='updatePishKharid(` + productId + `,this,` + lastOrderId + `)'
        style='width:auto;text-align: center;    padding-right: 10px;
        background-color: #6e3f06;
        font-weight: bold;'
        class='updateData btn-add-to-cart'>
    ` + showText + `</a>`
            );

        },
        error: function (msg) {
            console.log(msg);
        }
    });
}));

function updatePishKharid(code, event, SnOrderBYS) {
    $.ajax({
        type: "get",
        url: baseUrl + '/getUnitsForUpdatePishKharid',
        data: {
            _token: "{{ csrf_token() }}",
            Pcode: code
        },
        dataType: "json",
        success: function (msg) {
            $("#unitStuffContainer").html(msg);
            $(".SnOrderBYS").val(SnOrderBYS);
            const modal = document.querySelector('.modalBackdrop');
            const modalContent = modal.querySelector('.modal');
            modal.classList.add('active');
            modal.addEventListener('click', () => {
                modal.classList.remove('active');
            });
        },
        error: function (msg) {
            console.log(msg);
        }
    });
}
//for updating PishKharid
$(document).on('click', '.updatePishKharid', (function () {
    let amountUnit = $(this).val().split('_')[0];
    let productId = $(this).val().split('_')[1];
    let amountExist = parseInt($('#amountExist').val());
    let costLimit = parseInt($("#costLimit").val());
    let costError = $("#costError").val();
    var orderId = document.querySelector('.SnOrderBYS').value;
    var showText = $(this).text();
    $.ajax({
        type: "get",
        url: baseUrl + "/updateOrderPishKharid",
        data: {
            _token: "{{ csrf_token() }}",
            kalaId: productId,
            amountUnit: amountUnit,
            orderBYSSn: orderId
        },
        dataType: "json",
        success: function (msg) {
            $('#updatedPishKharid' + productId).text(showText);
        },
        error: function (msg) {
            console.log(msg);
        }
    });
}));

function addToBuy(id, partId) {
    let firstVale = parseInt(document.querySelector("#BuyNumber" + partId + '_' + id).innerText);
    if (firstVale == 0) {
        firstVale++;
        $.ajax({
            type: "get",
            url: baseUrl + "/buySomethingFromHome",
            data: { _token: "{{ csrf_token() }}", kalaId: id, amountUnit: firstVale },
            dataType: "json",
            success: function (lastOrderId) {
                document.querySelector("#BuyNumber" + partId + '_' + id).innerText = firstVale;
                document.querySelector("#orderNumber" + partId + '_' + id).value = lastOrderId;
                document.querySelector('#buySign' + partId + '_' + id).style = "font-size:27px;color:green";
                var buys = parseInt($('#basketCountWeb').text());
                var buysBottom = parseInt($('#basketCountWebBottom').text());
                $('#basketCountWeb').text(buys + 1)
                $('#basketCountWebBottom').text(buysBottom + 1)
                $('#basketCountWeb').addClass("headerNotifications1");
                $('#basketCountWebBottom').addClass("headerNotifications1");
            },
            error: function (msg) {
                console.log(msg);
            }
        });
    } else {
        firstVale++;
        let orderId = document.querySelector("#orderNumber" + partId + '_' + id).value;
        $.ajax({
            type: "get",
            url: baseUrl + "/updateOrderBYSFromHome",
            data: {
                _token: "{{ csrf_token() }}",
                kalaId: id,
                amountUnit: firstVale,
                orderBYSSn: orderId
            },
            dataType: "json",
            success: function (msg) {
                document.querySelector("#BuyNumber" + partId + '_' + id).innerText = firstVale;
                if (firstVale > 0) {
                    document.querySelector('#buySign' + partId + '_' + id).style = "font-size:27px;color:green";
                } else {
                    document.querySelector('#buySign' + partId + '_' + id).style = "font-size:30px;color:red";
                }
            },
            error: function (msg) {
                console.log(msg);
            }
        });
    }

}

function addToPreBuy(id, partId) {
    let firstVale = parseInt(document.querySelector("#preBuyNumber" + partId + '_' + id).innerText);
    if (firstVale == 0) {
        firstVale++;
        $.ajax({
            type: "get",
            url: baseUrl + "/preBuySomethingFromHome",
            data: { _token: "{{ csrf_token() }}", kalaId: id, amountUnit: firstVale },
            dataType: "json",
            success: function (lastOrderId) {
                document.querySelector("#preBuyNumber" + partId + '_' + id).innerText = firstVale;
                document.querySelector("#preOrderNumber" + partId + '_' + id).value = lastOrderId;
                document.querySelector('#preBuySign' + partId + '_' + id).style = "font-size:27px;color:green";
            },
            error: function (msg) {
                console.log(msg);
            }
        });
    } else {
        firstVale++;
        let orderId = document.querySelector("#preOrderNumber" + partId + '_' + id).value;

        $.ajax({
            type: "get",
            url: baseUrl + "/updatePreOrderBYSFromHome",
            data: {
                _token: "{{ csrf_token() }}",
                kalaId: id,
                amountUnit: firstVale,
                orderBYSSn: orderId
            },
            dataType: "json",
            success: function (msg) {
                document.querySelector("#preBuyNumber" + partId + '_' + id).innerText = firstVale;
                if (firstVale > 0) {
                    document.querySelector('#preBuySign' + partId + '_' + id).style = "font-size:27px;color:green";
                } else {
                    document.querySelector('#preBuySign' + partId + '_' + id).style = "font-size:30px;color:red";
                }
            },
            error: function (msg) {
                console.log(msg);
            }
        });
    }

}

function subFromPreBuy(id, partId) {
    let firstVale = parseInt(document.querySelector("#preBuyNumber" + partId + '_' + id).innerText);
    let orderId = document.querySelector("#preOrderNumber" + partId + '_' + id).value;
    if (firstVale > 0) {
        firstVale--;
        if (firstVale == 0) {
            document.querySelector('#preBuySign' + partId + '_' + id).style = "font-size:30px;color:red";
        }
        $.ajax({
            type: "get",
            url: baseUrl + "/updatePreOrderBYSFromHome",
            data: {
                _token: "{{ csrf_token() }}",
                kalaId: id,
                amountUnit: firstVale,
                orderBYSSn: orderId
            },
            dataType: "json",
            success: function (msg) {
                document.querySelector("#preBuyNumber" + partId + '_' + id).innerText = firstVale;
            },
            error: function (msg) {
                console.log(msg);
            }
        });
    }
}

function subFromBuy(id, partId) {
    let firstVale = parseInt(document.querySelector("#BuyNumber" + partId + '_' + id).innerText);
    let orderId = document.querySelector("#orderNumber" + partId + '_' + id).value;
    if (firstVale > 0) {
        firstVale--;
        if (firstVale == 0) {
            document.querySelector('#buySign' + partId + '_' + id).style = "font-size:27px;color:red";
            var buys = parseInt($('#basketCountWeb').text());
            if (buys > 0) {
                buys = buys - 1;
                $('#basketCountWeb').text(buys);
                $('#basketCountWebBottom').text(buys);

                if (buys == 0) {
                    $('#basketCountWeb').removeClass("headerNotifications1");
                    $('#basketCountWeb').addClass("headerNotifications0");
                    $('#basketCountWebBottom').removeClass("headerNotifications1");
                    $('#basketCountWebBottom').addClass("headerNotifications0");
                    $(".cont").css('display', 'none');
                }
            }
        }
        $.ajax({
            type: "get",
            url: baseUrl + "/updateOrderBYSFromHome",
            data: {
                _token: "{{ csrf_token() }}",
                kalaId: id,
                amountUnit: firstVale,
                orderBYSSn: orderId
            },
            dataType: "json",
            success: function (msg) {
                document.querySelector("#BuyNumber" + partId + '_' + id).innerText = firstVale;
            },
            error: function (msg) {
                console.log(msg);
            }
        });
    }
}

function buyFromHome(id, partId) {
    if (document.querySelector('#buyFromHome' + partId + '_' + id).style.display == "flex") {
        document.querySelector('#buyFromHome' + partId + '_' + id).style = "display:none";
    } else {
        document.querySelector('#buyFromHome' + partId + '_' + id).style = "display:flex";
    }
}

function preBuyFromHome(id, partId) {
    if (document.querySelector('#preBuyFromHome' + partId + '_' + id).style.display == "flex") {
        document.querySelector('#preBuyFromHome' + partId + '_' + id).style = "display:none";
    } else {
        document.querySelector('#preBuyFromHome' + partId + '_' + id).style = "display:flex";
    }
}



//for updating buy of kala
$(document).on('click', '.updateData', (function () {
    let amountUnit = $(this).val().split('_')[0];
    let productId = $(this).val().split('_')[1];
    let amountExist = parseInt($('#amountExist').val());
    let costLimit = parseInt($("#costLimit").val());
    let costError = $("#costError").val();
    if (amountUnit > amountExist) {
        alert("حد اکثر مقدار خرید شما " + amountExist + " " + $(this).val().split('_')[3] + " می باشد ");
    } else {
        if (costLimit > 0) {
            if (amountUnit >= costLimit) {
                alert(costError);
            }
        }
        var orderId = document.querySelector('.SnOrderBYS').value;
        var showText = $(this).text();
        $.ajax({
            type: "get",
            url: baseUrl + "/updateOrderBYS",
            data: {
                _token: "{{ csrf_token() }}",
                kalaId: productId,
                amountUnit: amountUnit,
                orderBYSSn: orderId
            },
            dataType: "json",
            success: function (msg) {
                $('#updatedBought' + productId).text(showText);
                let firstPrice = parseInt($("#Price" + orderId).val());
                let secondPrice = parseInt(msg / parseInt($("#Currency" + orderId).val()));
                let lastPrice = secondPrice - firstPrice;
                let allMoney = (parseInt($("#allMoneyToSend").val()) + lastPrice);
                $(".allMoney").text(allMoney.toLocaleString("en-US"));
                $("#orderBYS" + orderId).text((msg / $("#Currency" + orderId).val()).toLocaleString("en-US"));
                let minSaleMoney = parseInt($("#minSalePrice").val());
                if (minSaleMoney > allMoney) {
                    $("#notSufficient").css({ "display": "flex" });
                    $("#continueBuy").css({ "display": "none" });
                    $("#ContinueBasket").css({ "display": "none" });
                } else {
                    $("#notSufficient").css({ "display": "none" });
                    $("#continueBuy").css({ "display": "flex" });
                }
            },
            error: function (msg) {
                console.log(msg);
            }
        });
    }
}));


//for setting minimam saling of kala
$(document).on('click', '.setMinSale', (function () {
    var amountUnit = $(this).val().split('_')[0];
    var productId = $(this).val().split('_')[1];
    $.ajax({
        type: "get",
        url: baseUrl + "/setMinimamSaleKala",
        data: { _token: "{{ csrf_token() }}", kalaId: productId, amountUnit: amountUnit },
        dataType: "json",
        success: function (msg) {
            $('#minSaleValue').text(msg);
        },
        error: function (msg) {
            console.log(msg);
        }
    });
}));



//for setting maximam saling of kala
$(document).on('click', '.setMaxSale', (function () {
    var amountUnit = $(this).val().split('_')[0];
    var productId = $(this).val().split('_')[1];
    $.ajax({
        type: "get",
        url: baseUrl + "/setMaximamSaleKala",
        data: { _token: "{{ csrf_token() }}", kalaId: productId, amountUnit: amountUnit },
        dataType: "json",
        success: function (msg) {
            $('#maxSaleValue').text(msg);
        },
        error: function (msg) {
            console.log(msg);
        }
    });
}));


$(document).on('click', '#technicalCharecteristic', (() => {
    const discription = $('#describeKala').val();
    const kalaId = $('#GoodSn').val();
    $.ajax({
        type: "get",
        url: baseUrl + "/setDescribeKala",
        data: {
            _token: "{{ csrf_token() }}",
            kalaId: kalaId,
            discription: discription
        },
        dataType: "json",
        success: function (msg) { },
        error: function (msg) {
            console.log(msg);
        }
    });
}));

$(document).on('change', '.customerList', (() => {
    $('#customerSn').val($("input:radio.customerList:checked").val().split('_')[0]);
    $('#customerGroup').val($("input:radio.customerList:checked").val().split('_')[1]);
}));

$('#customerEdit').on("click", () => {

    let eqtisadiCode = $("#EqtisadiCode").val();
    let userName = $("#userName").val();
    let factorMinPrice = $("#FactorMinPrice").val();
    let existAllowance = $("#ExitButtonShow");
    let forceExit = $("#ForceExit");
    let pardakhtLive = $("#PardakhtLive");
    let manyMobile = $("#ManyMobile").val();
    let customerId = $("#CustomerSn").val();
    let officialInfo = $("#officialInfo");
    if (officialInfo.prop("checked")) {
        officialInfo = 1;
    } else {
        officialInfo = 0;
    }
    if (existAllowance.prop('checked')) {
        existAllowance = 1;
    } else {
        existAllowance = 0;
    }

    if (pardakhtLive.prop('checked')) {
        pardakhtLive = 1;
    } else {
        pardakhtLive = 0;
    }

    if (forceExit.prop('checked')) {
        forceExit = 0;
    } else {
        forceExit = 1;
    }

    $.ajax({
        type: "get",
        url: baseUrl + "/restrictCustomer",
        data: {
            _token: "{{ csrf_token() }}",
            userName: userName,
            officialInfo: officialInfo,
            EqtisadiCode: eqtisadiCode,
            ExitAllowance: existAllowance,
            ForceExit: forceExit,
            FactorMinPrice: factorMinPrice,
            PardakhtLive: pardakhtLive,
            ManyMobile: manyMobile,
            CustomerId: customerId
        },
        dataType: "json",
        success: function (msg) {
            window.location.href = baseUrl + "/listCustomers";
        },
        error: function (msg) {
            alert("تغییری اعمال نشد,ممکن بعضی از فیلد ها خالی باشند.");
        }
    });

});

$('#myTable tr').click(function () {
    $(this).find('input:radio').prop('checked', true);
    let inp = $(this).find('input:radio');
    $('td.selected').removeClass("selected");
    $(this).children('td').addClass('selected');
    $('#partType').val(inp.val().split('_')[2]);
    $('#partId').val(inp.val().split('_')[0]);
    $('#partTitle').val(inp.val().split('_')[3]);
    if (!($('#partType').val() == 3 || $('#partType').val() == 4)) {
        if (document.querySelector("#upArrow")) {
            document.querySelector("#upArrow").disabled = false;
            document.querySelector("#downArrow").disabled = false;
        }
        if (document.querySelector("#deletePart")) {
            document.querySelector("#deletePart").disabled = false;
        }
    } else {
        if (document.querySelector("#upArrow")) {
            document.querySelector("#upArrow").disabled = true;
            document.querySelector("#downArrow").disabled = true;
        }
        if (document.querySelector("#deletePart")) {
            document.querySelector("#deletePart").disabled = true;
        }
    }
    if (document.querySelector("#editPart")) {
        document.querySelector("#editPart").disabled = false;
    }
});
$('#listKala tr').on('click', function () {

    $(this).find('input:radio').prop('checked', true);
    let inp = $(this).find('input:radio:checked');
    $('td.selected').removeClass("selected");
    $(this).children('td').addClass('selected');
    $("#kalaIdForEdit").val(inp.val().split("_")[0]);
    $("#firstPrice").val(parseInt(inp.val().split("_")[1]).toLocaleString("en-US"));
    $("#secondPrice").val(parseInt(inp.val().split("_")[2]).toLocaleString("en-US"));
    $("#kalaId").val(parseInt(inp.val().split("_")[0]));
    if (document.querySelector("#editKalaList")) {
        document.querySelector("#editKalaList").disabled = false;
    }
});
//used for searching kala of SubGroup
$("#serachKalaOfSubGroup").on('keyup', () => {
    let searchTerm = $("#serachKalaOfSubGroup").val();
    let subGrId = $('#secondGroupId').val();
    $.ajax({
        method: 'get',
        url: baseUrl + "/searchSubGroupKala",
        data: {
            _token: "{{ csrf_token() }}",
            searchTerm: searchTerm,
            subGrId: subGrId
        },
        async: true,
        success: function (arrayed_result) {
            $('#allKalaOfGroup').empty();
            for (var i = 0; i <= arrayed_result.length; i++) {
                $('#allKalaOfGroup').append(`
    <tr  onclick="checkCheckBox(this,event)">
        <td>` + (i + 1) + `</td>
        <td>` + arrayed_result[i].GoodName + `</td>
        <td>
        <input class="form-check-input" name="kalaListForGroupIds[]" type="checkbox" value="` +
                    arrayed_result[i].GoodSn + `_` + arrayed_result[i]
                        .GoodName + `" id="kalaId">
        </td>
    </tr>
    `);

            }
        },
        error: function (data) {
            alert("not found");
        }

    });
});

function changeDeleteFactorButton(element) {
    if ($(element).find('input:checkbox').prop('disabled') == false) {
        if ($(element).find('input:checkbox').prop('checked') == false) {
            $(element).find('input:checkbox').prop('checked', true);
            $("#submitDeleteFactorButton").prop("disabled", false);
            $("#submitFactorToAppButton").prop("disabled", false);
        } else {
            $(element).find('input:checkbox').prop('checked', false);
        }
    }
}

function factorStuff(element) {
    $(element).find('input:radio').prop('checked', true);
    let factorNumber;
    let psn;
    let input = $(element).find('input:radio');
    factorNumber = input.val().split("_")[0];
    psn = input.val().split("_")[1];
    $("#factorNumberAfter").val(factorNumber);
    $("#psn").val(psn);
    $.ajax({
        type: 'get',
        async: true,
        dataType: 'text',
        url: baseUrl + "/getOrders",
        data: {
            _token: "{{ csrf_token() }}",
            id: factorNumber
        },
        success: function (answer) {
            data = $.parseJSON(answer);
            $('#ordersFactorAfter').empty();
            for (var i = 0; i <= data.length - 1; i++) {
                $('#ordersFactorAfter').append(
                    `<tr onclick="changeDeleteFactorButton(this)">
    <td>` + (i + 1) + `</td>
    <td>` + data[i].GoodCde + `</td>
    <td>` + data[i].GoodName + `</td>
    <td>` + data[i].firstUnitName + `</td>
    <td>` + data[i].secondUnitName + `</td>
    <td>` + parseInt(data[i].Amount / 1).toLocaleString("en-US") + `</td>
    <td>` + parseInt(data[i].Fi / 10).toLocaleString("en-US") + `</td>
    <td>` + parseInt(data[i].Price / 10).toLocaleString("en-US") + `</td>
    <td>
    <input type="checkBox" name="SnOrderBYSPishKharidAfter[]" value="` + data[i].SnOrderBYSPishKharidAfter + `" class="form-check-input">
    </td>
</tr>`);
            }

        }
    });

}


function changeCityStuff(element) {
    $(element).find('input:radio').prop('checked', true);
    let inp = $(element).find('input:radio');
    $('td.selected').removeClass("selected");
    $(element).children('td').addClass('selected');
    document.querySelector("#editCityButton").disabled = false;
    document.querySelector("#addNewMantiqah").disabled = false;
    $('#CityId').val(inp.val().split('_')[0]);



    $.ajax({
        method: 'get',
        url: baseUrl + "/searchMantagha",
        data: {
            _token: "{{ csrf_token() }}",
            cityId: $('#CityId').val()
        },
        success: function (answer) {
            $('#mantiqaBody').empty();
            if (answer.length < 1) {
                document.querySelector("#deleteCityButton").disabled = false;
            } else {
                document.querySelector("#deleteCityButton").disabled = true;
            }
            answer.forEach((element, index) => {
                $('#mantiqaBody').append(
                    `<tr class="subGroupList1" onclick="showMantiqah(this)">
                <td>` + (index + 1) + `</td>
                <td>` + element.NameRec + `</td>
                <td><span><input class="subGroupId"   name="mantiqah" value="`+ element.SnMNM + `" type="radio"></span></td></tr>`);
            });
        }
    });
}
$("#editMantiqah").on("click", function () {

    $.ajax({
        method: 'get',
        url: baseUrl + "/getMantaghehInfo",
        data: {
            _token: "{{ csrf_token() }}",
            id: $("#mantiqahIdForSearch").val()
        },
        success: function (answer) {
            $("#MantaghehNameEdit").val(answer.NameRec);
            $("#mantaghehIdEdit").val(answer.SnMNM);
            $("#mantiqahCity").val(answer.FatherMNM);

            if (!($('.modal.in').length)) {
                $('.modal-dialog').css({
                    left: 50,
                    top: 0
                });
            }
            $('#editMantagheh').modal({
                backdrop: false,
                show: true
            });

            $('.modal-dialog').draggable({
                handle: ".modal-header"
            });
            $("#editMantagheh").modal("show");
        }
    });
});

$("#editMantaghehForm").on("submit", function (e) {

    $.ajax({
        url: $(this).attr('action'),
        type: $(this).attr('method'),
        dataType: 'json',
        data: $(this).serialize(),
        success: function (answer) {
            $('#mantiqaBody').empty();
            answer.forEach((element, index) => {
                $('#mantiqaBody').append(
                    `<tr class="subGroupList1" onclick="showMantiqah(this)">
                <td>` + (index + 1) + `</td>
                <td>` + element.NameRec + `</td>
                <td><span><input class="subGroupId"   name="mantiqah" value="`+ element.SnMNM + `" type="radio"></span></td></tr>`);
            });
            $("#editMantagheh").modal("hide");
        },
        error: function (data) {

        }
    });
    e.preventDefault();
});

$("#deleteMantagheh").on("click", function () {
    $.ajax({
        method: 'get',
        url: baseUrl + "/deleteMantagheh",
        data: {
            _token: "{{ csrf_token() }}",
            cityId: $("#CityId").val(),
            mantiqahId: $("#mantiqahIdForSearch").val()
        },
        success: function (answer) {
            $('#mantiqaBody').empty();
            answer.forEach((element, index) => {
                $('#mantiqaBody').append(
                    `<tr class="subGroupList1" onclick="showMantiqah(this)">
                        <td>` + (index + 1) + `</td>
                        <td>` + element.NameRec + `</td>
                          <td><span><input class="subGroupId"   name="mantiqah" value="`+ element.SnMNM + `" type="radio"></span></td></tr>`);
            });
        }
    });
});
function showMantiqah(element) {
    $(element).find('input:radio').prop('checked', true);
    let input = $(element).find('input:radio');
    $('td.selected').removeClass("selected");
    $(element).children('td').addClass('selected');
    $("#customersList").css({ 'display': 'flex' });
    $("#mantiqahIdForSearch").val(input.val());
    $("#editMantiqah").prop("disabled", false);

    $.ajax({
        method: 'get',
        url: baseUrl + "/getAllCustomersToMNM",
        data: {
            _token: "{{ csrf_token() }}",
            MantiqahId: input.val()
        },
        success: function (answer) {
            $('#cutomerBody').empty();

            answer[0].forEach((element, index) => {
                $('#cutomerBody').append(
                    `<tr class="subGroupList1 maserTr" onclick="checkCheckBox(this,event)">
                        <td>` + (index + 1) + `</td>
                        <td>` + element.Name + `</td>
                        <td >` + element.peopeladdress + `</td>
                        <td><span><input class="subGroupId"   name="customerIDSforMantiqah[]" value="`+ element.PSN + `_` + element.PCode + `_` + element.Name + `_` + element.peopeladdress + `" type="checkbox"></span></td>
                    </tr>`);
            });
            $('#addedCutomerBody').empty();
            if (answer[1].length < 1) {
                $("#deleteMantagheh").prop("disabled", false);
            } else {
                $("#deleteMantagheh").prop("disabled", true);
            }
            answer[1].forEach((element, index) => {
                $('#addedCutomerBody').append(
                    `<tr class="subGroupList1" onclick="checkCheckBox(this,event)">
                        <td>` + (index + 1) + `</td>
                        <td>` + element.Name + `</td>
                        <td>` + element.peopeladdress + `</td>
                        <td><span><input class="subGroupId"   name="customerIDSofMantiqah[]" value="`+ element.PSN + `" type="checkbox"></span></td>
                    </tr>`);
            });
        }
    });
}

$("#cityId").on("change", function () {
    $.ajax({
        method: 'get',
        url: baseUrl + "/searchMantagha",
        data: {
            _token: "{{ csrf_token() }}",
            cityId: $("#cityId").val()
        },
        async: true,
        success: function (arrayed_result) {
            $("#selectMantiqah").empty();
            arrayed_result.forEach((element, index) => {
                $("#selectMantiqah").append(`
                <option value="`+ element.SnMNM + `">` + element.NameRec + `</option>
                `);
            });
        },
        error: function (data) { }
    });
});

$("#searchCityId").on("change", function () {
    $.ajax({
        method: 'get',
        url: baseUrl + "/searchMantagha",
        data: {
            _token: "{{ csrf_token() }}",
            cityId: $("#searchCityId").val()
        },
        async: true,
        success: function (arrayed_result) {
            $("#searchSelectMantiqah").empty();
            arrayed_result.forEach((element, index) => {
                $("#searchSelectMantiqah").append(`
                <option value="`+ element.NameRec + `">` + element.NameRec + `</option>
                `);
            });
        },
        error: function (data) { }
    });

    $.ajax({
        method: 'get',
        url: baseUrl + "/searchByCity",
        data: {
            _token: "{{ csrf_token() }}",
            csn: $("#searchCityId").val()
        },
        async: true,
        success: function (arrayed_result) {

            // $('.crmDataTable').dataTable().fnDestroy();
            $("#customerList").empty();
            arrayed_result.forEach((element, index) => {
                let nameRec = element.NameRec;
                let iterator = parseInt(index);
                if (element.NameRec == null) {
                    nameRec = ""
                }
                $("#customerList").append(`<tr onclick="selectCustomerStuff(this)">
                    <td>`+ (index + 1) + `</td>
                    <td>`+ element.PCode + `</td>
                    <td>`+ element.Name + `</td>
                    <td style="width:390px">`+ element.peopeladdress + `</td>
                    <td>`+ element.PhoneStr + `</td>
                    <td>`+ nameRec + `</td>
                    <td>2</td>
                    <td> <input class="customerList form-check-input" name="customerId" type="radio" value="`+ element.PSN + `_` + element.GroupCode + `" id="flexCheckChecked"></td>
                </tr>
                    `);
            });
            // $('.crmDataTable').dataTable();
        },
        error: function (data) { }
    });
});


$("#searchAddressMNM").on("keyup", () => {
    const searchTerm = $("#searchAddressMNM").val();
    $.ajax({
        method: 'get',
        url: baseUrl + "/searchCustomerByAddressMNM",
        data: {
            _token: "{{ csrf_token() }}",
            searchTerm: searchTerm
        },
        success: function (answer) {
            $('#cutomerBody').empty();
            answer.forEach((element, index) => {
                $('#cutomerBody').append(`<tr class="subGroupList1 maserTr" onclick="checkCheckBox(this,event)">
                <td style="width:62px">` + (index + 1) + `</td>
                <td style="width:66px">` + element.PCode + `</td>
                <td style="width:110px">` + element.Name + `</td>
                <td style="width:210px">` + element.peopeladdress + `</td>
                <td style="width:44px"><span><input class="subGroupId"   name="customerIDSforMantiqah[]" value="`+ element.PSN + `_` + element.PCode + `_` + element.Name + `_` + element.peopeladdress + `" type="checkbox"></span></td>
            </tr>`);
            });
        }
    });
});


$("#searchNameMNM").on("keyup", () => {
    const searchTerm = $("#searchNameMNM").val();
    $.ajax({
        method: 'get',
        url: baseUrl + "/searchCustomerByNameMNM",
        data: {
            _token: "{{ csrf_token() }}",
            searchTerm: searchTerm
        },
        success: function (answer) {
            $('#cutomerBody').empty();
            answer.forEach((element, index) => {
                $('#cutomerBody').append(`<tr class="subGroupList1 maserTr" onclick="checkCheckBox(this,event)">
                <td style="width:62px">` + (index + 1) + `</td>
                <td style="width:66px">` + element.PCode + `</td>
                <td style="width:110px">` + element.Name + `</td>
                <td style="width:210px">` + element.peopeladdress + `</td>
                <td style="width:44px"><span><input class="subGroupId"   name="customerIDSforMantiqah[]" value="`+ element.PSN + `_` + element.PCode + `_` + element.Name + `_` + element.peopeladdress + `" type="checkbox"></span></td>
            </tr>`);
            });
        }
    });
});

$("#filterCustomerBtn").on("click", function () {
    $.get('/filterCustomers', {
        nameOrCodeOrPhone: $("#searchCustomerByName").val(),
        recName: $("#searchSelectMantiqah").val(),
        locationState: $("#searchLocationOrNot").val(),
        activationState: $("#searchActiveOrNot").val(),
        baseName: $("#orderCustomers").val()
    }, function (arrayed_result, status) {
        if (status == "success") {
            $("#customerList").empty();
            arrayed_result.forEach((element, index) => {
                let nameRec = element.NameRec;
                let iterator = parseInt(index);
                if (element.NameRec == null) {
                    nameRec = ""
                }
                $("#customerList").append(`<tr onclick="selectCustomerStuff(this)">
            <td>`+ (index + 1) + `</td>
            <td>`+ element.PCode + `</td>
            <td>`+ element.Name + `</td>
            <td style="width:390px">`+ element.peopeladdress + `</td>
            <td>`+ element.PhoneStr + `</td>
            <td>`+ nameRec + `</td>
            <td>`+ element.TimeStamp + `</td>
            <td> <input class="customerList form-check-input" name="customerId" type="radio" value="`+ element.PSN + `_` + element.GroupCode + `" id="flexCheckChecked"></td>
        </tr>
            `);
            });
        }
    })

});

$("#searchAddedAddressMNM").on("keyup", () => {
    const searchTerm = $("#searchAddedAddressMNM").val();
    $.ajax({
        method: 'get',
        url: baseUrl + "/searchCustomerAddedAddressMNM",
        data: {
            _token: "{{ csrf_token() }}",
            searchTerm: searchTerm,
            mantiqahId: $("#mantiqahIdForSearch").val()
        },
        success: function (answer) {
            $('#addedCutomerBody').empty();
            answer.forEach((element, index) => {
                $('#addedCutomerBody').append(
                    `<tr class="subGroupList1" onclick="checkCheckBox(this,event)">
                    <td style="width:62px">` + (index + 1) + `</td>
                    <td style="width:66px">` + element.PCode + `</td>
                    <td style="width:110px">` + element.Name + `</td>
                    <td style="width:210px">` + element.peopeladdress + `</td>
                    <td style="width:44px"><span><input class="subGroupId"   name="customerIDSofMantiqah[]" value="`+ element.PSN + `" type="checkbox"></span></td>
                </tr>`
                );
            });
        }
    });
});


$("#searchAddedNameMNM").on("keyup", () => {
    const searchTerm = $("#searchAddedNameMNM").val();
    $.ajax({
        method: 'get',
        url: baseUrl + "/searchCustomerAddedNameMNM",
        data: {
            _token: "{{ csrf_token() }}",
            searchTerm: searchTerm,
            mantiqahId: $("#mantiqahIdForSearch").val()
        },
        success: function (answer) {
            $('#addedCutomerBody').empty();
            answer.forEach((element, index) => {
                $('#addedCutomerBody').append(
                    `<tr class="subGroupList1" onclick="checkCheckBox(this,event)">
                    <td style="width:62px">` + (index + 1) + `</td>
                    <td style="width:66px">` + element.PCode + `</td>
                    <td style="width:110px">` + element.Name + `</td>
                    <td style="width:210px">` + element.peopeladdress + `</td>
                    <td style="width:44px"><span><input class="subGroupId"   name="customerIDSofMantiqah[]" value="`+ element.PSN + `" type="checkbox"></span></td>
                </tr>`
                );
            });
        }
    });
});

$("#addDataToMantiqah").on("click", function () {
    let customerID = [];
    let customerIDsend = [];
    $('input[name="customerIDSforMantiqah[]"]:checked').map(function () {
        customerID.push($(this).val());
        customerIDsend.push($(this).val().split("_")[0]);
    });

    $('input[name="customerIDSforMantiqah[]"]:checked').parents('tr').css('color', 'white');
    $('input[name="customerIDSforMantiqah[]"]:checked').parents('tr').children('td').css('background-color', 'red');
    $('input[name="customerIDSforMantiqah[]"]:checked').prop("disabled", true);
    $('input[name="customerIDSforMantiqah[]"]:checked').prop("checked", false);
    $.ajax({
        method: 'get',
        url: baseUrl + "/addCustomerToMantiqah",
        data: {
            _token: "{{ csrf_token() }}",
            mantiqahId: $("#mantiqahIdForSearch").val(),
            customerIDs: customerIDsend,
            cityId: $("#CityId").val()
        },
        success: function (answer) {
            $('#addedCutomerBody').empty();
            answer[1].forEach((element, index) => {
                $('#addedCutomerBody').append(
                    `<tr class="subGroupList1" onclick="checkCheckBox(this,event)">
                    <td style="width:62px">` + (index + 1) + `</td>
                    <td style="width:66px">` + element.PCode + `</td>
                    <td style="width:110px">` + element.Name + `</td>
                    <td style="width:210px">` + element.peopeladdress + `</td>
                    <td style="width:44px"><span><input class="subGroupId"   name="customerIDSofMantiqah[]" value="`+ element.PSN + `" type="checkbox"></span></td>
                </tr>`
                );
            });

            $('#cutomerBody').empty();
            answer[0].forEach((element, index) => {
                $('#cutomerBody').append(
                    `<tr class="subGroupList1 maserTr" onclick="checkCheckBox(this,event)">
                        <td style="width:62px">` + (index + 1) + `</td>
                        <td style="width:66px">` + element.PCode + `</td>
                        <td style="width:110px">` + element.Name + `</td>
                        <td style="width:210px">` + element.peopeladdress + `</td>
                        <td style="width:44px"><span><input class="subGroupId"   name="customerIDSforMantiqah[]" value="`+ element.PSN + `_` + element.PCode + `_` + element.Name + `_` + element.peopeladdress + `" type="checkbox"></span></td>
                    </tr>`
                );
            });
        }
    });
});

$("#removeDataFromMantiqah").on('click', (function () {
    let customerIDsend = [];
    $('input[name="customerIDSofMantiqah[]"]:checked').map(function () {
        customerIDsend.push($(this).val());
    });
    $.ajax({
        method: 'get',
        url: baseUrl + "/removeCustomerFromMantiqah",
        data: {
            _token: "{{ csrf_token() }}",
            mantiqahId: $("#mantiqahIdForSearch").val(),
            customerIDs: customerIDsend
        },
        success: function (answer) {
            $('#addedCutomerBody').empty();
            answer[1].forEach((element, index) => {
                $('#addedCutomerBody').append(
                    `<tr class="subGroupList1" onclick="checkCheckBox(this,event)">
                    <td style="width:62px">` + (index + 1) + `</td>
                    <td style="width:66px">` + element.PCode + `</td>
                    <td style="width:110px">` + element.Name + `</td>
                    <td style="width:210px">` + element.peopeladdress + `</td>
                    <td style="width:44px"><span><input class="subGroupId"   name="customerIDSofMantiqah[]" value="`+ element.PSN + `" type="checkbox"></span></td>
                </tr>`
                );
            });

            $('#cutomerBody').empty();
            answer[0].forEach((element, index) => {
                $('#cutomerBody').append(
                    `<tr class="subGroupList1 maserTr" onclick="checkCheckBox(this,event)">
                        <td style="width:62px">` + (index + 1) + `</td>
                        <td style="width:66px">` + element.PCode + `</td>
                        <td style="width:110px">` + element.Name + `</td>
                        <td style="width:210px">` + element.peopeladdress + `</td>
                        <td style="width:44px"><span><input class="subGroupId"   name="customerIDSforMantiqah[]" value="`+ element.PSN + `_` + element.PCode + `_` + element.Name + `_` + element.peopeladdress + `" type="checkbox"></span></td>
                    </tr>`
                );
            });
        }
    });
}));

$("#editCityButton").on("click", function () {

    $.ajax({
        type: 'get',
        async: true,
        dataType: 'json',
        url: baseUrl + "/getCityInfo",
        data: {
            _token: "{{ csrf_token() }}",
            id: $("#CityId").val()
        },
        success: function (answer) {
            $("#cityNameEdit").val(answer.NameRec);
            $("#cityIdEdit").val(answer.SnMNM);

            if (!($('.modal.in').length)) {
                $('.modal-dialog').css({
                    left: 50,
                    top: 0
                });
            }
            $('#editCity').modal({
                backdrop: false,
                show: true
            });

            $('.modal-dialog').draggable({
                handle: ".modal-header"
            });
            $("#editCity").modal("show");

        }
    });

});
$("#editCityForm").on("submit", function (e) {

    $.ajax({
        url: $(this).attr('action'),
        type: $(this).attr('method'),
        dataType: 'json',
        data: $(this).serialize(),
        success: function (data) {
            $("#cityList").empty();
            data.forEach((element, index) => {
                $("#cityList").append(`
                <tr onclick="changeCityStuff(this)">
                <td>`+ (index + 1) + `</td>
                <td>`+ element.NameRec + `</td>
                <td>
                <input class="mainGroupId" type="radio" name="mainGroupId[]" value="`+ element.SnMNM + `_` + element.NameRec + `">
                </td>
                </tr>`);
            });
            $("#editCity").modal("hide");
        },
        error: function (xhr, err) {
            alert('Error');
        }
    });
    e.preventDefault();
});

$("#addNewMantiqah").on("click", function () {
    if (!($('.modal.in').length)) {
        $('.modal-dialog').css({
            top: 0,
            left: 0
        });
    }
    $('#addMontiqah').modal({
        backdrop: false,
        show: true
    });

    $('.modal-dialog').draggable({
        handle: ".modal-header"
    });
    $("#addMontiqah").modal("show");
});

$("#city").on("change", function () {

    $.ajax({
        method: 'get',
        url: baseUrl + "/searchMantagha",
        data: {
            _token: "{{ csrf_token() }}",
            cityId: $("#city").val()
        },
        async: true,
        success: function (arrayed_result) {
            $("#mantiqahForAdd").empty();
            arrayed_result.forEach((element, index) => {
                $("#mantiqahForAdd").append(`
                <option value="`+ element.SnMNM + `">` + element.NameRec + `</option>
                `);
            });
        },
        error: function (data) { }
    });
});

$("#deleteCityButton").on("click", function () {
    $.ajax({
        type: 'get',
        async: true,
        dataType: 'text',
        url: baseUrl + "/deleteCity",
        data: {
            _token: "{{ csrf_token() }}",
            id: $("#CityId").val()
        },
        success: function (data) {
            $("#cityList").empty();
            data.forEach((element, index) => {
                $("#cityList").append(`
                <tr onclick="changeCityStuff(this)">
                <td>`+ (index + 1) + `</td>
                <td>`+ element.NameRec + `</td>
                <td>
                <input class="mainGroupId" type="radio" name="mainGroupId[]" value="`+ element.SnMNM + `_` + element.NameRec + `">
                </td>
                </tr>`);
            });
        },
        error: function (xhr, err) {
            alert('Error');
        }
    });
});



$("#addCityForm").on("submit", function (e) {

    $.ajax({
        url: $(this).attr('action'),
        type: $(this).attr('method'),
        dataType: 'json',
        data: $(this).serialize(),
        success: function (data) {
            $("#cityList").empty();
            data.forEach((element, index) => {
                $("#cityList").append(`                                                <tr onclick="changeCityStuff(this)">
                    <td>`+ (index + 1) + `</td>
                    <td>`+ element.NameRec + `</td>
                    <td>
                    <input class="mainGroupId" type="radio" name="mainGroupId[]" value="`+ element.SnMNM + `_` + element.NameRec + `">
                    </td>
                    </tr>`);
            });
            $("#newCity").modal("hide");
        },
        error: function (xhr, err) {
            alert('Error');
        }
    });
    e.preventDefault();
});

$("#addNewCity").on("click", function () {
    if (!($('.modal.in').length)) {
        $('.modal-dialog').css({
            top: 0,
            left: 0
        });
    }
    $('#newCity').modal({
        backdrop: false,
        show: true
    });

    $('.modal-dialog').draggable({
        handle: ".modal-header"
    });
    $("#newCity").modal("show");
});

//used for changing mainGroup stuff
function changeMainGroupStuff(element) {

    $(element).find('input:radio').prop('checked', true);
    let inp = $(element).find('input:radio');
    $('td.selected').removeClass("selected");
    $(element).children('td').addClass('selected');
    $('#partType').val(inp.val().split('_')[2]);
    $('#partId').val(inp.val().split('_')[0]);
    $('#partTitle').val((String(inp.val().split('_')[3]).length));
    if (document.querySelector("#editGroupList")) {
        document.querySelector("#editGroupList").disabled = false;
    }
    if (document.querySelector("#addNewSubGroupButton")) {
        document.querySelector("#addNewSubGroupButton").disabled = false;
    }
    document.querySelector('#groupId').value = inp.val().split('_')[0];
    $('#mianGroupId').val(inp.val().split('_')[0]);

    $.ajax({
        type: 'get',
        async: true,
        dataType: 'text',
        url: baseUrl + "/subGroups",
        data: {
            _token: "{{ csrf_token() }}",
            id: $('.mainGroupId:checked').val().split('_')[0]
        },
        success: function (answer) {
            data = $.parseJSON(answer);
            if (data.length < 1) {
                if (document.querySelector("#deleteGroupList")) {
                    document.querySelector("#deleteGroupList").disabled = false;

                }
            } else {
                if (document.querySelector("#deleteGroupList")) {
                    document.querySelector("#deleteGroupList").disabled = true;
                }
            }
            $('#subGroup01').empty();

            $('.subGroupCount').empty();
            for (var i = 0; i <= data.length - 1; i++) {
                $('#subGroup01').append(`
                        <tr class="subGroupList1" onClick="changeId(this)">
                             <td>` + (i + 1) + `</td>
                             <td>` + data[i].title + `</td>
                             <td><input class="subGroupId"   name="subGroupId[]" value="` + data[i].id + `_` + data[i].selfGroupId + `_` + data[i].percentTakhf + `_` + data[i].title + `" type="radio" id="flexCheckChecked` + i + `"></td>
                         </tr>
                     `);

            }
            if (data.length > 0) {
                for (var i = 1; i <= (data.length + 1); i++) {
                    $('.subGroupCount').append(
                        `<option value="` + i + `">` + i + `</option>`
                    );
                }
            } else {
                $('.subGroupCount').append(
                    `<option value="` + 1 + `">` + 1 + `</option>`
                );
            }
        }
    });
}


//used for changing changePicture stuff
function changePicture(element) {
    $(element).find('input:radio').prop('checked', true);
    let inp = $(element).find('input:radio');
    $('td.selected').removeClass("selected");
    $(element).children('td').addClass('selected');
    $('#partType').val(inp.val().split('_')[2]);
    $('#partId').val(inp.val().split('_')[0]);
    $('#partTitle').val((String(inp.val().split('_')[3]).length));
    document.querySelector("#editGroupList").disabled = false;
    document.querySelector("#addNewSubGroupButton").disabled = false;
    document.querySelector('#groupId').value = inp.val().split('_')[0];
    var value1 = $('#mianGroupId').val(inp.val().split('_')[0]);

    $.ajax({
        type: 'get',
        async: true,
        dataType: 'text',
        url: baseUrl + "/subGroups",
        data: {
            _token: "{{ csrf_token() }}",
            id: $('.mainGroupId:checked').val().split('_')[0]
        },
        success: function (answer) {
            data = $.parseJSON(answer);
            if (data.length < 1) {
                document.querySelector("#deleteGroupList").disabled = false;
            } else {
                document.querySelector("#deleteGroupList").disabled = true;
            }
            $('#subGroup2').empty();
            $('.subGroupCount').empty();
            for (var i = 0; i <= data.length - 1; i++) {
                $('#subGroup2').append(
                    `<tr class="subGroupList1" onClick="changeId(this)">
                        <td>` + (i + 1) + `</td>
                        <td>` + data[i].title + `</td>
                        <td><a href="` + baseUrl + `/getKalaWithPic/` + data[i].id + `" target="_blank" class="btn btn-success btn-sm buttonHover"> <i class='fa fa-image'> </i> </a></td>
                    </tr>`);
            }
            if (data.length > 0) {
                for (var i = 1; i <= (data.length + 1); i++) {
                    $('.subGroupCount').append(
                        `<option value="` + i + `">` + i + `</option>`
                    );
                }
            } else {
                $('.subGroupCount').append(
                    `<option value="` + 1 + `">` + 1 + `</option>`
                );
            }
        }
    });
}
$(document).on('change', '.customerList', (() => {
    document.querySelector("#editPart").disabled = false;
    document.querySelector("#inActiveCustomer").disabled = false;

}));
//used for deleting a part of main page(Home page)
$(document).on('click', '#deletePart', (function () {
    if (confirm("می خواهید حذف کنید؟")) {
        $.ajax({
            method: 'get',
            async: true,
            dataType: 'text',
            url: baseUrl + "/deletePart",
            data: {
                _token: "{{ csrf_token() }}",
                id: $('input[name=partId]:checked').val().split('_')[0],
                priority: $('input[name=partId]:checked').val().split('_')[1],
                partType: $('#partType').val()
            },
            success: function (answer) {
                window.location.reload();
            }
        });
    } else {
        return false;
    }
}));
//جستجوی مشتری
$(document).on('keyup', '#customerName', (function () {
    var searchText = document.querySelector("#customerName").value;
    if (searchText.length > 2) {
        $.ajax({
            method: 'get',
            async: true,
            dataType: 'text',
            url: baseUrl + "/searchCustomer",
            data: {
                _token: "{{ csrf_token() }}",
                searchText: searchText
            },
            success: function (answer) {
                $('#customerBody').empty();
                var answer = JSON.parse(answer);
                for (let index = 0; index < answer.length; index++) {
                    $('#customerBody').append(
                        `<tr>
    <td>` + index + `</td>
    <td>` + answer[index].PCode + `</td>
    <td>` + answer[index].Name + `</td>
    <td>` + answer[index].peopeladdress + `</td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td>
    <input class="form-check-input" name="customerId" type="radio" value="` + answer[index].PSN + `_` + answer[index].GroupCode + `" id="flexCheckChecked" checked>
    </td>
</tr>`
                    );
                }
            }
        });
    }
}));

//used for searching by input to add groups to a part
$('#search_mainGroup').on('keyup', function () {
    var searchTerm = document.querySelector("#search_mainGroup").value;
    //قسمت لیست کالاها آورده شود
    if (searchTerm.length > 2) {
        $.ajax({
            method: 'get',
            url: baseUrl + "/ز",
            async: true,
            data: {
                _token: "{{ csrf_token() }}",
                searchTerm: searchTerm
            },
            success: function (arrayed_result) {
                $('#groupPart').empty();
                for (var i = 0; i <= arrayed_result.length - 1; i++) {
                    $('#groupPart').append(`<tr onclick="checkCheckBox(this,event)">
    <td>` + (i + 1) + `</td>
    <td>` + arrayed_result[i].title + `</td>
    <td>
        <input class="mainGroupId form-check-input" type="checkBox"
        name="mainGroupIds[]" value="` + arrayed_result[i].id + `_` + arrayed_result[i].title + `"
            id="flexCheckChecked">
    </td>
</tr>`);
                }
            },
            error: function (data) { }

        });
    }
});


$('#search_mainGroup2').on('keyup', function () {
    var searchTerm = document.querySelector("#search_mainGroup2").value;
    //قسمت لیست کالاها آورده شود
    $.ajax({
        method: 'get',
        url: baseUrl + "/ز",
        async: true,
        data: {
            _token: "{{ csrf_token() }}",
            searchTerm: searchTerm
        },
        success: function (arrayed_result) {
            $('#groupPart').empty();
            for (var i = 0; i <= arrayed_result.length - 1; i++) {
                $('#groupPart').append(`<tr   onclick="checkCheckBox(this,event)">
    <td>` + (i + 1) + `</td>
    <td>` + arrayed_result[i].title + `</td>
    <td>
        <input class="mainGroupId form-check-input" type="checkBox"
        name="groupListIds[]" value="` + arrayed_result[i].id + `_` + arrayed_result[i].title + `"
            id="flexCheckChecked">
    </td>
</tr>`);
            }
        },
        error: function (data) { }

    });
});
$(document).on('keyup', "#search_mainGroup3", function () {
    var searchTerm = document.querySelector("#search_mainGroup3").value;
    //قسمت لیست کالاها آورده شود
    $.ajax({
        method: 'get',
        url: baseUrl + "/ز",
        async: true,
        data: {
            _token: "{{ csrf_token() }}",
            searchTerm: searchTerm
        },
        success: function (arrayed_result) {
            $('#groupPart').empty();
            for (var i = 0; i <= arrayed_result.length - 1; i++) {
                $('#groupPart').append(`<tr   onclick="checkCheckBox(this,event)">
    <td>` + (i + 1) + `</td>
    <td>` + arrayed_result[i].title + `</td>
    <td>
        <input class="mainGroupId form-check-input" type="checkBox"
        name="groupListIds[]" value="` + arrayed_result[i].id + `_` + arrayed_result[i].title + `"
            id="flexCheckChecked">
    </td>
</tr>`);
            }
        },
        error: function (data) { }

    });
});
//جستجوی مشتری بر اساس آدرس
$(document).on('keyup', '#customerAddress', (function () {
    var searchText = document.querySelector("#customerAddress").value;
    if (searchText.length > 2) {
        $.ajax({
            method: 'get',
            async: true,
            dataType: 'text',
            url: baseUrl + "/searchCustomer",
            data: {
                _token: "{{ csrf_token() }}",
                searchText: searchText
            },
            success: function (answer) {
                $('#customerBody').empty();
                var answer = JSON.parse(answer);
                for (let index = 0; index < answer.length; index++) {
                    $('#customerBody').append(
                        `<tr onclick="checkCheckBox(this,event)">
                    <td style='color:red;'>` + index + `</td>
                    <td style='width:100px'>` + answer[index].PCode + `</td>
                    <td style='width:120px; background-color:black;'>` + answer[index].Name + `</td>
                    <td style='width:200px'>` + answer[index].peopeladdress + `</td>
                    <td>
                      <input class="form-check-input" name="customerId" type="radio" value="` + answer[index].PSN + `_` + answer[index].GroupCode + `" id="flexCheckChecked" checked>
                    </td>
                </tr>`
                    );
                }
            }
        });
    }
}));
//جستجوی کالا در افزدون کالای جدید
$(document).on('keyup', '#searchKala', (function () {
    var searchText = document.querySelector("#searchKala").value;
    $.ajax({
        method: 'get',
        async: true,
        dataType: 'text',
        url: baseUrl + "/searchKalas",
        data: {
            _token: "{{ csrf_token() }}",
            searchTerm: searchText
        },
        success: function (answer) {
            $('#kalaList').empty();
            var answer = JSON.parse(answer);

            for (let index = 0; index < answer.length; index++) {
                $('#kalaList').append(`
    <tr onclick="checkCheckBox(this,event)">
        <td>` + (index + 1) + `</td>
        <td>` + answer[index].GoodName + `</td>
        <td>
        <input class="form-check-input" name="kalaListIds[]" type="checkbox" value="` + answer[index].GoodSn + `_` + answer[index].GoodName + `" id="kalaId">
        </td>
    </tr>`);
            }
        }
    });
}));


$(document).on('keyup', '#customerCode', (function () {
    var searchText = document.querySelector("#customerCode").value;
    if (searchText.length > 2) {
        $.ajax({
            method: 'get',
            async: true,
            dataType: 'text',
            url: baseUrl + "/searchCustomer",
            data: {
                _token: "{{ csrf_token() }}",
                searchText: searchText
            },
            success: function (answer) {
                $('#customerBody').empty();
                var answer = JSON.parse(answer);
                for (let index = 0; index < answer.length; index++) {
                    $('#customerBody').append(
                        `<tr onclick="checkCheckBox(this,event)">
    <td>` + index + `</td>
    <td>` + answer[index].PCode + `</td>
    <td>` + answer[index].Name + `</td>
    <td>` + answer[index].peopeladdress + `</td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td>
    <input class="form-check-input" name="customerId" type="radio" value="` + answer[index].PSN + `_` + answer[index].GroupCode + `" id="flexCheckChecked" checked>
    </td>
</tr>`
                    );
                }
            }
        });
    }
}));

$(document).on('keyup', '#serachKalaForSubGroup', (() => {
    let searchTerm = $('#serachKalaForSubGroup').val();
    $.ajax({
        method: 'get',
        url: baseUrl + '/searchKalas',
        async: true,
        data: {
            _token: "{{ csrf_token() }}",
            searchTerm: searchTerm,
            id: $('#secondGroupId').val()
        },
        success: function (arrayed_result) {
            $('#allKalaForGroup').empty();
            for (let i = 0; i < arrayed_result.length; i++) {
                $('#allKalaForGroup').append(`
<tr  onclick="checkCheckBox(this,event)">
    <td>` + (i + 1) + `</td>
    <td>` + arrayed_result[i].GoodName + `</td>
    <td>
    <input class="form-check-input" name="kalaListForGroupIds[]" type="checkbox" value="` +
                    arrayed_result[i].GoodSn + `_` + arrayed_result[i]
                        .GoodName + `" id="kalaId">
    </td>
</tr>
`);
            }
        },
        error: function (data) {

        }

    });
}));

//used for adding kala to Group to the left side(kalaList)
$(document).on('click', '#addDataToGroup', (function () {

    var kalaListID = [];
    $('input[name="kalaListForGroupIds[]"]:checked').map(function () {
        kalaListID.push($(this).val());
    });
    $('input[name="kalaListForGroupIds[]"]:checked').parents('tr').css('color', 'white');
    $('input[name="kalaListForGroupIds[]"]:checked').parents('tr').children('td').css('background-color', 'red');
    $('input[name="kalaListForGroupIds[]"]:checked').prop("disabled", true);
    $('input[name="kalaListForGroupIds[]"]:checked').prop("checked", false);

    for (let i = 0; i < kalaListID.length; i++) {
        $('#allKalaOfGroup').prepend(`<tr class="addedTrGroup">
<td>` + kalaListID[i].split('_')[0] + `</td>
<td>` + kalaListID[i].split('_')[1] + `</td>
<td>
<input class="form-check-input" name="addedKalaToGroup[]" type="checkbox" value="` + kalaListID[i].split('_')[0] + `_` + kalaListID[i].split('_')[1] + `" id="kalaIds" checked>
</td>
</tr>`);

    }
}));

function selectBranKalaTr(element) {
    $(element).find('input:checkbox').prop('checked', true);
    $('td.selected').removeClass("selected");
}
//used for removing data from a Group
$(document).on('click', '#removeDataFromGroup', (function () {
    $('tr').find('input:checkbox:checked').attr("name", "removeKalaFromGroup[]");
    $('tr').has('input:checkbox:checked').hide();
}));

$(".selectAllFromTop").on("change", (e) => {
    if ($(e.target).is(':checked')) {
        var table = $(e.target).closest('table');
        $('td input:checkbox', table).prop('checked', true);
    } else {
        var table = $(e.target).closest('table');
        $('td input:checkbox', table).prop('checked', false);
    }

});
$(document).on('change', ".selectAllFromTop", (e) => {
    if ($(e.target).is(':checked')) {
        var table = $(e.target).closest('table');
        $('td input:checkbox', table).prop('checked', true);
    } else {
        var table = $(e.target).closest('table');
        $('td input:checkbox', table).prop('checked', false);
    }
});

function selectCustomerStuff(element) {
    $(element).find('input:radio').prop('checked', true);
    let inp = $(element).find('input:radio:checked');
    $('.select-highlightKala tr').removeClass('selected');
    $(element).toggleClass('selected');
    $(".enableBtn").prop("disabled", false);
    $('.select-highlight tr').removeClass('selected');
    $("#customerId").val(parseInt(inp.val().split("_")[0]));
    $('#customerSn').val(parseInt(inp.val().split("_")[0]));
    $('#customerGroup').val(parseInt(inp.val().split("_")[1]));
    $("#editMantiqah").prop("disabled", false);
}

function selectAllFromTop(element) {
    element.parents('table').find('td input:checkbox').prop('checked', true);
}

$(document).on("change", ".deleteBrandItem", () => {
    $("#deleteBrandItemButton").prop("disabled", false);
});
$(document).on("click", "#deleteBrandItemButton", () => {
    let input = $("input[type='radio']:checked");
    input.parents(".product-item").remove();
});

function displayTakhsisContainer(element) {
    document.querySelector(".takhsisContainerDisplay").className = "takhsisContainer row";
    document.querySelector(element.value).className = "takhsisContainerDisplay row";
}

function takhsisBrandKalaEdit(element) {
    document.querySelector(".takhsisContainerDisplay").className = "takhsisContainer c-checkout";
    document.querySelector(element.value).className = "takhsisContainerDisplay row";
    element.querySelector('i.inheritHover').style.setProperty('color', 'red', 'important');
}

function changePicBrandKalaEdit(element) {
    document.getElementById("file" + element.value).click();
    element.querySelector('i.inheritHover').style.setProperty('color', 'red', 'important');
}

function changeBackgroundDeleteCheck(element) {
    element.style.setProperty('color', 'red', 'important');
}

function setBrandDeleteButton(element) {
    document.querySelector("#deleteBrandButton").disabled = false;
    document.querySelector("#deleteBrandButton").value = element.value;
}

function deleteBrandItemEdit(element) {
    document.querySelector('#brandPictureStuff' + element.value).className = "takhsisContainer";
    document.querySelector('#brandPictureStuff' + element.value).className = "takhsisContainer";
    document.querySelector('#deleteBrandItem' + element.value).setAttribute('value', 'delete');
    document.querySelector('#brandPictureStuff' + element.value).parents(".product-item").remove();
    // document.querySelector('#takhsisBrandKala' + element.value).className = "takhsisContainer";
}


function addAllKalaToBrand(element) {
    var kalaListID = [];
    $('input[name="brandAllKala[]"]:checked').map(function () {
        kalaListID.push($(this).val());
    });
    $('input[name="brandAllKala[]"]:checked').parents('tr').css('color', 'white');
    $('input[name="brandAllKala[]"]:checked').parents('tr').children('td').css('background-color', 'red');
    $('input[name="brandAllKala[]"]:checked').prop("disabled", true);
    $('input[name="brandAllKala[]"]:checked').prop("checked", false);
    for (let i = 0; i < kalaListID.length; i++) {
        $('#brandAddedKalaContainer' + element.value).append(`<tr class="addedTrList">
<td>` + (i + 1) + `</td>
<td>` + kalaListID[i].split('_')[1] + `</td>
<td>
<input class="addKalaToList form-check-input" name="addedKalaTobrandList[]" type="checkbox" value="` + kalaListID[i].split('_')[0] + `_` + kalaListID[i].split('_')[1] + `" id="kalaIds" checked>
</td>
</tr>`);

    }
}

function removeAddedKalaFromBrand(element) {
    $('tr').find('input:checkbox:checked').attr("name", "removeKalaFromBrandList[]");
    $('tr').has('input:checkbox:checked').hide();
}

function changeBrandPictureEdit(code, element) {
    document.getElementById("brandItemId" + code).src = window.URL.createObjectURL(element.files[0]);
    document.getElementById("editPictureItem" + code).value = "edit";
}

function addKalaToBrandItem(element) {
    var kalaListID = [];
    $('input[name="kalaListBrand' + element.value + '[]"]:checked').map(function () {
        kalaListID.push($(this).val());
    });
    $('input[name="kalaListBrand' + element.value + '[]"]:checked').parents('tr').css('color', 'white');
    $('input[name="kalaListBrand' + element.value + '[]"]:checked').parents('tr').children('td').css('background-color', 'red');
    $('input[name="kalaListBrand' + element.value + '[]"]:checked').prop("disabled", true);
    $('input[name="kalaListBrand' + element.value + '[]"]:checked').prop("checked", false);


    for (let i = 0; i < kalaListID.length; i++) {
        $('#addedKalaOfBrand' + element.value).append(`<tr class="addedTrList" onclick="checkCheckBox(this,event)">
                                    <td>` + (i + 1) + `</td>
                                    <td>` + kalaListID[i].split('_')[1] + `</td>
                                    <td>
                                    <input class="addKalaToList form-check-input" name="addedKalaToBrandItem[]" type="checkbox" value="` + kalaListID[i].split('_')[0] + `_` + kalaListID[i].split('_')[1] + `" id="kalaIds" checked>
                                    </td>
                                </tr>`);

    }
}

function removeKalaFromBrandItemEdit(element) {

    $('tr').find('input:checkbox:checked').attr("name", "removeKalaFromBrand" + element.value + "List[]");
    $('tr').has('input:checkbox:checked').hide();
}

function setSubGroupForBrand(element) {
    let mainGrId = element.value.split('_')[0];
    let brandItem = element.value.split('_')[1];
    $.ajax({
        method: 'get',
        url: baseUrl + "/getSubGroups",
        data: {
            _token: "{{ csrf_token() }}",
            mainGrId: mainGrId
        },
        async: true,
        success: function (arrayed_result) {
            $("#searchSubGroupForBrand" + brandItem).empty();
            for (var i = 0; i <= arrayed_result.length - 1; i++) {
                $("#searchSubGroupForBrand" + brandItem).append(`<option value="` + arrayed_result[i].id + `_` + brandItem + `">` + arrayed_result[i].title + `</option>`);
            }
        },
        error: function (data) {
        }
    });
}


function searchKalaForBrand(element) {
    let brandItem = element.id;
    let searchTerm = element.value;
    $.ajax({
        method: 'get',
        url: baseUrl + "/searchKalaByName",
        async: true,
        data: {
            _token: "{{ csrf_token() }}",
            name: searchTerm
        },
        success: function (arrayed_result) {
            $('#brandAllKalaContainer' + brandItem).empty();

            for (var i = 0; i <= arrayed_result.length - 1; i++) {
                $('#brandAllKalaContainer' + brandItem).append(`
    <tr  onclick="checkCheckBox(this,event)">
        <td>` + (i + 1) + `</td>
        <td>` + arrayed_result[i].GoodName + `</td>
        <td>
        <input class="form-check-input" name="brandAllKala` + brandItem + `[]" type="checkbox" value="` +
                    arrayed_result[i].GoodSn + `_` + arrayed_result[i]
                        .GoodName + `" id="kalaId">
        </td>
    </tr>
`);
            }

        },
        error: function (data) { }

    });
}

function getsubGroupKalaForBrand(element) {
    let subGrId = element.value.split('_')[0];
    let brandItem = element.value.split('_')[1];
    $.ajax({
        method: 'get',
        url: baseUrl + "/getSubGroupKala",
        data: {
            _token: "{{ csrf_token() }}",
            id: subGrId
        },
        async: true,
        success: function (arrayed_result) {
            $('#brandAllKalaContainer' + brandItem).empty();

            for (var i = 0; i <= arrayed_result.length - 1; i++) {
                $('#brandAllKalaContainer' + brandItem).append(`
    <tr  onclick="checkCheckBox(this,event)">
        <td>` + (i + 1) + `</td>
        <td>` + arrayed_result[i].GoodName + `</td>
        <td>
        <input class="form-check-input" name="brandAllKala` + brandItem + `[]" type="checkbox" value="` +
                    arrayed_result[i].GoodSn + `_` + arrayed_result[i]
                        .GoodName + `" id="kalaId">
        </td>
    </tr>`
                );
            }
        },
        error: function (data) { }
    });
}

function removeKalaFromBrandItem(element) {
    $('input[name="addedKalaToBrandItem' + element.value + '[]"]:checked').attr("name", "removeKalaFrombrand" + element.value + "[]");
    $('input[name="removeKalaFrombrand[]"]:checked').parents('tr').hide();

}

function setDeleteBrandButtonEditValue(element) {
    document.querySelector("#deleteBrandButton").value = element.value;
}

function addBrandItemEdit(element) {
    let counter = element.value;
    counter++;
    element.value = counter;
    let itmeTedad = document.querySelector("#itemTedadEdit").value;
    document.querySelector("#itemTedadEdit").value = parseInt(itmeTedad) + 1;
    document.querySelector("#addBrandItem").innerHTML += `  <div class='product-item swiper-slide'>
                                                <div>
                                                    <button type="button" id="takhsisKala` + counter + `"  onClick="displayTakhsisContainer(this)" value="#takhsisContainer` + counter + `" class="takhsisKala btn btn-success">  تخصیص <i class="fa-light fa-image fa-lg"></i></button>
                                                </div>
                                                <img id="mainPicEdit` + counter + `" src="{{url('/resources/assets/images/kala/_1.jpg')}}" />
                                                <div>
                                                    <label for="brandPic` + counter + `" class="btn btn-success">  ویرایش <i class="fa-light fa-image fa-lg"></i></label>
                                                    <input type="file"  onchange='document.getElementById("mainPicEdit` + counter + `").src = window.URL.createObjectURL(this.files[0]); ' style="display: none" class="form-control" name="brandPic` + counter + `" id="brandPic` + counter + `">
                                                </div>
                                            <input type="radio" name="BrandItem" onchange='setDeleteBrandButtonEditValue(this)' id="brandPictureStuff` + counter + `" value="` + counter + `" name="deleteBrandItem" class="deleteBrandItem" />
                                            </div>`;
    document.querySelector("#addTakhsisKala").innerHTML += `
<div class="takhsisContainer row" id="takhsisContainer` + counter + `">
<div class="col-sm-5">
<div class="row" >
<div class="col-sm-12">
<div class='modal-body'>
<div class='c-checkout' style='padding-right:0;'>
<div class="form-group">
<label class="form-label">فعالسازی انتخاب همه</label>
<input type="checkbox" name="showAll` + counter + `" >
</div>
<div class="form-group">
<label class="form-label">نمایش تعداد کالا</label>
<input type="number" required name="showTedad` + counter + `" class="form-control ">
</div>
</div>
</div>
</div>
</div>
<div class='modal-body'>
<div class='c-checkout' style='padding-right:0;'>
<table class="tableSection table table-bordered table table-hover table-sm table-light" style='td:hover{ cursor:move;}'>
    <thead>
        <tr>
            <th>ردیف</th>
            <th>اسم((` + counter + `)) </th>
            <th>انتخاب</th>
        </tr>
    </thead>
    <tbody style="height: 400px;" id="kalaListBrand` + counter + `">

    </tbody>
</table>
</div>
</div>
</div>

<div class="col-sm-2" style="">
<div class='modal-body' style="position:relative; left: 5%; top: 30%;">
<div style="">
<button type="button" id="addDataToBrandItem` + counter + `" value="` + counter + `" onclick="addKalaToBrandItem(this)">
<i class="fa-regular fa-circle-chevron-left fa-3x"></i>
</button>
<br/>
<button type="button"  id="removeDataFromBrandItem` + counter + `" value="` + counter + `"  onclick="removeKalaFromBrandItem(this)">
<i class="fa-regular fa-circle-chevron-right fa-3x"></i>
</button>
</div>
</div>
</div>

<div class="col-sm-5">
<div class='modal-body'>
<div class='c-checkout' style='padding-right:0;'>
<table class="tableSection table table-bordered table table-hover table-sm table-light" style='td:hover{ cursor:move;}'>
    <thead>
        <tr>
            <th>ردیف</th>
            <th>گروه اصلی </th>
            <th>انتخاب</th>
        </tr>
    </thead>
    <tbody style="height: 400px;" id="addedKalaOfBrand` + counter + `">

    </tbody>
</table>
</div>
</div>
</div>
</div>
`;
    $.ajax({
        method: 'get',
        url: baseUrl + '/getListKala',
        async: true,
        success: function (arrayed_result) {
            for (var i = 0; i <= arrayed_result.length - 1; i++) {

                $(`#kalaListBrand` + counter + ``).append(` <tr onClick="checkCheckBox(this,event)">
    <td> ` + arrayed_result[i].GoodSn + ` </td> <td> ` + arrayed_result[i].GoodName + ` </td>
    <td>
    <input class = "form-check-input" name="kalaListBrand` + counter + `[]" type="checkbox" value="` +
                    arrayed_result[i].GoodSn + `_` + arrayed_result[i]
                        .GoodName + `" id="kalaId">
                                                </td>
                                            </tr>
                                            `);

            }
        },
        error: function (data) {
            alert("not good");
        }

    });

}

//buying kala SetQty
function AddQty(code, event) {

    $.ajax({
        type: "get",
        url: baseUrl + "/getUnits",
        data: { _token: "{{ csrf_token() }}", Pcode: code },
        crossDomain: false,
        dataType: 'json',
        success: function (msg) {
            $("#unitStuffContainer").html(msg);
            const modal = document.querySelector('.modalBackdrop');
            const modalContent = modal.querySelector('.modal');
            modal.classList.add('active');
            modal.addEventListener('click', () => {
                modal.classList.remove('active');
            });
        },
        error: function (msg) {
            alert("مشکل تنظیمات اختصاصی وب");
            console.log(msg);
        },
        headers: {
            'Access-Control-Allow-Origin': '*'
        }
    });
}

function AddQtyPishKharid(code, event) {
    $.ajax({
        type: "get",
        url: baseUrl + '/getUnitsForPishKharid',
        data: { _token: "{{ csrf_token() }}", Pcode: code },
        dataType: "json",
        success: function (msg) {
            $("#unitStuffContainer").html(msg);
            const modal = document.querySelector('.modalBackdrop');
            const modalContent = modal.querySelector('.modal');
            modal.classList.add('active');

            modal.addEventListener('click', () => {
                modal.classList.remove('active');
            });

            // modalContent.addEventListener('click', (e) => e.stopPropagation());
        },
        error: function (msg) {
            console.log(msg);
        }
    });
}

function SetMinQty() {
    const code = $("#kalaIdEdit").val();
    $.ajax({
        type: "get",
        url: baseUrl + "/getUnitsForSettingMinSale",
        data: { _token: "{{ csrf_token() }}", Pcode: code },
        dataType: "json",
        success: function (msg) {
            $("#unitStuffContainer").html(msg);
            const modal = document.querySelector('.modalBackdrop');
            const modalContent = modal.querySelector('.modal');
            modal.classList.add('active');
            modal.addEventListener('click', () => {
                modal.classList.remove('active');
            });
        },
        error: function (msg) {
            alert('Not good');
            console.log(msg);
        }
    });
}



function SetMaxQty() {
    const code = $("#kalaIdEdit").val();
    $.ajax({
        type: "get",
        url: baseUrl + "/getUnitsForSettingMaxSale",
        data: { _token: "{{ csrf_token() }}", Pcode: code },
        dataType: "json",
        success: function (msg) {
            $("#unitStuffContainer").html(msg);
            const modal = document.querySelector('.modalBackdrop');
            const modalContent = modal.querySelector('.modal');
            modal.classList.add('active');
            modal.addEventListener('click', () => {
                modal.classList.remove('active');
            });

        },
        error: function (msg) {
            alert('Not good');
            console.log(msg);
        }
    });
}

function UpdateQty(code, event, SnOrderBYS) {
    $.ajax({
        type: "get",
        url: baseUrl + "/getUnitsForUpdate",
        data: {
            _token: "{{ csrf_token() }}",
            Pcode: code
        },
        dataType: "json",
        success: function (msg) {
            $("#unitStuffContainer").html(msg);
            $(".SnOrderBYS").val(SnOrderBYS);
            const modal = document.querySelector('.modalBackdrop');
            const modalContent = modal.querySelector('.modal');
            modal.classList.add('active');
            modal.addEventListener('click', () => {
                modal.classList.remove('active');
            });


        },
        error: function (msg) {
            console.log(msg);
        }
    });
}


function SetFavoriteGood(snGood) {
    $.ajax({
        type: "get",
        url: baseUrl + "/setFavorite",
        data: { _token: "{{ csrf_token() }}", goodSn: snGood },
        dataType: "json",
        success: function (msg) {
            var goodSn = msg.split('_')[1];
            var flag = msg.split('_')[0];
            if (flag == "added") {
                $('#hear' + goodSn).addClass('fas fa-heart text-danger');
            }
            if (flag == "deleted") {
                $('#hear' + goodSn).removeClass('fas fa-heart text-danger');
                $('#hear' + goodSn).addClass('far fa-heart');
            }
        },
        error: function (msg) {
            console.log(msg);
        }
    });
}
//used for changing priority of mainGroups
function changeMainGroupPriority(element) {
    let mainGroupId = document.querySelector('#mianGroupId').value;
    let priorityState = element.value;
    $.ajax({
        type: "get",
        url: baseUrl + "/changeMainGroupPriority",
        data: {
            _token: "{{ csrf_token() }}",
            mainGrId: mainGroupId,
            priorityState: priorityState
        },
        dataType: "json",
        success: function (arrayed_result) {
            $('#mainGroupList2').empty();
            for (var i = 0; i <= arrayed_result.length - 1; i++) {
                $('#mainGroupList2').append(
                    `<tr onclick="changeMainGroupStuff(this)" >
        <td> ` + (i + 1) + ` </td> <td> ` + arrayed_result[i].title + ` </td> <td>
        <input class="mainGroupId" type ="radio" name="mainGroupId[]" value="` + arrayed_result[i].id + '_' + arrayed_result[i].title + `" id="flexCheckChecked" >
        </td>
    </tr>`);

            }
        },
        error: function (msg) {
            console.log(msg);
        }
    });
}
//for removing from 5 pic 5
$(document).on('click', '#removeData5Pic5', (function () {
    $('tr').find('input:checkbox:checked').attr("name", "removeKalaFromList[]");
    $('tr').has('input:checkbox:checked').hide();
}));
//for removing from 5 pic 1
$(document).on('click', '#removeData5Pic1', (function () {
    $('tr').find('input:checkbox:checked').attr("name", "removeKalaFromList[]");
    $('tr').has('input:checkbox:checked').hide();
}));
//for removing from 5 pic 2
$(document).on('click', '#removeData5Pic2', (function () {
    $('tr').find('input:checkbox:checked').attr("name", "removeKalaFromList[]");
    $('tr').has('input:checkbox:checked').hide();
}));
//for removing from 5 pic 3
$(document).on('click', '#removeData5Pic3', (function () {
    $('tr').find('input:checkbox:checked').attr("name", "removeKalaFromList[]");
    $('tr').has('input:checkbox:checked').hide();
}));
//for removing from 5 pic 4
$(document).on('click', '#removeData5Pic4', (function () {
    $('tr').find('input:checkbox:checked').attr("name", "removeKalaFromList[]");
    $('tr').has('input:checkbox:checked').hide();
}));
//for removing from 1 pic 1
$(document).on('click', '#removeDataOnePic1', (function () {
    $('tr').find('input:checkbox:checked').attr("name", "removeKalaFromList[]");
    $('tr').has('input:checkbox:checked').hide();
}));
//for removing from 2 pic 1
$(document).on('click', '#removeDataTwoPic1', (function () {
    $('tr').find('input:checkbox:checked').attr("name", "removeKalaFromList[]");
    $('tr').has('input:checkbox:checked').hide();
}));
//for removing from 2 pic 2
$(document).on('click', '#removeDataTwoPic2', (function () {
    $('tr').find('input:checkbox:checked').attr("name", "removeKalaFromList[]");
    $('tr').has('input:checkbox:checked').hide();
}));
//for removing from 3 pic 1
$(document).on('click', '#removeData3Pic1', (function () {
    $('tr').find('input:checkbox:checked').attr("name", "removeKalaFromList[]");
    $('tr').has('input:checkbox:checked').hide();
}));
//for removing from 3 pic 2
$(document).on('click', '#removeData3Pic2', (function () {
    $('tr').find('input:checkbox:checked').attr("name", "removeKalaFromList[]");
    $('tr').has('input:checkbox:checked').hide();
}));
//for removing from 3 pic 3
$(document).on('click', '#removeData3Pic3', (function () {
    $('tr').find('input:checkbox:checked').attr("name", "removeKalaFromList[]");
    $('tr').has('input:checkbox:checked').hide();
}));
//for removing from 4 pic 1
$(document).on('click', '#removeData4Pic1', (function () {
    $('tr').find('input:checkbox:checked').attr("name", "removeKalaFromList[]");
    $('tr').has('input:checkbox:checked').hide();
}));
//for removing from 4 pic 2
$(document).on('click', '#removeData4Pic2', (function () {
    $('tr').find('input:checkbox:checked').attr("name", "removeKalaFromList[]");
    $('tr').has('input:checkbox:checked').hide();
}));
//for removing from 4 pic 3
$(document).on('click', '#removeData4Pic3', (function () {
    $('tr').find('input:checkbox:checked').attr("name", "removeKalaFromList[]");
    $('tr').has('input:checkbox:checked').hide();
}));
//for removing from 4 pic 4
$(document).on('click', '#removeData4Pic4', (function () {
    $('tr').find('input:checkbox:checked').attr("name", "removeKalaFromList[]");
    $('tr').has('input:checkbox:checked').hide();
}));

//used for setting priority of brand in a part
$(document).on('click', '.brandPriority', (function () {
    let brandId = $('input[name="brandAddedKala[]"]:checked').val();
    let partId = $('#partId').val();
    $.ajax({
        method: 'get',
        url: baseUrl + "/changeBrandPartPriority",
        async: true,
        data: {
            _token: "{{ csrf_token() }}",
            brandId: brandId,
            partId: partId,
            priority: $(this).val()
        },
        success: function (arrayed_result) {
            $('#brandAddedKalaContainer').empty();
            for (var i = 0; i <= arrayed_result.length - 1; i++) {
                let check = ""
                if (brandId == arrayed_result[i].brandId) {
                    check = "checked"
                }
                $('#brandAddedKalaContainer').append(
                    `<tr  onClick="checkCheckBox(this,event)">
                    <td>` + (i + 1) + `</td>
                    <td>` + arrayed_result[i].name + `</td>
                    <td>
                        <input class="form-check-input" name="brandAddedKala[]" type="checkBox" ` + check + `  value="` + arrayed_result[i].brandId + `" id="flexCheckChecked">
                    </td>
                </tr>
            `);
            }
        },
        error: function (data) {
            alert("not good");
        }

    });
}));

//used for setting priority of brand in a part
$(document).on('click', '.groupPartPriority', (function () {
    let groupId = $('input[name="groupIds[]"]:checked').val();
    let partId = $('#partId').val();
    $.ajax({
        method: 'get',
        url: baseUrl + "/changeGroupPartPriority",
        async: true,
        data: {
            _token: "{{ csrf_token() }}",
            groupId: groupId,
            partId: partId,
            priority: $(this).val()
        },
        success: function (arrayed_result) {
            $('#addedGroups').empty();
            for (var i = 0; i <= arrayed_result.length - 1; i++) {
                let check = ""
                if (groupId == arrayed_result[i].id) {
                    check = "checked"
                }
                $('#addedGroups').append(
                    `<tr  onClick="checkCheckBox(this,event)">
                    <td>` + (i + 1) + `</td>
                    <td>` + arrayed_result[i].title + `</td>
                    <td>
                        <input class="form-check-input" name="groupIds[]" type="checkBox" ` + check + `  value="` + arrayed_result[i].firstGroupId + `" id="flexCheckChecked">
                    </td>
                </tr>
            `);
            }
        },
        error: function (data) {
            alert("not good");
        }

    });
}));


//used for changing priority of subGroups
function changeSubGroupPriority(element) {
    let subGroupId = document.querySelector('#secondGroupId').value;
    let mainGroupId = document.querySelector('#mianGroupId').value;
    let priorityState = element.value;
    $.ajax({
        type: "get",
        url: baseUrl + "/changeSubGroupPriority",
        data: {
            _token: "{{ csrf_token() }}",
            subGrId: subGroupId,
            priorityState: priorityState,
            mainGroupId: mainGroupId
        },
        dataType: "json",
        success: function (data) {
            if (data.length < 1) {
                document.querySelector("#deleteGroupList").disabled = false;
            } else {
                document.querySelector("#deleteGroupList").disabled = true;
            }
            $('#subGroup01').empty();
            for (var i = 0; i <= data.length - 1; i++) {
                $('#subGroup01').append(
                    ` <tr class ="subGroupList1"
            onClick ="changeId(this)" >
                <td> ` + (i + 1) + ` </td> <td > ` + data[i].title + ` </td> <td> <input class="subGroupId" name ="subGroupId[]"
            value="` + data[i].id + `_` + data[i].selfGroupId + `_` +
                    data[i].percentTakhf + `_` + data[i].title +
                    `" type="radio" id="flexCheckChecked` + i + `"></td>`);
            }
        },
        error: function (msg) {
            console.log(msg);
        }
    });
}

function activeSubmitInfo(element) {
    switch (element.id) {
        case "aboutUs":
            document.querySelector("#aboutUsSubmit").disabled = false;
            break;
        case "privacy":
            document.querySelector("#privacySubmit").disabled = false;
            break;
        case "storeInfo":
            document.querySelector("#storeSubmit").disabled = false;
            break;
        case "rules":
            document.querySelector("#rulesSubmit").disabled = false;
            break;
        case "more":
            document.querySelector("#moreFirst").disabled = false;
            break;
        case "more2":
            document.querySelector("#moreSecond").disabled = false;
            break;
        default:
            break;
    }
}

function changeId(element) {
    $(element).find('input:radio').prop('checked', true);
    let inp = $("#subGroupTable tr").find('input:radio');
    $('td.selected').removeClass("selected");
    $(element).children('td').addClass('selected');
    var checkedValue = $(element).find('input[type=radio]:checked').val();
    groupProperties = checkedValue.split("_");
    $('#subGroupNameEdit').val(groupProperties[3].trim());
    $('#subGroupPercentTakhfEdit').val(groupProperties[2]);
    $('#subGroupIdEdit').val(groupProperties[0]);
    $('#fatherMainGroupIdEdit').val(groupProperties[1]);

    $('#editSubGroupButton').prop('data-target', '#editSubGroup');
    $('#editSubGroupButton').prop('disabled', false);
    $('#addKalaToGroup').css('display', 'flex');
    $('#firstGroupId').val(groupProperties[1]);
    $('#secondGroupId').val(groupProperties[0]);
    $('#subGroupIdForDelete').val(groupProperties[0]);
    $.ajax({
        method: 'get',
        url: baseUrl + "/getSubGroupKala",
        data: {
            _token: "{{ csrf_token() }}",
            id: groupProperties[0]
        },
        async: true,
        success: function (arrayed_result) {
            $('#allKalaOfGroup').empty();
            $('#containPictureDiv').empty();
            if (arrayed_result.length < 1) {
                $('#deleteSubGroup').prop('disabled', false);
            } else {
                $('#deleteSubGroup').prop('disabled', true);
            }
            for (var i = 0; i <= arrayed_result.length - 1; i++) {
                $('#allKalaOfGroup').append(`
                        <tr  onclick="checkCheckBox(this,event)">
                            <td>` + (i + 1) + `</td>
                            <td>` + arrayed_result[i].GoodName + `</td>
                            <td>
                            <input class="form-check-input" name="kalaListOfGroupIds[]" type="checkbox" value="` + arrayed_result[i].GoodSn + `_` + arrayed_result[i].GoodName + `" id="kalaId">
                            </td>
                        </tr>
                    `);

            }

        },
        error: function (data) { }

    });

    $.ajax({
        method: 'get',
        url: baseUrl + "/getListOfSubGroup",
        data: {
            _token: "{{ csrf_token() }}",
            id: groupProperties[0]
        },
        async: true,
        success: function (arrayed_result) {
            $('#allKalaForGroup').empty();
            for (var i = 0; i <= arrayed_result.length - 1; i++) {
                $('#allKalaForGroup').append(`
                    <tr  onclick="checkCheckBox(this,event)">
                        <td>` + (i + 1) + `</td>
                        <td>` + arrayed_result[i].GoodName + `</td>
                        <td>
                        <input class="form-check-input" name="kalaListForGroupIds[]" type="checkbox" value="` +
                    arrayed_result[i].GoodSn + `_` + arrayed_result[i]
                        .GoodName + `" id="kalaId">
                        </td>
                    </tr>
                `);
            }
        },
        error: function (data) { }

    });

}


function checkCheckBox(element, event) {
    if (event.target.type == "checkbox") {
        e.stopPropagation();
    } else {
        if ($(element).find('input:checkbox').prop('disabled') == false) {
            if ($(element).find('input:checkbox').prop('checked') == false) {
                $(element).find('input:checkbox').prop('checked', true);
                $("#subGroupofSubGrouppBtn").prop('disabled', false);

            } else {
                $(element).find('input:checkbox').prop('checked', false);
                $(element).find('td.selected').removeClass("selected");
            }
        }
    }
}

function openChangePriceModal() {
    $("#changePriceModal").modal("show");
}

function doAddPM() {
    let pmContent = document.querySelector("#messageContent").value;
    $.ajax({
        method: 'GET',
        url: baseUrl + "/doAddMessage",
        data: {
            _token: "{{ csrf_token() }}",
            pmContent: pmContent
        },
        async: true,
        success: function () {
            $("#messageContent").val("");
            $("#messageList").append(`
                <div class="d-flex flex-row justify-content-start mb-2">
                    <img src="/resources/assets/images/boy.png" alt="avatar 1" style="width: 45px; height: 100%;">
                    <div class="p-3 ms-2" style="border-radius: 15px; background-color: rgba(57, 192, 237,.2);">
                        <p class="small mb-0" style="font-size:1rem;"> ` + pmContent + ` </p>
                    </div>
                </div>
                `);
            newMessageAdded();
        },
        error: function (data) { }

    });
}

function requestProduct(customerId, productId) {
    document.querySelector("#ring").play();
    $.ajax({
        method: 'GET',
        url: baseUrl + "/addRequestedProduct",
        data: {
            _token: "{{ csrf_token() }}",
            productId: productId,
            customerId: customerId
        },
        async: true,
        success: function () {
            $('#request' + productId).prepend(`<button class='btn-add-to-cart' value=''
style='padding-right:10px;border:2px solid rgb(251, 10, 10);
background-color:white;
color: rgb(232,20,20);
font-size: 16px;
cursor: pointer;'
class='btn-add-to-cart' value="1" id="afterButton` + productId + `" onclick='cancelRequestKala(` + customerId + `,` + productId + `)'>اعلام شد</button>`);
            $('#request' + productId).attr("id", 'norequest' + productId);
            $('#preButton' + productId).css('display', 'none');
        },
        error: function (data) { }

    });

}

function cancelRequestKala(customerId, productId) {
    $.ajax({
        method: 'GET',
        url: baseUrl + "/cancelRequestedProduct",
        data: {
            _token: "{{ csrf_token() }}",
            psn: customerId,
            gsn: productId
        },
        async: true,
        success: function (resp) {
            if (resp == 1) {
                $('#norequest' + productId).prepend(`<button value="0"  id="preButton` + productId + `" onclick="requestProduct(` + customerId + `,` + productId + `)" style="padding-right:5px;background-color:red; font-size:14px; cursor:pointer;" class="btn-add-to-cart">خبرم کنید <i class="fas fa-bell"></i></button>`);
                $('#norequest' + productId).attr("id", 'request' + productId);
                $('#afterButton' + productId).css('display', 'none');
            }
        },
        error: function (data) { }

    });
}

function checkActivation() {
    let activer = document.querySelector("#inactiveAll");
    if (activer.checked) {
        let inputElements = document.getElementsByTagName('input');
        let len = inputElements.length;

        for (let i = 0; i < len; i++) {
            inputElements[i].disabled = true;
        }
        let buttonElements = document.getElementsByTagName('button');
        let buttonLen = buttonElements.length;

        for (let i = 0; i < buttonLen; i++) {
            buttonElements[i].disabled = true;
        }
        let selectElements = document.getElementsByTagName('select');
        let selectLen = selectElements.length;

        for (let i = 0; i < selectLen; i++) {
            selectElements[i].disabled = true;
        }
        let textAreaElements = document.getElementsByTagName('textArea');
        let textAreaLen = textAreaElements.length;

        for (let i = 0; i < textAreaLen; i++) {
            textAreaElements[i].disabled = true;
        }
        document.querySelector("#inactiveAll").disabled = false;
        document.querySelector("#closeEditModal").disabled = false;
        document.querySelector("#cancelEditModal").disabled = false;
    } else {

    }
}

function displayRequestedKala(gsn) {
    $.ajax({
        method: 'GET',
        url: baseUrl + "/displayRequestedKala",
        data: {
            _token: "{{ csrf_token() }}",
            productId: gsn
        },
        async: true,
        success: function (arrayed_result) {
            let data = arrayed_result;
            $("#modalContent").empty();
            $("#GoodName").text(data[0].GoodName);
            data.forEach((element, index) => {
                $("#modalContent").append(`<tr>
                <td>` + (index + 1) + `</td>
                <td>` + element.peopeladdress + `</td>
                <td>` + element.Name + `</td>
                <td style="width:120px">` + element.TimeStamp + `</td>
                <td>` + element.PhoneStr + `</td>
                <td>` + element.PCode + `</td>
                </tr>`);
            });
            if (!($('.modal.in').length)) {
                $('.modal-dialog').css({
                    top: 0,
                    left: 0
                });
            }
            $('#requestModal').modal({
                backdrop: false,
                show: true
            });

            $('.modal-dialog').draggable({
                handle: ".modal-header"
            });

            $("#requestModal").modal("show");
        },
        error: function (data) { alert("server side error") }

    });
}

function searchRequestedKala(element) {
    $.ajax({
        method: 'GET',
        url: baseUrl + "/searchRequestedKala",
        data: {
            _token: "{{ csrf_token() }}",
            searchTerm: element.value
        },
        async: true,
        success: function (arrayed_result) {
            moment.locale('en');
            $("#requestedKalas").empty();
            arrayed_result.forEach((element, index) => {
                $("#requestedKalas").append(`<tr>
        <td>` + (index + 1) + `</td>
        <td>` + element.GoodName + `</td>
        <td>` + element.countRequest + `</td>
        <td>` + moment(element.TimeStamp, 'YYYY/M/D HH:mm:ss').locale('fa').format('YYYY/M/D') + `</td>
        <td style="text-align:center"><button type="button" onclick="displayRequestedKala(`+ element.GoodSn + `)" style=" background-color: #ffffff;">  <i class="fa fa-eye" style="color:green;"> </i>  </button> </td>
        <td style="text-align:center"><button type="button" onclick="removeRequestedKala(`+ element.GoodSn + `)" class="btn btn-sm"> <i class="fa fa-trash" style="color:red; font-size:18px;"></i> </button></td>
        </tr>`);
            });
        },
        error: function (data) { }
    });
}

function removeRequestedKala(gsn) {
    swal({
        title: 'اخطار!',
        text: 'آیا می خواهید حذف کنید؟',
        icon: 'warning',
        buttons: true
    }).then(function (willAdd) {
        if (willAdd) {
            $.ajax({
                type: 'GET',
                url: baseUrl + "/removeRequestedKala",
                data: {
                    _token: "{{ csrf_token() }}",
                    productId: gsn
                },
                async: true,
                success: function (arrayed_result) {
                    moment.locale('en');
                    $("#requestedKalas").empty();
                    arrayed_result.forEach((element, index) => {
                        $("#requestedKalas").append(`<tr>
            <td>` + (index + 1) + `</td>
            <td>` + element.GoodName + `</td>
            <td>` + element.countRequest + `</td>
            <td>` + moment(element.TimeStamp, 'YYYY/M/D HH:mm:ss').locale('fa').format('YYYY/M/D') + `</td>
            <td style="text-align:center"><button type="button" onclick="displayRequestedKala(`+ element.GoodSn + `)" style=" background-color: #ffffff;">  <i class="fa fa-eye" style="color:green;"> </i>  </button> </td>
            <td style="text-align:center"><button type="button" onclick="removeRequestedKala(`+ element.GoodSn + `)" class="btn btn-sm"> <i class="fa fa-trash" style="color:red; font-size:18px;"></i> </button></td>
            </tr>`);
                    });
                },
                error: function (data) { }

            });
        }
    });
}

function respondKalaRequest(gsn, psn) {
    $.ajax({
        method: 'GET',
        url: baseUrl + "/respondKalaRequest",
        data: {
            _token: "{{ csrf_token() }}",
            customerId: psn,
            productId: gsn
        },
        async: true,
        success: function (arrayed_result) {
            if (arrayed_result == 1) {
                $.ajax({
                    method: 'GET',
                    url: baseUrl + "/displayRequestedKala",
                    data: {
                        _token: "{{ csrf_token() }}",
                        customerId: psn
                    },
                    async: true,
                    success: function (arrayed_result) {
                        let data = arrayed_result;
                        $("#modalContent").empty();
                        for (let index = 0; index < data.length; index++) {
                            $("#modalContent").append(`<tr>
            <td>` + (index + 1) + `</td>
            <td>` + data[index].GoodName + `</td>
            <td><button type="button" onclick="respondKalaRequest(` + data[index].GoodSn + `,` + data[index].customerId + `)" class="btn btn-sm btn-info">بررسی</button></td></tr>`);
                        }
                    },
                    error: function (data) { }

                });
            } else {

            }
        },
        error: function (data) { }

    });
}

$("#firstPrize").on("keyup", function () {
    if (!$("#firstPrize").val()) {
        $("#firstPrize").val(0);
    }
    $("#firstPrize").val(parseInt($('#firstPrize').val().replace(/\,/g, '')).toLocaleString("en-US"));

});
$("#secondPrize").on("keyup", function () {
    if (!$("#secondPrize").val()) {
        $("#secondPrize").val(0);
    }
    $("#secondPrize").val(parseInt($('#secondPrize').val().replace(/\,/g, '')).toLocaleString("en-US"));

});
$("#thirdPrize").on("keyup", function () {
    if (!$("#thirdPrize").val()) {
        $("#thirdPrize").val(0);
    }
    $("#thirdPrize").val(parseInt($('#thirdPrize').val().replace(/\,/g, '')).toLocaleString("en-US"));

});
$("#fourthPrize").on("keyup", function () {
    if (!$("#fourthPrize").val()) {
        $("#fourthPrize").val(0);
    }
    $("#fourthPrize").val(parseInt($('#fourthPrize').val().replace(/\,/g, '')).toLocaleString("en-US"));

});
$("#fifthPrize").on("keyup", function () {
    if (!$("#fifthPrize").val()) {
        $("#fifthPrize").val(0);
    }
    $("#fifthPrize").val(parseInt($('#fifthPrize').val().replace(/\,/g, '')).toLocaleString("en-US"));

});

$("#sixthPrize").on("keyup", function () {
    if (!$("#sixthPrize").val()) {
        $("#sixthPrize").val(0);
    }
    $("#sixthPrize").val(parseInt($('#sixthPrize').val().replace(/\,/g, '')).toLocaleString("en-US"));

});
$("#seventhPrize").on("keyup", function () {
    if (!$("#seventhPrize").val()) {
        $("#seventhPrize").val(0);
    }
    $("#seventhPrize").val(parseInt($('#seventhPrize').val().replace(/\,/g, '')).toLocaleString("en-US"));

});
$("#eightthPrize").on("keyup", function () {
    if (!$("#eightthPrize").val()) {
        $("#eightthPrize").val(0);
    }
    $("#eightthPrize").val(parseInt($('#eightthPrize').val().replace(/\,/g, '')).toLocaleString("en-US"));

});
$("#ninthPrize").on("keyup", function () {
    if (!$("#ninthPrize").val()) {
        $("#ninthPrize").val(0);
    }
    $("#ninthPrize").val(parseInt($('#ninthPrize').val().replace(/\,/g, '')).toLocaleString("en-US"));

});
$("#teenthPrize").on("keyup", function () {
    if (!$("#teenthPrize").val()) {
        $("#teenthPrize").val(0);
    }
    $("#teenthPrize").val(parseInt($('#teenthPrize').val().replace(/\,/g, '')).toLocaleString("en-US"));

});
$("#minSalePriceFactor").on("keyup", function () {
    if (!$("#minSalePriceFactor").val()) {
        $("#minSalePriceFactor").val(0);
    }
    $("#minSalePriceFactor").val(parseInt($('#minSalePriceFactor').val().replace(/\,/g, '')).toLocaleString("en-US"));
});
$("#FactorMinPrice").on("keyup", function () {
    if (!$("#FactorMinPrice").val()) {
        $("#FactorMinPrice").val(0);
    }
    $("#FactorMinPrice").val(parseInt($('#FactorMinPrice').val().replace(/\,/g, '')).toLocaleString("en-US"));

});
function openMessageStuff() {
    var element = document.querySelector("#messageStuff");
    if (element.style.display === "none") {
        element.style.display = "block";
    } else {
        element.style.display = "none";
    }
}

function chekForm(event) {
    let unSelectTime;
    let unslectPayment;
    unSelectTime = document.querySelector('input[name = "recivedTime"]:checked');

    if (unSelectTime == null) {
        alert("بدون انتخاب زمان, خرید ممکن نیست");
        event.preventDefault();
    } else {
        if (event.target.id == 'bankPayment') {
            temproryClosed();

            $.ajax({
                method: 'get',
                url: "https://starfoods.ir/setFactorSessions",
                async: true,
                data: {
                    _token: "{{ csrf_token() }}",
                    recivedTime: $('input[name=recivedTime]:checked').val(),
                    takhfif: $("#discountWallet").val(),
                    receviedAddress: $('select[name="customerAddress"] option:selected').val(),
                    allMoneyToSend: $("#allMoneyToSend").val(),
                    isSent: 0,
                    orderSn: 0
                },
                success: function (respond) {
                },
                error: function (error) {
                    alert("some error exist");
                }
            });

        }
        if (event.target.id == 'hozoori') {
            payHoozori();
        }
    }

    if (document.querySelector('#hozoori') != null) {
        unslectPayment = document.querySelector('#hozoori').checked | document.querySelector('#bankPayment').checked;
    } else {
        unslectPayment = document.querySelector('#bankPayment').checked;
    }
    if (!(unslectPayment)) {
        alert("نوع پرداخت انتخاب نشده است.");
        event.preventDefault();
    }
}



$("#hozoori").on("change", function () {
    if ($("#hozoori").is(":checked")) {
        $("#discountWallet").css({ "color": "#dbdbdb" });
    }
});

$("#bankPayment").on("change", function () {
    if ($("#bankPayment").is(":checked")) {
        $("#discountWallet").css({ "color": "#080808" });
    }
});

function setTargetStuff(element) {
    $(element).find('input:radio').prop('checked', true);
    let input = $(element).find('input:radio');
    const targetId = input.val();
    $("#selectTargetId").val(targetId);
    $("#targetEditBtn").prop("disabled", false);
}
$("#targetEditBtn").on("click", function () {
    const targetId = $("#selectTargetId").val();

    $("#targetIdForEdit").val(targetId);
    $.ajax({
        method: 'get',
        url: baseUrl + "/getTargetInfo",
        data: {
            _token: "{{ csrf_token() }}",
            targetId: targetId
        },
        async: true,
        success: function (data) {
            msg = data[0];
            $("#baseName").val(msg.baseName);
            $("#firstTarget").val(parseInt(msg.firstTarget).toLocaleString("en-US"));
            $("#firstTargetBonus").val(msg.firstTargetBonus);
            $("#secondTarget").val(parseInt(msg.secondTarget).toLocaleString("en-US"));
            $("#secondTargetBonus").val(msg.secondTargetBonus);
            $("#thirdTarget").val(parseInt(msg.thirdTarget).toLocaleString("en-US"));
            $("#thirdTargetBonus").val(msg.thirdTargetBonus);


            if (!($('.modal.in').length)) {
                $('.modal-dialog').css({
                    top: 0,
                    left: 0
                });
            }
            $('#editingTargetModal').modal({
                backdrop: false,
                show: true
            });

            $('.modal-dialog').draggable({
                handle: ".modal-header"
            });
            $("#editingTargetModal").modal("show");
        },
        error: function () {
            alert("cant get data of target!!");
        }
    });

});

$("#editTarget").on("submit", function (e) {
    $("#editingTargetModal").modal("hide");
    $.ajax({
        method: $(this).attr('method'),
        url: $(this).attr('action'),
        data: $(this).serialize(),
        success: function (data) {
            $("#targetList").empty();
            data.forEach((element, index) => {
                $("#targetList").append(`<tr  onclick="setTargetStuff(this)">
                <td>`+ (index + 1) + `</td><td>` + element.baseName + `</td>
                <td>`+ parseInt(element.firstTarget).toLocaleString("en-US") + `</td><td>` + element.firstTargetBonus + `</td>
                <td>`+ parseInt(element.secondTarget).toLocaleString("en-US") + `</td><td>` + element.secondTargetBonus + `</td>
                <td>`+ parseInt(element.thirdTarget).toLocaleString("en-US") + `</td><td>` + element.thirdTargetBonus + `</td>
                <td><input class="form-check-input" name="targetId" type="radio" value="`+ element.id + `"></td>
                </tr>`);
            });
        },
        error: function (error) {

        }
    });
    e.preventDefault();
});

$("#selectTarget").on("change", function () {
    const targetId = $(this).val();
    $.ajax({
        method: 'get',
        url: baseUrl + "/getTargetInfo",
        data: {
            _token: "{{ csrf_token() }}",
            targetId: targetId
        },
        async: true,
        success: function (data) {
            $("#targetList").empty();
            data.forEach((element, index) => {
                $("#targetList").append(`<tr  onclick="setTargetStuff(this)">
                <td>`+ (index + 1) + `</td><td>` + element.baseName + `</td>
                <td>`+ element.firstTarget + `</td><td>` + element.firstTargetBonus + `</td>
                <td>`+ element.secondTarget + `</td><td>` + element.secondTargetBonus + `</td>
                <td>`+ element.thirdTarget + `</td><td>` + element.thirdTargetBonus + `</td>
                <td><input class="form-check-input" name="targetId" type="radio" value="`+ element.id + `"></td>
                </tr>`);
            });
        },
        error: function () {
            alert("cant get data of target!!");
        }
    });
});

$('#firstTarget').on("keyup", () => {

    if (!$("#firstTarget").val()) {

        $("#firstTarget").val(0);

    }

    $('#firstTarget').val(parseInt($('#firstTarget').val().replace(/\,/g, '')).toLocaleString("en-US"));

});
$('#secondTarget').on("keyup", () => {

    if (!$("#secondTarget").val()) {

        $("#secondTarget").val(0);

    }

    $('#secondTarget').val(parseInt($('#secondTarget').val().replace(/\,/g, '')).toLocaleString("en-US"));

});
$('#thirdTarget').on("keyup", () => {

    if (!$("#thirdTarget").val()) {

        $("#thirdTarget").val(0);

    }

    $('#thirdTarget').val(parseInt($('#thirdTarget').val().replace(/\,/g, '')).toLocaleString("en-US"));

});

$("#editLotteryPrizeBtn").on("click", function () {
    $.ajax({
        method: 'get',
        url: baseUrl + "/getLotteryInfo",
        async: true,
        success: function (arrayed_result) {
            let prizes = arrayed_result[0];
            $("#LotfirstPrize").val(prizes.firstPrize.trim());
            $("#LotsecondPrize").val(prizes.secondPrize.trim());
            $("#LotthirdPrize").val(prizes.thirdPrize.trim());
            $("#LotfourthPrize").val(prizes.fourthPrize.trim());
            $("#LotfifthPrize").val(prizes.fifthPrize.trim());
            $("#LotsixthPrize").val(prizes.sixthPrize.trim());
            $("#LotseventhPrize").val(prizes.seventhPrize.trim());
            $("#LoteightthPrize").val(prizes.eightthPrize.trim());
            $("#LotninethPrize").val(prizes.ninethPrize.trim());
            $("#LotteenthPrize").val(prizes.teenthPrize.trim());
            $("#LoteleventthPrize").val(prizes.eleventhPrize.trim());
            $("#LottwelvthPrize").val(prizes.twelvthPrize.trim());
            $("#LottherteenthPrize").val(prizes.therteenthPrize.trim());
            $("#LotfourteenthPrize").val(prizes.fourteenthPrize.trim());
            $("#LotfifteenthPrize").val(prizes.fifteenthPrize.trim());
            $("#LotsixteenthPrize").val(prizes.sixteenthPrize.trim());

            prizes.showfirstPrize == 1 ? $("#showfirstPrize").prop("checked", "checked") : $("#showfirstPrize").prop("checked", false);
            prizes.showsecondPrize == 1 ? $("#showsecondPrize").prop("checked", "checked") : $("#showsecondPrize").prop("checked", false);
            prizes.showthirdPrize == 1 ? $("#showthirdPrize").prop("checked", "checked") : $("#showthirdPrize").prop("checked", false);
            prizes.showfourthPrize == 1 ? $("#showfourthPrize").prop("checked", "checked") : $("#showfourthPrize").prop("checked", false);
            prizes.showfifthPrize == 1 ? $("#showfifthPrize").prop("checked", "checked") : $("#showfifthPrize").prop("checked", false);
            prizes.showsixthPrize == 1 ? $("#showsixthPrize").prop("checked", "checked") : $("#showsixthPrize").prop("checked", false);
            prizes.showseventhPrize == 1 ? $("#showseventhPrize").prop("checked", "checked") : $("#showseventhPrize").prop("checked", false);
            prizes.showeightthPrize == 1 ? $("#showeightthPrize").prop("checked", "checked") : $("#showeightthPrize").prop("checked", false);
            prizes.showninethPrize == 1 ? $("#showninethPrize").prop("checked", "checked") : $("#showninethPrize").prop("checked", false);
            prizes.showteenthPrize == 1 ? $("#showteenthPrize").prop("checked", "checked") : $("#showteenthPrize").prop("checked", false);
            prizes.showeleventthPrize == 1 ? $("#showeleventthPrize").prop("checked", "checked") : $("#showeleventthPrize").prop("checked", false);
            prizes.showtwelvthPrize == 1 ? $("#showtwelvthPrize").prop("checked", "checked") : $("#showtwelvthPrize").prop("checked", false);
            prizes.showtherteenthPrize == 1 ? $("#showtherteenthPrize").prop("checked", "checked") : $("#showtherteenthPrize").prop("checked", false);
            prizes.showfourteenthPrize == 1 ? $("#showfourteenthPrize").prop("checked", "checked") : $("#showfourteenthPrize").prop("checked", false);
            prizes.showfifteenthPrize == 1 ? $("#showfifteenthPrize").prop("checked", "checked") : $("#showfifteenthPrize").prop("checked", false);
            prizes.showsixteenthPrize == 1 ? $("#showsixteenthPrize").prop("checked", "checked") : $("#showsixteenthPrize").prop("checked", false);
        },
        error: function (error) {
            alert("you have error in your data getting");
        }
    });
});

$("#editSendForm").on("submit", function (e) {
    $.ajax({
        url: $(this).attr('action'),
        type: $(this).attr('method'),
        dataType: 'json',
        data: $(this).serialize(),
        success: function (data) {
            window.location.reload();
        },
        error: function (error) {

        }
    });

    e.preventDefault();
});

$("#editOrderBtn").on("click", () => {
    $.ajax({
        method: 'get',
        url: baseUrl + '/getOrderDetail',
        async: true,
        data: {
            _token: "{{ csrf_token() }}",
            orderSn: $("#editOrderBtn").val()

        },
        success: function (response) {
            $("#editFactorNo").val(response[1][0].OrderNo);
            $("#editCustomerSn").val(response[1][0].CustomerSn);
            $("#editOrderDate").val(response[1][0].OrderDate);
            $("#editPCode").val(response[1][0].PCode);
            $("#editName").val(response[1][0].Name);
            $("#editDiscription").val(response[1][0].OrderDesc);
            $("#editFatorHDS").val(response[1][0].SnOrder);
            if (response[1][0].isSent == 1) {
                $("#editSendState").css("display", "inline");
                $("#editSaveBtn").css("display", "inline");
                $("#editSentOrderSn").val(response[1][0].SnOrder);
            }
            if (response[1][0].isSent == 1 && response[1][0].isDistroy == 0) {
                $("#editSentOption").prop('selected', true);
            } else {
                if (response[1][0].isSent == 0 && response[1][0].isDistroy == 0) {
                    $("#editUnSentOption").prop('selected', true);
                } else {
                    $("#editDistroyOption").prop('selected', true);
                }
            }
            if (response[4][0]) {
                $("#editTotalMoney").text(parseInt(response[4][0].totalMoney / 10).toLocaleString("en-us"));
            } else {
                $("#editTotalMoney").text(parseInt(0 / 10).toLocaleString("en-us"));
            }
            if (response[3][0]) {
                $("#editTotalCosts").text(parseInt(response[3][0].totalPrice / 10).toLocaleString("en-us"));
            } else {
                $("#editTotalCosts").text(parseInt(0 / 10).toLocaleString("en-us"));
            }
            $("#editTakhfifTotal").val(parseInt(response[1][0].Takhfif / 10));
            $("#editHdsSn").val(response[1][0].SnOrder);
            $("#HdsSn").val(response[1][0].SnOrder);
            $("#editInVoiceNumber").val(response[1][0].InVoiceNumber);
            if (response[2].length < 1) {
                $("#editSabtBtn").prop("disabled", false);
            }
            if (response[1][0].OrderErsalTime == 1) {
                $("#editAm").prop("selected", true);
            } else {
                $("#editPm").prop("selected", true);
            }
            $("#editAddress").empty();
            $("#editAddress").append(`<option value="" selected>` + response[1][0].OrderAddress + `</option>`);

            //نمایش کالای سفارش داده شده
            $("#editSalesOrdersItemsBody").empty();

            response[0].forEach((element, index) => {
                let secondUnit = "ندارد";
                if (element.secondUnit) {
                    secondUnit = element.secondUnit;
                }
                $("#editSalesOrdersItemsBody").append(`                         
                <tr onclick="getEditItemInfo(this,`+ element.SnGood + `,` + element.SnOrderBYSS + `)">
                <td>`+ (index + 1) + `</td>
                <td>`+ element.GoodCde + `</td>
                <td style="width:180px;">`+ element.GoodName + `</td>
                <td>`+ element.DateOrder + `</td>
                <td>`+ element.firstUnit + `</td>
                <td>`+ secondUnit + `</td>
                <td>`+ parseInt(element.PackAmount).toLocaleString("en-us") + `</td>
                <td>0</td>
                <td>`+ parseInt(element.Amount).toLocaleString("en-us") + `</td>
                <td>`+ parseInt(element.Fi / 10).toLocaleString("en-us") + `</td>
                <td>`+ parseInt(element.FiPack / 10).toLocaleString("en-us") + ` </td>
                <td>`+ parseInt(element.totalPrice / 10).toLocaleString("en-us") + `</td>
                <td>`+ element.DescRecord + `</td>
                </tr>
                `);
            });

            if (!($('.modal.in').length)) {
                $('.modal-dialog').css({
                    top: 0,
                    left: 0
                });
            }
            $('#orderEditingModal').modal({
                backdrop: false,
                show: true
            });

            $('.modal-dialog').draggable({
                handle: ".modal-header"
            });

            $("#orderEditingModal").modal("show");
        },
        error: function (error) {

        }

    });


});

$("#distroyOrderBtn").on("click", () => {
    swal({
        title: 'اخطار!',
        text: 'آیا می خواهید حذف کنید؟',
        icon: 'warning',
        buttons: true
    }).then(function (willAdd) {
        if (willAdd) {
            $.ajax({
                method: 'get',
                url: baseUrl + '/distroyOrder',
                data: {
                    _token: "{{ csrf_token() }}",
                    orderId: $("#distroyOrderBtn").val()
                },
                success: function (respond) {
                    let orders = respond[0];
                    $("#orderListBody").empty();
                    orders.forEach((element, index) => {
                        let pmOrAm = "عصر";
                        let adminName = "ندارد";
                        if (element.adminName) {
                            adminName = element.adminName;
                        }
                        if (element.OrderErsalTime == 1) {
                            pmOrAm = "صبح";
                        }

                        $("#orderListBody").append(`
            <tr onclick="getOrderDetail(this,`+ element.SnOrder + `,` + element.isPayed + `,` + element.CustomerSn + `)">
            <td>`+ (index + 1) + `</td>
            <td>`+ element.OrderNo + `</td>
            <td>`+ element.OrderDate + `</td>
            <td>`+ element.Name + `</td>
            <td>`+ adminName + `</td>
            <td>`+ parseInt(element.allPrice / 10).toLocaleString("en-us") + ` ت</td>
            <td>`+ parseInt((element.allPrice - element.payedMoney) / 10).toLocaleString("en-us") + ` ت</td>
            <td>`+ element.OrderDesc + `</td>
            <td>`+ element.SaveTimeOrder + `</td>
            <td>`+ pmOrAm + `</td>
        </tr>`);
                    })

                    $("#sendAllMoney").text(parseInt(respond[1].sumAllMoney / 10).toLocaleString("en-us") + `  ت`);
                    $("#sendRemainedAllMoney").text(parseInt((respond[1].sumAllMoney - respond[2].payedMoney) / 10).toLocaleString("en-us") + `  ت`);
                },
                error: function (error) {
                    alert("error on distroying order server side");
                }

            });
        }

    });
});

function getPayDetail(element, FacorSn, psn, isSent) {
    $("tr").removeClass('selected');
    $(element).addClass('selected');
    if (isSent == 0) {
        $("#sendPayToHisabdariBtn").prop("disabled", false);
        $("#sendPayToHisabdariBtn").val(FacorSn);
    } else {
        $("#cancelPayFromHisabdariBtn").prop("disabled", false);
        $("#cancelPayFromHisabdariBtn").val(FacorSn);
    }
}
$("#sendPayToHisabdariBtn").on("click", function () {
    swal({
        title: 'اخطار!',
        text: 'آیا از انتقال مطمیین هستید؟',
        icon: 'warning',
        buttons: true
    }).then(function (willAdd) {
        if (willAdd) {
            $.ajax({
                method: 'get',
                url: baseUrl + "/sendPayToHisabdari",
                async: true,
                data: {
                    _token: "{{ csrf_token() }}",
                    paySn: $("#sendPayToHisabdariBtn").val(),
                    payState: 1
                },
                success: function (response) {
                    $("#paymentListBody").empty();
                    response.forEach((element, index) => {
                        payedClass = "";
                        isSent = "خیر";
                        if (element.isSent == 1) {
                            payedClass = "payedOnline";
                            isSent = "بله";
                        }

                        $("#paymentListBody").append(`
                <tr class="`+ payedClass + `" onclick="getPayDetail(this,` + element.id + `,` + element.PSN + `,` + element.isSent + `)">
                <td>`+ (index + 1) + `</td>
                <td>`+ element.FactNo + `</td>
                <td style="width:80px;">`+ element.payedDate + `</td>
                <td style="width:180px; font-weight:bold;">`+ element.Name + `</td>
                <td  style="font-weight:bold;">`+ parseInt(element.payedMoney / 10).toLocaleString("en-us") + ` ت</td>
                <td style="width:77px;">`+ element.TimeStamp + `</td>
                <td>`+ isSent + `</td>
            </tr>`);
                    });
                    $("#sendPayToHisabdariBtn").prop("disabled", true);
                    $("#cancelPayFromHisabdariBtn").prop("disabled", true);
                },
                error: function (error) {
                    alert("error in getting data");
                }
            });
        }
    });
});
$("#cancelPayFromHisabdariBtn").on("click", function () {
    swal({
        title: 'اخطار!',
        text: 'آیا از انصراف انتقال مطمیین هستید؟',
        icon: 'warning',
        buttons: true
    }).then(function (willAdd) {
        if (willAdd) {
            $.ajax({
                method: 'get',
                url: baseUrl + "/sendPayToHisabdari",
                async: true,
                data: {
                    _token: "{{ csrf_token() }}",
                    paySn: $("#cancelPayFromHisabdariBtn").val(),
                    payState: 0
                },
                success: function (response) {
                    $("#paymentListBody").empty();
                    response.forEach((element, index) => {
                        payedClass = "";
                        isSent = "خیر";
                        if (element.isSent == 1) {
                            payedClass = "payedOnline";
                            isSent = "بله";
                        }

                        $("#paymentListBody").append(`
                <tr class="`+ payedClass + `" onclick="getPayDetail(this,` + element.id + `,` + element.PSN + `,` + element.isSent + `)">
                <td>`+ (index + 1) + `</td>
                <td>`+ element.FactNo + `</td>
                <td style="width:80px;">`+ element.payedDate + `</td>
                <td style="width:180px; font-weight:bold;">`+ element.Name + `</td>
                <td  style="font-weight:bold;">`+ parseInt(element.payedMoney / 10).toLocaleString("en-us") + ` ت</td>
                <td style="width:77px;">`+ element.TimeStamp + `</td>
                <td>`+ isSent + `</td>
            </tr>`);
                    });
                    $("#sendPayToHisabdariBtn").prop("disabled", true);
                    $("#cancelPayFromHisabdariBtn").prop("disabled", true);
                },
                error: function (error) {
                    alert("error in getting data");
                }
            });
        }
    });
});

// $("#sefRemainPayRadio").on("change", () => {
//     $.ajax({
//         method: 'get',
//         url: baseUrl + "/remainedPays",
//         async: true,
//         data: {
//             _token: "{{ csrf_token() }}",
//             payState: 0
//         },
//         success: function (response) {
//             $("#paymentListBody").empty();
//             response.forEach((element, index) => {
//                 payedClass = "";
//                 isSent = "خیر";
//                 if (element.isSent == 1) {
//                     payedClass = "payedOnline";
//                     isSent = "بله";
//                 }

//                 $("#paymentListBody").append(`
//                 <tr class="`+ payedClass + `" onclick="getPayDetail(this,` + element.id + `,` + element.PSN + `,` + element.isSent + `)">
//                 <td>`+ (index + 1) + `</td>
//                 <td>`+ element.FactNo + `</td>
//                 <td style="width:80px;">`+ element.payedDate + `</td>
//                 <td style="width:180px; font-weight:bold;">`+ element.Name + `</td>
//                 <td  style="font-weight:bold;">`+ parseInt(element.payedMoney / 10).toLocaleString("en-us") + ` ت</td>
//                 <td style="width:77px;">`+ element.TimeStamp + `</td>
//                 <td>`+ isSent + `</td>
//             </tr>`);
//             })
//         },
//         error: function (error) {
//             alert("error in getting data");
//         }
//     });
// });
// $("#sefSentPayRadio").on("change", () => {
//     $.ajax({
//         method: 'get',
//         url: baseUrl + "/remainedPays",
//         async: true,
//         data: {
//             _token: "{{ csrf_token() }}",
//             payState: 1
//         },
//         success: function (response) {
//             $("#paymentListBody").empty();
//             response.forEach((element, index) => {
//                 payedClass = "";
//                 isSent = "خیر";
//                 if (element.isSent == 1) {
//                     payedClass = "payedOnline";
//                     isSent = "بله";
//                 }

//                 $("#paymentListBody").append(`
//                 <tr class="`+ payedClass + `" onclick="getPayDetail(this,` + element.id + `,` + element.PSN + `,` + element.isSent + `)">
//                 <td>`+ (index + 1) + `</td>
//                 <td>`+ element.FactNo + `</td>
//                 <td style="width:80px;">`+ element.payedDate + `</td>
//                 <td style="width:180px; font-weight:bold;">`+ element.Name + `</td>
//                 <td  style="font-weight:bold;">`+ parseInt(element.payedMoney / 10).toLocaleString("en-us") + ` ت</td>
//                 <td style="width:77px;">`+ element.TimeStamp + `</td>
//                 <td>`+ isSent + `</td>
//             </tr>`);
//             })
//         },
//         error: function (error) {
//             alert("error in getting data");
//         }
//     });
// });
function getOrderDetail(element, orderSn, isPayed, customerSn) {
    $("tr").removeClass('selected');
    $(element).addClass('selected');
    $("#saleToFactorSaleBtn").val(orderSn);

    $("#newOrderBtn").val(orderSn);
    $("#editOrderBtn").val(orderSn);
    $("#distroyOrderBtn").val(orderSn);
    $("#fakeLogin").prop("disabled", false);
    $("#psn").val(customerSn);
    // $("#printOrderBtn").val(orderSn);
    //  $("#newOrderBtn").prop("disabled",false); 
    $("#editOrderBtn").prop("disabled", false);
    $("#distroyOrderBtn").prop("disabled", false);
    // $("#printOrderBtn").prop("disabled",false);

    $.ajax({
        method: 'get',
        url: baseUrl + "/getOrderDetail",
        async: true,
        data: {
            _token: "{{ csrf_token() }}",
            orderSn: orderSn
        },
        success: function (response) {
            $("#orderDetailBody").empty();

            response[0].forEach((element, index) => {
                $("#orderDetailBody").append(`                         
                <tr>
                <td>`+ (index + 1) + `</td>
                <td style="width:160px;">`+ element.GoodName + `</td>
                <td>`+ element.DateOrder + `</td>
                <td>`+ element.secondUnit + `</td>
                <td>`+ parseInt(element.PackAmount).toLocaleString("en-us") + `</td>
                <td>0</td>
                <td>`+ parseInt(element.Amount).toLocaleString("en-us") + `</td>
                <td> 0 </td>
                <td>`+ parseInt(element.Fi / 10).toLocaleString("en-us") + ` ت</td>
                <td>`+ parseInt(element.totalPrice / 10).toLocaleString("en-us") + ` ت</td>
                <td>`+ element.DescRecord + `</td>
                </tr>
                `);
            });
        }
    });


}


$("#saleToFactorSaleBtn").on("click", () => {
    $.ajax({
        method: 'get',
        url: baseUrl + '/getOrderDetail',
        async: true,
        data: {
            _token: "{{ csrf_token() }}",
            orderSn: $("#saleToFactorSaleBtn").val()

        },
        success: function (response) {
            $("#sendFactorNo").val(response[1][0].OrderNo);
            $("#sendCustomerSn").val(response[1][0].CustomerSn);
            $("#sendOrderDate").val(response[1][0].OrderDate);
            $("#sendPCode").val(response[1][0].PCode);
            $("#sentPhoneStr").val(response[1][0].PhoneStr);
            $("#sentTotalAmount").val(parseInt(response[4][0].totalMoney / 10).toLocaleString("en-us"));
            $("#sentRemainedAmount").val(parseInt(((response[4][0].totalMoney) - (response[1][0].payedMoney)) / 10).toLocaleString("en-us"));
            $("#sendName").val(response[1][0].Name);
            $("#sendDiscription").val(response[1][0].OrderDesc);
            $("#sendFatorHDS").val(response[1][0].SnOrder);
            if (response[2].length < 1) {
                $("#sendSabtBtn").prop("disabled", false);
            }
            if (response[1][0].OrderErsalTime == 1) {
                $("#sendAm").prop("selected", true);
            } else {
                $("#sendPm").prop("selected", true);
            }
            $("#sendAddress").empty();
            $("#sendAddress").append(`<option value="` + response[1][0].OrderSnAddress + `" selected>` + response[1][0].OrderAddress + `</option>`);
            response[5].forEach((element, index) => {
                if (element.AddressPeopel != response[1][0].OrderAddress) {
                    $("#sendAddress").append(`<option value="` + element.SnPeopelAddress + `">` + element.AddressPeopel + `</option>`);
                }
            })
            //نمایش کالای سفارش داده شده
            $("#sendSalesOrdersItemsBody").empty();

            response[0].forEach((element, index) => {
                $("#sendSalesOrdersItemsBody").append(`                         
                <tr onclick="getSendItemInfo(this,`+ element.SnGood + `)">
                <td>`+ (index + 1) + `</td>
                <td>`+ element.GoodCde + `</td>
                <td>`+ element.GoodName + `</td>
                <td>`+ element.DateOrder + `</td>
                <td>`+ element.firstUnit + `</td>
                <td>`+ element.secondUnit + `</td>
                <td>`+ parseInt(element.PackAmount).toLocaleString("en-us") + `</td>
                <td>0</td>
                <td>`+ parseInt(element.Amount).toLocaleString("en-us") + `</td>
                <td> 0 </td>
                <td>`+ parseInt(element.Fi / 10).toLocaleString("en-us") + ` ت</td>
                <td>`+ parseInt(element.totalPrice / 10).toLocaleString("en-us") + ` ت</td>
                <td>`+ element.DescRecord + `</td>
                </tr>
                `);
            });

            if (!($('.modal.in').length)) {
                $('.modal-dialog').css({
                    left: 50,
                    top: 0
                });
            }
            $('#sentTosalesFactor').modal({
                backdrop: false,
                show: true
            });

            $('.modal-dialog').draggable({
                handle: ".modal-header"
            });

            $("#sentTosalesFactor").modal("show");
        },
        error: function (error) {

        }

    });
});

$("#editDeleteOrderItem").on("click", () => {
    swal({
        title: 'اخطار!',
        text: 'آیا می خواهید حذف کنید؟',
        icon: 'warning',
        buttons: true
    }).then(function (willAdd) {
        if (willAdd) {
            $.ajax({
                method: 'get',
                async: true,
                url: baseUrl + "/deleteOrderItem",
                data: {
                    _token: "{{ csrf_token() }}",
                    orderSn: $("#editDeleteOrderItem").val(),
                    hdsSn: $("#editOrderBtn").val()
                },
                success: function (respond) {
                    $("#editSalesOrdersItemsBody").empty();
                    respond.forEach((element, index) => {
                        let secondUnit = "ندارد";
                        if (element.secondUnit) {
                            secondUnit = element.secondUnit;
                        }
                        $("#editSalesOrdersItemsBody").append(`                         
                            <tr onclick="getEditItemInfo(this,`+ element.SnGood + `,` + element.SnOrderBYSS + `)">
                            <td>`+ (index + 1) + `</td>
                            <td>`+ element.GoodCde + `</td>
                            <td style="width:180px;">`+ element.GoodName + `</td>
                            <td>`+ element.DateOrder + `</td>
                            <td>`+ element.firstUnit + `</td>
                            <td>`+ secondUnit + `</td>
                            <td>`+ parseInt(element.PackAmount).toLocaleString("en-us") + `</td>
                            <td>0</td>
                            <td>`+ parseInt(element.Amount).toLocaleString("en-us") + `</td>
                            <td>`+ parseInt(element.Fi / 10).toLocaleString("en-us") + `</td>
                            <td>`+ parseInt(element.FiPack / 10).toLocaleString("en-us") + ` </td>
                            <td>`+ parseInt(element.totalPrice / 10).toLocaleString("en-us") + `</td>
                            <td>`+ element.DescRecord + `</td>
                            </tr>
                        `);
                    });
                },
                error: function (error) {

                }
            });
        }
    });
});

function deleteKalaPicture(goodSn, element) {
    $.get(baseUrl + '/getGoodPictureState', { goodSn: goodSn }, function (respond, status) {
        if (status == "success") {
            $("#mainPicEdit" + goodSn).attr('src', '');
            $(element).hide();
            if ($("#mainPicEdit")) {
                $("#mainPicEdit").attr('src', '');
            }
        }
    });
}

function getEditItemInfo(element, goodSn, snOrderBYS) {
    $("#editDeleteOrderItem").val(snOrderBYS);
    $("#editEditOrderItem").val(snOrderBYS);
    let stockId = $("#editSelectedStockKala").val();
    $("tr").removeClass('selected');
    $(element).addClass('selected');
    $.ajax({
        method: 'get',
        async: true,
        url: baseUrl + "/getSendItemInfo",
        data: {
            _token: "{{ csrf_token() }}",
            goodSn: goodSn,
            stockId: 23,
            customerSn: $("#editCustomerSn").val()
        },
        success: function (response) {
            $("#editStockExistance").text(parseInt(response[0][0].Amount).toLocaleString("en-us"));
            $("#editPrice").text(parseInt(response[1][0].Price3).toLocaleString("en-us"));
            if (response[2][0]) {
                $("#editPriceCustomer").text(parseInt(response[2][0].Fi).toLocaleString("en-us"));
            }
            $("#editLastPrice").text(parseInt(response[3][0].Fi).toLocaleString("en-us"));

            if (!isNaN(parseInt(response[0][0].Amount))) {
                $("#firstEditExistInStock").text(parseInt(response[0][0].Amount).toLocaleString("en-us"));
            } else {
                $("#firstEditExistInStock").text('ندارد');
            }
            if (!isNaN(parseInt(response[1][0].Price3))) {
                $("#firstEditPrice").text(parseInt(response[1][0].Price3 / 10).toLocaleString("en-us"));
            } else {
                $("#firstEditPrice").text('ندارد');
            }

            if (!isNaN(parseInt(response[2][0].Fi))) {
                $("#firstEditLastPriceCustomer").text(parseInt(response[2][0].Fi / 10).toLocaleString("en-us"));
            } else {
                $("#firstEditLastPriceCustomer").text('ندارد');
            }
            if (!isNaN(parseInt(response[3][0].Fi))) {
                $("#firstEditLastPrice").text(parseInt(response[3][0].Fi / 10).toLocaleString("en-us"));
            } else {
                $("#firstEditLastPrice").text('ندارد');
            }
        },
        error: function (error) {
            //alert("get item existance error found");
        }
    })
}

function getSendItemInfo(element, goodSn, isPayed) {
    let stockId = $("#sendSelectedStockKala").val();
    $("tr").removeClass('selected');
    $(element).addClass('selected');
    $.ajax({
        method: 'get',
        async: true,
        url: baseUrl + "/getSendItemInfo",
        data: {
            _token: "{{ csrf_token() }}",
            goodSn: goodSn,
            stockId: stockId,
            customerSn: $("#sendCustomerSn").val()
        },
        success: function (response) {

            $("#sendStockExistance").text(parseInt(response[0][0].Amount).toLocaleString("en-us"));
            $("#sendPrice").text(parseInt(response[1][0].Price3).toLocaleString("en-us"));
            if (response[2][0]) {
                $("#sendPriceCustomer").text(parseInt(response[2][0].Fi).toLocaleString("en-us"));
            }
            $("#sendLastPrice").text(parseInt(response[3][0].Fi).toLocaleString("en-us"));

        },
        error: function (error) {
            // alert("get item existance error found");
        }
    })
}
$("#searchItemForAddOrder").on("keyup", function () {
    let searchTerm = $("#searchItemForAddOrder").val();
    $.ajax({
        method: 'get',
        url: baseUrl + '/searchItemForAddToOrder',
        async: true,
        data: {
            _token: "{{@csrf}}",
            searchTerm: searchTerm
        },
        success: function (respond) {
            $("#addToOrderKalaCode").empty();
            $("#addToOrderKalaName").empty();
            $("#addToOrderAmount").empty();
            respond.forEach((element, index) => {
                $("#addToOrderKalaCode").append(`<option value="` + element.GoodCde + `">` + element.GoodCde + `</option>`);
                $("#addToOrderKalaName").append(`<option value="` + element.GoodSn + `">` + element.GoodName + `</option>`);
                $("#addToOrderAmount").append(`<option value="` + (index + 1) * respond[0].AmountUnit + `">` + (index + 1) + `  ` + respond[0].secondUnit + ` معادل ` + (index + 1) * respond[0].AmountUnit + ` ` + respond[0].firstUnit + `</option>`);
            });
        },
        error: function (error) {
            alert("server error on getting search result");
        }

    });
});

$("#addToOrderKalaName").on("change", () => {
    $.ajax({
        method: 'get',
        url: baseUrl + '/getGoodInfoForAddOrderItem',
        async: true,
        data: {
            _token: "{{@csrf}}",
            goodSn: $("#addToOrderKalaName").val(),
            customerSn: $("#editCustomerSn").val(),
            stockId: 23
        },
        success: function (response) {
            respond = response[0];
            $("#addToOrderAmount").empty();
            $("#addToOrderKalaCode").empty();
            if (respond[0].AmountUnit) {
                for (let index = 1; index <= 40; index++) {

                    $("#addToOrderAmount").append(`<option value="` + (index * respond[0].AmountUnit) + `">` + (index) + `  ` + respond[0].secondUnit + ` معادل ` + index * respond[0].AmountUnit + ` ` + respond[0].firstUnit + `</option>`);

                }
            } else {
                amountUnit = 1;
                for (let index = 1; index <= 40; index++) {

                    $("#addToOrderAmount").append(`<option value="` + (index) + `">` + (index) + `  ` + respond[0].firstUnit + ` معادل ` + index + ` ` + respond[0].firstUnit + `</option>`);

                }
            }

            if (response[1][0]) {
                $("#editExistance").text(parseInt(response[1][0].Amount).toLocaleString("en-us"));
            } else {
                $("#editExistance").text('ندارد');
            }
            if (response[2][0]) {
                $("#editPrice").text(parseInt(response[2][0].Price3 / 10).toLocaleString("en-us"));
            } else {
                $("#editPrice").text('ندارد');
            }

            if (response[3][0]) {
                $("#editLastSalsePriceToCustomer").text(parseInt(response[3][0].Fi / 10).toLocaleString("en-us"));
            } else {
                $("#editLastSalsePriceToCustomer").text('ندارد');
            }
            if (response[4][0]) {
                $("#editLastSalePrice").text(parseInt(response[4][0].Fi / 10).toLocaleString("en-us"));
            } else {
                $("#editLastSalePrice").text('ندارد');
            }
            $("#addToOrderAmount").change();
            $("#addToOrderKalaCode").append(`<option value="` + respond[0].GoodCde + `">` + respond[0].GoodCde + `</option>`);
        },
        error: function (error) {
            alert("get data serer side error kala info");
        }
    });
});

$("#editEditOrderItem").on("click", function () {

    $.ajax({
        method: "get",
        async: true,
        url: baseUrl + "/getOrderItemInfo",
        data: {
            _token: "{{@csrf}}",
            itemSn: $("#editEditOrderItem").val(),
            customerSn: $("#editCustomerSn").val()
        },
        success: function (response) {
            element = response[0];
            $("#editOrderKalaCode").empty();
            $("#editOrderKalaName").empty();
            $("#editOrderAmount").empty();
            $("#editOrderSn").val($("#editEditOrderItem").val());
            $("#editHdsSn").val(element.SnHDS);
            $("#editOrderKalaCode").append(`<option selected value="` + element.GoodCde + `">` + element.GoodCde + `</option>`);
            $("#editOrderKalaName").append(`<option selected value="` + element.GoodSn + `">` + element.GoodName + `</option>`);

            if (element.secondUnit) {
                $("#editOrderAmount").append(`<option selected value="` + parseInt(element.Amount) + `">` + parseInt(element.PackAmount) + `  ` + element.secondUnit + ` معادل ` + parseInt(element.Amount) + ` ` + element.firstUnit + `</option>`);
            } else {
                $("#editOrderAmount").append(`<option selected value="` + parseInt(element.Amount) + `">` + parseInt(element.PackAmount) + `  ` + element.firstUnit + ` معادل ` + parseInt(element.Amount) + ` ` + element.firstUnit + `</option>`);
            }

            if (element.AmountUnit) {
                for (let index = 1; index <= 40; index++) {
                    if (index != parseInt(element.AmountUnit)) {
                        $("#editOrderAmount").append(`<option value="` + (index * element.AmountUnit) + `">` + (index) + `  ` + element.secondUnit + ` معادل ` + index * element.AmountUnit
                            + ` ` + element.firstUnit + `</option>`);
                    } else {
                        continue;
                    }


                }

                if (!isNaN(parseInt(response[1][0].Amount))) {
                    $("#editEditExistance").text(parseInt(response[1][0].Amount).toLocaleString("en-us"));
                } else {
                    $("#editEditExistance").text('ندارد');
                }
                if (!isNaN(parseInt(response[2][0].Price3))) {
                    $("#editEditPrice").text(parseInt(response[2][0].Price3 / 10).toLocaleString("en-us"));
                } else {
                    $("#editEditPrice").text('ندارد');
                }

                if (!isNaN(parseInt(response[3][0].Fi))) {
                    $("#editEditLastSalsePriceToCustomer").text(parseInt(response[3][0].Fi / 10).toLocaleString("en-us"));
                } else {
                    $("#editEditLastSalsePriceToCustomer").text('ندارد');
                }
                if (!isNaN(parseInt(response[4][0].Fi))) {
                    $("#editEditLastSalePrice").text(parseInt(response[4][0].Fi / 10).toLocaleString("en-us"));
                } else {
                    $("#editEditLastSalePrice").text('ندارد');
                }



            } else {
                amountUnit = 1;
                for (let index = 1; index <= 40; index++) {
                    if (index != parseInt(element.Amount)) {
                        $("#editOrderAmount").append(`<option value="` + (index * amountUnit) + `">` + (index) + `  ` + element.firstUnit + ` معادل ` + index * amountUnit
                            + ` ` + element.firstUnit + `</option>`);
                    } else {
                        continue;
                    }


                }
            }

        },
        error: function (error) {
            alert("error in getttin server side data of get edit edit order Item");
        }

    })

    if (!($('.modal.in').length)) {
        $('.modal-dialog').css({
            top: 0,
            left: 0
        });
    }
    $('#editOrderItem').modal({
        backdrop: false,
        show: true
    });

    $('.modal-dialog').draggable({
        handle: ".modal-header"
    });
    $("#editOrderItem").modal("show");
});

$("#editOrderItemForm").on("submit", function (e) {

    $.ajax({
        url: $(this).attr('action'),
        type: $(this).attr('method'),
        dataType: 'json',
        data: $(this).serialize(),
        success: function (data) {
            //نمایش کالای سفارش داده شده
            $("#editSalesOrdersItemsBody").empty();

            data.forEach((element, index) => {
                let secondUnit = "ندارد";
                if (element.secondUnit) {
                    secondUnit = element.secondUnit;
                }
                $("#editSalesOrdersItemsBody").append(`
                            <tr onclick="getEditItemInfo(this,`+ element.SnGood + `,` + element.SnOrderBYSS + `)">
                            <td>`+ (index + 1) + `</td>
                            <td>`+ element.GoodCde + `</td>
                            <td style="width:180px">`+ element.GoodName + `</td>
                            <td>`+ element.DateOrder + `</td>
                            <td>`+ element.firstUnit + `</td>
                            <td>`+ secondUnit + `</td>
                            <td>`+ parseInt(element.PackAmount).toLocaleString("en-us") + `</td>
                            <td>0</td>
                            <td>`+ parseInt(element.Amount).toLocaleString("en-us") + `</td>
                            <td>`+ parseInt(element.Fi / 10).toLocaleString("en-us") + `</td>
                            <td>`+ parseInt(element.FiPack / 10).toLocaleString("en-us") + ` </td>
                            <td>`+ parseInt(element.totalPrice / 10).toLocaleString("en-us") + `</td>
                            <td>`+ element.DescRecord + `</td>
                            </tr>
                            `);
            });
            $("#editOrderItem").modal("hide");
        },
        error: function (xhr, err) {
            alert('Error');
        }
    });
    e.preventDefault();
});

$("#addToOrderForm").on("submit", function (e) {
    $.ajax({
        url: $(this).attr('action'),
        type: $(this).attr('method'),
        dataType: 'json',
        data: $(this).serialize(),
        success: function (response) {
            //نمایش کالای سفارش داده شده
            $("#editSalesOrdersItemsBody").empty();

            response.forEach((element, index) => {
                let secondUnit = "ندارد";
                if (element.secondUnit) {
                    secondUnit = element.secondUnit;
                }
                $("#editSalesOrdersItemsBody").append(`
                <tr onclick="getEditItemInfo(this,`+ element.SnGood + `,` + element.SnOrderBYSS + `)">
                <td>`+ (index + 1) + `</td>
                <td>`+ element.GoodCde + `</td>
                <td style="width:180px;"> `+ element.GoodName + `</td>
                <td>`+ element.DateOrder + `</td>
                <td>`+ element.firstUnit + `</td>
                <td>`+ secondUnit + `</td>
                <td>`+ parseInt(element.PackAmount).toLocaleString("en-us") + `</td>
                <td>0</td>
                <td>`+ parseInt(element.Amount).toLocaleString("en-us") + `</td>
                <td>`+ parseInt(element.Fi / 10).toLocaleString("en-us") + `</td>
                <td>`+ parseInt(element.FiPack / 10).toLocaleString("en-us") + ` </td>
                <td>`+ parseInt(element.totalPrice / 10).toLocaleString("en-us") + `</td>
                <td>`+ element.DescRecord + `</td>
                </tr>
                `);
            });
            $("#addOrderItem").modal("hide");
        },
        error: function (xhr, err) {
            alert('Error');
        }
    });
    e.preventDefault();
});

$("#addToOrderAmount").on("change", () => {

    if (isNaN(parseInt($("#editExistance").text()))) {
        $("#addToOrderAmount").prop("disabled", true);
        $("#editSaveBtn").prop("disabled", true);
    } else {
        if (parseInt($("#addToOrderAmount").val()) > parseInt($("#editExistance").text().replace(',', ''))) {

            $("#addToOrderAmount").prop("disabled", true);
            $("#editSaveBtn").prop("disabled", true);
        } else {

            $("#addToOrderAmount").prop("disabled", false);
            $("#editSaveBtn").prop("disabled", false);
        }
    }
});

$("#sefNewOrderRadio").change(function () {
    $.ajax({
        method: 'get',
        url: baseUrl + '/getAllNewOrders',
        async: true,
        success: function (response) {
            $("#orderListBody").empty();
            response[0].forEach((element, index) => {
                let payedClass = "";
                let adminName = "ندارد";
                let amOrPm = "2";
                if (element.OrderErsalTime == 1) {
                    amOrPm = "عصر";
                }
                if (element.adminName) {
                    adminName = element.adminName;
                }
                if (element.isPayed == 1) {
                    payedClass = "class='payedOnline'";
                }
                $("#orderListBody").append(`
    <tr `+ payedClass + ` onclick="getOrderDetail(this,` + element.SnOrder + `,` + element.isPayed + `,` + element.CustomerSn + `)">
    <td>`+ (index + 1) + `</td>
    <td style="width:70px;">`+ element.OrderNo + `</td>
    <td style="width:80px;">`+ element.OrderDate + `</td>
    <td style="width:180px; font-weight:bold;">`+ element.Name + `</td>
    <td>`+ adminName + `</td>
    <td style="font-weight:bold;">`+ parseInt(element.allPrice / 10).toLocaleString("en-us") + ` ت</td>
    <td style="color:red;">`+ parseInt((element.allPrice - element.payedMoney) / 10).toLocaleString("en-us") + ` ت</td>
    <td>`+ parseInt((element.payedMoney) / 10).toLocaleString("en-us") + ` ت</td>
    <td>`+ element.OrderDesc + `</td>
    <td style="width:77px;">`+ element.SaveTimeOrder + `</td>
    <td>`+ amOrPm + `</td></tr>`);
            });
            $("#sendTotalMoney").text(parseInt(response[1][0].sumAllMoney / 10).toLocaleString("en-us") + ' ت');
            $("#sendRemainedTotalMoney").text(parseInt((response[1][0].sumAllMoney - response[2][0].payedMoney) / 10).toLocaleString("en-us") + ' ت');
            $("#sendAllPayedMoney").text(parseInt((response[2][0].payedMoney) / 10).toLocaleString("en-us") + ' ت');
        },
        error: function (error) {
            alert("server side data getting errors");
        }
    });
});


$("#sefSentOrderRadio").change(function () {
    $.ajax({
        method: 'get',
        url: baseUrl + '/getAllSentOrders',
        async: true,
        success: function (response) {
            $("#orderListBody").empty();
            response[0].forEach((element, index) => {
                let payedClass = "";
                let adminName = "ندارد";
                let amOrPm = "2";
                if (element.OrderErsalTime == 1) {
                    amOrPm = "عصر";
                }
                if (element.adminName) {
                    adminName = element.adminName;
                }
                if (element.isPayed == 1) {
                    payedClass = "class='payedOnline'";
                }
                $("#orderListBody").append(`
        <tr `+ payedClass + ` onclick="getOrderDetail(this,` + element.SnOrder + `,` + element.isPayed + `,` + element.CustomerSn + `)">
        <td>`+ (index + 1) + `</td>
        <td style="width:70px">`+ element.OrderNo + `</td>
        <td style="width:80px">`+ element.OrderDate + `</td>
        <td style="width:180px; font-weight:bold;">`+ element.Name + `</td>
        <td>`+ adminName + `</td>
        <td style="font-weight:bold;">`+ parseInt(element.allPrice / 10).toLocaleString("en-us") + ` ت</td>
        <td style="color:red">`+ parseInt((element.allPrice - element.payedMoney) / 10).toLocaleString("en-us") + ` ت</td>
        <td>`+ parseInt((element.payedMoney) / 10).toLocaleString("en-us") + ` ت</td>
        <td>`+ element.OrderDesc + `</td>
        <td style="width:77px">`+ element.SaveTimeOrder + `</td>
        <td>`+ amOrPm + `</td></tr>`);
            });
            $("#sendTotalMoney").text(parseInt(response[1][0].sumAllMoney / 10).toLocaleString("en-us") + ' ت');
            $("#sendRemainedTotalMoney").text(parseInt((response[1][0].sumAllMoney - response[2][0].payedMoney) / 10).toLocaleString("en-us") + ' ت');
            $("#sendAllPayedMoney").text(parseInt((response[2][0].payedMoney) / 10).toLocaleString("en-us") + ' ت');
        },
        error: function (error) {
            alert("server side data getting errors");
        }
    });
});

$("#sefTodayOrderRadio").change(function () {
    $.ajax({
        method: 'get',
        url: baseUrl + '/getAllTodayOrders',
        async: true,
        success: function (response) {
            $("#orderListBody").empty();
            response[0].forEach((element, index) => {
                let payedClass = "";
                let adminName = "ندارد";
                let amOrPm = "2";
                if (element.OrderErsalTime == 1) {
                    amOrPm = "عصر";
                }
                if (element.adminName) {
                    adminName = element.adminName;
                }
                if (element.isPayed == 1) {
                    payedClass = "class='payedOnline'";
                }
                $("#orderListBody").append(`
            <tr `+ payedClass + ` onclick="getOrderDetail(this,` + element.SnOrder + `,` + element.isPayed + `,` + element.CustomerSn + `)">
            <td>`+ (index + 1) + `</td>
            <td style="width:70px;">`+ element.OrderNo + `</td>
            <td style="width:80px;">`+ element.OrderDate + `</td>
            <td style="width:180px; font-weight:bold;">`+ element.Name + `</td>
            <td>`+ adminName + `</td>
            <td  style="font-weight:bold;">`+ parseInt(element.allPrice / 10).toLocaleString("en-us") + ` ت</td>
            <td  style="color:red">`+ parseInt((element.allPrice - element.payedMoney) / 10).toLocaleString("en-us") + ` ت</td>
            <td>`+ parseInt((element.payedMoney) / 10).toLocaleString("en-us") + ` ت</td>
            <td>`+ element.OrderDesc + `</td>
            <td style="width:77px;">`+ element.SaveTimeOrder + `</td>
            <td>`+ amOrPm + `</td></tr>`);
            });
            $("#sendTotalMoney").text(parseInt(response[1][0].sumAllMoney / 10).toLocaleString("en-us") + ' ت');
            $("#sendRemainedTotalMoney").text(parseInt((response[1][0].sumAllMoney - response[2][0].payedMoney) / 10).toLocaleString("en-us") + ' ت');
            $("#sendAllPayedMoney").text(parseInt((response[2][0].payedMoney) / 10).toLocaleString("en-us") + ' ت');
        },
        error: function (error) {
            alert("server side data getting errors");
        }
    });
});

$("#sefRemainOrderRadio").change(function () {
    $.ajax({
        method: 'get',
        url: baseUrl + '/getAllRemainOrders',
        async: true,
        success: function (response) {
            console.log(response);
            $("#orderListBody").empty();
            response[0].forEach((element, index) => {
                let payedClass = "";
                let adminName = "ندارد";
                let amOrPm = "2";
                if (element.OrderErsalTime == 1) {
                    amOrPm = "عصر";
                }
                if (element.adminName) {
                    adminName = element.adminName;
                }
                if (element.isPayed == 1) {
                    payedClass = "class='payedOnline'";
                }
                $("#orderListBody").append(`
        <tr `+ payedClass + ` onclick="getOrderDetail(this,` + element.SnOrder + `,` + element.isPayed + `,` + element.CustomerSn + `)">
        <td>`+ (index + 1) + `</td>
        <td style="width:70px;">`+ element.OrderNo + `</td>
        <td style="width:80px;">`+ element.OrderDate + `</td>
        <td style="width:180px; font-weight:bold;">`+ element.Name + `</td>
        
        <td>`+ adminName + `</td>
        <td style="font-wieght:bold;">`+ parseInt(element.allPrice / 10).toLocaleString("en-us") + ` ت</td>
    
        <td style="color:red;">`+ parseInt((element.allPrice - element.payedMoney) / 10).toLocaleString("en-us") + ` ت</td>
        <td>`+ parseInt((element.payedMoney) / 10).toLocaleString("en-us") + ` ت</td>
                    
        <td>`+ element.OrderDesc + `</td>
        <td style="width:77px;">`+ element.SaveTimeOrder + `</td>
        <td>`+ amOrPm + `</td></tr>`);
            });
            $("#sendTotalMoney").text(parseInt(response[1][0].sumAllMoney / 10).toLocaleString("en-us") + ' ت');
            $("#sendRemainedTotalMoney").text(parseInt((response[1][0].sumAllMoney - response[2][0].payedMoney) / 10).toLocaleString("en-us") + ' ت');
            $("#sendAllPayedMoney").text(parseInt((response[2][0].payedMoney) / 10).toLocaleString("en-us") + ' ت');
        },
        error: function (error) {
            alert("server side data getting errors");
        }
    });
});
function addItemToOrder() {
    $("#addOrderItem").modal("show");
}

$("#sefTarafHisabName").on("keyup", function () {
    let orderType = 0;
    if ($("#sefRemainOrderRadio").is(':checked')) {

        orderType = 1
    }
    $.ajax({
        method: 'get',
        url: baseUrl + '/getOrderByCustName',
        async: true,
        data: {
            _token: "{{@csrf}}",
            fromDate: $("#sefFirstDate").val(),
            orderType: orderType,
            toDate: $("#sefSecondDate").val(),
            name: $("#sefTarafHisabName").val()
        },
        success: function (response) {
            console.log(response[0]);
            $("#orderListBody").empty();
            response[0].forEach((element, index) => {
                let payedClass = "";
                let adminName = "ندارد";
                let amOrPm = "2";
                if (element.OrderErsalTime == 1) {
                    amOrPm = "عصر";
                }
                if (element.adminName) {
                    adminName = element.adminName;
                }
                if (element.isPayed == 1) {
                    payedClass = "class='payedOnline'";
                }
                $("#orderListBody").append(`
                <tr `+ payedClass + ` onclick="getOrderDetail(this,` + element.SnOrder + `,` + element.isPayed + `,` + element.CustomerSn + `)">
                <td>`+ (index + 1) + `</td>
                <td>`+ element.OrderNo + `</td>
                
                <td>`+ element.OrderDate + `</td>
                <td>`+ element.Name + `</td>
                
                <td>`+ adminName + `</td>
                <td>`+ parseInt(element.allPrice / 10).toLocaleString("en-us") + ` ت</td>
            
                <td>`+ parseInt((element.allPrice - element.payedMoney) / 10).toLocaleString("en-us") + ` ت</td>
                <td>`+ parseInt((element.payedMoney) / 10).toLocaleString("en-us") + ` ت</td>

                <td>`+ element.OrderDesc + `</td>
                <td>`+ element.SaveTimeOrder + `</td>
                <td>`+ amOrPm + `</td></tr>`);
            });
            $("#sendTotalMoney").text(parseInt(response[1][0].sumAllMoney / 10).toLocaleString("en-us") + ' ت');
            $("#sendRemainedTotalMoney").text(parseInt((response[1][0].sumAllMoney - response[2][0].payedMoney) / 10).toLocaleString("en-us") + ' ت');
            $("#sendAllPayedMoney").text(parseInt((response[2][0].payedMoney) / 10).toLocaleString("en-us") + ' ت');
        },
        error: function (error) {

        }
    });
});

function showOnlinePaymentInfo() {

    $.ajax({
        method: 'get',
        url: baseUrl + "/getPaymentInfo",
        async: true,
        data: {
            _token: "{{@csrf}}",
            InVoiceNumber: $("#editInVoiceNumber").val()
        },
        success: function (respond) {
            $("#editOnlinePaymentBody").empty();
            console.log(respond);
            $("#onlinePaymentModalInfo").modal("show");
            respond.forEach((element, index) => {
                let isSuccess = "خیر";
                if (element.IsSuccess == 1) {
                    isSuccess = "بله";
                }
                $("#editOnlinePaymentBody").append(
                    `<tr>
                                    <td>`+ (index + 1) + `</td>
                                    <td>`+ element.ReferenceNumber + `</td>
                                    <td>`+ element.TraceNumber + `</td>
                                    <td>`+ moment(element.TransactionDate, 'YYYY/M/D HH:mm:ss').locale('fa').format('YYYY/M/D hh:mm:ss') + `</td>
                                    <td>`+ element.TransactionReferenceID + `</td>
                                    <td>`+ parseInt((element.Amount) / 10).toLocaleString("en-us") + ` ت</td>
                                    <td>`+ element.TrxMaskedCardNumber + `</td>
                                    <td>`+ isSuccess + `</td>
                                    <td>`+ element.Message + `</td></tr>`);
            });
        },
        error: function (error) {

        }
    });
}

$("#sefTarafHisabCode").on("keyup", function () {

    let orderType = 0;
    if ($("#sefRemainOrderRadio").is(':checked')) {

        orderType = 1
    }
    $.ajax({
        method: 'get',
        url: baseUrl + '/getOrdersByCustCode',
        async: true,
        data: {
            _token: "{{@csrf}}",
            fromDate: $("#sefFirstDate").val(),
            orderType: orderType,
            toDate: $("#sefSecondDate").val(),
            code: $("#sefTarafHisabCode").val()
        },
        success: function (response) {
            console.log(response[0]);
            $("#orderListBody").empty();
            response[0].forEach((element, index) => {
                let payedClass = "";
                let adminName = "ندارد";
                let amOrPm = "2";
                if (element.OrderErsalTime == 1) {
                    amOrPm = "عصر";
                }
                if (element.adminName) {
                    adminName = element.adminName;
                }
                if (element.isPayed == 1) {
                    payedClass = "class='payedOnline'";
                }
                $("#orderListBody").append(`
                    <tr `+ payedClass + ` onclick="getOrderDetail(this,` + element.SnOrder + `,` + element.isPayed + `,` + element.CustomerSn + `)">
                    <td>`+ (index + 1) + `</td>
                    <td>`+ element.OrderNo + `</td>
                    
                    <td>`+ element.OrderDate + `</td>
                    <td>`+ element.Name + `</td>
                    
                    <td>`+ adminName + `</td>
                    <td>`+ parseInt(element.allPrice / 10).toLocaleString("en-us") + ` ت</td>
                
                    <td>`+ parseInt((element.allPrice - element.payedMoney) / 10).toLocaleString("en-us") + ` ت</td>
                    <td>`+ parseInt((element.payedMoney) / 10).toLocaleString("en-us") + ` ت</td>

                    <td>`+ element.OrderDesc + `</td>
                    <td>`+ element.SaveTimeOrder + `</td>
                    <td>`+ amOrPm + `</td></tr>`);
            });

            $("#sendTotalMoney").text(parseInt(response[1][0].sumAllMoney / 10).toLocaleString("en-us") + ' ت');
            $("#sendRemainedTotalMoney").text(parseInt((response[1][0].sumAllMoney - response[2][0].payedMoney) / 10).toLocaleString("en-us") + ' ت');
            $("#sendAllPayedMoney").text(parseInt((response[2][0].payedMoney) / 10).toLocaleString("en-us") + ' ت');
        },
        error: function (error) {

        }
    });
});

$("#sefPoshtibanName").on("keyup", () => {
    let orderType = 0;
    if ($("#sefRemainOrderRadio").is(':checked')) {

        orderType = 1
    }
    $.ajax({
        method: 'get',
        url: baseUrl + '/getOrdersByPoshtibanName',
        async: true,
        data: {
            _token: "{{@csrf}}",
            fromDate: $("#sefFirstDate").val(),
            orderType: orderType,
            toDate: $("#sefSecondDate").val(),
            name: $("#sefPoshtibanName").val()
        },
        success: function (response) {

            console.log(response[0]);
            $("#orderListBody").empty();
            response[0].forEach((element, index) => {
                let payedClass = "";
                let adminName = "ندارد";
                let amOrPm = "2";
                if (element.OrderErsalTime == 1) {
                    amOrPm = "عصر";
                }
                if (element.adminName) {
                    adminName = element.adminName;
                }
                if (element.isPayed == 1) {
                    payedClass = "class='payedOnline'";
                }
                $("#orderListBody").append(`
                    <tr `+ payedClass + ` onclick="getOrderDetail(this,` + element.SnOrder + `,` + element.isPayed + `,` + element.CustomerSn + `)">
                    <td>`+ (index + 1) + `</td>
                    <td>`+ element.OrderNo + `</td>
                    
                    <td>`+ element.OrderDate + `</td>
                    <td>`+ element.Name + `</td>
                    
                    <td>`+ adminName + `</td>
                    <td>`+ parseInt(element.allPrice / 10).toLocaleString("en-us") + ` ت</td>
                
                    <td>`+ parseInt((element.allPrice - element.payedMoney) / 10).toLocaleString("en-us") + ` ت</td>
                    <td>`+ parseInt((element.payedMoney) / 10).toLocaleString("en-us") + ` ت</td>
                                        
                    <td>`+ element.OrderDesc + `</td>
                    <td>`+ element.SaveTimeOrder + `</td>
                    <td>`+ amOrPm + `</td></tr>`);
            });
            $("#sendTotalMoney").text(parseInt(response[1][0].sumAllMoney / 10).toLocaleString("en-us") + ' ت');
            $("#sendRemainedTotalMoney").text(parseInt((response[1][0].sumAllMoney - response[2][0].payedMoney) / 10).toLocaleString("en-us") + ' ت');
            $("#sendAllPayedMoney").text(parseInt((response[2][0].payedMoney) / 10).toLocaleString("en-us") + ' ت');

        },
        error: function (error) {

        }
    });
});

$("#sendSabtFactorBtn").on("click", () => {
    $.ajax({
        method: "get",
        url: baseUrl + '/checkOrderExistance',
        async: true,
        data: {
            _token: "{{@csrf}}",
            hds: $("#sendFatorHDS").val()
        },
        success: function (respond) {
            if (respond[0].length > 0) {
                //در صورتیکه کالاهای بدون موجودی وجود داشته باشد.
                $("#notExistGoodsBody").empty();
                if (respond[1] < 1) {
                    respond[0].forEach((element, index) => {
                        $("#notExistGoodsBody").append(`<tr><td>` + (index + 1) + `</td><td>` + element.GoodName + `</td><td>` + element.GoodCde + `</td><td>` + element.Amount + `</td></tr>`);
                    });
                } else {
                    $("#notExistGoodsBody").append(`<div><p>تمامی کالاهای سفارش موجودی ندارند.</p></div>`);
                }

                $("#notExistGoodsModal").modal({
                    backdrop: 'static',
                    keyboard: false
                })
                    .on('click', '#sendToFactor', function (e) {
                        $("#sendToFactorList").trigger('submit');
                    });
                $("#cancel").on('click', function (e) {
                    e.preventDefault();
                    $("#notExistGoodsModal").modal.model('hide');
                });
            } else {
                $("#sendToFactorList").trigger('submit');
            }
        },
        error: function (error) {

        }
    });
});


$("#setPaysStuff").on("click", () => {
    $.ajax({
        method: 'get',
        url: baseUrl + "/setFactorSessions",
        async: true,
        data: {
            _token: "{{ csrf_token() }}",
            recivedTime: "no Time",
            takhfif: 0,
            receviedAddress: "no address",
            allMoneyToSend: $("#allMoneyToSend").val(),
            isSent: 1,
            orderSn: $("#snOrder").val()
        },
        success: function (respond) {
        },
        error: function (error) {
            alert("some error exist");
        }
    });
});


$("#addNazarSanjiForm").on("submit", function (e) {

    $.ajax({
        method: $(this).attr('method'),
        url: $(this).attr('action'),
        data: $(this).serialize(),
        success: function (data) {
            $("#insetQuestion").modal("hide");
            $("#nazaranjicontainer").empty();
            data.forEach((element) => {
                $("#nazaranjicontainer").append(`
                <fieldset class="fieldsetBorder rounded mb-3">
                <legend  class="float-none w-auto forLegend"> `+ element.Name + ` </legend>	
                <div class="idea-container">
                  <div class="idea-item" id="listQuestionBtn">  
                      1- `+ element.question1 + `
                  </div>
                  <div class="idea-item" id="listQuestionBtn1"> 
                    2- `+ element.question2 + `
                  </div>
                  <div class="idea-item" id="listQuestionBtn2">  
                    3- `+ element.question3 + `
                  </div>
                </div>
              </fieldset>`);
            });
        },
        error: function (error) {
            console.log("error in submitting data");
        }
    });
    e.preventDefault();
})

//for slicknav
$('#cssmenu > ul > li ul').each(function (index, e) {
    var count = `<i class="fa-solid fa-caret-down text-white"></i>`;
    var content = '<span class="cnt">' + count + '</span>';
    $(e).closest('li').children('a').append(content);
});

$('#cssmenu ul ul li:odd').addClass('odd');

$('#cssmenu ul ul li:even').addClass('even');

$('#cssmenu > ul > li > a').click(function () {

    $('#cssmenu li').removeClass('active');
    $(this).closest('li').addClass('active');
    var checkElement = $(this).next();

    if ((checkElement.is('ul')) && (checkElement.is(':visible'))) {

        $(this).closest('li').removeClass('active');

        checkElement.slideUp('normal');

    }
    if ((checkElement.is('ul')) && (!checkElement.is(':visible'))) {

        $('#cssmenu ul ul:visible').slideUp('normal');

        checkElement.slideDown('normal');
    }
    if ($(this).closest('li').find('ul').children().length == 0) {
        return true;
    } else {
        return false;
    }


    //end of slicknav

    var row;
    function start() {
        row = event.target;
    }

    function dragover() {
        var e = event;
        e.preventDefault();
        let children = Array.from(e.target.parentNode.parentNode.children);
        if (children.indexOf(e.target.parentNode) > children.indexOf(row))
            e.target.parentNode.after(row);
        else
            e.target.parentNode.before(row);
    }
    // $.ajax({
    // method: 'get',
    // url: baseUrl + "/getMainGroups",
    // async: true,
    // success: function(arrayed_result) {
    //     $('#mainGroupForKalaListSearch').empty();
    //     $('#mainGroupForKalaListSearch').append(`<option value="0">همه</option>`);

    //     for (var i = 0; i <= arrayed_result.length - 1; i++) {
    //         $('#mainGroupForKalaListSearch').append(`<option value="` + arrayed_result[i].id + `">` + arrayed_result[i].title + `</option>`);
    //     }
    // },
    // error: function(data) {
    // }
    // });

});



let p = new persianDate();
$("#favDate").persianDatepicker({
    cellWidth: 32,
    cellHeight: 22,
    fontSize: 14,
    formatDate: "YYYY/0M/0D",
    startDate: p.now().addDay(1).toString("YYYY/MM/DD"),
    endDate: "1440/5/5",
    onShow: function () {
        $('#favDate').val('');
    },
    onSelect: () => {
        if ($("#favDate").val().length > 0) {
            $("#DAY1M").prop("checked", false);
            $("#DAY1A").prop("checked", false);
            $("#DAY2M").prop("checked", false);
            $("#DAY2A").prop("checked", false);
            $("#delkhah").prop("checked", true);
            var pd = new persianDate();
            var value = pd.parse($("#favDate").val());
            var jdf = new jDateFunctions("Y-M-d");

            $('#delkhah').val('1' + ',' + jdf.getGDate(value)._toString("YYYY-MM-DD 12:00:00"));
        }
    }
});

$("#payFirstDate").persianDatepicker({
    cellWidth: 32,
    cellHeight: 22,
    fontSize: 14,
    formatDate: "YYYY/0M/0D",
    onShow: function () {
        $('#payFirstDate').val(p.now().toString("YYYY/MM/DD"));
    }
});
$("#paySecondDate").persianDatepicker({
    cellWidth: 32,
    cellHeight: 22,
    fontSize: 14,
    formatDate: "YYYY/0M/0D",
    onShow: function () {
        $('#paySecondDate').val(p.now().toString("YYYY/MM/DD"));
    }
});
$("#submitpayForm").on("click", function () {

    let name = $("#payTarafHisabName").val();

    let payState = 0;

    if ($("#sefRemainPayRadio").is(':checked') && $("#sefSentPayRadio").is(':checked')) {
        payState = 2;
    }

    if ($("#sefRemainPayRadio").is(':checked') && !($("#sefSentPayRadio").is(':checked'))) {
        payState = 0;
    }

    if (!($("#sefRemainPayRadio").is(':checked')) && $("#sefSentPayRadio").is(':checked')) {
        payState = 1;
    }

    let fromDate = $("#payFirstDate").val();

    let toDate = $("#paySecondDate").val();

    let pCode = $("#payTarafHisabCode").val();

    $.ajax({
        method: 'get',
        url: baseUrl + "/getPayedOnline",
        async: true,
        data: {
            _token: "{{ csrf_token() }}",
            payState: payState,
            fromDate: fromDate,
            toDate: toDate,
            PCode: pCode,
            name: name
        },
        success: function (response) {
            $("#paymentListBody").empty();
            response.forEach((element, index) => {
                payedClass = "";
                isSent = "خیر";
                if (element.isSent == 1) {
                    payedClass = "payedOnline";
                    isSent = "بله";
                }

                $("#paymentListBody").append(`
                    <tr class="`+ payedClass + `" onclick="getPayDetail(this,` + element.id + `,` + element.PSN + `,` + element.isSent + `)">
                    <td>`+ (index + 1) + `</td>
                    <td>`+ element.FactNo + `</td>
                    <td style="width:80px;">`+ element.payedDate + `</td>
                    <td style="width:180px; font-weight:bold;">`+ element.Name + `</td>
                    <td  style="font-weight:bold;">`+ parseInt(element.payedMoney / 10).toLocaleString("en-us") + ` ت</td>
                    <td style="width:77px;">`+ element.TimeStamp + `</td>
                    <td>`+ isSent + `</td>
                </tr>`);
            })
        },
        error: function (error) {
            alert("error in getting data");
        }
    });
});

$("#sefFirstDate").persianDatepicker({
    cellWidth: 32,
    cellHeight: 22,
    fontSize: 14,
    formatDate: "YYYY/0M/0D",
    onShow: function () {
        $('#sefFirstDate').val(p.now().toString("YYYY/MM/DD"));
    },
    onSelect: () => {

        let orderType = 0;
        if ($("#sefRemainOrderRadio").is(':checked')) {

            orderType = 1
        }

        if ($("#sefSentOrderRadio").is(':checked')) {

            orderType = 2
        }
        $.ajax({
            method: 'get',
            url: baseUrl + '/getOrderFromDate',
            async: true,
            data: {
                _token: "{{@csrf}}",
                fromDate: $("#sefFirstDate").val(),
                orderType: orderType
            },
            success: function (response) {
                $("#orderListBody").empty();
                response[0].forEach((element, index) => {
                    let payedClass = "";
                    let adminName = "ندارد";
                    let amOrPm = "2";
                    if (element.OrderErsalTime == 1) {
                        amOrPm = "عصر";
                    }
                    if (element.adminName) {
                        adminName = element.adminName;
                    }
                    if (element.isPayed == 1) {
                        payedClass = "class='payedOnline'";
                    }
                    $("#orderListBody").append(`
                        <tr `+ payedClass + ` onclick="getOrderDetail(this,` + element.SnOrder + `,` + element.isPayed + `,` + element.CustomerSn + `)">
                        <td>`+ (index + 1) + `</td>
                        <td style="width:70px">`+ element.OrderNo + `</td>
                        
                        <td style="width:80px">`+ element.OrderDate + `</td>
                        <td style="width:180px">`+ element.Name + `</td>
                        
                        <td>`+ adminName + `</td>
                        <td>`+ parseInt(element.allPrice / 10).toLocaleString("en-us") + ` ت</td>
                    
                        <td>`+ parseInt((element.allPrice - element.payedMoney) / 10).toLocaleString("en-us") + ` ت</td>
                        <td>`+ parseInt((element.payedMoney) / 10).toLocaleString("en-us") + ` ت</td>
                    
                        <td>`+ element.OrderDesc + `</td>
                        <td style="width:180px">`+ element.SaveTimeOrder + `</td>
                        <td>`+ amOrPm + `</td></tr>`);
                });
                $("#sendTotalMoney").text(parseInt(response[1][0].sumAllMoney / 10).toLocaleString("en-us") + ' ت');
                $("#sendRemainedTotalMoney").text(parseInt((response[1][0].sumAllMoney - response[2][0].payedMoney) / 10).toLocaleString("en-us") + ' ت');
                $("#sendAllPayedMoney").text(parseInt((response[2][0].payedMoney) / 10).toLocaleString("en-us") + ' ت');
            },
            error: function (error) {

            }
        });
    }
});

$("#sendOrderDate").persianDatepicker({
    cellWidth: 32,
    cellHeight: 22,
    fontSize: 14,
    formatDate: "YYYY/0M/0D",
    onShow: function () {
        $('#sefSecondDate').val(p.now().toString("YYYY/MM/DD"));
    },
    onSelect: () => {

    }
});

$("#sefSecondDate").persianDatepicker({
    cellWidth: 32,
    cellHeight: 22,
    fontSize: 14,
    formatDate: "YYYY/0M/0D",
    onShow: function () {
        $('#sefSecondDate').val(p.now().toString("YYYY/MM/DD"));
    },
    onSelect: () => {
        let orderType = 0;
        if ($("#sefRemainOrderRadio").is(':checked')) {
            orderType = 1
        }

        if ($("#sefSentOrderRadio").is(':checked')) {

            orderType = 2
        }

        $.ajax({
            method: 'get',
            url: baseUrl + '/getOrderToDate',
            async: true,
            data: {
                _token: "{{@csrf}}",
                fromDate: $("#sefFirstDate").val(),
                toDate: $("#sefSecondDate").val(),
                orderType: orderType
            },
            success: function (response) {
                console.log(response);
                $("#orderListBody").empty();
                response[0].forEach((element, index) => {
                    let payedClass = "";
                    let adminName = "ندارد";
                    let amOrPm = "2";
                    if (element.OrderErsalTime == 1) {
                        amOrPm = "عصر";
                    }
                    if (element.adminName) {
                        adminName = element.adminName;
                    }
                    if (element.isPayed == 1) {
                        payedClass = "class='payedOnline'";
                    }
                    $("#orderListBody").append(`
                    <tr `+ payedClass + ` onclick="getOrderDetail(this,` + element.SnOrder + `,` + element.isPayed + `,` + element.CustomerSn + `)">
                    <td>`+ (index + 1) + `</td>
                    <td style="wdith:70px">`+ element.OrderNo + `</td>
                    
                    <td style="wdith:80px">`+ element.OrderDate + `</td>
                    <td style="wdith:180px">`+ element.Name + `</td>
                    
                    <td>`+ adminName + `</td>
                    <td>`+ parseInt(element.allPrice / 10).toLocaleString("en-us") + ` ت</td>
                
                    <td>`+ parseInt((element.allPrice - element.payedMoney) / 10).toLocaleString("en-us") + ` ت</td>
                    <td>`+ parseInt((element.payedMoney) / 10).toLocaleString("en-us") + ` ت</td>
                    
                    <td>`+ element.OrderDesc + `</td>
                    <td style="wdith:77px">`+ element.SaveTimeOrder + `</td>
                    <td>`+ amOrPm + `</td></tr>`);
                });
                $("#sendTotalMoney").text(parseInt(response[1][0].sumAllMoney / 10).toLocaleString("en-us") + ' ت');
                $("#sendRemainedTotalMoney").text(parseInt((response[1][0].sumAllMoney - response[2][0].payedMoney) / 10).toLocaleString("en-us") + ' ت');
                $("#sendAllPayedMoney").text(parseInt((response[2][0].payedMoney) / 10).toLocaleString("en-us") + ' ت');
            },
            error: function (error) {

            }
        });
    }
});





$(document).ready(function () {
    var keyCodes = [61, 107, 173, 109, 187, 189];

    $(document).keydown(function (event) {
        if (event.ctrlKey == true && (keyCodes.indexOf(event.which) != -1)) {
            alert('حالت زوم کردن غیر فعال است');
            event.preventDefault();
        }
    });

    $(window).bind('mousewheel DOMMouseScroll', function (event) {
        if (event.ctrlKey == true) {
            alert('حالت زوم کردن غیر فعال است');
            event.preventDefault();
        }
    });
});


// modal for idea result 
function showAnswers(nazarId, qNumber) {
    $.ajax({
        method: 'get',
        url: baseUrl + '/getQAnswers',
        async: true,
        data: {
            _token: "{{@csrf}}",
            nazarId: nazarId,
            question: qNumber
        },
        success: function (respond) {
            $("#nazarListBody").empty();
            respond.forEach((element, index) => {
                $("#nazarListBody").append(`<tr>
                <td>`+ (index + 1) + `</td>
                <td>`+ element.Name + `</td>
                <td>`+ element.answer + `</td>
                <td>`+ moment(element.TimeStamp, 'YYYY/M/D HH:mm:ss').locale('fa').format('YYYY/M/D') + `</td>
                <td> <i class="fa fa-trash" style="color:red;"></i> </td>
            </tr>`);
            })
            if (!($('.modal.in').length)) {
                $('.modal-dialog').css({
                    top: 0,
                    left: 0
                });
            }
            $('#listQuestionModal').modal({
                backdrop: false,
                show: true
            });

            $('.modal-dialog').draggable({
                handle: ".modal-header"
            });
            $("#listQuestionModal").modal("show");
        },
        error: function (error) {
        }
    });
}
// modal for idea result 
$("#listQuestionBtn").on("click", () => {

    if (!($('.modal.in').length)) {
        $('.modal-dialog').css({
            top: 0,
            left: 0
        });
    }
    $('#listQuestionModal').modal({
        backdrop: false,
        show: true
    });

    $('.modal-dialog').draggable({
        handle: ".modal-header"
    });
    $("#listQuestionModal").modal("show");
})


// modal for inseting question 
$("#insetQuestionBtn").on("click", () => {

    if (!($('.modal.in').length)) {
        $('.modal-dialog').css({
            top: 0,
            left: 0
        });
    }
    $('#insetQuestion').modal({
        backdrop: false,
        show: true
    });

    $('.modal-dialog').draggable({
        handle: ".modal-header"
    });
    $("#insetQuestion").modal("show");
})



function editNazar(element) {
    var radioValue = $('input[name="nazarNameRadio"]:checked').val();
    $("#editQuestionBtn").prop("disabled", false);
    $("#deletQuestionBtn").prop("disabled", false);
    $("#nazarIdinputVal").val = radioValue;

}

$("#editQuestionBtn").on("click", () => {
    var radioValue = $('input[name="nazarNameRadio"]:checked').val();
    $("#editQuestionBtn").val(radioValue);

    $.ajax({
        method: 'get',
        url: baseUrl + '/editNazar',
        data: {
            _token: "{{ @csrf }}",
            nazarId: radioValue,
        },
        async: true,
        success: function (questions) {

            $("#nazarName1").val(questions[0].Name);
            $("#cont1").val(questions[0].question1);
            $("#cont2").val(questions[0].question2);
            $("#cont3").val(questions[0].question3);
            $("#nazarId").val(radioValue);

            if (!($('.modal.in').length)) {
                $('.modal-dialog').css({
                    top: 0,
                    left: 0
                });
            }
            $('#editNazarModal').modal({
                backdrop: false,
                show: true
            });

            $('.modal-dialog').draggable({
                handle: ".modal-header"
            });

            $("#editNazarModal").modal("show");

        },

        error: function (error) { },
    });
})


$("#updateQuestion").on("submit", function (e) {

    $.ajax({
        method: $(this).attr('method'),
        url: $(this).attr('action'),
        data: $(this).serialize(),
        success: function (data) {
            $("#nazaranjicontainer").empty();
            data.forEach((element, index) => {
                $("#nazaranjicontainer").append(`
				 <fieldset class="fieldsetBorder rounded mb-3">
                    <legend  class="float-none w-auto forLegend">`+ element.Name + ` </legend>	
					     <div class="form-check">
						  <input class="form-check-input nazarIdRadio p-2" onclick="editNazar(this)" type="radio" name="nazarNameRadio" value="`+ element.nazarId + `" id="">
						</div>
                    <div class="idea-container">
                      <button class="idea-item listQuestionBtn" onclick="showAnswers(`+ element.nazarId + `,` + 1 + `)">
                          `+ element.question1 + `
                      </button>
                      <button class="idea-item listQuestionBtn" onclick="showAnswers(`+ element.nazarId + `,` + 2 + `)">
                          `+ element.question2 + `
                      </button>
                      <button class="idea-item listQuestionBtn" onclick="showAnswers(`+ element.nazarId + `,` + 3 + `)">
                           `+ element.question3 + `
                      </button>
                    </div>
                  </fieldset>
			`)
            });
        },
        error: function (error) {
            alert("something is wrong while updating question");
        }
    });
    e.preventDefault();
});

$("#viewQuestion").on("click", () => {

    if (!($('.modal.in').length)) {
        $('.modal-dialog').css({
            top: 0,
            left: 0
        });
    }
    $('#viewQuestionModal').modal({
        backdrop: false,
        show: true
    });

    $('.modal-dialog').draggable({
        handle: ".modal-header"
    });

    $("#viewQuestionModal").modal("show");
})


$("#checkToStartAgainNazar").on("change", () => {

    $("#startAgainNazarBtn").prop('disabled', false);
})

$(".specialSettings, .specialSettingsBtn, .emteyazSettingsPart").hide();

$("#mainPageSettings").on("change", () => {
    $("#myTable").fadeIn("slow");
    $(".mainPageStuff").fadeIn("slow");
    $(".specialSettingsBtn").fadeOut("slow");
    $(".specialSettings").fadeOut("slow");
    $(".emteyazSettingsPart").fadeOut("slow");
})
$("#specialSettings").on("change", () => {
    $(".specialSettings").fadeIn("slow");
    $("#myTable").fadeOut("slow");
    $(".mainPageStuff").fadeOut("slow");
    $(".specialSettingsBtn").fadeIn("slow");
    $(".emteyazSettingsPart").fadeOut("slow");

})

$("#emteyazSettings").on("change", () => {
    $(".emteyazSettingsPart").fadeIn("slow");
    $("#myTable").fadeOut("slow");
    $(".specialSettings").fadeOut("slow");
    $(".mainPageStuff").fadeOut("slow");
    $(".specialSettingsBtn").fadeOut("slow");
})




$("#lotteryResultRadioBtn").on("change", () => {
    $("#lotteryResultTable").css("display", "table");
    $("#gamerListsTable").css("display", "none");
})
$("#gamerListRadioBtn").on("change", () => {
    $("#gamerListsTable").css("display", "table");
    $("#lotteryResultTable").css("display", "none");
})

// لیست مشتریان 
$("#customerListRadioBtn").on("change", () => {
    $(".customerListStaff").css("display", "block");
    $("#officialCustomerStaff").css("display", "none");
})
$("#officialCustomerListRadioBtn").on("change", () => {
    $("#officialCustomerStaff").css("display", "block");
    $(".customerListStaff").css("display", "none");
})

$("#assesFirstDate").persianDatepicker({
    cellWidth: 32,
    cellHeight: 22,
    fontSize: 14,
    formatDate: "YYYY/0M/0D",
});

$("#assesSecondDate").persianDatepicker({
    cellWidth: 32,
    cellHeight: 22,
    fontSize: 14,
    formatDate: "YYYY/0M/0D",
});




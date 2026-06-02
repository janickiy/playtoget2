$(function () {

    // Tabs
    $("#tabs").tabs({
        activate: function (event, ui) {
            const active = $('#tabs').tabs('option', 'active');
            $("#tabid").html('the tab id is ' + $("#tabs ul>li a").eq(active).attr("href"));
        }
    });

    $.datepicker.setDefaults(
        $.extend($.datepicker.regional["ru"])
    );

    $("#datepicker").datepicker({changeYear: true, yearRange: '1940:2010'});
});


function save_form() {
    const form = $('#main_form');
    const form_content = form.serializeArray();
    const firstname = $('input[name=firstname]').val();
    const lastname = $('input[name=lastname]').val();
    const secondname = $('input[name=secondname]').val();
    const status = $('input[name=about]').val();
    const avatar = $('#preview_ava').attr('src');
    const cover = $('#preview_cover').attr('src');

    if (firstname == '' || lastname == '' || secondname == '') {
        if (firstname == '') {
            $('input[name=firstname]').addClass('error');
            $('body').append('<div id="ok_com_fr" class="save_window_fail hiden">Ошибка! Поле Имя не может быть пустым!</div>');

        }
        if (lastname == '') {
            $('input[name=lastname]').addClass('error');
            $('body').append('<div id="ok_com_fr" class="save_window_fail hiden">Ошибка! Поле Фамилия не может быть пустым!</div>');
        }
        if (secondname == '') {
            $('input[name=secondname]').addClass('error');
            $('body').append('<div id="ok_com_fr" class="save_window_fail hiden">Ошибка! Поле Ник не может быть пустым!</div>');

        }
        setTimeout(function () {
            $('#ok_com_fr').removeClass('hiden');
        }, 100);
        setTimeout(function () {
            $('#ok_com_fr').addClass('hiden');
        }, 1100)
        setTimeout(function () {
            $('#ok_com_fr').remove();
        }, 1500)
        setTimeout(function () {
            $('input[name=firstname]').removeClass('error');
            $('input[name=lastname]').removeClass('error');
            $('input[name=secondname]').removeClass('error');

        }, 2000);
    } else {
        $('h3.name').html(firstname + '<br>' + lastname + ' (' + secondname + ')');
        $('p.citation').html(status);
        $('.top_thumb_avatar').find('img').attr('src', avatar);
        $('.cover-photo').attr('src', cover);

        $.ajax({
            type: 'POST',
            url: './?task=ajax_action&action=edit_profile',
            data: form_content,
            success: function (data) {
                if (data.result == 'success') {
                    $('body').append('<div id="ok_com_fr" class="save_window_ok hiden">Профиль успешно сохранен!</div>');
                } else {
                    $('body').append('<div id="ok_com_fr" class="save_window_fail hiden">Профиль не сохранен</div>');
                }

                setTimeout(function () {
                    $('#ok_com_fr').removeClass('hiden');
                }, 100);
                setTimeout(function () {
                    $('#ok_com_fr').addClass('hiden');
                }, 1100)
                setTimeout(function () {
                    $('#ok_com_fr').remove();
                }, 1500)
            }
        })
    }
}


$(document).on('click', '#main-menu li', function () {
    const k = $(this).attr('data-type');
    switch (k) {
        case 'main':
            $('#next-step').show();
            $('#achivments').show();
            break;
        case 'education':
            $('#next-step').show();
            $('#achivments').hide();
            break;
        case 'job':
            $('#next-step').hide();
            $('#achivments').hide();
            break;
        case 'achivments':
            $('#next-step').hide();
            $('#achivments').hide();
            break;
    }

})

$(document).on('click', '#save_profile', function () {

    save_form();
    return false;
})
$(document).on('click', '#next-step', function () {
    let k = 'main';
    $('#main-menu li').each(function () {
        if ($(this).hasClass('ui-tabs-active')) {
            k = $(this).attr('data-type');
        }
    })
    switch (k) {
        case 'main':
            $('#main-menu li[data-type=education] a').click();
            break;
        case 'education':
            $('#main-menu li[data-type=job] a').click();
            break;
        case 'job':
            $('#main-menu li[data-type=achivments] a').click();
            break;
    }

    save_form();
    return false;
})


selectAction();


$(document).on('click', '.plus', function () {
    $('.education_form_new select:last-child').each(function () {
        const $this = $(this), numberOfOptions = $(this).children('option').length;

        $this.addClass('select-hidden');
        $this.wrap('<div class="select"></div>');
        $this.after('<div class="select-styled"></div>');

        const $styledSelect = $this.next('div.select-styled');
        $styledSelect.text($this.children('option').eq(0).text());

        const $list = $('<ul />', {
            'class': 'select-options'
        }).insertAfter($styledSelect);

        for (let i = 0; i < numberOfOptions; i++) {
            $('<li />', {
                text: $this.children('option').eq(i).text(),
                rel: $this.children('option').eq(i).val()
            }).appendTo($list);
        }

        const $listItems = $list.children('li');

        $styledSelect.click(function (e) {
            e.stopPropagation();
            const par = $(this).hasClass('active');
            $('.education_form_new div.select-styled.active').each(function () {
                $(this).removeClass('active').next('ul.select-options').hide();
            });
            if (!par)
                $(this).toggleClass('active').next('ul.select-options').toggle();
        });

        $listItems.click(function (e) {
            e.stopPropagation();
            $styledSelect.text($(this).text()).removeClass('active');
            $this.val($(this).attr('rel'));
            $list.hide();
            //console.log($this.val());
        });

        $(document).click(function () {
            $styledSelect.removeClass('active');
            $list.hide();
        });

    });
});

$(document).on('click', '.plus_job', function () {
    $('.job_form_new select:last-child').each(function () {
        const $this = $(this), numberOfOptions = $(this).children('option').length;

        $this.addClass('select-hidden');
        $this.wrap('<div class="select"></div>');
        $this.after('<div class="select-styled"></div>');

        const $styledSelect = $this.next('div.select-styled');
        $styledSelect.text($this.children('option').eq(0).text());

        const $list = $('<ul />', {
            'class': 'select-options'
        }).insertAfter($styledSelect);

        for (let i = 0; i < numberOfOptions; i++) {
            $('<li />', {
                text: $this.children('option').eq(i).text(),
                rel: $this.children('option').eq(i).val()
            }).appendTo($list);
        }

        const $listItems = $list.children('li');

        $styledSelect.click(function (e) {
            e.stopPropagation();
            const par = $(this).hasClass('active');
            $('.job_form_new div.select-styled.active').each(function () {
                $(this).removeClass('active').next('ul.select-options').hide();
            });
            if (!par)
                $(this).toggleClass('active').next('ul.select-options').toggle();
        });

        $listItems.click(function (e) {
            e.stopPropagation();
            $styledSelect.text($(this).text()).removeClass('active');
            $this.val($(this).attr('rel'));
            $list.hide();
            //console.log($this.val());
        });

        $(document).click(function () {
            $styledSelect.removeClass('active');
            $list.hide();
        });

    });
});

$(document).on('click', '.plus_sport_type', function () {
    $('.sport_type_form_new select:last-child').each(function () {
        const $this = $(this), numberOfOptions = $(this).children('option').length;

        $this.addClass('select-hidden');
        $this.wrap('<div class="select"></div>');
        $this.after('<div class="select-styled"></div>');

        const $styledSelect = $this.next('div.select-styled');
        $styledSelect.text($this.children('option').eq(0).text());

        const $list = $('<ul />', {
            'class': 'select-options'
        }).insertAfter($styledSelect);

        for (let i = 0; i < numberOfOptions; i++) {
            $('<li />', {
                text: $this.children('option').eq(i).text(),
                rel: $this.children('option').eq(i).val()
            }).appendTo($list);
        }

        const $listItems = $list.children('li');

        $styledSelect.click(function (e) {
            e.stopPropagation();
            const par = $(this).hasClass('active');
            $('.sport_type_form_new div.select-styled.active').each(function () {
                $(this).removeClass('active').next('ul.select-options').hide();
            });
            if (!par)
                $(this).toggleClass('active').next('ul.select-options').toggle();
        });

        $listItems.click(function (e) {
            e.stopPropagation();
            $styledSelect.text($(this).text()).removeClass('active');
            $this.val($(this).attr('rel'));
            $list.hide();
            //console.log($this.val());
        });

        $(document).click(function () {
            $styledSelect.removeClass('active');
            $list.hide();
        });

    });
});


$(document).on('click', '.minus_job', function () {
    $(this).closest('div.job_form').remove();
    $(this).closest('div.job_form_new').remove();
});

$(document).on('click', '.minus', function () {
    $(this).closest('div.education_form').remove();
    $(this).closest('div.education_form_new').remove();
});

$(document).on('click', '.minus_sport_type', function () {
    $(this).closest('div.sport_type_form').remove();
    $(this).closest('div.sport_type_form_new').remove();
});

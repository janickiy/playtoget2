function select_place(val, type) {
    const text = val;
    let url = '';
    if (type.match("^search_sport")) {
        url = './?task=ajax_action&action=search_sport_types&sport_types=';
    } else {
        url = './?task=ajax_action&action=search_city&city=';
    }
    if (text != '')
        $.ajax({
            type: 'GET',
            url: url + text,
            success: function (data) {
                $('.select-place').hide();
                $('.select-place').html('');
                console.log(url + text + ' ' + data.item);
                if (data != null && data.item != null) {
                    for (let i = 0; i < data.item.length; i++) {
                        $('.select-place[data-type=' + type + ']').append('<div class="place-item" data-item="' + data.item[i].id + '">' + data.item[i].name + '</div>')
                    }
                } else {
                    $('.select-place[data-type=' + type + ']').hide();
                }
                $('.select-place[data-type=' + type + ']').show();
            }
        })
    else {
        $('.select-place[data-type=' + type + ']').hide();
    }
}

$(document).ready(function () {
    $(document).on('keyup', '.text-place', function () {
        const text = $(this).val();
        const type = $(this).attr('data-type');
        select_place(text, type);

    })

    $(document).mouseup(function (e) {
        const div = $('.select-place');
        if (!$('.text-place').is(e.target) && !div.is(e.target) && div.has(e.target).length === 0) {
            div.hide();
        }
    })
    $(document).on('focus', '.text-place', function () {
        const text = $(this).val();
        const type = $(this).attr('data-type');
        if (text == 'Нет') {
            $(this).val('');
        } else {
            select_place(text, type);
        }
    })

    $(document).on('click', '.place-item', function () {
        const text = $(this).html();
        const type = $(this).parent('div').attr('data-type');
        const item = $(this).attr('data-item');
        $('.text-place[data-type=' + type + ']').val(text)
        $('.id_place[data-type=' + type + ']').val(item);
        $('.select-place').hide();

    })
})
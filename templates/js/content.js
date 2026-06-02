function getRandomInt(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

$(document).ready(function () {
    $(document).on('submit', '#feedback-form', function (event) {
        event.preventDefault();
        let error = false;
        const form = $(this).serializeArray();
        for (let i = 0; i < form.length; i++) {
            if (form[i].value == '') {
                $('input[name=' + form[i].name + ']').addClass('error');
                $('textarea[name=' + form[i].name + ']').addClass('error');
                error = true;
                setTimeout(function () {
                    $('input').removeClass('error');
                    $('textarea').removeClass('error');
                }, 3000)
            }
        }
        if (!error) {
            $.ajax({
                type: 'POST',
                url: './?task=ajax_action&action=send_message',
                data: form,
                success: function (data) {
                    if (data.status == 1) {
                        $('.save_window_ok').html(data.msg).removeClass('hiden');

                        setTimeout(function () {
                            $('.save_window_ok').addClass('hiden');
                        }, 3000)
                        $('input[type=text]').val('');
                        $('textarea').val('');
                    } else {
                        $('.save_window_fail').html(data.msg).removeClass('hiden');

                        setTimeout(function () {
                            $('.save_window_fail').addClass('hiden');
                        }, 3000)
                    }
                    $('#captcha').attr('src', 'captcha.php?id=' + getRandomInt(1, 99999));
                }

            })
        }

    })
})

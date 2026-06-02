let socket = null;
let time = null;

function initHeaderSocket() {
    if (socket || typeof io === 'undefined') {
        return;
    }

    socket = io.connect(window.socketHost || (window.location.protocol + '//' + window.location.hostname + ':3000'));
    time = setTimeout(function () {
        $('.typing').removeClass('show')
    }, 1000);


    socket.on('typing', function (msg) {
        if (msg.receiver_id == window.user) {
            $('.typing').addClass('show');
            clearTimeout(time);
            time = setTimeout(function () {
                $('.typing').removeClass('show')
            }, 1000);
            $('.mess_list').animate({scrollTop: 1000000}, 1100);
        }
    })


    socket.on('message', function (msg) {
        console.log(msg)
        if (msg.receiver_id == window.user) {

            $.ajax({
                type: 'POST',
                url: './?task=ajax_action&action=get_last_message',
                data: {
                    receiver_id: msg.sender_id,
                },
                success: function (data) {
                    console.log(data);
                    const audio = new Audio();
                    audio.preload = 'auto';
                    audio.src = './templates/audio/message.mp3';
                    audio.play();

                    let Message = '<div class="message-reply" id="message-' + data.item[0].id_message + '">';
                    Message += '<div class="message ">';
                    Message += '<div class="message-account">';
                    Message += '<img src="' + data.item[0].avatar + '" alt="" class="img-account">';
                    Message += '<h5 class="name"><a href="./?task=profile&user_id=' + data.item[0].sender_id + '">' + data.item[0].firstname + ' ' + data.item[0].lastname + '</a></h5>';
                    Message += '<p class="data">' + data.item[0].created + '</p>';
                    Message += '</div>';
                    Message += '<p class="message-reply-text">' + data.item[0].content + '<br>';
                    Message += data.item[0].image + '</p>';
                    Message += '</div>';
                    Message += '</div>';

                    const fact = $("div").is("#message-list[data-num='" + data.item[0].sender_id + "']");
                    console.log(fact)
                    if (fact) {
                        $('#message-list').append(Message);
                        $('#message_count').fadeOut();
                        $('.mess_list').find('.no_message').remove();
                        $('.mess_list').animate({scrollTop: 1000000}, 1100);
                        $('.message-text').each(function () {
                            $(this).emotions();
                        })

                        $('.message-reply-text').each(function () {
                            $(this).emotions();
                        })
                    } else {
                        const dialog = $('div').is('#old_dialogue');
                        const dialog_con = $('div').is('.dialogues[data-num=' + data.item[0].sender_id + ']');

                        if (dialog) {

                            $('#old_dialogue').find('.no_dialogues').remove();
                            if (!dialog_con) {
                                let dialogues = '<div class="row dialogues " data-num=' + data.item[0].sender_id + '>';
                                dialogues += '<div class="col-md-4">';
                                dialogues += '<a href="./?task=profile&user_id=' + data.item[0].sender_id + '">';
                                dialogues += '<img src="' + data.item[0].avatar + '" width="50" alt="" class="img-account" style="float: left;">'
                                dialogues += '<div class="fromwho">' + data.item[0].firstname + '<br>' + data.item[0].lastname + '<br>';
                                dialogues += '<span>' + data.item[0].created + '</span></div>';
                                dialogues += '</a></div>';
                                dialogues += '<div class="col-md-8 ">';
                                dialogues += '<a href="./?task=profile&user_id=' + data.item[0].sender_id + '&q=messages&sel=' + data.item[0].sender_id + '" >';
                                dialogues += '<img src="' + data.item[0].avatar + '" alt="" class="img-mess-dialog">';
                                dialogues += '<span class="ahref status_red ">' + data.item[0].content + '</span>';
                                dialogues += '</a></div></div>';
                                $('.container_dialog').prepend(dialogues);

                                $('.href').each(function () {
                                    $(this).emotions();
                                })
                            } else {
                                $('.dialogues[data-num=' + data.item[0].sender_id + ']').find('.ahref').html(data.item[0].content);
                            }
                        } else {
                            const count = parseInt($('#message_count').html()) + 1;
                            $('#message_count').html(count).fadeIn();

                            let message = '<img src="' + data.item[0].avatar + '" width="50" alt="" class="img-account" style="float: left;">';
                            message += '<div class="fromwho">' + data.item[0].firstname + '<br>' + data.item[0].lastname + '<br>';
                            message += '<span>' + data.item[0].created + '</span></div>';
                            message += '<p>' + data.item[0].content + '</p>';
                            $('.window-message').html(message);
                            $('.window-message').fadeIn();
                            setTimeout(function () {
                                $('.window-message').fadeOut();
                            }, 2000)
                        }
                    }
                }
            })
        }
    });
}


$(document).ready(function () {
    initHeaderSocket();

    $('#message').keypress(function () {
        if (!socket) {
            return;
        }

        const sender_id = $('[name=sender_id]').val();
        const receiver_id = $('[name=receiver_id]').val();
        socket.emit('typing', {sender_id: sender_id, receiver_id: receiver_id});
    })


    function getresult(url) {
        $.ajax({
            url: url,
            type: "GET",
            data: {rowcount: $("#rowcount").val()},
            beforeSend: function () {
                $('#loader-icon').show();
            },
            complete: function () {
                $('#loader-icon').hide();
            },
            success: function (data) {
                $("#faq-result").append(data);
            },
            error: function () {
            }
        });
    }

    $(window).scroll(function () {
        if ($(window).scrollTop() == $(document).height() - $(window).height()) {
            if ($(".pagenum:last").val() <= $(".total-page").val()) {
                const pagenum = parseInt($(".pagenum:last").val()) + 1;
                getresult('./?task=ajax_action&action=getpopphotos&page=' + pagenum);
            }
        }
    });

    $(document).on('keyup', '#main_search', function () {
        const text = $(this).val();
        if (text != '') {
            $(this).addClass('white');
        } else {
            $(this).removeClass('white');
        }
    })
});

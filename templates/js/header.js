let messagePollTimer = null;
let messagePollPrimed = false;
let lastInboxMessageId = 0;
const messagePollInterval = 5000;

function messageId(row) {
    return parseInt(row.id_message || row.id || 0, 10) || 0;
}

function rememberLastInboxId(rows) {
    $.each(rows || [], function (_, row) {
        lastInboxMessageId = Math.max(lastInboxMessageId, messageId(row));
    });
}

function applyMessageEmotions() {
    $('.message-text').each(function () {
        $(this).emotions();
    });

    $('.message-reply-text').each(function () {
        $(this).emotions();
    });
}

function setMessageCount(count) {
    const value = parseInt(count, 10) || 0;
    const counter = $('#message_count');

    if (!counter.length) {
        return;
    }

    counter.html(value);

    if (value > 0) {
        counter.removeClass('displayNone').fadeIn();
    } else {
        counter.addClass('displayNone').hide();
    }
}

function playMessageSound() {
    const audio = new Audio();
    audio.preload = 'auto';
    audio.src = './templates/audio/message.mp3';

    const result = audio.play();
    if (result && typeof result.catch === 'function') {
        result.catch(function () {});
    }
}

function renderIncomingMessage(row) {
    let message = '<div class="message-reply" id="message-' + messageId(row) + '">';
    message += '<div class="message ">';
    message += '<div class="message-account">';
    message += '<img src="' + row.avatar + '" alt="" class="img-account">';
    message += '<h5 class="name"><a href="./?task=profile&user_id=' + row.sender_id + '">' + row.firstname + ' ' + row.lastname + '</a></h5>';
    message += '<p class="data">' + row.created + '</p>';
    message += '</div>';
    message += '<p class="message-reply-text">' + row.content + '<br>';
    message += row.image + '</p>';
    message += '</div>';
    message += '</div>';

    return message;
}

function updateDialogPreview(row) {
    const dialog = $('div').is('#old_dialogue');
    const dialogExists = $('div').is('.dialogues[data-num=' + row.sender_id + ']');

    if (!dialog) {
        return false;
    }

    $('#old_dialogue').find('.no_dialogues').remove();

    if (!dialogExists) {
        let dialogues = '<div class="row dialogues " data-num=' + row.sender_id + '>';
        dialogues += '<div class="col-md-4">';
        dialogues += '<a href="./?task=profile&user_id=' + row.sender_id + '">';
        dialogues += '<img src="' + row.avatar + '" width="50" alt="" class="img-account" style="float: left;">';
        dialogues += '<div class="fromwho">' + row.firstname + '<br>' + row.lastname + '<br>';
        dialogues += '<span>' + row.created + '</span></div>';
        dialogues += '</a></div>';
        dialogues += '<div class="col-md-8 ">';
        dialogues += '<a href="./?task=profile&user_id=' + row.sender_id + '&q=messages&sel=' + row.sender_id + '" >';
        dialogues += '<img src="' + row.avatar + '" alt="" class="img-mess-dialog">';
        dialogues += '<span class="ahref status_red ">' + row.content + '</span>';
        dialogues += '</a></div></div>';
        $('.container_dialog').prepend(dialogues);
    } else {
        $('.dialogues[data-num=' + row.sender_id + ']').find('.ahref').html(row.content);
    }

    $('.href').each(function () {
        $(this).emotions();
    });

    return true;
}

function showMessagePopup(row) {
    let message = '<img src="' + row.avatar + '" width="50" alt="" class="img-account" style="float: left;">';
    message += '<div class="fromwho">' + row.firstname + '<br>' + row.lastname + '<br>';
    message += '<span>' + row.created + '</span></div>';
    message += '<p>' + row.content + '</p>';
    $('.window-message').html(message);
    $('.window-message').fadeIn();
    setTimeout(function () {
        $('.window-message').fadeOut();
    }, 2000);
}

function handleIncomingMessage(row, notify) {
    const id = messageId(row);
    const selectedDialogUser = $('#message-list').attr('data-num');

    if (!id || $('#message-' + id).length) {
        return;
    }

    if (selectedDialogUser && String(selectedDialogUser) === String(row.sender_id)) {
        $('#message-list').append(renderIncomingMessage(row));
        $('.mess_list').find('.no_message').remove();
        $('.mess_list').animate({scrollTop: 1000000}, 1100);
        applyMessageEmotions();
    } else if (!updateDialogPreview(row) && notify) {
        showMessagePopup(row);
    }

    if (notify) {
        playMessageSound();
    }
}

function pollIncomingMessages() {
    if (!window.user) {
        return;
    }

    $.ajax({
        type: 'POST',
        url: './?task=ajax_action&action=get_new_messages',
        dataType: 'json',
        data: {
            last_id: lastInboxMessageId
        },
        success: function (data) {
            const rows = data && data.item ? data.item : [];

            if (typeof data.count !== 'undefined') {
                setMessageCount(data.count);
            }

            if (!messagePollPrimed) {
                rememberLastInboxId(rows);
                messagePollPrimed = true;
                return;
            }

            $.each(rows, function (_, row) {
                rememberLastInboxId([row]);
                handleIncomingMessage(row, true);
            });
        }
    });
}

function initHeaderPolling() {
    if (messagePollTimer) {
        return;
    }

    pollIncomingMessages();
    messagePollTimer = setInterval(pollIncomingMessages, messagePollInterval);
}

window.initHeaderPolling = initHeaderPolling;


$(document).ready(function () {
    initHeaderPolling();


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

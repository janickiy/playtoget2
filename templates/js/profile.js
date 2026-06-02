$(document).ready(function () {
    $('.mess_list').animate({scrollTop: 1000000}, 1100);

    let lastDialogMessageId = 0;

    function getMessageId(row) {
        return parseInt(row.id_message || row.id || 0, 10) || 0;
    }

    function updateLastDialogMessageId(id) {
        lastDialogMessageId = Math.max(lastDialogMessageId, parseInt(id, 10) || 0);
    }

    function initLastDialogMessageId() {
        $('.mess_list [id^="message-"]').each(function () {
            updateLastDialogMessageId(String($(this).attr('id')).replace('message-', ''));
        });
    }

    function refreshMessageVisuals() {
        $('.message-text').each(function () {
            $(this).emotions();
        });
        $('.message-reply-text').each(function () {
            $(this).emotions();
        });
    }

    function renderDialogMessage(row) {
        const id = getMessageId(row);
        const isMine = String(row.sender_id) === String(window.user_id);
        let message = '';

        if (isMine) {
            message += '<div id="message-' + id + '" class="message">';
            message += '<div class="message-account"><img src="' + row.avatar + '" alt="" class="img-account">';
            message += '<h5 class="name"><a href="./?task=profile&user_id=' + row.sender_id + '">' + row.firstname + ' ' + row.lastname + '</a></h5>';
            message += '<p class="data">' + row.created + '</p></div>';
            message += '<p class="message-text">' + row.content + '<br>';
            message += row.image + '</p>';
            message += "<div class='del-message' data-item='" + id + "' data-tooltip='Удалить сообщение'></div>";
            message += '</div>';
        } else {
            message += '<div class="message-reply" id="message-' + id + '">';
            message += '<div class="message">';
            message += '<div class="message-account"><img src="' + row.avatar + '" alt="" class="img-account">';
            message += '<h5 class="name"><a href="./?task=profile&user_id=' + row.sender_id + '">' + row.firstname + ' ' + row.lastname + '</a></h5>';
            message += '<p class="data">' + row.created + '</p></div>';
            message += '<p class="message-reply-text">' + row.content + '<br>';
            message += row.image + '</p>';
            message += '</div>';
            message += '</div>';
        }

        return message;
    }

    function appendDialogMessage(row) {
        const id = getMessageId(row);

        if (!id) {
            return;
        }

        if ($('#message-' + id).length) {
            updateLastDialogMessageId(id);
            return;
        }

        $('#message-list').append(renderDialogMessage(row));
        $('.mess_list').find('.no_message').remove();
        $('.mess_list').animate({scrollTop: 1000000}, 1100);
        refreshMessageVisuals();
        updateLastDialogMessageId(id);
    }

    function pollDialogMessages() {
        const receiver_id = $('[name=receiver_id]').val() || $('#message-list').attr('data-num');

        if (!receiver_id) {
            return;
        }

        $.ajax({
            type: 'POST',
            url: './?task=ajax_action&action=get_new_messages',
            dataType: 'json',
            data: {
                receiver_id: receiver_id,
                last_id: lastDialogMessageId
            },
            success: function (data) {
                if (typeof data.count !== 'undefined' && typeof setMessageCount === 'function') {
                    setMessageCount(data.count);
                }

                if (data && data.item) {
                    $.each(data.item, function (_, row) {
                        appendDialogMessage(row);
                    });
                }
            }
        });
    }

    initLastDialogMessageId();
    setInterval(pollDialogMessages, 4000);

    $('#addMessageForm').submit(function (e) {
        $('.typing').removeClass('show')
        const then = $(this);
        const comment = then.find('#comment').val();
        const files = then.find('.attach');
        const attach = [];
        files.each(function () {
            attach.push($(this).attr('data-id'));
        })
        $('span.error').remove();

        if (comment != '' || files.length != 0) {
            const window_h = $(window).height();
            const mess_h = window_h - 310;
            $('.mess_list').css('height', mess_h);
            $('span.error').remove();
            $('.files_block').append('<div class="loading-mess"><img border="0" src="./templates/images/select2-spinner.gif" width=20px></div>');
            $('.files_block').html('');
            const formData = then.serializeArray();
            if (attach.length != 0)
                formData.push({'name': 'attach', 'value': attach});
            //console.log(formData);
            $.ajax({
                url: './?task=ajax_action&action=addmessage',
                data: formData,
                type: 'POST',
                success: function (data) {
                    //console.log(data);
                    then[0].reset();

                    if (data.status) {
                        let Message = '<div id="message-' + data.id + '" class="message">';
                        Message += '<div class="message-account"><img src="' + data.avatar + '" alt="" class="img-account">';
                        Message += '<h5 class="name"><a href="./?task=profile&user_id=' + data.sender_id + '">' + data.firstname + ' ' + data.lastname + '</a></h5>';
                        Message += '<p class="data">' + data.created + '</p></div>';
                        Message += '<p class="message-text">' + data.content + '<br>';
                        Message += data.image + '</p>';
                        Message += "<div class='del-message' data-item='" + data.id + "' data-tooltip='Удалить сообщение'></div>";
                        Message += '</div>';

                        $('#message-list').append(Message);
                        $('.message-text').each(function () {
                            $(this).emotions();
                        })
                        $('.message-reply-text').each(function () {
                            $(this).emotions();
                        })
                        $('.mess_list').find('.no_message').remove();
                        $('.mess_list').animate({scrollTop: 1000000}, 1100);
                        $('#message').val('');
                        updateLastDialogMessageId(data.id);
                    } else {
                        $.each(data.errors, function (k, v) {
                            $('label[for=' + k + ']').append('<span class="error">' + v + '</span>');
                        });
                    }
                }
            })
        }
        return false;
    });

    let messLoad = true;
    const settingsLoad = {
        number: 10,
        offset: 10,
    };
    const receiver_id = $('[name=receiver_id]').val() || $('#message-list').attr('data-num');

    $('.mess_list').scroll(function () {

        if ($(this).scrollTop() == 0) {
            if (messLoad) {
                $('.mess_list').prepend('<div class="loading-bar"><img border="0" src="./templates/images/select2-spinner.gif" width=20px></div>')
                messLoad = false;
                $.ajax({
                    type: 'POST',
                    url: '/?task=ajax_action&action=getmessages',
                    data: {
                        receiver_id: receiver_id,
                        number: settingsLoad.number,
                        offset: settingsLoad.offset,
                    },
                    success: function (data) {
                        if (data != '' && data.item != null) {
                            for (let i = 0; i < data.item.length; i++) {
                                let Message = '<div id="message-' + data.item[i].id_message + '" class="message">';
                                Message += '<div class="message-account"><img src="' + data.item[i].avatar + '" alt="" class="img-account">';
                                Message += '<h5 class="name"><a href="./?task=profile&user_id=' + data.item[i].sender_id + '">' + data.item[i].firstname + ' ' + data.item[i].lastname + '</a></h5>';
                                Message += '<p class="data">' + data.item[i].created + '</p></div>';
                                Message += '<p class="message-text">' + data.item[i].content + '</p>';
                                Message += '</div>';
                                $('.mess_list').prepend(Message);
                            }
                            settingsLoad.offset += settingsLoad.number;
                        }
                        $('.mess_list').find('.loading-bar').remove();
                        messLoad = true;
                    }
                })
            }
        }
    });


    $('.new_dialog').click(function () {
        const status = $(this).attr('data-status');
        if (status == 'new') {
            $('#old_dialogue').addClass('hide');
            $('#new_dialogue').removeClass('hide');
            $(this).attr('data-status', 'old');
            $(this).html("<h5><img src='./templates/images/message-sitebar.png'/> Вернуться в диалоги</h5>");
        } else {
            $('#old_dialogue').removeClass('hide');
            $('#new_dialogue').addClass('hide');
            $(this).attr('data-status', 'new');
            $(this).html("<h5><img src='./templates/images/pen.png'/> Начать новый диалог</h5>");
        }
    })


});


$(document).on("click", ".reply", function () {
    const IdComment = $(this).attr('data-item');
    const IdParent = $('#message-' + IdComment).attr('data-item');

    $('.reply').show();
    $(this).hide();
    $('.my-comment').hide();

    let ReplyForm = '<div id="my-comment-' + IdComment + '" class="my-comment">';
    ReplyForm += '<div class="message-account">';
    ReplyForm += '<img src="' + avatar + '" alt="" class="img-account">';
    ReplyForm += '</div>';
    ReplyForm += '<form autocomplete="off" id="reply-form-' + IdComment + '" data-num = ' + IdComment + ' action="" enctype="multipart/form-data">';
    ReplyForm += '<input type="hidden" name="commentable_type" value="user">';
    ReplyForm += '<input type="hidden" name="content_id" value="' + content_id + '">';
    ReplyForm += '<input type="hidden" name="user_id" value="' + user_id + '">';
    ReplyForm += '<input type="hidden" name="parent_id" value="' + IdComment + '">';
    ReplyForm += '<input type="file" class="file_name" name="file_name[]" data-num="' + IdComment + '" multiple/>';
    ReplyForm += '<input id="comment" name="comment" type="text" data-num="' + IdComment + '" placeholder="' + placeholder + '">';
    ReplyForm += '<div class="smile-files">';
    ReplyForm += '<a id="smilesBtn" class="smile smilesBtn" data-num="' + IdComment + '"><img src="./templates/images/smile.png" alt=""></a>';
    ReplyForm += '<a href="#" class="files" data-num="' + IdComment + '"  data-tooltip="Прикрепить изображение"><img src="./templates/images/files.png" alt=""></a>';
    ReplyForm += "<div class='smilesChoose add' data-num='" + IdComment + "'></div>";
    ReplyForm += '</div>';
    ReplyForm += "<div class='files_block two' data-num='" + IdComment + "'></div>";
    ReplyForm += '<input type="submit" id="send-reply" class="send" value="Отправить" data-item="' + IdComment + '">';
    ReplyForm += '</form>';
    ReplyForm += '<div style="clear:both"></div>';
    ReplyForm += '</div>';

    $(ReplyForm).hide().insertAfter('#message-' + IdComment).slideDown();

});

let evJob = true;
const settComments = {
    number: 10,
    offset: 10,
};

$(document).scroll(function () {

    if ($(window).scrollTop() + $(window).height() >= $(document).height()) {
        if (evJob) {
            evJob = false;
            $('#comment-list').append('<div class="loading-bar"><img border="0" src="./templates/images/select2-spinner.gif" width=20px></div>')
            $.ajax({
                type: 'POST',
                url: '/?task=ajax_action&action=getcomments',
                data: {
                    number: settComments.number,
                    offset: settComments.offset,
                    commentable_type: 'user',
                    id: id_profile
                },
                success: function (data) {
                    $('#comment-list').find('.loading-bar').remove();
                    $('#comment-list').append(data.html);
                    $('.message-text').each(function () {
                        $(this).emotions();
                    })
                    $('.message-reply-text').each(function () {
                        $(this).emotions();
                    })
                    settComments.offset += settComments.number;
                    evJob = true;
                }
            })
        }
    }
});

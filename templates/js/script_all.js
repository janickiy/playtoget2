function show_time_end() {
    const time_end = $('#time_end');
    $('.button_time').hide();
    time_end.toggleClass('hiden');

}

function parseGetParams() {
    const $_GET = {};
    const __GET = window.location.search.substring(1).split("&");
    for (let i = 0; i < __GET.length; i++) {
        const getVar = __GET[i].split("=");
        if (typeof (getVar[1]) == "undefined")
            $_GET['none'] = 'none';
        else
            $_GET[getVar[0]] = getVar[1];
    }
    return $_GET;
}

function delParams(par) {
    let str = window.location.toString();
    str = str.split('?' + par)[0];
    str = str.split('&' + par)[0];
    window.history.pushState(null, null, str);
    return str;
}


function selectAction() {
    $('select').each(function () {
        const $this = $(this), numberOfOptions = $(this).children('option').length;

        $this.addClass('select-hidden');
        $this.wrap('<div class="select"></div>');
        $this.after('<div class="select-styled"></div>');

        const $styledSelect = $this.next('div.select-styled');
        $styledSelect.text($this.children('option:selected').text());

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
            $('div.select-styled.active').each(function () {
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
            if (uploader)
                uploader.settings.multipart_params.categorie = $(this).attr('rel');

        });

        $(document).click(function () {
            $styledSelect.removeClass('active');
            $list.hide();
        });

    });
}

function getPosition(e) {
    let posx = 0;
    let posy = 0;
    if (!e) e = window.event;
    if (e.pageX || e.pageY) {
        posx = e.pageX;
        posy = e.pageY;
    } else if (e.clientX || e.clientY) {
        posx = e.clientX + document.body.scrollLeft
            + document.documentElement.scrollLeft;
        posy = e.clientY + document.body.scrollTop
            + document.documentElement.scrollTop;
    }
    return {
        x: posx,
        y: posy
    }
}

function getRandomInt(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

function remove_black_list(id) {
    $.confirm({
        'title': 'Подтверждение',
        'message': 'Вы действительно хотите удалить пользователя из черного списка?',
        'buttons': {
            'Да': {
                'class': 'blue',
                'action': function () {
                    $.ajax({
                        type: 'POST',
                        url: './?task=ajax_action&action=unblock_user',
                        data: 'user_id=' + id,
                        success: function (msg) {
                            if (msg.status == 'success') {
                                $('.possible-friend-cart[data-num=' + id + ']').remove();
                            }
                        }
                    });
                }
            },
            'Нет': {
                'class': 'gray',
                'action': function () {
                }
            }
        }
    });
}

const settMore = {
    number: 5,
    offset: 5,
};

function showMore(id, type) {
    $.ajax({
        type: 'POST',
        url: '/?task=ajax_action&action=get_communities_list',
        data: {
            number: settMore.number,
            offset: settMore.offset,
            user_id: id,
            type: type,
        },
        success: function (data) {
            //console.log(data);
            if (data.status == 1 && data.html != '') {
                $('#my-event').before(data.html);
                settMore.offset += settMore.number;
            } else {
                $('#my-event').hide();
            }
        }
    })
}

const settPopMore = {
    number: 5,
    offset: 5,
};

function showPopMore(type) {
    $.ajax({
        type: 'POST',
        url: '/?task=ajax_action&action=get_pop_communities_list',
        data: {
            number: settPopMore.number,
            offset: settPopMore.offset,
            type: type,
        },
        success: function (data) {
            //console.log(data);
            if (data.status == 1 && data.html != '') {
                $('#my-event-pop').before(data.html);
                settPopMore.offset += settPopMore.number;
            } else {
                $('#my-event-pop').hide();
            }
        }
    })
}

const settMoreEvent = {
    number: 5,
    offset: 5,
};

function showMoreEvent(id, type) {
    $.ajax({
        type: 'POST',
        url: '/?task=ajax_action&action=get_events_list',
        data: {
            number: settMore.number,
            offset: settMore.offset,
            member_id: id,
            eventable_type: type,
        },
        success: function (data) {
            //console.log(data);
            if (data.status == 1 && data.html != '') {
                $('#my-event').before(data.html);
                settMore.offset += settMore.number;
            } else {
                $('#my-event').hide();
            }
        }
    })
}

const settMorePopEvent = {
    number: 5,
    offset: 5,
};

function showMorePopEvent() {
    $.ajax({
        type: 'POST',
        url: '/?task=ajax_action&action=get_pop_events_list',
        data: {
            number: settMorePopEvent.number,
            offset: settMorePopEvent.offset,
        },
        success: function (data) {
            ///console.log(data);
            if (data.status == 1 && data.html != '') {
                $('#my-event-pop').before(data.html);
                settMorePopEvent.offset += settMorePopEvent.number;
            } else {
                $('#my-event-pop').hide();
            }
        }
    })
}

const settMorePhotos = {
    number: 6,
    offset: 6,
};

function showMorePhotos(id, type) {
    //alert(id+' '+type);
    //alert(settMorePhotos.number);
    $.ajax({
        type: 'POST',
        url: '/?task=ajax_action&action=get_photos_list',
        data: {
            number: settMorePhotos.number,
            offset: settMorePhotos.offset,
            owner_id: id,
            type: type,
        },
        success: function (data) {
            //console.log(data);
            if (data.status == 1 && data.html != '') {
                $('#my-event').before(data.html);
                settMorePhotos.offset += settMorePhotos.number;
            } else {
                $('#my-event').hide();
            }

        }
    })
}

const settMoreVideos = {
    number: 6,
    offset: 6,
};

function showMoreVideos(id, type) {
    //alert(id+' '+type);
    $.ajax({
        type: 'POST',
        url: '/?task=ajax_action&action=get_videos_list',
        data: {
            number: settMoreVideos.number,
            offset: settMoreVideos.offset,
            owner_id: id,
            type: type,
        },
        success: function (data) {
            //console.log(data);
            if (data.status == 1 && data.html != '') {
                $('#my-event').before(data.html);
                settMoreVideos.offset += settMoreVideos.number;
            } else {
                $('#my-event').hide();
            }
        }
    })
}

const settMoreFriend = {
    number: 10,
    offset: 10,
};

function showMoreFriend(user_id) {
    //alert(id+' '+type);
    $.ajax({
        type: 'POST',
        url: './?task=ajax_action&action=get_friends_list',
        data: {
            number: settMoreFriend.number,
            offset: settMoreFriend.offset,
            user_id: user_id,
        },
        success: function (data) {
            if (data.item != null) {
                for (let i = 0; i < data.item.length; i++) {
                    console.log(data.item[i])
                    html = '';
                    html += '<div class="col-xs-6 possible-friend-cart">';
                    html += '<a class="possible-avatar" href="./?task=profile&user_id=' + data.item[i]['user_id'] + '">';
                    html += '<img src="' + data.item[i]['avatar'] + '" alt=""> </a> <a href="./?task=profile&user_id=' + data.item[i]['user_id'] + '">';
                    html += '<h5><strong>' + data.item[i]['firstname'] + '<span class="status_user';
                    if (data.item[i]['status_user'] == 'online') html += 'online';
                    html += '" data-num="' + data.item[i]['user_id'] + '"></span><br />' + data.item[i]['lastname'] + '</strong></h5></a><br>';
                    if (data.item[i]['city'] != null) html += '<p>' + data.item[i]['city'] + '</p>';
                    html += '<a href="./?task=profile&q=messages&sel=' + data.item[i]['user_id'] + '" data-tooltip="Написать сообщение"><b></b></a>';
                    html += '</div>';

                    $('#friends').append(html);
                }
                settMoreFriend.offset += settMoreFriend.number;
            } else {
                $('#show_more_friends').hide();
            }

        }
    })
}

function community_leave(type, id) {
    let message = '';
    switch (type) {
        case 'owner':
            message = 'Вы – владелец сообщества. Покинув её, Вы лишитесь административных прав. Выйти из сообщества?';
            break;

        case 'admin':
            message = 'Вы – администратор сообщества. Покинув её, Вы лишитесь административных прав. Выйти из сообщества?';
            break;

        case 'member':
            message = 'Вы действительно хотите выйти из сообщества?';
            break;

        case 'invited':
            message = 'Вы действительно хотите отказаться от приглашения?';
            break;
    }
    $.confirm({
        'title': 'Подтверждение',
        'message': message,
        'buttons': {
            'Да': {
                'class': 'blue',
                'action': function () {
                    $.ajax({
                        type: 'POST',
                        url: '/?task=ajax_action&action=changememberstatus',
                        data: {
                            id: id,
                            status: 0,
                        },
                        success: function (data) {
                            //console.log(data);
                            if (data.result == 'success') {

                                $('.groups_button_leave').removeClass('red');
                                $('.groups_button').removeClass('leave_fr').html('Присоединиться');
                                $('.groups_button').attr('onclick', 'community(' + id + ',1,"");');
                                $('.groups_button_leave').addClass('hide');

                            }

                        }
                    })
                }
            },
            'Нет': {
                'class': 'gray',
                'action': function () {
                }
            }
        }
    });
}

function community_add(id) {
    $.ajax({
        type: 'POST',
        url: '/?task=ajax_action&action=changememberstatus',
        data: {
            id: id,
            status: 1,
        },
        success: function (data) {
            if (data.result == 'success') {
                $('.groups_button').addClass('leave_fr').html('Пригласить друзей');
                $('.groups_button_leave').removeClass('hide');
                $('.groups_button_leave').attr('onclick', 'community(' + id + ',0,"member");');
                $('.groups_button').attr('onclick', 'commun_fr(' + id + ');');
                $('.groups_button_leave').removeClass('red');
            }

        }
    })
}

function community(id, status, type) {

    //console.log(type);
    if (status == 0)
        community_leave(type, id);
    else
        community_add(id);
}

function commun_fr(id) {
    $.ajax({
        type: 'POST',
        url: '/?task=ajax_action&action=send_community_invitation',
        data: 'community_id=' + id,
        success: function (data) {
            if (data.result == 'success') {
                $('body').append('<div id="ok_com_fr" class="save_window_ok hiden">Приглашения вашим друзьям отправлены!</div>');
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
        }
    })
}

function add_admin(user_id, community_id) {
    $.ajax({
        type: 'POST',
        url: '/?task=ajax_action&action=add_community_administrator',
        data: {
            community_id: community_id,
            user_id: user_id,
        },
        success: function (data) {
            location.reload();
        }
    })
}

function remove_admin(user_id, community_id) {
    $.ajax({
        type: 'POST',
        url: '/?task=ajax_action&action=remove_community_administrator',
        data: {
            community_id: community_id,
            user_id: user_id,
        },
        success: function (data) {
            location.reload();
        }
    })
}

function add_black_community(user_id, community_id) {
    $.ajax({
        type: 'POST',
        url: '/?task=ajax_action&action=block_community_user',
        data: {
            community_id: community_id,
            user_id: user_id,
        },
        success: function (data) {
            location.reload();
        }
    })
}

function remove_black_community(user_id, community_id) {
    $.ajax({
        type: 'POST',
        url: '/?task=ajax_action&action=unblock_community_user',
        data: {
            community_id: community_id,
            user_id: user_id,
        },
        success: function (data) {
            location.reload();
        }
    })
}

function approve_community_user(user_id, community_id) {
    $.ajax({
        type: 'POST',
        url: '/?task=ajax_action&action=approve_community_user',
        data: {
            community_id: community_id,
            user_id: user_id,
        },
        success: function (data) {
            location.reload();
        }
    })

}


function event_fr(id) {
    $.ajax({
        type: 'POST',
        url: '/?task=ajax_action&action=send_event_invitation',
        data: 'event_id=' + id,
        success: function (data) {
            if (data.result == 'success') {
                $('body').append('<div id="ok_com_fr" class="save_window_ok hiden">Приглашения вашим друзьям отправлены!</div>');
                $('#ok_com_fr').removeClass('hiden');
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
        }
    })
}

function event_add(id) {
    $.ajax({
        type: 'POST',
        url: '?task=ajax_action&action=change_event_memberstatus',
        data: {
            event_id: id,
            status: 1,
        },
        success: function (data) {
            if (data.result == 'success') {
                $('.groups_button').addClass('leave_fr').html('Пригласить друзей');
                $('.groups_button').attr('onclick', 'event_fr(' + id + ');');
                $('.groups_button_leave').attr('onclick', 'event_join(' + id + ',0,"member");');
                $('.groups_button_leave').removeClass('hide');
            }
            $('.groups_button_leave').removeClass('red');
        }
    })
}

function event_leave(id, type) {
    let message = '';
    switch (type) {
        case 'owner':
            message = 'Вы – владелец мероприятия. Покинув его, Вы лишитесь административных прав. Покинуть мероприятие?';
            break;

        case 'admin':
            message = 'Вы – администратор мероприятия. Покинув его, Вы лишитесь административных прав. Покинуть мероприятие?';
            break;

        case 'member':
            message = 'Вы действительно хотите покинуть мероприятие?';
            break;

        case 'invited':
            message = 'Вы действительно хотите отказаться от приглашения?';
            break;
    }
    $.confirm({
        'title': 'Подтверждение',
        'message': message,
        'buttons': {
            'Да': {
                'class': 'blue',
                'action': function () {
                    $.ajax({
                        type: 'POST',
                        url: '?task=ajax_action&action=change_event_memberstatus',
                        data: {
                            event_id: id,
                            status: 0,
                        },
                        success: function (data) {
                            if (data.result == 'success') {
                                $('.groups_button').removeClass('leave_fr').html('Присоединиться');
                                $('.groups_button').attr('onclick', 'event_join(' + id + ',1,"");');
                                $('.groups_button_leave').addClass('hide');
                            }
                            $('.groups_button_leave').removeClass('red');
                        }
                    })
                }
            },
            'Нет': {
                'class': 'gray',
                'action': function () {
                }
            }
        }
    });
}

function event_join(id, status, type) {
    if (status == 0)
        event_leave(id, type);
    else
        event_add(id);
}

function change_event_community_status(community_id, event_id, status) {
    console.log(community_id + ' ' + event_id + ' ' + status);
    $.ajax({
        type: 'POST',
        url: '/?task=ajax_action&action=change_event_community_status',
        data: {
            event_id: event_id,
            community_id: community_id,
            status: status,
        },
        success: function (data) {
            if (data.result == 'success')
                location.reload();
        }
    })
}


/*ACEPT FRIEND*/
function accept_friendship(id) {
    $.ajax({
        url: "./?task=ajax_action&action=accept_friendship&user_id=" + id,
        cache: false,
        dataType: "json",
        success: function (data) {
            const Status = data.status;

            if (Status == 1) {
                // $('#friends_button').html('<button class="btn btn-danger" id="accept_friendship" >${BUTTON_TOP_REMOVE_FRIEND}</button>');
                location.reload();
            }
        }
    });
    return false;
}

/*REMOVE FRIEND*/
function remove_friend(id) {
    $.ajax({
        url: "./?task=ajax_action&action=remove_friend&user_id=" + id,
        cache: false,
        dataType: "json",
        success: function (data) {
            if (data.result != '') {
                // $('#friends_button').html('<button class="btn btn-success" id="remove_friend" >${STR_ADD_AS_FRIEND}</button>');
                location.reload();
            }
        }
    });
    return false;
};

/*ADD FRIEND*/
function add_as_friend(id) {
    //alert(id)
    $.ajax({
        url: "./?task=ajax_action&action=add_as_friend&user_id=" + id,
        cache: false,
        dataType: "json",
        success: function (data) {
            const Status = data.status;

            if (Status == 0) {
                //$('#friends_button').html('<button class="btn btn-primary" id="add_as_friend">${STR_INVITATION_SENT}</button>');
                location.reload();
            }
        }
    });
    return false;
}

$(window).load(function () {

    const get = parseGetParams();


    if (get['photo']) {
        $('body').append('<div class="photo_big" data-num=' + get['photo'] + '></div>');
        $('.photo_big[data-num=' + get['photo'] + ']').click();
    }
    if (get['video']) {
        $('body').append('<div class="video_prev" data-num=' + get['video'] + '></div>');
        $('.video_prev[data-num=' + get['video'] + ']').click();
    }

})


$(document).ready(function () {


    $('.lupa span').click(function () {
        $('form[role=search]').submit();
    })


    /*
    $('.save_window_ok').removeClass('hiden');
    $('.save_window_ok').removeClass('hiden');
    setTimeout(function(){
        $('.save_window_ok').addClass('hiden');
        $('.save_window_ok').addClass('hiden');
    },1000);
    */
    /*REMOVE ALBUM*/
    $(document).on('click', '.remove_album', function () {
        const href = $(this).attr('href');
        $.confirm({
            'title': 'Подтверждение',
            'message': 'Вы действительно хотите удалить альбом?',
            'buttons': {
                'Да': {
                    'class': 'blue',
                    'action': function () {
                        $(location).attr('href', href);
                    }
                },
                'Нет': {
                    'class': 'gray',
                    'action': function () {
                    }
                }
            }
        });
        return false;
    })
    /*TITLE BUTTON*/
    let tooltip = true;
    $(document).on("mousemove", "[data-tooltip]", function (eventObject) {
        if (tooltip) {
            $data_tooltip = $(this).attr("data-tooltip");

            $("#tooltip").html($data_tooltip)
                .css({
                    "top": getPosition(eventObject).y + 10,
                    "left": getPosition(eventObject).x + 10
                })
                .fadeIn();
            tooltip = false;
        }


    })
    $(document).on("mouseout", "[data-tooltip]", function () {
        if (!tooltip) {
            $("#tooltip").hide()
                .text("")
                .css({
                    "top": 0,
                    "left": 0
                });
            tooltip = true;
        }
    });

    let liked = true;
    $(document).on("mousemove", ".liked", function (eventObject) {
        if (liked) {
            $data_tooltip = 'Мне нравится';

            $("#tooltip").html($data_tooltip)
                .css({
                    "top": getPosition(eventObject).y + 10,
                    "left": getPosition(eventObject).x + 10
                })
                .fadeIn();
            liked = false;
        }


    })
    $(document).on("mouseout", ".liked", function () {
        if (!liked) {
            $("#tooltip").hide()
                .text("")
                .css({
                    "top": 0,
                    "left": 0
                });
            liked = true;
        }
    });
    let tell = true;
    $(document).on("mousemove", ".tell", function (eventObject) {
        if (tell) {
            $data_tooltip = 'Поделиться';

            $("#tooltip").html($data_tooltip)
                .css({
                    "top": getPosition(eventObject).y + 10,
                    "left": getPosition(eventObject).x + 10
                })
                .fadeIn();
            tell = false;
        }


    })
    $(document).on("mouseout", ".tell", function () {
        if (!tell) {
            $("#tooltip").hide()
                .text("")
                .css({
                    "top": 0,
                    "left": 0
                });
            tell = true;
        }
    });
    /*LIKE CONTENT*/
    $(document).on("click", ".liked", function () {
        const IdComment = $(this).attr('data-item');
        let type = $(this).attr('data-type');
        if (type == '') type = 'comment';
        $.ajax({
            url: "./?task=ajax_action&action=liked&id=" + IdComment + "&likeable_type=" + type,
            cache: false,
            dataType: "json",
            success: function (data) {
                //console.log(data);
                const Result = data.result;

                if (Result != '') {
                    //console.log(IdComment);
                    $('.liked[data-type=' + type + '][data-item=' + IdComment + ']').text(Result);
                }
            }
        });
    });


    /*SHARE CONTENT*/


    $(document).on("click", ".tell", function () {
        const $this = $(this);
        const IdComment = $(this).attr('data-item');
        let type = $(this).attr('data-type');
        if (type == '') type = 'comment';
        $.ajax({
            url: "./?task=ajax_action&action=shared&id=" + IdComment + "&shareable_type=" + type,
            cache: false,
            dataType: "json",
            success: function (data) {
                const Result = data.result;
                if (Result != '') {
                    $('.tell[data-type=' + type + '][data-item=' + IdComment + ']').text(Result);
                    $('body').append('<div id="ok_com_fr" class="save_window_ok hiden">Запись появится в новостях у Ваших друзей!</div>');
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
            }
        });
    });

    /*REMOVE COMMENT*/
    $(document).on('click', '.del_mess', function () {
        const id = $(this).attr('data-item');
        $.confirm({
            'title': 'Подтверждение',
            'message': 'Вы действительно хотите удалить?',
            'buttons': {
                'Да': {
                    'class': 'blue',
                    'action': function () {
                        $.ajax({
                            type: 'POST',
                            url: '/?task=ajax_action&action=removecomment',
                            data: 'id_comment=' + id,
                            success: function (msg) {
                                //console.log(msg);
                                if (msg.result == 'success') {
                                    $('#message-' + id).remove();
                                }
                            }
                        });
                    }
                },
                'Нет': {
                    'class': 'gray',
                    'action': function () {
                    }
                }
            }
        });


    })

    /*REMOVE MESSAGE*/
    $(document).on('click', '.del-message', function () {
        const id = $(this).attr('data-item');
        $.confirm({
            'title': 'Подтверждение',
            'message': 'Вы действительно хотите удалить?',
            'buttons': {
                'Да': {
                    'class': 'blue',
                    'action': function () {
                        $.ajax({
                            type: 'POST',
                            url: '/?task=ajax_action&action=remove_message',
                            data: 'id=' + id,
                            success: function (msg) {
                                console.log(msg);
                                if (msg.result == 'success') {
                                    $('#message-' + id).remove();
                                }
                            }
                        });
                    }
                },
                'Нет': {
                    'class': 'gray',
                    'action': function () {
                    }
                }
            }
        });


    })

    /*SUBMIT COMMENT FORM*/
    $(document).on('submit', '#addCommentForm', function () {
        const then = $(this);
        const comment = then.find('#comment').val();
        const files = then.find('.attach');
        const type = then.find('input[name=commentable_type]').val();
        const attach = [];
        files.each(function () {
            attach.push($(this).attr('data-id'));
        })
        if (comment != '' || attach.length != 0) {
            $('span.error').remove();
            $('.files_block').append('<div class="loading-mess"><img border="0" src="./templates/images/select2-spinner.gif" width=20px></div>');
            $('.files_block').html('');
            const formData = then.serializeArray();
            if (attach.length != 0)
                formData.push({'name': 'attach', 'value': attach});
            console.log(formData);

            $.ajax({
                url: './?task=ajax_action&action=addcomment',
                data: formData,
                type: 'POST',
                success: function (data) {

                    $('textarea').css('height', '38px');
                    then[0].reset();

                    if (data.status) {
                        $(data.html).hide().insertAfter('#addCommentContainers[data-type=' + type + ']').slideDown();
                        $('#comment').val('');
                        $('.message-text').emotions();
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


    /*SUBMIT REPLY FORM*/
    $(document).on("click", "#send-reply", function () {
        const IdComment = $(this).attr('data-item');
        const then = $(this).parent('form');
        const files = then.find('.attach');
        const comment = then.find('#comment').val();
        const attach = [];
        files.each(function () {
            attach.push($(this).attr('data-id'));
        })
        if (comment != '' || attach.length != 0) {
            $('#my-comment-' + IdComment).append('<div class="loading-mess"><img border="0" src="./templates/images/select2-spinner.gif" width=20px></div>');
            const formData = then.serializeArray();
            formData.push({'name': 'attach', 'value': attach});
            $.ajax({
                type: 'POST',
                url: './?task=ajax_action&action=addcomment',
                data: formData,
                success: function (data) {
                    //console.log(data);
                    then[0].reset();
                    if (data.status) {
                        $('#my-comment-' + IdComment).remove();
                        $(data.html).hide().insertAfter('#message-' + IdComment).slideDown();
                        $('#comment').val('');
                        $('.reply').show();
                    } else {
                        $.each(data.errors, function (k, v) {
                            $('label[for=' + k + ']').append('<span class="error">' + v + '</span>');
                        });
                    }
                }
            });

        }
        return false;
    });


    /*CHECKBOX*/
    $(document).on('click', '.checkbox', function () {
        const checkbox = $(this).children('input[type=checkbox]');
        const a = checkbox.prop('checked');
        if (a) {
            checkbox.prop("checked", false)
        } else {
            checkbox.prop("checked", true)
        }
        //console.log(a);
        return false;
    })


    /*$('input[type=text]').keyup(function (event) {
        if (event.ctrlKey && event.keyCode == 86) {
            const val = $(this).val();
            console.log(event);

        }
    });*/


    /*VALIDATE CREATE FORM*/
    $(document).on('submit', '.create_form', function () {
        const form = $(this).serializeArray();
        let error = false;
        for (let i = 0; i < form.length; i++) {
            if ((form[i].name == 'name' ||
                form[i].name == 'about' ||
                form[i].name == 'description' ||
                //form[i].name == 'id_place'||
                form[i].name == 'place' ||
                //form[i].name == 'id_sport'||
                form[i].name == 'sport' ||
                form[i].name == 'sport_type' ||
                form[i].name == 'address' ||
                form[i].name == 'phone' ||
                form[i].name == 'email' ||
                form[i].name == 'website' ||
                form[i].name == 'event_date_from') && form[i].value == '') {
                $('input[name=' + form[i].name + ']').addClass('error');
                $('textarea[name=' + form[i].name + ']').addClass('error');
                $('.error_label[name=' + form[i].name + ']').fadeIn();
                error = true;
            }
        }
        if (!error) {
            $('.create_form').submit();
            console.log('form submit');
        } else {
            setTimeout(function () {
                $('input').removeClass('error');
                $('textarea').removeClass('error');
                $('.error_label').fadeOut();
            }, 3000)
            console.log('error');
        }
        return false;
    })


    $('.search form').submit(function () {
        const val = $('#main_search').val();
        if (val != '') {
            $(this).submit();
        }


        return false;
    })


    $(document).on('keyup', '.input_hastags', function (event) {
        const num = $(this).attr('data-num');
        const text = $(this).val();
        const array = [];
        const highlighted = text.replace(/#\S*\s/g, function (el) {

            array.push(el.substring(0, el.length - 1));
        });
        $('.hashtags[data-num=' + num + ']').html('');
        for (let i = 0; i < array.length; i++) {
            $('.hashtags[data-num=' + num + ']').append(' <a href="#">' + array[i] + '</a>');
        }

    });
    /*
    let link = true;

    $(document).on('keyup','.ahref_input',function(){
        const num = $(this).attr('data-num');
        const str = $(this).val();
        const reg = str.match(/(https?:\/\/)?(www\.)?([-а-яa-z0-9_\.]{2,}\.)(рф|[a-z]{2,6})((\/[-а-яa-z0-9_]{1,})?\/?([a-z0-9_-]{2,}\.[a-z]{2,6})?(\?[a-z0-9_]{2,}=[-0-9]{1,})?((\&[a-z0-9_]{2,}=[-0-9]{1,}){1,})?)/i );
        if (reg&&reg[0]>'')
        {
            if (link)
            {
                console.log('ссылка найдена - '+reg[0]);
                $('.link_attach').html('');
                $('.link_attach').addClass('load').removeClass('show');
                link = false;
                $.ajax({
                    type:'POST',
                    url:'./?task=ajax_action&action=get_parsing',
                    data: 'str='+reg[0],
                    success:function(data){
                        if (data.status==1)
                        {
                            console.log(data.title);
                            console.log(data.description);
                            console.log(data.img);
                            $('.link_attach').html('<p class="a">Ссылка: <a href="'+reg[0]+'">'+reg[0]+'</a></p><img src="'+data.img+'"/><h5>'+data.title+'</h5><p>'+data.description+'</p><div class="del_link" data-tooltip="Не прикреплять"></div>')
                            $('.link_attach').addClass('show');
                        }
                    },
                    error:function(data){
                        $('.link_attach').removeClass('load').removeClass('show');
                        link=true;
                    }
                })
            }

        }

    })

    $(document).on('click','.del_link',function(){
        $(this).parent('.link_attach').removeClass('show').removeClass('load').html('');
        link=true;
    })*/
    $(document).on('click', '.back_one', function () {
        $('#photo_big').hide();
        $('#video_big').hide();
        $('body,html').css('overflow', 'auto');
        delParams('photo');
        delParams('video');
        return false;
    })


    $('.age').keydown(function (event) {
        if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 ||
            // Разрешаем: Ctrl+A
            (event.keyCode == 65 && event.ctrlKey === true) ||
            // Разрешаем: home, end, влево, вправо
            (event.keyCode >= 35 && event.keyCode <= 39)) {
            // Ничего не делаем
            return;
        } else {
            // Обеждаемся, что это цифра, и останавливаем событие keypress
            if ((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105)) {
                event.preventDefault();
            }
        }
    });
})

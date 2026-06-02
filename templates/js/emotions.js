const smile_string = ":-) :-( ;-) :-p :-* :-! :`( :-/ 8-) :ireful: :rofl: :banned: :nhl: :fan: :warning: :training: :aikido: :rotest:";
const smile_string_static = ":-) :-( ;-) :-p :-* 8-)";

function readFile(file, num, a) {
    const FR = new FileReader();
    FR.onload = function (e) {
        $('.files_block[data-num=' + num + ']').prepend('<div class="attach" data-num=' + a + '><img src="' + e.target.result + '" alt="" ><div class="percent"><p>0%</p></div></div>');
    };
    FR.readAsDataURL(file);
}


$(document).ready(function () {

    const smiles = $(".smilesChoose");
    const inputEl = $("#comment");
    const smilesBtn = $("#smilesBtn");
    const messages = $("div.chat-messages");
    const static_smile = $('.static_smile');


    static_smile.html(smile_string_static);
    static_smile.emotions();
    static_smile.toggle();

    $('div.chat-message').emotions();

    $('.message-text').each(function () {
        $(this).emotions();
    })
    $('.message-reply-text').each(function () {
        $(this).emotions();
    })
    $('.href').each(function () {
        $(this).emotions();
    })
    $('.article').each(function () {
        $(this).emotions();
    })

    $(document).on("click", ".smilesChoose span", function () {
        const num = $(this).parent('div').attr('data-num');
        const shortCode = $.emotions.shortcode($(this).attr("title"));
        $("#comment[data-num='" + num + "']").val($("#comment[data-num='" + num + "']").val() + " " + shortCode + " ");
        $("#message").val($("#message").val() + " " + shortCode + " ");

        //  $(".smilesChoose[data-num='"+num+"']").toggle();
        $("#comment[data-num='" + num + "']").focus();
        $("#message").focus();
    });


    let enter = 0;
    let setT;


    $(document).on("mouseover", ".smilesBtn", function () {
        if (enter == 0) {
            const num = $(this).attr('data-num');
            $(".smilesChoose[data-num='" + num + "']").html(smile_string);
            $(".smilesChoose[data-num='" + num + "']").emotions();
            $(".smilesChoose[data-num='" + num + "']").fadeIn();
            enter = 1;
        }


    });

    $(document).on("mouseleave", ".smilesBtn", function () {
        const num = $(this).attr('data-num');
        setT = setTimeout(function () {
            if (enter == 1) {

                $(".smilesChoose[data-num='" + num + "']").html(smile_string);
                $(".smilesChoose[data-num='" + num + "']").emotions();
                $(".smilesChoose[data-num='" + num + "']").fadeOut();
                enter = 0;
            }
        }, 500);

    });

    $(document).on("mouseover", ".smilesChoose", function () {
        clearTimeout(setT);
    });
    $(document).on("mouseleave", ".smilesChoose", function () {
        const num = $(this).attr('data-num');
        setT = setTimeout(function () {
            if (enter == 1) {

                $(".smilesChoose[data-num='" + num + "']").html(smile_string);
                $(".smilesChoose[data-num='" + num + "']").emotions();
                $(".smilesChoose[data-num='" + num + "']").fadeOut();
                enter = 0;
            }
        }, 500);
    })


    $("#sendBtn").click(function () {
        processMessage();
    });


    $(document).on("click", ".files", function () {
        const num = $(this).attr('data-num');
        $('.file_name[data-num=' + num + ']').click();
        return false;
    })

    $(document).on('change', '.file_name', function () {
        const num = $(this).attr('data-num');
        let input = $(this).closest('form').find('textarea');
        if (input.length == 0) {
            input = $(this).closest('form').find('#comment')
        }
        mess = input.val();
        const then = $(this).closest('form');
        const form = then[0];
        const files = $(this)[0].files;
        const base64 = '';
        let id_album;
        let error = true;
        if (files.length > 5) {
            form.reset();
            input.val(mess);
            $('.save_window_fail').html('Максимальное количество файлов 5').removeClass('hiden');
            setTimeout(function () {
                $('.save_window_fail').addClass('hiden');
            }, 2000);
            return false;
        } else {
            if (files.length > 0) {


                $('.files_block[data-num=' + num + ']').html('<div style="clear:both"></div>');
                for (let a = 0; a < files.length; a++) {
                    //console.log(this.files);
                    //$('.files_block[data-num='+num+']').append('<p><img src="./templates/images/files.png" alt=""> '+this.files[a].name+' ('+this.files[a].size/1000+'kb)</p>');

                    const rFilter = /^(image\/jpeg|image\/png)$/i;
                    if (rFilter.test(files[a].type)) {
                        error = false;
                        readFile(files[a], num, a);
                        const window_h = $(window).height();
                        const mess_h = window_h - 410;
                        $('.mess_list').css('height', mess_h);
                        //readFile(files[a],num);
                        const formData = new FormData();
                        formData.append('photoalbumable_type', 'user_attach');
                        formData.append('file', files[a]);
                        formData.append('num', a);

                        $.ajax({
                            url: '/?task=ajax_action&action=add_photo_ajax_attach',
                            type: 'POST',
                            contentType: false,
                            processData: false,
                            data: formData,
                            dataType: 'json',
                            xhr: function () {
                                const xhr = $.ajaxSettings.xhr(); // получаем объект XMLHttpRequest
                                xhr.upload.addEventListener('progress', function (evt) { // добавляем обработчик события progress (onprogress)
                                    if (evt.lengthComputable) { // если известно количество байт
                                        // высчитываем процент загруженного
                                        const percentComplete = Math.ceil(evt.loaded / evt.total * 100);
                                        $('.attach').find('p').html(percentComplete + '%');
                                        // устанавливаем значение в атрибут value тега <progress>
                                        // и это же значение альтернативным текстом для браузеров, не поддерживающих <progress>
                                        console.log('Загружено ' + percentComplete + '%');
                                    }
                                }, false);
                                return xhr;
                            },
                            success: function (json) {
                                if (json) {
                                    console.log(json)
                                    $('.attach[data-num=' + json.num + ']').attr('data-id', json.message.id).find('.percent').remove();

                                }
                            }
                        });

                    } else {
                        form.reset();
                        input.val(mess);
                        $('.save_window_fail').html('Неверный формат файла').removeClass('hiden');
                        setTimeout(function () {
                            $('.save_window_fail').addClass('hiden');
                        }, 2000);
                    }


                }
                if (!error)
                    $('.files_block[data-num=' + num + ']').prepend('<p><a href="#" class="removeAttach">Удалить прикрепленные файлы</a></p>')
            }
        }
        //console.log(files.length);
    })


    $(document).on('click', '.removeAttach', function () {
        let input = $(this).closest('form').find('textarea');
        if (input.length == 0) {
            input = $(this).closest('form').find('#comment')
        }
        mess = input.val();
        const then = $(this).closest('form');
        const form = then[0];
        form.reset();
        input.val(mess);
        $(this).closest('.files_block').html('');
        const window_h = $(window).height();
        const mess_h = window_h - 310;
        $('.mess_list').css('height', mess_h);
        return false;
    })


    $(document).mouseup(function (e) { // событие клика по веб-документу
        const div = $('.smilesChoose'); // тут указываем ID элемента
        if (!div.is(e.target) // если клик был не по нашему блоку
            && div.has(e.target).length === 0) {
            div.hide();

        }
    });


    document.onkeyup = function (e) {
        if ($('#message').is(":focus")) {
            e = e || window.event;
            if (e.keyCode === 13) {
                $('#addMessageForm').submit();
            }
        }
    }


});
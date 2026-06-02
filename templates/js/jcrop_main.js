$(function () {
    // Для примера 1
    $('#cropbox1').Jcrop({ // Привязываем плагин JСrop к изображению с id=cropbox1
        aspectRatio: 0,
        onChange: updateCoords,
        onSelect: updateCoords
    });

    // Для примера 2
    const api = $.Jcrop('#cropbox2', { // Привязываем плагин JСrop к изображению с id=cropbox2
        setSelect: [100, 100, 200, 200]
    });
    let i;

    // Обработчик, который прерывает действие
    function nothing(e) {
        e.stopPropagation();
        e.preventDefault();
        return false;
    }

    // Обработчик события для запуска анимации
    function anim_handler(ac) {
        return function (e) {
            api.animateTo(ac);
            return nothing(e);
        };
    }

    // Устанавливаем координаты областей для анимации
    const ac = {
        anim1: [0, 0, 40, 600],
        anim2: [115, 100, 210, 215],
        anim3: [80, 10, 760, 585],
        anim4: [105, 215, 665, 575],
        anim5: [495, 150, 570, 235]
    };

    // Привязываем соответствующий обработчик события
    for (i in ac) jQuery('#' + i).click(anim_handler(ac[i]));

    // Для примера 3
    $('#cropbox3').Jcrop({ // Привязываем плагин JСrop к изображению с id=cropbox3
        setSelect: [20, 130, 480, 230],
        addClass: 'jcrop_custom',
        bgColor: 'blue',
        bgOpacity: .5,
        sideHandles: false,
        minSize: [50, 50]
    });
});

function updateCoords(c) {
    $('#x').val(c.x);
    $('#y').val(c.y);
    $('#w').val(c.w);
    $('#h').val(c.h);

    $('#x2').val(c.x2);
    $('#y2').val(c.y2);


    const rx = 200 / c.w; // 200 - размер окна предварительного просмотра
    const ry = 200 / c.h;

    $('#preview').css({
        width: Math.round(rx * 800) + 'px',
        height: Math.round(ry * 600) + 'px',
        marginLeft: '-' + Math.round(rx * c.x) + 'px',
        marginTop: '-' + Math.round(ry * c.y) + 'px'
    });
};

jQuery(window).load(function () {
    $("#accordion").accordion({autoHeight: false, navigation: true});
});

function checkCoords() {
    if (parseInt($('#w').val())) return true;
    alert('Пожалуйста, выберите область для обрезки.');
    return false;
};

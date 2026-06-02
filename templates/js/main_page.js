if (screen.width > '768') {
    document.write('<div class="videoBox"><video class="video" autoplay="" loop="" muted=""><source src="video.mp4" type="video/mp4"></video></div>');
} else {
    document.write('<div class="videoBox"></div>');
}
$(document).ready(function () {
    $("#entrance-form").validate({
        rules: {
            username: {
                required: true,
            },

            password: {
                required: true,
            },
        },

        messages: {
            username: {
                required: "Это поле обязательно для заполнения",
            },

            password: {
                required: "Это поле обязательно для заполнения",
            },
        }
    });


});
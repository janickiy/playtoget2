//open nav menu
$(
    function () {
        $("body").addClass("closed-menu");
        $(".menu-icon").click(
            function () {
                $("body").toggleClass("opened-menu");
            }
        );
    }
);
//show or hidden all,more,less information of profile
$(
    function () {
        $("#information").addClass("hidden-achiv");
        $(".show-all-achiv").click(
            function () {
                $("#information").toggleClass("show-achiv");
            }
        );
    }
);
$(
    function () {
        $("#information").addClass("hidden-more-info");
        $(".minimax").click(
            function () {
                $("#information").toggleClass("show-more-info");
            }
        );
    }
);
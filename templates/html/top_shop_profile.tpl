<link rel="stylesheet" href="./templates/css/swiper.min.css">
<script>
$(document).ready(function(){
    let height_container = $('.text-container').height();
    let height_p = $('.text-container p').height();
    if (height_p<=height_container)
    {
        $('.read_next').hide();
    }

    $('.read_next').click(function(){
        let status = $(this).attr('data-status');
        if (status=='hide')
        {
            $('.text-container').css('height',height_p);
            $(this).html('Скрыть');
            $(this).attr('data-status','show');
        }
        else
        {
            $('.text-container').css('height','140px');
            $(this).html('Читать далее...');
            $(this).attr('data-status','hide');
        }
        
        return false;

    })
})
</script>
<div class='container_in_swiper'>
<div class="swiper-container gallery-top">
        <div class="swiper-wrapper">
        <!-- IF '${NO_IMAGES}' != '' -->
            <div class="swiper-slide" style="background-image:url(./templates/images/default_group.png)"></div>
        <!-- ELSE -->
            <!-- BEGIN row_big_images_list -->
            <div class="swiper-slide" style="background-image:url(${BIG_IMAGE})"></div>
            <!-- END row_big_images_list -->
        <!-- END IF -->
        </div>
        <!-- Add Arrows -->
        <div class="swiper-button-next swiper-button-white"></div>
        <div class="swiper-button-prev swiper-button-white"></div>
    </div>
    <div class="swiper-container gallery-thumbs">
        <div class="swiper-wrapper left220">
        <!-- IF '${NO_IMAGES}' != '' -->
            <div class="swiper-slide" style="background-image:url(./templates/images/default_group.png)"></div>
        <!-- ELSE -->
            <!-- BEGIN row_small_images_list -->
            <div class="swiper-slide" style="background-image:url(${SMALL_IMAGE})"></div>
            <!-- END row_small_images_list -->
        <!-- END IF -->
        </div>
    </div>
</div>

<div class='description_shop'>
	<div class='text'>
		<div class='text-container'>
            <p>${INFO_SPORT_BLOCK_ABOUT}</p>
		</div>
        <a href='#' class='read_next' data-status='hide'>Читать далее...</a>
	</div>
	<div class='contact'>
        <!-- IF '${SPORT_BLOCK_AVATAR}' !='' --><img src="${SPORT_BLOCK_AVATAR}" class='avatar_sport_block'></a><!-- END IF -->
        <!-- IF  '${ID_USER}'=='${ID_OWNER}' -->    
            <a class='button_edit_groups' href="./?task=${SPORT_BLOCK_TYPE}&id_sport_block=${ID_SPORT_BLOCK}&q=edit">${TOP_BUTTON_EDIT}</a>
    <!-- END IF -->
		<h3>${INFO_SPORT_BLOCK_NAME}</h3>
		<p class='adress'>${INFO_SPORT_BLOCK_ADDRESS}</p>
		<p class='phone'>${INFO_SPORT_BLOCK_PHONE}</p>
		<p class='mail'>${INFO_SPORT_BLOCK_EMAIL}</p>
		<p class='site'><a href='${INFO_SPORT_BLOCK_WEBSITE}' target='_blank'>${INFO_SPORT_BLOCK_WEBSITE}</a></p>


	</div>
</div>
<script src="./templates/js/swiper.js"></script>

<!-- Initialize Swiper -->
    <script>
    let galleryTop = new Swiper('.gallery-top', {
        nextButton: '.swiper-button-next',
        prevButton: '.swiper-button-prev',
        spaceBetween: 10,
        <!-- IF '${NO_IMAGES}' == '' -->
        loop:true,
        loopedSlides: 5, //looped slides should be the same*/
        <!-- END IF -->
        autoplay: 5000,
        autoplayDisableOnInteraction: false     
    });
    let galleryThumbs = new Swiper('.gallery-thumbs', {
        spaceBetween: 10,
        slidesPerView: 4,
        touchRatio: 0.2,
        <!-- IF '${NO_IMAGES}' == '' -->
        loop:true,
        loopedSlides: 5, //looped slides should be the same*/
        <!-- END IF -->
        slideToClickedSlide: true,
        autoplay: 5000,
        autoplayDisableOnInteraction: false
    });
    galleryTop.params.control = galleryThumbs;
    galleryThumbs.params.control = galleryTop;
    
    </script>
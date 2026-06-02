<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>

<div id="map"></div>

<script type="text/javascript">
    ymaps.ready(init);
    let myMap,
        myPlacemark,
        coord;
    <!-- IF '${INFO_SPORT_BLOCK_ADDRESS}' != '' --> 
    	let adress = '${INFO_SPORT_BLOCK_ADDRESS}';
    <!-- ELSE IF '${EVENT_ADDRESS}' != '' --> 
    	let adress = '${EVENT_ADDRESS}';
    <!-- ELSE IF '${CITY}' != '' --> 
    	let adress = '${CITY}';
    <!-- ELSE -->
    	let adress = 'Москва';
    <!-- END IF -->
    function init(){     

        let myGeocoder = ymaps.geocode(adress);
			myGeocoder.then(
			    function (res) {
			    	coord = res.geoObjects.get(0).geometry.getCoordinates();
			    	myMap = new ymaps.Map("map", {
			            center: coord,
			            zoom: 15,
			            controls: []
			        });
					myPlacemark = new ymaps.Placemark(coord, {
						balloonContent: '${INFO_SPORT_BLOCK_NAME}<p>'+adress+'</p>',
					}, {

				        iconLayout: 'default#image',
				        iconImageHref: './templates/images/point_map.png',
				        iconImageSize: [32, 38],
					    balloonCloseButton: false,
					    hideIconOnBalloonOpen: false
				    });
			        myMap.geoObjects.add(myPlacemark);
			    },
			    function (err) {
			        // обработка ошибки
			        console.log(err);
			    }
			);

    }
</script>
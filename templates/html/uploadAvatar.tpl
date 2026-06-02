<script src="./templates/js/jquery.Jcrop.min.js"></script>
<link rel="stylesheet" href="./templates/css/jquery.Jcrop.css" type="text/css" />
<script>

$(document).ready(function(){

	$('#target, #target-cover').each(function(){
		const src = $(this).data('src');
		if(src) $(this).attr('src', src);
	});

	let width;
	let height;
let new_width;
let k;


$(document).on('click', '.overlay_ava', function(e){
		let div = $("#avatarUpload"); 
		let div2 = $('#coverUpload');
			if (!div.is(e.target) && !div2.is(e.target) 
			    && div.has(e.target).length === 0 && div2.has(e.target).length === 0) { 
				if (jcrop_api)
					jcrop_api.destroy();
							$('#avatarUpload').removeClass('show');
							$('#coverUpload').removeClass('show');
							$('.avatarUpload').fadeOut();
							$('.overlay_ava').fadeOut();
			        		$('body').css('overflow','auto');
			    		 }

		
		
		
	})

/*AVATAR*/
let jcrop_api;
		function setCroppedFile(fileSelector, previewSelector, hiddenSelector) {
			const file = $(fileSelector).val();
			if (!file) return false;
			$(previewSelector).attr('src', file + "?" + Math.random());
			$(hiddenSelector).val(file);
			$('.overlay_ava').click();
			$('body').css('overflow','auto');
			return true;
		}

	    function showCoords(c)
		  {
      new_width = parseInt($('.jcrop-holder').css('width'));
      k = width/new_width;
	      // variables can be accessed here as
	      //console.log(width+' '+new_width+' '+k+' '+c.w+' '+c.w*k)
	      	$('#x').val(c.x);
        	$('#y').val(c.y);
        	$('#w').val(c.w);
        	$('#h').val(c.h); 
	  };
	$('input[name=avatar]').change(function(){
    let oFile = $(this)[0].files[0];


    let reader = new FileReader();
	    reader.onload = function(e) {
	      let img = document.createElement('img');
	      img.onload = function() {
	        width = this.width;
	        height = this.height;
      };
      img.src = e.target.result;
    }
    reader.readAsDataURL(this.files[0]);



    let rFilter = /^(image\/jpeg|image\/png)$/i;
    if (! rFilter.test(oFile.type)) {
        $('#avatarUpload .loading-bar').html('Неверный формат файла!');
        $('#avatarUpload .loading-bar').fadeIn();
        return;
    }
    /*if (oFile.size > 250 * 1024) {
        $('#avatarUpload .loading-bar').html('Файл слишком большой!');
        $('#avatarUpload .loading-bar').fadeIn();
        return;
    }*/

		$('#uploadAva').submit();
		$('#avatarUpload .loading-bar').html('<img border="0" src="./templates/images/select2-spinner.gif" width=20px>');
		$('#avatarUpload .loading-bar').fadeIn();


	});


		let options = 
			{  
			    success:function(data) { 
					const file = String(data).trim();
					$('.ff').val(file);
					$('#target').attr('src', file);
	        		$('#target').css('height','auto');
	        		$('#target').css('width','auto');
				$('#avatarUpload').addClass('show');
				$('#avatarUpload .loading-bar').fadeOut();

    


            //console.log(width+' '+new_width+' / '+height+' '+new_height);
				if (jcrop_api)
					jcrop_api.destroy();
        		$('#target').Jcrop({
		            onSelect:    showCoords,
		            bgColor:     'black',
		            bgOpacity:   .4,
		            aspectRatio: 1 / 1
			        },function(){
					    jcrop_api = this;
					  });


        
			    },
			    error:function(data){
					$('#avatarUpload .loading-bar').html('Ошибка загрузки!');
				}
		};
		$('#uploadAva').ajaxForm(options);  

	function openAvatarUpload(){
		$('#avatarUpload').fadeIn();
		$('#ov_ava').fadeIn();
		$('body').css('overflow','hidden');
		$('#uploadAva')[0].reset();	
	}

	$(document).on('click', '#avatar', function(){
		if($(this).is('input[type=file]')) return;
		openAvatarUpload();
	})

	$(document).on('click', '.editable-avatar', function(){
		openAvatarUpload();
	})

	

	$(document).on('click','.saveAva',function(){
		let width = parseInt($('#w').val());
		let height = parseInt($('#h').val());
		if (width<100 || height<100)
		{
			$('body').append('<div id="ok_com_fr" class="save_window_fail hiden">Выделенная область слишком мала!</div>');
  				setTimeout(function(){
  				$('#ok_com_fr').removeClass('hiden');
	  			},100);
	  			setTimeout(function(){
	  				$('#ok_com_fr').addClass('hiden');
	  			},1100)
	  			setTimeout(function(){
	  				$('#ok_com_fr').remove();
	  			},1500)
			return false;
		}
			
	})
	$(document).on('submit','#crop_ava',function(){
		  let $that = $(this),
		    formData = $that.serializeArray(); 
		    console.log(formData);
		    $.ajax({
			    url: $that.attr('action'),
			    type: $that.attr('method'),
			    data: formData,
			    dataType: 'json',
			    success: function(msg){
			      console.log(msg);
			    	if (!msg || msg.result !== 'success' || !setCroppedFile('#file_ava', '#preview_ava', '#file_ava_src')) {
						$('#avatarUpload .loading-bar').html('Ошибка обработки изображения!');
						$('#avatarUpload .loading-bar').fadeIn();
					}
			    },
			    error: function(){
					$('#avatarUpload .loading-bar').html('Ошибка обработки изображения!');
					$('#avatarUpload .loading-bar').fadeIn();
			    }
			    });
		  return false;
		})


/*COVER*/
function showCoordsCover(c)
	  {

      new_width = parseInt($('.jcrop-holder').css('width'));
      k = width/new_width;
	      // variables can be accessed here as
	      //console.log( c.x+' '+c.y+' '+c.x2+' '+c.y2+' '+c.w+' '+c.h)
	      	$('#x-cover').val(c.x);
        	$('#y-cover').val(c.y);
        	$('#w-cover').val(c.w);
        	$('#h-cover').val(c.h); 
	  };

		$('input[name=cover]').change(function(){
      let oFile = $(this)[0].files[0];


      let reader = new FileReader();
      reader.onload = function(e) {
        let img = document.createElement('img');
        img.onload = function() {
          width = this.width;
          height = this.height;
        };
        img.src = e.target.result;
      }
      reader.readAsDataURL(this.files[0]);



      let rFilter = /^(image\/jpeg|image\/png)$/i;
      if (! rFilter.test(oFile.type)) {
          $('#avatarUpload .loading-bar').html('Неверный формат файла!');
          $('#avatarUpload .loading-bar').fadeIn();
          return;
      }

			$('#uploadCover').submit();
			$('#coverUpload .loading-bar').html('<img border="0" src="./templates/images/select2-spinner.gif" width=20px>');
			$('#coverUpload .loading-bar').fadeIn();
		});

			let optionsCover = {  
			    success:function(data) { 
					const file = String(data).trim();
					$('.ffcover').val(file);
					$('#target-cover').attr('src', file);
	        		$('#target-cover').css('height','auto');
	        		$('#target-cover').css('width','auto');
				$('#coverUpload').addClass('show');
				$('#coverUpload .loading-bar').fadeOut();
				if (jcrop_api)
					jcrop_api.destroy();
        		$('#target-cover').Jcrop({
		            onSelect:    showCoordsCover,
		            bgColor:     'black',
		            bgOpacity:   .4,
		           // setSelect:   [ 100, 100, 50, 50 ],
		            aspectRatio: 3 / 1
			        },function(){
					    jcrop_api = this;
					  });
			    },
			    error:function(data){
				$('#coverUpload .loading-bar').html('Ошибка загрузки!');
			    }
		};
		$('#uploadCover').ajaxForm(optionsCover);

		function openCoverUpload(){
			$('#coverUpload').fadeIn();
			$('#ov_cover').fadeIn();
			$('body').css('overflow','hidden');
			$('#uploadCover')[0].reset();	
		}

		$(document).on('click', '#cover', function(){
			if($(this).is('input[type=file]')) return;
			openCoverUpload();
		})

		$(document).on('click','.upload_cover_img, .editable-cover, .editable-cover-area',function(){
			openCoverUpload();
		})

		$(document).on('click','.saveCover',function(){
			let width = parseInt($('#w-cover').val());
			let height = parseInt($('#h-cover').val());
			if (width<100 || height<100)
			{
				$('body').append('<div id="ok_com_fr" class="save_window_fail hiden">Выделенная область слишком мала!</div>');
	  				setTimeout(function(){
	  				$('#ok_com_fr').removeClass('hiden');
		  			},100);
		  			setTimeout(function(){
		  				$('#ok_com_fr').addClass('hiden');
		  			},1100)
		  			setTimeout(function(){
		  				$('#ok_com_fr').remove();
		  			},1500)
				return false;
			}
				
		})
		$(document).on('submit','#crop_cover',function(){
			  let $that = $(this),
			    formData = $that.serializeArray(); 
			    $.ajax({
				    url: $that.attr('action'),
				    type: $that.attr('method'),
				    data: formData,
				    dataType: 'json',
				    success: function(msg){
				      console.log(msg);
				    	if (!msg || msg.result !== 'success' || !setCroppedFile('#file_cover', '#preview_cover', '#file_cover_src')) {
							$('#coverUpload .loading-bar').html('Ошибка обработки изображения!');
							$('#coverUpload .loading-bar').fadeIn();
						}
				    },
				    error: function(){
						$('#coverUpload .loading-bar').html('Ошибка обработки изображения!');
						$('#coverUpload .loading-bar').fadeIn();
				    }
				    });
			  return false;
			})
})

</script>
<div class='overlay_ava' id='ov_ava'>
<div class="avatarUpload" id="avatarUpload" data-type='avatar'>

  <div class="container">
    <div class="row">
      <div class="span12">
        <div class="jc-demo-box">

          <div class="page-header">
            <h3>Загрузка аватара</h3>
          </div>
            <div class="loading-bar"><img border="0" src="./templates/images/select2-spinner.gif" width=20px></div>

          <p class='text-show'>Выберите область, которую хотите использовать</p>

            <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==" data-src="${TEAM_AVATAR}" id="target" alt=""/>

          <form autocomplete="off" action="/?task=ajax_action&action=uploadavatar" method="post" id="uploadAva" enctype="multipart/form-data">
            <div class="file_upload2">
              <button type="button" id='load'>Выберите файл</button>
               <input type="file" name="avatar" id='avatar'>
            </div>
            
          </form>
          
          <form id='crop_ava' autocomplete="off" action="./?task=ajax_action&action=crop" method="post" class="crop">
            <input type="hidden" id="x" name="x" value='0'/>
            <input type="hidden" id="y" name="y" value='0' />
            <input type="hidden" id="w" name="w" value='0' />
            <input type="hidden" id="h" name="h" value='0' />
            <input type="submit" value="Сохранить" class="save-button saveAva"/>
            <input type="hidden" name="file" class="ff" id='file_ava'>
          </form>
          <div class="clearfix"></div>

        </div>
      </div>
    </div>
  </div>

</div>
</div>


<div class='overlay_ava' id='ov_cover'>
<div class="avatarUpload" id="coverUpload" data-type='cover'>

  <div class="container">
    <div class="row">
      <div class="span12">
        <div class="jc-demo-box">

          <div class="page-header">
            <h3>Загрузка Обложки</h3>
          </div> 
          <div class="loading-bar"><img border="0" src="./templates/images/select2-spinner.gif" width=20px></div>
          <p class='text-show'>Выберите область, которую хотите использовать</p>
            <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==" data-src="${TEAM_COVER_PAGE}" id="target-cover" alt=""/>
          <form action="/?task=ajax_action&action=uploadcover" method="post" id="uploadCover" enctype="multipart/form-data">
            <div class="file_upload2">
              <button type="button" id='load-cover'>Выберите файл</button>
               <input type="file" name="cover" id='cover'>
            </div>
            
          </form>
          
          <form id='crop_cover' action="./?task=ajax_action&action=cropcover" method="post" onsubmit="return checkCoords();" class="crop">
            <input type="hidden" id="x-cover" name="x" value='0' />
            <input type="hidden" id="y-cover" name="y" value='0' />
            <input type="hidden" id="w-cover" name="w" value='0' />
            <input type="hidden" id="h-cover" name="h" value='0' />
            <input type="submit" value="Сохранить" class="save-button saveCover"/>
            <input type="hidden" name="file" class="ffcover" id='file_cover'>
          </form>
          <div class="clearfix"></div>

        </div>
      </div>
    </div>
  </div>

</div>
</div>

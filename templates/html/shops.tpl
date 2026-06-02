<!-- IF '${OPEN_PAGE}' == '' -->
<!-- INCLUDE header.tpl -->
<script>
	window.avatar = '${TOP_AVATAR}';
	window.user_id = '${ID_USER}';
	window.placeholder = '${STR_YOUR_COMMENT}';
	window.error = '${STR_THERE_ARE_NO_MORE_ENTRIES}';
	window.init = '${STR_CLICK}';
</script>

<!-- ELSE -->
<!-- INCLUDE unauthorizedheader.tpl -->
<!-- END IF -->
<script type="text/javascript" src="./templates/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="./templates/js/jquery.form.min.js"></script>
<script type="text/javascript" src="./templates/js/script_all.js"></script>
<script type="text/javascript">

$(function() {
	let wrapper = $( ".file_upload" ),
	inp = wrapper.find( "input" ),
	btn = wrapper.find( "button" ),
	lbl = wrapper.find( "div" );
	btn.focus(function(){
		inp.focus()
    });
	
    // Crutches for the :focus style:
    inp.focus(function(){
		wrapper.addClass( "focus" );
    }).blur(function(){
        wrapper.removeClass( "focus" );
    });
});

</script>

<!-- INCLUDE uploadAvatar.tpl -->
<!-- INCLUDE block_photowindow.tpl -->
<section class="wrapper">
  <div class="container">
    <div class="row">
      <div class="col-xs-12  bg">
        <!-- INCLUDE left_sitebar.tpl -->
        <div class="content content-groups friends">
          <!-- IF '${ID_SPORT_BLOCK}' != '' -->
		  
		  <!-- IF '${BLOCK_PAGE}' == '' -->
		  
          <!-- IF '${QUERY}' == 'edit' -->
          <div class="photo-caption">
            <h3>${TITLE_PAGE}</h3>
          </div>

          <div class="job_form">
		   <!-- INCLUDE sport_blocks_form.tpl --> 
          </div>
          <!-- ELSE -->
        <!-- INCLUDE top_shop_profile.tpl --> 
		  
<!-- IF  '${ID_USER}'=='${ID_OWNER}' -->		  
<div id="filelist">${STR_YOUR_BROWSER_DOESNT_SUPPORT}</div>
<br />
<div class='job_form'>
<form class="form-horizontal">
  <div class="form-group">
    <div id="container" class='center_text marginTop20'> 
      <a id="pickfiles" href="javascript:;" class='save-button'>${BUTTON_ADD_FILES}</a> 
      <a id="uploadfiles" href="javascript:;" class='save-button'>${BUTTON_DOWNLOAD_FILES}</a> 
    </div>
  </div>
</form>
</div>

<script type="text/javascript" src="./templates/js/puupload/plupload.full.min.js"></script>
<script type="text/javascript">     

let uploader = new plupload.Uploader({
	runtimes : 'html5,flash,silverlight,html4',
	browse_button : 'pickfiles', // you can pass an id...
	container: document.getElementById('container'), // ... or DOM Element itself
	url : './?task=ajax_action&action=add_photo_ajax',
	flash_swf_url : './templates/js/puupload/Moxie.swf',
	silverlight_xap_url : './templates/js/puupload/Moxie.xap',
  
	filters : {
		max_file_size : '10mb',
		mime_types: [
		{title : "Image files", extensions : "jpg,gif,png,jpeg"},
		{title : "Zip files", extensions : "zip"}
		]
	},
	multipart_params : {
		categorie           : '${ID_SPORT_BLOCK_PHOTO_ALBUM}',
		photoalbumable_type : 'shop',
		description     : ''
	},
	init: {
		PostInit: function() {
			document.getElementById('filelist').innerHTML = '';

			document.getElementById('uploadfiles').onclick = function() {
				uploader.start();
				return false;
			};
		},
		BeforeUpload: function(up, file)
		{
			uploader.settings.multipart_params.description = $('#'+file.id).find('textarea').val();
		},
		FilesAdded: function(up, files) {
			plupload.each(files, function(file) {
			/*document.getElementById('filelist').innerHTML += '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>';*/
				let FR= new FileReader();
				FR.onload = function(e) {
					document.getElementById('filelist').innerHTML += '<div id="' + file.id + '" ><div class="attach big"><img src="'+e.target.result+'" alt="" ><b></b><span class="icons-hid"><i class="no_attach" data-tooltip="Не добавлять" data-num = '+file.id+'><img src="./templates/images/icon-krest.png" alt=""></i></span></div><textarea class="form-control comment_attach" placeholder="Комментарий к фото"></textarea></div><div style="clear:both"></div>';
				};       
				FR.readAsDataURL(file.getNative());
			});
		},
		removeFile: function(up,file){
			$('div[id='+file.id+']').remove();
		},
		UploadProgress: function(up, file) {
			if (document.getElementById(file.id))
			{
				document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
			}
		},

		Error: function(up, err) {
			document.getElementById('console').appendChild(document.createTextNode("\nError #" + err.code + ": " + err.message));
		}
	}
});

uploader.init();
uploader.bind('UploadComplete', function(up, files) {
	location.reload();
});
$(document).on('click','.no_attach',function(){
	let num = $(this).attr('data-num');
	$('div[id='+num+']').remove();
	uploader.removeFile(num);
})

</script>
<br><br>
<div  class="photo-container pop-photos">
<!-- BEGIN row_photos_list -->
    <!-- IF '${SMALL_IMAGE}' != '' -->
    <div class="hov" id="photo-block-${ID_PHOTO}"> 
    <a class='photo_big' title="${DESCRIPTION}" href="${BIG_IMAGE}" data-lightbox="roadtrip" data-num='${ID_PHOTO}'> <img src="${SMALL_IMAGE}" alt="">
      <div class="transparent"></div>
    </a> 
    <span class="icons-hid">
    <span class="icons-hid"><i id="my-video-${ID_PHOTO}" class="remove_pic" id="${ID_PHOTO}" data-item="${ID_PHOTO}" data-tooltip="${STR_REMOVE_PHOTO}"><img src="./templates/images/icon-krest.png" alt=""></i></span> 
    </span> 
    </div>
    <!-- END IF -->
    <!-- END row_photos_list -->

</div>
    <!-- END IF -->  

          <!-- END IF -->
		   <!-- ELSE -->
		   <br><br>
	      <div class="photo-caption">
			    <h3>${STR_PAGE_LOCKED}</h3>
	      </div>
		  <!-- END IF -->
          <!-- ELSE -->
          <!-- IF '${QUERY}' == 'create' -->
          <div class="photo-caption">
            <h3>${TITLE_PAGE}</h3>
          </div>
          <div class="job_form">		  
		  <!-- INCLUDE sport_blocks_form.tpl -->		  
          </div>
          <!-- ELSE -->
          <!-- INCLUDE sport_blocks_search_form.tpl -->
          <div class="clearfix"></div>
          <div class="photo-caption">
            <h3>${STR_SHOPS}</h3>
          </div>
          <div class="event-container">
          <!-- BEGIN row_my_id_sport_block -->
            <div class="event-item"> 
			  <!-- IF '${AVATAR}' !='' --><a href="./?task=shops&id_sport_block=${ID}" class="img"><img src="${AVATAR}"></a><!-- END IF -->
              <div class="teg">
                <p><a href="./?task=shops&id_sport_block=${ID}">${NAME}</a></p>
                <!-- IF '${PLACE}' != '' --><p>${PLACE}</p><!-- END IF -->
                <p>${ABOUT}</p>
                <!-- IF '${SHOW_EDTI_LINK}' == 'show' --><a href="./?task=shops&id_sport_block=${ID}&q=edit">${STR_EDIT}</a><!-- END IF -->
				</div>
			</div>
          <!-- END row_my_id_sport_block -->
         </div>
          <!-- END IF -->
          <!-- END IF -->
        </div>
        <!-- INCLUDE right_sitebar.tpl -->
      </div>
    </div>
  </div>
</section>
<script src="./templates/js/select2.min.js"></script>
<script src="./templates/js/bootstrap.min.js?v=20260603-1"></script>
<script src="./templates/js/common.js"></script>
<script src="./templates/js/jquery.uploadThumbs.js"></script>
<script>selectAction();</script>
<script src="./templates/js/search.js"></script>
<!-- INCLUDE footer.tpl -->

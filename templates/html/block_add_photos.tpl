<center><h2>${TITLE_PAGE}</h2></center>
<!-- IF '${MSG}' != '' -->
<div class="mutations-both">
  <p>${MSG}</p>
  <a class="delete">x</a> </div>
<!-- END IF -->
<br />
<div class='job_form'>
<form class="form-horizontal" id="photo-upload-form">
    <div class="form-group">
    	<label class="col-lg-3 control-label" for="photoalbum_id">Выберите альбом:</label>
            <div class="col-lg-7">
                <div class="styled-select styled-select-4">
				<!-- IF '${SHOW_CATEGORY_LIST}' == 'show' -->
				<select name="photoalbum_id">
				  <!-- BEGIN row_option_album -->
				  <option value="${ID}">${NAME}</option>
				  <!-- END row_option_album -->
				</select>
				<!-- END IF -->
				</div>
			</div>
		</div>
		<div class="form-group">
			<div id="photo-upload-actions" class='marginTop20 center_text' style="margin-bottom:30px"> 
				<a id="pickfiles" href="javascript:;" class='save-button'>Добавить файлы</a> 
				<a id="uploadfiles" href="javascript:;" class='save-button'>Загрузить файлы</a> 
				<input id="photo-files" type="file" accept="image/jpeg,image/png,image/gif" multiple style="display:none">
			</div>
		</div>
	</form>
</div>
<div id="photo-upload-status" class="photo-upload-status">Выберите одну или несколько фотографий.</div>
<div id="filelist" class="photo-upload-list"></div>
<script>selectAction();</script>
<br/>
<script type="text/javascript">
(function () {
	let queue = [];
	let uploadUrl = './?task=ajax_action&action=add_photo_ajax';
	let maxFileSize = 32 * 1024 * 1024;
	let allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
	let allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
	let $fileInput = $('#photo-files');
	let $fileList = $('#filelist');
	let $status = $('#photo-upload-status');
	let $uploadButton = $('#uploadfiles');
	let $pickButton = $('#pickfiles');

	function supportsHtml5Upload() {
		return window.File && window.FileReader && window.FormData && window.XMLHttpRequest;
	}

	function escapeHtml(value) {
		return String(value).replace(/[&<>"']/g, function (char) {
			return {
				'&': '&amp;',
				'<': '&lt;',
				'>': '&gt;',
				'"': '&quot;',
				"'": '&#039;'
			}[char];
		});
	}

	function formatSize(bytes) {
		if (bytes >= 1048576) {
			return (bytes / 1048576).toFixed(1) + ' МБ';
		}
		return Math.ceil(bytes / 1024) + ' КБ';
	}

	function fileExtension(name) {
		return name.split('.').pop().toLowerCase();
	}

	function isImage(file) {
		let extension = fileExtension(file.name);
		return allowedExtensions.indexOf(extension) !== -1 &&
			(!file.type || allowedTypes.indexOf(file.type) !== -1);
	}

	function setStatus(text, type) {
		$status
			.removeClass('photo-upload-status-error photo-upload-status-success')
			.addClass(type ? 'photo-upload-status-' + type : '')
			.text(text);
	}

	function setProgress(id, percent) {
		let safePercent = Math.max(0, Math.min(100, percent));
		let $item = $('#' + id);
		$item.find('.photo-upload-progress-bar').css('width', safePercent + '%');
		$item.find('.photo-upload-percent').text(safePercent + '%');
	}

	function removeFile(id) {
		queue = queue.filter(function (item) {
			return item.id !== id;
		});

		$('#' + id).remove();

		if (!queue.length) {
			setStatus('Выберите одну или несколько фотографий.');
		}
	}

	function renderFile(file) {
		let id = 'photo-upload-' + Date.now() + '-' + Math.floor(Math.random() * 100000);
		let hashId = Math.floor(Math.random() * 100000);
		let item = { id: id, file: file };
		queue.push(item);

		$fileList.append(
			'<div class="photo-upload-item" id="' + id + '">' +
				'<div class="attach big photo-upload-preview">' +
					'<img alt="">' +
					'<b><span class="photo-upload-percent">0%</span></b>' +
					'<span class="icons-hid"><i class="no_attach" data-tooltip="Не добавлять" data-num="' + id + '">' +
						'<img src="./templates/images/icon-krest.png" alt="">' +
					'</i></span>' +
				'</div>' +
				'<div class="photo-upload-meta">' +
					'<div class="photo-upload-name">' + escapeHtml(file.name) + ' <span>' + formatSize(file.size) + '</span></div>' +
					'<textarea class="form-control comment_attach input_hastags" placeholder="Комментарий к фото" data-num="' + hashId + '"></textarea>' +
					'<div class="hashtags" data-num="' + hashId + '"></div>' +
					'<div class="photo-upload-progress"><div class="photo-upload-progress-bar"></div></div>' +
					'<div class="photo-upload-message"></div>' +
				'</div>' +
				'<div style="clear:both"></div>' +
			'</div>'
		);

		let reader = new FileReader();
		reader.onload = function (event) {
			$('#' + id).find('.photo-upload-preview img').attr('src', event.target.result);
		};
		reader.readAsDataURL(file);
	}

	function addFiles(files) {
		let added = 0;

		$.each(files, function (_, file) {
			if (!isImage(file)) {
				setStatus('Файл "' + file.name + '" не добавлен: допустимы только JPG, PNG и GIF.', 'error');
				return;
			}

			if (file.size > maxFileSize) {
				setStatus('Файл "' + file.name + '" не добавлен: размер больше 32 МБ.', 'error');
				return;
			}

			renderFile(file);
			added++;
		});

		if (added) {
			setStatus('Файлов в очереди: ' + queue.length + '.');
		}
	}

	function uploadFile(item) {
		return new Promise(function (resolve, reject) {
			let formData = new FormData();
			let $item = $('#' + item.id);
			let xhr = new XMLHttpRequest();

			formData.append('file', item.file);
			formData.append('categorie', $('select[name=photoalbum_id]').val());
			formData.append('photoalbumable_type', '${PHOTOALBUMABLE_TYPE}');
			formData.append('description', $item.find('textarea').val());

			xhr.open('POST', uploadUrl, true);
			xhr.upload.onprogress = function (event) {
				if (event.lengthComputable) {
					setProgress(item.id, Math.round((event.loaded / event.total) * 100));
				}
			};

			xhr.onload = function () {
				let response;

				try {
					response = JSON.parse(xhr.responseText);
				}
				catch (error) {
					reject('Некорректный ответ сервера.');
					return;
				}

				if (xhr.status >= 200 && xhr.status < 300 && response.info && !response.error) {
					setProgress(item.id, 100);
					$item.addClass('photo-upload-item-success');
					$item.find('.photo-upload-message').text('Загружено');
					resolve(response);
				}
				else {
					reject(response.error || 'Ошибка загрузки.');
				}
			};

			xhr.onerror = function () {
				reject('Ошибка соединения с сервером.');
			};

			xhr.send(formData);
		});
	}

	function uploadQueue() {
		let albumId = $('select[name=photoalbum_id]').val();
		let current = 0;
		let failed = 0;

		if (!queue.length) {
			setStatus('Сначала выберите фотографии.', 'error');
			return;
		}

		if (!albumId) {
			setStatus('Выберите альбом для загрузки.', 'error');
			return;
		}

		$uploadButton.addClass('disabled').text('Загрузка...');
		$pickButton.addClass('disabled');
		setStatus('Загрузка файлов...');

		function next() {
			if (current >= queue.length) {
				if (failed) {
					$uploadButton.removeClass('disabled').text('Загрузить файлы');
					$pickButton.removeClass('disabled');
					setStatus('Загрузка завершена с ошибками. Не загружено: ' + failed + '.', 'error');
					return;
				}

				setStatus('Фотографии загружены.', 'success');
				window.location = '${REDIRECT_PHOTO_ALBUM}&id_album=' + albumId;
				return;
			}

			let item = queue[current++];
			uploadFile(item)
				.catch(function (message) {
					failed++;
					$('#' + item.id)
						.addClass('photo-upload-item-error')
						.find('.photo-upload-message')
						.text(message);
				})
				.then(next);
		}

		next();
	}

	if (!supportsHtml5Upload()) {
		setStatus('Ваш браузер не поддерживает HTML5-загрузку файлов.', 'error');
		$pickButton.addClass('disabled');
		$uploadButton.addClass('disabled');
		return;
	}

	$pickButton.on('click', function () {
		if (!$(this).hasClass('disabled')) {
			$fileInput.click();
		}
	});

	$uploadButton.on('click', function () {
		if (!$(this).hasClass('disabled')) {
			uploadQueue();
		}
	});

	$fileInput.on('change', function () {
		addFiles(this.files);
		this.value = '';
	});

	$(document).on('click', '.no_attach', function () {
		if (!$uploadButton.hasClass('disabled')) {
			removeFile($(this).attr('data-num'));
		}
	});
})();
</script>

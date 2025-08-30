/**
* @package ThemeXpert
* @author ThemeXpert http://www.themexpert.com
* @copyright Copyright (c) 2010 - 2016 ThemeXpert
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
* @credit: Helix Framework
*/
jQuery(function($) {

	$('.tx-image-field').each(function(index, el) {
		
		var $field = $(el);

		// Upload form
		$field.find('.btn-tx-image-upload').on('click', function(event) {
			event.preventDefault();
			$field.find('.tx-image-upload').click();
		});

		//Upload
		$field.find(".tx-image-upload").on('change', (function(e) {
			e.preventDefault();
			var $this = $(this);
			var file = $(this).prop('files')[0];

			var data = new FormData();
			data.append('option', 'com_ajax');
			data.append('plugin', 'txadmin');
			data.append('action', 'upload_image');
			data.append('imageonly', false);
			data.append('format', 'json');
			
			if (file.type.match(/image.*/)) {
				data.append('image', file);

				$.ajax({
					type: "POST",
					data:  data,
					contentType: false,
					cache: false,
					processData:false,
					beforeSend: function() {
						$this.prop('disabled', true);
						$field.find('.btn-tx-image-upload').attr('disabled', 'disabled');
						var loader = $('<div class="tx-image-item-loader"><i class="fa fa-circle-o-notch fa-spin"></i></div>');
						$field.find('.tx-image-upload-wrapper').html(loader)
					},
					success: function(response)
					{
						var data = $.parseJSON(response);

						if(data.status) {
							$field.find('.tx-image-upload-wrapper').empty().html(data.output);
						} else {
							$field.find('.tx-image-upload-wrapper').empty();
							alert(data.output);
						}

						var $image = $field.find('.tx-image-upload-wrapper').find('>img');

						if($image.length) {
							$field.find('.btn-tx-image-upload').addClass('hide');
							$field.find('.btn-tx-image-remove').removeClass('hide');
							$field.find('.form-field-tximage').val($image.data('src'));
						} else {
							$field.find('.btn-tx-image-upload').removeClass('hide');
							$field.find('.btn-tx-image-remove').addClass('hide');
							$field.find('.form-field-tximage').val('');
						}
						
		 				$this.val('');
		 				$this.prop('disabled', false);
		 				$field.find('.btn-tx-image-upload').removeAttr('disabled');

					},
					error: function() 
					{
						$field.find('.tx-image-upload-wrapper').empty();
						$this.val('');
					} 	        
				});
			}
	
			$this.val('');

		}));

	});

	// Delete Image
	$(document).on('click', '.btn-tx-image-remove', function(event) {

		event.preventDefault();

		var $this = $(this);
		var $parent = $this.closest('.tx-image-field');

		if (confirm("You are about to permanently delete this item. 'Cancel' to stop, 'OK' to delete.") == true) {
		    var request = {
				'option' : 'com_ajax',
				'plugin' : 'txadmin',
				'action' : 'remove_image',
				'src'	 : $parent.find('.tx-image-upload-wrapper').find('>img').data('src'),
				'format' : 'json'
			};

			$.ajax({
				type: "POST",
				data   : request,
				success: function(response)
				{
					var data = $.parseJSON(response);
					if(data.status) {
						$parent.find('.tx-image-upload-wrapper').empty();
						$parent.find('.btn-tx-image-upload').removeClass('hide');
						$parent.find('.btn-tx-image-remove').addClass('hide');
						$parent.find('.form-field-tximage').val('');

					} else {
						alert(data.output);
					}
				}
			});
		}
	});
    
});
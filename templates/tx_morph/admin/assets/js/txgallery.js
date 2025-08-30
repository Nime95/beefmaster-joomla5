/**
* @package ThemeXpert
* @author ThemeXpert http://www.themexpert.com
* @copyright Copyright (c) 2010 - 2016 ThemeXpert
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
* @credit: Helix Framework
*/
jQuery(function($) {

	$('.tx-gallery-field').each(function(index, el) {
		
		var $field = $(el);

		// Upload form
		$field.find('.btn-tx-gallery-item-upload').on('click', function(event) {
			event.preventDefault();
			$field.find('.tx-gallery-item-upload').click();
		});

		//Sortable
		$field.find('.tx-gallery-items').sortable({
			stop : function(event,ui){
				// Set Value
				var images = [];
				$.each($field.find('.tx-gallery-items').find('>li'), function( index, value ) {
					images.push( '"' + $(value).data('src') + '"' );
				});
				var output = '{"'+ $field.find('.form-field-txgallery').data('name') +'":['+ images +']}';
				$field.find('.form-field-txgallery').val(output);
			}
		});

		//Upload
		$field.find(".tx-gallery-item-upload").on('change', (function(e) {
			e.preventDefault();
			var $this = $(this);
			var file = $(this).prop('files')[0];

			var data = new FormData();
			data.append('option', 'com_ajax');
			data.append('plugin', 'txadmin');
			data.append('action', 'upload_image');
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
						$field.find('.btn-tx-gallery-item-upload').attr('disabled', 'disabled');
						var loader = $('<li class="tx-gallery-item-loader"><i class="fa fa-circle-o-notch fa-spin"></i></li>');
						$this.prev('.tx-gallery-items').append(loader)
					},
					success: function(response)
					{
						var data = $.parseJSON(response);

						if(data.status) {
							$field.find('.tx-gallery-item-loader').before(data.output);
						} else {
							alert(data.output);
						}
		 				
		 				$this.val('');
		 				$this.prev('.tx-gallery-items').find('.tx-gallery-item-loader').remove();
		 				$this.prop('disabled', false);
		 				$field.find('.btn-tx-gallery-item-upload').removeAttr('disabled');

		 				var images = [];
		 				$.each($field.find('.tx-gallery-items').find('>li'), function( index, value ) {
		 					images.push( '"' + $(value).data('src') + '"' );
		 				});
		 				var output = '{"'+ $field.find('.form-field-txgallery').data('name') +'":['+ images +']}';
		 				$('.form-field-txgallery').val(output);

					},
					error: function() 
					{
						$this.prev('.tx-gallery-items').find('.tx-gallery-item-loader').remove();
						$this.val('');
					} 	        
				});
			}
	
			$this.val('');

		}));

	});

	// Delete Image
	$(document).on('click', '.btn-remove-image', function(event) {

		event.preventDefault();

		var $this = $(this);

		if (confirm("You are about to permanently delete this item. 'Cancel' to stop, 'OK' to delete.") == true) {
		    var request = {
				'option' : 'com_ajax',
				'plugin' : 'txadmin',
				'action' : 'remove_image',
				'src'	 : $(this).parent().data('src'),	
				'format' : 'json'
			};

			$.ajax({
				type: "POST",
				data   : request,
				success: function(response)
				{
					var data = $.parseJSON(response);
					if(data.status) {
						$this.parent().remove();

						var images = [];
						$.each($('.tx-gallery-items').find('>li'), function( index, value ) {
							images.push( '"' + $(value).data('src') + '"' );
						});
						var output = '{"'+ $('.form-field-txgallery').data('name') +'":['+ images +']}';
						$('.form-field-txgallery').val(output);

					} else {
						alert(data.output);
					}
				}
			});
		}
	});
    
});
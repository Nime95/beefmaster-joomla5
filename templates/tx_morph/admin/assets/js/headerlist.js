/**
* @package ThemeXpert
* @author ThemeXpert http://www.themexpert.com
* @copyright Copyright (c) 2010 - 2016 ThemeXpert
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
* @credit: Helix Framework
*/

function getHeaderPreview(){
	jQuery("#header-variations-preview a").removeClass('active');
	var header = jQuery('#jform_params_header_variation').val();
	jQuery("#header-option-" + header + " a").addClass('active');

	return 0;
}

function setHeaderValue(e, val){
	e.preventDefault();

	jQuery("#header-variations-preview a").removeClass('active');
	jQuery("#header-option-" + val + " a").addClass('active');
	jQuery("#jform_params_header_variation").val(val);
}

jQuery(function($) {


	jQuery('#header-variations-preview').fadeOut('fast').append('<ul class="thumbnails"></ul>');
	$("#jform_params_header_variation option").each(function()
	{
		var html = '';
		html += '<li id="header-option-' + $(this).val() + '" class="grid-item">';
		html += '<a href="#" class="thumbnail" onclick="setHeaderValue(event, \''+ $(this).val() +'\');">';
		html += '<img src="'+ Joomla.getOptions("tx_admin").template_path + '/etc/headers/' + $(this).val() +'.png" />';
		html += '<span>' + $(this).val() + '</span>';
		html += '</a>';
		html += '</li>';
	    jQuery('#header-variations-preview .thumbnails').append(html);
	    // Add $(this).val() to your list
	});
    jQuery('#header-variations-preview').fadeIn();

	// trigger default call
    getHeaderPreview();
});

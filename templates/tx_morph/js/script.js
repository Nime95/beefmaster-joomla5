/**
 *------------------------------------------------------------------------------
 * @package       T3 Framework for Joomla!
 *------------------------------------------------------------------------------
 * @copyright     Copyright (C) 2004-2013 JoomlArt.com. All Rights Reserved.
 * @license       GNU General Public License version 2 or later; see LICENSE.txt
 * @authors       JoomlArt, JoomlaBamboo, (contribute to this project at github
 *                & Google group to become co-author)
 * @Google group: https://groups.google.com/forum/#!forum/t3fw
 * @Link:         http://t3-framework.org
 *------------------------------------------------------------------------------
 */

(function ($) {
    $(document).ready(function () {

        //Preloader
        $(window).on('load', function() {
          $('.preloader-box').fadeOut('slow', function () {
            $(this).remove();
          });
        });

        // Back to top
        $('#back-to-top').on('click', function () {
            $("html, body").animate({scrollTop: 0}, 500);
            return false;
        });

        // Lazyload + Matchheight combined together

        $('.lazy').Lazy({
          // Matchheight perform after image loaded
          afterLoad: function(element) {
            var qx_mh = $('.qx-mh');
            $.each(qx_mh, function(i, x) {
              var el = $(x);
              var group = el.parents('.qx-row:eq(1)').attr('id');
              el.attr('data-mh', group);
            });
            $('.qx-mh').matchHeight();
            $.fn.matchHeight._afterUpdate = function (event, groups) {
              $('.qx-mh-grid-loader').remove();
            };
          }
        });

        // Moment Js
        $('.qx-element-jarticle-date').each( function(){
          var t = $(this).find('time');
          var day = moment(t.attr('datetime'));
          t.html(day.fromNow());
        });

    });
})(jQuery);

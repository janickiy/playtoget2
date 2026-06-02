/*
 * jQuery autoResize (textarea auto-resizer)
 * @copyright James Padolsey http://james.padolsey.com
 * @version 1.04
 */

(function($){
    
    $.fn.autoResize = function(options) {
        
        // Just some abstracted details,
        // to make plugin users happy:
        const settings = $.extend({
            onResize : function(){
				 
			},
            animate : true,
            animateDuration : 150,
            animateCallback : function(){},
            extraSpace : 20,
            limit: 1000
        }, options);
        
        // Only textarea's auto-resize:
        this.filter('textarea').each(function(){
            // Get rid of scrollbars and disable WebKit resizing:
            const textarea = $(this).css({resize:'none','overflow-y':'hidden'});

            const // Cache original height, for use later:
            origHeight = textarea.height();

            const // Need clone of textarea, hidden off screen:
            clone = (function(){

                // Properties which may effect space taken up by chracters:
                const props = ['height','width','lineHeight','textDecoration','letterSpacing'], propOb = {};

                // Create object of styles to apply:
                $.each(props, function(i, prop){
                    propOb[prop] = textarea.css(prop);
                });
                
                // Clone the actual textarea removing unique properties
                // and insert before original textarea:
                return textarea.clone().removeAttr('id').removeAttr('name').css({
                    position: 'absolute',
                    top: 0,
                    left: -9999
                }).css(propOb).attr('tabIndex','-1').insertBefore(textarea);
                
            })();

            let lastScrollTop = null;

            const updateSize = function() {
                // Prepare the clone:
                clone.height(0).val($(this).val()).scrollTop(10000);
                
                // Find the height of text:
                const scrollTop = Math.max(clone.scrollTop(), origHeight) + settings.extraSpace, toChange = $(this).add(clone);
                    
                // Don't do anything if scrollTip hasen't changed:
                if (lastScrollTop === scrollTop) { return; }
                lastScrollTop = scrollTop;
                
                // Check for limit:
                if ( scrollTop >= settings.limit ) {
                    $(this).css('overflow-y','');
                    return;
                }
                // Fire off callback:
                settings.onResize.call(this);
                
                // Either animate or directly apply height:
               settings.animate && textarea.css('display') === 'block' ?
                    toChange.stop().animate({height:scrollTop}, settings.animateDuration, settings.animateCallback)
                    : toChange.height(scrollTop);
                    
                
            };

	            // Bind namespaced handlers to appropriate events:
	            textarea
	                .off('.dynSiz')
	                .on('keyup.dynSiz', updateSize)
	                .on('keydown.dynSiz', updateSize)
	                .on('change.dynSiz', updateSize);
        });
        
        // Chain:
        return this;
        
    };
    
    
    
})(jQuery);

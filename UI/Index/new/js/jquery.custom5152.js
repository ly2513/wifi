/*-----------------------------------------------------------------------------------

 	Custom JS - All front-end jQuery
 
-----------------------------------------------------------------------------------*/
 
jQuery(document).ready(function() {
	
	jQuery('body').addClass('jsenabled');
	
	var slideDuration = 600;
	
/*-----------------------------------------------------------------------------------*/
/*	Superfish Settings - http://users.tpg.com.au/j_birch/plugins/superfish/
/*-----------------------------------------------------------------------------------*/
	
	if(jQuery().superfish) {
		
		// Main Navigation
		jQuery('#primary-nav ul.sf-menu').superfish({ 
			delay: 200,
			animation: {opacity:'show', height:'show'},
			speed: 'fast',
			dropShadows: false
		});
		
		jQuery('#primary-nav li li a').hover(
			function () {
				jQuery(this).find('span').not('span.sf-sub-indicator').stop().animate({paddingLeft: 10}, 200, 'jswing');
			},
			function () {
				jQuery(this).find('span').not('span.sf-sub-indicator').stop().animate({paddingLeft: 0}, 200, 'jswing');
			}
		);
	
	}
	
/*-----------------------------------------------------------------------------------*/
/*	Toggle Content
/*-----------------------------------------------------------------------------------*/
	
	jQuery('.toggle h4').each( 
		function () {
			
			var iHeight = jQuery(this).closest('.toggle').find('.inner').height();
			var ipaddingTop = jQuery(this).closest('.toggle').find('.inner').css('padding-top');
			var ipaddingBottom = jQuery(this).closest('.toggle').find('.inner').css('padding-bottom');
			
			jQuery(this).toggle( 
				function () {
					
					imageURL = jQuery(this).find('span').attr('class');
					
					jQuery(this)
					.closest('.toggle')
					.find('.inner')
					.animate({height: 0, paddingTop: 0, paddingBottom: 0, opacity: 0 }, 200, 'jswing');
					jQuery(this)
					.find('span')
					.css('background', 'url('+ imageURL +'/images/plus_minus_sprite.gif) 25px 12px');
				},
				function () {
					
					jQuery(this)
					.closest('.toggle')
					.find('.inner')
					.animate({height: iHeight, paddingTop: ipaddingTop, paddingBottom: ipaddingBottom, opacity: 1}, 200, 'jswing');
					
					jQuery(this)
					.find('span')
					.css('background', 'url('+ imageURL +'/images/plus_minus_sprite.gif) 12px 12px');
				}
			);
		}
	);
	
	
/*-----------------------------------------------------------------------------------*/
/* Tabs
/*-----------------------------------------------------------------------------------*/
	
	if(jQuery().tabs) {
		jQuery(".tabs, .tour").tabs({ 
			fx: { opacity: 'toggle', duration: 200} 
		});
		
		jQuery('.tour .nav a').click( function (e) {
			e.preventDefault();
		});
		
	}

/*-----------------------------------------------------------------------------------*/
/* Post Thumbnail Hover Effect
/*-----------------------------------------------------------------------------------*/
	
	function tz_overlay() {
		
		jQuery('.post-thumb a img, .tab-thumb img, .tz_flickr_widget img').hover( function() {
			jQuery(this).stop().animate({opacity : 0.8}, 200);
		}, function() {
			jQuery(this).stop().animate({opacity : 1}, 200);
		});
		
		jQuery('.plus').hover( function() {
			jQuery(this).parent('.post-thumb').find('img').stop().animate({opacity : 0.8}, 200);
		}, function() {
			jQuery(this).parent('.post-thumb').find('img').stop().animate({opacity : 1}, 200);
		});
	}
	
	tz_overlay();


/*-----------------------------------------------------------------------------------*/
/*	PrettyPhoto - http://www.no-margin-for-errors.com/projects/prettyphoto-jquery-lightbox-clone/
/*-----------------------------------------------------------------------------------*/
	
	function tz_lightbox() {
		
		jQuery("a[rel^='prettyPhoto']").prettyPhoto({
			animationSpeed:'fast',
			slideshow:5000,
			theme:'pp_default',
			show_title:false,
			overlay_gallery: false
		});
	
	}
	
	if(jQuery().prettyPhoto) {
		tz_lightbox(); 
	}

/*-----------------------------------------------------------------------------------*/
/* Portfolio li Effect
/*-----------------------------------------------------------------------------------*/

	jQuery('#taxs a').each( 
		function () {
			
			// This is for Macs, due to the text being wider than PC.
			var theWidth = jQuery(this).width() + 2;

			jQuery(this).hover(
				function () {
					jQuery(this).not('.active').stop().animate({width: '91%'}, 200, 'jswing');
				},
				function () {
					jQuery(this).not('.active').stop().animate({width: theWidth}, 200, 'jswing');
				}
			);

			jQuery(this).click(
				function(e) {
					jQuery('#taxs a').removeClass('active').width("");
					jQuery(this).addClass('active').width('91%');
					e.preventDefault();
				}
			);

		}
	);
	
	// This is rather important for the active states, do not touch this unless you know what you're doing.
	jQuery('#taxs a.active').css({width: '91%'});

/*-----------------------------------------------------------------------------------*/
/*	Portfolio Sorting
/*-----------------------------------------------------------------------------------*/
	
	if (jQuery().quicksand) {
	
		(function($) {
			
			$.fn.sorted = function(customOptions) {
				var options = {
					reversed: false,
					by: function(a) {
						return a.text();
					}
				};
		
				$.extend(options, customOptions);
		
				$data = jQuery(this);
				arr = $data.get();
				arr.sort(function(a, b) {
		
					var valA = options.by($(a));
					var valB = options.by($(b));
			
					if (options.reversed) {
						return (valA < valB) ? 1 : (valA > valB) ? -1 : 0;				
					} else {		
						return (valA < valB) ? -1 : (valA > valB) ? 1 : 0;	
					}
			
				});
		
				return $(arr);
		
			};
		
		})(jQuery);
		
		jQuery(function() {
		
			var read_button = function(class_names) {
				
				var r = {
					selected: false,
					type: 0
				};
				
				for (var i=0; i < class_names.length; i++) {
					
					if (class_names[i].indexOf('selected-') == 0) {
						r.selected = true;
					}
				
					if (class_names[i].indexOf('segment-') == 0) {
						r.segment = class_names[i].split('-')[1];
					}
				};
				
				return r;
				
			};
		
			var determine_sort = function($buttons) {
				var $selected = $buttons.parent().filter('[class*="selected-"]');
				return $selected.find('a').attr('data-value');
			};
		
			var determine_kind = function($buttons) {
				var $selected = $buttons.parent().filter('[class*="selected-"]');
				return $selected.find('a').attr('data-value');
			};
		
			var $preferences = {
				duration: slideDuration,
				easing: 'easeInOutQuad',
				adjustHeight: 'dynamic'
			};
		
			var $list = jQuery('#columns-wrap');
			var $data = $list.clone();
		
			var $controls = jQuery('#portfolio-filter');
		
			$controls.each(function(i) {
		
				var $control = jQuery(this);
				var $buttons = $control.find('a');
		
				$buttons.bind('click', function(e) {
		
					var $button = jQuery(this);
					var $button_container = $button.parent();
					var button_properties = read_button($button_container.attr('class').split(' '));      
					var selected = button_properties.selected;
					var button_segment = button_properties.segment;
		
					if (!selected) {
		
						$buttons.parent().removeClass();
						$button_container.addClass('selected-' + button_segment);
		
						var sorting_type = determine_sort($controls.eq(1).find('a'));
						var sorting_kind = determine_kind($controls.eq(0).find('a'));
		
						if (sorting_kind == 'all') {
							var $filtered_data = $data.find('li');
						} else {
							var $filtered_data = $data.find('li.' + sorting_kind);
						}

						var $sorted_data = $filtered_data.sorted({
							by: function(v) {
								return parseInt(jQuery(v).find('.count').text());
							}
						});
			
						$list.quicksand($sorted_data, $preferences, function () {
								tz_overlay();
								tz_lightbox();
						});
			
					}
			
					e.preventDefault();
				});
			
			}); 
		
		});
	
	}

});
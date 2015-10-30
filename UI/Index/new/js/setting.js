
$(document).ready(function(){ 
	$clientsHolder = $('ul.portfolio_contain');
	$clientsClone = $clientsHolder.clone(); 

	$('.portfolio_list a').click(function(e) {
		e.preventDefault();
	 
		$filterClass = $(this).attr('class');
	 
		$('.portfolio_list li').removeClass('active');
		$(this).parent().addClass('active');
	 
		if($filterClass == 'all'){
			$filters = $clientsClone.find('li');
		} else {
			$filters = $clientsClone.find('li[data-type~='+ $filterClass +']');
		}
	 
	   $clientsHolder.quicksand( $filters, {
			duration: 1000,
			easing: 'easeInOutQuint'
		}, function(){
		
			$("a[rel^='prettyPhoto']").prettyPhoto({animation_speed:'fast',theme:'pp_default',slideshow:10000});

			$(function() {
			// OPACITY OF BUTTON SET TO 50%
			$(".mask").css("opacity","0");
			 
			// ON MOUSE OVER
			$(".mask").hover(function () {
			 
			// SET OPACITY TO 100%
			$(this).stop().animate({opacity:1}, 1200);
			},
			 
			// ON MOUSE OUT
			function () {
			 
			// SET OPACITY BACK TO 50%
			$(this).stop().animate({opacity:0}, 1200);
			});
			});	
			
			// start image effect portfolio 2

			//Custom settings
			var style_in = 'easeOutBounce';
			var style_out = 'jswing';
			var speed_in = 1000;
			var speed_out = 300;	

			//Calculation for corners
			var neg = Math.round($('.qitem').width() / 2) * (-1);	
			var pos = neg * (-1);	
			var out = pos * 2;
			
			$('.qitem').each(function () {
			
				url = $(this).find('a').attr('href');
				img = $(this).find('img').attr('src');
				alt = $(this).find('img').attr('img');
				
				$('img', this).remove();
				$(this).append('<div class="topLeft"></div><div class="topRight"></div><div class="bottomLeft"></div><div class="bottomRight"></div>');
				$(this).children('div').css('background-image','url('+ img + ')');

				$(this).find('div.topLeft').css({top:0, left:0, width:pos , height:pos});	
				$(this).find('div.topRight').css({top:0, left:pos, width:pos , height:pos});	
				$(this).find('div.bottomLeft').css({bottom:0, left:0, width:pos , height:pos});	
				$(this).find('div.bottomRight').css({bottom:0, left:pos, width:pos , height:pos});	

			}).hover(function () {
			
				$(this).find('div.topLeft').stop(false, true).animate({top:neg, left:neg}, {duration:speed_out, easing:style_out});	
				$(this).find('div.topRight').stop(false, true).animate({top:neg, left:out}, {duration:speed_out, easing:style_out});	
				$(this).find('div.bottomLeft').stop(false, true).animate({bottom:neg, left:neg}, {duration:speed_out, easing:style_out});	
				$(this).find('div.bottomRight').stop(false, true).animate({bottom:neg, left:out}, {duration:speed_out, easing:style_out});	
						
			},
			
			function () {

				$(this).find('div.topLeft').stop(false, true).animate({top:0, left:0}, {duration:speed_in, easing:style_in});	
				$(this).find('div.topRight').stop(false, true).animate({top:0, left:pos}, {duration:speed_in, easing:style_in});	
				$(this).find('div.bottomLeft').stop(false, true).animate({bottom:0, left:0}, {duration:speed_in, easing:style_in});	
				$(this).find('div.bottomRight').stop(false, true).animate({bottom:0, left:pos}, {duration:speed_in, easing:style_in});	
			
			}).click (function () {
				window.location = $(this).find('a').attr('href');	
			});

			// end image effect portfolio2

			// start image effect portfolio clasic
			
			$(function() {
			// OPACITY OF BUTTON SET TO 50%
			$(".portfolio_clasic img").css("opacity","0.2");
			 
			// ON MOUSE OVER
			$(".portfolio_clasic img").hover(function () {
			 
			// SET OPACITY TO 100%
			$(this).stop().animate({opacity:1}, 1200);
			},
			 
			// ON MOUSE OUT
			function () {
			 
			// SET OPACITY BACK TO 50%
			$(this).stop().animate({opacity:0.6}, 900);
			});
			});
			// end image effect portfolio clasic
			
			
			
		});
		
	});
});


/* image hover portfolio */

$(document).ready(function() {

	//Custom settings
	var style_in = 'easeOutBounce';
	var style_out = 'jswing';
	var speed_in = 1000;
	var speed_out = 300;	

	//Calculation for corners
	var neg = Math.round($('.qitem').width() / 2) * (-1);	
	var pos = neg * (-1);	
	var out = pos * 2;
	
	$('.qitem').each(function () {
	
		url = $(this).find('a').attr('href');
		img = $(this).find('img').attr('src');
		alt = $(this).find('img').attr('img');
		
		$('img', this).remove();
		$(this).append('<div class="topLeft"></div><div class="topRight"></div><div class="bottomLeft"></div><div class="bottomRight"></div>');
		$(this).children('div').css('background-image','url('+ img + ')');

		$(this).find('div.topLeft').css({top:0, left:0, width:pos , height:pos});	
		$(this).find('div.topRight').css({top:0, left:pos, width:pos , height:pos});	
		$(this).find('div.bottomLeft').css({bottom:0, left:0, width:pos , height:pos});	
		$(this).find('div.bottomRight').css({bottom:0, left:pos, width:pos , height:pos});	

	}).hover(function () {
	
		$(this).find('div.topLeft').stop(false, true).animate({top:neg, left:neg}, {duration:speed_out, easing:style_out});	
		$(this).find('div.topRight').stop(false, true).animate({top:neg, left:out}, {duration:speed_out, easing:style_out});	
		$(this).find('div.bottomLeft').stop(false, true).animate({bottom:neg, left:neg}, {duration:speed_out, easing:style_out});	
		$(this).find('div.bottomRight').stop(false, true).animate({bottom:neg, left:out}, {duration:speed_out, easing:style_out});	
				
	},
	
	function () {

		$(this).find('div.topLeft').stop(false, true).animate({top:0, left:0}, {duration:speed_in, easing:style_in});	
		$(this).find('div.topRight').stop(false, true).animate({top:0, left:pos}, {duration:speed_in, easing:style_in});	
		$(this).find('div.bottomLeft').stop(false, true).animate({bottom:0, left:0}, {duration:speed_in, easing:style_in});	
		$(this).find('div.bottomRight').stop(false, true).animate({bottom:0, left:pos}, {duration:speed_in, easing:style_in});	
	
	}).click (function () {
		window.location = $(this).find('a').attr('href');	
	});
	
			// When a link is clicked
		$("a.tab").click(function () {
			
			
			// switch all tabs off
			$(".active").removeClass("active");
			
			// switch this tab on
			$(this).addClass("active");
			
			// slide all content up
			$(".tab_content").slideUp();
			
			// slide this content up
			var content_show = $(this).attr("title");
			$("#"+content_show).slideDown();
		  
		});
	

});



		

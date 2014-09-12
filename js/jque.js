
$('.menu_ul li a').click(function(){
	$('.menu_ul li a').each(function(){
		$(this).removeClass('active');
	});
	$(this).addClass('active');
});

$('#send_msg').click(function(){
	console.log("new message incomming!");
	$mail = $("input[name$='mail']").val();
	$sub = $("input[name$='subject']").val();
	$text = $("textarea[name$='text']").val();
	alert("Mail = " + $mail + "subject = " + $subject + "text = " + $text);
});

$(document).on('click', "#send_msg", function(){
	$c  = $("recaptcha_resposonse_field");
});

$('.sous_menu').hide();

$('.menu_header').on('mouseenter', function(){
	$('.sous_menu').show("1000");
});

$('.sous_menu li a').click(function(){
	$('.sous_menu').hide();
});

$('.menu_ul li a').click(function(){
	$('.sous_menu').hide();
});

		// PLAYER SOUNDCLOUD		
$timeout = function(){
	var time = setTimeout(function() {
			$('#playerSound').addClass('is_hidden');
			$('#playerSound').hide("slow");	
		}, 5000);
	return time;	
};

	$('#playerSound').hide();
	$('#playerSound').addClass('is_hidden');
	$('.btn_soundcloud').show();
	$('.btn_soundcloud').click(function(){
		if ($('#playerSound').hasClass('is_hidden')) {
			$('#playerSound').show("slow");
			$('#playerSound').removeClass('is_hidden');
			$time = $timeout();
		}else{
			$('#playerSound').addClass('is_hidden');
			$('#playerSound').hide("slow");
			clearTimeout($time);
		}
	});

	$('#playerSound').on('mouseenter', function(){
		clearTimeout($time);
	});

	$('#playerSound').on('mouseleave', function(){
		$time = $timeout();
	})

	$(window).scroll(
	{
		previousTop: 0
	}, 
	function () {
		var currentTop = $(window).scrollTop();
		if (currentTop < this.previousTop) {
			$("#top_bar").show();
		} else {
			$("#top_bar").hide();
		}
		this.previousTop = currentTop;
	});


	$(window).scroll(function(){
		var st = $(window).scrollTop();
		$("#header img").css("-webkit-transform", "translateY(" + (st/2) + "px)");
		$("#header img").css("transform", "translateY(" + (st/2) + "px)");
		$("#header img").css("-moz-transform", "translateY(" + (st/2) + "px)");
		$("#header img").css("-o-transform", "translateY(" + (st/2) + "px)");
		$("#header img").css("-ms-transform", "translateY(" + (st/2) + "px)");
		$(".img_wall").css("-webkit-transform", "translateY(" + (st/2) + "px)");
		$(".img_wall").css("transform", "translateY(" + (st/2) + "px)");
		$(".img_wall").css("-moz-transform", "translateY(" + (st/2) + "px)");
		$(".img_wall").css("-o-transform", "translateY(" + (st/2) + "px)");
		$(".img_wall").css("-ms-transform", "translateY(" + (st/2) + "px)");
	});

	$(document).ready(function(){
		$('#playerSound').html("<iframe width='100%' height='100%' scrolling='no' frameborder='no' src='https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/users/49488549&amp;color=ff5500&amp;auto_play=false&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false'></iframe>")

	});
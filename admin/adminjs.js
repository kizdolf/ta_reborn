
var url = $.url();
var param  = url.param();
$get_those_pics = function(){
	$('#img_div').html("");
	$.post('ajax.php', param).done(function(data){
		$res = jQuery.parseJSON(data);
		console.log($res);
	}).then(function(){
		for (var i = $res.length - 1; i >= 0; i--) {
			$('#img_div').append("<img src='" + $res[i] + "'><span class='pic_del glyphicon glyphicon-remove'><span>");
		};
		$("#img_div").hide();
	});
}

if (param.type == "artiste") {
	$get_those_pics();		
};

$(document).on('click', ".btn_pics", function(){
	$('.gal_pic').hide(0);
	$('.gal_pic').html($('#img_div').html());
	if($('.btn_pics').html() != "cacher les photos"){
		$('.btn_pics').html("cacher les photos");
		$('.gal_pic').show("slow");
	}else{
		$('.btn_pics').html("voir les photos");
	}
});

$(document).on('click', ".pic_del", function(){
	$img = $(this).prev();
	var src = $img.attr('src');
	$img.fadeOut(1000);
	$(this).fadeOut(1000);
	$.post('ajax.php', {del: 'img', src: src}).then(function  (data) {
		console.log(data);
	}).then(function(){
		$get_those_pics();
	})
});

$(document).on('click', ".valid", function(){

	$(this).html("<img src='img/miniloader.gif>");

});

$check = function(){
		$('.inputc').each(function(){
			if($(this).val() == ''){
				$(this).addClass('missing');
				$('.valid').hide();
				$('.alert').show();
			}
			else{
				$(this).removeClass('missing');
			}
		});
	};

	$check();

	$(".quartier_choix").click(function(){
		$val = $(this).val();
		$('.quartier_choix').each(function(){
			$(this).removeClass('btn-success')
		})
		$(this).addClass('btn-success');
		$("#quartier_id").val($val);
	});

	$(".style_choix").click(function(){
		$('#new_style').hide();
		$val = $(this).val();
		$('.style_choix').each(function(){
			$(this).removeClass('btn-success')
		})
		$(this).addClass('btn-success');
		$("#style_id").val($val);
	});

	$('input').keypress(function(){
		$('.valid').show();
		$('.alert').hide();
		$check();
	});

	$('#new_style').hide();

	$('.new_style_btn').click(function(){
		$('#new_style').show();
	})

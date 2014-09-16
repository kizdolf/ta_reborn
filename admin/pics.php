<?php 

spl_autoload_register(function ($class) {
	include __DIR__.'/../classes/' . $class . '_class.php';
});
require_once('admin_functions.php');
$log = new log();
if (!$log->is_logued()) {
	header('Location: login.php?case=disconnect');
}else{
	$bdd = new tapdo();
	$message = "";
	$rights = rights($bdd);
	// pics_handler($files, $path, $name_pic)

	if (isset($_POST['galerie'])) {
		pics_handler($_FILES, $_POST['path'], $_POST['name']);
		header('Location: index.php?add=images&n='.$_POST['name']);
	}
	elseif (!isset($_GET['show']) || ($_GET['show'] != 'off' && $_GET['show'] != 'team')) {
		header('Location: index.php?wrong=GET[show] in pics.php');
	}
	$galerie = ($_GET['show'] == 'off') ? 'off' :'team';
?>
<!DOCTYPE html>
<html>
<head>
	<title>Galerie <?php echo $_GET['show']; ?> | Admin</title>
	<meta charset="utf-8">
	<script src="../components/ckeditor/ckeditor.js"></script>
  	<link rel="stylesheet" type="text/css" href="../css/bootstrap/css/bootstrap.min.css">
	<link href='http://fonts.googleapis.com/css?family=Abel' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="css/style.css" type="text/css" media="screen"/>
</head>
<body>
	 <?php include('menu.php'); ?>
	<div id="img_div2" class="gal_pic2"></div>
	<div id="wrapper">
		<form method="post" action="pics.php" enctype="multipart/form-data">
			<input type="hidden" name="path" value="<?php echo "../portfolio/".$_GET['show']; ?>">
			<input type="hidden" name="name" value="<?php echo $_GET['show']; ?>">
			<div id="upload" class="jumbotron">
				<h3>Photos "<?php echo $_GET['show']; ?>"</h3>
				<input type="file" multiple="multiple" name="pics[]" id="pics"> <br>
			</div>
			<div id="sub_form">
				<button class="btn btn-lg btn-success valid" type="submit" name="galerie">Add it</button>
			</div>
		</form>
	</div>
	<script src="../components/jquery.js"></script>
	<script src="../components/purl.js"></script>
	<script type="text/javascript">
		$( document ).ready(function(){
			var path_rel = "../portfolio/" + $.url().param().show;
			console.log(path_rel);
			$.post('ajax.php',{galerie: path_rel}).done(function(data){
				$res = jQuery.parseJSON(data);
			}).then(function(){
				for (var i = $res.length - 1; i >= 0; i--) {
					$('#img_div2').append("<div class='wra_img'><img src='" + $res[i] + "'><span class='pic_del2 glyphicon glyphicon-remove'><span></div>");
				};
				// $("#img_div").hide();
			});
		});

		$(document).on('click', ".pic_del2", function(){
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
	</script>

</body>
</html>
<?php } ?>
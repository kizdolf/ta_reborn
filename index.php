<!DOCTYPE html>
<html  ng-app="myApp">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="Cache-control" content="public">
	<title>Toulouse Acoustics </title>
	<link rel="stylesheet" type="text/css" href="css/style.css" media="screen">
	<link rel="stylesheet" type="text/css" href="css/bootstrap/css/bootstrap.min.css">
	<link href='http://fonts.googleapis.com/css?family=Abel' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
	<base href="/ta/">
	<!-- <base href="/projects/admin/adminta/"> -->
</head>
<!-- Piwik -->
<script type="text/javascript">
  var _paq = _paq || [];
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u=(("https:" == document.location.protocol) ? "https" : "http") + "://localhost/public/adminta/components/piwik/piwik/";
    _paq.push(['setTrackerUrl', u+'piwik.php']);
    _paq.push(['setSiteId', 1]);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0]; g.type='text/javascript';
    g.defer=true; g.async=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
  })();
</script>
<noscript><p><img src="http://localhost/public/adminta/components/piwik/piwik/piwik.php?idsite=1" style="border:0;" alt="" /></p></noscript>
<!-- End Piwik Code -->
<body>
<!-- FAcebook SDK -->
<div id="fb-root"></div>
 <script>
      window.fbAsyncInit = function() {
        FB.init({
          appId      : '359277744238959',
          xfbml      : true,
          version    : 'v2.1'
        });
      };

      (function(d, s, id){
         var js, fjs = d.getElementsByTagName(s)[0];
         if (d.getElementById(id)) {return;}
         js = d.createElement(s); js.id = id;
         js.src = "//connect.facebook.net/en_US/sdk.js";
         fjs.parentNode.insertBefore(js, fjs);
       }(document, 'script', 'facebook-jssdk'));
    </script>
    <!-- End Facebook SDK -->

  <div id="header">
  	<a href="#/home" id="img_header">
  		<img src="img/headers/home.jpg" id="img_header">
  	</a>
  </div>
  <!-- Nouveau Menu responsive: -->
<div ng-controller="headCtrl">
	<nav  class="navbar navbar-default" role="navigation" id="resp_menu">
		<div class="container container-fluid" id="top_bar">
			<ul>
				<li>
					<a href="https://www.facebook.com/toulouseacoustics" alt="facebook"><img src="img/headers/facebook.png" title="facebook" alt="icon"></a>
				</li>
				<li>
					<a href="https://soundcloud.com/toulouse-acoustics" alt="soundcloud"><img src="img/headers/soundcloud.png" title="soundcloud" alt="icon"></a>
				</li>
				<li>
					<a href="http://vimeo.com/toulouseacoustics" alt="vimeo"><img src="img/headers/vimeo.png" title="vimeo" alt="icon"></a>
				</li>
				<li>
					<a href="https://www.youtube.com/channel/UCDYM7BgufabluMT01YmnK9g" alt="You Tube"><img src="img/headers/youtube.png" title="youtube" alt="icon"></a>
				</li>
				<li>
					<a href="http://instagram.com/toulouseacoustics" alt="instagram"><img src="img/headers/instagram.png" title="instagram" alt="icon"></a>
				</li>
			</ul>
		</div>
		<div class="container container-fluid">
			<div>
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#list_items_menu">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>
			<div class="collapse navbar-collapse" id="list_items_menu">
				<ul class="nav navbar-nav menu_ul">
					<li>
						<a href="#/home" >accueil</a>
					</li>
					<li>
						<a href="#/artistes" class='menu_header'>artistes</a>
						<ul class="sous_menu">
							<li>
								<a href="#/artistes_locaux">Locaux</a>
							</li>
							<li>
								<a href="#/artistes_visiteurs">visiteurs</a>
							</li>
						</ul>
					</li>
					<li>
						<a href="#/quartiers">quartiers</a>
					</li>
					<li>
						<a href="#/portfolio">portfolio</a>
					</li>
					<li>
						<a href="#/about">a propos</a>
					</li>
					<li>
						<a href="#/contact">contact</a>
					</li>
					<li>
						<a href="#/partners">partenaires</a>
					</li>
					<!-- <li>
						<span class="glyphicon glyphicon-search icn-search"></span>
						<input id="search" ng-model="str" ng-change="search()" >
					</li> -->
				</ul>
			</div>
		</div>
	</nav>

	<div id="res">
		<div ng-repeat="a in res.artistes">
			<div class="div_grid" >
				<a href="#/artistes/{{ a.id }}" >{{a.name}}<div class="arrow-right"></div></a>
				<span ng-if="a.path_vignette == '' ">
					<img src="img/badges/weekly.png" class="img_grid">
				</span>
				<span ng-if="a.path_vignette != ''">
					<img src="{{a.path_vignette}}" class="img_grid">
				</span>
				<!-- <hr class="huge-hr"> -->
			</div>
		</div>
		<div ng-repeat="a in res.quartiers">
			<div class="div_grid" >
				<a href="#/quartiers/{{ a.id }}" >{{a.name}}<div class="arrow-right"></div></a>
				<span ng-if="a.path_vignette == '' ">
					<img src="img/badges/weekly.png" class="img_grid">
				</span>
				<span ng-if="a.path_vignette != ''">
					<img src="{{a.path_vignette}}" class="img_grid">
				</span>
				<!-- <hr class="huge-hr"> -->
			</div>
		</div>
		<div ng-repeat="a in res.videos">
			<h2>{{a.name}}</h2>
			<div id="loader"><img  src ="img/badges/loadta.gif"></div>
			<img ng-show="a.weekly == 1" src="img/badges/weekly.png" alt="logo weekly" class='weekly_badge badge-g'>
			<div ng-bind-html="a.frame"></div>
		</div>
	</div>
</div>

<div class="container container-fluid">
<div class="fb-like" data-href="http://localhost/public/adminta/#/home" data-width="80" data-layout="standard" data-action="like" data-show-faces="false" data-share="true"></div>
<div ng-view ></div>

</div>

<!-- <div id="container-fluid" >
	by DonKino. all rights are reserved but do what you want also :)
</div> -->

<div id="sound">
	<button  class="btn_soundcloud btn btn-default btn-soundcloud">Notre playlist</button>
	<div id="playerSound"></div>
</div>

  <script type="text/javascript" src="http://www.google.com/recaptcha/api/js/recaptcha_ajax.js"></script>
  <script src="components/purl.js"></script>
  <script src="components/angular.min.js"></script>
  <script src="components/angular-route.min.js"></script>
  <script src="components/angular-animate.min.js"></script>
  <script src="components/angular-cookies.min.js"></script>
  <script src="components/jquery.js"></script>
  <script src="js/app.js"></script>
  <script src="js/controllers.js"></script>
  <script src="js/routes.js"></script>
  <script src="components/purl.js"></script>
  <script src="js/jque.js"></script>
  <script src="css/bootstrap/js/bootstrap.min.js"></script>
  <script src="js/services.js"></script>
  <script src="//connect.soundcloud.com/sdk.js"></script>
  </div>
</body>
</html>

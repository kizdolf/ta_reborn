'use strict';

angular.module('myApp.controllers', ['ngCookies', 'ngAnimate'])
.run(function($cookieStore){
	// $cookieStore.remove("visited");
	$cookieStore.put("visited", "first");
})

.controller('headCtrl', ['$location', 'pics', 'filterFilter', '$scope', '$http', '$sce', 'getData', 'tools',  '$cookies', '$cookieStore', '$log', '$animate'
	,function($location, pics, filterFilter, $scope, $http, $sce, getData, tools,  $cookies, $cookieStore, $log, $animate){

	$scope.title = "Welcome at Toulouse Acoustics";


	var url = $location.url().split('/');
	url = url[1];
	$('.menu_ul li a').each(function(){
		if ($(this).attr('href').indexOf(url) != -1) {
			$(".menu_ul").removeClass('active');
			$(this).addClass('active');
		}
	})

	getData.weekly().then(function(data){
		$scope.weekly = data.data;
		$scope.weekly.video.frame = tools.iframe($scope.weekly.video.url);
		$scope.load = "";
		$scope.weekly.video.text = $sce.trustAsHtml($scope.weekly.video.text);
		$scope.weekly.video.name = $sce.trustAsHtml($scope.weekly.video.name);
		$scope.weekly.artiste.name = $sce.trustAsHtml($scope.weekly.artiste.name);
		$scope.weekly.artiste.text = $sce.trustAsHtml($scope.weekly.artiste.text);
		$scope.weekly.quartier.text = $sce.trustAsHtml($scope.weekly.quartier.text);
		$scope.weekly.quartier.name = $sce.trustAsHtml($scope.weekly.quartier.name);
	}).then(function(){
		pics.list_pics($scope.weekly.artiste.path_pics).then(function(data){
			$scope.truc = data.data;
		});
	});
	$scope.change_video = function(choice){
		$('#loader').show(0);
		var id = parseInt($scope.weekly.video.id);
		$("#weekly_video").fadeOut("slow");
		$(".arr_pn").fadeOut("slow");
		getData.one_video(id, choice).then(function(data){
			$scope.weekly.video = data.data;
			$scope.weekly.video.frame = tools.iframe($scope.weekly.video.url);
			$scope.weekly.video.text = $sce.trustAsHtml($scope.weekly.video.text);
			$scope.weekly.video.name = $sce.trustAsHtml($scope.weekly.video.name);
		})
		.then(function(){
			var ids =  $scope.weekly.video.id_artiste + "," +  $scope.weekly.video.id_quartier;
			getData.related('video', 'id', ids).then(function(data){
				$scope.weekly.artiste = data.data.artiste;
				$scope.weekly.quartier = data.data.quartier;
				$scope.weekly.artiste.name = $sce.trustAsHtml($scope.weekly.artiste.name);
				$scope.weekly.artiste.text = $sce.trustAsHtml($scope.weekly.artiste.text);
				$scope.weekly.quartier.text = $sce.trustAsHtml($scope.weekly.quartier.text);
				$scope.weekly.quartier.name = $sce.trustAsHtml($scope.weekly.quartier.name);
			});
		})
		.then(function(){
			setTimeout(function(){
				$('#loader').hide("slow");
				$("#weekly_video").fadeIn("slow");
				$(".arr_pn").fadeIn("slow");
			}, 1500);
		});
	}

	$('body').click(function(){
		$('.sous_menu').hide();
	});

	$('.close').click(function(){
		$("#fs-wrapper").fadeOut("slow");
	});

	$(document).keyup(function(e) {
		if (e.keyCode == 27) {
			$("#fs-wrapper").fadeOut("slow");
		}
	});

	$scope.search = function(){
		if ($scope.str.length >= 3){
			console.log($scope.str);
			getData.searcha($scope.str).then(function(data){
				if (data.data != $scope.res) {
					$scope.res = data.data;
					for (var i in $scope.res.videos){
						$scope.res.videos[i].frame =  tools.iframe($scope.res.videos[i].url);
					}
				}
			});
		}else{
			$scope.res = "";
		}
	};
}])

.controller('artisteCtrl', ['pics', 'tools', 'getData', '$scope', '$routeParams', '$http', '$sce', function(pics, tools, getData, $scope, $routeParams, $http, $sce){

	$scope.pageClass = 'page-artiste';

	getData.related('artiste', 'id', $routeParams.id).then(function(data){
		$scope.artiste = data.data;
		$scope.artiste.artiste.text = $sce.trustAsHtml($scope.artiste.artiste.text);
		$scope.artiste.artiste.name = $sce.trustAsHtml($scope.artiste.artiste.name);
		for (var i in $scope.artiste.videos){
			$scope.artiste.videos[i].name = $sce.trustAsHtml($scope.artiste.videos[i].name);
			$scope.artiste.videos[i].frame = tools.iframe($scope.artiste.videos[i].url);
		}
		tools.soundcloud(data.data.artiste.itw, "lol");
	})
	.then(function(){
		pics.list_minpics($scope.artiste.artiste.path_pics).then(function(data){
			$scope.pics = data.data;
		});
	});
}])


.controller('artistesCtrl', ['pics', 'getData', '$scope', '$routeParams', '$http', '$sce', function(pics, getData, $scope, $routeParams, $http, $sce){
	$scope.pageClass = 'page-artistes';
	getData.artistes_by('style').then(function(data){
		$scope.artistes = data.data;
	}).then(function(){
		for (var i in $scope.artistes){
			if($scope.artistes[i].artistes.length == 0)
				delete $scope.artistes[i];
			else{
				for (var j in $scope.artistes[i].artistes){
					$scope.artistes[i].artistes[j].name = $sce.trustAsHtml($scope.artistes[i].artistes[j].name);
				}
			}
		}
	});
}])

.controller('locauxCtrl', ['pics', 'getData', '$scope', '$routeParams', '$http', '$sce', function(pics, getData, $scope, $routeParams, $http, $sce){
	$scope.pageClass = 'page-locaux';

	getData.art_cat(0).then(function(data){
		$scope.styles = data.data;
		console.log(data.data);
	}).then(function(){
		for (var i in $scope.styles){
			if ($scope.styles[i].artistes.length == 0) {
				delete $scope.styles[i];
			}else{
				for (var j in $scope.styles[i].artistes){
					$scope.styles[i].artistes[j].name = $sce.trustAsHtml($scope.styles[i].artistes[j].name);
				}
			}
		}
	});
}])

.controller('visiteursCtrl', ['pics', 'getData', '$scope', '$routeParams', '$http', '$sce', function(pics, getData, $scope, $routeParams, $http, $sce){
	$scope.pageClass = 'page-visiteurs';

	getData.art_cat(1).then(function(data){
		$scope.styles = data.data;
		console.log(data.data);
	}).then(function(){
		for (var i in $scope.styles){
			if ($scope.styles[i].artistes.length == 0) {
				delete $scope.styles[i];
			}else{
				for (var j in $scope.styles[i].artistes){
					$scope.styles[i].artistes[j].name = $sce.trustAsHtml($scope.styles[i].artistes[j].name);
				}
			}
		}
	});
}])

.controller('quartierCtrl', ['tools', 'getData', '$scope', '$routeParams', '$http', '$sce', function(tools, getData, $scope, $routeParams, $http, $sce){
	$scope.pageClass = 'page-quartier';

	getData.related('quartier', 'id', $routeParams.id).then(function(data){
		$scope.quartier = data.data;
		$scope.quartier.quartier.text = $sce.trustAsHtml($scope.quartier.quartier.text);
		$scope.quartier.quartier.name = $sce.trustAsHtml($scope.quartier.quartier.name);
		for (var i in $scope.quartier.videos){
			$scope.quartier.videos[i].frame = tools.iframe($scope.quartier.videos[i].url);
			$scope.quartier.videos[i].name = $sce.trustAsHtml($scope.quartier.videos[i].name);
		}
		for (var i in $scope.quartier.artistes)
		{
			$scope.quartier.artistes[i].name = $sce.trustAsHtml($scope.quartier.artistes[i].name);
		}
	});
}])

.controller('quartiersCtrl', ['pics', 'getData', '$scope', '$routeParams', '$http', '$sce', function(pics, getData, $scope, $routeParams, $http, $sce){

	$scope.pageClass = 'page-quartiers';
	getData.quartiers('').then(function(data){

		$scope.quartiers = data.data;
		
	}).then(function(){

	$scope.quartiers.forEach(function(i){
		i.name = $sce.trustAsHtml(i.name);
		pics.list_pics(i.path_pics).then(function(data){
			$scope.truc = data.data;
		}).then(function(){
			if ($scope.truc == "T") {
				i.imgs = ["img/badges/visiteur.jpg"];
			}
			else{
				i.imgs = $scope.truc;
			}
		});
	})
	});
}])

.controller('picsCtrl', ['pics', 'getData', '$scope', '$routeParams', '$http', '$sce', function(pics, getData, $scope, $routeParams, $http, $sce){

	$scope.pageClass = 'page-pics';
	$('#pics_viewer').hide();
	$scope.back = [];

	function shuffle(o){ //v1.0
		for(var j, x, i = o.length; i; j = Math.floor(Math.random() * i), x = o[--i], o[i] = o[j], o[j] = x);
		return o;
	};

	getData.artistes('').then(function(data){
		$scope.artistes = data.data;
	})
	.then(function(){
		for (var i in $scope.artistes){
			$scope.artistes[i].name = $sce.trustAsHtml($scope.artistes[i].name);
			pics.list_minpics($scope.artistes[i].path_pics).then(function(data){
				for (var j in data.data){
					$scope.back.push(data.data[j]);
				}
			}).then(function(){
				$scope.back = shuffle($scope.back);
			});
		}
	});

	getData.quartiers('').then(function(data){
		$scope.quartiers = data.data;
	})
	.then(function(){
		for (var i in $scope.quartiers){
			$scope.quartiers[i].name = $sce.trustAsHtml($scope.quartiers[i].name);
			pics.list_minpics($scope.quartiers[i].path_pics).then(function(data){
				for (var j in data.data){
					$scope.back.push(data.data[j]);
				}
			}).then(function(){
				$scope.back = shuffle($scope.back);
			});
		}
	});

	console.log($scope.back);

	$scope.name_click = function(path){
		$scope.index = 0;
		pics.list_pics(path).then(function(data){
			$scope.pics = data.data;
			$scope.pic = data.data[$scope.index];
		}).then(function(){
			$('#pics_viewer').show();
		});
	};

	if(angular.isDefined($routeParams.path)){
		$scope.name_click($routeParams.path);
	}

	$scope.next_pic = function(){
		$scope.index = $scope.index + 1;
		if(!angular.isDefined($scope.pics[$scope.index]))
				$scope.index = 0;
		$scope.pic = $scope.pics[$scope.index];
	}

	$scope.prev_pic = function(){
		$scope.index = $scope.index - 1;
		if($scope.index < 0)
			$scope.index = $scope.pics.length - 1;
		$scope.pic = $scope.pics[$scope.index];
	}

	$scope.change = function(i){
		// console.log("changed!!" + i);
		$scope.pic = $scope.pics[$scope.index];
		console.log($scope.pic);
	}

	$('.pic_close').click(function(){
		$('#pics_viewer').hide();
		$scope.index = 0;
	});

	$(document).keyup(function(e) {
		if (e.keyCode == 27) {
			$('#pics_viewer').hide();
		}
		if (e.keyCode == 39) {
			$scope.index = $scope.index + 1;
			if($scope.index < 0)
				$scope.index = 0;
			$scope.change($scope.index);
		}
		if (e.keyCode == 37) {
			$scope.index = $scope.index - 1;
			if($scope.index < 0)
				$scope.index = $scope.pics.length - 1;
			$scope.change($scope.index);
		}
	});
}])

.controller('aboutCtrl', ['getData', '$scope', '$sce', function(getData, $scope, $sce){
	$scope.pageClass = 'page-about';
	getData.about().then(function(data){
		$scope.text = $sce.trustAsHtml(data.data.text);
	});
}])

.controller('contactCtrl', ['$http', 'getData', '$scope', '$sce', function($http, getData, $scope, $sce){
	$scope.pageClass = 'page-contact';
	
	$scope.txt_btn = "Envoyer le message.";

	getData.contact().then(function(data){
		$scope.text = $sce.trustAsHtml(data.data.text);
	});

	Recaptcha.create("6LejkfkSAAAAAFzdmV65iEt7omLdnJXZoMpMVw3e",
		"captcha",
		{
		 	theme: "red",
		});

	$scope.send_msg = function(msg){

		$scope.message = "";
		$scope.txt_btn = "Verification en cour..."
		
		var rep = Recaptcha.get_response();
		var chal = Recaptcha.get_challenge();
		
		$scope.tosend = msg;

		$http.get('API/api.php?get=captcha&rep=' + rep + '&chal=' + chal).then(function(data){
			if(data.data == "true"){
				$scope.txt_btn = "Verification ok! Envoi en cour."
				//Utilisation de jquery car le post d'angular est foireux (demande l'ajout de centaines de lignes de code...)
				$.post( "API/contact.php", { mail: $scope.tosend.email, 
																		subject: $scope.tosend.subject,
																		txt: $scope.tosend.txt} )
				.done(function(data){
				
					if(data != "true"){
						$("#message").html(data);
						$('.btn_send').html("Renvoyer le message");
					}
					else{
						$("#message").html(" ");
						$('.btn_send').html("Message envoyé!");
					}
				});
			}else{
				$scope.message = "Vérification échouée. Veuillez entrer le code à nouveau.";
				$scope.txt_btn = "Renvoyer le message.";
				Recaptcha.reload();
				Recaptcha.focus_response_field();
			}
		});
	};
}])

.controller('partnersCtrl', ['$http', 'getData', '$scope', '$sce', function($http, getData, $scope, $sce){

	$scope.pageClass = 'page-partners';
	getData.partners().then(function(data){
		$scope.partners = data.data;
	}).then(function(){
		$scope.partners.forEach(function(one){
			one.desc= $sce.trustAsHtml(one.desc);
		});
	});

}]);


// "use strict";angular.module("myApp.controllers",["ngCookies","ngAnimate"]).run(function(e){e.put("visited","first")}).controller("headCtrl",["$location","pics","filterFilter","$scope","$http","$sce","getData","tools","$cookies","$cookieStore","$log","$animate",function(e,t,n,r,i,s,o,u,a,f,l,c){r.title="Welcome at Toulouse Acoustics";var h=e.url().split("/");h=h[1];$(".menu_ul li a").each(function(){if($(this).attr("href").indexOf(h)!=-1){$(".menu_ul").removeClass("active");$(this).addClass("active")}});o.weekly().then(function(e){r.weekly=e.data;r.weekly.video.frame=u.iframe(r.weekly.video.url);r.load="";r.weekly.video.text=s.trustAsHtml(r.weekly.video.text);r.weekly.video.name=s.trustAsHtml(r.weekly.video.name);r.weekly.artiste.name=s.trustAsHtml(r.weekly.artiste.name);r.weekly.artiste.text=s.trustAsHtml(r.weekly.artiste.text);r.weekly.quartier.text=s.trustAsHtml(r.weekly.quartier.text);r.weekly.quartier.name=s.trustAsHtml(r.weekly.quartier.name)}).then(function(){t.list_pics(r.weekly.artiste.path_pics).then(function(e){r.truc=e.data})});r.change_video=function(e){$("#loader").show(0);var t=parseInt(r.weekly.video.id);$("#weekly_video").fadeOut("slow");$(".arr_pn").fadeOut("slow");o.one_video(t,e).then(function(e){r.weekly.video=e.data;r.weekly.video.frame=u.iframe(r.weekly.video.url);r.weekly.video.text=s.trustAsHtml(r.weekly.video.text);r.weekly.video.name=s.trustAsHtml(r.weekly.video.name)}).then(function(){var e=r.weekly.video.id_artiste+","+r.weekly.video.id_quartier;o.related("video","id",e).then(function(e){r.weekly.artiste=e.data.artiste;r.weekly.quartier=e.data.quartier;r.weekly.artiste.name=s.trustAsHtml(r.weekly.artiste.name);r.weekly.artiste.text=s.trustAsHtml(r.weekly.artiste.text);r.weekly.quartier.text=s.trustAsHtml(r.weekly.quartier.text);r.weekly.quartier.name=s.trustAsHtml(r.weekly.quartier.name)})}).then(function(){setTimeout(function(){$("#loader").hide("slow");$("#weekly_video").fadeIn("slow");$(".arr_pn").fadeIn("slow")},1500)})};$("body").click(function(){$(".sous_menu").hide()});$(".close").click(function(){$("#fs-wrapper").fadeOut("slow")});$(document).keyup(function(e){if(e.keyCode==27){$("#fs-wrapper").fadeOut("slow")}});r.search=function(){if(r.str.length>=3){console.log(r.str);o.searcha(r.str).then(function(e){if(e.data!=r.res){r.res=e.data;for(var t in r.res.videos){r.res.videos[t].frame=u.iframe(r.res.videos[t].url)}}})}else{r.res=""}}}]).controller("artisteCtrl",["pics","tools","getData","$scope","$routeParams","$http","$sce",function(e,t,n,r,i,s,o){r.pageClass="page-artiste";n.related("artiste","id",i.id).then(function(e){r.artiste=e.data;r.artiste.artiste.text=o.trustAsHtml(r.artiste.artiste.text);r.artiste.artiste.name=o.trustAsHtml(r.artiste.artiste.name);for(var n in r.artiste.videos){r.artiste.videos[n].name=o.trustAsHtml(r.artiste.videos[n].name);r.artiste.videos[n].frame=t.iframe(r.artiste.videos[n].url)}t.soundcloud(e.data.artiste.itw,"lol")}).then(function(){e.list_minpics(r.artiste.artiste.path_pics).then(function(e){r.pics=e.data})})}]).controller("artistesCtrl",["pics","getData","$scope","$routeParams","$http","$sce",function(e,t,n,r,i,s){n.pageClass="page-artistes";t.artistes_by("style").then(function(e){n.artistes=e.data}).then(function(){for(var e in n.artistes){if(n.artistes[e].artistes.length==0)delete n.artistes[e];else{for(var t in n.artistes[e].artistes){n.artistes[e].artistes[t].name=s.trustAsHtml(n.artistes[e].artistes[t].name)}}}})}]).controller("locauxCtrl",["pics","getData","$scope","$routeParams","$http","$sce",function(e,t,n,r,i,s){n.pageClass="page-locaux";t.art_cat(0).then(function(e){n.styles=e.data;console.log(e.data)}).then(function(){for(var e in n.styles){if(n.styles[e].artistes.length==0){delete n.styles[e]}else{for(var t in n.styles[e].artistes){n.styles[e].artistes[t].name=s.trustAsHtml(n.styles[e].artistes[t].name)}}}})}]).controller("visiteursCtrl",["pics","getData","$scope","$routeParams","$http","$sce",function(e,t,n,r,i,s){n.pageClass="page-visiteurs";t.art_cat(1).then(function(e){n.styles=e.data;console.log(e.data)}).then(function(){for(var e in n.styles){if(n.styles[e].artistes.length==0){delete n.styles[e]}else{for(var t in n.styles[e].artistes){n.styles[e].artistes[t].name=s.trustAsHtml(n.styles[e].artistes[t].name)}}}})}]).controller("quartierCtrl",["tools","getData","$scope","$routeParams","$http","$sce",function(e,t,n,r,i,s){n.pageClass="page-quartier";t.related("quartier","id",r.id).then(function(t){n.quartier=t.data;n.quartier.quartier.text=s.trustAsHtml(n.quartier.quartier.text);n.quartier.quartier.name=s.trustAsHtml(n.quartier.quartier.name);for(var r in n.quartier.videos){n.quartier.videos[r].frame=e.iframe(n.quartier.videos[r].url);n.quartier.videos[r].name=s.trustAsHtml(n.quartier.videos[r].name)}for(var r in n.quartier.artistes){n.quartier.artistes[r].name=s.trustAsHtml(n.quartier.artistes[r].name)}})}]).controller("quartiersCtrl",["pics","getData","$scope","$routeParams","$http","$sce",function(e,t,n,r,i,s){n.pageClass="page-quartiers";t.quartiers("").then(function(e){n.quartiers=e.data}).then(function(){n.quartiers.forEach(function(t){t.name=s.trustAsHtml(t.name);e.list_pics(t.path_pics).then(function(e){n.truc=e.data}).then(function(){if(n.truc=="T"){t.imgs=["img/badges/visiteur.jpg"]}else{t.imgs=n.truc}})})})}]).controller("picsCtrl",["pics","getData","$scope","$routeParams","$http","$sce",function(e,t,n,r,i,s){function o(e){for(var t,n,r=e.length;r;t=Math.floor(Math.random()*r),n=e[--r],e[r]=e[t],e[t]=n);return e}n.pageClass="page-pics";$("#pics_viewer").hide();n.back=[];t.artistes("").then(function(e){n.artistes=e.data}).then(function(){for(var t in n.artistes){n.artistes[t].name=s.trustAsHtml(n.artistes[t].name);e.list_minpics(n.artistes[t].path_pics).then(function(e){for(var t in e.data){n.back.push(e.data[t])}}).then(function(){n.back=o(n.back)})}});t.quartiers("").then(function(e){n.quartiers=e.data}).then(function(){for(var t in n.quartiers){n.quartiers[t].name=s.trustAsHtml(n.quartiers[t].name);e.list_minpics(n.quartiers[t].path_pics).then(function(e){for(var t in e.data){n.back.push(e.data[t])}}).then(function(){n.back=o(n.back)})}});console.log(n.back);n.name_click=function(t){n.index=0;e.list_pics(t).then(function(e){n.pics=e.data;n.pic=e.data[n.index]}).then(function(){$("#pics_viewer").show()})};if(angular.isDefined(r.path)){n.name_click(r.path)}n.next_pic=function(){n.index=n.index+1;if(!angular.isDefined(n.pics[n.index]))n.index=0;n.pic=n.pics[n.index]};n.prev_pic=function(){n.index=n.index-1;if(n.index<0)n.index=n.pics.length-1;n.pic=n.pics[n.index]};n.change=function(e){n.pic=n.pics[n.index];console.log(n.pic)};$(".pic_close").click(function(){$("#pics_viewer").hide();n.index=0});$(document).keyup(function(e){if(e.keyCode==27){$("#pics_viewer").hide()}if(e.keyCode==39){n.index=n.index+1;if(n.index<0)n.index=0;n.change(n.index)}if(e.keyCode==37){n.index=n.index-1;if(n.index<0)n.index=n.pics.length-1;n.change(n.index)}})}]).controller("aboutCtrl",["getData","$scope","$sce",function(e,t,n){t.pageClass="page-about";e.about().then(function(e){t.text=n.trustAsHtml(e.data.text)})}]).controller("contactCtrl",["$http","getData","$scope","$sce",function(e,t,n,r){n.pageClass="page-contact";n.txt_btn="Envoyer le message.";t.contact().then(function(e){n.text=r.trustAsHtml(e.data.text)});Recaptcha.create("6LejkfkSAAAAAFzdmV65iEt7omLdnJXZoMpMVw3e","captcha",{theme:"red"});n.send_msg=function(t){n.message="";n.txt_btn="Verification en cour...";var r=Recaptcha.get_response();var i=Recaptcha.get_challenge();n.tosend=t;e.get("API/api.php?get=captcha&rep="+r+"&chal="+i).then(function(e){if(e.data=="true"){n.txt_btn="Verification ok! Envoi en cour.";$.post("API/contact.php",{mail:n.tosend.email,subject:n.tosend.subject,txt:n.tosend.txt}).done(function(e){if(e!="true"){$("#message").html(e);$(".btn_send").html("Renvoyer le message")}else{$("#message").html(" ");$(".btn_send").html("Message envoyé!")}})}else{n.message="Vérification échouée. Veuillez entrer le code à nouveau.";n.txt_btn="Renvoyer le message.";Recaptcha.reload();Recaptcha.focus_response_field()}})}}]).controller("partnersCtrl",["$http","getData","$scope","$sce",function(e,t,n,r){n.pageClass="page-partners";t.partners().then(function(e){n.partners=e.data}).then(function(){n.partners.forEach(function(e){e.desc=r.trustAsHtml(e.desc)})})}])
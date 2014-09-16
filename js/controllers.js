'use strict';

angular.module('myApp.controllers', ['ngCookies', 'ngAnimate'])
.run(function($cookieStore){
	// $cookieStore.remove("visited");
	$cookieStore.put("visited", "first");
})

.controller('headCtrl', ['pics', 'filterFilter', '$scope', '$http', '$sce', 'getData', 'tools',  '$cookies', '$cookieStore', '$log', '$animate'
	,function(pics, filterFilter, $scope, $http, $sce, getData, tools,  $cookies, $cookieStore, $log, $animate){

	$scope.title = "Welcome at Toulouse Acoustics";

	$scope.pageClass = 'page-home';

	$scope.load = "ok";

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
				$scope.artistes[i].name = "";
			else{
				for (var j in $scope.artistes[i].artistes){
					$scope.artistes[i].artistes[j].name = $sce.trustAsHtml($scope.artistes[i].artistes[j].name);
				}
			}
		}
	})
}])

.controller('locauxCtrl', ['pics', 'getData', '$scope', '$routeParams', '$http', '$sce', function(pics, getData, $scope, $routeParams, $http, $sce){
	$scope.pageClass = 'page-locaux';

	getData.art_cat(0).then(function(data){
		$scope.artistes = data.data;
	});
}])

.controller('visiteursCtrl', ['pics', 'getData', '$scope', '$routeParams', '$http', '$sce', function(pics, getData, $scope, $routeParams, $http, $sce){
	$scope.pageClass = 'page-visiteurs';

	getData.art_cat(1).then(function(data){
		$scope.artistes = data.data;
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
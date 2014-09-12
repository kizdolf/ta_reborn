'use strict';

angular.module('myApp.controllers', ['ngCookies'])
.run(function($cookieStore){
	// $cookieStore.remove("visited");
	$cookieStore.put("visited", "first");
})

.controller('headCtrl', ['pics', 'filterFilter', '$scope', '$http', '$sce', 'getData', 'tools',  '$cookies', '$cookieStore', '$log'
	,function(pics, filterFilter, $scope, $http, $sce, getData, tools,  $cookies, $cookieStore, $log){

	$scope.title = "Welcome at Toulouse Acoustics";

	$scope.pageClass = 'page-home';

	$scope.load = "ok";

	getData.weekly().then(function(data){
		$scope.weekly = data.data;
		$scope.weekly.video.frame = tools.iframe($scope.weekly.video.url);
		$scope.load = "";
		$scope.weekly.video.text = $sce.trustAsHtml($scope.weekly.video.text);
		$scope.weekly.artiste.text = $sce.trustAsHtml($scope.weekly.artiste.text);
		$scope.weekly.quartier.text = $sce.trustAsHtml($scope.weekly.quartier.text);
	}).then(function(){
		pics.list_pics($scope.weekly.artiste.path_pics).then(function(data){
			$scope.truc = data.data;
		});
	});
	// .then(function(){
	// 		$scope.first = $cookieStore.get("visited");
	// 		if ($scope.first == "first") {
	// 			$scope.fs = "1";
	// 		}
	// });

	$scope.change_video = function(choice){
		var id = parseInt($scope.weekly.video.id);
		getData.one_video(id, choice).then(function(data){
			$scope.weekly.video = data.data;
			$scope.weekly.video.frame = tools.iframe($scope.weekly.video.url);
			$scope.weekly.video.text = $sce.trustAsHtml($scope.weekly.video.text);
		})
		.then(function(){
			var ids =  $scope.weekly.video.id_artiste + "," +  $scope.weekly.video.id_quartier;
			getData.related('video', 'id', ids).then(function(data){
				$scope.weekly.artiste = data.data.artiste;
				$scope.weekly.quartier = data.data.quartier;
				$scope.weekly.artiste.text = $sce.trustAsHtml($scope.weekly.artiste.text);
				$scope.weekly.quartier.text = $sce.trustAsHtml($scope.weekly.quartier.text);
			});
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

}])

.controller('artisteCtrl', ['pics', 'tools', 'getData', '$scope', '$routeParams', '$http', '$sce', function(pics, tools, getData, $scope, $routeParams, $http, $sce){

	$scope.pageClass = 'page-artiste';

	getData.related('artiste', 'name', $routeParams.id).then(function(data){
		$scope.artiste = data.data;
		$scope.artiste.artiste.text = $sce.trustAsHtml($scope.artiste.artiste.text);
		for (var i in $scope.artiste.videos){
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
	});
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

	getData.related('quartier', 'name', $routeParams.id).then(function(data){
		$scope.quartier = data.data;
		for (var i in $scope.quartier.videos){
			$scope.quartier.videos[i].frame = tools.iframe($scope.quartier.videos[i].url);
		}
	});
}])

.controller('quartiersCtrl', ['pics', 'getData', '$scope', '$routeParams', '$http', '$sce', function(pics, getData, $scope, $routeParams, $http, $sce){

	$scope.pageClass = 'page-quartiers';
	getData.quartiers('').then(function(data){

		$scope.quartiers = data.data;
		
	}).then(function(){

	$scope.quartiers.forEach(function(i){
		pics.list_pics(i.path_pics).then(function(data){
			$scope.truc = data.data;
		}).then(function(){
			if ($scope.truc == "T") {
				i.imgs = ["img/badges/weekly.png"];
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

	$('.pic_close').click(function(){
		$('#pics_viewer').hide();
		$scope.index = 0;
	});

	$(document).keyup(function(e) {
		if (e.keyCode == 27) {
			$('#pics_viewer').hide();
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
		console.log($scope.partners);
	});

}]);
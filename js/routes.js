'use strict';

angular.module('myApp.routes', ['ngRoute'])

.config(['$routeProvider', '$locationProvider', function($routeProvider, $locationProvider){

	$routeProvider.when('/home', {
		templateUrl: 'views/home.html',
		controller: 'headCtrl'
	});

	$routeProvider.when('/artistes/:id', {
		templateUrl :'views/artiste.html',
		controller: 'artisteCtrl'
	});

	$routeProvider.when('/artistes', {
		templateUrl :'views/artistes.html',
		controller: 'artistesCtrl'
	});

	$routeProvider.when('/artistes_locaux', {
		templateUrl :'views/locaux.html',
		controller: 'locauxCtrl'
	});

	$routeProvider.when('/artistes_visiteurs', {
		templateUrl :'views/visiteurs.html',
		controller: 'visiteursCtrl'
	});

	$routeProvider.when('/quartiers', {
		templateUrl :'views/quartiers.html',
		controller: 'quartiersCtrl'
	});

	$routeProvider.when('/quartiers/:id', {
		templateUrl :'views/quartier.html',
		controller: 'quartierCtrl'
	});

	$routeProvider.when('/portfolio', {
		templateUrl :'views/pics.html',
		controller: 'picsCtrl'
	});

	$routeProvider.when('/about', {
		templateUrl :'views/about.html',
		controller: 'headCtrl'
	});

	$routeProvider.when('/partners', {
		templateUrl :'views/partners.html',
		controller: 'partnersCtrl'
	});


	$routeProvider.when('/contact', {
		templateUrl :'views/contact.html',
		controller: 'headCtrl'
	});

	$routeProvider.otherwise({
		redirectTo: '/home'
	});

	// // use the HTML5 History API
	// $locationProvider.html5Mode(true);

}]);
'use strict';

/**
 * @ngdoc service
 * @name mytodoApp.auth
 * @description
 * # auth
 * Service in the mytodoApp.
 */
angular.module('mytodoApp')
  .service('Authentication', function Authentication($q, $http, $timeout) {
		var authencatedUSer = null
		return {
			requestUser: function()
			{
				console.log("request user function is called");
				var deferred = $q.defer();
				$http.get('user.json').success(function(user)
				{
					$timeout(function(){
						console.log("in timeout function to get the authenticated user");
					authencatedUSer = user;
					deferred.resolve(user);
				},1000);
				}).error(function(error)
				{
					deferred.reject(error);
				})
				return deferred.promise;
			},
			getUser: function()
			{
				return authencatedUSer;
			},

			exists: function()
			{
				return authencatedUSer != null;
			},
			//login method
			login: function(credentials)
			{
				var deferred = $q.defer();
				$http.post('/auth/login',credentials).success(function(user)
				{
					if(user)
					{
						authencatedUSer = user;
						deferred.resolve(user);
					}
					else
					{
						deferred.reject('given credentials are incorrect');
					}

				}).error(function(error)
				{

				});
				return deferred.promise;
			},

			logout: function()
			{
				authencatedUSer = null;
			},

			isDeveloper: function()
			{
				return this.exists() && authencatedUSer.type == 'developer';
			}
		}  	
  });

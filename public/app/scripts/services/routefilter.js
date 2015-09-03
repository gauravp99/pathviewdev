'use strict';

/**
 * @ngdoc service
 * @name mytodoApp.RouteFilter
 * @description
 * # RouteFilter
 * Service in the mytodoApp.
 */
angular.module('mytodoApp')
  .service('RouteFilter', function RouteFilter() {
var filters = [];
  	return {
  		register: function(name, routes, callback, redirectUrl)
  		{
  			redirectUrl = typeof redirectUrl != "undefined" ? redirectUrl : null;

  			filters.push({
  				name: name,
  				routes:routes,
  				callback: callback,
  				redirectUrl: redirectUrl
  			});

  		},
  	}
    
  });

'use strict';

/**
 * @ngdoc service
 * @name mytodoApp.application
 * @description
 * # application
 * Service in the mytodoApp.
 */
angular.module('mytodoApp')
  .service('Application', function Application() {

var ready = false, registerListener = [];
var callListeners = function()
{
	console.log("in calllisteners");
	for(var i = registerListener.length-1 ; i >=0 ; i--)
	{
		registerListener[i]();
	}
}
  	return{
  		isReady: function()
  		{

  				return ready;
  		},

  		makeReady: function()
  		{
  			console.log("now ready is true");
  			ready = true;
console.log("now ready: "+ready);

  		},

  		registerListener: function(callback)
  		{
  			if(ready) callback();
  			else registerListener.push(callback);
  		}
  	}
    
  });

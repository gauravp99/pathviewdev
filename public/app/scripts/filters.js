angular.module('authApp')

.run(function (RouteFilter, Authentication)
{
  
  RouteFilter.register('auth', ['/profile'],function()
  {
  return Authentication.exists();

  },'signin');
  RouteFilter.register('guest', ['/signin'],function()
  {

  },'/');
  RouteFilter.register('developer', ['/settings'],function()
  {

return Auhtnetication.isDeveloper();
  },'/');
})
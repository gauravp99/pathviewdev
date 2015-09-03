'use strict';

describe('Service: RouteFilter', function () {

  // load the service's module
  beforeEach(module('mytodoApp'));

  // instantiate service
  var RouteFilter;
  beforeEach(inject(function (_RouteFilter_) {
    RouteFilter = _RouteFilter_;
  }));

  it('should do something', function () {
    expect(!!RouteFilter).toBe(true);
  });

});

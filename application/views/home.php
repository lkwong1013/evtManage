<!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">
        <h1>Hello, world!</h1>
        <p>This is a template for a simple marketing or informational website. It includes a large callout called a jumbotron and three supporting pieces of content. Use it as a starting point to create something more unique.</p>
        <p><a class="btn btn-primary btn-lg" href="#" role="button">Learn more &raquo;</a></p>
      </div>
    </div>

    <div class="container">
      <!-- Example row of columns -->
      <div class="row">
        <div class="table-responsive" ng-app="myApp" ng-controller="planetController">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Header</th>
                  <th>Header</th>
                  <th>Header</th>
                  <th>Header</th>
                </tr>
              </thead>
              <tbody>
                <tr ng-repeat="x in names">
					<td>{{x.name}}</td>
					<td>{{x.distanc}}</td>
					<td>{{x.light}}</td>
					<td>{{x.light}}</td>
					<td>{{x.light}}</td>
                </tr>
              </tbody>
            </table>
          </div>
      </div>

      <hr>
    </div> <!-- /container -->
    <script>

	var app = angular.module('myApp', []);
	app.controller('planetController', function($scope, $http) {
	$http.get("http://samaxxw.com/AngularJS/planet2.json")
	.success(function(response) {$scope.names = response;});
	});
	</script>
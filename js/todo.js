var app = angular.module('todoApp', ['ngResource', 'ng-file-model', 'ui.bootstrap']);

app.factory('Task', ['$resource', function ($resource) {
        return $resource('/api/task/:id', null,
                {
                    get: {method: 'GET', params: {}, isArray: false},
                    query: {method: 'GET', params: {}, isArray: true},
                    count: {method: 'GET', params: {'count': true}, isArray: false},
                    insert: {method: 'POST', params: {}, isArray: false},
                    done: {method: 'PUT', params: {}, isArray: false}
                });
    }])
        .factory('Security', ['$resource', function ($resource) {
                return $resource('/api/login', null,
                        {
                            auth: {method: 'POST', params: {}, isArray: false}
                        });
            }]);

app.controller('TodoAddController', ['$scope', 'Task', function ($scope, Task) {
        $scope.new = {};
        $scope.error = '';

        $scope.addTodo = function () {
            $scope.error = $scope.success = '';

            Task.insert($scope.new).$promise.then(function (data) {
                console.log(data);
                if (data.error) {
                    $scope.error = data.error;
                } else {
                    $scope.success = 'Saved!';
                    $scope.new = {};
                }
            }, function () {
                alert('Oops, somethig wrong!');
            });
        };
    }]);

app.controller('TodoSecurityController', ['$scope', '$window', 'Security', function ($scope, $window, Security) {

        $scope.login = function () {
            Security.auth({
                'username': $scope.username,
                'password': $scope.password
            })
                    .$promise.then(function (data) {
                        if (data.success) {
                            $window.location.href = '/admin';
                        } else {
                            $scope.error = data.error;
                        }
                    }, function () {
                        alert('Oops, somethig wrong!');
                    });
        };
    }]);

app.controller('TodoListController', ['$scope', 'Task', function ($scope, Task) {

        $scope.new = {};
        $scope.currentPage = 1;
        $scope.itemsPerPage = 3;
        $scope.sortType = 'id'; // set the default sort type
        $scope.sortReverse = false;  // set the default sort order

        $scope.setPage = function (pageNo) {
            $scope.currentPage = pageNo;
        };

        $scope.pageChanged = function () {
            $scope.list();
        };

        Task.count().$promise.then(function (data) {
            $scope.totalItems = data.count;
        });

        $scope.list = function () {
            Task.query({
                'page': $scope.currentPage,
                'sortType': $scope.sortType,
                'sortReverse': $scope.sortReverse
            }).$promise.then(function (data) {
                $scope.todos = data;
            });
        };

        $scope.list();

        $scope.addTodo = function () {
            $scope.todos.push($scope.new);
            $scope.new = {};
        };

        $scope.remaining = function () {
            var count = 0;
            angular.forEach($scope.todos, function (todo) {
                count += todo.done ? 0 : 1;
            });
            return count;
        };

        $scope.done = function (event) {
            Task.done({'id': event.target.value, 'done': true}).$promise.then(function (data) {
                if (data.error) {
                    $scope.error = data.error;
                } else {
                    $scope.success = 'Saved!';
                    $scope.new = {};
                }
            }, function () {
                alert('Oops, somethig wrong!');
            });
        };
    }]);
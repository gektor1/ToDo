<!doctype html>
<html ng-app="todoApp">
    <?php include INC_ROOT . '/app/views/partial/head.php'; ?>
    <body>
        <?php include INC_ROOT . '/app/views/partial/navbar.php'; ?>
        <div class="container">
            <div class="content">
                <h1>Todo</h1>
                <div ng-controller="TodoListController">
                    <div class="alert alert-danger" role="alert" ng-show="error">
                        {{ error}}
                    </div>
                    <div class="alert alert-success" role="alert" ng-show="success">
                        {{ success}}
                    </div>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>
                                    <a href="#" ng-click="sortType = 'done'; sortReverse = !sortReverse; list();">
                                        Done
                                        <span ng-show="sortType == 'done' && !sortReverse" class="fa fa-caret-down"></span>
                                        <span ng-show="sortType == 'done' && sortReverse" class="fa fa-caret-up"></span>
                                    </a>
                                </th>
                                <th>
                                    <a href="#" ng-click="sortType = 'user'; sortReverse = !sortReverse; list();">
                                        User
                                        <span ng-show="sortType == 'user' && !sortReverse" class="fa fa-caret-down"></span>
                                        <span ng-show="sortType == 'user' && sortReverse" class="fa fa-caret-up"></span>
                                    </a>
                                </th>
                                <th>
                                    <a href="#" ng-click="sortType = 'email'; sortReverse = !sortReverse; list();">
                                        Email
                                        <span ng-show="sortType == 'email' && !sortReverse" class="fa fa-caret-down"></span>
                                        <span ng-show="sortType == 'email' && sortReverse" class="fa fa-caret-up"></span>
                                    </a>
                                </th>
                                <th>Description</th>
                                <th>Image</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-show="todos.length == 0">
                                <td colspan="5">
                                    No records
                                </td>
                            </tr>
                            <tr ng-repeat="todo in todos" ng-class="{'success': todo.done == 1}">
                                <td>
                                    <input value="{{ todo.id}}" ng-show="todo.done == 0" type="checkbox" ng-model="todo.done" ng-click="done($event)">
                                </td>
                                <td>
                                    {{todo.user}}
                                </td>
                                <td>
                                    {{todo.email}}
                                </td>
                                <td>
                                    {{todo.description}}
                                </td>
                                <td>
                                    <img src="<?php echo $base_url ?>/upload/{{todo.id}}/{{todo.image}}">
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <pagination total-items="totalItems" ng-show="todos.length != 0" ng-model="currentPage" ng-change="pageChanged()" 
                                class="pagination-sm" items-per-page="itemsPerPage"></pagination>
                </div>
            </div>
        </div><!-- /.container -->
    </body>
</html>


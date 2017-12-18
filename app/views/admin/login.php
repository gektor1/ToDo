<!doctype html>
<html ng-app="todoApp">
    <?php include INC_ROOT . '/app/views/partial/head.php'; ?>
    <body>
        <?php include INC_ROOT . '/app/views/partial/navbar.php'; ?>
        <div class="container">
            <div class="content">
                <h1>Todo</h1>
                <div ng-controller="TodoSecurityController">
                    <div class="alert alert-danger" role="alert" ng-show="error">
                        {{ error}}
                    </div>
                    <form class="form-horizontal" ng-submit="login()">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input ng-model="username" type="text" 
                                   class="form-control" id="username" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input ng-model="password" type="password" 
                                   class="form-control" id="password" name="password" required>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-4 col-sm-2">
                                <button type="submit" class="btn btn-default">Login</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div><!-- /.container -->
    </body>
</html>
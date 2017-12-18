<!doctype html>
<html ng-app="todoApp">
    <?php include INC_ROOT . '/app/views/partial/head.php'; ?>
    <body>
        <?php include INC_ROOT . '/app/views/partial/navbar.php'; ?>
        <div class="container">
            <div class="content">
                <h1>Todo</h1>
                <div ng-controller="TodoAddController">
                    <div class="alert alert-danger" role="alert" ng-show="error">
                        {{ error}}
                    </div>
                    <div class="alert alert-success" role="alert" ng-show="success">
                        {{ success}}
                    </div>
                    <form class="form-horizontal" ng-submit="addTodo()">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input ng-model="new.user" type="text" 
                                   class="form-control" id="user" name="user" placeholder="Jane Doe" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input ng-model="new.email" type="email" 
                                   class="form-control" id="email" name="email" placeholder="jane.doe@example.com" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea ng-model="new.description" 
                                      class="form-control" id="description" name="description" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="image">Image</label>
                            <input ng-file-model="new.image" type="file" 
                                   class="form-control" id="image" name="image" required>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-4 col-sm-2">

                                <button ng-show="new.user && new.email && new.description" type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                                    Preview
                                </button>
                            </div>
                            <div class="col-sm-2">
                                <button type="submit" class="btn btn-default">Add</button>
                            </div>
                        </div>
                    </form>

                    <!-- Modal -->
                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h4 class="modal-title" id="myModalLabel">Preview</h4>
                                </div>
                                <div class="modal-body">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>User</th>
                                                <th>Email</th>
                                                <th>Description</th>
                                                <th>Image</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    {{ new.user }}
                                                </td>
                                                <td>
                                                    {{ new.email }}
                                                </td>
                                                <td>
                                                    {{ new.description }}
                                                </td>
                                                <td class="thumbnail">
                                                    <img ng-show="new.image" src="{{ new.image.data }}">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div><!-- /.container -->
    </body>
</html>
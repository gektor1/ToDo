<?php

/**
 * The default api controller.
 */
class Api extends Controller {

    /**
     * The default controller method.
     *
     * @return void
     */
    public function task() {
        $data = $this->getApiRequestData();

        switch ($this->method()) {
            case self::METHOD_GET:

                if (!empty($_GET['count'])) {
                    $count = $this->getEntityManager()->count('Task');
                    echo json_encode(['count' => $count]);
                }
                else {
                    $limit = 3;
                    $page = 1;
                    $sortType = !empty($_GET['sortType']) ? $_GET['sortType'] : 'id';
                    $sortReverse = isset($_GET['sortReverse']) ? ($_GET['sortReverse'] == 'true'  ? 'desc' : 'asc' ) : 'asc';
                    
                    if (!empty($_GET['page'])) $page = $_GET['page'];

                    $all = $this->getEntityManager()->findAll('Task', $sortType . ' ' . $sortReverse, $limit, $limit * ($page - 1));
                    echo json_encode($all);
                }

                break;
            case self::METHOD_POST:

                $task = new Task();
                $taskService = new TaskService($task);

                if ($taskService->isValid($data)) {

                    $taskService->bindPartial($data);
                    $this->getEntityManager()->save($taskService->getTask());

                    $taskService->upload($data['image']);
                    $this->getEntityManager()->save($taskService->getTask());

                    echo json_encode($taskService->getTask());
                } else {
                    echo json_encode(['error' => $taskService->getErrors()[0]]);
                }

                break;
            case self::METHOD_PUT:

                $task = $this->getEntityManager()->findBy('Task', ['id' => $data['id']]);
                foreach ($data as $key => $value) {
                    if ($key == 'id') continue;
                    $task->$key = $value;
                }
        
                $this->getEntityManager()->save($task);
                echo json_encode($task);
                
                break;

            default:
                break;
        }
    }

    public function login() {
        $data = $this->getApiRequestData();

        switch ($this->method()) {
            case self::METHOD_POST:

                $securityService = new SecurityService($this->getEntityManager());
                if ($securityService->login($data['username'], $data['password'])) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['error' => 'Wrong credentials!']);
                }

                break;
        }
    }

}

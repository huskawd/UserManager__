<?php

namespace App\Router;

use App\Model\User;
use App\Response\JsonResponse;
use App\Service\UserService;

class Router
{
    public function __construct(
        private UserService $userService,
        private JsonResponse $response
    ) {
    }
    public function handle(string $method, string $uri): void
    {
        if ($method === 'GET' && $uri === '/users') {
            $this->getUsers();
            return;
        }

        if ($method === 'POST' && $uri === '/users') {
            $this->createUser();
            return;
        }

        if ($method === 'DELETE' && $uri === '/users') {
            $this->deleteUser();
            return;
        }

        $this->response->send([
            'message' => 'Маршрут не найден',
        ], 404);


    }

    private function getUsers(): void
    {
        $users = $this->userService->getAll();
        $result = [];
        foreach ($users as $user) {
            $result[] = [
                    'id' => $user->id,
                    'surname' => $user->surname,
                    'name' => $user->name,
                    'email' => $user->email,
            ];
        }
        $this->response->send($result);
    }

    private function createUser(): void
    {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        $user = new User(
            null,
            $data['surname'],
            $data['name'],
            $data['email']
        );
        $this->userService->add($user);

        $this->response->send([
                'message' => 'Пользователь успешно добавлен',
        ], 201);

    }

    private function deleteUser(): void
    {
        $id = $_GET['id'];
        $this->userService->delete((int)$id);
        $this->response->send([
                'message' => 'Пользователь успешно удалён',
        ]);
    }
}

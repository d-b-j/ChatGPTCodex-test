<?php

namespace Src\Controllers;

use Src\Models\User;
use Src\Services\UserService;

class UserController
{
    private UserService $userService;

    public function __construct()
    {
        global $pdo;

        $userModel =
            new User($pdo);

        $this->userService =
            new UserService(
                $userModel
            );
    }

    /**
     * POST /v1/user/create
     */
    public function create(): void
    {
        try {

            $input =
                json_decode(
                    file_get_contents(
                        'php://input'
                    ),
                    true
                );

            $memberId =
                trim(
                    $input['member_id']
                    ?? ''
                );

            if (!$memberId) {

                http_response_code(422);

                echo json_encode([
                    'success' => false,
                    'message' =>
                        'member_id required'
                ]);

                exit;
            }

            $result =
                $this->userService
                    ->create(
                        $memberId
                    );

            echo json_encode([
                'success' =>
                    $result['success'],

                'message' =>
                    $result['message'],

                'data' =>
                    $result['credentials']
                    ?? null,

                'timestamp' =>
                    date('c')
            ]);

        } catch (\Throwable $e) {

            http_response_code(500);

            echo json_encode([
                'success' => false,

                'message' =>
                    $e->getMessage()
            ]);
        }
    }
}
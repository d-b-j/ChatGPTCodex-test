<?php

namespace App\Services;

use App\Config\Database;
use App\Models\User;

class UserService
{
    private User $userModel;

    public function __construct() {
        $pdo = Database::getInstance()->getConnection();
        $this->userModel = new User($pdo);
    }

    /**
     * Create member user
     */
    public function create(
        array $member
    ): array {

        if (
            $this->userModel
                ->existsByMemberId(
                    $member['member_id']
                )
        ) {

            return [
                'success' => false,
                'message' =>
                    'User already exists'
            ];
        }

        $passwordHash =
            password_hash(
                '123456',
                PASSWORD_BCRYPT
            );

        $created =
            $this->userModel->create([
                'member_id' =>
                    $member['member_id'],
                'username' =>
                    $member['member_no'],
                'password_hash' =>
                    $passwordHash,
                'role' => 10,
                'status' => 1
            ]);

        if (!$created) {

            return [
                'success' => false,
                'message' =>
                    'Failed to create user'
            ];
        }

        return [
            'success' => true,
            'message' =>
                'User created successfully',
            'credentials' => [
                'username' =>
                    $member['member_id'],
                'password' =>
                    '123456'
            ]
        ];
    }
}
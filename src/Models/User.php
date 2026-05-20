<?php

namespace App\Models;

use App\Config\Database;
use PDO;
use PDOException;

class User
{
    private PDO $db;

    public function __construct(PDO $connection)
    {
        $this->db = $connection;
    }

    /**
     * Create user
     */
    public function create(
        array $data
    ): bool {

        $query = "
            INSERT INTO users (
                `member_id`, 
                `username`, 
                `password_hash`, 
                `role`, 
                `status`,
                `last_login_at`,
                `created_at`
            ) VALUES (
                :member_id,
                :username,
                :password_hash,
                :role,
                :status,
                :last_login_at,
                NOW()
            )
        ";

        $stmt =
            $this->db->prepare(
                $query
            );

        return $stmt->execute([

            ':member_id' =>
                $data['member_id'],
            ':username' =>
                $data['username'],
            ':password_hash' =>
                $data['password_hash'],
            ':role' =>
                $data['role'],
            ':status' =>
                $data['status'],
            ':last_login_at' =>
                null
        ]);
    }


    /**
     * Get user by member ID
     *
     * @param string $memberId Member ID
     * @return array|null User data or null if not found
     * @throws PDOException
     */
    public function getUserByMemberId(string $memberId): ?array
    {
        $query = "
            SELECT *
            FROM users
            WHERE member_id = :member_id
        ";

        $stmt = $this->db->prepare($query);

        $stmt->execute([
            ':member_id' => $memberId
        ]);

        $u = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $u[0];
    }


    /**
     * Check existing user
     */
    public function existsByMemberId(
        string $memberId
    ): bool {

        $query = "
            SELECT COUNT(*) as total
            FROM users
            WHERE member_id = :member_id
        ";

        $stmt =
            $this->db->prepare(
                $query
            );

        $stmt->execute([
            ':member_id' => $memberId
        ]);

        $result =
            $stmt->fetch(PDO::FETCH_ASSOC);

        return (int)$result['total'] > 0;
    }
}
<?php
/**
 * Member Model
 * 
 * Handles all database operations related to members.
 * Implements CRUD operations with prepared statements.
 * 
 * PHP Version: 8.0+
 */

namespace App\Models;

use App\Config\Database;
use PDOException;

class Member
{
    /**
     * Database connection instance
     * @var Database
     */
    protected Database $db;

    /**
     * Table name
     * @var string
     */
    protected string $table = 'members';

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Create a new member
     * 
     * @param array $data Member data
     * @return string Member ID
     * @throws PDOException
     */
    public function create(array $data): string
    {
        $sql = "INSERT INTO {$this->table} 
                (id, member_no, first_name, last_name, full_name, email, phone, batch_year, gender, address, profile_photo, status, qr_code_path, qr_code_content_url, qr_code_generated_at)
                VALUES 
                (:id, :member_no, :first_name, :last_name, :full_name, :email, :phone, :batch_year, :gender, :address, :profile_photo, :status, :qr_code_path, :qr_code_content_url, :qr_code_generated_at)";

        try {
            $stmt = $this->db->prepare($sql);
            $id = $data['id'] ?? $this->generateUUID();

            $stmt->execute([
                ':id'             => $id,
                ':member_no'      => $data['member_no'] ?? null,
                ':first_name'     => $data['first_name'],
                ':last_name'      => $data['last_name'],
                ':full_name'      => $data['full_name'],
                ':email'          => $data['email'] ?? null,
                ':phone'          => $data['phone'] ?? null,
                ':batch_year'     => $data['batch_year'] ?? null,
                ':gender'         => $data['gender'] ?? null,
                ':address'        => $data['address'] ?? null,
                ':profile_photo'  => $data['profile_photo'] ?? null,
                ':status'         => $data['status'] ?? 'active',
                ':qr_code_path'   => $data['qr_code_path'] ?? null,
                ':qr_code_content_url' => $data['qr_code_content_url'] ?? null,
                ':qr_code_generated_at' => $data['qr_code_generated_at'] ?? null,
            ]);

            return $id;

        } catch (PDOException $e) {
            // Check for duplicate entry errors
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                throw new PDOException('Email, phone, or member number already exists', 1062);
            }
            throw $e;
        }
    }

    /**
     * Retrieve member by ID
     * 
     * @param string $id Member ID
     * @return array|null Member data or null if not found
     * @throws PDOException
     */
    public function getById(string $id): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            $result = $stmt->fetch();

            return $result ?: null;

        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * Retrieve member by email
     * 
     * @param string $email Member email
     * @return array|null Member data or null if not found
     * @throws PDOException
     */
    public function getByEmail(string $email): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':email' => $email]);
            $result = $stmt->fetch();

            return $result ?: null;

        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * Retrieve member by member number
     * 
     * @param string $memberNo Member number
     * @return array|null Member data or null if not found
     * @throws PDOException
     */
    public function getByMemberNo(string $memberNo): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE member_no = :member_no LIMIT 1";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':member_no' => $memberNo]);
            $result = $stmt->fetch();

            return $result ?: null;

        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * Update member information
     * 
     * @param string $id Member ID
     * @param array $data Data to update
     * @return bool Success
     * @throws PDOException
     */
    public function update(string $id, array $data): bool
    {
        // Build dynamic UPDATE clause
        $updateFields = [];
        $params = [':id' => $id];

        $allowedFields = [
            'member_no', 'first_name', 'last_name', 'full_name',
            'email', 'phone', 'batch_year', 'gender', 'address',
            'profile_photo', 'status', 'qr_code_path', 'qr_code_content_url', 'qr_code_generated_at'
        ];

        foreach ($data as $field => $value) {
            if (in_array($field, $allowedFields)) {
                $updateFields[] = "{$field} = :{$field}";
                $params[":{$field}"] = $value;
            }
        }

        if (empty($updateFields)) {
            throw new \InvalidArgumentException('No valid fields to update');
        }

        $sql = "UPDATE {$this->table} SET " . implode(', ', $updateFields) . " WHERE id = :id";

        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);

        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                throw new PDOException('Email, phone, or member number already in use', 1062);
            }
            throw $e;
        }
    }

    /**
     * Delete a member
     * 
     * @param string $id Member ID
     * @return bool Success
     * @throws PDOException
     */
    public function delete(string $id): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";

        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':id' => $id]);

        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * Check if member exists
     * 
     * @param string $id Member ID
     * @return bool
     * @throws PDOException
     */
    public function exists(string $id): bool
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE id = :id";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            $result = $stmt->fetch();

            return $result['count'] > 0;

        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * Update QR reference fields for a member.
     *
     * @param string $id
     * @param array $qrData
     * @return bool
     * @throws PDOException
     */
    public function updateQrReference(string $id, array $qrData): bool
    {
        $sql = "UPDATE {$this->table}
                SET qr_code_path = :qr_code_path,
                    qr_code_content_url = :qr_code_content_url,
                    qr_code_generated_at = :qr_code_generated_at
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':qr_code_path' => $qrData['qr_code_path'] ?? null,
            ':qr_code_content_url' => $qrData['qr_code_content_url'] ?? null,
            ':qr_code_generated_at' => $qrData['qr_code_generated_at'] ?? null,
        ]);
    }

    /**
     * Generate UUID v4
     * 
     * @return string
     */
    protected function generateUUID(): string
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
}

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
use PDO;
use PDOException;

class Member
{
    /**
     * Database connection instance
     * @var Database
     */
    // protected Database $db;

    private PDO $db;

    /**
     * Table name
     * @var string
     */
    protected string $table = 'members';

    /**
     * Constructor
     */
    public function __construct(PDO $db)
    {
        // $this->db = Database::getInstance();
        $this->db = $db;
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
        $sql = "INSERT INTO {$this->table} (member_id,member_no, status, created_at, updated_at) 
                VALUES (:member_id,:member_no, :status, NOW(), NOW())";
        try {
            $stmt = $this->db->prepare($sql);
            $id = $data['member_id'] ?? $this->generateUUID();

            $stmt->execute([
                ':member_id'             => $id,
                ':member_no'      => $data['member_no'] ?? null,
                ':status'         => $data['status'] ?? 'inactive',
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
     * Generate UUID v4
     * 
     * @return string
     */
    protected function generateUUID(): string
    {
        $uuid = '';
        $uuid .= sprintf('%04x', mt_rand(0, 0xffff));
        $uuid .= sprintf('%04x', mt_rand(0, 0xffff));
        $uuid .= '-';
        $uuid .= sprintf('%04x', mt_rand(0, 0x0fff) | 0x4000);
        // $uuid .= '-';
        // $uuid .= sprintf('%04x', mt_rand(0, 0x3fff) | 0x8000);
        // $uuid .= '-';
        // $uuid .= sprintf('%04x%04x%04x', mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));
        
        return substr($uuid, 0, 15);
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
        $sql = "SELECT * FROM {$this->table} WHERE member_id = :member_id LIMIT 1";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':member_id' => $id]);
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
     * Create or update member profile extension row.
     *
     * @param string $memberId
     * @param array $data
     * @return bool
     */
    public function saveProfile(string $memberId, array $data): bool
    {
        $sql = "
            INSERT INTO member_profiles
            (
                member_id,
                birthday,
                nic,
                cricket_years,
                membership_year,
                first_name,
                last_name,
                full_name,
                email,
                phone,
                batch_year,
                gender,
                address,
                profile_photo,
                created_at,
                updated_at
            )
            VALUES
            (
                :member_id,
                :birthday,
                :nic,
                :cricket_years,
                :membership_year,
                :first_name,
                :last_name,
                :full_name,
                :email,
                :phone,
                :batch_year,
                :gender,
                :address,
                :profile_photo,
                NOW(),
                NOW()
            )
            ON DUPLICATE KEY UPDATE
                birthday = VALUES(birthday),
                nic = VALUES(nic),
                cricket_years = VALUES(cricket_years),
                membership_year = VALUES(membership_year),
                first_name = VALUES(first_name),
                last_name = VALUES(last_name),
                full_name = VALUES(full_name),
                email = VALUES(email),
                phone = VALUES(phone),
                batch_year = VALUES(batch_year),
                gender = VALUES(gender),
                address = VALUES(address),
                profile_photo = VALUES(profile_photo),
                created_at = VALUES(created_at),
                updated_at = VALUES(updated_at)
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':member_id'        => $memberId,
            ':birthday'         => $data['birthday'] ?? null,
            ':nic'              => $data['nic'],
            ':cricket_years'    => $data['cricket_years'] ?? null,
            ':membership_year'  => $data['membership_year'] ?? null,
            ':first_name'       => $data['first_name'] ?? null,
            ':last_name'        => $data['last_name'] ?? null,
            ':full_name'        => $data['full_name'] ?? null,
            ':email'            => $data['email'] ?? null,
            ':phone'            => $data['phone'] ?? null,
            ':batch_year'       => $data['batch_year'] ?? null,
            ':gender'           => $data['gender'] ?? null,
            ':address'          => $data['address'] ?? null,
            ':profile_photo'    => $data['profile_photo'] ?? null,
        ]);
    }

    /**
     * Get member with profile extension.
     *
     * @param string $id
     * @return array|null
     */
    public function getFullById(string $id): ?array
    {
        $sql = "
            SELECT m.*, p.*
            FROM members m
            LEFT JOIN member_profiles p ON p.member_id = m.member_id
            WHERE m.member_id = :member_id
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':member_id' => $id]);

        $row = $stmt->fetch();

        return $row ?: null;
    }


    /**
     * Save attachment record.
     */
    public function saveAttachment(array $data): bool
    {
        $sql = "
            INSERT INTO member_attachments
            (
                member_id,
                field_key,
                field_label,
                category,
                context_ref,
                original_name,
                stored_name,
                file_path,
                mime_type,
                file_size,
                uploaded_by
            )
            VALUES
            (
                :member_id,
                :field_key,
                :field_label,
                :category,
                :context_ref,
                :original_name,
                :stored_name,
                :file_path,
                :mime_type,
                :file_size,
                :uploaded_by
            )
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':member_id'      => $data['member_id'],
            ':field_key'      => $data['field_key'],
            ':field_label'    => $data['field_label'],
            ':category'       => $data['category'],
            ':context_ref'    => $data['context_ref'],
            ':original_name'  => $data['original_name'],
            ':stored_name'    => $data['stored_name'],
            ':file_path'      => $data['file_path'],
            ':mime_type'      => $data['mime_type'],
            ':file_size'      => $data['file_size'],
            ':uploaded_by'    => $data['uploaded_by'],
        ]);
    }

    /**
     * Get member attachments.
     */
    public function getAttachments(string $memberId): array
    {
        $sql = "
            SELECT *
            FROM member_attachments
            WHERE member_id = :member_id
            AND is_active = 1
            ORDER BY created_at DESC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':member_id' => $memberId
        ]);

        return $stmt->fetchAll() ?: [];
    }


    public function archiveAttachment(
        int $id,
        string $memberId
    ): bool {
        $sql = "
            UPDATE member_attachments
            SET is_active = 0
            WHERE id = :id
            AND member_id = :member_id
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':member_id' => $memberId
        ]);
    }

    public function getAttachmentById(
        int $id,
        string $memberId
    ): ?array {
        $sql = "
            SELECT *
            FROM member_attachments
            WHERE id = :id
            AND member_id = :member_id
            AND is_active = 1
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':id' => $id,
            ':member_id' => $memberId
        ]);

        $row = $stmt->fetch();

        return $row ?: null;
    }


    /**
     * save new contribution
     */
    public function insertContribution(array $data): int
    {
        $sql = "
            INSERT INTO member_contributions
            (
                member_id, 
                field_id, 
                title, 
                amount, 
                description, 
                support_documents
            )
            VALUES
            (
                :member_id,
                :field_id,
                :title,
                :amount,
                :description,
                :support_documents
            )
        ";
        $stmt = $this->db->prepare($sql);


        $outcome = 0;
        $outcome = $stmt->execute([
            ':member_id' => $data['member_id'],
            ':field_id' => $data['field_id'] ?? null,
            ':title' => $data['title'],
            ':amount' => $data['amount'],
            ':description' => $data['description'] ?? null,
            ':support_documents' => $data['support_documents'] ?? null,
        ]);
        
        if (!$outcome) {
            throw new \Exception('Failed to save contribution record');
        }

        return $outcome;

        // return $this->db->lastInsertId();
       
    }

    public function getMemberPaymentStatus(string $memberId): array
    {
        $currentYear = date('Y');

        $sql = "
            SELECT * 
            FROM member_attachments
            WHERE member_id = :member_id
            AND field_key = 'member-fee-payment-record'
            AND context_ref = :context_ref
            ORDER BY created_at DESC
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':member_id' => $memberId,
            ':context_ref' => $currentYear
        ]);

        $payment = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$payment) {

            return [
                'paid' => false,
                'year' => $currentYear,
                'payment_date' => null,
                'payment_record' => null
            ];
        }

        return [
            'paid' => true,
            'year' => $currentYear,
            'payment_date' => $payment['created_at'],
            'payment_record' => $payment
        ];
    }    


    public function getMemberPaymentReceipt(string $memberId): ?array
    {
        $currentYear = date('Y');

        $sql = "
            SELECT
                file_path,
                file_name
            FROM member_attachments
            WHERE member_id = :member_id
            AND field_key = 'member-fee-payment-record'
            AND context_ref = :context_ref
            ORDER BY created_at DESC
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':member_id' => $memberId,
            ':context_ref' => $currentYear
        ]);

        $record = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$record) {
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | Convert Relative Path To Absolute
        |--------------------------------------------------------------------------
        */
        $absolutePath =
            dirname(__DIR__, 2) .
            '/public' .
            $record['file_path'];

        /*
        |--------------------------------------------------------------------------
        | Detect MIME Type
        |--------------------------------------------------------------------------
        */
        $mimeType =
            mime_content_type($absolutePath);

        return [
            'absolute_path' => $absolutePath,
            'mime_type' => $mimeType
        ];
    }

    public function getContributionsByMemberId(
        string $memberId
    ): array {

        $sql = "
            SELECT
                id,
                member_id,
                field_id,
                title,
                amount,
                description,
                created_at
            FROM member_contributions
            WHERE member_id = :member_id
            ORDER BY created_at DESC
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':member_id' => $memberId
        ]);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getContributionAttachments(
        string $memberId,
        int|string $fieldId
    ): array {

        $sql = "
            SELECT
                id,
                member_id,
                field_key,
                context_ref,
                stored_name,
                file_path,
                created_at
            FROM member_attachments
            WHERE member_id = :member_id
            AND field_key = :field_key
            ORDER BY created_at DESC
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':member_id' => $memberId,
            ':field_key' => $fieldId
        ]);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get members by status
     */
    public function getMembersByStatus(
        string $status
    ): array {

        $query = "
            SELECT
                m.*,

                mp.member_id,
                mp.first_name,
                mp.last_name,
                mp.full_name,
                mp.email,
                mp.phone,
                mp.batch_year,
                mp.profile_photo

            FROM members m

            LEFT JOIN member_profiles mp
                ON mp.member_id = m.member_id

            WHERE m.status = :status

            ORDER BY m.created_at DESC
        ";

        $stmt =
            $this->db->prepare($query);

        $stmt->execute([
            ':status' => $status
        ]);

        return $stmt->fetchAll(
            \PDO::FETCH_ASSOC
        );
    }

    /**
     * Get attachment by field key
     */
    public function getAttachmentByFieldKey(
        string $memberId,
        string $fieldKey
    ): ?array {

        $query = "
            SELECT *
            FROM member_attachments
            WHERE member_id = :member_id
            AND field_key = :field_key
            LIMIT 1
        ";

        $stmt =
            $this->db->prepare($query);

        $stmt->execute([
            ':member_id' => $memberId,
            ':field_key' => $fieldKey
        ]);

        $result =
            $stmt->fetch(
                \PDO::FETCH_ASSOC
            );

        return $result ?: null;
    }


    /**
     * Update member status
     */
    public function updateMemberStatus(
        string $memberId,
        string $status
    ): bool {

        $query = "
            UPDATE members
            SET
                status = :status,
                updated_at = NOW()
            WHERE id = :id
        ";

        $stmt =
            $this->connection->prepare($query);

        return $stmt->execute([
            ':status' => $status,
            ':id' => $memberId
        ]);
    }

    /**
     * Generate member number
     */
    public function generateMemberNumber(): string
    {
        $year =
            date('Y');

        $random =
            str_pad(
                (string) random_int(1, 9999),
                4,
                '0',
                STR_PAD_LEFT
            );

        return 'PCA' . $year . $random;
    }

    /**
     * Assign member number
     */
    public function assignMemberNumber(
        string $memberId,
        string $memberNumber
    ): bool {

        $query = "
            UPDATE members
            SET member_no = :member_no
            WHERE id = :id
        ";

        $stmt =
            $this->connection->prepare($query);

        return $stmt->execute([
            ':member_no' => $memberNumber,
            ':id' => $memberId
        ]);
    }



}

<?php
/**
 * Member Service
 * 
 * Encapsulates business logic for member operations.
 * Coordinates between controller and model layers.
 * 
 * PHP Version: 8.0+
 */

namespace App\Services;

use App\Models\Member;
use App\Validators\MemberValidator;
use InvalidArgumentException;
use PDOException;
use RuntimeException;

class MemberService
{
    /**
     * Member model instance
     * @var Member
     */
    protected Member $memberModel;

    /**
     * Member validator instance
     * @var MemberValidator
     */
    protected MemberValidator $validator;

    /**
     * QR code service instance
     * @var QrCodeService
     */
    protected QrCodeService $qrCodeService;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->memberModel = new Member();
        $this->validator = new MemberValidator();
        // $this->qrCodeService = new QrCodeService();
    }

    /**
     * Create a new member
     * 
     * @param array $data Member data from request
     * @return array Created member data
     * @throws InvalidArgumentException
     * @throws PDOException
     */
    public function create(array $data): array
    {
        // Validate input data
        if (!$this->validator->validateCreate($data)) {
            throw new InvalidArgumentException(
                'Validation failed',
                0,
                ['errors' => $this->validator->getErrors()]
            );
        }

        // Normalize data
        $memberData = $this->normalizeMemberData($data);

        // Create member in database
        $id = $this->memberModel->create($memberData);

        try {
            // $qrReference = $this->qrCodeService->generateForMember($id);
            $this->memberModel->updateQrReference($id, $qrReference);
        } catch (RuntimeException $exception) {
            $this->memberModel->delete($id);
            throw new RuntimeException('Member creation rolled back because QR generation failed: ' . $exception->getMessage(), 0, $exception);
        }

        // Retrieve and return created member
        return $this->memberModel->getById($id);
    }

    /**
     * Retrieve member by ID
     * 
     * @param string $id Member ID
     * @return array Member data
     * @throws InvalidArgumentException
     * @throws PDOException
     */
    public function getById(string $id): array
    {
        if (empty($id)) {
            throw new InvalidArgumentException('Member ID is required');
        }

        $member = $this->memberModel->getById($id);

        if ($member === null) {
            throw new InvalidArgumentException('Member not found');
        }

        return $member;
    }

    /**
     * Update member information
     * 
     * @param string $id Member ID
     * @param array $data Update data
     * @return array Updated member data
     * @throws InvalidArgumentException
     * @throws PDOException
     */
    public function update(string $id, array $data): array
    {
        if (empty($id)) {
            throw new InvalidArgumentException('Member ID is required');
        }

        // Check if member exists
        if (!$this->memberModel->exists($id)) {
            throw new InvalidArgumentException('Member not found');
        }

        // Validate update data
        if (!$this->validator->validateUpdate($data)) {
            throw new InvalidArgumentException(
                'Validation failed',
                0,
                ['errors' => $this->validator->getErrors()]
            );
        }

        // Normalize data
        $updateData = $this->normalizeMemberData($data);

        // Update in database
        $this->memberModel->update($id, $updateData);

        // Retrieve and return updated member
        return $this->memberModel->getById($id);
    }

    /**
     * Delete a member
     * 
     * @param string $id Member ID
     * @return bool Success
     * @throws InvalidArgumentException
     * @throws PDOException
     */
    public function delete(string $id): bool
    {
        if (empty($id)) {
            throw new InvalidArgumentException('Member ID is required');
        }

        // Check if member exists
        if (!$this->memberModel->exists($id)) {
            throw new InvalidArgumentException('Member not found');
        }

        // Delete from database
        return $this->memberModel->delete($id);
    }

    /**
     * Normalize member data
     * 
     * @param array $data Input data
     * @return array Normalized data
     */
    private function normalizeMemberData(array $data): array
    {
        $normalized = [];

        $allowedFields = [
            'id', 'member_no', 'first_name', 'last_name', 'full_name',
            'email', 'phone', 'batch_year', 'gender', 'address',
            'profile_photo', 'status'
        ];

        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $value = $data[$field];

                // Trim string values
                if (is_string($value)) {
                    $value = trim($value);
                }

                // Convert empty strings to null for optional fields
                if ($value === '') {
                    $value = null;
                }

                // Normalize status and gender to lowercase
                if (in_array($field, ['status', 'gender']) && $value !== null) {
                    $value = strtolower($value);
                }

                $normalized[$field] = $value;
            }
        }

        return $normalized;
    }
}

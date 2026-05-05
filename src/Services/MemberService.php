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

use App\Config\Database;
use App\Models\Member;
use App\Validators\MemberValidator;
use InvalidArgumentException;
use PDOException;
use RuntimeException;
use finfo;

class MemberService
{
    /**
     * Member model instance
     * @var Member
     */
    private Member $memberModel;

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
        $pdo = Database::getInstance()->getConnection();
        $this->memberModel = new Member($pdo);
        $this->validator = new MemberValidator();
        $this->qrCodeService = new QrCodeService();
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
        // Normalize data
        $memberData = $this->normalizeMemberData($data);
        // Create member in database
        $id = $this->memberModel->create($memberData);
        $member = $this->memberModel->getById($id) ?? [];
        return $member;
    }


    public function createMemberProfile(string $id,array $data): array
    {    
        $profileData = $this->normalizeProfileData($data);
        if( $this->memberModel->saveProfile($id, $profileData) ){
            return $this->memberModel->getFullById($id);
        } else {
            throw new RuntimeException('Failed to save member profile');
        }
        
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
        $memberFull = $this->memberModel->getFullById($id);

        if ($member === null) {
            throw new InvalidArgumentException('Member not found');
        }

        return $member;
    }


    /**
     * Retrieve full member profile by ID
     * 
     * @param string $id Member ID
     * @return array Full member profile data
     * @throws InvalidArgumentException
     * @throws PDOException
     */
    public function getFullProfileByID(string $id): array
    {
        $memberFull = $this->memberModel->getFullById($id) ?? [];
        
        if ( empty($memberFull) ) {
            throw new InvalidArgumentException('Member not found');
        }
        
        return $memberFull;
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
        $profileData = $this->normalizeProfileData($data);

        // Update in database
        if (!empty($updateData)) {
            $this->memberModel->update($id, $memberData);
        }

        if (!empty($profileData)) {
            $this->memberModel->saveProfile($id, $profileData);
        }

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
        // Normalize member data
        $normalizedData = [
            'member_no' => $data['member_no'] ?? null,
            'status' => $data['status'] ?? 'inactive',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        return $normalizedData;
    }

    private function normalizeProfileData(array $data): array
    {
        $allowed = [
            'member_id',
            'birthday',
            'nic',
            'al_batch_year',
            'cricket_years',
            'membership_year',
            'first_name',
            'last_name',
            'full_name',
            'email',
            'phone',
            'batch_year',
            'gender',
            'address',
            'profile_photo',
            'created_at',
            'updated_at'
        ];

        $normalized = [];

        foreach ($allowed as $field) {
            if (array_key_exists($field, $data)) {
                $value = $data[$field];

                if (is_string($value)) {
                    $value = trim($value);
                }

                if ($value === '') {
                    $value = null;
                }

                if ($field === 'cricket_years' && is_array($value)) {
                    $value = implode(',', $value);
                }

                $normalized[$field] = $value;
            }
        }

        return $normalized;
    }



    public function saveNewContribution(array $data): array
    {
        $status = $this->memberModel->insertContribution($data);
        return [
            'success' => $status > 0 ? true : false,
            'status' => $status,
            'data' => $data
        ];
    }


    public function attachments(string $memberId): array
    {
        return $this->memberModel->getAttachments($memberId);
    }


    public function archiveAttachment(
        int $id,
        string $memberId
    ): bool {
        return $this->memberModel
            ->archiveAttachment($id, $memberId);
    }

    public function attachment(
        int $id,
        string $memberId
    ): ?array {
        return $this->memberModel
            ->getAttachmentById($id, $memberId);
    }



    public function saveSingleAttachment(
        string $memberId,
        array $file,
        array $meta
    ): bool {

        if ($file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }

        $stored = preg_replace('/[^a-zA-Z0-9._-]/', '_', $name);
        $stored = time() . '_' . $stored;        

        $dir = dirname(__DIR__,2) . '/storage/'.date('Y').'/uploads/field_attachments/'.$memberId.'/';
        $filepath = 'storage/'.date('Y').'/uploads/field_attachments/'.$memberId.'/';
            if( !empty($meta['category']) ){
                $dir = $dir . $meta['category'] .'/';
                $filepath = $filepath . $meta['category'] .'/';
            }
            if( !empty($meta['field_key']) ){
                $dir = $dir . $meta['field_key'] .'/';
                $filepath = $filepath . $meta['field_key'] .'/';
            }
            if( !empty($meta['context_ref']) ){
                $dir = $dir . $meta['context_ref'] .'/';
                $filepath = $filepath . $meta['context_ref'] .'/';
            }

        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $target = $dir . $stored;

        move_uploaded_file($file['tmp_name'], $target);

        return $this->memberModel->saveAttachment([
            'member_id'     => $memberId,
            'field_key'     => $meta['field_key'],
            'field_label'   => $meta['field_label'],
            'category'      => $meta['category'],
            'context_ref'   => $meta['context_ref'],
            'original_name' => $file['name'],
            'stored_name'   => $stored,
            'file_path'     => $filepath . $stored,
            'mime_type'     => (new finfo(FILEINFO_MIME_TYPE))->file($target),
            'file_size'     => $file['size'],
            'uploaded_by'   => 'self'
        ]);
    }




    public function saveMultipleAttachments(
        string $memberId,
        array $files,
        array $meta
    ): array {

        $saved = [];

        $count = count($files['name']);

        for ($i = 0; $i < $count; $i++) {

            if ($files['error'][$i] !== UPLOAD_ERR_OK) {
                continue;
            }

            $name = $files['name'][$i];

            $changeName = time() .'_' . $i . "_";
            $stored = preg_replace('/[^a-zA-Z0-9._-]/', '_', $name);
            $stored = $changeName . $stored;

            $dir = dirname(__DIR__,2) . '/storage/'.date('Y').'/uploads/field_attachments/'.$memberId.'/';
            $filepath = 'storage/'.date('Y').'/uploads/field_attachments/'.$memberId.'/';
                if( !empty($meta['category']) ){
                    $dir = $dir . $meta['category'] .'/';
                    $filepath = $filepath . $meta['category'] .'/';
                }
                if( !empty($meta['field_key']) ){
                    $dir = $dir . $meta['field_key'] .'/';
                    $filepath = $filepath . $meta['field_key'] .'/';
                }
                if( !empty($meta['context_ref']) ){
                    $dir = $dir . $meta['context_ref'] .'/';
                    $filepath = $filepath . $meta['context_ref'] .'/';
                }
                // $dir = $dir .$i.'/';
                // $filepath = $filepath . $i.'/';

            if (!is_dir($dir)) {
                mkdir($dir, 0775, true);
            }

            $target = $dir . $stored;

            move_uploaded_file(
                $files['tmp_name'][$i],
                $target
            );

            $row = [
                'member_id'     => $memberId,
                'field_key'     => $meta['field_key'],
                'field_label'   => $meta['field_label'],
                'category'      => $meta['category'],
                'context_ref'   => $meta['context_ref'],
                'original_name' => $name,
                'stored_name'   => $stored,
                'file_path'     => $filepath . $stored,
                'mime_type'     => mime_content_type($target),
                'file_size'     => $files['size'][$i],
                'uploaded_by'   => 'self'
            ];

            $this->memberModel->saveAttachment($row);

            $saved[] = $row;
        }

        return $saved;
    }
}




// ////////////////////////// profile


// public function create(array $data): array {
//         $profileData = $this->normalizeProfileData($data);

//         if (!empty($profileData)) {
//             $this->memberModel->saveProfile($id, $profileData);
//         }
// }



// /////////////////////////////// 

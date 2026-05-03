<?php
/**
 * Member Controller
 * 
 * Handles HTTP requests and responses for member operations.
 * Coordinates with service layer for business logic.
 * 
 * PHP Version: 8.0+
 */

namespace App\Controllers;

use App\Services\MemberService;
use App\Helpers\Response;
use App\Helpers\Logger;
use InvalidArgumentException;
use PDOException;

class MemberController
{
    /**
     * Member service instance
     * @var MemberService
     */
    protected MemberService $memberService;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->memberService = new MemberService();
    }

    /**
     * Create a new member
     * POST /v1/member
     * 
     * @return void
     */
    public function create(): void
    {
        try {

            $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

            if (str_contains($contentType, 'multipart/form-data')) {
                $payload = $_POST;
            } else {
                $payload = json_decode(file_get_contents('php://input'), true) ?? [];
            }

            // Validate JSON input
            if (empty($payload)) {
                Response::error('Request body is empty', 400);
                return;
            }       

            $memberNumber = null;
            if( isset($payload['al_batch_year'])  ) {
                $alBatchYear = $payload['al_batch_year'];
                $memberNumber = $this->generateMemberNumber($alBatchYear);
            }

            $memberData = [
                'member_no' => $memberNumber,
                'status' => 'inactive'
            ];

            // Create member profile
            // $profileData['member_number'] = $memberNumber;

            // Extract the member data
            // $memberData = array_intersect_key($payload, array_flip(['member_no', 'status', 'id']));
            // The keys 'member_no', 'status', and 'id' should correspond to the keys expected by $this->memberService->create

            // Extract the profile data
            $profileData = array_intersect_key($payload, array_flip(['birthday', 'nic', 'al_batch_year', 'cricket_years', 'membership_year', 'first_name', 'last_name', 'full_name', 'email', 'phone', 'batch_year', 'gender', 'address', 'profile_photo']));
            // The keys 'birthday', 'nic', 'al_batch_year', 'cricket_years', 'membership_year', 'first_name', 'last_name', 'full_name', 'email', 'phone', 'batch_year', 'gender', 'address', 'profile_photo' should correspond to the keys expected by $this->memberService->createMemberProfile

            // Create member through service
            $member = $this->memberService->create($memberData); 
                $profile = [];
                if( isset($member['id']) ) {
                    // Create member profile
                    $profile = $this->memberService->createMemberProfile($member['id'], $profileData);
                }


                Response::success(
                    [
                        'member' => $member,
                        'profile' => $profile
                    ],
                    'Member created successfully',
                    201
                );            

        } catch (InvalidArgumentException $e) {
            $errors = json_decode($e->getPrevious() ? json_encode($e->getPrevious()) : '{}', true);
            Response::error(
                $e->getMessage(),
                400,
                $errors['errors'] ?? []
            );
            Logger::warning('Member creation validation failed', ['errors' => $errors]);

        } catch (PDOException $e) {
            // Handle duplicate entry
            if ($e->getCode() == 1062) {
                Response::error(
                    'Email, phone, or member number already exists',
                    409
                );
            } else {
                Response::error('Database error: ' . $e->getMessage(), 500);
                Logger::error('Database error during member creation', ['error' => $e->getMessage()]);
            }

        } catch (\Exception $e) {
            Response::error($e->getMessage(), 500);
            Logger::error('Unexpected error during member creation', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Generate a human-readable member number
     * 
     * @param string $alBatchYear AL batch year
     * @return string Generated member number
     */
    protected function generateMemberNumber(string $alBatchYear): string
    {
        // Ensure the AL batch year is not empty
        if (empty($alBatchYear)) {
            return '';
        }

        // Generate a random number between 111 and 999
        $number = rand(111, 999);

        // Return the generated member number in the format PCA+ALBatchYear+Number
        return 'PCA' . $alBatchYear . str_pad($number, 3, '0', STR_PAD_LEFT);
    }


    public function memberFeePayment(): void
    {

        $memberId = null;
        if( isset($_POST['member-id']) ){
            $memberId = $_POST['member-id'];
        }

        $field_key = isset($_POST['field_key']) ? $_POST['field_key'] : null;

        if (empty($memberId)) {
            Response::error('Member ID is required to upload file', 400);
            return;
        }

        $fileupload = []; 
            if (FILES_INBOUND != null) {
                $files = FILES_INBOUND;
                foreach ($files as $inputName => $fileData) {
        //             // Handle multiple files under same name (array input)
        //             if (is_array($fileData['name'])) {
        //                 // $savedFiles[$inputName] = $this->handleMultiple($fileData);
        //             } else {
                        $fileupload = $this->memberService->saveSingleAttachment(
                            $memberId, 
                            $fileData, 
                            [
                                'type' => 'membership-bankslip',
                                'field_key'     => $field_key,
                                'field_label'   => 'Membership Fee Payment - ' . date('Y'),
                                'category'      => 'payment-records',
                                'context_ref'   => $memberId                
                            ]
                        );
    //             }
                }
            } else {
                $fileupload = [
                    'message' => 'No files detected in the request'
                ];
            }

            if ( FILES_INBOUND != null && !empty($fileupload) ) {
                Response::json([
                    'success' => true,
                    'message' => 'File uploaded successfully',
                    'file' => $savedFile
                ]);

                // Response::success(
                //     [
                //         'member' => $member,
                //         'file_upload' => $fileupload
                //     ],
                //     'Member created successfully',
                //     201
                // );                
            } else {
                $this->memberService->delete($member['id']);
                Response::error('server side file copying failed.', 400);
                Logger::info('File upload detected during member creation', ['memberId' => $member['id']]);
                return;                
                
            }




        // if (empty($file) || $file['error'] !== UPLOAD_ERR_OK) {
        //     Response::error('Invalid file', 400);
        //     return;
        // }

        // $fileData = [
        //     'name' => $file['name'],
        //     'type' => $file['type'],
        //     'tmp_name' => $file['tmp_name'],
        //     'error' => $file['error'],
        //     'size' => $file['size']
        // ];

        // $savedFile = $this->memberService->saveSingleAttachment(
        //     $memberId,
        //     $fileData,
        //     [
        //         'type' => 'membership-bankslip',
        //         'field_key' => 'XOX_1',
        //         'field_label' => 'XOX_lab',
        //         'category' => 'XOX_cat',
        //         'context_ref' => 'XOX_ref',
        //     ]
        // );

        // if (!$savedFile) {
        //     Response::error('Failed to save file', 500);
        //     return;
        // }

        // // Call the function that updates the database for each user related attachment info table
        // $this->memberService->updateAttachmentInfo($memberId, $savedFile['stored_name']);

        // Response::json([
        //     'success' => true,
        //     'file' => $savedFile
        // ]);
    }

    /**
     * Retrieve member details
     * GET /v1/member/{id}
     * 
     * @param string $id Member ID
     * @return void
     */
    public function retrieve(string $id): void
    {
        try {
            // Validate ID format (UUID)
            // if (!$this->isValidUUID($id)) {
            //     Response::error('Invalid member ID format', 400);
            //     return;
            // }
            // Retrieve member through service
            $member = $this->memberService->getFullProfileByID($id);
            // Return success response with member data
            Response::success(
                $member,
                'Member retrieved successfully',
                200
            );

        } catch (InvalidArgumentException $e) {
            if ($e->getMessage() === 'Member not found') {
                Response::error('Member not found', 404);
            } else {
                Response::error($e->getMessage(), 400);
            }
            Logger::info('Member retrieval - not found', ['id' => $id]);

        } catch (PDOException $e) {
            Response::error('Database error: ' . $e->getMessage(), 500);
            Logger::error('Database error during member retrieval', ['error' => $e->getMessage()]);

        } catch (\Exception $e) {
            Response::error($e->getMessage(), 500);
            Logger::error('Unexpected error during member retrieval', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Update member information
     * PUT/PATCH /v1/member/{id}
     * 
     * @param string $id Member ID
     * @return void
     */
    public function update(string $id): void
    {
        try {
            // Validate ID format (UUID)
            if (!$this->isValidUUID($id)) {
                Response::error('Invalid member ID format', 400);
                return;
            }

            $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

            if (str_contains($contentType, 'multipart/form-data')) {
                $input = $_POST;
            } else {
                $input = $this->getJsonInput();
            }

            if (empty($input)) {
                Response::error('Request body is empty', 400);
                return;
            }

            // Update member through service
            $member = $this->memberService->update($id, $input);

            // Return success response with updated member
            Response::success(
                $member,
                'Member updated successfully',
                200
            );

        } catch (InvalidArgumentException $e) {
            if ($e->getMessage() === 'Member not found') {
                Response::error('Member not found', 404);
            } else {
                $errors = json_decode($e->getPrevious() ? json_encode($e->getPrevious()) : '{}', true);
                Response::error(
                    $e->getMessage(),
                    400,
                    $errors['errors'] ?? []
                );
                Logger::warning('Member update validation failed', ['errors' => $errors, 'id' => $id]);
            }

        } catch (PDOException $e) {
            // Handle duplicate entry
            if ($e->getCode() == 1062) {
                Response::error(
                    'Email, phone, or member number already in use',
                    409
                );
            } else {
                Response::error('Database error: ' . $e->getMessage(), 500);
                Logger::error('Database error during member update', ['error' => $e->getMessage()]);
            }

        } catch (\Exception $e) {
            Response::error($e->getMessage(), 500);
            Logger::error('Unexpected error during member update', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Delete a member
     * DELETE /v1/member/{id}
     * 
     * @param string $id Member ID
     * @return void
     */
    public function delete(string $id): void
    {
        try {
            // Validate ID format (UUID)
            if (!$this->isValidUUID($id)) {
                Response::error('Invalid member ID format', 400);
                return;
            }

            // Delete member through service
            $this->memberService->delete($id);

            // Return success response
            Response::success(
                ['id' => $id],
                'Member deleted successfully',
                200
            );

        } catch (InvalidArgumentException $e) {
            if ($e->getMessage() === 'Member not found') {
                Response::error('Member not found', 404);
            } else {
                Response::error($e->getMessage(), 400);
            }
            Logger::info('Member deletion - not found', ['id' => $id]);

        } catch (PDOException $e) {
            Response::error('Database error: ' . $e->getMessage(), 500);
            Logger::error('Database error during member deletion', ['error' => $e->getMessage()]);

        } catch (\Exception $e) {
            Response::error($e->getMessage(), 500);
            Logger::error('Unexpected error during member deletion', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Get JSON input from request body
     * 
     * @return array
     */
    protected function getJsonInput(): array
    {
        $input = file_get_contents('php://input');
        
        if (empty($input)) {
            return [];
        }

        $decoded = json_decode($input, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('Invalid JSON: ' . json_last_error_msg());
        }

        return is_array($decoded) ? $decoded : [];
    }

    /**
     * Validate UUID v4 format
     * 
     * @param string $uuid UUID to validate
     * @return bool
     */
    protected function isValidUUID(string $uuid): bool
    {
        $pattern = '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i';
        return preg_match($pattern, $uuid) === 1;
    }


    // $controller->listAttachments($id);
    // $controller->downloadAttachment($id, $aid);
    // $controller->archiveAttachment($id, $aid);    


    public function listAttachments(string $memberId): void
    {
        try {
            Response::json(
                $this->memberService->attachments($memberId)
            );
        } catch (\Throwable $e) {
            Response::json([
                'error' => $e->getMessage()
            ], 422);
        }
    }

    public function downloadAttachment(
        string $memberId,
        int $id
    ): void {
        $file = $this->memberService->attachment($id, $memberId);

        if (!$file) {
            http_response_code(404);
            exit('File not found');
        }

        $full = dirname(__DIR__, 2) . '/'
            . $file['file_path'];

        if (!is_file($full)) {
            http_response_code(404);
            exit('Missing file');
        }

        header('Content-Type: ' . $file['mime_type']);
        header(
            'Content-Disposition: attachment; filename="' .
            basename($file['original_name']) . '"'
        );
        header('Content-Length: ' . filesize($full));

        readfile($full);
        exit;
    }

    public function archiveAttachment(
        string $memberId,
        int $id
    ): void {
        $ok = $this->memberService
            ->archiveAttachment($id, $memberId);

        Response::json([
            'success' => $ok
        ]);
    }


///////////// FILE UPLOAD


    // public function create(): void
    // {
    //         $fileupload = []; 

    //         if (FILES_INBOUND != null) {
    //             $files = FILES_INBOUND;
    //             foreach ($files as $inputName => $fileData) {
    //                 // Handle multiple files under same name (array input)
    //                 if (is_array($fileData['name'])) {
    //                     // $savedFiles[$inputName] = $this->handleMultiple($fileData);
    //                 } else {
    //                     $fileupload = $this->memberService->saveSingleAttachment(
    //                         $member['id'], 
    //                         $fileData, 
    //                         [
    //                             'type' => 'membership-bankslip',
    //                             'field_key'     => 'XOX_1',
    //                             'field_label'   => 'XOX_lab',
    //                             'category'      => 'XOX_cat',
    //                             'context_ref'   => 'XOX_ref'                
    //                         ]
    //                     );
    //                 }

    //             }
    //         } else {
    //             $fileupload = $member;
    //         }

            
    //         if ( FILES_INBOUND != null && !empty($fileupload) ) {
    //             Response::success(
    //                 [
    //                     'member' => $member,
    //                     'file_upload' => $fileupload
    //                 ],
    //                 'Member created successfully',
    //                 201
    //             );                
    //         } else {
    //             $this->memberService->delete($member['id']);
    //             Response::error('server side file copying failed.', 400);
    //             Logger::info('File upload detected during member creation', ['memberId' => $member['id']]);
    //             return;                
                
    //         }

    // }

/////////////////////////



}

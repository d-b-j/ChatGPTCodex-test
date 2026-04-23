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
            // Get JSON input
            $input = $this->getJsonInput();

            // Validate JSON input
            if (empty($input)) {
                Response::error('Request body is empty', 400);
                return;
            }

            // Create member through service
            $member = $this->memberService->create($input);

            // Return success response with created member
            Response::success(
                $member,
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
            if (!$this->isValidUUID($id)) {
                Response::error('Invalid member ID format', 400);
                return;
            }

            // Retrieve member through service
            $member = $this->memberService->getById($id);

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

            // Get JSON input
            $input = $this->getJsonInput();

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
}

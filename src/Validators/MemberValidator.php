<?php
/**
 * Member Validator
 * 
 * Validates member input data before database operations.
 * Implements comprehensive validation rules.
 * 
 * PHP Version: 8.0+
 */

namespace App\Validators;

class MemberValidator
{
    /**
     * Validation errors
     * @var array
     */
    private array $errors = [];

    /**
     * Validate create request data
     * 
     * @param array $data Input data
     * @return bool
     */
    public function validateCreate(array $data): bool
    {
        $this->errors = [];

        // Required fields
        if (empty($data['first_name'])) {
            $this->errors['first_name'] = 'First name is required';
        } elseif (strlen($data['first_name']) > 100) {
            $this->errors['first_name'] = 'First name must not exceed 100 characters';
        }

        if (empty($data['last_name'])) {
            $this->errors['last_name'] = 'Last name is required';
        } elseif (strlen($data['last_name']) > 100) {
            $this->errors['last_name'] = 'Last name must not exceed 100 characters';
        }

        if (empty($data['full_name'])) {
            $this->errors['full_name'] = 'Full name is required';
        } elseif (strlen($data['full_name']) > 200) {
            $this->errors['full_name'] = 'Full name must not exceed 200 characters';
        }

        // Optional fields with validation
        if (isset($data['email']) && !empty($data['email'])) {
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $this->errors['email'] = 'Invalid email format';
            } elseif (strlen($data['email']) > 150) {
                $this->errors['email'] = 'Email must not exceed 150 characters';
            }
        }

        if (isset($data['phone']) && !empty($data['phone'])) {
            if (!$this->validatePhone($data['phone'])) {
                $this->errors['phone'] = 'Invalid phone format';
            } elseif (strlen($data['phone']) > 30) {
                $this->errors['phone'] = 'Phone must not exceed 30 characters';
            }
        }

        if (isset($data['member_no']) && !empty($data['member_no'])) {
            if (strlen($data['member_no']) > 30) {
                $this->errors['member_no'] = 'Member number must not exceed 30 characters';
            }
        }

        if (isset($data['batch_year']) && !empty($data['batch_year'])) {
            if (!is_numeric($data['batch_year']) || $data['batch_year'] < 1900 || $data['batch_year'] > date('Y') + 1) {
                $this->errors['batch_year'] = 'Invalid batch year';
            }
        }

        if (isset($data['gender']) && !empty($data['gender'])) {
            $validGenders = ['male', 'female', 'other'];
            if (!in_array(strtolower($data['gender']), $validGenders)) {
                $this->errors['gender'] = 'Invalid gender value';
            }
        }

        if (isset($data['status']) && !empty($data['status'])) {
            $validStatuses = ['active', 'inactive', 'pending'];
            if (!in_array(strtolower($data['status']), $validStatuses)) {
                $this->errors['status'] = 'Invalid status value';
            }
        }

        if (isset($data['address']) && !empty($data['address'])) {
            if (strlen($data['address']) > 65535) {
                $this->errors['address'] = 'Address is too long';
            }
        }

        return empty($this->errors);
    }

    /**
     * Validate update request data
     * 
     * @param array $data Input data
     * @return bool
     */
    public function validateUpdate(array $data): bool
    {
        $this->errors = [];

        // At least one field must be present for update
        if (empty($data)) {
            $this->errors['data'] = 'No fields to update';
            return false;
        }

        // Validate each field if present
        if (isset($data['first_name'])) {
            if (empty($data['first_name'])) {
                $this->errors['first_name'] = 'First name cannot be empty';
            } elseif (strlen($data['first_name']) > 100) {
                $this->errors['first_name'] = 'First name must not exceed 100 characters';
            }
        }

        if (isset($data['last_name'])) {
            if (empty($data['last_name'])) {
                $this->errors['last_name'] = 'Last name cannot be empty';
            } elseif (strlen($data['last_name']) > 100) {
                $this->errors['last_name'] = 'Last name must not exceed 100 characters';
            }
        }

        if (isset($data['full_name'])) {
            if (empty($data['full_name'])) {
                $this->errors['full_name'] = 'Full name cannot be empty';
            } elseif (strlen($data['full_name']) > 200) {
                $this->errors['full_name'] = 'Full name must not exceed 200 characters';
            }
        }

        if (isset($data['email'])) {
            if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $this->errors['email'] = 'Invalid email format';
            } elseif (strlen($data['email']) > 150) {
                $this->errors['email'] = 'Email must not exceed 150 characters';
            }
        }

        if (isset($data['phone'])) {
            if (!empty($data['phone']) && !$this->validatePhone($data['phone'])) {
                $this->errors['phone'] = 'Invalid phone format';
            } elseif (strlen($data['phone']) > 30) {
                $this->errors['phone'] = 'Phone must not exceed 30 characters';
            }
        }

        if (isset($data['member_no']) && !empty($data['member_no'])) {
            if (strlen($data['member_no']) > 30) {
                $this->errors['member_no'] = 'Member number must not exceed 30 characters';
            }
        }

        if (isset($data['batch_year']) && !empty($data['batch_year'])) {
            if (!is_numeric($data['batch_year']) || $data['batch_year'] < 1900 || $data['batch_year'] > date('Y') + 1) {
                $this->errors['batch_year'] = 'Invalid batch year';
            }
        }

        if (isset($data['gender']) && !empty($data['gender'])) {
            $validGenders = ['male', 'female', 'other'];
            if (!in_array(strtolower($data['gender']), $validGenders)) {
                $this->errors['gender'] = 'Invalid gender value';
            }
        }

        if (isset($data['status'])) {
            $validStatuses = ['active', 'inactive', 'pending'];
            if (!in_array(strtolower($data['status']), $validStatuses)) {
                $this->errors['status'] = 'Invalid status value';
            }
        }

        if (isset($data['address']) && strlen($data['address']) > 65535) {
            $this->errors['address'] = 'Address is too long';
        }

        return empty($this->errors);
    }

    /**
     * Get validation errors
     * 
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Validate phone number format
     * Accepts: +1234567890, 1234567890, (123) 456-7890, etc.
     * 
     * @param string $phone Phone number
     * @return bool
     */
    private function validatePhone(string $phone): bool
    {
        // Remove common formatting characters
        $cleaned = preg_replace('/[^\d+]/', '', $phone);
        
        // Check if at least 10 digits
        $digits = preg_replace('/\D/', '', $cleaned);
        
        return strlen($digits) >= 10 && strlen($digits) <= 15;
    }
}
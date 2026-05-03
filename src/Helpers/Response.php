<?php
/**
 * Response Helper
 * 
 * Provides standardized response formatting for API endpoints.
 * Handles both success and error responses with proper HTTP status codes.
 * 
 * PHP Version: 8.0+
 */

namespace App\Helpers;

class Response
{
    /**
     * Send success response
     * 
     * @param mixed $data Response data
     * @param string $message Success message
     * @param int $statusCode HTTP status code (default 200)
     * @return void
     */
    public static function success($data, string $message = 'Success', int $statusCode = 200): void
    {
        http_response_code($statusCode);

        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data,
            'timestamp' => date('c'),
        ];

        echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        exit;
    }

    /**
     * Send error response
     * 
     * @param string $message Error message
     * @param int $statusCode HTTP status code (default 500)
     * @param array $errors Additional error details
     * @return void
     */
    public static function error(string $message, int $statusCode = 500, array $errors = []): void
    {
        http_response_code($statusCode);

        $response = [
            'success' => false,
            'message' => $message,
            'timestamp' => date('c'),
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        exit;
    }


    /**
     * Send response in JSON format
     * 
     * @param mixed $data Response data
     * @param int $statusCode HTTP status code
     * @param array $headers Additional headers
     * @return void
     */
    public static function json($data, int $statusCode = 200, array $headers = []): void
    {
        http_response_code($statusCode);

        $response = [
            'success' => true,
            'data' => $data,
            'timestamp' => date('c'),
        ];

        if (!empty($headers)) {
            foreach ($headers as $key => $value) {
                header($key . ': ' . $value);
            }
        }

        echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        exit;
    }


    /**
     * Send paginated response
     * 
     * @param array $data Data items
     * @param int $total Total items
     * @param int $page Current page
     * @param int $limit Items per page
     * @param string $message Success message
     * @return void
     */
    public static function paginated(
        array $data,
        int $total,
        int $page = 1,
        int $limit = 10,
        string $message = 'Success'
    ): void {
        http_response_code(200);

        $totalPages = ceil($total / $limit);

        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data,
            'pagination' => [
                'total' => $total,
                'page' => $page,
                'limit' => $limit,
                'pages' => $totalPages,
            ],
            'timestamp' => date('c'),
        ];

        echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        exit;
    }
}
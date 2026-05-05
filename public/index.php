<?php
declare(strict_types=1);

/**
 * QR-Based School Alumnus API - Entry Point
 * 
 * This file serves as the main entry point for all API requests.
 * It handles routing, request parsing, and response dispatch.
 * 
 * PHP Version: 8.0+
 * Author: Development Team
 * Date: 2025
 */

// Enable error reporting for development (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 0); // Log errors instead of displaying
ini_set('log_errors', 1);

// Define application constants
define('APP_ROOT', dirname(__DIR__));
define('SRC_PATH', APP_ROOT . '/src');
define('BASE_URL', '/v1');
define('API_HOST', 'phpdev803.loc');
define('STORAGE_PATH', APP_ROOT . '/storage');
define('QR_CODE_STORAGE_PATH', STORAGE_PATH . '/qrcodes');
define('APP_URL', 'https://' . API_HOST . BASE_URL);
define('CONFIG_PATH', APP_ROOT . '/config');

// $host = $_SERVER['HTTP_HOST'];
// $requestUri = $_SERVER['REQUEST_URI'];
// $urlPath = parse_url("https://$host$requestUri", PHP_URL_PATH);
define('URL_PATH', $_SERVER['REQUEST_URI']);

define('FILES_INBOUND', isset($_FILES) ? $_FILES : null ); 
define('FILES_INBOUND_KEY', isset($_POST['field_key']) ? $_POST['field_key'] : null);

// Set content type header
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');


/*
|--------------------------------------------------------------------------
| Some older Endroid builds need Enum base directly
|--------------------------------------------------------------------------
*/
$enumBase = APP_ROOT . '/vendor/endroid-qrcode/src/DASPRiD/Enum.php';

if (file_exists($enumBase)) {
    require_once $enumBase;
}

/*
|--------------------------------------------------------------------------
| Ensure Storage Exists
|--------------------------------------------------------------------------
*/
if (!is_dir(QR_CODE_STORAGE_PATH)) {
    mkdir(QR_CODE_STORAGE_PATH, 0775, true);
}

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Autoloader function for PSR-4 style class loading
spl_autoload_register(function ($class) {
    $prefixes = [
        'App\\' => SRC_PATH . '/',
        'Endroid\\QrCode\\' => APP_ROOT . '/vendor/endroid-qrcode/src/',
        'DASPRiD\\Enum\\' => APP_ROOT . '/vendor/dasprid/enum/src/',
        'BaconQrCode\\' => APP_ROOT . '/vendor/bacon/bacon-qr-code/src/',
    ];

    foreach ($prefixes as $prefix => $base_dir) {
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            continue;
        }

        $relative_class = substr($class, $len);
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

        if (file_exists($file)) {
            require $file;
        }

        return;
    }
});

// Include helper files
require_once SRC_PATH . '/Helpers/Response.php';
require_once SRC_PATH . '/Helpers/Logger.php';
require_once SRC_PATH . '/Helpers/Utils.php';

try {
    // Parse the request URL
    $request_uri = $_SERVER['REQUEST_URI'];
    $base_path = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
    
    // Remove base URL prefix
    if (strpos($request_uri, BASE_URL) === 0) {
        $path = substr($request_uri, strlen(BASE_URL));
    } else {
        $path = $request_uri;
    }

    // Remove query string
    if (($pos = strpos($path, '?')) !== false) {
        $path = substr($path, 0, $pos);
    }

    // Remove trailing slash
    $path = rtrim($path, '/');

    if (empty($path)) {
        $path = '/';
    }

    $method = $_SERVER['REQUEST_METHOD'];
    // Route the request
    routeRequest($method, $path);

} catch (Exception $e) {
    \App\Helpers\Logger::error($e->getMessage(), ['exception' => $e]);
    \App\Helpers\Response::error($e->getMessage(), 500);
}

/**
 * Route the incoming request to the appropriate controller
 * 
 * @param string $method HTTP method (GET, POST, PUT, DELETE)
 * @param string $path   Request path
 * @return void
 */
function routeRequest($method, $path)
{
    // Parse the path to extract resource and ID
    $segments = array_filter(explode('/', $path));
    
    if (empty($segments)) {
        \App\Helpers\Response::error('Invalid request path', 400);
        return;
    }

    $resource = array_shift($segments);

    $id = !empty($segments) ? array_shift($segments) : null;

    // Route based on resource
    switch ($resource) {
        case 'member':
            handleMemberRequest($method, $id);
            break;
        
        case 'health':
            // Health check endpoint
            \App\Helpers\Response::success(['status' => 'OK'], 'API is healthy');
            break;

        case 'qrcode':

            require_once SRC_PATH . '/Services/QrCodeService.php';

            $service = new \App\Services\QrCodeService();

            $action = $segments[1] ?? 'index';

            /*
            | GET /v1/qrcode/test
            */
            if ($action === 'test') {
                \App\Helpers\Response::success(['status' => 'OK'], 'QR route reachable');
            }
            
            // handleQrCodeRequest($method, $id, $segments);
            break;

        default:
            \App\Helpers\Response::error('Resource not found', 404);
            break;
    }
}

/**
 * Handle member-related requests
 * 
 * @param string $method HTTP method
 * @param string|null $id Member ID
 * @return void
 */
function handleMemberRequest($method, $id = null)
{
    // Include necessary files
    require_once SRC_PATH . '/Config/Database.php';
    require_once SRC_PATH . '/Controllers/MemberController.php';

    $controller = new \App\Controllers\MemberController();

    try {
        switch ($method) {
            case 'POST':
                // if (empty($id)) {
                //     if( isset($_POST['member-id']) ){
                //         $id = $_POST['member-id'];
                //     } else {
                //     \App\Helpers\Response::error('Invalid request - @index.php', 400);
                //     return;
                //     }
                // }

                // \App\Helpers\Response::success(
                //     [
                //         'post' => $_POST,
                //         'file_upload' => FILES_INBOUND,
                //         'url_path' => URL_PATH,
                //         'resulting' => strpos( URL_PATH , '/v1/member/upload')
                //     ],
                //     'Member created successfully',
                //     201
                // ); 

                // Request::POST >> /v1/member/{id}/attachments
                if ( strpos( URL_PATH , '/v1/member/upload') !== false ) {
                    $uploadType = '';
                    if ( FILES_INBOUND_KEY !== null ){
                        $uploadType = FILES_INBOUND_KEY;
                    }
                        switch ($uploadType) {
                            case 'member-fee-payment-record':
                                $controller->memberFeePayment();
                                break;
                            default:
                                /// nothing
                                break;
                        }
                } elseif ( strpos( URL_PATH , '/v1/member/contribution') !== false ) {
                    $controller->createContribution();
                } else {
                    $controller->create();
                }
                break;

            case 'GET':
                if (empty($id)) {
                    \App\Helpers\Response::error('Member ID is required', 400);
                    return;
                }
                $controller->retrieve($id);
                break;

            case 'PUT':
            case 'PATCH':
                if (empty($id)) {
                    \App\Helpers\Response::error('Member ID is required', 400);
                    return;
                }
                $controller->update($id);
                break;

            case 'DELETE':
                if (empty($id)) {
                    \App\Helpers\Response::error('Member ID is required', 400);
                    return;
                }
                $controller->delete($id);
                break;

            default:
                \App\Helpers\Response::error('Method not allowed', 405);
                break;
        }
    } catch (Exception $e) {
        \App\Helpers\Logger::error($e->getMessage(), ['exception' => $e]);
        \App\Helpers\Response::error($e->getMessage(), 500);
    }
}

/**
 * Handle QR code-related requests
 *
 * @param string $method HTTP method
 * @param string|null $resourceType Resource type after /qrcode
 * @param array<int, string> $segments Remaining path segments
 * @return void
 */
function handleQrCodeRequest($method, $resourceType = null, array $segments = []): void
{
    require_once SRC_PATH . '/Config/Database.php';
    require_once SRC_PATH . '/Controllers/QrCodeController.php';

    $controller = new \App\Controllers\QrCodeController();

    try {
        if ($resourceType !== 'member') {
            \App\Helpers\Response::error('Resource not found', 404);
            return;
        }

        $memberId = !empty($segments) ? array_shift($segments) : null;

        if (empty($memberId)) {
            \App\Helpers\Response::error('Member ID is required', 400);
            return;
        }

        switch ($method) {
            case 'GET':
                $controller->show($memberId);
                return;

            case 'POST':
                $action = !empty($segments) ? array_shift($segments) : null;
                if ($action !== 'regenerate') {
                    \App\Helpers\Response::error('Invalid QR code action', 400);
                    return;
                }

                $controller->regenerate($memberId);
                return;

            default:
                \App\Helpers\Response::error('Method not allowed', 405);
                return;
        }
    } catch (Exception $e) {
        \App\Helpers\Logger::error($e->getMessage(), ['exception' => $e]);
        \App\Helpers\Response::error($e->getMessage(), 500);
    }
}
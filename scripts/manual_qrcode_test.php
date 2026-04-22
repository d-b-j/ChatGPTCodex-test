<?php
/**
 * Manual QR Code Test Script
 *
 * Usage:
 * php scripts/manual_qrcode_test.php <member-uuid> [--include-data-uri]
 *
 * PHP Version: 8.0+
 */

declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

define('APP_ROOT', dirname(__DIR__));
define('SRC_PATH', APP_ROOT . '/src');
define('BASE_URL', '/v1');
define('API_HOST', 'qrrest.technolide.xyz');
define('STORAGE_PATH', APP_ROOT . '/storage');
define('QR_CODE_STORAGE_PATH', STORAGE_PATH . '/qrcodes');
define('APP_URL', 'https://' . API_HOST . BASE_URL);

spl_autoload_register(function ($class) {
    $prefixes = [
        'App\\' => SRC_PATH . '/',
        'Endroid\\QrCode\\' => APP_ROOT . '/vendor/endroid-qrcode/src/',
        'BaconQrCode\\' => APP_ROOT . '/vendor/bacon/bacon-qr-code/src/',
    ];

    foreach ($prefixes as $prefix => $baseDir) {
        $length = strlen($prefix);
        if (strncmp($prefix, $class, $length) !== 0) {
            continue;
        }

        $relativeClass = substr($class, $length);
        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

        if (file_exists($file)) {
            require $file;
        }

        return;
    }
});

require_once SRC_PATH . '/Config/Database.php';

$memberId = $argv[1] ?? '';
$includeDataUri = in_array('--include-data-uri', $argv, true);

if ($memberId === '') {
    fwrite(STDERR, "Usage: php scripts/manual_qrcode_test.php <member-uuid> [--include-data-uri]" . PHP_EOL);
    exit(1);
}

try {
    $service = new \App\Services\QrCodeService();
    $payload = $service->regenerateForMember($memberId, $includeDataUri);

    echo json_encode([
        'success' => true,
        'message' => 'QR code generated successfully',
        'data' => $payload,
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL;

    exit(0);
} catch (\Throwable $exception) {
    fwrite(STDERR, json_encode([
        'success' => false,
        'message' => $exception->getMessage(),
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL);

    exit(1);
}

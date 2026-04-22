<?php
declare(strict_types=1);

/**
 * Manual QR generation test
 *
 * Usage:
 * php scripts/manual_qrcode_test.php UUID
 * php scripts/manual_qrcode_test.php UUID --include-data-uri
 */

error_reporting(E_ALL);
ini_set('display_errors', '1');

define('APP_ROOT', dirname(__DIR__));
define('SRC_PATH', APP_ROOT . '/src');
define('STORAGE_PATH', APP_ROOT . '/storage');
define('QR_CODE_STORAGE_PATH', STORAGE_PATH . '/qrcodes');

if (!is_dir(QR_CODE_STORAGE_PATH)) {
    mkdir(QR_CODE_STORAGE_PATH, 0775, true);
}

spl_autoload_register(function ($class) {

    $map = [

        'App\\' =>
            SRC_PATH . '/',

        'Endroid\\QrCode\\' =>
            APP_ROOT . '/vendor/endroid-qrcode/src/',

        'DASPRiD\\Enum\\' =>
            APP_ROOT . '/vendor/endroid-qrcode/src/DASPRiD/',

        'BaconQrCode\\' =>
            APP_ROOT . '/vendor/bacon/bacon-qr-code/src/',
    ];

    foreach ($map as $prefix => $baseDir) {

        $len = strlen($prefix);

        if (strncmp($prefix, $class, $len) !== 0) {
            continue;
        }

        $relative = substr($class, $len);

        $file = $baseDir . str_replace('\\', '/', $relative) . '.php';

        if (file_exists($file)) {
            require_once $file;
        }

        return;
    }
});

$enumBase = APP_ROOT . '/vendor/endroid-qrcode/src/DASPRiD/Enum.php';

if (file_exists($enumBase)) {
    require_once $enumBase;
}

require_once SRC_PATH . '/Config/Database.php';
require_once SRC_PATH . '/Services/QrCodeService.php';

$memberId = $argv[1] ?? '';
$includeDataUri = in_array('--include-data-uri', $argv, true);

if ($memberId === '') {
    exit("Usage: php scripts/manual_qrcode_test.php UUID [--include-data-uri]\n");
}

try {

    $service = new \App\Services\QrCodeService();

    $result = $service->regenerateForMember(
        $memberId,
        $includeDataUri
    );

    echo json_encode([
        'success' => true,
        'data' => $result
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL;

} catch (\Throwable $e) {

    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ], JSON_PRETTY_PRINT) . PHP_EOL;

    exit(1);
}

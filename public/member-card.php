<?php
declare(strict_types=1);

/**
 * public/member-card.php
 *
 * Production-ready member QR card page
 * Requirements:
 * - Plain PHP backend
 * - Publicly accessible from /public
 * - Existing API endpoint:
 *   GET https://qrrest.technolide.xyz/v1/member/{id}
 */

error_reporting(E_ALL);
ini_set('display_errors', '0');

/*
|--------------------------------------------------------------------------
| Config
|--------------------------------------------------------------------------
*/
define('API_BASE', 'https://qrrest.technolide.xyz/v1');
define('APP_NAME', 'School Alumni');

/*
|--------------------------------------------------------------------------
| Validate Input
|--------------------------------------------------------------------------
*/
$memberId = trim($_GET['id'] ?? '');

if ($memberId === '') {
    http_response_code(400);
    exit('Missing member id');
}

/*
|--------------------------------------------------------------------------
| Safe HTTP Request
|--------------------------------------------------------------------------
*/
function apiGet(string $url): array
{
    $ch = curl_init($url);

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 12,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_HTTPHEADER => [
            'Accept: application/json'
        ]
    ]);

    $response = curl_exec($ch);

    if ($response === false) {
        $err = curl_error($ch);
        curl_close($ch);
        throw new RuntimeException($err);
    }

    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    if ($status >= 400) {
        throw new RuntimeException('API HTTP ' . $status);
    }

    $json = json_decode($response, true);

    if (!is_array($json)) {
        throw new RuntimeException('Invalid API response');
    }

    return $json;
}

/*
|--------------------------------------------------------------------------
| Fetch Member
|--------------------------------------------------------------------------
*/
try {

    $api = apiGet(API_BASE . '/member/' . rawurlencode($memberId));

    if (!($api['success'] ?? false)) {
        throw new RuntimeException('Member not found');
    }

    $member = $api['data'];

} catch (Throwable $e) {

    http_response_code(500);

    echo 'Unable to load member card';
    exit;
}

/*
|--------------------------------------------------------------------------
| Safe Output
|--------------------------------------------------------------------------
*/
function e(?string $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

$fullName = trim(
    ($member['first_name'] ?? '') . ' ' .
    ($member['last_name'] ?? '')
);

$fullName = $fullName !== '' ? $fullName : 'Member';

$email = $member['email'] ?? '';
$phone = $member['phone'] ?? '';
$batch = $member['graduation_year'] ?? '';
$status = $member['status'] ?? 'Active';

/*
|--------------------------------------------------------------------------
| Secure QR Payload URL
|--------------------------------------------------------------------------
*/
$qrUrl = API_BASE . '/member/' . rawurlencode($memberId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= e($fullName) ?> | <?= APP_NAME ?></title>

<meta name="robots" content="noindex,nofollow">

<style>
body{
    margin:0;
    background:#f3f5f7;
    font-family:Arial,Helvetica,sans-serif;
    color:#111;
}

.wrapper{
    max-width:430px;
    margin:24px auto;
    padding:16px;
}

.card{
    background:#fff;
    border-radius:18px;
    padding:24px;
    box-shadow:0 10px 30px rgba(0,0,0,.08);
}

.brand{
    font-size:14px;
    letter-spacing:.08em;
    color:#666;
    margin-bottom:8px;
    text-transform:uppercase;
}

.name{
    font-size:26px;
    font-weight:700;
    line-height:1.2;
    margin-bottom:4px;
}

.meta{
    color:#666;
    font-size:14px;
    margin-bottom:16px;
}

.badge{
    display:inline-block;
    padding:6px 10px;
    border-radius:999px;
    background:#eef2f5;
    font-size:12px;
    margin-bottom:18px;
}

.qrbox{
    border:1px solid #ececec;
    border-radius:14px;
    padding:18px;
    display:flex;
    justify-content:center;
    background:#fff;
}

.info{
    margin-top:18px;
    font-size:14px;
    line-height:1.8;
}

.label{
    color:#666;
    width:85px;
    display:inline-block;
}

.actions{
    display:flex;
    gap:10px;
    margin-top:22px;
}

button,a.btn{
    flex:1;
    border:none;
    padding:12px;
    border-radius:10px;
    cursor:pointer;
    font-size:15px;
    text-align:center;
    text-decoration:none;
}

.primary{
    background:#111;
    color:#fff;
}

.secondary{
    background:#e9edf0;
    color:#111;
}

.footer{
    text-align:center;
    color:#777;
    font-size:12px;
    margin-top:14px;
}

@media print{
    body{
        background:#fff;
    }

    .actions,.footer{
        display:none;
    }

    .wrapper{
        margin:0;
        max-width:none;
    }

    .card{
        box-shadow:none;
        border:1px solid #ddd;
    }
}
</style>
</head>
<body>

<div class="wrapper">

    <div class="card">

        <div class="brand"><?= APP_NAME ?></div>

        <div class="name"><?= e($fullName) ?></div>

        <div class="meta">
            Member ID: <?= e($memberId) ?>
        </div>

        <div class="badge">
            <?= e($status) ?>
        </div>

        <div id="qrcode" class="qrbox"></div>

        <div class="info">

            <?php if ($batch !== ''): ?>
                <div><span class="label">Batch</span><?= e((string)$batch) ?></div>
            <?php endif; ?>

            <?php if ($email !== ''): ?>
                <div><span class="label">Email</span><?= e($email) ?></div>
            <?php endif; ?>

            <?php if ($phone !== ''): ?>
                <div><span class="label">Phone</span><?= e($phone) ?></div>
            <?php endif; ?>

        </div>

        <div class="actions">
            <button class="primary" onclick="downloadQR()">Download QR</button>
            <button class="secondary" onclick="window.print()">Print Card</button>
        </div>

    </div>

    <div class="footer">
        Scan for verified member lookup
    </div>

</div>

<script src="assets/js/qrcode.min.js"></script>

<script>
const qrValue = <?= json_encode($qrUrl, JSON_UNESCAPED_SLASHES) ?>;

new QRCode(document.getElementById("qrcode"), {
    text: qrValue,
    width: 260,
    height: 260,
    correctLevel: QRCode.CorrectLevel.H
});

function downloadQR(){

    const img = document.querySelector("#qrcode img");
    const canvas = document.querySelector("#qrcode canvas");

    let src = null;

    if (img) src = img.src;
    if (canvas) src = canvas.toDataURL("image/png");

    if (!src) {
        alert("QR not ready");
        return;
    }

    const a = document.createElement("a");
    a.href = src;
    a.download = "member-qr-<?= e($memberId) ?>.png";
    a.click();
}
</script>

</body>
</html>
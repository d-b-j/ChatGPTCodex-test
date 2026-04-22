<?php
/**
 * QR Code Service
 *
 * Generates, stores, and retrieves QR code assets for members.
 *
 * PHP Version: 8.0+
 */

namespace App\Services;

use App\Models\Member;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use InvalidArgumentException;
use RuntimeException;

class QrCodeService
{
    /**
     * Member model instance.
     *
     * @var Member
     */
    protected Member $memberModel;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->memberModel = new Member();
    }

    /**
     * Generate and persist a QR code file for the given member.
     *
     * @param string $memberId
     * @return array<string, mixed>
     */
    public function generateForMember(string $memberId): array
    {
        $this->assertMemberExists($memberId);
        $this->assertDependenciesAvailable();
        $this->ensureStorageDirectoryExists();

        $contentUrl = $this->buildMemberContentUrl($memberId);
        $writer = new PngWriter();
        $qrCode = QrCode::create($contentUrl)
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->setSize(320)
            ->setMargin(12)
            ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin());

        $result = $writer->write($qrCode);

        $absolutePath = $this->buildAbsoluteFilePath($memberId);
        $relativePath = $this->buildRelativeFilePath($memberId);

        $result->saveToFile($absolutePath);

        if (!is_file($absolutePath) || filesize($absolutePath) === 0) {
            throw new RuntimeException('QR code file was not written to storage');
        }

        return [
            'member_id' => $memberId,
            'qr_code_path' => $relativePath,
            'qr_code_content_url' => $contentUrl,
            'qr_code_generated_at' => date('Y-m-d H:i:s'),
            'mime_type' => $result->getMimeType(),
            'data_uri' => $result->getDataUri(),
            'file_size' => filesize($absolutePath),
        ];
    }

    /**
     * Retrieve QR code metadata for a member.
     *
     * @param string $memberId
     * @param bool $includeDataUri
     * @return array<string, mixed>
     */
    public function getMemberQrCode(string $memberId, bool $includeDataUri = false): array
    {
        $member = $this->memberModel->getById($memberId);

        if ($member === null) {
            throw new InvalidArgumentException('Member not found');
        }

        if (empty($member['qr_code_path']) || empty($member['qr_code_content_url'])) {
            throw new RuntimeException('QR code reference not found for member');
        }

        $absolutePath = APP_ROOT . '/' . ltrim($member['qr_code_path'], '/');

        if (!is_file($absolutePath)) {
            throw new RuntimeException('QR code file not found in storage');
        }

        $response = [
            'member_id' => $memberId,
            'content_url' => $member['qr_code_content_url'],
            'storage_path' => $member['qr_code_path'],
            'generated_at' => $member['qr_code_generated_at'] ?? null,
            'mime_type' => 'image/png',
            'file_size' => filesize($absolutePath),
        ];

        if ($includeDataUri) {
            $contents = file_get_contents($absolutePath);
            if ($contents === false) {
                throw new RuntimeException('Unable to read QR code file from storage');
            }

            $response['data_uri'] = 'data:image/png;base64,' . base64_encode($contents);
        }

        return $response;
    }

    /**
     * Generate and persist a fresh QR code, then return stored metadata.
     *
     * @param string $memberId
     * @param bool $includeDataUri
     * @return array<string, mixed>
     */
    public function regenerateForMember(string $memberId, bool $includeDataUri = false): array
    {
        $generated = $this->generateForMember($memberId);
        $this->memberModel->updateQrReference($memberId, $generated);

        return $includeDataUri ? $generated : $this->getMemberQrCode($memberId, false);
    }

    /**
     * Validate required QR dependencies.
     *
     * @return void
     */
    protected function assertDependenciesAvailable(): void
    {
        if (!extension_loaded('gd')) {
            throw new RuntimeException('GD extension is required for PNG QR generation');
        }

        if (!class_exists('BaconQrCode\\Encoder\\Encoder')) {
            throw new RuntimeException(
                'Missing dependency: bacon/bacon-qr-code. Install it under /vendor/bacon/bacon-qr-code to enable QR generation'
            );
        }
    }

    /**
     * Ensure the QR storage directory exists and is writable.
     *
     * @return void
     */
    protected function ensureStorageDirectoryExists(): void
    {
        if (!is_dir(QR_CODE_STORAGE_PATH) && !mkdir(QR_CODE_STORAGE_PATH, 0755, true) && !is_dir(QR_CODE_STORAGE_PATH)) {
            throw new RuntimeException('Unable to create QR code storage directory');
        }

        if (!is_writable(QR_CODE_STORAGE_PATH)) {
            throw new RuntimeException('QR code storage directory is not writable');
        }
    }

    /**
     * Ensure the target member exists.
     *
     * @param string $memberId
     * @return void
     */
    protected function assertMemberExists(string $memberId): void
    {
        if (!$this->memberModel->exists($memberId)) {
            throw new InvalidArgumentException('Member not found');
        }
    }

    /**
     * Build the QR content URL encoded into the QR image.
     *
     * @param string $memberId
     * @return string
     */
    protected function buildMemberContentUrl(string $memberId): string
    {
        return APP_URL . '/member/' . rawurlencode($memberId);
    }

    /**
     * Build absolute storage file path.
     *
     * @param string $memberId
     * @return string
     */
    protected function buildAbsoluteFilePath(string $memberId): string
    {
        return QR_CODE_STORAGE_PATH . '/member-' . $memberId . '.png';
    }

    /**
     * Build storage path relative to the project root.
     *
     * @param string $memberId
     * @return string
     */
    protected function buildRelativeFilePath(string $memberId): string
    {
        return 'storage/qrcodes/member-' . $memberId . '.png';
    }
}

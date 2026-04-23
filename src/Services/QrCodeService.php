<?php
declare(strict_types=1);

namespace App\Services;

use App\Config\Database;
use PDO;
use RuntimeException;
use Throwable;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;

class QrCodeService
{
    private PDO $db;

    private string $storagePath;

    public function __construct()
    {
        $database = Database::getInstance();
        $this->db = $database->getConnection();

        $this->storagePath = dirname(__DIR__, 2) . '/storage/qrcodes';

        if (!is_dir($this->storagePath)) {
            mkdir($this->storagePath, 0775, true);
        }
    }

    /**
     * Generate QR for member and persist reference
     */
    public function regenerateForMember(
        string $memberId,
        bool $includeDataUri = false
    ): array {

        $member = $this->findMember($memberId);

        if (!$member) {
            throw new RuntimeException('Member not found');
        }

        $url = $this->buildMemberUrl($memberId);

        $pngBinary = $this->generatePngBinary($url);

        $fileName = $memberId . '.png';

        $absoluteFile = $this->storagePath . '/' . $fileName;

        file_put_contents($absoluteFile, $pngBinary);

        $relativePath = '/storage/qrcodes/' . $fileName;

        $this->persistQrReference($memberId, $relativePath);

        $response = [
            'member_id' => $memberId,
            'member_name' => trim(($member['first_name'] ?? '') . ' ' . ($member['last_name'] ?? '')),
            'qr_url' => $url,
            'qr_image_path' => $relativePath,
            'qr_saved' => true,
        ];

        if ($includeDataUri) {
            $response['qr_data_uri'] =
                'data:image/png;base64,' . base64_encode($pngBinary);
        }

        return $response;
    }

    /**
     * Generate QR binary PNG
     */
    private function generatePngBinary(string $content): string
    {
        try {

            $result = Builder::create()
                ->writer(new PngWriter())
                ->data($content)
                ->size(320)
                ->margin(12)
                ->build();

            return $result->getString();

        } catch (Throwable $e) {
            throw new RuntimeException(
                'QR generation failed: ' . $e->getMessage()
            );
        }
    }

    /**
     * Build member profile URL
     */
    private function buildMemberUrl(string $memberId): string
    {
        return 'https://qrrest.technolide.xyz/v1/member/' . $memberId;
    }

    /**
     * Read member
     */
    private function findMember(string $memberId): ?array
    {
        $sql = "
            SELECT id, first_name, last_name
            FROM members
            WHERE id = :id
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id' => $memberId
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    /**
     * Save QR path into members table
     */
    private function persistQrReference(
        string $memberId,
        string $path
    ): void {

        $hasQrImage = $this->columnExists('members', 'qr_image');

        if ($hasQrImage) {

            $sql = "
                UPDATE members
                SET qr_image = :qr_image
                WHERE id = :id
            ";

            $stmt = $this->db->prepare($sql);

            $stmt->execute([
                ':qr_image' => $path,
                ':id' => $memberId
            ]);

            return;
        }

        $hasQrCode = $this->columnExists('members', 'qr_code');

        if ($hasQrCode) {

            $sql = "
                UPDATE members
                SET qr_code = :qr_code
                WHERE id = :id
            ";

            $stmt = $this->db->prepare($sql);

            $stmt->execute([
                ':qr_code' => $path,
                ':id' => $memberId
            ]);
        }
    }

    /**
     * Check schema safely
     */
    private function columnExists(
        string $table,
        string $column
    ): bool {

        $sql = "
            SHOW COLUMNS
            FROM {$table}
            LIKE :column
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':column' => $column
        ]);

        return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

<?php
/**
 * QR Code Controller
 *
 * Handles member QR code API requests.
 *
 * PHP Version: 8.0+
 */

namespace App\Controllers;

use App\Helpers\Logger;
use App\Helpers\Response;
use App\Services\QrCodeService;
use InvalidArgumentException;
use RuntimeException;

class QrCodeController
{
    /**
     * QR code service.
     *
     * @var QrCodeService
     */
    protected QrCodeService $qrCodeService;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->qrCodeService = new QrCodeService();
    }

    /**
     * Show stored QR code metadata for a member.
     *
     * GET /v1/qrcode/member/{id}?include_data_uri=1
     *
     * @param string $memberId
     * @return void
     */
    public function show(string $memberId): void
    {
        try {
            $this->assertValidUuid($memberId);
            $includeDataUri = $this->shouldIncludeDataUri();
            $payload = $this->qrCodeService->getMemberQrCode($memberId, $includeDataUri);

            Response::success($payload, 'QR code retrieved successfully');
        } catch (InvalidArgumentException $exception) {
            Response::error($exception->getMessage(), $exception->getMessage() === 'Member not found' ? 404 : 400);
        } catch (RuntimeException $exception) {
            $statusCode = $this->resolveRuntimeStatusCode($exception);
            Logger::warning('QR code retrieval failed', ['member_id' => $memberId, 'error' => $exception->getMessage()]);
            Response::error($exception->getMessage(), $statusCode);
        }
    }

    /**
     * Regenerate a member QR code.
     *
     * POST /v1/qrcode/member/{id}/regenerate
     *
     * @param string $memberId
     * @return void
     */
    public function regenerate(string $memberId): void
    {
        try {
            $this->assertValidUuid($memberId);
            $includeDataUri = $this->shouldIncludeDataUri();
            $payload = $this->qrCodeService->regenerateForMember($memberId, $includeDataUri);

            Response::success($payload, 'QR code generated successfully', 201);
        } catch (InvalidArgumentException $exception) {
            Response::error($exception->getMessage(), $exception->getMessage() === 'Member not found' ? 404 : 400);
        } catch (RuntimeException $exception) {
            $statusCode = $this->resolveRuntimeStatusCode($exception);
            Logger::error('QR code generation failed', ['member_id' => $memberId, 'error' => $exception->getMessage()]);
            Response::error($exception->getMessage(), $statusCode);
        }
    }

    /**
     * Determine whether the client asked for a data URI.
     *
     * @return bool
     */
    protected function shouldIncludeDataUri(): bool
    {
        if (!isset($_GET['include_data_uri'])) {
            return false;
        }

        return in_array(strtolower((string) $_GET['include_data_uri']), ['1', 'true', 'yes'], true);
    }

    /**
     * Validate UUID v4 member IDs.
     *
     * @param string $memberId
     * @return void
     */
    protected function assertValidUuid(string $memberId): void
    {
        if (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $memberId) !== 1) {
            throw new InvalidArgumentException('Invalid member ID format');
        }
    }

    /**
     * Map runtime failures to HTTP status codes.
     *
     * @param RuntimeException $exception
     * @return int
     */
    protected function resolveRuntimeStatusCode(RuntimeException $exception): int
    {
        $message = $exception->getMessage();

        if (strpos($message, 'Missing dependency:') === 0) {
            return 503;
        }

        if (strpos($message, 'QR code reference not found') === 0 || strpos($message, 'QR code file not found') === 0) {
            return 404;
        }

        if (strpos($message, 'storage') !== false || strpos($message, 'GD extension') !== false) {
            return 500;
        }

        return 500;
    }
}

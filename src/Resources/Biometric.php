<?php

namespace XShaan\Zohal\Resources;

use XShaan\Zohal\Contracts\ZohalClient;
use XShaan\Zohal\DTO\InquiryResult;

class Biometric
{
    public function __construct(protected ZohalClient $client)
    {
    }

    /**
     * آپلود مدیا (مثلاً ویدئوی سلفی). خروجی معمولاً UUID مدیا را برمی‌گرداند.
     *
     * @param  string  $filePath  مسیر فایل محلی
     * @param  string  $type  مثلاً selfie_video
     */
    public function uploadMedia(string $filePath, string $type = 'selfie_video'): InquiryResult
    {
        return $this->client->upload(
            '/services/biometric/media/',
            ['file' => $filePath],
            ['type' => $type],
        );
    }

    /**
     * ثبت جلسه Liveness.
     *
     * @param  array{selfie_video: string}  $media  UUIDهای برگشتی از uploadMedia
     */
    public function liveness(
        string $nationalCode,
        string $birthDate,
        string $nationalCardSerial,
        array $media,
        string $callbackUrl,
    ): InquiryResult {
        return $this->client->post('/services/biometric/session/liveness/', [
            'national_code' => $nationalCode,
            'birth_date' => $birthDate,
            'national_card_serial' => $nationalCardSerial,
            'media' => $media,
            'callback_url' => $callbackUrl,
        ]);
    }

    public function sessionResult(string $sessionId): InquiryResult
    {
        return $this->client->get('/services/biometric/session/'.$sessionId.'/result');
    }
}

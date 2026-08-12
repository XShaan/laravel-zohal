<?php

namespace XShaan\Zohal\Resources;

use XShaan\Zohal\Contracts\ZohalClient;
use XShaan\Zohal\DTO\InquiryResult;

class Credit
{
    public function __construct(protected ZohalClient $client)
    {
    }

    /** شروع اعتبارسنجی — ارسال OTP */
    public function sendOtp(string $nationalCode, string $mobile): InquiryResult
    {
        return $this->client->post('/services/inquiry/credit_inquiry/send_otp', [
            'national_code' => $nationalCode,
            'mobile' => $mobile,
        ]);
    }

    public function verifyOtp(string $referenceId, string $otp): InquiryResult
    {
        return $this->client->post('/services/inquiry/credit_inquiry/verify_otp', [
            'reference_id' => $referenceId,
            'otp' => $otp,
        ]);
    }

    public function result(string $referenceId): InquiryResult
    {
        return $this->client->get('/services/inquiry/credit_inquiry/result/'.$referenceId);
    }
}

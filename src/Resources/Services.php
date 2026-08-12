<?php

namespace XShaan\Zohal\Resources;

use XShaan\Zohal\Contracts\ZohalClient;
use XShaan\Zohal\DTO\InquiryResult;

class Services
{
    public function __construct(protected ZohalClient $client)
    {
    }

    public function postalCode(string $postalCode): InquiryResult
    {
        return $this->client->post('/services/inquiry/postal_code_inquiry', [
            'postal_code' => $postalCode,
        ]);
    }

    public function company(string $nationalId): InquiryResult
    {
        return $this->client->post('/services/inquiry/company_inquiry', [
            'national_id' => $nationalId,
        ]);
    }

    public function companyBoardMembers(string $nationalId): InquiryResult
    {
        return $this->client->post('/services/inquiry/company_inquiry/board_members', [
            'national_id' => $nationalId,
        ]);
    }

    public function companyBoardMembersHistory(string $nationalId): InquiryResult
    {
        return $this->client->post('/services/inquiry/company_inquiry/board_members/history', [
            'national_id' => $nationalId,
        ]);
    }

    public function enamad(string $website): InquiryResult
    {
        return $this->client->post('/services/inquiry/enamad_inquiry', [
            'website' => $website,
        ]);
    }

    public function persianToFinglish(string $persianText): InquiryResult
    {
        return $this->client->post('/services/inquiry/persian_to_finglish', [
            'persian_text' => $persianText,
        ]);
    }

    /** تأیید OTP صوتی */
    public function voiceOtp(string $mobile, string $code): InquiryResult
    {
        return $this->client->post('/services/inquiry/voice_otp', [
            'mobile' => $mobile,
            'code' => $code,
        ]);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function raw(string $path, array $payload = []): InquiryResult
    {
        return $this->client->post($path, $payload);
    }
}

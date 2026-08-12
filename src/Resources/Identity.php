<?php

namespace XShaan\Zohal\Resources;

use XShaan\Zohal\Contracts\ZohalClient;
use XShaan\Zohal\DTO\InquiryResult;

class Identity
{
    public function __construct(protected ZohalClient $client)
    {
    }

    /** شاهکار — تطابق کد ملی و موبایل */
    public function shahkar(string $nationalCode, string $mobile): InquiryResult
    {
        return $this->client->post('/services/inquiry/shahkar', [
            'national_code' => $nationalCode,
            'mobile' => $mobile,
        ]);
    }

    /** تطابق کارت با کد ملی (تاریخ تولد شمسی Y/m/d) */
    public function checkCardWithNationalCode(
        string $nationalCode,
        string $cardNumber,
        string $birthDate,
    ): InquiryResult {
        return $this->client->post('/services/inquiry/check_card_with_national_code', [
            'national_code' => $nationalCode,
            'card_number' => $cardNumber,
            'birth_date' => $birthDate,
        ]);
    }

    /** تطابق شبا با کد ملی */
    public function checkIbanWithNationalCode(
        string $nationalCode,
        string $iban,
        string $birthDate,
    ): InquiryResult {
        return $this->client->post('/services/inquiry/check_iban_with_national_code', [
            'national_code' => $nationalCode,
            'IBAN' => $iban,
            'birth_date' => $birthDate,
        ]);
    }

    /** استعلام ثبت احوال */
    public function nationalIdentity(string $nationalCode, string $birthDate): InquiryResult
    {
        return $this->client->post('/services/inquiry/national_identity_inquiry', [
            'national_code' => $nationalCode,
            'birth_date' => $birthDate,
        ]);
    }

    /**
     * OCR کارت ملی (تصویر رو الزامی، پشت اختیاری).
     *
     * @param  string  $frontPath  مسیر فایل تصویر روی کارت
     * @param  string|null  $backPath  مسیر فایل پشت کارت
     */
    public function nationalCardOcr(string $frontPath, ?string $backPath = null): InquiryResult
    {
        $files = ['national_card_front' => $frontPath];

        if ($backPath !== null) {
            $files['national_card_back'] = $backPath;
        }

        return $this->client->upload('/services/inquiry/national_card_ocr', $files);
    }
}

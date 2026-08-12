<?php

namespace XShaan\Zohal\Resources;

use XShaan\Zohal\Contracts\ZohalClient;
use XShaan\Zohal\DTO\InquiryResult;

class Cheque
{
    public function __construct(protected ZohalClient $client)
    {
    }

    /** استعلام چک صیادی */
    public function sayad(string $sayadId): InquiryResult
    {
        return $this->client->post('/services/inquiry/check_sayad_inquiry', [
            'sayad_id' => $sayadId,
        ]);
    }

    /**
     * زنجیره انتقالات چک صیادی.
     *
     * @param  string  $chequeType  معمولاً «1»
     */
    public function sayadChain(string $sayadId, string $nationalCode, string $chequeType = '1'): InquiryResult
    {
        return $this->client->post('/services/inquiry/check_sayad_inquiry/chain', [
            'sayad_id' => $sayadId,
            'national_code' => $nationalCode,
            'cheque_type' => $chequeType,
        ]);
    }

    /**
     * تعداد چک‌های برگشتی.
     *
     * @param  int  $nationalityType  ۱ = ایرانی
     */
    public function bounced(string $nationalCode, int $nationalityType = 1): InquiryResult
    {
        return $this->client->post('/services/inquiry/bounced_cheque', [
            'national_code' => $nationalCode,
            'nationality_type' => $nationalityType,
        ]);
    }
}

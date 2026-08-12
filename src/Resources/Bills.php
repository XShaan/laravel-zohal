<?php

namespace XShaan\Zohal\Resources;

use XShaan\Zohal\Contracts\ZohalClient;
use XShaan\Zohal\DTO\InquiryResult;

class Bills
{
    public function __construct(protected ZohalClient $client)
    {
    }

    public function rightel(string $mobile): InquiryResult
    {
        return $this->client->post('/services/inquiry/bill/rightel', [
            'mobile' => $mobile,
        ]);
    }

    public function mci(string $mobile): InquiryResult
    {
        return $this->client->post('/services/inquiry/bill/mci', [
            'mobile' => $mobile,
        ]);
    }

    public function irancell(string $mobile): InquiryResult
    {
        return $this->client->post('/services/inquiry/bill/irancell', [
            'mobile' => $mobile,
        ]);
    }

    public function fixedLine(string $phone): InquiryResult
    {
        return $this->client->post('/services/inquiry/bill/fixed_line', [
            'mobile' => $phone,
        ]);
    }

    public function gas(string $billId): InquiryResult
    {
        return $this->client->post('/services/inquiry/bill/gas', [
            'bill_id' => $billId,
        ]);
    }

    public function water(string $billId): InquiryResult
    {
        return $this->client->post('/services/inquiry/bill/water', [
            'bill_id' => $billId,
        ]);
    }

    public function electricity(string $billId): InquiryResult
    {
        return $this->client->post('/services/inquiry/bill/electricity', [
            'bill_id' => $billId,
        ]);
    }
}

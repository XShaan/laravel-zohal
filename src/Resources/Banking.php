<?php

namespace XShaan\Zohal\Resources;

use XShaan\Zohal\Contracts\ZohalClient;
use XShaan\Zohal\DTO\InquiryResult;

class Banking
{
    public function __construct(protected ZohalClient $client)
    {
    }

    /** نام صاحب کارت */
    public function cardInquiry(string $cardNumber): InquiryResult
    {
        return $this->client->post('/services/inquiry/card_inquiry', [
            'card_number' => $cardNumber,
        ]);
    }

    public function cardToIban(string $cardNumber): InquiryResult
    {
        return $this->client->post('/services/inquiry/card_to_iban', [
            'card_number' => $cardNumber,
        ]);
    }

    public function cardToAccount(string $cardNumber): InquiryResult
    {
        return $this->client->post('/services/inquiry/card_to_account', [
            'card_number' => $cardNumber,
        ]);
    }

    public function accountToIban(string $bankAccount, string $bankCode): InquiryResult
    {
        return $this->client->post('/services/inquiry/account_to_iban', [
            'bank_account' => $bankAccount,
            'bank_code' => $bankCode,
        ]);
    }

    public function ibanInquiry(string $iban): InquiryResult
    {
        return $this->client->post('/services/inquiry/iban', [
            'iban' => $iban,
        ]);
    }

    public function checkCardWithName(string $cardNumber, string $name): InquiryResult
    {
        return $this->client->post('/services/inquiry/check_card_with_name', [
            'card_number' => $cardNumber,
            'name' => $name,
        ]);
    }

    public function checkIbanWithName(string $iban, string $name): InquiryResult
    {
        return $this->client->post('/services/inquiry/check_iban_with_name', [
            'IBAN' => $iban,
            'name' => $name,
        ]);
    }
}

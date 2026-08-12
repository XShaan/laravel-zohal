<?php

namespace XShaan\Zohal;

use XShaan\Zohal\Contracts\ZohalClient;
use XShaan\Zohal\DTO\InquiryResult;
use XShaan\Zohal\Resources\Banking;
use XShaan\Zohal\Resources\Bills;
use XShaan\Zohal\Resources\Biometric;
use XShaan\Zohal\Resources\Cheque;
use XShaan\Zohal\Resources\Credit;
use XShaan\Zohal\Resources\Identity;
use XShaan\Zohal\Resources\Services;
use XShaan\Zohal\Resources\Vehicle;
use XShaan\Zohal\Support\JalaliDate;

class Zohal
{
    protected ?Identity $identity = null;

    protected ?Banking $banking = null;

    protected ?Cheque $cheque = null;

    protected ?Vehicle $vehicle = null;

    protected ?Bills $bills = null;

    protected ?Credit $credit = null;

    protected ?Biometric $biometric = null;

    protected ?Services $services = null;

    public function __construct(protected ZohalClient $client)
    {
    }

    public function client(): ZohalClient
    {
        return $this->client;
    }

    public function identity(): Identity
    {
        return $this->identity ??= new Identity($this->client);
    }

    public function banking(): Banking
    {
        return $this->banking ??= new Banking($this->client);
    }

    public function cheque(): Cheque
    {
        return $this->cheque ??= new Cheque($this->client);
    }

    public function vehicle(): Vehicle
    {
        return $this->vehicle ??= new Vehicle($this->client);
    }

    public function bills(): Bills
    {
        return $this->bills ??= new Bills($this->client);
    }

    public function credit(): Credit
    {
        return $this->credit ??= new Credit($this->client);
    }

    public function biometric(): Biometric
    {
        return $this->biometric ??= new Biometric($this->client);
    }

    public function services(): Services
    {
        return $this->services ??= new Services($this->client);
    }

    public function shahkar(string $nationalCode, string $mobile): InquiryResult
    {
        return $this->identity()->shahkar($nationalCode, $mobile);
    }

    /**
     * @param  string  $birthDate  شمسی `Y/m/d` (جداکننده `-` هم قبول می‌شود)
     */
    public function checkCard(string $nationalCode, string $cardNumber, string $birthDate): InquiryResult
    {
        return $this->identity()->checkCardWithNationalCode(
            $nationalCode,
            $cardNumber,
            JalaliDate::normalize($birthDate),
        );
    }

    /**
     * فراخوانی مستقیم هر مسیر کاتالوگ زحل.
     *
     * @param  array<string, mixed>  $payload
     */
    public function inquiry(string $path, array $payload = []): InquiryResult
    {
        return $this->client->post($path, $payload);
    }
}

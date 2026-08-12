<?php

namespace XShaan\Zohal\Facades;

use Illuminate\Support\Facades\Facade;
use XShaan\Zohal\DTO\InquiryResult;
use XShaan\Zohal\Resources\Banking;
use XShaan\Zohal\Resources\Bills;
use XShaan\Zohal\Resources\Biometric;
use XShaan\Zohal\Resources\Cheque;
use XShaan\Zohal\Resources\Credit;
use XShaan\Zohal\Resources\Identity;
use XShaan\Zohal\Resources\Services;
use XShaan\Zohal\Resources\Vehicle;

/**
 * @method static \XShaan\Zohal\Contracts\ZohalClient client()
 * @method static Identity identity()
 * @method static Banking banking()
 * @method static Cheque cheque()
 * @method static Vehicle vehicle()
 * @method static Bills bills()
 * @method static Credit credit()
 * @method static Biometric biometric()
 * @method static Services services()
 * @method static InquiryResult shahkar(string $nationalCode, string $mobile)
 * @method static InquiryResult checkCard(string $nationalCode, string $cardNumber, string $birthDate)
 * @method static InquiryResult inquiry(string $path, array $payload = [])
 *
 * @see \XShaan\Zohal\Zohal
 */
class Zohal extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \XShaan\Zohal\Zohal::class;
    }
}

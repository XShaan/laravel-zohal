<?php

namespace XShaan\Zohal\Resources;

use XShaan\Zohal\Contracts\ZohalClient;
use XShaan\Zohal\DTO\InquiryResult;

class Vehicle
{
    public function __construct(protected ZohalClient $client)
    {
    }

    /** مجموع خلافی خودرو */
    public function totalViolations(
        string $nationalCode,
        string $mobile,
        string $plateNumber,
        string $regionCode,
    ): InquiryResult {
        return $this->client->post('/services/inquiry/vehicle_inquiry/total_violations', [
            'national_code' => $nationalCode,
            'mobile' => $mobile,
            'plate_number' => $plateNumber,
            'region_code' => $regionCode,
        ]);
    }

    /** جزئیات خلافی خودرو */
    public function violationsDetails(
        string $nationalCode,
        string $mobile,
        string $plateNumber,
        string $regionCode,
    ): InquiryResult {
        return $this->client->post('/services/inquiry/vehicle_inquiry/violations_details', [
            'national_code' => $nationalCode,
            'mobile' => $mobile,
            'plate_number' => $plateNumber,
            'region_code' => $regionCode,
        ]);
    }
}

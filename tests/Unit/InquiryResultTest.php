<?php

namespace XShaan\Zohal\Tests\Unit;

use PHPUnit\Framework\TestCase;
use XShaan\Zohal\DTO\InquiryResult;
use XShaan\Zohal\Support\ErrorMessages;
use XShaan\Zohal\Support\JalaliDate;

class InquiryResultTest extends TestCase
{
    public function test_it_parses_wrapped_response_body(): void
    {
        $result = InquiryResult::fromResponse([
            'result' => 1,
            'response_body' => [
                'data' => ['matched' => true],
                'error_code' => null,
                'message' => 'موفق',
            ],
        ]);

        $this->assertTrue($result->ok());
        $this->assertTrue($result->isMatched());
        $this->assertSame('موفق', $result->message);
        $this->assertNull($result->errorCode);
    }

    public function test_it_parses_unmatched_payload(): void
    {
        $result = InquiryResult::fromResponse([
            'result' => 1,
            'response_body' => [
                'data' => ['matched' => false],
                'error_code' => null,
                'message' => 'موفق',
            ],
        ]);

        $this->assertTrue($result->ok());
        $this->assertFalse($result->isMatched());
    }

    public function test_it_treats_non_standard_200_payload_as_ok(): void
    {
        $result = InquiryResult::fromResponse([
            'reference_id' => 'abc',
            'status' => 'pending',
        ], 200);

        $this->assertTrue($result->ok());
        $this->assertSame('abc', $result->data['reference_id']);
    }

    public function test_jalali_normalize_keeps_jalali_and_converts_iso(): void
    {
        $this->assertSame('1377/07/19', JalaliDate::normalize('1377/7/19'));
        $this->assertSame('1380/12/25', JalaliDate::normalize('1380-12-25'));
    }

    public function test_error_messages_prefer_known_codes(): void
    {
        $this->assertSame(
            'شماره کارت نامعتبر است.',
            ErrorMessages::forResult(6, 'whatever', 'CARD_NUMBER_NOT_VALID'),
        );
    }
}

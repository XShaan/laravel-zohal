<?php

namespace XShaan\Zohal\Support;

class ErrorMessages
{
    /** @var array<int, string> */
    protected static array $results = [
        1 => 'درخواست با موفقیت انجام شد.',
        4 => 'درخواست نامعتبر است. پارامترها یا توکن را بررسی کنید.',
        5 => 'خطای داخلی سرور زحل. کمی بعد دوباره تلاش کنید.',
        6 => 'ورودی نادرست است یا سرویس موقتاً در دسترس نیست.',
    ];

    /** @var array<string, string> */
    protected static array $codes = [
        'ACCOUNT_NOT_ACTIVE' => 'حساب متصل به کارت فعال نیست.',
        'CARD_EXPIRED' => 'کارت منقضی شده است.',
        'CARD_LOST' => 'کارت به‌عنوان مفقود ثبت شده است.',
        'CARD_NOT_ACTIVE' => 'کارت غیرفعال است.',
        'CARD_NOT_FOUND' => 'کارت در سیستم بانکی یافت نشد.',
        'CARD_NUMBER_NOT_VALID' => 'شماره کارت نامعتبر است.',
        'IBAN_NOT_FOUND' => 'شماره شبا یافت نشد.',
        'IBAN_NOT_VALID' => 'شماره شبا نامعتبر است.',
        'NATIONAL_ID_INVALID' => 'شناسه ملی اشتباه است.',
        'NO_DATA_FOUND_FOR_COMPANY' => 'برای این شناسه ملی اطلاعاتی یافت نشد.',
    ];

    public static function forResult(int $result, ?string $apiMessage = null, ?string $errorCode = null): string
    {
        if ($errorCode && isset(self::$codes[$errorCode])) {
            return self::$codes[$errorCode];
        }

        if ($apiMessage !== null && $apiMessage !== '' && self::looksPersian($apiMessage)) {
            return $apiMessage;
        }

        if ($apiMessage !== null && $apiMessage !== '') {
            $mapped = self::translateLoose($apiMessage);
            if ($mapped !== null) {
                return $mapped;
            }
        }

        return self::$results[$result] ?? 'خطای ناشناخته از سرویس زحل (کد '.$result.').';
    }

    public static function missingToken(): string
    {
        return 'توکن زحل تنظیم نشده است. مقدار ZOHAL_TOKEN را در فایل محیطی قرار دهید.';
    }

    public static function unreachable(string $detail = ''): string
    {
        $base = 'ارتباط با سرویس زحل برقرار نشد.';

        return $detail !== '' ? $base.' ('.$detail.')' : $base;
    }

    public static function invalidDate(string $date): string
    {
        return "فرمت تاریخ تولد پشتیبانی نمی‌شود: {$date}. از قالب شمسی Y/m/d استفاده کنید.";
    }

    protected static function looksPersian(string $text): bool
    {
        return (bool) preg_match('/[\x{0600}-\x{06FF}]/u', $text);
    }

    protected static function translateLoose(string $message): ?string
    {
        $lower = strtolower($message);

        if (str_contains($lower, 'national code')) {
            return 'کد ملی نامعتبر است.';
        }

        if (str_contains($lower, 'mobile')) {
            return 'شماره موبایل نامعتبر است.';
        }

        return null;
    }
}

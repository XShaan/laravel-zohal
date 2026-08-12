<?php

namespace XShaan\Zohal\Support;

use InvalidArgumentException;

class JalaliDate
{
    /**
     * نرمال‌سازی جداکننده و صفرگذاری به `Y/m/d` — تبدیل میلادی↔شمسی انجام نمی‌شود.
     */
    public static function normalize(string $date): string
    {
        $date = trim($date);

        if (preg_match('/^(\d{4})[\/\-](\d{1,2})[\/\-](\d{1,2})$/', $date, $m) !== 1) {
            throw new InvalidArgumentException(ErrorMessages::invalidDate($date));
        }

        return sprintf('%04d/%02d/%02d', (int) $m[1], (int) $m[2], (int) $m[3]);
    }
}

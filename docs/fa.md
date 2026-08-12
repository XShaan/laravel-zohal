# مستندات فارسی Laravel Zohal

کلاینت لاراول برای وب‌سرویس‌های استعلام [زحل](https://zohal.io) — بر اساس کاتالوگ OpenAPI رسمی.

> راهنمای انگلیسی: [README.md](../README.md)

## نصب

```bash
composer require xshaan/laravel-zohal
```

```bash
php artisan vendor:publish --tag=zohal-config
```

```env
ZOHAL_TOKEN=توکن-پنل-توسعه‌دهندگان
# ZOHAL_BASE_URL=https://service.zohal.io/api/v0
# ZOHAL_TIMEOUT=30
# ZOHAL_THROW=true
```

قبل از فراخوانی production، IP سرور را در پنل زحل سفید کنید.

## شروع سریع

```php
use XShaan\Zohal\Facades\Zohal;

$result = Zohal::shahkar('0012345678', '09121234567');

if ($result->isMatched()) {
    // تطابق برقرار است
}

// تاریخ تولد باید شمسی باشد (Y/m/d)
$result = Zohal::checkCard('0012345678', '6037991234567890', '1377/07/19');
```

## ماژول‌ها و امکانات

همه مسیرهای کاتالوگ زحل پوشش داده شده‌اند. برای مسیرهای خاص می‌توانید از `Zohal::inquiry($path, $payload)` هم استفاده کنید.

### هویت — `identity()`

| متد | مسیر API |
|-----|----------|
| `shahkar($nationalCode, $mobile)` | شاهکار |
| `checkCardWithNationalCode(...)` | تطابق کارت و کد ملی |
| `checkIbanWithNationalCode(...)` | تطابق شبا و کد ملی |
| `nationalIdentity(...)` | استعلام ثبت احوال |
| `nationalCardOcr($front, $back?)` | OCR کارت ملی (multipart) |

میانبرها: `Zohal::shahkar()` و `Zohal::checkCard()`.

### بانکی — `banking()`

| متد | توضیح |
|-----|--------|
| `cardInquiry` | نام صاحب کارت |
| `cardToIban` / `cardToAccount` | تبدیل کارت |
| `accountToIban` | تبدیل حساب به شبا |
| `ibanInquiry` | استعلام شبا |
| `checkCardWithName` / `checkIbanWithName` | تطابق با نام |

### چک — `cheque()`

| متد | توضیح |
|-----|--------|
| `sayad($sayadId)` | استعلام صیادی |
| `sayadChain(...)` | زنجیره انتقالات |
| `bounced($nationalCode, $nationalityType = 1)` | چک برگشتی |

### خودرو — `vehicle()`

| متد | توضیح |
|-----|--------|
| `totalViolations(...)` | مجموع خلافی |
| `violationsDetails(...)` | جزئیات خلافی |

پارامترها: کد ملی، موبایل، پلاک، کد منطقه.

### قبوض — `bills()`

`rightel` / `mci` / `irancell` / `fixedLine` (موبایل یا تلفن) و `gas` / `water` / `electricity` (شناسه قبض).

### اعتبارسنجی — `credit()`

```php
$otp = Zohal::credit()->sendOtp($nationalCode, $mobile);
$ref = $otp->data['reference_id'] ?? null;

Zohal::credit()->verifyOtp($ref, '12345');
$report = Zohal::credit()->result($ref);
```

### بایومتریک — `biometric()`

```php
$media = Zohal::biometric()->uploadMedia('/path/to/selfie.mp4');
$uuid = $media->data['uuid'] ?? $media->data['id'] ?? null;

$session = Zohal::biometric()->liveness(
    nationalCode: '...',
    birthDate: '1370/01/01',
    nationalCardSerial: '...',
    media: ['selfie_video' => $uuid],
    callbackUrl: 'https://example.com/webhook',
);

Zohal::biometric()->sessionResult($session->data['session_id']);
```

### خدماتی — `services()`

کد پستی، شرکت (و هیئت‌مدیره / تاریخچه)، اینماد، فارسی→فینگلیش، OTP صوتی، و `raw($path, $payload)`.

## شکل پاسخ

```json
{
  "result": 1,
  "response_body": {
    "data": { "matched": true },
    "message": "موفق",
    "error_code": null
  }
}
```

`InquiryResult` فیلدها را باز می‌کند:

| متد | معنی |
|-----|------|
| `ok()` | `result === 1` |
| `isMatched()` | موفق و `matched === true` |
| `message` / `errorCode` / `data` | پیام، کد خطا، داده |
| `toArray()` | آرایه یکدست برای لایه اپلیکیشن |

## کدهای result زحل

| کد | معنی |
|----|------|
| ۱ | موفق |
| ۴ | درخواست نامعتبر |
| ۵ | خطای داخلی زحل |
| ۶ | ورودی نادرست / سرویس در دسترس نیست |

با `ZOHAL_THROW=true` (پیش‌فرض)، هر نتیجه‌ای غیر از ۱ باعث `ZohalApiException` می‌شود.  
`matched: false` همراه با `result: 1` **خطا نیست**.

## خطاها (فارسی)

پیام‌های پرتاب‌شده توسط پکیج فارسی و قابل‌نمایش به کاربرند:

| موقعیت | نمونه پیام |
|--------|------------|
| توکن خالی | توکن زحل تنظیم نشده است… |
| قطع ارتباط | ارتباط با سرویس زحل برقرار نشد. |
| result = ۴ / ۵ / ۶ | ترجمه هوشمند بر اساس کد |
| کدهای شناخته‌شده (`CARD_NOT_FOUND` و …) | پیام فارسی متناظر |
| پیام انگلیسی از API | در صورت امکان به فارسی برگردانده می‌شود |
| تاریخ نامعتبر | فرمت تاریخ تولد پشتیبانی نمی‌شود… |

```php
use XShaan\Zohal\Exceptions\ZohalApiException;

try {
    Zohal::shahkar($code, $mobile);
} catch (ZohalApiException $e) {
    // $e->getMessage() فارسی است
    $e->resultCode(); // مثلاً 6
    $e->errorCode();  // مثلاً CARD_NOT_FOUND
}
```

برای خاموش کردن پرتاب خودکار: `ZOHAL_THROW=false` و خودتان `$result->ok()` را چک کنید.

## نکات

- تاریخ‌های هویتی را **شمسی** بفرستید (`1377/07/19`).
- فیلد شبا در بعضی سرویس‌ها `IBAN` (حروف بزرگ) است؛ متدهای پکیج همین قرارداد را رعایت می‌کنند.
- آپلود فایل (OCR / بایومتریک) مسیر فایل محلی می‌گیرد.

## مجوز

MIT

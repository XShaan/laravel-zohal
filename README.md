# Laravel Zohal

Thin Laravel client for [Zohal](https://zohal.io) inquiry APIs — identity, banking, cheques, bills, credit scoring, biometric, and the rest of the catalog.

**مستندات فارسی:** [docs/fa.md](docs/fa.md)

## Requirements

- PHP 8.2+
- Laravel 10 / 11 / 12

## Install

```bash
composer require xshaan/laravel-zohal
```

Publish config (optional):

```bash
php artisan vendor:publish --tag=zohal-config
```

Environment:

```env
ZOHAL_TOKEN=your-token-from-zohal-panel
# ZOHAL_BASE_URL=https://service.zohal.io/api/v0
# ZOHAL_TIMEOUT=30
# ZOHAL_THROW=true
```

Whitelisted server IPs are required by Zohal — configure them in the developer panel before calling production endpoints.

## Quick start

```php
use XShaan\Zohal\Facades\Zohal;

$result = Zohal::shahkar('0012345678', '09121234567');

if ($result->isMatched()) {
    // ok
}

// Birth date must be Jalali Y/m/d
$result = Zohal::checkCard('0012345678', '6037991234567890', '1377/07/19');

$result->ok();        // API accepted the request (result === 1)
$result->isMatched(); // matched === true
$result->toArray();   // ['success' => ..., 'matched' => ..., 'provider' => 'zohal', ...]
```

### Modules

```php
Zohal::identity()->shahkar($nationalCode, $mobile);
Zohal::identity()->checkCardWithNationalCode($nationalCode, $card, $birthDate);
Zohal::identity()->checkIbanWithNationalCode($nationalCode, $iban, $birthDate);
Zohal::identity()->nationalIdentity($nationalCode, $birthDate);
Zohal::identity()->nationalCardOcr($frontPath, $backPath);

Zohal::banking()->cardInquiry($card);
Zohal::banking()->cardToIban($card);
Zohal::banking()->ibanInquiry($iban);

Zohal::cheque()->sayad($sayadId);
Zohal::cheque()->bounced($nationalCode);

Zohal::vehicle()->totalViolations($nationalCode, $mobile, $plate, $region);

Zohal::bills()->mci($mobile);
Zohal::bills()->electricity($billId);

Zohal::credit()->sendOtp($nationalCode, $mobile);
Zohal::credit()->verifyOtp($referenceId, $otp);
Zohal::credit()->result($referenceId);

Zohal::biometric()->uploadMedia($videoPath);
Zohal::biometric()->liveness(...);
Zohal::biometric()->sessionResult($sessionId);

Zohal::services()->postalCode('1234567890');
Zohal::services()->company($nationalId);
Zohal::services()->voiceOtp($mobile, $code);

// Any path from the Zohal OpenAPI catalog
Zohal::inquiry('/services/inquiry/enamad_inquiry', ['website' => 'https://example.com']);
```

### Container / DI

```php
use XShaan\Zohal\Zohal;

public function __construct(private Zohal $zohal) {}

public function handle(): void
{
    $this->zohal->shahkar(...);
}
```

## Response shape

Successful inquiries usually look like:

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

`InquiryResult` unwraps `response_body` for you. Result codes from Zohal:

| result | meaning |
|--------|---------|
| 1 | success |
| 4 | invalid request |
| 5 | internal error |
| 6 | bad input / unavailable |

When `ZOHAL_THROW=true` (default), non-`1` results raise `XShaan\Zohal\Exceptions\ZohalApiException` with a **Persian** message. A `matched: false` response with `result: 1` is **not** an exception.

## Local path install (development)

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/XShaan/laravel-zohal"
    }
  ],
  "require": {
    "xshaan/laravel-zohal": "^1.0"
  }
}
```

Or symlink a checkout:

```json
{
  "repositories": [
    {
      "type": "path",
      "url": "../laravel-zohal",
      "options": { "symlink": true }
    }
  ]
}
```

```bash
composer require xshaan/laravel-zohal:@dev
```

## License

MIT

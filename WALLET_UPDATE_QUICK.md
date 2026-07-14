# ملخص سريع — تحديث نظام المحافظ (Wallets)

> **التوثيق الكامل (إيداعات + محافظ + تعديل):** [`WALLETS_DEPOSITS_DOCUMENTATION_AR.md`](./WALLETS_DEPOSITS_DOCUMENTATION_AR.md)

تاريخ: يونيو 2026 · Laravel 8

---

## الهدف

فصل محافظ المستخدم للأدمن: **إيداع حقيقي، كريديت، بونص، MUP** — مع رصيد رئيسي للتداول = (إيداع + بونص + MUP) والكريديت منفصل حتى الموافقة.

---

## أعمدة قاعدة البيانات (`info_trade_users`)

| العمود | المعنى |
|--------|--------|
| `real_deposit` | الإيداع الحقيقي فقط (محفظة Real) |
| `bonus` | البونص |
| `mup` | MUP |
| `balance` | **مجموع** `real_deposit + bonus + mup` (يتحدّث تلقائياً) |
| `awaiting_deposit` | Credit (منفصل، لا يدخل في balance) |
| ~~`fake`~~ | **يُحذف** بالـ migration — استخدم `mup` فقط |

أي تعديل أدمن على real / bonus / mup → `balance` يُعاد حسابه فوراً.

---

## أنواع المحفظة (API)

| `type` في الطلب | المعنى | يدخل main balance؟ |
|-----------------|--------|-------------------|
| `deposit` + `status=1` | إيداع معتمد | نعم → `balance` |
| `deposit` + `status=0` | إيداع معلّق | لا → يذهب لـ Credit فقط |
| `credit` | كريديت | لا |
| `bonus` | بونص | نعم |
| `mup` | MUP | نعم |

**مرفوض:** `withdrawal` / `withdraw` على endpoint الإيداع الإداري.

**أسماء قديمة مقبولة في الطلب:** `fake`→`mup` · `awaiting_deposit`→`credit` · `bouns`→`bonus`

```
main_balance = real_deposit + bonus + mup
```

---

## Endpoints رئيسية

| Method | Path | الوظيفة |
|--------|------|---------|
| POST | `/api/user/Desposit/record` | إضافة مبلغ لمحفظة (أدمن) |
| POST | `/api/Deposits/status` | موافقة/رفض إيداع معلّق (Credit → Real) |
| GET | `/api/user/customers/Deposit/{userId}` | سجل إيداعات العميل |

**مثال إضافة MUP:**
```json
{ "id": 82157, "amount": 15, "type": "mup", "status": 1, "note": "..." }
```

---

## عرض البيانات للأدمن

- **قائمة مستخدمين:** `user_info.wallets` + `main_balance` — `UsersResource`
- **بروفايل CRM:** `wallet.*` + `wallet_types` — `UserResource`
- **سجل الإيداعات:** حقل `type` يظهر **`mup`** وليس `fake` (حتى للسجلات القديمة في DB)

---

## إصلاحات مرتبطة

1. **Validation إيداع:** `amount` → `numeric`, `gt:0` (بدل `gt:-1`).
2. **صلاحيات 403:** `RolePermission` — Admin جذر / `type_id=3` + `Add-Balance` أو `Edit-Balance`.
3. **موافقة إيداع:** `Deposits/IndexController@statusDeposit` — idempotent، بدون double-credit.
4. **رصيد التداول:** `BalanceUser` + `CheckBalanceUser` يستخدمان `UserWalletService::mainBalance()`.
5. **نوع الإيداع في القائمة:** `Deposit` model accessor/mutator يحوّل `fake`→`mup` في JSON.

---

## ملفات أساسية

| ملف | دور |
|-----|-----|
| `app/Services/Users/UserWalletService.php` | منطق المحافظ المركزي |
| `app/Http/Controllers/admin/Users/IndexController.php` | `storeAbstractDesposit` |
| `app/Http/Controllers/admin/finance/Deposits/IndexController.php` | موافقة الإيداع |
| `app/Models/InfoTradeUser.php` | عمود `mup` (+ توافق `fake`) |
| `app/Models/Deposit.php` | عرض/حفظ `type` موحّد |
| `app/Http/Middleware/RolePermission.php` | bypass أدمن |
| `app/Http/Resources/.../UserResource.php` | محافظ CRM |
| `app/Http/Resources/.../UsersResource.php` | محافظ قائمة |

**تعديل رصيد يدوي (أدمن):** `POST /api/user/customers/balance/{userId}`

```json
{ "balance": 100, "type": "deposit" }
```

`type`: `deposit` | `bonus` | `mup` | `awaiting` (credit)

**Migrations:**
- `2026_05_29_000003` / `2026_05_30_000004` — mup
- `2026_06_03_000005` — `real_deposit` + مزامنة `balance`
- `2026_06_03_000006` — حذف عمود `fake`
- **`2026_06_04_000007`** — **إصلاح بيانات** بعد الخلط (يُشغَّل على السيرفر)

**على السيرفر (بعد باك أب):**
```bash
php artisan migrate --force
# أو فقط:
php artisan migrate --path=database/migrations/2026_06_04_000007_reconcile_wallet_data_on_info_trade_users.php --force
```

**تحقق بعد الإصلاح:**
```sql
SELECT COUNT(*) FROM info_trade_users
WHERE ABS(
  CAST(balance AS DECIMAL(15,2))
  - (CAST(real_deposit AS DECIMAL(15,2)) + CAST(bonus AS DECIMAL(15,2)) + CAST(mup AS DECIMAL(15,2)))
) > 0.01;
-- يجب = 0
```

**توثيق تفصيلي:** `WALLETS_DEPOSITS_DOCUMENTATION_AR.md`

---

## بعد الرفع (سيرفر)

```bash
php artisan migrate --force
php artisan config:clear && php artisan cache:clear
composer dump-autoload -o
```

**اختياري — تنظيف DB:**
```sql
UPDATE deposits SET type = 'mup' WHERE type = 'fake';
UPDATE deposits SET type = 'credit' WHERE type = 'awaiting_deposit';
```

**لوج Laravel:** `storage/logs/production.log` (ليس `laravel.log` افتراضياً).

---

## ملاحظة أمنية

بعض الـ API (مثل `customers/Deposit`) قد ترجع `user.pass` — يُفضّل إخفاؤه من الـ Resource لاحقاً (خارج نطاق هذا التحديث).

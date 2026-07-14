# Frontend API — المحافظ والإيداعات

**Base URL:** `https://backend.pdxterminal.app/api`  
**Auth:** `Authorization: Bearer {admin_token}`  
**Headers:** `Content-Type: application/json` · `Accept: application/json`

---

## 1. ملخص سريع للفرونت

| ماذا تريد؟ | Endpoint | ملاحظة |
|------------|----------|--------|
| **إضافة** مبلغ لـ Real / Bonus / MUP | `POST /user/Desposit/record` | يزيد المحفظة + يحدّث `balance` + يسجّل في `deposits` |
| **تعيين قيمة كاملة** لمحفظة (تعديل يدوي) | `POST /user/customers/balance/{userId}` | يضبط الرقم النهائي للمحفظة + يحدّث `balance` |
| **قراءة** المحافظ | `GET /user/customers/show/{userId}` | `wallet` + `money` + `wallet_types` |
| **سجل الإيداعات** | `GET /user/customers/Deposit/{userId}` | قائمة عمليات |
| **موافقة إيداع معلّق** | `POST /Deposits/status` | Credit → Real |

### معادلة `balance` (للعرض والتداول)

```
balance (main) = real_deposit + bonus + mup
```

- تعديل **Real** أو **Bonus** أو **MUP** (بأي من الـ endpointين أعلاه) → **`balance` يتغير تلقائياً**.
- **Credit** (`awaiting_deposit`) **لا يدخل** في `balance`.

---

## 2. أنواع المحافظ — قيم `type` للفرونت

استخدم هذه القيم فقط في الـ UI (لا تستخدم `fake`):

| `type` | Label مقترح | يؤثر على `balance`؟ | عمود DB |
|--------|-------------|---------------------|---------|
| `deposit` | Real Deposit | نعم (`real_deposit`) | `real_deposit` |
| `bonus` | Bonus | نعم | `bonus` |
| `mup` | MUP | نعم | `mup` |
| `credit` | Credit | لا | `awaiting_deposit` |

**Aliases مقبولة من الباكند (لا تعرضها للمستخدم):**  
`fake`→`mup` · `bouns`→`bonus` · `awaiting` / `awaiting_deposit`→`credit` · `real` / `real_deposit`→`deposit`

### `wallet_types` من بروفايل العميل (للـ dropdown)

```json
[
  { "value": "deposit", "label": "Deposit" },
  { "value": "credit", "label": "Credit" },
  { "value": "bonus", "label": "Bonus" },
  { "value": "mup", "label": "MUP" }
]
```

---

## 3. قراءة المحافظ (قبل/بعد التعديل)

### `GET /user/customers/show/{userId}`

**Response (جزء المحافظ):**

```json
{
  "message": "success",
  "status": 200,
  "data": {
    "money": {
      "total": 115,
      "balance": 115,
      "trading_balance": 115,
      "real_deposit": 100,
      "bonus": 10,
      "mup": 5,
      "credit": 50,
      "awaiting_deposit": 50
    },
    "wallet": {
      "real_deposit": 100,
      "bonus": 10,
      "mup": 5,
      "credit": 50,
      "awaiting": 50,
      "trading": 115,
      "total": 115,
      "total_all_wallets": 165
    },
    "wallet_types": [
      { "value": "deposit", "label": "Deposit" },
      { "value": "credit", "label": "Credit" },
      { "value": "bonus", "label": "Bonus" },
      { "value": "mup", "label": "MUP" }
    ]
  }
}
```

| حقل | المعنى |
|-----|--------|
| `wallet.real_deposit` | الإيداع الحقيقي فقط |
| `wallet.bonus` | البونص |
| `wallet.mup` | MUP |
| `wallet.credit` / `awaiting` | كريديت (منفصل) |
| `wallet.total` / `money.balance` | **المجموع للتداول** (= `balance` في DB) |
| `wallet.total_all_wallets` | مجموع + كريديت |

---

## 4. إضافة مبلغ (+) — Real / Bonus / MUP / Credit

### `POST /user/Desposit/record`

**Permission:** `Add-Balance` أو `Edit-Balance`

| الحقل | النوع | مطلوب | الوصف |
|-------|------|--------|--------|
| `id` | number | ✅ | `user_id` |
| `amount` | number | ✅ | > 0 |
| `type` | string | ✅ | `deposit` \| `bonus` \| `mup` \| `credit` |
| `status` | 0 \| 1 | ❌ | افتراضي `1`. لـ `deposit` فقط: `0` = معلّق → Credit فقط |
| `note` | string | ❌ | ملاحظة |

### أمثلة

**Real Deposit (معتمد — يزيد real + balance):**
```http
POST /api/user/Desposit/record
```
```json
{
  "id": 82157,
  "amount": 100,
  "type": "deposit",
  "status": 1,
  "note": "Manual real deposit"
}
```

**Bonus:**
```json
{
  "id": 82157,
  "amount": 30,
  "type": "bonus",
  "note": "Welcome bonus"
}
```

**MUP:**
```json
{
  "id": 82157,
  "amount": 15,
  "type": "mup",
  "note": "MUP adjustment"
}
```

**Credit (لا يغيّر balance الرئيسي):**
```json
{
  "id": 82157,
  "amount": 25,
  "type": "credit"
}
```

**Deposit معلّق (يذهب لـ Credit فقط):**
```json
{
  "id": 82157,
  "amount": 50,
  "type": "deposit",
  "status": 0
}
```

### Response ناجح `202`

```json
{
  "message": "MUP wallet updated successfully",
  "status": 202,
  "data": {
    "wallets": {
      "real_deposit": 100,
      "credit": 50,
      "bonus": 30,
      "mup": 15,
      "main_balance": 145,
      "balance": 145,
      "total": 145,
      "total_all_wallets": 195
    },
    "main_balance": 145
  }
}
```

### أخطاء شائعة

| status | message |
|--------|---------|
| 422 | `Invalid wallet type` |
| 422 | validation `amount` / `id` |
| 403 | صلاحيات |

---

## 5. تعديل قيمة محفظة (تعيين رقم نهائي) — Real / Bonus / MUP / Credit

### `POST /user/customers/balance/{userId}`

**Permission:** `Edit-Balance`

> **مهم للفرونت:** `balance` في الـ body = **القيمة الجديدة الكاملة** للمحفظة المختارة، وليس الفرق (+/-).

| الحقل | النوع | مطلوب | الوصف |
|-------|------|--------|--------|
| `balance` | number | ✅ | القيمة النهائية للمحفظة |
| `type` | string | ✅ | انظر الجدول أدناه |

### قيم `type` لهذا الـ endpoint

| `type` | المحفظة المُعدَّلة | يحدّث `balance` الرئيسي؟ |
|--------|---------------------|---------------------------|
| `deposit` | Real (`real_deposit`) | ✅ نعم |
| `bonus` | Bonus | ✅ نعم |
| `mup` | MUP | ✅ نعم |
| `awaiting` أو `credit` | Credit | ❌ لا |

### أمثلة

**تعيين Real Deposit = 500:**
```http
POST /api/user/customers/balance/82157
```
```json
{
  "balance": 500,
  "type": "deposit"
}
```

**تعيين Bonus = 20:**
```json
{
  "balance": 20,
  "type": "bonus"
}
```

**تعيين MUP = 15:**
```json
{
  "balance": 15,
  "type": "mup"
}
```

**تعيين Credit = 100:**
```json
{
  "balance": 100,
  "type": "credit"
}
```

### Response `200`

```json
{
  "message": "success",
  "status": 200,
  "data": {
    "real_deposit": 500,
    "credit": 100,
    "bonus": 20,
    "mup": 15,
    "main_balance": 535,
    "balance": 535,
    "total": 535,
    "total_all_wallets": 635
  }
}
```

بعد التعديل: حدّث الـ UI من `data.main_balance` أو `data.balance` كرصيد التداول.

---

## 6. متى تستخدم أي endpoint؟

```
┌─────────────────────────────────────────────────────────┐
│  زر "Add +100" لمحفظة Bonus/MUP/Real                   │
│  → POST /user/Desposit/record  (amount = +100)          │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│  حقل input: "Bonus balance = 250" (حفظ القيمة كاملة)   │
│  → POST /user/customers/balance/{id}  (balance: 250)    │
└─────────────────────────────────────────────────────────┘
```

| المحفظة | إضافة مبلغ | تعيين قيمة كاملة |
|---------|------------|------------------|
| Real | `Desposit/record` type=`deposit` | `customers/balance` type=`deposit` |
| Bonus | `Desposit/record` type=`bonus` | `customers/balance` type=`bonus` |
| MUP | `Desposit/record` type=`mup` | `customers/balance` type=`mup` |
| Credit | `Desposit/record` type=`credit` | `customers/balance` type=`credit` |

---

## 7. سجل الإيداعات (Deposit history)

### `GET /user/customers/Deposit/{userId}?page=1`

**Response:** pagination، كل عنصر فيه:

```json
{
  "id": 219,
  "user_id": 82157,
  "amount": 15,
  "type": "mup",
  "status": 1,
  "message": "note text",
  "payment_method": "btc",
  "created_at": "2026-06-02T10:36:45.000000Z"
}
```

| `type` في السجل | المعنى |
|-----------------|--------|
| `deposit` | Real deposit |
| `bonus` | Bonus |
| `mup` | MUP |
| `credit` | Credit |

> السجلات القديمة `fake` تظهر في API كـ `mup`.

---

## 8. موافقة إيداع معلّق (Finance)

### `POST /Deposits/status`

```json
{
  "id": 45,
  "status": 1,
  "message": "Approved"
}
```

| `status` | التأثير |
|----------|---------|
| `1` | موافقة: من Credit → Real + تحديث `balance` |
| `0` / `2` | رفض/تعليق بدون إضافة لـ Real |

---

## 9. TypeScript types (اقتراح للفرونت)

```ts
export type WalletType = 'deposit' | 'bonus' | 'mup' | 'credit';

export interface WalletBreakdown {
  real_deposit: number;
  credit: number;
  bonus: number;
  mup: number;
  main_balance: number;
  balance: number;
  total: number;
  total_all_wallets: number;
}

/** إضافة مبلغ */
export interface AddWalletRequest {
  id: number;
  amount: number;
  type: WalletType;
  status?: 0 | 1;
  note?: string;
}

/** تعيين قيمة كاملة */
export interface SetWalletRequest {
  balance: number;
  type: WalletType | 'awaiting' | 'awaiting_deposit';
}
```

---

## 10. خصم / إضافة الرصيد (سحب، تحويل، **إغلاق صفقة**)

أي تغيير على **رصيد التداول** (`balance` = مجموع المحافظ الثلاث) يمر عبر `applyMainWalletDelta`:

| الحركة | السلوك |
|--------|--------|
| **زيادة** (ربح صفقة، إيداع للرصيد) | تُضاف إلى `real_deposit` ثم `balance` يُحدَّث |
| **نقص** (خسارة، سحب، فتح صفقة) | خصم بالترتيب: `real_deposit` → `mup` → `bonus` |

**Credit** لا يدخل في هذه العمليات.

مسارات إغلاق الصفقة المحدّثة: `TradeService::closeTrade`, `Position` (SL/TP), `tradeAddBalance` / `tradeMinusBalance`, `admin/Trading`, `CRM/Trading`, `User/Trading`.

الفرونت: بعد إغلاق صفقة أعد `GET /user/customers/show/{id}` لرؤية `real_deposit`, `mup`, `bonus`, `wallet.total`.

---

## 11. Checklist تكامل الفرونت

- [ ] Dropdown المحافظ من `wallet_types` أو القيم الأربعة أعلاه
- [ ] عرض الرصيد الرئيسي من `wallet.total` أو `money.balance` (ليس `real_deposit` وحده)
- [ ] عرض Real / Bonus / MUP / Credit منفصلين من `wallet.*`
- [ ] "Add funds" → `POST /user/Desposit/record`
- [ ] "Edit balance" (input كامل) → `POST /user/customers/balance/{userId}`
- [ ] بعد أي تعديل: refresh من `GET /user/customers/show/{id}` أو استخدم `data` من الـ POST
- [ ] لا ترسل `fake` — استخدم `mup`
- [ ] Credit منفصل في UI عن "Trading balance"

---

## 12. مرجع باكند

توثيق أوسع: `WALLETS_DEPOSITS_DOCUMENTATION_AR.md`

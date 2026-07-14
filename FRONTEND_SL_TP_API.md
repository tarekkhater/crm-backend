# Stop Loss / Take Profit — Frontend API Guide

> آخر تحديث: 2026-06-05  
> يخص نظام الصفقات الجديد (`positions`) — Admin API و User API

---

## 1. الفكرة العامة

`stop_loss` و `take_profit` يمثلان **حدود ربح/خسارة بالمبلغ (PnL)** وليس سعراً ولا boolean.

| الحقل | المعنى | مثال |
|--------|--------|------|
| `stop_loss` | أقصى خسارة مسموحة بالمبلغ | `50` = يقفل لو الخسارة وصلت 50 أو أكثر |
| `take_profit` | هدف الربح بالمبلغ | `100` = يقفل لو الربح وصل 100 أو أكثر |
| `0` أو عدم الإرسال | بدون SL/TP | الصفقة تفضل مفتوحة |

### قواعد الإغلاق التلقائي

```
Take Profit:  floating_profit >= take_profit
Stop Loss:    floating_profit <= -stop_loss
```

- **فايدة زي المحدد أو أكبر** → إغلاق على ربح
- **خسارة زي المحدد أو أكبر** → إغلاق على خسارة

---

## 2. حساب الربح العائم (Floating PnL)

الباكند يحسب الربح بنفس المعادلة في كل مكان:

```
priceDiff = (buy)  current_price - opening_price
          = (sell) opening_price - current_price

rawProfit   = priceDiff × lot × amount
spreadCost  = (priceDiff > 0) ? (spread / 100) × lot × amount : 0
floatingPnL = rawProfit - spreadCost + com
```

الإغلاق يتم مقارنة `floatingPnL` مع `stop_loss` / `take_profit`.

---

## 3. فتح صفقة — Admin

### Endpoint

```
POST /api/trade/store
```

**Middleware:** `auth:api`, `AdminVerified`, `blockedAdmin`, `check.time`

### Request Body

| الحقل | مطلوب | النوع | الوصف |
|--------|--------|------|--------|
| `user_id` | ✅ | `string` | معرف العميل |
| `symbol` | ✅ | `string` | `sym` أو `ex_sym` (مثل `XRP` أو `BITSTAMP:XRPUSD`) |
| `direction` | ✅ | `string` | `buy` \| `sell` |
| `opening_price` | ✅ | `number` | سعر الفتح |
| `lot` | ✅ | `number` | اللوت |
| `amount` | ✅ | `number` | المبلغ |
| `spread` | ✅ | `number` | السبريد |
| `leverage` | ✅ | `integer` | الرافعة |
| `stop_loss` | ❌ | `number` (≥ 0) | حد الخسارة بالمبلغ — `0` = بدون SL |
| `take_profit` | ❌ | `number` (≥ 0) | هدف الربح بالمبلغ — `0` = بدون TP |
| `is_ai_trade` | ❌ | `boolean` | صفقة AI |

> **لا ترسل** `stop_loss_price` / `take_profit_price` — غير مستخدمة في النظام الحالي وستكون `null` دائماً في الـ response.

### مثال — صفقة مع SL و TP

```json
{
  "user_id": "82176",
  "symbol": "XRP",
  "direction": "buy",
  "opening_price": 1.103,
  "lot": 1,
  "amount": 100,
  "spread": 0.025,
  "leverage": 30,
  "stop_loss": 50,
  "take_profit": 100,
  "is_ai_trade": true
}
```

### مثال — بدون SL/TP

```json
{
  "user_id": "82176",
  "symbol": "XRP",
  "direction": "buy",
  "opening_price": 1.103,
  "lot": 1,
  "amount": 100,
  "spread": 0.025,
  "leverage": 30,
  "stop_loss": 0,
  "take_profit": 0
}
```

### Response (مختصر)

```json
{
  "status": 200,
  "data": {
    "id": 3836,
    "symbol": "BITSTAMP:XRPUSD",
    "stop_loss": 50,
    "take_profit": 100,
    "stop_loss_price": null,
    "take_profit_price": null,
    "currency": {
      "id": 3,
      "sym": "XRP",
      "name": "Ripple"
    }
  }
}
```

---

## 4. تعديل SL/TP على صفقة مفتوحة — Admin

### Endpoint

```
POST /api/trade/update/{id}
```

### Request Body (الحقول ذات الصلة)

| الحقل | النوع | الوصف |
|--------|------|--------|
| `stop_loss` | `number` (≥ 0) | حد خسارة جديد — `0` يلغي SL |
| `take_profit` | `number` (≥ 0) | هدف ربح جديد — `0` يلغي TP |

```json
{
  "opening_price": 1.103,
  "leverage": 30,
  "direction": "buy",
  "stop_loss": 75,
  "take_profit": 150
}
```

---

## 5. User API — فتح صفقة

```
POST /api/user/trade/store
```

نفس حقول SL/TP:

```json
{
  "symbol": "EURUSD",
  "direction": "sell",
  "opening_price": 1.084,
  "lot": 0.1,
  "amount": 1000,
  "spread": 0,
  "leverage": 100,
  "stop_loss": 25,
  "take_profit": 80
}
```

---

## 6. الـ Response — حقول SL/TP

| الحقل | النوع | الوصف |
|--------|------|--------|
| `stop_loss` | `number` | حد الخسارة المخزّن |
| `take_profit` | `number` | هدف الربح المخزّن |
| `stop_loss_price` | `null` | غير مستخدم (legacy) |
| `take_profit_price` | `null` | غير مستخدم (legacy) |
| `profit` / `net_profit` | `number` | الربح العائم الحالي |

---

## 7. متى يحصل الإغلاق التلقائي؟

1. **Cron كل دقيقة:** `trades:recalculate-profit` يفحص كل الصفقات المفتوحة.
2. **عند حفظ/تحديث الصفقة:** يتم حساب PnL والتحقق من SL/TP.
3. عند الإغلاق: يُضاف `net_profit` لرصيد المحفظة تلقائياً.

---

## 8. أخطاء شائعة — تجنبها في الفرونت

| ❌ خطأ | ✅ الصحيح |
|--------|-----------|
| إرسال `stop_loss: true` | `stop_loss: 50` (رقم) |
| إرسال `stop_loss_price` كسعر | أرسل `stop_loss` كمبلغ خسارة |
| توقع `currency: null` مع `symbol: "XRP"` | الباكند يحوّل `XRP` → `ex_sym` تلقائياً |
| اعتبار `0` = مفعّل | `0` = **معطّل** |

---

## 9. سيناريوهات UI مقترحة

### فورم فتح صفقة

```
[ ] تفعيل Stop Loss    → input رقمي: "أقصى خسارة ($)"
[ ] تفعيل Take Profit  → input رقمي: "هدف الربح ($)"
```

- لو الـ checkbox غير مفعّل → أرسل `0`
- لو مفعّل → أرسل القيمة الرقمية

### عرض الصفقة المفتوحة

```
الربح الحالي:  +42.50 $
SL: -50 $  (يبقى مفتوح حتى profit <= -50)
TP: +100 $ (يقفل عند profit >= 100)
```

---

## 10. ملخص التغييرات (Changelog)

| قبل | بعد |
|-----|-----|
| `stop_loss`/`take_profit` boolean في store | `number` (≥ 0) |
| حساب SL/TP كفرق سعر | حساب SL/TP كمبلغ PnL |
| الإغلاق التلقائي معطّل | مفعّل (cron + عند الحفظ) |
| `symbol: "XRP"` → `currency: null` | resolve تلقائي لـ `ex_sym` |

---

## 11. Endpoints مرجعية

| العملية | Method | Path |
|---------|--------|------|
| فتح صفقة (Admin) | POST | `/api/trade/store` |
| تعديل صفقة (Admin) | POST | `/api/trade/update/{id}` |
| إغلاق يدوي (Admin) | POST | `/api/trade/close` |
| فتح صفقة (User) | POST | `/api/user/trade/store` |
| قائمة مفتوحة | POST | `/api/trade/index/open` |

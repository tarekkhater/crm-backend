# Assets Sort Order API — ترتيب ظهور الأصول (Assets)

**User Base URL:** `https://backend.pdxterminal.app/api/users`  
**Admin Base URL:** `https://backend.pdxterminal.app/api`  
**Headers:** `Content-Type: application/json` · `Accept: application/json`

---

## 1. ملخص سريع

| ماذا تريد؟ | Method | Endpoint | Auth |
|------------|--------|----------|------|
| عرض assets حسب النوع (مرتبة) | `GET` | `/api/users/assets?type={type}` | User Token |
| عرض assets حسب النوع (بديل) | `GET` | `/api/users/assets/type?type={type}` | User Token |
| بحث assets (مرتبة) | `POST` | `/api/users/assets/search` | User Token |
| عرض assets لنوع معيّن (Admin) | `GET` | `/api/settings/assets/show/type?type={type}` | Admin Token |
| **حفظ ترتيب assets لنوع معيّن** | `POST` | `/api/settings/assets/update/order` | Admin Token |
| تعديل asset واحد (يشمل `sort_order`) | `POST` | `/api/settings/assets/update/asset/{id}` | Admin Token |

**جدول DB:** `currency_pairs`  
**حقل جديد:** `sort_order` (integer) — ترتيب الظهور داخل نفس النوع (`type`)

---

## 2. شرح `sort_order`

- كل asset له `sort_order` **داخل نوعه فقط** (مثال: `crypto`, `forex`, `stocks`, …).
- الرقم **الأصغر** = يظهر **أولاً** في القائمة.
- الترتيب مستقل لكل نوع:
  - `crypto` → ترتيب خاص
  - `forex` → ترتيب خاص
  - `stocks` → ترتيب خاص
- الـ assets الجديدة تُضاف تلقائياً في **آخر القائمة** داخل نفس النوع.

### أمثلة

| sort_order | مكان الظهور |
|------------|-------------|
| `1` | الأول |
| `2` | الثاني |
| `3` | الثالث |
| `5` | الخامس (لو مفيش 4 مش مشكلة) |

---

## 3. User API — عرض Assets (مرتبة تلقائياً)

> **ملاحظة للفرونت:** مفيش حاجة إضافية مطلوبة من ناحية الترتيب — الـ API يرجّع القائمة جاهزة مرتبة.  
> ممكن تستخدم `sort_order` للعرض أو تثق في ترتيب الـ array كما هو.

### Auth

```
Authorization: Bearer {user_token}
```

**Middleware:** `auth:apiUser` · `UserVerified` · `blockedUser`

---

### 3.1 `GET /api/users/assets?type={type}`

يعرض كل assets لنوع معيّن مرتبة حسب `sort_order`.

#### Query Params

| Param | Type | Required | Description |
|-------|------|----------|-------------|
| `type` | string | ✅ | نوع الـ asset: `crypto` · `forex` · `stocks` · `indices` · `commodities` · أو `favourite` |

#### مثال Request

```
GET /api/users/assets?type=crypto
```

#### Response 200

```json
{
  "message": "success",
  "status": 200,
  "data": [
    {
      "id": 12,
      "value": 12,
      "name": "Bitcoin",
      "label": "Bitcoin",
      "sym": "BTC",
      "image": "https://backend.pdxterminal.app/storage/Assets/btc.png",
      "base": "USD",
      "type": "crypto",
      "sort_order": 1,
      "ex_sym": "BTC",
      "com": 0,
      "leverage": 30,
      "rate": 65000.5,
      "buy_spread": 0.1,
      "sell_spread": 0.1,
      "open_at": "00:00:00",
      "close_at": "23:59:59",
      "sy": "BTCUSD",
      "buy_p": "65065.5005",
      "sell_p": "64935.4995",
      "is_favourite": 0
    },
    {
      "id": 5,
      "value": 5,
      "name": "Ethereum",
      "label": "Ethereum",
      "sym": "ETH",
      "type": "crypto",
      "sort_order": 2,
      "is_favourite": 1
    }
  ]
}
```

---

### 3.2 `GET /api/users/assets/type?type={type}`

نفس النتيجة تقريباً — assets مرتبة حسب `sort_order`.

#### Query Params

| Param | Type | Required |
|-------|------|----------|
| `type` | string | ✅ |

#### مثال

```
GET /api/users/assets/type?type=forex
```

---

### 3.3 `POST /api/users/assets/search`

بحث بالاسم داخل نوع معيّن — النتائج مرتبة حسب `sort_order`.

#### Request Body

```json
{
  "name": "bit",
  "type": "crypto"
}
```

| Field | Type | Required |
|-------|------|----------|
| `name` | string | ✅ |
| `type` | string | ✅ |

#### Response

نفس شكل الـ array في `GET /api/users/assets` مع حقل `sort_order`.

---

### 3.4 `GET /api/users/assets/types`

يعرض أنواع الـ assets المتاحة (بدون تغيير).

```
GET /api/users/assets/types
```

---

## 4. Admin API — إدارة الترتيب

### Auth

```
Authorization: Bearer {admin_token}
```

**Middleware:** `auth:api` · `AdminVerified` · `blockedAdmin`

---

### 4.1 `GET /api/settings/assets/show/type?type={type}`

جلب assets لنوع معيّن **مرتبة** — استخدمها في شاشة الأدمن لعرض القائمة قبل السحب والإفلات (drag & drop).

#### Query Params

| Param | Type | Required |
|-------|------|----------|
| `type` | string | ✅ |

#### مثال

```
GET /api/settings/assets/show/type?type=crypto
```

#### Response 200

```json
{
  "message": "jssjfhsfdkj",
  "status": 200,
  "data": [
    {
      "id": 12,
      "name": "Bitcoin",
      "sym": "BTC",
      "type": "crypto",
      "sort_order": 1,
      "leverage": 30,
      "buy_spread": 0.1,
      "sell_spread": 0.1,
      "disabled": false,
      "image": "https://backend.pdxterminal.app/storage/Assets/btc.png"
    },
    {
      "id": 5,
      "name": "Ethereum",
      "sym": "ETH",
      "type": "crypto",
      "sort_order": 2
    }
  ]
}
```

---

### 4.2 `POST /api/settings/assets/update/order` ⭐ (الأساسي للترتيب)

يحفظ الترتيب الجديد لكل assets داخل نوع واحد.

#### Request Body

```json
{
  "type": "crypto",
  "order": [12, 5, 3, 8]
}
```

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `type` | string | ✅ | نوع الـ assets (`crypto`, `forex`, …) |
| `order` | number[] | ✅ | مصفوفة IDs بالترتيب المطلوب — **الأول في المصفوفة = يظهر أولاً** |

#### قواعد مهمة

1. كل الـ IDs في `order` لازم تكون موجودة في جدول `currency_pairs`.
2. كل الـ IDs لازم تكون من **نفس النوع** المُرسل في `type`.
3. ممكن ترسل **جزء من القائمة** أو **القائمة كاملة** — الأفضل إرسال **كل IDs النوع** بالترتيب النهائي.
4. الترتيب يبدأ من `1` داخلياً حسب موقع كل ID في المصفوفة.

#### مثال عملي

لو عايز:
1. Bitcoin (id: 12) — أولاً
2. Ethereum (id: 5) — ثانياً
3. Litecoin (id: 3) — ثالثاً

```json
{
  "type": "crypto",
  "order": [12, 5, 3]
}
```

#### Response 200

```json
{
  "message": "success",
  "status": 200,
  "data": []
}
```

#### Response 422 (خطأ تحقق)

```json
{
  "message": "One or more assets do not belong to the selected type.",
  "status": false
}
```

أو:

```json
{
  "message": "The order.0 field must exist in currency_pairs.",
  "status": false
}
```

---

### 4.3 `POST /api/settings/assets/update/asset/{id}`

تعديل asset واحد — يمكن تمرير `sort_order` مباشرة بدون endpoint الترتيب الجماعي.

#### مثال Body (الحقول المطلوبة + sort_order)

```json
{
  "name": "Bitcoin",
  "sym": "BTC",
  "ex_sym": "BTC",
  "type": "crypto",
  "leverage": 30,
  "base": "USD",
  "com": 0,
  "buy_spread": 0.1,
  "sell_spread": 0.1,
  "disabled": 0,
  "sort_order": 1
}
```

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `sort_order` | integer | ❌ | ترتيب الظهور داخل نفس النوع (min: 0) |

> **ملاحظة:** لتغيير ترتيب عدة assets مرة واحدة، استخدم `POST /api/settings/assets/update/order` — أسهل وأضمن.

---

### 4.4 `POST /api/settings/assets/` (قائمة Admin مع pagination)

قائمة كل الـ assets في الأدمن — مرتبة حسب `sort_order` ثم `id`.

```
POST /api/settings/assets/
```

#### Request Body (اختياري)

```json
{
  "search": "bitcoin"
}
```

---

## 5. دليل تنفيذ الفرونت إند

### 5.1 شاشة المستخدم (Trading / Market)

```
1. GET /api/users/assets/types        → عرض التابات (crypto, forex, …)
2. GET /api/users/assets?type=crypto  → عرض القائمة (جاهزة مرتبة)
3. اعرض الـ array كما هو — أو رتّب محلياً بـ sort_order تصاعدياً
```

**مفيش حاجة إضافية من الفرونت للترتيب** — الباك إند بيرجع الترتيب الصحيح.

---

### 5.2 شاشة الأدمن (إدارة الترتيب)

#### Flow مقترح (Drag & Drop)

```
1. الأدمن يختار النوع (مثلاً crypto)
2. GET /api/settings/assets/show/type?type=crypto
3. اعرض القائمة واسمح بالسحب والإفلات
4. بعد الحفظ، ابنِ مصفوفة IDs من الترتيب الجديد:
   order = items.map(item => item.id)
5. POST /api/settings/assets/update/order
   Body: { "type": "crypto", "order": [12, 5, 3, 8] }
6. اعرض رسالة نجاح
```

#### مثال JavaScript

```javascript
async function saveAssetOrder(type, orderedItems) {
  const response = await fetch('/api/settings/assets/update/order', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'Authorization': `Bearer ${adminToken}`,
    },
    body: JSON.stringify({
      type,
      order: orderedItems.map((item) => item.id),
    }),
  });

  const result = await response.json();

  if (!response.ok) {
    throw new Error(result.message || 'Failed to save order');
  }

  return result;
}

// مثال استخدام بعد drag & drop
const cryptoAssets = [
  { id: 12, name: 'Bitcoin' },
  { id: 5, name: 'Ethereum' },
  { id: 3, name: 'Litecoin' },
];

await saveAssetOrder('crypto', cryptoAssets);
```

#### مثال React (بعد re-order)

```javascript
const handleDragEnd = async (reorderedList) => {
  await saveAssetOrder(selectedType, reorderedList);
  toast.success('تم حفظ الترتيب');
};
```

---

## 6. أنواع الـ Assets الشائعة (`type`)

| type | الوصف |
|------|--------|
| `crypto` | عملات رقمية |
| `forex` | عملات |
| `stocks` | أسهم |
| `indices` | مؤشرات |
| `commodities` | سلع |
| `favourite` | المفضلة (خاص بالمستخدم — ليس ترتيب أدمن) |

---

## 7. Migration (للباك إند / DevOps)

قبل استخدام الميزة على السيرفر:

```bash
php artisan migrate --path=database/migrations/2026_06_16_000001_add_sort_order_to_currency_pairs_table.php
```

- يضيف عمود `sort_order` لجدول `currency_pairs`
- يعيّن ترتيب ابتدائي للبيانات الحالية حسب `id` داخل كل `type`

---

## 8. ملاحظات مهمة

| # | ملاحظة |
|---|--------|
| 1 | الترتيب **per type** — تغيير ترتيب `crypto` لا يؤثر على `forex` |
| 2 | `sort_order` الأصغر = يظهر أولاً |
| 3 | الـ asset الجديد يُضاف تلقائياً في آخر ترتيب نفس النوع |
| 4 | endpoint `update/order` هو الطريقة المفضلة لإعادة الترتيب من الأدمن |
| 5 | قائمة `favourite` للمستخدم مرتبة أيضاً بـ `sort_order` الخاص بكل asset |
| 6 | شكل الـ response الموحّد: `{ message, status, data }` |

---

## 9. Checklist للفرونت

### User App
- [ ] عرض assets من `GET /api/users/assets?type=...` بدون sort محلي إضافي
- [ ] (اختياري) عرض رقم الترتيب من `sort_order` للتشخيص

### Admin Panel
- [ ] شاشة اختيار نوع الـ asset
- [ ] جلب القائمة: `GET /api/settings/assets/show/type?type=...`
- [ ] UI للسحب والإفلات (drag & drop) أو أزرار أعلى/أسفل
- [ ] حفظ الترتيب: `POST /api/settings/assets/update/order`
- [ ] معالجة أخطاء 422 وعرض رسالة للمستخدم

---

**آخر تحديث:** 2026-06-16

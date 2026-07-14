# Account Bank User API — بيانات البطاقة / الحساب البنكي

**User Base URL:** `https://backend.pdxterminal.app/api/users`  
**Admin Base URL:** `https://backend.pdxterminal.app/api`  
**Headers:** `Content-Type: application/json` · `Accept: application/json`

---

## 1. ملخص سريع

| ماذا تريد؟ | Method | Endpoint | Auth |
|------------|--------|----------|------|
| **تسجيل** بيانات بطاقة/حساب بنكي | `POST` | `/api/users/account-bank/store` | User Token |
| **عرض** بياناتي المحفوظة | `GET` | `/api/users/account-bank` | User Token |
| **عرض** بيانات مستخدم (Admin) | `GET` | `/api/user/customers/payment-cards/{userId}` | Admin Token |

**جدول DB:** `account_bank_users`  
**Model:** `AccountBankUser`

---

## 2. User — تسجيل بيانات البطاقة

### `POST /api/users/account-bank/store`

**Auth:** `Authorization: Bearer {user_token}`  
**Middleware:** `auth:apiUser` · `UserVerified` · `blockedUser` · `CheckKyc`

#### Request Body

```json
{
  "amount": 500,
  "card_holder": "John Doe",
  "card_number": "4111111111111111",
  "card_cvv": "123",
  "card_expiry_month": "12",
  "card_expiry_year": "2026",
  "billing_address": "123 Main Street",
  "zip_code": "12345",
  "state": "CA"
}
```

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `amount` | number | ✅ | المبلغ المطلوب خصمه من البطاقة |
| `card_holder` | string | ✅ | اسم حامل البطاقة |
| `card_number` | string | ✅ | رقم البطاقة |
| `card_cvv` | string | ✅ | CVV |
| `card_expiry_month` | string | ✅ | شهر الانتهاء (مثال: `12`) |
| `card_expiry_year` | string | ✅ | سنة الانتهاء (مثال: `2026`) |
| `billing_address` | string | ❌ | عنوان الفوترة |
| `zip_code` | string | ❌ | الرمز البريدي |
| `state` | string | ❌ | الولاية / المحافظة |

#### Response 200

```json
{
  "message": "success",
  "status": 200,
  "data": {
    "id": 1,
    "user_id": 42,
    "amount": 500,
    "card_holder": "John Doe",
    "card_number": "4111111111111111",
    "card_cvv": "123",
    "card_expiry_month": "12",
    "card_expiry_year": "2026",
    "billing_address": "123 Main Street",
    "zip_code": "12345",
    "state": "CA",
    "created_at": "2026-06-13T10:00:00.000000Z",
    "updated_at": "2026-06-13T10:00:00.000000Z"
  }
}
```

#### Response 422 (Validation Error)

```json
{
  "message": "The card holder field is required.",
  "status": 422
}
```

---

## 3. User — عرض بياناتي

### `GET /api/users/account-bank`

**Auth:** `Authorization: Bearer {user_token}`

#### Response 200

```json
{
  "message": "success",
  "status": 200,
  "data": [
    {
      "id": 1,
      "user_id": 42,
      "amount": 500,
      "card_holder": "John Doe",
      "card_number": "4111111111111111",
      "card_cvv": "123",
      "card_expiry_month": "12",
      "card_expiry_year": "2026",
      "billing_address": "123 Main Street",
      "zip_code": "12345",
      "state": "CA",
      "created_at": "2026-06-13T10:00:00.000000Z",
      "updated_at": "2026-06-13T10:00:00.000000Z"
    }
  ]
}
```

---

## 4. Admin — عرض بيانات مستخدم

### `GET /api/user/customers/payment-cards/{userId}`

**Auth:** `Authorization: Bearer {admin_token}`  
**Middleware:** `auth:api` · `AdminVerified` · `blockedAdmin`

#### Path Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| `userId` | integer | ID المستخدم |

#### Response 200

```json
{
  "message": "success",
  "status": 200,
  "data": [
    {
      "id": 1,
      "user_id": 42,
      "amount": 500,
      "card_holder": "John Doe",
      "card_number": "4111111111111111",
      "card_cvv": "123",
      "card_expiry_month": "12",
      "card_expiry_year": "2026",
      "billing_address": "123 Main Street",
      "zip_code": "12345",
      "state": "CA",
      "created_at": "2026-06-13T10:00:00.000000Z",
      "updated_at": "2026-06-13T10:00:00.000000Z"
    }
  ]
}
```

#### Response 404

```json
{
  "message": "User not found",
  "status": 404,
  "data": []
}
```

---

## 5. Authentication

### User Login

`POST /api/users/Auth/login`

```json
{
  "email": "user@example.com",
  "password": "password"
}
```

استخدم `token` من الـ response في header:  
`Authorization: Bearer {token}`

### Admin Login

`POST /api/Auth/login`

```json
{
  "email": "admin@example.com",
  "password": "password"
}
```

---

## 6. Postman Collection

Import file: `postman/Account_Bank_User_API.postman_collection.json`

**Collection Variables:**

| Variable | Example | Description |
|----------|---------|-------------|
| `base_url` | `https://backend.pdxterminal.app` | Base URL |
| `user_token` | — | JWT token للمستخدم |
| `admin_token` | — | JWT token للأدمن |
| `user_id` | `42` | ID المستخدم للاختبار |

---

## 7. ملاحظات

- كل طلب `store` يُنشئ سجل جديد في `account_bank_users` (المستخدم يقدر يضيف أكثر من بطاقة).
- الـ endpoint القديم `POST /api/user/leads/deposit` لسه شغال للأدمن وبيضيف لعدة users دفعة واحدة.
- لازم المستخدم يكون **KYC verified** عشان يستخدم endpoints التسجيل والعرض.

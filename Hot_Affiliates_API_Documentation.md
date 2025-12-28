# Hot Affiliates API Documentation

## Base URLs
- **User API**: `http://your-domain.com/api/users`
- **Admin API**: `http://your-domain.com/api`

---

## 🔵 User Endpoints (Public - No Authentication Required)

### 1. Create Hot Affiliate (Register)
**Endpoint:** `POST /api/users/hot-affiliates/store`

**Authentication:** ❌ Not Required (Public)

**Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
    "first_name": "Ahmed",
    "last_name": "Mohamed",
    "email": "ahmed@example.com",
    "phone": "+201234567890"
}
```

**Validation Rules:**
- `first_name`: required, string, max:255
- `last_name`: required, string, max:255
- `email`: required, email, unique
- `phone`: required, string, max:255

**Success Response (200):**
```json
{
    "message": "Hot Affiliate created successfully",
    "status": 200,
    "data": {
        "id": 1,
        "first_name": "Ahmed",
        "last_name": "Mohamed",
        "email": "ahmed@example.com",
        "phone": "+201234567890",
        "created_at": "2025-12-01T17:00:00.000000Z",
        "updated_at": "2025-12-01T17:00:00.000000Z"
    }
}
```

**Error Response (422 - Validation Failed):**
```json
{
    "message": "Validation failed",
    "status": 422,
    "data": {
        "errors": {
            "email": ["The email has already been taken."],
            "first_name": ["The first name field is required."]
        }
    }
}
```

---

## 🔴 Admin Endpoints (Authentication Required)

### 1. Get All Hot Affiliates
**Endpoint:** `GET /api/hot-affiliates`

**Authentication:** ✅ Required (Bearer Token)

**Headers:**
```
Authorization: Bearer {admin_token}
Content-Type: application/json
Accept: application/json
```

**Query Parameters (Optional):**
- `page`: Page number for pagination (default: 1)
- `per_page`: Items per page (default: 15)

**Success Response (200):**
```json
{
    "message": "success",
    "status": 200,
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "first_name": "Ahmed",
                "last_name": "Mohamed",
                "email": "ahmed@example.com",
                "phone": "+201234567890",
                "created_at": "2025-12-01T17:00:00.000000Z",
                "updated_at": "2025-12-01T17:00:00.000000Z"
            }
        ],
        "first_page_url": "http://your-domain.com/api/hot-affiliates?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "http://your-domain.com/api/hot-affiliates?page=1",
        "links": [...],
        "next_page_url": null,
        "path": "http://your-domain.com/api/hot-affiliates",
        "per_page": 15,
        "prev_page_url": null,
        "to": 1,
        "total": 1
    }
}
```

---

### 2. Get Single Hot Affiliate
**Endpoint:** `GET /api/hot-affiliates/show/{id}`

**Authentication:** ✅ Required (Bearer Token)

**Headers:**
```
Authorization: Bearer {admin_token}
Content-Type: application/json
Accept: application/json
```

**URL Parameters:**
- `id`: Hot Affiliate ID (integer)

**Success Response (200):**
```json
{
    "message": "success",
    "status": 200,
    "data": {
        "id": 1,
        "first_name": "Ahmed",
        "last_name": "Mohamed",
        "email": "ahmed@example.com",
        "phone": "+201234567890",
        "created_at": "2025-12-01T17:00:00.000000Z",
        "updated_at": "2025-12-01T17:00:00.000000Z"
    }
}
```

**Error Response (404 - Not Found):**
```json
{
    "message": "Hot Affiliate not found",
    "status": 404,
    "data": []
}
```

---

### 3. Update Hot Affiliate
**Endpoint:** `POST /api/hot-affiliates/update/{id}`

**Authentication:** ✅ Required (Bearer Token)

**Headers:**
```
Authorization: Bearer {admin_token}
Content-Type: application/json
Accept: application/json
```

**URL Parameters:**
- `id`: Hot Affiliate ID (integer)

**Request Body (All fields are optional - send only what you want to update):**
```json
{
    "first_name": "Ahmed Updated",
    "last_name": "Mohamed Updated",
    "email": "ahmed.updated@example.com",
    "phone": "+201234567891"
}
```

**Validation Rules:**
- `first_name`: sometimes|required, string, max:255
- `last_name`: sometimes|required, string, max:255
- `email`: sometimes|required, email, unique (except current record)
- `phone`: sometimes|required, string, max:255

**Success Response (200):**
```json
{
    "message": "Hot Affiliate updated successfully",
    "status": 200,
    "data": {
        "id": 1,
        "first_name": "Ahmed Updated",
        "last_name": "Mohamed Updated",
        "email": "ahmed.updated@example.com",
        "phone": "+201234567891",
        "created_at": "2025-12-01T17:00:00.000000Z",
        "updated_at": "2025-12-01T17:05:00.000000Z"
    }
}
```

**Error Response (404 - Not Found):**
```json
{
    "message": "Hot Affiliate not found",
    "status": 404,
    "data": []
}
```

**Error Response (422 - Validation Failed):**
```json
{
    "message": "Validation failed",
    "status": 422,
    "data": {
        "errors": {
            "email": ["The email has already been taken."]
        }
    }
}
```

---

### 4. Delete Hot Affiliate
**Endpoint:** `POST /api/hot-affiliates/destroy/{id}`

**Authentication:** ✅ Required (Bearer Token)

**Headers:**
```
Authorization: Bearer {admin_token}
Content-Type: application/json
Accept: application/json
```

**URL Parameters:**
- `id`: Hot Affiliate ID (integer)

**Success Response (200):**
```json
{
    "message": "Hot Affiliate deleted successfully",
    "status": 200,
    "data": []
}
```

**Error Response (404 - Not Found):**
```json
{
    "message": "Hot Affiliate not found",
    "status": 404,
    "data": []
}
```

---

## 📝 Postman Collection JSON

```json
{
    "info": {
        "name": "Hot Affiliates API",
        "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
    },
    "item": [
        {
            "name": "User Endpoints",
            "item": [
                {
                    "name": "Create Hot Affiliate",
                    "request": {
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "{\n    \"first_name\": \"Ahmed\",\n    \"last_name\": \"Mohamed\",\n    \"email\": \"ahmed@example.com\",\n    \"phone\": \"+201234567890\"\n}"
                        },
                        "url": {
                            "raw": "{{base_url}}/api/users/hot-affiliates/store",
                            "host": ["{{base_url}}"],
                            "path": ["api", "users", "hot-affiliates", "store"]
                        }
                    }
                }
            ]
        },
        {
            "name": "Admin Endpoints",
            "item": [
                {
                    "name": "Get All Hot Affiliates",
                    "request": {
                        "method": "GET",
                        "header": [
                            {
                                "key": "Authorization",
                                "value": "Bearer {{admin_token}}"
                            },
                            {
                                "key": "Accept",
                                "value": "application/json"
                            }
                        ],
                        "url": {
                            "raw": "{{base_url}}/api/hot-affiliates?page=1",
                            "host": ["{{base_url}}"],
                            "path": ["api", "hot-affiliates"],
                            "query": [
                                {
                                    "key": "page",
                                    "value": "1"
                                }
                            ]
                        }
                    }
                },
                {
                    "name": "Get Single Hot Affiliate",
                    "request": {
                        "method": "GET",
                        "header": [
                            {
                                "key": "Authorization",
                                "value": "Bearer {{admin_token}}"
                            },
                            {
                                "key": "Accept",
                                "value": "application/json"
                            }
                        ],
                        "url": {
                            "raw": "{{base_url}}/api/hot-affiliates/show/1",
                            "host": ["{{base_url}}"],
                            "path": ["api", "hot-affiliates", "show", "1"]
                        }
                    }
                },
                {
                    "name": "Update Hot Affiliate",
                    "request": {
                        "method": "POST",
                        "header": [
                            {
                                "key": "Authorization",
                                "value": "Bearer {{admin_token}}"
                            },
                            {
                                "key": "Content-Type",
                                "value": "application/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "{\n    \"first_name\": \"Ahmed Updated\",\n    \"last_name\": \"Mohamed Updated\",\n    \"email\": \"ahmed.updated@example.com\",\n    \"phone\": \"+201234567891\"\n}"
                        },
                        "url": {
                            "raw": "{{base_url}}/api/hot-affiliates/update/1",
                            "host": ["{{base_url}}"],
                            "path": ["api", "hot-affiliates", "update", "1"]
                        }
                    }
                },
                {
                    "name": "Delete Hot Affiliate",
                    "request": {
                        "method": "POST",
                        "header": [
                            {
                                "key": "Authorization",
                                "value": "Bearer {{admin_token}}"
                            },
                            {
                                "key": "Accept",
                                "value": "application/json"
                            }
                        ],
                        "url": {
                            "raw": "{{base_url}}/api/hot-affiliates/destroy/1",
                            "host": ["{{base_url}}"],
                            "path": ["api", "hot-affiliates", "destroy", "1"]
                        }
                    }
                }
            ]
        }
    ],
    "variable": [
        {
            "key": "base_url",
            "value": "http://your-domain.com"
        },
        {
            "key": "admin_token",
            "value": "your_admin_bearer_token_here"
        }
    ]
}
```

---

## 🔑 Authentication

### For Admin Endpoints:
1. Login to admin panel: `POST /api/Auth/login`
2. Get the bearer token from response
3. Use it in `Authorization` header: `Bearer {token}`

### For User Endpoints:
- No authentication required (Public endpoints)

---

## 📊 Summary Table

| Method | Endpoint | Auth Required | Description |
|--------|----------|---------------|-------------|
| POST | `/api/users/hot-affiliates/store` | ❌ No | Create new Hot Affiliate (Public) |
| GET | `/api/hot-affiliates` | ✅ Yes | Get all Hot Affiliates (Admin) |
| GET | `/api/hot-affiliates/show/{id}` | ✅ Yes | Get single Hot Affiliate (Admin) |
| POST | `/api/hot-affiliates/update/{id}` | ✅ Yes | Update Hot Affiliate (Admin) |
| POST | `/api/hot-affiliates/destroy/{id}` | ✅ Yes | Delete Hot Affiliate (Admin) |

---

## ⚠️ Notes

1. Replace `{{base_url}}` with your actual domain
2. Replace `{{admin_token}}` with actual admin bearer token
3. All dates are in ISO 8601 format
4. Pagination is available for "Get All" endpoint (default: 15 items per page)
5. Email must be unique across all Hot Affiliates
6. All string fields have max length of 255 characters



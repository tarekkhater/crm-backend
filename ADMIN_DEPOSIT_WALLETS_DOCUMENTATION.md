# Admin Wallet & Deposit System (English)

> **Full Arabic documentation:** see [`WALLETS_DEPOSITS_DOCUMENTATION_AR.md`](./WALLETS_DEPOSITS_DOCUMENTATION_AR.md)  
> **Quick summary (AR):** see [`WALLET_UPDATE_QUICK.md`](./WALLET_UPDATE_QUICK.md)

---

## Database (`info_trade_users`)

| Column | Purpose |
|--------|---------|
| `real_deposit` | Real deposit wallet only |
| `bonus` | Bonus wallet |
| `mup` | MUP wallet (replaces `fake`) |
| `awaiting_deposit` | Credit wallet |
| `balance` | **Sum:** `real_deposit + bonus + mup + awaiting_deposit` (auto-synced) |

```
main_balance = real_deposit + bonus + mup + credit(awaiting_deposit)
balance column = main_balance
```

---

## API Endpoints (auth: `auth:api`, AdminVerified)

| Method | Path | Action |
|--------|------|--------|
| POST | `/api/user/Desposit/record` | Add amount to wallet + create deposit row |
| POST | `/api/user/customers/balance/{userId}` | Set absolute wallet value (admin edit) |
| POST | `/api/Deposits/status` | Approve/reject pending deposit |
| GET | `/api/user/customers/Deposit/{userId}` | Deposit history |

---

## Wallet types

| type | Storage | Counts in `balance`? |
|------|---------|-------------------|
| `deposit` + status=1 | `real_deposit` | Yes |
| `deposit` + status=0 | `awaiting_deposit` | **Yes** |
| `credit` | `awaiting_deposit` | **Yes** |
| `bonus` | `bonus` | Yes |
| `mup` | `mup` | Yes |

> **Credit is now part of trading balance.** All wallet types affect `balance`.

Debit order (trading loss / withdrawal): `real_deposit` → `mup` → `bonus` → `credit`

Aliases: `fake`→`mup`, `awaiting_deposit`→`credit`, `bouns`→`bonus`

---

## Core service

`App\Services\Users\UserWalletService` — `applyCredit()`, `setAbsolute()`, `syncBalance()`, `breakdown()`

---

## Migrations

- `2026_06_03_000005_add_real_deposit_and_sync_balance.php`
- `2026_06_03_000006_drop_fake_from_info_trade_users.php`
- `2026_07_20_000001_sync_balance_include_credit_info_trade_users.php` — يُضيف `awaiting_deposit` إلى `balance` للسجلات القديمة

```bash
php artisan migrate --force
```

# Security audit — chick/ (PHP e-commerce app)

Scope: all PHP files under `chick/`. No `composer.json`/`package.json`, no CORS headers and no
debug endpoints exist in the codebase, so dependency and CORS checks found nothing to review
(PHPMailer is `require`d from `src/`/`phpmailer/` paths that are not committed).

## Critical — fixed in this PR

| # | Issue | Location (before) | Fix |
|---|-------|-------------------|-----|
| 1 | Gmail SMTP app password and account hardcoded in source (4 files) | `contact_process.php`, `place_order.php`, `cancel_order.php`, `forgot_password.php` | Credentials now read from environment / `.env` via `config.php`; `.env` git-ignored, `.env.example` added |
| 2 | DB credentials hardcoded (`root`, empty password); a second copy pointed at an unrelated `portfgenie` DB | `connect.php`, `reset_password.php`, `forgot_password.php` | Single env-driven connection in `connect.php`; the two ad-hoc `mysqli_connect()` calls removed |
| 3 | Unrestricted file upload → remote code execution: any file (incl. `.php`) was moved into web-served `images/` | `add_product.php`, `edit_product.php` | `uploads.php` validates extension whitelist, real MIME via `getimagesize()`, size cap, and generates a random filename; `.htaccess` disables script execution in `images/` and `profile_images/` |
| 4 | Missing authentication: admin order list exposed every customer's name, phone and address to anonymous visitors (auth check was commented out); admin dashboard had no check | `admin_orders.php`, `admin_dashboard.php` | `require_admin()` guard |
| 5 | SQL built by string interpolation, plus reflected XSS and MySQL error disclosure | `contact_process.php` | Prepared statement, input validation, escaped output, errors written to the log only. The double `$conn->query($sql)` (message stored twice) is gone as a side effect |
| 6 | Password reset accepted expired tokens (`reset_expiry` was never checked) | `reset_password.php` | `reset_expiry > NOW()` enforced on both the lookup and the update; minimum password length added |
| 7 | Stored/reflected XSS in admin and order pages (`phone`, `total_price`, `image`, `product_name`, status/error messages rendered raw) | `admin_orders.php`, `cancel_response.php`, `checkout.php`, `place_order.php` | `htmlspecialchars()` on output |
| 8 | Session fixation on login; user enumeration via distinct "no user found" vs "invalid password" errors | `login.php` | `session_regenerate_id(true)`; single generic error message |
| 9 | Unvalidated order input (arbitrary payment method string, non-numeric phone, unbounded quantity) reached the DB and outbound HTML email | `place_order.php` | Whitelist for payment method, 10-digit phone check, quantity bounds, HTML-escaped mail body |
| 10 | Insecure session cookies; world-writable upload dir (`mkdir 0777`) | new `auth.php`, `edit_profile.php` | `HttpOnly`/`SameSite=Lax`/`Secure`-when-HTTPS cookie flags; `0755` |

Hardening also added: `admin@gmail.com` is now the configurable `ADMIN_EMAIL` constant instead of a
literal repeated across 12 files, auth logic is centralised in `auth.php`, and `.htaccess` blocks
HTTP access to `config.php`/`.env`.

Unrelated bug found while auditing and fixed: `view_orders.php` appended `AND ...` to a query with
no `WHERE`, so any admin search/status filter made `prepare()` fail.

## Not fixed — recommended follow-ups

- **No CSRF protection anywhere.** Every state-changing endpoint (`add_to_cart.php`,
  `place_order.php`, `cancel_order.php`, `update_order_status.php`, `add_product.php`,
  `delete_product.php`, `edit_profile.php`) accepts requests with no token, so any site can forge
  them against a logged-in user or admin. `delete_product.php` and `remove_from_wishlist.php`
  additionally mutate state over `GET`, so a plain `<img>` tag is enough. Fixing this properly
  means adding a token helper and touching every form — worth a dedicated PR.
- **Authorisation by email string.** Admin rights are inferred from `ADMIN_EMAIL`. A `role` column
  on `users` would be far more robust (anyone who registers that address becomes admin).
- **No rate limiting / lockout** on `login.php` or the password reset flow.
- **`forgot_password.php` does not actually reset anything** — it emails a contact-form message and
  never issues the `reset_token` that `reset_password.php` consumes. The flow is incomplete.
- **PHPMailer is not vendored or version-pinned** (`require 'src/PHPMailer.php'` etc. point at
  paths absent from the repo, and `forgot_password.php` uses a different path than the other
  files). Install it with Composer so security updates are trackable.
- Product/user images are served from a directory writable by the app; consider storing uploads
  outside the web root and streaming them.

## Action required by the repository owner

The Gmail app password `msft qfxp bfjj hgbu` for `vrundamaurya07@gmail.com` was committed in
plaintext and is still in git history (commit `9db83f1`), so removing it from the working tree does
not make it secret again. **Revoke that app password in the Google account now** and put the
replacement in `chick/.env` only. The same applies to any database password reused there.

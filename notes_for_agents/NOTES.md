Fix SQL injection — Replace all DB::select("...$var...") and DB::update("...$var...") with parameterized queries or Eloquent across all controllers (Accessrightscontroller, Reportcontroller, HomeController, JobCardcontroller, ServicesControler, etc.)
Secure cache-clear routes — Add auth + admin middleware to /clear-cache, /route-cache, /view-clear, /config-cache
Fix password reset flow — Replace custom PasswordResetController with Laravel's built-in ResetPassword notification and proper token-based reset
Enable rate limiting on login — Add throttle:5,1 middleware to login routes
Move Stripe keys to .env — Add STRIPE_SECRET and STRIPE_PUBLISHABLE to .env, update StripePaymentController to read from config() instead of database
Add CSRF protection back — Remove the $except array entries in VerifyCsrfToken.php (or replace with proper API token approach if needed for mobile app endpoints)
Security Hardening (High Priority)
Add Form Request validation — Create 20-30 form request classes for main store/update endpoints (invoice, sales, purchase, customer, vehicle, service, payroll, etc.)
Set APP_DEBUG=false in .env.example and add HTTPS redirect middleware
Fix LoginController::login() — Remove User::all(), use Auth::attempt(), add throttle:5,1
Add security headers middleware — X-Content-Type-Options: nosniff, X-Frame-Options: DENY, Strict-Transport-Security: max-age=31536000, Referrer-Policy: strict-origin-when-cross-origin
Add validation to unauthenticated frontend routes — frontendBook, forntendAdd, state/city AJAX endpoints need input validation even though they're public (you missed this one)
Quality / Reliability
Add basic PHPUnit tests — 15-20 test cases covering login, invoice creation, stock adjustment, payroll calculation, customer CRUD
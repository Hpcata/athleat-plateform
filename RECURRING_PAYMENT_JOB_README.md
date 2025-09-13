# Recurring Payment Status Check Job

This job automatically checks recurring payments and cancels subscriptions that are overdue based on payment history.

## Overview

The `CheckRecurringPaymentStatus` job:
1. Finds all active recurring payments (`payment_status = 'active'` and `canceled_at IS NULL`)
2. Checks payment history for each subscription
3. Cancels subscriptions that are overdue based on:
   - No payment for 2+ months
   - Missing more than 1 expected payment
   - All expected payments completed (8 months)

## Job Logic

### Cancellation Criteria

A subscription will be cancelled if:

1. **No payments found** - No payment records exist
2. **Overdue payments** - No payment for 2+ months
3. **Missing payments** - Missing more than 1 expected payment based on elapsed time
4. **Completed subscription** - All 8 expected payments made

### Payment Calculation

- **Expected payments** = Months since first payment + 1 (for current month)
- **Payment gap** = Expected payments - Actual payments
- **Cancellation threshold** = Payment gap > 1

## Usage

### Manual Execution

```bash
# Run synchronously (immediate execution)
php artisan recurring:check-payments --sync

# Queue the job (background execution)
php artisan recurring:check-payments
```

### Automatic Execution

The job runs automatically **daily at 9:00 AM** via Laravel's task scheduler.

### Programmatic Execution

```php
use App\Jobs\CheckRecurringPaymentStatus;

// Dispatch the job
CheckRecurringPaymentStatus::dispatch();

// Run synchronously
$job = new CheckRecurringPaymentStatus();
$job->handle();
```

## What Happens When Cancelled

When a subscription is cancelled:

1. **RecurringPayment** record is updated:
   - `payment_status` → `'canceled'`
   - `canceled_at` → Current timestamp
   - `cancelation_reason` → Reason for cancellation

2. **UserPlan** record is updated:
   - `status` → `'cancelled'`

3. **Logging** - All actions are logged for audit purposes

## Cancellation Reasons

- `"No payments found"` - No payment records exist
- `"No payment for X months"` - Overdue by X months
- `"All payments completed"` - Reached 8-month limit
- `"Missing X payments"` - Missing X expected payments

## Testing

Run the test suite:

```bash
php artisan test tests/Feature/CheckRecurringPaymentStatusTest.php
```

## Monitoring

Check logs for job execution:

```bash
tail -f storage/logs/laravel.log | grep "recurring payment"
```

## Configuration

### Schedule Timing

To change the schedule, edit `app/Console/Kernel.php`:

```php
// Current: Daily at 9 AM
$schedule->command('recurring:check-payments')->dailyAt('09:00');

// Alternative: Every 6 hours
$schedule->command('recurring:check-payments')->everySixHours();

// Alternative: Weekly on Monday at 10 AM
$schedule->command('recurring:check-payments')->weeklyOn(1, '10:00');
```

### Grace Period

To modify the grace period (currently 2 months), edit the job:

```php
// In shouldCancelSubscription() method
if ($monthsSinceLastPayment >= 2) { // Change this number
    return true;
}
```

## Database Tables Affected

- `recurring_payments` - Status and cancellation info
- `user_plans` - Plan status
- `payments` - Read-only for analysis

## Queue Configuration

Make sure your queue worker is running:

```bash
php artisan queue:work
```

Or for production with supervisor/daemon.

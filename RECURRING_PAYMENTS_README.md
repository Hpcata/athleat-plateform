# Monthly Recurring Payment System

This document explains the monthly recurring payment system implemented for Athleat platform.

## Overview

The system handles monthly recurring payments for plans using Stripe subscriptions, with automatic payment tracking and plan deactivation for failed payments.

## Features

### 1. Database Schema

The `user_plans` table has been extended with the following fields:

- `is_recurring` (boolean): Whether this plan has recurring monthly payments
- `stripe_subscription_id` (string): Stripe subscription ID for recurring payments
- `total_payments` (integer): Total number of payments completed
- `total_payments_expected` (integer): Total number of payments expected (8 months)
- `next_payment_date` (timestamp): Next payment due date
- `last_payment_date` (timestamp): Last successful payment date
- `payment_status` (enum): Current payment status from Stripe
- `canceled_at` (timestamp): When the subscription was canceled
- `cancelation_reason` (text): Reason for cancellation

### 2. Stripe Integration

#### Subscription Creation
When a user selects monthly payment:
1. A Stripe customer is created/retrieved
2. A Stripe product and price are created for the plan
3. A subscription is created with monthly recurring billing
4. The UserPlan is updated with subscription details

#### Payment Tracking
- First payment is tracked immediately upon subscription creation
- Subsequent payments are tracked via Stripe webhooks
- Payment records are created for each successful payment

### 3. Webhook Handling

The system handles the following Stripe webhook events:

- `invoice.payment_succeeded`: Updates payment count and status
- `invoice.payment_failed`: Marks plan as past_due
- `customer.subscription.updated`: Updates subscription status
- `customer.subscription.deleted`: Deactivates the plan

### 4. Failed Payment Handling

#### Automatic Deactivation
Plans are automatically deactivated when:
- Subscription status becomes 'canceled', 'unpaid', or 'incomplete_expired'
- Payment fails and subscription is not recovered

#### Manual Monitoring
A console command `payments:check-failed` can be run to:
- Check all active recurring plans
- Verify subscription status with Stripe
- Deactivate plans with failed payments
- Update payment statuses

## Setup Instructions

### 1. Environment Variables

Add the following to your `.env` file:

```env
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...
STRIPE_WEBHOOK_SECRET=whsec_...
```

### 2. Stripe Webhook Configuration

1. Go to Stripe Dashboard > Webhooks
2. Create a new webhook endpoint: `https://yourdomain.com/stripe/webhook`
3. Select these events:
   - `invoice.payment_succeeded`
   - `invoice.payment_failed`
   - `customer.subscription.updated`
   - `customer.subscription.deleted`
4. Copy the webhook secret to your `.env` file

### 3. Run Migrations

```bash
php artisan migrate
```

### 4. Schedule the Monitoring Command

Add to your `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule)
{
    // Check for failed payments daily
    $schedule->command('payments:check-failed')->daily();
}
```

## Usage

### For Users

1. Select "Monthly Plan" when choosing a plan
2. Complete payment with card details
3. Subscription is automatically created
4. Monthly payments are charged automatically
5. Plan remains active as long as payments succeed

### For Administrators

#### View Recurring Payments
- Access `/admin/recurring-payments` to view all recurring subscriptions
- See payment status, completion count, and next payment dates

#### Cancel Subscriptions
- Administrators can cancel subscriptions manually
- Plans are immediately deactivated
- Users lose access to plan features

#### Monitor Failed Payments
- Run `php artisan payments:check-failed` to check for failed payments
- Review logs for detailed information about payment failures

## Payment Flow

### Monthly Plan Purchase
1. User selects monthly plan
2. Payment form is submitted with `is_monthly: true`
3. Stripe subscription is created
4. UserPlan is marked as recurring
5. First payment is processed

### Subsequent Payments
1. Stripe automatically charges the customer monthly
2. Webhook receives `invoice.payment_succeeded` event
3. Payment count is incremented
4. Next payment date is updated
5. Payment record is created

### Failed Payments
1. Stripe attempts payment and fails
2. Webhook receives `invoice.payment_failed` event
3. Plan status is updated to 'past_due'
4. If payment continues to fail, subscription is canceled
5. Plan is deactivated and user loses access

## Monitoring and Maintenance

### Logs
All payment activities are logged with detailed information:
- Subscription creation
- Payment successes/failures
- Plan deactivations
- Webhook events

### Alerts
Consider setting up alerts for:
- High failure rates
- Multiple consecutive failures
- Subscription cancellations

### Regular Maintenance
- Monitor webhook delivery success rates
- Review failed payment patterns
- Update payment methods for users with expired cards

## Security Considerations

- Webhook signatures are verified to ensure authenticity
- All Stripe API calls use secret keys
- Payment data is not stored locally (only references)
- User plan access is controlled by subscription status

## Troubleshooting

### Common Issues

1. **Webhook not receiving events**
   - Check webhook URL is accessible
   - Verify webhook secret in environment
   - Check Stripe dashboard for delivery failures

2. **Subscriptions not creating**
   - Verify Stripe API keys are correct
   - Check customer creation permissions
   - Review error logs for API failures

3. **Plans not deactivating on failure**
   - Run the monitoring command manually
   - Check webhook event handling
   - Verify subscription status in Stripe

### Support Commands

```bash
# Check failed payments
php artisan payments:check-failed

# View logs
tail -f storage/logs/laravel.log

# Test webhook locally (using Stripe CLI)
stripe listen --forward-to localhost:8000/stripe/webhook
```

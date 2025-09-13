<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Plan;
use App\Models\Consultation;
use App\Models\RecurringPayment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

class MonthlyPlanPaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_monthly_plan_payment_flow()
    {
        // Create test data
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'name' => 'Test User'
        ]);

        $plan = Plan::factory()->create([
            'name' => 'Test Plan',
            'price' => 400.00
        ]);

        $consultation = Consultation::factory()->create([
            'time' => 30,
            'price' => 100.00
        ]);

        // Mock Stripe API calls
        $this->mockStripeCalls();

        // Authenticate user
        Auth::login($user);

        // Test monthly plan purchase
        $response = $this->postJson('/process-plan-purchase', [
            'plan_id' => $plan->id,
            'plan_type' => 'powerplay',
            'price' => 500.00, // Plan + consultation
            'final_price' => 500.00,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'payment_method_id' => 'pm_test_payment_method',
            'coupon_code' => null,
            'is_monthly' => true
        ]);

        // Assert response
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Plan purchased successfully!'
        ]);

        // Assert user plan was created
        $this->assertDatabaseHas('user_plans', [
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'status' => 'pending'
        ]);

        // Assert recurring payment was created
        $this->assertDatabaseHas('recurring_payments', [
            'user_plan_id' => \App\Models\UserPlan::where('user_id', $user->id)->where('plan_id', $plan->id)->first()->id,
            'payment_status' => 'active'
        ]);

        // Assert payment was recorded
        $this->assertDatabaseHas('payments', [
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'consultation_id' => $consultation->id,
            'price' => 500.00
        ]);

        // Assert consultation was booked
        $this->assertDatabaseHas('user_consultations', [
            'user_id' => $user->id,
            'consultation_id' => $consultation->id
        ]);
    }

    public function test_one_time_plan_payment_flow()
    {
        // Create test data
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'name' => 'Test User'
        ]);

        $plan = Plan::factory()->create([
            'name' => 'Test Plan',
            'price' => 400.00
        ]);

        // Mock Stripe API calls
        $this->mockStripeCalls();

        // Authenticate user
        Auth::login($user);

        // Test one-time plan purchase
        $response = $this->postJson('/process-plan-purchase', [
            'plan_id' => $plan->id,
            'plan_type' => 'main',
            'price' => 400.00,
            'final_price' => 400.00,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'payment_method_id' => 'pm_test_payment_method',
            'coupon_code' => null,
            'is_monthly' => false
        ]);

        // Assert response
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Plan purchased successfully!'
        ]);

        // Assert user plan was created without recurring payment
        $this->assertDatabaseHas('user_plans', [
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'status' => 'pending'
        ]);

        // Assert no recurring payment was created
        $userPlan = \App\Models\UserPlan::where('user_id', $user->id)->where('plan_id', $plan->id)->first();
        $this->assertDatabaseMissing('recurring_payments', [
            'user_plan_id' => $userPlan->id
        ]);
    }

    public function test_monthly_plan_payment_fails_on_payment_method_reuse_error()
    {
        // Create test data
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'name' => 'Test User'
        ]);

        $plan = Plan::factory()->create([
            'name' => 'Test Plan',
            'price' => 400.00
        ]);

        // Mock Stripe API calls to fail
        $this->mockFailingStripeCalls();

        // Authenticate user
        Auth::login($user);

        // Test monthly plan purchase with reused payment method
        $response = $this->postJson('/process-plan-purchase', [
            'plan_id' => $plan->id,
            'plan_type' => 'main',
            'price' => 400.00,
            'final_price' => 400.00,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'payment_method_id' => 'pm_reused_payment_method',
            'coupon_code' => null,
            'is_monthly' => true
        ]);

        // Assert response shows failure
        $response->assertStatus(400);
        $response->assertJson([
            'success' => false
        ]);
        $response->assertJsonStructure([
            'message'
        ]);

        // Assert no user plan was created (transaction rolled back)
        $this->assertDatabaseMissing('user_plans', [
            'user_id' => $user->id,
            'plan_id' => $plan->id
        ]);

        // Assert no payment was recorded
        $this->assertDatabaseMissing('payments', [
            'user_id' => $user->id,
            'plan_id' => $plan->id
        ]);

        // Assert no recurring payment was created
        $this->assertDatabaseMissing('recurring_payments', [
            'user_plan_id' => \App\Models\UserPlan::where('user_id', $user->id)->where('plan_id', $plan->id)->first()?->id ?? 999
        ]);
    }

    private function mockStripeCalls()
    {
        // Mock Stripe PaymentIntent creation
        $this->mock(\Stripe\PaymentIntent::class, function ($mock) {
            $mock->shouldReceive('create')
                ->andReturn((object) [
                    'id' => 'pi_test_payment_intent',
                    'status' => 'succeeded',
                    'client_secret' => 'pi_test_client_secret'
                ]);
        });

        // Mock Stripe Customer creation
        $this->mock(\Stripe\Customer::class, function ($mock) {
            $mock->shouldReceive('all')
                ->andReturn((object) ['data' => []]);
            
            $mock->shouldReceive('create')
                ->andReturn((object) [
                    'id' => 'cus_test_customer'
                ]);
        });

        // Mock Stripe Product creation
        $this->mock(\Stripe\Product::class, function ($mock) {
            $mock->shouldReceive('create')
                ->andReturn((object) [
                    'id' => 'prod_test_product'
                ]);
        });

        // Mock Stripe Price creation
        $this->mock(\Stripe\Price::class, function ($mock) {
            $mock->shouldReceive('create')
                ->andReturn((object) [
                    'id' => 'price_test_price'
                ]);
        });

        // Mock Stripe Subscription creation
        $this->mock(\Stripe\Subscription::class, function ($mock) {
            $subscription = (object) [
                'id' => 'sub_test_subscription',
                'status' => 'active',
                'current_period_end' => time() + (30 * 24 * 60 * 60) // 30 days from now
            ];
            
            $mock->shouldReceive('create')
                ->andReturn($subscription);
            
            $mock->shouldReceive('confirm')
                ->andReturn($subscription);
        });
    }

    private function mockFailingStripeCalls()
    {
        // Mock Stripe PaymentIntent creation
        $this->mock(\Stripe\PaymentIntent::class, function ($mock) {
            $mock->shouldReceive('create')
                ->andReturn((object) [
                    'id' => 'pi_test_payment_intent',
                    'status' => 'succeeded',
                    'client_secret' => 'pi_test_client_secret'
                ]);
        });

        // Mock Stripe Customer creation
        $this->mock(\Stripe\Customer::class, function ($mock) {
            $mock->shouldReceive('all')
                ->andReturn((object) ['data' => []]);
            
            $mock->shouldReceive('create')
                ->andReturn((object) [
                    'id' => 'cus_test_customer'
                ]);
        });

        // Mock Stripe PaymentMethod to fail on retrieve (payment method cannot be reused)
        $this->mock(\Stripe\PaymentMethod::class, function ($mock) {
            $mock->shouldReceive('retrieve')
                ->andThrow(new \Stripe\Exception\InvalidRequestException(
                    'This PaymentMethod was previously used without being attached to a Customer or was detached from a Customer, and may not be used again.',
                    400,
                    null,
                    null,
                    null,
                    'payment_method'
                ));
        });
    }
}

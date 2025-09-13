@extends('front.layouts.app')

@section('title', 'Payment Successful')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body text-center py-5">
                    <!-- Success Icon -->
                    <div class="mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 80 80" fill="none" class="mx-auto">
                            <circle cx="40" cy="40" r="40" fill="#3E8E00"/>
                            <path d="M25 40L35 50L55 30" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    
                    <!-- Success Message -->
                    <h2 class="mb-3 text-success">Payment Successful!</h2>
                    <p class="lead mb-4">Thank you for your purchase. Your payment has been processed successfully.</p>
                    
                    <!-- Additional Information -->
                    <div class="alert alert-info mb-4">
                        <h5 class="alert-heading">What's Next?</h5>
                        <ul class="mb-0 text-start">
                            <li>You will receive a confirmation email shortly</li>
                            <li>Your plan is now active and ready to use</li>
                            <li>You can access your personalized nutrition plan in your dashboard</li>
                        </ul>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                        <a href="{{ route('front.profile', ['id' => Auth::id()]) }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-user me-2"></i>Go to Dashboard
                        </a>
                        <a href="{{ route('front.index') }}" class="btn btn-outline-secondary btn-lg">
                            <i class="fas fa-home me-2"></i>Back to Home
                        </a>
                    </div>
                    
                    <!-- Support Information -->
                    <div class="mt-5 pt-4 border-top">
                        <p class="text-muted mb-0">
                            Need help? <a href="mailto:support@athleat.com" class="text-decoration-none">Contact our support team</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-redirect after 10 seconds
setTimeout(function() {
    window.location.href = '{{ route("front.profile", ["id" => Auth::id()]) }}';
}, 10000);

// Show countdown
let countdown = 10;
const countdownElement = document.createElement('p');
countdownElement.className = 'text-muted mt-3';
countdownElement.innerHTML = `Redirecting to dashboard in <span id="countdown">${countdown}</span> seconds...`;
document.querySelector('.card-body').appendChild(countdownElement);

const countdownInterval = setInterval(function() {
    countdown--;
    document.getElementById('countdown').textContent = countdown;
    if (countdown <= 0) {
        clearInterval(countdownInterval);
    }
}, 1000);
</script>
@endsection

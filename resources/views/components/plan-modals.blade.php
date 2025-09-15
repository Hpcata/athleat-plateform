{{-- Plan Modals Component --}}
{{-- Usage: @include('components.plan-modals', ['userEmail' => $userEmail, 'planDetails' => $planDetails, 'consultations' => $consultations]) --}}
@php
    $months = 8;
    $thirtyMinConsultationPlanPrice = $consultations->where('time', 30)->first()?->price ?? 0;
    $sixtyMinConsultationPlanPrice = $consultations->where('time', 60)->first()?->price ?? 0;
    $powerPlayPlanPrice = $planDetails?->price + $thirtyMinConsultationPlanPrice;
    $gamePlanPlanPrice = $planDetails?->price + $sixtyMinConsultationPlanPrice;
    
    // Monthly calculations with 10% markup
    $monthlyPlanPrice = ($planDetails?->price * 1.1) / $months;
    $monthlyPowerPlayPrice = ($powerPlayPlanPrice * 1.1) / $months;
    $monthlyGamePlanPrice = ($gamePlanPlanPrice * 1.1) / $months;
@endphp

<div class="modal fade" id="planChooseModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="p-3 modal-content" style="border-radius: 12px;">

              
                <div class="mb-3 pb-0 border-0 modal-header">
                    <div>&nbsp;</div>
                    <h5 class="mb-0 modal-title fw-bold">Choose your plan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                </div>

            
                <div class="p-0 modal-body">
                    <div class="d-flex justify-content-center mb-4">
                        <div class="d-flex justify-content-center w-fit payment-toggle-swich">
                            <button class="position-relative toggle-btn active" id="onePaymentBtn">One Payment <span
                                    class="badge-discount">-10%</span></button>
                            <button class="toggle-btn" id="monthlyPlanBtn">Monthly Plan</button>
                        </div>
                    </div>

                   
                    <div id="onePaymentPlans">
                      
                        <div class="mb-4 border-0 card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h6 class="mb-1" id="currentPlanName">{{ $planDetails?->name }}</h6>
                                    <div class="">
                                        <span class="" id="planPrice">A${{ number_format($planDetails?->price, 0) }}</span>
                                        <small class="d-block mb-2" id="planDuration">One payment</small>
                                    </div>
                                </div>

                                {!! $planDetails?->description !!}
                                <div class="d-flex align-items-center justify-content-between">
                                    <button class="btn btn-signup plan-get-started-btn" data-plan-type="main" data-plan-price="{{ $planDetails?->price }}" data-monthly-price="{{ number_format($monthlyPlanPrice, 2) }}">Get started</button>
                                    <a href="" class="text-decoration-none whats-included-link" data-bs-dismiss="modal">What's included
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="19"
                                            viewBox="0 0 14 19" fill="none">
                                            <path
                                                d="M8.00034 10.4993L4.85934 7.0793C4.73048 6.94917 4.6582 6.77343 4.6582 6.5903C4.6582 6.40717 4.73048 6.23144 4.85934 6.1013C4.92261 6.03705 4.99803 5.98603 5.08121 5.9512C5.16439 5.91637 5.25366 5.89844 5.34384 5.89844C5.43401 5.89844 5.52329 5.91637 5.60646 5.9512C5.68964 5.98603 5.76506 6.03705 5.82834 6.1013L9.65834 10.0093C9.78701 10.1397 9.85915 10.3156 9.85915 10.4988C9.85915 10.682 9.78701 10.8579 9.65834 10.9883L5.82834 14.8963C5.76506 14.9606 5.68964 15.0116 5.60646 15.0464C5.52329 15.0812 5.43401 15.0992 5.34384 15.0992C5.25366 15.0992 5.16439 15.0812 5.08121 15.0464C4.99803 15.0116 4.92261 14.9606 4.85934 14.8963C4.73048 14.7662 4.6582 14.5904 4.6582 14.4073C4.6582 14.2242 4.73048 14.0484 4.85934 13.9183L8.00034 10.4993Z"
                                                fill="#3B3B3B" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>

               
                        <div class="position-relative mb-4 border-0 card">
                            <span class="popular-badge">Popular</span>
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h6 class="mb-1">Power Play</h6>
                                    <div class="">
                                        <span class="" id="powerPlayPrice">A${{ number_format($powerPlayPlanPrice, 0) }}</span>
                                        <small class="d-block mb-2" id="powerPlayDuration">One payment</small>
                                    </div>
                                </div>

                                <p class="">{{ $planDetails?->name }} + 30 min Consult with Extreme Sports Dietitian Kerry O'Byran.</p>
                                <div class="d-flex align-items-center justify-content-between">
                                    <button class="btn btn-signup plan-get-started-btn" data-plan-type="powerplay" data-plan-price="{{ $powerPlayPlanPrice }}" data-monthly-price="{{ number_format($monthlyPowerPlayPrice, 2) }}">Get started</button>
                                    <a href="{{ route('front.consultations') }}" class="text-decoration-none">About Consultations
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="19"
                                            viewBox="0 0 14 19" fill="none">
                                            <path
                                                d="M8.00034 10.4993L4.85934 7.0793C4.73048 6.94917 4.6582 6.77343 4.6582 6.5903C4.6582 6.40717 4.73048 6.23144 4.85934 6.1013C4.92261 6.03705 4.99803 5.98603 5.08121 5.9512C5.16439 5.91637 5.25366 5.89844 5.34384 5.89844C5.43401 5.89844 5.52329 5.91637 5.60646 5.9512C5.68964 5.98603 5.76506 6.03705 5.82834 6.1013L9.65834 10.0093C9.78701 10.1397 9.85915 10.3156 9.85915 10.4988C9.85915 10.682 9.78701 10.8579 9.65834 10.9883L5.82834 14.8963C5.76506 14.9606 5.68964 15.0116 5.60646 15.0464C5.52329 15.0812 5.43401 15.0992 5.34384 15.0992C5.25366 15.0992 5.16439 15.0812 5.08121 15.0464C4.99803 15.0116 4.92261 14.9606 4.85934 14.8963C4.73048 14.7662 4.6582 14.5904 4.6582 14.4073C4.6582 14.2242 4.73048 14.0484 4.85934 13.9183L8.00034 10.4993Z"
                                                fill="#3B3B3B" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>

                   
                        <div class="mb-4 border-0 card">

                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h6 class="mb-1">Game Plan</h6>
                                    <div class="">
                                        <span class="" id="gamePlanPrice">A${{ number_format($gamePlanPlanPrice, 0) }}</span>
                                        <small class="d-block mb-2" id="gamePlanDuration">One payment</small>
                                    </div>
                                </div>

                                <p class="">{{ $planDetails?->name }} + 60 min Consult with Kerry to cover Nutrition AND Training Advise</p>
                                <div class="d-flex align-items-center justify-content-between">
                                    <button class="btn btn-signup plan-get-started-btn" data-plan-type="gameplan" data-plan-price="{{ $gamePlanPlanPrice }}" data-monthly-price="{{ number_format($monthlyGamePlanPrice, 2) }}">Get started</button>
                                    <a href="#" class="text-decoration-none whats-in-one-on-one-link" data-bs-dismiss="modal">What's in a 1 on 1
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="19"
                                            viewBox="0 0 14 19" fill="none">
                                            <path
                                                d="M8.00034 10.4993L4.85934 7.0793C4.73048 6.94917 4.6582 6.77343 4.6582 6.5903C4.6582 6.40717 4.73048 6.23144 4.85934 6.1013C4.92261 6.03705 4.99803 5.98603 5.08121 5.9512C5.16439 5.91637 5.25366 5.89844 5.34384 5.89844C5.43401 5.89844 5.52329 5.91637 5.60646 5.9512C5.68964 5.98603 5.76506 6.03705 5.82834 6.1013L9.65834 10.0093C9.78701 10.1397 9.85915 10.3156 9.85915 10.4988C9.85915 10.682 9.78701 10.8579 9.65834 10.9883L5.82834 14.8963C5.76506 14.9606 5.68964 15.0116 5.60646 15.0464C5.52329 15.0812 5.43401 15.0992 5.34384 15.0992C5.25366 15.0992 5.16439 15.0812 5.08121 15.0464C4.99803 15.0116 4.92261 14.9606 4.85934 14.8963C4.73048 14.7662 4.6582 14.5904 4.6582 14.4073C4.6582 14.2242 4.73048 14.0484 4.85934 13.9183L8.00034 10.4993Z"
                                                fill="#3B3B3B" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

       <!-- Payment Modal -->
    <div class="modal fade" id="paymentModalPlan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <!-- Monthly Payment Content -->
                <div id="paymentContentConsultation">
                    <div class="pt-0 pb-0 border-0 modal-header">
                        <h5 class="modal-title" id="paymentModalTitle">Power Play</h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="modal-subtitle" id="paymentModalSubtitle">Nutrition Training Plan PLUS 30 min Nutrition Consultation </p>
                        <p class="amount"><strong id="paymentModalPrice" data-original-price="0">A$51.25</strong> <span class="" id="paymentModalDuration">Per month for 8 months.</span></p>

                        <span class="divider"></span>
                        <p class="mb-2 sign-in-text" style="line-height: 22px;">Signed in as<br><strong>{{ Auth::user()->email ?? '' }}</strong></p>
                        <a href="#" class="d-block mb-3 coupon-code" id="toggle-coupon-consultation">Add a Coupon
                            Code</a>
                        <!-- Coupon Code -->
                        <div class="mb-3 d-none" id="coupon-details-consultation">
                            <label for="promo-code-consultation" class="form-label">Coupon Code</label>
                            <div class="d-flex gap-2">
                                <input type="text" class="h-auto form-control" id="promo-code-consultation"
                                    placeholder="Enter coupon code">
                                <input type="hidden" class="form-control" id="discount-consultation">
                                <button type="button" class="btn btn-signup"
                                    id="apply-promo-code-consultation">Apply</button>
                            </div>
                            <small id="promo-message-consultation" class="form-text"></small>
                        </div>

                        <form id="paymentForm">
                            <div class="form-wrap">
                                <div class="mb-3">
                                    <label class="form-label">Card number</label>
                                    <div class="input-with-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" class="input-icon">
                                            <path d="M22.5 21H1.5C1.10218 21 0.720644 20.842 0.43934 20.5607C0.158035 20.2794 0 19.8978 0 19.5L0 7.5H24V19.5C24 20.3295 23.3295 21 22.5 21ZM13.1355 11.0265C12.579 10.6995 11.94 10.5 11.25 10.5C9.1785 10.5 7.5 12.1785 7.5 14.25C7.5 16.3215 9.1785 18 11.25 18C11.94 18 12.579 17.8005 13.1355 17.4735C12.435 16.5825 12 15.471 12 14.25C12 13.029 12.435 11.9175 13.1355 11.0265ZM17.25 10.5C15.1785 10.5 13.5 12.1785 13.5 14.25C13.5 16.3215 15.1785 18 17.25 18C19.3215 18 21 16.3215 21 14.25C21 12.1785 19.3215 10.5 17.25 10.5ZM0 4.5C0 4.10218 0.158035 3.72064 0.43934 3.43934C0.720644 3.15804 1.10218 3 1.5 3H22.5C23.3295 3 24 3.6705 24 4.5V6H0V4.5Z" fill="#B1B1B1"/>
                                        </svg>
                                        <div id="card-number-element" class="form-control">
                                            <!-- Stripe Elements will create form elements here -->
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Name on card</label>
                                    <input type="text" class="form-control" id="card-holder-name" placeholder="Card name" required>
                                </div>
                                <div class="row">
                                    <div class="mb-3 col-6">
                                        <label class="form-label">Expiry date</label>
                                        <div id="card-expiry-element" class="form-control">
                                            <!-- Stripe Elements will create form elements here -->
                                        </div>
                                    </div>
                                    <div class="mb-3 col-6">
                                        <label class="form-label">CVV</label>
                                        <div id="card-cvc-element" class="form-control">
                                            <!-- Stripe Elements will create form elements here -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Button that swaps modal content -->
                            <button type="submit" class="w-100 btn btn-signup" id="paymentButton">
                                One Payment | A${{ number_format($planDetails?->price, 0) }}
                            </button>
                        </form>

                      <p class="mt-3 text-muted small confirm-text">
                            By confirming your monthly amount, you allow Athleat Fuel to charge you for future payments in accordance with your chosen plan for the next 8 months.
                        </p>
                        <p class="mt-3 text-muted small confirm-text">
                            By placing your order, you agree to our <a href="#" class="terms-link">Terms of Service</a>
                            and <a href="#" class="terms-link">Privacy Policy</a>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <!-- Congrats Modal for Plan Selection -->
    <div class="modal fade" id="congratsModalPlan" tabindex="-1" aria-labelledby="congratsModalPlanLabel"
        aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 12px;">
                <div id="congratsContentPlan">
                    <!-- Remove close button to prevent manual closing, same as consultation -->
                    <!-- <button type="button" class="btn-close congrats-modal" data-bs-dismiss="modal"
                        aria-label="Close"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                            viewBox="0 0 12 12" fill="none">
                            <path
                                d="M0.219668 1.28033C-0.0732225 0.987438 -0.0732225 0.512558 0.219668 0.219668C0.512558 -0.0732225 0.987438 -0.0732225 1.28033 0.219668L5.999 4.9384L10.7176 0.219798C11.0105 -0.0730923 11.4854 -0.0730923 11.7782 0.219798C12.0711 0.512688 12.0711 0.987568 11.7782 1.28046L7.0597 5.999L11.7782 10.7176C12.0711 11.0105 12.0711 11.4854 11.7782 11.7782C11.4854 12.0711 11.0105 12.0711 10.7176 11.7782L5.999 7.0597L1.28033 11.7784C0.987438 12.0713 0.512558 12.0713 0.219668 11.7784C-0.0732225 11.4855 -0.0732225 11.0106 0.219668 10.7177L4.9384 5.999L0.219668 1.28033Z"
                                fill="#626262" />
                        </svg></button> -->
                        <img src="{{ frontAssets('images/consultation/congrats-modal-img.png') }}" alt="Congrats"
                        class="rounded-top w-100">
                    <div class="p-4 text-center modal-body">
                        <div class="mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="61" height="60" viewBox="0 0 61 60"
                                fill="none">
                                <path
                                    d="M30.1875 53.75C43.4768 53.75 54.25 42.9768 54.25 29.6875C54.25 16.3981 43.4768 5.625 30.1875 5.625C16.8981 5.625 6.125 16.3981 6.125 29.6875C6.125 42.9768 16.8981 53.75 30.1875 53.75Z"
                                    stroke="#3E8E00" stroke-width="3" />
                                <path
                                    d="M19.25 30.625C20.2764 31.6514 22.6373 34.0123 25.2945 36.6695C26.2708 37.6458 27.8539 37.6461 28.8302 36.6698L42.375 23.125"
                                    stroke="#3E8E00" stroke-width="3" stroke-linecap="round" />
                            </svg>
                        </div>
                        <h4 class="congrats-title"><strong>Congrats legend!</strong></h4>
                        <p class="mb-1 congrats-subtitle" id="congratsPlanName"><strong>Your {{ $planDetails?->name }}</strong><br>{{ $planDetails?->name }}</p>
                        <p class="congrats-para" id="congratsPlanDescription">
                            We'll send you an email to book your consultation. You will need to complete your
                            questionnaire prior then Kerry will start working on your personalised nutrition plan and it
                            will appear in your web app, as soon as it's ready.
                        </p>
                        <!-- Dynamic content based on plan type -->
                        <div id="congratsPlanContent">
                            <!-- For Power Play/Game Plan - show Book a time button -->
                            <div id="powerPlayGamePlanContent" style="display: none;">
                                <p class="mb-3 congrats-para">
                                    Let's book in a time for your consultation!
                                </p>
                                <button type="button" class="w-100 btn btn-signup" id="book-time-btn-plan">Book a Time</button>
                            </div>
                            <!-- For Normal Plan - show questionnaire button -->
                            <div id="normalPlanContent" style="display: none;">
                                <button type="button" class="w-100 btn btn-signup" id="completeQuestionnaireBtn">Next - Complete Questionnaire</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendar Booking Modal -->
    <div class="modal fade" id="calendarBookingModalPlan" tabindex="-1" aria-labelledby="calendarBookingModalPlanLabel"
        aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content" style="border-radius: 12px;">
                <div class="modal-header">
                    <h5 class="modal-title" id="calendarBookingModalPlanLabel">Book Your Consultation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="calendar-container" style="height: 600px;">
                        <!-- Google Calendar Appointment Scheduling begin -->
                        <iframe id="calendar-iframe-plan" src="" style="border: 0" width="100%" height="600"
                            frameborder="0"></iframe>
                        <!-- end Google Calendar Appointment Scheduling -->
                    </div>
                </div>
                <div class="modal-footer">
                    <p class="text-muted small mb-0">
                        <i class="fas fa-info-circle me-1"></i>
                        After booking your consultation, you'll be redirected based on your questionnaire completion status.
                    </p>
                </div>
            </div>
        </div>
    </div>

<!-- JavaScript for Modal Functionality -->
<script src="https://js.stripe.com/v3/"></script>
<style>
/* Payment processing modal styles */
.payment-processing {
    pointer-events: auto !important;
}

.payment-processing .modal-backdrop {
    pointer-events: none !important;
}

.payment-processing .btn-close,
.payment-processing .close {
    opacity: 0.5 !important;
    cursor: not-allowed !important;
}

.payment-processing .modal-dialog {
    pointer-events: auto !important;
}

/* Prevent text selection during payment processing */
.payment-processing * {
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

/* Allow text selection in input fields */
.payment-processing input,
.payment-processing textarea {
    -webkit-user-select: text;
    -moz-user-select: text;
    -ms-user-select: text;
    user-select: text;
}
</style>
<script>
// Initialize Stripe globally
const stripe = Stripe('{{ config("services.stripe.key") }}');
let cardNumberElement, cardExpiryElement, cardCvcElement;
let paymentMethodId = null; // Global variable for payment method ID
let isPaymentModalClosingProgrammatically = false; // Flag to track programmatic closing

document.addEventListener('DOMContentLoaded', function() {

    // Initialize Stripe Elements when payment modal is shown
    $('#paymentModalPlan').on('shown.bs.modal', function() {
        if (!cardNumberElement) {
            const elements = stripe.elements();
            
            cardNumberElement = elements.create('cardNumber', {
                style: {
                    base: {
                        fontSize: '16px',
                        color: '#424770',
                        '::placeholder': {
                            color: '#aab7c4',
                        },
                    },
                },
            });
            
            cardExpiryElement = elements.create('cardExpiry', {
                style: {
                    base: {
                        fontSize: '16px',
                        color: '#424770',
                        '::placeholder': {
                            color: '#aab7c4',
                        },
                    },
                },
            });
            
            cardCvcElement = elements.create('cardCvc', {
                style: {
                    base: {
                        fontSize: '16px',
                        color: '#424770',
                        '::placeholder': {
                            color: '#aab7c4',
                        },
                    },
                },
            });
            
            cardNumberElement.mount('#card-number-element');
            cardExpiryElement.mount('#card-expiry-element');
            cardCvcElement.mount('#card-cvc-element');
        }
    });
    // Toggle between one payment and monthly plans
    const onePaymentBtn = document.getElementById('onePaymentBtn');
    const monthlyPlanBtn = document.getElementById('monthlyPlanBtn');
    const dynamicPlans = document.getElementById('dynamicPlans');

    // Plan pricing data
    const planPricing = {
        oneTime: {
            planPrice: 'A${{ number_format($planDetails?->price, 0) }}',
            planDuration: 'One payment',
            powerPlayPrice: 'A${{ number_format($powerPlayPlanPrice, 0) }}',
            powerPlayDuration: 'One payment',
            gamePlanPrice: 'A${{ number_format($gamePlanPlanPrice, 0) }}',
            gamePlanDuration: 'One payment'
        },
        monthly: {
            planPrice: 'A${{ number_format($monthlyPlanPrice, 2) }}/mth',
            planDuration: 'Over {{ $months }} Months',
            powerPlayPrice: 'A${{ number_format($monthlyPowerPlayPrice, 2) }}/mth',
            powerPlayDuration: 'Over {{ $months }} Months',
            gamePlanPrice: 'A${{ number_format($monthlyGamePlanPrice, 2) }}/mth',
            gamePlanDuration: 'Over {{ $months }} Months'
        }
    };

    function updatePlanPricing(pricingType) {
        const pricing = planPricing[pricingType];
        
        // Update main plan
        document.getElementById('planPrice').textContent = pricing.planPrice;
        document.getElementById('planDuration').textContent = pricing.planDuration;
        
        // Update Power Play plan
        document.getElementById('powerPlayPrice').textContent = pricing.powerPlayPrice;
        document.getElementById('powerPlayDuration').textContent = pricing.powerPlayDuration;
        
        // Update Game Plan
        document.getElementById('gamePlanPrice').textContent = pricing.gamePlanPrice;
        document.getElementById('gamePlanDuration').textContent = pricing.gamePlanDuration;
    }

    if (onePaymentBtn && monthlyPlanBtn) {
        onePaymentBtn.addEventListener('click', function() {
            onePaymentBtn.classList.add('active');
            monthlyPlanBtn.classList.remove('active');
            updatePlanPricing('oneTime');
        });

        monthlyPlanBtn.addEventListener('click', function() {
            monthlyPlanBtn.classList.add('active');
            onePaymentBtn.classList.remove('active');
            updatePlanPricing('monthly');
        });
    }

    // Handle plan selection
    const planSelectBtns = document.querySelectorAll('.plan-select-btn');
    planSelectBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const planId = this.getAttribute('data-plan-id');
            const planName = this.getAttribute('data-plan-name');
            const planPrice = this.getAttribute('data-plan-price');
            const planType = this.getAttribute('data-plan-type');

            // Update payment modal with selected plan data
            document.getElementById('paymentModalTitle').innerHTML = planName;
            document.getElementById('paymentModalPrice').textContent = planPrice;
            document.getElementById('selectedPlanId').value = planId;
            document.getElementById('selectedPlanType').value = planType;
            document.getElementById('selectedPlanPrice').value = planPrice;

            // Update payment button text
            const paymentButtonText = document.getElementById('paymentButtonText');
            if (planType === 'one_time') {
                paymentButtonText.textContent = `One Payment | ${planPrice}`;
                document.getElementById('paymentModalDuration').textContent = 'One time payment';
            } else {
                paymentButtonText.textContent = `Monthly | ${planPrice}`;
                document.getElementById('paymentModalDuration').textContent = 'Per month for 8 months';
            }

            // Update congrats modal
            document.getElementById('congratsPlanName').innerHTML = `Your ${planName}`;
            document.getElementById('congratsPlanDescription').textContent = planName;
        });
    });

    // Coupon code toggle (exactly like consultation flow)
    const toggleCouponBtn = document.getElementById('toggle-coupon-consultation');
    const couponDetails = document.getElementById('coupon-details-consultation');
    const promoCodeInput = document.getElementById('promo-code-consultation');
    const promoMessage = document.getElementById('promo-message-consultation');
    const discountField = document.getElementById('discount-consultation');
    const paymentModalPrice = document.getElementById('paymentModalPrice');
    const paymentButton = document.getElementById('paymentButton');
    const toggleCouponLink = document.getElementById('toggle-coupon-consultation');
    
    if (toggleCouponBtn && couponDetails) {
        toggleCouponBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (couponDetails.classList.contains('d-none')) {
                // Show coupon details
                couponDetails.classList.remove('d-none');
            } else {
                // Hide coupon details and reset coupon
                couponDetails.classList.add('d-none');
                
                // Reset coupon values
                promoCodeInput.value = '';
                promoMessage.textContent = '';
                promoMessage.className = 'form-text';
                discountField.value = '';
                
                // Reset price display to original price
                const originalPrice = parseFloat(paymentModalPrice.getAttribute('data-original-price') || '0');
                
                // Debug logging
                console.log('Coupon toggle reset:', {
                    dataOriginalPrice: paymentModalPrice.getAttribute('data-original-price'),
                    originalPrice: originalPrice,
                    isNaN: isNaN(originalPrice)
                });
                
                // Check if this is a monthly plan to preserve "/mth" suffix
                const isMonthlyActive = document.getElementById('paymentModalPlan').getAttribute('data-is-monthly') === 'true' || 
                                       (document.getElementById('monthlyPlanBtn') && document.getElementById('monthlyPlanBtn').classList.contains('active'));
                
                if (isMonthlyActive) {
                    paymentModalPrice.textContent = `A$${originalPrice.toFixed(2)}/mth`;
                } else {
                    paymentModalPrice.textContent = `A$${originalPrice.toFixed(2)}`;
                }
                
                // Reset button text
                if (isMonthlyActive) {
                    const monthlyPrice = paymentButton.getAttribute('data-monthly-price');
                    paymentButton.innerHTML = `Monthly | A$${monthlyPrice}/mth`;
                } else {
                    paymentButton.innerHTML = `One Payment | A$${originalPrice.toFixed(0)}`;
                }
                
                // Show payment form
                document.querySelector('#paymentForm .form-wrap').classList.remove('d-none');
                // Add required attribute back for paid plans
                document.getElementById('card-holder-name').setAttribute('required', 'required');
                
                // Reset toggle link text
                this.textContent = 'Add a Coupon Code';
            }
        });
    }

    // Apply coupon code (exactly like consultation flow)
    const applyPromoBtn = document.getElementById('apply-promo-code-consultation');
    
    if (applyPromoBtn) {
        applyPromoBtn.addEventListener('click', function() {
            const promoCode = promoCodeInput.value.trim();
            const planId = '{{ $planDetails?->id }}';
            const originalPrice = parseFloat(paymentModalPrice.getAttribute('data-original-price') || '0');
            
            // Determine consultation ID based on plan type
            let consultationId = null;
            const planType = getPlanTypeFromTitle(document.getElementById('paymentModalTitle').textContent);
            
            if (planType === 'powerplay') {
                // For Power Play, we need to get the 30-minute consultation ID
                consultationId = '{{ $consultations->where("time", 30)->first()?->id }}';
            } else if (planType === 'gameplan') {
                // For Game Plan, we need to get the 60-minute consultation ID
                consultationId = '{{ $consultations->where("time", 60)->first()?->id }}';
            }
            
            // Debug logging
            console.log('Coupon application:', {
                promoCode: promoCode,
                planId: planId,
                consultationId: consultationId,
                planType: planType,
                originalPrice: originalPrice,
                dataOriginalPrice: paymentModalPrice.getAttribute('data-original-price'),
                isNaN: isNaN(originalPrice)
            });
            
            if (!promoCode) {
                promoMessage.textContent = "Please enter a coupon code.";
                promoMessage.className = "form-text text-danger";
                return;
            }
            
            // Disable apply button during validation
            this.disabled = true;
            this.textContent = "Applying...";
            
            // Prepare request body
            const requestBody = {
                code: promoCode,
                plan_id: planId
            };
            
            // Add consultation_id if applicable
            if (consultationId) {
                requestBody.consultation_id = consultationId;
            }
            
            fetch('{{ route("validate.coupon.code") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(requestBody)
            })
            .then(response => response.json())
            .then(data => {
                if (data.valid) {
                    const discount = parseFloat(data.discount);
                    discountField.value = discount;
                    
                    let finalPrice = originalPrice;
                    let discountText = "";
                    
                    if (data.type === 'percentage') {
                        const discountAmount = (originalPrice * discount) / 100;
                        finalPrice = originalPrice - discountAmount;
                        discountText = `${discount}% off`;
                    } else if (data.type === 'fixed') {
                        finalPrice = Math.max(0, originalPrice - discount);
                        discountText = `$${discount} off`;
                    }
                    
                    // Debug logging for price calculation
                    console.log('Price calculation:', {
                        originalPrice: originalPrice,
                        discount: discount,
                        finalPrice: finalPrice,
                        isNaN: isNaN(finalPrice)
                    });
                    
                    // Update display
                    const isMonthlyActive = document.getElementById('paymentModalPlan').getAttribute('data-is-monthly') === 'true' || 
                                           (document.getElementById('monthlyPlanBtn') && document.getElementById('monthlyPlanBtn').classList.contains('active'));
                    
                    if (isNaN(finalPrice)) {
                        console.error('Final price is NaN, using original price');
                        paymentModalPrice.textContent = isMonthlyActive ? `A$${originalPrice.toFixed(2)}/mth` : `A$${originalPrice.toFixed(2)}`;
                    } else {
                        if (finalPrice <= 0) {
                            paymentModalPrice.textContent = 'A$0';
                        } else {
                            paymentModalPrice.textContent = isMonthlyActive ? `A$${finalPrice.toFixed(2)}/mth` : `A$${finalPrice.toFixed(2)}`;
                        }
                    }
                    
                    // Update toggle link text
                    toggleCouponLink.textContent = `Remove Coupon Code (${discountText})`;
                    
                    promoMessage.textContent = `Coupon applied successfully! ${discountText}`;
                    promoMessage.className = "form-text text-success";
                    
                    // If 100% discount, update button text and hide payment form
                    if (finalPrice <= 0) {
                        paymentButton.innerHTML = 'Next';
                        // Hide payment form for free plan
                        document.querySelector('#paymentForm .form-wrap').classList.add('d-none');
                        // Remove required attribute from card holder name for free plans
                        document.getElementById('card-holder-name').removeAttribute('required');
                    } else {
                        const isMonthlyActive = document.getElementById('paymentModalPlan').getAttribute('data-is-monthly') === 'true' || 
                                       (document.getElementById('monthlyPlanBtn') && document.getElementById('monthlyPlanBtn').classList.contains('active'));
                        if (isMonthlyActive) {
                            // Calculate discounted monthly price
                            const monthlyPrice = paymentButton.getAttribute('data-monthly-price');
                            const originalMonthlyPrice = parseFloat(monthlyPrice);
                            
                            // Debug logging
                            console.log('Monthly calculation:', {
                                monthlyPrice: monthlyPrice,
                                originalMonthlyPrice: originalMonthlyPrice,
                                discount: discount,
                                isNaN: isNaN(originalMonthlyPrice)
                            });
                            
                            if (isNaN(originalMonthlyPrice)) {
                                console.error('Monthly price is NaN, using fallback');
                                paymentButton.innerHTML = `Monthly | A$${monthlyPrice}/mth`;
                            } else {
                                let discountedMonthlyPrice;
                                
                                if (data.type === 'percentage') {
                                    const monthlyDiscountAmount = (originalMonthlyPrice * discount) / 100;
                                    discountedMonthlyPrice = originalMonthlyPrice - monthlyDiscountAmount;
                                } else if (data.type === 'fixed') {
                                    discountedMonthlyPrice = Math.max(0, originalMonthlyPrice - discount);
                                } else {
                                    discountedMonthlyPrice = originalMonthlyPrice;
                                }
                                
                                paymentButton.innerHTML = `Monthly | A$${discountedMonthlyPrice.toFixed(2)}/mth`;
                            }
                        } else {
                            paymentButton.innerHTML = `One Payment | A$${finalPrice.toFixed(0)}`;
                        }
                        // Show payment form for paid plan
                        document.querySelector('#paymentForm .form-wrap').classList.remove('d-none');
                        // Add required attribute back for paid plans
                        document.getElementById('card-holder-name').setAttribute('required', 'required');
                    }
                } else {
                    promoMessage.textContent = data.message || "Invalid coupon code.";
                    promoMessage.className = "form-text text-danger";
                    
                    // Reset values
                    discountField.value = "";
                    const resetPrice = parseFloat(paymentModalPrice.getAttribute('data-original-price') || '0');
                    
                    // Check if this is a monthly plan to preserve "/mth" suffix
                    const isMonthlyActive = document.getElementById('paymentModalPlan').getAttribute('data-is-monthly') === 'true' || 
                                       (document.getElementById('monthlyPlanBtn') && document.getElementById('monthlyPlanBtn').classList.contains('active'));
                    
                    if (isMonthlyActive) {
                        paymentModalPrice.textContent = `A$${resetPrice.toFixed(2)}/mth`;
                    } else {
                        paymentModalPrice.textContent = `A$${resetPrice.toFixed(2)}`;
                    }
                    
                    toggleCouponLink.textContent = "Add a Coupon Code";
                    
                    if (isMonthlyActive) {
                        const monthlyPrice = paymentButton.getAttribute('data-monthly-price');
                        paymentButton.innerHTML = `Monthly | A$${monthlyPrice}/mth`;
                    } else {
                        paymentButton.innerHTML = `One Payment | A$${resetPrice.toFixed(0)}`;
                    }
                    
                    // Show payment form
                    document.querySelector('#paymentForm .form-wrap').classList.remove('d-none');
                    // Add required attribute back for paid plans
                    document.getElementById('card-holder-name').setAttribute('required', 'required');
                }
                
                // Re-enable apply button
                this.disabled = false;
                this.textContent = "Apply";
            })
            .catch(error => {
                console.error('Error:', error);
                promoMessage.textContent = "Error applying coupon code. Please try again.";
                promoMessage.className = "form-text text-danger";
                
                // Re-enable apply button
                this.disabled = false;
                this.textContent = "Apply";
            });
        });
    }

    // Handle payment button click (exactly like consultation flow)
    $('#paymentButton').on('click', function(e) {
            e.preventDefault();
        processPlanPayment();
    });

    // Handle "What's included" link click to scroll to plan-inclusion-section
    const whatsIncludedLinks = document.querySelectorAll('.whats-included-link, .whats-in-one-on-one-link');
    whatsIncludedLinks.forEach(link => {
        link.addEventListener('click', function() {
            // Wait for modal to close, then scroll to plan-inclusion-section
            setTimeout(function() {
                const planInclusionSection = document.querySelector('.plan-inclusion-section');
                if (planInclusionSection) {
                    planInclusionSection.scrollIntoView({ 
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }, 300); // Small delay to ensure modal is closed
        });
    });

    // Prevent page refresh when congrats modal is open
    $('#congratsModalPlan').on('shown.bs.modal', function () {
        // Add beforeunload event listener when modal opens
        window.addEventListener('beforeunload', preventRefreshWhenCongratsOpen);
    });

    $('#congratsModalPlan').on('hidden.bs.modal', function () {
        // Remove beforeunload event listener when modal closes
        window.removeEventListener('beforeunload', preventRefreshWhenCongratsOpen);
    });

    // Handle calendar booking modal close - check questionnaire status and redirect
    $('#calendarBookingModalPlan').on('hidden.bs.modal', function () {
        checkQuestionnaireStatusAndRedirect(currentPaymentId);
    });

    // Prevent page refresh when calendar booking modal is open
    $('#calendarBookingModalPlan').on('shown.bs.modal', function () {
        // Add beforeunload event listener when modal opens
        window.addEventListener('beforeunload', preventRefreshWhenCalendarOpen);
    });

    $('#calendarBookingModalPlan').on('hidden.bs.modal', function () {
        // Remove beforeunload event listener when modal closes
        window.removeEventListener('beforeunload', preventRefreshWhenCalendarOpen);
    });

});

// Function to prevent page refresh when congrats modal is open (moved outside DOMContentLoaded for global access)
function preventRefreshWhenCongratsOpen(event) {
    const message = 'Please complete your plan purchase process first before leaving this page.';
    event.preventDefault();
    event.returnValue = message;
    return message;
}

// Function to prevent page refresh when calendar is open (moved outside DOMContentLoaded for global access)
function preventRefreshWhenCalendarOpen(event) {
    const message = 'Please complete your consultation booking first before leaving this page.';
    event.preventDefault();
    event.returnValue = message;
    return message;
}

// Check questionnaire status and redirect accordingly (moved outside DOMContentLoaded for global access)
function checkQuestionnaireStatusAndRedirect(paymentId = null) {
    const userId = '{{ Auth::user()->id ?? "" }}';
    let url = '{{ route("front.consultation.questionnaire.status") }}';
    
    // Build query parameters
    const params = new URLSearchParams();
    if (userId) params.append('user_id', userId);
    if (paymentId) params.append('payment_id', paymentId);
    
    if (params.toString()) {
        url += '?' + params.toString();
    }
    
    fetch(url, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.redirect_url) {
            // Redirect to appropriate page based on questionnaire completion
            window.location.href = data.redirect_url;
        } else if (data.requires_auth) {
            // User needs to login
            $('#signupModalathlete').modal('show');
        } else {
            console.error('Error checking questionnaire status:', data.message);
            // Fallback: redirect to home page
            window.location.href = '{{ route("front.index") }}';
        }
    })
    .catch(error => {
        console.error('Error checking questionnaire status:', error);
        // Fallback: redirect to home page
        window.location.href = '{{ route("front.index") }}';
    });
}

// Global payment processing state
let isPaymentProcessing = false;
let currentPaymentId = null; // Store current payment ID for questionnaire redirect

// Function to prevent page reload during payment processing
function preventPageReload() {
    window.addEventListener('beforeunload', handleBeforeUnload);
}

// Function to prevent modal close during payment processing
function preventModalClose() {
    // Prevent modal close via hide.bs.modal event
    $('#paymentModalPlan').off('hide.bs.modal').on('hide.bs.modal', function(e) {
        if (isPaymentProcessing) {
            e.preventDefault();
            e.stopPropagation();
            return false;
        }
    });
    
    // Prevent modal close via ESC key
    $(document).off('keydown.paymentModal').on('keydown.paymentModal', function(e) {
        if (isPaymentProcessing && e.keyCode === 27) { // ESC key
            e.preventDefault();
            e.stopPropagation();
            return false;
        }
    });
    
    // Prevent modal close via backdrop click - use Bootstrap's backdrop event
    $('#paymentModalPlan').off('click.dismiss.bs.modal').on('click.dismiss.bs.modal', function(e) {
        if (isPaymentProcessing && e.target === this) {
            e.preventDefault();
            e.stopPropagation();
            return false;
        }
    });
    
    // Additional backdrop click prevention
    $('#paymentModalPlan').off('click.paymentModal').on('click.paymentModal', function(e) {
        if (isPaymentProcessing && e.target === this) {
            e.preventDefault();
            e.stopPropagation();
            return false;
        }
    });
    
    // Disable close button during payment processing
    $('#paymentModalPlan .btn-close, #paymentModalPlan .close').off('click.paymentModal').on('click.paymentModal', function(e) {
        if (isPaymentProcessing) {
            e.preventDefault();
            e.stopPropagation();
            return false;
        }
    });
    
    // Set modal data attributes to prevent backdrop dismissal
    if (isPaymentProcessing) {
        $('#paymentModalPlan').attr('data-bs-backdrop', 'static');
        $('#paymentModalPlan').attr('data-bs-keyboard', 'false');
        
        // Also update the modal instance configuration if it exists
        const modalElement = document.getElementById('paymentModalPlan');
        if (modalElement && bootstrap.Modal.getInstance(modalElement)) {
            const modalInstance = bootstrap.Modal.getInstance(modalElement);
            modalInstance._config.backdrop = 'static';
            modalInstance._config.keyboard = false;
        }
    }
    
    // Add visual indicators that modal cannot be closed
    if (isPaymentProcessing) {
        $('#paymentModalPlan').addClass('payment-processing');
        $('#paymentModalPlan .btn-close, #paymentModalPlan .close').addClass('disabled').css('opacity', '0.5');
        $('#paymentModalPlan .modal-backdrop').css('pointer-events', 'none');
    }
}

// Function to handle beforeunload event
function handleBeforeUnload(e) {
    if (isPaymentProcessing) {
        e.preventDefault();
        e.returnValue = 'Payment is being processed. Are you sure you want to leave? This may cause issues with your payment.';
        return e.returnValue;
    }
}

// Function to reset payment processing state
function resetPaymentProcessingState() {
    isPaymentProcessing = false;
    window.removeEventListener('beforeunload', handleBeforeUnload);
    
    // Remove all modal close prevention event listeners
    $('#paymentModalPlan').off('hide.bs.modal');
    $(document).off('keydown.paymentModal');
    $('#paymentModalPlan').off('click.dismiss.bs.modal');
    $('#paymentModalPlan').off('click.paymentModal');
    $('#paymentModalPlan .btn-close, #paymentModalPlan .close').off('click.paymentModal');
    
    // Restore modal data attributes
    $('#paymentModalPlan').attr('data-bs-backdrop', 'true');
    $('#paymentModalPlan').attr('data-bs-keyboard', 'true');
    
    // Also restore the modal instance configuration if it exists
    const modalElement = document.getElementById('paymentModalPlan');
    if (modalElement && bootstrap.Modal.getInstance(modalElement)) {
        const modalInstance = bootstrap.Modal.getInstance(modalElement);
        modalInstance._config.backdrop = true;
        modalInstance._config.keyboard = true;
    }
    
    // Remove visual indicators
    $('#paymentModalPlan').removeClass('payment-processing');
    $('#paymentModalPlan .btn-close, #paymentModalPlan .close').removeClass('disabled').css('opacity', '1');
    $('#paymentModalPlan .modal-backdrop').css('pointer-events', 'auto');
}

// Function to process plan payment (single request to backend)
function processPlanPayment() {
    const planType = document.getElementById('paymentModalTitle').textContent;
    const originalPrice = parseFloat(document.getElementById('paymentModalPrice').getAttribute('data-original-price') || '0');
    const finalPrice = document.getElementById('paymentModalPrice').textContent.replace(/[A$,\s]/g, '');
    const email = '{{ Auth::user()->email ?? "" }}';
    const couponCode = $('#promo-code-consultation').val().trim();
    const cardHolderName = $('#card-holder-name').val();
    
    // Set payment processing state
    isPaymentProcessing = true;
    
    // Disable button to prevent double submission
    $('#paymentButton').prop('disabled', true).text('Processing...');
    
    // Prevent page reload and modal close during payment processing
    preventPageReload();
    preventModalClose();
    
    // Check if this is a free plan (final price is 0 or less)
    if (parseFloat(finalPrice) <= 0) {
        // Free plan - no payment required, skip payment method validation
        processFreePlanPurchase();
        return;
    }
    
    // For paid plans, validate card holder name
    if (!cardHolderName.trim()) {
        alert('Please enter the name on card.');
        $('#paymentButton').prop('disabled', false).text('One Payment | A$' + finalPrice);
        resetPaymentProcessingState();
        return;
    }

    // Send single request to backend with card details
    sendPlanRequestWithCardDetails();
}

// Send plan request to backend with payment method (single request)
function sendPlanRequestWithCardDetails() {
    const planType = document.getElementById('paymentModalTitle').textContent;
    const originalPrice = parseFloat(document.getElementById('paymentModalPrice').getAttribute('data-original-price') || '0');
    const finalPrice = document.getElementById('paymentModalPrice').textContent.replace(/[A$,\s]/g, '');
    const email = '{{ Auth::user()->email ?? "" }}';
    const couponCode = $('#promo-code-consultation').val().trim();
    const cardHolderName = $('#card-holder-name').val() || '{{ Auth::user()->name ?? "" }}';
    const isMonthly = document.getElementById('paymentModalPlan').getAttribute('data-is-monthly') === 'true' || 
                     (document.getElementById('monthlyPlanBtn') && document.getElementById('monthlyPlanBtn').classList.contains('active'));
    
    // Check if this is a free plan (final price is 0 or less)
    if (parseFloat(finalPrice) <= 0) {
        // Free plan - send request without payment method
        sendFreePlanRequest();
        return;
    }
    
    // For paid plans, validate Stripe Elements
    if (!cardNumberElement || !cardExpiryElement || !cardCvcElement) {
        alert('Payment form is not ready. Please try again.');
        $('#paymentButton').prop('disabled', false).text(isMonthly ? 'Monthly | A$' + finalPrice + '/mth' : 'One Payment | A$' + finalPrice);
        resetPaymentProcessingState();
        return;
    }
    
    // Create payment method using Stripe Elements
    stripe.createPaymentMethod({
        type: 'card',
        card: cardNumberElement,
        billing_details: {
            name: cardHolderName,
            email: email
        }
    }).then(function(result) {
        if (result.error) {
            // Handle payment method creation error
            $('#paymentButton').prop('disabled', false).text(isMonthly ? 'Monthly | A$' + finalPrice + '/mth' : 'One Payment | A$' + finalPrice);
            alert('Payment method error: ' + result.error.message);
            resetPaymentProcessingState();
        } else {
            // Payment method created successfully, send to backend
            sendPlanRequestToBackend(result.paymentMethod.id);
        }
    });
}

// Send plan request to backend (for paid plans)
function sendPlanRequestToBackend(paymentMethodId) {
    const planType = document.getElementById('paymentModalTitle').textContent;
    const originalPrice = parseFloat(document.getElementById('paymentModalPrice').getAttribute('data-original-price') || '0');
    const finalPrice = document.getElementById('paymentModalPrice').textContent.replace(/[A$,\s]/g, '');
    const email = '{{ Auth::user()->email ?? "" }}';
    const couponCode = $('#promo-code-consultation').val().trim();
    const cardHolderName = $('#card-holder-name').val() || '{{ Auth::user()->name ?? "" }}';
    const isMonthly = document.getElementById('paymentModalPlan').getAttribute('data-is-monthly') === 'true' || 
                     (document.getElementById('monthlyPlanBtn') && document.getElementById('monthlyPlanBtn').classList.contains('active'));
    
    $.ajax({
        url: '{{ route("process.plan.purchase") }}',
        method: 'POST',
        data: {
            plan_id: '{{ $planDetails?->id }}',
            plan_type: getPlanTypeFromTitle(planType),
            price: originalPrice,
            final_price: parseFloat(finalPrice),
            name: cardHolderName,
            email: email,
            phone: '{{ Auth::user()->phone ?? "" }}',
            coupon_code: couponCode,
            is_monthly: isMonthly,
            payment_method_id: paymentMethodId,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                // Store payment ID for questionnaire redirect
                currentPaymentId = response.data.payment_id;
                
                // Reset payment processing state
                resetPaymentProcessingState();
                // Hide payment modal programmatically
                isPaymentModalClosingProgrammatically = true;
                $('#paymentModalPlan').modal('hide');
                // Update congrats modal content based on plan type
                updateCongratsModal(response.data.plan_type, response.data.has_consultation);
                // Show congrats modal
                $('#congratsModalPlan').modal('show');
            } else if (response.requires_action) {
                // Handle 3D Secure authentication
                const clientSecret = response.client_secret || response.payment_intent_client_secret;
                handlePaymentAction(clientSecret, response.subscription_id);
            } else if (response.requires_confirmation) {
                // Handle payment confirmation
                const clientSecret = response.client_secret || response.payment_intent_client_secret;
                handlePaymentConfirmation(clientSecret, response.subscription_id);
            } else {
                // Handle error
                const isMonthly = document.getElementById('paymentModalPlan').getAttribute('data-is-monthly') === 'true' || 
                     (document.getElementById('monthlyPlanBtn') && document.getElementById('monthlyPlanBtn').classList.contains('active'));
                const finalPrice = document.getElementById('paymentModalPrice').textContent.replace(/[A$,\s]/g, '');
                $('#paymentButton').prop('disabled', false).text(isMonthly ? 'Monthly | A$' + finalPrice + '/mth' : 'One Payment | A$' + finalPrice);
                alert('Payment failed: ' + (response.message || 'Unknown error'));
            }
        },
        error: function(xhr) {
            const isMonthly = document.getElementById('paymentModalPlan').getAttribute('data-is-monthly') === 'true' || 
                     (document.getElementById('monthlyPlanBtn') && document.getElementById('monthlyPlanBtn').classList.contains('active'));
            const finalPrice = document.getElementById('paymentModalPrice').textContent.replace(/[A$,\s]/g, '');
            $('#paymentButton').prop('disabled', false).text(isMonthly ? 'Monthly | A$' + finalPrice + '/mth' : 'One Payment | A$' + finalPrice);
            const errorMessage = xhr.responseJSON?.message || 'Payment failed. Please try again.';
            alert('Payment failed: ' + errorMessage);
            resetPaymentProcessingState();
        }
    });
}

// Send free plan request to backend (no payment method required)
function sendFreePlanRequest() {
    const planType = document.getElementById('paymentModalTitle').textContent;
    const originalPrice = parseFloat(document.getElementById('paymentModalPrice').getAttribute('data-original-price') || '0');
    const finalPrice = document.getElementById('paymentModalPrice').textContent.replace(/[A$,\s]/g, '');
    const email = '{{ Auth::user()->email ?? "" }}';
    const couponCode = $('#promo-code-consultation').val().trim();
    const cardHolderName = $('#card-holder-name').val() || '{{ Auth::user()->name ?? "" }}';
    const isMonthly = document.getElementById('paymentModalPlan').getAttribute('data-is-monthly') === 'true' || 
                     (document.getElementById('monthlyPlanBtn') && document.getElementById('monthlyPlanBtn').classList.contains('active'));
    
    console.log('Sending free plan request:', {
        planType: planType,
        originalPrice: originalPrice,
        finalPrice: finalPrice,
        isMonthly: isMonthly,
        couponCode: couponCode
    });
    
    $.ajax({
        url: '{{ route("process.plan.purchase") }}',
        method: 'POST',
        data: {
            plan_id: '{{ $planDetails?->id }}',
            plan_type: getPlanTypeFromTitle(planType),
            price: originalPrice,
            final_price: parseFloat(finalPrice),
            name: cardHolderName,
            email: email,
            phone: '{{ Auth::user()->phone ?? "" }}',
            coupon_code: couponCode,
            is_monthly: isMonthly,
            payment_method_id: null, // No payment method for free plans
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            console.log('Free plan response:', response);
            if (response.success) {
                // Store payment ID for questionnaire redirect
                currentPaymentId = response.data.payment_id;
                
                // Reset payment processing state
                resetPaymentProcessingState();
                // Hide payment modal programmatically
                isPaymentModalClosingProgrammatically = true;
                $('#paymentModalPlan').modal('hide');
                // Update congrats modal content based on plan type
                updateCongratsModal(response.data.plan_type, response.data.has_consultation);
                // Show congrats modal
                $('#congratsModalPlan').modal('show');
            } else {
                // Handle error
                $('#paymentButton').prop('disabled', false).text('Next');
                alert('Plan purchase failed: ' + (response.message || 'Unknown error'));
                resetPaymentProcessingState();
            }
        },
        error: function(xhr) {
            console.error('Free plan error:', xhr);
            $('#paymentButton').prop('disabled', false).text('Next');
            const errorMessage = xhr.responseJSON?.message || 'Plan purchase failed. Please try again.';
            alert('Plan purchase failed: ' + errorMessage);
            resetPaymentProcessingState();
        }
    });
}

// Process free plan purchase (single request)
function processFreePlanPurchase() {
    // Free plan purchase - send request without payment method
    sendFreePlanRequest();
}

// Handle payment action (3D Secure)
function handlePaymentAction(clientSecret, subscriptionId) {
    // Check if this is a subscription (has subscriptionId) or one-time payment
    if (subscriptionId) {
        // For subscriptions, use confirmPayment
        stripe.confirmPayment({
            clientSecret: clientSecret,
            confirmParams: {
                return_url: window.location.origin + '/payment-success',
            },
        }).then(function(result) {
            if (result.error) {
                const finalPrice = document.getElementById('paymentModalPrice').textContent.replace(/[A$,\s]/g, '');
                const isMonthly = document.getElementById('paymentModalPlan').getAttribute('data-is-monthly') === 'true' || 
                     (document.getElementById('monthlyPlanBtn') && document.getElementById('monthlyPlanBtn').classList.contains('active'));
                $('#paymentButton').prop('disabled', false).text(isMonthly ? 'Monthly | A$' + finalPrice + '/mth' : 'One Payment | A$' + finalPrice);
                alert('Payment failed: ' + result.error.message);
            } else {
                // Payment succeeded after 3D Secure
                isPaymentModalClosingProgrammatically = true;
                $('#paymentModalPlan').modal('hide');
                updateCongratsModal('main', true);
                $('#congratsModalPlan').modal('show');
            }
        });
    } else {
        // For one-time payments, use handleCardAction
        stripe.handleCardAction(clientSecret).then(function(result) {
            if (result.error) {
                const finalPrice = document.getElementById('paymentModalPrice').textContent.replace(/[A$,\s]/g, '');
                $('#paymentButton').prop('disabled', false).text('One Payment | A$' + finalPrice);
                alert('Payment failed: ' + result.error.message);
            } else {
                // Payment succeeded after 3D Secure
                isPaymentModalClosingProgrammatically = true;
                $('#paymentModalPlan').modal('hide');
                updateCongratsModal('main', true);
                $('#congratsModalPlan').modal('show');
            }
        });
    }
}

// Handle payment confirmation
function handlePaymentConfirmation(clientSecret, subscriptionId) {
    // Check if this is a subscription (has subscriptionId) or one-time payment
    if (subscriptionId) {
        // For subscriptions, use confirmPayment
        stripe.confirmPayment({
            clientSecret: clientSecret,
            confirmParams: {
                return_url: window.location.origin + '/payment-success',
            },
        }).then(function(result) {
            if (result.error) {
                const finalPrice = document.getElementById('paymentModalPrice').textContent.replace(/[A$,\s]/g, '');
                const isMonthly = document.getElementById('paymentModalPlan').getAttribute('data-is-monthly') === 'true' || 
                     (document.getElementById('monthlyPlanBtn') && document.getElementById('monthlyPlanBtn').classList.contains('active'));
                $('#paymentButton').prop('disabled', false).text(isMonthly ? 'Monthly | A$' + finalPrice + '/mth' : 'One Payment | A$' + finalPrice);
                alert('Payment failed: ' + result.error.message);
            } else {
                // Payment succeeded after confirmation
                isPaymentModalClosingProgrammatically = true;
                $('#paymentModalPlan').modal('hide');
                updateCongratsModal('main', true);
                $('#congratsModalPlan').modal('show');
            }
        });
    } else {
        // For one-time payments, use confirmCardPayment
        stripe.confirmCardPayment(clientSecret).then(function(result) {
            if (result.error) {
                const finalPrice = document.getElementById('paymentModalPrice').textContent.replace(/[A$,\s]/g, '');
                $('#paymentButton').prop('disabled', false).text('One Payment | A$' + finalPrice);
                alert('Payment failed: ' + result.error.message);
            } else {
                // Payment succeeded after confirmation
                isPaymentModalClosingProgrammatically = true;
                $('#paymentModalPlan').modal('hide');
                updateCongratsModal('main', true);
                $('#congratsModalPlan').modal('show');
            }
        });
    }
}

// Helper function to determine plan type from modal title (moved outside DOMContentLoaded)
function getPlanTypeFromTitle(title) {
    if (title.includes('Power Play')) {
        return 'powerplay';
    } else if (title.includes('Game Plan')) {
        return 'gameplan';
    } else {
        return 'main';
    }
}

// Function to update congrats modal content based on plan type (moved outside DOMContentLoaded)
function updateCongratsModal(planType, hasConsultation) {
    const planName = '{!! $planDetails?->name !!}';
    const congratsPlanName = document.getElementById('congratsPlanName');
    const congratsPlanDescription = document.getElementById('congratsPlanDescription');
    const powerPlayGamePlanContent = document.getElementById('powerPlayGamePlanContent');
    const normalPlanContent = document.getElementById('normalPlanContent');
    
    // Hide both content sections first
    if (powerPlayGamePlanContent) powerPlayGamePlanContent.style.display = 'none';
    if (normalPlanContent) normalPlanContent.style.display = 'none';
    
    if (planType === 'powerplay') {
        congratsPlanName.innerHTML = `<strong>Your Power Play</strong><br>${planName} + 30 min Consultation`;
        congratsPlanDescription.textContent = 'We\'ll send you an email to book your consultation. You will need to complete your questionnaire prior then Kerry will start working on your personalised nutrition plan and it will appear in your web app, as soon as it\'s ready.';
        // Show Book a time button for Power Play
        if (powerPlayGamePlanContent) powerPlayGamePlanContent.style.display = 'block';
    } else if (planType === 'gameplan') {
        congratsPlanName.innerHTML = `<strong>Your Game Plan</strong><br>${planName} + 60 min Consultation`;
        congratsPlanDescription.textContent = 'We\'ll send you an email to book your consultation. You will need to complete your questionnaire prior then Kerry will start working on your personalised nutrition plan and it will appear in your web app, as soon as it\'s ready.';
        // Show Book a time button for Game Plan
        if (powerPlayGamePlanContent) powerPlayGamePlanContent.style.display = 'block';
    } else {
        congratsPlanName.innerHTML = `<strong>Your ${planName}</strong><br>${planName}`;
        congratsPlanDescription.textContent = 'You will need to complete your questionnaire prior then Kerry will start working on your personalised nutrition plan and it will appear in your web app, as soon as it\'s ready.';
        // Show questionnaire button for normal plans
        if (normalPlanContent) normalPlanContent.style.display = 'block';
    }
}

    // Complete questionnaire button
    const completeQuestionnaireBtn = document.getElementById('completeQuestionnaireBtn');
    if (completeQuestionnaireBtn) {
        completeQuestionnaireBtn.addEventListener('click', function() {
            // Temporarily remove page refresh prevention to avoid confirmation dialog
            window.removeEventListener('beforeunload', preventRefreshWhenCongratsOpen);
            window.removeEventListener('beforeunload', preventRefreshWhenCalendarOpen);
            
            // Call the questionnaire function
            checkQuestionnaireStatusAndRedirect(currentPaymentId);
        });
    }

    // Book a time button for Power Play/Game Plan
    const bookTimeBtnPlan = document.getElementById('book-time-btn-plan');
    if (bookTimeBtnPlan) {
        bookTimeBtnPlan.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Get consultation time from stored data or determine from plan type
            const planType = getPlanTypeFromTitle(document.getElementById('congratsPlanName').textContent);
            const consultationTime = (planType === 'powerplay') ? 30 : 60; // Default to 30 for powerplay, 60 for gameplan
            
            // Set the appropriate calendar URL based on consultation time
            const calendarIframe = document.getElementById('calendar-iframe-plan');
            if (consultationTime === 30) {
                // 30-minute consultation calendar
                calendarIframe.src = 'https://calendar.google.com/calendar/appointments/schedules/AcZssZ06hsdgy_YQNWOYK-jUrwBejSClhQehI3ZTeUgD7TKX7PCOZV5xyDfcIOTMPC2YImB4zCr92BYJ?gv=true';
            } else {
                // Other consultation types calendar
                calendarIframe.src = 'https://calendar.google.com/calendar/appointments/schedules/AcZssZ0J7QhuvkeNW899AvG5ODe7rGS92oCSl9nE5Gb4LDh_1SlNDXRaIloRBv9w7ftzOzf1DiAB93li?gv=true';
            }

            // Hide congrats modal
            $('#congratsModalPlan').modal('hide');

            // Show calendar booking modal
            $('#calendarBookingModalPlan').modal('show');
        });
    }

    // Handle "Get Started" button clicks for plan purchase
    const planGetStartedBtns = document.querySelectorAll('.plan-get-started-btn');
    planGetStartedBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const isAuthenticated = {{ Auth::check() ? 'true' : 'false' }};
            
            if (!isAuthenticated) {
                // Store plan data for after login
                const planData = {
                    type: this.getAttribute('data-plan-type'),
                    price: this.getAttribute('data-plan-price'),
                    monthlyPrice: this.getAttribute('data-monthly-price'),
                    planName: '{!! $planDetails?->name !!}',
                    planId: this.getAttribute('data-plan-id') || '{{ $planDetails?->id }}',
                    isMonthlyActive: document.getElementById('monthlyPlanBtn') ? document.getElementById('monthlyPlanBtn').classList.contains('active') : false
                };
                
                // Store in sessionStorage
                sessionStorage.setItem('pendingPlanPurchase', JSON.stringify(planData));
                window.pendingPlanPurchase = planData;
                
                // Mark that this login was triggered by plan purchase
                sessionStorage.setItem('loginTriggeredByPlanPurchase', 'true');
                
                // Close any existing modals first
                $('.modal').modal('hide');
                
                // Wait for existing modal to close, then show signup modal
                setTimeout(() => {
                    // Initialize signup modal content before showing
                    initializeSignupModal();
                    // Show login/signup modal
                    $('#signupModalathlete').modal('show');
                }, 300);
                return;
            }
            
            // User is authenticated, show payment modal
            showPlanPaymentModal(this);
        });
    });

    // Function to show payment modal for plans
    function showPlanPaymentModal(button, storedPlanData = null) {
        const planType = button.getAttribute('data-plan-type');
        const oneTimePrice = button.getAttribute('data-plan-price');
        const monthlyPrice = button.getAttribute('data-monthly-price');
        const planId = button.getAttribute('data-plan-id') || '{{ $planDetails?->id }}';
        
        // Check if user already has this plan before showing payment modal
        checkExistingPlan(planId, planType, oneTimePrice, monthlyPrice, button, storedPlanData);
    }

    // Function to check if user already has the plan
    function checkExistingPlan(planId, planType, oneTimePrice, monthlyPrice, button, storedPlanData = null) {
        // Show loading state
        const originalText = button.textContent;
        button.textContent = 'Checking...';
        button.disabled = true;

        // Make API call to check existing plan
        fetch('/check-existing-plan', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                plan_id: planId
            })
        })
        .then(response => response.json())
        .then(data => {
            // Reset button state
            button.textContent = originalText;
            button.disabled = false;

            if (data.success && !data.has_plan) {
                // User doesn't have the plan, proceed with payment modal
                proceedWithPaymentModal(planType, oneTimePrice, monthlyPrice, button, storedPlanData);
            } else if (data.has_plan) {
                // User already has the plan, show error message
                alert(data.message);
                return;
            } else {
                // Other error (like not authenticated)
                if (data.requires_auth) {
                    // Handle authentication requirement
                    handleAuthenticationRequired(button, storedPlanData);
                } else {
                    alert(data.message || 'An error occurred. Please try again.');
                }
            }
        })
        .catch(error => {
            // Reset button state
            button.textContent = originalText;
            button.disabled = false;
            
            console.error('Error checking existing plan:', error);
            alert('An error occurred while checking your plan status. Please try again.');
        });
    }

    // Function to proceed with payment modal after validation
    function proceedWithPaymentModal(planType, oneTimePrice, monthlyPrice, button, storedPlanData = null) {
        // Check which pricing type is currently active, or use stored preference
        let isMonthlyActive;
        let finalOneTimePrice = oneTimePrice;
        let finalMonthlyPrice = monthlyPrice;
        
        if (storedPlanData) {
            // Use stored pricing preference and prices
            isMonthlyActive = storedPlanData.isMonthlyActive;
            finalOneTimePrice = storedPlanData.price;
            finalMonthlyPrice = storedPlanData.monthlyPrice;
            
            // Update UI to reflect stored preference
            const monthlyBtn = document.getElementById('monthlyPlanBtn');
            const oneTimeBtn = document.getElementById('oneTimePlanBtn');
            
            if (monthlyBtn && oneTimeBtn) {
                if (isMonthlyActive) {
                    monthlyBtn.classList.add('active');
                    oneTimeBtn.classList.remove('active');
                } else {
                    monthlyBtn.classList.remove('active');
                    oneTimeBtn.classList.add('active');
                }
            }
        } else {
            // Use current UI state
            const monthlyBtn = document.getElementById('monthlyPlanBtn');
            isMonthlyActive = monthlyBtn ? monthlyBtn.classList.contains('active') : false;
        }
        
        // Close any existing modals first
        $('.modal').modal('hide');
        
        // Wait for existing modal to close, then show payment modal
        setTimeout(() => {
            // Store the monthly preference in the payment modal for later use
            document.getElementById('paymentModalPlan').setAttribute('data-is-monthly', isMonthlyActive);
            
            // Update payment modal content based on plan type and pricing
            if (planType === 'main') {
                document.getElementById('paymentModalTitle').innerHTML = '{!! $planDetails?->name !!}';
                document.getElementById('paymentModalSubtitle').innerHTML = '{!! $planDetails?->name !!}';
                document.getElementById('paymentModalPrice').textContent = isMonthlyActive ? 'A$' + finalMonthlyPrice + '/mth' : 'A$' + finalOneTimePrice;
                document.getElementById('paymentModalPrice').setAttribute('data-original-price', isMonthlyActive ? finalMonthlyPrice : finalOneTimePrice);
                document.getElementById('paymentModalDuration').textContent = isMonthlyActive ? 'Over {{ $months }} Months' : 'One time payment';
                document.getElementById('paymentButton').textContent = isMonthlyActive ? 'Monthly | A$' + finalMonthlyPrice + '/mth' : 'One Payment | A$' + finalOneTimePrice;
                document.getElementById('paymentButton').setAttribute('data-monthly-price', finalMonthlyPrice);
            } else if (planType === 'powerplay') {
                document.getElementById('paymentModalTitle').textContent = 'Power Play';
                document.getElementById('paymentModalSubtitle').innerHTML = '{!! $planDetails?->name !!} + 30 min Consult with Extreme Sports Dietitian Kerry O\'Byran';
                document.getElementById('paymentModalPrice').textContent = isMonthlyActive ? 'A$' + finalMonthlyPrice + '/mth' : 'A$' + finalOneTimePrice;
                document.getElementById('paymentModalPrice').setAttribute('data-original-price', isMonthlyActive ? finalMonthlyPrice : finalOneTimePrice);
                document.getElementById('paymentModalDuration').textContent = isMonthlyActive ? 'Over {{ $months }} Months' : 'One time payment';
                document.getElementById('paymentButton').textContent = isMonthlyActive ? 'Monthly | A$' + finalMonthlyPrice + '/mth' : 'One Payment | A$' + finalOneTimePrice;
                document.getElementById('paymentButton').setAttribute('data-monthly-price', finalMonthlyPrice);
            } else if (planType === 'gameplan') {
                document.getElementById('paymentModalTitle').textContent = 'Game Plan';
                document.getElementById('paymentModalSubtitle').innerHTML = '{!! $planDetails?->name !!} + 60 min Consult with Kerry to cover Nutrition AND Training Advise';
                document.getElementById('paymentModalPrice').textContent = isMonthlyActive ? 'A$' + finalMonthlyPrice + '/mth' : 'A$' + finalOneTimePrice;
                document.getElementById('paymentModalPrice').setAttribute('data-original-price', isMonthlyActive ? finalMonthlyPrice : finalOneTimePrice);
                document.getElementById('paymentModalDuration').textContent = isMonthlyActive ? 'Over {{ $months }} Months' : 'One time payment';
                document.getElementById('paymentButton').textContent = isMonthlyActive ? 'Monthly | A$' + finalMonthlyPrice + '/mth' : 'One Payment | A$' + finalOneTimePrice;
                document.getElementById('paymentButton').setAttribute('data-monthly-price', finalMonthlyPrice);
            }
            
            // Reset coupon fields when modal is shown
            document.getElementById('promo-code-consultation').value = '';
            document.getElementById('discount-consultation').value = '';
            document.getElementById('promo-message-consultation').textContent = '';
            document.getElementById('promo-message-consultation').className = 'form-text';
            document.getElementById('toggle-coupon-consultation').textContent = 'Add a Coupon Code';
            document.getElementById('coupon-details-consultation').classList.add('d-none');
            
            // Ensure required attribute is set for card holder name
            document.getElementById('card-holder-name').setAttribute('required', 'required');
            
            // Show payment modal
            $('#paymentModalPlan').modal('show');
        }, 300);
    }

    // Function to handle authentication requirement
    function handleAuthenticationRequired(button, storedPlanData = null) {
        // Store plan data for after login
        const planData = {
            type: button.getAttribute('data-plan-type'),
            price: button.getAttribute('data-plan-price'),
            monthlyPrice: button.getAttribute('data-monthly-price'),
            planName: '{!! $planDetails?->name !!}',
            planId: button.getAttribute('data-plan-id') || '{{ $planDetails?->id }}',
            isMonthlyActive: document.getElementById('paymentModalPlan').getAttribute('data-is-monthly') === 'true'
        };
        
        // Store in sessionStorage
        sessionStorage.setItem('pendingPlanPurchase', JSON.stringify(planData));
        window.pendingPlanPurchase = planData;
        
        // Mark that this login was triggered by plan purchase
        sessionStorage.setItem('loginTriggeredByPlanPurchase', 'true');
        
        // Close any existing modals first
        $('.modal').modal('hide');
        
        // Wait for existing modal to close, then show signup modal
        setTimeout(() => {
            // Initialize signup modal content before showing
            initializeSignupModal();
            // Show login/signup modal
            $('#signupModalathlete').modal('show');
        }, 300);
    }

// Handle successful login/signup for plan purchase
window.onPlanPurchaseLoginSuccess = function() {
    const pendingPlanData = sessionStorage.getItem('pendingPlanPurchase');
    if (pendingPlanData) {
        try {
            const planData = JSON.parse(pendingPlanData);
            
            // Update UI to reflect stored preference
            const monthlyBtn = document.getElementById('monthlyPlanBtn');
            const oneTimeBtn = document.getElementById('oneTimePlanBtn');
            
            if (monthlyBtn && oneTimeBtn) {
                if (planData.isMonthlyActive) {
                    monthlyBtn.classList.add('active');
                    oneTimeBtn.classList.remove('active');
                } else {
                    monthlyBtn.classList.remove('active');
                    oneTimeBtn.classList.add('active');
                }
            }
            
            // Find the button with the stored plan data
            const button = document.querySelector(`.plan-get-started-btn[data-plan-type="${planData.type}"]`);
            if (button) {
                // Check if user already has this plan before showing payment modal
                checkExistingPlan(planData.planId, planData.type, planData.price, planData.monthlyPrice, button, planData);
            }
            sessionStorage.removeItem('pendingPlanPurchase');
            if (window.pendingPlanPurchase) {
                delete window.pendingPlanPurchase;
            }
        } catch (e) {
            console.error('Error parsing pending plan data:', e);
            sessionStorage.removeItem('pendingPlanPurchase');
        }
    }
    
    // Clear the stored return URL
    sessionStorage.removeItem('loginTriggeredByPlanPurchase');
};

    // Handle payment modal close to show plan selection modal
    $('#paymentModalPlan').on('hidden.bs.modal', function() {
        // Small delay to ensure modal is fully closed
        setTimeout(() => {
            // Only show plan selection modal if it was closed manually (not programmatically)
            if (!isPaymentModalClosingProgrammatically) {
                $('#planChooseModal').modal('show');
            }
            // Reset the flag
            isPaymentModalClosingProgrammatically = false;
        }, 300);
    });

    // Handle congrats modal close to reload the page
    $('#congratsModalPlan').on('hidden.bs.modal', function() {
        // Small delay to ensure modal is fully closed
        setTimeout(() => {
            // Reload the page
            window.location.reload();
        }, 300);
    });

// Common function to handle pending plan purchase after page load
window.handlePendingPlanPurchase = function() {
    const pendingPlanData = sessionStorage.getItem('pendingPlanPurchase');
    if (pendingPlanData) {
        try {
            const planData = JSON.parse(pendingPlanData);
            
            // Check if user is authenticated before showing payment modal
            const isAuthenticated = {{ Auth::check() ? 'true' : 'false' }};
            
            if (!isAuthenticated) {
                // User is not authenticated, show login/signup modal instead
                // Close any existing modals first
                $('.modal').modal('hide');
                
                // Wait for existing modal to close, then show signup modal
                setTimeout(() => {
                    // Initialize signup modal content before showing
                    initializeSignupModal();
                    // Show login/signup modal
                    $('#signupModalathlete').modal('show');
                }, 300);
                // Don't clear pending data yet - keep it for after login
                return;
            }
            
            // User is authenticated, update UI to reflect stored preference first
            const monthlyBtn = document.getElementById('monthlyPlanBtn');
            const oneTimeBtn = document.getElementById('oneTimePlanBtn');
            
            if (monthlyBtn && oneTimeBtn) {
                if (planData.isMonthlyActive) {
                    monthlyBtn.classList.add('active');
                    oneTimeBtn.classList.remove('active');
                } else {
                    monthlyBtn.classList.remove('active');
                    oneTimeBtn.classList.add('active');
                }
            }
            
            // Find the button with the stored plan data
            const button = document.querySelector(`.plan-get-started-btn[data-plan-type="${planData.type}"]`);
            if (button) {
                // Check if user already has this plan before showing payment modal
                setTimeout(() => {
                    checkExistingPlan(planData.planId, planData.type, planData.price, planData.monthlyPrice, button, planData);
                }, 500); // Small delay to ensure page is fully loaded
            }
            // Clear the pending plan purchase
            sessionStorage.removeItem('pendingPlanPurchase');
            if (window.pendingPlanPurchase) {
                delete window.pendingPlanPurchase;
            }
        } catch (e) {
            console.error('Error parsing pending plan data:', e);
            sessionStorage.removeItem('pendingPlanPurchase');
        }
    }
};

// Global function to initialize signup modal content
window.initializeSignupModal = function() {
    // Show the signup/login content sections
    $('.signup-login-h2-title').removeClass('d-none');
    $('.signup-login-h2-img').removeClass('d-none');
    
    $('#signupModalathlete #new-user-singup').removeClass('d-none');
    $('#signupModalathlete #existing-user-login').addClass('d-none');

    // Hide quiz-specific content
    $('.quiz-h2-title').addClass('d-none');
    $('.quiz-h2-img').addClass('d-none');
    
    // Reset to step 1
    if (typeof showStep === 'function') {
        showStep(1);
    }
    
    // Clear any previous form data
    $('#mobile_number').val('');
    $('#firstname').val('');
    $('#email').val('');
    $('input[name="userType"]').prop('checked', false);
    $('input[name="ageGroup"]').prop('checked', false);
    $('#sportstype').val('');
    
    // Remove selected classes
    $('.user-type-box').removeClass('selected');
    $('.age-box').removeClass('selected');
    
    // Hide age groups and sports selection by default
    $('#age-groups-id').addClass('d-none');
    $('#select-sports-id').addClass('d-none');
};
</script>

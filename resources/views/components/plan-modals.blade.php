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
                        <p class="mb-2 sign-in-text" style="line-height: 22px;">Signed in as<br><strong>jordansmith@gmail.com</strong></p>
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
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 12px;">
                <div id="congratsContentPlan">
                    <button type="button" class="btn-close congrats-modal" data-bs-dismiss="modal"
                        aria-label="Close"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                            viewBox="0 0 12 12" fill="none">
                            <path
                                d="M0.219668 1.28033C-0.0732225 0.987438 -0.0732225 0.512558 0.219668 0.219668C0.512558 -0.0732225 0.987438 -0.0732225 1.28033 0.219668L5.999 4.9384L10.7176 0.219798C11.0105 -0.0730923 11.4854 -0.0730923 11.7782 0.219798C12.0711 0.512688 12.0711 0.987568 11.7782 1.28046L7.0597 5.999L11.7782 10.7176C12.0711 11.0105 12.0711 11.4854 11.7782 11.7782C11.4854 12.0711 11.0105 12.0711 10.7176 11.7782L5.999 7.0597L1.28033 11.7784C0.987438 12.0713 0.512558 12.0713 0.219668 11.7784C-0.0732225 11.4855 -0.0732225 11.0106 0.219668 10.7177L4.9384 5.999L0.219668 1.28033Z"
                                fill="#626262" />
                        </svg></button>
                    <img src="images/congrats-modal-img.png" alt="Congrats" class="rounded-top w-100">
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
                        <button type="button" class="w-100 btn btn-signup" id="completeQuestionnaireBtn">Next - Complete Questionnaire</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- JavaScript for Modal Functionality -->
<script src="https://js.stripe.com/v3/"></script>
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
            document.getElementById('paymentModalTitle').textContent = planName;
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
            document.getElementById('congratsPlanName').textContent = `Your ${planName}`;
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
                
                paymentModalPrice.textContent = `A$${originalPrice.toFixed(2)}`;
                
                // Reset button text
                const isMonthlyActive = document.getElementById('monthlyPlanBtn').classList.contains('active');
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
            
            // Debug logging
            console.log('Coupon application:', {
                promoCode: promoCode,
                planId: planId,
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
            
            fetch('{{ route("validate.coupon.code") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    code: promoCode,
                    plan_id: planId
                })
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
                    if (isNaN(finalPrice)) {
                        console.error('Final price is NaN, using original price');
                        paymentModalPrice.textContent = `A$${originalPrice.toFixed(2)}`;
                    } else {
                        paymentModalPrice.textContent = finalPrice <= 0 ? 'A$0' : `A$${finalPrice.toFixed(2)}`;
                    }
                    
                    // Update toggle link text
                    toggleCouponLink.textContent = `Remove Coupon Code (${discountText})`;
                    
                    promoMessage.textContent = `Coupon applied successfully! ${discountText}`;
                    promoMessage.className = "form-text text-success";
                    
                    // If 100% discount, update button text and hide payment form
                    if (finalPrice <= 0) {
                        paymentButton.innerHTML = 'Get Free Plan';
                        // Hide payment form for free plan
                        document.querySelector('#paymentForm .form-wrap').classList.add('d-none');
                        // Remove required attribute from card holder name for free plans
                        document.getElementById('card-holder-name').removeAttribute('required');
                    } else {
                        const isMonthlyActive = document.getElementById('monthlyPlanBtn').classList.contains('active');
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
                                const monthlyDiscountAmount = (originalMonthlyPrice * discount) / 100;
                                const discountedMonthlyPrice = originalMonthlyPrice - monthlyDiscountAmount;
                                
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
                    paymentModalPrice.textContent = `A$${resetPrice.toFixed(2)}`;
                    toggleCouponLink.textContent = "Add a Coupon Code";
                    
                    const isMonthlyActive = document.getElementById('monthlyPlanBtn').classList.contains('active');
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
});

// Function to process plan payment (exactly like consultation flow)
function processPlanPayment() {
    const planType = document.getElementById('paymentModalTitle').textContent;
    const originalPrice = parseFloat(document.getElementById('paymentModalPrice').getAttribute('data-original-price') || '0');
    const finalPrice = document.getElementById('paymentModalPrice').textContent.replace(/[A$,\s]/g, '');
    const email = '{{ Auth::user()->email ?? "" }}';
    const couponCode = $('#promo-code-consultation').val().trim();
    const cardHolderName = $('#card-holder-name').val();
    
    // Disable button to prevent double submission
    $('#paymentButton').prop('disabled', true).text('Processing...');
    
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
        return;
    }
    
    // Create payment method with Stripe for paid plans
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
            $('#paymentButton').prop('disabled', false).text('One Payment | A$' + finalPrice);
            alert('Payment method error: ' + result.error.message);
        } else {
            // Payment method created successfully
            paymentMethodId = result.paymentMethod.id;
            
            // Send payment to server
            sendPlanRequest();
        }
    });
}

// Send plan request to server (exactly like consultation flow)
function sendPlanRequest() {
    const planType = document.getElementById('paymentModalTitle').textContent;
    const originalPrice = parseFloat(document.getElementById('paymentModalPrice').getAttribute('data-original-price') || '0');
    const finalPrice = document.getElementById('paymentModalPrice').textContent.replace(/[A$,\s]/g, '');
    const email = '{{ Auth::user()->email ?? "" }}';
    const couponCode = $('#promo-code-consultation').val().trim();
    const cardHolderName = $('#card-holder-name').val() || '{{ Auth::user()->name ?? "" }}';
    
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
            payment_method_id: paymentMethodId,
            is_monthly: document.getElementById('monthlyPlanBtn').classList.contains('active'),
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                // Hide payment modal programmatically
                isPaymentModalClosingProgrammatically = true;
                $('#paymentModalPlan').modal('hide');
                // Update congrats modal content based on plan type
                updateCongratsModal(response.data.plan_type, response.data.has_consultation);
                // Show congrats modal
                $('#congratsModalPlan').modal('show');
            } else if (response.requires_action) {
                // Handle 3D Secure authentication
                stripe.handleCardAction(response.payment_intent_client_secret).then(function(result) {
                    if (result.error) {
                        $('#paymentButton').prop('disabled', false).text('One Payment | A$' + finalPrice);
                        alert('Payment failed: ' + result.error.message);
                    } else {
                        // Payment succeeded after 3D Secure
                        isPaymentModalClosingProgrammatically = true;
                        $('#paymentModalPlan').modal('hide');
                        updateCongratsModal(response.data.plan_type, response.data.has_consultation);
                        $('#congratsModalPlan').modal('show');
                    }
                });
            } else {
                alert(response.message || 'An error occurred while purchasing the plan.');
                $('#paymentButton').prop('disabled', false).text('One Payment | A$' + finalPrice);
            }
        },
        error: function(xhr) {
            const response = xhr.responseJSON;
            alert(response.message || 'An error occurred while purchasing the plan.');
            $('#paymentButton').prop('disabled', false).text('One Payment | A$' + finalPrice);
        }
    });
}

// Process free plan purchase (exactly like consultation flow)
function processFreePlanPurchase() {
    // Free plan purchase - just call sendPlanRequest which will handle the original price
    sendPlanRequest();
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
    const planName = '{{ $planDetails?->name }}';
    const congratsPlanName = document.getElementById('congratsPlanName');
    const congratsPlanDescription = document.getElementById('congratsPlanDescription');
    
    if (planType === 'powerplay') {
        congratsPlanName.innerHTML = `<strong>Your Power Play</strong><br>${planName} + 30 min Consultation`;
        congratsPlanDescription.textContent = 'We\'ll send you an email to book your consultation. You will need to complete your questionnaire prior then Kerry will start working on your personalised nutrition plan and it will appear in your web app, as soon as it\'s ready.';
    } else if (planType === 'gameplan') {
        congratsPlanName.innerHTML = `<strong>Your Game Plan</strong><br>${planName} + 60 min Consultation`;
        congratsPlanDescription.textContent = 'We\'ll send you an email to book your consultation. You will need to complete your questionnaire prior then Kerry will start working on your personalised nutrition plan and it will appear in your web app, as soon as it\'s ready.';
    } else {
        congratsPlanName.innerHTML = `<strong>Your ${planName}</strong><br>${planName}`;
        congratsPlanDescription.textContent = 'You will need to complete your questionnaire prior then Kerry will start working on your personalised nutrition plan and it will appear in your web app, as soon as it\'s ready.';
    }
    }

    // Complete questionnaire button
    const completeQuestionnaireBtn = document.getElementById('completeQuestionnaireBtn');
    if (completeQuestionnaireBtn) {
        completeQuestionnaireBtn.addEventListener('click', function() {
            // Close the modal and reload the page
            const congratsModal = bootstrap.Modal.getInstance(document.getElementById('congratsModalPlan'));
            congratsModal.hide();
            
            // Reload the page after modal closes
            setTimeout(() => {
                window.location.reload();
            }, 300);
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
                    planName: '{{ $planDetails?->name }}',
                    planId: '{{ $planDetails?->id }}'
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
    function showPlanPaymentModal(button) {
        const planType = button.getAttribute('data-plan-type');
        const oneTimePrice = button.getAttribute('data-plan-price');
        const monthlyPrice = button.getAttribute('data-monthly-price');
        
        // Check which pricing type is currently active
        const isMonthlyActive = document.getElementById('monthlyPlanBtn').classList.contains('active');
        
        // Close any existing modals first
        $('.modal').modal('hide');
        
        // Wait for existing modal to close, then show payment modal
        setTimeout(() => {
            // Update payment modal content based on plan type and pricing
            if (planType === 'main') {
                document.getElementById('paymentModalTitle').textContent = '{{ $planDetails?->name }}';
                document.getElementById('paymentModalSubtitle').textContent = '{{ $planDetails?->name }}';
                document.getElementById('paymentModalPrice').textContent = isMonthlyActive ? 'A$' + monthlyPrice + '/mth' : 'A$' + oneTimePrice;
                document.getElementById('paymentModalPrice').setAttribute('data-original-price', isMonthlyActive ? monthlyPrice : oneTimePrice);
                document.getElementById('paymentModalDuration').textContent = isMonthlyActive ? 'Over {{ $months }} Months' : 'One time payment';
                document.getElementById('paymentButton').textContent = isMonthlyActive ? 'Monthly | A$' + monthlyPrice + '/mth' : 'One Payment | A$' + oneTimePrice;
                document.getElementById('paymentButton').setAttribute('data-monthly-price', monthlyPrice);
            } else if (planType === 'powerplay') {
                document.getElementById('paymentModalTitle').textContent = 'Power Play';
                document.getElementById('paymentModalSubtitle').textContent = '{{ $planDetails?->name }} + 30 min Consult with Extreme Sports Dietitian Kerry O\'Byran';
                document.getElementById('paymentModalPrice').textContent = isMonthlyActive ? 'A$' + monthlyPrice + '/mth' : 'A$' + oneTimePrice;
                document.getElementById('paymentModalPrice').setAttribute('data-original-price', isMonthlyActive ? monthlyPrice : oneTimePrice);
                document.getElementById('paymentModalDuration').textContent = isMonthlyActive ? 'Over {{ $months }} Months' : 'One time payment';
                document.getElementById('paymentButton').textContent = isMonthlyActive ? 'Monthly | A$' + monthlyPrice + '/mth' : 'One Payment | A$' + oneTimePrice;
                document.getElementById('paymentButton').setAttribute('data-monthly-price', monthlyPrice);
            } else if (planType === 'gameplan') {
                document.getElementById('paymentModalTitle').textContent = 'Game Plan';
                document.getElementById('paymentModalSubtitle').textContent = '{{ $planDetails?->name }} + 60 min Consult with Kerry to cover Nutrition AND Training Advise';
                document.getElementById('paymentModalPrice').textContent = isMonthlyActive ? 'A$' + monthlyPrice + '/mth' : 'A$' + oneTimePrice;
                document.getElementById('paymentModalPrice').setAttribute('data-original-price', isMonthlyActive ? monthlyPrice : oneTimePrice);
                document.getElementById('paymentModalDuration').textContent = isMonthlyActive ? 'Over {{ $months }} Months' : 'One time payment';
                document.getElementById('paymentButton').textContent = isMonthlyActive ? 'Monthly | A$' + monthlyPrice + '/mth' : 'One Payment | A$' + oneTimePrice;
                document.getElementById('paymentButton').setAttribute('data-monthly-price', monthlyPrice);
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

// Handle successful login/signup for plan purchase
window.onPlanPurchaseLoginSuccess = function() {
    const pendingPlanData = sessionStorage.getItem('pendingPlanPurchase');
    if (pendingPlanData) {
        try {
            const planData = JSON.parse(pendingPlanData);
            // Find the button with the stored plan data
            const button = document.querySelector(`.plan-get-started-btn[data-plan-type="${planData.type}"]`);
            if (button) {
                showPlanPaymentModal(button);
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
            
            // User is authenticated, proceed with payment modal
            // Find the button with the stored plan data
            const button = document.querySelector(`.plan-get-started-btn[data-plan-type="${planData.type}"]`);
            if (button) {
                // Show payment modal automatically
                setTimeout(() => {
                    showPlanPaymentModal(button);
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

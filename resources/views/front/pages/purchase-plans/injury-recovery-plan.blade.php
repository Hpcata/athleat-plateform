@extends(frontView('layouts.app'))

@section('title', 'Injury Plan & Diet for Athletes | Performance Health')
@section('meta_description', 'Get a personalised athlete meal plan with Performance Health Support. Expert sports nutrition plans and diet strategies tailored to fuel performance and recovery.')

@push('styles')
    <link rel="stylesheet" href="{!! frontAssets('css/purchase-plans/injury-recovery.css') !!}">
    <style>
        #loader {
            width: 100%;
            height: 100vh;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            z-index: 999999;
            backdrop-filter: blur(4px);
            background-color: #00000082;
        }
    </style>
@endpush

@section('content')
    <div id="loader" class="d-none">
        <div class="box" id="loader1"></div>
        <div class="box" id="loader2"></div>
        <div class="box" id="loader3"></div>
        <div class="box" id="loader4"></div>
        <div class="box" id="loader5"></div>
    </div>
    <main class="main injury-plan-page">

        <div class="hero-container">
            <div class="hero-section">
                @if (!empty($sportGameData['sport_image']))
                    <div class="hero-background" style="background-image: url('{{ webAssets('storage/' . $sportGameData['sport_image']) }}')" >
                        <div class="hero-overlay"></div>
                    </div>
                @else
                    <div class="hero-background" style="background-image: url('{{ frontAssets('images/hero-section/injury.svg') }}');">
                        <div class="hero-overlay"></div>
                    </div>
                @endif
                <div class="hero-content">
                    <div class="hero-bottom">
                        <h1 class="hero-title">Injury & Recovery Plan</h1>
                        <div class="hero-top">
                            <p class="hero-subtitle-plan">{{ isset($sportGameData['sport_name']) ? $sportGameData['sport_name'] : '' }}</p>
                            <a href="{{ route('front.my-plans') }}" class="view-all-link"> View all plans </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="section-header injury-recovery-head" >
                <h2 style="margin-bottom:0;">Injury & Recovery Plan</h2>
            </div>

            <!-- Button wrapper -->
            <div class="button-wrapper">
                <button class="btn btn-share print-plan-btn" data-user-id="{{ $user->id}}" data-plan-id="{{ $plan->id}}">View plan</button>
                <button class="btn-outline btn" id="shoppingList" data-bs-toggle="modal"
                    data-bs-target="#shoppingListModal">Shopping list</button>
                {{-- <button class="btn btn-share coming-soon-popup" type="button" id="">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17" fill="none">
                        <g clip-path="url(#clip0_3008_7695)">
                            <path
                                d="M0.888672 8.50124V14.3194C0.888672 14.7052 1.07597 15.0752 1.40937 15.3479C1.74277 15.6207 2.19495 15.774 2.66645 15.774H13.3331C13.8046 15.774 14.2568 15.6207 14.5902 15.3479C14.9236 15.0752 15.1109 14.7052 15.1109 14.3194V8.50124M11.5553 4.13761L7.99978 1.22852M7.99978 1.22852L4.44423 4.13761M7.99978 1.22852V10.6831"
                                stroke="#3B3B3B" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
                        </g>
                        <defs>
                            <clipPath id="clip0_3008_7695">
                                <rect width="16" height="16" fill="white" transform="translate(0 0.5)" />
                            </clipPath>
                        </defs>
                    </svg>
                    Share
                </button> --}}
            </div>

            <!-- Recovery Meals -->
            <div class="training-plan-wrapper">
                <div class="custom-accordion">
                    <button class="custom-accordion-header" style="margin-bottom:0px;">
                        <div class="accordion-title tab-box-title">
                            <h2 >Recovery Meals</h2>
                            <span>Post Injury</span>
                        </div>
                        <span class="arrow header-arrow"></span>
                    </button>
                    <div class="custom-accordion-content">
                        <div class="accordion-body meal-sec">
                            <section class="training-plan">
                                <div class="tab-box">
                                    <div class="tab-header">
                                        <p style="margin-top:12px;">Targeted nutrition and supplementation to optimise healing through reduced inflammation and fast tissue repair.</p>
                                    </div>
                                    <div class="meal-height-accordion">
                                    @if (isset($userPlan) && $userPlan->status == 'active')
                                        @include('front.pages.partials.active-plan-section', ['userPlan' => $userPlan, 'isAdminView' => false, 'plan' => $plan])
                                    @endif
                                    </div>
                                </div>
                            </section>
                            <!-- Arrow at the end of body when open -->
                            <div class="accordion-body-arrow">
                                <span class="arrow body-arrow"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

              <!-- Level-Up Library -->
            <div class="training-plan-wrapper">
                <div class="custom-accordion resources">
                    <button class="custom-accordion-header">
                        <div class="accordion-title tab-box-title">
                            <h2>Level-Up Library</h2>
                        </div>
                        <span class="arrow header-arrow"></span>
                    </button>
                    <div class="custom-accordion-content level-up-accordion-body">
                        <div class="accordion-body">
                              <section class="resources resources-body" style="margin-bottom: 0;">

                                <div class="" style="margin: 12px 0;">
                                    <div class="resource-card-custom resource-tip">
                                        <div class="tip-title">Kez's Tip of the Day</div>
                                        <div class="tip-text">"Prioritise rest, balanced nutrition, and gradual movement to speed up recovery."</div>
                                    </div>
                                </div>
                                <div class="resources-custom-grid grid-2 injury-page">
                                    <div class="resource-card-custom resource-video clickable hover-card"
                                        onclick="showComingSoonTooltip(this, 'Coming Soon')">
                                        <div class="video-thumb-container"
                                            onclick="openVideoPopup('https://www.w3schools.com/html/mov_bbb.mp4')">
                                            <img src="{{ frontAssets('images/video-bg.webp') }}" class="video-thumb"
                                                alt="Video thumbnail for whey protein post-training" />
                                            <div class="video-icon-overlay">
                                                <img src="{{ frontAssets('images/play.svg') }}" class="video-thumb" alt="play icon" />
                                            </div>
                                        </div>
                                        <div class="video-info">
                                            <div class="video-title">
                                                Perfecting protein intake to enhance rehab & recovery
                                            </div>
                                            <div class="video-meta">
                                                <span>
                                                    <img src="{{ frontAssets('images/Clock.webp') }}" class="clock-img" alt="Clock icon"
                                                        width="16" height="16" /></span><span>3 min • Video</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="resource-card-custom resource-video clickable hover-card"
                                        onclick="showComingSoonTooltip(this, 'Coming Soon')">
                                        <div class="video-thumb-container">
                                            <img src="{{ frontAssets('images/gym.webp') }}" class="video-thumb" alt="Gym video thumbnail" />
                                        </div>
                                        <div class="video-info">
                                            <div class="video-title">
                                                How to maintain muscle while injured
                                            </div>
                                            <div class="video-meta">
                                                <span>
                                                    <img src="{{ frontAssets('images/Clock.webp') }}" class="clock-img" alt="Clock icon" width="16" height="16" /></span><span>3 min • Short read</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mark-complete-wrap">
                                    <label class="mark-complete">Mark complete</label>
                                    <div class="">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
                                    <path d="M7.39033 1.18919C7.31665 1.03985 7.16452 0.945312 6.99798 0.945312C6.83149 0.945312 6.67936 1.03985 6.60569 1.18919L5.05739 4.32637L1.59531 4.82944C1.4305 4.85339 1.29359 4.96882 1.24213 5.1272C1.19067 5.28558 1.23359 5.45944 1.35284 5.57569L3.85802 8.01765L3.26663 11.4657C3.23848 11.6299 3.30595 11.7957 3.44068 11.8937C3.5754 11.9915 3.75402 12.0044 3.90142 11.9269L6.99798 10.2989L10.0946 11.9269C10.242 12.0044 10.4206 11.9915 10.5553 11.8937C10.6901 11.7957 10.7575 11.6299 10.7294 11.4657L10.138 8.01765L12.6432 5.57569C12.7624 5.45944 12.8054 5.28558 12.7539 5.1272C12.7024 4.96882 12.5655 4.85339 12.4007 4.82944L8.93861 4.32637L7.39033 1.18919Z" fill="#EF7F00"/>
                                    </svg>
                                    <span>5</span>
                                    </div>
                                </div>

                            </section>
                            <!-- Arrow at the end of body when open -->
                            <div class="accordion-body-arrow">
                                <span class="arrow body-arrow"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resources -->
            <div class="training-plan-wrapper">
                <div class="custom-accordion resources">
                    <button class="custom-accordion-header">
                        <div class="accordion-title tab-box-title">
                            <h2>Resources</h2>
                        </div>
                        <span class="arrow header-arrow"></span>
                    </button>
                    <div class="custom-accordion-content">
                        <div class="accordion-body">
                            <div class="cards-grid">
                                <!-- Card 1 -->
                                <div class="recovery-card white-card">
                                    <div class="card-content">
                                        <h3>Scans & Supplements</h3>
                                        <p>
                                            Track + Store supplement use, medications & scans for better results all
                                            in 1 place - BioHealth Passport.
                                        </p>
                                    </div>
                                    <div class="card-logo">
                                        <img src="{{ frontAssets('images/biohealth.svg') }}" alt="BioHealthPassport">
                                    </div>
                                </div>

                                 <!-- Card 2 -->
                                 {{-- need to show this below card if user is above 18 years old --}}
                                @if($userPlan->user->userPrePlans()->first()->getUserAge() > 18)
                                    <div class="recovery-card">
                                        <div class="card-logo">
                                            <img src="{{ frontAssets('images/card-img1.png') }}" alt="Athleat">
                                        </div>
                                        <div class="card-content">
                                            <h3>Injury Recovery Pack</h3>
                                            <p>Safe, smart & strategic foods + supplements that can support faster recovery.</p>
                                            <button class="btn btn-share" onclick="window.open('https://athleatshop.com/products/collagen-regenerate', '_blank')">Shop Now</button>
                                        </div>
                                    </div>
                                @endif

                            </div>
                            <!-- Arrow at the end of body when open -->
                            <div class="accordion-body-arrow">
                                <span class="arrow body-arrow"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Optimize Performance -->
            <section class="optimize-performance">
                <div class="section-header">
                    <h2>Optimise your performance</h2>
                    <a href="{{ route('front.my-plans') }}" class="see-all">All Plans</a>
                </div>

                <label class="plan-subtitle-mob">Nutrition plans</label>
                <div class="consults-plans-grid">
                    @if(isset($notPurchasedPlans) && $notPurchasedPlans->count() > 0)
                        @foreach($notPurchasedPlans as $planData)
                            @if($planData)
                                @include('front.pages.plan-cards.card-nutrition', ['plan' => $planData])
                            @endif
                        @endforeach
                    @else
                        <div class="col-12">
                            <div class="alert alert-warning text-center">
                                <p>No nutrition plans available at the moment. Please check back later!</p>
                            </div>
                        </div>
                    @endif
                </div>

                <label class="plan-subtitle-mob">Consultations</label>
                <div class="consults-plans-grid grid-1">
                    @include('front.pages.plan-cards.card-consultations')
                </div>
            </section>
        </div>
    </main>

    <!-- Bootstrap Modal for Download Plan (keep your content inside) -->
    <div class="modal" id="print-plan-modal" tabindex="-1" aria-labelledby="printPlanModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content" style="border-radius: 12px;">
                <div class="modal-header" style="border-bottom: 1px solid #d8d8d8;">
                    <h5 class="modal-title" id="printPlanModalLabel">Download Plan</h5>
                    <button type="button" class="meal-item-modal-close btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding: 0;    overflow: auto;">
                    <div style="flex: 1 1 auto; padding: 16px 16px 0 16px;">
                        <div id="pdf-preview" style="width: 100%; height: 100%; display: flex; justify-content: center;"
                            class="downloadplan-inner-content"></div>
                    </div>
                </div>
                <div class="modal-footer"
                    style="text-align: end; padding: 12px 16px; border-top: 1px solid #d8d8d8; border-radius:0 0 12px 12px; background-color:#fff;">
                    <button id="download-plan-btn" class="btn btn-primary" onclick="downloadPDF('{{ $plan->name }}')">
                        Download Plan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Modal for Shopping List -->
    <div class="modal" id="shoppingListModal" tabindex="-1" aria-labelledby="shoppingListModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius: 20px;">
                <div class="modal-header" style="border-bottom: 1px solid #d8d8d8;">
                    <h5 class="modal-title" id="shoppingListModalLabel">Shopping Lists</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Your existing shopping list content will be injected here by JS -->
                    <div id="shopping-list-content-container">
                        <!-- Content loaded via AJAX or JS -->
                    </div>
                </div>
                <div class="modal-footer"
                    style="text-align: end; padding: 12px 16px; border-top: 1px solid #d8d8d8; background-color:#fff; border-radius:0 0 12px 12px; ">
                    <button id="print-shopping-list" class="btn btn-primary">Print Shopping List</button>
                </div>
            </div>
        </div>
    </div>

    @include('front.modal.print-shopping-list')
    @include('front.modal.meal-detail')
    @include('front.modal.smart-swap')
    @include('front.modal.smart-swap-items')
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>

    <script>

        // Ensure userId and userPlanId are already defined globally
        document.addEventListener('DOMContentLoaded', function () {
            const accordionHeaders = document.querySelectorAll('.custom-accordion-header');

            // Simple function to calculate and set accordion height
            function setAccordionHeight(header) {
                const content = header.nextElementSibling;
                if (!content) return;

                const isOpen = header.classList.contains('active');

                if (isOpen) {
                    // Check if this is the recovery meals accordion (first one)
                    const isRecoveryMeals = header.closest('.training-plan-wrapper') ===
                        document.querySelector('.training-plan-wrapper:first-child');

                    // Temporarily remove max-height to get natural height
                    content.style.maxHeight = 'none';

                    // Use setTimeout to ensure DOM is updated
                    setTimeout(() => {
                        const naturalHeight = content.scrollHeight;

                        if (isRecoveryMeals) {
                            // Recovery meals has minimum 565px
                            const minHeight = 565;
                            const finalHeight = Math.max(naturalHeight, minHeight);
                            content.style.maxHeight = finalHeight + 'px';
                            content.style.minHeight = minHeight + 'px';
                        } else {
                            // Other accordions use natural height
                            content.style.maxHeight = naturalHeight + 'px';
                            content.style.minHeight = 'auto';
                        }
                    }, 10);
                } else {
                    // For closed accordions, set to 0
                    content.style.maxHeight = '0px';
                    content.style.minHeight = '0px';
                }
            }

            // Toggle accordion function
            function toggleAccordion(header) {
                const content = header.nextElementSibling;
                if (!content) return;

                const isCurrentlyOpen = header.classList.contains('active');

                if (isCurrentlyOpen) {
                    // Closing accordion
                    header.classList.remove('active');
                    content.style.maxHeight = '0px';
                    content.style.minHeight = '0px';
                } else {
                    // Opening accordion
                    header.classList.add('active');
                    // Small delay to ensure active class is applied
                    setTimeout(() => {
                        setAccordionHeight(header);
                    }, 10);
                }
            }

            // Add click event listeners for header
            accordionHeaders.forEach(header => {
                header.addEventListener('click', function (e) {
                    e.preventDefault();
                    toggleAccordion(this);
                });
            });

            // Add click event listeners for body arrows
            const bodyArrows = document.querySelectorAll('.arrow.body-arrow');
            bodyArrows.forEach(arrow => {
                arrow.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation(); // Prevent event bubbling

                    // Find the accordion header for this body arrow
                    const accordionBody = this.closest('.accordion-body');
                    const accordionContent = accordionBody.closest('.custom-accordion-content');
                    const accordionHeader = accordionContent.previousElementSibling;

                    if (accordionHeader && accordionHeader.classList.contains('active')) {
                        // Close the accordion
                        toggleAccordion(accordionHeader);
                    }
                });
            });

            // Open all accordions by default after a short delay to ensure content is loaded
            setTimeout(() => {
                accordionHeaders.forEach(header => {
                    header.classList.add('active');
                    setAccordionHeight(header);
                });
            }, 500);
        });

        // Function to manually update accordion height and arrows
        function updateAccordionHeights() {
            const accordionHeaders = document.querySelectorAll('.custom-accordion-header.active');
            accordionHeaders.forEach(header => {
                const content = header.nextElementSibling;
                if (content) {
                    // Check if this is the recovery meals accordion
                    const isRecoveryMeals = header.closest('.training-plan-wrapper') ===
                        document.querySelector('.training-plan-wrapper:first-child');

                    // Temporarily remove max-height to get natural height
                    content.style.maxHeight = 'none';

                    setTimeout(() => {
                        const naturalHeight = content.scrollHeight;

                        if (isRecoveryMeals) {
                            // Recovery meals has minimum 565px
                            const minHeight = 565;
                            const finalHeight = Math.max(naturalHeight, minHeight);
                            content.style.maxHeight = finalHeight + 'px';
                            content.style.minHeight = minHeight + 'px';
                        } else {
                            // Other accordions use natural height
                            content.style.maxHeight = naturalHeight + 'px';
                            content.style.minHeight = 'auto';
                        }

                        // Update arrow visibility for this content
                        const leftArrow = content.querySelector('.left-arrow');
                        const rightArrow = content.querySelector('.right-arrow');
                        const cardsWrapper = content.querySelector('#meal-cards-wrapper') || content.querySelector('.challenge-cards');

                        if (leftArrow && rightArrow && cardsWrapper) {
                            const cards = cardsWrapper.querySelectorAll('.challenge-card');
                            const shouldShowArrows = cards.length > 4;

                            leftArrow.style.display = shouldShowArrows ? 'block' : 'none';
                            rightArrow.style.display = shouldShowArrows ? 'block' : 'none';
                            leftArrow.style.opacity = shouldShowArrows ? '1' : '0';
                            rightArrow.style.opacity = shouldShowArrows ? '1' : '0';
                            leftArrow.style.pointerEvents = shouldShowArrows ? 'auto' : 'none';
                            rightArrow.style.pointerEvents = shouldShowArrows ? 'auto' : 'none';
                        }
                    }, 50);
                }
            });
        }

        // Make the function globally available for AJAX callbacks
        window.updateAccordionHeights = updateAccordionHeights;

        window["profile-landing-page"] = {
            userPlan: @json($userPlan ?? null),
            userId: {{ $userPlan->user_id ?? 0 }},
            isFreeUser: {{ $userPlan->free_user ?? 0 }},
            routes: {
                getProfileMeals: @json(route('front.get-profile-meals', ['plan' => 'PLAN_ID', 'category' => 'CATEGORY_ID'])),
                mealDetails: @json(route('front.meal.details')),
                mealsItems: @json(route('front.meals.items', ':mealId')),
                itemsSwapItems: @json(route('front.items.swap-items', ':id')),
                itemsSwaps: @json(route('front.items.swaps')),
                quizNutritionScore: @json(route('front.quiz.nutrition-score'))
            },
            csrfToken: '{{ csrf_token() }}',
            assets: {
                storage: '{{ asset('storage') }}',
                frontImages: '{{ asset('front/images') }}',
                frontAssets: '{{ frontAssets('') }}'
            }
        };
    </script>

    <script>
        const assetBaseUrl = "{{ asset('storage') }}";
        // Ensure userId and userPlanId are already defined globally
        document.addEventListener('DOMContentLoaded', function () {
            const dropdownOptions = document.querySelectorAll('.custom-dropdown-option');
            const plateImg = document.getElementById('plate-img'); // Get the image element

            dropdownOptions.forEach(option => {
                option.addEventListener('click', function () {
                    // Remove 'selected' class from all options
                    dropdownOptions.forEach(opt => opt.classList.remove('selected'));

                    // Add 'selected' class to clicked option
                    this.classList.add('selected');

                    // Update button text
                    const title = this.querySelector('.option-title').textContent;
                    const subtitle = this.querySelector('.option-subtitle').textContent;

                    const dropdown = this.closest('.dropdown');
                    dropdown.querySelector('.custom-dropdown-title').textContent = title;
                    dropdown.querySelector('.custom-dropdown-subtitle').textContent = subtitle;

                    // Optionally update hidden input if needed
                    const value = this.getAttribute('data-value');
                    const hiddenInput = dropdown.querySelector('input[type="hidden"]');
                    if (hiddenInput) {
                        hiddenInput.value = value;
                    }

                    // Update plate image based on selection
                    const imageSrc = this.getAttribute('data-image');
                    if (plateImg && imageSrc) {
                        plateImg.src = imageSrc;
                    }

                    // Close the Bootstrap dropdown after selection
                    const toggleEl = document.getElementById('trainingLoadDropdown');
                    if (typeof bootstrap !== 'undefined' && toggleEl) {
                        const dd = bootstrap.Dropdown.getOrCreateInstance(toggleEl);
                        dd.hide();
                    }
                });
            });
        });

        function showLoader() {
            $('#loader').removeClass('d-none');
        }

        function hideLoader() {
            $('#loader').addClass('d-none');
        }

        $(document).ready(function() {
            // Bind once
            $(document).on('click', '#shoppingList', function() {
                const shoppingListModal = document.getElementById('shoppingListModal');
                showLoader();

                // Inject content
                const contentContainer = $('#shoppingListModal .modal-body');

                $.ajax({
                    url: '{{ route("front.get.meals.items") }}' + `?user_id=${userId}&user_plan_id=${userPlanId}`,
                    method: 'GET',
                    success: function(response) {
                        const meals = response.meals;
                        if (!meals || meals.length === 0) {
                            contentContainer.html('<p>No meal foods found for this plan.</p>');
                            hideLoader();
                            return;
                        }
                        let modalContent = `
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="selectAllCheckbox">
                                <label class="form-check-label" for="selectAllCheckbox">Select All</label>
                            </div>
                        `;

                        meals.forEach(meal => {
                            // Meal-level checkbox
                            modalContent += `
                                <div class="mb-2 form-check">
                                    <input type="checkbox" class="form-check-input meal-checkbox" id="meal-${meal.meal_id}-checkbox" data-meal-id="${meal.meal_id}">
                                    <label class="form-check-label" for="meal-${meal.meal_id}-checkbox" style="font-weight: bold; font-size: 1.2rem;">${meal.meal_title}</label>
                                </div>
                            `;

                            modalContent += `<div class="mb-3 card"><div class="p-3 card-body"><ul class="mb-0 list-unstyled meal-items" data-meal-id="${meal.meal_id}">`;

                            meal.items.forEach(item => {
                                let selectedQtyUnits = [];
                                try {
                                    selectedQtyUnits = item.selected_qty_unit ? JSON.parse(item.selected_qty_unit) : [];
                                    if (!Array.isArray(selectedQtyUnits)) selectedQtyUnits = [];
                                } catch (e) {
                                    selectedQtyUnits = [];
                                }

                                const checkedUnits = selectedQtyUnits.filter(u =>
                                    u.checked === true || u.checked === "true" || u.checked === 1 || u.checked === "1"
                                );

                                let qtyText = '';
                                if (checkedUnits.length > 0) {
                                    qtyText = checkedUnits.map(unitObj => {
                                        let rawQty = unitObj.qty;
                                        let unit = unitObj.unit;
                                        let qty = isNaN(rawQty) ? rawQty : parseFloat(rawQty);

                                        if (typeof rawQty === 'string' && rawQty.includes('/')) {
                                            const parts = rawQty.split('/');
                                            if (parts.length === 2 && !isNaN(parts[0]) && !isNaN(parts[1])) {
                                                qty = parseFloat(parts[0]) / parseFloat(parts[1]);
                                            }
                                        }

                                        if (['g', 'ml', 'mL'].includes(unit)) {
                                            return `${Math.round(qty)}${unit}`;
                                        }

                                        let displayQty = rawQty;
                                        const rounded = Math.round(qty * 100) / 100;
                                        if (rounded === 0.25) displayQty = '¼';
                                        else if (rounded === 0.5) displayQty = '½';
                                        else if (rounded === 0.75) displayQty = '¾';

                                        return `${displayQty} ${unit}`;
                                    }).join(' or ');
                                } else {
                                    let qty = parseFloat(item.qty);
                                    let unit = item.unit;
                                    let displayQty = qty;

                                    const rounded = Math.round(qty * 100) / 100;
                                    if (rounded === 0.25) displayQty = '¼';
                                    else if (rounded === 0.5) displayQty = '½';
                                    else if (rounded === 0.75) displayQty = '¾';

                                    qtyText = ['g', 'ml', 'mL'].includes(unit) ?
                                        `${Math.round(qty)}${unit}` :
                                        `${displayQty} ${unit}`;
                                }

                                modalContent += `
                                    <li class="d-flex align-items-center mb-3">
                                        <input class="me-3 form-check-input item-checkbox" type="checkbox" id="item-${item.id}" data-meal-id="${meal.meal_id}">
                                        <input type="hidden" id="category" value="${item.category}">
                                        <img src="${assetBaseUrl}/${item.image || ''}" alt="${item.title}" class="me-3" style="width:50px;height:50px;object-fit:cover;border-radius:4px;background-color:#f1f1f1;">
                                        <div>
                                            <span style="font-weight: 600; color: #4b5c6b;">${item.title}</span><br>
                                            <span style="font-size: 0.97rem;"><b>QTY:</b> ${qtyText}</span>
                                        </div>
                                    </li>
                                `;
                            });

                            modalContent += `</ul></div></div>`;
                        });

                        contentContainer.html(modalContent);

                        // === Checkbox Logic ===
                        // Select All
                        $('#selectAllCheckbox').on('change', function() {
                            const isChecked = $(this).is(':checked');
                            $('.meal-checkbox, .item-checkbox').prop('checked', isChecked);
                        });

                        // Meal checkbox controls items
                        $('.meal-checkbox').on('change', function() {
                            const mealId = $(this).data('meal-id');
                            const isChecked = $(this).is(':checked');
                            $(`.item-checkbox[data-meal-id="${mealId}"]`).prop('checked', isChecked);
                        });

                        // Item checkbox updates meal checkbox
                        $('.item-checkbox').on('change', function() {
                            const mealId = $(this).data('meal-id');
                            const items = $(`.item-checkbox[data-meal-id="${mealId}"]`);
                            const allChecked = items.length === items.filter(':checked').length;
                            $(`#meal-${mealId}-checkbox`).prop('checked', allChecked);
                        });
                        hideLoader();

                    },
                    error: function(xhr) {
                        console.error('Error fetching meals:', xhr);
                        contentContainer.html('<p>Error loading data.</p>');
                        hideLoader();

                    }
                });
            });

            $(document).on('click', '#print-shopping-list', function() {
                let aggregatedItems = {};

                // Loop through checked checkboxes inside shopping list modal
                $('#shoppingListModal .item-checkbox:checked').each(function() {
                    const listItem = $(this).closest('li');

                    const itemName = listItem.find('span').first().text().trim() || "Unnamed";
                    const category = listItem.find('input[type="hidden"]#category').val()?.trim() || "Uncategorized";

                    const qtyContainer = listItem.find('div > span:contains("QTY:")');
                    if (qtyContainer.length === 0) return;

                    const fullText = qtyContainer.text().trim();
                    const qtyTextMatch = fullText.match(/QTY:\s*(.+)/i);
                    if (!qtyTextMatch) return;

                    const qtyText = qtyTextMatch[1];
                    const qtyParts = qtyText.split(" or ").map(part => part.trim());

                    qtyParts.forEach(part => {
                        const match = part.match(/^([\d¼½¾/.]+)\s*([a-zA-Z\s]+)$/);
                        if (!match) return;

                        let qtyRaw = match[1].trim();
                        let unit = match[2].trim();

                        const unicodeFractions = {
                            '¼': 0.25,
                            '½': 0.5,
                            '¾': 0.75
                        };
                        let qty = unicodeFractions[qtyRaw] ?? null;

                        if (qty === null) {
                            if (qtyRaw.includes('/')) {
                                const [num, den] = qtyRaw.split('/').map(Number);
                                if (!isNaN(num) && !isNaN(den) && den !== 0) {
                                    qty = num / den;
                                }
                            } else {
                                qty = parseFloat(qtyRaw);
                            }
                        }

                        if (!qty || isNaN(qty)) return;

                        if (!aggregatedItems[category]) aggregatedItems[category] = {};
                        if (!aggregatedItems[category][itemName]) aggregatedItems[category][itemName] = {};
                        if (!aggregatedItems[category][itemName][unit]) aggregatedItems[category][itemName][unit] = 0;

                        aggregatedItems[category][itemName][unit] += qty;
                    });
                });

                // Build final HTML content in new style
                let printHtml = '';

                for (let [category, items] of Object.entries(aggregatedItems)) {
                    printHtml += `
                        <div style="margin-bottom: 12px">
                            <span style="font-size: 14px; font-weight: 600; color: #3b3b3b;">${category}</span>
                            <ul style="margin: 6px 0 0 0; list-style: none">
                    `;

                    for (let [itemName, unitMap] of Object.entries(items)) {
                        const qtyText = Object.entries(unitMap).map(([unit, total]) => {
                            const rounded = Math.round(total * 100) / 100;
                            if (rounded === 0.25) return `¼ ${unit}`;
                            if (rounded === 0.5) return `½ ${unit}`;
                            if (rounded === 0.75) return `¾ ${unit}`;
                            if (['g', 'ml', 'mL'].includes(unit)) return `${Math.round(total)}${unit}`;
                            return `${rounded} ${unit}`;
                        }).join(' or ');

                        printHtml += `
                        <li style="display: flex; align-items: center; font-size: 14px; font-weight: 400; color: #3b3b3b; line-height: 1.4; padding: 4px 0;">
                            <span style="display: inline-block; width: 16px; height: 16px; border: 1px solid #3b3b3b; margin-right: 8px; box-sizing: border-box;"></span>
                            <span style="flex: 1;">${qtyText} ${itemName}</span>
                        </li>`;
                    }

                    printHtml += '</ul></div>';
                }

                if (printHtml.trim() === '') {
                    printHtml = '<p>No items selected.</p>';
                }

                $('#print-shopping-list-modal #shopping-list-content').html(printHtml);
                $('#print-shopping-list-modal').fadeIn();

                const printShoppingListModal = document.getElementById('print-shopping-list-modal');
                const printShoppingListClose = document.getElementById('print-shopping-list-close');

                // Show modal
                printShoppingListModal.style.display = 'block';

                // Close modal handler (only bind once)
                printShoppingListClose.onclick = () => {
                    printShoppingListModal.style.display = 'none';
                };

                printShoppingListModal.onclick = (e) => {
                    if (e.target === printShoppingListModal) {
                        printShoppingListModal.style.display = 'none';
                    }
                };
            });

            $(document).on('click', '#download-pdf', function() {
                showLoader();
                const content = document.querySelector('#print-shopping-list-modal #shopping-list-content');

                if (!content || content.innerHTML.trim() === '') {
                    $('#errormodalmain').modal('show');
                    return;
                }

                // Create a temporary container with a header and the shopping list content
                const container = document.createElement('div');
                container.innerHTML = `
                    <div style="font-family: Arial, sans-serif; padding: 20px; max-width: 600px; margin: auto; color: #222;">
                        <h2 style="text-align: center; margin-bottom: 20px;">Shopping List</h2>
                        ${content.innerHTML}
                    </div>
                `;

                // Generate and download the PDF
                // html2pdf().set(options).from(container).save();
                html2pdf().from(container).set({
                    margin: 0.5,
                    filename: 'shopping_list.pdf',
                    html2canvas: { scale: 2 },
                    jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
                }).save();
                hideLoader();
                // Close the modal after download
                $('#print-shopping-list-modal').fadeOut();
                // Reset the modal content
                $('#print-shopping-list-modal #shopping-list-content').html('');
                // Hide the modal
                $('#shoppingListModal').modal('hide');

            });

            $(document).on('click', ".print-plan-btn", function () {
                showLoader();
                const planId = $(this).data("plan-id");
                const userId = $(this).data("user-id");

                // ✅ Bootstrap 5 modal instance
                const printPlanModalEl = document.getElementById('print-plan-modal');
                const printPlanModal = new bootstrap.Modal(printPlanModalEl);

                printPlanModal.show(); // ✅ Show the modal
                showLoader();
                // ✅ Reset preview content with loading text
                $("#pdf-preview").html(`<div class="py-4 text-center">
                    <div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>
                </div>`);

                // ✅ Fetch preview content via AJAX and inject
                fetch("{{ route('plans.preview', ':id') }}".replace(':id', planId) + "?user_id=" + userId)
                    .then(res => {
                        if (!res.ok) throw new Error("Failed to load preview");
                        return res.text();
                    })
                    .then(html => {
                        $("#pdf-preview").html(html); // ✅ Inject fetched HTML
                    })
                    .catch(err => {
                        $("#pdf-preview").html('<div class="py-4 text-danger">Error loading preview</div>');
                    });
                    hideLoader();
            });

            $('#print-plan-modal').on('hide.bs.modal', function () {
                $('.modal-backdrop').remove();
                $("#pdf-preview").html('');
            });

            $('#shoppingListModal').on('hidden.bs.modal', function () {
                $('.modal-backdrop').remove();
                $(this).find('.modal-body').html(''); // Clear modal content
            });

        });

        window.logoBase64 = 'data:image/jpg;base64,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'; // Replace with base64 logo image

        // window.logoBase64 = 'data:image/jpg;base64,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'; // Replace with base64 logo image

        function downloadPDF(planName = '') {
            showLoader();
            var element = document.getElementById("pdf-content");
            const images = element.querySelectorAll("img");

            const promises = Array.from(images).map(img => {
                return toDataURL(img.src).then(dataUrl => {
                    img.setAttribute("src", dataUrl);
                }).catch(err => {
                    console.warn("Image failed to load as base64:", img.src);
                });
            });

            Promise.all(promises).then(() => {

                // Set margins (in inches: 1in = 25.4mm = 72pt)
                const topMargin = 0.3; // ~15mm
                const bottomMargin = 1.0; // ~18mm (footer + buffer)
                const leftRightMargin = 0.3;

                const container = document.getElementById('pdf-content');
                addPageBreaks(container, topMargin, bottomMargin);
                var element = document.getElementById("pdf-content");

                html2pdf()
                    .set({
                        margin: [topMargin, leftRightMargin, bottomMargin, leftRightMargin],
                        filename: 'print-plan.pdf',
                        image: { type: 'jpeg', quality: 1 },
                        html2canvas: { scale: 2, useCORS: true },
                        jsPDF: {
                            unit: 'in',
                            format: 'a4',
                            orientation: 'portrait'
                        },
                        pagebreak: {
                            mode: ['css', 'legacy'],
                            before: '.page-break',
                            // Avoid breaking these elements - they should stay intact
                            avoid: ['.card-box', '.img-square', '.logo', 'h5']
                        }

                    })
                    .from(element)
                    .toPdf()
                    .get('pdf')
                    .then(pdf => {
                        const totalPages = pdf.internal.getNumberOfPages();
                        const pageWidth = pdf.internal.pageSize.getWidth();
                        const pageHeight = pdf.internal.pageSize.getHeight();

                        // Footer settings
                        const footerHeight = 0.5; // in inches
                        const footerY = pageHeight - bottomMargin + 0.15; // 0.15in above page bottom

                        // Logo
                        const logoWidth = 1.2;
                        const logoHeight = 0.2;
                        const logoX = 0.5;
                        const logoY = footerY + (footerHeight - logoHeight) / 2;

                        // Circle (page number)
                        const circleRadius = 0.15;
                        const circleCenterX = pageWidth / 2;
                        const circleCenterY = footerY + footerHeight / 2;

                        // Right-side text
                        const dateText = `${planName ?? ''} | ${new Date().toLocaleDateString('en-GB')}`;
                        const dateFontSize = 9; // smaller font
                        const dateColor = "#649ef7"; // blue
                        const dateX = pageWidth - 0.5;
                        const dateY = circleCenterY + 0.04; // align with circle


                        for (let i = 1; i <= totalPages; i++) {
                            pdf.setPage(i);
                            // Draw a white rectangle to clear the footer area
                            pdf.setFillColor(255, 255, 255);
                            pdf.rect(
                                0,
                                pageHeight - bottomMargin,
                                pageWidth,
                                footerHeight,
                                'F'
                            );

                            // Add logo (left, vertically centered)
                            if (window.logoBase64) {
                                pdf.addImage(window.logoBase64, 'PNG', logoX, logoY, logoWidth, logoHeight);
                            }

                            // Page number in white, centered in the circle
                            pdf.setTextColor(0, 116, 217);
                            pdf.setFontSize(11);
                            pdf.setFont(undefined, 'bold');

                            // Center vertically and horizontally
                            pdf.text(`${i}`, circleCenterX, circleCenterY, { align: 'center', baseline: 'middle' });

                            // Date text (right, blue, smaller font, vertically centered)
                            pdf.setTextColor(0, 116, 217); // blue
                            pdf.setFontSize(dateFontSize);
                            pdf.setFont(undefined, 'normal');
                            pdf.text(dateText, dateX, dateY, { align: 'right', baseline: 'middle', background: 'red' });
                        }
                    })
                    .save()
                    .then(() => {
                        hideLoader();
                    });
            });
        }

        function addPageBreaks(container, topMargin, bottomMargin) {
            // first generate temporary content in given size canvas
            const A4_WIDTH_IN = 8.2677;
            const A4_HEIGHT_IN = 11.6929;
            const PX_PER_IN = 96;
            const A4_WIDTH_PX = A4_WIDTH_IN * PX_PER_IN;
            const A4_HEIGHT_PX = A4_HEIGHT_IN * PX_PER_IN;

            clearPageBreaks(container);
            const tempContainer = createTempA4Container(container, A4_WIDTH_PX);

            // find useable canvas
            const usableInches = A4_HEIGHT_IN - (topMargin || 0) - (bottomMargin || 0);
            let usablePx = usableInches * PX_PER_IN;

            // also remove footer height from usablePx
            const footerHeight = 0.5;
            const footerHeightPx = footerHeight * PX_PER_IN;
            usablePx = usablePx - footerHeightPx; // this is usable px of one page
            tempUseableDivPx = usablePx;

            // fetch all main div inside of pdf-content
            const mainDivs = tempContainer.querySelectorAll('#pdf-content > *');

            // start filling content one by one in pages
            $currentPage = 1;

            mainDivs.forEach(div => {
                if (div.classList.contains('header-box') || div.classList.contains('pdf-footer')) {
                    tempUseableDivPx = tempUseableDivPx - div.offsetHeight;
                    return;
                }

                // if current box has class meal-block then check main divs inside if of it
                var mealBlockDivs = div.querySelectorAll('.meal-block > *');

                if (mealBlockDivs.length === 0) {
                    return;
                }

                // need to check if h5 and first div.card-box can be fit using usablePx
                const h5Height = mealBlockDivs[0].offsetHeight;
                const firstCardBox = mealBlockDivs[1];
                const firstCardBoxHeight = firstCardBox.offsetHeight;

                var addedFirstCardBox = false;
                if ((h5Height + firstCardBoxHeight) > tempUseableDivPx) {
                    $currentPage++;

                    var remainingHeight = (h5Height + firstCardBoxHeight)-tempUseableDivPx;

                    if(tempUseableDivPx < 50) {

                    } else {
                        addPageBreak(div, remainingHeight);
                    }
                    // add page break
                    // Insert page break before the meal block
                    // div.parentNode.insertBefore(pageBreak, div);
                    // console.log('pageBreak', pageBreak);

                    // reset tempUseableDivPx to mark it as new page
                    tempUseableDivPx = usablePx - h5Height - firstCardBoxHeight - 10;
                    addedFirstCardBox = true;
                }

                if (addedFirstCardBox === true) {
                    mealBlockDivs = Array.from(mealBlockDivs).slice(2);
                } else {
                    mealBlockDivs = Array.from(mealBlockDivs);
                }
                //now for each card box check if it can be fit using usablePx
                mealBlockDivs.forEach(cardBox => {
                    const cardBoxHeight = cardBox.offsetHeight;
                    if (cardBoxHeight > tempUseableDivPx) {
                        $currentPage++;

                        var remainingHeight = cardBoxHeight-tempUseableDivPx;

                        if(tempUseableDivPx < 50) {

                        } else {
                            addPageBreak(cardBox, remainingHeight);
                        }
                        // Insert page break before the card box
                        // cardBox.parentNode.insertBefore(pageBreak, cardBox);

                        // console.log('pageBreak1', pageBreak);

                        // reset tempUseableDivPx to mark it as new page
                        tempUseableDivPx = usablePx - cardBoxHeight - 10;
                    } else {
                        //deduct the cardBoxHeight from tempUseableDivPx
                        tempUseableDivPx = tempUseableDivPx - cardBoxHeight;
                    }
                });

            });

            var pdfContent = tempContainer.firstChild;
            // replace the tempContainer with originalContainer
            container.replaceWith(pdfContent);

            // tempContainer.remove();
        }

        function createTempA4Container(originalContainer, pageWidthPx) {
            // Create temporary container with A4 dimensions
            const tempContainer = document.createElement('div');
            // tempContainer.style.cssText = `
            // position: absolute;
            // top: -9999px;
            // left: -9999px;
            // width: ${pageWidthPx}px;
            // visibility: hidden;
            // overflow: visible;
            // font-family: inherit;
            // font-size: inherit;
            // line-height: inherit;
            // `;

            // Clone the content from original container
            const clonedContent = originalContainer.cloneNode(true);
            tempContainer.appendChild(clonedContent);

            // Add to DOM temporarily for measurements
            document.body.appendChild(tempContainer);

            return tempContainer;
        }

        // remove previously inserted page-breaks
        function clearPageBreaks(container) {
            const existing = container.querySelectorAll('.page-break');
            existing.forEach(e => e.remove());
        }

        function addPageBreak(element, remainingHeight) {
            const pageBreak = document.createElement('div');
                pageBreak.className = 'page-break';
                pageBreak.style.cssText = `
                page-break-before: always;
                break-before: page;
                margin: 0 0 10px 0;
                padding: 0;
                border: none;
                background: red;
                display: block;
            `;
            // pageBreak.style.height = `${remainingHeight}px`;
            // console.log('remainingHeight', remainingHeight);

            element.parentNode.insertBefore(pageBreak, element);
        }

        // Helper to convert images to base64
        function toDataURL(url) {
            return fetch(url, {
                    mode: 'cors'
                })
                .then(response => response.blob())
                .then(blob => new Promise((resolve, reject) => {
                    const reader = new FileReader();
                    reader.onloadend = () => resolve(reader.result);
                    reader.onerror = reject;
                    reader.readAsDataURL(blob);
                }));
        }

        $(document).ready(function () {
            // Open Bootstrap modal on meal click
            $('.quick-view-btn').on('click', function () {
                const user_meal_id = $(this).data('meal-id');
                const user_plan_id = $(this).data('user-plan-id');
                const user_sub_category_id = $(this).data('sub-category-id');
                const user_category_id = $(this).data('category-id');
                showLoader();

                $.ajax({
                    url: "{{ route('front.meal.details') }}",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        user_meal_id,
                        user_plan_id,
                        user_sub_category_id,
                        user_category_id
                    },
                    success: function (response) {
                        const meal = response.meal;

                        // 🖼️ Set meal title and description
                        $('#recipeDialogModal .modal-body .dialog-header h2').text(meal.meal.title || 'Meal');
                        $('#recipeDialogModal .modal-body .dialog-header p').text(meal.meal.description || '');

                        const imageUrl = meal.meal.image
                            ? `{{ asset('storage') }}/` + meal.meal.image
                            : `{{ asset('front/images/placeholder.png') }}`;
                        $('#recipeDialogModal .modal-body .dialog-img').attr('src', imageUrl);

                        // 🥣 Ingredients
                        let ingredientsHtml = '';
                        meal.user_items.forEach(function (userItem) {
                            const item = userItem.item;
                            if (!item) return;

                            const selectedUnits = item.selected_qty_unit || [];

                            // ✅ get all checked units
                            const checkedUnits = selectedUnits.filter(u => u.checked);

                            let qtyUnitText = '';
                            if (checkedUnits.length > 0) {
                                // format each unit (e.g. "250mL", "1 cup")
                                qtyUnitText = checkedUnits.map(u => {
                                    const unit = u.unit?.trim() || '';
                                    const noSpaceUnits = ['g', 'ml', 'mL'];
                                    const space = noSpaceUnits.includes(unit) ? '' : ' ';
                                    return `${u.qty}${space}${unit}`;
                                }).join(' or ');
                            } else {
                                // fallback to default
                                const unit = item.unit?.trim() || '';
                                const noSpaceUnits = ['g', 'ml', 'mL'];
                                const space = noSpaceUnits.includes(unit) ? '' : ' ';
                                qtyUnitText = `${item.qty}${space}${unit}`;
                            }

                            // ✅ final ingredient line
                            ingredientsHtml += `<li>${qtyUnitText} ${item.title}</li>`;
                        });

                        $('#recipeDialogModal .modal-body ul').html(ingredientsHtml);

                        // 📝 Instructions / Note
                        if (meal.meal.note && meal.meal.note.trim() !== '') {
                            $('#recipeDialogModal .modal-body .note').html(
                                `${meal.meal.note}`
                            ).show();
                            $('#recipeDialogModal .modal-body h3:contains("Instructions")').show();
                        } else {
                            $('#recipeDialogModal .modal-body .note').hide();
                            $('#recipeDialogModal .modal-body h3:contains("Instructions")').hide();
                        }

                        // 🔢 Nutrition Info
                        $('#recipeDialogModal .modal-body .nutrition-info').html(`
                            <span style="color: #a60015">●  <span style="color:rgba(59, 59, 59, 1)">Protein: ${(Number(response.totalProtein) || 0).toFixed(2)} g</span></span>
                            <span style="color: #3e8e00">●  <span style="color:rgba(59, 59, 59, 1)">Carb: ${(Number(response.totalCarbs) || 0).toFixed(2)} g</span></span>
                            <span style="color: #0077b6">●  <span style="color:rgba(59, 59, 59, 1)">Fat: ${(Number(response.totalFats) || 0).toFixed(2)} g</span></span>
                            <span style="color: #967500">●  <span style="color:rgba(59, 59, 59, 1)">Energy: ${(Number(response.totalEnergy) || 0).toFixed(2)} kJ</span></span>
                        `);

                        // Set data attributes for Smart Swap
                        $('#recipeDialogModal .modal-body .smart-swap-btn')
                            .attr('data-meal-id', user_meal_id)
                            .attr('data-user-plan-id', user_plan_id)
                            .attr('data-sub-category-id', user_sub_category_id)
                            .attr('data-category-id', user_category_id)
                            .attr('data-meal-name', meal.meal.title);

                        // 👁️ Show Bootstrap modal
                        const modal = new bootstrap.Modal(document.getElementById('recipeDialogModal'));
                        modal.show();
                        hideLoader();
                    },
                    error: function () {
                        $('#errormodalmain').modal('show');
                        hideLoader();
                    }
                });
            });
        });

        $(document).on('hide.bs.modal', '#recipeDialogModal', function () {
            // Clear the modal content when it is closed
            $('#recipeDialogModal .modal-body .dialog-header h2').text('');
            $('#recipeDialogModal .modal-body .dialog-header p').text('');
            $('#recipeDialogModal .modal-body .dialog-img').attr('src', '');
            $('#recipeDialogModal .modal-body ul').empty();
            $('#recipeDialogModal .modal-body .note').hide();
            $('#recipeDialogModal .modal-body h3:contains("Instructions")').hide();
            $('#recipeDialogModal .modal-body .nutrition-info').empty();
            $('.modal-backdrop').remove();
        });
    </script>

    <script src="{!! frontAssets('js/profile-landing-page.js') !!}"></script>
@endpush
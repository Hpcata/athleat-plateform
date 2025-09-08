@extends(frontView('layouts.app'))

@section('title', 'Injury Plan & Diet for Athletes | Performance Health')
@section('meta_description', 'Get a personalised athlete meal plan with Performance Health Support. Expert sports nutrition plans and diet strategies tailored to fuel performance and recovery.')

@push('styles')
    <link rel="stylesheet" href="{!! frontAssets('css/purchase-plans/injury-recovery.css') !!}">
@endpush

@section('content')
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
                            <p class="hero-subtitle-plan">{{ isset($sportGameData['sport_image']) ? $sportGameData['sport_name'] : '' }}</p>
                            <a href="{{ route('front.my-plans') }}" class="view-all-link"> View all plans </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="section-header">
                <h1>Injury & Recovery Plan</h1>
            </div>

            <!-- Button wrapper -->
            <div class="button-wrapper">
                <button class="btn btn-share ">
                    <a href="#" class="ms-0 print-plan-btn" data-user-id="{{ $user->id}}" data-plan-id="{{ $plan->id}}" style="text-decoration:none; color:#3b3b3b">View plan</a>
                </button>
                <button class="btn-outline btn" id="shoppingList" data-bs-toggle="modal"
                    data-bs-target="#shoppingListModal">Shopping list</button>
                <button class="btn btn-share coming-soon-popup" type="button" id="">
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
                </button>

            </div>

            <!-- Recovery Meals -->
            <div class="training-plan-wrapper">
                <div class="custom-accordion">
                    <button class="custom-accordion-header">
                        <div class="accordion-title tab-box-title">
                            <h2>Recovery Meals</h2>
                            <span>Post Injury</span>
                        </div>
                        <span class="arrow"></span>
                    </button>
                    <div class="custom-accordion-content">
                        <div class="accordion-body">
                            <section class="training-plan">
                                <div class="tab-box">
                                    <div class="tab-header">
                                        <p>Targeted nutrition and supplementation to optimise healing through reduced inflammation and fast tissue repair.</p>
                                    </div>
                                    @if (isset($userPlan) && $userPlan->status == 'active')
                                        @include('front.pages.partials.active-plan-section', ['userPlan' => $userPlan, 'isAdminView' => false, 'plan' => $plan])
                                    @endif
                                </div>
                            </section>
                            <section class="resources" style="margin-bottom: 0;">
                                <div class="section-header">
                                    <h2>Level-Up Library </h2>
                                </div>
                                <div class="" style="margin-bottom: 12px;">
                                    <div class="resource-card-custom resource-tip">
                                        <div class="tip-title">Kez's Tip of the Day</div>
                                        <div class="tip-text">“Prioritise rest, balanced nutrition, and gradual movement to speed up recovery.”</div>
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
                            </section>
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
                        <span class="arrow"></span>
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
                        </div>
                    </div>
                </div>
            </div>

            <!-- Optimize Performance -->
            <section class="optimize-performance">
                <div class="section-header">
                    <h2>Optimise your performance</h2>
                    <a href="/my-plans" class="see-all">All Plans</a>
                </div>

                @php
                    $mealCount = isset($userPlan->userMeals) ? $userPlan->userMeals->count() : 0;
                    $userPlan = $userPlan ?? null;

                    $latestMealImages = isset($userPlan->userMeals)
                        ? $userPlan
                            ->userMeals()
                            ->with('meal')
                            ->latest()
                            ->get()
                            ->map(function ($userMeal) {
                                return $userMeal->meal?->image ? asset('storage/' . $userMeal->meal->image) : null;
                            })
                            ->filter(function ($image) {
                                return !empty($image); // filters out null and empty strings
                            })
                            ->take(2)
                            ->values()
                            ->toArray()
                        : [];

                    $mealImage1 = $latestMealImages[0] ?? frontAssets('images/sports-training/fooditem1.webp');
                    $mealImage2 = $latestMealImages[1] ?? frontAssets('images/sports-training/fooditem6.webp');
                @endphp

                <label class="plan-subtitle-mob">Nutrition plans</label>
                <div class="consults-plans-grid">
                    <div class="plan-card-custom plan-competition">
                        <div class="">
                            <div class="plan-title">Competition Plan</div>
                            <div class="plan-desc">
                                Unlock your peak performance with a 24-hour Competition Nutrition Plan - Ensuring you’re hydrated,
                                fuelled & ON when it’s game time so that nutrition is never your weakness!
                            </div>
                            <div class="consult-user-row">
                                <img src="{{ $mealImage1 }}" class="consult-avatar" alt="Kerry O'Bryan, expert coach avatar" />
                                <img src="{{ $mealImage2 }}" class="consult-avatar overlap1"
                                    alt="Kerry O'Bryan, expert coach avatar" />
                                <span>{{ $mealCount }} meals • 18 Nutrition tips</span>
                            </div>
                        </div>
                        <button class="btn-consult" onclick="showLearnMoreTooltip(this, 'Coming Soon')">Learn more</button>
                    </div>
                    <div class="plan-card-custom plan-injury">
                        <div class="">
                            <div class="plan-title">Injury & Recovery Plan</div>
                            <div class="plan-desc">
                                Optimised nutrition to support soft tissue injury. Hold muscle, reduce
                                inflammation & limit fat gain with a
                                personalised plan that caters to where you're at. Faster recovery is the goal & nutrition is too
                                often overlooked!
                            </div>
                            <div class="consult-user-row">
                                <img src="{{ $mealImage1 }}" class="consult-avatar" alt="Kerry O'Bryan, expert coach avatar" />
                                <img src="{{ $mealImage2 }}" class="consult-avatar overlap1"
                                    alt="Kerry O'Bryan, expert coach avatar" />
                                <span>{{ $mealCount }} meals • 18 Nutrition tips</span>
                            </div>
                        </div>
                        <button class="btn-consult" onclick="showLearnMoreTooltip(this, 'Coming Soon')">Learn more</button>
                    </div>
                </div>

                <label class="plan-subtitle-mob">Consultations</label>
                <div class="consults-plans-grid grid-1">
                    <div class="consultation-card-custom">
                        <div class="consult-title">Private Consultations</div>
                        <div class="consult-desc">
                            Get answers from a real-life expert coaching Elite Athletes and Olympians.
                            An in-depth session to review your current approach, identify key opportunities, and give you practical,
                            tailored strategies to reach your sporting goals. Get expert support that meets you where you’re at,
                            with relevant education and answers to the questions that matter most.
                        </div>
                        <div class="consult-user-row">
                            <img src="https://booking.biohealthpassport.com.au/public/uploads/hero01.png" class="consult-avatar"
                                alt="Kerry O'Bryan, expert coach avatar" style="border:none;" />
                            <span style="padding-left:0">Kerry O'Bryan • 60 min</span>
                        </div>
                        <a href="https://booking.biohealthpassport.com.au/kerry-obryan" target="_blank"
                            class="text-decoration-none btn-consult">Book consult</a>
                    </div>
                </div>
            </section>
        </div>
    </main>

    @include('front.modal.shopping-list')
    @include('front.modal.print-shopping-list')
    @include('front.modal.meal-detail')
    @include('front.modal.smart-swap')
    @include('front.modal.smart-swap-items')
@endsection

@push('scripts')
    <script> // Open all accordions by default
        document.addEventListener('DOMContentLoaded', function () {
            const accordionHeaders = document.querySelectorAll('.custom-accordion-header');

            accordionHeaders.forEach(header => {
                header.addEventListener('click', function () {
                    this.classList.toggle('active');
                    const content = this.nextElementSibling;
                    if (content.style.maxHeight) {
                        content.style.maxHeight = null;
                    } else {
                        content.style.maxHeight = content.scrollHeight + 'px';
                    }
                });
            });


            accordionHeaders.forEach(header => {
                setTimeout(() => {
                    header.classList.add('active');
                    const content = header.nextElementSibling;
                    if (content) {
                        content.style.maxHeight = content.scrollHeight + 'px';
                    }
                }, 100);
            });
        });

        window["profile-landing-page"] = {
            userPlan: @json($userPlan ?? null),
            userId: {{ $userPlan->user_id ?? 0 }},
            isFreeUser: {{ $userPlan->free_user ?? 0 }},
            routes: {
                getProfileMeals: @json(route('front.get-profile-meals', ['plan' => 'PLAN_ID', 'category' => 'CATEGORY_ID'])),
                supplementScanner: @json(route('front.supplement-scanner')),
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
    <script src="{!! frontAssets('js/profile-landing-page.js') !!}"></script>
@endpush
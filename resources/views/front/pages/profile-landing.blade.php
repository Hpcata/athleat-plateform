@extends(frontView('layouts.app'))

@section('title', 'Best Sports Nutritionist & Dietitians Australia | Kerry O’Bryan')
@section('meta_description',
    'Performance Health Support offers expert care from top sports nutritionists, strength
    coaches, and sports dietitians in Australia to boost health and performance.')

@section('content')
    <!-- Main Content -->
    <main class="main">
        <div class="container">
            <!-- Welcome Section -->
            <section class="welcome-section">
                <div class="welcome-card hover-card" id="welcome-card">
                    <div class="welcome-message" style="position: relative;">
                        <h2>Welcome back legend! How's your week going?</h2>
                        <!--<<div class="welcome-row">
                            <a class="start-chat" id="start-chat-link">Start chat</a>
                            <span class="assistant-name">Kerry O'Bryan Virtual</span>
                        </div>
                        <img src="{{ frontAssets('images/profile.svg') }}" alt="Profile" class="profile-avatar-overlap" />
                        <div class="welcome-arrow"></div>-->
                    </div>
                </div>
            </section>

            @php
                // variables from controller: $viewState, $userPlan, $planPayment, $latestPayment, $paymentForQuestionnaireRoute, $questionnaireComplete
                $payment = $planPayment ?? $latestPayment ?? null;
                $questionnaireIsComplete = $questionnaireComplete ?? false;
            @endphp

            @if ($viewState === 'free_user')
                {{-- IMPORTANT: free users always see Purchase overlay regardless of questionnaire --}}
                @include('front.pages.partials.nutrition-plan-section', [
                    'title' => 'My Plans',
                    'actionText' => 'Purchase Plan',
                    'actionRoute' => 'front.my-plans',
                    'overlayText' => 'Purchase a personalised plan',
                    'overlayRoute' => route('front.my-plans')
                ])
            @elseif (in_array($viewState, ['continue_questionnaire', 'continue_questionnaire_for_plan']))
                @include('front.pages.partials.nutrition-plan-section', [
                    'title' => 'My Plans',
                    'actionText' => 'Purchase Plan',
                    'actionRoute' => 'front.my-plans',
                    'overlayText' => 'Continue your Questionnaire',
                    'hideActionText' => false,
                    'overlayRoute' => route('front.pre-plan-details', ['id' => $paymentForQuestionnaireRoute->id ?? null, 'user_id' => $paymentForQuestionnaireRoute->user_id ?? null])
                ])
            @elseif ($viewState === 'consultation_only_after_questionnaire' || $viewState === 'purchase_prompt')
                @include('front.pages.partials.nutrition-plan-section', [
                    'title' => 'My Plans',
                    'actionText' => 'Purchase Plan',
                    'actionRoute' => 'front.my-plans',
                    'overlayText' => 'Purchase a personalised plan',
                    'overlayRoute' => route('front.my-plans'),
                    'hideActionText' => false,
                ])
            @elseif ($viewState === 'preparing_plan')
                @include('front.pages.partials.plan-preparation-section', [
                    'plan' => $planPayment->plan ?? $userPlan->plan ?? null,
                    'isPreparingPlan' => true
                ])
            @elseif ($viewState === 'final_plan')
                @include('front.pages.partials.plan-preparation-section', [
                    'plan' => $planPayment->plan ?? $userPlan->plan ?? null,
                    'isPreparingPlan' => false,
                    'redirectRoute' => true,
                    'dynamicMealCount' => true ?? null
                ])
            @endif

            <!-- Challenges -->
            <section class="challenges">
                <div class="section-header">
                    <h2>Challenges</h2>
                    <a href="#" class="see-all coming-soon-popup">See all</a>
                </div>
                <div class="slider-container">
                    <div class="challenge-cards horizontal-scroll" style="overflow-x:auto;scroll-behavior:smooth;position:relative;">
                        @if ($userPlan && $userPlan->free_user)
                            <div class="purchase-plan-overlay subscribe">
                                <button class="purchase-plan-btn" onclick="showLearnMoreTooltip(this, 'Coming Soon')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="21"
                                        viewBox="0 0 22 21" fill="none">
                                        <path
                                            d="M21.573 8.84212L15.7828 6.88626L13.6206 0.419575C13.5798 0.29811 13.4973 0.191804 13.3854 0.116281C13.2734 0.040758 13.1378 9.0512e-07 12.9986 9.0512e-07C12.8594 9.0512e-07 12.7238 0.040758 12.6119 0.116281C12.4999 0.191804 12.4174 0.29811 12.3766 0.419575L10.2151 6.88626L4.42424 8.84212C4.29972 8.8843 4.19232 8.96025 4.11649 9.05975C4.04065 9.15926 4 9.27757 4 9.39878C4 9.51998 4.04065 9.63829 4.11649 9.7378C4.19232 9.83731 4.29972 9.91325 4.42424 9.95543L10.2125 11.9107L12.3746 18.5745C12.4144 18.6974 12.4967 18.8052 12.6091 18.8819C12.7216 18.9586 12.8582 19 12.9986 19C13.139 19 13.2756 18.9586 13.3881 18.8819C13.5005 18.8052 13.5828 18.6974 13.6226 18.5745L15.7854 11.9107L21.5736 9.95543C21.6987 9.9137 21.8067 9.8379 21.8829 9.73829C21.9592 9.63868 22.0001 9.52008 22 9.39857C21.9999 9.27705 21.9589 9.15849 21.8825 9.05896C21.8062 8.95943 21.6981 8.88373 21.573 8.84212Z"
                                            fill="#CCACFF" />
                                        <path
                                            d="M8.69123 16.2497L7.23038 15.7091L6.66196 13.6249C6.6256 13.4911 6.54623 13.373 6.43608 13.2888C6.32593 13.2045 6.19114 13.1589 6.05249 13.1589C5.91384 13.1589 5.77905 13.2045 5.6689 13.2888C5.55875 13.373 5.47938 13.4911 5.44302 13.6249L4.87459 15.7091L3.41375 16.2497C3.29288 16.2946 3.18864 16.3754 3.11502 16.4812C3.04141 16.5871 3.00195 16.7129 3.00195 16.8418C3.00195 16.9707 3.04141 17.0966 3.11502 17.2024C3.18864 17.3083 3.29288 17.3891 3.41375 17.4339L4.86701 17.9727L5.43986 20.2602C5.47408 20.3968 5.55296 20.518 5.66395 20.6046C5.77495 20.6912 5.9117 20.7383 6.05249 20.7383C6.19328 20.7383 6.33003 20.6912 6.44102 20.6046C6.55202 20.518 6.63089 20.3968 6.66512 20.2602L7.23796 17.9727L8.69123 17.4339C8.8121 17.3891 8.91634 17.3083 8.98995 17.2024C9.06357 17.0966 9.10302 16.9707 9.10302 16.8418C9.10302 16.7129 9.06357 16.5871 8.98995 16.4812C8.91634 16.3754 8.8121 16.2946 8.69123 16.2497Z"
                                            fill="#F5B266" />
                                        <path
                                            d="M5.95184 2.56593L4.45816 2.0133L3.90553 0.519616C3.86078 0.398536 3.78001 0.294072 3.67409 0.220294C3.56817 0.146515 3.44219 0.106963 3.31311 0.106963C3.18402 0.106963 3.05804 0.146515 2.95212 0.220294C2.8462 0.294072 2.76543 0.398536 2.72069 0.519616L2.16742 2.0133L0.67437 2.56593C0.553291 2.61068 0.448828 2.69145 0.37505 2.79737C0.301271 2.90329 0.261719 3.02927 0.261719 3.15835C0.261719 3.28744 0.301271 3.41342 0.37505 3.51934C0.448828 3.62526 0.553291 3.70603 0.67437 3.75077L2.16742 4.3034L2.72069 5.79709C2.76516 5.91844 2.84582 6.02321 2.95178 6.09723C3.05773 6.17125 3.18386 6.21094 3.31311 6.21094C3.44235 6.21094 3.56848 6.17125 3.67444 6.09723C3.78039 6.02321 3.86106 5.91844 3.90553 5.79709L4.45816 4.3034L5.95184 3.75077C6.07292 3.70603 6.17739 3.62526 6.25116 3.51934C6.32494 3.41342 6.36449 3.28744 6.36449 3.15835C6.36449 3.02927 6.32494 2.90329 6.25116 2.79737C6.17739 2.69145 6.07292 2.61068 5.95184 2.56593Z"
                                            fill="#A2C5FA" />
                                    </svg>
                                    Subscribe
                                </button>
                            </div>
                        @endif
                        <div class="challenge-card clickable hover-card"  onclick="showLearnMoreTooltip(this, 'Coming Soon')">
                            <img src="{{ frontAssets('images/challenges-card-img-3.webp') }}"
                                alt="Eat, Snap, Repeat: 3-Day Food Awareness Sprint thumbnail" />
                            <h3>Eat, Snap, Repeat: 3-Day Food Awareness Sprint</h3>
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <span>30</span>
                            </div>
                        </div>
                        <div class="challenge-card clickable hover-card"  onclick="showLearnMoreTooltip(this, 'Coming Soon')">
                            <img src="{{ frontAssets('images/Peanut-Butter-Breakfast-Oatmeal-Bowl-6 1 (1).webp') }}"
                                alt="Fat Loss Protein and Fats Diet Plan thumbnail" />
                            <h3>What do you really know about Supplements - Take Quiz</h3>
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <span>10</span>
                            </div>
                        </div>
                        <div class="challenge-card clickable hover-card"  onclick="showLearnMoreTooltip(this, 'Coming Soon')">
                            <img src="{{ frontAssets('images/challenges-card-img-2.webp') }}"
                                alt="Eat, Snap, Repeat: 3-Day Food Awareness Sprint thumbnail" />
                            <h3>Are You Eating for the Win? Take this Quiz</h3>
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <span>30</span>
                            </div>
                        </div>
                        <div class="challenge-card clickable hover-card"  onclick="showLearnMoreTooltip(this, 'Coming Soon')">
                            <img src="{{ frontAssets('images/challenges-card-img-4.webp') }}"
                                alt="Eat, Snap, Repeat: 3-Day Food Awareness Sprint thumbnail" />
                            <h3>Which of these foods could actually make you faster? 3 min read</h3>
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <span>30</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Resources and Tools -->
            <section class="resources">
                <div class="section-header">
                    <h2>Resources and tools</h2>
                    <a href="#" class="see-all coming-soon-popup">See all</a>
                </div>
                <div class="resources-custom-grid">
                    <div class="cursor-pointer resource-card-custom resource-supplement hover-card scanner-btn" id="scanner-btn" onclick="window.location.href='{{ route('front.supplement-scanner') }}'">
                        <img src="{{ frontAssets('images/cardbg.webp') }}" class="resource-bg-img"
                            alt="Supplement scanner background" />
                        <div class="icon-bg">
                            <img src="{{ frontAssets('images/camera.svg') }}" class="resource-bg-img"
                                alt="Camera icon for supplement scanner" />
                        </div>
                        <div class="resource-title">Supplement scanner</div>
                    </div>

                    <div class="cursor-pointer resource-card-custom resource-chat hover-card" id="chat-to-virtual-kez-btn">
                        <img src="{{ frontAssets('images/cardimg-2.webp') }}" class="resource-bg-img"
                            alt="Chat resource background" />
                    <!--    <div class="icon-bg">
                            <img src="{{ frontAssets('images/chat.svg') }}" class="resource-bg-img"
                                alt="Chat icon for virtual Kez" />
                        </div>
                        <div class="resource-title">Chat to Virtual Kez</div>-->
                    </div>
                    <div class="resource-card-custom resource-tip">
                        <div class="tip-title">Kez's Tip of the Day</div>
                        <div class="tip-text">
                            "Supplements can support your goals, but they're not shortcuts. Prioritise food first, and always choose batch-tested products to reduce your risk."
                        </div>
                    </div>
                </div>
                <div class="resources-custom-grid grid-2">
                    <div class="resource-card-custom resource-video clickable hover-card" onclick="showLearnMoreTooltip(this, 'Coming Soon')">
                        <div class="video-thumb-container"
                            onclick="openVideoPopup('https://www.w3schools.com/html/mov_bbb.mp4')">
                            <img src="{{ frontAssets('images/video-bg.webp') }}" class="video-thumb"
                                alt="Video thumbnail for whey protein post-training" />
                            <div class="video-icon-overlay">
                                 <img
                                src="{{ frontAssets('images/play.svg') }}"
                                class="video-thumb"
                                alt="play icon" />
                            </div>
                        </div>
                        <div class="video-info">
                            <div class="video-title">
                                At what age should supplements be on the table?
                            </div>
                            <div class="video-meta">
                                <span>
                                    <img src="{{ frontAssets('images/Clock.webp') }}" class="clock-img" alt="Clock icon" width="16" height="16" />
                                </span>
                                <span>5 min • Video</span>
                            </div>
                        </div>
                    </div>

                    <div class="resource-card-custom resource-video clickable hover-card" onclick="showLearnMoreTooltip(this, 'Coming Soon')">
                        <div class="video-thumb-container">
                            <img src="{{ frontAssets('images/gym.webp') }}" class="video-thumb"
                                alt="Gym video thumbnail" />
                        </div>
                        <div class="video-info">
                            <div class="video-title">
                                How to build systems that maximise gains
                            </div>
                            <div class="video-meta">
                                <span>
                                    <img src="{{ frontAssets('images/Clock.webp') }}" class="clock-img" alt="Clock icon" width="16" height="16" />
                                </span>
                                <span>5 min • Short read</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Optimize Performance -->
            <section class="optimize-performance">
                <div class="section-header">
                    <h2>Optimise your performance</h2>
                    <a href="/my-plans" class="see-all">All Plans</a>
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

    <!-- Custom Congrats Modal -->
    <div class="modal" id="customCongratsModal" tabindex="-1" aria-labelledby="customCongratsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="p-0 modal-body">
                    <div class="recipe-dialog">
                        <button class="dialog-close" data-bs-dismiss="modal" aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                                fill="none">
                                <path
                                    d="M0.366171 2.13422C-0.122057 1.64599 -0.122057 0.8544 0.366171 0.366171C0.8544 -0.122057 1.64599 -0.122057 2.13422 0.366171L9.99993 8.23198L17.8655 0.366388C18.3538 -0.12184 19.1454 -0.12184 19.6335 0.366388C20.1217 0.854617 20.1217 1.64621 19.6335 2.13444L11.7681 9.99993L19.6335 17.8655C20.1217 18.3538 20.1217 19.1454 19.6335 19.6335C19.1454 20.1217 18.3538 20.1217 17.8655 19.6335L9.99993 11.7681L2.13422 19.6338C1.64599 20.1221 0.8544 20.1221 0.366171 19.6338C-0.122057 19.1456 -0.122057 18.3539 0.366171 17.8657L8.23198 9.99993L0.366171 2.13422Z"
                                    fill="#3B3B3B" />
                            </svg>
                        </button>
                        <div class="dialog-content">
                            <div class="dialog-main-view">
                                <div class="dialog-header" style="position:relative;">
                                    <div class="custom-popup-main-row">
                                        <div class="custom-popup-text-section">
                                            <div class="custom-popup-text">
                                                <h3>Congrats, you're in!</h3>
                                                <p>You've just taken the first step toward smarter fuel, stronger
                                                    performance, and better results. We're stoked to have you - let's get
                                                    started. 🚀</p>
                                                <h4>Your quiz score as promised </h4>
                                            </div>
                                        </div>
                                        <div class="custom-popup-gauge-section">
                                            <div class="custom-popup-gauge">
                                                <div class="score-meter-box">
                                                    <div class="score-meter-text">
                                                        <span class="meter-text-01">Needs <br>work </span>
                                                        <span class="meter-text-02">Pretty <br>ordinary</span>
                                                        <span class="meter-text-03">Not bad</span>
                                                        <span class="meter-text-04">Good</span>
                                                    </div>
                                                    <div class="score-meter-box-frame">
                                                        <svg version="1.1" x="0px" y="0px" viewBox="0 0 500 243"
                                                            style="enable-background:new 0 0 500 243;"
                                                            xml:space="preserve">
                                                            <path
                                                                d="M0,0v243h500V0H0z M474.7,233.7h-79.1c-4.9,0-9.2-3.6-9.9-8.5c-9.6-65.5-66.1-115.9-134.3-115.9s-124.6,50.3-134.3,115.9c-0.7,4.9-4.9,8.5-9.9,8.5H28.2c-5.9,0-10.5-5.1-10-11c11.3-119,111.4-212,233.2-212s221.9,93.1,233.2,212C485.2,228.6,480.6,233.7,474.7,233.7z"
                                                                fill="#ffffff" />
                                                        </svg>
                                                        <div class="bgradient-bg"
                                                            style="background: conic-gradient(from -1.65deg at 48.15% 84.72%, #FF9500 -33.16deg, #FFDE48 31.45deg, #03741B 91.78deg, #CF080A 265.07deg, #FF9500 326.84deg, #FFDE48 391.45deg);">
                                                        </div>
                                                    </div>
                                                    <span class="meter-arrow nutrition-result"
                                                        style="transform: rotate(90deg);">
                                                        <svg version="1.1" x="0px" y="0px" viewBox="0 0 133 22"
                                                            style="enable-background:new 0 0 133 22;"
                                                            xml:space="preserve">
                                                            <path
                                                                d="M91.8,0.4L3.4,8.7c-2.5,0.2-2.5,3.8,0,4.1l88.4,8.9c20.5-0.4,12.7-0.4,20.5-0.4c11.8,0,19.2,1.6,19.2-10.1c0-11.8-10-10.2-21.7-10.3C101.9,0.8,112,0.9,91.8,0.4z" />
                                                        </svg>
                                                    </span>
                                                </div>
                                                <h4 class="mt-4">General Nutrition Knowledge</h4>
                                                <h3 class="mt-1 text-black nutrition-percentage">--%</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <p>If you have any gaps, we are here to help and will add some tips and info into the
                                    Level-up library for you.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('front.modal.shopping-list')
    @include('front.modal.print-shopping-list')
    @include('front.modal.meal-detail')
    @include('front.modal.smart-swap')
    @include('front.modal.smart-swap-items')
@endsection

@push('scripts')
    <script>
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
        window.addEventListener("load", () => {
        document.body.classList.add("page-loaded");
        });
    </script>
    <script src="{!! frontAssets('js/profile-landing-page.js') !!}"></script>
@endpush

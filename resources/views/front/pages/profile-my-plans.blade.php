@extends(frontView('layouts.app'))

@section('title', 'My Plans | Performance Health Support')
@section('meta_description', 'Explore and manage your personalised nutrition and training plans with Performance Health Support. Achieve your health and performance goals with expert guidance from Australia\'s leading sports nutritionists and coaches.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('front/css/profile-my-plan.css') }}">
@endpush

@section('content')
    <main class="main">
        <div class="container">
            <!-- Resources and Tools -->
            <section class="resources myplans-section">
                <div class="section-header">
                    <h2>My Plans</h2>
                </div>
                <div class="card-row">
                    @if(isset($isQuestionnaireSubmitted) && !$isQuestionnaireSubmitted->is_complete)
                        {{-- need to show only once per user if we got multiple payments --}}
                        @include('front.pages.partials.nutrition-plan-section', [
                            'title' => 'My Plans',
                            'actionText' => 'Purchase Plan',
                            'actionRoute' => 'front.my-plans',
                            'overlayText' => 'Continue your Questionnaire',
                            'hideActionText' => true,
                            'overlayRoute' => route('front.pre-plan-details', ['user_id' => $payment->user_id ?? null])
                        ])
                    @else
                        @if(isset($plansWithAnimation) && count($plansWithAnimation) > 0)
                            <!-- Plans with animation (questionnaire completed but meals not sent) -->
                            @foreach($plansWithAnimation as $planData)
                                @if(isset($planData['plan']) && $planData['plan'])
                                    @include('front.pages.plan-cards.card-with-animation', [
                                        'plan' => $planData['plan'],
                                        'userPlan' => $planData['userPlan'] ?? null,
                                        'payment' => $planData['payment'] ?? null
                                    ])
                                @endif
                            @endforeach
                        @endif

                        @if(isset($plansWithoutAnimation) && count($plansWithoutAnimation) > 0)
                            <!-- Plans without animation (meals sent) -->
                            @foreach($plansWithoutAnimation as $planData)
                                @if(isset($planData['plan']) && $planData['plan'])
                                    @include('front.pages.plan-cards.card-without-animation', [
                                        'plan' => $planData['plan'],
                                        'userPlan' => $planData['userPlan'] ?? null,
                                        'payment' => $planData['payment'] ?? null,
                                        'redirectRoute' => true,
                                        'dynamicMealCount' => true
                                    ])
                                @endif
                            @endforeach
                        @endif

                        @if((!isset($plansWithAnimation) || count($plansWithAnimation) == 0) && (!isset($plansWithoutAnimation) || count($plansWithoutAnimation) == 0))
                            <div class="consults-plans-grid" style="margin-bottom: 0;">
                                <div class="no-plan-container">
                                    <img src="{{ asset('front/images/my-plan/vector.svg') }}" alt="No Plan Yet" class="no-plan-image" />
                                    <h2 class="no-plan-title">Uh-oh! You don't have a plan yet.</h2>
                                    <p class="no-plan-description">
                                        Get ahead of your competition by signing up for a plan below.
                                    </p>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </section>

            <!-- Recommended plan -->
            <section class="optimize-performance x">
                <div class="section-header">
                    <h2>All Plans</h2>
                </div>

                <!-- Nutrition plans -->
                <div class="card-row">
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
                </div>

                <!-- Consultations -->
                <div class="card-row">
                    <label class="plan-subtitle-mob">Consultations</label>
                    <div class="consults-plans-grid grid-1">
                                @include('front.pages.plan-cards.card-consultations')
                        </div>
                </div>
            </section>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('.coming-soon-popup').click(function () {
                $('#comingSoonModal').modal('show');
            });
        });
    </script>
@endpush
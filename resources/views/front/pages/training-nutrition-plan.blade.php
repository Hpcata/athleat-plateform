@extends(frontView('layouts.app'))

@section('title', 'Training Nutrition Plan & Diet for Athletes | Performance Health')
@section('meta_description', 'Get a personalized athlete meal plan with Performance Health Support. Expert sports nutrition plans and diet strategies tailored to fuel performance and recovery.')

@php
    $intresetsmallimg1 = $intresetsmallimg2 = $intrestimg1 = $intrestimg2 = $blueBadgeImg = '';
@endphp
<style>
      @media only screen and (max-width: 600px){
            .sport-nutrition-promo_bg
            {
                right:4px !important;
                height:90px !important;
                width:90px !important;
            }
            .pricing-buttons .btn-login{height:73px;}
        }
</style>
@section('content')
    @if(isset($page->sections))
        @foreach($page->sections as $section)
            @if($section->section_type == \App\Models\Section::TYPE_TRAINING_PLAN_MAIN_BANNER && $section->enabled == 1) <!-- done -->
                    @php
                        $bannerImage = '';
                        if (isset($section->banner_image[0])) {
                            $bannerImage = $section->banner_image[0];
                        }
                    @endphp
                    <div class="hero-section-landing"
                        style="background-image: url('{{ webAssets('storage/' . $bannerImage) }}')">
                        <div class="container-homepage">
                            <div class="hero-content-fixed">
                                <h1 class="hero-title-landing">{{ $section->title }}</h1>
                                <button class="btn-learn-more-blue"
                                    data-bs-toggle="modal"
                                    data-bs-target="#planChooseModal">
                                    Purchase plan
                                </button>
                            </div>
                        </div>
                    </div>
            @endif

            @if($section->section_type == \App\Models\Section::TYPE_BUILT_FOR_REAL_RESULT && $section->enabled == 1) <!-- done -->
                <section class="about-section training-nutrition-landing">
                    <div class="container-homepage">
                        <div class="about-content-wrapper">
                            {!! $section->content !!}
                        </div>
                    </div>
                    <div class="about-image-container">
                        @if(isset($section->image[0]) && !empty($section->image[0]))
                            <img src="{{ asset('storage/' . $section->image[0]) }}" alt="about" class="img-fluid about-image" />
                        @endif
                    </div>
                </section>
            @endif

            @if($section->section_type == \App\Models\Section::TYPE_PLAN_INCLUSIONS && $section->enabled == 1) <!-- done -->
                @php
                    $backgroundImage = '';
                    if (isset($section->banner_image[0])) {
                        $backgroundImage = asset('storage/' . $section->banner_image[0]);
                    }
                    if (isset($section->image[0])) {
                        $blueBadgeImg = asset('storage/' . $section->image[0]);
                    }
                @endphp
                <section class="plan-inclusion-section" style="background-image: url('{{ $backgroundImage }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
                    <div class="container-homepage">
                        <div class="title-content">
                            <h2 class="title">{{ $section->title }}</h2>
                        </div>
                        {!! $section->content !!}

                        {{-- <button id="TPMAIU-purchase-plan-btn"
                            data-bs-toggle="modal"
                            data-bs-target="#planChooseModal">
                            Purchase plan
                        </button> --}}
                    </div>
                </section>
            @endif

            @if($section->section_type == \App\Models\Section::TYPE_PLAN_INTERESTS && $section->enabled == 1) <!-- done -->
                @php
                    if (isset($section->banner_image[0])) {
                        $intresetsmallimg1 = asset('storage/' . $section->banner_image[0]);
                    }
                    if (isset($section->banner_image[1])) {
                        $intresetsmallimg2 = asset('storage/' . $section->banner_image[1]);
                    }
                    if (isset($section->image[0])) {
                        $intrestimg1 = asset('storage/' . $section->image[0]);
                    }
                    if (isset($section->image[1])) {
                        $intrestimg2 = asset('storage/' . $section->image[1]);
                    }
                @endphp
                <section class="recommended-plans-section">
                    <div class="container-homepage">
                        <h2 class="section-title">{{ $section->title }}</h2>
                        {!! $section->content !!}
                    </div>
                </section>
            @endif
        @endforeach
    @endif

    @include('front.pages.partials.purchase-plan-register')
    @include('front.pages.partials.purchase-plan-login')

    @include('components.plan-modals', [
        'userEmail' => Auth::check() ? Auth::user()->email : 'guest@example.com',
        'planDetails' => $planDetails,
        'consultations' => $consultations
    ])

@endsection

@push('scripts')
    <script>
        window.purchasePlanConfig = {
            paymentUrl: "{{ route('process.payment') }}",
            validateCouponCodeUrl: "{{ route('validate.coupon.code') }}",
            getDefaultPlanDetailsUrl: "{{ route('front.get-default-plan-details', ':id') }}",
            csrfToken: "{{ csrf_token() }}",
            stripeKey: "{{ config('services.stripe.public_key') }}",
            isAuthenticated: @json(Auth::guard('web')->check()),
            userId: {{ Auth::check() ? Auth::user()->id : 'null' }},
            isAdmin: {{ Auth::check() && Auth::user()->is_superadmin == 1 ? 'true' : 'false' }},
            userData: @json(Auth::check() ? [
                'name' => Auth::user()->first_name . ' ' . Auth::user()->last_name,
                'email' => Auth::user()->email,
                'phone' => Auth::user()->phone ?? ''
            ] : null),
            env: "{{ env('APP_ENV') }}"
        };


        var intresetsmallimg1 = "{{ $intresetsmallimg1 }}";
        var intresetsmallimg2 = "{{ $intresetsmallimg2 }}";
        var intrestimg1 = "{{ $intrestimg1 }}";
        var intrestimg2 = "{{ $intrestimg2 }}";
        var blueBadgeImg = "{{ $blueBadgeImg }}";

        if (intresetsmallimg1 !== '') {
            document.getElementById('TPMAIU-card1-icon').src = intresetsmallimg1;
        }
        if (intresetsmallimg2 !== '') {
            document.getElementById('TPMAIU-card1').src = intresetsmallimg2;
        }
        if (intrestimg1 !== '') {
            document.getElementById('TPMAIU-card2-icon').src = intrestimg1;
        }
        if (intrestimg2 !== '') {
            document.getElementById('TPMAIU-card2').src = intrestimg2;
        }
        if (blueBadgeImg !== '') {
            document.getElementById('blue-badge-img').src = blueBadgeImg;
        }

        //btn-learn-more-blue
        // $('.plan-inclusion-section .btn-learn-more-blue').on('click', function() {
        //     $('#TPMAIU-purchase-plan-btn').click();
        // });

        const injuryPlanLink = document.getElementById('injury-plan-link');
        const trainingPlanLink = document.getElementById('training-plan-link');

        if (injuryPlanLink) {
            injuryPlanLink.dataset.url = "{{ route('front.injury.recovery.plan') }}";
            injuryPlanLink.addEventListener('click', function() {
                window.location.href = injuryPlanLink.href;
            });
        }

        if (trainingPlanLink) {
            trainingPlanLink.dataset.url = "{{ route('front.training.nutrition.plan') }}";
            trainingPlanLink.addEventListener('click', function() {
                window.location.href = trainingPlanLink.href;
            });
        }

        $('#competition-plan-link').on('click', function() {
            showLearnMoreTooltip(this, 'Coming Soon')
        });

        $('#surgery-plan-link').on('click', function() {
            showLearnMoreTooltip(this, 'Coming Soon')
        });

        $(document).ready(function() {
            if($('.plan-inclusion-section .pricing-section .pricing-amount').length > 0) {
                $('.plan-inclusion-section .pricing-section .pricing-amount').html('${{ number_format($planDetails?->price, 0) }} AUD');
            }

            // Check if there's a pending plan purchase after page refresh
            if (typeof window.handlePendingPlanPurchase === 'function') {
                window.handlePendingPlanPurchase();
            }
        });
    </script>

    <script src="{!! frontAssets('js/purchase-plan.js') !!}"></script>
@endpush


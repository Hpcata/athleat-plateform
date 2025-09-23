@if(isset($plan) && $plan)
    <div class="plan-card-custom">
        <div class="">
            <div class="plan-title">{{ $plan->name ?? 'Competition Plan' }}</div>
            <div class="plan-desc">
                {{ strip_tags($plan->description) ?? 'Unlock your peak performance with a 24-hour Nutrition Plan - Ensuring you\'re hydrated, fuelled & ON when it\'s game time so that nutrition is never your' }}
            </div>
            <div class="consult-user-row">
                <img src="{{ asset('front/images/circled-meal-1.svg') }}" class="consult-avatar" alt="Kerry O'Bryan, expert coach avatar" />
                <img src="{{ asset('front/images/circled-meal-2.svg') }}" class="consult-avatar overlap1" alt="Kerry O'Bryan, expert coach avatar" />
                <span>21 meals customised for you</span>
            </div>
        </div>
        @if($plan->name == 'Training Nutrition Plan')
            <button class="btn-consult" onclick="window.location.href='{{ route('front.training.nutrition.plan') }}'">Learn more</button>
        @elseif($plan->name == 'Injury & Recovery Plan')
            <button class="btn-consult" onclick="window.location.href='{{ route('front.injury.recovery.plan') }}'">Learn more</button>
        @elseif($plan->name == 'Competition Plan')
            <button class="btn-consult" onclick="showLearnMoreTooltip(this, 'Coming Soon')">Learn more</button>
        @elseif($plan->name == 'Injury Recovery + Post Surgery')
            <button class="btn-consult" onclick="showLearnMoreTooltip(this, 'Coming Soon')">Learn more</button>
        @else
            <button class="btn-consult" onclick="showLearnMoreTooltip(this, 'Coming Soon')">Learn more</button>
        @endif
    </div>
@endif
<section class="training-plan plan-detail-page">
    @if (isset($userPlan) && isset($userPlan?->plan))
        <div class="section-header">
            <h2>{{ $userPlan->plan->name }}</h2>
            <a href="{{ route('front.plans.details', ['id' => $userPlan->plan->id, 'user_id' => $userPlan->user->id]) }}" class="see-all">See Plan</a>
        </div>
    @endif

    {{-- Tabs --}}
    @if (isset($userPlan) && isset($userPlan->userCategories) && $userPlan->userCategories->count() > 0)
        <div class="tabs">
            @php $firstTab = true; @endphp
            @foreach ($userPlan->userCategories->where('user_plan_id', $userPlan->id) as $userCategory)
                @php
                    $category = $userCategory->category;
                    $hasValidMeal = $userCategory
                        ->userSubCategories()
                        ->where('user_plan_id', $userPlan->id)
                        ->whereHas('userMeals', function ($q) use ($userPlan, $userCategory) {
                            $q->where('user_plan_id', $userPlan->id)->where(
                                'user_category_id',
                                $userCategory->id,
                            );
                        })
                        ->exists();
                @endphp

                @if ($hasValidMeal && $category)
                    <button class="tab {{ $firstTab ? 'active' : '' }}" data-category-id="{{ $category->id }}" data-plan-id="{{ $userPlan->id }}">
                        {{ $category->title }}
                    </button>
                    @php $firstTab = false; @endphp
                @endif
            @endforeach
        </div>

        <div class="tab-content challenges">
            <div class="slider-wrapper" style="position:relative;">
                <button class="left-arrow slider-arrow">
                    <svg width="18" height="24" viewBox="0 0 18 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <polyline points="14,4 4,16 14,28" stroke="#080808" stroke-width="3" fill="none" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </button>
                <div class="challenge-cards horizontal-scroll" style="overflow-x:auto;scroll-behavior:smooth;"
                    id="meal-cards-wrapper">
                    <p>Loading meals...</p>
                </div>
                <button class="right-arrow slider-arrow">
                    <svg width="18" height="24" viewBox="0 0 18 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <polyline points="4,4 14,16 4,28" stroke="#080808" stroke-width="3" fill="none" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
        </div>
    @endif
</section>
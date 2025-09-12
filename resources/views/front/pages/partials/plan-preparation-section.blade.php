<section class="challenges">
    <div class="section-header">
        <h2>My Plan</h2>
        <a href="{{ route('front.my-plans') }}" class="view-all-link"> View all plans </a>
    </div>
    @if ($isPreparingPlan)
        @include('front.pages.plan-cards.card-with-animation', ['plan' => $plan])
    @else
        @include('front.pages.plan-cards.card-without-animation', ['plan' => $plan])
    @endif
</section>

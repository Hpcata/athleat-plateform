<section class="challenges">
    <div class="section-header">
        <h2>My Plan</h2>
    </div>
    @if ($isPreparingPlan)
        @include('front.pages.plan-cards.card-with-animation', ['plan' => $plan])
    @else
        @include('front.pages.plan-cards.card-without-animation', ['plan' => $plan])
    @endif
</section>

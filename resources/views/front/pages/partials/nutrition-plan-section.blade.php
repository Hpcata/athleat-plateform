<section class="challenges">
    @if(!$hideActionText)
        <div class="section-header">
            <h2>{{ $title }}</h2>
            <a href="{{ route($actionRoute) }}" class="see-all">{{ $actionText }}</a>
        </div>
    @endif
    <div class="slider-container">
        <div class="challenge-cards horizontal-scroll" style="overflow-x:auto;scroll-behavior:smooth; position:relative;">
            @if($overlayText == 'Continue your Questionnaire')
                <div class="purchase-plan-overlay complete-questionnaire">
                    <a href="{{ $overlayRoute }}" class="purchase-plan-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                            <path
                                d="M13.5 16C13.5 16.5523 13.0523 17 12.5 17C11.9477 17 11.5 16.5523 11.5 16C11.5 15.4477 11.9477 15 12.5 15C13.0523 15 13.5 15.4477 13.5 16Z"
                                fill="#FBBC05" />
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M2.5 7C2.5 4.23858 4.73858 2 7.5 2H17.5C20.2614 2 22.5 4.23858 22.5 7V17C22.5 19.7614 20.2614 22 17.5 22H7.5C4.73858 22 2.5 19.7614 2.5 17V7ZM7.5 4C5.84315 4 4.5 5.34315 4.5 7V17C4.5 18.6569 5.84315 20 7.5 20H17.5C19.1569 20 20.5 18.6569 20.5 17V7C20.5 5.34315 19.1569 4 17.5 4H7.5ZM12.5 7C13.0523 7 13.5 7.44772 13.5 8V12C13.5 12.5523 13.0523 13 12.5 13C11.9477 13 11.5 12.5523 11.5 12V8C11.5 7.44772 11.9477 7 12.5 7Z"
                                fill="#FBBC05" />
                        </svg>
                        Complete Your Questionnaire
                    </a>
                </div>
                <div class="fade-full"></div>
            @else
                <div class="purchase-plan-overlay">
                    <a href="{{ $overlayRoute }}" class="purchase-plan-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="21" viewBox="0 0 22 21" fill="none">
                            <path d="M21.573 8.84212L15.7828 6.88626L13.6206 0.419575C13.5798 0.29811 13.4973 0.191804 13.3854 0.116281C13.2734 0.040758 13.1378 9.0512e-07 12.9986 9.0512e-07C12.8594 9.0512e-07 12.7238 0.040758 12.6119 0.116281C12.4999 0.191804 12.4174 0.29811 12.3766 0.419575L10.2151 6.88626L4.42424 8.84212C4.29972 8.8843 4.19232 8.96025 4.11649 9.05975C4.04065 9.15926 4 9.27757 4 9.39878C4 9.51998 4.04065 9.63829 4.11649 9.7378C4.19232 9.83731 4.29972 9.91325 4.42424 9.95543L10.2125 11.9107L12.3746 18.5745C12.4144 18.6974 12.4967 18.8052 12.6091 18.8819C12.7216 18.9586 12.8582 19 12.9986 19C13.139 19 13.2756 18.9586 13.3881 18.8819C13.5005 18.8052 13.5828 18.6974 13.6226 18.5745L15.7854 11.9107L21.5736 9.95543C21.6987 9.9137 21.8067 9.8379 21.8829 9.73829C21.9592 9.63868 22.0001 9.52008 22 9.39857C21.9999 9.27705 21.9589 9.15849 21.8825 9.05896C21.8062 8.95943 21.6981 8.88373 21.573 8.84212Z" fill="#CCACFF" />
                            <path d="M8.69123 16.2497L7.23038 15.7091L6.66196 13.6249C6.6256 13.4911 6.54623 13.373 6.43608 13.2888C6.32593 13.2045 6.19114 13.1589 6.05249 13.1589C5.91384 13.1589 5.77905 13.2045 5.6689 13.2888C5.55875 13.373 5.47938 13.4911 5.44302 13.6249L4.87459 15.7091L3.41375 16.2497C3.29288 16.2946 3.18864 16.3754 3.11502 16.4812C3.04141 16.5871 3.00195 16.7129 3.00195 16.8418C3.00195 16.9707 3.04141 17.0966 3.11502 17.2024C3.18864 17.3083 3.29288 17.3891 3.41375 17.4339L4.86701 17.9727L5.43986 20.2602C5.47408 20.3968 5.55296 20.518 5.66395 20.6046C5.77495 20.6912 5.9117 20.7383 6.05249 20.7383C6.19328 20.7383 6.33003 20.6912 6.44102 20.6046C6.55202 20.518 6.63089 20.3968 6.66512 20.2602L7.23796 17.9727L8.69123 17.4339C8.8121 17.3891 8.91634 17.3083 8.98995 17.2024C9.06357 17.0966 9.10302 16.9707 9.10302 16.8418C9.10302 16.7129 9.06357 16.5871 8.98995 16.4812C8.91634 16.3754 8.8121 16.2946 8.69123 16.2497Z" fill="#F5B266" />
                            <path d="M5.95184 2.56593L4.45816 2.0133L3.90553 0.519616C3.86078 0.398536 3.78001 0.294072 3.67409 0.220294C3.56817 0.146515 3.44219 0.106963 3.31311 0.106963C3.18402 0.106963 3.05804 0.146515 2.95212 0.220294C2.8462 0.294072 2.76543 0.398536 2.72069 0.519616L2.16742 2.0133L0.67437 2.56593C0.553291 2.61068 0.448828 2.69145 0.37505 2.79737C0.301271 2.90329 0.261719 3.02927 0.261719 3.15835C0.261719 3.28744 0.301271 3.41342 0.37505 3.51934C0.448828 3.62526 0.553291 3.70603 0.67437 3.75077L2.16742 4.3034L2.72069 5.79709C2.76516 5.91844 2.84582 6.02321 2.95178 6.09723C3.05773 6.17125 3.18386 6.21094 3.31311 6.21094C3.44235 6.21094 3.56848 6.17125 3.67444 6.09723C3.78039 6.02321 3.86106 5.91844 3.90553 5.79709L4.45816 4.3034L5.95184 3.75077C6.07292 3.70603 6.17739 3.62526 6.25116 3.51934C6.32494 3.41342 6.36449 3.28744 6.36449 3.15835C6.36449 3.02927 6.32494 2.90329 6.25116 2.79737C6.17739 2.69145 6.07292 2.61068 5.95184 2.56593Z" fill="#A2C5FA" />
                        </svg>
                        {{ $overlayText }}
                    </a>
                </div>
                <div class="fade-full"></div>
            @endif

            <div class="challenge-card clickable hover-card">
                <img src="{{ frontAssets('images/personalised-1.webp') }}" alt="personalised-1" />
                <h3>Creamy oats topped with banana, berries, and chia for lasting energy.</h3>
            </div>
            <div class="challenge-card clickable hover-card">
                <img src="{{ frontAssets('images/personalised-2.webp') }}" alt="personalised-2" />
                <h3>Char-grilled chicken with crisp salad in a soft pita pocket.</h3>
            </div>
            <div class="challenge-card clickable hover-card">
                <img src="{{ frontAssets('images/personalised-3.webp') }}" alt="personalised-3" />
                <h3>Grilled chicken with rice, beans, corn, avocado, and fresh veg.</h3>
            </div>
            <div class="challenge-card clickable hover-card">
                <img src="{{ frontAssets('images/personalised-4.webp') }}" alt="personalised-4" />
                <h3>Glazed tofu with broccoli, carrots, and capsicum over brown rice.</h3>
            </div>
        </div>
    </div>
</section>

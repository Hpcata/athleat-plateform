@extends(frontView('layouts.app'))

@section('title', 'Private Consultations | Performance Health')
@section('meta_description', 'Get expert nutrition advice from Kerry O\'Bryan. 30 and 60 minute private consultations tailored to your athletic goals and performance needs.')

@php
$intresetsmallimg1 = $intresetsmallimg2 = $intresetsmallimg3 = $intrestimg1 = $intrestimg2 = $intrestimg3 = $blueBadgeImg = '';
@endphp
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('content')
    @if(isset($page->sections))
        @foreach($page->sections as $section)
            @if($section->section_type == \App\Models\Section::TYPE_CONSULTATION_MAIN_BANNER && $section->enabled == 1)
                @php
                    $bannerImage = '';
                    if (isset($section->banner_image[0])) {
                        $bannerImage = $section->banner_image[0];
                    }
                @endphp
                <div class="hero-section-landing consultations"
                    style="background-image: url('{{ webAssets('storage/' . $bannerImage) }}')">
                    <div class="container-homepage">
                        <div class="hero-content-fixed">
                            {!! $section->content !!}
                            <button class="btn-signup" id="book-consult-purchase-btn">View Consults</button>
                        </div>
                    </div>
                </div>
            @endif
            @if($section->section_type == \App\Models\Section::TYPE_MEET_EXPERT && $section->enabled == 1)
                <div class="container-homepage about-us">
                    <section class="kerry-section about-section training-nutrition-landing consultations">
                        <div class="kerry-content">
                            {!! $section->content !!}
                        </div>
                        <div class="kerry-image">
                            @if(isset($section->image[0]) && !empty($section->image[0]))
                                <img src="{{ asset('storage/' . $section->image[0]) }}" alt="Kerry O'Bryan" class="img-fluid about-image" />
                            @endif
                        </div>
                    </section>
                </div>
            @endif
            @if($section->section_type == \App\Models\Section::TYPE_CONSULTATION_INCLUSIONS && $section->enabled == 1 && isset($consultations) && $consultations->count() > 0)
                @php
                    $backgroundImage = '';
                    if (isset($section->banner_image[0])) {
                        $backgroundImage = asset('storage/' . $section->banner_image[0]);
                    }

                    if (isset($section->image[0])) {
                        $blueBadgeImg = asset('storage/' . $section->image[0]);
                    }
                @endphp
                <section class="plan-inclusion-section"
                    style="background-image: url('{{ $backgroundImage }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
                    <div class="container-homepage">
                        <div class="title-content">
                            <h2 class="title">{{ $section->title }}</h2>
                        </div>
                        <!-- {!! $section->content !!} -->

                        <div class="plan-features consultations">
                            @foreach($consultations as $consultation)
                                @if($consultation->show_on_consultation_page)
                                    <div class="feature-column">
                                        {!! $consultation->content !!}

                                        <div class="pricing-section">
                                            <h2 class="pricing-amount">${{ number_format($consultation->price, 0) }} AUD</h2>
                                            <div class="pricing-buttons">
                                                <button class="btn-signup book-consult-btn" data-consultation-id="{{ $consultation->id }}"
                                                    data-consultation-price="{{ $consultation->price }}"
                                                    data-consultation-time="{{ $consultation->time }}"
                                                    data-consultation-content="{{ $consultation->content }}">
                                                    Book consult
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </section>
            @endif
            @if($section->section_type == \App\Models\Section::TYPE_CONSULTATION_PARTNERS && $section->enabled == 1)
                <!-- trusted partners section -->
                <section class="py-5 partners-section">
                    <div class="container-homepage">
                        <h2 class="mb-5 text-md-start text-center section-title">
                            {!! $section->title !!}
                        </h2>
                    </div>


                    <div class="slider-container">
                        <div class="slide-left logo-row">
                            <!-- Duplicate content for seamless loop -->
                            @if(!empty($section->banner_image) && is_array($section->banner_image))
                                @foreach($section->banner_image as $bannerImage)
                                    <div class="logo-card">
                                        <img src="{{ asset('storage/' . ($bannerImage['image'] ?? $bannerImage)) }}"
                                            alt="{{ $bannerImage['alt'] ?? 'Partner Logo' }}" />
                                    </div>
                                @endforeach
                                @foreach($section->banner_image as $bannerImage)
                                    <div class="logo-card">
                                        <img src="{{ asset('storage/' . ($bannerImage['image'] ?? $bannerImage)) }}"
                                            alt="{{ $bannerImage['alt'] ?? 'Partner Logo' }}" />
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <div class="slide-right logo-row">
                            <!-- Duplicate content for seamless loop -->
                            @if(!empty($section->image) && is_array($section->image))
                                @foreach($section->image as $bannerImage)
                                    <div class="logo-card">
                                        <img src="{{ asset('storage/' . ($bannerImage['image'] ?? $bannerImage)) }}"
                                            alt="{{ $bannerImage['alt'] ?? 'Partner Logo' }}" />
                                    </div>
                                @endforeach
                                @foreach($section->image as $bannerImage)
                                    <div class="logo-card">
                                        <img src="{{ asset('storage/' . ($bannerImage['image'] ?? $bannerImage)) }}"
                                            alt="{{ $bannerImage['alt'] ?? 'Partner Logo' }}" />
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                </section>
            @endif
            @if($section->section_type == \App\Models\Section::TYPE_CONSULTATION_INTERESTS && $section->enabled == 1)
                @php
                    if (isset($section->banner_image[0])) {
                        $intresetsmallimg1 = asset('storage/' . $section->banner_image[0]);
                    }
                    if (isset($section->banner_image[1])) {
                        $intrestimg1 = asset('storage/' . $section->banner_image[1]);
                    }
                    if (isset($section->banner_image[2])) {
                        $intresetsmallimg2 = asset('storage/' . $section->banner_image[2]);
                    }
                    if (isset($section->banner_image[3])) {
                        $intrestimg2 = asset('storage/' . $section->banner_image[3]);
                    }
                    if (isset($section->image[0])) {
                        $intresetsmallimg3 = asset('storage/' . $section->image[0]);
                    }
                    if (isset($section->image[1])) {
                        $intrestimg3 = asset('storage/' . $section->image[1]);
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

    <!-- Payment Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <!-- Monthly Payment Content -->
                <div id="paymentContentConsultation">
                    <div class="pt-0 pb-0 border-0 modal-header">
                        <h5 class="modal-title">Consultation</h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="modal-subtitle" id="consultation-description">30 min One on One, online Nutrition
                            Consultation </p>
                        <p class="amount"><strong id="consultation-price">A$120.</strong> <span class="">one time
                                payment</span></p>

                        <span class="divider"></span>
                        <p class="mb-2 sign-in-text" style="line-height: 22px;">Signed in
                            as<br><strong id="user-email">{{ Auth::user()?->email }}</strong></p>
                        <a href="##" class="d-block mb-3 coupon-code" id="toggle-coupon-consultation">Add a Coupon
                            Code</a>
                        <!-- Coupon Code -->
                        <div class="mb-3 d-none" id="coupon-details-consultation">
                            <label for="promo-code-consultation" class="form-label">Coupon Code</label>
                            <div class="d-flex gap-2">
                                <input type="text" class="h-auto form-control" id="promo-code-consultation"
                                    placeholder="Enter coupon code">
                                <input type="hidden" class="form-control" id="discount-consultation">
                                <button type="button" class="btn btn-signup"
                                    id="apply-promo-code-consultation">Apply</button>
                            </div>
                            <small id="promo-message-consultation" class="form-text"></small>
                        </div>

                        <form id="consultation-payment-form">
                            <input type="hidden" id="consultation-id" name="consultation_id">
                            <input type="hidden" id="consultation-final-price" name="price">
                            <input type="hidden" id="payment-method-id" name="payment_method_id">
                            <div class="form-wrap">
                                <div class="mb-3">
                                    <label class="form-label">Card number</label>
                                    <div class="input-with-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                            fill="none" class="input-icon">
                                            <path
                                                d="M22.5 21H1.5C1.10218 21 0.720644 20.842 0.43934 20.5607C0.158035 20.2794 0 19.8978 0 19.5L0 7.5H24V19.5C24 20.3295 23.3295 21 22.5 21ZM13.1355 11.0265C12.579 10.6995 11.94 10.5 11.25 10.5C9.1785 10.5 7.5 12.1785 7.5 14.25C7.5 16.3215 9.1785 18 11.25 18C11.94 18 12.579 17.8005 13.1355 17.4735C12.435 16.5825 12 15.471 12 14.25C12 13.029 12.435 11.9175 13.1355 11.0265ZM17.25 10.5C15.1785 10.5 13.5 12.1785 13.5 14.25C13.5 16.3215 15.1785 18 17.25 18C19.3215 18 21 16.3215 21 14.25C21 12.1785 19.3215 10.5 17.25 10.5ZM0 4.5C0 4.10218 0.158035 3.72064 0.43934 3.43934C0.720644 3.15804 1.10218 3 1.5 3H22.5C23.3295 3 24 3.6705 24 4.5V6H0V4.5Z"
                                                fill="#B1B1B1" />
                                        </svg>
                                        <div id="card-number-element" class="form-control"></div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Name on card</label>
                                    <input type="text" class="form-control" id="card-holder-name" placeholder="Card name">
                                </div>
                                <div class="row">
                                    <div class="mb-3 col-6">
                                        <label class="form-label">Expiry date</label>
                                        <div id="card-expiry-element" class="form-control"></div>
                                    </div>
                                    <div class="mb-3 col-6">
                                        <label class="form-label">CVV</label>
                                        <div id="card-cvc-element" class="form-control"></div>
                                    </div>
                                </div>
                            </div>
                            <!-- Button that swaps modal content -->
                            <button type="button" class="w-100 btn btn-signup" id="payConsultationBtn">
                                Pay | $<span id="pay-button-price">120</span>
                            </button>
                        </form>
                        <p class="mt-3 text-muted small confirm-text">
                            By placing your order, you agree to our <a href="#" class="terms-link">Terms of Service</a>
                            and <a href="#" class="terms-link">Privacy Policy</a>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Congrats Modal for Consultation -->
    <div class="modal fade" id="congratsModalConsultation" tabindex="-1" aria-labelledby="congratsModalConsultationLabel"
        aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 12px;">
                <div id="congratsContentConsultation">
                    <img src="{{ frontAssets('images/consultation/congrats-modal-img.png') }}" alt="Congrats"
                        class="rounded-top w-100">
                    <div class="p-4 text-center modal-body">
                        <div class="mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="61" height="60" viewBox="0 0 61 60" fill="none">
                                <path
                                    d="M30.1875 53.75C43.4768 53.75 54.25 42.9768 54.25 29.6875C54.25 16.3981 43.4768 5.625 30.1875 5.625C16.8981 5.625 6.125 16.3981 6.125 29.6875C6.125 42.9768 16.8981 53.75 30.1875 53.75Z"
                                    stroke="#3E8E00" stroke-width="3" />
                                <path
                                    d="M19.25 30.625C20.2764 31.6514 22.6373 34.0123 25.2945 36.6695C26.2708 37.6458 27.8539 37.6461 28.8302 36.6698L42.375 23.125"
                                    stroke="#3E8E00" stroke-width="3" stroke-linecap="round" />
                            </svg>
                        </div>
                        <h4 class="congrats-title"><strong>Congrats legend!</strong></h4>
                        <p class="mb-1 congrats-subtitle"><strong>You're all set for your Consultation</strong><br></p>
                        <p class="congrats-para">
                            Lets book in a time
                        </p>
                        <input type="hidden" id="payment-id" name="payment_id">
                        <button type="button" class="w-100 btn btn-signup" id="book-time-btn">Book a Time</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendar Booking Modal -->
    <div class="modal fade" id="calendarBookingModal" tabindex="-1" aria-labelledby="calendarBookingModalLabel"
        aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content" style="border-radius: 12px;">
                <div class="modal-header">
                    <h5 class="modal-title" id="calendarBookingModalLabel">Book Your Consultation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="calendar-container" style="height: 600px;">
                        <input type="hidden" id="payment-id" name="payment_id">
                        <!-- Google Calendar Appointment Scheduling begin -->
                        <iframe id="calendar-iframe" src="" style="border: 0" width="100%" height="600"
                            frameborder="0"></iframe>
                        <!-- end Google Calendar Appointment Scheduling -->
                    </div>
                </div>
                <div class="modal-footer">
                    <p class="text-muted small mb-0">
                        <i class="fas fa-info-circle me-1"></i>
                        After booking your consultation, you'll be redirected based on your questionnaire completion status.
                    </p>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <!-- Stripe.js -->
    <script src="https://js.stripe.com/v3/"></script>

    <script>


        // Learn more tooltip function
        function showLearnMoreTooltip(element, message) {
            // Create tooltip element
            const tooltip = document.createElement('div');
            tooltip.className = 'tooltip';
            tooltip.textContent = message;
            tooltip.style.cssText = `
                    position: absolute;
                    background: #333;
                    color: white;
                    padding: 8px 12px;
                    border-radius: 4px;
                    font-size: 14px;
                    z-index: 1000;
                    white-space: nowrap;
                    pointer-events: none;
                `;

            // Position tooltip near the button
            const rect = element.getBoundingClientRect();
            tooltip.style.left = rect.left + 'px';
            tooltip.style.top = (rect.bottom + 5) + 'px';

            // Add to page
            document.body.appendChild(tooltip);

            // Remove after 3 seconds
            setTimeout(() => {
                if (tooltip.parentNode) {
                    tooltip.parentNode.removeChild(tooltip);
                }
            }, 3000);
        }
    </script>

    <script>


        // Learn more tooltip function
        function showLearnMoreTooltip(element, message) {
            // Create tooltip element
            const tooltip = document.createElement('div');
            tooltip.className = 'tooltip';
            tooltip.textContent = message;
            tooltip.style.cssText = `
                    position: absolute;
                    background: #333;
                    color: white;
                    padding: 8px 12px;
                    border-radius: 4px;
                    font-size: 14px;
                    z-index: 1000;
                    white-space: nowrap;
                    pointer-events: none;
                `;

            // Position tooltip near the button
            const rect = element.getBoundingClientRect();
            tooltip.style.left = rect.left + 'px';
            tooltip.style.top = (rect.bottom + 5) + 'px';

            // Add to page
            document.body.appendChild(tooltip);

            // Remove after 3 seconds
            setTimeout(() => {
                if (tooltip.parentNode) {
                    tooltip.parentNode.removeChild(tooltip);
                }
            }, 3000);
        }
    </script>

    <!--  hero section slider script -->
    <script>
        // Initialize carousels with smooth transitions
        document.addEventListener("DOMContentLoaded", function () {
            const desktopCarousel = document.getElementById("heroCarouselDesktop");
            const mobileCarousel = document.getElementById("heroCarouselMobile");

            // Add fade effect class to both carousels
            desktopCarousel.classList.add("carousel-fade");
            mobileCarousel.classList.add("carousel-fade");

            // Function to setup carousel events
            function setupCarousel(carousel) {
                // Ensure continuous looping
                carousel.addEventListener("slide.bs.carousel", function (e) {
                    // Smooth transition effect
                    const activeItem = carousel.querySelector(".carousel-item.active");
                    const nextItem = e.relatedTarget;

                    // Add smooth transition
                    nextItem.style.transition = "opacity 0.8s ease-in-out";
                });

                // Auto-restart carousel when it reaches the end
                carousel.addEventListener("slid.bs.carousel", function (e) {
                    const items = carousel.querySelectorAll(".carousel-item");
                    const activeIndex = Array.from(items).indexOf(e.relatedTarget);

                    // Ensure continuous loop
                    if (activeIndex === items.length - 1) {
                        setTimeout(() => {
                            bootstrap.Carousel.getInstance(carousel).to(0);
                        }, 3000);
                    }
                });
            }

            // Setup both carousels
            setupCarousel(desktopCarousel);
            setupCarousel(mobileCarousel);
        });

        // Remove the old carousel slide event listener and replace with:
        // Smooth navbar background change on scroll
        window.addEventListener("scroll", function () {
            const navbar = document.querySelector(".navbar-custom");
            if (window.scrollY > 50) {
                navbar.style.background = "rgba(59, 59, 59, 1)";
            } else {
                navbar.style.background = "transparent";
            }
        });

        if(document.querySelector(".chat-widget")) {
        // Chat widget interaction
            document
            .querySelector(".chat-widget")
            .addEventListener("click", function () {
                alert("Chat feature would open here!");
            });
        }

    </script>

    <!--  Food slider script -->
    <script>
        class FoodCarousel {
            constructor() {
                this.track = document.getElementById("foodCarouselTrack");
                this.cards = Array.from(this.track.children);
                this.currentIndex = 0;
                this.isMobile = window.innerWidth <= 768;

                // Set card width based on screen size
                this.cardWidth = this.isMobile ? 290 : 400; // 280px card + 10px margin on mobile, 380px + 20px on desktop

                this.init();
            }

            init() {

                // Calculate how many cards fit in viewport
                this.calculateVisibleCards();

                // Create infinite loop with clones
                this.setupInfiniteLoop();

                // Set initial position
                this.updatePosition(true);

                // Add event listeners
                document
                    .getElementById("prevBtn")
                    .addEventListener("click", () => this.prev());
                document
                    .getElementById("nextBtn")
                    .addEventListener("click", () => this.next());

                // Auto-slide with different timing for mobile
                this.startAutoSlide();

                // Keyboard navigation
                document.addEventListener("keydown", (e) => {
                    if (e.key === "ArrowLeft") this.prev();
                    if (e.key === "ArrowRight") this.next();
                });

                // Handle window resize
                window.addEventListener('resize', () => {
                    this.handleResize();
                });


            }

            handleResize() {
                const wasMobile = this.isMobile;
                this.isMobile = window.innerWidth <= 768;
                this.cardWidth = this.isMobile ? 290 : 400;

                // Only recalculate if mobile state changed
                if (wasMobile !== this.isMobile) {
                    this.calculateVisibleCards();
                    this.updatePosition(true);
                }
            }

            calculateVisibleCards() {
                const viewportWidth = window.innerWidth;
                const cardWidth = this.cardWidth;

                // Calculate how many full cards fit in viewport
                this.visibleCards = Math.floor(viewportWidth / cardWidth);

                // Ensure at least 2 cards are visible on mobile, 3 on desktop for better infinite loop
                const minCards = this.isMobile ? 2 : 3;
                if (this.visibleCards < minCards) this.visibleCards = minCards;


            }

            setupInfiniteLoop() {
                // Clone cards for infinite loop
                const originalCards = [...this.cards];

                // Add clones at the end
                originalCards.forEach((card) => {
                    const clone = card.cloneNode(true);
                    this.track.appendChild(clone);
                });

                // Add clones at the beginning
                originalCards.forEach((card) => {
                    const clone = card.cloneNode(true);
                    this.track.insertBefore(clone, this.track.firstChild);
                });

                // Update cards array to include clones
                this.cards = Array.from(this.track.children);
                this.originalCardCount = originalCards.length;


            }

            updatePosition(noAnimation = false) {
                const viewportWidth = window.innerWidth;
                const cardWidth = this.cardWidth;

                // Calculate center position
                const totalCardsWidth = this.visibleCards * cardWidth;
                const centerOffset = (viewportWidth - totalCardsWidth) / 2;

                // Calculate position with clones offset
                const translateX =
                    centerOffset -
                    this.currentIndex * cardWidth -
                    this.originalCardCount * cardWidth;

                if (noAnimation) {
                    this.track.style.transition = "none";
                } else {
                    // Use faster transition for better responsiveness
                    const transitionDuration = this.isMobile ? "0.3s" : "0.25s";
                    this.track.style.transition = `transform ${transitionDuration} ease-in-out`;
                }

                this.track.style.transform = `translateX(${translateX}px)`;
            }

            next() {
                this.currentIndex++;

                // Check if we need to loop
                if (this.currentIndex >= this.originalCardCount) {
                    // Reset to beginning without animation
                    setTimeout(() => {
                        this.track.style.transition = "none";
                        this.currentIndex = 0;
                        this.updatePosition(true);
                    }, this.isMobile ? 300 : 250);
                }

                this.updatePosition();
            }

            prev() {
                console.log("⬅️ Previous slide");
                this.currentIndex--;

                // Check if we need to loop
                if (this.currentIndex < 0) {
                    // Reset to end without animation
                    setTimeout(() => {
                        this.track.style.transition = "none";
                        this.currentIndex = this.originalCardCount - 1;
                        this.updatePosition(true);
                    }, this.isMobile ? 300 : 250);
                }

                this.updatePosition();
            }

            startAutoSlide() {
                // Use different timing for mobile vs desktop
                const interval = this.isMobile ? 1500 : 1200; // 1.5 seconds on mobile, 1.2 on desktop
                console.log(`⏰ Starting auto-slide (${interval / 1000} seconds)`);
                this.autoSlideInterval = setInterval(() => {
                    this.next();
                }, interval);
            }

            stopAutoSlide() {
                if (this.autoSlideInterval) {
                    clearInterval(this.autoSlideInterval);
                }
            }
        }

        // Initialize when DOM is ready
        document.addEventListener("DOMContentLoaded", () => {

            // Force refresh the carousel if it already exists
            if (window.foodCarousel) {
                window.foodCarousel.stopAutoSlide();
            }

            window.foodCarousel = new FoodCarousel();
        });
    </script>

    <script>
        var intresetsmallimg1 = "{{ $intresetsmallimg1 }}";
        var intresetsmallimg2 = "{{ $intresetsmallimg2 }}";
        var intrestimg1 = "{{ $intrestimg1 }}";
        var intrestimg2 = "{{ $intrestimg2 }}";
        var blueBadgeImg = "{{ $blueBadgeImg }}";
        var intresetsmallimg3 = "{{ $intresetsmallimg3 }}";
        var intrestimg3 = "{{ $intrestimg3 }}";

        if (intresetsmallimg1 !== '') {
            document.getElementById('TPMAIU-card1-icon').src = intresetsmallimg1;
        }
        if (intrestimg1 !== '') {
            document.getElementById('TPMAIU-card1').src = intrestimg1;
        }
        if (intresetsmallimg2 !== '') {
            document.getElementById('TPMAIU-card2-icon').src = intresetsmallimg2;
        }
        if (intrestimg2 !== '') {
            document.getElementById('TPMAIU-card2').src = intrestimg2;
        }
        if (blueBadgeImg !== '') {
            document.getElementById('blue-badge-img').src = blueBadgeImg;
        }
        if (intresetsmallimg3 !== '') {
            document.getElementById('TPMAIU-card3-icon').src = intresetsmallimg3;
        }
        if (intrestimg3 !== '') {
            document.getElementById('TPMAIU-card3').src = intrestimg3;
        }

        //btn-signup
        $('.plan-inclusion-section .btn-signup').on('click', function () {
            $('#TPMAIU-purchase-plan-btn').click();
        });

        $('#competition-plan-link').on('click', function () {
            window.location.href = "{{ route('front.competition.plan') }}";
        });
        $('#injury-plan-link').on('click', function () {
            showLearnMoreTooltip(this, 'Coming Soon')
            // window.location.href = "{{ route('front.injury.recovery.plan') }}";
        });
        $('#surgery-plan-link').on('click', function () {
            showLearnMoreTooltip(this, 'Coming Soon')
            // window.location.href = "{{ route('front.surgery.plan') }}";
        });
        $('#training-plan-link').on('click', function () {
            window.location.href = "{{ route('front.training.nutrition.plan') }}";
        });
        // Scroll to Consults section when book-consult-purchase-btn is clicked
        $('#book-consult-purchase-btn').on('click', function () {
            $('html, body').animate({
                scrollTop: $('.plan-inclusion-section').offset().top
            }, 800);
        });
    </script>

    <script>
        // const onePaymentBtn = document.getElementById("onePaymentBtn");
        // const monthlyPlanBtn = document.getElementById("monthlyPlanBtn");
        // const onePaymentPlans = document.getElementById("onePaymentPlans");
        // const monthlyPlans = document.getElementById("monthlyPlans");

        // onePaymentBtn.addEventListener("click", () => {
        //     onePaymentPlans.classList.remove("d-none");
        //     monthlyPlans.classList.add("d-none");
        //     onePaymentBtn.classList.add("active");
        //     monthlyPlanBtn.classList.remove("active");
        // });

        // monthlyPlanBtn.addEventListener("click", () => {
        //     onePaymentPlans.classList.add("d-none");
        //     monthlyPlans.classList.remove("d-none");
        //     monthlyPlanBtn.classList.add("active");
        //     onePaymentBtn.classList.remove("active");
        // });
    </script>

    <script>
        // Handle monthlyBtn in planChooseModal
        // document.getElementById("monthlyBtn").addEventListener("click", function () {
        //     // Hide the current modal
        //     const planModal = bootstrap.Modal.getInstance(document.getElementById('planChooseModal'));
        //     if (planModal) {
        //         planModal.hide();
        //     }

        //     // Show the consultation congrats modal
        //     const congratsModalConsultation = new bootstrap.Modal(document.getElementById('congratsModalConsultation'));
        //     congratsModalConsultation.show();
        // });

        // // Handle consultation booking button
        // const consultationBtn = document.querySelector('#paymentModal .btn-signup');
        // if (consultationBtn) {
        //     consultationBtn.addEventListener("click", function () {
        //         // Hide the current modal
        //         const paymentModal = bootstrap.Modal.getInstance(document.getElementById('paymentModal'));
        //         if (paymentModal) {
        //             paymentModal.hide();
        //         }

        //         // Show the consultation congrats modal
        //         const congratsModalConsultation = new bootstrap.Modal(document.getElementById('congratsModalConsultation'));
        //         congratsModalConsultation.show();
        //     });
        // }

    </script>
    <script>
        document.getElementById("toggle-coupon-consultation").addEventListener("click", function (e) {
            e.preventDefault();
            const couponDetails = document.getElementById("coupon-details-consultation");
            const toggleText = this.textContent;

            if (couponDetails.classList.contains("d-none")) {
                // Show coupon section
                couponDetails.classList.remove("d-none");
                this.textContent = "Remove Coupon Code";
            } else {
                // Hide coupon section and clear coupon
                couponDetails.classList.add("d-none");
                this.textContent = "Add a Coupon Code";

                // Reset coupon values
                document.getElementById("promo-code-consultation").value = "";
                document.getElementById("discount-consultation").value = "";
                document.getElementById("promo-message-consultation").textContent = "";
                document.getElementById("promo-message-consultation").className = "form-text";

                // Reset price to original
                const originalPrice = parseFloat(document.getElementById("consultation-final-price").getAttribute("data-original-price") || "120");
                document.getElementById("consultation-final-price").value = originalPrice;
                if (document.getElementById("pay-button-price")) {
                    document.getElementById("pay-button-price").textContent = originalPrice;
                }
                if (document.getElementById("consultation-price")) {
                    document.getElementById("consultation-price").innerHTML = `A$${originalPrice}`;
                }
                if (document.getElementById("payConsultationBtn")) {
                    document.getElementById("payConsultationBtn").innerHTML = `Pay | $<span id="pay-button-price">${originalPrice}</span>`;
                }
                // Ensure the button is not disabled
                document.getElementById("payConsultationBtn").disabled = false;

                // Show payment form again when coupon is removed
                document.querySelector('#consultation-payment-form .form-wrap').classList.remove('d-none');
            }
        });
    </script>
    <script>
        document.getElementById("apply-promo-code-consultation").addEventListener("click", function () {
            const promoCode = document.getElementById("promo-code-consultation").value.trim();
            const consultationId = document.getElementById("consultation-id").value;
            const promoMessage = document.getElementById("promo-message-consultation");
            const discountField = document.getElementById("discount-consultation");
            const originalPrice = parseFloat(document.getElementById("consultation-final-price").value);
            const payButton = document.getElementById("payConsultationBtn");
            const payButtonPrice = document.getElementById("pay-button-price");
            const consultationPrice = document.getElementById("consultation-price");

            if (!promoCode) {
                promoMessage.textContent = "Please enter a coupon code.";
                promoMessage.className = "form-text text-danger";
                return;
            }

            // Disable apply button during validation
            this.disabled = true;
            this.textContent = "Applying...";

            fetch('{{ route("validate.coupon.code") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    code: promoCode,
                    consultation_id: consultationId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.valid) {
                    const discount = parseFloat(data.discount);
                    discountField.value = discount;

                    let finalPrice = originalPrice;
                    let discountText = "";

                    if (data.type === 'percentage') {
                        const discountAmount = (originalPrice * discount) / 100;
                        finalPrice = originalPrice - discountAmount;
                        discountText = `${discount}% off`;
                    } else if (data.type === 'fixed') {
                        finalPrice = Math.max(0, originalPrice - discount);
                        discountText = `$${discount} off`;
                    }

                    // Update display
                    document.getElementById("consultation-final-price").value = finalPrice.toFixed(2);
                    payButtonPrice.textContent = finalPrice.toFixed(0);
                    consultationPrice.innerHTML = `A$${finalPrice.toFixed(2)}`;

                    // Update toggle link text
                    document.getElementById("toggle-coupon-consultation").textContent = `Remove Coupon Code (${discountText})`;

                    promoMessage.textContent = `Coupon applied successfully! ${discountText}`;
                    promoMessage.className = "form-text text-success";

                    // If 100% discount, update button text and hide payment form
                    if (finalPrice <= 0) {
                        payButton.innerHTML = 'Get Free Consultation';
                        // Hide payment form for free consultation
                        document.querySelector('#consultation-payment-form .form-wrap').classList.add('d-none');
                    } else {
                        payButton.innerHTML = `Pay | $<span id="pay-button-price">${finalPrice.toFixed(0)}</span>`;
                        // Show payment form for paid consultation
                        document.querySelector('#consultation-payment-form .form-wrap').classList.remove('d-none');
                    }
                } else {
                    promoMessage.textContent = data.message || "Invalid coupon code.";
                    promoMessage.className = "form-text text-danger";

                    // Reset values - DO NOT calculate amount for invalid coupons
                    discountField.value = "";
                    document.getElementById("consultation-final-price").value = originalPrice;
                    payButtonPrice.textContent = originalPrice;
                    consultationPrice.innerHTML = `A$${originalPrice}`;
                    document.getElementById("toggle-coupon-consultation").textContent = "Add a Coupon Code";
                    payButton.innerHTML = `Pay | $<span id="pay-button-price">${originalPrice}</span>`;
                    payButton.disabled = false;

                    // Ensure payment form is visible for invalid coupons
                    document.querySelector('#consultation-payment-form .form-wrap').classList.remove('d-none');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                promoMessage.textContent = error.message || "Something went wrong. Please try again.";
                promoMessage.className = "form-text text-danger";

                // Reset values on error - DO NOT calculate amount
                discountField.value = "";
                document.getElementById("consultation-final-price").value = originalPrice;
                payButtonPrice.textContent = originalPrice;
                consultationPrice.innerHTML = `A$${originalPrice}`;
                document.getElementById("toggle-coupon-consultation").textContent = "Add a Coupon Code";
                payButton.innerHTML = `Pay | $<span id="pay-button-price">${originalPrice}</span>`;
                payButton.disabled = false;

                // Ensure payment form is visible on error
                document.querySelector('#consultation-payment-form .form-wrap').classList.remove('d-none');
            })
            .finally(() => {
                // Re-enable apply button
                this.disabled = false;
                this.textContent = "Apply";
            });
        });
    </script>
    <!-- plan section slider -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const track = document.getElementById("planSlider");
            const prevBtn = document.getElementById("planPrev");
            const nextBtn = document.getElementById("planNext");
            const cards = track.children;
            let index = 0;

            function getCardWidth() {
                return cards[0].offsetWidth + parseInt(getComputedStyle(cards[0]).marginRight || 0) + parseInt(getComputedStyle(cards[0]).marginLeft || 0);
            }

            function updateSlider() {
                const cardWidth = getCardWidth();
                track.style.transform = `translateX(-${index * cardWidth}px)`;
            }

            nextBtn.addEventListener("click", () => {
                const visible = window.innerWidth <= 768 ? 1 : 2;
                if (index < cards.length - visible) {
                    index++;
                    updateSlider();
                }
            });

            prevBtn.addEventListener("click", () => {
                if (index > 0) {
                    index--;
                    updateSlider();
                }
            });

            window.addEventListener("resize", updateSlider);
        });
    </script>

    <!-- Consultation Booking Script -->
    <style>
    /* Payment processing modal styles */
    .payment-processing {
        pointer-events: auto !important;
    }

    .payment-processing .modal-backdrop {
        pointer-events: none !important;
    }

    .payment-processing .btn-close,
    .payment-processing .close {
        opacity: 0.5 !important;
        cursor: not-allowed !important;
    }

    .payment-processing .modal-dialog {
        pointer-events: auto !important;
    }

    /* Prevent text selection during payment processing */
    .payment-processing * {
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    /* Allow text selection in input fields */
    .payment-processing input,
    .payment-processing textarea {
        -webkit-user-select: text;
        -moz-user-select: text;
        -ms-user-select: text;
        user-select: text;
    }
    </style>
    <script>
        // Stripe configuration
        let stripe;
        let elements;
        let cardNumberElement;
        let cardExpiryElement;
        let cardCvcElement;
        let paymentMethodId = null;

        // Reset consultation flow and show coming soon tooltip
        function resetConsultationFlow(button) {
            // Show coming soon tooltip
            showComingSoonTooltip(button, 'Booking System');

            // Reset all consultation-related flags and state
            resetConsultationState();

            // Hide the coming soon tooltip after 5 seconds (but keep modal open)
            setTimeout(() => {
                const existingTooltip = document.querySelector('.coming-soon-tooltip');
                if (existingTooltip) {
                    existingTooltip.remove();
                }
            }, 5000);

            // Reset any pending consultation data
            if (window.pendingConsultation) {
                delete window.pendingConsultation;
            }

            // Reset any stored consultation data in session/local storage
            if (typeof sessionStorage !== 'undefined') {
                sessionStorage.removeItem('pending_consultation');
                sessionStorage.removeItem('consultation_flow_state');
                sessionStorage.removeItem('consultation_booking_data');
            }

            // Reset form fields if they exist
            resetConsultationForms();

            // Reset any custom event handlers
            resetEventHandlers();

            // Reset any timers or intervals
            resetTimers();

            // Reset any AJAX requests
            resetAjaxRequests();

            console.log('Consultation flow reset - fresh state restored');
        }

        // Reset all consultation state
        function resetConsultationState() {
            // Reset any global consultation flags
            window.consultationBooked = false;
            window.consultationProcessing = false;
            window.consultationModalOpen = false;

            // Reset any jQuery data attributes on consultation buttons
            $('.book-consult-btn').removeData('processed');
            $('.book-consult-btn').removeData('clicked');

            // Reset any active states
            $('.book-consult-btn').removeClass('active processing disabled');
            $('.book-consult-btn').prop('disabled', false);

            // Reset payment button state
            $('#payConsultationBtn').prop('disabled', false);
            $('#payConsultationBtn').html('Pay | $<span id="pay-button-price">120</span>');
        }

        // Reset consultation forms
        function resetConsultationForms() {
            // Reset payment form
            if (document.getElementById('consultation-payment-form')) {
                document.getElementById('consultation-payment-form').reset();
            }

            // Reset Stripe elements
            if (cardNumberElement) {
                cardNumberElement.clear();
            }
            if (cardExpiryElement) {
                cardExpiryElement.clear();
            }
            if (cardCvcElement) {
                cardCvcElement.clear();
            }

            // Reset payment method ID
            paymentMethodId = null;

            // Reset consultation ID and price fields
            if (document.getElementById('consultation-id')) {
                document.getElementById('consultation-id').value = '';
            }
            if (document.getElementById('consultation-final-price')) {
                document.getElementById('consultation-final-price').value = '';
                document.getElementById('consultation-final-price').removeAttribute('data-original-price');
            }

            // Reset coupon fields
            if (document.getElementById('promo-code-consultation')) {
                document.getElementById('promo-code-consultation').value = '';
            }
            if (document.getElementById('discount-consultation')) {
                document.getElementById('discount-consultation').value = '';
            }
            if (document.getElementById('promo-message-consultation')) {
                document.getElementById('promo-message-consultation').textContent = '';
                document.getElementById('promo-message-consultation').className = 'form-text';
            }

            // Reset coupon toggle
            if (document.getElementById('toggle-coupon-consultation')) {
                document.getElementById('toggle-coupon-consultation').textContent = 'Add a Coupon Code';
            }

            // Hide coupon details section
            const couponDetails = document.getElementById('coupon-details-consultation');
            if (couponDetails && !couponDetails.classList.contains('d-none')) {
                couponDetails.classList.add('d-none');
            }
        }

        // Reset modals
        function resetModals() {
            // Close any open modals
            const modals = document.querySelectorAll('.modal.show');
            modals.forEach(modal => {
                const modalInstance = bootstrap.Modal.getInstance(modal);
                if (modalInstance) {
                    modalInstance.hide();
                }
            });

            // Remove any modal backdrops
            const backdrops = document.querySelectorAll('.modal-backdrop');
            backdrops.forEach(backdrop => {
                backdrop.remove();
            });

            // Reset body classes
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
        }

        // Reset event handlers
        function resetEventHandlers() {
            // Remove any custom event listeners that might have been added during consultation flow
            // This ensures a clean slate for event handling

            // Reset any button states
            const buttons = document.querySelectorAll('.book-consult-btn, #payConsultationBtn');
            buttons.forEach(button => {
                // Remove any custom event listeners
                button.replaceWith(button.cloneNode(true));
            });

            // Re-initialize basic event handlers if needed
            initializeBasicHandlers();
        }

        // Reset timers and intervals
        function resetTimers() {
            // Clear any setTimeout or setInterval that might be running
            // Note: This is a global clear, so use with caution
            const highestTimeoutId = setTimeout(() => { }, 0);
            for (let i = 0; i < highestTimeoutId; i++) {
                clearTimeout(i);
            }

            const highestIntervalId = setInterval(() => { }, 0);
            for (let i = 0; i < highestIntervalId; i++) {
                clearInterval(i);
            }
        }

        // Reset AJAX requests
        function resetAjaxRequests() {
            // Abort any ongoing AJAX requests
            if (window.jQuery && window.jQuery.active) {
                window.jQuery.active = 0;
            }

            // Abort any fetch requests
            if (window.AbortController) {
                // This would require storing AbortController instances during requests
                // For now, we'll just reset the jQuery active count
            }
        }

        // Initialize basic handlers after reset
        function initializeBasicHandlers() {
            // Re-attach basic event handlers that should always be present
            // This ensures the page remains functional after reset

            // Re-attach book consultation button handlers
            $('.book-consult-btn').off('click').on('click', function (e) {
                e.preventDefault();

                const isAuthenticated = {{ Auth::check() ? 'true' : 'false' }};

                if (!isAuthenticated) {
                    // Close any existing modals first
                    $('.modal').modal('hide');

                    // Wait for existing modal to close, then show signup modal
                    setTimeout(() => {
                        // Initialize signup modal content before showing
                        window.initializeSignupModal();
                        // Show login/signup modal
                        $('#signupModalathlete').modal('show');
                    }, 300);
                    // Store consultation data for after login
                    const consultationData = {
                        id: $(this).data('consultation-id'),
                        price: $(this).data('consultation-price'),
                        time: $(this).data('consultation-time'),
                        content: $(this).data('consultation-content')
                    };
                    sessionStorage.setItem('pendingConsultation', JSON.stringify(consultationData));
                    window.pendingConsultation = consultationData;
                    return;
                }

                // User is authenticated, show payment modal
                showPaymentModal($(this));
            });

            // Re-attach payment button handler
            $('#payConsultationBtn').off('click').on('click', function (e) {
                e.preventDefault();
                processConsultationPayment();
            });
        }

        // Initialize Stripe when DOM is ready
        $(document).ready(function () {
            // Initialize Stripe
            initializeStripe();

            // Check if user is authenticated
            const isAuthenticated = {{ Auth::check() ? 'true' : 'false' }};

            // Check if there's a pending consultation after page refresh
            const pendingConsultationData = sessionStorage.getItem('pendingConsultation');
            if (isAuthenticated && pendingConsultationData) {
                try {
                    const consultationData = JSON.parse(pendingConsultationData);
                    // Find the button with the stored consultation data
                    const button = $(`.book-consult-btn[data-consultation-id="${consultationData.id}"]`);
                    if (button.length) {
                        // Show payment modal automatically
                        setTimeout(() => {
                            showPaymentModal(button);
                        }, 500); // Small delay to ensure page is fully loaded
                    }
                    // Clear the pending consultation
                    sessionStorage.removeItem('pendingConsultation');
                    if (window.pendingConsultation) {
                        delete window.pendingConsultation;
                    }
                } catch (e) {
                    console.error('Error parsing pending consultation data:', e);
                    sessionStorage.removeItem('pendingConsultation');
                }
            }

            // Handle book consult button clicks
            $('.book-consult-btn').on('click', function (e) {
                e.preventDefault();

                if (!isAuthenticated) {
                    // Store current page URL to return after login
                    sessionStorage.setItem('returnToConsultationPage', window.location.href);
                    // Mark that this login was triggered by consultation booking
                    sessionStorage.setItem('loginTriggeredByConsultation', 'true');

                    // Initialize signup modal content before showing
                    initializeSignupModal();
                    // Show login/signup modal
                    $('#signupModalathlete').modal('show');
                    // Store consultation data for after login
                    const consultationData = {
                        id: $(this).data('consultation-id'),
                        price: $(this).data('consultation-price'),
                        time: $(this).data('consultation-time'),
                        content: $(this).data('consultation-content')
                    };
                    sessionStorage.setItem('pendingConsultation', JSON.stringify(consultationData));
                    window.pendingConsultation = consultationData;
                    return;
                }

                // User is authenticated, show payment modal
                showPaymentModal($(this));
            });

            // Handle payment button click
            $('#payConsultationBtn').on('click', function (e) {
                e.preventDefault();
                processConsultationPayment();
            });

            // Handle book time button click in congrats modal
            $('#book-time-btn').on('click', function (e) {
                e.preventDefault();

                // Get consultation time from stored data
                const consultationTime = window.currentConsultationTime || 30; // Default to 30 if not set
                const paymentId = $("#congratsModalConsultation #payment-id").val();
                // Set the appropriate calendar URL based on consultation time
                const calendarIframe = document.getElementById('calendar-iframe');
                if (consultationTime === 30) {
                    // 30-minute consultation calendar
                    calendarIframe.src = 'https://calendar.google.com/calendar/appointments/schedules/AcZssZ06hsdgy_YQNWOYK-jUrwBejSClhQehI3ZTeUgD7TKX7PCOZV5xyDfcIOTMPC2YImB4zCr92BYJ?gv=true';
                } else {
                    // Other consultation types calendar
                    calendarIframe.src = 'https://calendar.google.com/calendar/appointments/schedules/AcZssZ0J7QhuvkeNW899AvG5ODe7rGS92oCSl9nE5Gb4LDh_1SlNDXRaIloRBv9w7ftzOzf1DiAB93li?gv=true';
                }

                // Hide congrats modal
                $('#congratsModalConsultation').modal('hide');

                // set payment id
                $("#calendarBookingModal #payment-id").val(paymentId);

                // Show calendar booking modal
                $('#calendarBookingModal').modal('show');
            });

            // Prevent page refresh when congrats modal is open
            $('#congratsModalConsultation').on('shown.bs.modal', function () {
                // Add beforeunload event listener when modal opens
                window.addEventListener('beforeunload', preventRefreshWhenCongratsOpen);
            });

            $('#congratsModalConsultation').on('hidden.bs.modal', function () {
                // Remove beforeunload event listener when modal closes
                window.removeEventListener('beforeunload', preventRefreshWhenCongratsOpen);
                // Remove any existing coming soon tooltip when modal is closed
                const existingTooltip = document.querySelector('.coming-soon-tooltip');
                if (existingTooltip) {
                    existingTooltip.remove();
                }
            });

            // Function to prevent page refresh when congrats modal is open
            function preventRefreshWhenCongratsOpen(event) {
                const message = 'Please complete your consultation booking process first before leaving this page.';
                event.preventDefault();
                event.returnValue = message;
                return message;
            }

            // Handle calendar booking modal close - check questionnaire status and redirect
            $('#calendarBookingModal').on('hidden.bs.modal', function () {
                const paymentId = $("#calendarBookingModal #payment-id").val();
                checkQuestionnaireStatusAndRedirect(paymentId);
            });

            // Prevent page refresh when calendar booking modal is open
            $('#calendarBookingModal').on('shown.bs.modal', function () {
                // Add beforeunload event listener when modal opens
                window.addEventListener('beforeunload', preventRefreshWhenCalendarOpen);
            });

            $('#calendarBookingModal').on('hidden.bs.modal', function () {
                // Remove beforeunload event listener when modal closes
                window.removeEventListener('beforeunload', preventRefreshWhenCalendarOpen);
            });

            // Function to prevent page refresh when calendar is open
            function preventRefreshWhenCalendarOpen(event) {
                const message = 'Please complete your consultation booking first before leaving this page.';
                event.preventDefault();
                event.returnValue = message;
                return message;
            }

            // Handle successful login/signup (this will be called from single-signup.js)
            window.onConsultationLoginSuccess = function () {
                const pendingConsultationData = sessionStorage.getItem('pendingConsultation');
                if (pendingConsultationData) {
                    try {
                        const consultationData = JSON.parse(pendingConsultationData);
                        // Find the button with the stored consultation data
                        const button = $(`.book-consult-btn[data-consultation-id="${consultationData.id}"]`);
                        if (button.length) {
                            showPaymentModal(button);
                        }
                        sessionStorage.removeItem('pendingConsultation');
                        if (window.pendingConsultation) {
                            delete window.pendingConsultation;
                        }
                    } catch (e) {
                        console.error('Error parsing pending consultation data:', e);
                        sessionStorage.removeItem('pendingConsultation');
                    }
                }

                // Clear the stored return URL
                sessionStorage.removeItem('returnToConsultationPage');
            };
        });

        // Initialize Stripe Elements
        function initializeStripe() {
            // Initialize Stripe with your publishable key
            stripe = Stripe('{{ config("services.stripe.key") }}');

            // Create card elements
            elements = stripe.elements();

            // Create card number element
            cardNumberElement = elements.create('cardNumber', {
                style: {
                    base: {
                        fontSize: '16px',
                        color: '#424770',
                        '::placeholder': {
                            color: '#aab7c4',
                        },
                    },
                    invalid: {
                        color: '#9e2146',
                    },
                },
            });

            // Create card expiry element
            cardExpiryElement = elements.create('cardExpiry', {
                style: {
                    base: {
                        fontSize: '16px',
                        color: '#424770',
                        '::placeholder': {
                            color: '#aab7c4',
                        },
                    },
                    invalid: {
                        color: '#9e2146',
                    },
                },
            });

            // Create card CVC element
            cardCvcElement = elements.create('cardCvc', {
                style: {
                    base: {
                        fontSize: '16px',
                        color: '#424770',
                        '::placeholder': {
                            color: '#aab7c4',
                        },
                    },
                    invalid: {
                        color: '#9e2146',
                    },
                },
            });

            // Mount elements when payment modal is shown
            $('#paymentModal').on('shown.bs.modal', function () {
                mountStripeElements();
            });
        }

        // Mount Stripe Elements
        function mountStripeElements() {
            // Unmount existing elements if any
            if (cardNumberElement) {
                cardNumberElement.unmount();
                cardExpiryElement.unmount();
                cardCvcElement.unmount();
            }

            // Mount elements
            cardNumberElement.mount('#card-number-element');
            cardExpiryElement.mount('#card-expiry-element');
            cardCvcElement.mount('#card-cvc-element');

            // Reset payment method ID
            paymentMethodId = null;
        }

        // Function to initialize signup modal content
        function initializeSignupModal() {
            // Show the signup/login content sections
            $('.signup-login-h2-title').removeClass('d-none');
            $('.signup-login-h2-img').removeClass('d-none');

            $('#signupModalathlete #new-user-singup').removeClass('d-none');
            $('#signupModalathlete #existing-user-login').addClass('d-none');

            // Hide quiz-specific content
            $('.quiz-h2-title').addClass('d-none');
            $('.quiz-h2-img').addClass('d-none');

            // Reset to step 1
            showStep(1);

            // Clear any previous form data
            $('#mobile_number').val('');
            $('#firstname').val('');
            $('#email').val('');
            $('input[name="userType"]').prop('checked', false);
            $('input[name="ageGroup"]').prop('checked', false);
            $('#sportstype').val('');

            // Remove selected classes
            $('.user-type-box').removeClass('selected');
            $('.age-box').removeClass('selected');

            // Hide age groups and sports selection by default
            $('#age-groups-id').addClass('d-none');
            $('#select-sports-id').addClass('d-none');
        }

        function showPaymentModal(button) {
            const consultationId = button.data('consultation-id');
            const consultationPrice = button.data('consultation-price');
            const consultationTime = button.data('consultation-time');
            const consultationContent = button.data('consultation-content');

            // Store consultation time globally for calendar selection
            window.currentConsultationTime = consultationTime;

            // Update modal content with consultation details
            $('#consultation-description').text(`${consultationTime} min One on One, online Nutrition Consultation`);
            $('#consultation-price').text(`A$${consultationPrice}.`);
            $('#pay-button-price').text(consultationPrice);
            $('#consultation-id').val(consultationId);
            $('#consultation-final-price').val(consultationPrice);
            $('#consultation-final-price').attr('data-original-price', consultationPrice);
            $('#user-email').text('{{ Auth::user()->email ?? "" }}');

            // Reset coupon section
            $('#coupon-details-consultation').addClass('d-none');
            $('#toggle-coupon-consultation').text('Add a Coupon Code');
            $('#promo-code-consultation').val('');
            $('#discount-consultation').val('');
            $('#promo-message-consultation').text('').removeClass('text-success text-danger');

            // Show/hide payment form based on price
            if (parseFloat(consultationPrice) <= 0) {
                // Free consultation - hide payment form
                $('#consultation-payment-form .form-wrap').addClass('d-none');
                $('#payConsultationBtn').text('Get Free Consultation');
            } else {
                // Paid consultation - show payment form
                $('#consultation-payment-form .form-wrap').removeClass('d-none');
                $('#payConsultationBtn').html('Pay | $<span id="pay-button-price">' + consultationPrice + '</span>');
            }

            // Show the payment modal
            $('#paymentModal').modal('show');
        }

        // Global payment processing state
        let isConsultationPaymentProcessing = false;

        // Function to prevent page reload during payment processing
        function preventConsultationPageReload() {
            window.addEventListener('beforeunload', handleConsultationBeforeUnload);
        }

        // Function to prevent modal close during payment processing
        function preventConsultationModalClose() {
            // Prevent modal close via hide.bs.modal event
            $('#paymentModal').off('hide.bs.modal').on('hide.bs.modal', function(e) {
                if (isConsultationPaymentProcessing) {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }
            });

            // Prevent modal close via ESC key
            $(document).off('keydown.consultationModal').on('keydown.consultationModal', function(e) {
                if (isConsultationPaymentProcessing && e.keyCode === 27) { // ESC key
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }
            });

            // Prevent modal close via backdrop click - use Bootstrap's backdrop event
            $('#paymentModal').off('click.dismiss.bs.modal').on('click.dismiss.bs.modal', function(e) {
                if (isConsultationPaymentProcessing && e.target === this) {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }
            });

            // Additional backdrop click prevention
            $('#paymentModal').off('click.consultationModal').on('click.consultationModal', function(e) {
                if (isConsultationPaymentProcessing && e.target === this) {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }
            });

            // Disable close button during payment processing
            $('#paymentModal .btn-close, #paymentModal .close').off('click.consultationModal').on('click.consultationModal', function(e) {
                if (isConsultationPaymentProcessing) {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }
            });

            // Set modal data attributes to prevent backdrop dismissal
            if (isConsultationPaymentProcessing) {
                $('#paymentModal').attr('data-bs-backdrop', 'static');
                $('#paymentModal').attr('data-bs-keyboard', 'false');

                // Also update the modal instance configuration if it exists
                const modalElement = document.getElementById('paymentModal');
                if (modalElement && bootstrap.Modal.getInstance(modalElement)) {
                    const modalInstance = bootstrap.Modal.getInstance(modalElement);
                    modalInstance._config.backdrop = 'static';
                    modalInstance._config.keyboard = false;
                }
            }

            // Add visual indicators that modal cannot be closed
            if (isConsultationPaymentProcessing) {
                $('#paymentModal').addClass('payment-processing');
                $('#paymentModal .btn-close, #paymentModal .close').addClass('disabled').css('opacity', '0.5');
                $('#paymentModal .modal-backdrop').css('pointer-events', 'none');
            }
        }

        // Function to handle beforeunload event
        function handleConsultationBeforeUnload(e) {
            if (isConsultationPaymentProcessing) {
                e.preventDefault();
                e.returnValue = 'Payment is being processed. Are you sure you want to leave? This may cause issues with your payment.';
                return e.returnValue;
            }
        }

        // Function to reset payment processing state
        function resetConsultationPaymentProcessingState() {
            isConsultationPaymentProcessing = false;
            window.removeEventListener('beforeunload', handleConsultationBeforeUnload);

            // Remove all modal close prevention event listeners
            $('#paymentModal').off('hide.bs.modal');
            $(document).off('keydown.consultationModal');
            $('#paymentModal').off('click.dismiss.bs.modal');
            $('#paymentModal').off('click.consultationModal');
            $('#paymentModal .btn-close, #paymentModal .close').off('click.consultationModal');

            // Restore modal data attributes
            $('#paymentModal').attr('data-bs-backdrop', 'true');
            $('#paymentModal').attr('data-bs-keyboard', 'true');

            // Also restore the modal instance configuration if it exists
            const modalElement = document.getElementById('paymentModal');
            if (modalElement && bootstrap.Modal.getInstance(modalElement)) {
                const modalInstance = bootstrap.Modal.getInstance(modalElement);
                modalInstance._config.backdrop = true;
                modalInstance._config.keyboard = true;
            }

            // Remove visual indicators
            $('#paymentModal').removeClass('payment-processing');
            $('#paymentModal .btn-close, #paymentModal .close').removeClass('disabled').css('opacity', '1');
            $('#paymentModal .modal-backdrop').css('pointer-events', 'auto');
        }

        function processConsultationPayment() {
            const consultationId = $('#consultation-id').val();
            const price = $('#consultation-final-price').val();
            const email = $('#user-email').text();
            const couponCode = $('#promo-code-consultation').val().trim();
            const cardHolderName = $('#card-holder-name').val();

            // Set payment processing state
            isConsultationPaymentProcessing = true;

            // Disable button to prevent double submission
            $('#payConsultationBtn').prop('disabled', true).text('Processing...');

            // Prevent page reload and modal close during payment processing
            preventConsultationPageReload();
            preventConsultationModalClose();

            // Check if this is a free consultation (price is 0 or less)
            if (parseFloat(price) <= 0) {
                // Free consultation - no payment required, skip payment method validation
                processFreeConsultation();
                return;
            }

            // For paid consultations, validate card holder name
            if (!cardHolderName.trim()) {
                alert('Please enter the name on card.');
                $('#payConsultationBtn').prop('disabled', false).html('Pay | $<span id="pay-button-price">' + price + '</span>');
                resetConsultationPaymentProcessingState();
                return;
            }

            // Create payment method with Stripe for paid consultations
            stripe.createPaymentMethod({
                type: 'card',
                card: cardNumberElement,
                billing_details: {
                    name: cardHolderName,
                    email: email
                }
            }).then(function (result) {
                if (result.error) {
                    // Handle payment method creation error
                    $('#payConsultationBtn').prop('disabled', false).html('Pay | $<span id="pay-button-price">' + price + '</span>');
                    alert('Payment method error: ' + result.error.message);
                    resetConsultationPaymentProcessingState();
                } else {
                    // Payment method created successfully
                    paymentMethodId = result.paymentMethod.id;
                    $('#payment-method-id').val(paymentMethodId);
                    // Send payment to server
                    sendConsultationRequest();
                }
            });
        }

        // Process free consultation
        function processFreeConsultation() {
            const consultationId = $('#consultation-id').val();
            const price = $('#consultation-final-price').val();
            const email = $('#user-email').text();
            const couponCode = $('#promo-code-consultation').val().trim();
            const cardHolderName = $('#card-holder-name').val() || '{{ Auth::user()->name ?? "" }}';

            sendConsultationRequest();
        }

        // Check questionnaire status and redirect accordingly
        function checkQuestionnaireStatusAndRedirect(paymentId = null) {
            fetch('{{ route("front.consultation.questionnaire.status") }}', {
                method: 'POST',
                body: JSON.stringify({
                    payment_id: paymentId || null,
                    user_id: '{{ Auth::user()->id ?? "" }}' || null
                }),
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.redirect_url) {
                    // Redirect to appropriate page based on questionnaire completion
                    window.location.href = data.redirect_url;
                } else if (data.requires_auth) {
                    // User needs to login
                    $('#signupModalathlete').modal('show');
                } else {
                    console.error('Error checking questionnaire status:', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        // Send consultation request to server
        function sendConsultationRequest() {
            const consultationId = $('#consultation-id').val();
            const price = $('#consultation-final-price').val();
            const email = $('#user-email').text();
            const couponCode = $('#promo-code-consultation').val().trim();
            const cardHolderName = $('#card-holder-name').val() || '{{ Auth::user()->name ?? "" }}';
            const paymentMethodId = $('#payment-method-id').val();

            $.ajax({
                url: '{{ route("front.consultation.book") }}',
                method: 'POST',
                data: {
                    consultation_id: consultationId,
                    price: price,
                    name: cardHolderName,
                    email: email,
                    phone: '{{ Auth::user()->phone ?? "" }}',
                    coupon_code: couponCode,
                    payment_method_id: paymentMethodId,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.success) {
                        // Reset payment processing state
                        resetConsultationPaymentProcessingState();
                        // Hide payment modal
                        $('#paymentModal').modal('hide');
                        // Show congrats modal
                        $('#congratsModalConsultation').modal('show');
                        $("#congratsModalConsultation #payment-id").val(response.payment_id);
                    } else if (response.requires_action) {
                        // Handle 3D Secure authentication
                        stripe.handleCardAction(response.payment_intent_client_secret).then(function (result) {
                            if (result.error) {
                                $('#payConsultationBtn').prop('disabled', false).html('Pay | $<span id="pay-button-price">' + price + '</span>');
                                alert('Payment failed: ' + result.error.message);
                                resetConsultationPaymentProcessingState();
                            } else {
                                // Payment succeeded after 3D Secure
                                resetConsultationPaymentProcessingState();
                                $('#paymentModal').modal('hide');
                                $('#congratsModalConsultation').modal('show');
                                $("#congratsModalConsultation #payment-id").val(response.payment_id);
                            }
                        });
                    } else {
                        alert(response.message || 'An error occurred while booking the consultation.');
                        $('#payConsultationBtn').prop('disabled', false).html('Pay | $<span id="pay-button-price">' + price + '</span>');
                        resetConsultationPaymentProcessingState();
                    }
                },
                error: function (xhr) {
                    const response = xhr.responseJSON;
                    if (response && response.requires_auth) {
                        // User needs to login
                        $('#paymentModal').modal('hide');
                        $('#signupModalathlete').modal('show');
                        resetConsultationPaymentProcessingState();
                    } else {
                        // Show more informative error message
                        let errorMessage = 'An error occurred while booking the consultation. Please try again.';
                        if (response && response.message) {
                            errorMessage = response.message;
                        }

                        // Show error in a more user-friendly way
                        if (parseFloat(price) > 0) {
                            alert('Payment was processed but consultation booking failed. Your payment will be refunded automatically. Please try booking again.');
                        } else {
                            alert(errorMessage);
                        }

                        $('#payConsultationBtn').prop('disabled', false).html('Pay | $<span id="pay-button-price">' + price + '</span>');
                        resetConsultationPaymentProcessingState();
                    }
                }
            });
        }
    </script>
@endpush
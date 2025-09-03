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
                            <button class="btn-signup" id="book-consult-purchase-btn">Purchase plan</button>
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
            @if($section->section_type == \App\Models\Section::TYPE_CONSULTATION_INCLUSIONS && $section->enabled == 1)
                @php
                    $backgroundImage = '';
                    if (isset($section->banner_image[0])) {
                        $backgroundImage = asset('storage/' . $section->banner_image[0]);
                    }

                    if(isset($section->image[0])) {
                        $blueBadgeImg = asset('storage/' . $section->image[0]);
                    }
                @endphp
                <section class="plan-inclusion-section" style="background-image: url('{{ $backgroundImage }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
                    <div class="container-homepage">
                        <div class="title-content">
                            <h2 class="title">{{ $section->title }}</h2>
                        </div>
                        <!-- {!! $section->content !!} -->

                        <div class="plan-features consultations">
                            @foreach($consultations as $consultation)
                                @if($consultation->show_on_consultation_page)
                                    <div class="feature-column">
                                        <h3 class="mb-0 feature-title">{{ $consultation->time }} min Consultation</h3>

                                        {!! $consultation->content !!}

                                        <div class="pricing-section">
                                            <h2 class="pricing-amount">${{ number_format($consultation->price, 2) }} AUD</h2>
                                            <div class="pricing-buttons">
                                                <button class="btn-signup" data-consultation-id="{{ $consultation->id }}" data-bs-toggle="modal" data-bs-target="#paymentModal">Book consult</button>
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
@endsection


<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Monthly Payment Content -->
            <div id="paymentContentConsultation">
                <div class="pb-0 border-0 modal-header">
                    <h5 class="modal-title">Consultation</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="modal-subtitle">Consultation

                        XYZ min One on One, online Nutrition Consultation </p>
                    <p class="amount"><strong>A$120.</strong> <span class="text-muted">one time payment</span></p>

                    <span class="divider"></span>
                    <p class="mb-2 sign-in-text">Signed in as<br><strong>jordansmith@gmail.com</strong></p>
                    <a href="#" class="d-block mb-3 coupon-code" id="toggle-coupon-consultation">Add a Coupon
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

                    <form>
                        <div class="form-wrap">
                            <div class="mb-3">
                                <label class="form-label">Card number</label>
                                <input type="text" class="form-control" placeholder="1234 1234 1234 1234">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Name on card</label>
                                <input type="text" class="form-control" placeholder="Card name">
                            </div>
                            <div class="row">
                                <div class="mb-3 col-6">
                                    <label class="form-label">Expiry date</label>
                                    <input type="text" class="form-control" placeholder="MM/YY">
                                </div>
                                <div class="mb-3 col-6">
                                    <label class="form-label">CVV</label>
                                    <input type="text" class="form-control" placeholder="CVV">
                                </div>
                            </div>
                        </div>
                        <!-- Button that swaps modal content -->
                        <button type="button" class="w-100 btn btn-signup" id="monthlyBtn" >
                            Monthly | $51.25
                        </button>
                    </form>

                    <p class="mt-3 text-muted small confirm-text">
                        By confirming your monthly amount, you allow Athleaf Fuel to charge you for future payments
                        in accordance with your chosen plan for the next 8 months.
                    </p>
                    <p class="text-muted small confirm-text">
                        By placing your order, you agree to our <a href="#" class="terms-link">Terms of Service</a>
                        and <a href="#" class="terms-link">Privacy Policy</a>.
                    </p>
                </div>
            </div>



        </div>
    </div>
    </div>

    <!-- Congrats Modal for Consultation -->
    <div class="modal fade" id="congratsModalConsultation" tabindex="-1"
    aria-labelledby="congratsModalConsultationLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 12px;">
            <div id="congratsContentConsultation">
                <button type="button" class="btn-close congrats-modal" data-bs-dismiss="modal"
                    aria-label="Close"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                        viewBox="0 0 12 12" fill="none">
                        <path
                            d="M0.219668 1.28033C-0.0732225 0.987438 -0.0732225 0.512558 0.219668 0.219668C0.512558 -0.0732225 0.987438 -0.0732225 1.28033 0.219668L5.999 4.9384L10.7176 0.219798C11.0105 -0.0730923 11.4854 -0.0730923 11.7782 0.219798C12.0711 0.512688 12.0711 0.987568 11.7782 1.28046L7.0597 5.999L11.7782 10.7176C12.0711 11.0105 12.0711 11.4854 11.7782 11.7782C11.4854 12.0711 11.0105 12.0711 10.7176 11.7782L5.999 7.0597L1.28033 11.7784C0.987438 12.0713 0.512558 12.0713 0.219668 11.7784C-0.0732225 11.4855 -0.0732225 11.0106 0.219668 10.7177L4.9384 5.999L0.219668 1.28033Z"
                            fill="#626262" />
                    </svg></button>
                <img src="images/congrats-modal-img.png" alt="Congrats" class="rounded-top w-100">
                <div class="p-4 text-center modal-body">
                    <div class="mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="61" height="60" viewBox="0 0 61 60"
                            fill="none">
                            <path
                                d="M30.1875 53.75C43.4768 53.75 54.25 42.9768 54.25 29.6875C54.25 16.3981 43.4768 5.625 30.1875 5.625C16.8981 5.625 6.125 16.3981 6.125 29.6875C6.125 42.9768 16.8981 53.75 30.1875 53.75Z"
                                stroke="#3E8E00" stroke-width="3" />
                            <path
                                d="M19.25 30.625C20.2764 31.6514 22.6373 34.0123 25.2945 36.6695C26.2708 37.6458 27.8539 37.6461 28.8302 36.6698L42.375 23.125"
                                stroke="#3E8E00" stroke-width="3" stroke-linecap="round" />
                        </svg>
                    </div>
                    <h4 class="congrats-title"><strong>Congrats legend!</strong></h4>
                    <p class="mb-1 congrats-subtitle"><strong>You’re all set for your Consultation</strong><br></p>
                    <p class="congrats-para">
                        Lets book in a time 
                    </p>
                    <button type="button" class="w-100 btn btn-signup">Book a Time</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
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


    // Coming soon tooltip functionality
    function showComingSoonTooltip(button, platform) {
        // Remove any existing tooltips
        const existingTooltip = document.querySelector('.coming-soon-tooltip');
        if (existingTooltip) {
            existingTooltip.remove();
        }

        // Create tooltip element
        const tooltip = document.createElement('div');
        tooltip.className = 'coming-soon-tooltip';
        tooltip.textContent = 'Coming Soon!';

        // Position tooltip above the button
        const buttonRect = button.getBoundingClientRect();
        tooltip.style.position = 'fixed';
        tooltip.style.top = (buttonRect.top - 40) + 'px';
        tooltip.style.left = (buttonRect.left + buttonRect.width / 2 - 50) + 'px';
        tooltip.style.zIndex = '9999';

        // Add tooltip to body
        document.body.appendChild(tooltip);
    }

    function hideComingSoonTooltip() {
        const existingTooltip = document.querySelector('.coming-soon-tooltip');
        if (existingTooltip) {
            existingTooltip.remove();
        }
    }

    // Learn more tooltip functionality
    function showLearnMoreTooltip(button, planType) {
        // Remove any existing learn more tooltips
        const existingTooltip = document.querySelector('.learn-more-tooltip');
        if (existingTooltip) {
            existingTooltip.remove();
        }

        // Create tooltip element
        const tooltip = document.createElement('div');
        tooltip.className = 'learn-more-tooltip';
        tooltip.textContent = `${planType} `;

        // Position tooltip above the button
        const buttonRect = button.getBoundingClientRect();
        tooltip.style.position = 'fixed';
        tooltip.style.top = (buttonRect.top - 40) + 'px';
        tooltip.style.left = (buttonRect.left + buttonRect.width / 2 - 80) + 'px';
        tooltip.style.zIndex = '9999';

        // Add tooltip to body
        document.body.appendChild(tooltip);

        // Auto-hide tooltip after 3 seconds
        setTimeout(() => {
            const tooltipToRemove = document.querySelector('.learn-more-tooltip');
            if (tooltipToRemove) {
                tooltipToRemove.remove();
            }
        }, 3000);
    }

    // Scroll to contact section functionality
    function scrollToContact() {
        const contactSection = document.querySelector('#contact-section');
        if (contactSection) {
            const offset = 80; // Offset in pixels from the top
            const elementPosition = contactSection.getBoundingClientRect().top;
            const offsetPosition = elementPosition + window.pageYOffset - offset;

            window.scrollTo({
                top: offsetPosition,
                behavior: 'smooth'
            });
        }
    }

    // Add CSS for tooltip
    const tooltipStyle = document.createElement('style');
    tooltipStyle.textContent = `
    .coming-soon-tooltip {
    background-color: #333;
    color: white;
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    animation: tooltipFadeIn 0.3s ease-out;
    white-space: nowrap;
    }

    .coming-soon-tooltip::after {
    content: '';
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translateX(-50%);
    border: 6px solid transparent;
    border-top-color: #333;
    }

    .learn-more-tooltip {
    background-color: #333;
    color: white;
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    animation: tooltipFadeIn 0.3s ease-out;
    white-space: nowrap;
    }

    .learn-more-tooltip::after {
    content: '';
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translateX(-50%);
    border: 6px solid transparent;
    border-top-color: #333;
    }

    @keyframes tooltipFadeIn {
    from {
    opacity: 0;
    transform: translateY(20px);
    }
    to {
    opacity: 1;
    transform: translateY(0);
    }
    }

    /* Consultation Cards Styles */
    .consultations-list-section {
        background-color: #f8f9fa;
    }

    .consultation-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        border-radius: 12px;
    }

    .consultation-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
    }

    .consultation-card .card-title {
        color: #333;
        font-weight: 600;
        margin-bottom: 15px;
    }

    .consultation-card .card-text {
        color: #666;
        line-height: 1.6;
        margin-bottom: 20px;
    }

    .consultation-details {
        margin-bottom: 20px;
    }

    .price-time-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-top: 1px solid #eee;
    }

    .price {
        font-size: 1.25rem;
        font-weight: 700;
        color: #28a745;
    }

    .time {
        font-size: 0.9rem;
        color: #666;
        background-color: #f8f9fa;
        padding: 4px 8px;
        border-radius: 4px;
    }

    .consultation-card .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .consultation-card .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
        transform: translateY(-2px);
    }
    `;
    document.head.appendChild(tooltipStyle);



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

    // Chat widget interaction
    document
        .querySelector(".chat-widget")
        .addEventListener("click", function () {
            alert("Chat feature would open here!");
        });


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
        $('.plan-inclusion-section .btn-signup').on('click', function() {
            $('#TPMAIU-purchase-plan-btn').click();
        });

        $('#competition-plan-link').on('click', function() {
            window.location.href = "{{ route('front.competition.plan') }}";
        });
        $('#injury-plan-link').on('click', function() {
            showLearnMoreTooltip(this, 'Coming Soon')
            // window.location.href = "{{ route('front.injury.recovery.plan') }}";
        });
        $('#surgery-plan-link').on('click', function() {
            showLearnMoreTooltip(this, 'Coming Soon')
            // window.location.href = "{{ route('front.surgery.plan') }}";
        });
        $('#training-plan-link').on('click', function() {
            window.location.href = "{{ route('front.training.nutrition.plan') }}";
        });
        // Scroll to Consults section when book-consult-purchase-btn is clicked
        $('#book-consult-purchase-btn').on('click', function() {
            $('html, body').animate({
                scrollTop: $('.plan-inclusion-section').offset().top
            }, 800);
        });
    </script>

    <script>
        const onePaymentBtn = document.getElementById("onePaymentBtn");
        const monthlyPlanBtn = document.getElementById("monthlyPlanBtn");
        const onePaymentPlans = document.getElementById("onePaymentPlans");
        const monthlyPlans = document.getElementById("monthlyPlans");

        onePaymentBtn.addEventListener("click", () => {
            onePaymentPlans.classList.remove("d-none");
            monthlyPlans.classList.add("d-none");
            onePaymentBtn.classList.add("active");
            monthlyPlanBtn.classList.remove("active");
        });

        monthlyPlanBtn.addEventListener("click", () => {
            onePaymentPlans.classList.add("d-none");
            monthlyPlans.classList.remove("d-none");
            monthlyPlanBtn.classList.add("active");
            onePaymentBtn.classList.remove("active");
        });
    </script>

    <script>
        // Handle monthlyBtn in planChooseModal
        document.getElementById("monthlyBtn").addEventListener("click", function () {
            // Hide the current modal
            const planModal = bootstrap.Modal.getInstance(document.getElementById('planChooseModal'));
            if (planModal) {
                planModal.hide();
            }

            // Show the consultation congrats modal
            const congratsModalConsultation = new bootstrap.Modal(document.getElementById('congratsModalConsultation'));
            congratsModalConsultation.show();
        });

        // Handle consultation booking button
        const consultationBtn = document.querySelector('#paymentModal .btn-signup');
        if (consultationBtn) {
            consultationBtn.addEventListener("click", function () {
                // Hide the current modal
                const paymentModal = bootstrap.Modal.getInstance(document.getElementById('paymentModal'));
                if (paymentModal) {
                    paymentModal.hide();
                }

                // Show the consultation congrats modal
                const congratsModalConsultation = new bootstrap.Modal(document.getElementById('congratsModalConsultation'));
                congratsModalConsultation.show();
            });
        }

    </script>
    <script>
        document.getElementById("toggle-coupon").addEventListener("click", function (e) {
            e.preventDefault();
            document.getElementById("coupon-details").classList.toggle("d-none");
        });
    </script>
    <script>
        document.getElementById("toggle-coupon-consultation").addEventListener("click", function (e) {
            e.preventDefault();
            document.getElementById("coupon-details-consultation").classList.toggle("d-none");
        });
    </script>
    <script>
        document.getElementById("apply-promo-code").addEventListener("click", function () {
            // Add your promo code application logic here
            console.log("Promo code applied");
        });
    </script>
    <script>
        document.getElementById("apply-promo-code-consultation").addEventListener("click", function () {
            // Add your promo code application logic here
            console.log("Consultation promo code applied");
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
@endpush

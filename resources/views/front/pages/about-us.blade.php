@extends(frontView('layouts.app'))

@section('title', 'Expert in Athlete Performance & Nutrition Athletes | Performance Health')
@section('meta_description', 'Get a personalised athlete meal plan with Performance Health Support. Expert sports nutrition plans and diet strategies tailored to fuel performance and recovery.')

@section('content')
    @if(isset($page->sections))
        @foreach($page->sections as $section)
            @if($section->section_type == \App\Models\Section::TYPE_ABOUT_US_BANNER && $section->enabled == 1) <!-- done -->
                @php
                    $bannerImage = '';
                    if (isset($section->banner_image[0])) {
                        $bannerImage = $section->banner_image[0];
                    }
                @endphp
                <div class="hero-section-landing" style="background-image: url('{{ webAssets('storage/' . $bannerImage) }}')">
                    <div class="container-homepage">
                        <div class="hero-content-fixed">
                            {!! $section->content !!}
                        </div>
                    </div>
                </div>
            @endif
            <div class="container-homepage about-us">
                @if($section->section_type == \App\Models\Section::TYPE_ATHLETE_NUTRITION_FOCUS && $section->enabled == 1) <!-- done -->
                    @php
                        $bannerImage = '';
                        if (isset($section->banner_image[0])) {
                            $bannerImage = $section->banner_image[0];
                        }
                    @endphp
                    <section class="hero-section overlapping-img-section">
                        <h1 class="hero-title-landing">{{!empty($section->title) ? $section->title : ''}}</h1>
                        {!! $section->content !!}
                        <div class="profile-images">
                            @if(isset($section->image) && is_array($section->image))
                                @foreach($section->image as $index => $imagePath)
                                    <div class="profile-img">
                                        <img src="{{ webAssets('storage/' . $imagePath) }}" alt="Athlete Profile {{ $index + 1 }}">
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </section>
                @endif
                @if($section->section_type == \App\Models\Section::TYPE_ABOUT_KERRY_INTRO && $section->enabled == 1) <!-- done -->
                    @php
                        $bannerImage = '';
                        if (isset($section->banner_image[0])) {
                            $bannerImage = $section->banner_image[0];
                        }
                        $image = '';
                        if (isset($section->image[0])) {
                            $image = $section->image[0];
                        }
                    @endphp
                    <section class="kerry-section">
                        <div class="kerry-content">
                            <h2 class="kerry-title">{{!empty($section->title) ? $section->title : ''}}</h2>
                            {!! $section->content !!}
                        </div>
                        <div class="kerry-image">
                            <img src="{{ webAssets('storage/' . $image) }}" alt="Kerry O'Bryan">
                        </div>
                    </section>
                @endif
                @if($section->section_type == \App\Models\Section::TYPE_ABOUT_BOOKING && $section->enabled == 1) <!-- done -->
                    @php
                        $bannerImage = '';
                        $image = '';
                        if (isset($section->banner_image[0])) {
                            $bannerImage = $section->banner_image[0];
                        }
                        if (isset($section->image[0])) {
                            $image = $section->image[0];
                        }

                    @endphp
                <section class="biohealth-section">
                    <div class="biohealth-header">
                        <img src="{{ webAssets('storage/' . $image) }}" alt="biohealth-logo">
                    </div>
                    {!! $section->content !!}
                    <button class="learn-more-btn coming-soon-popup" id="biohealth-passport-learn-more-btn">Learn More
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            style="margin-left: 8px;">
                            <path
                                d="M17.9999 7.05C17.9999 6.78478 17.8946 6.53043 17.707 6.34289C17.5195 6.15536 17.2652 6.05 16.9999 6.05L8.99994 6C8.73472 6 8.48037 6.10536 8.29283 6.29289C8.1053 6.48043 7.99994 6.73478 7.99994 7C7.99994 7.26522 8.1053 7.51957 8.29283 7.70711C8.48037 7.89464 8.73472 8 8.99994 8H14.5599L6.28994 16.29C6.19621 16.383 6.12182 16.4936 6.07105 16.6154C6.02028 16.7373 5.99414 16.868 5.99414 17C5.99414 17.132 6.02028 17.2627 6.07105 17.3846C6.12182 17.5064 6.19621 17.617 6.28994 17.71C6.3829 17.8037 6.4935 17.8781 6.61536 17.9289C6.73722 17.9797 6.86793 18.0058 6.99994 18.0058C7.13195 18.0058 7.26266 17.9797 7.38452 17.9289C7.50638 17.8781 7.61698 17.8037 7.70994 17.71L15.9999 9.42V15C15.9999 15.2652 16.1053 15.5196 16.2928 15.7071C16.4804 15.8946 16.7347 16 16.9999 16C17.2652 16 17.5195 15.8946 17.707 15.7071C17.8946 15.5196 17.9999 15.2652 17.9999 15V7.05Z"
                                fill="white" />
                        </svg>
                    </button>
                </section>

                @endif
                @if($section->section_type == \App\Models\Section::TYPE_ATHLETES_WE_WORK_WITH && $section->enabled == 1) <!-- done -->
                    @php
                        $athleteImages = [];
                        foreach ($section->image as $image) {
                            $athleteImages[] = webAssets('storage/' . $image);
                        }
                    @endphp
                    <section class="athletes-section">
                        <div class="athletes-header">
                            <h2 class="athletes-title">{{!empty($section->title) ? $section->title : ''}}</h2>
                        </div>
                        <div class="athletes-grid">
                            {!! $section->content !!}
                        </div>
                    </section>
                @endif
            </div>
        @endforeach
    @endif
@endsection

@push('scripts')
   <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if athlete images data exists from PHP
            @if(isset($athleteImages) && is_array($athleteImages) && count($athleteImages) > 0)
                const athleteImages = @json($athleteImages);

                // Dynamically find all athlete elements and map them to images
                function updateAthleteImages() {
                    // Find all elements with IDs that start with 'athlete'
                    const athleteElements = document.querySelectorAll('[id^="athlete"]');

                    if (athleteElements.length === 0) {
                        console.log('No athlete elements found on the page');
                        return;
                    }

                    // Convert to array and sort by ID to ensure consistent ordering
                    const athleteArray = Array.from(athleteElements).sort((a, b) => {
                        // Extract numbers from IDs like 'athlete1', 'athlete2', etc.
                        const numA = parseInt(a.id.replace('athlete', ''));
                        const numB = parseInt(b.id.replace('athlete', ''));
                        return numA - numB;
                    });

                    // Map each athlete element to an image
                    athleteArray.forEach((athleteElement, index) => {
                        if (athleteImages[index]) {
                            athleteElement.src = athleteImages[index];
                            console.log(`Updated ${athleteElement.id} with image: ${athleteImages[index]}`);
                        } else {
                            console.warn(`No image available for ${athleteElement.id} at index ${index}`);
                        }
                    });
                }

                // Execute the dynamic update
                updateAthleteImages();
            @endif
        });


        $(document).ready(function() {
            // $('#biohealth-passport-learn-more-btn').click(function() {
            //     window.open('https://biohealthpassport.com.au/', '_blank');
            // });
        });
    </script>
@endpush

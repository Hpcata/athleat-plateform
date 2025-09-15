<!-- Plate Breakdown and Training Load -->
<section aria-label="Plate Breakdown and Training Load" style="margin-top: 2rem">
    <div class="section-header">
        <h2>{{ $title }}</h2>
    </div>
    <p>
        {{ $description }}
    </p>
    <div class="dropdown dropdown-container training-load-dropdown">
        {{-- add label --}}
        <label class="dropdown-label" for="trainingLoadDropdown">{{ $label }}</label>
        <button class="btn custom-dropdown-button dropdown-toggle" type="button" id="trainingLoadDropdown"
            data-bs-toggle="dropdown" aria-expanded="false">
            <div class="custom-dropdown-content">
                <div class="custom-dropdown-content-inner">
                    <div class="custom-dropdown-title">Low</div>
                    <div class="custom-dropdown-subtitle">Low load, rest and recovery days</div>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="8" viewBox="0 0 12 8" fill="none">
                    <path d="M1 1.5L6 6.5L11 1.5" stroke="#3B3B3B" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </div>
        </button>
        <ul class="dropdown-menu custom-dropdown-menu" aria-labelledby="trainingLoadDropdown">
            <li>
                <div class="custom-dropdown-option selected" data-value="low"
                    data-image="{{ webAssets('front/images/low-load.png') }}">
                    <div class="option-title">Low</div>
                    <div class="option-subtitle">Low load, rest and recovery days</div>
                </div>
            </li>
            <li>
                <div class="custom-dropdown-option" data-value="moderate"
                    data-image="{{ webAssets('front/images/medium-load.png') }}">
                    <div class="option-title">Medium </div>
                    <div class="option-subtitle">Training days</div>
                </div>
            </li>
            <li>
                <div class="custom-dropdown-option" data-value="high"
                    data-image="{{ webAssets('front/images/high-load.svg') }}">
                    <div class="option-title">High </div>
                    <div class="option-subtitle">Competition or heavy training days</div>
                </div>
            </li>
        </ul>
    </div>

    <div style="display: flex; align-items: start; gap: 1.5rem; flex-wrap: wrap; flex-direction:column;">
        <img src="{{ webAssets('front/images/low-load.png') }}" alt="Plate like this image" width="318" height="350"
            class="plate-img" id="plate-img" />
        <ul style="list-style: none; padding-left: 0; font-size: 1rem">
            <li class="list-w-image">
                <img src="{{ webAssets('front/images/boiled egg.svg') }}" alt="Plate like this image"
                    style="width: 32px; height: auto" width="32" height="33" />
                <div>
                    <span style="color: #A60015; font-weight: bold">{{ $proteinLabel }}</span>
                    <br />{{ $proteinDescription }}
                </div>
            </li>
            <li class="list-w-image">
                <img src="{{ webAssets('front/images/Bread.svg') }}" alt="Plate like this image"
                    style="width: 32px; height: auto" width="32" height="33" />
                <div>
                    <span style="color: #967500; font-weight: bold">{{ $fuelLabel }}</span>
                    <br />{{ $fuelDescription }}
                </div>
            </li>
            <li class="list-w-image">
                <img src="{{ webAssets('front/images/apple.svg') }}" alt="Plate like this image"
                    style="width: 32px; height: auto" width="32" height="33" />
                <div>
                    <span style="color: #3E8E00; font-weight: bold">{{ $protectLabel }}</span>
                    <br />{{ $protectDescription }}
                </div>
            </li>
        </ul>
    </div>
</section>
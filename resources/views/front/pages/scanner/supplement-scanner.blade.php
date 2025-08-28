@extends(frontView('layouts.app'))

@section('title', 'Supplement Scanner')
@section('meta_description', 'Supplement Scanner')

@push('styles')
    <link rel="stylesheet" href="{{ frontAssets('css/scanner.css') }}">
@endpush

@section('content')
<main class="main">
    <div class="container">
        <section class="supplement-scanner-section">
            <h1>Supplement scanner</h1>
            <p>
            Upload clear photos of front and back of product labels to check
            it's risk of containing banned substances.
            </p>
            <div class="info-box">
            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17" fill="none"
                style="margin-right: 4px; margin-top: 2px">
                <path
                d="M9.76777 11.5887H9.30039V7.85834C9.30039 7.69372 9.23499 7.53585 9.11859 7.41945C9.00219 7.30304 8.84431 7.23765 8.6797 7.23765H6.86728C6.70267 7.23765 6.54479 7.30304 6.42839 7.41945C6.31199 7.53585 6.24659 7.69372 6.24659 7.85834C6.24659 8.02296 6.31199 8.18083 6.42839 8.29723C6.54479 8.41363 6.70267 8.47903 6.86728 8.47903H8.05901V11.5887H6.86728C6.70267 11.5887 6.54479 11.6541 6.42839 11.7705C6.31199 11.8869 6.24659 12.0448 6.24659 12.2094C6.24659 12.374 6.31199 12.5319 6.42839 12.6483C6.54479 12.7647 6.70267 12.8301 6.86728 12.8301H9.76777C9.93238 12.8301 10.0903 12.7647 10.2067 12.6483C10.3231 12.5319 10.3885 12.374 10.3885 12.2094C10.3885 12.0448 10.3231 11.8869 10.2067 11.7705C10.0903 11.6541 9.93238 11.5887 9.76777 11.5887ZM8.31721 5.88765C8.50136 5.88765 8.68136 5.83304 8.83447 5.73074C8.98758 5.62844 9.10691 5.48303 9.17738 5.31291C9.24785 5.14278 9.26628 4.95558 9.23036 4.77498C9.19444 4.59438 9.10576 4.42848 8.97556 4.29827C8.84535 4.16807 8.67945 4.07939 8.49885 4.04347C8.31825 4.00755 8.13105 4.02598 7.96092 4.09645C7.7908 4.16692 7.64539 4.28625 7.54309 4.43936C7.44078 4.59247 7.38618 4.77247 7.38618 4.95661C7.38618 5.20354 7.48427 5.44035 7.65887 5.61495C7.83348 5.78956 8.07029 5.88765 8.31721 5.88765ZM8.31721 0.914062C6.26021 0.916362 4.28811 1.73452 2.83358 3.18905C1.37906 4.64358 0.560894 6.61568 0.558594 8.67268C0.56204 10.7293 1.38057 12.7008 2.83485 14.1551C4.28913 15.6093 6.26056 16.4279 8.31721 16.4313C10.3749 16.4313 12.3484 15.6139 13.8034 14.1589C15.2584 12.7038 16.0758 10.7304 16.0758 8.67268C16.0758 6.61497 15.2584 4.64153 13.8034 3.18651C12.3484 1.73149 10.3749 0.914063 8.31721 0.914062ZM12.8669 13.3316C11.7903 14.3805 10.384 15.0246 8.88651 15.1548C7.38907 15.2849 5.89272 14.893 4.65134 14.0455C3.40996 13.198 2.49998 11.9471 2.07581 10.5052C1.65164 9.06316 1.73939 7.51883 2.32417 6.13416C2.90896 4.7495 3.95476 3.60978 5.28415 2.90837C6.61355 2.20696 8.14466 1.98705 9.61772 2.28596C11.0908 2.58486 12.4151 3.38417 13.3659 4.54828C14.3168 5.71239 14.8356 7.1696 14.8345 8.67268C14.8366 9.54164 14.6634 10.4021 14.3254 11.2026C13.9873 12.0031 13.4912 12.7272 12.8669 13.3316Z"
                fill="#1751AA" />
            </svg>
            <p>
                Athletes should both check AND track their supplement use
                appropriately to limit the risk of failing a doping test.
            </p>
            </div>
            <div class="scanner-container">
            <div class="upload-section">
                <h2>Product Front Label</h2>
                <div class="upload-area" id="front-label-upload-area">
                <svg xmlns="http://www.w3.org/2000/svg" width="37" height="37" viewBox="0 0 37 37" fill="none"
                    class="upload-icon">
                    <g clip-path="url(#clip0_2916_5137)">
                    <path
                        d="M30.4336 7.66406H5.68359C5.06227 7.66406 4.55859 8.16774 4.55859 8.78906V29.0391C4.55859 29.6604 5.06227 30.1641 5.68359 30.1641H30.4336C31.0549 30.1641 31.5586 29.6604 31.5586 29.0391V8.78906C31.5586 8.16774 31.0549 7.66406 30.4336 7.66406Z"
                        stroke="#626262" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" />
                    <path
                        d="M4.55859 24.5379L11.6381 17.4584C11.7425 17.3539 11.8666 17.2711 12.0031 17.2145C12.1395 17.158 12.2858 17.1289 12.4336 17.1289C12.5813 17.1289 12.7276 17.158 12.8641 17.2145C13.0006 17.2711 13.1246 17.3539 13.2291 17.4584L19.5131 23.7424C19.6175 23.8469 19.7416 23.9298 19.8781 23.9863C20.0145 24.0428 20.1608 24.0719 20.3086 24.0719C20.4563 24.0719 20.6026 24.0428 20.7391 23.9863C20.8756 23.9298 20.9996 23.8469 21.1041 23.7424L24.0131 20.8334C24.1175 20.7289 24.2416 20.6461 24.3781 20.5895C24.5145 20.533 24.6608 20.5039 24.8086 20.5039C24.9563 20.5039 25.1026 20.533 25.2391 20.5895C25.3756 20.6461 25.4996 20.7289 25.6041 20.8334L31.5586 26.7879"
                        stroke="#626262" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" />
                    <path
                        d="M21.9961 16.6641C22.9281 16.6641 23.6836 15.9085 23.6836 14.9766C23.6836 14.0446 22.9281 13.2891 21.9961 13.2891C21.0641 13.2891 20.3086 14.0446 20.3086 14.9766C20.3086 15.9085 21.0641 16.6641 21.9961 16.6641Z"
                        fill="#626262" />
                    </g>
                    <defs>
                    <clipPath id="clip0_2916_5137">
                        <rect width="36" height="36" fill="white" transform="translate(0.0585938 0.914062)" />
                    </clipPath>
                    </defs>
                </svg>
                <button class="upload-button" onclick="document.getElementById('front-label-input').click()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17" fill="none">
                    <g clip-path="url(#clip0_2916_5147)">
                        <path
                        d="M0.947266 8.41335V14.2315C0.947266 14.6173 1.13457 14.9873 1.46796 15.2601C1.80136 15.5328 2.25355 15.6861 2.72504 15.6861H13.3917C13.8632 15.6861 14.3154 15.5328 14.6488 15.2601C14.9822 14.9873 15.1695 14.6173 15.1695 14.2315V8.41335M11.6139 4.04972L8.05838 1.14062M8.05838 1.14062L4.50282 4.04972M8.05838 1.14062V10.5952"
                        stroke="white" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
                    </g>
                    <defs>
                        <clipPath id="clip0_2916_5147">
                        <rect width="16" height="16" fill="white" transform="translate(0.0585938 0.414062)" />
                        </clipPath>
                    </defs>
                    </svg>
                    Upload image
                </button>
                <input type="file" id="front-label-input" accept="image/jpeg, image/png" hidden />
                <p>or drag and drop</p>
                <p>JPG, PNG</p>
                <div class="image-preview-container" style="display: none;">
                    <img id="front-label-preview" class="image-preview" src="" alt="Image Preview" />
                    <button class="remove-image-btn" onclick="removeImage('front-label')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <path d="M12 4L4 12M4 4L12 12" stroke="#666" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                    </svg>
                    </button>
                </div>
                </div>
            </div>
            <div class="upload-section">
                <h2>Product back label (Ingredients Panel)</h2>
                <div class="upload-area" id="back-label-upload-area">
                <svg xmlns="http://www.w3.org/2000/svg" width="37" height="37" viewBox="0 0 37 37" fill="none"
                    class="upload-icon">
                    <g clip-path="url(#clip0_2916_5137)">
                    <path
                        d="M30.4336 7.66406H5.68359C5.06227 7.66406 4.55859 8.16774 4.55859 8.78906V29.0391C4.55859 29.6604 5.06227 30.1641 5.68359 30.1641H30.4336C31.0549 30.1641 31.5586 29.6604 31.5586 29.0391V8.78906C31.5586 8.16774 31.0549 7.66406 30.4336 7.66406Z"
                        stroke="#626262" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" />
                    <path
                        d="M4.55859 24.5379L11.6381 17.4584C11.7425 17.3539 11.8666 17.2711 12.0031 17.2145C12.1395 17.158 12.2858 17.1289 12.4336 17.1289C12.5813 17.1289 12.7276 17.158 12.8641 17.2145C13.0006 17.2711 13.1246 17.3539 13.2291 17.4584L19.5131 23.7424C19.6175 23.8469 19.7416 23.9298 19.8781 23.9863C20.0145 24.0428 20.1608 24.0719 20.3086 24.0719C20.4563 24.0719 20.6026 24.0428 20.7391 23.9863C20.8756 23.9298 20.9996 23.8469 21.1041 23.7424L24.0131 20.8334C24.1175 20.7289 24.2416 20.6461 24.3781 20.5895C24.5145 20.533 24.6608 20.5039 24.8086 20.5039C24.9563 20.5039 25.1026 20.533 25.2391 20.5895C25.3756 20.6461 25.4996 20.7289 25.6041 20.8334L31.5586 26.7879"
                        stroke="#626262" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" />
                    <path
                        d="M21.9961 16.6641C22.9281 16.6641 23.6836 15.9085 23.6836 14.9766C23.6836 14.0446 22.9281 13.2891 21.9961 13.2891C21.0641 13.2891 20.3086 14.0446 20.3086 14.9766C20.3086 15.9085 21.0641 16.6641 21.9961 16.6641Z"
                        fill="#626262" />
                    </g>
                    <defs>
                    <clipPath id="clip0_2916_5137">
                        <rect width="36" height="36" fill="white" transform="translate(0.0585938 0.914062)" />
                    </clipPath>
                    </defs>
                </svg>
                <button class="upload-button" onclick="document.getElementById('back-label-input').click()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17" fill="none">
                    <g clip-path="url(#clip0_2916_5147)">
                        <path
                        d="M0.947266 8.41335V14.2315C0.947266 14.6173 1.13457 14.9873 1.46796 15.2601C1.80136 15.5328 2.25355 15.6861 2.72504 15.6861H13.3917C13.8632 15.6861 14.3154 15.5328 14.6488 15.2601C14.9822 14.9873 15.1695 14.6173 15.1695 14.2315V8.41335M11.6139 4.04972L8.05838 1.14062M8.05838 1.14062L4.50282 4.04972M8.05838 1.14062V10.5952"
                        stroke="white" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
                    </g>
                    <defs>
                        <clipPath id="clip0_2916_5147">
                        <rect width="16" height="16" fill="white" transform="translate(0.0585938 0.414062)" />
                        </clipPath>
                    </defs>
                    </svg>
                    Upload image
                </button>
                <input type="file" id="back-label-input" accept="image/jpeg, image/png" hidden />
                <p>or drag and drop</p>
                <p>JPG, PNG</p>
                <div class="image-preview-container" style="display: none;">
                    <img id="back-label-preview" class="image-preview" src="" alt="Image Preview" />
                    <button class="remove-image-btn" onclick="removeImage('back-label')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <path d="M12 4L4 12M4 4L12 12" stroke="#666" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                    </svg>
                    </button>
                </div>
                </div>
            </div>
            <div class="upload-section">
                <h2>Additional Image (Optional)</h2>
                <div class="upload-area" id="additional-image-upload-area">
                <svg xmlns="http://www.w3.org/2000/svg" width="37" height="37" viewBox="0 0 37 37" fill="none"
                    class="upload-icon">
                    <g clip-path="url(#clip0_2916_5137)">
                    <path
                        d="M30.4336 7.66406H5.68359C5.06227 7.66406 4.55859 8.16774 4.55859 8.78906V29.0391C4.55859 29.6604 5.06227 30.1641 5.68359 30.1641H30.4336C31.0549 30.1641 31.5586 29.6604 31.5586 29.0391V8.78906C31.5586 8.16774 31.0549 7.66406 30.4336 7.66406Z"
                        stroke="#626262" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" />
                    <path
                        d="M4.55859 24.5379L11.6381 17.4584C11.7425 17.3539 11.8666 17.2711 12.0031 17.2145C12.1395 17.158 12.2858 17.1289 12.4336 17.1289C12.5813 17.1289 12.7276 17.158 12.8641 17.2145C13.0006 17.2711 13.1246 17.3539 13.2291 17.4584L19.5131 23.7424C19.6175 23.8469 19.7416 23.9298 19.8781 23.9863C20.0145 24.0428 20.1608 24.0719 20.3086 24.0719C20.4563 24.0719 20.6026 24.0428 20.7391 23.9863C20.8756 23.9298 20.9996 23.8469 21.1041 23.7424L24.0131 20.8334C24.1175 20.7289 24.2416 20.6461 24.3781 20.5895C24.5145 20.533 24.6608 20.5039 24.8086 20.5039C24.9563 20.5039 25.1026 20.533 25.2391 20.5895C25.3756 20.6461 25.4996 20.7289 25.6041 20.8334L31.5586 26.7879"
                        stroke="#626262" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" />
                    <path
                        d="M21.9961 16.6641C22.9281 16.6641 23.6836 15.9085 23.6836 14.9766C23.6836 14.0446 22.9281 13.2891 21.9961 13.2891C21.0641 13.2891 20.3086 14.0446 20.3086 14.9766C20.3086 15.9085 21.0641 16.6641 21.9961 16.6641Z"
                        fill="#626262" />
                    </g>
                    <defs>
                    <clipPath id="clip0_2916_5137">
                        <rect width="36" height="36" fill="white" transform="translate(0.0585938 0.914062)" />
                    </clipPath>
                    </defs>
                </svg>
                <button class="upload-button" onclick="document.getElementById('additional-image-input').click()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17" fill="none">
                    <g clip-path="url(#clip0_2916_5147)">
                        <path
                        d="M0.947266 8.41335V14.2315C0.947266 14.6173 1.13457 14.9873 1.46796 15.2601C1.80136 15.5328 2.25355 15.6861 2.72504 15.6861H13.3917C13.8632 15.6861 14.3154 15.5328 14.6488 15.2601C14.9822 14.9873 15.1695 14.6173 15.1695 14.2315V8.41335M11.6139 4.04972L8.05838 1.14062M8.05838 1.14062L4.50282 4.04972M8.05838 1.14062V10.5952"
                        stroke="white" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
                    </g>
                    <defs>
                        <clipPath id="clip0_2916_5147">
                        <rect width="16" height="16" fill="white" transform="translate(0.0585938 0.414062)" />
                        </clipPath>
                    </defs>
                    </svg>
                    Upload image
                </button>
                <input type="file" id="additional-image-input" accept="image/jpeg, image/png" hidden />
                <p>or drag and drop</p>
                <p>JPG, PNG</p>
                <div class="image-preview-container" style="display: none;">
                    <img id="additional-image-preview" class="image-preview" src="" alt="Image Preview" />
                    <button class="remove-image-btn" onclick="removeImage('additional-image')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <path d="M12 4L4 12M4 4L12 12" stroke="#666" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                    </svg>
                    </button>
                </div>
                </div>
            </div>
            </div>
            <button class="scan-button" onclick="scanNow()">
            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17" fill="none">
                <path fill-rule="evenodd" clip-rule="evenodd"
                d="M6.55827 1.74609H3.8916C3.36093 1.74609 2.85227 1.95676 2.4776 2.33209C2.10227 2.70676 1.8916 3.21543 1.8916 3.74609C1.8916 4.45476 1.8916 5.39009 1.8916 6.41276C1.8916 6.78076 2.19027 7.07943 2.55827 7.07943C2.92627 7.07943 3.22493 6.78076 3.22493 6.41276V3.74609C3.22493 3.56943 3.29493 3.39943 3.42027 3.27476C3.54493 3.14943 3.71493 3.07943 3.8916 3.07943H6.55827C6.92627 3.07943 7.22493 2.78076 7.22493 2.41276C7.22493 2.04476 6.92627 1.74609 6.55827 1.74609ZM1.8916 9.74609V13.0794C1.8916 13.6101 2.10227 14.1188 2.4776 14.4934C2.85227 14.8688 3.36093 15.0794 3.8916 15.0794C4.44293 15.0794 5.1316 15.0794 5.8916 15.0794C6.2596 15.0794 6.55827 14.7808 6.55827 14.4128C6.55827 14.0448 6.2596 13.7461 5.8916 13.7461H3.8916C3.71493 13.7461 3.54493 13.6761 3.42027 13.5508C3.29493 13.4261 3.22493 13.2561 3.22493 13.0794V9.74609C3.22493 9.37809 2.92627 9.07943 2.55827 9.07943C2.19027 9.07943 1.8916 9.37809 1.8916 9.74609ZM10.5583 15.0794H13.2249C13.7556 15.0794 14.2643 14.8688 14.6389 14.4934C15.0143 14.1188 15.2249 13.6101 15.2249 13.0794C15.2249 12.3708 15.2249 11.4354 15.2249 10.4128C15.2249 10.0448 14.9263 9.74609 14.5583 9.74609C14.1903 9.74609 13.8916 10.0448 13.8916 10.4128V13.0794C13.8916 13.2561 13.8216 13.4261 13.6963 13.5508C13.5716 13.6761 13.4016 13.7461 13.2249 13.7461H10.5583C10.1903 13.7461 9.8916 14.0448 9.8916 14.4128C9.8916 14.7808 10.1903 15.0794 10.5583 15.0794ZM15.2249 6.41276V3.74609C15.2249 3.21543 15.0143 2.70676 14.6389 2.33209C14.2643 1.95676 13.7556 1.74609 13.2249 1.74609C12.5163 1.74609 11.5809 1.74609 10.5583 1.74609C10.1903 1.74609 9.8916 2.04476 9.8916 2.41276C9.8916 2.78076 10.1903 3.07943 10.5583 3.07943H13.2249C13.4016 3.07943 13.5716 3.14943 13.6963 3.27476C13.8216 3.39943 13.8916 3.56943 13.8916 3.74609V6.41276C13.8916 6.78076 14.1903 7.07943 14.5583 7.07943C14.9263 7.07943 15.2249 6.78076 15.2249 6.41276ZM5.22493 9.07943H11.8916C12.2596 9.07943 12.5583 8.78076 12.5583 8.41276C12.5583 8.04476 12.2596 7.74609 11.8916 7.74609H5.22493C4.85693 7.74609 4.55827 8.04476 4.55827 8.41276C4.55827 8.78076 4.85693 9.07943 5.22493 9.07943Z"
                fill="white" />
            </svg>
            Scan now
            </button>
        </section>

        <section class="scan-results d-none" id="scan-result-section">
            <div class="content">
                <h2>Scan results</h2>
                <div class="info-section">
                    <div class="info-row">
                        <span class="info-label">Name</span>
                        <span class="info-value" id="food-name">Gold Standard Whey Protein</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Classification</span>
                        <!-- Use class to style based on classification: 'Food' or 'Supplement' -->
                        <span class="info-value" data-classification="Food|Supplement" id="classification-type"></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Batch Tested</span>
                        <!-- Use class to style based on batch testing status: 'Yes', 'No', or 'Unsure' -->
                        <span class="info-value" data-batch-tested="Yes|No|Unsure" id="batch-tested"></span>
                    </div>
                    <!-- Conditionally shown only when batch tested is 'Yes' -->
                    <div class="info-row" data-batch-number-row style="display: none;">
                        <span class="info-label">Batch Number</span>
                        <span class="info-value" id="batch-number"></span>
                    </div>
                </div>
                <div class="risk-section" data-risk-level="Low|High|Unknown">
                    <div class="risk-header">
                        <!-- Risk icon: Use SVG for High/Unknown risk, simple 'i' for Low risk -->
                        <div class="risk-icon" data-risk-icon="Low|High|Unknown" id="risk-icon">
                            <!-- SVG for High/Unknown risk; conditionally included -->
                            <!-- <svg class="risk-svg" style="display: none;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M8.0003 10.6681C7.86845 10.6681 7.73956 10.7072 7.62992 10.7805C7.52029 10.8538 7.43484 10.9579 7.38438 11.0797C7.33393 11.2015 7.32072 11.3356 7.34645 11.4649C7.37217 11.5942 7.43566 11.713 7.5289 11.8062C7.62213 11.8995 7.74092 11.9629 7.87024 11.9887C7.99956 12.0144 8.13361 12.0012 8.25543 11.9507C8.37724 11.9003 8.48136 11.8148 8.55462 11.7052C8.62787 11.5956 8.66697 11.4667 8.66697 11.3348C8.66697 11.158 8.59673 10.9884 8.47171 10.8634C8.34668 10.7384 8.17711 10.6681 8.0003 10.6681ZM15.1136 11.6481L9.74697 2.31482C9.57351 2.00383 9.32016 1.74479 9.0131 1.56446C8.70604 1.38414 8.3564 1.28906 8.0003 1.28906C7.64421 1.28906 7.29457 1.38414 6.98751 1.56446C6.68045 1.74479 6.4271 2.00383 6.25364 2.31482L0.920304 11.6481C0.740834 11.9508 0.644399 12.2955 0.640734 12.6474C0.637068 12.9992 0.726303 13.3458 0.899428 13.6522C1.07255 13.9585 1.32344 14.2138 1.62676 14.3922C1.93008 14.5705 2.27509 14.6657 2.62697 14.6681H13.3736C13.7283 14.6716 14.0776 14.5807 14.3856 14.4048C14.6936 14.2288 14.9492 13.9741 15.1263 13.6667C15.3034 13.3593 15.3955 13.0104 15.3933 12.6557C15.3911 12.301 15.2946 11.9533 15.1136 11.6481ZM13.9603 12.9815C13.9019 13.0855 13.8166 13.1718 13.7134 13.2316C13.6102 13.2914 13.4929 13.3225 13.3736 13.3215H2.62697C2.50771 13.3225 2.39037 13.2914 2.28718 13.2316C2.18399 13.1718 2.09874 13.0855 2.0403 12.9815C1.98179 12.8801 1.95099 12.7652 1.95099 12.6481C1.95099 12.5311 1.98179 12.4162 2.0403 12.3148L7.37364 2.98148C7.42958 2.87228 7.51458 2.78064 7.61927 2.71665C7.72395 2.65265 7.84427 2.61879 7.96697 2.61879C8.08967 2.61879 8.20999 2.65265 8.31467 2.71665C8.41936 2.78064 8.50436 2.87228 8.5603 2.98148L13.927 12.3148C13.9931 12.4147 14.0311 12.5306 14.037 12.6502C14.0428 12.7699 14.0164 12.8889 13.9603 12.9948V12.9815ZM8.0003 5.33482C7.82349 5.33482 7.65392 5.40505 7.5289 5.53008C7.40387 5.6551 7.33364 5.82467 7.33364 6.00148V8.66815C7.33364 8.84496 7.40387 9.01453 7.5289 9.13955C7.65392 9.26458 7.82349 9.33482 8.0003 9.33482C8.17711 9.33482 8.34668 9.26458 8.47171 9.13955C8.59673 9.01453 8.66697 8.84496 8.66697 8.66815V6.00148C8.66697 5.82467 8.59673 5.6551 8.47171 5.53008C8.34668 5.40505 8.17711 5.33482 8.0003 5.33482Z" fill="#A85526" />
                            </svg> -->
                        </div>
                        <!-- Risk title: Low, High, or Unknown -->
                        <span class="risk-title"></span>
                    </div>
                    <!-- Risk description: Varies by case -->
                    <p class="risk-description"></p>
                    <!-- Visit store link: Shown for High and Unknown risk -->
                    <div class="visit-store" style="display: none;">
                        <label>Visit store</label>
                        <svg xmlns="http://www.w3.org/2000/svg" width="8" height="9" viewBox="0 0 8 9" fill="none">
                            <path d="M8 1.19966C8 1.02294 7.9298 0.853449 7.80483 0.728485C7.67987 0.603521 7.51038 0.533317 7.33366 0.533317L2.0029 0.5C1.82617 0.5 1.65668 0.570204 1.53172 0.695168C1.40676 0.820132 1.33655 0.989619 1.33655 1.16634C1.33655 1.34307 1.40676 1.51256 1.53172 1.63752C1.65668 1.76249 1.82617 1.83269 2.0029 1.83269H5.70777L0.197104 7.35669C0.134648 7.41863 0.0850761 7.49233 0.0512467 7.57353C0.0174172 7.65473 0 7.74183 0 7.82979C0 7.91776 0.0174172 8.00485 0.0512467 8.08605C0.0850761 8.16725 0.134648 8.24095 0.197104 8.3029C0.259049 8.36535 0.332747 8.41492 0.413948 8.44875C0.495148 8.48258 0.582243 8.5 0.670208 8.5C0.758174 8.5 0.845269 8.48258 0.926469 8.44875C1.00767 8.41492 1.08137 8.36535 1.14331 8.3029L6.66731 2.7789V6.4971C6.66731 6.67383 6.73751 6.84332 6.86248 6.96828C6.98744 7.09324 7.15693 7.16345 7.33366 7.16345C7.51038 7.16345 7.67987 7.09324 7.80483 6.96828C7.9298 6.84332 8 6.67383 8 6.4971V1.19966Z" fill="#A85526" />
                        </svg>
                    </div>
                </div>
                <!-- Save section: Shown only when batch tested is 'Yes' -->
                <div class="save-section" style="display: none;">
                    <h2>Save & track results</h2>
                    <div class="passport-button">
                        <div class="passport-wrap">
                            <img src="{{ frontAssets('images/biohelathlogo.svg') }}" alt="logo" class="" width="36" height="36" />
                            <label>My BioHealth Passport</label>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="7" height="12" viewBox="0 0 7 12" fill="none">
                            <path d="M1 11L6 6L1 1" stroke="#626262" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                </div>
                <!-- Upload button: Shown for High and Unknown risk -->
                <button class="upload-button" style="display: none;">Athleat.shop</button>
            </div>
        </section>

        <div class="disclaimer">
            The automated tool provides a general analysis, if unsure don't take it.
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
    // Global variables to store uploaded images
    let frontImage = null;
    let backImage = null;
    let additionalImage = null;

    // Handle front label image upload
    document.getElementById('front-label-input').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            frontImage = file;
            displayImagePreview(file, 'front-label');
        }
    });

    // Handle back label image upload
    document.getElementById('back-label-input').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            backImage = file;
            displayImagePreview(file, 'back-label');
        }
    });

    // Handle additional image upload
    document.getElementById('additional-image-input').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            additionalImage = file;
            displayImagePreview(file, 'additional-image');
        }
    });

    // Display image preview
    function displayImagePreview(file, type) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById(type + '-preview');
            const container = preview.parentElement;
            preview.src = e.target.result;
           // Hide upload UI and show preview (match script.js behavior)
            const uploadArea = document.getElementById(type + '-upload-area');
            const uploadIcon = uploadArea.querySelector('.upload-icon');
            const uploadButton = uploadArea.querySelector('.upload-button');
            const dragDropText = uploadArea.querySelectorAll('p');
            const previewContainer = uploadArea.querySelector('.image-preview-container');

            if (uploadIcon) uploadIcon.style.display = 'none';
            if (uploadButton) uploadButton.style.display = 'none';
            dragDropText.forEach(p => p.style.display = 'none');
            if (previewContainer) previewContainer.style.display = 'flex';
            uploadArea.classList.add('has-image');
        };
        reader.readAsDataURL(file);
    }

    // Remove image
    function removeImage(type) {
        if (type === 'front-label') {
            frontImage = null;
            document.getElementById('front-label-input').value = '';
        } else if (type === 'back-label') {
            backImage = null;
            document.getElementById('back-label-input').value = '';
        } else if (type === 'additional-image') {
            additionalImage = null;
            document.getElementById('additional-image-input').value = '';
        }
         const uploadArea = document.getElementById(type + '-upload-area');
        const uploadIcon = uploadArea.querySelector('.upload-icon');
        const uploadButton = uploadArea.querySelector('.upload-button');
        const dragDropText = uploadArea.querySelectorAll('p');
        const previewContainer = uploadArea.querySelector('.image-preview-container');
        const container = document.querySelector(`#${type}-upload-area .image-preview-container`);
        if (previewContainer) previewContainer.style.display = 'none';
        if (uploadIcon) uploadIcon.style.display = '';
        if (uploadButton) uploadButton.style.display = '';
        dragDropText.forEach(p => p.style.display = '');
        uploadArea.classList.remove('has-image');

        
        container.style.display = 'none';
    }

    // Scan now function
    function scanNow() {
        // Check if both images are uploaded
        if (!frontImage || !backImage) {
            alert('Please upload both front and back label images before scanning.');
            return;
        }

        // Create FormData for the API call
        const formData = new FormData();
        formData.append('front_image', frontImage);
        formData.append('back_image', backImage);
        
        // Add additional image if it exists
        if (additionalImage) {
            formData.append('additional_image', additionalImage);
        }

        // Show loading state
        const scanButton = document.querySelector('.scan-button');
        const originalText = scanButton.innerHTML;
        scanButton.innerHTML = '<span>Scanning...</span>';
        scanButton.disabled = true;

        // Make API call
        fetch('https://athleat.app.n8n.cloud/webhook/upload-image-test', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            console.log('API Response:', response);
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('API Response:', data);

            $('#scan-result-section').removeClass('d-none');

            // Set product name
            $('#scan-result-section').find('#food-name').text(data.product_name || 'Product Name Not Available');
            
            // Set classification
            const classificationElement = $('#scan-result-section').find('#classification-type');
            classificationElement.text(data.product_type.charAt(0).toUpperCase() + data.product_type.slice(1));
            
            if (data.product_type.toLowerCase() === 'supplement') {
                classificationElement.addClass('supplement-tag');
            } else {
                classificationElement.addClass('yes-tag');
            }
            
            // Set batch tested status
            const batchTestedElement = $('#scan-result-section').find('#batch-tested');
            batchTestedElement.text(data.batch_tested.charAt(0).toUpperCase() + data.batch_tested.slice(1));
            
            if (data.batch_tested.toLowerCase() === 'no') {
                batchTestedElement.addClass('no-tag').removeClass('yes-tag').removeClass('unsure-tag');
            } else if(data.batch_tested.toLowerCase() === 'yes') {
                batchTestedElement.addClass('yes-tag').removeClass('no-tag').removeClass('unsure-tag');
            } else {
                batchTestedElement.addClass('unsure-tag').removeClass('no-tag').removeClass('yes-tag');
            }

            // Handle batch number if available
            if(data.batch_number && data.batch_tested.toLowerCase() === 'yes'){
                $('#scan-result-section').find('#batch-number').text(data.batch_number);
                $('#scan-result-section').find('[data-batch-number-row]').show();
            } else {
                $('#scan-result-section').find('[data-batch-number-row]').hide();
            }

            // Handle risk assessment and UI based on classification and batch testing
            const riskSection = $('#scan-result-section').find('.risk-section');
            const riskIcon = riskSection.find('.risk-icon');
            const riskTitle = riskSection.find('.risk-title');
            const riskDescription = riskSection.find('.risk-description');
            const visitStore = riskSection.find('.visit-store');
            const saveSection = $('#scan-result-section').find('.save-section');
            const athleatButton = $('#scan-result-section').find('.upload-button');

            // Reset all sections first
            riskSection.removeClass('high-risk unknown-risk');
            visitStore.hide();
            saveSection.hide();
            athleatButton.hide();

            if (data.product_type.toLowerCase() === 'food' && data.batch_tested.toLowerCase() === 'no') {
                // Food + No batch testing = Low risk
                riskSection.removeClass('high-risk unknown-risk');
                riskIcon.html('i');
                riskTitle.text('Low risk');
                riskDescription.text(data.risk_message || 'This product is classified as a food and is subject to food manufacturing standards. It is considered very low risk of contamination with banned substances, and doesn\'t require batch testing.');
            } 
            else if (data.product_type.toLowerCase() === 'supplement' && data.batch_tested.toLowerCase() === 'no') {
                // Supplement + No batch testing = High risk
                riskSection.addClass('high-risk');
                riskTitle.text('High risk');
                riskDescription.text(data.risk_message || 'This supplement has not been batch tested, and is considered high risk for containing banned substances. Visit this store for safe alternatives.');
                visitStore.show();
                athleatButton.show();
            }
            else if (data.product_type.toLowerCase() === 'supplement' && data.batch_tested.toLowerCase() === 'yes') {
                // Supplement + Yes batch testing = Low risk
                riskSection.removeClass('high-risk unknown-risk');
                riskIcon.html('i');
                riskTitle.text('Low risk');
                riskDescription.text(data.risk_message || 'This supplement has been batch tested for banned substances, so considered very low risk of contamination.');
                saveSection.show();
            }
            else if (data.product_type.toLowerCase() === 'supplement' && data.batch_tested.toLowerCase() === 'unsure') {
                // Supplement + Unsure batch testing = Unknown risk
                riskSection.addClass('unknown-risk');
                riskTitle.text('Unknown risk');
                riskDescription.text(data.risk_message || 'Batch Testing logo\'s have not been detected, however the companies website refers to testing. Visit this store for safe supplement alternatives.');
                visitStore.show();
                athleatButton.show();
            } else if (data.product_type.toLowerCase() === 'unsure' && data.batch_tested.toLowerCase() === 'unsure') {
                // Supplement + Unsure batch testing = Unknown risk
                riskSection.addClass('unknown-risk');
                riskTitle.text('Unknown risk');
                riskDescription.text(data.risk_message);
                visitStore.show();
                athleatButton.show();
            } else {
                // Default case - Low risk
                riskSection.removeClass('high-risk unknown-risk');
                riskIcon.html('i');
                riskTitle.text('Low risk');
                riskDescription.text(data.risk_message || 'This product is considered low risk.');
            }

            // Add SVG outside of risk-icon for high/unknown risk
            if (riskSection.hasClass('high-risk') || riskSection.hasClass('unknown-risk')) {
                const svg = '<svg class="risk-svg" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M8.0003 10.6681C7.86845 10.6681 7.73956 10.7072 7.62992 10.7805C7.52029 10.8538 7.43484 10.9579 7.38438 11.0797C7.33393 11.2015 7.32072 11.3356 7.34645 11.4649C7.37217 11.5942 7.43566 11.713 7.5289 11.8062C7.62213 11.8995 7.74092 11.9629 7.87024 11.9887C7.99956 12.0144 8.13361 12.0012 8.25543 11.9507C8.37724 11.9003 8.48136 11.8148 8.55462 11.7052C8.62787 11.5956 8.66697 11.4667 8.66697 11.3348C8.66697 11.158 8.59673 10.9884 8.47171 10.8634C8.34668 10.7384 8.17711 10.6681 8.0003 10.6681ZM15.1136 11.6481L9.74697 2.31482C9.57351 2.00383 9.32016 1.74479 9.0131 1.56446C8.70604 1.38414 8.3564 1.28906 8.0003 1.28906C7.64421 1.28906 7.29457 1.38414 6.98751 1.56446C6.68045 1.74479 6.4271 2.00383 6.25364 2.31482L0.920304 11.6481C0.740834 11.9508 0.644399 12.2955 0.640734 12.6474C0.637068 12.9992 0.726303 13.3458 0.899428 13.6522C1.07255 13.9585 1.32344 14.2138 1.62676 14.3922C1.93008 14.5705 2.27509 14.6657 2.62697 14.6681H13.3736C13.7283 14.6716 14.0776 14.5807 14.3856 14.4048C14.6936 14.2288 14.9492 13.9741 15.1263 13.6667C15.3034 13.3593 15.3955 13.0104 15.3933 12.6557C15.3911 12.301 15.2946 11.9533 15.1136 11.6481ZM13.9603 12.9815C13.9019 13.0855 13.8166 13.1718 13.7134 13.2316C13.6102 13.2914 13.4929 13.3225 13.3736 13.3215H2.62697C2.50771 13.3225 2.39037 13.2914 2.28718 13.2316C2.18399 13.1718 2.09874 13.0855 2.0403 12.9815C1.98179 12.8801 1.95099 12.7652 1.95099 12.6481C1.95099 12.5311 1.98179 12.4162 2.0403 12.3148L7.37364 2.98148C7.42958 2.87228 7.51458 2.78064 7.61927 2.71665C7.72395 2.65265 7.84427 2.61879 7.96697 2.61879C8.08967 2.61879 8.20999 2.65265 8.31467 2.71665C8.41936 2.78064 8.50436 2.87228 8.5603 2.98148L13.927 12.3148C13.9931 12.4147 14.0311 12.5306 14.037 12.6502C14.0428 12.7699 14.0164 12.8889 13.9603 12.9948V12.9815ZM8.0003 5.33482C7.82349 5.33482 7.65392 5.40505 7.5289 5.53008C7.40387 5.6551 7.33364 5.82467 7.33364 6.00148V8.66815C7.33364 8.84496 7.40387 9.01453 7.5289 9.13955C7.65392 9.26458 7.82349 9.33482 8.0003 9.33482C8.17711 9.33482 8.34668 9.26458 8.47171 9.13955C8.59673 9.01453 8.66697 8.84496 8.66697 8.66815V6.00148C8.66697 5.82467 8.59673 5.6551 8.47171 5.53008C8.34668 5.40505 8.17711 5.33482 8.0003 5.33482Z" fill="#A85526" /></svg>';
                $('.risk-svg').remove();
                riskIcon.after(svg);
                $('#risk-icon').hide();
            } else {
                // Remove any existing SVG
                riskSection.find('.risk-svg').remove();
                $('#risk-icon').show();
            }
        })
        .catch(error => {
            $('#scan-result-section').addClass('d-none');
            console.error('Error:', error);
            alert('Invalid image format OR response error from OpenAI');
        })
        .finally(() => {
            // Reset button state
            scanButton.innerHTML = originalText;
            scanButton.disabled = false;
        });
    }

    // Drag and drop functionality
    function setupDragAndDrop() {
        const frontArea = document.getElementById('front-label-upload-area');
        const backArea = document.getElementById('back-label-upload-area');
        const additionalArea = document.getElementById('additional-image-upload-area');

        [frontArea, backArea, additionalArea].forEach(area => {
            area.addEventListener('dragover', (e) => {
                e.preventDefault();
                area.style.borderColor = '#1751AA';
            });

            area.addEventListener('dragleave', (e) => {
                e.preventDefault();
                area.style.borderColor = '#626262';
            });

            area.addEventListener('drop', (e) => {
                e.preventDefault();
                area.style.borderColor = '#626262';
                
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    const file = files[0];
                    if (file.type.startsWith('image/')) {
                        if (area === frontArea) {
                            frontImage = file;
                            displayImagePreview(file, 'front-label');
                        } else if (area === backArea) {
                            backImage = file;
                            displayImagePreview(file, 'back-label');
                        } else if (area === additionalArea) {
                            additionalImage = file;
                            displayImagePreview(file, 'additional-image');
                        }
                    }
                }
            });
        });
    }

    // Initialize drag and drop when page loads
    document.addEventListener('DOMContentLoaded', function() {
        setupDragAndDrop();
    });
</script>
@endpush

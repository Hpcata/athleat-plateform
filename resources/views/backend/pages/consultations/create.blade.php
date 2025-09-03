@extends(backendView('layouts.app'))

@section('title', 'Create Consultation')

@section('content')
<div class="container-xxl">
    <div class="row align-items-center">
        <div class="border-0 mb-4">
            <div class="card-header pb-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom">
                <h3 class="fw-bold mb-0">Create Consultation</h3>
                <a type="button" href="{{ route('dashboard') }}" class="btn btn-primary btn-set-task">Back</a>
            </div>
        </div>
    </div>
    <div class="row align-item-center">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-body">
                    <form action="{{ route('backend.consultations.store') }}" method="POST">
                        @csrf
                        <div class="row g-3 align-items-center">
                            <!-- Content Field -->
                            <div class="col-md-12">
                                <label for="content" class="form-label">Consultation Content</label>
                                <textarea class="form-control" id="content" name="content" rows="5" required placeholder="Enter consultation content...">{{ old('content') }}</textarea>
                                @error('content')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Price Field -->
                            <div class="col-md-6">
                                <label for="price" class="form-label">Price ($)</label>
                                <input type="number" class="form-control" id="price" name="price" step="0.01" min="0" required placeholder="0.00" value="{{ old('price') }}">
                                @error('price')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Time Field -->
                            <div class="col-md-6">
                                <label for="time" class="form-label">Time (Minutes)</label>
                                <input type="number" class="form-control" id="time" name="time" min="1" required placeholder="30" value="{{ old('time') }}">
                                @error('time')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Show on Consultation Page Field -->
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="show_on_consultation_page" name="show_on_consultation_page" value="1" {{ old('show_on_consultation_page') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="show_on_consultation_page">
                                        Show on Consultation Page
                                    </label>
                                </div>
                                @error('show_on_consultation_page')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-4">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<!-- plugin css file  -->
<link rel="stylesheet" href="{!! backendAssets('dist/assets/plugin/parsleyjs/css/parsley.css') !!}">
@endpush

@push('custom_styles')
@endpush

@push('scripts')
<!-- Plugin Js-->
<script src="{!! backendAssets('dist/assets/plugin/parsleyjs/js/parsley.js') !!}"></script>
@endpush

@push('custom_scripts')
<script>
	$(function() {
		// initialize after multiselect
		$('#basic-form').parsley();
	});
</script>
@endpush

@push('modals')
@endpush

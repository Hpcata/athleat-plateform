@extends(backendView('layouts.app'))

@section('title', 'View Consultation')

@section('content')
<div class="container-xxl">
    <div class="row align-items-center">
        <div class="border-0 mb-4">
            <div class="card-header pb-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom">
                <h3 class="fw-bold mb-0">View Consultation</h3>
                <div>
                    <a href="{{ route('backend.consultations.edit', $consultation->id) }}" class="btn btn-primary btn-set-task me-2">Edit</a>
                    <a type="button" href="{{ route('backend.consultations.index') }}" class="btn btn-secondary btn-set-task">Back</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row align-item-center">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <h5 class="card-title">Consultation Details</h5>
                        </div>
                        
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Content:</label>
                            <div class="p-3 bg-light rounded">
                                {{ $consultation->content }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Price:</label>
                            <div class="p-2">
                                ${{ number_format($consultation->price, 2) }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Time:</label>
                            <div class="p-2">
                                {{ $consultation->time }} minutes
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Created At:</label>
                            <div class="p-2">
                                {{ $consultation->created_at->format('M d, Y H:i A') }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Updated At:</label>
                            <div class="p-2">
                                {{ $consultation->updated_at->format('M d, Y H:i A') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

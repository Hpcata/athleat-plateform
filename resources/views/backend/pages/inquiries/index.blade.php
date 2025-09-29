@extends(backendView('layouts.app'))

@section('title', 'Inquiries List')

@section('content')
	<div class="container-xxl">
		<!-- Flash Messages -->
		@if (session('success'))
			<div class="alert alert-success alert-dismissible fade show" role="alert">
				{{ session('success') }}
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
		@elseif (session('error'))
			<div class="alert alert-danger alert-dismissible fade show" role="alert">
				{{ session('error') }}
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
		@endif
		<div class="row align-items-center">
			<div class="border-0 mb-4">
				<div class="card-header pb-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom">
					<h3 class="fw-bold mb-0">Inquiries List</h3>
				</div>
			</div>
		</div> <!-- Row end  -->
		<div class="row g-3 mb-3">
			<div class="col-md-12">
				<div class="card">
					<div class="card-body">
						<table id="myDataTable" class="table table-hover align-middle mb-0" style="width: 100%;">
							<thead>
								<tr>
									<th>Id</th>
									<th>Name</th>
									<th>Email</th>
									<th>Mobile Number</th>
									<th>Message</th>
									<th>Created Date</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($inquiries as $inquiry)
								<tr>
									<td><strong>{{ $inquiry->id }}</strong></td>
									<td>{{ $inquiry->name ?? 'N/A' }}</td>
									<td>{{ $inquiry->email ?? 'N/A' }}</td>
									<td>{{ $inquiry->mobile_number ?? 'N/A' }}</td>
									<td>{{ $inquiry->message ?? 'N/A' }}</td>
									<td>{{ $inquiry->created_at->setTimezone(aestTimezone())->format('d-m-Y h:i A') }}</td>
									<td>
										<div class="btn-group" role="group" aria-label="Basic outlined example">
											<form action="{{ route('backend.inquiries.destroy', $inquiry->id) }}" method="POST" style="display:inline;">
												@csrf
												@method('DELETE')
												<button type="submit" class="btn btn-outline-secondary" onclick="return confirm('Are you sure you want to delete this inquiry?')">
													<i class="icofont-ui-delete text-danger"></i>
												</button>
											</form>
										</div>
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

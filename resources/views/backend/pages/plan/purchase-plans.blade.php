@extends('backend.layouts.app')

@section('title', 'Purchase Plans List')

@section('content')
    <style>
        hr {
            margin-top: 0px !important;
            margin-bottom: 15px !important;
            border-top: 1px solid black !important;
        }

        /* Mobile responsive table styles */
        @media (max-width: 768px) {
            .table-responsive-mobile {
                display: none;
            }

            .mobile-card-view {
                display: block;
            }

            .mobile-card {
                border: 1px solid #dee2e6;
                border-radius: 8px;
                margin-bottom: 15px;
                padding: 15px;
                background: #fff;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }

            .mobile-card-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 10px;
                padding-bottom: 10px;
                border-bottom: 1px solid #eee;
            }

            .mobile-card-title {
                font-weight: 600;
                color: #333;
                margin: 0;
            }

            .mobile-card-id {
                background: #f8f9fa;
                padding: 4px 8px;
                border-radius: 4px;
                font-size: 12px;
                color: #6c757d;
            }

            .mobile-card-body {
                margin-bottom: 10px;
            }

            .mobile-card-row {
                display: flex;
                justify-content: space-between;
                margin-bottom: 8px;
                font-size: 14px;
            }

            .mobile-card-label {
                font-weight: 500;
                color: #666;
                min-width: 80px;
            }

            .mobile-card-value {
                color: #333;
                text-align: right;
                flex: 1;
                margin-left: 10px;
            }

            .mobile-card-actions {
                display: flex;
                gap: 5px;
                justify-content: flex-end;
                margin-top: 10px;
                padding-top: 10px;
                border-top: 1px solid #eee;
            }

            .mobile-card-actions .btn {
                padding: 4px 8px;
                font-size: 12px;
            }
        }

        @media (min-width: 769px) {
            .mobile-card-view {
                display: none;
            }

            .table-responsive-mobile {
                display: block;
            }
        }
    </style>

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
                    <h3 class="fw-bold mb-0">Purchase Plans List</h3>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Search Filter - Mobile Only -->
                        <div class="row mb-3 d-md-none">
                            <div class="col-12">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="input-group flex-grow-1">
                                        <span class="input-group-text"><i class="icofont-search-1"></i></span>
                                        <input type="text" id="searchInput" class="form-control" placeholder="Search by name, email, plan, or phone...">
                                    </div>
                                    <button type="button" id="clearSearch" class="btn btn-outline-secondary">
                                        <i class="icofont-close-circled me-1"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Desktop Table View -->
                        <div class="table-responsive-mobile">
                            <table id="purchasePlansTable" class="table table-hover align-middle mb-0" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Plan</th>
                                        <th>Email</th>
                                        <th>Price</th>
                                        <th>Phone</th>
                                        <th style="white-space: nowrap;">Purchase Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payments as $payment)
                                        @php
                                            $isPlanCreated = false;
                                            if (array_key_exists($payment->user_id, $useWisePlanData) && in_array($payment->plan_id, $useWisePlanData[$payment->user_id])) {
                                                $isPlanCreated = true;
                                            }
                                        @endphp
                                        <tr>
                                            <td>{{ $payment->id }}</td>
                                            <td>{{ $payment->name }}</td>
                                            <td>{{ $payment->plan->name ?? 'N/A' }}</td>
                                            <td>{{ $payment->email }}</td>
                                            <td>{{ $payment->price }}</td>
                                            <td>{{ $payment->phone }}</td>
                                            <td>{{ formatDate($payment->created_at) }}</td>
                                            <td>
                                                <!-- Action link to show payment details -->
                                                <a href="javascript:void(0);"
                                                    class="btn btn-sm btn-outline-primary user-pre-plan-details m-1"
                                                    data-payment-id="{{ $payment->id }}"><i
                                                        class="icofont-eye text-primary"></i></a>
                                                <a href="javascript:void(0);"
                                                    class="btn btn-sm btn-outline-info payment-info-btn m-1"
                                                    data-payment-id="{{ $payment->id }}" title="Payment Information"><i
                                                        class="icofont-info-circle text-info"></i></a>
                                                @if($isPlanCreated)
                                                    <a href="{{ route('admin.purchase-plans.edit', ['user' => $payment->user_id, 'plan' => $payment->id]) }}"
                                                        class="btn btn-sm btn-outline-success m-1"><i
                                                            class="icofont-edit text-success"></i></a>
                                                @else
                                                    <a href="{{ route('admin.purchase-plans.create', $payment->id) }}"
                                                        class="btn btn-sm btn-outline-success m-1"><i
                                                            class="icofont-plus text-success"></i></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Mobile Card View -->
                        <div class="mobile-card-view">
                            @foreach($payments as $payment)
                                @php
                                    $isPlanCreated = false;
                                    if (array_key_exists($payment->user_id, $useWisePlanData) && in_array($payment->plan_id, $useWisePlanData[$payment->user_id])) {
                                        $isPlanCreated = true;
                                    }
                                @endphp
                                <div class="mobile-card">
                                    <div class="mobile-card-header">
                                        <h6 class="mobile-card-title">{{ $payment->name }}</h6>
                                        <span class="mobile-card-id">ID: {{ $payment->id }}</span>
                                    </div>
                                    <div class="mobile-card-body">
                                        <div class="mobile-card-row">
                                            <span class="mobile-card-label">Plan:</span>
                                            <span class="mobile-card-value">{{ $payment->plan->name ?? 'N/A' }}</span>
                                        </div>
                                        <div class="mobile-card-row">
                                            <span class="mobile-card-label">Email:</span>
                                            <span class="mobile-card-value">{{ $payment->email }}</span>
                                        </div>
                                        <div class="mobile-card-row">
                                            <span class="mobile-card-label">Price:</span>
                                            <span class="mobile-card-value">${{ $payment->price }}</span>
                                        </div>
                                        <div class="mobile-card-row">
                                            <span class="mobile-card-label">Phone:</span>
                                            <span class="mobile-card-value">{{ $payment->phone }}</span>
                                        </div>
                                        <div class="mobile-card-row">
                                            <span class="mobile-card-label">Date:</span>
                                            <span class="mobile-card-value">{{ formatDate($payment->created_at) }}</span>
                                        </div>
                                    </div>
                                    <div class="mobile-card-actions">
                                        <a href="javascript:void(0);"
                                            class="btn btn-sm btn-outline-primary user-pre-plan-details"
                                            data-payment-id="{{ $payment->id }}" title="View Details">
                                            <i class="icofont-eye"></i>
                                        </a>
                                        <a href="javascript:void(0);"
                                            class="btn btn-sm btn-outline-info payment-info-btn"
                                            data-payment-id="{{ $payment->id }}" title="Payment Info">
                                            <i class="icofont-info-circle"></i>
                                        </a>
                                        @if($isPlanCreated)
                                            <a href="{{ route('admin.purchase-plans.edit', ['user' => $payment->user_id, 'plan' => $payment->id]) }}"
                                                class="btn btn-sm btn-outline-success" title="Edit Plan">
                                                <i class="icofont-edit"></i>
                                            </a>
                                        @else
                                            <a href="{{ route('admin.purchase-plans.create', $payment->id) }}"
                                                class="btn btn-sm btn-outline-success" title="Create Plan">
                                                <i class="icofont-plus"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="prePlanDetail" class="modal " tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Questionnaire</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Dynamic content will be injected here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Information Modal -->
    <div id="paymentInfoModal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Payment Information</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Dynamic payment content will be injected here -->
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{!! backendAssets('dist/assets/bundles/dataTables.bundle.js') !!}"></script>

    <script>
        $(document).ready(function () {
            // Initialize DataTable
            let dataTable = $('#purchasePlansTable').DataTable({
                responsive: true,
                pageLength: 25,
                order: [[0, 'desc']], // Sort by ID descending
                columnDefs: [
                    {
                        "targets": -1, // targets the last column (Action)
                        "orderable": false
                    }
                ],
                language: {
                    search: "",
                    searchPlaceholder: "Search table...",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                }
            });

            // Desktop search functionality (DataTable built-in search)
            // Note: Desktop uses DataTable's built-in search, mobile uses custom search below

            // Mobile search functionality
            function filterMobileCards(searchTerm) {
                const cards = $('.mobile-card');
                const term = searchTerm.toLowerCase();

                cards.each(function() {
                    const card = $(this);
                    const name = card.find('.mobile-card-title').text().toLowerCase();
                    const plan = card.find('.mobile-card-value').eq(0).text().toLowerCase(); // Plan is first value
                    const email = card.find('.mobile-card-value').eq(1).text().toLowerCase(); // Email is second value
                    const phone = card.find('.mobile-card-value').eq(3).text().toLowerCase(); // Phone is fourth value

                    if (name.includes(term) || plan.includes(term) || email.includes(term) || phone.includes(term)) {
                        card.show();
                    } else {
                        card.hide();
                    }
                });
            }

            // Mobile search input handler
            $('#searchInput').on('keyup', function() {
                const searchTerm = $(this).val();
                filterMobileCards(searchTerm);
            });

            // Mobile clear search handler
            $('#clearSearch').on('click', function() {
                $('.mobile-card').show();
            });

            $(document).on('click', '.user-pre-plan-details', function () {
                const paymentId = $(this).data('payment-id');
                $.ajax({
                    url: '{{ route('admin.pre-plan-details', ':id') }}'.replace(':id', paymentId),
                    method: 'GET',

                    success: function (response) {
                        if (response.success) {
                            let modalContent = '';

                            // Add User Details at the top
                            if (response.userDetails) {
                                const userDetails = response.userDetails;
                                modalContent += `
                                    <div>
                                        <h4 style="color:#7258db;">User Details</h4><hr>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Name:</strong> ${userDetails.name || 'N/A'}</p>
                                                <p><strong>Email:</strong> ${userDetails.email || 'N/A'}</p>
                                                <p><strong>Phone:</strong> ${userDetails.phone || 'N/A'}</p>
                                                <p><strong>DOB:</strong> ${userDetails.dob || 'N/A'}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Postcode:</strong> ${userDetails.address || 'N/A'}</p>
                                                <p><strong>Referred By:</strong> ${userDetails.referredBy || 'N/A'}</p>
                                                <p><strong>Sport:</strong> ${userDetails.occupation || 'N/A'}</p>
                                            </div>
                                        </div>
                                    </div><hr>`;
                            }

                            const formData = response.data;

                            const foodGroups = response.foodGroups || {};

                            Object.keys(formData).forEach(function (formName) {
                                if (formName === 'Personal Details') return;

                                modalContent += `<div><h4 style="color:#7258db;">${formName}</h4><hr>`;
                                const formQuestions = formData[formName];

                                Object.keys(formQuestions).forEach(function (question) {
                                    let answer = formQuestions[question];
                                    let answerContent = '';

                                    // === FOOD PREFERENCE HANDLING ===
                                    if (formName === 'Food Preference') {
                                        const expectedGroups = foodGroups;
                                        const groupNameRaw = question;

                                        const clean = s => (s || '').toString().trim();
                                        const normal = s => clean(s).replace(/\s{2,}/g, ' ');

                                        const groupKey = normal(groupNameRaw);
                                        const expectedSubs = expectedGroups[groupKey] || [];
                                        const userValue = answer;

                                        answerContent = '<ul>';

                                        if (userValue === null || userValue === undefined) {
                                            answerContent += `<li class="text-danger">Not selected</li>`;
                                        } else {
                                            if (Array.isArray(userValue)) {
                                                const nonEmpty = userValue.filter(x => clean(x));
                                                if (nonEmpty.length) {
                                                    nonEmpty.forEach(item => {
                                                        answerContent += `<li>${item}</li>`;
                                                    });
                                                } else {
                                                    answerContent += `<li class="text-danger">Not selected</li>`;
                                                }
                                            } else if (typeof userValue === 'object') {
                                                expectedSubs.forEach(sub => {
                                                    const subKey = normal(sub);
                                                    const val = userValue[subKey];
                                                    if (Array.isArray(val)) {
                                                        const cleanItems = val.filter(x => x && x !== 'null' && x !== null);
                                                        if (cleanItems.length) {
                                                            answerContent += `<li><strong>${subKey}</strong><ul>${cleanItems.map(v => `<li>${v}</li>`).join('')}</ul></li>`;
                                                        }
                                                    } else if (typeof val === 'string' && clean(val) !== '' && val !== 'null') {
                                                        answerContent += `<li><strong>${subKey}:</strong> ${val}</li>`;
                                                    } else {
                                                        answerContent += `<li class="text-danger">${subKey} — Not selected</li>`;
                                                    }
                                                });
                                            } else {
                                                answerContent += `<li>${clean(userValue)}</li>`;
                                            }
                                        }

                                        answerContent += '</ul>';

                                        modalContent += `
                                            <div>
                                                <p><strong>Q : ${groupNameRaw}</strong></p>
                                                <div>${answerContent}</div>
                                            </div>`;
                                        return; // skip to next question
                                    }

                                    // === DEFAULT LOGIC FOR OTHER FORMS ===
                                    if (!answer) {
                                        answerContent = '<span class="text-danger">Not selected</span>';
                                    } else if (Array.isArray(answer)) {
                                        const filtered = answer.filter(item => item);
                                        if (filtered.length) {
                                            answerContent = '<ul>' + filtered.map(i => `<li>${i}</li>`).join('') + '</ul>';
                                        }
                                    } else if (typeof answer === 'object') {
                                        if ('answer' in answer && 'date' in answer) {
                                            answerContent = `
                                                <ul>
                                                    <li><strong>Answer:</strong> ${answer.answer}</li>
                                                    <li><strong>Date:</strong> ${answer.date}</li>
                                                </ul>`;
                                        } else {
                                            let valid = Object.entries(answer).filter(([_, v]) => v);
                                            if (question.includes('hunger/appetite over the day')) {
                                                const order = ['breakfast', 'morning_tea', 'lunch', 'afternoon_tea', 'dinner', 'dessert'];
                                                valid.sort((a, b) => order.indexOf(a[0]) - order.indexOf(b[0]));
                                            }
                                            if (valid.length) {
                                                answerContent = '<ul>';
                                                valid.forEach(([k, v]) => {
                                                    const keyLabel = k.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                                                    if (Array.isArray(v)) {
                                                        const sub = v.filter(x => x);
                                                        if (sub.length) {
                                                            answerContent += `<li><strong>${keyLabel}</strong><ul>${sub.map(s => `<li>${s}</li>`).join('')}</ul></li>`;
                                                        }
                                                    } else {
                                                        answerContent += `<li><strong>${keyLabel}:</strong> ${v}</li>`;
                                                    }
                                                });
                                                answerContent += '</ul>';
                                            }
                                        }
                                    } else {
                                        answerContent = answer;
                                    }

                                    // Append question + answer block
                                    if (answerContent && question) {
                                        modalContent += `
                                            <div>
                                                <p><strong>Q : ${question}</strong></p>
                                                <p>${answerContent}</p>
                                            </div>`;
                                    }
                                });

                                modalContent += '</div><hr>';
                            });

                            // Set the content inside the modal
                            $('#prePlanDetail .modal-body').html(modalContent);

                            // Show the modal
                            $('#prePlanDetail').modal('show');
                        } else {
                            if (!response.data) {
                                alert('Pre plan details not available.');
                            } else {
                                alert('Failed to load the data');
                            }
                        }
                    },
                    error: function () {
                        alert('An error occurred while fetching the data.');
                    }
                });
            });

            // Handle the "Send" button click (Send meal plan)
            $('button[name="action"][value="send"]').on('click', function (e) {
                e.preventDefault();

                var $button = $(this);
                var user_id = $button.data('user-id');
                var payment_id = $button.data('payment-id');
                const loader = $('#loader-2');
                loader.show();
                $.ajax({
                    url: '{{ route("admin.handle-plan-action") }}',
                    method: 'POST',
                    data: {
                        action: 'send',
                        user_id: user_id,
                        payment_id: payment_id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            alert(response.message);

                            $button.css('background-color', '').removeClass('btn-secondary btn-danger').addClass('btn-success');

                            const now = new Date();
                            const formattedDate = now.toLocaleString('en-GB', {
                                day: '2-digit',
                                month: '2-digit',
                                year: 'numeric',
                                hour: 'numeric',
                                minute: '2-digit',
                                hour12: true,
                            }).replace(',', '');

                            const timestampId = 'timestamp-' + user_id + '-' + payment_id;

                            if ($('#' + timestampId).length) {
                                $('#' + timestampId).text(formattedDate);
                            } else {
                                $('<div>')
                                    .attr('id', timestampId)
                                    .addClass('mt-2 text-muted')
                                    .css('margin-left', '330px')
                                    .text(formattedDate)
                                    .insertAfter($button);
                            }
                            loader.hide();
                        } else {
                            alert('Error: ' + response.message);
                            loader.hide();
                        }
                    },
                    error: function (xhr) {
                        alert('Something went wrong!');
                        loader.hide();
                    }
                });
            });


            $(document).on('click', '.view-info', function () {
                var description = $(this).data('description') || 'N/A';
                $('#modalDescription').text(description);
                $('#itemInfoModal').modal('show');
            });

            // Handle payment information button click
            $(document).on('click', '.payment-info-btn', function () {
                const paymentId = $(this).data('payment-id');

                $.ajax({
                    url: '{{ route('admin.purchase-plans.payment-info', ':id') }}'.replace(':id', paymentId),
                    method: 'GET',
                    success: function (response) {
                        if (response.success) {
                            let modalContent = '';
                            const data = response.data;
                            const payment = data.payment;
                            const isRecurring = data.is_recurring;
                            const recurringInfo = data.recurring_info;
                            const paymentGroup = data.payment_group;
                            const firstPayment = data.first_payment;

                            // Payment Basic Information
                            modalContent += `
                                <div>
                                    <h4 style="color:#7258db;">Payment Details</h4><hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Payment ID:</strong> ${payment.id}</p>
                                            <p><strong>Plan:</strong> ${payment.plan ? payment.plan.name : 'N/A'}</p>
                                            <p><strong>Customer Name:</strong> ${payment.name || 'N/A'}</p>
                                            <p><strong>Email:</strong> ${payment.email || 'N/A'}</p>
                                            <p><strong>Phone:</strong> ${payment.phone || 'N/A'}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Amount:</strong> $${payment.price || 'N/A'}</p>
                                            <p><strong>Original Price:</strong> $${payment.original_price || 'N/A'}</p>
                                            <p><strong>Status:</strong> <span class="badge ${payment.status === 'completed' ? 'bg-success' : payment.status === 'pending' ? 'bg-warning' : 'bg-danger'}">${payment.status || 'N/A'}</span></p>
                                            <p><strong>Payment Intent ID:</strong> ${firstPayment ? firstPayment.payment_intent_id || 'N/A' : payment.payment_intent_id || 'N/A'}</p>
                                            <p><strong>Coupon Code:</strong> ${firstPayment ? firstPayment.coupon_code || 'N/A' : payment.coupon_code || 'N/A'}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p><strong>Purchase Date:</strong> ${new Date(payment.created_at).toLocaleString()}</p>
                                        </div>
                                    </div>
                                </div><hr>`;

                            // Recurring Payment Information
                            if (isRecurring && recurringInfo) {
                                const firstPaymentDate = new Date(payment.created_at);
                                const eightMonthsLater = new Date(firstPaymentDate);
                                eightMonthsLater.setMonth(eightMonthsLater.getMonth() + 8);
                                const currentDate = new Date();

                                const isWithinEightMonths = currentDate < eightMonthsLater;
                                const isActiveSubscription = isWithinEightMonths;

                                const subscriptionStatus = isActiveSubscription ? 'Active Subscription' : 'Canceled Subscription';
                                const statusClass = isActiveSubscription ? 'bg-success' : 'bg-danger';

                                modalContent += `
                                    <div>
                                        <h4 style="color:#7258db;">Recurring Payment Information</h4><hr>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Subscription Status:</strong> <span class="badge ${statusClass}">${subscriptionStatus}</span></p>
                                                <p><strong>Stripe Subscription ID:</strong> ${recurringInfo.stripe_subscription_id || 'N/A'}</p>
                                                <p><strong>Total Payments Made:</strong> ${recurringInfo.total_payments}</p>
                                                <p><strong>Total Payments Expected:</strong> ${recurringInfo.total_payments_expected}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Remaining Payments:</strong> <span class="badge ${recurringInfo.remaining_payments > 0 ? 'bg-info' : 'bg-success'}">${recurringInfo.remaining_payments}</span></p>
                                                <p><strong>First Payment Date:</strong> ${firstPaymentDate.toLocaleDateString()}</p>
                                                <p><strong>8-Month Period Ends:</strong> ${eightMonthsLater.toLocaleDateString()}</p>
                                                <p><strong>Next Payment Date:</strong> ${recurringInfo.calculated_next_payment_date ? new Date(recurringInfo.calculated_next_payment_date).toLocaleDateString() : 'N/A (All payments completed)'}</p>
                                            </div>
                                        </div>`;

                                if (isActiveSubscription) {
                                    modalContent += `
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <div class="alert alert-success">
                                                    <p><strong>Active Subscription</strong></p>
                                                    <p>This subscription is active with ${recurringInfo.remaining_payments} payment(s) remaining and is within the 8-month period.</p>
                                                </div>
                                            </div>
                                        </div>`;
                                } else {
                                    modalContent += `
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <div class="alert alert-danger">
                                                    <p><strong>Canceled Subscription</strong></p>
                                                    <p>This subscription has expired after the 8-month period ended on ${eightMonthsLater.toLocaleDateString()}.</p>
                                                </div>
                                            </div>
                                        </div>`;
                                }

                                modalContent += '</div><hr>';
                            } else {
                                modalContent += `
                                    <div>
                                        <h4 style="color:#7258db;">Payment Type</h4><hr>
                                        <div class="alert alert-info">
                                            <p><strong>One-time Payment</strong></p>
                                            <p>This is a single payment transaction, not a recurring subscription.</p>
                                        </div>
                                    </div><hr>`;
                            }

                            $('#paymentInfoModal .modal-body').html(modalContent);

                            $('#paymentInfoModal').modal('show');
                        } else {
                            alert('Failed to load payment information.');
                        }
                    },
                    error: function () {
                        alert('An error occurred while fetching payment information.');
                    }
                });
            });
        });
    </script>
@endsection
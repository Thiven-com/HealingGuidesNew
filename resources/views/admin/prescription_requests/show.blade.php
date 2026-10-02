@extends('layout.mainlayout')

@section('content')

<div class="page-wrapper">
    <div class="content">

        <div class="page-header d-flex justify-content-between align-items-center">

            <div class="page-title">
                <h4>Prescription Request #{{ $prescriptionRequest->id }}</h4>
                <h6>View prescription and quotations</h6>
            </div>

            <div>

                <a href="{{ route('admin.prescription-requests.index') }}"
                   class="btn btn-secondary">
                    Back
                </a>

                @if($prescriptionRequest->status === 'pending')

                    <form method="POST"
                          action="{{ route(
                              'admin.prescription-requests.review',
                              $prescriptionRequest->id
                          ) }}"
                          class="d-inline">

                        @csrf

                        <button type="submit"
                                class="btn btn-info">
                            Mark Reviewing
                        </button>

                    </form>

                @endif

            </div>

        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif


        {{-- REQUEST DETAILS --}}

        <div class="row">

            <div class="col-md-6">

                <div class="card">

                    <div class="card-header">
                        <h5>Customer Details</h5>
                    </div>

                    <div class="card-body">

                        <p>
                            <strong>Name:</strong>
                            {{ optional($prescriptionRequest->customer)->name ?? '-' }}
                        </p>

                        <p>
                            <strong>Email:</strong>
                            {{ optional($prescriptionRequest->customer)->email ?? '-' }}
                        </p>

                        <p>
                            <strong>Mobile:</strong>
                            {{ optional($prescriptionRequest->customer)->mobile ?? '-' }}
                        </p>

                        <p>
                            <strong>Family Member:</strong>
                            {{ optional($prescriptionRequest->familyMember)->name ?? '-' }}
                        </p>

                    </div>

                </div>

            </div>


            <div class="col-md-6">

                <div class="card">

                    <div class="card-header">
                        <h5>Request Details</h5>
                    </div>

                    <div class="card-body">

                        <p>
                            <strong>Request Type:</strong>

                            {{ ucfirst(
                                str_replace(
                                    '_',
                                    ' ',
                                    $prescriptionRequest->request_type
                                )
                            ) }}

                        </p>

                        <p>
                            <strong>Status:</strong>

                            <span class="badge bg-primary">
                                {{ ucfirst(
                                    str_replace(
                                        '_',
                                        ' ',
                                        $prescriptionRequest->status
                                    )
                                ) }}
                            </span>

                        </p>

                        <p>
                            <strong>Address:</strong>
                            {{ $prescriptionRequest->address ?? '-' }}
                        </p>

                        <p>
                            <strong>City:</strong>
                            {{ $prescriptionRequest->city ?? '-' }}
                        </p>

                        <p>
                            <strong>State:</strong>
                            {{ $prescriptionRequest->state ?? '-' }}
                        </p>

                        <p>
                            <strong>Pincode:</strong>
                            {{ $prescriptionRequest->pincode ?? '-' }}
                        </p>

                        <p>
                            <strong>Notes:</strong>
                            {{ $prescriptionRequest->notes ?? '-' }}
                        </p>

                    </div>

                </div>

            </div>

        </div>


        {{-- PRESCRIPTION --}}

        <div class="card">

            <div class="card-header d-flex justify-content-between">

                <h5>Prescription</h5>

                @if($prescriptionRequest->prescription_image)

                    <a href="{{ asset($prescriptionRequest->prescription_image) }}"
                       target="_blank"
                       class="btn btn-sm btn-primary">

                        View Prescription

                    </a>

                @endif

            </div>

            <div class="card-body">

                @if($prescriptionRequest->prescription_image)

                    <div class="text-center">

                        <img src="{{ asset($prescriptionRequest->prescription_image) }}"
                             class="img-fluid"
                             style="max-height:500px;">

                    </div>

                @elseif($prescriptionRequest->prescription)

                    <p>
                        Prescription ID:
                        {{ $prescriptionRequest->prescription->id }}
                    </p>

                @else

                    <p class="text-muted">
                        No prescription uploaded.
                    </p>

                @endif

            </div>

        </div>


        {{-- CREATE QUOTATION --}}

        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">

                <h5>Quotations</h5>

                @if(!in_array($prescriptionRequest->status, [
                    'completed',
                    'cancelled',
                    'rejected'
                ]))

                    <a href="{{ route(
                        'admin.prescription-requests.quotation.create',
                        $prescriptionRequest->id
                    ) }}"
                       class="btn btn-success">

                        <i class="fa fa-plus"></i>
                        Create Quotation

                    </a>

                @endif

            </div>

            <div class="card-body">

                @forelse($prescriptionRequest->quotations as $quotation)

                    <div class="border rounded p-3 mb-3">

                        <div class="row">

                            <div class="col-md-3">

                                <strong>Quotation No</strong>

                                <div>
                                    {{ $quotation->quotation_no }}
                                </div>

                            </div>

                            <div class="col-md-2">

                                <strong>Type</strong>

                                <div>
                                    {{ ucfirst($quotation->quotation_type) }}
                                </div>

                            </div>

                            <div class="col-md-2">

                                <strong>Total</strong>

                                <div>
                                    ₹{{ number_format($quotation->total_amount, 2) }}
                                </div>

                            </div>

                            <div class="col-md-2">

                                <strong>Status</strong>

                                <div>
                                    <span class="badge bg-info">
                                        {{ ucfirst(str_replace('_', ' ', $quotation->status)) }}
                                    </span>
                                </div>

                            </div>

                            <div class="col-md-3 text-end">

                                <a href="{{ route(
                                    'admin.prescription-quotations.show',
                                    $quotation->id
                                ) }}"
                                   class="btn btn-sm btn-primary">
                                    View
                                </a>

                                <a href="{{ route(
                                    'admin.prescription-quotations.edit',
                                    $quotation->id
                                ) }}"
                                   class="btn btn-sm btn-warning">
                                    Edit
                                </a>

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="text-center text-muted py-4">
                        No quotations created yet.
                    </div>

                @endforelse

            </div>

        </div>


        {{-- REJECT --}}

        @if(!in_array($prescriptionRequest->status, [
            'completed',
            'cancelled',
            'rejected'
        ]))

            <div class="card">

                <div class="card-header">
                    <h5>Reject Request</h5>
                </div>

                <div class="card-body">

                    <form method="POST"
                          action="{{ route(
                              'admin.prescription-requests.reject',
                              $prescriptionRequest->id
                          ) }}">

                        @csrf

                        <div class="mb-3">

                            <label>Admin Notes</label>

                            <textarea name="admin_notes"
                                      class="form-control"
                                      rows="3"></textarea>

                        </div>

                        <button type="submit"
                                class="btn btn-danger"
                                onclick="return confirm('Reject this request?')">

                            Reject Request

                        </button>

                    </form>

                </div>

            </div>

        @endif

    </div>
</div>

@endsection
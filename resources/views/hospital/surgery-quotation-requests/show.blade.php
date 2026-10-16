@extends('layout.mainlayout')

@section('content')
    <div class="page-wrapper">
        <div class="content">

            <div class="page-header">
                <div class="page-title">
                    <h4>Submit Surgery Quotation</h4>
                    <h6>Provide your hospital's quotation for this request</h6>
                </div>

                <a href="{{ route('hospital.surgery-quotation-requests.index') }}" class="btn btn-secondary">
                    <i class="ti ti-arrow-left me-1"></i> Back
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Surgery Request Details</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="text-muted">Request No</label>
                            <h6>
                                {{ $quotation->quotationRequest->request_no
        ?? $quotation->surgery_quotation_request_id }}
                            </h6>
                        </div>

                        <div class="col-md-4">
                            <label class="text-muted">Surgery</label>
                            <h6>
                                {{ $quotation->quotationRequest->surgery->name
        ?? $quotation->quotationRequest->surgery->surgery_name
        ?? '-' }}
                            </h6>
                        </div>

                        <div class="col-md-4">
                            <label class="text-muted">Request Date</label>
                            <h6>
                                {{ optional($quotation->created_at)->format('d M Y, h:i A') ?? '-' }}
                            </h6>
                        </div>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route(
        'hospital.surgery-quotation-requests.update',
        $quotation->id
    ) }}">
                @csrf
                @method('PUT')

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Hospital Quotation Details</h5>
                    </div>

                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-4 mb-3">
                                <label class="form-label">
                                    Amount <span class="text-danger">*</span>
                                </label>
                                <input type="number" name="amount" id="amount" class="form-control" min="0" step="0.01"
                                    value="{{ old('amount', $quotation->amount) }}" required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Discount</label>
                                <input type="number" name="discount" id="discount" class="form-control" min="0" step="0.01"
                                    value="{{ old('discount', $quotation->discount ?? 0) }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Tax</label>
                                <input type="number" name="tax" id="tax" class="form-control" min="0" step="0.01"
                                    value="{{ old('tax', $quotation->tax ?? 0) }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Total Amount</label>
                                <input type="number" id="total_amount" class="form-control"
                                    value="{{ old('total_amount', $quotation->total_amount) }}" readonly>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Valid Until</label>
                                <input type="date" name="valid_until" class="form-control"
                                    value="{{ old('valid_until', $quotation->valid_until) }}">
                            </div>
                            {{-- Status --}}
                            

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Quotation Details</label>
                                <textarea name="quotation_details" class="form-control"
                                    rows="4">{{ old('quotation_details', $quotation->quotation_details) }}</textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Included Services</label>
                                <textarea name="included_services" class="form-control"
                                    rows="4">{{ old('included_services', $quotation->included_services) }}</textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Excluded Services</label>
                                <textarea name="excluded_services" class="form-control"
                                    rows="4">{{ old('excluded_services', $quotation->excluded_services) }}</textarea>
                            </div>

                        </div>
                    </div>

                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-send me-1"></i>
                            Submit Quotation
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const amount = document.getElementById('amount');
            const discount = document.getElementById('discount');
            const tax = document.getElementById('tax');
            const total = document.getElementById('total_amount');

            function calculateTotal() {
                const a = parseFloat(amount.value) || 0;
                const d = parseFloat(discount.value) || 0;
                const t = parseFloat(tax.value) || 0;

                total.value = Math.max(0, a - d + t).toFixed(2);
            }

            [amount, discount, tax].forEach(function (input) {
                input.addEventListener('input', calculateTotal);
            });

            calculateTotal();
        });
    </script>
@endsection
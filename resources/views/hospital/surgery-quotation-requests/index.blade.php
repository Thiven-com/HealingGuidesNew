@extends('layout.mainlayout')

@section('content')
    <div class="page-wrapper">
        <div class="content">

            <div class="page-header">
                <div class="page-title">
                    <h4>Surgery Quotation Requests</h4>
                    <h6>Requests assigned to your hospital</h6>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Request No</th>
                                    <th>Surgery</th>
                                    <th>Amount</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Received On</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($quotationRequests as $quotation)
                                                            <tr>
                                                                <td>{{ $quotationRequests->firstItem() + $loop->index }}</td>
                                                                <td>
                                                                    {{ $quotation->quotationRequest->request_no
                                        ?? $quotation->surgery_quotation_request_id }}
                                                                </td>
                                                                <td>
                                                                    {{ $quotation->quotationRequest->surgery->name
                                        ?? $quotation->quotationRequest->surgery->surgery_name
                                        ?? '-' }}
                                                                </td>
                                                                <td>₹ {{ number_format((float) $quotation->amount, 2) }}</td>
                                                                <td>
                                                                    <strong>
                                                                        ₹ {{ number_format((float) $quotation->total_amount, 2) }}
                                                                    </strong>
                                                                </td>
                                                                <td>
                                                                    <span class="badge bg-info">
                                                                        {{ ucfirst($quotation->status ?? 'Pending') }}
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    {{ optional($quotation->created_at)->format('d M Y') ?? '-' }}
                                                                </td>
                                                                <td>
                                                                    <a href="{{ route(
                                        'hospital.surgery-quotation-requests.show',
                                        $quotation->id
                                    ) }}" class="btn btn-sm btn-primary">
                                                                        <i class="ti ti-edit me-1"></i>
                                                                        View / Submit
                                                                    </a>
                                                                </td>
                                                            </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            No surgery quotation requests found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $quotationRequests->links() }}
                </div>
            </div>

        </div>
    </div>
@endsection
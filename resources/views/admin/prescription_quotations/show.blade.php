@extends('layout.mainlayout')

@section('content')

<div class="page-wrapper">
    <div class="content">

        <div class="page-header d-flex justify-content-between">

            <div class="page-title">

                <h4>
                    Quotation {{ $quotation->quotation_no }}
                </h4>

                <h6>
                    Prescription Request #{{ $quotation->prescription_request_id }}
                </h6>

            </div>

            <div>

                <a href="{{ route(
                    'admin.prescription-quotations.edit',
                    $quotation->id
                ) }}"
                   class="btn btn-warning">

                    Edit

                </a>

                <a href="{{ route(
                    'admin.prescription-requests.show',
                    $quotation->prescription_request_id
                ) }}"
                   class="btn btn-secondary">

                    Back

                </a>

            </div>

        </div>


        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif


        <div class="row">

            <div class="col-md-8">

                <div class="card">

                    <div class="card-header">
                        <h5>Quotation Items</h5>
                    </div>

                    <div class="card-body">

                        @if($quotation->medicineItems->count())

                            <h6 class="mb-3">
                                Medicines
                            </h6>

                            <div class="table-responsive">

                                <table class="table table-bordered">

                                    <thead>

                                        <tr>
                                            <th>Medicine</th>
                                            <th>Code</th>
                                            <th>Qty</th>
                                            <th>Price</th>
                                            <th>Total</th>
                                        </tr>

                                    </thead>

                                    <tbody>

                                    @foreach($quotation->medicineItems as $item)

                                        <tr>

                                            <td>
                                                {{ $item->medicine_name }}
                                            </td>

                                            <td>
                                                {{ $item->medicine_code }}
                                            </td>

                                            <td>
                                                {{ $item->quantity }}
                                            </td>

                                            <td>
                                                ₹{{ number_format($item->price, 2) }}
                                            </td>

                                            <td>
                                                ₹{{ number_format($item->total, 2) }}
                                            </td>

                                        </tr>

                                    @endforeach

                                    </tbody>

                                </table>

                            </div>

                        @endif


                        @if($quotation->labTestItems->count())

                            <h6 class="mt-4 mb-3">
                                Lab Tests
                            </h6>

                            <div class="table-responsive">

                                <table class="table table-bordered">

                                    <thead>

                                        <tr>
                                            <th>Test</th>
                                            <th>Code</th>
                                            <th>MRP</th>
                                            <th>Price</th>
                                            <th>Total</th>
                                        </tr>

                                    </thead>

                                    <tbody>

                                    @foreach($quotation->labTestItems as $item)

                                        <tr>

                                            <td>
                                                {{ $item->test_name }}
                                            </td>

                                            <td>
                                                {{ $item->test_code }}
                                            </td>

                                            <td>
                                                ₹{{ number_format($item->mrp, 2) }}
                                            </td>

                                            <td>
                                                ₹{{ number_format($item->price, 2) }}
                                            </td>

                                            <td>
                                                ₹{{ number_format($item->total, 2) }}
                                            </td>

                                        </tr>

                                    @endforeach

                                    </tbody>

                                </table>

                            </div>

                        @endif

                    </div>

                </div>

            </div>


            <div class="col-md-4">

                <div class="card">

                    <div class="card-header">
                        <h5>Quotation Summary</h5>
                    </div>

                    <div class="card-body">

                        <p>
                            <strong>Quotation No:</strong>
                            {{ $quotation->quotation_no }}
                        </p>

                        <p>
                            <strong>Type:</strong>
                            {{ ucfirst($quotation->quotation_type) }}
                        </p>

                        <p>
                            <strong>Status:</strong>

                            <span class="badge bg-primary">
                                {{ ucfirst(str_replace('_', ' ', $quotation->status)) }}
                            </span>

                        </p>

                        <hr>

                        <div class="d-flex justify-content-between">
                            <span>Subtotal</span>
                            <strong>
                                ₹{{ number_format($quotation->subtotal, 2) }}
                            </strong>
                        </div>

                        <div class="d-flex justify-content-between">
                            <span>Delivery</span>
                            <strong>
                                ₹{{ number_format($quotation->delivery_charge, 2) }}
                            </strong>
                        </div>

                        <div class="d-flex justify-content-between">
                            <span>Discount</span>
                            <strong>
                                ₹{{ number_format($quotation->discount, 2) }}
                            </strong>
                        </div>

                        <div class="d-flex justify-content-between">
                            <span>Tax</span>
                            <strong>
                                ₹{{ number_format($quotation->tax, 2) }}
                            </strong>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between">

                            <strong>Total</strong>

                            <strong>
                                ₹{{ number_format($quotation->total_amount, 2) }}
                            </strong>

                        </div>

                    </div>

                </div>


                @if($quotation->status === 'draft')

                    <div class="card">

                        <div class="card-body">

                            <form method="POST"
                                  action="{{ route(
                                      'admin.prescription-quotations.send',
                                      $quotation->id
                                  ) }}">

                                @csrf

                                <button type="submit"
                                        class="btn btn-success w-100">

                                    Send Quotation To Customer

                                </button>

                            </form>

                        </div>

                    </div>

                @endif


                @if(!in_array($quotation->status, [
                    'paid',
                    'completed',
                    'rejected'
                ]))

                    <div class="card">

                        <div class="card-body">

                            <form method="POST"
                                  action="{{ route(
                                      'admin.prescription-quotations.reject',
                                      $quotation->id
                                  ) }}">

                                @csrf

                                <button type="submit"
                                        class="btn btn-danger w-100"
                                        onclick="return confirm('Reject this quotation?')">

                                    Reject Quotation

                                </button>

                            </form>

                        </div>

                    </div>

                @endif

            </div>

        </div>

    </div>
</div>

@endsection
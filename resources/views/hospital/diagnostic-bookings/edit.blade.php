
@extends('layout.mainlayout')

@section('content')
<div class="page-wrapper">

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Edit Diagnostic Booking</h4>

        <a href="{{ route('hospital.diagnostic-bookings.index') }}"
           class="btn btn-secondary">
            <i class="fa fa-arrow-left"></i> Back
        </a>
    </div>

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5>Booking Information</h5>
        </div>

        <div class="card-body">

            <form method="POST"
                  action="{{ route('hospital.diagnostic-bookings.update', $booking->id) }}">

                @csrf
                @method('PUT')

                <div class="row">

                    {{-- Booking Number --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Booking Number</label>
                        <input type="text"
                               class="form-control"
                               value="{{ $booking->booking_no }}"
                               readonly>
                    </div>

                    {{-- Customer ID --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Customer ID</label>
                        <input type="text"
                               class="form-control"
                               value="{{ $booking->customer_id }}"
                               readonly>
                    </div>

                    {{-- Diagnostic ID --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Diagnostic ID</label>
                        <input type="text"
                               class="form-control"
                               value="{{ $booking->diagnostic_id }}"
                               readonly>
                    </div>

                    {{-- Collection Type --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Collection Type</label>

                        <select name="collection_type"
                                class="form-select"
                                required>

                            <option value="home_collection"
                                @selected(old('collection_type', $booking->collection_type) == 'home_collection')>
                                Home Collection
                            </option>

                            <option value="lab_visit"
                                @selected(old('collection_type', $booking->collection_type) == 'lab_visit')>
                                Lab Visit
                            </option>

                        </select>
                    </div>

                    {{-- Booking Date --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Booking Date</label>

                        <input type="date"
                               name="booking_date"
                               class="form-control"
                               value="{{ old('booking_date', $booking->booking_date) }}">
                    </div>

                    {{-- Booking Time --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Booking Time</label>

                        <input type="time"
                               name="booking_time"
                               class="form-control"
                               value="{{ old('booking_time', $booking->booking_time ? \Carbon\Carbon::parse($booking->booking_time)->format('H:i') : '') }}">
                    </div>

                    {{-- Booking Status --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Booking Status</label>

                        <select name="booking_status"
                                class="form-select"
                                required>

                            @foreach(['pending', 'confirmed', 'completed', 'cancelled'] as $status)
                                <option value="{{ $status }}"
                                    @selected(old('booking_status', $booking->booking_status) == $status)>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    {{-- Payment Status --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Payment Status</label>

                        <select name="payment_status"
                                class="form-select"
                                required>

                            @foreach(['pending', 'paid', 'failed', 'refunded'] as $status)
                                <option value="{{ $status }}"
                                    @selected(old('payment_status', $booking->payment_status) == $status)>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    {{-- Address --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Address</label>

                        <textarea name="address"
                                  class="form-control"
                                  rows="3">{{ old('address', $booking->address) }}</textarea>
                    </div>

                    {{-- Notes --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Notes</label>

                        <textarea name="notes"
                                  class="form-control"
                                  rows="3">{{ old('notes', $booking->notes) }}</textarea>
                    </div>

                    {{-- City --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">City</label>

                        <input type="text"
                               name="city"
                               class="form-control"
                               value="{{ old('city', $booking->city) }}">
                    </div>

                    {{-- State --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">State</label>

                        <input type="text"
                               name="state"
                               class="form-control"
                               value="{{ old('state', $booking->state) }}">
                    </div>

                    {{-- Pincode --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Pincode</label>

                        <input type="text"
                               name="pincode"
                               class="form-control"
                               value="{{ old('pincode', $booking->pincode) }}">
                    </div>

                    {{-- Cancel Reason --}}
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Cancel Reason</label>

                        <textarea name="cancel_reason"
                                  class="form-control"
                                  rows="3">{{ old('cancel_reason', $booking->cancel_reason) }}</textarea>
                    </div>

                </div>

                <div class="mt-3">

                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-save"></i> Update Booking
                    </button>

                    <a href="{{ route('hospital.diagnostic-bookings.index') }}"
                       class="btn btn-secondary">
                        Cancel
                    </a>

                </div>

            </form>

        </div>
    </div>

</div>
</div>

@endsection

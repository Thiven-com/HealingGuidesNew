@extends('layout.mainlayout')

@section('content')

<div class="page-wrapper">
    <div class="content">

        <div class="page-header">

            <div class="page-title">

                <h4>Edit Quotation</h4>

                <h6>
                    {{ $quotation->quotation_no }}
                </h6>

            </div>

        </div>


        @if($errors->any())

            <div class="alert alert-danger">

                <ul class="mb-0">

                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>

        @endif


        <form method="POST"
              action="{{ route(
                  'admin.prescription-quotations.update',
                  $quotation->id
              ) }}">

            @csrf
            @method('PUT')


            <div class="card">

                <div class="card-header">
                    <h5>Quotation Information</h5>
                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-4">

                            <label>Quotation Type</label>

                            <input type="text"
                                   class="form-control"
                                   value="{{ ucfirst($quotation->quotation_type) }}"
                                   readonly>

                        </div>

                        <div class="col-md-4">

                            <label>Valid Until</label>

                            <input type="date"
                                   name="valid_until"
                                   class="form-control"
                                   value="{{ optional($quotation->valid_until)->format('Y-m-d') }}">

                        </div>

                        <div class="col-md-4">

                            <label>Status</label>

                            <input type="text"
                                   class="form-control"
                                   value="{{ ucfirst(str_replace('_', ' ', $quotation->status)) }}"
                                   readonly>

                        </div>

                    </div>

                </div>

            </div>


            <div class="card">

                <div class="card-header">
                    <h5>Amounts</h5>
                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-3">

                            <label>Subtotal</label>

                            <input type="number"
                                   step="0.01"
                                   name="subtotal"
                                   class="form-control"
                                   value="{{ $quotation->subtotal }}">

                        </div>

                        <div class="col-md-3">

                            <label>Delivery Charge</label>

                            <input type="number"
                                   step="0.01"
                                   name="delivery_charge"
                                   class="form-control"
                                   value="{{ $quotation->delivery_charge }}">

                        </div>

                        <div class="col-md-3">

                            <label>Discount</label>

                            <input type="number"
                                   step="0.01"
                                   name="discount"
                                   class="form-control"
                                   value="{{ $quotation->discount }}">

                        </div>

                        <div class="col-md-3">

                            <label>Tax</label>

                            <input type="number"
                                   step="0.01"
                                   name="tax"
                                   class="form-control"
                                   value="{{ $quotation->tax }}">

                        </div>

                    </div>

                </div>

            </div>


            <div class="card">

                <div class="card-header">
                    <h5>Details</h5>
                </div>

                <div class="card-body">

                    <div class="mb-3">

                        <label>Quotation Details</label>

                        <textarea name="quotation_details"
                                  class="form-control"
                                  rows="5">{{ $quotation->quotation_details }}</textarea>

                    </div>

                    <div class="mb-3">

                        <label>Admin Notes</label>

                        <textarea name="admin_notes"
                                  class="form-control"
                                  rows="4">{{ $quotation->admin_notes }}</textarea>

                    </div>

                </div>

            </div>


            <div class="text-end">

                <a href="{{ route(
                    'admin.prescription-quotations.show',
                    $quotation->id
                ) }}"
                   class="btn btn-secondary">

                    Cancel

                </a>

                <button type="submit"
                        class="btn btn-primary">

                    Update Quotation

                </button>

            </div>

        </form>

    </div>
</div>

@endsection
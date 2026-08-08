@extends('layout.mainlayout')

@section('content')

    <div class="page-wrapper">

        <div class="content">

            <div class="page-header">

                <div class="page-title">
                    <h4>Hospital Profile</h4>
                    <h6>Manage your hospital profile</h6>
                </div>

            </div>


            @if(session('success'))

                <div class="alert alert-success alert-dismissible fade show">

                    <i class="ti ti-circle-check me-1"></i>

                    {{ session('success') }}

                    <button type="button" class="btn-close" data-bs-dismiss="alert">
                    </button>

                </div>

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


            <div class="card">

                <div class="card-header">

                    <h5 class="card-title mb-0">

                        <i class="ti ti-building-hospital me-2"></i>

                        Hospital Information

                    </h5>

                </div>


                <div class="card-body">

                    <form method="POST" action="{{ route('hospital.profile.update') }}">

                        @csrf
                        <div class="row">


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Hospital Name
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text" name="hospital_name" class="form-control" value="{{ old(
        'hospital_name',
        $hospital->hospital_name ?? ''
    ) }}" required>

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Email
                                </label>

                                <input type="email" name="email" class="form-control" value="{{ old(
        'email',
        $hospital->email ?? ''
    ) }}">

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Mobile
                                </label>

                                <input type="text" name="mobile" class="form-control" value="{{ old(
        'mobile',
        $hospital->mobile ?? ''
    ) }}">

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Pincode
                                </label>

                                <input type="text" name="pincode" class="form-control" value="{{ old(
        'pincode',
        $hospital->pincode ?? ''
    ) }}">

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    City
                                </label>

                                <input type="text" name="city" class="form-control" value="{{ old(
        'city',
        $hospital->city ?? ''
    ) }}">

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    State
                                </label>

                                <input type="text" name="state" class="form-control" value="{{ old(
        'state',
        $hospital->state ?? ''
    ) }}">

                            </div>


                            <div class="col-md-12 mb-3">

                                <label class="form-label">
                                    Address
                                </label>

                                <textarea name="address" class="form-control" rows="3">{{ old(
        'address',
        $hospital->address ?? ''
    ) }}</textarea>

                            </div>


                            <div class="col-md-12 mb-3">

                                <label class="form-label">
                                    About Hospital
                                </label>

                                <textarea name="about" class="form-control" rows="4">{{ old(
        'about',
        $hospital->about ?? ''
    ) }}</textarea>

                            </div>


                        </div>


                        <div class="text-end">

                            <button type="submit" class="btn btn-primary">

                                <i class="ti ti-device-floppy me-1"></i>

                                Save Changes

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

@endsection
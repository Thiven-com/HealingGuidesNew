@extends('layout.mainlayout')

@section('content')

<div class="page-wrapper">

    <div class="content">

        {{-- =========================================================
        HEADER
        ========================================================== --}}

        <div class="page-header">

            <div class="page-title">

                <h4>
                    Live Ambulance Tracking
                </h4>

                <h6>
                    {{ $booking->booking_no }}
                </h6>

            </div>


            <div class="page-btn">

                <a href="{{ route(
                    'hospital.ambulance-bookings.show',
                    $booking->id
                ) }}"
                   class="btn btn-light">

                    <i class="ti ti-arrow-left me-1"></i>

                    Back

                </a>

            </div>

        </div>


        {{-- =========================================================
        TRACKING CONTENT
        ========================================================== --}}

        <div class="row">


            {{-- =====================================================
            MAP
            ====================================================== --}}

            <div class="col-xl-8">

                <div class="card">

                    <div class="card-header">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <h5 class="card-title mb-0">
                                    Ambulance Location
                                </h5>

                                <small class="text-muted">
                                    Live location & route
                                </small>

                            </div>


                            <div class="d-flex align-items-center gap-2">

                                {{-- ROUTE INFO --}}

                                <span id="routeInfo"
                                      class="badge bg-light text-dark d-none">

                                    <i class="ti ti-route me-1"></i>

                                    <span id="routeDistance">
                                        --
                                    </span>

                                    <span class="mx-1">
                                        •
                                    </span>

                                    <span id="routeDuration">
                                        --
                                    </span>

                                </span>


                                {{-- LIVE STATUS --}}

                                <span id="trackingStatus"
                                      class="badge bg-success">

                                    <i class="ti ti-live-photo me-1"></i>

                                    Live

                                </span>

                            </div>

                        </div>

                    </div>


                    <div class="card-body p-0">

                        <div id="map"
                             style="
                                width:100%;
                                height:550px;
                                border-radius:0 0 8px 8px;
                             ">
                        </div>

                    </div>

                </div>


                {{-- =================================================
                MAP LEGEND
                ================================================== --}}

                <div class="card">

                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-md-4">

                                <div class="d-flex align-items-center">

                                    <span class="rounded-circle bg-danger"
                                          style="
                                            width:12px;
                                            height:12px;
                                            display:inline-block;
                                            margin-right:8px;
                                          ">
                                    </span>

                                    <span>
                                        Pickup Location
                                    </span>

                                </div>

                            </div>


                            <div class="col-md-4">

                                <div class="d-flex align-items-center">

                                    <span class="rounded-circle bg-success"
                                          style="
                                            width:12px;
                                            height:12px;
                                            display:inline-block;
                                            margin-right:8px;
                                          ">
                                    </span>

                                    <span>
                                        Destination
                                    </span>

                                </div>

                            </div>


                            <div class="col-md-4">

                                <div class="d-flex align-items-center">

                                    <span class="rounded-circle bg-primary"
                                          style="
                                            width:12px;
                                            height:12px;
                                            display:inline-block;
                                            margin-right:8px;
                                          ">
                                    </span>

                                    <span>
                                        Ambulance
                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =====================================================
            RIGHT DETAILS
            ====================================================== --}}

            <div class="col-xl-4">


                {{-- =================================================
                AMBULANCE
                ================================================== --}}

                <div class="card">

                    <div class="card-header">

                        <h5 class="card-title mb-0">
                            Ambulance
                        </h5>

                    </div>


                    <div class="card-body">

                        @if($booking->ambulance)

                            <div class="text-center">

                                <div class="
                                    avatar
                                    avatar-xl
                                    bg-light-primary
                                    rounded-circle
                                    mx-auto
                                    mb-3
                                ">

                                    <i class="ti ti-ambulance text-primary"
                                       style="font-size:35px;">
                                    </i>

                                </div>


                                <h5 class="mb-1">

                                    {{ $booking->ambulance->ambulance_name
                                        ?? 'Ambulance' }}

                                </h5>


                                <p class="text-muted mb-3">

                                    {{ $booking->ambulance->vehicle_number
                                        ?? '-' }}

                                </p>

                            </div>


                            <hr>


                            {{-- STATUS --}}

                            <div class="d-flex justify-content-between mb-3">

                                <span class="text-muted">
                                    Status
                                </span>


                                <span id="bookingStatus"
                                      class="badge
                                      {{ $booking->booking_status == 'completed'
                                            ? 'bg-success'
                                            : ($booking->booking_status == 'cancelled'
                                                || $booking->booking_status == 'rejected'
                                                ? 'bg-danger'
                                                : 'bg-warning') }}">

                                    {{ ucwords(
                                        str_replace(
                                            '_',
                                            ' ',
                                            $booking->booking_status
                                        )
                                    ) }}

                                </span>

                            </div>


                            {{-- LAST UPDATED --}}

                            <div class="d-flex justify-content-between">

                                <span class="text-muted">
                                    Last Updated
                                </span>

                                <span id="lastUpdated">
                                    --
                                </span>

                            </div>


                            {{-- DISTANCE --}}

                            <div class="d-flex justify-content-between mt-3">

                                <span class="text-muted">
                                    Route Distance
                                </span>

                                <span id="sideRouteDistance">
                                    --
                                </span>

                            </div>


                            {{-- ETA --}}

                            <div class="d-flex justify-content-between mt-3">

                                <span class="text-muted">
                                    Estimated Time
                                </span>

                                <span id="sideRouteDuration">
                                    --
                                </span>

                            </div>


                        @else

                            <div class="text-center py-4">

                                <i class="ti ti-ambulance-off"
                                   style="font-size:45px;">
                                </i>

                                <p class="text-muted mt-2 mb-0">

                                    No ambulance assigned.

                                </p>

                            </div>

                        @endif

                    </div>

                </div>


                {{-- =================================================
                PICKUP
                ================================================== --}}

                <div class="card">

                    <div class="card-header">

                        <h5 class="card-title mb-0">
                            Pickup
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="d-flex">

                            <div class="
                                avatar
                                avatar-sm
                                bg-danger-subtle
                                rounded
                                me-2
                            ">

                                <i class="ti ti-map-pin text-danger"></i>

                            </div>


                            <div>

                                <small class="text-muted">
                                    Pickup Location
                                </small>

                                <h6 class="mb-0">

                                    {{ $booking->pickup_address ?? '-' }}

                                </h6>


                                @if(
                                    $booking->pickup_city
                                    || $booking->pickup_state
                                    || $booking->pickup_pincode
                                )

                                    <small class="text-muted d-block mt-1">

                                        {{ $booking->pickup_city }}

                                        @if($booking->pickup_state)
                                            , {{ $booking->pickup_state }}
                                        @endif

                                        @if($booking->pickup_pincode)
                                            - {{ $booking->pickup_pincode }}
                                        @endif

                                    </small>

                                @endif

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                DESTINATION
                ================================================== --}}

                <div class="card">

                    <div class="card-header">

                        <h5 class="card-title mb-0">
                            Destination
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="d-flex">

                            <div class="
                                avatar
                                avatar-sm
                                bg-success-subtle
                                rounded
                                me-2
                            ">

                                <i class="ti ti-map-pin text-success"></i>

                            </div>


                            <div>

                                <small class="text-muted">
                                    Destination
                                </small>

                                <h6 class="mb-0">

                                    {{ $booking->destination_address ?? '-' }}

                                </h6>


                                @if(
                                    $booking->destination_city
                                    || $booking->destination_state
                                    || $booking->destination_pincode
                                )

                                    <small class="text-muted d-block mt-1">

                                        {{ $booking->destination_city }}

                                        @if($booking->destination_state)
                                            , {{ $booking->destination_state }}
                                        @endif

                                        @if($booking->destination_pincode)
                                            - {{ $booking->destination_pincode }}
                                        @endif

                                    </small>

                                @endif

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                BOOKING INFORMATION
                ================================================== --}}

                <div class="card">

                    <div class="card-header">

                        <h5 class="card-title mb-0">
                            Booking Information
                        </h5>

                    </div>


                    <div class="card-body">


                        <div class="d-flex justify-content-between mb-3">

                            <span class="text-muted">
                                Booking No
                            </span>

                            <strong>
                                {{ $booking->booking_no }}
                            </strong>

                        </div>


                        @if(isset($booking->booking_date))

                            <div class="d-flex justify-content-between mb-3">

                                <span class="text-muted">
                                    Date
                                </span>

                                <span>

                                    {{ \Carbon\Carbon::parse(
                                        $booking->booking_date
                                    )->format('d M Y') }}

                                </span>

                            </div>

                        @endif


                        @if(isset($booking->booking_time))

                            <div class="d-flex justify-content-between">

                                <span class="text-muted">
                                    Time
                                </span>

                                <span>

                                    {{ \Carbon\Carbon::parse(
                                        $booking->booking_time
                                    )->format('h:i A') }}

                                </span>

                            </div>

                        @endif


                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- =============================================================
LEAFLET CSS
============================================================= --}}

<link rel="stylesheet"
      href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      crossorigin="">


{{-- =============================================================
LEAFLET JS
============================================================= --}}

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        crossorigin=""></script>


<script>

document.addEventListener('DOMContentLoaded', function () {


    /* =============================================================
       BOOKING
    ============================================================= */

    const bookingId = {{ $booking->id }};


    /* =============================================================
       COORDINATES
    ============================================================= */

    const pickupLat =
        {{ $booking->pickup_latitude ?? 'null' }};

    const pickupLng =
        {{ $booking->pickup_longitude ?? 'null' }};


    const destinationLat =
        {{ $booking->destination_latitude ?? 'null' }};

    const destinationLng =
        {{ $booking->destination_longitude ?? 'null' }};


    /* =============================================================
       DEFAULT LOCATION
    ============================================================= */

    const defaultLat =
        pickupLat !== null
            ? parseFloat(pickupLat)
            : 20.5937;


    const defaultLng =
        pickupLng !== null
            ? parseFloat(pickupLng)
            : 78.9629;


    /* =============================================================
       MAP
    ============================================================= */

    const map = L.map('map', {
        zoomControl: true
    }).setView(
        [
            defaultLat,
            defaultLng
        ],
        13
    );


    /* =============================================================
       OPEN STREET MAP
    ============================================================= */

    L.tileLayer(
        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        {
            maxZoom: 19,

            attribution:
                '&copy; OpenStreetMap contributors'
        }
    ).addTo(map);


    /* =============================================================
       ICONS
    ============================================================= */

    const pickupIcon = L.divIcon({

        className: '',

        html: `
            <div style="
                width:38px;
                height:38px;
                background:#dc3545;
                border:4px solid #fff;
                border-radius:50%;
                display:flex;
                align-items:center;
                justify-content:center;
                box-shadow:0 3px 10px rgba(0,0,0,.25);
            ">
                <i class="ti ti-map-pin"
                   style="
                        color:#fff;
                        font-size:20px;
                   ">
                </i>
            </div>
        `,

        iconSize: [38, 38],

        iconAnchor: [19, 19]

    });


    const destinationIcon = L.divIcon({

        className: '',

        html: `
            <div style="
                width:38px;
                height:38px;
                background:#20b26b;
                border:4px solid #fff;
                border-radius:50%;
                display:flex;
                align-items:center;
                justify-content:center;
                box-shadow:0 3px 10px rgba(0,0,0,.25);
            ">
                <i class="ti ti-map-pin"
                   style="
                        color:#fff;
                        font-size:20px;
                   ">
                </i>
            </div>
        `,

        iconSize: [38, 38],

        iconAnchor: [19, 19]

    });


    const ambulanceIcon = L.divIcon({

        className: '',

        html: `
            <div style="
                width:46px;
                height:46px;
                background:#6f2cff;
                border:4px solid #fff;
                border-radius:50%;
                display:flex;
                align-items:center;
                justify-content:center;
                box-shadow:0 3px 12px rgba(0,0,0,.35);
            ">
                <i class="ti ti-ambulance"
                   style="
                        color:#fff;
                        font-size:24px;
                   ">
                </i>
            </div>
        `,

        iconSize: [46, 46],

        iconAnchor: [23, 23]

    });


    /* =============================================================
       MARKERS
    ============================================================= */

    let pickupMarker = null;

    let destinationMarker = null;

    let ambulanceMarker = null;


    /* =============================================================
       MAP BOUNDS
    ============================================================= */

    const mapPoints = [];


    /* =============================================================
       PICKUP MARKER
    ============================================================= */

    if (
        pickupLat !== null &&
        pickupLng !== null
    ) {

        pickupMarker = L.marker(
            [
                parseFloat(pickupLat),
                parseFloat(pickupLng)
            ],
            {
                icon: pickupIcon
            }
        )
        .addTo(map)
        .bindPopup(`
            <strong>Pickup Location</strong>
            <br>
            {{ addslashes($booking->pickup_address ?? 'Pickup') }}
        `);


        mapPoints.push([
            parseFloat(pickupLat),
            parseFloat(pickupLng)
        ]);

    }


    /* =============================================================
       DESTINATION MARKER
    ============================================================= */

    if (
        destinationLat !== null &&
        destinationLng !== null
    ) {

        destinationMarker = L.marker(
            [
                parseFloat(destinationLat),
                parseFloat(destinationLng)
            ],
            {
                icon: destinationIcon
            }
        )
        .addTo(map)
        .bindPopup(`
            <strong>Destination</strong>
            <br>
            {{ addslashes($booking->destination_address ?? 'Destination') }}
        `);


        mapPoints.push([
            parseFloat(destinationLat),
            parseFloat(destinationLng)
        ]);

    }


    /* =============================================================
       ROUTE LINE
    ============================================================= */

    let routeLine = null;


    /* =============================================================
       ROUTE FUNCTION
    ============================================================= */

    function drawRoute(
        startLat,
        startLng,
        endLat,
        endLng
    ) {

        if (
            startLat === null ||
            startLng === null ||
            endLat === null ||
            endLng === null
        ) {

            return;

        }


        const url =
            'https://router.project-osrm.org/route/v1/driving/' +

            startLng +
            ',' +
            startLat +

            ';' +

            endLng +
            ',' +
            endLat +

            '?overview=full&geometries=geojson';


        fetch(url)

            .then(response => {

                if (!response.ok) {

                    throw new Error(
                        'Route API failed'
                    );

                }

                return response.json();

            })


            .then(data => {


                if (
                    !data.routes ||
                    !data.routes.length
                ) {

                    return;

                }


                const route =
                    data.routes[0];


                const coordinates =
                    route.geometry.coordinates.map(
                        point => [
                            point[1],
                            point[0]
                        ]
                    );


                /* =================================================
                   REMOVE OLD ROUTE
                ================================================== */

                if (routeLine) {

                    map.removeLayer(
                        routeLine
                    );

                }


                /* =================================================
                   DRAW ROUTE
                ================================================== */

                routeLine =
                    L.polyline(
                        coordinates,
                        {
                            color: '#6f2cff',

                            weight: 6,

                            opacity: 0.85,

                            lineJoin: 'round'
                        }
                    ).addTo(map);


                /* =================================================
                   DISTANCE
                ================================================== */

                const distanceKm =
                    route.distance / 1000;


                document.getElementById(
                    'routeDistance'
                ).innerText =
                    distanceKm.toFixed(1) +
                    ' km';


                document.getElementById(
                    'sideRouteDistance'
                ).innerText =
                    distanceKm.toFixed(1) +
                    ' km';


                /* =================================================
                   DURATION
                ================================================== */

                const durationMinutes =
                    Math.round(
                        route.duration / 60
                    );


                let durationText;


                if (durationMinutes < 60) {

                    durationText =
                        durationMinutes +
                        ' min';

                } else {

                    const hours =
                        Math.floor(
                            durationMinutes / 60
                        );

                    const minutes =
                        durationMinutes % 60;


                    durationText =
                        hours +
                        ' hr ' +
                        minutes +
                        ' min';

                }


                document.getElementById(
                    'routeDuration'
                ).innerText =
                    durationText;


                document.getElementById(
                    'sideRouteDuration'
                ).innerText =
                    durationText;


                document.getElementById(
                    'routeInfo'
                ).classList.remove(
                    'd-none'
                );


            })

            .catch(error => {

                console.error(
                    'Route error:',
                    error
                );

            });

    }


    /* =============================================================
       INITIAL ROUTE
       PICKUP → DESTINATION
    ============================================================= */

    if (
        pickupLat !== null &&
        pickupLng !== null &&
        destinationLat !== null &&
        destinationLng !== null
    ) {

        drawRoute(
            parseFloat(pickupLat),
            parseFloat(pickupLng),
            parseFloat(destinationLat),
            parseFloat(destinationLng)
        );

    }


    /* =============================================================
       FIT INITIAL MAP
    ============================================================= */

    if (mapPoints.length > 1) {

        map.fitBounds(
            mapPoints,
            {
                padding: [40, 40]
            }
        );

    }


    /* =============================================================
       LIVE LOCATION API
    ============================================================= */

    function getLiveLocation() {

        fetch(
            "{{ url('hospital/ambulance-bookings') }}/" +
            bookingId +
            "/location"
        )

        .then(response => {

            if (!response.ok) {

                throw new Error(
                    'Location API failed'
                );

            }

            return response.json();

        })


        .then(response => {


            if (!response.success) {

                return;

            }


            const data =
                response.data;


            /* =====================================================
               LOCATION CHECK
            ====================================================== */

            if (
                !data.latitude ||
                !data.longitude
            ) {

                return;

            }


            const lat =
                parseFloat(
                    data.latitude
                );


            const lng =
                parseFloat(
                    data.longitude
                );


            /* =====================================================
               AMBULANCE MARKER
            ====================================================== */

            if (!ambulanceMarker) {

                ambulanceMarker =
                    L.marker(
                        [
                            lat,
                            lng
                        ],
                        {
                            icon: ambulanceIcon
                        }
                    )
                    .addTo(map)
                    .bindPopup(`
                        <strong>Ambulance</strong>
                        <br>
                        Live Location
                    `);


            } else {

                ambulanceMarker.setLatLng(
                    [
                        lat,
                        lng
                    ]
                );

            }


            /* =====================================================
               AMBULANCE → DESTINATION ROUTE
            ====================================================== */

            if (
                destinationLat !== null &&
                destinationLng !== null
            ) {

                drawRoute(
                    lat,
                    lng,
                    parseFloat(destinationLat),
                    parseFloat(destinationLng)
                );

            }


            /* =====================================================
               MAP MOVE
            ====================================================== */

            map.setView(
                [
                    lat,
                    lng
                ],
                14,
                {
                    animate: true
                }
            );


            /* =====================================================
               LAST UPDATED
            ====================================================== */

            if (data.updated_at) {

                document.getElementById(
                    'lastUpdated'
                ).innerText =
                    data.updated_at;

            }


            /* =====================================================
               BOOKING STATUS
            ====================================================== */

            if (data.booking_status) {


                const status =
                    data.booking_status;


                const statusText =
                    status
                        .replaceAll(
                            '_',
                            ' '
                        )
                        .replace(
                            /\b\w/g,
                            char =>
                                char.toUpperCase()
                        );


                const statusElement =
                    document.getElementById(
                        'bookingStatus'
                    );


                statusElement.innerText =
                    statusText;


                /* =================================================
                   STATUS BADGE
                ================================================== */

                statusElement.className =
                    'badge ' +
                    getStatusClass(status);


                /* =================================================
                   TRACKING STATUS
                ================================================== */

                const activeStatuses = [

                    'ambulance_assigned',

                    'on_the_way',

                    'arrived',

                    'patient_picked'

                ];


                const trackingElement =
                    document.getElementById(
                        'trackingStatus'
                    );


                if (
                    activeStatuses.includes(
                        status
                    )
                ) {

                    trackingElement.className =
                        'badge bg-success';

                    trackingElement.innerHTML =
                        '<i class="ti ti-live-photo me-1"></i> Live';

                }


                if (
                    status === 'completed'
                ) {

                    trackingElement.className =
                        'badge bg-success';

                    trackingElement.innerHTML =
                        '<i class="ti ti-circle-check me-1"></i> Completed';

                }


                if (
                    status === 'cancelled' ||
                    status === 'rejected'
                ) {

                    trackingElement.className =
                        'badge bg-danger';

                    trackingElement.innerHTML =
                        '<i class="ti ti-alert-circle me-1"></i> Closed';

                }

            }

        })


        .catch(error => {

            console.error(
                'Tracking error:',
                error
            );


            const trackingElement =
                document.getElementById(
                    'trackingStatus'
                );


            trackingElement.className =
                'badge bg-danger';


            trackingElement.innerHTML =
                '<i class="ti ti-alert-circle me-1"></i> Offline';

        });

    }


    /* =============================================================
       STATUS CLASS
    ============================================================= */

    function getStatusClass(status) {

        switch(status) {


            case 'pending':

                return 'bg-warning';


            case 'accepted':

                return 'bg-info';


            case 'ambulance_assigned':

                return 'bg-primary';


            case 'on_the_way':

                return 'bg-warning';


            case 'arrived':

                return 'bg-info';


            case 'patient_picked':

                return 'bg-primary';


            case 'completed':

                return 'bg-success';


            case 'rejected':

                return 'bg-danger';


            case 'cancelled':

                return 'bg-danger';


            default:

                return 'bg-secondary';

        }

    }


    /* =============================================================
       INITIAL LIVE LOCATION
    ============================================================= */

    getLiveLocation();


    /* =============================================================
       UPDATE EVERY 5 SECONDS
    ============================================================= */

    setInterval(
        getLiveLocation,
        5000
    );


});

</script>

@endsection
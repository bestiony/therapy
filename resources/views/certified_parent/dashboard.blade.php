@extends('layouts.certified_parent')

@section('breadcrumb')
    <div class="page-banner-content text-center">
        <h3 class="page-banner-heading text-white pb-15">Hey,
            {{ auth::user()->instructor ? auth::user()->instructor->name : '' }} <img
                src="{{ asset('frontend/assets/img/student-profile-img/waving-hand.png') }}" alt="student" class="me-2">
        </h3>

        <!-- Breadcrumb Start-->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item font-14"><a href="{{ route('main.index') }}">{{ __('Home') }}</a></li>
                <li class="breadcrumb-item font-14 active" aria-current="page">{{ __('Dashboard') }}</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
    <div class="instructor-profile-right-part">
        <div class="instructor-dashboard-box">

            <div class="row instructor-dashboard-top-part instructor-only-dashboard-top-part">



                <div class="col-md-4 mb-30">
                    <div class="instructor-dashboard-top-part-item d-flex align-items-center radius-8">
                        <div class="instructor-dashboard-top-part-icon flex-shrink-0">
                            <span class="iconify" data-icon="carbon:user-multiple"></span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="para-color font-11 font-semi-bold">{{ __('Total Conversations') }}
                                {{-- <span class="color-gray font-13 font-normal">({{__('This Month')}})</span> --}}
                            </h6>
                            <h5>{{ @$total_conversations ?? 0 }}</h5>
                        </div>
                    </div>
                </div>



            </div>

            <div class="row recently-added-courses">
                <div class="are-you-available-box mb-30">
                    <form action="{{ route('parent.consultation.parentAvailabilityStoreUpdate') }}" method="post">
                        @csrf
                        <h6 class="are-you-available-title mb-3 d-inline-flex align-items-center"><span class="iconify me-2"
                                data-icon="heroicons-outline:thumb-up"></span>{{ __('Are you available for 1 to 1 conversation ?') }}
                        </h6>
                        <div class="mb-3">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" @if ($parent->consultation_available == 1) checked @endif
                                    type="radio" id="inlineCheckbox1" value="1" name="consultation_available">
                                <label class="form-check-label color-heading mb-0"
                                    for="inlineCheckbox1">{{ __('Yes') }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" @if ($parent->consultation_available != 1) checked @endif
                                    type="radio" id="inlineCheckbox2" value="0" name="consultation_available">
                                <label class="form-check-label color-heading mb-0"
                                    for="inlineCheckbox2">{{ __('No') }}</label>
                            </div>
                        </div>

















                        <button type="submit"
                            class="theme-btn theme-button1 default-hover-btn">{{ __('Save') }}</button>
                    </form>

                </div>


            </div>

            
        </div>

    </div>
@endsection

@push('style')
@endpush

@push('script')
    <!--Apexcharts js-->
    <script src="{{ asset('common/js/apexcharts.min.js') }}"></script>

    <!--Chart1 Script-->
    <script>
        // Chart Start
        var options = {
            chart: {
                height: '100%',
                type: "area"
            },
            dataLabels: {
                enabled: false,
            },
            series: [{
                name: "Sale",
                data: @json(@$totalPrice)
            }],
            fill: {
                type: "gradient",
                colors: ['#5e3fd7'],
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.9,
                    stops: [0, 90, 100]
                }
            },
            markers: {
                size: 4,
                colors: ['#fff'],
                strokeColors: '#5e3fd7',
                strokeWidth: 2,
                strokeOpacity: 0.9,
                strokeDashArray: 0,
                fillOpacity: 1,
                discrete: [],
                shape: "circle",
                radius: 2,
                offsetX: 0,
                offsetY: 0,
                onClick: undefined,
                onDblClick: undefined,
                showNullDataPoints: true,
                hover: {
                    size: undefined,
                    sizeOffset: 3
                }
            },
            stroke: {
                show: true,
                curve: 'smooth',
                lineCap: 'butt',
                colors: undefined,
                width: 3,
                dashArray: 0,
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        var result = val;
                        if ("{{ get_currency_placement() }}" == 'after') {
                            result = val + ' ' + "{{ get_currency_symbol() }}"
                        } else {
                            result = "{{ get_currency_symbol() }}" + ' ' + val
                        }
                        return result;
                    }
                }
            },
            xaxis: {
                categories: @json(@$months),
                axisBorder: {
                    show: false,
                    color: '#E7E3EB',
                    height: 1,
                    width: '100%',
                    offsetX: 0,
                    offsetY: 0
                },
                axisTicks: {
                    show: false,
                    borderType: 'solid',
                    color: '#E7E3EB',
                    height: 6,
                    offsetX: 0,
                    offsetY: 0
                },
            },
            yaxis: {
                show: true,
                showAlways: true,
                showForNullSeries: true,
                opposite: false,
                reversed: false,
                logarithmic: false,
                // logBase: 10,
                // tickAmount: 6,
                // min: 0.0,
                // max: 100.0,
                type: 'numeric',
                categories: [
                    '5', '10', '15', '20', '25', '30', '35', '40'
                ],
                axisBorder: {
                    show: false,
                    color: '#E7E3EB',
                    offsetX: 0,
                    offsetY: 0
                },
                axisTicks: {
                    show: false,
                    borderType: 'solid',
                    color: '#E7E3EB',
                    width: 6,
                    offsetX: 0,
                    offsetY: 0
                },
            },
        };
        var chart = new ApexCharts(document.querySelector("#chart1"), options);
        chart.render();

        // Chart End
    </script>
@endpush

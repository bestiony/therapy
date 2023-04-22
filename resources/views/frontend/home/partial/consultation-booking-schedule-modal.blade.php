<!-- Consultation Booking Modal Start-->
<div class="modal fade" id="consultationBookingModal" tabindex="-1" aria-labelledby="consultationBookingModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="consultationBookingModalLabel">{{ __('Booking Now') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class=" bg-white rounded ">
                <!-- Rounded tabs -->
                <ul id="myTab-consultation" role="tablist"
                    class="nav nav-tabs nav-pills flex-column flex-sm-row text-center bg-light border-0 rounded-nav px-4 py-2">
                    <li class="nav-item flex-sm-fill">
                        <a id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
                            aria-selected="true"
                            class="nav-link border-0 text-uppercase font-weight-bold active" booking-type="hourly">Session</a>
                    </li>
                    <li class="nav-item flex-sm-fill">
                        <a id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile"
                            aria-selected="false" class="nav-link border-0 text-uppercase font-weight-bold" booking-type="monthly">Monthly</a>
                    </li>

                </ul>
                <div id="myTabContent" class="tab-content">
                    <div id="home" role="tabpanel" aria-labelledby="home-tab"
                        class="tab-pane fade px-4 py-2 show active">
                        <div class="row booking-header-row consultation-select-date-hour">
                            <div class="input-group flex-nowrap col-md-6 consultantion-calendar-box">
                                <label
                                    class="col-md-5 col-form-label font-17 font-semi-bold color-heading">{{ __('Select Date') }}:</label>
                                <div class="book-schedule-calendar-wrap position-relative appendDatePicker">
                                    <!-- Append from booking.js -->
                                </div>
                            </div>
                            <div class="input-group col-md-6 consultantion-hours-box">
                                <label
                                    class="col-md-3 col-form-label font-17 font-semi-bold color-heading">{{ __('Hours') }}</label>
                                <input type="text" class="form-control font-medium hourly_fee" disabled
                                    value="0">
                                <input type="hidden" class="form-control font-medium hourly_rate" value="0">
                                <input type="hidden" class="form-control font-medium monthly_rate" value="0">
                            </div>
                            <input type="hidden" class="booking_instructor_user_id" value="">
                        </div>


                    </div>


                    <div id="profile" role="tabpanel" aria-labelledby="profile-tab" class="tab-pane fade px-4 py-2">
                        <div class="row booking-header-row consultation-select-date-hour">
                            <div class="input-group flex-nowrap col-md-6 consultantion-calendar-box">
                                <label
                                    class="col-md-5 col-form-label font-17 font-semi-bold color-heading">{{ __('Starting Date') }}:</label>
                                <div class="book-schedule-calendar-wrap position-relative appendStartingDatePicker">
                                    <!-- Append from booking.js -->
                                </div>
                            </div>
                            <div class="input-group col-md-6 consultantion-hours-box">
                                <label
                                    class="col-md-3 col-form-label font-17 font-semi-bold color-heading">{{ __('Monthly Rate') }}</label>
                                <input type="text" class="form-control font-medium monthly_rate" disabled
                                    value="0">
                                <input type="hidden" class="form-control font-medium monthly_rate" value="0">
                                <input type="hidden" class="form-control font-medium monthly_rate_pure" value="0">
                            </div>
                            <input type="hidden" class="booking_instructor_user_id" value="">
                        </div>


                    </div>
                    <div class="px-4 py-2">
                        <div class="row booking-header-row">
                            <div class="col-sm-6 col-md-4">
                                <div class="input-group row">
                                    <label
                                        class="col-sm-4 col-md-6 col-form-label font-17 font-semi-bold color-heading">{{ __('Type') }}</label>
                                    <div class="col-sm-8 col-md-6 InPerson d-none">
                                        <div class="time-slot-item">
                                            <input type="radio" name="available_type" class="btn-check" value="1"
                                                id="checkboxInperson" autocomplete="off">
                                            <label class="btn btn-outline-primary mb-0"
                                                for="checkboxInperson">{{ __('In Person') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-2">
                                <div class="input-group row">
                                    <div class="col Online d-none">
                                        <div class="time-slot-item">
                                            <input type="radio" name="available_type" class="btn-check" value="2"
                                                id="checkboxOnline" autocomplete="off">
                                            <label class="btn btn-outline-primary mb-0"
                                                for="checkboxOnline">{{ __('Online') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="appendDayAndTime">

                        </div>
                        <div class="  d-flex booking-header-row align-items-center ">
                            <input type="checkbox" name="agreedToTerms" id="agreedToTerms">
                            <label class="my-0 mx-2" for="#agreedToTerms"><a target="_blank"
                                    href="{{ route('terms-conditions') }}">
                                    {{ get_option('accept_terms_message') }}</a></label>
                        </div>

                        <div class="modal-footer d-flex justify-content-between align-items-center">
                            <button type="button" class="theme-btn theme-button1 default-hover-btn w-100 makePayment"
                                data-route="{{ route('student.addToCartConsultation') }}">{{ __('Make Payment') }}</button>
                        </div>
                    </div>
                </div>
                <!-- End rounded tabs -->
            </div>


        </div>
    </div>
</div>
<!-- Consultation Booking Modal End-->

<input type="hidden" class="getInstructorBookingTimeRoute" value="{{ route('getInstructorBookingTime') }}">
<input type="hidden" class="clientOrderType" value="hourly">

<script>
    var clientOrderType = document.querySelector('.clientOrderType');

    document.addEventListener("DOMContentLoaded", function() {
        var tabList = [].slice.call(document.querySelectorAll("#myTab-consultation a"));
        tabList.forEach(function(tab) {
            var tabTrigger = new bootstrap.Tab(tab);

            tab.addEventListener("click", function(event) {
                event.preventDefault();
                tabTrigger.show();
                clientOrderType.value = tab.getAttribute('booking-type')
                $('.appendDayAndTime').empty();
            });
        });
    });
</script>

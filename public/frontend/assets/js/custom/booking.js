(function ($) {
    "use strict";
    $(document).ready(function () {
        $(document).on('click', ".bookSchedule", function () {
            var hourly_fee = $(this).data('hourly_fee');
            var hourly_rate = $(this).data('hourly_rate');
            var monthly_rate = $(this).data('monthly_rate');
            console.log(monthly_rate);
            var booking_instructor_user_id = $(this).data('booking_instructor_user_id');
            var available_type = $(this).data('type');
            if (available_type == 3) {
                $('.InPerson').removeClass('d-none');
                $('.Online').removeClass('d-none');
            } else if (available_type == 2) {
                $('.Online').removeClass('d-none');
                $('.InPerson').addClass('d-none');
            } else if (available_type == 1) {
                $('.InPerson').removeClass('d-none');
                $('.Online').addClass('d-none');
            }
            $('.bookingDate').val('');
            $('.appendDayAndTime').html('');
            $('.hourly_fee').val(hourly_fee);
            $('.hourly_rate').val(hourly_rate);
            $('.monthly_rate').val(monthly_rate);
            $('.booking_instructor_user_id').val(booking_instructor_user_id);

            var getOffDaysRoute = $(this).data('get_off_days_route');

            $.ajax({
                type: "GET",
                url: getOffDaysRoute,
                datatype: "json",
                success: function (response) {
                    const dateArray = response.days
                    console.log(dateArray)
                    $('.appendDatePicker').html(' <input type="text" class="bookingDate" id="datepicker" autocomplete="off" placeholder="Select Date">\n' +
                        '                            <span class="iconify" data-icon="akar-icons:calendar"></span>');
                    $("#datepicker").datepicker({
                        dateFormat: "dd-mm-yy",
                        minDate: new Date()

                    });
                    $('.appendStartingDatePicker').html(' <input type="text" class="startingDate bookingDate" id="startdatepicker" autocomplete="off" placeholder="Select Date">\n' +
                        '                            <span class="iconify" data-icon="akar-icons:calendar"></span>');
                    $("#startdatepicker").datepicker({
                        dateFormat: "dd-mm-yy",
                        minDate: new Date()

                    });
                },
                error: function () {
                    alert("Error!");
                },
            });

        })

        $(document).on('change', ".bookingDate", function () {
            if($(this).hasClass('startingDate')){
                return
            }
            var bookingDate = $('.bookingDate').val();
            var user_id = $('.booking_instructor_user_id').val();
            var clientOrderType = $('.clientOrderType').val();
            var getInstructorBookingTimeRoute = $('.getInstructorBookingTimeRoute').val();
            $.ajax({
                type: "GET",
                url: getInstructorBookingTimeRoute,
                data: {"user_id": user_id, "bookingDate": bookingDate, "clientOrderType":clientOrderType},
                datatype: "json",
                success: function (response) {
                    toastr.options.positionClass = 'toast-bottom-right';

                    if (response.status == 404) {
                        toastr.error(response.msg);
                    }

                    $('.appendDayAndTime').html(response);
                },
                error: function () {
                    alert("Error!");
                },
            });
        })


        $(document).on('change', ".startingDate", function () {
            // if ($(this).hasClass("bookingDate")) {
            //     return;
            // }
            var bookingDate = $('.startingDate').val();
            var user_id = $('.booking_instructor_user_id').val();
            var clientOrderType = $('.clientOrderType').val();
            var getInstructorBookingTimeRoute = $('.getInstructorBookingTimeRoute').val();
            $.ajax({
                type: "GET",
                url: getInstructorBookingTimeRoute,
                data: {"user_id": user_id, "bookingDate": bookingDate, "clientOrderType":clientOrderType},
                datatype: "json",
                success: function (response) {
                    toastr.options.positionClass = 'toast-bottom-right';

                    if (response.status == 404) {
                        toastr.error(response.msg);
                    }

                    $('.appendDayAndTime').html(response);
                },
                error: function () {
                    alert("Error!");
                },
            });
        })

        $(document).on('click', ".makePayment", function () {
            var clientOrderType = $('.clientOrderType').val().toLowerCase();
            var booking_instructor_user_id = $('.booking_instructor_user_id').val();
            if(clientOrderType == "hourly"){
                var bookingDate = $('.bookingDate').val();
            }else {
                var bookingDate = $('.startingDate').val();
            }
            var agreedToTerms = $('#agreedToTerms').is(':checked');
            var available_type = $("input[name='available_type']:checked").val()
            var time = $("input[name='time']:checked").val();
            var route = $(this).data('route');
            var consultation_slot_id = $('.consultation_slot_id').val();
            toastr.options.positionClass = 'toast-bottom-right';
            if (!agreedToTerms) {
                toastr.error("Please accept the terms and conditions first")
                return
            }
            if (!bookingDate) {
                toastr.error("Please select date!")
                return
            }


            if (!available_type) {
                toastr.error("Please select type!")
                return
            }

            if (!time) {
                toastr.error("Please select time!")
                return
            }

            $.ajax({
                type: "POST",
                url: route,
                data: {
                    'consultation_slot_id': consultation_slot_id,
                    'booking_instructor_user_id': booking_instructor_user_id,
                    'bookingDate': bookingDate,
                    'time': time,
                    'available_type' : available_type,
                    'clientOrderType' : clientOrderType,
                    '_token': $('meta[name="csrf-token"]').attr('content')
                },
                datatype: "json",
                success: function (response) {
                    if (response.status == 401 || response.status == 402 || response.status == 404 || response.status == 409) {
                        toastr.error(response.msg)
                    } else if (response.status == 200) {
                        location.href = response.redirect_route
                        toastr.success(response.msg)
                    }
                },
                error: function (error) {
                    if (error.status == 401) {
                        toastr.error("You need to login first!")
                    }
                    if (error.status == 403) {
                        toastr.error("You don't have permission!")
                    }

                },
            });
        })
    });

})(jQuery)

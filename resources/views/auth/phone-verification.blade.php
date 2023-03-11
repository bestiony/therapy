@extends('layouts.auth')

@section('content')
    <!-- Verify Phone Area Start -->
    <section class="sign-up-page p-0">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-md-5">
                    <div class="sign-up-left-content">
                        <div class="sign-up-top-logo">
                            <a href="{{ route('main.index') }}"><img src="{{getImageFile(get_option('app_logo'))}}" alt="logo"></a>
                        </div>
                        <p>{{ __(get_option('sign_up_left_text')) }}</p>
                        @if(get_option('sign_up_left_image'))
                            <div class="sign-up-bottom-img">
                                <img src="{{getImageFile(get_option('sign_up_left_image'))}}" alt="hero" class="img-fluid">
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="sign-up-right-content bg-white">
                        <form action="{{ route('verify_phone') }}" method="post">
                            @csrf

                            <h5 class="mb-1">{{ __(get_option('phone_verify_title')) }}</h5>
                            {{-- <div class="forgot-pass-text mb-25 mt-3">
                                <p class="mb-2">{{ __(get_option('forgot_subtitle')) }}</p>
                            </div> --}}

                            <div class="row mb-30 d-flex align-items-end">
                                <div class="col-md-8">
                                    <label class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Phone Number') }}</label>
                                    <input value="{{auth()->user()->phone_number}}" type="text" name="phone_number" id="phone_number" class="form-control" placeholder="{{ __('Type your phone number') }}" required>
                                </div>
                                <a href="#" id="send_again" class="theme-btn theme-button1 theme-button3 font-15 fw-bold col-4">send again</a>
                            </div>
                            <div id="success_message" class="alert alert-success d-none" role="alert">
                                This is a success alert—check it out!
                              </div>
                            <div class="row mb-30">
                                <div class="col-md-12">
                                    <label class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Verification Code') }}</label>
                                    <input  type="text" name="verification_code" class="form-control" placeholder="{{ __('type your phone verification code') }}" required>
                                </div>
                            </div>
                            <div class="row mb-30">
                                <div class="col-md-12">
                                    <button type="submit" class="theme-btn theme-button1 theme-button3 font-15 fw-bold w-100">{{ __(get_option('verify_phone_button')) }}</button>
                                </div>
                            </div>
                            <div class="row mb-30">
                                <div class="col-md-12"><a href="{{ route('logout') }}" class="color-hover text-decoration-underline font-medium">{{ __('Logout') }}</a></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @push('script')
    <script>
        $(document).ready(function() {
        // Wait for the document to be ready
        $("#send_again").click(function() {
            if(!$("#success_message").hasClass('d-none')){
                $("#success_message").addClass('d-none')
            }
            // When the "send_again" button is clicked
            var csrf_token = "{{csrf_token()}}";

            var phone_number = $("#phone_number").val(); // Get the value of the phone_number input
            $.ajax({
            url: "{{route('change_phone_number')}}",
            method: "POST",
            data: {
                phone_number: phone_number,
                _token : csrf_token,
            }, // Send the phone number as data
            success: function() {
                // If the request succeeds

                $("#success_message").removeClass('d-none') // Set the success message text
                $("#success_message").text("Verification code sent!"); // Set the success message text
            },
            error: function() {
                // If the request fails
                $("#success_message").text("Error sending verification code!"); // Set the error message text
            }
            });
        });
        });

    </script>
    @endpush


    <!-- Verify Phone Area End -->

@endsection

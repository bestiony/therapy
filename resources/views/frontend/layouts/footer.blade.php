<footer class="footer-area footer-gradient-bg position-relative">
    <div class="section-overlay">
        <div class="container">
            <!-- footer-widget-area -->
            <div class="row footer-top-part section-p-t-b-90">
                <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                    <div class="footer-widget footer-about">
                        <img src="{{asset(get_option('app_logo'))}}" alt="Logo">
                        <p>{{ __(get_option('footer_quote')) }}</p>
                        <div class="footer-social mt-30">
                            <ul class="d-flex align-items-center">
                                <li><a href="{{get_option('facebook_url')}}"><span class="iconify" data-icon="ant-design:facebook-filled"></span></a></li>
                                <li><a href="{{get_option('twitter_url')}}"><span class="iconify" data-icon="ant-design:twitter-square-filled"></span></a></li>
                                <li><a href="{{get_option('linkedin_url')}}"><span class="iconify" data-icon="ant-design:linkedin-filled"></span></a></li>
                                <li><a href="{{get_option('pinterest_url')}}"><span class="iconify" data-icon="fa-brands:pinterest-square" data-width="1em" data-height="1em"></span></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                    <div class="footer-widget">
                        <h6 class="footer-widget-title">{{__('Company')}}</h6>
                        <div class="footer-links d-flex">
                            <ul>
                                <li><a href="{{ route('about') }}">{{ __('About')  }}</a></li>
                                <li><a href="{{ route('faq') }}">{{__('FAQ')}}</a></li>
                                <li><a href="{{ route('blogs') }}">{{ __('Blogs') }}</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                    <div class="footer-widget">
                        <h6 class="footer-widget-title">{{__('Support')}}</h6>
                        <div class="footer-links d-flex">
                            <ul>
                                <li><a href="{{ route('contact') }}">{{  __('Contact')  }}</a></li>
                                <li><a href="{{ route('support-ticket-faq') }}">{{  __('Support')  }}</a></li>
                                @if(!get_option('private_mode') || !auth()->guest())
                                <li><a href="{{ route('courses') }}">{{ __('Courses')  }}</a></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                    <div class="footer-widget footer-contact-info">
                        <h6 class="footer-widget-title">{{__('Contact Info')}}</h6>

                        <div class="footer-links d-flex">
                            <ul>
                                <li><span class="iconify" data-icon="carbon:location-filled"></span><span>{{ __(get_option('app_location')) }}</span></li>
                                <li><span class="iconify" data-icon="fluent-emoji-high-contrast:telephone-receiver"></span><a href="tel:12457835">{{ __(get_option('app_contact_number')) }}</a></li>
                                <li><span class="iconify" data-icon="ic:round-email"></span><a href="mailto:demo@gmail.com">{{ __(get_option('app_email')) }}</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!--copyright-text-->
            <div class="row copyright-wrapper">
                <div class="col-12 col-md-12 col-lg-6">
                    <div class="footer-payment">
                        <img src="{{ asset('frontend/assets/img/payment-cards.png') }}" alt="payments">
                    </div>
                </div>
                <div class="col-12 col-md-12 col-lg-6">
                    <div class="copyright-text text-center">
                        <p class="text-white font-13">{{ __(get_option('app_copyright')) }}</p>
                    </div>
                </div>
                <div class="col-12 col-md-12 col-lg-4" style="display:none;">
                    <div class="footer-bottom-nav">
                        <ul class="d-flex justify-content-end">
                            <li><a href="{{ route('allInstructor') }}">{{__('Instructor')}}</a></li>
                            <li><a href="{{ route('student.become-an-instructor') }}">{{__('Become Instructor')}}</a></li>
                            <li><a href="{{ route('verify_certificate') }}">{{__('Verify Certificate')}}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<div style="position:fixed;bottom:0;width:100%;background:#fff;left:0;" class="aiz-mobile-bottom-nav d-xl-none fixed-bottom bg-white shadow-lg border-top rounded-top">
    <div class="row align-items-center " style="margin:auto;">
        <div class="col-xs-3">
            <a href="{{route('forum.index')}}" class="text-reset d-block text-center pb-2 pt-3">
               <img src="{{asset('uploads/b1.png')}}"
               style="width:40px;" alt="feature" />
                <span class="d-block fs-10 fw-600 opacity-60 opacity-100 fw-600">{{ __('forum') }}</span>
            </a>
        </div>
        <div class="col-xs-3">
            <a href="{{route('instructor')}}" class="text-reset d-block text-center pb-2 pt-3">
                 <img src="{{asset('uploads/b2.png')}}"  style="width:40px;" alt="feature" />
                <span class="d-block fs-10 fw-600 opacity-60 ">{{ __('Instructor') }}</span>
            </a>
        </div>

        <div class="col-xs-3">
            <a href="{{route('organizations')}}" class="text-reset d-block text-center pb-2 pt-3">
                 <img src="{{asset('uploads/b3.png')}}"  style="width:40px;" alt="feature" />
                <span class="d-block fs-10 fw-600 opacity-60 ">{{ __('Organizations') }}</span>
            </a>
        </div>
        <div class="col-xs-3">
                    <a href="{{route('courses')}}" class="text-reset d-block text-center pb-2 pt-3">
                 <img src="{{asset('uploads/b4.png')}}"  style="width:40px;" alt="feature" />
                <span class="d-block fs-10 fw-600 opacity-60"> {{ __('Courses') }}</span>
            </a>
                </div>
    </div>
</div>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

<a target="_blank" href="https://my-therapists.com/public/platform-ALL.pdf"  class=" theme-btn2 call" style="padding-left:0;"><i class="fa fa-user "></i><span>{{ __('Important Information') }} </span></a>
<a href="https://api.whatsapp.com/send?phone=+97431062048&text= 
" target="_blank" class=" theme-btn2 whatsapp"><img style="width:50px;" src="https://seeklogo.com/images/W/whatsapp-icon-logo-8CA4FB831E-seeklogo.com.png" /><span>{{ __('Whatsapp') }}</span></a>
<style>
 .client-logo-item img {
    max-height: 100%;
}.client-logo-item {
    margin: 0;
    width: 100%;
    height: auto;
    overflow: hidden;
}
    .theme-btn2{background-color:#fff;border-radius:40px;bottom:10px;color:#fff;display:table;height:50px;right:30px;min-width:50px;position:fixed;text-align:center;z-index:9999}.theme-btn2 i{font-size:22px;line-height:50px}.theme-btn2.bt-support-now{background:#1ebbf0;background:-moz-linear-gradient(45deg,#1ebbf0 8%,#39dfaa 100%);background:-webkit-linear-gradient(45deg,#1ebbf0 8%,#39dfaa 100%);background:linear-gradient(45deg,#1ebbf0 8%,#39dfaa 100%);bottom:70px}.theme-btn2.bt-buy-now{background:#1fdf61;background:-moz-linear-gradient(top,#a3d179 0,#88ba46 100%);background:-webkit-linear-gradient(top,#a3d179 0,#88ba46 100%);background:linear-gradient(to bottom,#a3d179 0,#88ba46 100%)}.theme-btn2:hover{color:#fff;padding:0 20px}.theme-btn2 span{display:table-cell;vertical-align:middle;font-size:16px;letter-spacing:-15px;opacity:0;line-height:50px;transition:all .5s;-webkit-transition:all .5s;-moz-transition:all .5s;text-transform:uppercase}.theme-btn2:hover span{opacity:1;letter-spacing:1px;padding-left:10px}.at-expanding-share-button[data-position=bottom-left]{bottom:130px!important}
.whatsapp{background:green;}
.call{background:red;}
.cons{background:#e63108;}
.service-content p{color:#000;}

.theme-btn2.call{bottom:70px;}
.theme-btn2.cons{bottom:130px;}

</style>

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



<style>#mainNav .navbar-brand img{width:220px;}
header.hero-area.gradient-bg.position-relative {
    background: #fff;
    background-blend-mode: none;
    display: flex;
}.come-for-learn-text ,.hero-heading{
   
    color: var(--theme-color);
  
}.hero-area .section-overlay {
    min-height: 795px;
    padding: 0px 0;
}.header-nav-left-side form {
   
    border: 2px solid var(--theme-color);
    
    }#mainNav .navbar-nav .nav-item .nav-link {
    color: var(--theme-color)!important;
    background: transparent;
}.green-theme-btn,.course-instructor-support-wrap div:nth-child(2) .instructor-support-item .theme-btn {
    background-color: #7108c1;
    border-color: #7108c1!important;
    color: var(--white-color)!important;
}
    .instructor-support-item {
    MARGIN: 22PX AUTO;}
    .footer-about img{width:160px;}
    .bg-page {
    background-color: #fff;
}.single-feature-item {
    background-color: var(--white-color);
    box-shadow: 0 6px 21px rgba(21, 3, 89, 0.08);
    border-radius: 3px;
    padding: 43px 30px;
    margin-bottom: 78px;
}.search-instructor-item {
    background: #fafafa;}
    .hero-area .section-overlay {
   
    min-height: 725px;
    padding: 0px 0;
}.course-sidebar-accordion-item{
    padding: 22px;}
    header.hero-area.gradient-bg.position-relative .section-overlay {
   position:relative;
   
}.how-it-work-area.bg-image,li.nav-item.dropdown.menu-round-btn.menu-language-btn.dropdown-top-space {
    display: none;
}.navbar .nav-item .dropdown-menu{z-index:999999999999999999999999999999999999999999;}
#map{display:none;}header.hero-area.gradient-bg.position-relative .section-overlay {
    background: transparent;
    height: 100%;
    width: 100%;
    z-index: 200;
}
#mainNav.sticky {
    position: relative;}
.hero-heading {
   
    line-height: 120.5%;
    
} .page-banner-header .section-overlay {

    min-height: 200px;
    padding: 10px 0 10px;

}

.blank-page-banner-wrap {

    padding: 90px 0 80px;
    background-color: 

    transparent;
    margin-top: 10px;
    min-height: 100px;

}.page-banner-header .section-overlay {
    min-height: 100px!important;
    padding: 10px 0 10px!important;
}
.page-banner-heading {

    color: 

    #fff;

}#course_type option:last-child,.instructor-my-course-btns .instructor-course-btn:nth-child(2),.course-watch-banner-items li:last-child{display:none;}
.course-single-page-header .section-overlay {
    min-height: 330px;
    display: flex;
    align-items: center;
    padding: 30px 0 30px;
}


.course-watch-banner-items li {
    display: inline-flex;
    align-items: center;
    margin: 0 10px;
    color: 
    #fff;
}
.search-instructor-img-wrap img {
    height: 248px;
    width: 100%!important;
    border-radius: 0!important;
}.card.instructor-item.search-instructor-item.position-relative.text-center.border-0.p-30.px-3{padding:0!important;}
.page-banner-header.blank-page-banner-header.gradient-bg.position-relative {
    margin-bottom: 70px;
}
#mainNav.sticky {
    position: fixed;
    z-index: 99;
    width: 100%;
    top: 0;
    box-shadow: 0 0 2px rgba(0, 0, 0, 0.1);
    -webkit-animation: 500ms ease-in-out 0s normal none 1 running fadeInDown;
    animation: 500ms ease-in-out 0s normal none 1 running fadeInDown;
    -webkit-transition: 0.6s;
    transition: 0.6s;
    background-color: #fafafa;
}.page-banner-header .section-overlay {
    min-height: 200px;
    padding: 100px 0 100px;
}
header.hero-area.gradient-bg.position-relative .section-overlay {
    background: transparent;
    height: 100%;
    width: 100%;
}
    #mainNav .navbar-nav .nav-item .nav-link{color:var(--theme-color);}
    .hero-content {
    padding:122px 88px;}
.hero-right-side img{width:100%;float:right;}
#mainNav {
    position: relative;}
   .hero-content p{display:none;} 
    
    .navbar-toggler {
    padding: var(--bs-navbar-toggler-padding-y) var(--bs-navbar-toggler-padding-x);
    font-size: var(--bs-navbar-toggler-font-size);
    line-height: 1;
    color: var(--bs-navbar-color);
    background-color: #09005e;
    border: var(--bs-border-width) solid var(--bs-navbar-toggler-border-color);
    border-radius: var(--bs-navbar-toggler-border-radius);
    transition: var(--bs-navbar-toggler-transition);
}
    @media only screen and (max-width: 779px) {
video#video_background{display:none;}
.hero-content {
    padding: 42px 31px;
}#mainNav .navbar-nav .nav-item .nav-link {
    color: #fff!important;
    background: transparent;
}}
</style>

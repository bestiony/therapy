<style>
@import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap');

*{font-family: 'Tajawal', sans-serif!important;}
    :root {
        --white-color: #fff;
        /* --theme-color: #5e3fd7; */
        --light-purple: rgba(117, 79, 254, 0.1);
        /* --heading-color: #040453; */
        --orange-color: #FC8068;
        --orange-deep: #FF3C16;
        /* --para-color: #52526C; */
        --gray-color: #767588;
        --gray-color2: #929292;
        --disable-color: #B5B4BD;
        --color-green: #45C881;
        --color-light-green: rgba(69, 200, 129, 0.22);
        --color-yellow: #FFC014;
        --light-bg: #F9F8F6;
        --page-bg: #F8F6F0;
        /* --plyr-color-main: #5e3fd7; */
        --border-color: rgba(0, 0, 0, 0.07);
        --border-color2: rgba(0, 0, 0, 0.09);
        /* --font-jost: 'Jost', sans-serif; */

        @if(get_option('app_font_design_type') == 2)
            @if(empty(get_option('app_font_link')))
               --body-font-family: 'Jost', sans-serif;
            @else
               --body-font-family: {!! get_option('app_font_family') !!};
            @endif
        @else
            --body-font-family: 'Jost', sans-serif;
        @endif

        @if(get_option('app_color_design_type') == 2)
            /* New Assigned */
            --theme-color: {{ empty(get_option('app_theme_color')) ? '#5e3fd7' : get_option('app_theme_color') }};
            --plyr-color-main: {{ empty(get_option('app_theme_color')) ? '#5e3fd7' : get_option('app_theme_color') }}; /* --theme-color value set here*/
            --heading-color: {{ empty(get_option('app_heading_color')) ? '#040453' : get_option('app_heading_color') }};
            --body-font-color: {{ empty(get_option('app_body_font_color')) ? '#52526C' : get_option('app_body_font_color') }};
            --navbar-bg-color: {{ empty(get_option('app_navbar_background_color')) ? '#030060' : get_option('app_navbar_background_color') }};
            --gradient-banner-bg: @if(empty(get_option('app_gradiant_banner_color'))) linear-gradient(130deg, #ad90c1 0%, rgb(3, 0, 84) 100%), linear-gradient(130deg, #09007b 0%, rgba(15, 0, 66, 0) 30%),
            linear-gradient(129.96deg, rgb(255, 47, 47) 10.43%, rgb(0, 4, 96) 92.78%), radial-gradient(100% 246.94% at 100% 0%, rgb(255, 255, 255) 0%, rgba(37, 0, 66, 0.8) 100%),
            linear-gradient(121.18deg, rgb(20, 0, 255) 0.45%, rgb(27, 0, 62) 100%), linear-gradient(154.03deg, rgb(206, 0, 0) 0%, rgb(255, 0, 61) 74.04%),
            linear-gradient(341.1deg, rgb(178, 91, 186) 7.52%, rgb(16, 0, 119) 77.98%), linear-gradient(222.34deg, rgb(169, 0, 0) 12.99%, rgb(0, 255, 224) 87.21%),
            linear-gradient(150.76deg, rgb(183, 213, 0) 15.35%, rgb(34, 0, 170) 89.57%) @else {!! get_option('app_gradiant_banner_color') !!} @endif;
             --overlay-bg-opacity: {{ empty(get_option('app_gradiant_overlay_background_color_opacity')) ? 0 : get_option('app_gradiant_overlay_background_color_opacity') }};
            --gradient-overlay-bg: rgba(0,0,0,{{ empty(get_option('app_gradiant_overlay_background_color_opacity')) ? 0 : get_option('app_gradiant_overlay_background_color_opacity') }})!important;
            --footer-gradient-bg: @if(empty(get_option('app_gradiant_footer_color'))) linear-gradient(130deg, #ad90c1 0%, rgb(3, 0, 84) 100%), linear-gradient(130deg, #09007b 0%, rgba(15, 0, 66, 0) 30%),
            linear-gradient(129.96deg, rgb(255, 47, 47) 10.43%, rgb(0, 4, 96) 92.78%), radial-gradient(100% 246.94% at 100% 0%, rgb(255, 255, 255) 0%, rgba(37, 0, 66, 0.8) 100%),
            linear-gradient(121.18deg, rgb(20, 0, 255) 0.45%, rgb(27, 0, 62) 100%), linear-gradient(154.03deg, rgb(206, 0, 0) 0%, rgb(255, 0, 61) 74.04%),
            linear-gradient(341.1deg, rgb(178, 91, 186) 7.52%, rgb(16, 0, 119) 77.98%), linear-gradient(222.34deg, rgb(169, 0, 0) 12.99%, rgb(0, 255, 224) 87.21%),
            linear-gradient(150.76deg, rgb(183, 213, 0) 15.35%, rgb(34, 0, 170) 89.57%) @else {!! get_option('app_gradiant_footer_color') !!} @endif;
        @else
            --theme-color: #5e3fd7;
            --plyr-color-main: #5e3fd7; /* --theme-color value set here*/
            --heading-color: #040453;
            --body-font-color: #52526C;
            --navbar-bg-color: #030060;
            --gradient-banner-bg: linear-gradient(130deg, #ad90c1 0%, rgb(3, 0, 84) 100%), linear-gradient(130deg, #09007b 0%, rgba(15, 0, 66, 0) 30%),
            linear-gradient(129.96deg, rgb(255, 47, 47) 10.43%, rgb(0, 4, 96) 92.78%), radial-gradient(100% 246.94% at 100% 0%, rgb(255, 255, 255) 0%, rgba(37, 0, 66, 0.8) 100%),
            linear-gradient(121.18deg, rgb(20, 0, 255) 0.45%, rgb(27, 0, 62) 100%), linear-gradient(154.03deg, rgb(206, 0, 0) 0%, rgb(255, 0, 61) 74.04%),
            linear-gradient(341.1deg, rgb(178, 91, 186) 7.52%, rgb(16, 0, 119) 77.98%), linear-gradient(222.34deg, rgb(169, 0, 0) 12.99%, rgb(0, 255, 224) 87.21%),
            linear-gradient(150.76deg, rgb(183, 213, 0) 15.35%, rgb(34, 0, 170) 89.57%);

            --gradient-overlay-bg: linear-gradient(180deg, rgba(0, 0, 61, 0.27) 0%, rgba(1, 1, 52, 0.9) 100%);

            --footer-gradient-bg: linear-gradient(130deg, #ad90c1 0%, rgb(3, 0, 84) 100%), linear-gradient(130deg, #09007b 0%, rgba(15, 0, 66, 0) 30%),
            linear-gradient(129.96deg, rgb(255, 47, 47) 10.43%, rgb(0, 4, 96) 92.78%), radial-gradient(100% 246.94% at 100% 0%, rgb(255, 255, 255) 0%, rgba(37, 0, 66, 0.8) 100%),
            linear-gradient(121.18deg, rgb(20, 0, 255) 0.45%, rgb(27, 0, 62) 100%), linear-gradient(154.03deg, rgb(206, 0, 0) 0%, rgb(255, 0, 61) 74.04%),
            linear-gradient(341.1deg, rgb(178, 91, 186) 7.52%, rgb(16, 0, 119) 77.98%), linear-gradient(222.34deg, rgb(169, 0, 0) 12.99%, rgb(0, 255, 224) 87.21%),
            linear-gradient(150.76deg, rgb(183, 213, 0) 15.35%, rgb(34, 0, 170) 89.57%);
        @endif
}
#mainNav .navbar-brand img{width:220px;}
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
}.hero-heading {
    font-weight: 300;
    font-size: 54px;}
   .direction-rtl .hero-content {
    margin-right: 20%;
    float: left;}
    .hidden-lg video#video_background,.hidden-lg.filter-box-short-icon.color-gray.font-15 ,.search-instructor-award-img img {
    display: none;
}.course-rating.search-instructor-rating.w-100.mb-15.d-inline-flex.align-items-center.justify-content-center {
    display: none!important;
}.fa, .far, .fas {
    font-family: "Font Awesome 5 Free"!important;
}.wishlist-add-to-cart-btn button {
    color: #aa53d2;
    font-size: 30px!important;
    padding: 7px 14px!important;
}.search-instructor-new-price.font-medium.mx-1 {
    color: #fff;
    text-align: center;
    border: 1px solid #e3e1e1;
    margin: auto;
    background: #7108c1;
    padding: 6px;margin-bottom:5px;
    border-radius: 9px;
}.instructor-designation {
    font-size: 17px;
    line-height: 100%;
    color: #7108c1;
    margin-bottom: 15px;
}.text-bg-primary {
    color: #7108c1!important;
    background-color: #fff!important;
    border: 1px solid;
}p.card-text.instructor-designation.font-medium.m-4 {
    margin: 11px;
}
    @media only screen and (max-width: 779px) {.hero-heading {
    font-weight: 300;
    font-size: 34px;}
video#video_background{display:none;}
.hero-content {
    padding: 42px 31px;
}#mainNav .navbar-nav .nav-item .nav-link {
    color: #fff!important;
    background: transparent;
}.direction-rtl .hero-content {
    margin-right: 0;
    float: left;
}.hidden-lg video#video_background {
    display: block;
}.hidden-lg.filter-box-short-icon.color-gray.font-15 {
    display: inline-block;
    border: 1px solid #e6e3e3;
    border-radius: 3px;
    padding: 9px 19px;
}}
.instructor-social.mt-20,.search-instructor-award-img,.course-rating.search-instructor-rating.w-100.mb-15.d-inline-flex.align-items-center.justify-content-center{display:none;}
.achievement-area,section.insturctor-search-map-area {
    display: none;
}.search-instructor-price.align-items-center.mb-15 {
    height: 70px;
}p.card-text.instructor-designation.font-medium.m-4 {
    margin: 11px!important;
}.card-title {
    margin-bottom: 0;
}.search-instructor-price.align-items-center.mb-4 {
    height: 50px;margin-bottom:0!important;
    overflow: hidden;
}.m-4 {
    margin: 0.5rem!important;
}
li.nav-item.dropdown.menu-round-btn.menu-language-btn.dropdown-top-space {
    display: block;
}.direction-rtl *{letter-spacing:0!important;}
.hidden-lg{display:none;}
#mainNav .navbar-brand {
    position: relative;
    display: flex;
    align-items: center;
    padding: 0;
    margin-right: 0;
}.about-instructor-box p{width:auto;}.instructor-support-item p {
    font-size: 22px;
    margin: 20px 0 22px;
}
@media only screen and (max-width: 779px){
    .hidden-lg{display:block;}
    .isLoginMenu .menu-notification-btn,li.nav-item.dropdown.menu-round-btn.menu-language-btn.dropdown-top-space{display:inline-block;}
    .search-instructor-price.align-items-center.mb-4 {
    height: 73px;
    overflow: hidden;
}
.theme-button1, .theme-button2, .btn-violet-transparent {
    color: var(--white-color)!important;
    background-color: var(--theme-color);
    border: 2px solid var(--theme-color);
    display: inline-flex;
    align-items: center;
    cursor: pointer;
    outline: none;
    /* text-transform: capitalize; */
    z-index: 99;
    padding: 3.5px 4px!important;
    font-size: 15px!important;
    justify-content: center;
    border-radius: 4px;
    font-weight: 600!important;
}

.hero-area .section-overlay {
    min-height: 651px;
    padding: 0px 0;
}.fixed-nav li{display:inline-block;position:relative;} .isLoginMenu .menu-user-btn {
    display: inline;
}.hero-content {
    padding: 42px 3px;
}.menu-language-btn > a {
    margin-right: 0;
   
}.menu-round-btn > a {
    margin-right: 0;
}
.hidden-lg video#video_background {
    display: block;
    float: right;
}#mainNav .navbar-brand img {
    width: 120px;
}#mainNav .navbar-brand img {
    width: 120px;
}h5.card-title.course-title {
    font-size: 14px;
}#mainNav.sticky {
    position: relative;}.sign-up-bottom-img img{display:none;}.col-xs-3 {
    max-width: 25%;
}.direction-rtl .menu-round-btn > a {
    margin-left: 0;
}.text-bg-primary {
    font-size: 14px;}.page-banner-header.blank-page-banner-header.gradient-bg.position-relative {
    margin-bottom: 30px;
}.hidden-lg video#video_background {
    display: block;
    position: absolute;
    float: right;
    top: 33px;
    right: -25px;
}.hero-content {
    float: left;
    padding-right: 100px;
}.hero-heading {
    font-weight: 300;
    font-size: 19px;
}.search-instructor-price.align-items-center.mb-4 {
    margin-bottom: 0!important;
}.main-middle-text {
    margin-right: 0;
}.hero-area .section-overlay {
    min-height: 292px;
    padding: 0px 0;
}.col-sm-6.col-md-6.col-lg-6.col-xl-4.mt-0.mb-25 {
    max-width: 49%;
}}.payment-method-card-box {
    border: 1px solid #681bbc;
    border-radius: 4px;
    padding: 20px!important;
}
</style>

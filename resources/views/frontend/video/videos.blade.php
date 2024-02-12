@extends('frontend.layouts.app')

@section('content')

<div class="bg-page">
<!-- Page Header Start -->
<header class="page-banner-header gradient-bg position-relative">
    <div class="section-overlay">
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12">
                    <div class="page-banner-content text-center">
                        <h3 class="page-banner-heading text-white pb-15">podcast</h3>

                        <!-- Breadcrumb Start-->
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb justify-content-center">
                                <li class="breadcrumb-item font-14"><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                                <li class="breadcrumb-item font-14 active" aria-current="page">podcast</li>
                            </ol>
                        </nav>
                        <!-- Breadcrumb End-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- Page Header End -->
<style>
 .blog-sidebar-categories li {
    border-radius: 33px;
    margin: 9px;
    color: #fff;
    padding: 33px;
    height: 200px;
    width: 30%;
    position: relative;
    display: inline-grid;
}.blog-sidebar-categories li a {
    font-size: 22px;
    color: #fff;
    display: block;
    margin-bottom: 0;
    margin-left: 0;
    margin: 18% auto;
}


@media only screen and (max-width: 575.98px){
.blog-sidebar-categories li {
    border-radius: 33px;
    margin: 9px;
    color: #fff;
    padding: 33px;
    height: 200px;
    width: 100%;
    position: relative;
    display: inline-grid;
}}
</style>
<!-- Course Single Details Area Start -->
<section class="blog-page-area section-t-space">
    <div class="container">
        <div class="row">
             <div class="blog-sidebar-box">
                        <h6 class="blog-sidebar-box-title">{{ __('Categories') }}</h6>
                        <ul class="blog-sidebar-categories">
                            @foreach($videoCategories as $videoCategory)
                            <li class="gradient-bg"><a href="{{ route('categoryVideos', $videoCategory->slug) }}" class="font-15">{{ __($videoCategory->name) }} ({{ $videoCategory->active_Videos_count }})</a></li>
                            @endforeach
                        </ul>
                    </div>
            <div class="col-12 col-md-7 col-lg-8" >

                <div class="blog-page-left-content row" >

                    @forelse($videos as $video)
                    <!-- Blog Item Start -->
                    <div class="blog-item col-md-4">

                        <div class="blog-item-img-wrap overflow-hidden position-relative">
                            <a href="{{ route('video-details', $video->slug) }}"><img src="{{ getImageFile($video->image_path) }}" alt="img" class="img-fluid"></a>
                            <div class="blog-item-tag position-absolute font-12 font-semi-bold text-white bg-hover radius-3">{{ __(@$video->category->name) }}</div>
                        </div>

                        <div class="blog-item-bottom-part">
                            <h3 class="card-title blog-title"><a href="{{ route('blog-details', $video->slug) }}">{{ __($video->title) }}</a></h3>
                            <p class="blog-author-name-publish-date font-13 font-medium color-gray">By: {{ $video->user->name }} / {{ $video->created_at->format(' j  M, Y')  }}</p>
                            <p class="card-text blog-content">{!!  Str::limit($video->details, 200) !!}</p>

                            <div class="blog-read-more-btn">
                                <a href="{{ route('blog-details', $video->slug) }}" class="theme-btn font-15 ps-0 font-medium color-hover">{{ __('Read More') }} <i data-feather="arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <!-- Blog Item End -->
                    @empty
                        <div class="no-course-found text-center">
                            <img src="{{ asset('frontend/assets/img/empty-data-img.png') }}" alt="img" class="img-fluid">
                            <h5 class="mt-3">{{ __('Video Not Found') }}</h5>
                        </div>
                    @endforelse
                    <div class="col-12">
                    <!-- Pagination Start -->
                    @if(@$videos->hasPages())
                        {{ @$videos->links('frontend.paginate.paginate') }}
                    @endif
                    <!-- Pagination End -->
                    </div>

                </div>

            </div>
            
            
            <div class="col-12 col-md-5 col-lg-4">
                <div class="blog-page-right-content bg-white">

                    <div class="blog-sidebar-box">
                        <form class="blog-sidebar-search-box position-relative">
                            <div class="input-group">
                                <input class="form-control border-0 searchBlog" type="search" placeholder="{{ __('Search...') }}">
                                <button class="bg-transparent border-0"><span class="iconify" data-icon="akar-icons:search"></span></button>
                            </div>

                            <!-- Search Bar Suggestion Box Start -->
                            <div class="search-bar-suggestion-box searchBlogBox d-none custom-scrollbar">
                                <ul class="appendBlogSearchList">

                                </ul>
                            </div>
                            <!-- Search Bar Suggestion Box End -->

                        </form>
                    </div>

                    <div class="blog-sidebar-box">
                        <h6 class="blog-sidebar-box-title">{{ __('Recent Videos') }}</h6>
                        <ul class="popular-posts">
                            @foreach($recentVideos as $recentVideo)
                            <li>
                                <div class="sidebar-blog-item d-flex">
                                    <div class="flex-shrink-0">
                                        <div class="sidebar-blog-item-img-wrap overflow-hidden">
                                            <a href="{{ route('blog-details', $recentVideo->slug) }}"><img src="{{ getImageFile($recentVideo->image_path) }}" alt="img" class="img-fluid"></a>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 {{selectedLanguage()->rtl == 1 ? 'me-3' : 'ms-3' }}">
                                        <h6 class="sidebar-blog-item-title"><a href="{{ route('video-details', $recentVideo->slug) }}">{{ __(@$recentVideo->title) }}</a></h6>
                                        <p class="blog-author-name-publish-date font-12 font-medium color-gray mb-0">{{ @$recentVideo->created_at->format(' j  M, Y')  }}</p>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="blog-sidebar-box">
                        <h6 class="blog-sidebar-box-title">{{ __('Categories') }}</h6>
                        <ul class="blog-sidebar-categories">
                            @foreach($videoCategories as $videoCategory)
                            <li><a href="{{ route('categoryVideos', $videoCategory->slug) }}" class="font-15">{{ __($videoCategory->name) }} ({{ $videoCategory->active_Videos_count }})</a></li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="blog-sidebar-box">
                        <h6 class="blog-sidebar-box-title">{{ __('Tags') }}</h6>
                        <ul class="blog-sidebar-tags">
                            @foreach($tags as $tag)
                            <li><a href="#">{{ $tag->name }}</a></li>
                            @endforeach
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
<!-- Course Single Details Area End -->

</div>

<input type="hidden" class="searchBlogRoute" value="{{ route('search-video.list') }}">

@endsection

@push('script')
    <!-- Start:: Blog Search  -->
    <script src="{{ asset('frontend/assets/js/custom/search-blog-list.js') }}"></script>
    <!-- End:: Blog Search  -->
@endpush

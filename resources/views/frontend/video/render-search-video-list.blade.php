@forelse(@$videos as $video)
    <li class="search-bar-result-item">
        <a href="{{ route('video-details', $video->slug) }}">
            <img src="{{ getImageFile($video->image_path) }}" alt="img">
            <span>{{ Str::limit($video->title, 30) }}</span>
        </a>
    </li>
@empty
    <li class="search-bar-result-item no-search-result-found">{{ __('No Data Found') }}</li>
@endforelse





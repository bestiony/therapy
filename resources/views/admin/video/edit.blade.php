@extends('layouts.admin')

@section('content')
    <!-- Page content area start -->
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb__content">
                        <div class="breadcrumb__content__left">
                            <div class="breadcrumb__title">
                                <h2>{{__('All Video')}}</h2>
                            </div>
                        </div>
                        <div class="breadcrumb__content__right">
                            <nav aria-label="breadcrumb">
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Dashboard')}}</a></li>
                                    <li class="breadcrumb-item"><a href="{{route('video.index')}}">{{__('All Video')}}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{__('Edit Video')}}</li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="customers__area bg-style mb-30">
                        <div class="item-title d-flex justify-content-between">
                            <h2>{{__('Edit Video')}}</h2>
                        </div>
                        <form action="{{route('video.update', [$video->uuid])}}" method="post" class="form-horizontal" enctype="multipart/form-data">
                            @csrf

                            <div class="input__group mb-25">
                                <label>{{__('Title')}} <span class="text-danger">*</span></label>
                                <input type="text" name="title" value="{{$video->title}}" placeholder="{{__('Title')}}" class="form-control slugable"  onkeyup="slugable()">
                                @if ($errors->has('title'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('title') }}</span>
                                @endif
                            </div>




                            <div class="input__group mb-25">
                                <label>{{ __('Video') }} <span class="text-danger">*</span></label>
                                <input type="text" name="video" value="{{$video->video}}"
                                       placeholder="{{ __('Video') }}" class="form-control slugable" onkeyup="slugable()">
                                @if ($errors->has('video'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                        {{ $errors->first('video') }}</span>
                                @endif
                            </div>

                            <div class="input__group mb-25">
                                <label>{{__('Slug')}} <span class="text-danger">*</span></label>
                                <input type="text" name="slug" value="{{$video->slug}}" placeholder="{{__('Slug')}}" class="form-control slug" onkeyup="getMyself()">
                                @if ($errors->has('slug'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('slug') }}</span>
                                @endif
                            </div>


                            <div class="input__group mb-25">
                                <label for="video_category_id"> {{ __('Video category') }} </label>
                                <select name="video_category_id" id="video_category_id">
                                    <option value="">--{{ __('Select Option') }}--</option>
                                    @foreach($videoCategories as $videoCategory)
                                        <option value="{{ $videoCategory->id }}" @if($videoCategory->id = $video->video_category_id) selected @endif>{{ $videoCategory->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input__group mb-25">
                                <label>Status</label>
                                <select name="status" id="status">
                                    <option value="">--{{ __('Select Option') }}--</option>
                                    <option value="1" @if($video->status == 1) selected @endif>{{ __('Published') }}</option>
                                    <option value="0" @if($video->status != 1) selected @endif>{{ __('Unpublished') }}</option>
                                </select>
                            </div>
                            <div class="input__group mb-25">
                                <label for="tag_ids"> {{ __('Tag') }} </label>
                                <select name="tag_ids[]" multiple id="tag_ids" class="multiple-basic-single form-control">
                                    @foreach($tags as $tag)
                                        <option value="{{ $tag->id }}" {{ in_array($tag->id, @$video->tags->pluck('tag_id')->toArray() ?? []) ? 'selected' : null }}>{{ $tag->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="input__group mb-25">
                                <label>{{__('Details')}} <span class="text-danger">*</span></label>
                                <!--<textarea name="details" id="summernote">{{$video->details}}</textarea>-->

                                <textarea name="details" id="summernote">{!! $video->details !!}</textarea>


                                @if ($errors->has('details'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('details') }}</span>
                                @endif

                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="upload-img-box mb-25">
                                        @if($video->image)
                                            <img src="{{asset($video->image_path)}}" alt="img">
                                        @else
                                            <img src="" alt="No img">
                                        @endif
                                        <input type="file" name="image" id="image" accept="image/*" onchange="previewFile(this)">
                                        <div class="upload-img-box-icon">
                                            <i class="fa fa-camera"></i>
                                            <p class="m-0">{{__('Image')}}</p>
                                        </div>
                                    </div>
                                    @if ($errors->has('image'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('image') }}</span>
                                    @endif
                                    <p>{{ __('Accepted Files') }}: JPEG, JPG, PNG <br> {{ __('Recommend Size') }}: 870 x 500 (1MB)</p>
                                </div>
                            </div>
                            <div class="row">
                                <hr>
                                <h2>Meta Data</h2>
                                <hr>
                                <div class="input__group mb-25">
                                    <label>{{ __('Description') }} <span class="text-danger">*</span></label>
                                    <textarea name="description" id="description">{{ $video->description }}</textarea>

                                    @if ($errors->has('description'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                            {{ $errors->first('description') }}</span>
                                    @endif

                                </div>
                                <div class="input__group mb-25">
                                    <label>{{ __('Keywords') }} <span class="text-danger">*</span></label>
                                    <textarea name="keywords" id="keywords">{{$video->keywords }}</textarea>

                                    @if ($errors->has('keywords'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                            {{ $errors->first('keywords') }}</span>
                                    @endif

                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12 text-right">
                                    @updateButton
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- Page content area end -->
@endsection

@push('style')
    <link rel="stylesheet" href="{{asset('admin/css/custom/image-preview.css')}}">

    <!-- Summernote CSS - CDN Link -->
    <link href="{{ asset('common/css/summernote/summernote.min.css') }}" rel="stylesheet">
    <link href="{{ asset('common/css/summernote/summernote-lite.min.css') }}" rel="stylesheet">
    <!-- //Summernote CSS - CDN Link -->
@endpush

@push('script')
    <script src="{{asset('admin/js/custom/image-preview.js')}}"></script>
    <script src="{{asset('admin/js/custom/slug.js')}}"></script>
    <script src="{{asset('admin/js/custom/form-editor.js')}}"></script>

    <!-- Summernote JS - CDN Link -->
    <script src="{{ asset('common/js/summernote/summernote-lite.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            /*$("#summernote").summernote({dialogsInBody: true});*/

            $("#summernote").summernote();

            $('.dropdown-toggle').dropdown();
        });
    </script>
    <!-- //Summernote JS - CDN Link -->
@endpush

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
                                <h2>{{ __('Version Update') }}</h2>
                            </div>
                        </div>
                        <div class="breadcrumb__content__right">
                            <nav aria-label="breadcrumb">
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ __(@$title) }}</li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="customers__area bg-style mb-30">
                        <div class="item-title d-flex justify-content-between">
                            <h2>{{ __(@$title) }}</h2>
                        </div>

                        <form action="{{ route('settings.file-version-update-store') }}" enctype="multipart/form-data" class="form-horizontal" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input__group mb-25">
                                        <label for="update_file">{{ __('Update File') }}</label>
                                        <input type="file" name="update_file" id="update_file" required>
                                    </div>
                                    @if ($errors->has('update_file'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('update_file') }}</span>
                                @endif
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-success">Upload</button>
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
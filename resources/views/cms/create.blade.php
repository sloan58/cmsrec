@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'cms'
])

@section('content')
    <div class="content">
        @include('components.flash-message')
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <form class="col-md-12" action="{{ route('cms.store') }}" method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <h5 class="title">{{ __('Create CMS') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <label class="col-md-3 col-form-label">{{ __('Name') }}</label>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                                    </div>
                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback text-left" style="display: block;" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-3 col-form-label">{{ __('Host') }}</label>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <input type="text" name="host" class="form-control" value="{{ old('host') }}" required>
                                    </div>
                                    @if ($errors->has('host'))
                                        <span class="invalid-feedback text-left" style="display: block;" role="alert">
                                            <strong>{{ $errors->first('host') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-3 col-form-label">{{ __('Username') }}</label>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <input type="text" name="username" class="form-control" value="{{ old('username') }}" required>
                                    </div>
                                    @if ($errors->has('username'))
                                        <span class="invalid-feedback text-left" style="display: block;" role="alert">
                                            <strong>{{ $errors->first('username') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-3 col-form-label">{{ __('Password') }}</label>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <input type="password" name="password" class="form-control">
                                    </div>
                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback text-left" style="display: block;" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-footer ">
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <a href="{{ route('cms.index') }}" class="btn btn-default btn-round">{{ __('Back') }}</a>
                                    <button type="submit" class="btn btn-info btn-round">{{ __('Save Changes') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

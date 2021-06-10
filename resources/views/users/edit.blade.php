@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'users'
])

@section('content')
    <div class="content">
        @include('components.flash-message')
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <form class="col-md-12" action="{{ route('user.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card">
                        <div class="card-header">
                            <h5 class="title">{{ __('Edit User') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <label class="col-md-3 col-form-label">{{ __('Username') }}</label>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                                    </div>
                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback text-left" style="display: block;" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-3 col-form-label">{{ __('Email') }}</label>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <input type="text" name="email" class="form-control" value="{{ $user->email }}" required>
                                    </div>
                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback text-left" style="display: block;" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-3 col-form-label">{{ __('New Password') }}</label>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <input type="password" name="password" class="form-control" placeholder="Password">
                                    </div>
                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback text-left" style="display: block;" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-3 col-form-label">{{ __('Password Confirmation') }}</label>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <input type="password" name="password_confirmation" class="form-control" placeholder="Password Confirmation">
                                    </div>
                                    @if ($errors->has('password_confirmation'))
                                        <span class="invalid-feedback text-left" style="display: block;" role="alert">
                                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-footer ">
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <a href="{{ route('user.index') }}" class="btn btn-default btn-round">{{ __('Back') }}</a>
                                    <button type="submit" class="btn btn-info btn-round">{{ __('Save Changes') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-4 text-center">
                <div class="card">
                    <div class="card-header">
                        <h5 class="title">{{ __('CMS Recordings') }}</h5>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">Space Name</th>
                                <th scope="col">Space ID</th>
                                <th scope="col">Recordings</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($user->cmsCoSpaces as $coSpace)
                            <tr>
                                <th>{{ $coSpace->name }}</th>
                                <td>{{ $coSpace->space_id}}</td>
                                <td>{{ $coSpace->cmsRecordings()->count() }}</td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

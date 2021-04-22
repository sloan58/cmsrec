@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'settings'
])

@section('content')
    <div class="content">
        @include('components.flash-message')
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <form class="col-md-12" action="{{ route('ldap.settings.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card">
                        <div class="card-header">
                            <h5 class="title">{{ __('LDAP Settings') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <label class="col-md-3 col-form-label">{{ __('Name') }}</label>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <input type="text" name="name" class="form-control" value="{{ app(\App\Settings\LdapSettings::class)->name }}" required>
                                    </div>
                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback text-left" style="display: block;" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-3 col-form-label">{{ __('Hostname or IP Address') }}</label>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <input type="text" name="host" class="form-control" value="{{ app(\App\Settings\LdapSettings::class)->host }}" required>
                                    </div>
                                    @if ($errors->has('host'))
                                        <span class="invalid-feedback text-left" style="display: block;" role="alert">
                                            <strong>{{ $errors->first('host') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-3 col-form-label">{{ __('Search Base') }}</label>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <input type="text" name="searchBase" class="form-control" value="{{ app(\App\Settings\LdapSettings::class)->searchBase }}" required>
                                    </div>
                                    @if ($errors->has('searchBase'))
                                        <span class="invalid-feedback text-left" style="display: block;" role="alert">
                                            <strong>{{ $errors->first('searchBase') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-3 col-form-label">{{ __('Bind DN') }}</label>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <input type="text" name="bindDN" class="form-control" value="{{ app(\App\Settings\LdapSettings::class)->bindDN }}" required>
                                    </div>
                                    @if ($errors->has('bindDN'))
                                        <span class="invalid-feedback text-left" style="display: block;" role="alert">
                                            <strong>{{ $errors->first('bindDN') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-3 col-form-label">{{ __('Password') }}</label>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <input type="password" name="password" class="form-control" value="{{ app(\App\Settings\LdapSettings::class)->password }}" required>
                                    </div>
                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback text-left" style="display: block;" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-3 col-form-label">{{ __('Filter') }}</label>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <input type="text" name="filter" class="form-control" value="{{ app(\App\Settings\LdapSettings::class)->filter }}">
                                    </div>
                                    @if ($errors->has('filter'))
                                        <span class="invalid-feedback text-left" style="display: block;" role="alert">
                                            <strong>{{ $errors->first('filter') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-footer ">
                            <div class="row">
                                <div class="col-md-12 text-center">
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

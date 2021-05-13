<div class="row justify-content-center">
    <div class="col-md-8 text-center">
        <form class="col-md-12" action="{{ route('nfs.settings.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-header">
                    <h5 class="title">{{ __('NFS Settings') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <label class="col-md-3 col-form-label">{{ __('Hostname or IP Address') }}</label>
                        <div class="col-md-9">
                            <div class="form-group">
                                <input type="text" name="host" class="form-control" value="{{ app(\App\Settings\NfsSettings::class)->host }}" required>
                            </div>
                            @if ($errors->has('host'))
                                <span class="invalid-feedback text-left" style="display: block;" role="alert">
                                            <strong>{{ $errors->first('host') }}</strong>
                                        </span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-3 col-form-label">{{ __('Path (Absolute)') }}</label>
                        <div class="col-md-9">
                            <div class="form-group">
                                <input type="text" name="path" class="form-control" value="{{ app(\App\Settings\NfsSettings::class)->path }}" required>
                            </div>
                            @if ($errors->has('path'))
                                <span class="invalid-feedback text-left" style="display: block;" role="alert">
                                            <strong>{{ $errors->first('path') }}</strong>
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

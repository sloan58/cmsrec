<div class="row justify-content-center">
    <div class="col-12 text-center">
        <div class="card">
            <div class="card-header">
                <h5 class="title">{{ __('NFS Settings') }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <label class="col-md-3 col-form-label">{{ __('Hostname or IP Address') }}</label>
                    <div class="col-md-9">
                        <div class="form-group">
                            <input wire:model="host" type="text" name="host" class="form-control" value="{{ app(\App\Settings\NfsSettings::class)->host }}" required>
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
                            <input wire:model="path" type="text" name="path" class="form-control" value="{{ app(\App\Settings\NfsSettings::class)->path }}" required>
                        </div>
                        @if ($errors->has('path'))
                            <span class="invalid-feedback text-left" style="display: block;" role="alert">
                                    <strong>{{ $errors->first('path') }}</strong>
                                </span>
                        @endif
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-10 offset-1">
                        <div class="table-responsive">
                            <table class="table table-dark text-success">
                                <thead>
                                <tr>
                                    @foreach(app(\App\Settings\NfsSettings::class)->mnt_view as $stat => $val)
                                        <th>{{ $stat }}</th>
                                    @endforeach
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    @foreach(app(\App\Settings\NfsSettings::class)->mnt_view as $stat => $val)
                                        <th>{{ $val }}</th>
                                    @endforeach
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer ">
                <div class="row">
                    <div class="col-md-12 text-center">
                        @if(app(\App\Settings\NfsSettings::class)->host)
                            <button wire:click="disconnect" type="submit" class="btn btn-warning btn-round">{{ __('Disconnect') }}</button>
                        @else
                            <button wire:click="connect" type="submit" class="btn btn-info btn-round">{{ __('Save Changes') }}</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

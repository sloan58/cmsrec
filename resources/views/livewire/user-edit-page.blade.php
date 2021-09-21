<div class="content">
    @include('components.flash-message')
    <div class="row justify-content-center">
        <div class="col-12 col-xl-8 offset-xl-1 text-center">
            <form action="{{ route('user.update', $user->id) }}" method="POST">
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
        @if(!$addingCoSpaceToUser)
        <div class="col-12 col-xl-8 offset-xl-1 text-center">
            <div class="card">
                <div class="card-header">
                    <div class="row mx-auto">
                        <div class="col-12">
                            <h5 class="title">{{ __('CMS CoSpaces') }}</h5>
                        </div>
                    </div>
                    <div class="row mx-auto">
                        <div class="col-12">
                            <button wire:click="$toggle('addingCoSpaceToUser')" type="button" class="btn btn-success float-right px-3"><i class="fa fa-crop mr-2"></i> Associate CMS CoSpace</button>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">Space Name</th>
                            <th scope="col">Space ID</th>
                            <th scope="col">Recordings</th>
                            <th scope="col">Administratively Assigned</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($currentAssignedCoSpaces as $coSpace)
                            <tr>
                                <td>{{ $coSpace->name }}</td>
                                <td>{{ $coSpace->space_id}}</td>
                                <td>{{ $coSpace->cmsRecordings()->count() }}</td>
                                @if($coSpace->pivot->admin_assigned)
                                <td>
                                    <button
                                        wire:click="removeCoSpaceFromUser('{{ $coSpace->id }}')"
                                        type="button"
                                        class="btn btn-danger btn-sm"
                                    >
                                        <i class="fa fa-times"></i> Remove
                                    </button>
                                </td>
                                @else
                                <td></td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
        @if($addingCoSpaceToUser)
            <div class="col-12 col-xl-8 offset-xl-1 text-center">
                <div class="card">
                    <div class="card-header">
                        <div class="row mx-auto">
                            <div class="col-12">
                                <h5 class="title">{{ __('Associate CMS CoSpace') }}</h5>
                            </div>
                        </div>
                        <div class="row mx-auto">
                            <div class="col-12">
                                <button wire:click="$toggle('addingCoSpaceToUser')" type="button" class="btn btn-primary float-right px-3"><i class="fa fa-times mr-2"></i> Done</button>
                            </div>
                        </div>
                        <div class="row mx-auto">
                            <div class="col-6 mx-auto">
                                <input wire:model="term" type="text" class="form-control text-center" placeholder="Search Space Names">
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">Space Name</th>
                                <th scope="col">Space ID</th>
                                <th scope="col">Recordings</th>
                                <th scope="col">Associate Space</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($availableCoSpaces as $availableCoSpace)
                                <tr>
                                    <td>{{ $availableCoSpace->name }}</td>
                                    <td>{{ $availableCoSpace->space_id}}</td>
                                    <td>{{ $availableCoSpace->cmsRecordings()->count() }}</td>
                                    <td>
                                        <button
                                            wire:click="addCoSpaceToUser('{{ $availableCoSpace->id }}')"
                                            type="button"
                                            class="btn btn-success btn-sm"
                                        >
                                            <i class="fa fa-plus"></i> Add
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row mx-auto">
                        <div class="col-12">
                            {{ $availableCoSpaces->links() }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

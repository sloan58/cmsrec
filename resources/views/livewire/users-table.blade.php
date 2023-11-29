<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4 class="card-title">Users</h4>
                <a href="{{ route('user.create') }}" class="btn btn-success btn-fab btn-icon btn-round">
                    <i class="fa fa-plus"></i>
                </a>
            </div>
            <div class="card-header d-flex justify-content-end">
                <div class="form-inline my-2 my-lg-0">
                    <input wire:model.debounce.200ms="term" class="form-control mr-sm-2" name="q" type="search" placeholder="Search" value="{{ $term }}" aria-label="Search">
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead class=" text-primary">
                        <th>
                            Name
                        </th>
                        <th>
                            Email
                        </th>
                        <th>
                            CMS CoSpaces
                        </th>
                        <th class="text-right">
                            Actions
                        </th>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>
                                    {{ $user->name }}
                                </td>
                                <td>
                                    {{ $user->email }}
                                </td>
                                <td>
                                    {{ $user->cmsCoSpaces()->count() }}
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('user.edit', $user->id) }}" class="btn btn-primary btn-fab btn-icon btn-round"><i class="fa fa-edit"></i></a>
                                    <button class="btn btn-danger btn-fab btn-icon btn-round deleteUser"
                                            data-route="{{ route('user.destroy', $user->id) }}"
                                            data-toggle="modal"
                                            data-target="#x-modal">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-12">
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

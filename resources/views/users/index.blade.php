@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'users'
])

@section('content')
    <div class="content">
        @include('components.flash-message')
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Users</h4>
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
                                <th class="text-right">
                                    Actions
                                </th>
                                </thead>
                                <tbody>
                                @forelse($users as $user)
                                <tr>
                                    <td>
                                        {{$user->name}}
                                    </td>
                                    <td>
                                        {{$user->email}}
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
                                @empty
                                @endforelse
                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col-12">
                                    {{ $users->links('vendor.pagination.bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include(
    'components.delete-modal', [
        'heading' => 'Delete User',
        'body' => "Are you sure you'd like to delete this user?"
    ])
@endsection

@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'cms'
])

@section('content')
    <div class="content">
        @include('components.flash-message')
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h4 class="card-title">CMS Servers</h4>
                        <a href="{{ route('cms.create') }}" class="btn btn-primary btn-fab btn-icon btn-round">
                            <i class="fa fa-plus"></i>
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class=" text-primary">
                                <th>
                                    Name
                                </th>
                                <th>
                                    Host
                                </th>
                                <th>
                                    Username
                                </th>
                                <th class="text-right">
                                    Actions
                                </th>
                                </thead>
                                <tbody>
                                @forelse($cmss as $cms)
                                <tr>
                                    <td>
                                        {{$cms->name}}
                                    </td>
                                    <td>
                                        {{$cms->host}}
                                    </td>
                                    <td>
                                        {{$cms->username}}
                                    </td>
                                    <td class="text-right">
                                        <a href="{{ route('cms.edit', $cms->id) }}" class="btn btn-primary btn-fab btn-icon btn-round"><i class="fa fa-edit"></i></a>
                                        <button class="btn btn-danger btn-fab btn-icon btn-round deleteUser"
                                                data-route="{{ route('cms.destroy', $cms->id) }}"
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
                                    {{ $cmss->links('vendor.pagination.bootstrap-4') }}
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
        'heading' => 'Delete CMS',
        'body' => "Are you sure you'd like to delete this server?"
    ])
@endsection

@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'cospaces'
])

@section('content')
    <div class="content">
        @include('components.flash-message')
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h4 class="card-title">CMS CoSpaces</h4>
                    </div>
                    <div class="card-header d-flex justify-content-end">
                        <form action="{{ route('user.index') }}" class="form-inline my-2 my-lg-0" method="GET">
                            <input class="form-control mr-sm-2" name="q" type="search" placeholder="Search" value="{{ $q ?? '' }}" aria-label="Search">
                            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class=" text-primary">
                                <th>
                                    Name
                                </th>
                                <th>
                                    Owner
                                </th>
                                <th>
                                    Size
                                </th>
                                <th>
                                    Recordings
                                </th>
                                <th class="text-right">
                                    Actions
                                </th>
                                </thead>
                                <tbody>
                                @foreach($coSpaces as $coSpace)
                                <tr>
                                    <td>
                                        {{ $coSpace->name }}
                                    </td>
                                    <td>
                                        {{ $coSpace->owner->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $coSpace->size() }}
                                    </td>
                                    <td>
                                        {{ count($coSpace->recordings() )}}
                                    </td>
                                    <td class="text-right">
{{--                                        <a href="{{ route('user.edit', $user->id) }}" class="btn btn-primary btn-fab btn-icon btn-round"><i class="fa fa-edit"></i></a>--}}
{{--                                        <button class="btn btn-danger btn-fab btn-icon btn-round deleteUser"--}}
{{--                                                data-route="{{ route('user.destroy', $user->id) }}"--}}
{{--                                                data-toggle="modal"--}}
{{--                                                data-target="#x-modal">--}}
{{--                                            <i class="fa fa-trash"></i>--}}
{{--                                        </button>--}}
                                    </td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
{{--                            <div class="row">--}}
{{--                                <div class="col-12">--}}
{{--                                    {{ $users->links('vendor.pagination.bootstrap-4') }}--}}
{{--                                </div>--}}
{{--                            </div>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

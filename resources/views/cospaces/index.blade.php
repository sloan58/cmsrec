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
                                        {{ $coSpace->cmsRecordings()->count() }}
                                    </td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

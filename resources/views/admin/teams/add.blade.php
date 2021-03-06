@extends('layouts.admin-left-sidebar')

@section('content')
    <div class="row no-gutters">
        <div class="col-12 p-0 p-md-4">

            @include('admin.teams.add.page_browser')

            @include('admin.teams.common.notifications')

            @include('admin.teams.add.data')

        </div> {{-- col --}}
    </div> {{-- row --}}
@endsection

@section('js')
    @include('admin.teams.add.javascript')
@endsection
@extends('layouts.app')

@section('style')
    <link href="{{ asset('css/competition/competition.css') }}" rel="stylesheet">
@endsection

@if ($group->phase->mode == 'league')
	@section('content')

		@include('competitions.partials.header')

		<div class="wrapper" style="background: #f9f9f9">
			@include('competitions.league.calendar.content')
		</div> {{-- wrapper --}}
	@endsection

	@section('breadcrumb')
		@include('competitions.league.calendar.breadcrumb')
	@endsection

	@section('bottom-fixed')
		@if (active_season()->competitions->count() > 1)
			@include('competitions.partials.bottom_fixed')
		@endif
	@endsection

	@section('modal')
	    @include('competitions.league.calendar.update_modal')
	    @include('competitions.league.calendar.match_details_modal')
	@endsection

	@section('js')
	    @include('competitions.league.calendar.javascript')
	@endsection
@endif
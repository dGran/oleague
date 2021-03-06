@extends('layouts.app')

@section('style')
    <link href="{{ asset('css/competition/competition.css') }}" rel="stylesheet">
@endsection

@section('content')
	<div class="competition-header">
		<div class="container">
			<div class="logo">
				<i class="icon-trophy"></i>
			</div>
			<div class="title">
	    		<h3>
	    			Competiciones
	    		</h3>
	    		<span class="subtitle">
	    			{{ $season->name }}
	    		</span>
			</div>
		</div>
	</div>

	<div class="wrapper">
		@include('competitions.index.content')
	</div> {{-- wrapper --}}
@endsection

@section('breadcrumb')
	@include('competitions.index.breadcrumb')
@endsection

@section('js')
    @include('competitions.index.javascript')
@endsection
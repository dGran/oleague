@extends('layouts.app')

@section('style')
    <style>
    	body {
    		background: #f9f9f9;
    	}
		.notifications-header {
			background: #353f48;
			margin-top: 55px;
			padding: .75rem .25rem;
		}
		.notifications-header h3 {
			color: #fff;
			font-size: 1.5em;
			margin: 0;
		}
		.notifications-header img {
			width: 65px;
		}
		.notifications-header .subname {
			color: #B2B2B2;
			display: block;
			font-weight: bold;
			font-size: .8em;
		}
    </style>
@endsection

@section('content')
	@include('users.notifications.header')

	<div class="container">
		<div class="row">
			<div class="col-12">
				@include('users.notifications.content')
			</div>
		</div>
	</div>

@endsection

@section('breadcrumb')
	@include('users.notifications.breadcrumb')
@endsection

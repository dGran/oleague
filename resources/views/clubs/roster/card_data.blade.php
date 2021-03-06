<div class="col-6 col-md-4 col-lg-3 col-xl-2 p-0">
	<div class="club-player-card mb-3">
		<figure class="player-img">
			<img src="{{ $player->player->getImgFormatted() }}" alt="{{$player->player->name}}">
		</figure>
		<img class="ball" src="{{ asset($player->player->getBall()) }}">
		<span class="overall-rating">
			{{ $player->player->overall_rating }}
		</span>
		<span class="position" style="border-bottom-color: {{ $player->player->getPositionColor() }}">
			{{ $player->player->position }}
		</span>
		<div class="player-name">
			<span style="">
				{{ $player->player->name }}
			</span>
		</div>
		<div class="more-data">
			<img src="{{ asset($player->player->nation_flag()) }}" width="20">
			<span>
				{{ $player->player->nation_name }}
			</span>
			<span>
				{{ $player->player->age }} años - {{ $player->player->height }} cm
			</span>
		</div>
	</div>
</div>
<div style="background: #F2F2F2; border-bottom: 1px solid #f1eff3; padding: 1rem .5rem 1rem .5rem; color: #4ea800; font-weight: 400; font-size: 28px; line-height: 1.3em;">
	<div class="container">
		Nueva negociación
	</div>
</div>
<div class="container py-3">
	<form
	    id="frmSendOffer"
	    lang="{{ app()->getLocale() }}"
	    role="form"
	    method="POST"
	    action="{{ route('market.trades.save', $participant->id) }}"
	    data-toggle="validator"
	    autocomplete="off">
	    {{ csrf_field() }}

		<div class="row">
			<div class="col-12 col-md-6 px-3">
				<div class="pb-3">
					<img src="{{ participant_of_user()->logo() }}" width="90" class="d-inline-block align-middle mr-1">
					<div class="d-inline-block align-middle">
						<strong>
							{{ participant_of_user()->name() }}
						</strong>
						<small class="d-block">
							{{ participant_of_user()->sub_name() }}
						</small>
						<small class="d-block">
							<span class="text-muted">Presupuesto:</span> {{ number_format(participant_of_user()->budget(), 2, ',', '.') }} M.
						</small>
						<small class="d-block">
							<span class="text-muted">Plantilla:</span> {{ participant_of_user()->players->count() }} jugadores
						</small>
					</div>
				</div>

				<div class="form-group">
					<label for="p1_cash">Ofrecer dinero</label>
	                <div class="input-group col-6 p-0">
	                    <div class="input-group-prepend">
	                        <span class="input-group-text">
	                            <i class="fas fa-euro-sign"></i>
	                        </span>
	                    </div>
	                    <input type="number" class="form-control" name="p1_cash" id="p1_cash" value="0" step="any" min="0" max="{{ participant_of_user()->budget() }}">
	                </div> {{-- input-group --}}
                    <small class="text-muted">Máximo {{ number_format(participant_of_user()->budget(), 2, ',', '.') }} M.</small>
				</div>

				<div class="form-group">
					<label for="p1_players">Ofrecer jugadores</label>
		            <select name="p1_players[]" id="p1_players" class="form-control selectpicker show-tick" data-size="5" title="Selecciona jugadores..." multiple>
		                @foreach (participant_of_user()->players as $player)
		                	<option title="{{ $player->player->name }}" data-content="<img class='mr-1' src='{{ $player->player->getImgFormatted() }}' width='20'><span>{{ $player->player->name }}</span><small class='text-muted'>{{ $player->player->position }}-{{ $player->player->overall_rating }}</small>" value="{{ $player->id }}" style="font-size: .8em"></option>
		                @endforeach
		            </select>
				</div>
			</div>

			<div class="col-12 col-md-6 px-3 pt-3 pt-md-0">
				<div class="pb-3">
					<img src="{{ $participant->logo() }}" width="90" class="d-inline-block align-middle mr-1">
					<div class="d-inline-block align-middle">
						<strong>
							{{ $participant->name() }}
						</strong>
						<small class="d-block">
							{{ $participant->sub_name() }}
						</small>
						<small class="d-block">
							<span class="text-muted">Presupuesto:</span> {{ number_format($participant->budget(), 2, ',', '.') }} M.
						</small>
						<small class="d-block">
							<span class="text-muted">Plantilla:</span> {{ $participant->players->count() }} jugadores
						</small>
					</div>
				</div>

				<div class="form-group">
					<label for="p1_cash">Solicitar dinero</label>
	                <div class="input-group col-6 p-0">
	                    <div class="input-group-prepend">
	                        <span class="input-group-text">
	                            <i class="fas fa-euro-sign"></i>
	                        </span>
	                    </div>
	                    <input type="number" class="form-control" name="p2_cash" id="p2_cash" value="0" step="any" min="0" max="{{ $participant->budget() }}">
	                </div> {{-- input-group --}}
                    <small class="text-muted">Máximo {{ number_format($participant->budget(), 2, ',', '.') }} M.</small>
				</div>

				<div class="form-group">
					<label for="p2_players">Solicitar jugadores</label>
		            <select name="p2_players[]" id="p2_players" class="form-control selectpicker show-tick" data-size="5" title="Selecciona jugadores..." multiple>
		                @foreach ($participant->players as $player)
		                	<option title="{{ $player->player->name }}" data-content="<img class='mr-1' src='{{ $player->player->getImgFormatted() }}' width='20'><span>{{ $player->player->name }}</span><small class='text-muted'>{{ $player->player->position }}-{{ $player->player->overall_rating }}</small>" value="{{ $player->id }}" style="font-size: .8em"></option>
		                @endforeach
		            </select>
				</div>
			</div>
		</div> {{-- row --}}

		<div class="row">
			<div class="col-12 pt-4 pb-2 text-center">
				<input type="submit" id="btn_submit" value="Enviar oferta" class="btn btn-primary">
			</div>
		</div>

	</form>

</div> {{-- container --}}

<div class="row m-0">
	<div class="col-12 mt-1 px-3 pt-3 border-top" style="color: #731d24; background-color: #f8d7da; border-top: 1px solid #f5c6ca; font-size: .9em">
		<div class="container">
			<strong>Advertencia</strong>
			<p class="text-justify">
				<small>
				*Los intercambios no serán validados si al aceptar la oferta alguno de los dos equipos se queda con un presupuesto negativo o su plantilla fuera del rango mínimo ({{active_season()->min_players}}) y máximo ({{active_season()->max_players}}) de jugadores.
				</small>
			</p>
		</div>
	</div>
</div>
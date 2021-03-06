<div class="modal-content">
    <div class="modal-header bg-light">
    	Partido #{{ $match->id }}
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body">
        <form
            id="frmMatchLimit"
            lang="{{ app()->getLocale() }}"
            role="form"
            method="POST"
            action="{{ route('admin.season_competitions_phases_groups_leagues.calendar.match.update_limit', $match->id) }}"
            enctype="multipart/form-data"
            data-toggle="validator"
            autocomplete="off">
            {{ method_field('PUT') }}
            {{ csrf_field() }}

            <div class="main-content">

                <div class="form-group">
                    <label for="date_limit">Fecha límite de partido</label>
                    <input type="datetime-local" class="form-control" name="date_limit" id="date_limit" value="{{ $match->getDateLimit_date() }}T{{ $match->getDateLimit_time() }}">
                </div>
            </div> {{-- main-content --}}

            <div class="border-top mt-2 py-3">
                <input type="submit" class="btn btn-primary" value="Actualizar fecha">
            </div>
        </form>
    </div> {{-- modal-body --}}
</div> {{-- modal-content --}}
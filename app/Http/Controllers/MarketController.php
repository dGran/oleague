<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Player;
use App\Showcase;
use App\Season;
use App\SeasonPlayer;
use App\SeasonParticipant;
use App\SeasonParticipantCashHistory as Cash;

class MarketController extends Controller
{
    public function index()
    {
        return view('market.index');
    }

    public function playerView($id)
    {
        $player = SeasonPlayer::find($id);
        if ($player) {
            return view('general_modals.player_view', compact('player'))->render();
        }
    }

    public function onSale()
    {
    	$order = request()->order;
        if (!$order) {
            $order = 'date_desc';
        }
        $order_ext = $this->saleGetOrder($order);
        $filterSeason = active_season()->id;
        if (request()->filterParticipant == NULL) { request()->filterParticipant = 0; }
        $filterParticipant = request()->filterParticipant;
        $filterPosition = request()->filterPosition;
    	if (request()->overall_range) {
	    	$overall_rating = (explode( ';', request()->overall_range));
	    	$filterOverallRangeFrom = $overall_rating[0];
	    	$filterOverallRangeTo = $overall_rating[1];
    	} else {
    		$filterOverallRangeFrom = 70;
	    	$filterOverallRangeTo = 99;
    	}
    	if (request()->sale_price_range) {
	    	$sale_price_range = (explode( ';', request()->sale_price_range));
	    	$filterSalePriceRangeFrom = $sale_price_range[0];
	    	$filterSalePriceRangeTo = $sale_price_range[1];
    	} else {
    		$filterSalePriceRangeFrom = 0;
	    	$filterSalePriceRangeTo = 500;
    	}
    	$filterState = 'all';
		if (request()->filterState) {
			$filterState = request()->filterState;
		}

    	$players = Showcase::select('showcase.*')
	        ->join('players', 'players.id', '=', 'showcase.player_id')
	        ->join('season_players', 'season_players.id', '=', 'showcase.player_id');
        if ($filterParticipant > 0) {
            $players = $players->where('season_players.participant_id', '=', $filterParticipant);
        }
        if ($filterPosition != NULL) {
            $players = $players->where('players.position', '=', $filterPosition);
        }
        // $players->where('active', '=', 1);
        $players = $players->where('players.overall_rating', '>=', $filterOverallRangeFrom);
        $players = $players->where('players.overall_rating', '<=', $filterOverallRangeTo);
        $players = $players->where('season_players.sale_price', '>=', $filterSalePriceRangeFrom);
        $players = $players->where('season_players.sale_price', '<=', $filterSalePriceRangeTo);
        if ($filterState != 'all') {
        	switch ($filterState) {
        		case 'transferable':
        			$players = $players->where('season_players.transferable', '=', 1);
        			break;
        		case 'onloan':
        			$players = $players->where('season_players.player_on_loan', '=', 1);
        			break;
        		case 'saleprice':
        			$players = $players->where('season_players.sale_price', '>', 0);
        			break;
        		case 'buynow':
        			$players = $players->where('season_players.sale_auto_accept', '=', 1);
        			break;
        	}
        }
		$players = $players->orderBy($order_ext['sortField'], $order_ext['sortDirection'])
			->orderBy('showcase.created_at', 'desc')
	        ->get();

        if (Season::find($filterSeason)->participant_has_team) {
            $participants = SeasonParticipant::
            leftJoin('teams', 'teams.id', '=', 'season_participants.team_id')
            ->select('season_participants.*', 'teams.name as team_name')
            ->seasonId($filterSeason)->orderBy('team_name', 'asc')->get();
        } else {
            $participants = SeasonParticipant::
            leftJoin('users', 'users.id', '=', 'season_participants.user_id')
            ->select('season_participants.*', 'users.name as user_name')
            ->seasonId($filterSeason)->orderBy('user_name', 'asc')->get();
        }
        $positions = Player::select('position')->distinct()->where('players_db_id', '=', Season::find($filterSeason)->players_db_id)->orderBy('position', 'asc')->get();

        return view('market.sale', compact('players', 'participants', 'positions', 'filterParticipant', 'filterPosition', 'filterOverallRangeFrom', 'filterOverallRangeTo', 'filterTransferable', 'filterOnLoan', 'filterBuyNow', 'filterState', 'filterSalePriceRangeFrom', 'filterSalePriceRangeTo', 'order'));
    }

    public function onSalePlayer($id)
    {
        $player = SeasonPlayer::find($id);
        if ($player) {
            return view('market.sale.view', compact('player'))->render();
        } else {
            return view('market.sale.view-empty')->render();
        }
    }

    public function search()
    {
    	//data of user->participant
    	if (!auth()->guest() && user_is_participant(auth()->user()->id)) {
    		$participant_of_user = participant_of_user();
    	}
    	//filter variables
    	$order = request()->order;
        if (!$order) {
            $order = 'overall_desc';
        }

        $pagination = request()->pagination;
        if (!$pagination == null) {
            $perPage = $pagination;
        } else {
            $perPage = 10;
        }

        $page = request()->page;

        $order_ext = $this->searchGetOrder($order);

        $filterSeason = active_season()->id;

        $filterName = null;
        if (!is_null(request()->filterName)) {
        	$filterName = request()->filterName;
        }

        $filterParticipant = request()->filterParticipant;
        if ($filterParticipant == NULL) {
        	$filterParticipant = -1;
        }

        $filterPosition = request()->filterPosition;

        $filterNation = request()->filterNation;

        $filterOriginalTeam = request()->filterOriginalTeam;

        $filterOriginalLeague = request()->filterOriginalLeague;

		if (request()->filterHideFree == "on") {
			$filterHideFree = true;
		} else {
			$filterHideFree = false;
		}

		if (request()->filterHideClausePaid == "on") {
			$filterHideClausePaid = true;
		} else {
			$filterHideClausePaid = false;
		}

		if (request()->filterHideParticipantClauseLimit == "on") {
			$filterHideParticipantClauseLimit = true;
		} else {
			$filterHideParticipantClauseLimit = false;
		}

		if (request()->filterShowClausesCanPay == "on") {
			$filterShowClausesCanPay = true;
		} else {
			$filterShowClausesCanPay = false;
		}

    	if (request()->overall_range) {
	    	$overall_rating = (explode( ';', request()->overall_range));
	    	$filterOverallRangeFrom = $overall_rating[0];
	    	$filterOverallRangeTo = $overall_rating[1];
    	} else {
    		$filterOverallRangeFrom = 70;
	    	$filterOverallRangeTo = 99;
    	}

    	if (request()->clause_range) {
	    	$clause_range = (explode( ';', request()->clause_range));
	    	$filterClauseRangeFrom = $clause_range[0];
	    	$filterClauseRangeTo = $clause_range[1];
    	} else {
    		$filterClauseRangeFrom = 0;
	    	$filterClauseRangeTo = 500;
    	}

    	if (request()->age_range) {
	    	$age_range = (explode( ';', request()->age_range));
	    	$filterAgeRangeFrom = $age_range[0];
	    	$filterAgeRangeTo = $age_range[1];
    	} else {
    		$filterAgeRangeFrom = 15;
	    	$filterAgeRangeTo = 45;
    	}

    	if (request()->height_range) {
	    	$height_range = (explode( ';', request()->height_range));
	    	$filterHeightRangeFrom = $height_range[0];
	    	$filterHeightRangeTo = $height_range[1];
    	} else {
    		$filterHeightRangeFrom = 150;
	    	$filterHeightRangeTo = 210;
    	}

    	//list of players
        $players = SeasonPlayer::select('season_players.*', 'season_participants.clauses_received')
        	->leftjoin('players', 'players.id', '=', 'season_players.player_id')
        	->leftjoin('season_participants', 'season_participants.id', '=', 'season_players.participant_id');
		$players->where('season_players.season_id', "=", $filterSeason);
        $players->where('active', '=', 1);
        if (!is_null($filterName)) {
        	$players->where('players.name', "LIKE", "%$filterName%");
        }
        if ($filterParticipant >= 0) {
            $players = $players->where('season_players.participant_id', '=', $filterParticipant);
        }
        if ($filterShowClausesCanPay) {
        	$players = $players->where(function($q) use ($participant_of_user) {
          		$q->where('season_players.participant_id', '!=', 0)
            	  ->where('season_players.participant_id', '!=', $participant_of_user->id);
      		});
        	$players = $players->where('season_players.allow_clause_pay', '=', 1);
        	$players = $players->where('season_participants.clauses_received', '<', active_season()->max_clauses_received);
        	$players = $players->where(\DB::raw('season_players.price * 1.10'), '<', $participant_of_user->budget());
        } else {
	        if ($filterHideFree) {
	        	$players = $players->where('season_players.participant_id', '!=', 0);
	        }
	        if ($filterHideClausePaid) {
	        	$players = $players->where('season_players.allow_clause_pay', '=', 1);
	        }
	        if ($filterHideParticipantClauseLimit) {
	        	$players = $players->where('season_participants.clauses_received', '<', active_season()->max_clauses_received);
	        }
        }
        if ($filterPosition != NULL) {
            $players = $players->where('players.position', '=', $filterPosition);
        }
        if ($filterNation != NULL) {
            $players = $players->where('players.nation_name', '=', $filterNation);
        }
        if ($filterOriginalTeam != NULL) {
            $players = $players->where('players.team_name', '=', $filterOriginalTeam);
        }
        if ($filterOriginalLeague != NULL) {
            $players = $players->where('players.league_name', '=', $filterOriginalLeague);
        }
        $players = $players->where('players.overall_rating', '>=', $filterOverallRangeFrom);
        $players = $players->where('players.overall_rating', '<=', $filterOverallRangeTo);
        $players = $players->where('season_players.price', '>=', $filterClauseRangeFrom);
        $players = $players->where('season_players.price', '<=', $filterClauseRangeTo);
        $players = $players->where('players.age', '>=', $filterAgeRangeFrom);
        $players = $players->where('players.age', '<=', $filterAgeRangeTo);
        $players = $players->where('players.height', '>=', $filterHeightRangeFrom);
        $players = $players->where('players.height', '<=', $filterHeightRangeTo);
		$players = $players->orderBy($order_ext['sortField'], $order_ext['sortDirection'])
			->orderBy('players.name', 'asc')
	        ->paginate($perPage, ['*'], 'page', $page);

	    //list of participants
        if (Season::find($filterSeason)->participant_has_team) {
            $participants = SeasonParticipant::
            leftJoin('teams', 'teams.id', '=', 'season_participants.team_id')
            ->select('season_participants.*', 'teams.name as team_name')
            ->seasonId($filterSeason)->orderBy('team_name', 'asc')->get();
        } else {
            $participants = SeasonParticipant::
            leftJoin('users', 'users.id', '=', 'season_participants.user_id')
            ->select('season_participants.*', 'users.name as user_name')
            ->seasonId($filterSeason)->orderBy('user_name', 'asc')->get();
        }
        //list of positions
        $positions = Player::select('position')->distinct()->where('players_db_id', '=', Season::find($filterSeason)->players_db_id)->orderBy('position', 'asc')->get();
        //list of nations
		$nations = Player::select('nation_name')->distinct()->where('players_db_id', '=', Season::find($filterSeason)->players_db_id)->orderBy('nation_name', 'asc')->get();
        //list of original_teams
		$original_teams = Player::select('team_name')->distinct()->where('players_db_id', '=', Season::find($filterSeason)->players_db_id)->orderBy('team_name', 'asc')->get();
		//list of original_league
		$original_leagues = Player::select('league_name')->distinct()->where('players_db_id', '=', Season::find($filterSeason)->players_db_id)->orderBy('league_name', 'asc')->get();

		//return view
        return view('market.search', compact('players', 'participants', 'positions', 'nations', 'original_teams', 'original_leagues', 'filterName', 'filterParticipant', 'filterPosition', 'filterNation', 'filterOriginalTeam', 'filterOriginalLeague', 'filterOverallRangeFrom', 'filterOverallRangeTo', 'filterTransferable', 'filterOnLoan', 'filterBuyNow', 'filterClauseRangeFrom', 'filterClauseRangeTo', 'filterAgeRangeFrom', 'filterAgeRangeTo', 'filterHeightRangeFrom', 'filterHeightRangeTo', 'filterHideFree', 'filterHideClausePaid', 'filterHideParticipantClauseLimit', 'filterShowClausesCanPay', 'order', 'pagination', 'page'));
    }

    public function myTeam()
    {
    	if (auth()->guest()) {
    		return redirect()->route('market')->with('info', 'La página ha expirado debido a la inactividad.');
    	} else {
			if (user_is_participant(auth()->user()->id)) {
				$participant = SeasonParticipant::where('season_id', '=', active_season()->id)
					->where('user_id', '=', auth()->user()->id)->first();

				$players = SeasonPlayer::select('season_players.*')
			        ->join('players', 'players.id', '=', 'season_players.player_id')
			        ->seasonId(active_season()->id);
	            $players = $players->participantId($participant->id);
		        // if ($filterNation != NULL) {
		        //     $players = $players->where('players.nation_name', '=', $filterNation);
		        // }
		        // if ($filterPosition != NULL) {
		        //     $players = $players->where('players.position', '=', $filterPosition);
		        // }
		        // if ($filterActive == 1) {
		        //     $players->where('active', '=', $filterActive);
		        // }
		        $players = $players->orderBy('players.overall_rating', 'desc')
			        ->orderBy('players.name', 'asc')
			        ->get();

				return view('market.my_team', compact('participant', 'players'));
			}
    	}

		return redirect()->route('market')->with('info', 'Debes ser participante para tener acceso.');
    }

    public function myTeamPlayerEdit($id)
    {
        $player = SeasonPlayer::find($id);
        if ($player) {
            return view('market.my_team.edit', compact('player'))->render();
        } else {
            return view('market.my_team.edit-empty')->render();
        }
    }

    public function myTeamPlayerUpdate($id)
    {
    	if (auth()->guest()) {
    		return redirect()->route('market')->with('info', 'La página ha expirado debido a la inactividad.');
    	} else {
	        $player = SeasonPlayer::find($id);
	        if ($player) {
    			if (auth()->user()->id == $player->participant->user_id) {
		        	$player->salary = request()->salary;
		        	$player->price = request()->price;

		        	if (request()->untransferable == 'on') {
		        		$player->untransferable	= 1;
		        		$player->player_on_loan = 0;
		        		$player->transferable = 0;
		        		$player->sale_price = null;
		        		$player->sale_auto_accept = 0;
		        	} else {
		        		$player->untransferable	= 0;
			        	$player->player_on_loan = request()->player_on_loan == 'on' ? 1 : 0;
			        	$player->transferable = request()->transferable == 'on' ? 1 : 0;
			        	$player->sale_price = request()->sale_price;
			        	$player->sale_auto_accept = request()->sale_auto_accept == 'on' ? 1 : 0;
		        	}
		        	$player->market_phrase = request()->market_phrase;
		        	$player->save();
		        	if ($player->save()) {
		        		$this->manage_player_showcase($player);
		            	return redirect()->route('market.my_team')->with('success', 'Jugador editado correctamente.');
		        	} else {
		        		return back()->with('error', 'No se han guardado los datos, se ha producido un error en el servidor.');
		        	}
    			} else {
    				return back()->with('error', 'Acción cancelada. Ya no eres propietario del jugador');
    			}
	        } else {
				return redirect()->route('market.my_team')->with('error', 'El jugador ya no existe en la base de datos.');
	        }
    	}
    }

    public function tagsTransferable($id)
    {
    	if (auth()->guest()) {
    		return back()->with('info', 'La página ha expirado debido a la inactividad.');
    	} else {
	        $player = SeasonPlayer::find($id);
	        if ($player) {
	        	if (auth()->user()->id == $player->participant->user_id) {
		        	$player->transferable = 1;
		        	$player->untransferable = 0;
		        	$player->save();
		        	if ($player->save()) {
		        		$this->manage_player_showcase($player);
		            	return redirect()->route('market.my_team')->with('success', $player->player->name . ' ha sido declarado transferible.');
		        	} else {
		        		return back()->with('error', 'No se han guardado los datos, se ha producido un error en el servidor.');
		        	}
	        	} else {
	        		return back()->with('error', 'Acción cancelada. Ya no eres propietario del jugador');
	        	}
	        }
	        return back();
    	}
    }

    public function tagsUntransferable($id)
    {
    	if (auth()->guest()) {
    		return back()->with('info', 'La página ha expirado debido a la inactividad.');
    	} else {
	        $player = SeasonPlayer::find($id);
	        if ($player) {
	        	if (auth()->user()->id == $player->participant->user_id) {
		        	$player->untransferable = 1;
		    		$player->player_on_loan = 0;
		    		$player->transferable = 0;
		    		$player->sale_price = null;
		    		$player->sale_auto_accept = 0;
		    		$player->market_phrase = null;
		        	$player->save();
		        	if ($player->save()) {
		        		$this->manage_player_showcase($player);
		            	return redirect()->route('market.my_team')->with('success', $player->player->name . ' ha sido declarado intransferible.');
		        	} else {
		        		return back()->with('error', 'No se han guardado los datos, se ha producido un error en el servidor.');
		        	}
	        	} else {
	        		return back()->with('error', 'Acción cancelada. Ya no eres propietario del jugador');
	        	}
	        }
	        return back();
    	}
    }

    public function tagsOnLoan($id)
    {
    	if (auth()->guest()) {
    		return back()->with('info', 'La página ha expirado debido a la inactividad.');
    	} else {
	        $player = SeasonPlayer::find($id);
	        if ($player) {
	        	if (auth()->user()->id == $player->participant->user_id) {
		        	$player->player_on_loan = 1;
		        	$player->untransferable = 0;
		        	$player->save();
		        	if ($player->save()) {
		        		$this->manage_player_showcase($player);
		            	return redirect()->route('market.my_team')->with('success', $player->player->name . ' ha sido declarado cedible.');
		        	} else {
		        		return back()->with('error', 'No se han guardado los datos, se ha producido un error en el servidor.');
		        	}
	        	} else {
	        		return back()->with('error', 'Acción cancelada. Ya no eres propietario del jugador');
	        	}
	        }
	        return back();
    	}
    }

    public function tagsDelete($id)
    {
    	if (auth()->guest()) {
    		return back()->with('info', 'La página ha expirado debido a la inactividad.');
    	} else {
	        $player = SeasonPlayer::find($id);
	        if ($player) {
	        	if (auth()->user()->id == $player->participant->user_id) {
		        	$player->untransferable = 0;
		    		$player->player_on_loan = 0;
		    		$player->transferable = 0;
		    		$player->sale_price = null;
		    		$player->sale_auto_accept = 0;
		    		$player->market_phrase = null;
		        	$player->save();
		        	if ($player->save()) {
		        		$this->manage_player_showcase($player);
		            	return redirect()->route('market.my_team')->with('success', 'Se han eliminado las etiquetas de ' . $player->player->name);
		        	} else {
		        		return back()->with('error', 'No se han guardado los datos, se ha producido un error en el servidor.');
		        	}
	        	} else {
	        		return back()->with('error', 'Acción cancelada. Ya no eres propietario del jugador');
	        	}
	        }
	        return back();
    	}
    }

    public function dismiss($id)
    {
    	if (auth()->guest()) {
    		return back()->with('info', 'La página ha expirado debido a la inactividad.');
    	} else {
	        $player = SeasonPlayer::find($id);
	        if ($player) {
	        	if (auth()->user()->id == $player->participant->user_id) {
		        	$this->add_cash_history(
		        		$player->participant_id,
		        		'Despido de ' . $player->player->name,
		        		$player->season->free_players_remuneration,
		        		'E'
		        	);

		        	//generate new
		        	//generate transfers table

		        	$player->participant_id = null;
		        	$player->market_phrase = null;
		        	$player->untransferable = 0;
		        	$player->player_on_loan = 0;
		        	$player->transferable = 0;
		        	$player->sale_price = null;
		        	$player->sale_auto_accept = 0;
		        	$player->price = 5;
		        	$player->salary = 0.5;
		        	$player->save();
		        	if ($player->save()) {
		        		$this->manage_player_showcase($player);
		            	return redirect()->route('market.my_team')->with('success', $player->player->name . ' ha sido declarado despedido.');
		        	} else {
		        		return back()->with('error', 'No se han guardado los datos, se ha producido un error en el servidor.');
		        	}
	        	} else {
	        		return back()->with('error', 'Acción cancelada. Ya no eres propietario del jugador');
	        	}
	        }
	        return back();
    	}
    }

    /*
     * HELPERS FUNCTIONS
     *
     */
	protected function add_cash_history($participant_id, $description, $amount, $movement) {
	    $cash = new Cash;
	    $cash->participant_id = $participant_id;
	    $cash->description = $description;
	    $cash->amount = $amount;
	    $cash->movement = $movement;
	    $cash->save();
	}

	protected function manage_player_showcase($player) {
		$player_showcase = $this->check_player_showcase($player->id);
		if ($player_showcase) {
			if (!$player->transferable && !$player->player_on_loan) {
				$player_showcase = Showcase::where('player_id', '=', $player->id)->first();
				$player_showcase->delete();
			}
		} else {
			if ($player->transferable || $player->player_on_loan) {
				$player_showcase = new Showcase;
				$player_showcase->player_id = $player->id;
				$player_showcase->save();
			}
		}
	}

	protected function check_player_showcase($id) {
		$showcase = Showcase::where('player_id', '=', $id)->first();
		if ($showcase) {
			return true;
		}
		return false;
	}

    protected function saleGetOrder($order) {
        $order_ext = [
            'date' => [
                'sortField'     => 'showcase.created_at',
                'sortDirection' => 'asc'
            ],
            'date_desc' => [
                'sortField'     => 'showcase.created_at',
                'sortDirection' => 'desc'
            ],
            'name' => [
                'sortField'     => 'players.name',
                'sortDirection' => 'asc'
            ],
            'name_desc' => [
                'sortField'     => 'players.name',
                'sortDirection' => 'desc'
            ],
            'overall' => [
                'sortField'     => 'players.overall_rating',
                'sortDirection' => 'asc'
            ],
            'overall_desc' => [
                'sortField'     => 'players.overall_rating',
                'sortDirection' => 'desc'
            ],
            'saleprice' => [
                'sortField'     => 'season_players.sale_price',
                'sortDirection' => 'asc'
            ],
            'saleprice_desc' => [
                'sortField'     => 'season_players.sale_price',
                'sortDirection' => 'desc'
            ]
        ];
        return $order_ext[$order];
    }

    protected function searchGetOrder($order) {
        $order_ext = [
            'name' => [
                'sortField'     => 'players.name',
                'sortDirection' => 'asc'
            ],
            'name_desc' => [
                'sortField'     => 'players.name',
                'sortDirection' => 'desc'
            ],
            'overall' => [
                'sortField'     => 'players.overall_rating',
                'sortDirection' => 'asc'
            ],
            'overall_desc' => [
                'sortField'     => 'players.overall_rating',
                'sortDirection' => 'desc'
            ],
            'saleprice' => [
                'sortField'     => 'season_players.sale_price',
                'sortDirection' => 'asc'
            ],
            'saleprice_desc' => [
                'sortField'     => 'season_players.sale_price',
                'sortDirection' => 'desc'
            ]
        ];
        return $order_ext[$order];
    }
}
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminFiltersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_filters', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("user_id")->unsigned()->index();
            $table->string("game_filterName")->nullable();
            $table->string("game_order")->nullable();
            $table->string("game_pagination")->nullable();
            $table->string("game_page")->nullable();
            $table->string("teamCategories_filterName")->nullable();
            $table->string("teamCategories_order")->nullable();
            $table->string("teamCategories_pagination")->nullable();
            $table->string("teamCategories_page")->nullable();
            $table->string("team_filterName")->nullable();
            $table->string("team_filterCategory")->nullable();
            $table->string("team_order")->nullable();
            $table->string("team_pagination")->nullable();
            $table->string("team_page")->nullable();
            $table->string("playerDB_filterName")->nullable();
            $table->string("playerDB_order")->nullable();
            $table->string("playerDB_pagination")->nullable();
            $table->string("playerDB_page")->nullable();
            $table->string("player_filterName")->nullable();
            $table->string("player_filterPlayerDb")->nullable();
            $table->string("player_filterTeam")->nullable();
            $table->string("player_filterNation")->nullable();
            $table->string("player_filterPosition")->nullable();
            $table->string("player_order")->nullable();
            $table->string("player_pagination")->nullable();
            $table->string("player_page")->nullable();
            $table->string("table_zone_filterName")->nullable();
            $table->string("table_zone_order")->nullable();
            $table->string("table_zone_pagination")->nullable();
            $table->string("table_zone_page")->nullable();
            $table->string("season_filterName")->nullable();
            $table->string("season_order")->nullable();
            $table->string("season_pagination")->nullable();
            $table->string("season_page")->nullable();
            $table->string("seasonParticipants_filterSeason")->nullable();
            $table->string("seasonParticipants_order")->nullable();
            $table->string("seasonParticipants_pagination")->nullable();
            $table->string("seasonParticipants_page")->nullable();
            $table->string("seasonPlayers_filterName")->nullable();
            $table->string("seasonPlayers_filterSeason")->nullable();
            $table->string("seasonPlayers_filterParticipant")->nullable()->default(-1);
            $table->string("seasonPlayers_filterTeam")->nullable();
            $table->string("seasonPlayers_filterLeague")->nullable();
            $table->string("seasonPlayers_filterNation")->nullable();
            $table->string("seasonPlayers_filterPosition")->nullable();
            $table->string("seasonPlayers_filterPack")->nullable();
            $table->string("seasonPlayers_filterActive")->nullable();
            $table->string("seasonPlayers_order")->nullable();
            $table->string("seasonPlayers_pagination")->nullable();
            $table->string("seasonPlayers_page")->nullable();
            $table->string("seasonCompetitions_filterSeason")->nullable();
            $table->string("seasonCompetitions_order")->nullable();
            $table->string("seasonCompetitions_pagination")->nullable();
            $table->string("seasonCompetitions_page")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_filters');
    }
}

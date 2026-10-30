<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoriquesTable extends Migration
{
    public function up()
    {
        Schema::create('historiques', function (Blueprint $table) {
            $table->bigIncrements('idHistorique');
            $table->string('action');
            $table->text('description')->nullable();
            $table->dateTime('dateModification');

            $table->unsignedBigInteger('idUtilisateur');
            $table->unsignedBigInteger('idProjet')->nullable();
            $table->unsignedBigInteger('idTache')->nullable();

            $table->foreign('idUtilisateur')
                  ->references('idUtilisateur')
                  ->on('utilisateurs');

            $table->foreign('idProjet')
                  ->references('idProjet')
                  ->on('projets');

            $table->foreign('idTache')
                  ->references('idTache')
                  ->on('taches');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('historiques');
    }
}
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTachesTable extends Migration
{
    public function up()
    {
        Schema::create('taches', function (Blueprint $table) {
            $table->bigIncrements('idTache');
            $table->string('titre');
            $table->text('description')->nullable();
            $table->date('dateDebut')->nullable();
            $table->date('dateEcheance');
            $table->string('statut')->default('À faire');

            $table->unsignedBigInteger('idProjet');
            $table->unsignedBigInteger('idUtilisateur')->nullable();

            $table->foreign('idProjet')
                  ->references('idProjet')
                  ->on('projets');

            $table->foreign('idUtilisateur')
                  ->references('idUtilisateur')
                  ->on('utilisateurs');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('taches');
    }
}
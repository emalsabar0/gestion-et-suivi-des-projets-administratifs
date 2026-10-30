<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjetsTable extends Migration
{
    public function up()
    {
        Schema::create('projets', function (Blueprint $table) {
            $table->bigIncrements('idProjet');
            $table->string('nomProjet');
            $table->text('description')->nullable();
            $table->date('dateDebut');
            $table->date('dateFin');
            $table->string('statut');

            $table->unsignedBigInteger('idUtilisateur');

            $table->foreign('idUtilisateur')
                  ->references('idUtilisateur')
                  ->on('utilisateurs');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('projets');
    }
}
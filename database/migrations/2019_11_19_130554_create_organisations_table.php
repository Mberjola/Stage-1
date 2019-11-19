<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganisationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organisations', function (Blueprint $table) {
            $table->increments('Id');
            $table->integer('SIRET');
            $table->string('RaisonsSociales');
            $table->string('SigleOrg');
            $table->text('LogoUrl');
            $table->text('Activite');
            $table->integer('Telephone');
            $table->integer('NbSalaries');
            $table->text('SiteUrl');
            $table->text('Adresse');
            $table->foreign('IdCP')->references('Id')->on('codePostal');
            $table->foreign('IdTypeOrga')->references('Id')->on('TypeOrganisation');
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
        Schema::dropIfExists('organisations');
    }
}

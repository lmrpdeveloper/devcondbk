<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Createalltables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /* TABELA DE USUÁRIOS */
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('cpf')->unique();
            $table->string('password');
        });

        /* TABELA DE UNIDADES (APARTAMENTOS, LOTES) */
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('id_owner');
        });

        /* TABELA DE UNIDADES POR PESSOA (APARTAMENTOS, LOTES) */
        Schema::create('unitpeoples', function (Blueprint $table) {
            $table->id();
            $table->integer('id_unit');
            $table->string('name');
            $table->date('birthdate');
        });

        /* TABELA DE UNIDADES POR PESSOA (CARROS, MOTOS) */
        Schema::create('unitvehicles', function (Blueprint $table) {
            $table->id();
            $table->integer('id_unit');
            $table->string('title');
            $table->string('color');
            $table->string('plate');
        });

        /* TABELA DE UNIDADES POR PESSOA (GATOS, CACHORROS) */
        Schema::create('unitpets', function (Blueprint $table) {
            $table->id();
            $table->integer('id_unit');
            $table->string('name');
            $table->string('race');
        });

        /* TABELA DE MURAL */
        Schema::create('walls', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('body');
            $table->datetime('datecreated');
        });

        /* TABELA DE MURAL (LIKES) */
        Schema::create('walllikes', function (Blueprint $table) {
            $table->id();
            $table->integer('id_wall');
            $table->integer('id_user');
        });

        /* TABELA DE DOCUMENTOS */
        Schema::create('docs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('fileurl');
        });

        /* TABELA DE BOLETOS */
        Schema::create('billets', function (Blueprint $table) {
            $table->id();
            $table->integer('id_unit');
            $table->string('title');
            $table->string('fileurl');
        });

        /* TABELA DE OCORRÊNCIAS */
        Schema::create('warnings', function (Blueprint $table) {
            $table->id();
            $table->integer('id_unit');
            $table->string('title');
            $table->string('status')->default('IN_REVIEW'); // IN_REVIEW (Em análise), RESOLVED (Resolvido)
            $table->date('datecreated');
            $table->text('photos'); // foto1.jpg, foto2.jpg, ...
        });

        /* TABELA DE ACHADOS E PERDIDOS */
        Schema::create('foundandlost', function (Blueprint $table) {
            $table->id();
            $table->string('status')->default('LOST'); // LOST (Perdido), RECOVERED (Recuperado)
            $table->string('photo');
            $table->string('description');
            $table->string('where');
            $table->date('datecreated');
        });

        /* TABELA DE ÁREAS EM COMUM */
        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->integer('allowed')->default(1); // 0 (Não permitido), 1 (Permitido)
            $table->string('title');
            $table->string('cover');
            $table->string('days'); // Dias disponíveis (0, 1, 2, 3, 4, 5, 6)
            $table->time('start_time');
            $table->time('end_time');
        });

        /* TABELA DE ÁREAS EM COMUM (DIAS INDISPONÍVES) */
        Schema::create('areadisableddays', function (Blueprint $table) {
            $table->id();
            $table->integer('id_area');
            $table->date('day');
        });

        /* TABELA RESERVAS DE ÁREAS */
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->integer('id_unit');
            $table->integer('id_area');
            $table->datetime('reservation_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('units');
        Schema::dropIfExists('unitpeople');
        Schema::dropIfExists('unitvehicles');
        Schema::dropIfExists('unitpets');
        Schema::dropIfExists('walls');
        Schema::dropIfExists('walllikes');
        Schema::dropIfExists('docs');
        Schema::dropIfExists('billets');
        Schema::dropIfExists('warnings');
        Schema::dropIfExists('foundandlost');
        Schema::dropIfExists('areas');
        Schema::dropIfExists('areadisableddays');
        Schema::dropIfExists('reservations');
    }
}

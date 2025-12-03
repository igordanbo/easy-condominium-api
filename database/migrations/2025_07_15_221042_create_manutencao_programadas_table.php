<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManutencaoProgramadasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manutencao_programadas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipo_manutencao_id')->constrained('tipo_manutencaos');
            $table->foreignId('condominio_id')->constrained();
            $table->foreignId('bloco_id')->nullable()->constrained();
            $table->foreignId('apartamento_id')->nullable()->constrained();
            $table->date('data_agendada');
            $table->date('data_conclusao')->nullable();
            $table->enum('status', ['agendado', 'concluido', 'cancelado', 'adiado'])->default('agendado');
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
        Schema::dropIfExists('manutencao_programadas');
    }
}

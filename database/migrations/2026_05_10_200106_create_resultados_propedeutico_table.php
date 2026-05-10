<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */

    public function up()
    {
        Schema::create('resultados_propedeutico', function (Blueprint $table)
        {
            $table->id('id_resultados_propedeutico');
            $table->decimal('examen_inicial', 5, 2)->nullable();
            $table->decimal('examen_final', 5, 2)->nullable();
            $table->unsignedBigInteger('id_curso');
            $table->foreign('id_curso')
                ->references('id_curso')
                ->on('cursos')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resultados_propedeutico');
    }
};

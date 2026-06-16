<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('alumno', function (Blueprint $table) {
            $table->integer('matricula')->unsigned()->primary();
            $table->string('nombre', 45);
            $table->string('ap_pat', 25);
            $table->string('ap_mat', 25)->nullable();
            $table->string('correo_alternativo', 150)->unique();
            $table->string('telefono', 10)->unique();
            $table->smallInteger('puntaje_ingreso')->nullable();
            $table->string('correo_institucional', 125)->unique()->nullable();

            // llaves foraneas
            $table->unsignedBigInteger('id_carrera');
            $table->foreign('id_carrera')->references('id_carrera')->on('carrera');
            $table->unsignedBigInteger('id_grupo_induccion')->nullable();
            $table->foreign('id_grupo_induccion')->references('id_grupo')->on('grupos');
            $table->unsignedBigInteger('id_grupo_propedeutico')->nullable();
            $table->foreign('id_grupo_propedeutico')->references('id_grupo')->on('grupos');
            $table->unsignedBigInteger('id_grupo_definitivo')->nullable();
            $table->foreign('id_grupo_definitivo')->references('id_grupo')->on('grupos');
            $table->unsignedBigInteger('id_resultados_propedeutico')->nullable();
            $table->foreign('id_resultados_propedeutico', 'fk_res_prop')
                ->references('id_resultados_propedeutico')
                ->on('resultados_propedeutico');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumno');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::create('grupos', function (Blueprint $table)
    {
        $table->id('id_grupo');
        $table->string('nombre_grupo', 25);

        // llaves foraneas
        $table->unsignedTinyInteger('id_turno');
        $table->foreign('id_turno')->references('id_turno')->on('turnos');
        $table->unsignedBigInteger('id_curso');
        $table->foreign('id_curso')->references('id_curso')->on('cursos');
        $table->integer('num_empleado');
        $table->foreign('num_empleado')->references('num_empleado')->on('usuarios');
        $table->unsignedTinyInteger('id_estado');
        $table->foreign('id_estado')->references('id_estado')->on('estado_grupo');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grupos');
    }
};


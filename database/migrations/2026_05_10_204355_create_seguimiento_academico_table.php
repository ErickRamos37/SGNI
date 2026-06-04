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
        Schema::create('seguimiento_academico', function (Blueprint $table) {
            $table->id('id_seguimiento');
            $table->text('comentarios')->nullable();

            // llaves foraneas
            $table->integer('matricula')->unsigned();
            $table->foreign('matricula')->references('matricula')->on('alumno')
                ->references('matricula')
                ->on('alumno')
                ->onDelete('no action')
                ->onUpdate('no action');
            $table->unsignedTinyInteger('id_situacion_alum');
            $table->foreign('id_situacion_alum')
                ->references('id_situacion_alum')
                ->on('situacion_alumno')
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
        Schema::dropIfExists('seguimiento_academico');
    }
};

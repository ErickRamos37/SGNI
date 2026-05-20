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
        Schema::create('configuracion_peso', function (Blueprint $table)
        {
            $table->id('id_configuracion');
            $table->string('tipo_configuracion', 45);
            $table->decimal('valor', 5, 2);

            // llaves foraneas
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
        Schema::dropIfExists('configuracion_peso');
    }
};



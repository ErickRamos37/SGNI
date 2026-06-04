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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->integer('num_empleado')->primary();
            $table->string('nombre', 45);
            $table->string('ap_pat', 25);
            $table->string('ap_mat', 25)->nullable();
            $table->string('correo_institucional', 100)->unique();

            // llaves foraneas
            $table->unsignedTinyInteger('id_rol');
            $table->foreign('id_rol')
                ->references('id_rol')
                ->on('roles')
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
        Schema::dropIfExists('usuarios');
    }
};

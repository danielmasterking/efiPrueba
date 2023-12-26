<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id(); // ID llave primaria auto incremental
            $table->string('nombre');
            $table->boolean('activo');
            $table->decimal('precio', 10, 2);
            $table->integer('stock');
            $table->string('ean_13');
            $table->timestamps(); // Campos created_at y updated_at automáticos
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productos');
    }
}

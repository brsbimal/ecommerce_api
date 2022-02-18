<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppProductSpecificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_product_specifications', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->string('language_code');
            $table->string('brand')->nullable();
            $table->string('made_type')->nullable();
            $table->string('materials')->nullable();
            $table->string('weight')->nullable();
            $table->string('weight_unit')->nullable();
            $table->string('dimension')->nullable();
            $table->string('dimension_unit')->nullable();
            $table->string('categories')->nullable();
            
            $table->string('size')->nullable();
            $table->string('size_unit')->nullable();
            $table->string('size_remarks')->nullable();

            $table->string('color')->nullable();
            $table->string('color_remarks')->nullable();

            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('app_product_specifications');
    }
}

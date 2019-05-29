<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldIdCategoryId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('id_category');
            $table->foreign('id_category')
                ->references('id')
                ->on('categories')
                ->onDelete('cascade');

            //  Activer les contraintes de clé étrangères. On ne peut pas lier un produit à une catégorie qui n'existe pas...
            Schema::enableForeignKeyConstraints();


            // Si on ne veut pas supprimer les produits à la suppression d'une catégorie: Ex: je supprime la catégorie films, tous les produits liés à cette catégorie auront une valeur NULL dans l'attribut id_category

//            $table->unsignedInteger('id_category')->nullable();
//            $table->foreign('id_category')
//                ->references('id')
//                ->on('categories')
//                ->onDelete('set null');
//            Schema::enableForeignKeyConstraints();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
}

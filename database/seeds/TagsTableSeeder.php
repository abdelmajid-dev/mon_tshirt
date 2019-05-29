<?php

use Illuminate\Database\Seeder;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $tag = new \App\Tag();
        $tag->nom ="Humour";
        $tag->save();
        // Associer le tag aux produits id=2 et id=4
        $tag->products()->attach([2,4]);
    }
}

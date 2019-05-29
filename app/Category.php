<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    public function products() {
        return $this->hasMany('App\Product','id_category');
    }

    // Récupérer les catégories enfant d'une catégorie
    public function childrens() {
        return $this->hasMany('App\Category','id_parent');
    }

    // Récupérer la catégorie parent d'une catégorie
    public function parent() {
        return $this->belongsTo("App\Category",'id_parent');
    }

}

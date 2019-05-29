<?php

namespace App\Http\Controllers\Shop;

use App\Product;
use Darryldecode\Cart\CartCondition;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    // Ajouter au panier un produit
    public function add(Request $request) {
//        dd($request->id);
        $id_product = $request->id;
        $product = Product::find($id_product);
        \Cart::add(array(
            'id' => $id_product."_".$request->size,
            'name' => $product->nom,
            'price' => $product->prix_ht,
            'quantity' => $request->qty,
            'attributes' => array(
                'size'=>$request->size,'photo'=>$product->photo_principale, 'id'=>$id_product)
        ));
        // Redirection vers la page du panier
        return redirect(route('cart'));
    }

    // Afficher le contenu du panier
    public function cart() {
        // Récupérer les produits ajoutés au panier
        $products_cart = \Cart::getContent()->sort();

        $total_panier_ht = \Cart::getSubTotal();
        // Ajouter la TVA 20% au sous-total du panier
        $condition = new CartCondition([
            'name'=>'VAT 20%',
            'type'=>'tax',
            'target'=>'total',
            'value'=>'20%'
        ]);
        // Appliquer la condition au panier
        \Cart::condition($condition);
        // Récupérer le total TTC du panier
        $total_panier_ttc = \Cart::getTotal();

        return view('shop.cart',compact('products_cart','total_panier_ht','total_panier_ttc'));
    }

    public function update(Request $request) {
        // Mettre à jour la quantité d'un produit du panier
        $qty = $request->qty;
        if($qty > 0) {
            \Cart::update($request->id, array(
                'quantity' => array(
                    'relative' => false,
                    'value' => $qty
                ),
            ));
        }
        // Rediriger vers la page panier avec les données de prix actualisées

        // Redirection vers la page panier
        return redirect(route('cart'));
    }

    public function remove(Request $request) {
        $id_product = $request->id;
        \Cart::remove($id_product);
        return redirect(route('cart'));
    }
}

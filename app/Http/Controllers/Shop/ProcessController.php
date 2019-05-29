<?php

namespace App\Http\Controllers\Shop;

use App\Adresse;
use App\Order;
use App\OrderProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProcessController extends Controller
{
    public function __construct()
    {
        // On doit être loggé pour accéder à toutes les pages du process sauf la page identification
        $this->middleware('auth')->except('identification');

        // empêcher l'accès au formulaire d'identification/register si on est déjà loggé
        $this->middleware('guest')->only('identification');
    }

    // Etape 1 // Identification
    public function identification() {
        return view('shop.process.identification');
    }

    // Etape 2 // Adresse de livraison
    public function adresse() {
        return view('shop.process.adresse');
    }

    // Etape 2 Bis // Stocker l'adresse de livraison dans la DB
    public function adresseStore(Request $request) {
        // Récupération des datas du form
//        dd($request->all());
        // validation
            $request->validate([
                'nom'=>'required',
                'adresse'=>'required',
                'telephone'=>'required | digits:10',
                'code_postal'=>'required',
                'ville'=>'required',
                'pays'=>'required'
            ]);
        // Création de l'entité et hydratation
        $adresse = new Adresse();
        // Soit on hydrate les attributs 1 à 1 ...
//        $adresse->prenom = $request->prenom;
//        $adresse->nom = $request->nom;
//        $adresse->adresse = $request->adresse;

        // ... soit on hydrate tous les attributs de l'adresse en 1 seule ligne
        $adresse->fill($request->all());
        // Sauvegarder dans la db
        $adresse->save();

        // Récupération du user connecté pour lui associer
        // l'adresse qui vient d'être créée
        $user_auth = Auth::user();
        $user_auth->adresse_id = $adresse->id;
        $user_auth->save();

        // Redirection vers la page pour procéder au paiement
        return redirect(route('order_paiement'));
    }

    // Etape 3 > page confirmation paiement
    public function paiement() {
        $total_a_payer = \Cart::getTotal();
        return view('shop.process.paiement',compact('total_a_payer'));
    }

    // Etape 3bis > création de la commande dans la DB
    public function confirmationCommande() {
        // Créer l'objet Order > hydrater
        $order = new Order();
        $order->total_ttc = \Cart::getTotal();
        $order->total_ht = \Cart::getSubTotal();
        $order->tva = \Cart::getTotal() - \Cart::getSubTotal();
        $order->taux_tva = 20;

        // Associer Order à une adresse de livraison
        // Récupérer le user connecté
        $user = Auth::user();
        $order->adresse_id = $user->adresse_id;

        // Associer Order à l'utilisateur connecté
        $order->user_id = $user->adresse_id;
        $order->save();

        // Créer un objet OrderProduct par produit dans le panier
        $products = \Cart::getContent();
        foreach($products as $product) {
            $order_product = new OrderProduct();
            $order_product->qty = $product['quantity'];
            $order_product->prix_unitaire_ht = $product['price'];
            $order_product->prix_unitaire_ttc = $product['price'] * 1.2;

            $prix_total_ttc = ($product['price'] * $product['quantity']) * 1.2;
            $prix_total_ht = ($product['price'] * $product['quantity']);

            $order_product->prix_total_ttc = $prix_total_ttc;
            $order_product->prix_total_ht = $prix_total_ht;
            $order_product->size = $product['attributes']['size'];
            $order_product->order_id = $order->id;
            $order_product->product_id = $product['attributes']['id'];
            $order_product->save();
        }

        // Vider le panier
        \Cart::clear();

        // Rediriger vers page Merci
        return redirect(route('order_merci'));
    }

    // Etape 4 > Page Merci
    public function merci() {
        return view('shop.process.merci');

    }
}

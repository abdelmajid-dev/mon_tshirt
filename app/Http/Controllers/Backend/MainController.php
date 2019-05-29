<?php

namespace App\Http\Controllers\Backend;

use App\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MainController extends Controller
{
    //Afficher le formulaire d'identification
    public function loginBackend(){
        return view('backend.login');
    }

    // Afficher la page liste des commandes (homepage du backend)
    public function index() {
        // sa fait un Select * FROM orders LIMIT 1
        //$orders = Order::all();
        $orders = Order::paginate(2);
        return view('backend.index',compact('orders'));
    }

    // Afficher le détail d'une commande
    public function orderShow(Request $request) {
        // Récupérer l'id de la commande à afficher via le param dans la route
        $order_id = $request->id;
        // Récupérer la commande dans la DB
        $order = Order::find($order_id);
        //Afficher la page voir commande
        return view('backend.orderShow',compact('order'));
    }


}

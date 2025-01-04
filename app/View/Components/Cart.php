<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Cart extends Component
{
    public $cart;

    public function __construct()
    {
        // Recebe o carrinho da sessão
        $this->cart = session()->get('cart', []);
    }

    public function render()
    {
        return view('components.cart');
    }
}

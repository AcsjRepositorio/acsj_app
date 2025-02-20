<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Footer extends Component
{
    // Propriedade de exemplo
    public $title;

    /**
     * Cria uma nova instância do componente.
     *
     * @return void
     */
    public function __construct($title = 'Rodapé padrão')
    {
        $this->title = $title;
    }

    /**
     * Renderiza a view do componente.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.footer');
    }
}


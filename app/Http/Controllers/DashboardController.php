<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;


class DashboardController extends Controller
{

   
    
    /**
     * Este controller foi criado com o intuito de dividir funções que não se encaixam em outros controllers e mesmo assim, permanecer no mesmo folder (adminpanel) já que este folder reúne outras funções administrativas(no dashboard), protegidas por um middleweare. 
     */

     public function dashboardView(){
        return view('adminpanel.manage_order');
     }

     
     public function index()
     {
         // Busca todas as ordens com os pratos relacionados
         $orders=Order::with('meals')->get();
         return view('adminpanel.order_overview', compact('orders'));
     }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

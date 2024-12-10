<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;


use App\Models\Meal;
use Illuminate\Http\Request;

class MealController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     // Criei esta função para pegar através de um Get,  sendo apenas esta função acessada por rotas de users 
     // não autenticados e o endereço da página tb não fica como "adminPanel" e sim como viewallmeal
     
    public function viewallmeals(){

        $meals=Meal::all();
        return view('viewallmeals', compact('meals'));

    }

    public function index()
    {
        $meals= Meal::all();
        
        return view('adminpanel.manage_meals', compact('meals'));
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
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'price' => 'required|numeric',
    ]);

   
    $meal = Meal::findOrFail($id);

   
    $meal->name = $request->name;
    $meal->description = $request->description;
    $meal->price = $request->price;

    
    if ($request->hasFile('photo')) {
        
        if ($meal->photo && Storage::exists($meal->photo)) {
            \Storage::delete($meal->photo);
        }

        
        $path = $request->file('photo')->store('meals', 'public');
        $meal->photo = $path;
    }

    
    $meal->save();

    
    return redirect('adminpanel/manage_meals')->with('success', 'Refeição alterada com sucesso!');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $meal=Meal::findOrfail($id);
        $meal->delete();

        return redirect('/adminpanel/manage_meals')->with('success','Refeição excluida do menu com sucesso');
    }
}

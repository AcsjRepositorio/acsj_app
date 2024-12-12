<?php

namespace App\Http\Controllers;
use App\Models\Meal;



use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
        $meal=Meal::findOrfail($id);

        $categories=[
        'Pequeno almoço' => 1,
        'Almoço' => 2,
        'Jantar' => 3,
        'Lanche' => 4,
        ];


        $daysOfWeek = [
            1 => 'Segunda-feira',
            2 => 'Terça-feira',
            3 => 'Quarta-feira',
            4 => 'Quinta-feira',
            5 => 'Sexta-feira',
            6 => 'Sábado',
            7 => 'Domingo',
        ];



        return view('adminpanel.edit_meals',compact('meal','categories','daysOfWeek'));
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
        'category_id' => 'required|integer|in:1,2,3,4', // IDs das categorias
        'day_of_week'=>'required|integer|in:1,2,3,4,5,6,7',//Ids dos dias 
    ]);

   
    $meal = Meal::findOrFail($id);

   
    $meal->name = $request->name;
    $meal->description = $request->description;
    $meal->price = $request->price;
    $meal->category_id=$request->category_id;
    $meal->day_of_week=$request->day_of_week;

    
    if ($request->hasFile('photo')) {
        // Excluir a foto antiga, se existir
        if ($meal->photo) {
            Storage::disk('public')->delete($meal->photo);
        }

        // Fazer o upload da nova foto
        $photoPath = $request->file('photo')->store('photos', 'public');
        $meal->photo = $photoPath;
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

<?php

namespace App\Http\Controllers;
use App\Models\Meal;

use Carbon\Carbon;



use App\Models\Category;
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
        $meals = Meal::with('category')->get()->map(function ($meal) {
            $meal->day_of_week = $meal->day_week_start 
                ? Carbon::parse($meal->day_week_start)->locale('pt_BR')->dayName 
                : 'Data não definida';
            return $meal;
        });
    
        return view('adminpanel.manage_meals', compact('meals'));
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $categories = Category::pluck('meal_category', 'id'); 
      

        
    
        return view('adminpanel.create_meals', compact('categories'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated=$request->validate([


            
            'name' => 'required|string|max:255',
            'description' => 'required',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'required|numeric',
            'category_id' => 'required|integer|in:1,2,3,4', // IDs das categorias
            
            'day_week_start' => 'required|date',
            // 'day_week_end' => '|date|after_or_equal:day_week_start',
                   
            

        ],[
            'name.required' => 'O nome do prato é obrigatório.',
            'price.required' => 'O preço é obrigatório.',
            'category_id.required' => 'O tipo de refeição é obrigatório.',
            'day_week_start.required' => 'A data de venda é obrigatória.',
            'description.required' => 'A descrição é obrigatória.',
            'photo.image' => 'O arquivo enviado deve ser uma imagem.',
        ]
    
    );


        $photoPath = $request->hasFile('photo') 
        ? $request->file('photo')->store('photos', 'public') 
        : 'images/default-meal.jpg';





        $mealData = [
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'category_id' => $validated['category_id'],
            'day_week_start' => $validated['day_week_start'],
            'photo' => $photoPath,
            // 'day_week_end' => $validated['day_week_end'],
        ];
        
        Meal::create($mealData);
        
        return redirect('adminpanel/manage_meals')->with('success', 'Refeição alterada com sucesso!');

       


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

        
      

        return view('adminpanel.edit_meals',compact('meal','categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
{
    $validatedData=$request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'price' => 'required|numeric',
        'category_id' => 'required|integer|in:1,2,3,4', // IDs das categorias
        // 'day_of_week'=>'required|integer|in:1,2,3,4,5,6,7',//Ids dos dias 
        'day_week_start' => 'required|date'
        
    ], [
        'name.required' => 'O nome do prato é obrigatório.',
        'price.required' => 'O preço é obrigatório.',
        'category_id.required' => 'O tipo de refeição é obrigatório.',
        'day_week_start.required' => 'A data de venda é obrigatória.',
        'description.required' => 'A descrição é obrigatória.',
        'photo.image' => 'O arquivo enviado deve ser uma imagem.',
    ]);

    $meal = Meal::findOrFail($id);
    $meal->update($validatedData);

    // Verificar se uma nova foto foi enviada
   
   
    if ($request->hasFile('photo')) {
        if ($meal->photo && $meal->photo !== 'images/default-meal.jpg') {
            Storage::disk('public')->delete($meal->photo);
        }
        $meal->photo = $request->file('photo')->store('photos', 'public');
    }

   
   
   

   
    $meal->name = $request->name;
    $meal->description = $request->description;
    $meal->price = $request->price;
    $meal->category_id=$request->category_id;
    $meal->day_week_start=$request->day_week_start;
    // $meal->day_of_week=$request->day_of_week;
    $meal->photo = $meal->photo ?? 'images/default-meal.jpg';
   
    
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

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



     public static function getAllMeals()
    {
        return Meal::all();
    }
     

    public function index()
    {
        $meals = Meal::all()->map(function ($meal) {
            if ($meal->day_week_start) {
                $meal->day_of_week = ucfirst(Carbon::parse($meal->day_week_start)
                    ->locale('pt_PT')
                    ->dayName);
                $meal->formatted_date = Carbon::parse($meal->day_week_start)->format('d/m/Y');
            } else {
                $meal->day_of_week = 'Data não definida';
                $meal->formatted_date = 'Data não definida';
            }
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
            'day_of_week' => 'required|string|max:50',
            
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
           'day_of_week' => strtolower(Carbon::parse($validated['day_week_start'])->locale('pt_PT')->dayName), // Padronizar com letras minúsculas
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
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'price' => 'required|numeric',
        'category_id' => 'required|integer|in:1,2,3,4', // IDs das categorias
        'day_week_start' => 'nullable|date', // Permitir nulo
        'day_of_week' => 'nullable|string|max:50', // Permitir nulo
    ], [
        'name.required' => 'O nome do prato é obrigatório.',
        'price.required' => 'O preço é obrigatório.',
        'category_id.required' => 'O tipo de refeição é obrigatório.',
        'description.required' => 'A descrição é obrigatória.',
        'photo.image' => 'O arquivo enviado deve ser uma imagem.',
        
    ]);

    $meal = Meal::findOrFail($id);

    // Se a data não foi alterada, mantém o valor atual
    $validatedData['day_week_start'] = $request->day_week_start ?: $meal->day_week_start;
    $validatedData['day_of_week'] = $request->day_of_week ?: ucfirst(Carbon::parse($validatedData['day_week_start'])->locale('pt_PT')->dayName);

    // Atualizar dados gerais
    $meal->update($validatedData);

    // Verificar se uma nova foto foi enviada
    if ($request->hasFile('photo')) {
        if ($meal->photo && $meal->photo !== 'images/default-meal.jpg') {
            Storage::disk('public')->delete($meal->photo);
        }
        $meal->photo = $request->file('photo')->store('photos', 'public');
        $meal->save();
    }

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

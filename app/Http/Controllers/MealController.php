<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MealController extends Controller
{
    /**
     * Retorna todas as refeições (rota pública).
     */
    public static function getAllMeals()
    {
        return Meal::all();
    }

    /**
     * Exibe a lista de refeições.
     */
    public function index()
    {
        $meals = Meal::all()->map(function ($meal) {
            if ($meal->day_week_start) {
                $meal->day_of_week = ucfirst(Carbon::parse($meal->day_week_start)->locale('pt_PT')->dayName);
                $meal->formatted_date = Carbon::parse($meal->day_week_start)->format('d-m-Y');
            } else {
                $meal->day_of_week = 'Data não definida';
                $meal->formatted_date = 'Data não definida';
            }
            return $meal;
        });
        return view('adminpanel.manage_meals', compact('meals'));
    }

    /**
     * Exibe o formulário para criar uma nova refeição.
     */
    public function create()
    {
        $categories = Category::pluck('meal_category', 'id');
        return view('adminpanel.create_meals', compact('categories'));
    }

    /**
     * Armazena uma nova refeição no banco de dados.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'description'    => 'required',
            'photo'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price'          => 'required|numeric',
            'category_id'    => 'required|integer|in:1,2,3,4',
            'day_week_start' => 'required_if:category_id,2|nullable|date',
            'day_of_week'    => 'required_if:category_id,2|nullable|string|max:50',
            'stock'          => 'required|integer|min:0',
        ], [
            'name.required'              => 'O nome do prato é obrigatório.',
            'price.required'             => 'O preço é obrigatório.',
            'category_id.required'       => 'O tipo de refeição é obrigatório.',
            'day_week_start.required_if' => 'A data de venda é obrigatória para refeições do tipo Almoço.',
            'description.required'       => 'A descrição é obrigatória.',
            'photo.image'                => 'O arquivo enviado deve ser uma imagem.',
            'stock.required'             => 'Defina a quantidade a ser vendida.',
        ]);

        // Tratamento da imagem
        $photoPath = $request->hasFile('photo')
            ? $request->file('photo')->store('photos', 'public')
            : 'images/default-meal.jpg';

        if ($validated['category_id'] == 2) {
            $mealData = [
                'name'           => $validated['name'],
                'description'    => $validated['description'],
                'price'          => $validated['price'],
                'category_id'    => $validated['category_id'],
                'day_week_start' => $validated['day_week_start'],
                'day_of_week'    => strtolower(Carbon::parse($validated['day_week_start'])->locale('pt_PT')->dayName),
                'photo'          => $photoPath,
                'stock'          => $validated['stock'],
            ];
        } else {
            $mealData = [
                'name'           => $validated['name'],
                'description'    => $validated['description'],
                'price'          => $validated['price'],
                'category_id'    => $validated['category_id'],
                'day_week_start' => null,
                'day_of_week'    => null,
                'photo'          => $photoPath,
                'stock'          => $validated['stock'],
            ];
        }

        Meal::create($mealData);

        return redirect('adminpanel/manage_meals')->with('success', 'Refeição criada com sucesso!');
    }

    /**
     * Exibe os detalhes de uma refeição (não implementado).
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Exibe o formulário para editar uma refeição.
     */
    public function edit(string $id)
    {
        $meal = Meal::findOrFail($id);
        $categories = [
            'Pequeno almoço' => 1,
            'Almoço'         => 2,
            'Jantar'         => 3,
            'Lanche'         => 4,
        ];
        return view('adminpanel.edit_meals', compact('meal', 'categories'));
    }

    /**
     * Atualiza uma refeição existente no banco de dados.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'name'           => 'required|string|max:255',
            'description'    => 'required|string',
            'photo'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price'          => 'required|numeric',
            'category_id'    => 'required|integer|in:1,2,3,4',
            'day_week_start' => 'required_if:category_id,2|nullable|date',
            'day_of_week'    => 'required_if:category_id,2|nullable|string|max:50',
            'stock'          => 'required|integer|min:0', // Nova regra para estoque
        ], [
            'name.required'              => 'O nome do prato é obrigatório.',
            'price.required'             => 'O preço é obrigatório.',
            'category_id.required'       => 'O tipo de refeição é obrigatório.',
            'day_week_start.required_if' => 'A data de venda é obrigatória para refeições do tipo Almoço.',
            'description.required'       => 'A descrição é obrigatória.',
            'photo.image'                => 'O arquivo enviado deve ser uma imagem.',
            'stock.required'             => 'Defina a quantidade a ser vendida.',
        ]);

        $meal = Meal::findOrFail($id);
        $dayWeekStart = $request->input('day_week_start') ?: $meal->day_week_start;

        if ($validatedData['category_id'] == 2 && $dayWeekStart) {
            $validatedData['day_week_start'] = $dayWeekStart;
            $validatedData['day_of_week'] = strtolower(Carbon::parse($dayWeekStart)->locale('pt_PT')->dayName);
        } else {
            $validatedData['day_week_start'] = null;
            $validatedData['day_of_week'] = null;
        }

        $meal->update($validatedData);

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
     * Atualiza o estoque de uma refeição.
     */
    public function updateStock(Request $request, $id)
    {
        $request->validate([
            'stock' => 'required|integer|min:0'
        ]);

        $meal = Meal::findOrFail($id);
        $meal->update(['stock' => $request->input('stock')]);

        return redirect()->back()->with('success', 'Estoque atualizado com sucesso!');
    }

    /**
     * Remove uma refeição do banco de dados.
     */
    public function destroy(string $id)
    {
        $meal = Meal::findOrFail($id);
        $meal->delete();
        return redirect('adminpanel/manage_meals')->with('success', 'Refeição excluída do menu com sucesso');
    }
}

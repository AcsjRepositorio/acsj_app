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
     * Retorna todas as refeições.
     * (Esta função é utilizada em rotas públicas)
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
                $meal->day_of_week = ucfirst(Carbon::parse($meal->day_week_start)
                    ->locale('pt_PT')
                    ->dayName);
                // Corrigindo o formato da data
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
        // Pega as categorias: a chave é o ID e o valor é o nome da categoria
        $categories = Category::pluck('meal_category', 'id');
        return view('adminpanel.create_meals', compact('categories'));
    }

    /**
     * Armazena uma nova refeição no banco de dados.
     */
    public function store(Request $request)
    {
        // Validação condicional: 
        // Os campos day_week_start e day_of_week são obrigatórios somente se a categoria for "Almoço" (id = 2)
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'description'    => 'required',
            'photo'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price'          => 'required|numeric',
            'category_id'    => 'required|integer|in:1,2,3,4',
            'day_week_start' => 'required_if:category_id,2|nullable|date',
            'day_of_week'    => 'required_if:category_id,2|nullable|string|max:50',
        ], [
            'name.required'              => 'O nome do prato é obrigatório.',
            'price.required'             => 'O preço é obrigatório.',
            'category_id.required'       => 'O tipo de refeição é obrigatório.',
            'day_week_start.required_if' => 'A data de venda é obrigatória para refeições do tipo Almoço.',
            'description.required'       => 'A descrição é obrigatória.',
            'photo.image'                => 'O arquivo enviado deve ser uma imagem.',
        ]);

        // Tratamento da imagem
        $photoPath = $request->hasFile('photo')
            ? $request->file('photo')->store('photos', 'public')
            : 'images/default-meal.jpg';

        // Se a categoria for "Almoço" (id = 2), os campos de data serão salvos; caso contrário, eles serão nulos.
        if ($validated['category_id'] == 2) {
            $mealData = [
                'name'           => $validated['name'],
                'description'    => $validated['description'],
                'price'          => $validated['price'],
                'category_id'    => $validated['category_id'],
                'day_week_start' => $validated['day_week_start'],
                'day_of_week'    => strtolower(Carbon::parse($validated['day_week_start'])->locale('pt_PT')->dayName),
                'photo'          => $photoPath,
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
        // Mapeamento de categorias: chave => nome, onde os IDs são definidos manualmente.
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
        ], [
            'name.required'              => 'O nome do prato é obrigatório.',
            'price.required'             => 'O preço é obrigatório.',
            'category_id.required'       => 'O tipo de refeição é obrigatório.',
            'day_week_start.required_if' => 'A data de venda é obrigatória para refeições do tipo Almoço.',
            'description.required'       => 'A descrição é obrigatória.',
            'photo.image'                => 'O arquivo enviado deve ser uma imagem.',
        ]);

        $meal = Meal::findOrFail($id);

        // Se o campo day_week_start estiver vazio no request, mantém o valor atual se existir
        $dayWeekStart = $request->input('day_week_start') ?: $meal->day_week_start;

        // Se a categoria for "Almoço" (id = 2) e houver data definida, atualiza os campos de data;
        // Caso contrário, define como null.
        if ($validatedData['category_id'] == 2 && $dayWeekStart) {
            $validatedData['day_week_start'] = $dayWeekStart;
            $validatedData['day_of_week'] = strtolower(Carbon::parse($dayWeekStart)->locale('pt_PT')->dayName);
        } else {
            $validatedData['day_week_start'] = null;
            $validatedData['day_of_week'] = null;
        }

        $meal->update($validatedData);

        // Se uma nova foto for enviada, faz o upload e remove a antiga (se não for a imagem padrão)
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
     * Remove uma refeição do banco de dados.
     */
    public function destroy(string $id)
    {
        $meal = Meal::findOrFail($id);
        $meal->delete();

        return redirect('adminpanel/manage_meals')->with('success', 'Refeição excluída do menu com sucesso');
    }
}

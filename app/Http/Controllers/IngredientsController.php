<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Ingredient;
use Response;

class IngredientsController extends Controller
{
    public function createIngredient(Request $request)
    {

        $ingredient = new Ingredient();
        $ingredient->Name = $request->name;

        $ingredient->save();

    }

    public function showAddIngredientsPage()
    {
        return view('createIngredient');
    }

    public function getIngredients(Request $request){
        $ingredients = Ingredient::all();

        return Response::json([
            'ingredients'=>$ingredients
        ], 200);
    }
}

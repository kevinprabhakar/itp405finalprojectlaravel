<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\FoodType;
use App\Recipe;
use Response;
use Validator;
use App\Ingredient;
use App\Review;
use App\Direction;
use Illuminate\Support\Facades\Input;
use Auth;
use App\User;

class RecipesController extends Controller
{
    public function CreateRecipe(Request $request){
        $input = $request->all();

        $validation = Validator::make($input, [
            'name'=>'required',
            'foodTypeSelect'=>'required',
            "amount.*"  => "required|alpha_num",
            'ingredient.*' => "required",
            'direction.*'=>"required|min:10",
            'Calories' => 'required|numeric',
            'Fat' => 'required|numeric',
            'Cholesterol' => 'required|numeric',
            'Sugar' => 'required|numeric',
            'Protein' => 'required|numeric',
            'Sodium' => 'required|numeric',
            'Carbs' => 'required|numeric',
        ]);

        if ($validation->fails())
        {
            return response()->json(['errors'=>$validation->errors()], 400);
        };

        $recipe = new Recipe();
        $recipe->Title = $request->input('name');
        $recipe->FoodType = $request->input('foodTypeSelect');
        $recipe->Calories = $request->input('Calories');
        $recipe->Fat = $request->input('Fat');
        $recipe->Cholesterol = $request->input('Cholesterol');
        $recipe->Sugar = $request->input('Sugar');
        $recipe->Protein = $request->input('Protein');
        $recipe->Sodium = $request->input('Sodium');
        $recipe->Carbs = $request->input('Carbs');
        $recipe->UserId = Auth::id();
        $recipe->save();

        $amounts = $request->input('amount.*');
        $ingredients = $request->input('ingredient.*');
        $directions = $request->input('direction.*');

        for ($i = 0; $i < count($amounts); $i++){
            $ingredient = new Ingredient();
            $ingredient->RecipeId = $recipe->id;
            $ingredient->Name = $ingredients[$i];
            $ingredient->Amount = $amounts[$i];
            $ingredient->save();
        }
        for ($i = 0; $i < count($directions); $i++){
            $direction = new Direction();
            $direction->RecipeId = $recipe->id;
            $direction->StepNum = $i + 1;
            $direction->Direction = $directions[$i];
            $direction->save();
        }

        $recipes = Recipe::all();

        return redirect('/recipes');

    }

    public function showCreateRecipesPage(){
        $foodTypes = FoodType::all();

        return view('createRecipe',[
            'foodTypes'=> $foodTypes
        ]);
    }

    public function Index(){
        $recipes = Recipe::all();

        return view('recipesIndex',[
            'isSearchResults'=>false,
            'recipes'=>$recipes
        ]);
    }

    public function GetRecipe($recipeId=null){
        if ($recipeId){
            $recipe = Recipe::find($recipeId);
            $ingredients = Ingredient::where('RecipeId','=',$recipeId)->get();
            $directions = Direction::where('RecipeId','=',$recipeId)->get();
            $user = Auth::id();
            $reviews = Review::where('RecipeId','=',$recipeId)->get();

            $averageRating = 0.0;

            if (count($reviews) != 0){
                foreach ($reviews as $review){
                    $averageRating = $averageRating + $review->AverageRating;
                }

                $averageRating = $averageRating / count($reviews);
            }

            return view('recipeDisplay',[
                'recipe'=>$recipe,
                'ingredients' => $ingredients,
                'directions' => $directions,
                'user'=> $user,
                'reviews' => $reviews,
                'AverageRating' => $averageRating
            ]);
        }
    }

    public function showSearchRecipesPage(){
        $foodTypes = FoodType::all();

        return view('searchRecipes',[
            'foodTypes'=>$foodTypes
        ]);
    }

    public function autocompleteIngredients(Request $request){
        $searchTerm = $request->SearchTerm;
        $ingredients = Ingredient::where('Name','like','%'.$searchTerm.'%')->get();

        return response()->json([
            'ingredients'=>$ingredients
        ]);
    }

    public function SearchRecipes(Request $request){
        $input = $request->all();

        $validation = Validator::make($input,[
            'foodTypeSelect'=>'required',
            'ingredient.*' => "required",
        ]);

        if ($validation->fails()){
            return response()->json(['errors'=>$validation->errors()], 400);
        }

        $ingredients = Ingredient::whereIn('Name',$request->input('ingredient'))->get();
        $recipes = array();
        foreach ($ingredients as $ingredient){
            $recipe = Recipe::find($ingredient->RecipeId);
            if (($recipe->FoodType == $request->input('foodTypeSelect'))or($request->input('foodTypeSelect') == 'Any')){
                array_push($recipes, $recipe);
            }
        }

        return view('recipesIndex',[
            'isSearchResults'=>true,
            'recipes'=>$recipes
        ]);
    }
}

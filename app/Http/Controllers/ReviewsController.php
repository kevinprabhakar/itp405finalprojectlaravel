<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Review;
use App\Recipe;
use Validator;
use Auth;
use Response;

class ReviewsController extends Controller
{
    public function CreateReview(Request $request, $recipeId=null){
        $input = $request->all();


        $validator = Validator::make($input,[
            'Rating'=>'required|numeric',
            'Body'=>'required|min:10|max:2000',
            'Title'=>'required'
        ]);

        if ($validator->fails())
        {
            return redirect("/reviews/new/".$recipeId)
                    ->withInput()
                    ->withErrors($validator);
        };

        $review = new Review();
        $review->RecipeId = $recipeId;
        $review->UserId = Auth::id();
        $review->Body = $request->Body;
        $review->Title = $request->Title;
        $review->AverageRating = $request->Rating;
        $review->save();

        return redirect('/recipes/'.$recipeId);
    }

    public function showCreateReviewPage($recipeId=null){
        $recipe = Recipe::find($recipeId);
        return view('createReview',[
            'recipe'=>$recipe
        ]);
    }
}

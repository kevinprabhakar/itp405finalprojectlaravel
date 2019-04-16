<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/recipes');
});


Route::post('/signup', 'SignUpController@signup');
Route::get('/signup','SignUpController@index');
Route::get('/login', 'LoginController@index');
Route::get('/logout', function(){
    return redirect('/recipes');
});
Route::post('/login','LoginController@login');
Route::post("/autocomplete",'RecipesController@autocompleteIngredients');

Route::get('/recipes','RecipesController@Index');



Route::middleware(['Authenicated'])->group(function(){
    Route::post('/recipes/new','RecipesController@CreateRecipe');
    Route::get('/recipes/new','RecipesController@showCreateRecipesPage');
    Route::get('/recipes/{recipeId}','RecipesController@GetRecipe');

    Route::post('/reviews/new/{recipeId}','ReviewsController@CreateReview');
    Route::get('/reviews/new/{recipeId}','ReviewsController@showCreateReviewPage');

    Route::get('/search','RecipesController@showSearchRecipesPage');
    Route::post('/search','RecipesController@SearchRecipes');


});

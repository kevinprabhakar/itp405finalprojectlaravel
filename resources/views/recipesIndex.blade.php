@extends('layout')

@section('title','Recipes Home')

<!-- Defines a section -->
@section('main')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <div class="row">
        <div class="col">
            @if ($isSearchResults)
            <h1>Search Results</h1>
            @endif
            @forelse ($recipes as $recipe)
            <h2><a href="/recipes/{{$recipe->id}}">{{$recipe->Title}}</a></h2>
            <table class="table table-bordered">
                <tr>
                    <td>{{$recipe->Calories}} calories</td>
                    <td>{{$recipe->Fat}} g of Fat</td>
                    <td>{{$recipe->Cholesterol}} g of Cholesterol</td>
                    <td>{{$recipe->Sugar}} g of Sugar</td>
                    <td>{{$recipe->Protein}} g of Protein</td>
                    <td>{{$recipe->Sodium}} g of Sodium</td>
                    <td>{{$recipe->Carbs}} g of Carbs</td>
                </tr>
            </table>
            @empty
            <h2>No Recipes</h2>
            @endforelse
        </div>
    </div>
@endsection

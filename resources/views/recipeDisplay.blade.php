@extends('layout')

@section('title','{{$recipe->Title}}')

<!-- Defines a section -->
@section('main')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <div class="row">
        <div class="col">
            <h1>{{$recipe->Title}}</h1>
            Rating: {{$AverageRating}}
            <h2>Ingredients</h2>

            <ul>
            @forelse ($ingredients as $ingredient)
                <li>{{$ingredient->Amount}} {{$ingredient->Name}}</li>
            @empty
                <li>No Ingredients!</li>
            @endforelse
            </ul>

            <h2>Directions</h2>
            <ol type="1">
                @forelse ($directions as $direction)
                    <li>{{$direction->Direction}}</li>
                @empty
                    No Directions!
                @endforelse
            </ol>


            <a href="/reviews/new/{{$recipe->id}}"><button class="btn btn-primary">Add a Review!</button></a>
            <h3>Reviews</h3>
                @forelse ($reviews as $review)
                    <h4>{{$review->Title}}</h4>
                    Rating: {{$review->AverageRating}}
                    <br>
                    {{$review->Body}}
                    <br>
                @empty
                No reviews for this recipe.
                @endforelse
        </div>
    </div>
@endsection

@extends('layout')

@section('title','Add a recipe')

<!-- Defines a section -->
@section('main')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function(){
             var i=1;



             $('#addIng').click(function(){
                  i++;
                  $('#dynamic_field').append('<tr id="row'+i+'" class="dynamic-added"><td><input type="text" name="amount[]" placeholder="Enter your ingredient amount" class="form-control amount_list" /></td><td><input type="text" name="ingredient[]" placeholder="Enter your ingredient" class="form-control ingredient_list" /></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');
             });

             $('#addDir').click(function(){
                  i++;
                  $('#dynamic_field2').append('<tr id="row'+i+'" class="dynamic-added"><td><input type="text" name="direction[]" placeholder="Enter your directions" class="form-control amount_list" /></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');
             });


             $(document).on('click', '.btn_remove', function(){
                  var button_id = $(this).attr("id");
                  $('#row'+button_id+'').remove();
             });

             $('#recipesForm').submit(function(e){
                 e.preventDefault();

                 var form = $(this);
                var urlReal = form.attr('action');


                  $.ajax({
                      url: urlReal,
                      type: 'POST',
                      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                      data: {
                          name: $('#name').val(),
                          foodTypeSelect: $("#foodTypeSelect").val(),
                          amount: $("input[name='amount[]']").map(function() { return this.value; }).get(),
                          ingredient:$("input[name='ingredient[]']").map(function() { return this.value; }).get(),
                          direction:$("input[name='direction[]']").map(function() { return this.value; }).get(),
                          Calories: $("#Calories").val(),
                          Fat: $("#Fat").val(),
                          Cholesterol: $("#Cholesterol").val(),
                          Sugar: $("#Sugar").val(),
                          Protein: $("#Protein").val(),
                          Sodium: $("#Sodium").val(),
                          Carbs: $("#Carbs").val()
                      },
                      success: function(response){
                          $('#recipesForm').unbind('submit').submit();
                      },
                      error: function(response){
                          laravelErrors = response["responseJSON"]
                          if (laravelErrors["errors"]){
                              keys = Object.keys(laravelErrors["errors"]);
                              for (var i = 0; i< keys.length; i += 1){
                                  if ((keys[i].includes('amount')) || (keys[i].includes('ingredient'))){
                                      $("#ingredientsError").text(laravelErrors["errors"][keys[i]][0]);
                                  }
                                  if (keys[i].includes('direction')){
                                      $("#directionsError").text(laravelErrors["errors"][keys[i]][0]);
                                  }
                                  if (keys[i].includes('name')){
                                      $("#nameError").text(laravelErrors["errors"][keys[i]][0]);
                                  }
                                  if (keys[i].includes('Fat')){
                                      $("#fatError").text(laravelErrors["errors"][keys[i]][0]);
                                  }
                                  if (keys[i].includes('Calories')){
                                      $("#caloriesError").text(laravelErrors["errors"][keys[i]][0]);
                                  }
                                  if (keys[i].includes('Cholesterol')){
                                      $("#cholesterolError").text(laravelErrors["errors"][keys[i]][0]);
                                  }
                                  if (keys[i].includes('Sugar')){
                                      $("#sugarError").text(laravelErrors["errors"][keys[i]][0]);
                                  }
                                  if (keys[i].includes('Protein')){
                                      $("#proteinError").text(laravelErrors["errors"][keys[i]][0]);
                                  }
                                  if (keys[i].includes('Sodium')){
                                      $("#sodiumError").text(laravelErrors["errors"][keys[i]][0]);
                                  }
                                  if (keys[i].includes('Carbs')){
                                      $("#carbsError").text(laravelErrors["errors"][keys[i]][0]);
                                  }
                              }
                          }
                      }
                  })
             })

         })

    </script>
    <div class="row">
        <div class="col">
            <form id="recipesForm" action="/recipes/new" method="post">
                @csrf
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{old('name')}}">
                    <span class="text-danger" id="nameError"></span>
                    <br>

                    <label for="foodTypeSelect">Select food type:</label>
                    <select class="form-control" name="foodTypeSelect" id="foodTypeSelect">
                      @forelse ($foodTypes as $foodType)
                          <option value="{{$foodType->Type}}" @if ($foodType->Type == old('foodTypeSelect')) selected="selected" @endif>
                              {{$foodType->Type}}
                          </option>
                      @empty
                          <option>None</option>
                      @endforelse
                    </select>
                    <small class="text-danger">{{$errors->first('foodTypeSelect')}}</small>

                    <br>
                    <label for="ingredients">Ingredients</label>
                    <div class="table-responsive" id ="ingredients">
                        <span class="text-danger" id="ingredientsError"></span>
                        <table class="table table-bordered" id="dynamic_field">
                            <tr>
                                <td><input type="text" name="amount[]" placeholder="Enter your ingredient amount" class="form-control amount_list" /></td>
                                <td><input type="text" name="ingredient[]" placeholder="Enter your ingredient" class="form-control ingredient_list" /></td>
                                <td><button type="button" name="addIng" id="addIng" class="btn btn-success">Add More</button></td>
                            </tr>
                        </table>
                    </div>

                    <label for="directions">Directions</label>
                    <div class="table-responsive" id ="directions">
                        <span class="text-danger" id="directionsError"></span>

                        <table class="table table-bordered" id="dynamic_field2">
                            <tr>
                                <td><input type="text" name="direction[]" placeholder="Enter your directions" class="form-control amount_list" /></td>
                                <td><button type="button" name="addDir" id="addDir" class="btn btn-success">Add More</button></td>
                            </tr>
                        </table>
                    </div>

                    <br>
                    <label for="Calories">Calories</label>
                    <span class="text-danger" id="caloriesError"></span>

                    <input type="text" name="Calories" id="Calories" class="form-control" value="{{old('Calories')}}">

                    <br>
                    <label for="Fat">Fat</label>
                    <span class="text-danger" id="fatError"></span>

                    <input type="text" name="Fat" id="Fat" class="form-control" value="{{old('Fat')}}">

                    <br>
                    <label for="Cholesterol">Cholesterol</label>
                    <span class="text-danger" id="cholesterolError"></span>

                    <input type="text" name="Cholesterol" id="Cholesterol" class="form-control" value="{{old('Cholesterol')}}">

                    <br>
                    <label for="Sugar">Sugar</label>
                    <span class="text-danger" id="sugarError"></span>

                    <input type="text" name="Sugar" id="Sugar" class="form-control" value="{{old('Sugar')}}">

                    <br>
                    <label for="Protein">Protein</label>
                    <span class="text-danger" id="proteinError"></span>

                    <input type="text" name="Protein" id="Protein" class="form-control" value="{{old('Protein')}}">

                    <br>
                    <label for="Sodium">Sodium</label>
                    <span class="text-danger" id="sodiumError"></span>

                    <input type="text" name="Sodium" id="Sodium" class="form-control" value="{{old('Sodium')}}">

                    <br>
                    <label for="Carbs">Carbs</label>
                    <span class="text-danger" id="carbsError"></span>

                    <input type="text" name="Carbs" id="Carbs" class="form-control" value="{{old('Carbs')}}">



                </div>
                <button id='submit' class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
@endsection

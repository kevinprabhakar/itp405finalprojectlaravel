@extends('layout')

@section('title','Search Recipes')

<!-- Defines a section -->
@section('main')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
             var i=1;

             $('#addIng').click(function(){
                  i++;
                  $('#dynamic_field').append('<tr id="row'+i+'" class="dynamic-added"><td><input type="text" list="json-datalist'+i+'"id="input'+i+'"name="ingredient[]" placeholder="Enter your ingredient" class="form-control ingredient_list" /></td></datalist><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');
                  $('#ingredients').append('<datalist id="json-datalist'+i+'">');
             });



             $(document).on('click', '.btn_remove', function(){
                  var button_id = $(this).attr("id");
                  $('#row'+button_id+'').remove();
             });

             $(document).on('keyup','input[id^="input"]',
                function(){
                    var inputName = this.id;
                    var dataListName = this.list.id;
                    $.ajaxSetup({
                      headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                      type: "POST",
                      url: '/autocomplete',
                      dataType:"json",
                      data: {"SearchTerm":this.value},
                      success: function(resp){
                          ingredients = resp["ingredients"];
                          console.log(ingredients);
                          if (ingredients.length !== 0){
                              var dataList = document.getElementById(dataListName);
                              dataList.innerHTML = '';
                              ingredients.forEach(function(item) {
                                // Create a new <option> element.
                                var option = document.createElement('option');
                                // Set the value using the item in the JSON array.
                                option.value = item.Name;
                                // Add the <option> element to the <datalist>.
                                dataList.appendChild(option);
                              });

                          }

                      },
                    });
                }
            );

            $('#searchForm').submit(function(e){
                e.preventDefault();
                e.stopImmediatePropagation();

                var form = $(this);
               var urlReal = form.attr('action');

                 $.ajax({
                     url: urlReal,
                     type: 'POST',
                     headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                     data: {
                         foodTypeSelect: $("#foodTypeSelect").val(),

                         ingredient:$("input[name='ingredient[]']").map(function() { return this.value; }).get(),

                     },
                     success: function(data){
                         $('.container-fluid').html(data);
                     },
                     error: function(response){
                         laravelErrors = response["responseJSON"]
                         if (laravelErrors["errors"]){
                             keys = Object.keys(laravelErrors["errors"]);
                             for (var i = 0; i< keys.length; i += 1){
                                 if (keys[i].includes('ingredient')){
                                     $("#ingredientsError").text(laravelErrors["errors"][keys[i]][0]);
                                 }
                                 if (keys[i].includes('foodTypeSelect')){
                                     $("#foodTypeSelectError").text(laravelErrors["errors"][keys[i]][0]);
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
            <form id="searchForm" action="/search" method="post">
                @csrf
                <div class="form-group">
                    <label for="foodTypeSelect">Select food type:</label>
                    <select class="form-control" name="foodTypeSelect" id="foodTypeSelect">
                          <option value="Any">Any</option>
                          @foreach ($foodTypes as $foodType)
                          <option value="{{$foodType->Type}}" @if ($foodType->Type == old('foodTypeSelect')) selected="selected" @endif>
                              {{$foodType->Type}}
                          </option>
                      @endforeach
                    </select>
                    <span class="text-danger" id="foodTypeSelectError"></span>

                    <br>
                    <label for="ingredients">Ingredients</label>
                    <div class="table-responsive" id ="ingredients">
                        <span class="text-danger" id="ingredientsError"></span>

                        <table class="table table-bordered" id="dynamic_field">
                            <tr>
                                <td><input type="text" name="ingredient[]" id="input0" placeholder="Enter your ingredient" list="json-datalist0" class="form-control ingredient_list" /></td>
                                <datalist id="json-datalist0"></datalist>
                                <td><button type="button" name="addIng" id="addIng" class="btn btn-success">Add More</button></td>
                            </tr>
                        </table>
                    </div>

                </div>
                <button type="submit" id="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
@endsection

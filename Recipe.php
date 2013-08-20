<?php
   //Empty Array to hold all the recipes
   $Json = [];
    
   $UserID = 'ff5a5852';//Your ID Here
    
   $UserKey = '32f9b07cad4cfd3a4de034061c37c6f5';//Your Yummly key
   
   $aContext = array(
       'http' => array(
           'proxy' => 'tcp://85.94.33.20:8080',
           'request_fulluri' => true,
       ),
   );

   $cxContext = stream_context_create($aContext);

   //This searches Yummly for cake recipes
   $Recipes = file_get_contents("http://api.yummly.com/v1/api/recipes?_app_id=ff5a5852&_app_key=c39c09a5fd9e31edae4a9cfa619a5a1c&maxResult=2&requirePictures=true&q=Cake", False, $cxContext);


   //Decode the JSON into a php object
   $Recipes = json_decode($Recipes)->matches;
    
   // var_dump($Recipes);
   //Cycle Through The Recipes and Get full recipe for each
   foreach($Recipes as $Recipe)
   {
      
      $aContext = array(
          'http' => array(
              'proxy' => 'tcp://85.94.33.20:8080',
              'request_fulluri' => true,
          ),
      );

      $cxContext = stream_context_create($aContext);


      $ID = $Recipe->id;
      $R = json_decode(file_get_contents("http://api.yummly.com/v1/api/recipe/" . $ID . "?_app_id=ff5a5852&_app_key=c39c09a5fd9e31edae4a9cfa619a5a1c&images=large", False, $cxContext));
       
       
      //This is the data we are going to pass to our Template
      array_push($Json, array(
         Name => $R->name,
         Ingredients => $R->ingredientLines,
         Image => $R->images[0]->hostedLargeUrl,
         Yield => $R->yield,
         Flavors => $R->flavors,
         Source => array(
            Name => $R->source->sourceDisplayName,
            Url => $R->source->sourceRecipeUrl
         )
      ));
   }
    
   //Print out the final JSON object
   echo json_encode($Json);
?>
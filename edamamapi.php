<?php
    function search_recipes($ingredients, $page, $cuisineType, $mealType, $max_pages = 1) {
        // Your Edamam Recipe API credentials
        $app_id = 'c76d15f1';
        $app_key = 'fa3e758a9709dd80d1d85a0b1bef6adf';
        $base_url = 'https://api.edamam.com/search';

        // Calculate the starting and ending indexes
        $from = ($page - 1) * 12; // Calculate starting index for current page
        $to = $from + 12; // Calculate ending index for current page

        // Build the query string
        $query = array(
            'app_id' => $app_id,
            'app_key' => $app_key,
            'q' => implode(',', $ingredients),
            'from' => $from,
            'to' => $to,
            'cuisineType' => $cuisineType,
            'mealType' => $mealType
        );


        $url = $base_url . '?' . http_build_query($query);

        // Make the API request
        $result = file_get_contents($url);

        // Parse the response
        $data = json_decode($result, true);

        // Get the total number of hits
        $recipes = $data['hits'];
        $total_hits = $data['count'];

        $total_pages = min(ceil($total_hits / 12), $max_pages); // Calculate total number of pages

        // Extract the recipe information from the response
        $recipes = array();
        foreach ($data['hits'] as $hit) {
            $recipe = $hit['recipe'];
            $recipes[] = array(
            'label' => $recipe['label'],
            'image' => $recipe['image'],
            'uri' => $recipe['uri'],
            'url' => $recipe['url'],
            'source' => $recipe['source']
            );
        }

        return array(
            'recipes' => $recipes,
            'total_pages' => $total_pages
        );
    }

    function extract_recipe_id($uri) {
        $recipe_id = substr($uri, strrpos($uri, '#') + 1);
        return $recipe_id;
    }

function get_recipe_details($recipe_id) {
    // Replace YOUR_APP_ID and YOUR_APP_KEY with your actual Edamam API credentials
    $app_id = 'c76d15f1';
    $app_key = 'fa3e758a9709dd80d1d85a0b1bef6adf';

    // Construct the API endpoint URL with the recipe ID
    $endpoint = 'https://api.edamam.com/api/recipes/v2/' . $recipe_id . '?type=public&app_id=' . $app_id . '&app_key=' . $app_key;

    // Make a GET request to the API endpoint
    $response = file_get_contents($endpoint);

    // Decode the JSON response into an associative array
    $recipe_details = json_decode($response, true);

    // Return the recipe details
    return $recipe_details;
}

?>

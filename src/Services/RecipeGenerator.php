<?php

namespace App\Services;

use OpenAI;
use OpenAI\Client;
use App\Models\Recipe;

class RecipeGenerator
{
    private Client $client;

    public function __construct()
    {
        $this->client = \OpenAI::client($_ENV['OPENAI_API_KEY']);
    }

    public function generateRecipe(array $parameters): Recipe
    {
        $prompt = $this->buildPrompt($parameters);
        
        $response = $this->client->chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'system', 'content' => 'You are a professional chef and recipe creator. Create detailed, accurate, and delicious recipes.'],
                ['role' => 'user', 'content' => $prompt]
            ],
            'temperature' => 0.7,
        ]);

        $recipeData = $this->parseAIResponse($response->choices[0]->message->content);
        
        $recipe = new Recipe();
        $recipe->title = $recipeData['title'];
        $recipe->ingredients = $recipeData['ingredients'];
        $recipe->instructions = $recipeData['instructions'];
        $recipe->cooking_time = $recipeData['cooking_time'];
        $recipe->difficulty = $recipeData['difficulty'];
        
        return $recipe;
    }

    private function buildPrompt(array $parameters): string
    {
        $prompt = "Create a recipe with the following specifications:\n";
        
        if (!empty($parameters['ingredients'])) {
            $prompt .= "- Must include these ingredients: " . implode(', ', $parameters['ingredients']) . "\n";
        }
        
        if (!empty($parameters['cuisine'])) {
            $prompt .= "- Cuisine type: " . $parameters['cuisine'] . "\n";
        }
        
        if (!empty($parameters['dietary_restrictions'])) {
            $prompt .= "- Dietary restrictions: " . implode(', ', $parameters['dietary_restrictions']) . "\n";
        }
        
        if (!empty($parameters['cooking_time'])) {
            $prompt .= "- Maximum cooking time: " . $parameters['cooking_time'] . " minutes\n";
        }
        
        if (!empty($parameters['difficulty'])) {
            $prompt .= "- Difficulty level: " . $parameters['difficulty'] . "\n";
        }
        
        $prompt .= "\nPlease provide the recipe in the following format:\n";
        $prompt .= "Title: [Recipe Title]\n";
        $prompt .= "Ingredients: [List of ingredients]\n";
        $prompt .= "Instructions: [Step-by-step instructions]\n";
        $prompt .= "Cooking Time: [Time in minutes]\n";
        $prompt .= "Difficulty: [Easy/Medium/Hard]";
        
        return $prompt;
    }

    private function parseAIResponse(string $response): array
    {
        $lines = explode("\n", $response);
        $recipeData = [
            'title' => '',
            'ingredients' => [],
            'instructions' => [],
            'cooking_time' => 0,
            'difficulty' => 'Medium'
        ];

        $currentSection = '';
        
        foreach ($lines as $line) {
            if (strpos($line, 'Title:') === 0) {
                $recipeData['title'] = trim(substr($line, 6));
            } elseif (strpos($line, 'Ingredients:') === 0) {
                $currentSection = 'ingredients';
            } elseif (strpos($line, 'Instructions:') === 0) {
                $currentSection = 'instructions';
            } elseif (strpos($line, 'Cooking Time:') === 0) {
                $recipeData['cooking_time'] = (int)trim(substr($line, 13));
            } elseif (strpos($line, 'Difficulty:') === 0) {
                $recipeData['difficulty'] = trim(substr($line, 10));
            } elseif (!empty($line) && in_array($currentSection, ['ingredients', 'instructions'])) {
                $recipeData[$currentSection][] = trim($line);
            }
        }
        
        return $recipeData;
    }
} 
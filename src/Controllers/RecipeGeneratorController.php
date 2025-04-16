<?php

namespace App\Controllers;

use App\Services\RecipeGenerator;
use App\Models\Recipe;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class RecipeGeneratorController
{
    private RecipeGenerator $recipeGenerator;

    public function __construct(RecipeGenerator $recipeGenerator)
    {
        $this->recipeGenerator = $recipeGenerator;
    }

    public function generate(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        
        try {
            $recipe = $this->recipeGenerator->generateRecipe([
                'ingredients' => $data['ingredients'] ?? [],
                'cuisine' => $data['cuisine'] ?? null,
                'dietary_restrictions' => $data['dietary_restrictions'] ?? [],
                'cooking_time' => $data['cooking_time'] ?? null,
                'difficulty' => $data['difficulty'] ?? null,
            ]);

            $response->getBody()->write(json_encode([
                'status' => 'success',
                'data' => [
                    'title' => $recipe->title,
                    'ingredients' => $recipe->ingredients,
                    'instructions' => $recipe->instructions,
                    'cooking_time' => $recipe->cooking_time,
                    'difficulty' => $recipe->difficulty,
                ]
            ]));

            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                'status' => 'error',
                'message' => 'Failed to generate recipe: ' . $e->getMessage()
            ]));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }
    }
} 
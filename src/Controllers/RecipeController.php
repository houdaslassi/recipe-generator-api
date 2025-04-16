<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Recipe;

class RecipeController
{
    public function index(Request $request, Response $response): Response
    {
        $recipes = Recipe::all();
        
        $response->getBody()->write(json_encode([
            'status' => 'success',
            'data' => $recipes
        ]));
        
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function show(Request $request, Response $response, array $args): Response
    {
        $recipe = Recipe::find($args['id']);
        
        if (!$recipe) {
            $response->getBody()->write(json_encode([
                'status' => 'error',
                'message' => 'Recipe not found'
            ]));
            return $response->withStatus(404);
        }
        
        $response->getBody()->write(json_encode([
            'status' => 'success',
            'data' => $recipe
        ]));
        
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function create(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        
        $recipe = new Recipe();
        $recipe->title = $data['title'];
        $recipe->ingredients = json_encode($data['ingredients']);
        $recipe->instructions = $data['instructions'];
        $recipe->cooking_time = $data['cooking_time'];
        $recipe->difficulty = $data['difficulty'];
        $recipe->save();
        
        $response->getBody()->write(json_encode([
            'status' => 'success',
            'message' => 'Recipe created successfully',
            'data' => $recipe
        ]));
        
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        $recipe = Recipe::find($args['id']);
        
        if (!$recipe) {
            $response->getBody()->write(json_encode([
                'status' => 'error',
                'message' => 'Recipe not found'
            ]));
            return $response->withStatus(404);
        }
        
        $data = $request->getParsedBody();
        
        $recipe->title = $data['title'] ?? $recipe->title;
        $recipe->ingredients = isset($data['ingredients']) ? json_encode($data['ingredients']) : $recipe->ingredients;
        $recipe->instructions = $data['instructions'] ?? $recipe->instructions;
        $recipe->cooking_time = $data['cooking_time'] ?? $recipe->cooking_time;
        $recipe->difficulty = $data['difficulty'] ?? $recipe->difficulty;
        $recipe->save();
        
        $response->getBody()->write(json_encode([
            'status' => 'success',
            'message' => 'Recipe updated successfully',
            'data' => $recipe
        ]));
        
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        $recipe = Recipe::find($args['id']);
        
        if (!$recipe) {
            $response->getBody()->write(json_encode([
                'status' => 'error',
                'message' => 'Recipe not found'
            ]));
            return $response->withStatus(404);
        }
        
        $recipe->delete();
        
        $response->getBody()->write(json_encode([
            'status' => 'success',
            'message' => 'Recipe deleted successfully'
        ]));
        
        return $response->withHeader('Content-Type', 'application/json');
    }
} 
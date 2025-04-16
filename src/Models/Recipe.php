<?php

namespace App\Models;

use PDO;

class Recipe
{
    private static $pdo;
    public $id;
    public $title;
    public $ingredients;
    public $instructions;
    public $cooking_time;
    public $difficulty;
    public $created_at;
    public $updated_at;

    public static function init(PDO $pdo)
    {
        self::$pdo = $pdo;
    }

    public static function all()
    {
        $stmt = self::$pdo->query('SELECT * FROM recipes ORDER BY created_at DESC');
        return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    public static function find($id)
    {
        $stmt = self::$pdo->prepare('SELECT * FROM recipes WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetchObject(self::class);
    }

    public function save()
    {
        if (isset($this->id)) {
            // Update
            $stmt = self::$pdo->prepare('
                UPDATE recipes 
                SET title = ?, ingredients = ?, instructions = ?, cooking_time = ?, difficulty = ?, updated_at = NOW()
                WHERE id = ?
            ');
            $stmt->execute([
                $this->title,
                $this->ingredients,
                $this->instructions,
                $this->cooking_time,
                $this->difficulty,
                $this->id
            ]);
        } else {
            // Create
            $stmt = self::$pdo->prepare('
                INSERT INTO recipes (title, ingredients, instructions, cooking_time, difficulty, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, NOW(), NOW())
            ');
            $stmt->execute([
                $this->title,
                $this->ingredients,
                $this->instructions,
                $this->cooking_time,
                $this->difficulty
            ]);
            $this->id = self::$pdo->lastInsertId();
        }
    }

    public function delete()
    {
        $stmt = self::$pdo->prepare('DELETE FROM recipes WHERE id = ?');
        $stmt->execute([$this->id]);
    }

    public function getIngredients()
    {
        return json_decode($this->ingredients, true);
    }

    public function setIngredients($ingredients)
    {
        $this->ingredients = json_encode($ingredients);
    }
} 
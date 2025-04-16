# Recipe Generator API

A PHP-based API for managing and generating recipes using AI technology.

## Features

- AI-powered recipe generation based on ingredients and preferences
- RESTful API endpoints for recipe management
- Support for dietary restrictions and cuisine types
- Customizable cooking time and difficulty levels

## Installation

1. Clone the repository:
```bash
git clone [repository-url]
cd recipe-generator-api
```

2. Install dependencies:
```bash
composer install
```

3. Configure environment:
   - Copy `.env.example` to `.env`
   - Add your OpenAI API key to `.env`:
     ```
     OPENAI_API_KEY=your_api_key_here
     ```
   - Configure your database connection:
     ```
     DB_HOST=localhost
     DB_NAME=recipe_generator
     DB_USER=your_username
     DB_PASS=your_password
     ```

4. Set up the database:
```bash
# Create the database
mysql -u your_username -p -e "CREATE DATABASE recipe_generator;"

# Run migrations
php database/migrations/migrate.php
```

## API Endpoints

### Generate Recipe
```
POST /api/recipes/generate
```

Request body:
```json
{
    "ingredients": ["chicken", "rice", "vegetables"],
    "cuisine": "italian",
    "dietary_restrictions": ["gluten-free"],
    "cooking_time": 30,
    "difficulty": "medium"
}
```

Response:
```json
{
    "status": "success",
    "data": {
        "title": "Recipe Title",
        "ingredients": ["ingredient1", "ingredient2", ...],
        "instructions": ["step1", "step2", ...],
        "cooking_time": 30,
        "difficulty": "medium"
    }
}
```

### Recipe Management

- `GET /api/recipes` - List all recipes
- `GET /api/recipes/{id}` - Get a specific recipe
- `POST /api/recipes` - Create a new recipe
- `PUT /api/recipes/{id}` - Update a recipe
- `DELETE /api/recipes/{id}` - Delete a recipe

## Requirements

- PHP 8.0 or higher
- Composer
- OpenAI API key
- MySQL/MariaDB

## Technologies Used

- Slim Framework
- OpenAI PHP Client
- PHP-DI (Dependency Injection)
- MySQL/MariaDB

## Project Structure

The project follows a modular structure:

```
src/
├── Controllers/    # Request handlers
├── Models/        # Data models
├── Config/        # Configuration files
└── app.php        # Application entry point

database/
└── migrations/    # Database schema files

public/
└── index.php      # Entry point for web requests
```


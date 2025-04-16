# Recipe Generator API

A PHP-based API for managing and generating recipes.

## Installation

1. Clone the repository:
```bash
git clone [repository-url]
cd recipe-generator
```

2. Install dependencies:
```bash
composer install
```

3. Configure environment:
```bash
cp .env.example .env
# Edit .env with your configuration
```

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
```

## Dependencies

- PHP 8.1 or higher
- Slim Framework 4.x
- Doctrine DBAL
- PHP-DI
- vlucas/phpdotenv

## Development

To start the development server:
```bash
php -S localhost:8000 -t public
```

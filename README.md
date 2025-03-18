# Symfony 6 Web Application

This is a web application built with Symfony 6, a powerful PHP framework for building modern web applications.

## Prerequisites

- PHP 8.1 or higher
- Composer
- MySQL or PostgreSQL
- Symfony CLI (recommended)
- Node.js and npm (for asset management)

## Installation

1. Clone the repository:
```bash
git clone <your-repository-url>
cd web_symfony_6
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install JavaScript dependencies:
```bash
npm install
```

4. Create the `.env.local` file:
```bash
cp .env .env.local
```

5. Configure your database connection in `.env.local`:
```
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name"
```

6. Create the database and run migrations:
```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

7. Build assets:
```bash
npm run build
```

## Development

Start the Symfony development server:
```bash
symfony server:start
```

For development with hot-reloading of assets:
```bash
npm run watch
```

## Testing

Run tests with PHPUnit:
```bash
php bin/phpunit
```

## Deployment

1. Set up your production environment variables
2. Install dependencies without dev dependencies:
```bash
composer install --no-dev --optimize-autoloader
```
3. Clear and warm up cache:
```bash
APP_ENV=prod APP_DEBUG=0 php bin/console cache:clear
```
4. Build assets for production:
```bash
npm run build

## Contributing

1. Create a new branch for your feature
2. Make your changes
3. Submit a pull request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

# web_symfony_6
The goal of this project is to build a web platform services with symfony 6, with of methodology of Test Driven Design
At the same time I am preparing symfony Certification. I created a MCQ for questions and answers on the subject. The database with questions is private for legal reason. 

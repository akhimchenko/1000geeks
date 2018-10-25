# 1000geeks

## Deployment

Create .env file based on .env.dist - enter your database params. Create a new database if necessary.

Install modules with composer in project directory:

```
composer install
```

Run migrations:

```
bin/console doctrine:migrations:migrate
```

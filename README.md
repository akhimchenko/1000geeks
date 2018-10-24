# 1000geeks

Развёртывание:

Сделать .env на основе .env.dist - ввести в DATABASE_URL параметры своей базы. Создать базу, если надо.
Скачать компоненты - composer install
Запустить миграции - bin/console doctrine:migrations:migrate

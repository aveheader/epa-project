# Order Service

## Описание

Order Service — это простое веб-приложение на Symfony для создания заявки на услугу с серверной валидацией и сохранением данных в базу данных MySQL.

Реализовано:

* авторизация через Symfony Security
* форма создания заявки
* серверная валидация через DTO
* динамическое обновление стоимости услуги (Twig + JS)
* функциональные тесты на базе WebTestCase

---

## Доступ к приложению

После запуска приложение доступно по адресу:

```
http://localhost
```

---

## Разворачивание проекта

Проект докеризирован. Рекомендуется использовать Makefile.

### Сборка

```bash
make build
```

или

```bash
docker compose build --pull --no-cache
```

---

### Запуск

```bash
make up
```

или

```bash
docker compose up --detach --wait
```

---

## Первый запуск

Применить миграции:

```bash
make db-migrate
```

Загрузить фикстуры (создаётся тестовый пользователь):

```bash
make load
```

Тестовый пользователь:

* Email: `user@yandex.ru`
* Пароль: `password`

---

## Полезные команды

Войти в контейнер PHP:

```bash
make bash
```

Запустить тесты:

```bash
make test
```

или

```bash
php bin/phpunit
```

Загрузить фикстуры:

```bash
make load
```

Если возникают проблемы с правами доступа:

```bash
make chown
```

---

## База данных

Приложение использует MySQL.

Параметры подключения:

* Host: `localhost`
* Port: `33060`
* User: `app`
* Password: `!ChangeMe!`
* Database: `app`

Для тестов используется отдельная база данных:

* Database: `app_test`

---

Docker-окружение основано на:
[https://github.com/dunglas/symfony-docker](https://github.com/dunglas/symfony-docker)

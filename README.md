# Описание

## Общее

Order Service — это простое веб-приложение на Symfony, реализующее форму создания заявки на услугу с серверной валидацией и сохранением данных в файл.

## Разворачивание проекта

Проект докеризирован, для разворачивания проекта необходимо собрать его и запустить.
Настоятельно рекомендуется использовать [Makefile](https://ru.wikipedia.org/wiki/Makefile).

### Сборка

```bash
make build
```

Или без Makefile:
```bash
docker compose build --pull --no-cache
```

### Запуск

```bash
make up
```

Или без Makefile:
```bash
docker compose up --detach --wait
```

### Первый запуск

Перед первым запуском необходимо исполнить миграции:

```bash
make db-migrate
```

## Полезные команды

Зайти в контейнер php (только при запущенном проекте):
```bash
make bash
```

Исполнить тесты:
```bash
make test
```
или
```bash
php bin/phpunit
```

В тестовом окружении заявки записываются в:
```bash
var/orders_test.jsonl
```

В обычном окружении:
```bash
var/orders.jsonl
```

Запустить фикстуры:
```bash
make load
```

Если IDE ругается на сохранение файлов:
```bash
make chown
```

## Дополнительная информация

Docker окружение честно взято [отсюда](https://github.com/dunglas/symfony-docker).

Конфигурация docker-compose для локального окружения находится в файле `compose.override.yaml`.

Для получения доступа к БД необходимо подключаться со следующими параметрами:
- Host: `localhost`
- Port: `54320`
- User: `app`
- Pass: `!ChangeMe!`
- Database: `app`

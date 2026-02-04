# Rest API Skeleton + MoonShine Admin

Laravel-проект с **REST API** (JWT) и **админ-панелью MoonShine** (сессионная авторизация). Два независимых контура: API для мобильных/внешних клиентов и админка для управления контентом и пользователями админки.

## Требования

- **PHP** ≥ 8.2 (расширения: mbstring, tokenizer, xml, pdo, json, openssl)
- **Composer**
- **Node.js** и **npm** (для фронта/сборки)
- **БД**: PostgreSQL или MySQL
- **Redis** (опционально, для кэша/очередей)

## Архитектура

| Контур        | URL-префикс | Авторизация | Пользователи              |
|---------------|-------------|-------------|----------------------------|
| **REST API**  | `/api/v1`   | JWT (guard `api`) | Таблица `users`          |
| **Админка**  | `/admin`    | Сессия (guard `moonshine`) | Таблица `moonshine_users` |

- API: токены через `POST /api/v1/auth/login`, далее заголовок `Authorization: Bearer <token>`.
- Админка: логин по email/паролю на `/admin/login`, сессия и cookie.

## Развёртывание приложения

### 1. Клонирование и зависимости

```bash
git clone <url-репозитория> skeleton-api
cd skeleton-api
composer install
```

### 2. Окружение

```bash
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
```

Отредактируйте `.env`: укажите БД (`DB_*`), при необходимости Redis, S3, L5-Swagger и т.д.

**JWT:** ключ задаётся командой `php artisan jwt:secret` (пишет `JWT_SECRET` в `.env`). Варианты: `--show` — только показать; `--force` — перезаписать без подтверждения. Время жизни токена и окно refresh настраиваются в `.env`: `JWT_TTL` (минуты, по умолчанию 60), `JWT_REFRESH_TTL` (минуты, по умолчанию 20160).

**MoonShine:** в `.env` можно задать `MOONSHINE_TITLE` и `MOONSHINE_ROUTE_PREFIX` (по умолчанию `admin`).

### 3. База данных

```bash
php artisan migrate
```

Миграции создают в том числе таблицы для MoonShine (`moonshine_users`, `moonshine_user_roles` и др.).

### 4. Пользователь админки (MoonShine)

После первого развёртывания создайте суперпользователя админки:

```bash
php artisan moonshine:user --username=admin@example.com --name="Admin" --password=ваш_пароль
```

Дополнительных админов и роли можно создавать в админке: разделы «Admins» и «Roles».

### 5. Фронт и сборка (если нужны)

```bash
npm install
npm run build
```

Для разработки с hot-reload:

```bash
npm run dev
```

### 6. Запуск

Локально:

```bash
php artisan serve
# или на порту: php artisan serve --port=8002
```

Через очередь (если используется):

```bash
php artisan queue:work
```

### 7. Дополнительно

**Swagger (OpenAPI):**
- Первый раз или после изменений в OA-атрибутах контроллеров/ресурсов перегенерируйте документацию: `make swagger` или `php artisan l5-swagger:generate`.
- UI доступен по адресу из конфига (например `/api/documentation`).

**MoonShine:**
- Админка: `http://localhost:8000/admin` (или ваш `APP_URL` + значение `MOONSHINE_ROUTE_PREFIX`).
- Конфиг: `config/moonshine.php`. Ресурсы и страницы регистрируются в `app/Providers/MoonShineServiceProvider.php`, меню — в `app/MoonShine/Layouts/MoonShineLayout.php`.

**Продакшен:**
- Кэш конфига: `php artisan config:cache`, `php artisan route:cache`.
- Оптимизация MoonShine: `php artisan moonshine:optimize`.

---

## Перед коммитом

Перед каждым коммитом нужно прогнать проверки качества кода.

**Минимум — одна команда:**

```bash
make check-all
```

Она по очереди выполняет:

- `make lint` — проверка стиля (PHP CodeSniffer, PSR-12)
- `make format` — проверка форматирования (Laravel Pint)
- `make static-analysis` — статический анализ (PHPStan + Larastan)

Если что-то падает по стилю/форматированию, сначала автоисправьте:

```bash
make format-fix
make lint-fix
```

Затем снова:

```bash
make check-all
```

Коммитить имеет смысл только когда `make check-all` завершается без ошибок.

Подробнее про инструменты и порядок действий — в [PRE_COMMIT.md](PRE_COMMIT.md).

---

## Тесты

В проект добавлены базовые Feature-тесты:

- **Healthcheck**: `GET /api/v1/health`
- **Auth**: `POST /api/v1/auth/login`, проверка защиты `GET /api/v1/auth/me`
- **CRUD Test-модуля**: полный цикл по `Route::apiResource('test', ...)` под JWT

### Запуск тестов

```bash
make test
```

Полезные варианты:

```bash
make test-feature
make test-unit
```

<h1 align="center">TaskFlow · API</h1>

<p align="center">
  <em>Backend REST en Laravel 10 para tareas, categorías y comentarios.</em><br>
  JSON bajo <code>/api/v1</code> · autenticación por token · listo para cualquier frontend.
</p>

<p align="center">
  <img alt="Laravel 10" src="https://img.shields.io/badge/Laravel-10-FF2D20?logo=laravel&logoColor=white">
  <img alt="PHP 8.3" src="https://img.shields.io/badge/PHP-8.3-777BB4?logo=php&logoColor=white">
  <img alt="PostgreSQL 18" src="https://img.shields.io/badge/PostgreSQL-18-4169E1?logo=postgresql&logoColor=white">
  <img alt="Sanctum" src="https://img.shields.io/badge/Auth-Sanctum-FF2D20?logo=laravel&logoColor=white">
  <img alt="API" src="https://img.shields.io/badge/API-24_endpoints-0969DA">
  <img alt="Tests" src="https://img.shields.io/badge/tests-46_passing-3FB950">
</p>

---

## ⚡ Quick start

```bash
composer install
cp .env.example .env && php artisan key:generate
php artisan migrate --seed          # tablas + usuarios admin y demo
php artisan serve                   # http://127.0.0.1:8000
```

> **Requiere** PHP 8.3 (extensión `pdo_pgsql`) y PostgreSQL 18.
> Configura `DB_*` y `FRONTEND_URL` en el `.env` antes de migrar.

## 🧭 Qué hace

🔐 Auth por token (Sanctum) &nbsp;·&nbsp; ✅ Tareas con **papelera** (soft delete, restaurar, borrar) &nbsp;·&nbsp; 🏷️ Categorías privadas por usuario &nbsp;·&nbsp; 💬 Comentarios públicos con moderación &nbsp;·&nbsp; 🛡️ Roles `admin` / `user` &nbsp;·&nbsp; 🚦 Rate limiting &nbsp;·&nbsp; 🌐 CORS allowlist.

## 🏗️ Arquitectura

**Vertical slice**: cada entidad vive en `app/Modules/<X>/`, autocontenida. El controlador solo orquesta; la lógica va en Actions.

```mermaid
flowchart LR
    C([Frontend]) -- "Bearer" --> R[routes] --> M{auth · admin} --> K[Controller]
    K --> Q[Request<br/>valida]
    K --> A[Action<br/>lógica] --> P[(Eloquent + Policy)]
    K --> S[Resource] -- "JSON" --> C
```

```
app/Modules/<Modulo>/
├── <Modulo>Controller.php   Orquesta: Request → Action → Resource
├── <Modulo>.php             Modelo Eloquent
├── routes.php               Rutas del módulo (montadas en /api/v1)
├── Actions/                 Lógica: una clase por operación
├── Requests/                Validación (contrato de entrada)
├── Resources/               Forma del JSON (contrato de salida)
├── Policies/                Permisos por recurso
└── Middleware/              Solo si hace falta (ej. admin)
```

**Principios**
- Controladores finos: no consultan la BD directamente.
- El dueño (`user_id`) lo fija la Action, nunca llega desde el input.
- `role` no es asignable en masa: se asigna aparte.
- Los Resources son el contrato público; el front no ve columnas crudas.

Módulos: **Auth · Task · Category · Comment · Users**.

## 🛣️ Endpoints

Todos bajo `/api/v1`. **Token** = requiere `Authorization: Bearer`.

<details>
<summary><b>Ver los 24 endpoints</b></summary>

#### Auth
| Método | URL | Acceso |
| --- | --- | --- |
| POST | `/register` · `/login` | Público |
| POST | `/logout` | Token |
| GET | `/me` | Token |

#### Tareas
| Método | URL | Acceso |
| --- | --- | --- |
| GET · POST | `/tasks` | Token |
| GET | `/tasks/trashed` | Token |
| GET · PUT · DELETE | `/tasks/{id}` | Token |
| POST | `/tasks/{id}/restore` | Token |
| DELETE | `/tasks/{id}/force` | Token |

#### Categorías
| Método | URL | Acceso |
| --- | --- | --- |
| GET · POST | `/categories` | Token |
| GET · PUT · DELETE | `/categories/{id}` | Token |

#### Comentarios
| Método | URL | Acceso |
| --- | --- | --- |
| GET | `/comments` | Público |
| POST | `/comments` | Token |
| DELETE | `/comments/{id}` | Admin |

#### Usuarios (admin)
| Método | URL | Acceso |
| --- | --- | --- |
| GET | `/admin/users` · `/admin/users/{id}` | Admin |
| PUT · DELETE | `/admin/users/{id}` | Admin |

</details>

<details>
<summary><b>Modelo de datos</b></summary>

| Tabla | Campos propios | Notas |
| --- | --- | --- |
| `users` | `user_name`, `password`, `role` | Sin email · `role`: `admin` \| `user` |
| `category` | `name`, `user_id` | Privada por usuario |
| `task` | `title`, `description`, `status`, `category_id`, `user_id` | FK `set null` |
| `comments` | `body`, `user_id` | Autor nulo si se elimina |

Todas con `timestamps` y `deleted_at` (borrado lógico).

</details>

## 🧪 Tests

**46 tests** contra PostgreSQL (base `taskflow_testing`, fijada en `phpunit.xml`).

```bash
createdb taskflow_testing   # solo la 1ª vez
php artisan test
```

## 🧰 Comandos útiles

```bash
php artisan route:list             # ver rutas
php artisan db:seed                # recrear admin y demo
php artisan migrate:fresh --seed   # rehacer la BD (borra datos)
./vendor/bin/pint                  # formato de código
```

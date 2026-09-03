<h1 align="center">TaskFlow · API</h1>

<p align="center">
  <em>Backend REST en Laravel 10 para tareas, categorías, etiquetas y comentarios.</em><br>
  JSON bajo <code>/api/v1</code> · autenticación por token · listo para cualquier frontend.
</p>

<p align="center">
  <img alt="Laravel 10" src="https://img.shields.io/badge/Laravel-10-FF2D20?logo=laravel&logoColor=white">
  <img alt="PHP 8.3" src="https://img.shields.io/badge/PHP-8.3-777BB4?logo=php&logoColor=white">
  <img alt="Composer 2" src="https://img.shields.io/badge/Composer-2-885630?logo=composer&logoColor=white">
  <img alt="PostgreSQL 18" src="https://img.shields.io/badge/PostgreSQL-18-4169E1?logo=postgresql&logoColor=white">
  <img alt="Sanctum" src="https://img.shields.io/badge/Auth-Sanctum-FF2D20?logo=laravel&logoColor=white">
  <img alt="API" src="https://img.shields.io/badge/API-45_endpoints-0969DA">
  <img alt="Docker" src="https://img.shields.io/badge/Docker-ready-2496ED?logo=docker&logoColor=white">
</p>

## Quick start

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

> **Requiere** PHP 8.3 (extensión `pdo_pgsql`), Composer 2 y PostgreSQL 18.
> Configura `DB_*` y `FRONTEND_URL` (allowlist de CORS) en el `.env` antes de migrar.
> Las credenciales del seeder salen de `ADMIN_*` / `DEMO_*`; si dejas el `*_PASSWORD` vacío, se genera una al azar y se imprime. **No fijes una contraseña real en `.env.example`.**

## Qué hace

- 🔐 Auth por token (Sanctum, login por **email**) &nbsp;·&nbsp;
- ✅ Tareas con **papelera** (soft delete, restaurar, borrar definitivo) &nbsp;·&nbsp;
- 🏷️ Categorías y etiquetas privadas por usuario &nbsp;·&nbsp;
- 💬 Comentarios públicos con moderación &nbsp;·&nbsp;
- 🛡️ Roles `admin` / `user` + catálogo de permisos &nbsp;·&nbsp;
- 🚦 Rate limiting &nbsp;·&nbsp; 🌐 CORS allowlist.

## Arquitectura

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
├── Exceptions/              Reglas de negocio (ej. borrado con dependientes)
└── Middleware/              Solo si hace falta (ej. admin)
```

## 🧰 Comandos útiles

```bash
php artisan route:list             # ver rutas
php artisan db:seed                # recrear admin y demo
php artisan migrate:fresh --seed   # rehacer la BD (borra datos)
./vendor/bin/pint                  # formato de código
```

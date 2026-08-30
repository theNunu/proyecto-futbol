# AGENTS.md

Guía para agentes de IA (y colaboradores) que trabajan sobre este proyecto. Léela completa antes de tocar código.

## Resumen del proyecto

- **API pura** Laravel 12 (PHP `^8.2`). No hay frontend Blade de negocio; el frontend real es una app **Angular** externa en `http://localhost:4200` (CORS configurado solo para ese origen).
- Autenticación **JWT** vía `tymon/jwt-auth` `^2.3`. El modelo `User` implementa `JWTSubject`; secreto en `JWT_SECRET` (generar con `php artisan jwt:secret`).
- Base de datos **PostgreSQL** en local (`DB_CONNECTION=pgsql`, base `mi_partido_grafico_db`, con **stored procedures**). Los tests usan **SQLite en memoria** (`phpunit.xml`).
- Estilo de código con **Laravel Pint** (`vendor/bin/pint`).

## Arquitectura y convenciones

Patrón estricto **Controller → Service → Repository → Model** (inyección por DI vía constructor, sin interfaces en los dominios deportivo/contenido/navegación; el módulo ERP sí usa contrato + implementación Eloquent):

```
Controller (valida/responde) → Service (lógica de negocio) → Repository (Eloquent) → Model
```

- **Respuestas JSON uniformes**: usa el trait `App\Traits\ApiResponse` (`respondOk`, `respondCreated`, `respondNotFound`, `respondBadRequest`, `respondUnauthorized`, `respondAccessDenied`, `respondUnprocessableEntity`, `respondServerError`, `respondConflict`). Formato: `{status_code, success, message, data|errors}`.
- **Controladores**: delegan en el Service y devuelven `respondOk(...)` / `parseException(...)` (mapea excepciones Symfony/DB/Validación a JSON).
- **IDs custom con prefijo de tabla** en lugar de `id` genérico: p. ej. `tournament_id`, `team_id`, `match_id`, `news_id`, `rubro_id`. Respétalos en queries, rutas y modelos.
- **Mensajes de usuario en español**.
- **Validación**: use Form Requests (`app/Http/Requests/`) con `authorize() = true` y `failedValidation()` que lanza 422 JSON.
- **Orden/orden de capas**: no agregar lógica de negocio en controladores ni queries complejas en modelos.

## Dominios funcionales

1. **Deportivo**: Torneos, Fases, Temporadas, Equipos, Partidos (Inscripción de equipos a torneos).
2. **Contenido**: Noticias, Media (imágenes/videos), Archivos, Catálogos y sus detalles, Banners.
3. **Navegación/Admin**: Menús y Módulos (árboles recursivos) + autenticación (register/login).
4. **ERP financiero**: Rubros, Tipos de Pago, Transacciones e Historial de Saldos (libro mayor, con stored procedures de PostgreSQL).

## Estructura de directorios

```
app/
├── Http/Controllers/Controller.php      # Base: helpers response()/parseException() + anotación Swagger "Api FEF"
│   ├── Api/                             # 13 controladores REST (Tournament, Phase, Season, Team, TournamentTeam,
│   │                                    #   MatchGame, Catalog, File, Banner, News, Menu, Module)
│   ├── Admin/Controllers/AuthController # register + login
│   └── Erp/Controllers/                 # Transaccion, Rubro, TipoPago
├── Http/Requests/                       # 15+ Form Requests (Store*, UpdateNews, {Menu,Module,ChangeMenu}, ERP)
├── Services/                            # Lógica de negocio (Tournament, Phase, Season, Team, TournamentTeam,
│                                        #   Match, News, Banner, Menu, Module, Catalog)
├── Repositories/                        # Acceso a datos Eloquent (uno por dominio deportivo/contenido/navegación)
├── Admin/
│   ├── Service/AuthService.php          # register/login + generación de token JWT
│   ├── Repository/UserRepository.php
│   └── Requests/RegisterRequest.php
├── Erp/
│   ├── Services/                        # FinancieroService (núcleo), TransaccionService, RubroService, TipoPagoService
│   └── Repositories/
│       ├── Contracts/                   # TransaccionRepositoryInterface
│       └── Eloquent/                    # TransaccionRepository, RubroRepository, TipoPagoRepository
├── Models/                              # 20 modelos Eloquent
├── Traits/ApiResponse.php               # helpers de respuesta JSON
└── Providers/AppServiceProvider.php     # único bind: TransaccionRepositoryInterface → TransaccionRepository
```

- No hay `app/Console`, `app/Http/Middleware`, `app/Http/Kernel`, `app/Exceptions` (todo en `bootstrap/app.php`, estilo Laravel 12).
- `resources/views/` solo contiene `welcome.blade.php` (página por defecto). El frontend real es Angular externo.

## Modelos principales

| Modelo | Tabla | PK | Dominio |
|---|---|---|---|
| Tournament | tournaments | tournament_id | Deportivo |
| Phase | phases | phase_id | Deportivo |
| Season | seasons | season_id | Deportivo |
| Team | teams | team_id | Deportivo |
| TournamentTeam | tournament_teams | tournament_team_id | Deportivo (pivot) |
| GameMatch | macth_games (typo) | match_id | Deportivo |
| News | news | news_id | Contenido |
| NewsMedia | news_media | news_media_id (UUID) | Contenido |
| File | files | file_id | Contenido |
| Catalog | catalogs | catalog_id | Contenido |
| CatalogDetail | catalog_details | catalog_detail_id | Contenido |
| Banner | banners | banner_id | Contenido |
| Menu | menus | menu_id | Navegación |
| Module | modules | module_id | Navegación |
| Rubro | rubros | rubro_id | ERP |
| TipoPago | tipo_pagos | tipo_pago_id | ERP |
| Transaccion | transaccions | transaccion_id | ERP |
| HistorialSaldo | historial_saldos | historial_saldo_id | ERP |
| User | users | id | Auth |

HistorialSaldo (libro mayor): guarda `saldo_anterior`, `monto_movimiento`, `saldo_posterior` por cada transacción.

## Rutas

- `routes/api.php` (~150 líneas): todos los endpoints API. **Importante: ninguna ruta está protegida con `auth:api`; todas son públicas.**
  - Deportivas: tournaments, phases, seasons, teams, tournament-teams.
  - Contenido: catalogs (con `/catalog-detail`), news (incl. `/info` y `/{id}/add-media`), files (subida genérica), banners.
  - Navegación: menus, modules.
  - Auth: `POST /register`, `POST /login`.
  - ERP: transacciones (incl. `/rubro-rendimiento` y `/rubro-rendimiento/sp`), rubros, tipo-pagos.
  - `GET /api/test`: endpoint de prueba.
- `routes/web.php`: `GET /` → welcome.
- `routes/console.php`: comando `inspire` por defecto.

## Comandos clave

```bash
composer dev                 # dev local: servidor + queue + pail + vite (concurrently)
php artisan serve            # solo el servidor
npm run dev                  # Vite
npm run build                # build de assets
composer build               # build general
composer test                # php artisan config:clear && php artisan test (SQLite en memoria)
vendor/bin/pint              # formateo de estilo de código
php artisan jwt:secret       # generar/regenerar secreto JWT
php artisan serve --port=8000  # (por si necesitas puerto concreto)
```

## Base de datos

- **PostgreSQL** en local con **stored procedures**:
  - `sp_search_noticias(...)` — búsqueda filtrada de noticias (vía `NewsRepository::infoNews`).
  - `sp_obtener_rubro(...)` — rendimiento de rubros (vía SPI/SP).
- **Aviso importante**: los stored procedures **NO funcionan en los tests** (SQLite en memoria). Si añades tests que toquen esos flujos, tenlo en cuenta.
- Migraciones: 26 (base Laravel + dominio deportivo + contenido + menús/módulos + ERP).
- Seeders: solo `DatabaseSeeder` crea el usuario de prueba `Test User` / `test@example.com`.

## Inconsistencias / bugs conocidos (¡no romper nada!)

Estas zonas están rotas o incompletas. No las "arregles" sin avisar; evita apoyarte en ellas y, si las tocas, hazlo con cuidado:

- **Imports inexistentes**:
  - `app/Http/Requests/StoreMediaRequest.php` importa el enum `App\FileRelationType` (no existe).
  - `app/Http/Controllers/Api/NewsController.php` importa `App\Http\Requests\StoreMedia` (no existe).
  - `app/Http/Controllers/Controller.php` importa `App\Models\ErrorLog` (no existe; su uso está comentado).
- **`MatchGameController`** no está registrado en ninguna ruta de `routes/api.php`.
- **Banners**: `BannerRepository`/`BannerService` operan sobre el modelo `Banner` pero con columnas de News (`title`, `is_active`) que no existen en banners; `update()` espera `begin_date/end_date`. Posibles bugs.
- **`Tournament::season()`** usa la FK `tournament_id` en lugar de `season_id` (relación incorrecta).
- **Migraciones incompletas**: `catalogs`, `catalog_details`, `banners` (y columnas de `news`/`files`) no definen en la migración las columnas que los modelos/requests usan (se añadieron directo en PostgreSQL).
- **Typo en tabla**: `macth_games` (debería ser `match_games`).
- **`NewsService.php`** tiene cambios sin commitear (lógica de videos en `addMedia`).
- **Validación incongruente**: `RegisterRequest` solo permite password de máx. 6 caracteres (inusual).
- **Mensajes/commits informales** ("sigo ahi", "oui"): el proyecto está en desarrollo activo; sigue el estilo pero mantén claridad cuando aportes algo nuevo.

## Reglas para el agente

- No asumas que una ruta está protegida; verifica el middleware real en `routes/api.php`.
- Antes de modificar modelos/migraciones, revisa las inconsistencias listadas arriba para no profundizar un bug.
- Al añadir endpoints, respeta el patrón Controller → Service → Repository y el formato de respuesta de `ApiResponse`.
- Mensajes y errores de usuario van en **español**.
- Corre `vendor/bin/pint` sobre los archivos PHP modificados antes de terminar.

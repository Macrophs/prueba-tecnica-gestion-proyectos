# Gestor de Proyectos - Prueba Técnica

## Descripción

Aplicación web para gestión de proyectos y tareas, desarrollada como parte de una prueba técnica. Permite:

- Crear y gestionar proyectos
- Organizar tareas con diferentes estados (Pendiente, En Progreso, Finalizada, Bloqueada)
- Asignar tareas a usuarios
- Visualizar el progreso de cada proyecto
- Gestión de usuarios

## Instalación y configuración


### Pasos para la instalación

1. **Instalar dependencias de PHP**:

```bash
    composer install
```

2. **Instalar dependencias de Node**:

```bash
    npm install && npm run build
```

3. **Configurar archivo .env**:

```ini
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=nombre_base_datos
    DB_USERNAME=usuario_mysql
    DB_PASSWORD=contraseña_mysql
```

4. **Generar clave de aplicación**:

```bash
    php artisan key:generate
```

5. **Ejecutar migraciones y seeders**:

```bash
    php artisan migrate --seed
```

Esto Creará un usuario administrador por defecto:

* Username: **admin**
* Contraseña: **admin**


6. **Compilar proyecto localmente**:

```bash
    composer run dev
```


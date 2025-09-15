
#  Configuración Inicial del Proyecto Laravel

Este proyecto fue configurado siguiendo la actividad **"chore: configure MySQL connection, timezone, language and profile photo"**.  
El objetivo fue preparar el entorno inicial, configurar la base de datos, el idioma, la zona horaria y personalizar la interfaz visual.

---

##  Configuraciones Realizadas

### Conexión a MySQL
Se configuró la conexión en el archivo **`.env`**:

env:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=appointment_db_test4a
DB_USERNAME=laravel
DB_PASSWORD=laravel123


Para crear la base de datos y el usuario en MySQL se ejecutaron:

sql
CREATE DATABASE appointment_db_test4a CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'laravel'@'localhost' IDENTIFIED BY 'laravel123';
GRANT ALL PRIVILEGES ON appointment_db_test4a.* TO 'laravel'@'localhost';
FLUSH PRIVILEGES;


### 2️⃣ Configuración de Zona Horaria

En el archivo **`config/app.php`** se cambió la línea:

php
'timezone' => 'America/Merida',


Esto garantiza que todas las fechas y horas de la aplicación usen la zona horaria correcta.


### 3️⃣ Configuración de Idioma

En el archivo **`.env`** se agregaron:

```env
APP_LOCALE=es
APP_FALLBACK_LOCALE=es
APP_FAKER_LOCALE=es_MX
```

Y en **`config/app.php`** se actualizaron las siguientes líneas:

```php
'locale' => env('APP_LOCALE', 'es'),
'fallback_locale' => env('APP_FALLBACK_LOCALE', 'es'),
'faker_locale' => env('APP_FAKER_LOCALE', 'es_MX'),
```

Con esto, Laravel mostrará mensajes y validaciones en español.


### 4️⃣ Personalización Visual Básica

Se agregaron en la carpeta **`public/`**:

* `mi_foto.png`


##  Verificación

Para comprobar las configuraciones:

1. **Instalar dependencias**

   ```bash
   composer install
   npm install
   npm run build
   ```

2. **Limpiar y actualizar configuración**

   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan migrate
   ```

3. **Levantar servidor**

   ```bash
   php artisan serve
   ```

4. **Abrir en el navegador**
   [http://localhost:8000](http://localhost:8000)

Deberías ver:

* Logo y foto de perfil
* Zona horaria: **America/Merida**
* Idioma: **es**


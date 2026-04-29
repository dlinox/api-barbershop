# Configuración del Cron en cPanel

Este documento describe cómo programar el comando artisan `academy:update-group-status` en cPanel para que se ejecute automáticamente cada día a medianoche.

---

## ¿Qué hace este cron?

El comando `academy:update-group-status` evalúa diariamente todos los grupos académicos y actualiza su estado automáticamente según las fechas:

| Condición | Nuevo estado |
|---|---|
| `start_date <= hoy` Y `end_date >= hoy` Y estado era `coming` | `active` |
| `end_date < hoy` Y estado era `active` o `coming` | `finished` |
| Estado `active` o `coming` → sigue con fechas futuras (ajuste manual) | `coming` |

Los grupos en estado `cancelled` **nunca** son modificados por el cron.

---

## Configurar el Cron en cPanel

### 1. Ingresar a cPanel

1. Abrir el panel de control de tu hosting (normalmente `https://tu-dominio.com/cpanel` o a través del proveedor).
2. Ir a la sección **Cron Jobs** (dentro de "Advanced" o "Avanzado").

---

### 2. Identificar la ruta al ejecutable PHP

Antes de crear el cron, debes saber qué versión de PHP usa el proyecto. En cPanel:

1. Ve a **MultiPHP Manager** o **Select PHP Version**.
2. Identifica la versión asignada a tu dominio (ej. PHP 8.2).
3. La ruta al binario suele ser una de estas:

| PHP Version | Ruta probable |
|---|---|
| PHP 8.1 | `/usr/local/bin/php81` o `/opt/cpanel/ea-php81/root/usr/bin/php` |
| PHP 8.2 | `/usr/local/bin/php82` o `/opt/cpanel/ea-php82/root/usr/bin/php` |
| PHP 8.3 | `/usr/local/bin/php83` o `/opt/cpanel/ea-php83/root/usr/bin/php` |

> **Tip:** Puedes verificar la ruta ejecutando `which php` o `php -v` en la terminal SSH de cPanel.

---

### 3. Crear el Cron Job

En la sección **Add New Cron Job**, configura:

| Campo | Valor |
|---|---|
| **Minute** | `0` |
| **Hour** | `0` |
| **Day** | `*` |
| **Month** | `*` |
| **Weekday** | `*` |

Esto equivale a la expresión cron: **`0 0 * * *`** (cada día a medianoche).

**Comando:**

```bash
/usr/local/bin/php /home/TU_USUARIO/public_html/artisan academy:update-group-status >> /dev/null 2>&1
```

> Reemplaza `TU_USUARIO` con tu nombre de usuario de cPanel y ajusta la ruta al proyecto si es necesario (ej. si está en un subdirectorio como `/home/TU_USUARIO/api/`).

**Ejemplo completo:**
```bash
/usr/local/bin/php /home/miusuario/public_html/artisan academy:update-group-status >> /dev/null 2>&1
```

---

### 4. Verificar la zona horaria

El sistema está configurado con la zona horaria `America/Lima` (`config/app.php`). Asegúrate de que el servidor de cPanel también esté en esa zona, o ajusta la hora del cron en consecuencia:

- Si el servidor está en UTC, la medianoche de Lima (UTC-5) corresponde a las **05:00 UTC**.
- En ese caso usa: `0 5 * * *` en lugar de `0 0 * * *`.

---

### 5. Verificar el cron manualmente (SSH)

Puedes probar el comando antes de programarlo:

```bash
cd /home/TU_USUARIO/public_html
php artisan academy:update-group-status
```

También puedes verificar el listado de crons de Laravel:

```bash
php artisan schedule:list
```

Salida esperada:
```
0 0 * * *  php artisan academy:update-group-status    Next Due: en 23 horas
```

---

## Resumen rápido

```
Expresión: 0 0 * * *
Comando:   /usr/local/bin/php /home/TU_USUARIO/public_html/artisan academy:update-group-status >> /dev/null 2>&1
Frecuencia: Diario a las 00:00 (medianoche)
```

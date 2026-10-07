# Base de datos

Este directorio contiene un SQL con el esquema de la base de datos generado a partir de las migraciones del proyecto.

Archivos:
- `schema.sql` — Sentencias SQL para crear la base de datos y tablas principales (utf8mb4, InnoDB).

Instrucciones rápidas (MySQL / MariaDB):

1. Ajusta el nombre de la base de datos en `schema.sql` si quieres otro nombre.
2. Desde un cliente MySQL (o cmd):

```sql
-- importar todo
SOURCE path/to/e:\Bellas-app\database\schema.sql;
```

O usando la línea de comandos (Windows cmd):

```bat
mysql -u root -p < "e:\Bellas-app\database\schema.sql"
```

Notas importantes:
- Las migraciones originales no definen claves foráneas explícitas; sólo se establecen llaves primarias y constraints de unicidad/index según migraciones.
- `personal_access_tokens` usa una relación polimórfica (`morphs`) — no se añadió FK porque `tokenable_type` puede apuntar a diferentes tablas.
- Si añades tablas que referencien `users.id`, crea las FK explícitas y crea primero la tabla referenciada (`users`).

Si quieres, puedo:

- Generar las FK esperadas según convenciones del proyecto (por ejemplo, `*_user_id` → `users.id`) y actualizar `schema.sql`.
- Generar migraciones nuevas que añadan constraints.

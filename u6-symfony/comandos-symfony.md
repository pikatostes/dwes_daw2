# Symfony Quick Reference Guide
## 1. Crear proyecto sin Git
```bash
symfony new nombreproyecto --no-git
```

## 2. Instalar el depurador
```bash
composer require debug
```
## 3. Crear proyecto normal con webapp
```bash
symfony new nombreproyecto --webapp
```

## 4. Arrancar el servidor
```bash
symfony serve
```

## 5. Añadir templates en caso de no tenerlos
```bash
composer require templates
```

## 6. Agregar rutas
Edita el archivo config/routes/framework.yaml para añadir rutas a la aplicación.

## 7. Añadir comandos de base de datos
```bash
composer require symfony/orm-pack
```
Añadir la siguiente línea en el archivo .env:

```cs
DATABASE_URL="mysql://root@127.0.0.1:3306/[db_name]"
```

## 8. Crear una base de datos:
```bash
symfony console doctrine:database:create
```
## 9. Instalar comandos de creación de entidades
```bash
composer require maker-bundle
```

## 10. Crear una entidad
```bash
symfony console make:entity
```

## 11. Crear la migración
```bash
symfony console make:migration
```

## 12. Hacer la migración
```bash
symfony console doctrine:migrations:migrate
```

## 13. Hacer consultas SQL
```bash
symfony console doctrine:query:sql '[CONSULTA SQL]'
```

## 14. Historial de migraciones
```bash
symfony console doctrine:migrations:status
```

## 15. Ver todos los comandos de la consola
```bash
symfony console
```

## 16. Lista de migraciones
```bash
symfony console doctrine:migrations:list
```

## 17. Actualizar la base de datos
```bash
symfony console doctrine:schema:update --dump-sql
```

## 18. Crear controladores
```bash
symfony console make:controller [Nombre del controlador]
```

## 19. Instalar formularios
```bash
composer require symfony/form
```

## 20. Crear un formulario
```bash
symfony console make:form
```
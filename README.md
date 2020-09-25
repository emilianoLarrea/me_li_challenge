# MercadoLibre Challenge
Este es un proyecto propuesto por MercadoLibre a modo de Test. 
Consiste en un proyecto laravel, desarrollado a modo de "mvp", estructurado por dominios e implementado de forma "agnóstica" al framework.

## Puesta a Punto y Ejecución: 
- Abrir un terminal
- situarse dentro de la carpeta root del proyecto
- Ejecutar:
```sh
docker-compose up
```
-En una consola nueva, sobre el mismo directorio ejecutar:
```sh
dockercompose exec php composer install
docker-compose exec php php artisan migrate
```
-En consolas independientes ejecutar, sobre el mismo directorio:
```sh
docker-compose exec php php artisan consumer:headers
docker-compose exec php php artisan consumer:saver
```
Estos dos comandos últimos inicial el consumo de colas de rabbitMq, de forma tal que la obtención de información de encabezados de correos y el guardado en la base de datos se realizan de forma asíncrona.


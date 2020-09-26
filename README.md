# MercadoLibre Challenge
Este es un proyecto propuesto por MercadoLibre a modo de Test. 
Consiste en un proyecto laravel, desarrollado a modo de una API cómo "mvp", estructurado por dominios e implementado de forma "agnóstica" al framework.

Utiliza la tecnología de rabbitMQ para manejar la obtención de información de encabezados de correos y el guardado en la base de datos de forma asíncrona.   

## Requerimientos
Es necesario tener conexión a internet e instalar [Docker](https://www.docker.com)

## Puesta a Punto y Ejecución 
- Abrir un terminal
- Situarse dentro de la carpeta root del proyecto
```sh
cd {ROOT PROYECTO}
```
- Ejecutar:
```sh
docker-compose up
```
- En una consola nueva, sobre el mismo directorio ejecutar:
```sh
dockercompose exec php composer install
docker-compose exec php php artisan migrate
```
- En consolas independientes ejecutar, sobre el mismo directorio:
```sh
docker-compose exec php php artisan consumer:headers
docker-compose exec php php artisan consumer:saver
```
Estos dos últimos comandos inician el consumo de colas de rabbitMQ, de forma tal que la obtención de información de encabezados de correos y el guardado en la base de datos se realizan de forma asíncrona.

## Utilización

Se puede encontrar documentación de uso en [URL](http://127.0.0.1:8001)

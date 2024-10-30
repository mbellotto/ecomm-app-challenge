# ECOMM-APP CHALLENGE

Solución propuesta para el desafío técnico de Ecomm-app, donde se busca implementar un CRUD básico de una sola entidad (Producto) 

## Tecnologías usadas

- PHP (8.2)
- Laravel (11.29.0)
- Composer
- PHPUnit

## Setup del entorno

Como mínimo se deberá contar con los aplicativos detallaados en la sección anterior instalados. Refereirse a los manuales de las herramienta para su instalación.

## Instalasción

1. Clonar el repositorio
```bash
    git clone https://github.com/mbellotto/ecomm-app-challenge.git
    cd ./ecomm-app-challenge
```

2. Instalar dependencias
``` bash
    npm install
    
    composer install
```

## Configuración

Antes de ejecutar la aplicación se recomienda seteaar las variables de entorno. Para ello se debe editar el archivo ubicado en **.env** ubicado en la raiz del proyecto.

### Variable: PRODUCTOS_FILE_LOCATION

Indica donde se encuentra ubicado el archivo en formato JSON que contendrá los productos. Por defecto esta inicializada con el valor:

``` env
    PRODUCTOS_FILE_LOCATION=app/productos.json
```

## Uso

En caso de necesitar iniciar el aplpicativo con una lista de productos previamente cvargados, se deberá crear el archivo **productos.json** en la ubicación indicada en el punto anterior.

El archivo deberá tener el siguiente formato:

``` json
    [
        {
            "id":1,
            "title":"Producto del Challenge",
            "price":2000,
            "created_at":"2022-12-13 10:41"
        }
    ]
```

Se podrá generar un archivo sin productos (array vacio **[]**). En caso de que no se haya creado este archivo, el aplicativo generara un archivo vacio.

Para iniciar el servidor de desarrollo, en el directorio del proyecto, ejecutar:

``` bash
        php artisan serve
```


## Tests

Para correr las pruebas disponibles ejecutar

``` bash
    php artisan test tests/Feature/ProductoControllerTest.php
    php artisan test tests/Feature/ProductoServiceTest.php 
```


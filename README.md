# ORM en PHP

![Logo del ORM](ORMPHP.png)

Este es un ORM (Object-Relational Mapping) desarrollado en PHP que facilita la creación y gestión de entidades almacenadas en una base de datos relacional.

## Tabla de Contenidos

1. [Instalación](#instalación)
2. [Conceptos Básicos](#conceptos-básicos)
3. [Configuración](#configuración)
4. [Uso](#uso)
5. [Ejemplos](#ejemplos)
6. [Contribución](#contribución)
7. [Licencia](#licencia)

## Instalación

Para instalar el ORM, puedes clonar el repositorio desde GitHub y luego instalar las dependencias usando Composer:

```bash
git clone https://github.com/tu_usuario/orm-php.git
cd orm-php
composer install
```

## Conceptos básicos
El ORM facilita la interacción entre objetos de PHP y una base de datos relacional, mapeando las entidades a tablas y las propiedades a columnas. Nos facilita la ejecución de las operaciones CRUDL y la obtención de otras entidades a traves de Foreign Keys.

## Configuración
Asegurate que los permisos en tu DB están correctos para permitir el acceso.

## Uso
Antes de comenzar a usar el ORM, configura la conexión a tu base de datos creando una instacia de DBContext. Esta instancia se encargará de mantener la conexión con la DB y servirá de punto de acceso para los repositorios de las entidades.

#### DBContext
```php
<?php
$host = 'address'; 
$db = 'dbName';
$user = 'username';
$pass = 'dbPass';

$context = new DbContext($host,$db,$user,$pass);
```

#### Create Entity
Ahora necesitas crear tu clase de tipo entidad que cada instancia representará una linea de tu tabla.
Cosas a tener en cuenta:
- Tu clase si representa una tabla entonces necesita tener un comentario con un parametro que será el nombre de tu tabla en tu DB.
- Las propiedades que quieras que sean representadas por una columna en tu BD necesitan tener un comentario que puede contener tres argumentos:
    - type="typeName" el tipo de dato que es.
    - name="columnName" el nombre de la columna en la tabla 
    - isPrimaryKey="true or false" si la columna en cuestión es la PK.
- Las propiedades representadas por la BD deben ser públicas o protegidas.
- La function __construct debe poder ser ejecutada sin parametros.
```php 
<?php

namespace ORM\Models\Example;

use ORM\Models\Entity;

/**
 * @ORM\Table(name="tableName")
 */
class Example extends Entity
{
    /**
     * @ORM\Column(type="int", name="id", isPrimaryKey="true")
     */
    protected $id;
    /**
     * @ORM\Column(type="string", name="name")
     */
    protected ?string $example;


    public function __construct($example = null)
    {
        $this->example = $example;
    }
}


```

#### Repository
Ahora puedes pasar el DBContext a la clase Repository para poder acceder a tu clase.
```php
<?php
$repository = new Repository($context, Example::class);
```
o puedes extender de la clase Repository para crear tus propias funciones.
```php
<?php
namespace ORM\Models\Example;

use ORM\Database\DbContext;
use ORM\Models\Repository;
use PDO;

class ExampleRepository extends Repository
{

    public function __construct(DbContext $context)
    {
        parent::__construct($context, Example::class);
    }
}

```

#### CRUDL

```php
<?php
$repository = new Repository($context, Example::class);

$repository->getAll(); // Regresa todas las Entities de la tabla de la class Example.
$repository->getById(1); // Regresa la entidad con id 1
$repository->insert($entity); //Inserta la entidad de tipo Example guardada en $entity.
$repository->update($entity); //Actualiza la entidad de tipo Example guardada en $entity.
$repository->delete(1); //Borra la entidad con id 1 de la tabla
```
## Ejemplos

Los siguientes archivos son ejemplos funcionales de dicho ORM. Simplemente incluyelos en el Main.

- ApartmentExample.php
- BungalowExample.php
- ClientExample.php
- PremiumClientExample.php
- ProductExample.php
- UserExample.php

## Contribución

¡Gracias por considerar contribuir al proyecto! Si deseas contribuir, por favor sigue estos pasos:

1. Fork del repositorio y clónalo localmente.
2. Crea una nueva rama para tu contribución.
3. Haz tus cambios y asegúrate de seguir las guías de estilo y estándares de código.
4. Haz pruebas exhaustivas de tus cambios.
5. Envía un Pull Request explicando claramente los cambios que has realizado y cualquier problema que hayas resuelto.
6. Participa en la discusión y revisión de tu Pull Request.


## Licencia

Este proyecto está licenciado bajo la [Licencia MIT](LICENSE).


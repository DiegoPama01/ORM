
# ORM in PHP

![ORM Logo](ORMPHP.png)

This is an ORM (Object-Relational Mapping) developed in PHP that facilitates the creation and management of entities stored in a relational database.

## Table of Contents

- [ORM in PHP](#orm-in-php)
  - [Table of Contents](#table-of-contents)
  - [Installation](#installation)
  - [Basic Concepts](#basic-concepts)
  - [Configuration](#configuration)
  - [Usage](#usage)
      - [DBContext](#dbcontext)
      - [Create Entity](#create-entity)
      - [Repository](#repository)
      - [CRUD](#crud)
  - [Examples](#examples)
  - [Contributing](#contributing)
  - [License](#license)

## Installation

To install the ORM, you can clone the repository from GitHub and then install the dependencies using Composer:

```bash
git clone https://github.com/your_user/orm-php.git
cd orm-php
composer install
```

## Basic Concepts
The ORM facilitates interaction between PHP objects and a relational database by mapping entities to tables and properties to columns. It simplifies the execution of CRUD operations and the retrieval of other entities through Foreign Keys.

## Configuration
Ensure that your DB permissions are correctly set to allow access.

## Usage
Before you start using the ORM, configure the connection to your database by creating an instance of DBContext. This instance will handle the connection to the DB and will serve as the access point for the entity repositories.

#### DBContext
```php
<?php
$host = 'address'; 
$db = 'dbName';
$user = 'username';
$pass = 'dbPass';

$context = new DbContext($host, $db, $user, $pass);
```

#### Create Entity
Now you need to create your entity class where each instance will represent a row in your table.
Things to keep in mind:
- Your class, if representing a table, needs to have a comment with a parameter that will be the name of your table in your DB.
- Properties that you want to be represented by a column in your DB need to have a comment that can contain three arguments:
    - type="typeName": the data type.
    - name="columnName": the column name in the table.
    - isPrimaryKey="true or false": if the column is the primary key.
- Properties represented by the DB should be public or protected.
- The __construct function must be able to run without parameters.
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
Now you can pass the DBContext to the Repository class to access your class.
```php
<?php
$repository = new Repository($context, Example::class);
```
Or you can extend the Repository class to create your own functions.
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

#### CRUD

```php
<?php
$repository = new Repository($context, Example::class);

$repository->getAll(); // Returns all entities from the table of the Example class.
$repository->getById(1); // Returns the entity with id 1
$repository->insert($entity); // Inserts the entity of type Example stored in $entity.
$repository->update($entity); // Updates the entity of type Example stored in $entity.
$repository->delete(1); // Deletes the entity with id 1 from the table
```
## Examples

The following files are functional examples of this ORM. Simply include them in the Main.

- ApartmentExample.php
- BungalowExample.php
- ClientExample.php
- PremiumClientExample.php
- ProductExample.php
- UserExample.php

## Contributing

Thank you for considering contributing to the project! If you want to contribute, please follow these steps:

1. Fork the repository and clone it locally.
2. Create a new branch for your contribution.
3. Make your changes and ensure you follow the style guides and coding standards.
4. Thoroughly test your changes.
5. Submit a Pull Request clearly explaining the changes you made and any issues you resolved.
6. Participate in the discussion and review of your Pull Request.

## License

This project is licensed under the [MIT License](LICENSE).

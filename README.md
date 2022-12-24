![KoalaFacade Logo](https://camo.githubusercontent.com/cc271582ba553880fcdfe628ce5a24f4b410c82032469cffb30eaf03afa2944b/68747470733a2f2f692e6962622e636f2f437670575758762f4c6f676f2d4b6f616c616772616d6d65722d62616e6e65722e706e67)

<p align="center">
    <a href="https://packagist.org/packages/koalafacade/diamond-console"><img src="https://img.shields.io/packagist/v/KoalaFacade/diamond-console?color=F28D1A&style=for-the-badge" alt="Test Passing"/></a>
    <a href="https://github.com/KoalaFacade/diamond-console/actions/workflows/run-test.yml"><img src="https://img.shields.io/github/actions/workflow/status/KoalaFacade/diamond-console/run-test.yml?branch=main&label=test&style=for-the-badge" alt="Test Passing"/></a>
    <a href="https://laravel.com"><img src="https://img.shields.io/badge/Laravel-^9.x-red?style=for-the-badge&logo=Laravel" alt="Laravel" /></a>
    <a href="https://php.net"><img src="https://img.shields.io/badge/PHP-8.2-7A86B8?style=for-the-badge&logo=php" alt="PHP Badge"/></a>
</p>

> Artisan command package to handle your Domain Driven Design project that suitable with Laravel base structures, made for comer of Domain Driven Design
and advanced.
>

## Table of Contents

1. [Documentation](#documentation)
    - [Installation](#installation)
    - [Commands](#commands)
        - [Application](#application)
            - [Request](#applicationmakerequest-storeuserrequest-user)
            - [Resource](#applicationmakeresource-userresource-user---modeluser)
        - [Domain](#domain)
            - [Action](#domainmakeaction-generateprofileaction-user)
            - [Builder](#domainmakebuilder-userbuilder-user)
            - [Data Transfer Object](#domainmakedata-transfer-object-roledata-user)
            - [Enum](#domainmakeenum-role-user)
            - [Model](#domainmakemodel-user-user)
            - [Value Object](#domainmakevalue-object-referralcode-user)
        - [Infrastructure](#infrastructure)
            - [Event](#infrastructuremakeevent-postevent-post)
            - [Factory](#infrastructuremakefactory-rolefactory-user)
            - [Listener](#infrastructuremakelistener-postlistener-post)
            - [Mail](#infrastructuremakemail-approveduser-user)
            - [Observer](#infrastructuremakeobserver-userobserver-user)
            - [Seeder](#infrastructuremakeseeder-userseeder-user)
            - [Service Provider](#infrastructuremakeprovider-factoryserviceprovider-user)
        - [Diamond](#diamond)
            - [Migration](#diamondmakemigration-create_user_table)
2. [Contribution](#contribution)

## Documentation

---

### Installation
Install Diamond Console with composer
```bash
 composer require koalafacade/diamond-console
```
then after Diamond Console installed run command below to set up your project. 
The command below will generate namespace in composer and base directory structures.
```bash
 php artisan diamond:install
```
---
### Commands

#### Application

#### `application:make:request StoreUserRequest User`
Command for generate a Request file

**Arguments**

|  Name  |    Description     |
|:------:|:------------------:|
|  Name  | Request class name |
| Domain |    Domain Name     |

**Options**

|  Name   |          Description           |
|:-------:|:------------------------------:|
| --force | Force create the Request class |

---

#### `application:make:resource UserResource User --model=User`
Command for generate a Request file

**Arguments**

|  Name  |     Description     |
|:------:|:-------------------:|
|  Name  | Resource class name |
| Domain |     Domain Name     |

**Options**

|        Name        |             Description              |
|:------------------:|:------------------------------------:|
| --model=ModelName  |   To hint Model class on Resource    |
|      --force       |   Force create the Resource class    |

---

#### Domain

#### `domain:make:action GenerateProfileAction User`
Command for generate an Action inside your Domain directory.

**Arguments**

|  Name  |    Description    |
|:------:|:-----------------:|
|  Name  | Action class name |
| Domain |    Domain Name    |

**Options**

|  Name   |          Description          |
|:-------:|:-----------------------------:|
| --force | Force create the Action class |

---

#### `domain:make:builder UserBuilder User`
Command for generate a Query Builder inside your Domain directory.

**Arguments**

|  Name  |    Description     |
|:------:|:------------------:|
|  Name  | Builder class name |
| Domain |    Domain Name     |

**Options**

|       Name        |             Description              |
|:-----------------:|:------------------------------------:|
| --model=ModelName | To hint Model class on Query Builder |
|      --force      |    Force create the Builder class    |

**Usage**

On Models use the Builder and add a function like bellow.
`src/Domain/Shared/User/Models/User.php`
```php
<?php

namespace Domain\Shared\User\Models;

use Domain\Shared\User\Models\Builders\UserBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin  UserBuilder
 */
class User extends Model
{
    use HasFactory;
    
    /**
     * @param  $query
     * 
     * @return UserBuilder<User>
     */
    public function newEloquentBuilder($query): UserBuilder
    {
        return new UserBuilder(query: $query);
    }
}
```

---

#### `domain:make:data-transfer-object RoleData User`
Command for generate a Data Transfer Object with plain PHP to your domain directory.

**Arguments**

|  Name  |           Description           |
|:------:|:-------------------------------:|
|  Name  | Data Transfer Object class name |
| Domain |           Domain Name           |

**Options**

|  Name   |                 Description                 |
|:-------:|:-------------------------------------------:|
| --force | Force create the Data Transfer Object class |

---

#### `domain:make:enum Role User`
Command for generate an Enum to your Domain directory.

**Arguments**

|  Name  |   Description   |
|:------:|:---------------:|
|  Name  | Enum class name |
| Domain |   Domain Name   |

**Options**

|  Name   |         Description         |
|:-------:|:---------------------------:|
| --force | Force create the Enum class |

---

#### `domain:make:model User User`
Command for generate a Model inside Shared in Domain directory,
all Model will store shared folder since another Domain probably consume
the Model at the same time.

**Arguments**

|  Name  |   Description    |
|:------:|:----------------:|
|  Name  | Model class name |
| Domain |   Domain Name    |

**Options**

|       Name        |                                                       Description                                                        |
|:-----------------:|:------------------------------------------------------------------------------------------------------------------------:|
| -m or --migration |                                         Create Migration file when model created                                         |
|  -f or --factory  | Create Factory class when Model created this option will generate two files, <br/> Factory contract and Factory concrete |
|      --force      |                                               Force create the Model class                                               |

---

#### `domain:make:value-object ReferralCode User`
Command for generate a Value Object class.
this command will generate Value Object class into Domain.

**Arguments**

|  Name  |         Description         |
|:------:|:---------------------------:|
|  Name  |   Value Object name class   |
| Domain |         Domain Name         |

**Options**

|  Name   |               Description               |
|:-------:|:---------------------------------------:|
| --force |   Force create the Value Object class   |

---

#### Infrastructure

#### `infrastructure:make:event PostEvent Post`
Command for generate an Event class to your project.

**Arguments**

|  Name  |   Description    |
|:------:|:----------------:|
|  Name  | Event name class |
| Domain |   Domain Name    |

**Options**

|  Name   |         Description          |
|:-------:|:----------------------------:|
| --force | Force create the Event class |

---

#### `infrastructure:make:factory RoleFactory User`
Command for generate a Factory class, this command would generate two files :

1. Factory concrete at Infrastructure/{DomainName}/Database/Factories
2. Factory Contract at Domain/Shared/Contracts/Database/Factories

The bottom of reason why we did this, cause Factories is an Infrastructure 
component then Domain can't consume any stuff inside Infrastructure, 
so you can do Dependency Injection at Service Provider for resolve this one.

**Arguments**

|  Name  | Description  |
|:------:|:------------:|
|  Name  | Factory Name |
| Domain | Domain Name  |

**Options**

|  Name   |          Description           |
|:-------:|:------------------------------:|
| --force | Force create the Factory class |

---

#### `infrastructure:make:listener PostListener Post`
Command for generate a Listener class to your project.


|  Name  |     Description     |
|:------:|:-------------------:|
|  Name  | Listener name class |
| Domain |     Domain Name     |

**Options**

|       Name        |                      Description                      |
|:-----------------:|:-----------------------------------------------------:|
| --event=NameEvent | For create Event class and use it into Listener class |
|      --force      |            Force create the Listener class            |

---

#### `infrastructure:make:mail ApprovedUser User`
Command for generate a Mail class.
this command will generate Mail class into Infrastructure side because this class purpose is
store to external.

**Arguments**

|  Name  |   Description   |
|:------:|:---------------:|
|  Name  | Mail name class |
| Domain |   Domain Name   |

**Options**

|  Name   |         Description         |
|:-------:|:---------------------------:|
| --force | Force create the Mail class |

---

#### `infrastructure:make:observer UserObserver User`
Command for generate an Observer class to your project.

**Arguments**

|  Name  |     Description     |
|:------:|:-------------------:|
|  Name  | Observer name class |
| Domain |     Domain Name     |

**Options**

|  Name   |           Description           |
|:-------:|:-------------------------------:|
| --force | Force create the Observer class |

---
#### `infrastructure:make:seeder UserSeeder User`
Command for generate a Seeder class.
this command will generate Seeder class into Infrastructure because this class purpose is to insert a test data into table.

**Arguments**

|  Name  |         Description         |
|:------:|:---------------------------:|
|  Name  |      Seeder name class      |
| Domain |         Domain Name         |

**Options**

|  Name   |               Description               |
|:-------:|:---------------------------------------:|
| --force |      Force create the Seeder class      |

---

#### `infrastructure:make:provider FactoryServiceProvider User`
Command for generate a Service Provider class.
this command will generate Service Provider class into Infrastructure to binds between Domain and Infrastructure.

**Arguments**

|  Name  |         Description         |
|:------:|:---------------------------:|
|  Name  | Service Provider name class |
| Domain |         Domain Name         |

**Options**

|  Name   |               Description               |
|:-------:|:---------------------------------------:|
| --force | Force create the Service Provider class |

---

#### Diamond
> Diamond console purpose for generate files in Laravel default structures.

#### `diamond:make:migration create_user_table`
Command for generate a Migration file

**Arguments**

|  Name  | Description |
|:------:|:-----------:|
|  Name  | Table Name  |

**Options**

|        Name        |                      Description                       |
|:------------------:|:------------------------------------------------------:|
| --table=tableName  |    To generate a Migration with purpose edit table     |
| --create=tableName | To generate a Migration with purpose to create a table |

---

### Contribution
Thanks for consideration to contribute to Diamond Console of Domain Driven Design you can go through to
[Contribute Area](https://github.com/KoalaFacade/diamond-console/blob/main/CONTRIBUTE.md)

![KoalaFacade Logo](https://camo.githubusercontent.com/cc271582ba553880fcdfe628ce5a24f4b410c82032469cffb30eaf03afa2944b/68747470733a2f2f692e6962622e636f2f437670575758762f4c6f676f2d4b6f616c616772616d6d65722d62616e6e65722e706e67)

<p align="center">
    <a href="https://github.com/KoalaFacade/diamond-console/actions/workflows/run-test.yml"><img src="https://img.shields.io/github/workflow/status/KoalaFacade/diamond-console/run-tests?label=Test&style=for-the-badge" alt="Test Passing"/></a>
    <a href="https://laravel.com"><img src="https://img.shields.io/badge/Laravel-8.x-red?style=for-the-badge&logo=Laravel" alt="Laravel" /></a>
    <a href="https://php.net"><img src="https://img.shields.io/badge/PHP-8.x-7A86B8?style=for-the-badge&logo=php" alt="PHP Badge"/></a>
</p>

> Artisan command package to handle your Domain Driven Design project that suitable with Laravel base structures, made for comer of Domain Driven Design
and advanced.
>

## Table of Contents

1. [Documentation](#documentation)
    - [Installation](#installation)
    - [Commands](#commands)
        - [Action](#domainmakeaction-generateprofileaction-user)
        - [Enum](#domainmakeenum-role-user)
        - [Data Transfer Object](#domainmakedto-roledata-user)
        - [Migration](#applicationmigration-createusertable)
        - [Model](#domainmakemodel-user-user)
        - [Factory](#infrastructuremakefactory-rolefactory-user)
        - [Mail](#infrastructuremakemail-approveduser-user)
        - [Service Provider](#infrastructuremakeprovider-factoryserviceprovider-user)
        - [Seeder](#infrastructuremakeseeder-userseeder-user)
        - [Value Object](#domainmakevalue-object-referralcode-user)
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

#### `domain:make:dto RoleData User`
Command for generate a Data Transfer Object with plain PHP to your Domain directory.

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

#### `application:migration create_user_table`
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

#### `domain:make:model User User`
Command for generate a Model inside Shared in Domain directory,
all Model will store shared folder since another Domain probably consume
the Model at the same time.

**Arguments**

|  Name  |    Description   |
|:------:|:----------------:|
|  Name  | Model class name |
| Domain |   Domain Name    |

**Options**

|       Name        |                                                       Description                                                        |
|:-----------------:|:------------------------------------------------------------------------------------------------------------------------:|
| -m or --migration |                                         Create Migration file when model created                                         |
|  -f or --factory  | Create Factory class when Model created this option will generate two files, <br/> Factory contract and Factory concrete |
|      --force      |                                                  Force create the Model class                                                   |


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

### Contribution
Thanks for consideration to contribute to Diamond Console of Domain Driven Design you can go through to
[Contribute Area](https://github.com/KoalaFacade/diamond-console/blob/main/CONTRIBUTE.md)

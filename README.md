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
        - [Action](#diamondaction-generateprofileaction-user)
        - [Enum](#diamondenum-role-user)
        - [Data Transfer Object](#diamonddto-roledata-user)
        - [Migration](#diamondmigration-users)
        - [Model](#diamondmodel-user-user)
        - [Factory](#diamondfactory-rolefactory-user)
        - [Mail](#diamondmail-approveduser-user)
2. [Contribution](#contribution)

## Documentation

---

### Installation
Install Diamond Console with composer
```bash
 composer require --dev koalafacade/diamond-console
```
then after Diamond Console installed run command below to set up your project. 
The command below will generate namespace in composer and base directory structures.
```bash
 php artisan diamond:install
```

### Commands

---
#### `diamond:action GenerateProfileAction User`
Command for generate an action inside your domain directory.

**Arguments**

|  Name  |    Description    |
|:------:|:-----------------:|
|  Name  | Action class name |
| Domain |    Domain Name    |

**Options**

|  Name   |       Description       |
|:-------:|:-----------------------:|
| --force | Force create the action |

---

#### `diamond:enum Role User`
Command for generate an enum to your domain directory.

**Arguments**

|  Name  |   Description   |
|:------:|:---------------:|
|  Name  | Enum class name |
| Domain |   Domain Name   |

**Options**

|  Name   |      Description      |
|:-------:|:---------------------:|
| --force | Force create the enum |

---

#### `diamond:dto RoleData User`
Command for generate a Data Transfer Object with plain PHP to your domain directory.

**Arguments**

|  Name  |           Description            |
|:------:|:--------------------------------:|
|  Name  | Data Transfer Object class  name |
| Domain |           Domain Name            |

**Options**

|  Name   |              Description              |
|:-------:|:-------------------------------------:|
| --force | Force create the Data Transfer Object |

---

#### `diamond:migration users`
Command for generate a migration file

**Arguments**

|  Name  | Description |
|:------:|:-----------:|
|  Name  | Table Name  |


---

#### `diamond:model User User`
Command for generate a model inside Shared in Domain directory,
all model will store shared folder since another domain probably consume
the model at the same time.

**Arguments**

|  Name  | Description |
|:------:|:-----------:|
|  Name  | Model  name |
| Domain | Domain Name |

**Options**

|       Name        |                                                       Description                                                        |
|:-----------------:|:------------------------------------------------------------------------------------------------------------------------:|
| -m or --migration |                                         Create migration file when model created                                         |
|  -f or --factory  | Create factory class when model created this option will generate two files, <br/> Factory contract and Factory concrete |
|      --force      |                                                  Force create the enum                                                   |


---

#### `diamond:factory RoleFactory User`
Command for generate a factory class, this command would generate two files :

1. Factory concrete at Infrastructure/{DomainName}/Database/Factories
2. Factory Contract at Domain/Shared/Contracts/Database/Factories

The bottom of reason why we did this, cause Factories is an Infrastructure 
component then Domain can't consume any stuff inside infrastructure, 
so you can do Dependency Injection at Service Provider for resolve this one.

**Arguments**

|  Name  | Description  |
|:------:|:------------:|
|  Name  | Factory Name |
| Domain | Domain Name  |

**Options**

|  Name   |       Description        |
|:-------:|:------------------------:|
| --force | Force create the factory |

---

#### `diamond:mail ApprovedUser User`
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

### Contribution
Thanks for consideration to contribute to Diamond Console of Domain Driven Design you can go through to
[Contribute Area](https://github.com/KoalaFacade/diamond-console/blob/main/CONTRIBUTE.md)

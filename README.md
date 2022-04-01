# Banking
[![Minimum PHP Version](https://img.shields.io/badge/php-8.1-blue)](https://www.php.net)

* [Overview](#overview)
* [Arquitetura](#architecture)
  * [Driver](#driver)
  * [Model](#model)
  * [Driven](#driven)
* [Instalação](#install)
    - [Configuração](#config)
    - [Subindo a aplicação](#up)
* [Endpoints](#endpoints)
* [Testes](#tests)
* [To Do](#todo)

<div id="overview"></div>

## Overview
Este projeto é uma simulação do funcionamento de uma conta bancária. 

Nele podemos:

  * Abrir uma conta
  * Depositar dinheiro
  * Sacar dinheiro

A intenção deste projeto é aplicar conceitos de **Event Sourcing**, **CQRS**, **Domain Driven Design**, **Arquitetura hexagonal**

<div id="architecture"></div>

___
## Arquitetura

Nesse projeto foi aplicado o conceito de arquitetura hexagonal, onde a aplicação é dividida em 3 partes principais:

  * Driver
  * Model
  * Driven

Essas três camadas não devem conhecer o funcionamento umas das outras (baixo acoplamento) e deve ser possível adicionar novas portas sem alterar no funcionamento do produto. Por exemplo:

Nesse projeto foi utilizado somente uma porta de entrada (HTTP), mas é possível adicionar outros como: linha de comando, kafka, etc. E isso não afetará o fluxo de negócio

<div id="driver"></div>

### Driver
Nessa camada temos um adapatdor HTTP que recebe as requisições e as transformam em casos de uso para o núcleo da aplicação

<div id="model"></div>

### Model
Nessa camada estão as regras do negócio que controlam o fluxo da aplicação.

<div id="driven"></div>

### Driven
É a camada de persistência dos dados. Assim como no driver, é possível adicionar novos adaptadores para outros tipos de bancos.
___

<div id="install"></div>

## Instalação

Clone o repositório usando o seguinte comando:
```bash
> https://github.com/WilliamSakata/banking.git
```

<div id="config"></div>

### Configuração
Para fazer a configuração inicial utilize o seguinte comando:
```bash
> make configure
```

<div id="up"></div>

### Subindo a aplicação
```bash
> make up
```

<div id="endpoints"></div>

___
## Endpoints
`URL: http://account.localhost:81`

**POST** `{{URL}}/create`

**Request body**

```json
{
  "cpf": "085.792.800-79"
}
```

**Response**

```HTTP/1.1 209 NO CONTENT```
___
**POST** `{{URL}}/deposit`

**Request body**

```json
{
  "cpf": "085.792.800-79",
  "amount": 100
}
```

```HTTP/1.1 209 NO CONTENT```
___

**POST** `{{URL}}/withdraw`

**Request body**

```json
{
  "cpf": "085.792.800-79",
  "amount": 100
}
```

```HTTP/1.1 209 NO CONTENT```
___

<div id="tests"></div>

## Testes

Comando para executar todos os testes

```bash
> make test
```

<div id="todo"></div>

___
## To Do

  - [ ] Criar um modelo de leitura para os dados das contas 
  
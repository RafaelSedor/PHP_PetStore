# PetStore - Sistema de PetShop

[![Apresentação do Projeto](https://img.youtube.com/vi/0RMEFlIPjpM/0.jpg)](https://youtu.be/0RMEFlIPjpM)

## 1. Descrição do Projeto
PetStore é um sistema de PetShop desenvolvido em PHP utilizando arquitetura MVC, com Vue.js para o frontend e Tailwind CSS para estilização. O projeto faz uso de Docker e Docker Compose para simplificar a configuração do ambiente. O objetivo do PetStore é fornecer uma plataforma robusta para a gestão de produtos, serviços e animais de estimação, permitindo que administradores, usuários e visitantes tenham acesso a funcionalidades específicas.

O sistema inclui:
- Uma área administrativa restrita para a gestão de produtos e categorias.
- Uma área de usuário para o cadastro e acompanhamento de animais e serviços adquiridos.
- Uma interface para visitantes explorarem produtos e serviços disponíveis.
- Funcionalidades de CRUD, upload de imagens, e validações completas.
- Suporte para relações 1xN e NxN.
- Validações de formulários, campos diversos e integração com Ajax para uma experiência dinâmica.

**Repositório do Projeto:** [GitHub - PHP_PetStore](https://github.com/RafaelSedor/PHP_PetStore.git)

## 2. Como Executar o Projeto
### Dependências

- Docker
- Docker Compose

### To run

#### Clone Repository

```
$ git clone https://github.com/RafaelSedor/PHP_PetStore.git
$ cd PetStore
```

#### Define the env variables

```
$ cp .env.example .env
```

#### Install the dependencies

```
$ ./run composer install
```

#### Up the containers

```
$ docker compose up -d
```

ou

```
$ ./run up -d
```

#### Create database and tables

```
$ ./run db:reset
```

#### Populate database

```
$ ./run db:populate
```

### Fixed uploads folder permission

```
sudo chown www-data:www-data public/assets/uploads
```

#### Run the tests

```
$ docker compose run --rm php ./vendor/bin/phpunit tests --color
```

ou

```
$ ./run test
```

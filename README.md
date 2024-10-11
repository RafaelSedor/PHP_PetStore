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
### Pré-requisitos
- Docker
- Docker Compose

### Passo a Passo para Executar:
1. Clone o repositório do projeto:
   ```bash
   git clone https://github.com/RafaelSedor/PHP_PetStore.git
   ```
2. Navegue até o diretório do projeto:
   ```bash
   cd PHP_PetStore
   ```
3. Execute o Docker Compose para configurar e iniciar os serviços:
   ```bash
   docker-compose up --build
   ```
4. Acesse a aplicação no navegador em `http://localhost:8000`.

## 3. Como Contribuir
Contribuições são sempre bem-vindas! Siga as etapas abaixo para contribuir com o projeto:

1. Faça um fork do projeto.
2. Crie uma nova branch para a sua feature ou correção de bug:
   ```bash
   git checkout -b feature/nome-da-feature
   ```
3. Commit suas mudanças:
   ```bash
   git commit -m 'Descrição da nova feature ou correção'
   ```
4. Envie suas alterações para o seu repositório forkado:
   ```bash
   git push origin feature/nome-da-feature
   ```
5. Abra um Pull Request no repositório original.


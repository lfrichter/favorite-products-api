# 🛍️ Favorite Products API

Uma API RESTful construída com **Laravel** para gerenciar clientes e seus **produtos favoritos**, integrando-se à [Fake Store API](https://fakestoreapi.com/) para buscar dados reais de e-commerce.
Ideal para prototipagem, testes de integração e como base para aplicações com *wishlists* (listas de desejos).

!["Favourite Products API"](https://i.imgur.com/o5DPcMF.jpeg)


## 🧰 Tecnologias

<p>
  <img src="https://img.shields.io/badge/PHP-8.3-777BB4?logo=php&logoColor=white" alt="PHP 8.3" />
  <img src="https://img.shields.io/badge/Laravel-12-FF2D20?logo=laravel&logoColor=white" alt="Laravel 12" />
  <img src="https://img.shields.io/badge/PostgreSQL-15-4169E1?logo=postgresql&logoColor=white" alt="PostgreSQL 15" />
  <img src="https://img.shields.io/badge/Sanctum-Auth-FF2D20?logo=laravel&logoColor=white" alt="Laravel Sanctum" />
  <img src="https://img.shields.io/badge/Pest-Testing-FF69B4?logo=laravel&logoColor=white" alt="Pest Testing" />
  <img src="https://img.shields.io/badge/Docker-Compose-2496ED?logo=docker&logoColor=white" alt="Docker Compose" />
  <img src="https://img.shields.io/badge/Swagger-OpenAPI-85EA2D?logo=swagger&logoColor=black" alt="Swagger OpenAPI" />
  <img src="https://img.shields.io/badge/Vite-Frontend-646CFF?logo=vite&logoColor=white" alt="Vite" />
  <img src="https://img.shields.io/badge/TailwindCSS-4.0-38B2AC?logo=tailwindcss&logoColor=white" alt="TailwindCSS 4.0" />
  <img src="https://img.shields.io/badge/TypeScript-Support-3178C6?logo=typescript&logoColor=white" alt="TypeScript Support" />
</p>


| Categoria      | Tecnologia              |
| -------------- | ----------------------- |
| Linguagem      | PHP 8.3+                |
| Framework      | Laravel 12              |
| Banco de Dados | PostgreSQL              |
| Autenticação   | Laravel Sanctum         |
| Ambiente       | Docker + Docker Compose |
| Testes         | Pest                    |
| Documentação   | Swagger (OpenAPI)       |

---

## 🚀 Instruções de Setup

### 1. Clonar o projeto

```bash
git clone https://github.com/lfrichter/favorite-products-api.git
cd favorite-products-api
```

### 2. Configurar o ambiente

```bash
cp .env.example .env
```

### 3. Subir containers

```bash
docker-compose up -d --build
```

### 4. Instalar dependências

```bash
docker-compose exec app composer install
```

### 5. Gerar chave da aplicação

```bash
docker-compose exec app php artisan key:generate
```

### 6. Rodar migrações e seeders

```bash
docker-compose exec app php artisan migrate:fresh --seed
```

---

## 📚 Documentação da API (Swagger)

A documentação completa da API foi gerada com Swagger e está disponível de forma interativa.

### Como Gerar a Documentação

Para regenerar a documentação após fazer alterações nas anotações dos controllers, execute o comando:

```bash
docker-compose exec app php artisan l5-swagger:generate
```

### Como Acessar

Após subir os containers, acesse a URL abaixo no seu navegador:

[http://localhost:8000/api/documentation](http://localhost:8000/api/documentation)


!["Swagger API Documentation"](https://i.imgur.com/o7kWs3f.png)

---

## 🧪 Testes

Rode a suíte de testes completa:

```bash
docker-compose exec app php artisan test
```

!["Tests executed"](https://i.imgur.com/ZXfl7Ev.png)

---

## 📚 Exemplos de Uso (cURL)

### Criar um novo usuário

```bash
curl -X POST http://localhost:8000/api/users \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john.doe@example.com",
    "password": "password"
  }'
```

### Autenticar e Obter Token

Utilize o usuário criado pelo seeder:

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "luis@teste.com",
    "password": "password"
  }'
```

### Adicionar Produto Favorito

```bash
curl -X POST http://localhost:8000/api/favorites \
  -H "Authorization: Bearer {TOKEN}" \
  -H "Content-Type: application/json" \
  -d '{"product_id": 1}'
```

### Listar Produtos Favoritos

```bash
curl -X GET http://localhost:8000/api/favorites \
  -H "Authorization: Bearer {TOKEN}"
```

### Remover Produto Favorito

```bash
curl -X DELETE http://localhost:8000/api/favorites/1 \
  -H "Authorization: Bearer {TOKEN}"
```

---

## 🧩 Decisões de Arquitetura

1. [**Services**](https://github.com/lfrichter/favorite-products-api/blob/main/docs/adr/0001-classes-services-para-logica-de-negocio.md): toda a lógica de negócio e integração com a Fake Store API é isolada em `Services`, mantendo os `Controllers` enxutos.
2. [**API Resources**](https://github.com/lfrichter/favorite-products-api/blob/main/docs/adr/0002-uso-de-api-resources-para-padronizacao-de-respostas.md): formatam as respostas de maneira consistente.
3. [**Form Requests**](https://github.com/lfrichter/favorite-products-api/blob/main/docs/adr/0003-uso-de-form-requests-para-validacao-de-requisicoes.md): garantem validação e padronização das requisições.
4. [**Autenticação via Sanctum**](https://github.com/lfrichter/favorite-products-api/blob/main/docs/adr/0004-escolha-do-laravel-sanctum-para-autenticacao.md): ideal para SPAs e apps móveis, leve e segura.
5. [**Documentação com Swagger**](https://github.com/lfrichter/favorite-products-api/blob/main/docs/adr/0005-documentacao-com-openapi-swagger.md): anotações nos controllers geram a documentação da API automaticamente, facilitando o consumo e a manutenção.


---

## 🌍 Integração com Fake Store API

Os dados dos produtos são obtidos dinamicamente a partir da [Fake Store API](https://fakestoreapi.com/), permitindo simular interações reais de catálogo sem necessidade de um backend de produtos local.

---

## 🧱 Estrutura do Projeto

```
app/
 ├── Http/
 │   ├── Controllers/
 │   │   └── Api/
 │   │       ├── AuthController.php
 │   │       ├── FavoriteProductController.php
 │   │       ├── ProductController.php
 │   │       └── UserController.php
 │   ├── Requests/
 │   └── Resources/
 ├── Models/
 │   ├── FavoriteProduct.php
 │   └── User.php
 ├── Providers/
 ├── Services/
 │   └── FakeStoreApiService.php
 └── ...
database/
 ├── factories/
 ├── migrations/
 └── seeders/
routes/
 ├── api.php
 └── ...
tests/
 ├── Feature/
 └── Unit/
```

## 🧠 Autor

Desenvolvido por **Luis Fernando Richter**
Tech Lead & Senior Software Engineer — Laravel, Vue, TypeScript
🔗 [github.com/lfrichter](https://github.com/lfrichter)

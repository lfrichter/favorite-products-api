# Favorite Products API

Uma API RESTful construída com Laravel para gerenciar clientes e seus produtos favoritos, integrando-se à [Fake Store API](https://fakestoreapi.com/) para dados reais de e-commerce. Ideal para protótipos, testes ou como base para aplicações com listas de desejos (*wishlists*).

## 🛠 Tecnologias Utilizadas

- **PHP 8.3+**
- **Laravel 12**
- **PostgreSQL**
- **Laravel Sanctum** para autenticação
- **Docker** e **Docker Compose** para o ambiente de desenvolvimento
- **Pest** para testes

## 🚀 Setup com Docker

Para rodar o projeto, você precisa ter o Docker e o Docker Compose instalados.

1. **Clone o repositório:**

   ```bash
   git clone https://github.com/seu-usuario/aiqfome-challenge.git
   cd aiqfome-challenge
   ```

2. **Copie o arquivo de ambiente:**

   ```bash
   cp .env.example .env
   ```

3. **Suba os containers do Docker:**

   ```bash
   docker-compose up -d --build
   ```

4. **Instale as dependências do Composer:**

   ```bash
   docker-compose exec app composer install
   ```

5. **Gere a chave da aplicação:**

   ```bash
   docker-compose exec app php artisan key:generate
   ```

6. **Rode as migrações do banco de dados:**

   ```bash
   docker-compose exec app php artisan migrate
   ```

## 🧪 Exemplos de Uso com cURL

### Criar um Cliente

```bash
curl -X POST http://localhost:8000/api/clients \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Richter Belmont",
    "email": "richter@castlevania.com",
    "password": "password123"
  }'
```

### Autenticar e Obter Token

```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "richter@castlevania.com",
    "password": "password123"
  }'
```

### Adicionar um Produto Favorito

**Substitua `{TOKEN}` pelo token obtido na autenticação.**

```bash
curl -X POST http://localhost:8000/api/clients/favorites \
  -H "Authorization: Bearer {TOKEN}" \
  -H "Content-Type: application/json" \
  -d '{
    "product_id": 1
  }'
```

### Listar Produtos Favoritos

```bash
curl -X GET http://localhost:8000/api/clients/favorites \
  -H "Authorization: Bearer {TOKEN}"
```

### Remover um Produto Favorito

```bash
curl -X DELETE http://localhost:8000/api/clients/favorites/1 \
  -H "Authorization: Bearer {TOKEN}"
```

## 🏗 Decisões de Arquitetura

- **Services para Lógica de Negócio**: A lógica de negócio, especialmente a comunicação com a API externa, é encapsulada em `Services` para manter os `Controllers` limpos e focados no tratamento das requisições HTTP.
- **API Resources para Respostas**: As respostas da API são padronizadas usando `API Resources` para garantir um formato consistente.
- **Form Requests para Validação**: A validação das requisições é feita em `Form Requests` para separar as responsabilidades e manter os `Controllers` mais enxutos.
- **Autenticação com Sanctum**: A autenticação é feita com Laravel Sanctum, utilizando tokens de API, por ser uma solução leve e ideal para SPAs e aplicações móveis.


## 📦 Integração com Fake Store API
Este projeto consome dados reais de produtos da [Fake Store API](https://fakestoreapi.com/?spm=a2ty_o01.29997173.0.0.36cac9216BRG96), permitindo simular cenários de e-commerce sem a necessidade de um backend próprio para catálogo.


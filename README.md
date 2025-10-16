# 🛍️ Favorite Products API

Uma API RESTful construída com **Laravel** para gerenciar clientes e seus **produtos favoritos**, integrando-se à [Fake Store API](https://fakestoreapi.com/) para buscar dados reais de e-commerce.
Ideal para prototipagem, testes de integração e como base para aplicações com *wishlists* (listas de desejos).

---

## ⚙️ Tecnologias

| Categoria      | Tecnologia              |
| -------------- | ----------------------- |
| Linguagem      | PHP 8.3+                |
| Framework      | Laravel 12              |
| Banco de Dados | PostgreSQL              |
| Autenticação   | Laravel Sanctum         |
| Ambiente       | Docker + Docker Compose |
| Testes         | Pest                    |

---

## 🚀 Instruções de Setup

### 1. Clonar o projeto

```bash
git clone https://github.com/lfrichter/favorite-products-api.git
cd favorite-products-api
````

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

### 6. Rodar migrações

```bash
docker-compose exec app php artisan migrate
```

---

## 🧪 Testes

Rode a suíte de testes completa:

```bash
docker-compose exec app php artisan test -v
```

---

## 📚 Exemplos de Uso (cURL)

### Autenticar e Obter Token

```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "richter@castlevania.com",
    "password": "password123"
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

* **Services:** toda a lógica de negócio e integração com a Fake Store API é isolada em `Services`, mantendo os `Controllers` enxutos.
* **Form Requests:** garantem validação e padronização das requisições.
* **API Resources:** formatam as respostas de maneira consistente.
* **Autenticação via Sanctum:** ideal para SPAs e apps móveis, leve e segura.

---

## 🌍 Integração com Fake Store API

Os dados dos produtos são obtidos dinamicamente a partir da [Fake Store API](https://fakestoreapi.com/), permitindo simular interações reais de catálogo sem necessidade de um backend de produtos local.

---

## 🧱 Estrutura do Projeto

```
app/
 ├── Http/
 │   ├── Controllers/
 │   ├── Requests/
 │   └── Resources/
 ├── Models/
 ├── Services/
 └── ...
database/
 ├── migrations/
tests/
 ├── Feature/
 └── Unit/
```

## 🧠 Autor

Desenvolvido por **Luis Fernando Richter**
Tech Lead & Senior Software Engineer — Laravel, Vue, TypeScript
🔗 [github.com/lfrichter](https://github.com/lfrichter)

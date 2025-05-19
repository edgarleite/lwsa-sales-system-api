---

# 🛠️ Setup e Documentação do Projeto Laravel com Docker

Este guia detalha todos os passos necessários para configurar, rodar e testar a API do projeto Laravel utilizando Docker.

---

## 1. 📦 Clonar o repositório

```bash
git clone [url-do-repositorio] projeto
cd projeto
```

---

## 2. 📄 Configurar o arquivo `.env`

```bash
cp .env.example .env
```

> ✅ Abra o arquivo `.env` e configure corretamente as variáveis de ambiente, especialmente as de **banco de dados** e **JWT**.

---

## 3. 🔧 Construir as imagens Docker (se necessário)

```bash
docker compose build
```

---

## 4. 🐳 Iniciar os containers Docker

```bash
docker compose up -d
```

---

## 5. 🧩 Instalar dependências do Composer

```bash
docker compose exec app composer install
```

---

## 6. 🔑 Gerar chave da aplicação

```bash
docker compose exec app php artisan key:generate
```

---

## 7. 🔐 Gerar chave JWT

```bash
docker compose exec app php artisan jwt:secret
```

---

## 8. 🗃️ Executar migrações

```bash
docker compose exec app php artisan migrate
```

---

## 9. 🌱 Popular o banco com dados iniciais (seeders)

```bash
docker compose exec app php artisan db:seed
```

---

## 10. 💨 Limpar caches (útil após alterações no ambiente ou configurações)

```bash
docker compose exec app php artisan config:clear
docker compose exec app php artisan cache:clear
docker compose exec app php artisan route:clear
docker compose exec app php artisan view:clear
```

---

## 11. 🔒 Configurar permissões (se necessário)

```bash
docker compose exec app chmod -R 777 storage bootstrap/cache
```

---

## 12. 🧾 Verificar status dos containers

```bash
docker compose ps
```

---

## 13. 🔍 Visualizar logs da aplicação

```bash
docker compose logs app
```

---

## 14. 🧪 Executar testes unitários

```bash
docker compose exec app php artisan test
```

---

## 15. ↻ Reiniciar containers

```bash
docker compose restart
```

---

## 16. ⏹️ Parar containers

```bash
docker compose down
```

---

## 17. 🖥️ Acessar o shell do container da aplicação

```bash
docker compose exec app bash
```

---

## 18. 🛢️ Acessar o banco de dados MySQL

```bash
docker compose exec db mysql -u user -psecret sales_system
```
---

## 🔧 Acessar a API via Navegador (após deploy)

### URL Raiz:
```
http://localhost:8080/
```

### URL Base da API:
```
http://localhost:8080/api/v1
```

> ✅ Após executar `docker compose up -d`, a aplicação estará disponível nesses endereços. Caso precise alterar a porta, verifique as configurações em `docker-compose.yml`.

---

## 📚 Endpoints da API

Abaixo estão listados os endpoints da API com base na Postman Collection fornecida.

---

### 🔐 **Autenticação**

| Método | Endpoint               | Descrição                         |
|--------|------------------------|-----------------------------------|
| POST   | `/login`               | Autentica um usuário             |
| POST   | `/register`            | Registra um novo usuário         |
| POST   | `/logout`              | Invalida o token JWT             |
| POST   | `/refresh`             | Atualiza o token JWT             |
| GET    | `/me`                  | Retorna dados do usuário logado  |

---

### 👨‍💼 **Vendedores**

| Método | Endpoint                    | Descrição                               |
|--------|-----------------------------|-----------------------------------------|
| GET    | `/sellers?per_page=10`      | Lista vendedores com paginação          |
| GET    | `/sellers/{id}`             | Obtém detalhes de um vendedor           |
| POST   | `/sellers`                  | Cria um novo vendedor                   |
| PUT    | `/sellers/{id}`             | Atualiza dados de um vendedor           |
| DELETE | `/sellers/{id}`             | Exclui (soft delete) um vendedor        |
| GET    | `/sellers/{id}/sales`       | Lista todas as vendas de um vendedor    |

---

### 💰 **Vendas**

| Método | Endpoint                | Descrição                                 |
|--------|-------------------------|-------------------------------------------|
| GET    | `/sales?per_page=10`    | Lista vendas com paginação                |
| GET    | `/sales/{id}`           | Obtém detalhes de uma venda               |
| POST   | `/sales`                | Cria uma nova venda                       |
| PUT    | `/sales/{id}`           | Atualiza dados de uma venda               |
| DELETE | `/sales/{id}`           | Exclui (soft delete) uma venda            |

---

### 📊 **Relatórios**

| Método | Endpoint                  | Descrição                                      |
|--------|---------------------------|------------------------------------------------|
| POST   | `/reports/send-daily`     | Envia relatórios diários para todos os vendedores |
| POST   | `/reports/resend/{id}`    | Reenvia relatório para um vendedor específico   |

---

## 📦 Arquivo da Collection do Postman

O arquivo pode ser importado no Postman:

```
sales_system_postman_collection.json
```

> 💡 Importe esse arquivo no Postman para testar facilmente todos os endpoints.

### Variáveis disponíveis na collection:

| Nome       | Valor Padrão                     | Tipo   |
|------------|----------------------------------|--------|
| `base_url` | `http://localhost:8080`          | string |
| `token`    | `seu_token_jwt_aqui`             | string |

---

## 🌐 Testando a API via Navegador

Como exemplo, para listar os primeiros 10 vendedores:

```
http://localhost:8080/api/v1/sellers?per_page=10
```

> ⚠️ Você precisa estar autenticado! Use o endpoint `/login` ou insira o token manualmente no header:

```http
Authorization: Bearer {{token}}
```

---
---

# 🛠️ Setup do Projeto Laravel com Docker

Este guia detalha todos os passos necessários para configurar e rodar o projeto Laravel utilizando Docker.

---

## 1. 📦 Clonar o repositório

```bash
git clone https://github.com/edgarleite/lwsa-sales-system-api.git
cd lwsa-sales-system-api
```

---

## 2. 📄 Configurar o arquivo `.env`

```bash
cp .env.example .env
```

> ✅ Abra o arquivo `.env` e configure corretamente as variáveis de ambiente, especialmente as de **banco de dados** e **JWT**.

---

## 3. 🔧 Construir as imagens Docker

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
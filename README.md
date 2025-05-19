---

# 🛠️ Setup do Projeto Laravel com Docker

Este guia detalha todos os passos necessários para configurar e rodar o projeto Laravel utilizando Docker.

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

## 3. 🐳 Iniciar os containers Docker

```bash
docker compose up -d
```

---

## 4. 🧩 Instalar dependências do Composer

```bash
docker compose exec app composer install
```

---

## 5. 🔑 Gerar chave da aplicação

```bash
docker compose exec app php artisan key:generate
```

---

## 6. 🔐 Gerar chave JWT

```bash
docker compose exec app php artisan jwt:secret
```

---

## 7. 🗃️ Executar migrações

```bash
docker compose exec app php artisan migrate
```

---

## 8. 🌱 Popular o banco com dados iniciais (seeders)

```bash
docker compose exec app php artisan db:seed
```

---

## 9. 💨 Limpar caches (útil após alterações no ambiente ou configurações)

```bash
docker compose exec app php artisan config:clear
docker compose exec app php artisan cache:clear
docker compose exec app php artisan route:clear
docker compose exec app php artisan view:clear
```

---

## 10. 🔒 Configurar permissões (se necessário)

```bash
docker compose exec app chmod -R 777 storage bootstrap/cache
```

---

## 11. 🧾 Verificar status dos containers

```bash
docker compose ps
```

---

## 12. 🔍 Visualizar logs da aplicação

```bash
docker compose logs app
```

---

## 13. 🧪 Executar testes unitários

```bash
docker compose exec app php artisan test --coverage
```

---

## 14. ↻ Reiniciar containers

```bash
docker compose restart
```

---

## 15. ⏹️ Parar containers

```bash
docker compose down
```

---

## 16. 🖥️ Acessar o shell do container da aplicação

```bash
docker compose exec app bash
```

---

## 17. 🛢️ Acessar o banco de dados MySQL

```bash
docker compose exec db mysql -u user -psecret sales_system
```

---
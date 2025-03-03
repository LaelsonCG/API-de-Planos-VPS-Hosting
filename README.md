# 📡 API de Planos VPS Host

Esta API fornece endpoints para gerenciamento de planos de hospedagem e VPS, permitindo autenticação de usuários, listagem de planos, criação, edição e exclusão de planos.

## 🚀 Recursos Principais
- Autenticação de usuários
- Listagem de planos de VPS e hospedagem
- Adição, edição e remoção de planos
- Proteção CORS configurável

## 🌎 URL Base
A API está disponível em:  
`https://api.clickhost.cloud`

---

## 🔑 Autenticação

### **📌 Login**
Autentica um usuário e retorna um token de sessão.  
**Endpoint:**  
```http
POST /login
```
**Corpo da requisição (JSON):**
```json
{
  "usuario": "admin",
  "senha": "123456"
}
```
**Resposta de sucesso (200 OK):**
```json
{
  "status": "success",
  "token": "gerado_token_aqui"
}
```
**Resposta de erro (403 Forbidden):**
```json
{
  "status": "error",
  "message": "Usuário ou senha inválidos"
}
```

---

## 📦 Gerenciamento de Planos

### **📌 Listar Planos**
Obtém todos os planos cadastrados.  
**Endpoint:**  
```http
GET /planos
```
**Resposta de sucesso (200 OK):**
```json
[
  {
    "id": 1,
    "categoria": "VPS",
    "nome": "VPS Básico",
    "descricao": "Servidor VPS com 2 vCPUs e 4GB RAM",
    "url_compra": "https://exemplo.com/comprar-vps"
  }
]
```

---

### **📌 Criar um Novo Plano**
Adiciona um novo plano à API.  
**Endpoint:**  
```http
POST /planos
```
**Corpo da requisição (JSON):**
```json
{
  "token": "gerado_token_aqui",
  "categoria": "Hospedagem",
  "nome": "Plano Ilimitado",
  "descricao": "Hospedagem com tráfego ilimitado e suporte 24/7",
  "url_compra": "https://exemplo.com/compra"
}
```
**Resposta de sucesso (201 Created):**
```json
{
  "status": "success",
  "message": "Plano adicionado com sucesso",
  "id": 2
}
```
**Resposta de erro (400 Bad Request):**
```json
{
  "status": "error",
  "message": "Campos obrigatórios ausentes"
}
```

---

### **📌 Editar um Plano**
Modifica os detalhes de um plano existente.  
**Endpoint:**  
```http
PUT /planos/{id}
```
**Exemplo de requisição (JSON):**
```json
{
  "token": "gerado_token_aqui",
  "nome": "VPS Premium",
  "descricao": "Servidor VPS com 4 vCPUs e 8GB RAM"
}
```
**Resposta de sucesso (200 OK):**
```json
{
  "status": "success",
  "message": "Plano atualizado com sucesso"
}
```
**Resposta de erro (404 Not Found):**
```json
{
  "status": "error",
  "message": "Plano não encontrado"
}
```

---

### **📌 Excluir um Plano**
Remove um plano pelo seu ID.  
**Endpoint:**  
```http
DELETE /planos/{id}
```
**Exemplo de requisição:**
```http
DELETE /planos/2
```
**Resposta de sucesso (200 OK):**
```json
{
  "status": "success",
  "message": "Plano excluído com sucesso"
}
```
**Resposta de erro (404 Not Found):**
```json
{
  "status": "error",
  "message": "Plano não encontrado"
}
```

---

## ⚙️ Configuração de CORS
Se precisar desativar temporariamente a proteção CORS na API, adicione o seguinte código ao início do arquivo `index.php`:

```php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}
```
⚠ **Atenção:** Desativar CORS completamente pode gerar riscos de segurança. Use essa configuração apenas para testes.

---

## 🛠 Tecnologias Utilizadas
- PHP 7.4+
- JSON como banco de dados
- Apache/Nginx (dependendo da hospedagem)

---

## 📌 Contribuindo
Se quiser contribuir com melhorias, faça um fork do repositório, crie um branch com as suas alterações e envie um pull request.

1. Faça um fork do projeto
2. Crie um branch: `git checkout -b minha-melhoria`
3. Commit suas mudanças: `git commit -m 'Adicionei nova funcionalidade'`
4. Push para o branch: `git push origin minha-melhoria`
5. Abra um Pull Request

---

## 📞 Suporte
Caso tenha dúvidas ou precise de suporte, entre em contato via WhatsApp: (83) 9 8204-6892

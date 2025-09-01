
# 📚 Projeto Web – 3º Bimestre

## 📝 Descrição Geral
Este projeto consiste no desenvolvimento de um **sistema de gerenciamento de biblioteca** baseado em **arquitetura de API RESTful**, implementada em **PHP** com **MySQL** como sistema de gerenciamento de banco de dados.  

O sistema contempla operações de **CRUD (Create, Read, Update, Delete)** sobre as entidades principais — **Autores, Gêneros, Livros e Funcionários** — além de **autenticação e autorização** utilizando **JWT (JSON Web Token)**.  

O projeto foi estruturado em camadas, visando **manutenibilidade, escalabilidade e separação de responsabilidades**.  

---

## ⚙️ Arquitetura e Estrutura do Projeto

```

Projeto\_Web\_3Bimestre/
│── .htaccess                  # Configuração do servidor (URL rewriting)
│── api.php                    # Ponto de entrada da API
│── api/
│   ├── src/
│   │   ├── controllers/       # Controladores responsáveis pela lógica de cada entidade
│   │   │   ├── AutorControl.php
│   │   │   ├── LivroControl.php
│   │   │   ├── GeneroControl.php
│   │   │   ├── FuncionarioControl.php
│   │   │   └── LoginControl.php
│   │   ├── DAO/               # Data Access Objects (comunicação com o banco de dados)
│   │   │   ├── AutorDAO.php
│   │   │   ├── LivroDAO.php
│   │   │   ├── GeneroDAO.php
│   │   │   ├── FuncionarioDAO.php
│   │   │   └── LoginDAO.php
│   │   ├── db/                # Configuração e scripts SQL
│   │   │   ├── Database.php
│   │   │   └── create database.txt
│   │   ├── http/              # Respostas HTTP padronizadas
│   │   │   └── Response.php
│   │   ├── middlewares/       # Middlewares para autenticação e validações
│   │   │   ├── JWTMiddleware.php
│   │   │   └── (outros para Autor, Livro, etc.)
│   │   └── models/            # Modelos das entidades (Autor, Livro, Gênero, Funcionário)

```

### 🔑 Camadas do Sistema

- **Controllers** → Implementam a lógica de negócios e recebem as requisições.  
- **DAO (Data Access Object)** → Responsáveis por interagir com o banco de dados (MySQL).  
- **Models** → Representam a estrutura das entidades do sistema.  
- **Middlewares** → Garantem autenticação, validação de dados e autorização via JWT.  
- **HTTP** → Padronização das respostas ao cliente.  

---

## 🗄️ Banco de Dados

O banco de dados foi implementado em **MySQL**.  
O script de criação está em:  
```

api/src/db/create database.txt

```

As tabelas contemplam as seguintes entidades:  
- **Autor**  
- **Gênero**  
- **Livro**  
- **Funcionário**  
- **Login**  

Cada entidade está devidamente normalizada e relacionada conforme boas práticas de modelagem de dados relacionais.  

---

## 🔑 Autenticação

A autenticação utiliza **JWT (JSON Web Token)**.  
- O usuário realiza login via `POST /login`.  
- O sistema retorna um **token JWT**.  
- Esse token deve ser enviado no cabeçalho de cada requisição autenticada:  

```

Authorization: Bearer \<seu\_token>

````

Os middlewares são responsáveis por validar o token em cada requisição.  

---

## 📌 Endpoints Principais

### 🔹 Autores
- `GET /autores` → Lista todos os autores.  
- `POST /autores` → Cria um novo autor.  
- `PUT /autores/{id}` → Atualiza dados de um autor.  
- `DELETE /autores/{id}` → Exclui um autor.  

### 🔹 Gêneros
- `GET /generos`  
- `POST /generos`  
- `PUT /generos/{id}`  
- `DELETE /generos/{id}`  

### 🔹 Livros
- `GET /livros`  
- `POST /livros`  
- `PUT /livros/{id}`  
- `DELETE /livros/{id}`  

### 🔹 Funcionários
- `GET /funcionarios`  
- `POST /funcionarios`  
- `PUT /funcionarios/{id}`  
- `DELETE /funcionarios/{id}`  

### 🔹 Login
- `POST /login` → Autenticação e geração de token JWT.  

---

## ▶️ Como Executar

1. Clone o repositório:  
   ```bash
   git clone https://github.com/sbaarzii/Projeto-WEB-.git
````

2. Configure um servidor **Apache + PHP 8** (pode usar XAMPP, WAMP ou Docker).
3. Crie o banco de dados no MySQL utilizando o script em `create database.txt`.
4. Configure as credenciais do banco em:

   ```
   api/src/db/Database.php
   ```
5. Inicie o servidor Apache.
6. Acesse os endpoints da API via navegador ou ferramenta de testes (Postman, Insomnia).

---

## ✅ Conclusão

Este projeto demonstra a aplicação de conceitos de **arquitetura em camadas, boas práticas de desenvolvimento backend e segurança via JWT**.
Serve como base para sistemas que demandem **gestão de entidades** e **proteção de rotas** com autenticação.



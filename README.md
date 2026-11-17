# Kanban Laravel

Sistema de gerenciamento de tarefas no estilo Kanban (quadros, listas e tarefas), desenvolvido em **PHP com Laravel**. Cada usuário cria seus próprios quadros, organiza tarefas em listas e reordena tudo com **drag-and-drop**.

> Projeto pessoal de estudo, criado para praticar os fundamentos do Laravel: Eloquent, relacionamentos, rotas, autenticação e autorização.

<!-- Adicione aqui um print ou GIF da tela do quadro -->
<!-- ![Tela do quadro](docs/screenshot.png) -->

---

## Funcionalidades

- **Autenticação**: cadastro, login, logout e recuperação de senha (Laravel Breeze)
- **Quadros**: criar, listar, renomear e excluir
- **Listas**: criar, renomear e excluir dentro de cada quadro (ex.: *To do*, *Doing*, *Done*)
- **Tarefas**: criar, renomear e excluir dentro de cada lista, com descrição opcional
- **Drag-and-drop**:
  - reordenar listas dentro do quadro
  - reordenar tarefas dentro de uma lista
  - mover tarefas entre listas
  - a nova ordem é salva no banco de dados
- **Isolamento entre usuários**: ninguém acessa, edita ou reorganiza o conteúdo de outra pessoa (resposta `403`)

---

## Tecnologias

| Camada | Tecnologia |
| --- | --- |
| Back-end | PHP 8.4 e Laravel 13 |
| Banco de dados | SQLite |
| Autenticação | Laravel Breeze (stack Blade) |
| Front-end | Blade, Tailwind CSS e Alpine.js |
| Drag-and-drop | SortableJS |
| Build dos assets | Vite |

---

## Modelo de dados

```
User ──< Board ──< BoardList ──< Task
```

| Tabela | Principais colunas |
| --- | --- |
| `users` | id, name, email, password |
| `boards` | id, **user_id**, name |
| `board_lists` | id, **board_id**, name, position |
| `tasks` | id, **board_list_id**, name, description, position |

- Todas as chaves estrangeiras usam **exclusão em cascata**: apagar um quadro remove suas listas e tarefas.
- A coluna `position` guarda a ordem de listas e tarefas, e os relacionamentos `lists()` e `tasks()` já retornam os itens ordenados por ela.

---

## Rotas principais

As rotas de listas e tarefas são **aninhadas** e usam `shallow()`, mantendo as URLs curtas onde o pai não é necessário.

| Método | Rota | Ação |
| --- | --- | --- |
| `GET` | `/boards` | Lista os quadros do usuário |
| `POST` | `/boards` | Cria um quadro |
| `GET` | `/boards/{board}` | Exibe o quadro com listas e tarefas |
| `PUT/PATCH` | `/boards/{board}` | Atualiza o quadro |
| `DELETE` | `/boards/{board}` | Exclui o quadro |
| `POST` | `/boards/{board}/lists` | Cria uma lista |
| `POST` | `/boards/{board}/lists/reorder` | Salva a nova ordem das listas |
| `PUT/PATCH` | `/lists/{list}` | Renomeia a lista |
| `DELETE` | `/lists/{list}` | Exclui a lista |
| `POST` | `/lists/{list}/tasks` | Cria uma tarefa |
| `POST` | `/lists/{list}/tasks/reorder` | Salva a ordem das tarefas (e a lista de destino) |
| `PUT/PATCH` | `/tasks/{task}` | Atualiza a tarefa |
| `DELETE` | `/tasks/{task}` | Exclui a tarefa |

Todas exigem usuário autenticado.

---

## Autorização

A autorização é centralizada em **Policies** (`BoardPolicy`, `BoardListPolicy` e `TaskPolicy`). Em cada caso, a regra é a mesma: só o dono do quadro pode agir sobre ele ou sobre qualquer coisa que esteja dentro dele.

Como listas e tarefas não têm `user_id`, a Policy chega ao dono pelos relacionamentos:

```
Task → BoardList → Board → user_id
```

Os controllers chamam `$this->authorize(...)` e o Laravel devolve `403 Forbidden` automaticamente quando a Policy nega o acesso.

---

## Como rodar localmente

### Pré-requisitos

- PHP 8.4 (versão usada no desenvolvimento) com as extensões `zip`, `mbstring`, `fileinfo`, `openssl` e `pdo_sqlite` habilitadas
- [Composer](https://getcomposer.org/)
- [Node.js](https://nodejs.org/) e npm

### Passo a passo

```bash
# 1. Clone o repositório
git clone https://github.com/SEU-USUARIO/NOME-DO-REPOSITORIO.git
cd NOME-DO-REPOSITORIO

# 2. Instale as dependências
composer install
npm install

# 3. Configure o ambiente
cp .env.example .env          # no PowerShell: copy .env.example .env
php artisan key:generate

# 4. Crie o banco SQLite e rode as migrations
touch database/database.sqlite   # no PowerShell: New-Item database/database.sqlite
php artisan migrate
```

Confira no `.env` se a conexão está assim:

```env
DB_CONNECTION=sqlite
```

### Subindo a aplicação

Abra **dois terminais** na pasta do projeto:

```bash
# Terminal 1: servidor do Laravel
php artisan serve

# Terminal 2: assets (Vite + Tailwind)
npm run dev
```

Acesse **http://127.0.0.1:8000**, crie uma conta e comece a montar seus quadros.

---

## Estrutura do projeto

```
app/
├── Http/Controllers/
│   ├── BoardController.php
│   ├── BoardListController.php
│   └── TaskController.php
├── Models/
│   ├── Board.php
│   ├── BoardList.php
│   └── Task.php
└── Policies/
    ├── BoardPolicy.php
    ├── BoardListPolicy.php
    └── TaskPolicy.php

resources/views/
├── boards/            # index, create, edit, show
└── components/
    └── board-form.blade.php   # formulário reutilizável (create/edit)

routes/web.php         # rotas protegidas por auth
```

---

## O que pratiquei neste projeto

- Arquitetura **MVC** no Laravel
- **Migrations**, chaves estrangeiras e exclusão em cascata
- **Eloquent**: `hasMany`, `belongsTo`, `$fillable` (mass assignment) e ordenação em relacionamentos
- **Rotas resource aninhadas** com `shallow()` e **Route Model Binding**
- **Validação** de formulários com `validate()` e exibição de erros no Blade
- **Policies** e `authorize()` para autorização em camadas
- **Componentes Blade** com `@props` para evitar repetição
- Integração **back-end + JavaScript**: SortableJS, `fetch` com token CSRF e endpoints que respondem em JSON

---

## Possíveis melhorias

- Edição da descrição da tarefa diretamente na tela do quadro
- Mensagens de sucesso após criar, editar e excluir
- Testes automatizados (Feature Tests com PHPUnit/Pest)
- Cores, prazos e etiquetas nas tarefas
- Compartilhamento de quadros entre usuários

---

## Autor

Desenvolvido por **Pedro** como projeto de estudo.

- GitHub: [@SEU-USUARIO](https://github.com/SEU-USUARIO)

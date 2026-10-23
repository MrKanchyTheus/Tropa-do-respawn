# Tropa do Respawn - Wiki de Jogos & Comunidade Gamer

Plataforma oficial da **Tropa do Respawn**, criada por e para gamers. Um ponto de encontro com wiki, dicas, guias estratégicos, tutoriais e fóruns interativos onde os jogadores podem compartilhar táticas, comentar e avaliar tópicos.

🌐 **URL de Produção:** [https://tropadorespwan.infinityfree.io/](https://tropadorespwan.infinityfree.io/)

---

## 🎮 Funcionalidades Principais

- **Wiki de Jogos & Guias Estratégicos:**
  - Jogos em destaque: *Brawl Stars, Jujutsu Shenanigans (JJS), 99 Nights in the Forest, All Star X, Volleyball Legends, Roblox, Fortnite*.
  - Categorias: *Jogos, Guias, Tutoriais, Listas (Tier Lists), Regras*.
  - Busca rápida integrada na Wiki e navegação por jogo.

- **Comunidade & Fóruns Interativos:**
  - Criação de fóruns de discussão sobre combos, táticas e dúvidas.
  - **Sistema de Avaliação:** Avaliação com estrelas (1 a 5 estrelas) com recálculo automático de média e contagem de votos.
  - **Respostas & Comentários:** Interação entre jogadores com ganho de XP.

- **Autenticação Segura & Gamificação:**
  - Cadastro com validação de formato de e-mail, termos de uso e **data de nascimento**.
  - Senhas criptografadas com **BCrypt** (`password_hash` / `password_verify`).
  - Sessões nativas do PHP.
  - Sistema de Nível, XP Gamer e badges.

- **Regra de Negócio de Idade Mínima (CORE):**
  - Para criar tópicos no fórum, o usuário deve estar logado e possuir a **idade mínima** (14 anos). Usuários abaixo da idade mínima são informados de forma amigável através de MODAL e impedidos de publicar para segurança da comunidade.

- **Histórico & Auditoria Completa (CORE):**
  - Registro contínuo e persistente em `historico_logs` de qualquer alteração feita por qualquer usuário (cadastros, logins, criação/exclusão de fóruns, comentários, avaliações, chamados de suporte).
  - Tela dedicada `historico.php` com filtros por ação, usuário, data e busca de texto.

- **Interface Gamer Responsiva (Tailwind CSS Mobile-First):**
  - Paleta de cores oficial: Fundo preto e superfícies escuras com detalhes em roxo neon (`#9333ea`, `#a855f7`).
  - Tipografias: **Inter**, **Rajdhani** e **JetBrains Mono**.
  - **TOASTs** elegantes para mensagens de sucesso, erro e alertas.
  - **MODAL** gamer customizado que intercepta alertas nativos do navegador (`window.alert` e confirmações).

---

## 📁 Estrutura do Projeto

```text
Projeto tropa do respawn/
├── actions/                  # Scripts de backend isolados
│   ├── auth_login.php        # Processamento de login
│   ├── auth_logout.php       # Encerramento de sessão
│   ├── auth_register.php     # Cadastro com hash BCrypt
│   ├── forum_avaliar.php     # Avaliação de 1 a 5 estrelas
│   ├── forum_comentar.php    # Comentários em tópicos
│   ├── forum_criar.php       # Criação de fórum com checagem de idade
│   ├── forum_excluir.php     # Exclusão de fórum por autor/admin
│   └── suporte_enviar.php    # Envio de chamados de atendimento
├── assets/
│   ├── css/custom.css        # Efeitos neon, fontes e animações
│   ├── img/                  # Imagens oficiais, logo e mascote gamer
│   └── js/app.js             # Modais, Toasts, estrelas e interações
├── config/
│   ├── bd.php                # Conexão PDO segura (MySQL / Fallback SQLite)
│   └── schema.sql            # Script DDL completo para MySQL / InfinityFree
├── includes/
│   ├── auth.php              # Verificação de sessão e idade
│   ├── footer.php            # Rodapé compartilhado
│   ├── functions.php         # Sanitização, idade, tempo relativo
│   ├── head.php              # Tailwind, fontes e meta tags
│   ├── logger.php            # Registro no histórico de auditoria
│   ├── modal.php             # Modal gamer universal
│   ├── nav.php               # Barra de navegação responsiva
│   ├── sidebar.php           # Barra lateral da Wiki
│   └── toast.php             # Notificações flutuantes (Toasts)
├── index.php                 # Página inicial ("Dicas que te levam mais longe")
├── wiki.php                  # Hub central da Wiki com categorias
├── jogos.php                 # Catálogo de jogos em destaque
├── guia.php                  # Leitor de guia / artigo completo
├── comunidade.php            # Lista de fóruns e discussões
├── forum_detalhe.php         # Tópico completo com comentários e estrelas
├── forum_novo.php            # Formulário de criação de fórum
├── historico.php             # Página de Auditoria e Histórico (CORE)
├── suporte.php               # Central de suporte e atendimento
├── login.php                 # Tela de Login gamer
├── registro.php              # Tela de Cadastro gamer
├── perfil.php                # Perfil do jogador com conquistas e XP
├── requisitos.md             # Requisitos originais do projeto
└── README.md                 # Documentação do projeto
```

---

## 🚀 Como Executar Localmente

### Pré-requisitos
- PHP 8.0+ com extensões `pdo`, `pdo_mysql` e `pdo_sqlite` habilitadas.

### 1. Iniciar o Servidor Embutido do PHP
Abra o terminal na pasta do projeto e execute:
```bash
php -S 127.0.0.1:8000
```
Ou no PowerShell:
```powershell
& "$env:USERPROFILE\php83\php.exe" -S 127.0.0.1:8000
```

### 2. Acessar no Navegador
Abra seu navegador em:
👉 **[http://127.0.0.1:8000](http://127.0.0.1:8000)**

> **Nota de Compatibilidade:** O sistema possui conexão inteligente via PDO: caso o MySQL local não esteja em execução, o sistema utiliza automaticamente uma base de dados local SQLite (`data/database.sqlite`), pré-populada com jogos, guias, fóruns e logs de auditoria para testes imediatos sem nenhuma configuração adicional!

---

## 🗄️ Configuração do Banco de Dados MySQL (Produção / InfinityFree / XAMPP)

1. No phpMyAdmin ou terminal MySQL, crie o banco de dados:
   ```sql
   CREATE DATABASE tropa_do_respawn CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```
2. Importe o arquivo `config/schema.sql`.
3. Ajuste as credenciais no arquivo `config/bd.php` ou via variáveis de ambiente (`DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`).

---

## 👤 Contas de Teste Pré-cadastradas

| Usuário | E-mail | Senha | Nível | Tipo | Idade |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **ShadowGamer** | `admin@tropadorespawn.com` | `tropa123` | Nv. 23 | Administrador | 24 anos (Apto a criar fórum) |
| **CyberValkyrie** | `valkyrie@gamers.com` | `tropa123` | Nv. 14 | Jogadora | 21 anos (Apta a criar fórum) |

---

## 📜 Licença
Distribuído sob a licença MIT. Consulte o arquivo [LICENSE](LICENSE) para mais detalhes.

<?php
/**
 * Tropa do Respawn - Conexão e Gerenciamento de Banco de Dados via PDO
 * Atende às Regras Globais: Uso exclusivo de PDO, proteção contra SQL Injection
 * e compatibilidade dupla: MySQL (produção/InfinityFree/XAMPP) com fallback SQLite local.
 */

// Inicia sessão nativa caso ainda não tenha sido iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Configurações do Banco de Dados MySQL
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_PORT', getenv('DB_PORT') ?: '3306');
define('DB_NAME', getenv('DB_NAME') ?: 'tropa_do_respawn');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_CHARSET', 'utf8mb4');

// Regra de Negócio: Idade mínima para criação de fóruns (em anos)
define('IDADE_MINIMA_FORUM', 14);

/**
 * Retorna uma instância PDO configurada
 */
function obterConexao(): PDO {
    static $pdo = null;

    if ($pdo !== null) {
        return $pdo;
    }

    $opcoes = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    // Tenta primeiro conectar ao MySQL
    try {
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $opcoes);
        garantirEstruturaBanco($pdo, 'mysql');
        return $pdo;
    } catch (PDOException $eMysql) {
        // Se a base de dados MySQL não existir ainda no MySQL local, tenta criar
        if (strpos($eMysql->getMessage(), 'Unknown database') !== false) {
            try {
                $dsnRoot = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";charset=" . DB_CHARSET;
                $pdoRoot = new PDO($dsnRoot, DB_USER, DB_PASS, $opcoes);
                $pdoRoot->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                $pdo = new PDO("mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET, DB_USER, DB_PASS, $opcoes);
                garantirEstruturaBanco($pdo, 'mysql');
                return $pdo;
            } catch (Exception $eCreate) {
                // Cai no fallback SQLite se MySQL falhar
            }
        }

        // Fallback robusto para SQLite em ambiente local onde MySQL não estiver rodando
        $diretorioData = __DIR__ . '/../data';
        if (!is_dir($diretorioData)) {
            mkdir($diretorioData, 0777, true);
        }
        $caminhoSqlite = $diretorioData . '/database.sqlite';
        $dsnSqlite = 'sqlite:' . $caminhoSqlite;
        
        $pdo = new PDO($dsnSqlite, null, null, $opcoes);
        $pdo->exec("PRAGMA foreign_keys = ON;");
        garantirEstruturaBanco($pdo, 'sqlite');
        return $pdo;
    }
}

/**
 * Cria as tabelas e dados iniciais se ainda não existirem
 */
function garantirEstruturaBanco(PDO $pdo, string $driver): void {
    if ($driver === 'sqlite') {
        // Tabelas SQLite
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS usuarios (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username TEXT UNIQUE NOT NULL,
                nome_completo TEXT NOT NULL,
                email TEXT UNIQUE NOT NULL,
                senha TEXT NOT NULL,
                data_nascimento TEXT NOT NULL,
                avatar TEXT DEFAULT 'assets/img/mascote.jpeg',
                nivel INTEGER DEFAULT 1,
                xp INTEGER DEFAULT 150,
                tipo TEXT DEFAULT 'usuario',
                criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
            );

            CREATE TABLE IF NOT EXISTS jogos (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                slug TEXT UNIQUE NOT NULL,
                nome TEXT NOT NULL,
                categoria TEXT NOT NULL,
                descricao TEXT NOT NULL,
                imagem TEXT NOT NULL,
                banner TEXT,
                total_dicas INTEGER DEFAULT 0,
                criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
            );

            CREATE TABLE IF NOT EXISTS artigos_wiki (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                jogo_id INTEGER,
                usuario_id INTEGER NOT NULL,
                categoria TEXT NOT NULL,
                titulo TEXT NOT NULL,
                slug TEXT UNIQUE NOT NULL,
                resumo TEXT NOT NULL,
                conteudo TEXT NOT NULL,
                imagem TEXT,
                tempo_leitura TEXT DEFAULT '5 min',
                visualizacoes INTEGER DEFAULT 0,
                criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
            );

            CREATE TABLE IF NOT EXISTS foruns (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                usuario_id INTEGER NOT NULL,
                jogo_id INTEGER,
                categoria TEXT NOT NULL,
                titulo TEXT NOT NULL,
                conteudo TEXT NOT NULL,
                tags TEXT,
                visualizacoes INTEGER DEFAULT 0,
                media_avaliacao REAL DEFAULT 0.00,
                total_avaliacoes INTEGER DEFAULT 0,
                total_comentarios INTEGER DEFAULT 0,
                criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
                atualizado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
            );

            CREATE TABLE IF NOT EXISTS forum_comentarios (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                forum_id INTEGER NOT NULL,
                usuario_id INTEGER NOT NULL,
                conteudo TEXT NOT NULL,
                criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (forum_id) REFERENCES foruns(id) ON DELETE CASCADE,
                FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
            );

            CREATE TABLE IF NOT EXISTS forum_avaliacoes (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                forum_id INTEGER NOT NULL,
                usuario_id INTEGER NOT NULL,
                nota INTEGER NOT NULL,
                criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
                UNIQUE (forum_id, usuario_id),
                FOREIGN KEY (forum_id) REFERENCES foruns(id) ON DELETE CASCADE,
                FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
            );

            CREATE TABLE IF NOT EXISTS chamados_suporte (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                usuario_id INTEGER,
                nome TEXT NOT NULL,
                email TEXT NOT NULL,
                canal TEXT DEFAULT 'Formulário',
                categoria TEXT NOT NULL,
                mensagem TEXT NOT NULL,
                status TEXT DEFAULT 'Aberto',
                criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
            );

            CREATE TABLE IF NOT EXISTS historico_logs (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                usuario_id INTEGER,
                usuario_nome TEXT NOT NULL,
                acao TEXT NOT NULL,
                entidade TEXT NOT NULL,
                registro_id INTEGER,
                detalhes TEXT NOT NULL,
                ip_origem TEXT NOT NULL,
                criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
            );
        ");
    } else {
        // Tabelas MySQL
        $script = file_get_contents(__DIR__ . '/schema.sql');
        if ($script) {
            $pdo->exec($script);
        }
    }

    // Popula dados essenciais se o banco estiver vazio
    popularDadosIniciais($pdo);
}

/**
 * Insere sementes (seed) de usuários, jogos, artigos e fóruns se não existirem
 */
function popularDadosIniciais(PDO $pdo): void {
    // Verifica se já existem usuários
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM usuarios");
    $total = (int) $stmt->fetch()['total'];

    if ($total === 0) {
        // Cria usuário padrão gamer / admin
        $senhaHash = password_hash('tropa123', PASSWORD_BCRYPT);
        $insertUser = $pdo->prepare("
            INSERT INTO usuarios (username, nome_completo, email, senha, data_nascimento, avatar, nivel, xp, tipo)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $insertUser->execute([
            'ShadowGamer',
            'Matheus Kanchy',
            'admin@tropadorespawn.com',
            $senhaHash,
            '2002-05-15',
            'assets/img/mascote.jpeg',
            23,
            4250,
            'admin'
        ]);
        $shadowId = (int) $pdo->lastInsertId();

        $insertUser->execute([
            'CyberValkyrie',
            'Ana Clara Ribeiro',
            'valkyrie@gamers.com',
            $senhaHash,
            '2005-08-20',
            'assets/img/logo.jpeg',
            14,
            1890,
            'usuario'
        ]);
        $valkId = (int) $pdo->lastInsertId();

        // Insere Jogos em Destaque mencionados na documentação
        $jogos = [
            [
                'brawl-stars',
                'Brawl Stars',
                'Mobile / Ação',
                'Batalhas 3v3 e modo Battle Royale frenético com diversos brawlers únicos e atualizações constantes.',
                'assets/img/logo.jpeg',
                12
            ],
            [
                'jujutsu-shenanigans',
                'Jujutsu Shenanigans (JJS)',
                'Roblox / Luta',
                'Jogo dinâmico inspirado em Jujutsu Kaisen com combos velozes, técnicas especiais e expansões de domínio.',
                'assets/img/mascote.jpeg',
                18
            ],
            [
                '99-nights-in-the-forest',
                '99 Nights in the Forest',
                'Sobrevivência',
                'Guia completo de sobrevivência, táticas noturnas, rotas seguras e estratégias para farm de gemas raras.',
                'assets/img/banner-home.jpeg',
                9
            ],
            [
                'all-star-x',
                'All Star X',
                'Anime / Tower Defense',
                'Builds definitivas, tier lists atualizadas de unidades meta e táticas para dominar cada estágio.',
                'assets/img/logo.jpeg',
                15
            ],
            [
                'volleyball-legends',
                'Volleyball Legends',
                'Esportes',
                'Dicas de saque perfeito, timing de bloqueio, habilidades especiais e tier lists de posições.',
                'assets/img/mascote.jpeg',
                7
            ],
            [
                'roblox-geral',
                'Roblox (Geral)',
                'Sandbox',
                'Tutoriais, códigos ativos, novos lançamentos e melhores mapas da plataforma mais jogada do mundo.',
                'assets/img/banner-home.jpeg',
                25
            ],
            [
                'fortnite',
                'Fortnite',
                'Battle Royale',
                'Construção, mira competitiva, rotas de loot e guias semanais da temporada.',
                'assets/img/logo.jpeg',
                30
            ]
        ];

        $insJogo = $pdo->prepare("
            INSERT INTO jogos (slug, nome, categoria, descricao, imagem, total_dicas)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        foreach ($jogos as $j) {
            $insJogo->execute($j);
        }

        // Insere Artigos da Wiki
        $artigos = [
            [
                1,
                $shadowId,
                'Guias',
                'Melhores brawlers para o controle de mapa em 2026',
                'melhores-brawlers-controle-mapa',
                'Entenda quais brawlers dominam as partidas ranqueadas e como se posicionar defensivamente e ofensivamente.',
                "O controle de terreno no Brawl Stars é o fator decisivo para vencer nos modos Pique-Gema, Zona Estratégica e Fute-Brawl.\n\n### 1. Brawlers Controladores em Destaque\n- **Sandy**: Seu Super de tempestade de areia garante invisibilidade tática em grupo.\n- **Emz**: Zona de spray de dano contínuo que repele tanques com facilidade.\n- **Otis**: Seu efeito de silêncio anula qualquer jogada adversária crucial.\n\n### Dica Pro da Tropa do Respawn\nSempre jogue em equipe sincronizando o tempo de recarga das habilidades. Nunca avance sozinho sem visão dos arbustos!",
                'assets/img/logo.jpeg',
                '6 min',
                1420
            ],
            [
                2,
                $shadowId,
                'Tutoriais',
                'Guia completo do Jujutsu Shenanigans (JJS): Combos e Técnicas',
                'guia-completo-jujutsu-shenanigans-combos',
                'Aprenda como encaixar sequências de golpes, esquivas perfeitas e como liberar a Expansão de Domínio.',
                "O Jujutsu Shenanigans exige agilidade reflexiva e conhecimento preciso de animações.\n\n### Princípios Básicos de Combate:\n1. **Cancelamento de Ataque (M1 Feint)**: Pressione bloqueio no segundo golpe para enganar o contra-ataque adversário.\n2. **Técnicas Secretas**: Aproveite o knockback perto de paredes para estender seu combo com o chute ascendente.\n3. **Expansão de Domínio**: Utilize apenas quando o oponente tiver gastado o escape (dash evasivo).\n\nPratique na sala de treino até os reflexos se tornarem memória muscular!",
                'assets/img/mascote.jpeg',
                '8 min',
                2890
            ],
            [
                3,
                $valkId,
                'Estratégias',
                'Dicas de farm e sobrevivência no 99 Nights in the Forest',
                'dicas-farm-99-nights-in-the-forest',
                'Rota ideal para coletar gemas sem ser encurralado pelas criaturas nas noites mais perigosas.',
                "Sobreviver às 99 noites requer gestão meticulosa de recursos e antecipação dos ciclos de horda.\n\n- Construa sua fogueira central sempre perto de recursos renováveis.\n- Colete gemas roxas apenas durante as primeiras 2 horas da noite.\n- Tenha pelo menos 2 rotas de fuga mapeadas antes do anoitecer.",
                'assets/img/banner-home.jpeg',
                '5 min',
                980
            ],
            [
                7,
                $valkId,
                'Guias',
                'Como evoluir rápido no Fortnite: Mira, Edição e Rotação',
                'como-evoluir-rapido-fortnite',
                'Passo a passo com rotinas de treino diário para subir de elo na ranqueada.',
                "Se você quer subir para o ranque Surreal, siga essa rotina diária de 25 minutos:\n\n1. **10 minutos de Mira**: Mapas de Skavok e tracking de alvos móveis.\n2. **10 minutos de Edição**: Treino de retakes e túneis de proteção.\n3. **5 minutos de Rotação**: Analisar mapas de tempestade e zonas elevadas.",
                'assets/img/logo.jpeg',
                '7 min',
                3210
            ]
        ];

        $insArtigo = $pdo->prepare("
            INSERT INTO artigos_wiki (jogo_id, usuario_id, categoria, titulo, slug, resumo, conteudo, imagem, tempo_leitura, visualizacoes)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        foreach ($artigos as $a) {
            $insArtigo->execute($a);
        }

        // Insere Fóruns iniciais
        $foruns = [
            [
                $shadowId,
                2,
                'Combos',
                'Qual o combo mais quebrado do JJS atualmente? Discussão de builds',
                'Salve tropa! Tenho testado a combinação do Feiticeiro dos Golpes com dash reverso na parede. Alguém aí conseguiu escapar disso com facilidade ou realmente tá indefensável? Vamos trocar ideias sobre counters!',
                '#JJS #Jujutsu #Combos #Meta',
                412,
                4.85,
                7,
                3
            ],
            [
                $valkId,
                1,
                'Dicas',
                'Melhores composições para subir no Modo Ranqueado do Brawl Stars',
                'Queria compartilhar minha experiência subindo pro Lendário no Brawl Stars. Composição indispensável: um sniper de longo alcance (Piper ou Angelo), um brawler de controle (Sandy ou Gene) e um tanque ágil (Buster). O que vocês usam?',
                '#BrawlStars #Ranqueada #Dicas',
                350,
                5.00,
                5,
                2
            ],
            [
                $shadowId,
                3,
                'Dúvidas',
                'Como passar da Noite 45 no 99 Nights in the Forest?',
                'Galera, na noite 45 os monstros da névoa estão derrubando minha base de madeira muito rápido. Vale a pena focar em reforçar com pedra ou é melhor fazer torre suspensa?',
                '#99Nights #Survival #Estrategia',
                210,
                4.50,
                4,
                1
            ]
        ];

        $insForum = $pdo->prepare("
            INSERT INTO foruns (usuario_id, jogo_id, categoria, titulo, conteudo, tags, visualizacoes, media_avaliacao, total_avaliacoes, total_comentarios)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        foreach ($foruns as $f) {
            $insForum->execute($f);
        }

        // Comentários iniciais
        $comentarios = [
            [1, $valkId, 'O combo do Feiticeiro é muito forte se você não tiver com o dash recarregado. O segredo contra ele é antecipar a esquiva pra diagonal ao invés de pular reto!'],
            [1, $shadowId, 'Exatamente @CyberValkyrie, testei essa esquiva diagonal e funcionou bem contra o segundo ataque!'],
            [2, $shadowId, 'Buster + Sandy tá completamente quebrado na Zona Estratégica. Se o time souber fechar os corredores ninguém passa!'],
            [3, $valkId, 'Definitivamente torre suspensa! A pedra eles quebram em 15 segundos se vierem em grupo. Faça a base com escada removível.']
        ];
        $insComentario = $pdo->prepare("
            INSERT INTO forum_comentarios (forum_id, usuario_id, conteudo)
            VALUES (?, ?, ?)
        ");
        foreach ($comentarios as $c) {
            $insComentario->execute($c);
        }

        // Avaliações de estrelas iniciais
        $avaliacoes = [
            [1, $shadowId, 5],
            [1, $valkId, 5],
            [2, $shadowId, 5],
            [3, $shadowId, 4]
        ];
        $insAvaliacao = $pdo->prepare("
            INSERT INTO forum_avaliacoes (forum_id, usuario_id, nota)
            VALUES (?, ?, ?)
        ");
        foreach ($avaliacoes as $av) {
            $insAvaliacao->execute($av);
        }

        // Log de Auditoria Inicial (Requisito CORE)
        $insLog = $pdo->prepare("
            INSERT INTO historico_logs (usuario_id, usuario_nome, acao, entidade, registro_id, detalhes, ip_origem)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $insLog->execute([
            $shadowId,
            'ShadowGamer',
            'SISTEMA_INICIALIZADO',
            'sistema',
            1,
            'Banco de dados estruturado e populado com sementes e regras da comunidade.',
            '127.0.0.1'
        ]);
        $insLog->execute([
            $shadowId,
            'ShadowGamer',
            'CADASTRO_USUARIO',
            'usuarios',
            $shadowId,
            'Conta de administrador ShadowGamer criada com senha criptografada em bcrypt.',
            '127.0.0.1'
        ]);
    }
}

# Tropa do Respawn

## Objetivo
Crie um site de wiki de jogos que contém dicas e posts, e nesse aplicativo os usuários vão poder criar fóruns comentar em outros fóruns, avaliar fóruns.

### Stack Tecnlógico
- Backend: PHP estruturado com sessões nativas
- Banco de dados: MySQL (PDO para segurança)
- Frontend: HTML5, PHP, CSS, Tailwind CSS

#### Regras de negócio (CORE)
Tratar senhas de usuários com hash bcript
O sistema deve ter uma página de históricos e manter sempre os logs de qualquer alteração feita por 
qualquer usuário, para auditorias futuras.
Insira suas regras de negocio.
-Narrativa: 1-O autor principal é o usuário e ele acessa o site e cria sua conta colocando seu e-mail e colocando uma senha.
-Narrativa: 2-É possível que ele crie fóruns em "criar fóruns". Uns dos requisitos pra ele poder criar um fórum é ele estar com sua conta logada e com uma idade mínima

##### Regras Globais
- Use sempre PDO para conexão e queries no MySQL para evitar SQL Injections
- Mantenha o código limpo e comente apenas logicas complexas.
- Separe os arquivos de forma lógica: um arquivo para conexão com a base (bd.php) e scripts de backend isolados e views em HTML5/PHP, nunca faça o sistema como um monolito, deixe sempre separados todas as regras para facilitar os futuros upgrades.
 - Estilize as telas em Tailwind de forma responsiva priorizando o MobileFrist.
 - Retorne sempre as mensagens de erros de forma claras na interface para o usuário (TOAST)
 - sempre trate as mensagens de caixa de mensagens nativas do navegador em um MODAL
 - As fontes vão ser inter, Rajdhani e JetBrains Mono, a cor vai ser branca e roxa.
 - As cores do site vão se caracterizar  ser com fundo preto e com detalhes em roxo.

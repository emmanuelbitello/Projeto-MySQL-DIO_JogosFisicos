# Projeto Exemplo: Script PHP com MySQL Local

Este repositório contém um exemplo simples de um script PHP (`index.php`) que se conecta a um banco de dados MySQL local para inserir dados em uma tabela (`Jogos`). O projeto inclui o esquema do banco de dados e o script PHP adaptado para um ambiente local no Ubuntu com MySQL Server instalado diretamente.

Inicialmente, este setup estava configurado para um ambiente remoto na AWS, e os arquivos foram adaptados para funcionar em um ambiente de desenvolvimento local.

## Estrutura do Projeto

.
├── README.md # Arquivo com informações sobre o repositório.  
├── Jogos.sql # Esquema da tabela `Jogos`.  
└── index.php # Script PHP para conexão e inserção.

## Pré-requisitos

Para rodar este projeto localmente, você precisa ter instalado:

* **Ubuntu 25.04 Plucky Puffin:** ![plucky-puffin-mascot](https://i.ibb.co/vCWBpvBL/UBUNTU-PLUCKY-PUFFIN-50px.webp "Mascote Plucky Puffin [Ubuntu 25.04]")  
  O setup foi baseado/testado nesta versão do Ubuntu.

* **MySQL Server:** ![mysql-logo.svg](https://i.ibb.co/FL4tRHfK/mysql-logo-50px.webp "MySQL TradeMark Logo")  
  Um servidor MySQL rodando no seu Ubuntu local. Versão utilizada: 8.4.4 (Ubuntu)

* **php8:** ![logo-php8](https://i.ibb.co/9340wFPF/logo-php8-50px.webp "php8 Logo")  
  PHP instalado com a extensão `mysqli` habilitada. Versão utilizada: 8.4.5 (cli).

* **phpMyAdmin:** ![logo-phpMyAdmin](https://i.ibb.co/TMZ23bP9/logo-phpmyadmin-50px.webp "phpMyAdmin Logo)  
  Ferramenta de código aberto, escrita em PHP, que permite gerenciar e manipular bancos de dados MySQL e MariaDB através de uma interface web. Versão utilizada: 4:5.2.2-really5.2.2+20250121+dfsg-1

* **Apache HTTP Server Project:** ![logo-Apache-HTTP-Server-Project](https://i.ibb.co/Nn3pp8H2/Apache-HTTP-server-logo-2019-present-50px.webp "Apache HTTP Server Project Logo")  
  Um servidor web (como Apache ou Nginx) configurado para processar arquivos PHP.

> *(Os comandos de instalação podem variar ligeiramente dependendo da sua versão exata do Ubuntu e das suas preferências de servidor web, mas os pacotes principais (`mysql-server`, `php`, `php-mysqli`, `apache2` ou `nginx`) são geralmente instalados via `sudo apt update && sudo apt install ...`)*  

## Instalação de Componentes

Para configurar o ambiente local necessário, siga os passos abaixo para instalar o servidor web, o banco de dados e a ferramenta de administração.

### Instalação do Apache2

O Apache2 é um dos servidores web mais populares.

1. Atualize o índice de pacotes do sistema:
   
   ```bash
   sudo apt update
   ```

2. Instale o pacote Apache2:
   
   ```bash
   sudo apt install apache2 -y
   ```

3. Verifique se o serviço Apache2 está rodando:
   
   ```bash
   sudo systemctl status apache2
   ```
   
   Você deverá ver "Active: active (running)".

### Instalação do MySQL Server

O MySQL Server é o sistema de gerenciamento de banco de dados.

1. Atualize o índice de pacotes do sistema (se não fez recentemente):
   
   ```bash
   sudo apt update
   ```

2. Instale o pacote mysql-server:
   
   ```bash
   sudo apt install mysql-server -y
   ```

3. Verifique a versão instalada:
   
   ```bash
   mysql --version
   ```

4. Verifique se o serviço MySQL está rodando:
   
   ```bash
   sudo systemctl status mysql
   ```
   
   Você deverá ver "Active: active (running)".

5. (Opcional, mas recomendado) Execute o script de segurança pós-instalação:
   
   ```bash
   sudo mysql_secure_installation
   ```
   
   Este script ajudará a remover configurações padrão inseguras.

### Instalação do phpMyAdmin

phpMyAdmin é uma interface web para gerenciar bancos de dados MySQL.

1. Atualize o índice de pacotes do sistema (se não fez recentemente):
   
   ```bash
   sudo apt update
   ```

2. Instale o pacote phpmyadmin, juntamente com extensões PHP comuns necessárias:
   
   ```bash
   sudo apt install phpmyadmin php php-mysql php-mbstring php-json php-xml php-curl php-zip php-common php-gd -y
   ```

3. Durante a instalação do phpMyAdmin, você será solicitado a:
   
   * Escolher o servidor web a ser reconfigurado automaticamente (selecione `apache2` pressionando `barra de espaço` e depois `Enter`).
   * Configurar o banco de dados para phpmyadmin com `dbconfig-common` (mantenha `Yes` e pressione `Enter`).
   * Criar e confirmar uma senha de aplicação MySQL para o phpMyAdmin. Escolha uma senha forte.

4. Habilite as extensões PHP `mbstring` e `mysqli` (geralmente já habilitadas com a instalação, mas bom verificar):
   
   ```bash
   sudo phpenmod mbstring mysqli
   ```

5. Reinicie o serviço do servidor web para que as configurações do phpMyAdmin sejam carregadas:
   
   ```bash
   sudo systemctl restart apache2
   # ou sudo systemctl restart nginx
   ```

6. Acesse o phpMyAdmin no seu navegador web, geralmente em `http://localhost/phpmyadmin`. Você precisará fazer login com um usuário válido do MySQL (como o `root` ou o usuário que você criou para o seu projeto.  

## Configuração

Siga os passos abaixo para configurar o banco de dados e o script PHP no seu ambiente local.

### 1. Configurar o Banco de Dados MySQL

Este projeto utiliza a tabela `Jogos`. Você precisa criar um banco de dados no seu MySQL local e executar o script SQL para criar esta tabela.

1. Conecte-se ao seu servidor MySQL local usando um cliente de linha de comando (como `mysql`) ou uma ferramenta GUI (como phpMyAdmin, MySQL Workbench). Utilize o usuário e senha que você configurou no seu MySQL local (o usuário padrão do sistema com direitos sudo que você mencionou provavelmente servirá como usuário MySQL também).
   
   ```bash
   mysql -u seu_usuario_mysql_local -p
   ```

2. Crie um novo banco de dados (se você ainda não tiver um que queira usar). Substitua `seu_nome_do_banco_local` pelo nome desejado.
   
   ```sql
   CREATE DATABASE seu_nome_do_banco_local;
   ```

3. Selecione o banco de dados que você acabou de criar ou que pretende usar.
   
   ```sql
   USE seu_nome_do_banco_local;
   ```

4. Execute o script `Jogos.sql` para criar a tabela `Jogos` e certifique-se de estar no diretório onde o arquivo `Jogos.sql` está localizado ou forneça o caminho completo.
   
   ```bash
   SOURCE Jogos.sql;
   ```
   
   Ou, se já estiver no cliente `mysql`:
   
   ```sql
   -- Dentro do cliente mysql
   SOURCE /caminho/completo/para/Jogos.sql;
   ```
   
   A tabela `Jogos` será criada no banco de dados selecionado.
   
   *(Nota: O arquivo `banco.sql` está incluído apenas para referência, pois ele criava a tabela 'dados' usada na versão original do `index.php`.)*

### 2. Configurar o Script PHP (`index.php`)

O arquivo `index.php` precisa ser configurado com os detalhes de conexão do seu banco de dados MySQL local.

1. Abra o arquivo `index.php`.

2. Localize a seção de configuração de conexão:
   
   ```php
   // Configurações para conectar ao seu MySQL Server LOCAL no Ubuntu
   $servername = "localhost"; // Endereço do servidor MySQL local. Use "127.0.0.1" se "localhost" der problema.
   $username = "SEU_USUARIO_MYSQL_LOCAL"; // <-- *** SUBSTITUA *** pelo seu usuário MySQL local
   $password = "SUA_SENHA_MYSQL_LOCAL"; // <-- *** SUBSTITUA *** pela sua senha do usuário MySQL local
   $database = "SEU_NOME_DO_BANCO_LOCAL"; // <-- *** SUBSTITUA *** pelo nome do banco de dados
   ```

3. Substitua os placeholders (`SEU_USUARIO_MYSQL_LOCAL`, `SUA_SENHA_MYSQL_LOCAL`, `SEU_NOME_DO_BANCO_LOCAL`) pelas suas credenciais reais e o nome do banco de dados que você usou na etapa anterior.
   
   * Para `$username`, use o seu usuário padrão do sistema, se for esse que você usa para o MySQL.
   * Para `$password`, use a senha **deste usuário no MySQL**.
   * Para `$database`, use o nome do banco de dados onde você criou a tabela `Jogos`.

4. Salve o arquivo `index.php`.

5. Coloque o arquivo `index.php` no diretório raiz do seu servidor web local (por exemplo, `/var/www/html/` para Apache padrão ou o `root` configurado no seu Nginx).

## Como Usar

Após configurar o banco de dados e o script PHP, você pode acessá-lo via navegador web.

1. Certifique-se de que seu servidor web e o PHP-FPM (se estiver usando Nginx/PHP-FPM) estão rodando.
2. Abra seu navegador e acesse a URL correspondente ao local onde você colocou o arquivo `index.php` (ex: `http://localhost/index.php` ou `http://localhost:PORTA/index.php`).

A cada vez que você acessar a página, o script PHP tentará se conectar ao banco de dados local, gerar dados aleatórios e inserir um novo registro nas colunas `Titulo`, `Plataforma`, `Midia`, `Rergiao`, `EstadoConservacao`, e `DataAquisicao` na tabela `Jogos`. Se a inserção for bem-sucedida, você verá uma mensagem de confirmação no navegador.

## Descrição dos Arquivos

* `Jogos.sql`: Contém a instrução `CREATE TABLE` para criar a tabela `Jogos` para catalogar uma coleção de jogos físicos ou biblioteca de jogos digitais. Inclui colunas como Título, Plataforma, Mídia, Região, etc.
* `index.php`: Script PHP que se conecta a um banco de dados MySQL e insere dados na tabela `Jogos`. Contém a lógica de conexão e a query SQL adaptada para o ambiente local.

## Resolução de Problemas

Esta seção lista problemas comuns que podem ocorrer durante a configuração e execução do projeto e como solucioná-los.

### Erro: `Uncaught mysqli_sql_exception: Plugin 'mysql_native_password' is not loaded`

* **O que significa:** Este erro ocorre quando o servidor MySQL tenta usar o plugin de autenticação `mysql_native_password` para a conexão (geralmente porque o usuário MySQL está configurado para usá-lo ou é o padrão desejado), mas o servidor não conseguiu carregar ou habilitar este plugin durante a inicialização.

* **Como verificar o status do plugin:** Conecte-se ao seu servidor MySQL via terminal (como root ou um usuário com privilégios suficientes, ex: `sudo mysql` ou `mysql -u root -p`). * Execute o seguinte comando SQL: 

```sql
    SHOW PLUGINS;  
```

* Provavelmente o plugin é o penúltimo da lista. Em seguida verifique se na coluna `STATUS` está como `DISABLED` ou `ENABLED`. Se estiver como `ENABLED` está tudo certo, se estiver como `DISABLED` vamos realizar os seguintes passos: 
1. Edite o arquivo de configuração para o MySQL no diretório de inclusão, que é o local recomendado para configurações personalizadas. O arquivo que precisa ser editado é o `my.cnf` localizado no diretório `/etc/mysql/mysql.conf.d/`. Use um editor de texto com privilégios de superusuário:
   
   ```bash
   sudo nano /etc/mysql/mysql.conf.d/my.cnf
   ```

2. Adicione a seguinte linha sob a seção `[mysqld]` no arquivo. Se a seção `[mysqld]` não existir, adicione o cabeçalho e a linha no final do arquivo.
   
   ```ini
   [mysqld]
   mysql_native_password=ON
   ```
   
   Certifique-se de que a linha não está comentada (não começa com `#`).

3. Salve o arquivo e feche o editor.

4. **Reinicie o serviço MySQL Server** para que a nova configuração seja aplicada.
   
   ```bash
   sudo systemctl restart mysql
   ```

5. Se o plugin não estiver instalado, faremos a instalação, enquanto conectados ao servidor MYSQL via terminal `sudo mysql`, com o comando: 

```
INSTALL PLUGIN mysql_native_password SONAME 'mysql_native_password.so';`
```

6. Verifique o status do serviço MySQL para garantir que ele reiniciou sem erros. Se houver erros, verifique os logs (`sudo less /var/log/mysql/error.log`) para encontrar a causa. 
* Após o MySQL reiniciar com sucesso, o plugin `mysql_native_password` deverá estar ativo, resolvendo este erro de conexão.

* Caso, após a instalação do phpmyadmin, o endereço `http://localhost/phpmyadmin` não abrir o gestor, criaremos o arquivo `phpmyadmin.conf` no diretório `conf-available`, do Apache, usando um editor de texto com `sudo`:
  
        ```bash
        sudo nano /etc/apache2/conf-available/phpmyadmin.conf
        ```
  
  * Copie e cole o seguinte conteúdo neste arquivo:
    
    ```apacheconf
    #
    # Apache configuration for phpMyAdmin
    #
    
    Alias /phpmyadmin /usr/share/phpmyadmin
    
    <Directory /usr/share/phpmyadmin>
        Options FollowSymLinks
        DirectoryIndex index.php
    
        <IfModule mod_authz_core.c>
            # Apache 2.4+
            Require all granted
        </IfModule>
        <IfModule !mod_authz_core.c>
            # Apache 2.2
            Order Allow,Deny
            Allow from All
        </IfModule>
    </Directory>
    
    # Allow for phpMyAdmin's setup directory
    <Directory /usr/share/phpmyadmin/setup>
        <IfModule mod_authz_core.c>
            # Apache 2.4+
            Require all granted
        </IfModule>
        <IfModule !mod_authz_core.c>
            # Apache 2.2
            Order Allow,Deny
            Allow from All
        </IfModule>
    </Directory>
    
    # Exclude the /setup directory from being indexed by AllowOverride indexes
    <Directory /usr/share/phpmyadmin/setup/>
        Options -Indexes
    </Directory>
    ```
  
  * Salve o arquivo (Ctrl+O, Enter em `nano`) e saia (Ctrl+X em `nano`).
  
  * Habilite este arquivo de configuração usando o comando `a2enconf`:
    
    ```bash
    sudo a2enconf phpmyadmin
    ```
  
  * **Reinicie o serviço Apache2** para que a nova configuração seja aplicada:
    
    ```bash
    sudo systemctl restart apache2
    ```
  
  * Verifique o status do Apache2 para garantir que reiniciou sem erros:
    
    ```bash
    sudo systemctl status apache2
    ```
7. Acesse o phpMyAdmin no seu navegador web, geralmente em `http://localhost/phpmyadmin`. Você precisará fazer login com um usuário válido do MySQL (como o `root` ou o usuário que você criou para o seu projeto).

**Nota:** O phpMyAdmin é instalado por padrão no diretório `/usr/share/phpmyadmin`. Não é necessário mover esta pasta. O acesso via web em `/phpmyadmin` é feito através de um apelido (alias) configurado no Apache.

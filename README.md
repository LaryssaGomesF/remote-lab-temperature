# Tutorial: Configurando e Executando o Projeto "lab-remoto-temperatura" Localmente

Este tutorial irá guiá-lo passo a passo na configuração do ambiente de desenvolvimento necessário para executar o projeto "lab-remoto-temperatura" em sua máquina local. O projeto utiliza PHP para o backend e MySQL para o banco de dados, HTML e CSS para o frontend e será executado através do XAMPP.

## Pastas

#### Pastas oficiais
remote-lab > lab-remoto-temperatura - É o código final que vamos copiar e utilizar para executar


simulação > Tem o código arduino e a montagem no picsimlab você pode executar simultâneamente caso queira testar a comunicação

#### Pastas com código teste
remote-lab-api, remote-lab-server,teste-camera, teste-camera-php e sketch-aug17a - São códigos que foram desenvolvidos ao longo do projeto para alguns teste

## Pré-requisitos

Antes de começar, certifique-se de ter o Visual Studio Code instalado, pois ele será útil para visualizar e editar os arquivos do projeto.

## Sumário

1.  Instalação do XAMPP
2.  Instalação e Configuração do FFmpeg
3.  Configuração do MySQL e Criação do Banco de Dados
4.  Configuração do Projeto PHP
5.  Execução e Teste do Projeto
6.  Solução de Problemas Comuns

## 1. Instalação do XAMPP

O XAMPP é um pacote de software gratuito e de código aberto que contém o Apache HTTP Server, MySQL database, e interpretadores para scripts PHP e Perl. Ele simplifica a configuração de um ambiente de servidor local.

### 1.1. Download do XAMPP




Acesse o site oficial do XAMPP: [https://www.apachefriends.org/pt_br/download.html](https://www.apachefriends.org/pt_br/download.html)

Baixe a versão do XAMPP compatível com o seu sistema operacional (Windows, macOS ou Linux). Recomenda-se baixar a versão mais recente com PHP 8.x.

### 1.2. Instalação do XAMPP

1.  **Execute o instalador:** Após o download, localize o arquivo `.exe` (Windows), `.dmg` (macOS) ou `.run` (Linux) e execute-o.
2.  **Siga as instruções:** O assistente de instalação irá guiá-lo através do processo. Você pode manter as configurações padrão na maioria dos casos.
    *   **Seleção de Componentes:** Certifique-se de que o Apache, MySQL e PHP estejam selecionados para instalação.
    *   **Diretório de Instalação:** O diretório padrão é `C:\xampp` no Windows. Você pode alterá-lo se desejar, mas lembre-se do local escolhido.
3.  **Conclua a instalação:** Ao final, o instalador perguntará se você deseja iniciar o Painel de Controle do XAMPP. Marque essa opção e clique em "Finish".

### 1.3. Iniciando o XAMPP

1.  **Abra o Painel de Controle do XAMPP:** Se ele não iniciar automaticamente, você pode encontrá-lo no menu Iniciar (Windows) ou na pasta de instalação do XAMPP.
2.  **Inicie os módulos:** No Painel de Controle do XAMPP, clique nos botões "Start" ao lado de "Apache" e "MySQL".
3.  **Verifique o status:** Se tudo estiver correto, os módulos Apache e MySQL ficarão verdes, indicando que estão em execução.

## 2. Instalação e Configuração do FFmpeg

O FFmpeg é uma ferramenta de linha de comando para manipular arquivos de áudio e vídeo. Seu projeto PHP pode utilizá-lo para processamento de mídia.

### 2.1. Download do FFmpeg

1.  Acesse o site oficial do FFmpeg para downloads: [https://ffmpeg.org/download.html](https://ffmpeg.org/download.html)
2.  Na seção "Get packages & executable files", procure por links para builds pré-compiladas para Windows. Uma fonte comum e recomendada é [https://www.gyan.dev/ffmpeg/builds/](https://www.gyan.dev/ffmpeg/builds/).
3.  Baixe a versão "full" ou "essentials" mais recente (geralmente um arquivo `.zip` ou `.7z`).

### 2.2. Extraindo e Configurando o FFmpeg

1.  Crie uma nova pasta em um local de fácil acesso, por exemplo, `C:\ffmpeg`.
2.  Extraia o conteúdo do arquivo `.zip` (ou `.7z`) baixado para dentro desta nova pasta. Você deve ter uma estrutura como `C:\ffmpeg\bin` contendo os executáveis do FFmpeg (`ffmpeg.exe`, `ffplay.exe`, `ffprobe.exe`).

### 2.3. Adicionando o FFmpeg ao PATH do Sistema

Para que o seu projeto PHP (ou qualquer outro programa) possa executar o FFmpeg a partir de qualquer diretório, você precisa adicionar o caminho da pasta `bin` do FFmpeg às variáveis de ambiente do sistema (PATH).

**No Windows:**

1.  Pressione `Win + R`, digite `sysdm.cpl` e pressione Enter para abrir as Propriedades do Sistema.
2.  Clique na aba "Avançado" e depois em "Variáveis de Ambiente...".
3.  Na seção "Variáveis do sistema", localize a variável `Path` e clique em "Editar...".
4.  Clique em "Novo" e adicione o caminho completo para a pasta `bin` do FFmpeg (ex: `C:\ffmpeg\bin`).
5.  Clique em "OK" em todas as janelas para fechar.

**Para verificar se o FFmpeg foi adicionado corretamente ao PATH:**

1.  Abra o Prompt de Comando (CMD) ou PowerShell.
2.  Digite `ffmpeg -version` e pressione Enter.
3.  Se a instalação e a configuração do PATH estiverem corretas, você verá as informações da versão do FFmpeg.

## 3. Configuração do MySQL e Criação do Banco de Dados

Agora que o MySQL está em execução, vamos configurar o usuário `root` e criar o banco de dados necessário para o projeto.

**Nota sobre o MySQL:** Embora você possa ter uma instalação separada do MySQL em sua máquina, para simplificar e garantir a compatibilidade com o ambiente do XAMPP, este tutorial assume que você usará o servidor MySQL que vem com o XAMPP. Isso evita conflitos de porta e garante que o PHP do XAMPP se conecte facilmente ao banco de dados.

### 3.1. Acessando o phpMyAdmin

1.  Abra seu navegador e digite `http://localhost/phpmyadmin` na barra de endereços. Isso o levará à interface web do phpMyAdmin, uma ferramenta para gerenciar bancos de dados MySQL.

### 3.2. Criando o Banco de Dados `remote-lab-db`

1.  No phpMyAdmin, clique na aba "Bancos de dados" (Databases).
2.  No campo "Criar banco de dados" (Create database), digite `remote-lab-db`.
3.  Clique em "Criar" (Create).

### 3.3. Criando as Tabelas

Com o banco de dados criado, vamos criar as tabelas `control` e `plant_data`.

1.  No phpMyAdmin, selecione o banco de dados `remote-lab-db` no painel esquerdo.
2.  Clique na aba "SQL".
3.  Copie e cole o seguinte código SQL na área de texto:

    ```sql
    CREATE TABLE IF NOT EXISTS control(
          id INT NOT NULL PRIMARY KEY,
          ki DOUBLE NOT NULL,
          kd DOUBLE NOT NULL,
          kp DOUBLE NOT NULL,
          s DOUBLE NOT NULL,
          m DOUBLE DEFAULT 0.0
         );
    CREATE TABLE IF NOT EXISTS plant_data(
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            t INT,
            tp DOUBLE,
            e DOUBLE,
            s DOUBLE,
            a DOUBLE
            );
    ```

4.  Clique em "Executar" (Go).

Você deverá ver uma mensagem de sucesso indicando que as tabelas foram criadas.




## 4. Configuração do Projeto PHP

Agora é hora de configurar o seu projeto PHP para que ele possa ser acessado pelo servidor Apache do XAMPP.

### 4.1. Copiando os Arquivos do Projeto para o `htdocs`

1.  Localize a pasta do seu projeto "lab-remoto-temperatura" em seu computador.
2.  Copie toda a pasta do projeto.
3.  Navegue até o diretório de instalação do XAMPP. Por padrão, é `C:\xampp` no Windows.
4.  Dentro do diretório do XAMPP, localize a pasta `htdocs` (ex: `C:\xampp\htdocs`).
5.  Cole a pasta do seu projeto "lab-remoto-temperatura" dentro da pasta `htdocs`.

    Após este passo, o caminho completo para o seu projeto deve ser algo como `C:\xampp\htdocs\lab-remoto-temperatura`.

### 4.2. Configurando as Credenciais do Banco de Dados (`db.php`)

Você mencionou que o arquivo `db.php` contém as credenciais do banco de dados. Precisamos garantir que elas estejam corretas para que seu código possa se conectar ao MySQL.

1.  Abra o Visual Studio Code.
2.  No Visual Studio Code, abra a pasta do seu projeto que você acabou de copiar para o `htdocs` (ex: `C:\xampp\htdocs\lab-remoto-temperatura`).
3.  Localize e abra o arquivo `db.php` dentro do seu projeto.
4.  Verifique se o conteúdo do arquivo `db.php` é o seguinte:

    ```php
    <?php
    $host = "localhost";
    $user = "root";
    $pass = ""; // Deixe em branco se o usuário 'root' não tiver senha no MySQL do XAMPP
    $dbname = "remote-lab-db";

    $conn = new mysqli($host, $user, $pass, $dbname);
    if ($conn->connect_error) {
        http_response_code(500);
        die(json_encode(["error" => "Erro ao conectar com o banco de dados: " . $conn->connect_error]));
    }
    ?>
    ```

    **Observação:** Se o seu usuário `root` do MySQL tiver uma senha, certifique-se de atualizar a linha `$pass = "";` com a senha correta.

## 5. Execução e Teste do Projeto

Com os arquivos no lugar e as credenciais configuradas, é hora de testar se tudo está funcionando corretamente.

### 5.1. Iniciando o Apache e MySQL no XAMPP

Certifique-se de que os módulos Apache e MySQL estejam em execução no Painel de Controle do XAMPP. Se não estiverem, clique em "Start" ao lado de cada um.

### 5.2. Acessando o Projeto pelo Navegador

1.  Abra seu navegador web (Chrome, Firefox, Edge, etc.).
2.  Na barra de endereços, digite a seguinte URL e pressione Enter:

    `http://localhost/lab-remoto-temperatura/`

3.  Se tudo estiver configurado corretamente, você deverá ver a página inicial do seu projeto "lab-remoto-temperatura" sendo exibida no navegador.





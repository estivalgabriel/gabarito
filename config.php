<?php
//adiciona as configurações gerais da aplicação
//não esquecer de adicionar o serial quer no arquivo: require/system/serial.txt
//não esquecer de atribuir o copyright no arquivo: require/system/copyright.txt

//nome do projeto
define( 'NOME_PROJETO', 'Nome do Projeto');
define( 'ANO_PROJETO', 'Ano do Projeto');
define( 'PUBLISHER',"Nome do Cliente");
define( 'AUTOR',"Nome do Cliente");
define( 'VERSAO_SISTEMA',"1.0");


//caso esteja em modo de desenvolvedor
define( 'DEBUG', false);

//caminho para a raiz
define( 'ABSPATH', dirname( __FILE__ ) );

//caminho para a pasta de uploads
define( 'UP_ABSPATH', ABSPATH . '/public/upload' );
define( 'VIEW_ABSPATH', ABSPATH . '/app/views' );


// para conhecimento existem as seguintes constantes que não estão relacionadas nesse arquivo
// const    URL - chama a url do site
// const    PATH_IMG - chama o diretorio padrao das images
// const    PATH_JS - chama o diretorio padrao dos javascripts
// const    PATH_CSS - chama o diretorio padrao dos css

//configuração das urls do projeto
define( 'PROTOCOLO_LOCALHOST', 'http://'); //ou https
define( 'DIRETORIO_LOCALHOST', '/gabarito');

define( 'PROTOCOLO_TESTSERVER', 'http://'); //ou https
define( 'DIRETORIO_TESTSERVER', '/cliente/');

define( 'PROTOCOLO_REAL', 'http://'); //ou https
define( 'URL_REAL', '');
define( 'DIRETORIO_REAL', '');



//configuração para conexao com o banco de dados /*ainda nao implementado*/
if(DEBUG == true){
    define( 'HOSTNAME', 'localhost' );
    define( 'DB_NAME', 'novosip2' );
    define( 'DB_USER', 'root' );
    define( 'DB_PASSWORD', '' );
    define( 'DB_CHARSET', 'utf8');
}else{
    define( 'HOSTNAME', '' );
    define( 'DB_NAME', '' );
    define( 'DB_USER', '' );
    define( 'DB_PASSWORD', '' );
    define( 'DB_CHARSET', 'utf8');
}



//informações para servidor de e-mail
define( 'IS_SMTP', true);
define( 'SMTP', 'smtp.gabrielestival.com.br' );
define( 'EMAIL', 'sender@gabrielestival.com.br' );
define( 'PWD_EMAIL', 'Senha' );
define( 'PORT_SMTP', '587');



//defino as imagens que utilizaremos
define('FAVICON', '/public/assets/images/img/favicon.png');
define('MARCA_DAGUA', '/public/assets/images/padrao/selo.png');

//outras configuraçoes
date_default_timezone_set('America/Sao_Paulo');

//código do tagmanager
define( 'IS_TAG_MANAGER', true);
define( 'TAG_MANAGER',"GTM_XXXXXXX");


//carrego a aplicação do site ou sistema
require_once ABSPATH . '/loader.php';
?>

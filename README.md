# CRUD de Livros PHP POO
Um projeto CRUD feito em <strong>PHP 8</strong> e Orientado a Objetos, utilizando MySQL
![Screenshot 2021-08-01 141544](https://user-images.githubusercontent.com/62625567/128727270-48038261-676c-41c9-b807-e9d6b72bdf48.png)

## Banco de Dados
Veja o modelo Entidade e Relacionamento dos dados que serão armazenados no sistema
![DER_CRUD-Livros](https://user-images.githubusercontent.com/62625567/126000284-17544e15-856f-406a-8a7f-e912bac328bc.png)
<br>

## Modelo Lógico
![ModeloLogico_CRUD-Livros](https://user-images.githubusercontent.com/62625567/127215102-d16c14f2-cd17-4525-aeb6-9296f812e6aa.png)
<br>

## Diagrama de Classes
![Screenshot 2021-08-01 173233](https://user-images.githubusercontent.com/62625567/127784632-0d7f9f50-eea6-4c57-b227-4e2c19c74515.png)

## Configuração do Banco de Dados
Em `System/Classes/Database.class.php` estão as configurações do Banco de Dados MySQL, sendo necessário alterar apenas as variáveis "HOST", "USER" e "PASS" para seu determinado ambiente.
Além disso, deve-se criar o banco de dados pelo script SQL localizado em `Database/Query_CRUD_Livros.sql`, e devemos povoá-lo com os INSERTs localizados em `Database/Inserts_Crud_Livros.sql`

## Autoload
O autoload de classes é feito pelo arquivo localizado em `System/includes/autoloader.php`
<br>

## Agradecimentos especiais
Código adaptado de um projeto com <a href="https://github.com/william-costa/wdev-crud-php-pdo-mysql">cadastro de vagas</a>. Vídeos <a>https://www.youtube.com/watch?v=uG64BgrlX7o</a> e <a>https://www.youtube.com/watch?v=BT5QY3VYSsk</a>
# Simple C2C E-commerce

<div style = "display:flex;">
    <img src = "https://img.shields.io/badge/MIT%20-%20black?style=flat-square&label=License">
    <img src = "https://img.shields.io/badge/PHP%20-%20black?style=flat-square&logo=php">
    <img src = "https://img.shields.io/badge/JavaScript%20-%20black?style=flat-square&logo=Javascript">
</div>

## 📒 Descrição

Este projeto é uma plataforma simples de e-commerce C2C onde usuários podem criar conta, publicar anúncios e comentar em postagens.
O objetivo é estudar PHP, lógica de back-end, autenticação e organização de projeto MVC simples.

## ✨ Funcionalidades

- Login/cadastro
- Comentários na postagem

## ✍️ Pré-requisitos

- PHP 8+
- Apache (ou XAMPP/WAMP)
- MySql


## ⚙️ Como rodar

``` bash
git clone https://github.com/Lurafig/simple-c2c-ecommerce-php.git
```

- Importe o arquivo sql/estrutura_banco.sql no seu MySQL
- Coloque o projeto dentro do diretório do Apache (htdocs)
- Acesse: http://localhost/simple-c2c-ecommerce-php

## 🔧 Configuração do EmailJS

Este projeto utiliza o EmailJS para envio de e-mails diretamente pelo backend PHP.

Para que o sistema funcione corretamente, você deve:

- Criar uma conta no [EmailJS](https://www.emailjs.com/)

- Criar um Service ID, Template ID e obter sua Public Key / Private Key

## 🔧 Credenciais nos arquivos

- config/db_connect.php (para conexão com o banco de dados):
``` php
$host = 'localhost';
$db   = 'ecommerce';
$user = 'root';
$pass = '<YOUR_PASSWORD>';
```

- ejsConfig.php (para o uso do EmailJS):
``` php
$serviceID = '<SERVICE_ID>';
$templateID = '<TEMPLATE_ID>';
$privateKey = '<PRIVATE_KEY>';
$publicKey = '<PUBLIC_KEY>';
```

## 📂 Estrutura do Projeto

```
/
├── assets/
├── config/
├── controllers/
│   ├── delete_ad.php
│   ├── get_spec_ad.php
│   ├── get_user_infos.php
│   ├── login_process.php
│   ├── logout_process.php
│   ├── post_comment.php
│   ├── register_process.php
│   ├── search_and_post.php
│   ├── sendEmail.php
│   ├── upload.php
├── files/
├── includes/
│   ├── auth_check.php
│   ├── header.php
│   ├── login_ctn.php
├── sql/
│   ├── estrutura_banco.sql
├── src/
│   ├── scripts/
│   │   ├── header.js
│   │   ├── LoginInt.js
│   │   ├── OrganizeHome.js
│   │   ├── OrganizePostAd.js
│   │   ├── productPage.js
│   │   ├── profilePage.js
│   │   ├── script.js
│
│   ├── styles/
│       ├── Consts.css
│       ├── HomeStyle.css
│       ├── PostAd.css
│       ├── ProductStyle.css
│       ├── ProfilePage.css
├── .htaccess
├── home.php
├── post.php
├── product.php
├── profile.php
```

## 📄 Licença

Este projeto está licenciado sob a licença MIT.


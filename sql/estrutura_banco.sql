create database 
if not exists ecommerce
character set utf8mb4 
collate utf8mb4_unicode_ci;

use ecommerce;

CREATE TABLE IF NOT EXISTS users (
	id INT auto_increment primary key,
	username varchar(100) NOT NULL,
    senha varchar(255) NOT NULL,
    email varchar(150) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS ads (
	-- id do anúncio
	id INT auto_increment primary key,
    -- id do usuário dono do anúncio
    user_id INT NOT NULL,
    -- titulo do anúncio
    title varchar(255) NOT NULL,
    -- data de inserção do anúncio 
    insertion_date datetime not null default now(),
    -- valor do produto
    product_value DECIMAL(10,2) NOT NULL,
    foreign key (user_id) references users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS images(
	-- id da imagem
	id INT auto_increment primary key,
    -- id chave estrangeira
    produto_id INT NOT NULL,
    -- caminho da imagem
    image_path varchar(255) NOT NULL,
    foreign key (produto_id) references ads(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS comments(
	id INT auto_increment primary key,
    user_id INT NOT NULL,
    ad_id INT NOT NULL,
    username varchar(100) NOT NULL,
	content TEXT NOT NULL,
    created_at datetime NOT NULL DEFAULT now(),
    foreign key (user_id) references users(id) ON DELETE CASCADE,
    foreign key (ad_id) references ads(id) ON DELETE CASCADE
);
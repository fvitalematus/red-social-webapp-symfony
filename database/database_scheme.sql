CREATE DATABASE IF NOT EXISTS curso_social_network;
USE curso_social_network;

CREATE TABLE users(
id       int(255) auto_increment not null,
role     varchar(20),
email    varchar(255),
name     varchar(255),
surname  varchar(255),
password varchar(255),
nick     varchar(50),
bio      varchar(255),
active   varchar(2),
image    varchar(255),
CONSTRAINT users_uniques_fields UNIQUE (email, nick),
CONSTRAINT pk_users PRIMARY KEY(id)
)ENGINE = InnoDb;


CREATE TABLE publications(
id       int(255) auto_increment not null,
user_id  int(255),
text     mediumtext,
document varchar(100),
image   varchar(255),
status   varchar(30),
created_at datetime,
CONSTRAINT pk_publications PRIMARY KEY(id),
CONSTRAINT fk_publications_users FOREIGN KEY(user_id) references users(id)
)ENGINE = InnoDb;


CREATE TABLE following(
id       int(255) auto_increment not null,
user     int(255),
followed int(255),
CONSTRAINT pk_following PRIMARY KEY(id),
CONSTRAINT fk_following_users FOREIGN KEY(user) references users(id),
CONSTRAINT fk_followed FOREIGN KEY(followed) references users(id)
)ENGINE = InnoDb;


CREATE TABLE private_messages(
id       int(255) auto_increment not null,
message  longtext,
emitter  int(255),
receiver int(255),
file     varchar(255),
image    varchar(255),
readed   varchar(3),
created_at datetime,
CONSTRAINT pk_private_messages PRIMARY KEY(id),
CONSTRAINT fk_emmiter_privates FOREIGN KEY(emitter) references users(id),
CONSTRAINT fk_receiver_privates FOREIGN KEY(receiver) references users(id)
)ENGINE = InnoDb;


CREATE TABLE likes(
id       int(255) auto_increment not null,
user        int(255),
publication int(255),
CONSTRAINT pk_likes PRIMARY KEY(id),
CONSTRAINT fk_likes_users FOREIGN KEY(user) references users(id),
CONSTRAINT fk_likes_publication FOREIGN KEY(publication) references publications(id)
)ENGINE = InnoDb;


CREATE TABLE notifications(
id        int(255) auto_increment not null,
user_id   int(255),
type      varchar(255),
type_id   int(255),
readed   varchar(3),
created_at datetime,
extra   varchar(100),
CONSTRAINT pk_notifications PRIMARY KEY(id),
CONSTRAINT fk_notifications_users FOREIGN KEY(user_id) references users(id)
)ENGINE = InnoDb;
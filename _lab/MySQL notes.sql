-- Creacion de tablas

CREATE TABLE images (
    id INT NOT NULL AUTO_INCREMENT,
    location VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE playlists (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(128) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE playlists_images (
    playlist_id INT NOT NULL,
    image_id INT NOT NULL,
    position INT NOT NULL,
    PRIMARY KEY (playlist_id, position),
    FOREIGN KEY (playlist_id) REFERENCES  playlists(id),
    FOREIGN KEY (image_id) REFERENCES  images(id)
);

CREATE TABLE screens (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(128) NOT NULL,
    playlist_id INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (playlist_id) REFERENCES playlists(id)
);

-- nueva tabla para pantallas con UDID

CREATE TABLE screens (
    id VARCHAR(128) NOT NULL,
    name VARCHAR(128) NOT NULL,
    playlist_id INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (playlist_id) REFERENCES playlists(id)
);

CREATE TABLE screens_setup (
    screen_id VARCHAR(128) NOT NULL,
    pin VARCHAR(128) NOT NULL
);

-- tabla para nested_sets

CREATE TABLE nested_sets (
    id INT NOT NULL AUTO_INCREMENT,
    left_value INT NOT NULL,
    right_value INT NOT NULL,
    parent_id INT NOT NULL,
    name VARCHAR(128) NOT NULL,
    type ENUM('group', 'screen') NOT NULL,
    date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
);

-- NUEVAS ------------------------------------------------------

CREATE TABLE directory (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(128) NOT NULL,
    type ENUM('group', 'screen') NOT NULL,
    parent_id INT NOT NULL,
    date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    left_value INT NOT NULL,
    right_value INT NOT NULL,    
    PRIMARY KEY (id),
    UNIQUE (name, type, parent_id)
);

CREATE TABLE images (
    id INT NOT NULL AUTO_INCREMENT,
    location VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE playlists (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(128) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE playlists_images (
    playlist_id INT NOT NULL,
    image_id INT NOT NULL,
    position INT NOT NULL,
    PRIMARY KEY (playlist_id, position),
    FOREIGN KEY (playlist_id) REFERENCES  playlists(id) ON DELETE CASCADE,
    FOREIGN KEY (image_id) REFERENCES  images(id)
);

CREATE TABLE screens_data (
    id INT NOT NULL,
    udid VARCHAR(128) NOT NULL UNIQUE,
    playlist_id INT NOT NULL,
    -- Campos especificos al tipo "screen" (si los hay)
    -- ...
    FOREIGN KEY (id) REFERENCES directory(id) ON DELETE CASCADE,
    FOREIGN KEY (playlist_id) REFERENCES playlists(id)
);

CREATE TABLE groups_data (
    id INT NOT NULL,
    -- Campos especificos al tipo "grupo" (si los hay)
    -- ...
    FOREIGN KEY (id) REFERENCES directory(id) ON DELETE CASCADE
);

CREATE TABLE screens_setup (
    udid VARCHAR(128) NOT NULL,
    pin VARCHAR(128) NOT NULL
);

-- VISTAS

CREATE VIEW groups AS (
    SELECT id, name, type, parent_id, date_created
    FROM directory JOIN groups_data USING (id)
    WHERE type = 'group'
);

CREATE VIEW screens AS (
    SELECT id, name, type, parent_id, date_created, udid, playlist_id
    FROM directory JOIN screens_data USING (id)
    WHERE type = 'screen'
);

-- Items por defecto

INSERT INTO images (location) VALUES ('public/images/image1.jpg');
INSERT INTO images (location) VALUES ('public/images/image2.jpg');
INSERT INTO images (location) VALUES ('public/images/image3.jpg');
INSERT INTO images (location) VALUES ('public/images/image4.jpg');
INSERT INTO images (location) VALUES ('public/images/image5.jpg');
INSERT INTO images (location) VALUES ('public/images/image6.jpg');

INSERT INTO playlists (name) VALUES ('cities');
INSERT INTO playlists (name) VALUES ('yosemite');

INSERT INTO playlists_images VALUES (1, 1, 0);
INSERT INTO playlists_images VALUES (1, 2, 1);
INSERT INTO playlists_images VALUES (1, 3, 2);
INSERT INTO playlists_images VALUES (2, 4, 0);
INSERT INTO playlists_images VALUES (2, 5, 1);
INSERT INTO playlists_images VALUES (2, 6, 2);

INSERT INTO screens (name, playlist_id) VALUES ('home_1', 1);
INSERT INTO screens (name, playlist_id) VALUES ('home_2', 2);

INSERT INTO screens (id, name, playlist_id) VALUES ('asdf', 'home_1', 1);

-- Modificacion de items

UPDATE images SET location='public/images/image2.jpg' WHERE id = 2;


-- Queries

-- > playlist para pantalla con nombre X
SELECT playlist_id
FROM screens
WHERE name = X

--> imagenes en orden ascendente para la playlist con ID X
SELECT images.location
FROM    playlists_images JOIN images
WHERE   playlists_images.image_id = images.id AND playlists_images.playlist_id = X
ORDER BY position ASC
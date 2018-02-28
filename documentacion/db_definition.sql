-- Tablas ------------------------------------------------------

CREATE TABLE directory (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(128) NOT NULL,
    type ENUM('group', 'screen') NOT NULL,
    parent_id INT NOT NULL,
    date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    left_value INT NOT NULL,
    right_value INT NOT NULL,    
    PRIMARY KEY (id)
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

-- Vistas

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
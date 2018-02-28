-- Items para el demo

INSERT INTO images (location) VALUES ('public/images/image1.jpg');
INSERT INTO images (location) VALUES ('public/images/image2.jpg');
INSERT INTO images (location) VALUES ('public/images/image3.jpg');
INSERT INTO images (location) VALUES ('public/images/image4.jpg');
INSERT INTO images (location) VALUES ('public/images/image5.jpg');
INSERT INTO images (location) VALUES ('public/images/image6.jpg');
INSERT INTO images (location) VALUES ('public/images/image7.jpg');
INSERT INTO images (location) VALUES ('public/images/image8.jpg');
INSERT INTO images (location) VALUES ('public/images/image9.jpg');

INSERT INTO playlists (name) VALUES ('ciudades');
INSERT INTO playlists (name) VALUES ('yosemite');
INSERT INTO playlists (name) VALUES ('verduleria');

INSERT INTO playlists_images VALUES (1, 1, 0);
INSERT INTO playlists_images VALUES (1, 2, 1);
INSERT INTO playlists_images VALUES (1, 3, 2);
INSERT INTO playlists_images VALUES (2, 4, 0);
INSERT INTO playlists_images VALUES (2, 5, 1);
INSERT INTO playlists_images VALUES (2, 6, 2);
INSERT INTO playlists_images VALUES (3, 7, 0);
INSERT INTO playlists_images VALUES (3, 8, 1);
INSERT INTO playlists_images VALUES (3, 9, 2);

INSERT INTO directory (name, type, parent_id, left_value, right_value) VALUES ('Grupo 1', 'group', 0, 0, 9);
INSERT INTO directory (name, type, parent_id, left_value, right_value) VALUES ('Grupo 2', 'group', 1, 1, 6);
INSERT INTO groups_data (id) VALUES (1);
INSERT INTO groups_data (id) VALUES (2);

INSERT INTO directory (name, type, parent_id, left_value, right_value) VALUES ('Pantalla 1', 'screen', 2, 2, 3);
INSERT INTO directory (name, type, parent_id, left_value, right_value) VALUES ('Pantalla 2', 'screen', 2, 4, 5);
INSERT INTO directory (name, type, parent_id, left_value, right_value) VALUES ('Pantalla 3', 'screen', 1, 7, 8);
INSERT INTO screens_data (id, udid, playlist_id) VALUES (3, 123, 1);
INSERT INTO screens_data (id, udid, playlist_id) VALUES (4, 456, 2);
INSERT INTO screens_data (id, udid, playlist_id) VALUES (5, 789, 3);
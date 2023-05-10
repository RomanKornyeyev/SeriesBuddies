INSERT INTO generos (nombre) VALUES ('Terror');
INSERT INTO generos (nombre) VALUES ('Zombie');
INSERT INTO generos (nombre) VALUES ('Gore');
INSERT INTO generos (nombre) VALUES ('Comedia');
INSERT INTO generos (nombre) VALUES ('Telenovela');
INSERT INTO generos (nombre) VALUES ('Thriller');
INSERT INTO generos (nombre) VALUES ('Psicológico');
INSERT INTO generos (nombre) VALUES ('Policiaco');
INSERT INTO generos (nombre) VALUES ('Legal');
INSERT INTO generos (nombre) VALUES ('Político');
INSERT INTO generos (nombre) VALUES ('Romance');
INSERT INTO generos (nombre) VALUES ('Romcom');
INSERT INTO generos (nombre) VALUES ('Drama');
INSERT INTO generos (nombre) VALUES ('Médico');
INSERT INTO generos (nombre) VALUES ('Aventuras');
INSERT INTO generos (nombre) VALUES ('Ciencia Ficción');
INSERT INTO generos (nombre) VALUES ('Reallity');
INSERT INTO generos (nombre) VALUES ('Cyberpunk');
INSERT INTO generos (nombre) VALUES ('Melodrama');
INSERT INTO generos (nombre) VALUES ('Musical');
INSERT INTO generos (nombre) VALUES ('Fantasía');
INSERT INTO generos (nombre) VALUES ('Documental');
INSERT INTO generos (nombre) VALUES ('Acción');
INSERT INTO generos (nombre) VALUES ('Sobrenatural');
INSERT INTO generos (nombre) VALUES ('Bélico');
INSERT INTO generos (nombre) VALUES ('Histórico');
INSERT INTO generos (nombre) VALUES ('Anime');


-- select titulo, nombre 
-- from generos_series gs 
-- inner join series s on gs.id_serie=s.id 
-- inner join generos g on gs.id_genero=g.id 
-- group by titulo, nombre;
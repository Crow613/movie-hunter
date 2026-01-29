INSERT INTO producers (name, image, bio) VALUES
('Steven Spielberg', 'https://example.com/images/spielberg.jpg', 'Legendary American film director and producer.'),
('Christopher Nolan', 'https://example.com/images/nolan.jpg', 'British-American film director, screenwriter, and producer.'),
('James Cameron', 'https://example.com/images/cameron.jpg', 'Canadian filmmaker, known for epic blockbusters.'),
('Ridley Scott', 'https://example.com/images/scott.jpg', 'English film director and producer.'),
('Kathleen Kennedy', 'https://example.com/images/kennedy.jpg', 'American film producer and president of Lucasfilm.');

INSERT INTO directors (name, image, bio) VALUES
('Quentin Tarantino', 'https://example.com/images/tarantino.jpg', 'Known for non-linear storytelling and sharp dialogue.'),
('Martin Scorsese', 'https://example.com/images/scorsese.jpg', 'Master of crime and biographical films.'),
('Denis Villeneuve', 'https://example.com/images/villeneuve.jpg', 'Canadian director famous for sci-fi dramas.'),
('Guy Ritchie', 'https://example.com/images/ritchie.jpg', 'British director with a unique crime-comedy style.'),
('David Fincher', 'https://example.com/images/fincher.jpg', 'American director known for dark psychological films.');

INSERT INTO actors (name, image, bio) VALUES
('Leonardo DiCaprio', 'https://example.com/images/dicaprio.jpg', 'Academy Award-winning American actor.'),
('Brad Pitt', 'https://example.com/images/bradpitt.jpg', 'American actor and film producer.'),
('Morgan Freeman', 'https://example.com/images/freeman.jpg', 'American actor with iconic narration voice.'),
('Christian Bale', 'https://example.com/images/bale.jpg', 'British actor known for dramatic transformations.'),
('Tom Hardy', 'https://example.com/images/hardy.jpg', 'English actor and producer.'),
('Robert De Niro', 'https://example.com/images/deniro.jpg', 'Legendary American actor and producer.'),
('Al Pacino', 'https://example.com/images/pacino.jpg', 'American actor known for intense performances.'),
('Matthew McConaughey', 'https://example.com/images/mcconaughey.jpg', 'Academy Award-winning American actor.'),
('Scarlett Johansson', 'https://example.com/images/johansson.jpg', 'American actress and singer.'),
('Joaquin Phoenix', 'https://example.com/images/phoenix.jpg', 'American actor known for deep character roles.');

INSERT INTO movies (name, image, movie_link, rating, producer_id, director_id) VALUES
('Inception', 'https://example.com/images/inception.jpg', 'https://example.com/movies/inception.mp4', 8.8, 2, 2),
('Pulp Fiction', 'https://example.com/images/pulpfiction.jpg', 'https://example.com/movies/pulpfiction.mp4', 8.9, 1, 1),
('The Dark Knight', 'https://example.com/images/darkknight.jpg', 'https://example.com/movies/darkknight.mp4', 9.0, 2, 5),
('Fight Club', 'https://example.com/images/fightclub.jpg', 'https://example.com/movies/fightclub.mp4', 8.8, 4, 5),
('Interstellar', 'https://example.com/images/interstellar.jpg', 'https://example.com/movies/interstellar.mp4', 8.6, 2, 3),
('The Wolf of Wall Street', 'https://example.com/images/wolf.jpg', 'https://example.com/movies/wolf.mp4', 8.2, 1, 2),
('Gladiator', 'https://example.com/images/gladiator.jpg', 'https://example.com/movies/gladiator.mp4', 8.5, 4, 2),
('Dune', 'https://example.com/images/dune.jpg', 'https://example.com/movies/dune.mp4', 8.1, 5, 3),
('Se7en', 'https://example.com/images/se7en.jpg', 'https://example.com/movies/se7en.mp4', 8.6, 4, 5),
('Joker', 'https://example.com/images/joker.jpg', 'https://example.com/movies/joker.mp4', 8.4, 1, 5);

INSERT INTO movie_actor (movie_id, actor_id) VALUES
(1, 1), (1, 5),
(2, 2),
(3, 4),
(4, 2), (4, 5),
(5, 8),
(6, 1),
(7, 6),
(8, 9),
(9, 2),
(10, 10);

INSERT INTO movie_producer (movie_id, producer_id) VALUES
(1, 2),
(2, 1),
(3, 2),
(4, 4),
(5, 2),
(6, 1),
(7, 4),
(8, 5),
(9, 4),
(10, 1);

INSERT INTO movie_director (movie_id, director_id) VALUES
(1, 2),
(2, 1),
(3, 5),
(4, 5),
(5, 3),
(6, 2),
(7, 2),
(8, 3),
(9, 5),
(10, 5);

CREATE DATABASE db;

USE db;

CREATE TABLE `jobs` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `priority` int NOT NULL,
  `cmd` varchar(255) NOT NULL,
  `processor_id` int NULL,
  `created_at` timestamp NOT NULL,
  `finished_at` timestamp NULL
);

CREATE INDEX priority_idx ON `jobs` (`priority`);

CREATE TABLE `users` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255) NOT NULL
);

CREATE TABLE `user_jobs` (
  `user_id` int,
  `job_id` int,
  PRIMARY KEY (`user_id`, `job_id`)
);

ALTER TABLE `user_jobs` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `user_jobs` ADD FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`);

INSERT INTO `jobs` (`priority`,`cmd`) VALUES (1,'alias');
INSERT INTO `jobs` (`priority`,`cmd`) VALUES (1,'cat');
INSERT INTO `jobs` (`priority`,`cmd`) VALUES (3,'ls');
INSERT INTO `jobs` (`priority`,`cmd`) VALUES (3,'cd');
INSERT INTO `jobs` (`priority`,`cmd`) VALUES (5,'chmod');
INSERT INTO `jobs` (`priority`,`cmd`) VALUES (5,'chown');
INSERT INTO `jobs` (`priority`,`cmd`) VALUES (7,'grep');
INSERT INTO `jobs` (`priority`,`cmd`) VALUES (7,'curl');

INSERT INTO `users` (`name`) VALUES ('Frontline Commando');
INSERT INTO `users` (`name`) VALUES ('Big Time Gangsta');
INSERT INTO `users` (`name`) VALUES ('Blackjack Hustler');
INSERT INTO `users` (`name`) VALUES ('Brain Genius');
INSERT INTO `users` (`name`) VALUES ('Contract Killer');

INSERT INTO `user_jobs` VALUES (1,7);
INSERT INTO `user_jobs` VALUES (2,6);
INSERT INTO `user_jobs` VALUES (3,5);
INSERT INTO `user_jobs` VALUES (4,4);
INSERT INTO `user_jobs` VALUES (5,3);
INSERT INTO `user_jobs` VALUES (4,2);
INSERT INTO `user_jobs` VALUES (3,1);



-- update text commented
ALTER TABLE `#__quix_collections` CHANGE `type` `type` ENUM('layout','section','header','footer','editor') NOT NULL DEFAULT 'section';

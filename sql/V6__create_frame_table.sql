-- sample sql
CREATE  TABLE IF NOT EXISTS `frame` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `user_id` INT NOT NULL ,
  `theme_id` INT NOT NULL ,
  `image_id` INT NOT NULL ,
  `parent_id` INT NOT NULL ,
  `last_story_id` INT NOT NULL ,
  `caption` VARCHAR(255) NOT NULL ,
  `created_at` DATETIME NOT NULL ,
  `updated_at` DATETIME NULL ,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`)
    REFERENCES user (`id`),
  FOREIGN KEY (`theme_id`)
    REFERENCES theme (`id`),
  FOREIGN KEY (`image_id`)
    REFERENCES image (`id`)
)
ENGINE = InnoDB;

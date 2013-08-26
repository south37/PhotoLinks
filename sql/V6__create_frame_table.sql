-- sample sql
CREATE  TABLE IF NOT EXISTS `frame` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `user_id` INT UNSIGNED NOT NULL ,
  `theme_id` INT UNSIGNED NOT NULL ,
  `image_id` INT UNSIGNED NOT NULL ,
  `parent_id` INT UNSIGNED NULL ,
  `last_story_id` INT UNSIGNED NOT NULL ,
  `caption` VARCHAR(255) NOT NULL ,
  `created_at` DATETIME NOT NULL ,
  `updated_at` DATETIME NULL ,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`)
    REFERENCES user (`id`),
  FOREIGN KEY (`theme_id`)
    REFERENCES theme (`id`),
  FOREIGN KEY (`image_id`)
    REFERENCES image (`id`),
  INDEX parent_id_idx(`parent_id`)
)
ENGINE = InnoDB;

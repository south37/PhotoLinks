-- sample sql
CREATE  TABLE IF NOT EXISTS `liked` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `story_id` INT UNSIGNED NOT NULL ,
  `user_id` INT UNSIGNED NOT NULL ,
  `created_at` DATETIME NOT NULL ,
  `updated_at` DATETIME NULL ,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`story_id`)
    REFERENCES story (`id`),
  FOREIGN KEY (`user_id`)
    REFERENCES user (`id`)
)
ENGINE = InnoDB;

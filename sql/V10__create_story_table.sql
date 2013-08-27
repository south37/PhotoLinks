-- sample sql
CREATE  TABLE IF NOT EXISTS `story` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `user_id` INT UNSIGNED NOT NULL ,
  `title` VARCHAR(255) NOT NULL ,
  `favorite` INT UNSIGNED NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL ,
  `updated_at` DATETIME NULL ,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`)
    REFERENCES user (`id`)
)
ENGINE = InnoDB;

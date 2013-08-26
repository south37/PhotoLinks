-- sample sql
CREATE  TABLE IF NOT EXISTS `image` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `user_id` INT UNSIGNED NOT NULL ,
  `path` VARCHAR(255) NOT NULL ,
  `scope` INT UNSIGNED NOT NULL,
  `deleted` INT UNSIGNED NOT NULL ,
  `created_at` DATETIME NOT NULL ,
  `updated_at` DATETIME NULL ,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`)
    REFERENCES user (`id`)
)
ENGINE = InnoDB;

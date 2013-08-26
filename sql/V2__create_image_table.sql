-- sample sql
CREATE  TABLE IF NOT EXISTS `image` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `user_id` INT NOT NULL ,
  `path` VARCHAR(255) NOT NULL ,
  `scope` INT NOT NULL ,
  `deleted` INT NOT NULL ,
  `created_at` DATETIME NOT NULL ,
  `updated_at` DATETIME NULL ,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`)
    REFERENCES user (`id`)
)
ENGINE = InnoDB;

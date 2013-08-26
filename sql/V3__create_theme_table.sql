-- sample sql
CREATE  TABLE IF NOT EXISTS `theme` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `user_id` INT UNSIGNED NOT NULL,
  `frame_id` INT UNSIGNED NOT NULL,
  `title` VARCHAR(255) NOT NULL ,
  `fix_num` INT UNSIGNED NOT NULL ,
  `frame_num` INT UNSIGNED NOT NULL ,
  `created_at` DATETIME NOT NULL ,
  `updated_at` DATETIME NULL ,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`)
    REFERENCES user (`id`)
)  
ENGINE = InnoDB;

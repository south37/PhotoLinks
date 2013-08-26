-- sample sql
CREATE  TABLE IF NOT EXISTS `theme` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `user_id` INT NOT NULL ,
  `path` VARCHAR(255) NOT NULL ,
  `fix_num` INT NOT NULL ,
  `frame_num` INT NOT NULL ,
  `created_at` DATETIME NOT NULL ,
  `updated_at` DATETIME NULL ,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`)
    REFERENCES user (`id`)
)  
ENGINE = InnoDB;

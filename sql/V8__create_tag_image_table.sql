-- sample sql
CREATE  TABLE IF NOT EXISTS `tag_image` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `tag_id` INT UNSIGNED NOT NULL ,
  `image_id` INT UNSIGNED NOT NULL ,
  `created_at` DATETIME NOT NULL ,
  `updated_at` DATETIME NULL ,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`tag_id`)
    REFERENCES tag (`id`),
  FOREIGN KEY (`image_id`)
    REFERENCES image (`id`)
)
ENGINE = InnoDB;

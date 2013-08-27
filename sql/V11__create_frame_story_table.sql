-- sample sql
CREATE  TABLE IF NOT EXISTS `frame_story` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `frame_id` INT UNSIGNED NOT NULL ,
  `story_id` INT UNSIGNED NOT NULL ,
  `created_at` DATETIME NOT NULL ,
  `updated_at` DATETIME NULL ,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`frame_id`)
    REFERENCES frame (`id`),
  FOREIGN KEY (`story_id`)
    REFERENCES story (`id`)
)
ENGINE = InnoDB;

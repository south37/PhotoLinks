-- theme
INSERT INTO `theme` (`id`, `user_id`, `frame_id`, `title`, `fix_num`, `frame_num`, `created_at`, `updated_at`)
    VALUES (1, NULL, 1, 'test', 1, 0, NOW(), NOW());

-- image
INSERT INTO `image` (`id`, `user_id`, `path`, `scope`, `deleted`, `created_at`, `updated_at`)
    VALUES (1, 1, 'img/public_html/study2.png', 1, 0, NOW(), NOW());
INSERT INTO `image` (`id`, `user_id`, `path`, `scope`, `deleted`, `created_at`, `updated_at`)
    VALUES (2, 1, 'img/public_html/study2.png', 1, 0, NOW(), NOW());
INSERT INTO `image` (`id`, `user_id`, `path`, `scope`, `deleted`, `created_at`, `updated_at`)
    VALUES (3, 1, 'img/public_html/study2.png', 1, 0, NOW(), NOW());

-- frame
INSERT INTO `frame` (`id`, `user_id`, `theme_id`, `image_id`, `parent_id`, `last_story_id`, `caption`, `created_at`, `updated_at`)
    VALUES (1, 1, 1, 1, NULL, 0, 'test1', NOW(), NOW());
INSERT INTO `frame` (`id`, `user_id`, `theme_id`, `image_id`, `parent_id`, `last_story_id`, `caption`, `created_at`, `updated_at`)
    VALUES (2, 1, 1, 2, 1, 0, 'test2', NOW(), NOW());
INSERT INTO `frame` (`id`, `user_id`, `theme_id`, `image_id`, `parent_id`, `last_story_id`, `caption`, `created_at`, `updated_at`)
    VALUES (3, 1, 1, 3, 1, 0, 'test3', NOW(), NOW());

-- theme
INSERT INTO `theme` (`id`, `user_id`, `frame_id`, `title`, `fix_num`, `frame_num`, `created_at`, `updated_at`)
    VALUES (1, NULL, 1, 'test', 1, 0, NOW(), NOW());

-- image
INSERT INTO `image` (`id`, `user_id`, `path`, `scope`, `deleted`, `created_at`, `updated_at`)
    VALUES (1, 1, 'img/ismTest/hatsune01.png', 1, 0, NOW(), NOW());
INSERT INTO `image` (`id`, `user_id`, `path`, `scope`, `deleted`, `created_at`, `updated_at`)
    VALUES (2, 1, 'img/ismTest/hatsune02.png', 1, 0, NOW(), NOW());
INSERT INTO `image` (`id`, `user_id`, `path`, `scope`, `deleted`, `created_at`, `updated_at`)
    VALUES (3, 1, 'img/ismTest/miku1.jpg', 1, 0, NOW(), NOW());
INSERT INTO `image` (`id`, `user_id`, `path`, `scope`, `deleted`, `created_at`, `updated_at`)
    VALUES (4, 1, 'img/ismTest/miku2.jpg', 1, 0, NOW(), NOW());
INSERT INTO `image` (`id`, `user_id`, `path`, `scope`, `deleted`, `created_at`, `updated_at`)
    VALUES (5, 1, 'img/ismTest/miku3.jpg', 1, 0, NOW(), NOW());
INSERT INTO `image` (`id`, `user_id`, `path`, `scope`, `deleted`, `created_at`, `updated_at`)
    VALUES (6, 1, 'img/ismTest/miku4.gif', 1, 0, NOW(), NOW());

-- frame
INSERT INTO `frame` (`id`, `user_id`, `theme_id`, `image_id`, `parent_id`, `last_story_id`, `caption`, `created_at`, `updated_at`)
    VALUES (1, 1, 1, 1, NULL, 0, 'test1', NOW(), NOW());
INSERT INTO `frame` (`id`, `user_id`, `theme_id`, `image_id`, `parent_id`, `last_story_id`, `caption`, `created_at`, `updated_at`)
    VALUES (2, 1, 1, 2, 1, 0, 'test2', NOW(), NOW());
INSERT INTO `frame` (`id`, `user_id`, `theme_id`, `image_id`, `parent_id`, `last_story_id`, `caption`, `created_at`, `updated_at`)
    VALUES (3, 1, 1, 3, 1, 0, 'test3', NOW(), NOW());
INSERT INTO `frame` (`id`, `user_id`, `theme_id`, `image_id`, `parent_id`, `last_story_id`, `caption`, `created_at`, `updated_at`)
    VALUES (4, 1, 1, 4, 2, 0, 'test4', NOW(), NOW());
INSERT INTO `frame` (`id`, `user_id`, `theme_id`, `image_id`, `parent_id`, `last_story_id`, `caption`, `created_at`, `updated_at`)
    VALUES (5, 1, 1, 5, 2, 0, 'test5', NOW(), NOW());
INSERT INTO `frame` (`id`, `user_id`, `theme_id`, `image_id`, `parent_id`, `last_story_id`, `caption`, `created_at`, `updated_at`)
    VALUES (6, 1, 1, 6, 3, 0, 'test6', NOW(), NOW());

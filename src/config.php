<?php
/**
 * このファイルでコンテナに設定項目を定義する
 */

// see also: http://pimple.sensiolabs.org/
$container = new Pimple();

/**
 * ここでアプリケーションが利用する項目を設定する
 */
DB設定 :{
    $container['db.host']     = 'localhost';
    $container['db.database'] = 'group_c';
    $container['db.user']     = 'group_c';
    $container['db.password'] = 'group_cpass';
}
テンプレート設定: {
    $container['twig.templateDir'] = __DIR__ . '/views';
}

return $container;

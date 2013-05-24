<?php
use Symfony\Component\Validator\Constraints as Assert;

ログイン: {
    $app->get('/user/login', function() use ($app, $container) {
            $app->render('user/login.html.twig');
        })
        ->name('user_login')
    ;
    $app->post('/user/login', function() use ($app, $container) {
            $input = $app->request()->post();

            // 入力チェック
            // see also: https://github.com/symfony/Validator/blob/master/Constraints
            $constraint = new Assert\Collection([
                'email'    => new Assert\NotBlank(['message' => 'メールアドレスを入力してください']),
                'password'   =>  [
                    new Assert\NotBlank(['message' => 'パスワードを入力してください']),
                ],
            ]);
            $errors = $container['validator']->validateValue($input, $constraint);

            if (count($errors) === 0) {
                // ユーザーの存在チェック
                $repository = $container['repository.user'];
                $user = $repository->findByEmailPassword($input['email'], $input['password']);
                if (!$user) {
                    $errors->add($container['error']('メールアドレスまたはパスワードを確認してください'));
                    $app->render('user/login.html.twig', ['errors' => $errors, 'input' => $input]);
                    $app->stop();
                }
                $container['session']->set('isLogin', true);
                $container['session']->set('user.name', $user->name);
                $container['session']->set('user.id', $user->id);
                $app->redirect($app->urlFor('welcome'));
            }

            $app->render('user/login.html.twig', ['errors' => $errors, 'input' => $input]);

        })
        ->name('user_login_post')
    ;
}

ログアウト: {
    $app->get('/user/logout', function() use ($app, $container) {
            $container['session']->clear();
            $app->redirect($app->urlFor('user_login'));
        })
        ->name('user_logout')
    ;
}

新規登録: {
    $app->get('/user/register', function () use ($app, $container) {
            $app->render('user/register.html.twig');
        })
        ->name('user_register')
    ;
    $app->post('/user/register', function () use ($app, $container) {
            $input = $app->request()->post();

            // 入力チェック
            // see also: https://github.com/symfony/Validator/blob/master/Constraints
            $constraint = new Assert\Collection([
                'name' =>  [
                    new Assert\NotBlank(['message' => '名前を入力してください']),
                    new Assert\Length(['max' => 255, 'maxMessage' => '255文字以内で登録してください']),

                ],
                'email'    => [
                    new Assert\NotBlank(['message' => 'メールアドレスを入力してください']),
                    new Assert\Email(['message' => 'メールアドレスが正しくありません']),
                ],
                'password'   => [
                    new Assert\NotBlank(['message' => 'パスワードを入力してください']),
                    new Assert\Length([
                        'min' => 6, 'minMessage' => 'パスワードは6文字以上で登録してください',
                        'max' => 64, 'maxMessage' => 'パスワードは120文字以上で登録してください',
                    ]),
                    new Assert\Regex(['pattern' => '/\A[0-9a-zA-Z&%$#!?_]{6,64}\z/',
                                      'message' => 'パスワードは半角英数&%$#!?_の組み合わせで登録してください']),
                ],
                'birthday'   => new Assert\Regex(['pattern' => '/\A[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])\z/',
                                                  'message' => '誕生日はyyyy-mm-dd形式で登録してください']),
            ]);

            $errors = $container['validator']->validateValue($input, $constraint);
            if (count($errors) === 0) {
                $user = new \Vg\Model\User();
                $user->setProperties($input);

                $repository = $container['repository.user'];
                try {
                    $repository->insert($user);
                } catch (Exception $e) {
                    $app->halt(500, $e->getMessage());
                }

                $app->redirect($app->urlFor('user_login'));
            }

            $app->render('user/register.html.twig', ['errors' => $errors, 'input' => $input]);
        })
        ->name('user_register_post')
    ;
}

編集: {

    /**
     * ログインしていない場合はログイン画面にリダイレクト
     *
     * http://docs.slimframework.com/#Route-Middleware
     *
     * @param $session
     *
     * @return callable
     */
    $rediretIfNotLogin = function ( $session ) {
        return function () use ( $session ) {
            if ( $session->get('isLogin') !== true ) {
                $app = \Slim\Slim::getInstance();
                $app->flash('error', 'Login required');
                $app->redirect($app->urlFor('user_login'));
            }
        };
    };

    $app->get('/user/edit', $rediretIfNotLogin($container['session']), function () use ($app, $container) {
            $repository = $container['repository.user'];
            $user = $repository->findById($container['session']->get('user.id'));

            $app->render('user/edit.html.twig', ['input' => $user]);
        })
        ->name('user_edit')
    ;
    $app->post('/user/update', function () use ($app, $container) {
            $input = $app->request()->post();

            // 入力チェック
            // see also: https://github.com/symfony/Validator/blob/master/Constraints
            $constraint = new Assert\Collection([
                'name' =>  [
                    new Assert\NotBlank(['message' => '名前を入力してください']),
                    new Assert\Length(['max' => 255, 'maxMessage' => '255文字以内で登録してください']),

                ],
                'email'    => [
                    new Assert\NotBlank(['message' => 'メールアドレスを入力してください']),
                    new Assert\Email(['message' => 'メールアドレスが正しくありません']),
                ],
                'birthday'   => new Assert\Regex(['pattern' => '/\A[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])\z/',
                                                  'message' => '誕生日はyyyy-mm-dd形式で登録してください']),
            ]);

            $errors = $container['validator']->validateValue($input, $constraint);
            if (count($errors) === 0) {
                $repository = $container['repository.user'];
                $user = $repository->findById($container['session']->get('user.id'));
                $user->name = $input['name'];
                $user->email = $input['email'];
                $user->birthday = $input['birthday'];

                try {
                    $repository->update($user);
                } catch (Exception $e) {
                    $app->halt(500, $e->getMessage());
                }

                $container['session']->set('user.name', $user->name);
                $app->redirect($app->urlFor('welcome'));
            }

            $app->render('user/edit.html.twig', ['errors' => $errors, 'input' => $input]);
        })
        ->name('user_update')
    ;
}

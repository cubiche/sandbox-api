<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Controller;

use Sandbox\Core\Domain\Exception\NotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * UserController class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class UserController extends Controller
{
    /**
     * @param Request $request
     * @param string  $token
     *
     * @return JsonResponse
     */
    public function verifyAction(Request $request, $token)
    {
        $controller = $this->get('app.controller.user');
        try {
            $controller->verifyAction($token);

            return $this->render('default/verify.html.twig');
        } catch (NotFoundException $e) {
            return $this->render('default/verify.html.twig', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * @param Request $request
     * @param string  $token
     *
     * @return JsonResponse
     */
    public function resetAction(Request $request, $token)
    {
        $controller = $this->get('app.controller.read_model.user');
        $user = $controller->findOneByPasswordResetTokenAction($token);
        if ($user === null) {
            throw new NotFoundHttpException('There is no user with token: '.$token);
        }

        if ($request->getMethod() == 'GET') {
            return $this->render('default/resetForm.html.twig', array(
                'token' => $token,
                'username' => $user->username()->toNative(),
            ));
        }

        // Method: POST
        $password = $request->request->get('password');
        $confirm = $request->request->get('confirm');
        if ($password !== $confirm) {
            return $this->render('default/reset.html.twig', [
                'error' => 'Password does not match the confirm password',
            ]);
        } else {
            $controller = $this->get('app.controller.user');
            try {
                $controller->resetPasswordAction($user->userId()->toNative(), $password);

                return $this->render('default/reset.html.twig');
            } catch (\Exception $e) {
                return $this->render('default/reset.html.twig', [
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}

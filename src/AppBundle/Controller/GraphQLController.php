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

use Sandbox\Core\Infrastructure\GraphQL\ApplicationSchema;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Youshido\GraphQL\Execution\Processor;

/**
 * GraphQLController class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class GraphQLController extends Controller
{
    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function graphqlAction(Request $request)
    {
        if ($this->isJsonRequest($request)) {
            $payload = json_decode($request->getContent(), true);
            $request->request->replace(is_array($payload) ? $payload : array());

            $payload = $request->request->get('query');
            $variables = $request->request->get('variables');

            $processor = new Processor(new ApplicationSchema());
            $processor->getExecutionContext()
                ->setContainer($this->get('app.container.graphql'))
            ;

            $response = $processor->processPayload($payload, $variables)->getResponseData();
            if ($processor->getExecutionContext()->hasErrors()) {
                $response['errors'] = ApplicationSchema::formatErrors(
                    $processor->getExecutionContext()->getErrors()
                );
            }

            return new JsonResponse($response);
        }

        return new JsonResponse(array(
            'errors' => array('Bad request. The request must be in application/json Content-Type.'),
        ), 400);
    }

    /**
     * @param name $route
     *
     * @return Response
     */
    public function graphiqlAction($route)
    {
        return $this->container->get('templating')->renderResponse(
            'graphiql/index.html.twig',
            array(
                'endpoint' => $this->generateUrl($route),
                'versions' => array(
                    'graphiql' => '0.11',
                    'react' => '15.6',
                    'fetch' => '2.0',
                ),
            )
        );
    }

    /**
     * @param Request $request
     *
     * @return bool
     */
    protected function isJsonRequest(Request $request)
    {
        return 0 === strpos($request->headers->get('Content-Type'), 'application/json');
    }
}

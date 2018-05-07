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

use AppBundle\Faker\Provider\EmailProvider;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Faker\Factory as Faker;

/**
 * EmailRendererController class.
 *
 * @Route("/email")
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class EmailRendererController extends Controller
{
    /**
     * @var string
     */
    protected $basePath;

    /**
     * @var Finder
     */
    protected $finder;

    /**
     * EmailRendererController constructor.
     */
    public function __construct()
    {
        $this->finder = new Finder();
    }

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
        $this->basePath = $this->container->getParameter('kernel.root_dir').'/Resources/views/Mailer';
    }

    /**
     * @Route("/", name="email_templates")
     */
    public function indexAction(Request $request)
    {
        $directories = array();
        $templates = array();

        $directory = $request->query->get('directory');
        $folder = $this->basePath.'/'.$directory;

        if ($directory !== null && is_dir($folder)) {
            foreach ($this->finder->files()->name('*.twig')->in($folder) as $file) {
                $fileName = $file->getBasename();
                $name = ucwords(
                    implode(' ', $this->splitCamelCase(str_replace('.html.twig', '', $fileName)))
                );

                $templates[$name] = $fileName;
            }
        } else {
            foreach ($this->finder->directories()->in($this->basePath) as $folder) {
                $folderName = $folder->getBasename();

                $fileFinder = new Finder();
                $files = $fileFinder
                    ->files()
                    ->name('*.twig')
                    ->in($this->basePath.'/'.$folderName)
                ;

                $directories[$folderName] = count($files);
            }
        }

        return $this->render('Renderer/email.html.twig', [
            'directories' => $directories,
            'templates' => $templates,
            'directory' => $directory,
        ]);
    }

    /**
     * @Route("/email/{directory}/{template}", name="email_template_render")
     */
    public function templateAction(Request $request, $directory, $template)
    {
        $this->container->get('profiler')->disable();
        $templateName = sprintf('%s/%s/%s', $this->basePath, $directory, $template);

        /** @var \Twig_Environment $twig */
        $twig = $this->container->get('twig');
        $globals = $twig->getGlobals();

        $loaded = $twig->getLoader()->getSourceContext($templateName);
        $variables = $this->extractVars($twig->parse($twig->tokenize($loaded)));

        $faker = Faker::create();
        $faker->addProvider(new EmailProvider($faker));

        foreach ($variables as $key => $method) {
            if (isset($globals[$key])) {
                $variables[$key] = $globals[$key];
            } else {
                $variables[$key] = $faker->{$method};
            }
        }

        return new Response($this->container->get('twig')->render($templateName, $variables));
    }

    /**
     * @param $node
     *
     * @return array
     */
    protected function extractVars($node)
    {
        if (!$node instanceof \Traversable) {
            return array();
        }

        $vars = array();
        foreach ($node as $cur) {
            if (get_class($cur) != 'Twig_Node_Expression_Name') {
                $vars = array_merge($vars, $this->extractVars($cur));
            } else {
                $vars[$cur->getAttribute('name')] = $cur->getAttribute('name');
            }
        }

        return $vars;
    }

    /**
     * @param string $input
     *
     * @return array
     */
    private function splitCamelCase($input)
    {
        return preg_split(
            '/(^[^A-Z]+|[A-Z][^A-Z]+)/',
            $input,
            -1, /* no limit for replacement count */
            PREG_SPLIT_NO_EMPTY /*don't return empty elements*/
            | PREG_SPLIT_DELIM_CAPTURE /*don't strip anything from output array*/
        );
    }
}

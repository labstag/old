<?php
/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Labstag\Controller;

use Knp\Component\Pager\PaginatorInterface;
use Labstag\Lib\ControllerLib;
use Psr\Log\LoggerInterface;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Loader\ExistsLoaderInterface;

/**
 * ExceptionController renders error or exception pages for a given
 * FlattenException.
 */
class ExceptionController extends ControllerLib
{

    /**
     * @var Environment
     */
    protected $twig;

    /**
     * @var bool
     */
    protected $debug;

    /**
     * @param bool $debug Show error (false) or exception (true) pages by default
     */
    public function __construct(
        Environment $twig,
        ContainerInterface $container,
        PaginatorInterface $paginator,
        RequestStack $requestStack,
        RouterInterface $router,
        bool $debug,
        LoggerInterface $logger
    )
    {
        parent::__construct($container, $paginator, $requestStack, $router, $logger);
        $this->twig  = $twig;
        $this->debug = $debug;
    }

    /**
     * Converts an Exception to a Response.
     *
     * A "showException" request parameter can be used to force display of an error page (when set to false) or
     * the exception page (when true). If it is not present, the "debug" value passed into the constructor will
     * be used.
     *
     * @throws \InvalidArgumentException When the exception template does not exist
     *
     * @return Response
     */
    public function showAction(Request $request, FlattenException $exception, DebugLoggerInterface $logger = null)
    {
        /** @var mixed $xPOhpObLevel */
        $xPOhpObLevel   = -1;
        $currentContent = $this->getAndCleanOutputBuffering((int) $request->headers->get('X-Php-Ob-Level', $xPOhpObLevel));
        $showException  = $request->attributes->get('showException', $this->debug);
        // As opposed to an additional parameter, this maintains BC
        $code = $exception->getStatusCode();

        $parameters = [
            'class_body'     => 'ErrorPage',
            'status_code'    => $code,
            'status_text'    => '',
            'exception'      => $exception,
            'logger'         => $logger,
            'currentContent' => $currentContent,
        ];
        $this->addParamViewsSite($parameters);

        $templates   = $this->findTemplate($request, (string) $request->getRequestFormat(), $code, $showException);
        $contentType = (string) $request->getMimeType((string) $request->getRequestFormat());
        $contentType = !empty($contentType) ? $contentType : 'text/html';

        return new Response(
            $this->twig->render(
                $templates,
                $this->paramViews
            ),
            200,
            ['Content-Type' => $contentType]
        );
    }

    /**
     * @return string|false
     */
    protected function getAndCleanOutputBuffering(int $startObLevel)
    {
        if (ob_get_level() <= $startObLevel) {
            return '';
        }

        Response::closeOutputBuffers(($startObLevel + 1), true);

        return ob_get_clean();
    }

    /**
     * @param string $format
     * @param int    $code          An HTTP response status code
     * @param bool   $showException
     *
     * @return string
     */
    protected function findTemplate(Request $request, $format, $code, $showException)
    {
        $name = $showException ? 'exception' : 'error';
        if ($showException && 'html' == $format) {
            $name = 'exception_full';
        }

        // For error pages, try to find a template for the specific HTTP status code and format
        if (!$showException) {
            $template = sprintf('exception/%s%s.%s.twig', $name, $code, $format);
            if ($this->templateExists($template)) {
                return $template;
            }
        }

        // try to find a template for the given format
        $template = sprintf('exception/%s.%s.twig', $name, $format);
        if ($this->templateExists($template)) {
            return $template;
        }

        // default to a generic HTML exception
        $request->setRequestFormat('html');

        return sprintf('exception/%s.html.twig', $showException ? 'exception_full' : $name);
    }

    /**
     * to be removed when the minimum required version of Twig is >= 3.0.
     *
     * @return string|bool
     */
    protected function templateExists(string $template)
    {
        $loader = $this->twig->getLoader();
        if ($loader instanceof ExistsLoaderInterface || method_exists($loader, 'exists')) {
            return $loader->exists($template);
        }

        try {
            $loader->getSourceContext($template)->getCode();

            return true;
        } catch (LoaderError $exception) {
            $this->logger->error($exception->getMessage());

            return $exception->getMessage();
        }
    }
}

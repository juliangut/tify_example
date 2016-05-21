<?php
/**
 * Push notification server example (http://github.com/juliangut/tify_example)
 *
 * @link https://github.com/juliangut/tify_example for the canonical source repository
 *
 * @license https://github.com/juliangut/tify_example/blob/master/LICENSE
 */

namespace Jgut\Pusher\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AbstractController
 */
abstract class AbstractController
{
    /**
     * @param ServerRequestInterface $request
     * @param array                  $definedParameters
     * @param array                  $defaultParameters
     * @param array                  $requiredParameters
     *
     * @throws \RuntimeException
     *
     * @return array
     */
    protected function resolveRequestParameters(
        ServerRequestInterface $request,
        array $definedParameters = [],
        array $defaultParameters = [],
        array $requiredParameters = []
    ) {
        $parametersResolver = new OptionsResolver();

        $parametersResolver->setDefined($definedParameters);
        $parametersResolver->setDefaults($defaultParameters);
        $parametersResolver->setRequired($requiredParameters);

        $parameters = $request->getParsedBody();

        try {
            return $parametersResolver->resolve(is_array($parameters) ? $parameters : []);
        } catch (\Exception $exception) {
            throw new \RuntimeException(str_replace(' option', ' parameter', $exception->getMessage()), 0, $exception);
        }

    }
}

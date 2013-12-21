<?php
/**
 * 
 * 
 * 
 * 
 * 
 */

namespace WizardsFugue\Silex\Provider;

use p2ee\Preparables\Preparable;
use Silex\Application;
use Silex\ServiceProviderInterface;
use p2ee\Preparables\Preparer;
use p2ee\Partlets\PartletRequirement;
use p2ee\Partlets\PartletResolver;
use p2ee\BaseRequirements\Requirements\RequestDataRequirement;
use p2ee\BaseRequirements\Requirements\ServiceRequirement;
use p2ee\BaseRequirements\Resolvers\RequestDataResolver;
use p2ee\BaseRequirements\Resolvers\ServiceResolver;
use rg\injektor\Provider;

class PartletServiceProvider implements ServiceProviderInterface{
    
    
    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Application $app An Application instance
     */
    public function register(Application $app)
    {


        $app['partlet.rg.injektor'] = $app->share(function () use ($app) {
                
                /** @var \Symfony\Component\HttpFoundation\Request $request */
                $request = $app['request'];

                $configuration = new \rg\injektor\Configuration(null,$app['rg.injector.factories']);
                $configuration->setConfig(array(
                        \Symfony\Component\HttpFoundation\Request::class => [
                            'instance' => $app['request']
                        ]
                    ));
                return new \rg\injektor\FactoryDependencyInjectionContainer($configuration);
            });

        $app['partlet.preparer'] = $app->share( function () use ($app) {
                $dic = $app['partlet.rg.injektor'];

                $preparer = new Preparer([
                    ServiceRequirement::class => $dic->getInstanceOfClass(ServiceResolver::class),
                    RequestDataRequirement::class => $dic->getInstanceOfClass(RequestDataResolver::class),
                    PartletRequirement::class => $dic->getInstanceOfClass(PartletResolver::class),
                ]);
                return $preparer;
            });
        
        $app['partlet.render'] = $app->protect( function($partletClass) use ($app) {

                $dic = $app['partlet.rg.injektor'];
                $partlet =  $dic->getInstanceOfClass($partletClass);
                $app['partlet.preparer']->prepare( $partlet );

                $renderer = $dic->getInstanceOfClass( \Cotya\Yofo\Partlets\JsonRenderer::class );
                $content = $renderer->render($partlet);
                return $content;
            });
        
    }

    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registered
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     */
    public function boot(Application $app)
    {
        
    }


} 
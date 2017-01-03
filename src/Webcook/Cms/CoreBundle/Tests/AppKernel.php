<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            // Additional bundles
            new FOS\RestBundle\FOSRestBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new \Tbbc\MoneyBundle\TbbcMoneyBundle(),

            // Webcookcms bundles, loaded via `bundles.php`
            new Webcook\Cms\SecurityBundle\WebcookCmsSecurityBundle(),
            new Webcook\Cms\CoreBundle\WebcookCmsCoreBundle(),
            new Webcook\Cms\I18nBundle\WebcookCmsI18nBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Liip\FunctionalTestBundle\LiipFunctionalTestBundle();
            $bundles[] = new Nelmio\ApiDocBundle\NelmioApiDocBundle();
        }

        $bundlePath = __DIR__.'/../../../../../../../../tests/bundles.php';
        if (file_exists($bundlePath)) {
            $sBundles = require $bundlePath;
            $bundles = array_merge($bundles, $sBundles);
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');

        $testConfigPath = __DIR__.'/../../../../../../../../tests/config.yml';
        if (file_exists($testConfigPath)) {
            $loader->load($testConfigPath);
        } else {
            $loader->load(__DIR__.'/../../../../../tests/config.yml');
        }
    }
}

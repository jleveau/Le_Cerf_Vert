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
            new LCV\PlatformBundle\LCVPlatformBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new Ivory\CKEditorBundle\IvoryCKEditorBundle(),
        		
        	// Sonata
        	new Sonata\CoreBundle\SonataCoreBundle(),
        	new Sonata\BlockBundle\SonataBlockBundle(),
        	new Knp\Bundle\MenuBundle\KnpMenuBundle(),
        	new Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),
        		
        	// Then add SonataMedia & Dependancies
        	new Sonata\AdminBundle\SonataAdminBundle(),
        	new Sonata\EasyExtendsBundle\SonataEasyExtendsBundle(),
        	new Sonata\MediaBundle\SonataMediaBundle(),
        	new Application\Sonata\MediaBundle\ApplicationSonataMediaBundle(),
        	new CoopTilleuls\Bundle\CKEditorSonataMediaBundle\CoopTilleulsCKEditorSonataMediaBundle(),
        	
            new Sonata\UserBundle\SonataUserBundle('FOSUserBundle'),
        	new Application\Sonata\UserBundle\ApplicationSonataUserBundle(),

            new LCV\CoreBundle\LCVCoreBundle(),
        		
        	//Bootstrap bundle
        	new Braincrafted\Bundle\BootstrapBundle\BraincraftedBootstrapBundle(),
            
            //FOSCommentBundle
            
            //Rest api
            new FOS\RestBundle\FOSRestBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),


            new LCV\CommentBundle\LCVCommentBundle(),
            new CoreSphere\ConsoleBundle\CoreSphereConsoleBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            $bundles[] = new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle();
           
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}

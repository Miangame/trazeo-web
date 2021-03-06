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
            new JMS\AopBundle\JMSAopBundle(),
            new JMS\DiExtraBundle\JMSDiExtraBundle($this),
            new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new Sonata\AdminBundle\SonataAdminBundle(),
            new Sonata\CoreBundle\SonataCoreBundle(),
            new Sonata\BlockBundle\SonataBlockBundle(),
            new Sonata\CacheBundle\SonataCacheBundle(),
            new Sonata\jQueryBundle\SonatajQueryBundle(),
            new Sonata\EasyExtendsBundle\SonataEasyExtendsBundle(),
            new Sonata\UserBundle\SonataUserBundle('FOSUserBundle'),
            new Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new Craue\FormFlowBundle\CraueFormFlowBundle(),
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new A2lix\TranslationFormBundle\A2lixTranslationFormBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle($this),
            new FOS\RestBundle\FOSRestBundle(),
            new Sopinet\Bundle\BootstrapExtendBundle\SopinetBootstrapExtendBundle(),
            new RaulFraile\Bundle\LadybugBundle\RaulFraileLadybugBundle(),
            new Sopinet\Template\Sbadmin2Bundle\SopinetTemplateSbadmin2Bundle(),
            new Application\Sonata\UserBundle\ApplicationSonataUserBundle(),
            new Trazeo\BaseBundle\TrazeoBaseBundle(),
            new Trazeo\FrontBundle\TrazeoFrontBundle(),
            new JJs\Bundle\GeonamesBundle\JJsGeonamesBundle(),
            new Sopinet\Template\AmoebaBundle\SopinetTemplateAmoebaBundle(),
            new JMS\TranslationBundle\JMSTranslationBundle(),
            new Elao\ErrorNotifierBundle\ElaoErrorNotifierBundle(),
            #new Sopinet\Bundle\AdminBundle\SopinetAdminBundle(),
            new HWI\Bundle\OAuthBundle\HWIOAuthBundle(),
            new Sopinet\UserBundle\SopinetUserBundle(),
            new Sopinet\Template\LandingBundle\SopinetTemplateLandingBundle(),
            new FOS\CommentBundle\FOSCommentBundle(),
            new Sopinet\TimelineBundle\SopinetTimelineBundle(),
            new Sopinet\OpenMapBundle\SopinetOpenMapBundle(),
            new Sopinet\UserPreferencesBundle\SopinetUserPreferencesBundle(),
            new Sopinet\Bundle\UserNotificationsBundle\SopinetUserNotificationsBundle(),
            new Sopinet\FlashMessagesBundle\SopinetFlashMessagesBundle(),
            new Lunetics\LocaleBundle\LuneticsLocaleBundle(),
            //new Endroid\Bundle\QrCodeBundle\EndroidQrCodeBundle(),
            new Sopinet\Bundle\GamificationBundle\SopinetGamificationBundle(),
            new Sopinet\Bundle\SuggestionBundle\SopinetSuggestionBundle(),
            new Sopinet\GCMBundle\SopinetGCMBundle(),
            new Sopinet\AutologinBundle\SopinetAutologinBundle(),
            new Hip\MandrillBundle\HipMandrillBundle(),
            new Oneup\UploaderBundle\OneupUploaderBundle(),
            new Sopinet\Bundle\UploadMagicBundle\SopinetUploadMagicBundle(),
            new Trazeo\MyPageBundle\TrazeoMyPageBundle(),
            new Ob\HighchartsBundle\ObHighchartsBundle(),
            new Ivory\CKEditorBundle\IvoryCKEditorBundle(),
            new Sopinet\Bundle\ChatBundle\SopinetChatBundle(),
            new Nelmio\ApiDocBundle\NelmioApiDocBundle(),
            new RMS\PushNotificationsBundle\RMSPushNotificationsBundle(),
            new OldSound\RabbitMqBundle\OldSoundRabbitMqBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/config/config_' . $this->getEnvironment() . '.yml');

        // Subdominio personalizado o BETA subdominio
        /**
        $parts=explode('.', $_SERVER["SERVER_NAME"]);
        if ($parts[0] == "beta") {
            $loader->load(__DIR__ . '/config/configSonataAdmin.yml');
        } else {
            $loader->load(__DIR__ . '/config/configSonataPanel.yml');
        }
         **/
        //}
    }
}
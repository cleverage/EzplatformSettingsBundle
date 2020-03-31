<?php

namespace Masev\SettingsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Masev\SettingsBundle\Form\Type\SiteaccessType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use eZ\Publish\Core\MVC\Symfony\Security\Authorization\Attribute;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $this->denyAccessUnlessGranted('ez:settings:manage');

        $container = $this->container;
        $listSiteaccess = $container->getParameter('ezpublish.siteaccess.list');
        array_unshift($listSiteaccess, 'default');

        $options = [
          'siteaccess_list' => $listSiteaccess,
        ];

        $form = $this->createForm(SiteaccessType::class, null, $options);

        return $this->render('MasevSettingsBundle:Default:index.html.twig', [
          'form' => $form->createView(),
        ] );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool|\Symfony\Component\HttpFoundation\Response
     */
    public function ajaxGetFormSiteaccessAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ez:settings:manage');

        $container = $this->container;

        $siteaccess = $request->request->get('siteaccess');
        if (!$siteaccess) {
            return false;
        }

        $dataAsArray = $container->get("masev_settings.model.settings")->getDataAsArray($siteaccess);
        $sections = $container->get("masev_settings.model.settings")->getSections();

        $level2_sections = [];
        foreach ($sections as $key => $section) {
            foreach ($section as $key2 => $level2) {
                array_push($level2_sections,['name' => $key2, 'level1' => $key]);
            }
        }

        $pathUpdate = $this->container->get('router')->generate('masev_ajax_update');

        $html = $this->renderView('MasevSettingsBundle:Default:form_settings.html.twig', [
          'sections' => $sections,
          'data' => $dataAsArray,
          'level2_sections' => $level2_sections,
          'site'    => $siteaccess,
          'path_update' => $pathUpdate
        ] );

        $data = json_encode([
          'html' => $html
        ]);

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function ajaxUpdateAction(Request $request) {

        $this->denyAccessUnlessGranted('ez:settings:manage');
        $siteaccess = $request->request->get('siteaccess');
        $schemaName = $request->request->get('schema_name');
        $value = $request->request->get('value');
        $type_element = $request->request->get('type_element');
        $error = '';

        try {
            $settingsModel = $this->container->get("masev_settings.model.settings");
            $settingsModel->__set($schemaName, urldecode($value));
            $settingsModel->save($siteaccess);

            $success = true;
        } catch (\Exception $e) {
            $error = $e->getMessage();
            $success = false;
        }

        $data = json_encode([
          'error' => $error,
          'result' => $value,
          'success' => $success
        ]);

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function clearCacheAction(Request $request) {
        $this->denyAccessUnlessGranted('ez:settings:manage');

        $container = $this->container;
        $kernel = $container->get('kernel');
        $error = '';

        try {
            $injectionManager = $container->get('masev_settings.dependency_injection.container_injection_manager');
            $injectionManager->rebuild($kernel);

            if ($container->getParameter('masev_settings.varnish_purge.enabled') == true && $container->hasParameter('masev_settings.varnish_purge.purger_interface_id')) {
                $purger = $container->get($container->getParameter('masev_settings.varnish_purge.purger_interface_id'));
                $purger->purgeAll();
            }

            $success = true;
        }
        catch (\Exception $e) {
            $success = false;
            $error = $e->getMessage();
        }

        $data = json_encode([
          'error' => $error,
          'success' => $success
        ]);

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}

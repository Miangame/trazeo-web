<?php // src/Acme/DemoBundle/Controller/CRUDController.php

namespace Trazeo\MyPageBundle\Controller;

use Ob\HighchartsBundle\Highcharts\Highchart;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CRUDController extends Controller
{
    public function batchActionMerge(ProxyQueryInterface $selectedModelQuery)
    {
        if ($this->admin->isGranted('EDIT') === false || $this->admin->isGranted('DELETE') === false) {
            throw new AccessDeniedException();
        }

        $request = $this->get('request');
        $modelManager = $this->admin->getModelManager();

        //$target = $modelManager->find($this->admin->getClass(), $request->get('targetId'));
        //ldd($selectedModelQuery);

        // Obtenemos los datos seleccionados
        $selectedModels = $selectedModelQuery->execute();

        $formattedData = $this->filterByModeDate($selectedModels, 'day');

        $otro[] = 12;
        $otro[] = 254;

        // Chart
        $series = array(
            array("name" => "Registro de Niños",    "data" => $formattedData['data']),
            array("name" => "Locomotor", "data" => $otro)
        );

        $ob = new Highchart();
        $ob->chart->renderTo('linechart');  // The #id of the div where to render the chart
        $ob->title->text('Gráfica');
        $ob->xAxis->title(array('text'  => "Fecha"));
        $ob->xAxis->categories($formattedData['label']);
        $ob->yAxis->title(array('text'  => "Número de Registros"));
        $ob->series($series);

        return $this->render('TrazeoMyPageBundle:Admin:view_linegraph.html.twig', array(
            'chart' => $ob
        ));
    }

    private function filterByModeDate($data, $modeDate = "month") {
        switch ($modeDate) {
            case "day":
                $formatCode = "Y-m-d";
                $formatView = "d-m-Y";
                break;

            case "week":
                $formatCode = "Y-W";
                $formatView = "l, d-m-Y";
                break;

            case "month":
                $formatCode = "Y-m";
                $formatView = "F";
                break;
        }

        // Hacemos el sumatorio de número de datos por tipo de formato de fecha
        $output=array();
        $categoriesDateFormat = array();
        foreach($data as $object)
        {
            /** @var \DateTime $datetime */
            $datetime = $object->getCreatedAt();
            if ($datetime != null) {
                //$day = $datetime->format("Y-m-d");
                $dateFormatCode = $datetime->format($formatCode);
                $dateFormatView = $datetime->format($formatView);
                if (!isset($output[$dateFormatCode])) {
                    $output[$dateFormatCode] = 0;
                    $categoriesDateFormat[] = $dateFormatView;
                }
                $output[$dateFormatCode] += 1;
            }
        }

        // Preparamos los datos
        foreach($output as $key => $value) {
            $values[] = $value;
        }

        // Preparamos las etiquetas (TODO)

        $return['data'] = $values;
        $return['label'] = $categoriesDateFormat;


        return $return;
    }
}
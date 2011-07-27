<?php
/**
 * InteractiveChart Helper Class. Used to create new objects of any of the
 * different chart objects
 *
 * @package    plugins
 * @subpackage sdInteractiveChart
 * @author     Seb Dangerfield - Second2
 * @version    0.4.1
 */

class InteractiveChart {
    static $dataFunctionCount = 1;
    
    /**
     * Creates and returns a new instance of the BarChart class to use.
     *
     * @return BarGraph
     */
    static function newScatterChart() {
        return new ScatterGraph();
    }
    
    /**
     * Creates and returns a new instance of the BarChart class to use.
     *
     * @return BarGraph
     */
    static function newBarChart() {
        return new BarGraph();
    }

    /**
     * Creates and returns a new instance of the LineChart class to use.
     * 
     * @return LineGraph
     */
    static function newLineChart() {
        return new LineGraph();
    }

    /**
     * Creates and returns a new instance of the AreaChart class to use.
     *
     * @return AreaGraph
     */
    static function newAreaChart() {
        return new AreaGraph();
    }

    /**
     * Creates and returns a new instance of the ColumnChart class to use.
     * 
     * @return ColumnGraph
     */
    static function newColumnChart() {
        return new ColumnGraph();
    }

    /**
     * Creates and returns a new instance of the GaugeChart class to use.
     * 
     * @return GaugeGraph
     */
    static function newGuageChart() {
        return new GaugeGraph();
    }

    /**
     * Creates and returns a new instance of the PieChart class to use.
     * 
     * @return PieGraph
     */
    static function newPieChart() {
        return new PieGraph();
    }

    /**
     * Creates and returns a new instance of the TimelineChart class to use.
     * - NEW V0.4 - Thanks to Robert Heim
     *
     * @return AnnotatedTimeLineGraph
     */
    static function newTimeLineChart() {
        return new AnnotatedTimeLineGraph();
    }



    /**
     * Formats chart data into the correct format to be returned as JSON data to the 
     * plugin AJAX data request.
     *
     * @param array $data
     * @param array $chartLabels
     * @param array $extraData
     * @return string - JSON encoded data to be returned to create the chart
     */
    static function generateJsonData(&$data, $chartLabels, $extraData = array()) {
        $result = $extraData;
        $result['dataNames'] = array();
        $result['data'] = array();

        $result['labels'] = $chartLabels;
        // if we were passed a single array of values we need to wrap it
        // in another array!
        if (!is_array(current($data))) {
            $result['data'][0] = $data;
        } else {

            foreach ($data as $key=>$val) {
                if (!is_string($key))
                    $key = '' . $key;

                array_push($result['dataNames'], $key);
                array_push($result['data'], $val);
            }
        }

        return json_encode($result);
    }


    


}

/**
 * This function is automatically called when symfony loads factories.
 * It will add the required JS files to the list to be inlcuded in the
 * template file.
 */
function addInteractiveChartJavascript() {
    $version = '0.3.0';
    $ajax_api_url = sfConfig::get('app_sdInteractiveChart_chart_js_url');
    $ajax_api = sfConfig::get('app_sdInteractiveChart_ajax_api');

    if (sfContext::getInstance()->getRequest()->isSecure()) { // Load over SSL if the page is over SSL - Google supports this!
        $ajax_api_url = str_replace ('http:', 'https:', $ajax_api_url);
    }
    sfContext::getInstance()->getResponse()->addJavascript($ajax_api_url . $ajax_api);
    $relRoot = sfContext::getInstance()->getRequest()->getRelativeUrlRoot();
    
    if ((sfConfig::get('sf_environment') == 'dev') || (sfConfig::get('app_sdInteractiveChart_debug_mode'))) {
        $url = sfConfig::get('app_sdInteractiveChart_web_dir') . "/js/interactiveCharts$version.js";
        if (!file_exists(sfConfig::get('sf_web_dir') . $url)) {
            //throw new Exception('Unable to locate SF_WEB_DIR' . $url . ' you need to the run the symfony plugin:publish-assets method.', E_WARNING);
        }
        sfContext::getInstance()->getResponse()->addJavascript($relRoot . $url, 'last');
    } else {
        $url = sfConfig::get('app_sdInteractiveChart_web_dir') . "/js/interactiveCharts$version-min.js";
        sfContext::getInstance()->getResponse()->addJavascript($relRoot . $url, 'last');
    }
}

?>

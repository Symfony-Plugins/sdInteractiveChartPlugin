<?php
/**
 * InteractiveChart Helper Class. Used to create new objects of any of the
 * different chart objects
 *
 * @package    plugins
 * @subpackage sdInteractiveChart
 * @author     Seb Dangerfield - Second2
 * @version    0.4
 */

class InteractiveChart {
    


    static function newBarChart() {
        return new BarGraph();
    }

    static function newLineChart() {
        return new LineGraph();
    }

    static function newAreaChart() {
        //require_once(InteractiveChartRoute . 'sdAxisBaseChart.class.php');
        //require_once(InteractiveChartRoute . 'sdLineGraph.class.php');
        //require_once(InteractiveChartRoute . 'sdAreaGraph.class.php');
        return new AreaGraph();
    }

    static function newColumnChart() {
        return new ColumnGraph();
    }

    static function newGuageChart() {
        return new GaugeGraph();
    }

    static function newPieChart() {
        return new PieGraph();
    }

    static function newTimeLineChart() {
        //require_once(InteractiveChartRoute . 'sdPieGraph.class.php');
        return new AnnotatedTimeLineGraph();
    }



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


function addInteractiveChartJavascript() {
    $version = '0.3.0';
    $ajax_api_url = sfConfig::get('app_sdInteractiveChart_chart_js_url');
    $ajax_api = sfConfig::get('app_sdInteractiveChart_ajax_api');

    if (isset($_SERVER['HTTPS'])) { // Load over SSL if the page is over SSL - Google supports this!
        $ajax_api_url = str_replace ('http:', 'https:', $ajax_api_url);
    }
    sfContext::getInstance()->getResponse()->addJavascript($ajax_api_url . $ajax_api);

    if ((sfConfig::get('sf_environment', 'not set')) || (sfConfig::get('app_sdInteractiveChart_debug_mode'))) {
        $url = sfConfig::get('app_sdInteractiveChart_web_dir') . "/js/interactiveCharts$version.js";
        if (!file_exists(sfConfig::get('sf_web_dir') . $url)) {
            throw new Exception('Unable to locate SF_WEB_DIR' . $url . ' you need to the run the symfony plugin:publish-assets method.', E_WARNING);
        }
        sfContext::getInstance()->getResponse()->addJavascript($url, 'last');
    } else {
        $url = sfConfig::get('app_sdInteractiveChart_web_dir') . "/js/interactiveCharts$version-min.js";
        sfContext::getInstance()->getResponse()->addJavascript($url, 'last');
    }
}

?>

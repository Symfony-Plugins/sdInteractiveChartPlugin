<?php
/**
 * InteractiveChart Helper Class. Used to create new objects of any of the
 * different chart objects
 *
 * @package    plugins
 * @subpackage sdInteractiveChart
 * @author     Seb Dangerfield - Second2
 * @version    0.3
 */

define('InteractiveChartRoute', dirname(__FILE__).'/../');
require_once(InteractiveChartRoute . 'sdBaseChart.class.php');

class InteractiveChart {
    


    static function newBarChart() {
        require_once(InteractiveChartRoute . 'sdAxisBaseChart.class.php');
        require_once(InteractiveChartRoute . 'sdBarGraph.class.php');
        return new BarGraph();
    }

    static function newLineChart() {
        require_once(InteractiveChartRoute . 'sdAxisBaseChart.class.php');
        require_once(InteractiveChartRoute . 'sdLineGraph.class.php');
        return new LineGraph();
    }

    static function newAreaChart() {
        require_once(InteractiveChartRoute . 'sdAxisBaseChart.class.php');
        require_once(InteractiveChartRoute . 'sdLineGraph.class.php');
        require_once(InteractiveChartRoute . 'sdAreaGraph.class.php');
        return new AreaGraph();
    }

    static function newColumnChart() {
        require_once(InteractiveChartRoute . 'sdAxisBaseChart.class.php');
        require_once(InteractiveChartRoute . 'sdBarGraph.class.php');
        require_once(InteractiveChartRoute . 'sdColumnGraph.class.php');
        return new ColumnGraph();
    }

    static function newGuageChart() {
        require_once(InteractiveChartRoute . 'sdGaugeGraph.class.php');
        return new GaugeGraph();
    }

    static function newPieChart() {
        require_once(InteractiveChartRoute . 'sdPieGraph.class.php');
        return new PieGraph();
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

    if (isset($_SERVER['HTTPS']))
        $ajax_api_url = str_replace ('http:', 'https:', $ajax_api_url);
    sfContext::getInstance()->getResponse()->addJavascript($ajax_api_url . $ajax_api);

    if (sfConfig::get('app_sdInteractiveChart_debug_mode')) {
        $url = sfConfig::get('app_sdInteractiveChart_web_dir') . "/js/interactiveCharts$version.js";
        if (isset($_SERVER['HTTPS']))
            $url = str_replace ('http:', 'https:', $url);
        sfContext::getInstance()->getResponse()->addJavascript($url, 'last');
    } else {
        $url = sfConfig::get('app_sdInteractiveChart_web_dir') . "/js/interactiveCharts$version-min.js";
        if (isset($_SERVER['HTTPS']))
            $url = str_replace ('http:', 'https:', $url);
        sfContext::getInstance()->getResponse()->addJavascript($url, 'last');
    }
}

?>

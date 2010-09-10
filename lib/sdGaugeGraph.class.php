<?php
/**
 * sdGaugeGraph - Creates gauge style charts!
 * Google API URL: http://code.google.com/apis/visualization/documentation/gallery/gauge.html
 *
 * @package    plugins
 * @subpackage sdInteractiveChart
 * @author     Seb Dangerfield
 * @version    0.2
 */

class GaugeGraph extends BaseChart {
    protected $greenFrom = null;
    protected $greenTo = null;
    protected $yellowFrom = null;
    protected $yellowTo = null;
    protected $redFrom = null;
    protected $redTo = null;
    protected $min = null;
    protected $max = null;
    protected $minorTicks = null;
    protected $majorTicks = null;
    protected $chartType = 'Gauge';


    public function  __construct() {
        $this->chartPackage = 'gauge';
    }


    

    /**
     * Generates the HTML/JS output required to create the graph when loading the
     * data for the graph via AJAX.
     *
     * @param string The url to call to retrieve the chart data
     * @param array an array of params to pass to the ajax url
     * @param string OPTIONAL the name of the div to render the chart in
     */
    public function ajaxGraph($ajaxUrl, $ajaxParams, $divName = '') {

        // If the limits have been set relative then we better go and replace
        // the values!
        if ($this->greenTo == 'MAX')
                $this->greenTo = $this->max;

        if ($this->redTo == 'MAX')
            $this->redTo = $this->max;

        if ($this->yellowTo == 'MAX')
            $this->yellowTo = $this->max;

        parent::ajaxGraph($ajaxUrl, $ajaxParams, $divName);
    }


    public function setGreenLimits($from, $to = 'MAX') {
        $this->greenFrom = $from;
        $this->greenTo = $to;
    }

    public function setRedLimits($from, $to = 'MAX') {
        $this->redFrom = $from;
        $this->redTo = $to;
    }

    public function setYellowLimits($from, $to = 'MAX') {
        $this->yellowFrom = $from;
        $this->yellowTo = $to;
    }

    public function setLimits($min, $max) {
        $this->min = $min;
        $this->max = $max;
    }

    public function setMajorTicks($majorTicks) {
        $this->majorTicks = $majorTicks;
    }
    
    public function setMinorTicks($minorTicks) {
        $this->minorTicks = $minorTicks;
    }
}

?>

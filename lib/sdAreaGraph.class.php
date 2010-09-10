<?php
/**
 * sdLineChart - Class for creating area graphs. Extends the LineChart
 * Google API: http://code.google.com/apis/visualization/documentation/gallery/areachart.html
 *
 * @package    plugins
 * @subpackage sdInteractiveChart
 * @author     Seb Dangerfield - Second2
 * @version    0.2
 */

class AreaGraph extends LineGraph {
    protected $chartType = 'AreaChart';

    public function  __construct() {
        unset($this->setSmoothLines);
        $this->chartPackage = 'corechart';
    }
}

?>

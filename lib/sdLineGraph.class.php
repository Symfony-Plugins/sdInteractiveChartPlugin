<?php
/**
 * sdLineChart - Class for creating basic line graphs. Use the timeline graph
 * for ploting values over time!
 * Google API: http://code.google.com/apis/visualization/documentation/gallery/linechart.html
 *
 * @package    plugins
 * @subpackage sdInteractiveChart
 * @author     Seb Dangerfield
 * @version    0.2
 */

class LineGraph extends AxisBaseChart {
    protected $chartType = 'LineChart';
    protected $smoothLines = null;
    protected $pointSize = null;

    static $SMOOTH_LINES = 'function';
    static $NO_SMOOTH_LINES = 'none';


    /**
     * Allows the horiziontal axis labels to be drawn at an angle,
     * the default angle given by Google is 30(degrees) but during testing
     * it appeared 45 degrees would be more useful, so this is the default
     * which is used if slanted text is switched on.
     * 
     * @param bool $bool
     * @param int $angle
     */
    public function setSlantedText($bool, $angle = 45) {
        $this->hAxis['slantedText'] = $bool;
        $this->hAxis['slantedTextAngle'] = $angle;
    }


    public function setSmoothLines($value) {
        if ($value == LineGraph::$NO_SMOOTH_LINES)
            $value = null;
        $this->smoothLines = $value;
    }

    public function setPointSize($size) {
        $this->pointSize = $size;
    }

    public function setHorizontalMaxAlternation($maxAlternation) {
        $this->hAxis['maxAlternation'] = $maxAlternation;
    }
    
}

?>

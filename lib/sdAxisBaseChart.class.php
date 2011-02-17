<?php
/**
 * sdAxisBaseChart functions used by all charts that have the usual two
 * axis, used by line, bar, column charts.
 *
 * @package    plugins
 * @subpackage sdInteractiveChart
 * @author     Seb Dangerfield - Second2
 * @version    0.2
 */

class AxisBaseChart extends BaseChart {
    protected $legend = null;
    protected $legendTextStyle = null;
    protected $hAxis = null;
    protected $vAxis = null;
    protected $title = null;
    protected $titleTextStyle = null;
    protected $reverseCategories = null;
    protected $isStacked = null;

    static $LEGEND_RIGHT = 'right';
    static $LEGEND_TOP = 'top';
    static $LEGEND_BOTTOM = 'bottom';
    static $LEGEND_NONE = 'none';

    public function  __construct() {
        $this->chartPackage = 'corechart';
    }


    public function setBaselineColor($color) {
        $this->hAxis['baselineColor'] = $color;
        $this->vAxis['baselineColor'] = $color;
    }

    public function setHorizontalAxisDirection($direction) {
        $this->hAxis['direction'] = $direction;
    }

    public function setAxisTextStyle($color, $fontName = false, $fontSize = false) {
        $this->setHorizontalAxisTextStyle($color, $fontName, $fontSize);
        $this->setVerticalAxisTextStyle($color, $fontName, $fontSize);
    }

    public function setHorizontalAxisTextStyle($color = false, $fontName = false, $fontSize = false) {
        if ($color)
            $this->hAxis['textStyle']['color'] = $color;
        if ($fontName)
            $this->hAxis['textStyle']['fontName'] = $fontName;
        if ($fontSize)
            $this->hAxis['textStyle']['fontSize'] = $fontSize;
    }

    public function setVerticalAxisTextStyle($color = false, $fontName = false, $fontSize = false) {
        if ($color)
            $this->vAxis['textStyle']['color'] = $color;
        if ($fontName)
            $this->vAxis['textStyle']['fontName'] = $fontName;
        if ($fontSize)
            $this->vAxis['textStyle']['fontSize'] = $fontSize;
    }

    public function setHorizontalAxisTitle($title) {
        $this->hAxis['title'] = $title;
    }
    
    public function setVerticalAxisTitle($title) {
        $this->vAxis['title'] = $title;
    }


    /**
     * Sets the title of the chart
     *
     * @param string $title
     */
    public function setTitle($title) {
        $this->title = $title;
    }

    /**
     * @deprecated
     * This function is now deprecated use setLegendPosition and setLegendTextStyle
     * instead
     *
     * @param string $legendType
     * @param array $textStyle - color, fontName and fontSize can be set.
     */
    public function setLegend($legendType, $textStyle = null) {
        $this->setLegendPosition($legendType);
        if ($textStyle != null) {
            $this->setLegendTextStyle(((array_key_exists('color', $textStyle)) ? $textStyle['color'] : false),
                                      ((array_key_exists('fontName', $textStyle)) ? $textStyle['fontName'] : false),
                                      ((array_key_exists('fontSize', $textStyle)) ? $textStyle['fontSize'] : false));
        }
    }

    public function setLegendPosition($legendType) {
        $this->legend = $legendType;
    }

    public function setLegendTextStyle($color = false, $fontName = false, $fontSize = false) {
        if ($color)
            $this->legendTextStyle['color'] = $color;
        if ($fontName)
            $this->legendTextStyle['fontName'] = $fontName;
        if ($fontSize)
            $this->legendTextStyle['fontSize'] = $fontSize;
    }

    public function setTitleTextStyle($color = false, $fontName = false, $fontSize = false) {
        if ($color)
            $this->titleTextStyle['color'] = $color;
        if ($fontName)
            $this->titleTextStyle['fontName'] = $fontName;
        if ($fontSize)
            $this->titleTextStyle['fontSize'] = $fontSize;
    }

    public function setVerticalAxisExtremeValues($min, $max) {
        $this->vAxis['minValue'] = $min;
        $this->vAxis['maxValue'] = $max;
    }

    public function setVerticalAxisLogScale($bool) {
        $this->vAxis['logScale'] = $bool;
    }

    public function setReverseCategories($bool) {
        $this->reverseCategories = $bool;
    }

    public function setIsStacked($bool) {
        $this->isStacked = $bool;
    }

}

?>

<?php
/**
 * sdGaugeGraph - Creates Pie chart (These can be 3D)!
 * Google API URL: http://code.google.com/apis/visualization/documentation/gallery/piechart.html
 *
 * @package    plugins
 * @subpackage sdInteractiveChart
 * @author     Seb Dangerfield
 * @version    0.2
 */


class PieGraph extends BaseChart {
    protected $is3D = null;
    protected $chartType = 'PieChart';
    protected $pieSliceText = null;
    protected $pieSliceTextStyle = null;
    protected $sliceVisibilityThreshold = null;
    protected $reverseCategories = null;
    protected $legend = null;
    protected $legendTextStyle = null;

    static $PIE_TEXT_PERCENTAGE = 'percentage';
    static $PIE_TEXT_VALUE = 'value';
    static $PIE_TEXT_LABEL = 'label';
    static $PIE_TEXT_NONE = 'none';

    public function  __construct() {
        $this->chartPackage = 'corechart';
    }


    public function setPieSliceText($text) {
        $this->pieSliceText = $text;
    }

    public function setPieSliceTextStyle($color = false, $fontName = false, $fontSize = false) {
        if ($color)
            $this->pieSliceTextStyle['color'] = $color;
        if ($fontName)
            $this->pieSliceTextStyle['fontName'] = $fontName;
        if ($fontSize)
            $this->pieSliceTextStyle['fontSize'] = $fontSize;
    }

    public function set3D($bool) {
        $this->is3D = $bool;
    }

    public function setSliceVisibilityThreshold($sliceVisibilityThreshold) {
        $this->sliceVisibilityThreshold = $sliceVisibilityThreshold;
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

    public function setReverseCategories($bool) {
        $this->reverseCategories = $bool;
    }
}

?>

<?php
/**
 * sdBaseChart - parent charting class, all charts extend this class
 *
 * @package    plugins
 * @subpackage sdInteractiveChart
 * @author     Seb Dangerfield
 * @version    0.5.0
 */

class BaseChart {
    const AXIS_TITLES_POS_IN = 'in';
    const AXIS_TITLES_POS_OUT = 'out';
    const AXIS_TITLES_POS_NONE = 'none';
    
    const TITLE_POS_IN = 'in';
    const TITLE_POS_OUT = 'out';
    const TITLE_POS_NONE = 'none';
    
    protected $width = 200;
    protected $height = 150;
    protected $backgroundColor = '#fff';
    protected $url = null;
    protected $ajaxData = null;
    protected $chartPackage = '';

    protected $colors = null;
    protected $div = '';
    protected $html = '';
    protected $callback = null;
    protected $data = "";
    protected $fontSize = null;
    protected $fontName = null;
    private $loadedPackages = array();
    private $noneJavascriptParams = array('html', 'chartPackage', 'loadedPackages');

    /**
     * -- For use when there isn't a dedicated function for the option you want to set.
     * For example to set the data colours use the 'setDataColors()' function instead.
     * Sets an option on the google chart as defined in Google's visualization
     * API documentation, make sure you use exactly the same name (case senstive)
     *
     * Data Types: also make sure you always use the correct data type when
     *             passing values to this function, if Google's API expects an
     *             array give this function an array, it will not work it out for
     *             you an convert.
     *
     * @param string $name
     * @param any $value
     */
    public function setOption($name, $value) {
        $this->{$name} = $value;
    }

    public function setAxisTitlesPosition($position) {
        $this->axisTitlesPosition = $position;
    }
    
    public function setTitlePosition($position) {
        $this->titlePosition = $position;
    }

    /**
     * If you need another javascript function to be called once the graph has
     * succesfully been created and rendered then pass the name of the function
     * to this function.
     *
     * @param string $callback - name of the javascript call back function
     *                           the chart object will be passed to it
     *                           as its only parameter
     */
    public function setCallback($callback) {
        $this->callback = $callback;
    }

    public function setDefaultFont($fontName, $fontSize) {
        $this->fontName = $fontName;
        $this->fontSize = $fontSize;
    }

    public function setDivName($name) {
        $this->div = $name;
    }

    public function setWidthAndHeight($width, $height) {
        $this->width = $width;
        $this->height = $height;
    }

    public function setBackgroundColor($color) {
        $this->backgroundColor = $color;
    }


    public function setDataColors($colors) {
        if (!is_array($colors)) {
            $this->colors = array($colors);
        } else {
            $this->colors = $colors;
        }
    }


    /**
     * Creates the Javascript string required to load in a certain
     * Google visualization package
     *
     * @param string $packageName
     */
    protected function loadPackage($packageName) {
        if (!in_array($packageName, $this->loadedPackages)) {
            $this->html .= "google.load('visualization', '1', {'packages':['$packageName']});";
            array_push($this->loadedPackages, $packageName);
        }
    }


    protected function formatData($data, $labels = null) {
        $addedColumns = false;
        $this->html .= 'var data = new google.visualization.DataTable();' . "\n";
        if ($labels != null)
            $this->html .= "data.addColumn('string','Labels');" . "\n";

        $rowCountAdded = false;
        $row = 0;
        $latestRow = -1;
        foreach($data as $dataText=>$dataSet) {
            if ($latestRow < $row) {
                if (is_string($dataText)) {
                    $this->html .= "data.addColumn('number','".$dataText."');" . "\n";
                } else
                    $this->html .= "data.addColumn('number','total');" . "\n";
                $latestRow = $row;
            }


            if (!$rowCountAdded) {
                if ($dataSet instanceof sfOutputEscaperArrayDecorator) {
                    $total = $dataSet->count();
                } else {
                    $total = (is_array($dataSet)) ? count($dataSet) : count($data);
                }
                $this->html .= 'data.addRows('.$total.');' . "\n";
                // Draw labels
                if ($labels != null) {
                    foreach($labels as $key=>$label) {
                        $this->html .= "data.setValue($key, 0, '{$label}');" . "\n";
                    }
                    $row++;
                }
                $rowCountAdded = true;
            }
            
            if ((!is_array($dataSet)) && !($dataSet instanceof sfOutputEscaperArrayDecorator)) {
                 $this->html .= "data.setValue($dataText, $row, $dataSet);" . "\n";
            } else {
                foreach($dataSet as $key=>$nextRow) {
                    if ((!is_array($nextRow)) && !($nextRow instanceof sfOutputEscaperArrayDecorator)) {
						// If we are given a single element, then we just use it as the standard value
                        $this->html .= "data.setValue($key, $row, $nextRow);" . "\n";
                    } else if (array_key_exists('value', $nextRow)) {
						// If the array contains the key 'value' then we use keys to fetch the value and formatted value
                        $this->html .= sprintf("data.setValue(%s, %s, %s);" . "\n", $key, $row, $nextRow['value']);
                        $this->html .= sprintf("data.setFormattedValue(%s, %s, '%s');" . "\n", $key, $row, $nextRow['formatted_value']);
					} else {
						// If the array doesn't contain the key 'value' then we use numerical keys to fetch the value and formatted value.
						$this->html .= sprintf("data.setValue(%s, %s, %s);" . "\n", $key, $row, $nextRow[0]);
						$this->html .= sprintf("data.setFormattedValue(%s, %s, '%s');" . "\n", $key, $row, $nextRow[1]);
					}
                }
                $row++;
            }
        }
    }


    /**
     * Generates the HTML/JS output required to create the graph while loading
     * the data for the graph from a randomly named javascript function.
     *
     * @param array $data - array of data to be used for the chart
     * @param array $labels - labels to use on the chart
     * @param string $divName - OPTIONAL name of the div to draw the chart in
     */
    public function inlineGraph($data, $labels, $divName = '') {
        $functionName = 'produceData' . InteractiveChart::$dataFunctionCount++;
        $this->html .= '<script type="text/javascript">'  . "\n";
            $this->html .= "function $functionName() {" . "\n";
                $this->html .= $this->formatData($data, $labels);
                $this->html .= 'return data;';
            $this->html .= '}';
        $this->html .= '</script>';
        $this->data = $functionName;

        $this->ajaxGraph('', array(), $divName);
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
        if ($divName != '')
            $this->setDivName ($divName);

        $this->url = $ajaxUrl;
        $this->ajaxData = $ajaxParams;

        $this->html .= '<script type="text/javascript">'  . "\n";
        $this->loadPackage($this->chartPackage);
        $this->html .= 'CHARTS.toCreate.push({';

        foreach ($this as $key => $propety) {
            if ((in_array($key, $this->noneJavascriptParams)) || ($key == 'noneJavascriptParams'))
                continue;
            if ((($key == 'callback') || ($key == 'data')) && ($propety != null)) {
                $this->html .= "$key: $propety,";
            } else if (is_string($propety)) {
                $this->html .= "$key: '$propety',";
            } else if (is_int($propety)) {
                $this->html .= "$key: $propety,";
            } else if (is_bool($propety)) {
                $propety = ($propety) ? 'true' : 'false';
                $this->html .= "$key: $propety,";
            } else if (is_array($propety)) {
                $this->html .= "$key: " . json_encode($propety) . ',';
            }
        }
        $this->html = trim($this->html, ',');
        $this->html .= '});';
        $this->html .= '</script>';
    }


    /**
     * Does what it says on the tin. echo's the HTML/JS required to draw the chart!
     *
     * @param Bool return HTML or echo output
     * @return void or string HTML
     */
    public function render($returnHTML = false) {
        if ($returnHTML)
            return $this->html;
        else
            echo $this->html;
    }


}

?>

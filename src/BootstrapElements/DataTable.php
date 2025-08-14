<?php
namespace Den\BootstrapElements;

abstract class AbstractDataTable
{
    public $options = null;
    public function __construct($options = null)
    {
        $this->options = is_array($options) ? $options : array();
    }
    abstract public function toHtml();
    /**
     * @return static
     */
    public static function newObj($options = null)
    {
        return (new static($options));
    }
    public static function render($options)
    {
        return (new static($options))->toHtml();
    }
}

class DataTable extends AbstractDataTable
{
    public function toHtml()
    {
        $caps = isset($this->options['caps']) ? (array) $this->options['caps'] : [];
        $id = isset($this->options['id']) ? (string) $this->options['id'] : 'datatable-' . uniqid();
        $class = isset($this->options['class']) ? (string) $this->options['class'] : 'table table-striped table-bordered';
        $responsive = isset($this->options['responsive']) ? (bool) $this->options['responsive'] : true;
        $searching = isset($this->options['searching']) ? (bool) $this->options['searching'] : true;
        $ordering = isset($this->options['ordering']) ? (bool) $this->options['ordering'] : true;
        $paging = isset($this->options['paging']) ? (bool) $this->options['paging'] : true;
        $pageLength = isset($this->options['pageLength']) ? (int) $this->options['pageLength'] : 10;
        $language = isset($this->options['language']) ? (string) $this->options['language'] : 'tr';
        
        // Generate table headers
        $headersHtml = '';
        foreach ($caps as $cap) {
            $safeCap = htmlspecialchars($cap, ENT_QUOTES, 'UTF-8');
            $headersHtml .= '<th>' . $safeCap . '</th>';
        }
        
        // Generate table body (empty for now, can be populated via JavaScript)
        $bodyHtml = '<tbody></tbody>';
        
        // Generate DataTable initialization JavaScript
        $jsOptions = [
            'searching' => $searching,
            'ordering' => $ordering,
            'paging' => $paging,
            'pageLength' => $pageLength,
            'language' => [
                'url' => '//cdn.datatables.net/plug-ins/1.13.7/i18n/' . $language . '.json'
            ]
        ];
        
        $jsOptionsJson = json_encode($jsOptions, JSON_UNESCAPED_SLASHES);
        
        // CDN links
        $cdnLinks = '
        <!-- DataTables CSS -->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
        
        <!-- DataTables JavaScript -->
        <script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
        ';
        
        // DataTable initialization script
        $initScript = '
        <script type="text/javascript">
        $(document).ready(function() {
            $("#' . $id . '").DataTable(' . $jsOptionsJson . ');
        });
        </script>
        ';
        
        // Build the complete HTML
        $tableHtml = '';
        if ($responsive) {
            $tableHtml .= '<div class="table-responsive">';
        }
        
        $tableHtml .= '<table id="' . htmlspecialchars($id, ENT_QUOTES, 'UTF-8') . '" class="' . htmlspecialchars($class, ENT_QUOTES, 'UTF-8') . '" style="width:100%">';
        $tableHtml .= '<thead><tr>' . $headersHtml . '</tr></thead>';
        $tableHtml .= $bodyHtml;
        $tableHtml .= '</table>';
        
        if ($responsive) {
            $tableHtml .= '</div>';
        }
        
        return $cdnLinks . $tableHtml . $initScript;
    }
    
    public function setCaps($caps)
    {
        $this->options['caps'] = is_array($caps) ? $caps : [$caps];
        return $this;
    }
    
    public function setId($id)
    {
        $this->options['id'] = $id;
        return $this;
    }
    
    public function setClass($class)
    {
        $this->options['class'] = $class;
        return $this;
    }
    
    public function setResponsive($responsive)
    {
        $this->options['responsive'] = $responsive;
        return $this;
    }
    
    public function setSearching($searching)
    {
        $this->options['searching'] = $searching;
        return $this;
    }
    
    public function setOrdering($ordering)
    {
        $this->options['ordering'] = $ordering;
        return $this;
    }
    
    public function setPaging($paging)
    {
        $this->options['paging'] = $paging;
        return $this;
    }
    
    public function setPageLength($pageLength)
    {
        $this->options['pageLength'] = $pageLength;
        return $this;
    }
    
    public function setLanguage($language)
    {
        $this->options['language'] = $language;
        return $this;
    }
}
?>
<?php
namespace Den\DataTable;

use Den\AbstractWidget;

class DataTableJs extends AbstractWidget
{
    public function toHtml()
    {
        $caps = isset($this->options['caps']) ? (array) $this->options['caps'] : [];
        $data = isset($this->options['data']) ? (array) $this->options['data'] : [];
        $id = isset($this->options['id']) ? (string) $this->options['id'] : 'datatable-' . uniqid();
        $class = isset($this->options['class']) ? (string) $this->options['class'] : 'table table-hover table-striped align-middle mb-0';
        $responsive = isset($this->options['responsive']) ? (bool) $this->options['responsive'] : true;
        $searching = isset($this->options['searching']) ? (bool) $this->options['searching'] : true;
        $ordering = isset($this->options['ordering']) ? (bool) $this->options['ordering'] : true;
        $paging = isset($this->options['paging']) ? (bool) $this->options['paging'] : true;
        $pageLength = isset($this->options['pageLength']) ? (int) $this->options['pageLength'] : 10;
        $language = isset($this->options['language']) ? (string) $this->options['language'] : 'tr';
        $textColor = isset($this->options['textColor']) ? (string) $this->options['textColor'] : 'text-dark';


        // Generate table headers with Bootstrap styling
        $headersHtml = '';
        foreach ($caps as $cap) {
            $safeCap = htmlspecialchars($cap, ENT_QUOTES, 'UTF-8');
            $headersHtml .= '<th class="fw-semibold text-uppercase small border-0 py-3 px-3 bg-light text-secondary">' . $safeCap . '</th>';
        }

        // Generate table body with data and Bootstrap styling
        $bodyHtml = '<tbody class="' . htmlspecialchars($textColor, ENT_QUOTES, 'UTF-8') . '">';
        foreach ($data as $rowIndex => $row) {
            $bodyHtml .= '<tr class="transition-all">';
            if (is_array($row)) {
                foreach ($row as $cell) {
                    $safeCell = htmlspecialchars($cell, ENT_QUOTES, 'UTF-8');
                    $bodyHtml .= '<td class="py-3 px-3 border-0 align-middle">' . $safeCell . '</td>';
                }
            } else {
                $safeRow = htmlspecialchars($row, ENT_QUOTES, 'UTF-8');
                $bodyHtml .= '<td class="py-3 px-3 border-0 align-middle">' . $safeRow . '</td>';
            }
            $bodyHtml .= '</tr>';
        }
        $bodyHtml .= '</tbody>';
        $jsOptions = [
            'processing' => true,
            'serverSide' => true,
            'searchDelay' => 500,
            'ajax' => route('sent.invoices.ajax'),
            'searching' => $searching,
            'ordering' => $ordering,
            'paging' => $paging,
            'pageLength' => $pageLength,
        ];

        if ($language === 'tr') {
            $jsOptions['language'] = [
                'decimal' => ',',
                'thousands' => '.',
                'search' => 'Ara:',
                'lengthMenu' => '_MENU_ kayıt göster',
                'info' => '_TOTAL_ kayıttan _START_ - _END_ arası gösteriliyor',
                'infoEmpty' => 'Kayıt yok',
                'infoFiltered' => '(_MAX_ kayıt içerisinden bulunan)',
                'loadingRecords' => 'Yükleniyor...',
                'processing' => 'İşleniyor...',
                'zeroRecords' => 'Eşleşen kayıt bulunamadı',
                'paginate' => [
                    'first' => 'İlk',
                    'previous' => 'Önceki',
                    'next' => 'Sonraki',
                    'last' => 'Son'
                ],
                'aria' => [
                    'sortAscending' => ': artan sütun sıralamasını aktifleştir',
                    'sortDescending' => ': azalan sütun sıralamasını aktifleştir'
                ]
            ];
        }

        $jsOptionsJson = json_encode($jsOptions, JSON_UNESCAPED_SLASHES);

        // CDN links
        $cdnLinks = '
        <!-- jQuery (required for DataTables) -->
        <script type="text/javascript" src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        
        <!-- DataTables CSS -->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
        
        <!-- DataTables JavaScript -->
        <script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
        ';

        $initScript = '
        <script type="text/javascript">
        $(document).ready(function() {
            $("#' . $id . '").DataTable(' . $jsOptionsJson . ');
        });
        </script>
        ';

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

    public function setData($data)
    {
        $this->options['data'] = is_array($data) ? $data : [$data];
        return $this;
    }

    public function addRow($row)
    {
        if (!isset($this->options['data'])) {
            $this->options['data'] = [];
        }
        $this->options['data'][] = $row;
        return $this;
    }

    public function addRows($rows)
    {
        if (!isset($this->options['data'])) {
            $this->options['data'] = [];
        }
        if (is_array($rows)) {
            $this->options['data'] = array_merge($this->options['data'], $rows);
        }
        return $this;
    }

    public function clearData()
    {
        $this->options['data'] = [];
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

    public function setTextColor($textColor)
    {
        $this->options['textColor'] = $textColor;
        return $this;
    }

    public function setBorderColor($borderColor)
    {
        $this->options['borderColor'] = $borderColor;
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
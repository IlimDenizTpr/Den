<?php
namespace Den\BootstrapElements;

abstract class AbstractWidget
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
class WidgetCard extends AbstractWidget
{
    public function toHtml()
    {
        $title = isset($this->options['title']) ? (string) $this->options['title'] : null;
        $text = isset($this->options['text']) ? (string) $this->options['text'] : null;
        $btnText = isset($this->options['buttonText']) ? (string) $this->options['buttonText'] : null;
        $btnUrl = isset($this->options['buttonUrl']) ? (string) $this->options['buttonUrl'] : null;
        $textColor = isset($this->options['textColor']) ? (string) $this->options['textColor'] : '#000';
        $icon = isset($this->options['icon']) ? (string) $this->options['icon'] : null;
        $number = isset($this->options['number']) ? (string) $this->options['number'] : null;
        $margin = isset($this->options['margin']) ? (array) $this->options['margin'] : null;
        $bg1 = isset($this->options['bg1']) ? trim((string) $this->options['bg1']) : '';
        $bg2 = isset($this->options['bg2']) ? trim((string) $this->options['bg2']) : '';
        $degRaw = isset($this->options['deg']) ? (string) $this->options['deg'] : (isset($this->options['degree']) ? (string) $this->options['degree'] : (isset($this->options['derece']) ? (string) $this->options['derece'] : ''));
        $columns = isset($this->options['columns']) ? (array) $this->options['columns'] : [];
        $alerts = isset($this->options['alerts']) ? (array) $this->options['alerts'] : null;

        $backgroundCss = '';
        if ($bg1 !== '') {
            if ($bg2 !== '') {
                $degreeNum = is_numeric($degRaw) ? (float) $degRaw : 90.0;
                if ($degreeNum < 0) {
                    $degreeNum = 0;
                }
                if ($degreeNum > 360) {
                    $degreeNum = 360;
                }
                $degStr = rtrim(rtrim(sprintf('%.2f', $degreeNum), '0'), '.');
                $backgroundCss = 'linear-gradient(' . $degStr . 'deg, ' . $bg1 . ', ' . $bg2 . ')';
            } else {
                $backgroundCss = $bg1;
            }
        }

        $marginCss = '';
        if ($margin) {
            if (isset($margin['top']) && $margin['top'] != 0) {
                $marginCss .= 'margin-top: ' . $margin['top'] . 'px;';
            }
            if (isset($margin['left']) && $margin['left'] != 0) {
                $marginCss .= 'margin-left: ' . $margin['left'] . 'px;';
            }
            if (isset($margin['bottom']) && $margin['bottom'] != 0) {
                $marginCss .= 'margin-bottom: ' . $margin['bottom'] . 'px;';
            }
            if (isset($margin['right']) && $margin['right'] != 0) {
                $marginCss .= 'margin-right: ' . $margin['right'] . 'px;';
            }
        }
        $safeTitle = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
        $safeText = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
        $safeBtnText = htmlspecialchars($btnText, ENT_QUOTES, 'UTF-8');
        $safeBtnUrl = htmlspecialchars($btnUrl, ENT_QUOTES, 'UTF-8');
        $safeTextColor = htmlspecialchars($textColor, ENT_QUOTES, 'UTF-8');
        $safeBackground = htmlspecialchars($backgroundCss, ENT_QUOTES, 'UTF-8');
        $safeIcon = htmlspecialchars($icon, ENT_QUOTES, 'UTF-8');
        $safeNumber = htmlspecialchars($number, ENT_QUOTES, 'UTF-8');
        $safeMargin = htmlspecialchars($marginCss, ENT_QUOTES, 'UTF-8');

        if ($btnText && $btnUrl) {
            $btnHtml = '<a href="' . $safeBtnUrl . '" class="btn btn-outline-light btn-sm rounded-pill" style="color: ' . $safeTextColor . '; border-color: rgba(255,255,255,0.3);">' . $safeBtnText . '</a>';
        } else {
            $btnHtml = '';
        }

        $iconHtml = '';
        if ($safeIcon !== '') {
            $iconHtml = '<div class="d-flex align-items-center justify-content-center rounded-circle me-3" style="width: 60px; height: 60px; background: rgba(255,255,255,0.15);"><i class="' . $safeIcon . ' fs-2" style="color: ' . $safeTextColor . '; opacity: 0.9;"></i></div>';
        }

        $numberHtml = '';
        if ($safeNumber !== '') {
            $numberHtml = '<div class="text-end flex-shrink-0"><div style="color: ' . $safeTextColor . '; text-shadow: 0 2px 4px rgba(0,0,0,0.1); font-size: 5rem; font-weight: 700; line-height: 1;">' . $safeNumber . '</div></div>';
        }

        $columnsHtml = '';
        if (isset($this->options['columns']) && is_array($this->options['columns'])) {
            $columnsHtml = '<div class="row mt-3">';
            foreach ($columns as $column) {
                $columnText = isset($column['title']) ? htmlspecialchars($column['title'], ENT_QUOTES, 'UTF-8') : '';
                $columnNumber = isset($column['text']) ? htmlspecialchars($column['text'], ENT_QUOTES, 'UTF-8') : '';

                $columnsHtml .= '<div class="col-lg-6 mb-2">'
                    . '<div class="d-flex justify-content-between align-items-center p-2" style="background: rgba(255,255,255,0.1); border-radius: 8px;">'
                    . '<span style="color: ' . $safeTextColor . '; font-size: 0.9rem;">' . $columnText . '</span>'
                    . '<span style="color: ' . $safeTextColor . '; font-size: 1.2rem; font-weight: 600;">' . $columnNumber . '</span>'
                    . '</div>'
                    . '</div>';
            }
            $columnsHtml .= '</div>';
        }

        $alertsHtml = '';
        if ($alerts) {
            foreach ($alerts as $alert) {
                $alertType = isset($alert['type']) ? htmlspecialchars($alert['type'], ENT_QUOTES, 'UTF-8') : 'info';
                $alertText = isset($alert['text']) ? htmlspecialchars($alert['text'], ENT_QUOTES, 'UTF-8') : '';
                $alertsHtml .= '<div class="alert alert-' . $alertType . ' mt-3" role="alert">' . $alertText . '</div>';
            }
        }
        return '<div class="card w-100 border-0 rounded-3 shadow-lg overflow-hidden position-relative" style="background: ' . $safeBackground . '; backdrop-filter: blur(10px); transition: all 0.3s ease; transform: translateY(0); ' . $safeMargin . '" onmouseover="this.style.transform=\'translateY(-8px)\'; this.style.boxShadow=\'0 12px 40px rgba(0,0,0,0.15)\'" onmouseout="this.style.transform=\'translateY(0)\'; this.style.boxShadow=\'0 8px 32px rgba(0,0,0,0.1)\'">'
            . '<div class="card-body p-4 position-relative" style="z-index: 2;">'
            . '<div class="d-flex align-items-center justify-content-between">'
            . '<div class="d-flex align-items-center flex-grow-1">'
            . $iconHtml
            . '<div class="flex-grow-1">'
            . '<h5 class="card-title h5 fw-semibold mb-2" style="color: ' . $safeTextColor . '; text-shadow: 0 1px 2px rgba(0,0,0,0.1);">' . $safeTitle . '</h5>'
            . '<p class="card-text small mb-2" style="color: ' . $safeTextColor . '; opacity: 0.85; line-height: 1.4;">' . $safeText . '</p>'
            . $btnHtml
            . '</div>'
            . '</div>'
            . $numberHtml
            . '</div>'
            . $columnsHtml
            . $alertsHtml
            . '</div>'
            . '<div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%); pointer-events: none; z-index: 1;"></div>'
            . '</div>';
    }
    public function setTitle($title)
    {
        $this->options["title"] = $title;
        return $this;
    }
    public function setText($text)
    {
        $this->options["text"] = $text;
        return $this;
    }
    public function setButtonText($btnText)
    {
        $this->options["buttonText"] = $btnText;
        return $this;
    }
    public function setButtonUrl($btnUrl)
    {
        $this->options["buttonUrl"] = $btnUrl;
        return $this;
    }
    public function setTextColor($textColor)
    {
        $this->options["textColor"] = $textColor;
        return $this;
    }
    public function setBg1($bg1)
    {
        $this->options["bg1"] = $bg1;
        return $this;
    }
    public function setBg2($bg2)
    {
        $this->options["bg2"] = $bg2;
        return $this;
    }
    public function setDegree($degree)
    {
        $this->options["degree"] = $degree;
        return $this;
    }
    public function setIcon($icon)
    {
        $this->options["icon"] = $icon;
        return $this;
    }
    public function setNumber($number)
    {
        $this->options["number"] = $number;
        return $this;
    }

    public function setMargin($top = 0, $left = 0, $bottom = 0, $right = 0)
    {
        $this->options["margin"] = [
            'top' => $top,
            'left' => $left,
            'bottom' => $bottom,
            'right' => $right
        ];
        return $this;
    }

    public function appendColumn($title, $text)
    {
        if (!isset($this->options['columns'])) {
            $this->options['columns'] = [];
        }
        $this->options['columns'][] = [
            'title' => $title,
            'text' => $text
        ];
        return $this;
    }

    public function appendAlert($type, $text)
    {
        if (!isset($this->options['alerts'])) {
            $this->options['alerts'] = [];
        }
        $this->options['alerts'][] = [
            'type' => $type,
            'text' => $text
        ];
        return $this;
    }

}
?>
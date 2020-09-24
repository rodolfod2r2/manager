<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 28/12/2017
 * Time: 15:32
 */

namespace application\assets\fpdf;


class FpdfTpl extends Fpdf {

    protected $templates = [];
    protected $currentTemplateId;
    protected $templateId = 0;

    public function useTemplate($tpl, $x = 0, $y = 0, $width = null, $height = null, $adjustPageSize = false) {
        if (!isset($this->templates[$tpl])) {
            throw new \InvalidArgumentException('Template does not exist!');
        }

        if (\is_array($x)) {
            unset($x['tpl']);
            \extract($x, EXTR_IF_EXISTS);
            /** @noinspection NotOptimalIfConditionsInspection */
            /** @noinspection CallableParameterUseCaseInTypeContextInspection */
            if (\is_array($x)) {
                $x = 0;
            }
        }

        $template = $this->templates[$tpl];

        $originalSize = $this->getTemplateSize($tpl);
        $newSize = $this->getTemplateSize($tpl, $width, $height);
        if ($adjustPageSize) {
            $this->setPageFormat($newSize, $newSize['orientation']);
        }

        $this->_out(
        // reset standard values, translate and scale
            \sprintf(
                'q 0 J 1 w 0 j 0 G 0 g %.4F 0 0 %.4F %.4F %.4F cm /%s Do Q',
                ($newSize['width'] / $originalSize['width']),
                ($newSize['height'] / $originalSize['height']),
                $x * $this->k,
                ($this->h - $y - $newSize['height']) * $this->k,
                $template['id']
            )
        );

        return $newSize;
    }

    public function getTemplateSize($tpl, $width = null, $height = null) {
        if (!isset($this->templates[$tpl])) {
            return false;
        }

        if ($width === null && $height === null) {
            $width = $this->templates[$tpl]['width'];
            $height = $this->templates[$tpl]['height'];
        } elseif ($width === null) {
            $width = $height * $this->templates[$tpl]['width'] / $this->templates[$tpl]['height'];
        }

        if ($height === null) {
            $height = $width * $this->templates[$tpl]['height'] / $this->templates[$tpl]['width'];
        }

        if ($height <= 0. || $width <= 0.) {
            throw new \InvalidArgumentException('Width or height parameter needs to be larger than zero.');
        }

        return [
            'width' => $width,
            'height' => $height,
            0 => $width,
            1 => $height,
            'orientation' => $width > $height ? 'L' : 'P'
        ];
    }

    public function setPageFormat($size, $orientation) {
        if (!\in_array($orientation, ['P', 'L'], true)) {
            throw new \InvalidArgumentException(\sprintf(
                'Invalid page orientation "%s"! Only "P" and "L" are allowed!',
                $orientation
            ));
        }

        $size = $this->_getpagesize($size);

        if ($orientation != $this->CurOrientation
            || $size[0] != $this->CurPageSize[0]
            || $size[1] != $this->CurPageSize[1]
        ) {
            // New size or orientation
            if ($orientation === 'P') {
                $this->w = $size[0];
                $this->h = $size[1];
            } else {
                $this->w = $size[1];
                $this->h = $size[0];
            }
            $this->wPt = $this->w * $this->k;
            $this->hPt = $this->h * $this->k;
            $this->PageBreakTrigger = $this->h - $this->bMargin;
            $this->CurOrientation = $orientation;
            $this->CurPageSize = $size;

            $this->PageInfo[$this->page]['size'] = array($this->wPt, $this->hPt);
        }
    }

    public function _out($s) {
        if ($this->currentTemplateId !== null) {
            $this->templates[$this->currentTemplateId]['buffer'] .= $s . "\n";
        } else {
            parent::_out($s);
        }
    }

    public function beginTemplate($width = null, $height = null) {
        if ($width === null) {
            $width = $this->w;
        }

        if ($height === null) {
            $height = $this->h;
        }

        $templateId = $this->getNextTemplateId();

        // initiate buffer with current state of FPDF
        $buffer = "2 J\n"
            . \sprintf('%.2F w', $this->LineWidth * $this->k) . "\n";

        if ($this->FontFamily) {
            $buffer .= \sprintf("BT /F%d %.2F Tf ET\n", $this->CurrentFont['i'], $this->FontSizePt);
        }

        if ($this->DrawColor !== '0 G') {
            $buffer .= $this->DrawColor . "\n";
        }
        if ($this->FillColor !== '0 g') {
            $buffer .= $this->FillColor . "\n";
        }

        $this->templates[$templateId] = [
            'objectNumber' => null,
            'id' => 'TPL' . $templateId,
            'buffer' => $buffer,
            'width' => $width,
            'height' => $height,
            'state' => [
                'x' => $this->x,
                'y' => $this->y,
                'AutoPageBreak' => $this->AutoPageBreak,
                'bMargin' => $this->bMargin,
                'tMargin' => $this->tMargin,
                'lMargin' => $this->lMargin,
                'rMargin' => $this->rMargin,
                'h' => $this->h,
                'w' => $this->w,
                'FontFamily' => $this->FontFamily,
                'FontStyle' => $this->FontStyle,
                'FontSizePt' => $this->FontSizePt,
                'FontSize' => $this->FontSize
            ]
        ];

        $this->SetAutoPageBreak(false);
        $this->currentTemplateId = $templateId;

        $this->h = $height;
        $this->w = $width;

        $this->SetXY($this->lMargin, $this->tMargin);
        $this->SetRightMargin($this->w - $width + $this->rMargin);

        return $templateId;
    }

    protected function getNextTemplateId() {
        return $this->templateId++;
    }

    /**
     * Ends a template.
     *
     * @return bool|int|null A template identifier.
     */
    public function endTemplate() {
        if (null === $this->currentTemplateId) {
            return false;
        }

        $templateId = $this->currentTemplateId;
        $template = $this->templates[$templateId];

        $state = $template['state'];
        $this->SetXY($state['x'], $state['y']);
        $this->tMargin = $state['tMargin'];
        $this->lMargin = $state['lMargin'];
        $this->rMargin = $state['rMargin'];
        $this->h = $state['h'];
        $this->w = $state['w'];
        $this->SetAutoPageBreak($state['AutoPageBreak'], $state['bMargin']);

        $this->FontFamily = $state['FontFamily'];
        $this->FontStyle = $state['FontStyle'];
        $this->FontSizePt = $state['FontSizePt'];
        $this->FontSize = $state['FontSize'];

        $fontKey = $this->FontFamily . $this->FontStyle;
        if ($fontKey) {
            $this->CurrentFont =& $this->fonts[$fontKey];
        } else {
            unset($this->CurrentFont);
        }

        $this->currentTemplateId = null;

        return $templateId;
    }

    public function Link($x, $y, $w, $h, $link) {
        if ($this->currentTemplateId !== null) {
            throw new \BadMethodCallException('Links cannot be set when writing to a template.');
        }
        parent::Link($x, $y, $w, $h, $link);
    }

    public function SetLink($link, $y = 0, $page = -1) {
        if ($this->currentTemplateId !== null) {
            throw new \BadMethodCallException('Links cannot be set when writing to a template.');
        }
        return parent::SetLink($link, $y, $page);
    }

    public function SetDrawColor($r, $g = null, $b = null) {
        parent::SetDrawColor($r, $g, $b);
        if ($this->page === 0 && $this->currentTemplateId !== null) {
            $this->_out($this->DrawColor);
        }
    }

    public function SetFillColor($r, $g = null, $b = null) {
        parent::SetFillColor($r, $g, $b);
        if ($this->page === 0 && $this->currentTemplateId !== null) {
            $this->_out($this->FillColor);
        }
    }

    public function SetLineWidth($width) {
        parent::SetLineWidth($width);
        if ($this->page === 0 && $this->currentTemplateId !== null) {
            $this->_out(\sprintf('%.2F w', $width * $this->k));
        }
    }

    public function SetFont($family, $style = '', $size = 0) {
        parent::SetFont($family, $style, $size);
        if ($this->page === 0 && $this->currentTemplateId !== null) {
            $this->_out(\sprintf('BT /F%d %.2F Tf ET', $this->CurrentFont['i'], $this->FontSizePt));
        }
    }

    public function SetFontSize($size) {
        parent::SetFontSize($size);
        if ($this->page === 0 && $this->currentTemplateId !== null) {
            $this->_out(sprintf('BT /F%d %.2F Tf ET', $this->CurrentFont['i'], $this->FontSizePt));
        }
    }

    protected function _putimages() {
        parent::_putimages();

        foreach ($this->templates as $key => $template) {
            $this->_newobj();
            $this->templates[$key]['objectNumber'] = $this->n;

            $this->_put('<</Type /XObject /Subtype /Form /FormType 1');
            $this->_put(\sprintf('/BBox[0 0 %.2F %.2F]', $template['width'] * $this->k, $template['height'] * $this->k));
            $this->_put('/Resources 2 0 R'); // default resources dictionary of FPDF

            if ($this->compress) {
                $buffer = \gzcompress($template['buffer']);
                $this->_put('/Filter/FlateDecode');
            } else {
                $buffer = $template['buffer'];
            }

            $this->_put('/Length ' . \strlen($buffer));
            $this->_put('>>');
            $this->_putstream($buffer);
            $this->_put('endobj');
        }
    }

    protected function _putxobjectdict() {
        foreach ($this->templates as $key => $template) {
            $this->_put('/' . $template['id'] . ' ' . $template['objectNumber'] . ' 0 R');
        }

        parent::_putxobjectdict();
    }

}
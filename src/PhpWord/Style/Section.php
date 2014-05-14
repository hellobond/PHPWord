<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Style;

use PhpOffice\PhpWord\Style\LineNumbering;

/**
 * Section settings
 */
class Section extends Border
{
    /**
     * Page orientation
     *
     * @const string
     */
    const ORIENTATION_PORTRAIT = 'portrait';
    const ORIENTATION_LANDSCAPE = 'landscape';

    /**
     * Page default constants
     *
     * @const int|float
     */
    const DEFAULT_WIDTH = 11906; // In twip
    const DEFAULT_HEIGHT = 16838; // In twip
    const DEFAULT_MARGIN = 1440; // In twip
    const DEFAULT_GUTTER = 0; // In twip
    const DEFAULT_HEADER_HEIGHT = 720; // In twip
    const DEFAULT_FOOTER_HEIGHT = 720; // In twip
    const DEFAULT_COLUMN_COUNT = 1;
    const DEFAULT_COLUMN_SPACING = 720; // In twip

    /**
     * Page Orientation
     *
     * @var string
     * @link http://www.schemacentral.com/sc/ooxml/a-w_orient-1.html
     */
    private $orientation = self::ORIENTATION_PORTRAIT;

    /**
     * Page Size Width
     *
     * @var int|float
     */
    private $pageSizeW = self::DEFAULT_WIDTH;

    /**
     * Page Size Height
     *
     * @var int|float
     */
    private $pageSizeH = self::DEFAULT_HEIGHT;

    /**
     * Top margin spacing
     *
     * @var int|float
     */
    private $marginTop = self::DEFAULT_MARGIN;

    /**
     * Left margin spacing
     *
     * @var int|float
     */
    private $marginLeft = self::DEFAULT_MARGIN;

    /**
     * Right margin spacing
     *
     * @var int|float
     */
    private $marginRight = self::DEFAULT_MARGIN;

    /**
     * Bottom margin spacing
     *
     * @var int|float
     */
    private $marginBottom = self::DEFAULT_MARGIN;

    /**
     * Page gutter spacing
     *
     * @var int|float
     * @link http://www.schemacentral.com/sc/ooxml/e-w_pgMar-1.html
     */
    private $gutter = self::DEFAULT_GUTTER;

    /**
     * Header height
     *
     * @var int|float
     */
    private $headerHeight = self::DEFAULT_HEADER_HEIGHT;

    /**
     * Footer height
     *
     * @var int|float
     */
    private $footerHeight = self::DEFAULT_FOOTER_HEIGHT;

    /**
     * Page Numbering Start
     *
     * @var int
     */
    private $pageNumberingStart;

    /**
     * Section columns count
     *
     * @var int
     */
    private $colsNum = self::DEFAULT_COLUMN_COUNT;

    /**
     * Section spacing between columns
     *
     * @var int|float
     */
    private $colsSpace = self::DEFAULT_COLUMN_SPACING;

    /**
     * Section break type
     *
     * Options:
     * - nextPage: Next page section break
     * - nextColumn: Column section break
     * - continuous: Continuous section break
     * - evenPage: Even page section break
     * - oddPage: Odd page section break
     *
     * @var string
     */
    private $breakType;

    /**
     * Line numbering
     *
     * @var \PhpOffice\PhpWord\Style\LineNumbering
     * @link http://www.schemacentral.com/sc/ooxml/e-w_lnNumType-1.html
     */
    private $lineNumbering;

    /**
     * Set Setting Value
     *
     * @param string $key
     * @param string $value
     * @return self
     */
    public function setSettingValue($key, $value)
    {
        return $this->setStyleValue($key, $value);
    }

    /**
     * Set orientation
     *
     * @param string $value
     * @return self
     */
    public function setOrientation($value = null)
    {
        $enum = array(self::ORIENTATION_PORTRAIT, self::ORIENTATION_LANDSCAPE);
        $this->orientation = $this->setEnumVal($value, $enum, $this->orientation);
        $longSize = $this->pageSizeW >= $this->pageSizeH ? $this->pageSizeW : $this->pageSizeH;
        $shortSize = $this->pageSizeW < $this->pageSizeH ? $this->pageSizeW : $this->pageSizeH;

        if ($this->orientation == self::ORIENTATION_PORTRAIT) {
            $this->pageSizeW = $shortSize;
            $this->pageSizeH = $longSize;
        } else {
            $this->pageSizeW = $longSize;
            $this->pageSizeH = $shortSize;
        }

        return $this;
    }

    /**
     * Get Page Orientation
     *
     * @return string
     */
    public function getOrientation()
    {
        return $this->orientation;
    }

    /**
     * Set Portrait Orientation
     *
     * @return self
     */
    public function setPortrait()
    {
        return $this->setOrientation(self::ORIENTATION_PORTRAIT);
    }

    /**
     * Set Landscape Orientation
     *
     * @return self
     */
    public function setLandscape()
    {
        return $this->setOrientation(self::ORIENTATION_LANDSCAPE);
    }

    /**
     * Get Page Size Width
     *
     * @return int|float
     */
    public function getPageSizeW()
    {
        return $this->pageSizeW;
    }

    /**
     * Set Page Size Width
     *
     * @return int|float
     */
    public function setPageSizeW($value = '')
    {
        $this->pageSizeW = $this->setNumericVal($value, self::DEFAULT_WIDTH);

        return $this;
    }

    /**
     * Get Page Size Height
     *
     * @return int|float
     */
    public function getPageSizeH()
    {
        return $this->pageSizeH;
    }

    /**
     * Set Page Size Height
     *
     * @return int|float
     */
    public function setPageSizeH($value = '')
    {
        $this->pageSizeH = $this->setNumericVal($value, self::DEFAULT_HEIGHT);

        return $this;
    }

    /**
     * Get Margin Top
     *
     * @return int|float
     */
    public function getMarginTop()
    {
        return $this->marginTop;
    }

    /**
     * Set Margin Top
     *
     * @param int|float $value
     * @return self
     */
    public function setMarginTop($value = '')
    {
        $this->marginTop = $this->setNumericVal($value, self::DEFAULT_MARGIN);

        return $this;
    }

    /**
     * Get Margin Left
     *
     * @return int|float
     */
    public function getMarginLeft()
    {
        return $this->marginLeft;
    }

    /**
     * Set Margin Left
     *
     * @param int|float $value
     * @return self
     */
    public function setMarginLeft($value = '')
    {
        $this->marginLeft = $this->setNumericVal($value, self::DEFAULT_MARGIN);

        return $this;
    }

    /**
     * Get Margin Right
     *
     * @return int|float
     */
    public function getMarginRight()
    {
        return $this->marginRight;
    }

    /**
     * Set Margin Right
     *
     * @param int|float $value
     * @return self
     */
    public function setMarginRight($value = '')
    {
        $this->marginRight = $this->setNumericVal($value, self::DEFAULT_MARGIN);

        return $this;
    }

    /**
     * Get Margin Bottom
     *
     * @return int|float
     */
    public function getMarginBottom()
    {
        return $this->marginBottom;
    }

    /**
     * Set Margin Bottom
     *
     * @param int|float $value
     * @return self
     */
    public function setMarginBottom($value = '')
    {
        $this->marginBottom = $this->setNumericVal($value, self::DEFAULT_MARGIN);

        return $this;
    }

    /**
     * Get gutter
     *
     * @return int|float
     */
    public function getGutter()
    {
        return $this->gutter;
    }

    /**
     * Set gutter
     *
     * @param int|float $value
     * @return self
     */
    public function setGutter($value = '')
    {
        $this->gutter = $this->setNumericVal($value, self::DEFAULT_GUTTER);

        return $this;
    }

    /**
     * Get Header Height
     *
     * @return int|float
     */
    public function getHeaderHeight()
    {
        return $this->headerHeight;
    }

    /**
     * Set Header Height
     *
     * @param int|float $value
     * @return self
     */
    public function setHeaderHeight($value = '')
    {
        $this->headerHeight = $this->setNumericVal($value, self::DEFAULT_HEADER_HEIGHT);

        return $this;
    }

    /**
     * Get Footer Height
     *
     * @return int|float
     */
    public function getFooterHeight()
    {
        return $this->footerHeight;
    }

    /**
     * Set Footer Height
     *
     * @param int|float $value
     * @return self
     */
    public function setFooterHeight($value = '')
    {
        $this->footerHeight = $this->setNumericVal($value, self::DEFAULT_FOOTER_HEIGHT);

        return $this;
    }

    /**
     * Get page numbering start
     *
     * @return null|int
     */
    public function getPageNumberingStart()
    {
        return $this->pageNumberingStart;
    }

    /**
     * Set page numbering start
     *
     * @param null|int $pageNumberingStart
     * @return $this
     */
    public function setPageNumberingStart($pageNumberingStart = null)
    {
        $this->pageNumberingStart = $pageNumberingStart;
        return $this;
    }

    /**
     * Get Section Columns Count
     *
     * @return int
     */
    public function getColsNum()
    {
        return $this->colsNum;
    }

    /**
     * Set Section Columns Count
     *
     * @param int $value
     * @return self
     */
    public function setColsNum($value = '')
    {
        $this->colsNum = $this->setIntVal($value, self::DEFAULT_COLUMN_COUNT);

        return $this;
    }

    /**
     * Get Section Space Between Columns
     *
     * @return int|float
     */
    public function getColsSpace()
    {
        return $this->colsSpace;
    }

    /**
     * Set Section Space Between Columns
     *
     * @param int|float $value
     * @return self
     */
    public function setColsSpace($value = '')
    {
        $this->colsSpace = $this->setNumericVal($value, self::DEFAULT_COLUMN_SPACING);

        return $this;
    }

    /**
     * Get Break Type
     *
     * @return string
     */
    public function getBreakType()
    {
        return $this->breakType;
    }

    /**
     * Set Break Type
     *
     * @param string $value
     * @return self
     */
    public function setBreakType($value = null)
    {
        $this->breakType = $value;
        return $this;
    }

    /**
     * Get line numbering
     *
     * @return \PhpOffice\PhpWord\Style\LineNumbering
     */
    public function getLineNumbering()
    {
        return $this->lineNumbering;
    }

    /**
     * Set line numbering
     *
     * @param array $value
     * @return self
     */
    public function setLineNumbering($value = null)
    {
        if (is_array($value)) {
            if (!$this->lineNumbering instanceof LineNumbering) {
                $this->lineNumbering = new LineNumbering($value);
            }
            $this->lineNumbering->setStyleByArray($value);
        } else {
            $this->lineNumbering = null;
        }

        return $this;
    }
}

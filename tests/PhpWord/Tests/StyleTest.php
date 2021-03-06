<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Tests;

use PhpOffice\PhpWord\Style;

/**
 * Test class for PhpOffice\PhpWord\Style
 *
 * @runTestsInSeparateProcesses
 */
class StyleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Add and get paragraph, font, link, title, and table styles
     */
    public function testStyles()
    {
        $paragraph = array('align' => 'center');
        $font = array('italic' => true, '_bold' => true);
        $table = array('bgColor' => 'CCCCCC');
        $styles = array('Paragraph' => 'Paragraph', 'Font' => 'Font',
            'Link' => 'Font', 'Table' => 'Table',
            'Heading_1' => 'Font', 'Normal' => 'Paragraph');
        $elementCount = 6;

        Style::addParagraphStyle('Paragraph', $paragraph);
        Style::addFontStyle('Font', $font);
        Style::addLinkStyle('Link', $font);
        Style::addTableStyle('Table', $table);
        Style::addTitleStyle(1, $font);
        Style::setDefaultParagraphStyle($paragraph);

        $this->assertEquals($elementCount, count(Style::getStyles()));
        foreach ($styles as $name => $style) {
            $this->assertInstanceOf("PhpOffice\\PhpWord\\Style\\{$style}", Style::getStyle($name));
        }
        $this->assertNull(Style::getStyle('Unknown'));

        Style::resetStyles();
        $this->assertEquals(0, count(Style::getStyles()));

    }

    /**
     * Set default paragraph style
     */
    public function testDefaultParagraphStyle()
    {
        $paragraph = array('align' => 'center');

        Style::setDefaultParagraphStyle($paragraph);

        $this->assertInstanceOf("PhpOffice\\PhpWord\\Style\\Paragraph", Style::getStyle('Normal'));
    }
}

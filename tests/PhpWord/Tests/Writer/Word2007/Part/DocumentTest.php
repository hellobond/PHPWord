<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */
namespace PhpOffice\PhpWord\Tests\Writer\Word2007\Part;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Tests\TestHelperDOCX;
use PhpOffice\PhpWord\Writer\Word2007\Part\Document;

/**
 * Test class for PhpOffice\PhpWord\Writer\Word2007\Part\Document
 *
 * @runTestsInSeparateProcesses
 */
class DocumentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Executed before each method of the class
     */
    public function tearDown()
    {
        TestHelperDOCX::clear();
    }

    /**
     * Test write word/document.xm with no PhpWord
     *
     * @expectedException \PhpOffice\PhpWord\Exception\Exception
     * @expectedExceptionMessage No PhpWord assigned.
     */
    public function testWriteDocumentNoPhpWord()
    {
        $object = new Document();
        $object->writeDocument();
    }

    /**
     * Write end section page numbering
     */
    public function testWriteEndSectionPageNumbering()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $settings = $section->getSettings();
        $settings->setLandscape();
        $settings->setPageNumberingStart(2);
        $settings->setBorderSize(240);
        $settings->setBreakType('nextPage');

        $doc = TestHelperDOCX::getDocument($phpWord);
        $element = $doc->getElement('/w:document/w:body/w:sectPr/w:pgNumType');

        $this->assertEquals(2, $element->getAttribute('w:start'));
    }

    /**
     * Write elements
     */
    public function testElements()
    {
        $objectSrc = __DIR__ . "/../../../_files/documents/sheet.xls";

        $phpWord = new PhpWord();
        $phpWord->addTitleStyle(1, array('color' => '333333', 'bold'=>true));
        $phpWord->addTitleStyle(2, array('color'=>'666666'));
        $section = $phpWord->addSection();
        $section->addTOC();
        $section->addPageBreak();
        $section->addTitle('Title 1', 1);
        $section->addListItem('List Item 1', 0);
        $section->addListItem('List Item 2', 0);
        $section->addListItem('List Item 3', 0);
        $section = $phpWord->addSection();
        $section->addTitle('Title 2', 2);
        $section->addObject($objectSrc);
        $doc = TestHelperDOCX::getDocument($phpWord);

        // TOC
        $element = $doc->getElement('/w:document/w:body/w:p[1]/w:pPr/w:tabs/w:tab');
        $this->assertEquals('right', $element->getAttribute('w:val'));
        $this->assertEquals('dot', $element->getAttribute('w:leader'));
        $this->assertEquals(9062, $element->getAttribute('w:pos'));

        // Page break
        $element = $doc->getElement('/w:document/w:body/w:p[4]/w:r/w:br');
        $this->assertEquals('page', $element->getAttribute('w:type'));

        // Title
        $element = $doc->getElement('/w:document/w:body/w:p[5]/w:pPr/w:pStyle');
        $this->assertEquals('Heading1', $element->getAttribute('w:val'));

        // List item
        $element = $doc->getElement('/w:document/w:body/w:p[6]/w:pPr/w:numPr/w:numId');
        $this->assertEquals(3, $element->getAttribute('w:val'));

        // Object
        $element = $doc->getElement('/w:document/w:body/w:p[11]/w:r/w:object/o:OLEObject');
        $this->assertEquals('Embed', $element->getAttribute('Type'));
    }

    /**
     * Write element with some styles
     */
    public function testElementStyles()
    {
        $objectSrc = __DIR__ . "/../../../_files/documents/sheet.xls";

        $phpWord = new PhpWord();
        $phpWord->addParagraphStyle('pStyle', array('align' => 'center')); // Style #1
        $phpWord->addFontStyle('fStyle', array('size' => '20', 'doubleStrikethrough' => true, 'allCaps' => true)); // Style #2
        $phpWord->addTitleStyle(1, array('color' => '333333', 'bold' => true)); // Style #3
        $fontStyle = new Font('text', array('align' => 'center'));
        $section = $phpWord->addSection();
        $section->addListItem('List Item', 0, null, null, 'pStyle'); // Style #4
        $section->addObject($objectSrc, array('align' => 'center'));
        $section->addTOC($fontStyle);
        $section->addTitle('Title 1', 1);
        $section->addTOC('fStyle');
        $doc = TestHelperDOCX::getDocument($phpWord);

        // List item
        $element = $doc->getElement('/w:document/w:body/w:p[1]/w:pPr/w:numPr/w:numId');
        $this->assertEquals(4, $element->getAttribute('w:val'));

        // Object
        $element = $doc->getElement('/w:document/w:body/w:p[2]/w:r/w:object/o:OLEObject');
        $this->assertEquals('Embed', $element->getAttribute('Type'));

        // TOC
        $element = $doc->getElement('/w:document/w:body/w:p[3]/w:pPr/w:tabs/w:tab');
        $this->assertEquals('right', $element->getAttribute('w:val'));
        $this->assertEquals('dot', $element->getAttribute('w:leader'));
        $this->assertEquals(9062, $element->getAttribute('w:pos'));
    }

    /**
     * Test write text element
     */
    public function testWriteText()
    {
        $rStyle = 'rStyle';
        $pStyle = 'pStyle';

        $phpWord = new PhpWord();
        $phpWord->addFontStyle($rStyle, array('bold' => true));
        $phpWord->addParagraphStyle($pStyle, array('hanging' => 120, 'indent' => 120));
        $section = $phpWord->addSection();
        $section->addText('Test', $rStyle, $pStyle);
        $doc = TestHelperDOCX::getDocument($phpWord);

        $element = "/w:document/w:body/w:p/w:r/w:rPr/w:rStyle";
        $this->assertEquals($rStyle, $doc->getElementAttribute($element, 'w:val'));
        $element = "/w:document/w:body/w:p/w:pPr/w:pStyle";
        $this->assertEquals($pStyle, $doc->getElementAttribute($element, 'w:val'));
    }

    /**
     * Test write textrun element
     */
    public function testWriteTextRun()
    {
        $pStyle = 'pStyle';
        $aStyle = array('align' => 'justify', 'spaceBefore' => 120, 'spaceAfter' => 120);
        $imageSrc = __DIR__ . "/../../../_files/images/earth.jpg";

        $phpWord = new PhpWord();
        $phpWord->addParagraphStyle($pStyle, $aStyle);
        $section = $phpWord->addSection('Test');
        $textrun = $section->addTextRun($pStyle);
        $textrun->addText('Test');
        $textrun->addTextBreak();
        $textrun = $section->addTextRun($aStyle);
        $textrun->addLink('http://test.com');
        $textrun->addImage($imageSrc, array('align' => 'top'));
        $textrun->addFootnote();
        $doc = TestHelperDOCX::getDocument($phpWord);

        $parent = "/w:document/w:body/w:p";
        $this->assertTrue($doc->elementExists("{$parent}/w:pPr/w:pStyle[@w:val='{$pStyle}']"));
    }

    /**
     * Test write link element
     */
    public function testWriteLink()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $fontStyleArray = array('bold' => true);
        $fontStyleName = 'Font Style';
        $paragraphStyleArray = array('align' => 'center');
        $paragraphStyleName = 'Paragraph Style';

        $expected = 'PhpWord';
        $section->addLink('http://github.com/phpoffice/phpword', $expected);
        $section->addLink('http://github.com/phpoffice/phpword', 'Test', $fontStyleArray, $paragraphStyleArray);
        $section->addLink('http://github.com/phpoffice/phpword', 'Test', $fontStyleName, $paragraphStyleName);

        $doc = TestHelperDOCX::getDocument($phpWord);
        $element = $doc->getElement('/w:document/w:body/w:p/w:hyperlink/w:r/w:t');

        $this->assertEquals($expected, $element->nodeValue);
    }

    /**
     * Test write preserve text element
     */
    public function testWritePreserveText()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $footer = $section->addFooter();
        $fontStyleArray = array('bold' => true);
        $fontStyleName = 'Font';
        $paragraphStyleArray = array('align' => 'right');
        $paragraphStyleName = 'Paragraph';

        $footer->addPreserveText('Page {PAGE}');
        $footer->addPreserveText('{PAGE}', $fontStyleArray, $paragraphStyleArray);
        $footer->addPreserveText('{PAGE}', $fontStyleName, $paragraphStyleName);

        $doc = TestHelperDOCX::getDocument($phpWord);
        $preserve = $doc->getElement("w:p/w:r[2]/w:instrText", 'word/footer1.xml');

        $this->assertEquals('PAGE', $preserve->nodeValue);
        $this->assertEquals('preserve', $preserve->getAttribute('xml:space'));
    }

    /**
     * Test write text break
     */
    public function testWriteTextBreak()
    {
        $fArray = array('size' => 12);
        $pArray = array('spacing' => 240);
        $fName = 'fStyle';
        $pName = 'pStyle';

        $phpWord = new PhpWord();
        $phpWord->addFontStyle($fName, $fArray);
        $phpWord->addParagraphStyle($pName, $pArray);
        $section = $phpWord->addSection();
        $section->addTextBreak();
        $section->addTextBreak(1, $fArray, $pArray);
        $section->addTextBreak(1, $fName, $pName);
        $doc = TestHelperDOCX::getDocument($phpWord);

        $element = $doc->getElement('/w:document/w:body/w:p/w:pPr/w:rPr/w:rStyle');
        $this->assertEquals($fName, $element->getAttribute('w:val'));
        $element = $doc->getElement('/w:document/w:body/w:p/w:pPr/w:pStyle');
        $this->assertEquals($pName, $element->getAttribute('w:val'));
    }

    /**
     * covers ::_writeImage
     */
    public function testWriteImage()
    {
        $phpWord = new PhpWord();
        $styles = array('align' => 'left', 'width' => 40, 'height' => 40, 'marginTop' => -1, 'marginLeft' => -1);
        $wraps = array('inline', 'behind', 'infront', 'square', 'tight');
        $section = $phpWord->addSection();
        foreach ($wraps as $wrap) {
            $styles['wrappingStyle'] = $wrap;
            $section->addImage(__DIR__ . "/../../../_files/images/earth.jpg", $styles);
        }

        $archiveFile = realpath(__DIR__ . '/../../../_files/documents/reader.docx');
        $imageFile = 'word/media/image1.jpeg';
        $source = 'zip://' . $archiveFile . '#' . $imageFile;
        $section->addImage($source);

        $doc = TestHelperDOCX::getDocument($phpWord);

        // behind
        $element = $doc->getElement('/w:document/w:body/w:p[2]/w:r/w:pict/v:shape');
        $style = $element->getAttribute('style');
        $this->assertRegExp('/z\-index:\-[0-9]*/', $style);

        // square
        $element = $doc->getElement('/w:document/w:body/w:p[4]/w:r/w:pict/v:shape/w10:wrap');
        $this->assertEquals('square', $element->getAttribute('type'));
    }

    /**
     * covers ::_writeWatermark
     */
    public function testWriteWatermark()
    {
        $imageSrc = __DIR__ . "/../../../_files/images/earth.jpg";

        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $header = $section->addHeader();
        $header->addWatermark($imageSrc);
        $doc = TestHelperDOCX::getDocument($phpWord);

        $element = $doc->getElement("/w:document/w:body/w:sectPr/w:headerReference");
        $this->assertStringStartsWith("rId", $element->getAttribute('r:id'));
    }

    /**
     * covers ::_writeTitle
     */
    public function testWriteTitle()
    {
        $phpWord = new PhpWord();
        $phpWord->addTitleStyle(1, array('bold' => true), array('spaceAfter' => 240));
        $phpWord->addSection()->addTitle('Test', 1);
        $doc = TestHelperDOCX::getDocument($phpWord);

        $element = "/w:document/w:body/w:p/w:pPr/w:pStyle";
        $this->assertEquals('Heading1', $doc->getElementAttribute($element, 'w:val'));
        $element = "/w:document/w:body/w:p/w:r/w:fldChar";
        $this->assertEquals('end', $doc->getElementAttribute($element, 'w:fldCharType'));
    }

    /**
     * covers ::_writeCheckbox
     */
    public function testWriteCheckbox()
    {
        $rStyle = 'rStyle';
        $pStyle = 'pStyle';

        $phpWord = new PhpWord();
        $phpWord->addFontStyle($rStyle, array('bold' => true));
        $phpWord->addParagraphStyle($pStyle, array('hanging' => 120, 'indent' => 120));
        $section = $phpWord->addSection();
        $section->addCheckbox('Check1', 'Test', $rStyle, $pStyle);
        $doc = TestHelperDOCX::getDocument($phpWord);

        $element = '/w:document/w:body/w:p/w:r/w:fldChar/w:ffData/w:name';
        $this->assertEquals('Check1', $doc->getElementAttribute($element, 'w:val'));
    }

    /**
     * covers ::_writeParagraphStyle
     */
    public function testWriteParagraphStyle()
    {
        // Create the doc
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $attributes = array(
            'align' => 'right',
            'widowControl' => false,
            'keepNext' => true,
            'keepLines' => true,
            'pageBreakBefore' => true,
        );
        foreach ($attributes as $attribute => $value) {
            $section->addText('Test', null, array($attribute => $value));
        }
        $doc = TestHelperDOCX::getDocument($phpWord);

        // Test the attributes
        $i = 0;
        foreach ($attributes as $key => $value) {
            $i++;
            $nodeName = ($key == 'align') ? 'jc' : $key;
            $path = "/w:document/w:body/w:p[{$i}]/w:pPr/w:{$nodeName}";
            if ($key != 'align') {
                $value = $value ? 1 : 0;
            }
            $element = $doc->getElement($path);
            $this->assertEquals($value, $element->getAttribute('w:val'));
        }
    }

    /**
     * covers ::_writeTextStyle
     */
    public function testWriteFontStyle()
    {
        $phpWord = new PhpWord();
        $styles['name'] = 'Verdana';
        $styles['size'] = 14;
        $styles['bold'] = true;
        $styles['italic'] = true;
        $styles['underline'] = 'dash';
        $styles['strikethrough'] = true;
        $styles['superScript'] = true;
        $styles['color'] = 'FF0000';
        $styles['fgColor'] = 'yellow';
        $styles['bgColor'] = 'FFFF00';
        $styles['hint'] = 'eastAsia';
        $styles['smallCaps'] = true;

        $section = $phpWord->addSection();
        $section->addText('Test', $styles);
        $doc = TestHelperDOCX::getDocument($phpWord);

        $parent = '/w:document/w:body/w:p/w:r/w:rPr';
        $this->assertEquals($styles['name'], $doc->getElementAttribute("{$parent}/w:rFonts", 'w:ascii'));
        $this->assertEquals($styles['size'] * 2, $doc->getElementAttribute("{$parent}/w:sz", 'w:val'));
        $this->assertTrue($doc->elementExists("{$parent}/w:b"));
        $this->assertTrue($doc->elementExists("{$parent}/w:i"));
        $this->assertEquals($styles['underline'], $doc->getElementAttribute("{$parent}/w:u", 'w:val'));
        $this->assertTrue($doc->elementExists("{$parent}/w:strike"));
        $this->assertEquals('superscript', $doc->getElementAttribute("{$parent}/w:vertAlign", 'w:val'));
        $this->assertEquals($styles['color'], $doc->getElementAttribute("{$parent}/w:color", 'w:val'));
        $this->assertEquals($styles['fgColor'], $doc->getElementAttribute("{$parent}/w:highlight", 'w:val'));
        $this->assertTrue($doc->elementExists("{$parent}/w:smallCaps"));
    }

    /**
     * covers ::_writeTableStyle
     */
    public function testWriteTableStyle()
    {
        $phpWord = new PhpWord();
        $tWidth = 120;
        $rHeight = 120;
        $cWidth = 120;
        $imageSrc = __DIR__ . "/../../../_files/images/earth.jpg";
        $objectSrc = __DIR__ . "/../../../_files/documents/sheet.xls";

        $tStyles["width"] = 50;
        $tStyles["cellMarginTop"] = 120;
        $tStyles["cellMarginRight"] = 120;
        $tStyles["cellMarginBottom"] = 120;
        $tStyles["cellMarginLeft"] = 120;
        $rStyles["tblHeader"] = true;
        $rStyles["cantSplit"] = true;
        $cStyles["valign"] = 'top';
        $cStyles["textDirection"] = 'btLr';
        $cStyles["bgColor"] = 'FF0000';
        $cStyles["borderTopSize"] = 120;
        $cStyles["borderBottomSize"] = 120;
        $cStyles["borderLeftSize"] = 120;
        $cStyles["borderRightSize"] = 120;
        $cStyles["borderTopColor"] = 'FF0000';
        $cStyles["borderBottomColor"] = 'FF0000';
        $cStyles["borderLeftColor"] = 'FF0000';
        $cStyles["borderRightColor"] = 'FF0000';
        $cStyles["vMerge"] = 'restart';

        $section = $phpWord->addSection();
        $table = $section->addTable($tStyles);
        $table->setWidth = 100;
        $table->addRow($rHeight, $rStyles);
        $cell = $table->addCell($cWidth, $cStyles);
        $cell->addText('Test');
        $cell->addTextBreak();
        $cell->addLink('http://google.com');
        $cell->addListItem('Test');
        $cell->addImage($imageSrc);
        $cell->addObject($objectSrc);
        $textrun = $cell->addTextRun();
        $textrun->addText('Test');

        $doc = TestHelperDOCX::getDocument($phpWord);

        $parent = '/w:document/w:body/w:tbl/w:tblPr/w:tblCellMar';
        // $this->assertEquals($tStyles['cellMarginTop'], $doc->getElementAttribute("{$parent}/w:top", 'w:w'));
        // $this->assertEquals($tStyles['cellMarginRight'], $doc->getElementAttribute("{$parent}/w:right", 'w:w'));
        // $this->assertEquals($tStyles['cellMarginBottom'], $doc->getElementAttribute("{$parent}/w:bottom", 'w:w'));
        // $this->assertEquals($tStyles['cellMarginLeft'], $doc->getElementAttribute("{$parent}/w:right", 'w:w'));

        $parent = '/w:document/w:body/w:tbl/w:tr/w:trPr';
        $this->assertEquals($rHeight, $doc->getElementAttribute("{$parent}/w:trHeight", 'w:val'));
        $this->assertEquals($rStyles['tblHeader'], $doc->getElementAttribute("{$parent}/w:tblHeader", 'w:val'));
        $this->assertEquals($rStyles['cantSplit'], $doc->getElementAttribute("{$parent}/w:cantSplit", 'w:val'));

        $parent = '/w:document/w:body/w:tbl/w:tr/w:tc/w:tcPr';
        $this->assertEquals($cWidth, $doc->getElementAttribute("{$parent}/w:tcW", 'w:w'));
        $this->assertEquals($cStyles['valign'], $doc->getElementAttribute("{$parent}/w:vAlign", 'w:val'));
        $this->assertEquals($cStyles['textDirection'], $doc->getElementAttribute("{$parent}/w:textDirection", 'w:val'));
    }

    /**
     * covers ::_writeCellStyle
     */
    public function testWriteCellStyleCellGridSpan()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $table = $section->addTable();

        $table->addRow();
        $cell = $table->addCell(200);
        $cell->getStyle()->setGridSpan(5);

        $table->addRow();
        $table->addCell(40);
        $table->addCell(40);
        $table->addCell(40);
        $table->addCell(40);
        $table->addCell(40);

        $doc = TestHelperDOCX::getDocument($phpWord);
        $element = $doc->getElement('/w:document/w:body/w:tbl/w:tr/w:tc/w:tcPr/w:gridSpan');

        $this->assertEquals(5, $element->getAttribute('w:val'));
    }

    /**
     * Test write gutter and line numbering
     */
    public function testWriteGutterAndLineNumbering()
    {
        $pageMarginPath = '/w:document/w:body/w:sectPr/w:pgMar';
        $lineNumberingPath = '/w:document/w:body/w:sectPr/w:lnNumType';

        $phpWord = new PhpWord();
        $section = $phpWord->addSection(array('gutter' => 240, 'lineNumbering' => array()));
        $doc = TestHelperDOCX::getDocument($phpWord);

        $this->assertEquals(240, $doc->getElement($pageMarginPath)->getAttribute('w:gutter'));
        $this->assertTrue($doc->elementExists($lineNumberingPath));
    }
}

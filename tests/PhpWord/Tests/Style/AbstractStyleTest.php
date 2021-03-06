<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Tests\Style;

/**
 * Test class for PhpOffice\PhpWord\Style\AbstractStyle
 *
 * @runTestsInSeparateProcesses
 */
class AbstractStyleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test set style by array
     */
    public function testSetStyleByArray()
    {
        $stub = $this->getMockForAbstractClass('\PhpOffice\PhpWord\Style\AbstractStyle');
        $stub->setStyleByArray(array('index' => 1));

        $this->assertEquals(1, $stub->getIndex());
    }

    /**
     * Test setBoolVal, setIntVal, setFloatVal, setEnumVal with normal value
     */
    public function testSetValNormal()
    {
        $stub = $this->getMockForAbstractClass('\PhpOffice\PhpWord\Style\AbstractStyle');

        $this->assertEquals(true, self::callProtectedMethod($stub, 'setBoolVal', array(true, false)));
        $this->assertEquals(12, self::callProtectedMethod($stub, 'setIntVal', array(12, 200)));
        $this->assertEquals(871.1, self::callProtectedMethod($stub, 'setFloatVal', array(871.1, 2.1)));
        $this->assertEquals('a', self::callProtectedMethod($stub, 'setEnumVal', array('a', array('a', 'b'), 'b')));
    }

    /**
     * Test setBoolVal, setIntVal, setFloatVal, setEnumVal with default value
     */
    public function testSetValDefault()
    {
        $stub = $this->getMockForAbstractClass('\PhpOffice\PhpWord\Style\AbstractStyle');

        $this->assertEquals(false, self::callProtectedMethod($stub, 'setBoolVal', array('a', false)));
        $this->assertEquals(200, self::callProtectedMethod($stub, 'setIntVal', array('foo', 200)));
        $this->assertEquals(2.1, self::callProtectedMethod($stub, 'setFloatVal', array('foo', 2.1)));
        $this->assertEquals('b', self::callProtectedMethod($stub, 'setEnumVal', array('z', array('a', 'b'), 'b')));
    }

    /**
     * Helper function to call protected method
     *
     * @param mixed $object
     * @param string $method
     * @param array $args
     */
    public static function callProtectedMethod($object, $method, array $args = array())
    {
        $class = new \ReflectionClass(get_class($object));
        $method = $class->getMethod($method);
        $method->setAccessible(true);
        return $method->invokeArgs($object, $args);
    }
}

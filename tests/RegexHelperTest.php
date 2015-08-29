<?php

namespace JurijVeresciaka\Component\RegexHelper\Tests;

use JurijVeresciaka\Component\RegexHelper\RegexHelper;

class RegexHelperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RegexHelper
     */
    protected $regexHelper;

    protected function setUp()
    {
        $this->regexHelper = new RegexHelper();
    }

    /**
     * @param string $regexPattern
     * @param string[] $regexModifierList
     * @param string[] $stringList
     * @param boolean $expectedResult
     *
     * @dataProvider providerIsMatch
     */
    public function testIsMatch($regexPattern, $regexModifierList, $stringList, $expectedResult)
    {
        foreach ($stringList as $string) {
            $this->assertEquals(
                $expectedResult,
                $this->regexHelper->isMatch($regexPattern, $regexModifierList, $string)
            );
        }
    }

    public function providerIsMatch()
    {
        return array(
            array(
                '^[a-z]{3}$',
                array(),
                array('aaa', 'bbb'),
                true
            ),
            array(
                '^[A-Z]$',
                array(),
                array('aaa', '1'),
                false
            ),
            array(
                '^.+$',
                array(RegexHelper::REGEX_MODIFIER_UTF8),
                array('©℗¶№§'),
                true
            ),
        );
    }

    /**
     * @expectedException JurijVeresciaka\Component\RegexHelper\Exception\InvalidRegexModifierException
     */
    public function testIsMatch_invalid_regex_modifier()
    {
        $this->regexHelper->isMatch('', array('I'), '');
    }

    /**
     * @expectedException JurijVeresciaka\Component\RegexHelper\Exception\RegexErrorException
     */
    public function testIsMatch_regex_error()
    {
        $this->regexHelper->isMatch('(?:\D+|<\d+>)*[!?]', array(), 'foobar foobar foobar');
    }
}

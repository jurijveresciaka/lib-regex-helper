<?php

namespace JurijVeresciaka\Component\RegexHelper;

use JurijVeresciaka\Component\RegexHelper\Exception\InvalidRegexModifierException;
use JurijVeresciaka\Component\RegexHelper\Exception\RegexErrorException;
use JurijVeresciaka\Component\RegexHelper\Exception\RegexHelperException;

class RegexHelper
{
    const REGEX_MODIFIER_CASELESS = 'i';
    const REGEX_MODIFIER_MULTILINE = 'm';
    const REGEX_MODIFIER_DOTALL = 's';
    const REGEX_MODIFIER_EXTENDED = 'x';
    const REGEX_MODIFIER_ANCHORED = 'A';
    const REGEX_MODIFIER_DOLLAR_ENDONLY = 'D';
    const REGEX_MODIFIER_S = 'S';
    const REGEX_MODIFIER_UNGREEDY = 'U';
    const REGEX_MODIFIER_EXTRA = 'X';
    const REGEX_MODIFIER_INFO_JCHANGED = 'J';
    const REGEX_MODIFIER_UTF8 = 'u';

    /**
     * @var string[]
     */
    protected $validRegexModifierList = array(
        self::REGEX_MODIFIER_CASELESS,
        self::REGEX_MODIFIER_MULTILINE,
        self::REGEX_MODIFIER_DOTALL,
        self::REGEX_MODIFIER_EXTENDED,
        self::REGEX_MODIFIER_ANCHORED,
        self::REGEX_MODIFIER_DOLLAR_ENDONLY,
        self::REGEX_MODIFIER_S,
        self::REGEX_MODIFIER_UNGREEDY,
        self::REGEX_MODIFIER_EXTRA,
        self::REGEX_MODIFIER_INFO_JCHANGED,
        self::REGEX_MODIFIER_UTF8,
    );

    /**
     * @var string[]
     */
    protected $errorMap = array(
        PREG_NO_ERROR => 'no error',
        PREG_INTERNAL_ERROR => 'internal error',
        PREG_BACKTRACK_LIMIT_ERROR => 'backtrack limit error',
        PREG_RECURSION_LIMIT_ERROR => 'recursion limit error',
        PREG_BAD_UTF8_ERROR => 'bad utf8 error',
        PREG_BAD_UTF8_ERROR => 'bad utf8 error',
        PREG_BAD_UTF8_OFFSET_ERROR => 'bad utf8 offset error',
        // PREG_JIT_STACKLIMIT_ERROR => 'jit stacklimit error', // PHP 7.0.0
    );

    /**
     * @param string $regexPattern
     * @param string[] $regexModifierList
     * @param string $string
     *
     * @return boolean
     *
     * @throws RegexHelperException
     */
    public function isMatch(
        $regexPattern,
        array $regexModifierList,
        $string
    ) {
        foreach ($regexModifierList as $regexModifier) {
            $this->validateRegexModifier($regexModifier);
        }

        $result = preg_match('/' . $regexPattern . '/' . implode('', $regexModifierList), $string);

        if ($result === false) {
            throw new RegexErrorException('Regex error');
        }

        if (preg_last_error() !== PREG_NO_ERROR) {
            throw new RegexErrorException(
                sprintf(
                    'Regex error (error: "%s")',
                    $this->errorMap[preg_last_error()]
                )
            );
        }

        return $result === 1 ? true : false;
    }

    /**
     * @param $regexModifier
     *
     * @throws RegexHelperException
     */
    protected function validateRegexModifier($regexModifier)
    {
        if (!in_array($regexModifier, $this->validRegexModifierList)) {
            throw new InvalidRegexModifierException(
                sprintf(
                    'Invalid regex modifier (regex modifier: "%s")',
                    $regexModifier
                )
            );
        }
    }
}

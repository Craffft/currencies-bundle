<?php

/*
 * This file is part of the Currencies Bundle.
 *
 * (c) Daniel Kiesel <https://github.com/iCodr8>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Currencies;

class CurrencyField extends \TextField
{
    /**
     * Validate the value
     *
     * @param $varInput
     * @return decimal
     */
    protected function validator($varInput)
    {
        $this->rgxp = 'digit';
        $varInput = $this->encode($varInput);

        if ($this->unsigned && $varInput < 0) {
            $varInput = $varInput * (-1);
        }

        return parent::validator(trim($varInput));
    }

    /**
     * Generates the currency field
     *
     * @return string
     */
    public function generate()
    {
        $this->varValue = $this->decode($this->varValue);

        if (\Input::post($this->strName) !== null) {
            $this->varValue = \Input::post($this->strName);
        }

        return sprintf('<input type="text" name="%s" id="ctrl_%s" class="tl_text%s" value="%s"%s onfocus="Backend.getScrollOffset()">%s',
            $this->strName,
            $this->strId,
            (($this->strClass != '') ? ' ' . $this->strClass : ''),
            specialchars($this->varValue),
            $this->getAttributes(),
            $this->wizard);
    }

    /**
     * Encode a formatted number to a decimal number
     *
     * @param $varValue
     * @return decimal
     */
    protected function encode($varValue)
    {
        $varValue = str_replace($GLOBALS['TL_LANG']['MSC']['thousandsSeparator'], '', $varValue);
        $varValue = str_replace($GLOBALS['TL_LANG']['MSC']['decimalSeparator'], '.', $varValue);

        return $varValue;
    }

    /**
     * Decode a decimal number to a formatted number
     *
     * @param $varValue
     * @return string
     */
    protected function decode($varValue)
    {
        return \System::getFormattedNumber($varValue);
    }
}

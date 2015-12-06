<?php

/*
 * This file is part of the Currencies Bundle.
 *
 * (c) Daniel Kiesel <https://github.com/iCodr8>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Craffft\CurrenciesBundle\Util;

use Patchwork\Utf8;
use Symfony\Component\Yaml\Parser;

class Currencies
{
    private $arrCurrencies;

    /**
     * @param bool|false $isForce
     */
    public function loadCurrencies($isForce = false)
    {
        if (!$isForce && !empty($this->arrCurrencies)) {
            return;
        }

        $yaml = new Parser();
        $arrCurrencies = $yaml->parse(file_get_contents(dirname(__DIR__) . '/Resources/config/currencies.yml'));

        if (isset($arrCurrencies['currencies'])) {
            $this->arrCurrencies = $arrCurrencies['currencies'];
        }
    }

    /**
     * @return array
     */
    public function getCurrencies()
    {
        $return = array();
        $arrAux = array();

        \Contao\System::loadLanguageFile('currencies');
        $this->loadCurrencies();

        if (is_array($this->arrCurrencies)) {
            foreach ($this->arrCurrencies as $strKey => $strName) {
                $arrAux[$strKey] = isset($GLOBALS['TL_LANG']['CUR'][$strKey]) ? Utf8::toAscii($GLOBALS['TL_LANG']['CUR'][$strKey]) : $strName;
            }
        }

        asort($arrAux);

        if (is_array($arrAux)) {
            foreach (array_keys($arrAux) as $strKey) {
                $return[$strKey] = isset($GLOBALS['TL_LANG']['CUR'][$strKey]) ? $GLOBALS['TL_LANG']['CUR'][$strKey] : $this->arrCurrencies[$strKey];
            }
        }

        // HOOK: add custom logic
        if (isset($GLOBALS['TL_HOOKS']['getCurrencies']) && is_array($GLOBALS['TL_HOOKS']['getCurrencies'])) {
            foreach ($GLOBALS['TL_HOOKS']['getCurrencies'] as $callback) {
                $return = static::importStatic($callback[0])->$callback[1]($return, $this->arrCurrencies);
            }
        }

        return $return;
    }

    /**
     * @param $strCurrencyKey
     * @return string
     */
    public function getCurrencySymbol($strCurrencyKey)
    {
        $this->loadCurrencies();

        if (isset($this->arrCurrencies[$strCurrencyKey]['symbol'])) {
            if (strlen($this->arrCurrencies[$strCurrencyKey]['symbol'])) {
                return $this->arrCurrencies[$strCurrencyKey]['symbol'];
            }
        }

        return $strCurrencyKey;
    }
}

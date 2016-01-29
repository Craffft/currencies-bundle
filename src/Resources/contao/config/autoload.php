<?php

/*
 * This file is part of the Currencies Bundle.
 *
 * (c) Daniel Kiesel <https://github.com/iCodr8>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
    'Currencies',
));

/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
    // Widgets
    'Currencies\CurrencyField' => 'vendor/craffft/currencies-bundle/src/Resources/contao/widgets/CurrencyField.php',
));

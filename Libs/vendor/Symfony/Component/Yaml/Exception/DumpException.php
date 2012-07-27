<?php

/*
 * This File is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please View the LICENSE
 * File that was distributed with this source code.
 */

namespace Symfony\Component\Yaml\Exception;

/**
 * Exception class thrown when an error occurs during dumping.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 *
 * @api
 */
class DumpException extends \RuntimeException implements ExceptionInterface
{
}

<?php
namespace Shift1\Core\Bundle\Exceptions;

class DefinitionException extends \RuntimeException {

    const BUNDLE_NAMESPACE_INVALID      = 0;
    const BUNDLE_SUFFIX_INVALID         = 1;
    const BUNDLE_VENDOR_INVALID         = 2;
    const BUNDLE_DEFINITION_INVALID     = 3;

    const CONTROLLER_NAMESPACE_INVALID  = 10;

    const ACTION_DEFINITION_INVALID     = 20;

    const TEMPLATE_DEFINITION_INVALID   = 30;

}

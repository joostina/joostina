<?php

$decode = json_decode($encodedValue, $objectDecodeType);

switch (json_last_error()) {
    case JSON_ERROR_NONE:
        break;
    case JSON_ERROR_DEPTH:
        throw new RuntimeException('Decoding failed: Maximum stack depth exceeded');
    case JSON_ERROR_CTRL_CHAR:
        throw new RuntimeException('Decoding failed: Unexpected control character found');
    case JSON_ERROR_SYNTAX:
        throw new RuntimeException('Decoding failed: Syntax error');
    default:
        throw new RuntimeException('Decoding failed');
}


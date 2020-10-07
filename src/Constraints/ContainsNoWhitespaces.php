<?php


namespace CodexSoft\Transmission\Schema\Constraints;


use Symfony\Component\Validator\Constraint;

class ContainsNoWhitespaces extends Constraint
{
    public $message = 'The string "{{ string }}" contains whitespaces.';
}

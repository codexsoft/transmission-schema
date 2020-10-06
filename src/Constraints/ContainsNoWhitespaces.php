<?php


namespace CodexSoft\TransmissionSchema\Constraints;


use Symfony\Component\Validator\Constraint;

class ContainsNoWhitespaces extends Constraint
{
    public $message = 'The string "{{ string }}" contains whitespaces.';
}

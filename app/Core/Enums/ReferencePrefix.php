<?php

namespace App\Core\Enums;

enum ReferencePrefix: string
{
    case COMPANY = 'CMP';
    case EMPLOYEE = 'EMP';
    case CONTACT = 'CNT';
    case PROJECT = 'PRJ';
    case TASK = 'TSK';
    case INVOICE = 'INV';
    case DEFAULT = 'REF';

    public static function fromModel(string $modelClass): self
    {
        $caseName = strtoupper(class_basename($modelClass));

        foreach (self::cases() as $case) {
            if ($case->name === $caseName) {
                return $case;
            }
        }

        return self::DEFAULT;
    }
}

<?php

class PensionSchemeValidator
{
    private array $pensionSchemeTable;

    public function __construct(array $pensionSchemeTable)
    {
        $this->pensionSchemeTable = $pensionSchemeTable;
    }

    public function validate(string $scheme): ?int
    {
        $schemeCode = $this->findSchemeByName($scheme);

        if ($schemeCode !== null) {
            return $schemeCode;
        }

        return $this->findSchemeInArray($scheme);
    }

    private function findSchemeByName(string $name): ?int
    {
        foreach ($this->pensionSchemeTable as $code => $names) {
            if (is_array($names) && $names[0] === $name) {
                return $code;
            }
        }

        return null;
    }

    private function findSchemeInArray(string $name): ?int
    {
        foreach ($this->pensionSchemeTable as $code => $names) {
            if (in_array($name, $names, true)) {
                return $code;
            }
        }

        return null;
    }
}

$pensionSchemeTable = [
    1 => ['PROPRIETARY PENSION SCHEME', 'PPS'],
    2 => ['MILITARY PENSION SCHEME'],
    3 => ['PROPRIETARY SCHEME IN EXTINCTION', 'PPS In Extinction'],
    4 => ['MILITARY SCHEME IN EXTINCTION'],
    5 => ['GENERAL SOCIAL SECURITY SCHEME (INSS)', 'GSS'],
];

$validator = new PensionSchemeValidator($pensionSchemeTable);
echo $validator->validate('GSS');

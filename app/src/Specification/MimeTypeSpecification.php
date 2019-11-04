<?php

namespace App\Specification;

class MimeTypeSpecification
{
    private $availableMimeTypes = [
        'image/jpeg',
        'image/png',

        'application/msword',  // .doc
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // .docx

        'application/vnd.ms-excel', // .xls
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // .xlsx

        'application/pdf',
    ];

    /**
     * @param string $mimeType
     * @return bool
     */
    public function isSatisfiedBy(string $mimeType): bool
    {
        return in_array($mimeType, $this->availableMimeTypes, true);
    }
}

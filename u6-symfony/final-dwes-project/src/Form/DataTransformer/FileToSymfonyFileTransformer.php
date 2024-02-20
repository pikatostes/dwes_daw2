<?php
// src/Form/DataTransformer/FileToSymfonyFileTransformer.php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\File;

class FileToSymfonyFileTransformer implements DataTransformerInterface
{
    public function transform($value): mixed
    {
        // transform the File instance to a string (file path)
        if ($value instanceof File) {
            return $value->getPathname();
        }

        return null;
    }

    public function reverseTransform($value): mixed
    {
        // transform the string (file path) to a File instance
        if ($value) {
            return new File($value);
        }

        return null;
    }
}

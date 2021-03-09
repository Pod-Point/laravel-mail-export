<?php

namespace PodPoint\LaravelMailExport;

interface Exportable
{
    /**
     * Returns the storage path for where the mail should be stored.
     *
     * @return string
     */
    public function getStoragePath(): string;

    /**
     * Returns the disk that should be used to store the mail.
     *
     * @return string
     */
    public function getStorageDisk(): string;
}

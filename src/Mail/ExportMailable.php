<?php

namespace PodPoint\LaravelMailExport\Mail;

use Illuminate\Mail\Mailable as BaseMailable;

class ExportMailable extends BaseMailable
{
    /**
     * @param bool $write
     */
    public function writeToDisk()
    {
        $this->callbacks[] = function (\Swift_Message $message) {
            $mailable = Str::kebab(self::class);

            Storage::disk(config('mail-export.bucket'))
                ->put("ordering-tool/{$this->installation->uuid}/{$mailable}.eml", $message->toString());
        };
    }
}

<?php

namespace AppBundle\Storage;

use Aws\Common\Credentials\Credentials;
use Aws\S3\S3Client;
use Aws\S3\Exception;

class S3Storage {

    const AWS_KEY = "AKIAJSPX2IFWEQTPFFYA";
    const AWS_SECRET = "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";

    public function PutFile($filename, $localPath){

        // generate a unique prefix to prevent collisions
        $uniquePrefix = substr(md5(microtime() . $filename), 0, 5) . "_";
        $prefixedFilename = $uniquePrefix . $filename;



        $s3 = S3Client::factory(array(
            'credentials' => new Credentials(S3Storage::AWS_KEY, S3Storage::AWS_SECRET)
        ));

        try {
            $s3->putObject([
                'Bucket' => 'shoperella-images',
                'Key'    => $prefixedFilename,
                'Body'   => fopen($localPath, 'r'),
            ]);
        } catch (\Exception $e) {
            return "";
        }

        return "https://s3.amazonaws.com/shoperella-images/" . $prefixedFilename;
    }
}
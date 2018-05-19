<?php
namespace App\PhotoClient;
/**
 * Created by PhpStorm.
 * User: macbook
 * Date: 4/28/18
 * Time: 5:08 PM
 */
use App\Repositories\Interfaces\PhotoInterface;
use Aws\S3\S3Client;
use http\Exception;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class AWSPhoto implements PhotoInterface
{
    private $s3Client;

    private $bucket = "otruvez-images";

    public function __construct() {
        $this->s3Client = new S3Client([
            'version' => 'latest',
            'region'  => 'us-west-2',
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY'),
                'secret' => env('AWS_SECRET'),
            ],
        ]);
    }

    public function getS3Client() {
        return $this->s3Client;
    }

    public function createFileName(UploadedFile $file, $folderName) {
        return "/".$folderName . sha1($file->getClientOriginalName().date('now')) . "." . $file->getClientOriginalExtension();
    }


    public function store(UploadedFile $file, $folderName)
    {
        $filename = $this->createFileName($file, $folderName);

        try {
            $this->getS3Client()->upload($this->bucket, $filename, fopen($file->getPathname(), 'rb'), "public-read");
            return $filename;
        } catch (Exception $e) {
            return 0;
        }
    }

    public function retrieve($filename)
    {
        // TODO: Implement retrieve() method.
    }

    public function unlink($filename)
    {
        $this->getS3Client()->deleteObject([
            'Bucket'    => $this->bucket,
            'Key'       => $filename
        ]);
    }
}
<?php

namespace App\Service;

use App\Service\Base\Service\Contract;
use App\Service\Base;
use Slim\Http\UploadedFile;
use THS\Utils\Enum\HttpStatusCode;

/**
 * Serviço relacionado aos arquivos.
 *
 * @author Thiago Hofmeister <thiago.souza@moovin.com.br>
 */
class File extends Contract
{
    /**
     * @var string
     * @Inject("upload.basePath")
     */
    private $uploadBasePath;

    /**
     * @var string
     * @Inject("upload.link")
     */
    private $uploadLink;

    /**
     * Realiza o upload das imagens.
     *
     * @return Base\Response
     */
    public function upload(): Base\Response
    {
        /** @var UploadedFile $uploadedFile */
        $uploadedFiles = $this->getRequest()->getUploadedFiles()['images'];

        $path = 'announcements';

        $urls = [];
        foreach ($uploadedFiles as $uploadedFile) {

            if ($uploadedFile->getError() === UPLOAD_ERR_OK) {

                try {
                    $fileName = $this->moveUploadedFile($this->uploadBasePath . $path, $uploadedFile);
                    $urls[] = $this->uploadLink . $path . DIRECTORY_SEPARATOR . $fileName;

                } catch (\Throwable $throwable) {
                    ~rt($throwable);
                    // previne fatal error
                }
            }
        }

        return Base\Response::create(['urls' => $urls], HttpStatusCode::OK());
    }

    /**
     * Move o arquivo para a pasta dele, dando um nome ficticio.
     *
     * @param string $directory
     * @param UploadedFile $uploadedFile
     *
     * @return string
     *
     * @throws \Exception
     */
    private function moveUploadedFile($directory, UploadedFile $uploadedFile)
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $basename = bin2hex(random_bytes(8));
        $filename = sprintf('%s.%0.8s', $basename, $extension);

        if (!is_dir($directory)) {
            mkdir($directory);
        }

        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

        return $filename;
    }
}

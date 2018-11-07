<?php

namespace App\Core\Route;

use App\Controller;
use Slim\Http\Response;

/**
 * Rota para autenticação de usuários.
 *
 * @author Thiago Hofmeister <thiago.hofmeister@gmail.com>
 */
class Image extends AbstractRoute
{
    /**
     * @inheritDoc
     */
    public function __invoke(Response $response, string $parameters = ''): Response
    {
        $pathTmp = [
            '..',
            'public',
            'uploads'
        ];
        $pathFull = implode(DIRECTORY_SEPARATOR, $pathTmp) . DIRECTORY_SEPARATOR . $parameters;

        $image = file_get_contents($pathFull);
        $finfo = @finfo_open(FILEINFO_MIME_TYPE);
        $type = finfo_file($finfo, $pathFull);
        if($image === false) {
            return $response->withJson([
                'message' => 'Arquivo não foi encontrado.'
            ], 404);
        }

        $response->write($image);
        return $response->withHeader('Content-Type', $type);
    }

    /**
     * @inheritDoc
     */
    public function getPattern(): string
    {
        return '/image[/{parameters:.*}]';
    }
}

<?php

namespace App\Service\Base\Loader;

/**
 * Constrói o caminho dos serviços.
 *
 * @author Thiago Hofmeister <thiago.hofmeister@gmail.com>
 */
class Path
{
    /**
     * Retorna caminho do serviço.
     *
     * @param string $serviceName
     *
     * @return string
     *
     * @throws \Exception
     */
    public function service(string $serviceName): string
    {
        $serviceName = implode('\\', [
            'App',
            'Service',
            $this->getStudlyCaps($serviceName)
        ]);

        return $serviceName;
    }

    /**
     * Retorna string no padrão StudlyCaps.
     *
     * @param string $text
     *
     * @return string
     */
    private function getStudlyCaps(string $text): string
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $text)));
    }
}

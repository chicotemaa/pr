<?php

namespace App\Action;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadFile
{
    private $fileBase64;

    /**
     * @Route(
     *     name="upload_file",
     *     path="/api/upload-file/",
     * )
     * @Method("POST")
     */
    public function __invoke(Request $request)
    {
        $this->fileBase64 = $request->request->get('data', null);

        $resultado = '';
        if ($this->fileBase64) {
            $resultado = $this->guardarResultadoImagen();
        }

        $response = new JsonResponse($resultado);

        return $response;
    }

    private function guardarResultadoImagen()
    {
        $decodeImg = base64_decode($this->fileBase64);
        $nombreImagenTemp = 'imagen_temp_'.(new \Datetime())->format('dmYHIsu').rand(5, 15).'.png';

        $filepath = $this->createDirectory();

        $filepath .= $nombreImagenTemp;

        file_put_contents($filepath, $decodeImg);

        $UploadedFile = new UploadedFile($filepath, 'tmp.png', 'image/png', null, null, true);

        $ret = [
            'name' => $nombreImagenTemp,
            'size' => $UploadedFile->getClientSize(),
        ];

        return $ret;
    }

    private function createDirectory()
    {
        $filepath = __DIR__.'/../../public/uploads/imagenes/resultado/';
        if (!file_exists($filepath)) {
            $filepath = __DIR__.'/../../public/uploads/';

            if (!file_exists($filepath)) {
                mkdir($filepath);
            }

            $filepath .= 'imagenes/';

            if (!file_exists($filepath)) {
                mkdir($filepath);
            }

            $filepath .= 'resultado/';

            if (!file_exists($filepath)) {
                mkdir($filepath);
            }
        }

        return $filepath;
    }
}

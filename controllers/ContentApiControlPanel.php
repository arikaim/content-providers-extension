<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Content\Controllers;

use Arikaim\Core\Controllers\ControlPanelApiController;
use Arikaim\Core\Controllers\Traits\FileUpload;
use Arikaim\Core\Controllers\Traits\Actions;
//use Arikaim\Core\Actions\Actions;

/**
 * Content control panel api controller
*/
class ContentApiControlPanel extends ControlPanelApiController
{
    use 
        Actions,
        FileUpload;

    /**
     * Init controller
     *
     * @return void
     */
    public function init()
    {
        $this->loadMessages('content::admin.messages');
    }

    /**
     * Import data item
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function import($request, $response, $data) 
    {         
        $data     
            ->validate(true);
        
        $path = 'files' . DIRECTORY_SEPARATOR . 'json' . DIRECTORY_SEPARATOR;
        $file = $this->uploadFiles($request,$path);
        
        $fileName = $file[0]['file_name'] ?? null;

        if (empty($fileName) === true) {
            $this->error('Error upload file');
            return false;
        }

        $this->runAction('ModelImport','content',[
            'file_name' => $fileName,
            'path'      => $path
        ]);
    }
}

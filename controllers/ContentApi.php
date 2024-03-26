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

use Arikaim\Core\Controllers\ApiController;

/**
 * Content api controller
*/
class ContentApi extends ApiController
{
    /**
     * Init controller
     *
     * @return void
     */
    public function init()
    {
        $this->loadMessages('content>content.messages');
    }

    /**
     * Save content item
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function update($request, $response, $data) 
    {       
        $data
            ->addRule('text:min=2','key')         
            ->addRule('text:min=2','content_type')             
            ->validate(true);     

        $key = $data->get('key');  
        $contentType = $data->get('content_type');  
        $userId = $this->getUserId(); 

        $content = Model::Content('content');   
                                                                  
        $this
            ->message('add')
            ->field('key',$content->key)              
            ->field('uuid',$content->uuid);             
    }
}

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
use Arikaim\Core\Db\Model;

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
     * Add content item
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return mixed
    */
    public function add($request, $response, $data) 
    { 
        $data
            ->addRule('text:min=2','key')         
            ->addRule('text:min=2','content_type')             
            ->validate(true); 
            
        $key = $data->get('key');  
        $contentType = \trim($data->get('content_type')); 
        $title = $data->get('title');   
        $userId = $this->getUserId(); 

        $content = Model::Content('content');   
        if ($content->hasContentItem($key,$userId) == true) {
            $this->error('Content item key are used');
            return false;
        }

        // check content type
        if ($this->get('content')->hasContentType($contentType) == false) {
            $this->error('Not valid content type');
            return false;
        }

        $contentProvider = $this->get('content')->type($contentType);
        $contyentItem = $contentProvider->createItem([
            'user_id' => $userId
        ]);

        if ($contyentItem == null) {
            $this->error('Error create content item');
            return false;
        }

        $item = $content->create([
            'key'          => $key,
            'user_id'      => $userId,
            'title'        => $title,
            'content_type' => $contentType,
            'content_id'   => $contyentItem[0]
        ]);

        if ($item == null) {
            $this->error('Error create content item');
            return false;
        }

        $this
            ->message('add')
            ->field('key',$item->key)              
            ->field('uuid',$item->uuid);         
    }

    /**
     * Save content item
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return mixed
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

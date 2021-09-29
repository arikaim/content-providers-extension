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

use Arikaim\Core\Db\Model;
use Arikaim\Core\Controllers\ApiController;

use Arikaim\Core\Content\ContentSelector;

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
     * Change user details page
     * 
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function searchListController($request, $response, $data) 
    { 
        // get current auth user
        $user = $this->get('access')->getUser();

        $this->onDataValid(function($data) use($user) {
            $search = $data->get('query','');
            $dataField = $data->get('data_field','uuid');
            $contentSelector = $data->get('selector','');
            $size = $data->get('size',15);
            
            $selector = ContentSelector::parse($contentSelector);
            print_r($selector);
            exit();

            $model = Model::Users()->getNotDeletedQuery();
            $model = $model->where('user_name','like','%' . $search . '%')->take($size)->get();
          
            $this->setResponse(\is_object($model),function() use($model,$dataField) {     
                $items = [];
                foreach ($model as $item) {
                    $items[] = ['name' => $item['user_name'],'value' => $item[$dataField]];
                }
                $this                    
                    ->field('success',true)
                    ->field('results',$items);  
            },'errors.list');    
        });         
        $data                     
            ->validate();     
    }
}

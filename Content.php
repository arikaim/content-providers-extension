<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Content;

use Arikaim\Core\Extension\Extension;
use Arikaim\Core\Db\Model;

/**
 * Content extension
*/
class Content extends Extension
{
    /**
     * Install extension routes, events, jobs
     *
     * @return void
    */
    public function install()
    {        
        // Register db tables
        $this->createDbTable('TextContentSchema');
        $this->createDbTable('LinksContentSchema');

        // Register system content types
        $this->registerContentType('Classes\\TextContentType');
        $this->registerContentType('Classes\\EmailContentType');
        $this->registerContentType('Classes\\LinkContentType');
        $this->registerContentType('Classes\\SmsContentType');
        // Register content prviders
        $this->registerContentProvider(Model::TextContent('content'));
        $this->registerContentProvider(Model::LinksContent('content'));
    }   
}

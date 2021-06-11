<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Content\Models\Schema;

use Arikaim\Core\Db\Schema;

/**
 * Link content database table schema definition.
 */
class LinksContentSchema extends Schema  
{    
    /**
     * Table name
     *
     * @var string
     */
    protected $tableName = 'links_content';

    /**
     * Create table
     *
     * @param \Arikaim\Core\Db\TableBlueprint $table
     * @return void
     */
    public function create($table) 
    {            
        // columns
        $table->id();
        $table->prototype('uuid');   
        $table->userId();
        $table->string('title')->nullable(true);
        $table->string('url')->nullable(false);
        $table->string('target')->nullable(true);        
        $table->text('options')->nullable(true);
        $table->dateCreated();
        $table->dateUpdated();
        // indexes   
        $table->index('url');  
        $table->index('title');          
    }

    /**
     * Update table
     *
     * @param \Arikaim\Core\Db\TableBlueprint $table
     * @return void
     */
    public function update($table) 
    {              
    }
}

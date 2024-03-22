<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Content\Models;

use Illuminate\Database\Eloquent\Model;

use Arikaim\Core\Db\Traits\Uuid;
use Arikaim\Core\Db\Traits\Find;
use Arikaim\Core\Db\Traits\UserRelation;

/**
 * Content db model class
 */
class Content extends Model 
{
    use Uuid,     
        Find,       
        UserRelation;
    
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'content';

    /**
     * Fillable attributes
     *
     * @var array
     */
    protected $fillable = [
        'status', 
        'key',
        'content_type',
        'content_id',
        'user_id'
    ];
    
    /**
     * Disable timestamps
     *
     * @var boolean
     */
    public $timestamps = false;
   

}

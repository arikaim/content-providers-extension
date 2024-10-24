<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Content\Actions;

use Arikaim\Core\Db\Model;
use Arikaim\Core\Utils\Utils;
use Arikaim\Core\Utils\Factory;
use Arikaim\Core\Actions\Action;

use Arikaim\Core\Interfaces\ImportModelInterface;

/**
* Model import from Json action
*/
class ModelImport extends Action 
{
    /**
     * Init action
     *
     * @return void
    */
    public function init(): void
    {
    }

    /**
     * Run action
     *
     * @param mixed ...$params
     * @return bool
     */
    public function run(...$params)
    {
        global $arikaim;

        $update = $this->getOption('update',true);
        $fileName = $this->getOption('file_name');
        $path = $this->getOption('path','');
        if (empty($fileName) == true) {
            $this->error("File name not set!");
            return false;
        }

        $fileName = $path . $fileName;
        $content = $arikaim->get('storage')->read($fileName);
        if ($content === false) {
            $this->error("Not valid file name!");
            return false;
        }

        $item = \json_decode($content,true);

        $modelClass = $item['model_class'] ?? null;
        $schemaClass = $item['schema_class'] ?? $modelClass;
        $unique = $item['unique'] ?? [];
        $extension = $item['extension'] ?? null; 
        $data = $item['data'];
        $relations = $item['relations'];

        $schema = Factory::createSchema($schemaClass,$extension);
        if ($schema == null) {
            $this->error("Not valid schema class or extension name!");
            return false;
        }

        //if (($schema instanceof ImportModelInterface) == false) {
            //$this->error("Db schema model not allow import!");
            //return false;
        //}

        $model = Model::create($modelClass,$extension);
        if ($model == null) {
           $this->error("Not valid model class or extension name!");
           return false;
        }

        print_r($relations);
        //$model->fill($data);
      //  $model->save();

        return ($this->hasError() == false);
    }

    /**
     * Get relations names
     *
     * @param object $model
     * @return array
     */
    public function getRelationNames(object $model): array
    {
        return \array_keys($model->getRelations());            
    }

    /**
     * Import model relations
     *
     * @param object $model
     * @param array  $data
     * @return void
     */
    public function importRelations(object $model, array $data)
    {

    } 

    /**
    * Init descriptor properties 
    *
    * @return void
    */
    protected function initDescriptor(): void
    {
    }

    /**
     * Create search item values
     *
     * @param array $keys
     * @param array $item
     * @return array
     */
    protected function createSearchValues(array $keys, array $items): array
    {
        $search = [];
        foreach ($keys as $key) {
            $search[$key] = $items[$key] ?? null;
        }

        return $search;
    }
}

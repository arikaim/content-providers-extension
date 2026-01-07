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
use Arikaim\Core\Db\Schema;

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

        $model = Model::create($modelClass,$extension);
        if ($model == null) {
           $this->error("Not valid model class or extension name!");
           return false;
        }

        Schema::schema()->disableForeignKeyConstraints();

        $this->importRelations($relations);
        // import model
        $this->saveModel($model,$data);
        
        Schema::schema()->enableForeignKeyConstraints();

        return ($this->hasError() == false);
    }

    /**
     * Save model
     *
     * @param object $model
     * @param array  $data
     * @return object
     */
    protected function saveModel(object $model, array $data): object
    {
        $info = $this->getFillable($model,$data);

        $item = $model->findById($data['uuid']);
        $item = ($item == null) ? $model->findByid($data['id']) : $item;
        
        if ($item == null) {
            $model->id = $data['id'];
            $model->incrementing = false;
            $saved = $model->create($info);
        } else {
            $item->update($info);
            $saved = $item;
        }
        
        return $saved;
    }

    /**
     * Get fileble attr values
     *
     * @param object $model
     * @param array  $data
     * @return array
     */
    protected function getFillable(object $model, array $data): array
    {
        $result = [];
        foreach ($model->getFillable() as $key) {
            $value = $data[$key] ?? null;
            $result[$key] = (\is_array($value) == true) ? Utils::jsonEncode($value) : $value;
        }

        return $result;
    }   

    /**
     * Fil model attributes
     *
     * @param object $model
     * @param array  $data
     * @return object
     */
    protected function fillModel(object $model, array $data): object
    {
        $model->id = $data['id'];

        foreach ($model->getFillable() as $key) {
            $model->{$key} = $data[$key] ?? null;
        }

        return $model;
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
     * @param array  $data
     * @return void
     */
    public function importRelations(array $data)
    {
        foreach ($data as $item) {
            $relationClass = $item['class'];
            $data = $item['data'];         
            $relation = new $relationClass();

            if (isset($data[0]) == true) {
                $this->importHasMany($relation,$data);
            } else {
                if (count($data) !== 0) {
                    $this->saveModel($relation,$data);
                }                
            }
        }
    } 

    /**
     * Import hasMany relation
     *
     * @param object $relation
     * @param array $data
     * @return void
     */
    public function importHasMany(object $relation, $data): void
    {
        foreach ($data as $item) {
            $this->saveModel($relation,$item);            
        }
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

<?php

namespace App\Services;

abstract class BaseService
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $_repository;

    /**
     * BaseServicesImpl constructor.
     */
    public function __construct()
    {
        $this->setRepository();
    }

    /**
     * get model
     * @return string
     */
    abstract public function getRepository();

    /**
     * Set repository
     */
    public function setRepository()
    {
        $this->_repository = app()->make(
            $this->getRepository()
        );
    }

    public function getFieldsSearchable()
    {
        return $this->_repository->getFieldsSearchable();
    }

    public function paginate($perPage, $search = [], $columns = ['*'], $withRelation = false)
    {
        return $this->_repository->paginate($perPage, $search, $columns, $withRelation);
    }

    public function all($search = [], $skip = null, $limit = null, $columns = ['*'], $withRelation = false)
    {
        return $this->_repository->all($search, $skip, $limit, $columns, $withRelation);
    }

    public function create($input)
    {
        return $this->_repository->create($input);
    }

    public function find($id, $columns = ['*'])
    {
        return $this->_repository->find($id, $columns);
    }

    public function findOrFail($id, $column = ['*'])
    {
        return $this->_repository->findOrFail($id, $column);
    }

    public function findOneByColumn($column, $value)
    {
        return $this->_repository->findOneByColumn($column, $value);
    }

    public function firstOrCreate(array $conditions, array $attributes = [])
    {
        return $this->_repository->firstOrCreate($conditions, $attributes);
    }

    public function update($input, $id)
    {
        return $this->_repository->update($input, $id);
    }

    public function updateOrCreate(array $conditions, array $attributes)
    {
        return $this->_repository->updateOrCreate($conditions, $attributes);
    }

    public function delete($id)
    {
        return $this->_repository->delete($id);
    }

    public function deleteByConditions(array $conditions)
    {
        return $this->_repository->deleteByConditions($conditions);
    }
}
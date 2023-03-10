<?php

namespace App\Repositories;

interface BaseRepository
{

    /**
     * Get searchable fields array
     *
     * @return array
     */
   	public function getFieldsSearchable();


    /**
     * Make Model instance
     *
     * @throws \Exception
     *
     * @return Model
     */
    public function makeModel();

    /**
     * Paginate records for scaffold.
     *
     * @param int $perPage
     * @param array $columns
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($perPage, $search, $columns, $withRelation);

    /**
     * Build a query for retrieving all records.
     *
     * @param array $search
     * @param int|null $skip
     * @param int|null $limit
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function allQuery($search, $skip, $limit, $withRelation);

    /**
     * Retrieve all records with given filter criteria
     *
     * @param array $search
     * @param int|null $skip
     * @param int|null $limit
     * @param array $columns
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function all($search, $skip, $limit, $columns, $withRelation);

    /**
     * Create model record
     *
     * @param array $input
     *
     * @return Model
     */
    public function create($input);

    /**
     * Find model record for given id
     *
     * @param int $id
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Model|null
     */
    public function find($id, $columns);

    /**
     * findOrFail model
     *
     * @param $id
     * @param array $column
     * @return mixed
     */
    public function findOrFail($id, $column = ['*']);

    /**
     * Retrieve first record by given value column
     *
     * @param string $column
     * @param $value
     * @return Model
     */
    public function findOneByColumn($column, $value);

    /**
     * Get first or create new record
     *
     * @param array $conditions
     * @param array $attributes
     * @return Model
     */
    public function firstOrCreate(array $conditions, array $attributes = []);

    /**
     * Update model record for given id
     *
     * @param array $input
     * @param int $id
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Model
     */
    public function update($input, $id);

    /**
     *  Update an existing model or create a new model
     *
     * @param array $conditions
     * @param array $attributes
     * @return Model
     */
    public function updateOrCreate(array $conditions, array $attributes);

    /**
     *  Update an existing model or create a new model with column need insert or update
     *
     * @param array $value
     * @param array $conditions
     * @param array $column
     * @return Model
     */
    public function upsert(array $value = [], array $conditions, array $column);

    /**
     * @param int $id
     *
     * @throws \Exception
     *
     * @return bool|mixed|null
     */
    public function delete($id);

    /**
     * Delete model record for given conditions
     *
     * @param array $conditions
     * @return bool
     */
    public function deleteByConditions(array $conditions);
}
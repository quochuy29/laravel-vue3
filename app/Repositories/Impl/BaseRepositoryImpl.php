<?php

namespace App\Repositories\Impl;

use Illuminate\Container\Container as Application;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;

abstract class BaseRepositoryImpl implements BaseRepository
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @var Application
     */
    protected $app;

    /**
     * @param Application $app
     *
     * @throws \Exception
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->makeModel();
    }

    /**
     * Get searchable fields array
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->model->fillable;
    }

    /**
     * Configure the Model
     *
     * @return string
     */
    abstract public function model();

    /**
     * Make Model instance
     *
     * @throws \Exception
     *
     * @return Model
     */
    public function makeModel()
    {
        $model = $this->app->make($this->model());

        if (!$model instanceof Model) {
            throw new \Exception("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $this->model = $model;
    }

    /**
     * Paginate records for scaffold.
     *
     * @param int $perPage
     * @param array $columns
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($perPage, $search = [], $columns = ['*'], $withRelation = false)
    {
        return $this->allQuery($search, null, null, $withRelation)->paginate($perPage, $columns);
    }

    /**
     * Build a query for retrieving all records.
     *
     * @param array $search
     * @param int|null $skip
     * @param int|null $limit
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function allQuery($search = [], $skip = null, $limit = null, $withRelation = false)
    {
        $model = $this->model;
        $conditions = [];
        if (count($search)) {
            foreach ($search as $key => $value) {
                if (in_array($key, $this->model->entityAttributes)) {
                    if (is_array($value)) {
                        $model = $model->whereIn($key, $value);
                    } else {
                        $operator = $this->getOperator($value);
                        //$conditions[] = [$key, $operator, $value];
                       $model = $model->where($key, $operator, $value);
                    }
                }
            }
        }

        //$model = $this->model->where($conditions);
        if (!is_null($skip)) {
            $model = $model->skip($skip);
        }

        if ($withRelation) {
            $model = $model->with($withRelation);
        }

        if (!is_null($limit)) {
            $model = $model->limit($limit);
        }

        if (isset($search["sort"])) {
            $sorts = is_array($search["sort"]) ? $search["sort"] : [$search["sort"]];
            foreach ($sorts as $item) {
                $sortField = $this->getOrderBy($item);
                if (in_array($sortField["field"], $this->model->entityAttributes)) {
                    $model = $model->orderBy($sortField["field"], $sortField["sort"]);
                }
            }
        }

        return $model;
    }

    private function getOperator($inputString)
    {
        $inputString = trim($inputString);
        $firstElement = substr($inputString, 0, 1);
        $twoFirstElement = substr($inputString, 0, 2);
        $lastElement = substr($inputString, -1);

        if (in_array($twoFirstElement, ["<=", ">="])) {
            return $twoFirstElement;
        }
        if ($firstElement == "%" || $lastElement == "%") {
            return "LIKE";
        }

        switch ($firstElement) {
            case ">":
                return ">";
                break;
            case "<":
                return "<";
                break;
            case "=":
            default:
                return "=";
                break;
        }

        return "=";
    }

    protected function getOrderBy($fieldName)
    {
        $fieldName = trim($fieldName);
        $firstElement = substr($fieldName, 0, 1);
        if ($firstElement == "-") {
            $sort = "DESC";
            $fieldName = substr($fieldName, 1, strlen($fieldName));
        } else {
            $sort = "ASC";
        }
        return ["field" =>  $fieldName, "sort" => $sort];
    }

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
    public function all($search = [], $skip = null, $limit = null, $columns = ['*'], $withRelation = false)
    {
        $query = $this->allQuery($search, $skip, $limit, $withRelation);

        return $query->get($columns);
    }

    /**
     * Create model record
     *
     * @param array $input
     *
     * @return Model
     */
    public function create($input)
    {
        $model = $this->model->newInstance($input);

        $model->save();

        return $model;
    }

    /**
     * Find model record for given id
     *
     * @param int $id
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Model|null
     */
    public function find($id, $columns = ['*'])
    {
        $query = $this->model->newQuery();

        return $query->find($id, $columns);
    }

    /**
     * findOrFail model
     *
     * @param $id
     * @param array $column
     * @return mixed
     */
    public function findOrFail($id, $column = ['*'])
    {
        return $this->model->findOrFail($id, $column);
    }

    /**
     * Retrieve first record by given value column
     *
     * @param string $column
     * @param $value
     * @return Model
     */
    public function findOneByColumn($column, $value)
    {
        return $this->model->firstWhere($column, $value);
    }

    /**
     * Get first or create new record
     *
     * @param array $conditions
     * @param array $attributes
     * @return Model
     */
    public function firstOrCreate($conditions, $attributes = [])
    {
        return $this->model->firstOrCreate($conditions, $attributes);
    }

    /**
     * Update model record for given id
     *
     * @param array $input
     * @param int $id
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Model
     */
    public function update($input, $id)
    {
        $query = $this->model->newQuery();

        $model = $query->findOrFail($id);

        $model->fill($input);

        $model->save();

        return $model;
    }

    /**
     * Update an existing model or create a new model
     *
     * @param array $conditions
     * @param array $attributes
     * @return Model
     */
    public function updateOrCreate(array $conditions, array $attributes)
    {
        return $this->model->updateOrCreate($conditions, $attributes);
    }

    /**
     * Update an existing model or create a new model
     *
     * @param array $conditions
     * @param array $attributes
     * @return Model
     */
    public function upsert(array $value = [], array $conditions, array $column)
    {
        return $this->model->upsert($value, $conditions, $column);
    }

    /**
     * @param int $id
     *
     * @throws \Exception
     *
     * @return bool|mixed|null
     */
    public function delete($id)
    {
        $query = $this->model->newQuery();

        $model = $query->findOrFail($id);

        return $model->delete();
    }

    /**
     * Delete model record for given conditions
     *
     * @param array $conditions
     * @return bool
     */
    public function deleteByConditions(array $conditions)
    {
        return $this->model->where($conditions)->delete();
    }
}
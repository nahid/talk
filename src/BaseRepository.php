<?php


namespace Nahid\Talk;


use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    /**
     * @var Model
     */
    protected $model = null;

    public function __construct()
    {
        $this->model = $this->makeModel();
    }

    public function __call($method, $arguments)
    {
        return call_user_func_array([$this->model, $method], $arguments);
    }

    protected function makeModel()
    {
        $model = $this->takeModel();
        if (is_null($this->model)) {
            $this->model = new $model();
        }

        return $this->model;
    }

    abstract public function takeModel();

    public function update($id, $data)
    {
        $model = $this->model->find($id);

        return $model->update($data);
    }


    public function create($data)
    {
        return $this->model->create($data);
    }

    public function delete($id)
    {
        $model = $this->model->find($id);

        return $model->delete();
    }
}

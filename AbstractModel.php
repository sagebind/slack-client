<?php
namespace Slackyboy\Slack;

abstract class AbstractModel
{
    /**
     * @var ApiClient A reference to the API client.
     */
    protected $client;

    /**
     * @var array The model's data cached from the remote server.
     */
    protected $data = [];

    /**
     * @var bool Indicates if remote data has been fetched yet.
     */
    private $dataFetched = false;

    /**
     * Creates a model object from a data array.
     *
     * @param array $data An array of model data.
     *
     * @return AbstractModel
     */
    final public static function fromData(ApiClient $client, array $data)
    {
        $class = new \ReflectionClass(get_called_class());
        $model = $class->newInstanceWithoutConstructor();
        $model->client = $client;
        $model->data = $data;
        $model->dataFetched = true;
        return $model;
    }

    /**
     * Gets the data associated with this model.
     *
     * @return array An array of model data.
     */
    final protected function getData()
    {
        if (!$this->dataFetched && method_exists($this, 'fetchData')) {
            $this->data = $this->fetchData();
            $this->dataFetched = true;
        }

        return $this->data;
    }
}

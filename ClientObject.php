<?php
namespace Slackyboy\Slack;

abstract class ClientObject
{
    /**
     * @var ApiClient A reference to the API client.
     */
    private $client;

    /**
     * @var array The object's data cached from the remote server.
     */
    private $data = [];

    /**
     * @var string The remote ID of the object, if any.
     */
    private $id;

    /**
     * @var bool Indicates if remote data has been fetched yet.
     */
    private $dataFetched = false;

    /**
     * Creates a client object from its ID.
     *
     * @param string $id An array of model data.
     *
     * @return AbstractModel
     */
    final public static function fromId(ApiClient $client, $id)
    {
        $class = new \ReflectionClass(get_called_class());
        $instance = $class->newInstanceWithoutConstructor();
        $instance->client = $client;
        $instance->id = $id;
        return $instance;
    }

    /**
     * Creates a client object from a data array.
     *
     * @param array $data An array of model data.
     *
     * @return AbstractModel
     */
    final public static function fromData(ApiClient $client, array $data)
    {
        $class = new \ReflectionClass(get_called_class());
        $instance = $class->newInstanceWithoutConstructor();
        $instance->client = $client;
        if (isset($data['id'])) {
            $instance->id = $data['id'];
        }
        $instance->data = $data;
        $instance->dataFetched = true;
        return $instance;
    }

    public function __construct(ApiClient $client)
    {
        $this->client = $client;
    }

    /**
     * Gets the API client the object belongs to.
     *
     * @return ApiClient The API client the object belongs to.
     */
    final public function getClient()
    {
        return $this->client;
    }

    /**
     * Gets the object ID.
     *
     * @return string The object ID.
     */
    public function getId()
    {
        return $this->id;
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

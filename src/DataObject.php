<?php
namespace Slack;

/**
 * A serializable model object that stores its data in a hash.
 */
abstract class DataObject implements \JsonSerializable
{
    /**
     * @var array The object's data cache.
     */
    public $data = [];

    /**
     * Creates a data object from an array of data.
     *
     * @param array $data The array containing object data.
     *
     * @return self A new object instance.
     */
    public static function fromData(array $data)
    {
        $reflection = new \ReflectionClass(static::class);
        $instance = $reflection->newInstanceWithoutConstructor();
        $instance->data = $data;
        $instance->jsonUnserialize($data);
        return $instance;
    }

    /**
     * Returns scalar data to be serialized.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->data;
    }

    /**
     * Re-initializes the object when unserialized or created from a data array.
     *
     * @param array $data The object data.
     */
    public function jsonUnserialize(array $data)
    {
    }
}

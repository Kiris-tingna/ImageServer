<?php
namespace Lcy\Traits;
use Lcy\Traits\SingleTonTrait;

trait StaticCallTrait{
    use SingleTonTrait;
    /**
     * Allow all the methods of Instance to be called.
     *
     * @param string $name The name of the method to run
     * @param array $arguments The parameters to pass to the method
     *
     * @return mixed
     */
    public static function __callStatic($method, $arguments)
    {
        $name = $method."Action";
        return call_user_func_array(array(self::getInstance(), $name), $arguments);
    }
}
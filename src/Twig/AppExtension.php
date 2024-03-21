<?php


namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function __construct()
    {

    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getClass', [$this, 'getClass']),
            new TwigFunction('getStaticMethod', [$this, 'getStaticMethod']),
        ];
    }

    public function getFilters(): array
    {
        return [
        ];
    }


    public function getClass($entity): ?string
    {
        return (new \ReflectionClass($entity))->getName();
    }

    public function getStaticMethod(string $className, string $methodName)
    {
        if (class_exists($className) && method_exists($className, $methodName) && is_callable([$className, $methodName])) {
            return call_user_func([$className, $methodName]);
        }

        return null;
    }


}
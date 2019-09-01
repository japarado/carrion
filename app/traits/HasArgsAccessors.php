<?php 

namespace Carrion\Traits;

trait HasArgsAccessors
{
    public function getArgs(): array 
    {
        return $this->args;
    }

    public function setArgs(array $args): void
    {
        $this->args = $args;
    }
}

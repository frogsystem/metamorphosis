<?php
namespace Frogsystem\Metamorphosis\Constrains;

use Frogsystem\Metamorphosis\Contracts\Huggable;

/**
 * Class GroupHuggableTrait
 * @package Frogsystem\Metamorphosis\Constrains
 */
trait HuggableTrait
{
    /**
     * List of already hugged Huggables.
     * @var array
     */
    private $hugged = [];

    /**
     * Hugs this object.
     *
     * All hugs are mutual. An object that is hugged MUST in turn hug the other
     * object back by calling hug() on the first parameter. All objects MUST
     * implement a mechanism to prevent an infinite loop of hugging.
     *
     * @param Huggable $huggable
     *   The object that is hugging this object.
     */
    public function hug(Huggable $huggable)
    {
        // add huggable to the list of hugged objects and return the hug
        if (!in_array($huggable, $this->hugged)) {
            $this->hugged[] = $huggable;
            $huggable->hug($this);
        }
    }
}

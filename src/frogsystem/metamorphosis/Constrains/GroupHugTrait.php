<?php
namespace Frogsystem\Metamorphosis\Constrains;

/**
 * Trait to group hug any set of huggables by calling the classes hug implementation.
 * Class GroupHugTrait
 * @package Frogsystem\Metamorphosis\Constrains
 */
trait GroupHugTrait
{
    /**
     * Hugs a series of huggable objects.
     *
     * When called, this object MUST invoke the hug() method of every object
     * provided. The order of the collection is not significant, and this object
     * MAY hug each of the objects in any order provided that all are hugged.
     *
     * @param $huggables
     *   An array or iterator of objects implementing the Huggable interface.
     */
    public function groupHug($huggables)
    {
        foreach ($huggables as $huggable) {
            $this->hug($huggable);
        }
    }
}

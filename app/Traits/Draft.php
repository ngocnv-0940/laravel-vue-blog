<?php

namespace App\Traits;

use App\Scopes\StatusScope;

trait Draft
{
    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new StatusScope);
    }

    public function getStatusColumn()
    {
        return 'is_public';
    }

    public function publish()
    {
        $this->{$this->getStatusColumn()} = true;
        $result = $this->save();
        return $result;
    }

    public function saveAsDraft()
    {
        $this->{$this->getStatusColumn()} = false;
        $result = $this->save();
        return $result;
    }
}

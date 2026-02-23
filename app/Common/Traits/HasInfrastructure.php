<?php

namespace App\Common\Traits;

use App\Models\Core\Infrastructure;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasInfrastructure
{
    public static function bootHasInfrastructure(): void
    {
        static::created(function ($model) {
            $model->infrastructure()->create();
        });

        static::deleting(function ($model) {
            $model->infrastructure?->delete();
        });
    }

    public function infrastructure(): MorphOne
    {
        return $this->morphOne(Infrastructure::class, 'infrastructurable');
    }

    public function getInfrastructureId(): ?int
    {
        return $this->infrastructure?->id;
    }
}

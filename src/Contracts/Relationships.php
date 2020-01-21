<?php

namespace CodexShaper\DBM\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

interface Relationships
{
    public function has_one(): HasOne;

    public function has_many(): HasMany;

    public function belongs_to_many(): BelongsToMany;

    public function belongs_to(): BelongsTo;

    public function has_one_through(): HasOneThrough;

    public function has_many_through(): HasManyThrough;

    public function morph_one(): MorphOne;

    public function morph_to(): MorphTo;

    public function morph_many(): MorphMany;

    public function morph_to_many(): MorphToMany;

    public function morphed_by_many(): MorphToMany;
}

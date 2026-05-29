<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name'])]
class ModuleGroup extends Model
{
    use HasFactory, SoftDeletes;

    public function modules(): HasMany
    {
        return $this->hasMany(Module::class);
    }
}

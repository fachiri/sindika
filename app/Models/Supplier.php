<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Supplier extends Model
{
  use HasFactory;

  protected $fillable = ['name', 'address', 'phone_number'];

  protected $guarded = ['_token'];

  public function getRouteKeyName(): string
  {
    return 'uuid';
  }

  protected static function boot()
  {
    parent::boot();
    self::creating(function ($model) {
      $model->uuid = (string) Uuid::uuid4();
    });
  }

  public function stocks(): HasMany
  {
    return $this->hasMany(Stock::class);
  }

  protected function phoneNumber(): Attribute
  {
    return Attribute::make(
      set: fn(string $value) => str_replace('-', '', $value),
    );
  }
}

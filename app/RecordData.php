<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecordData extends Model
{
  protected $table = "record_data";

  public function record()
  {
    return $this->belongsTo('App\Record');
  }
}

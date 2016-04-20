<?php
namespace App\Api;

use JWTAuth;
use App\Record;
use Carbon\Carbon;
use App\RecordData;

class RecordApi
{

  # this function is to fill up the record
  # if we have the record type is checker, then it should fill with 0 (unchecked)
  # or 1 for checked
  # if the record type is counter, we simply fill up the field with integer >= 1
  public function insertData($record_id, $value)
  {
    $record = $this->getById($record_id);
    # we can only insert the record once a day, so we need to check it if then
    # user has recorded or not on the day
    if ($record = $this->getRecordDataOfTheDay($record)) {
      if (count($record->recordData) > 0) {
        # if true, sorry, we need to return an error
        return ['message' => 'could not assign a value twice a day'];
      }
    }

    if ($record->type === 'checker') {
      return $this->insertDataChecker($record, $value);
    }

    return $this->insertDataCounter($record, $value);
  }

  public function getById($record_id)
  {
    return JWTAuth::authenticate()->records()->find($record_id);
  }

  private function insertDataCounter($record, $value)
  {
    if ($value < 0) {
      return ['message' => 'the value must be greater than 0'];
    }

    # if lolos
    $recordData = new RecordData;
    $recordData->record_id = $record->id;
    $recordData->value = $value;
    $recordData->save();

    return $record;
  }

  private function insertDataChecker($record, $value)
  {
    # because this is a checker type, the value should only 0 or 1
    if ($value < 0 || $value > 1) {
      return ['message' => 'the value should only be 0 or 1'];
    }

    # if lolos, then, try to input the value
    $recordData = new RecordData;
    $recordData->record_id = $record->id;
    $recordData->value = $value;
    $recordData->save();

    return $record;
  }

  /**
  * this function used to get the record data of the day
  */
  public function getRecordDataOfTheDay($record)
  {
    return $this->getRecordDataByDate($record, Carbon::today()->toDateString());
  }

  public function getRecordDataByDate($record, $date)
  {
    $recordData = $record->recordData()->whereDate('created_at', '=', $date)->get();
    $record->recordData = $recordData;

    return $record;
  }

  public function getRecordDataByMonth($record, $year, $month)
  {
    $recordData = $record->recordData()->whereYear('created_at', $year)->whereMonth('created_at', $month)->get();
    $record->recordData = $recordData;

    return $record;
  }

  public function getRecordDataByYear($record, $year)
  {
    $recordData = $record->recordData()->whereYear('created_at', $year)->get();
    $record->recordData = $recordData;

    return $record;
  }
}

<?php

namespace App\Http\Controllers\Record;

use Illuminate\Http\Request;

use App\Api\RecordApi;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class RecordController extends Controller
{

  private $recordApi;

  public function __construct(RecordApi $recordApi)
  {
    $this->recordApi = $recordApi;

    $this->middleware(['jwt.auth', 'jwt.refresh']);
  }

  public function show($record_id)
  {
    $record = $this->recordApi->getById($record_id);
    $record = $this->recordApi->getRecordDataOfTheDay($record);

    return response()->json(compact('record'));
  }

  public function assignValue(Request $request, $record_id)
  {
    $validator = \Validator::make($request->all(), ['value' => 'required|numeric']);
    if ($validator->fails()) {
      return response()->json(['errors' => $validator->errors()], 500);
    }
    $value = $request->input('value');
    # TANBIH. can only assign value once a day!!
    $record = $this->recordApi->insertData($record_id, $value);

    # the result is array if the process error
    if (is_array($record)) {
      return response()->json($record, 500);
    }

    return response()->json(compact('record'));
  }
}

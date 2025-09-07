<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Exception;

class TimeApiSlotController extends ApiController
{
    public function getSlots(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
                    'date' => 'required|date|after_or_equal:today'
                ]);
        $request->validate([
        ]);

            if ($validatedData->fails()) {
                // throw new Exception($validatedData->messages()->first(), 1);

                 $this->response_json['status'] = 0;
                $this->response_json['message'] = $validatedData->messages()->first();
                return $this->responseError();
            }

        $date = $request->input('date');
        $dateCheck = Carbon::parse($date);
        if ($dateCheck->isToday()) {
            $startTime = Carbon::now()->addHour()->startOfHour()->format('H:i');
        } else {
            $startTime = '06:00';
        }


        $slots = $this->generateTimeSlots($startTime);

        $this->response_json['status'] = 1;
        $this->response_json['date'] = $date;
        $this->response_json['slots'] = $slots;
        $this->response_json['message'] = 'Time Slot SuccessFully';
        return response()->json($this->response_json, 200);

        // return response()->json([
        //     'date' => $date,
        //     'slots' => $slots
        // ]);
    }

    private function generateTimeSlots($startTime, $endTime = '21:00', $slotDuration = 60, $breakAfterSlots = 3, $breakDuration = 60)
    {
        $slots = [];
        $current = Carbon::createFromTimeString($startTime);
        $end = Carbon::createFromTimeString($endTime);
        $slotCount = 0;

        while ($current->lt($end)) {
            if ($slotCount === $breakAfterSlots) {
                $current->addMinutes($breakDuration);
                $slotCount = 0;
                continue;
            }

            $slotStart = $current->format('H:i');
            $slotEnd = $current->copy()->addMinutes($slotDuration)->format('H:i');

            if ($current->copy()->addMinutes($slotDuration)->gt($end)) {
                break;
            }

            $slots[] = [
                'start' => $slotStart,
                'end' => $slotEnd
            ];

            $current->addMinutes($slotDuration);
            $slotCount++;
        }

        return $slots;
    }

    public function generateSlots(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'start_date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'frequency_type' => 'required'
        ]);

        $startDate = $request->get('start_date');
        $startTime = $request->input('start_time');
        $endTime   = $request->input('end_time');
        $frequencyType  = $request->input('frequency_type');


        $slots = [];
        switch ($frequencyType) {
            case 'daily':
                $slots = $this->generateAlternateDaySlots($startDate, $startTime, $endTime, 30);
                break;

            case 'weekly_2':
                $slots = $this->generateAlternateDaySlots($startDate, $startTime, $endTime, 8);
                break;

            case 'weekly_1':
                $slots = $this->generateAlternateDaySlots($startDate, $startTime, $endTime, 4);
                break;

            case 'one_time':
                $slots[] = [
                    'date' => Carbon::parse($startDate)->format('Y-m-d'),
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                ];
                break;
        }

        return response()->json([
            'frequency_type' => $frequencyType,
            'total_slots' => count($slots),
            'slots' => $slots
        ]);
    }

    private function generateAlternateDaySlots($startDate, $startTime, $endTime, $totalWashes)
    {
        $slots = [];
        $start = Carbon::parse($startDate)->setTimeFromTimeString($startTime);
        $end   = Carbon::parse($endTime)->format('g:i A');

        if($totalWashes == 30){
            $dayCount = 1;
        }else{
            $dayCount = 2;
        }

        for ($i = 0; $i < $totalWashes; $i++) {
            $slots[] = [
                'date' => $start->copy()->addDays($i * $dayCount)->format('d-m-Y'),
                'start_time' => $start->format('g:i A'),
                'end_time' => $end
            ];
        }

        return $slots;
    }


}

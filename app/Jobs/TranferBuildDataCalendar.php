<?php

namespace App\Jobs;

use App\Repositories\Impl\CalendarRepositoryImpl;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TranferBuildDataCalendar implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $column_value = [
        'code',
        'date',
        'checkin',
        'checkout',
        'unpaid_leave',
        'paid_leave',
        'unpaid_flag',
        'late_flag',
        'early_flag'
    ];

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     */
    public function handle(CalendarRepositoryImpl $calendarRepo): void
    {
        $row = 10;
        $input = [
            [
                'name' => 'huypq',
                'code' => 'pLbMuC'
            ],
            [
                'name' => 'huypq1',
                'code' => 'dv900n'
            ],
            [
                'name' => 'huypq2',
                'code' => 'vP3sfp'
            ]
        ];
        $arr = [];
        for ($i = 0; $i >= $row; $i++) {
            $unix_start = strtotime("2017-10-10 08:30:00");
            $unix_end = strtotime("2017-10-10 13:30:00");
            $diff = $unix_end - $unix_start;
            $rndtime =  $unix_start + mt_rand(0, $diff);

            $unix_start1 = strtotime(date("Y-m-d") . " 13:00:00");
            $unix_end1 = strtotime(date("Y-m-d") . " 18:30:00");
            $diff1 = $unix_end1 - $unix_start1;
            $rndtime1 =  $unix_start1 + mt_rand(0, $diff1);

            $defaultStartTime = strtotime('08:30');
            $defaultEndTime = strtotime('17:30');
            $late_flag = 0;
            $early_flag = 0;
            $unpaid_flag = 0;


            if (strtotime($rndtime) > $defaultStartTime) {
                $late_flag = 1;
            }

            if (strtotime($rndtime1) < $defaultEndTime) {
                $early_flag = 1;
            }

            $unpaid_leave = $this->calculateUnpaidLeave($rndtime, $rndtime1, $defaultStartTime, $defaultEndTime);

            if ($unpaid_leave > 0.188) {
                $unpaid_flag = 1;
            }

            $arr[$i] = [
                'code' => '',
                'user_code' => $input[array_rand($input)]['code'],
                'user_name' => $input[array_rand($input)]['name'],
                'date' => date("Y-m-d"),
                'checkin' => $rndtime,
                'checkout' => $rndtime1,
                'unpaid_leave' => $unpaid_leave,
                'unpaid_flag' => $unpaid_flag,
                'late_flag' => $late_flag,
                'early_flag' => $early_flag
            ];
        }

        $calendarRepo->upsert($arr, $this->column_value, []);
    }

    public function calculateUnpaidLeave($checkIn, $checkOut, $defaultStartTime, $defaultEndTime)
    {
        $breakStartTime = strtotime('12:00');
        $breakEndTime = strtotime('13:00');

        if ($checkOut < $defaultEndTime) {
            if ($defaultEndTime <= $breakStartTime && $checkOut >= $breakEndTime) {
                $durationIn = (($defaultEndTime - $checkOut - 3600)/8)/3600;
            } else {
                $durationIn = (($defaultEndTime - $checkOut)/8)/3600;
            }
        }
        
        if ($checkIn > $defaultStartTime) {
            if ($defaultStartTime <= $breakStartTime && $checkIn >= $breakEndTime) {
                $durationOut = (($checkIn - $defaultStartTime - 3600)/8)/3600;
            } else {
                $durationOut = (($checkIn - $defaultStartTime)/8)/3600;
            }
        }
        
        if ($durationIn > 0 && $durationOut > 0) {
            $duration = round($durationIn, 3) + round($durationOut, 3);
            return $duration;
        }

        if ($durationIn > 0) {
            return round($durationIn, 3);
        }

        return round($durationOut, 3);
    }
}

<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Repositories\Impl\CalendarRepositoryImpl;
use Illuminate\Console\Command;

class TranferBuildDataCalendar extends Command
{
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
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'TranferBuildDataCalendar:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
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
        for ($i = 0; $i <= $row; $i++) {
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


            if ($rndtime > $defaultStartTime) {
                $late_flag = 1;
            }

            if ($rndtime1 < $defaultEndTime) {
                $early_flag = 1;
            }

            $unpaid_leave = $this->calculateUnpaidLeave($rndtime, $rndtime1, $defaultStartTime, $defaultEndTime);

            if ($unpaid_leave > 0.188) {
                $unpaid_flag = 1;
            }
            $ran_keys = array_rand($input);

            $arr[$i] = [
                'code' => $this->generateRandomString(),
                'user_code' => $input[$ran_keys]['code'],
                'user_name' => $input[$ran_keys]['name'],
                'date' => date("Y-m-d"),
                'checkin' => date('H:i', $rndtime),
                'checkout' => date('H:i', $rndtime1),
                'unpaid_leave' => $unpaid_leave,
                'paid_leave' => '',
                'unpaid_flag' => $unpaid_flag,
                'late_flag' => $late_flag,
                'early_flag' => $early_flag
            ];
        }
        $calendarRepo->upsert($arr, $this->column_value, []);
    }

    public  function generateRandomString($length = 6) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        if (User::where('code', $randomString)->exists()) {
            $this->generateRandomString(6);
        }
        
        return $randomString;
    }

    public function calculateUnpaidLeave($checkIn, $checkOut, $defaultStartTime, $defaultEndTime)
    {
        $breakStartTime = strtotime('12:00');
        $breakEndTime = strtotime('13:00');
        $durationOut = 0;
        $durationIn = 0;

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

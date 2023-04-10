<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Repositories\Impl\CalendarRepositoryImpl;
use App\Services\CalendarService;
use Illuminate\Console\Command;

class TranferBuildDataCalendar extends Command
{
    protected $column_value = [
        'code',
        'date',
        'checkin_origin',
        'checkout_origin',
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
    public function handle(CalendarRepositoryImpl $calendarRepo, CalendarService $calendarSer): void
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
            $unix_start = strtotime("08:30");
            $unix_end = strtotime("13:00");
            $diff = $unix_end - $unix_start;
            $start_time =  $unix_start + mt_rand(0, $diff);

            $unix_start1 = strtotime("13:00");
            $unix_end1 = strtotime("18:30");
            $diff1 = $unix_end1 - $unix_start1;
            $end_time =  $unix_start1 + mt_rand(0, $diff1);

            $defaultStartTime = strtotime('08:30');
            $defaultEndTime = strtotime('17:30');
            $breakStartTime = strtotime('12:00');
            $breakEndTime = strtotime('13:00');
            $late_flag = 0;
            $early_flag = 0;
            $unpaid_flag = 0;
            $late_time = 0;
            $early_time = 0;

            if ($start_time > $defaultStartTime) {
                $late_time = $calendarSer->requestEditEarlyLate($start_time, $defaultStartTime, $breakStartTime, $breakEndTime, $defaultEndTime, $defaultStartTime);
                $late_flag = 1;
            }

            if ($end_time < $defaultEndTime) {
                $early_time = $calendarSer->requestEditEarlyLate($defaultEndTime, $end_time, $breakStartTime, $breakEndTime, $defaultEndTime, $defaultStartTime);
                $early_flag = 1;
            }

            $unpaid_leave = $late_time + $early_time;

            if ($unpaid_leave > 0.188) {
                $unpaid_flag = 1;
            }

            $ran_keys = array_rand($input);

            $arr[$i] = [
                'code' => $this->generateRandomString(),
                'user_code' => $input[$ran_keys]['code'],
                'user_name' => $input[$ran_keys]['name'],
                'date' => date("Y-m-d"),
                'checkin_origin' => date('H:i', $start_time),
                'checkout_origin' => date('H:i', $end_time),
                'checkin' => date('H:i', $start_time),
                'checkout' => date('H:i', $end_time),
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

}

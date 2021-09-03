<?php
namespace Modules\Smallticket\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * @author liming
 * @date 2021-08-16
 */
class SmallTicketSettingSeeder extends Seeder
{
    public function run()
    {
        if (Schema::hasTable('small_ticket_setting')){
            $info = DB::table('small_ticket_setting')->first();
            if(!$info){
                $arr = $this->defaultInfo();
                if(!empty($arr) && is_array($arr)) {
                    $created_at = $updated_at = date("Y-m-d H:i:s");
                    foreach ($arr as $item) {
                        DB::table('small_ticket_setting')->insert([
                            'code' => $item['code'],
                            'na' => $item["na"],
                            'va' => $item["va"],
                            'sort' => $item["sort"],
                            'created_at' => $created_at,
                            'updated_at' => $updated_at,
                        ]);
                    }
                }
            }
        }
    }

    /**
     * 新增小票打印设置信息
     * @parent small_ticket_id  选择打印机  small_ticker 表ID
     * @parent order_printer_type  订单打印方式(以中文逗号分割字符串)：1=下单打印  2=付款打印  3=确认收货打印
     * @parent is_order_attr  是否开启打印规格  0=关闭  1=开启
     */
    private function defaultInfo()
    {
        return [
            ["code" => "small_ticket_id", "na" => "选择打印机", "va" => "0", "sort" => 0],
            ["code" => "order_printer_type", "na" => "订单打印方式", "va" => "", "sort" => 1],
            ["code" => "is_order_attr", "na" => "是否打印规格", "va" => "0", "sort" => 2],
        ];
    }
}
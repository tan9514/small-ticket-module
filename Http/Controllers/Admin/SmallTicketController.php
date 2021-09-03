<?php
// @author liming
namespace Modules\Smallticket\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Smallticket\Http\Controllers\Controller;
use Modules\Smallticket\Http\Requests\Admin\SmallTicketEditRequest;
use Modules\Smallticket\Http\Requests\Admin\SmallTicketSettingRequest;
use Modules\Smallticket\Entities\SmallTicket;
use Modules\Smallticket\Entities\SmallTicketSetting;

class SmallTicketController extends Controller
{
    /**
     * 分页列表
     */
    public function list()
    {
        return view('smallticketview::admin.small_ticket.list');
    }

    /**
     * ajax获取列表数据
     */
    public function ajaxList(Request $request)
    {
        $pagesize = $request->input('limit'); // 每页条数
        $page = $request->input('page',1);//当前页
        $where = [];

        $name = $request->input('name');
        if($name != "") $where[] = ["name", "like", "%{$name}%"];

        //获取总条数
        $count = SmallTicket::where($where)->count();

        //求偏移量
        $offset = ($page-1)*$pagesize;
        $list = SmallTicket::where($where)
            ->offset($offset)
            ->limit($pagesize)
            ->orderBy("id", "desc")
            ->get();
        return $this->success(compact('list', 'count'));
    }

    /**
     * 新增|编辑打印机信息
     * @param $id
     */
    public function edit(SmallTicketEditRequest $request)
    {
        if($request->isMethod('post')) {
            $request->check();
            $data = $request->post();

            if(isset($data["id"])){
                $info = SmallTicket::where("id",$data["id"])->first();
                if(!$info) return $this->failed('数据不存在');
            }else{
                $info = new SmallTicket();
            }

            $info->name = $data["name"];
            $info->brand = $data["brand"];
            $info->type = $data["type"] ?? '';
            $info->url = $data["url"] ?? '';
            $info->setting = $data["setting"];
            switch ($info->brand){
                case "365云打印":
                    if(!isset($info->setting["device_no"]) || $info->setting["device_no"] == "") return $this->failed('打印机编号不能为空');
                    if(!isset($info->setting["key"]) || $info->setting["key"] == "") return $this->failed('打印机密钥不能为空');
                    break;
                case "易联云":
                    if(!isset($info->setting["client_id"]) || $info->setting["client_id"] == "") return $this->failed('开发者应用ID不能为空');
                    if(!isset($info->setting["api_key"]) || $info->setting["api_key"] == "") return $this->failed('apiKey不能为空');
                    if(!isset($info->setting["machine_code"]) || $info->setting["machine_code"] == "") return $this->failed('打印机终端号不能为空');
                    if(!isset($info->setting["machine_key"]) || $info->setting["machine_key"] == "") return $this->failed('打印机终端密钥不能为空');
                    break;
                case "飞鹅":
                    if(!isset($info->setting["user"]) || $info->setting["user"] == "") return $this->failed('飞鹅后台登录名不能为空');
                    if(!isset($info->setting["ukey"]) || $info->setting["ukey"] == "") return $this->failed('UKEY不能为空');
                    if(!isset($info->setting["sn"]) || $info->setting["sn"] == "") return $this->failed('打印机编号不能为空');
                    if(!isset($info->setting["key"]) || $info->setting["key"] == "") return $this->failed('打印机key不能为空');
                    break;
                default:
                    return $this->failed('目前只支持365云打印、易联云、飞鹅');
            }
            $info->setting = json_encode($info->setting, JSON_UNESCAPED_UNICODE);
            if(!$info->save()) return $this->failed('操作失败');
            return $this->success();
        } else {
            $id = $request->input('id') ?? 0;
            $brandList = config("smallticketconfig.brand");
            if($id > 0){
                $info = SmallTicket::where('id',$id)->first();
                $title = "编辑打印机";
                $info->setting = json_decode($info->setting, true);
                foreach ($brandList as &$item){
                    if($item["name"] == $info->brand){
                        foreach ($item["param"] as $k => $v){
                            $item["param"][$k]["va"] = $info->setting[$k];
                        }
                        break;
                    }
                }
                $oldType = $info->type;
            }else{
                $info = new SmallTicket();
                $title = "新增打印机";
                $oldType = "";
            }
            return view('smallticketview::admin.small_ticket.edit', compact('info', 'title', 'brandList', 'oldType'));
        }
    }

    /**
     * 删除打印机
     */
    public function del(Request $request)
    {
        if($request->isMethod('post')){
            $id = $request->input('id');
            $info = SmallTicket::where('id', $id)->first();
            if (!$info) return $this->failed("数据不存在");
            DB::beginTransaction();
            try {
                if (!$info->delete()) throw new \Exception("操作失败：删除数据失败");
                $info = SmallTicketSetting::where("code", "small_ticket_id")->first();
                if($info && $info->va == $id) {
                    if (!SmallTicketSetting::where("code", "small_ticket_id")->update([
                        "va" => 0,
                    ])) throw new \Exception("操作失败: 修改配置失败");
                }

                DB::commit();
                return $this->success();
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->failed($e->getMessage());
            }
        }
        return $this->failed('请求出错.');
    }

    /**
     * @param SmallTicketSettingRequest $request
     */
    public function setting(SmallTicketSettingRequest $request)
    {
        if($request->isMethod('post')) {
            $request->check();
            $data = $request->post();
            if(empty($data)) return $this->failed('数据不存在');
            // 修改数据
            DB::beginTransaction();
            try {
                foreach ($data as $k => $item){
                    if($k == "small_ticket_id"){
                        $ii = SmallTicket::where("id", $item)->first();
                        if(!$ii) return $this->failed('打印机不存在');
                    }

                    if(SmallTicketSetting::where("code",$k)->first()){
                        if(!SmallTicketSetting::where("code",$k)->update([
                            "va" => is_array($item) ? implode(",",$item) : $item,
                        ])) throw new \Exception("操作失败: 修改{$k}失败");
                    }else{
                        $info = new SmallTicketSetting();
                        $info->na = $k;
                        $info->code = $k;
                        $info->va = is_array($item) ? implode(",",$item) : $item;
                        if(!$info->save()) throw new \Exception("操作失败: 修改{$k}失败");
                    }
                }

                DB::commit();
                return $this->success();
            }catch (\Exception $e){
                DB::rollBack();
                return $this->failed($e->getMessage());
            }
        } else {
            $pList = [
                ["id" => 0, "name" => "请选择打印机"],
            ];
            $printerList = SmallTicket::select("id","name")->orderBy("id")->get()->toArray();
            $pList = array_merge($pList, $printerList);
            $list = SmallTicketSetting::orderBy("sort")->get();
            $title = "打印设置";
            return view('smallticketview::admin.small_ticket.setting', compact('pList', 'list', 'title'));
        }
    }
}

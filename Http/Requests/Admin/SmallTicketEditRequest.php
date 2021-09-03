<?php

namespace Modules\Smallticket\Http\Requests\Admin;

use Modules\Smallticket\Http\Requests\BaseRequest;

class SmallTicketEditRequest extends BaseRequest
{
    /**
     * 判断用户是否有请求权限
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * 获取规则
     * @return string[]
     */
    public function newRules()
    {
        return [
            'id' => 'nullable|integer|min:1',
            'name' => 'required|string|min:1|max:100',
            'brand' => 'required|string|min:1|max:100',
            'type' => 'nullable|string|min:1|max:100',
            'url' => 'nullable|url',
            'setting' => 'required|array',
            'setting.times' => 'required|integer|min:1'
        ];
    }

    /**
     * 获取自定义验证规则的错误消息
     * @return array
     */
    public function messages()
    {
        return [
//            'phone.regex' => "请输入正确的 :attribute",
        ];
    }

    /**
     * 获取自定义参数别名
     * @return string[]
     */
    public function attributes()
    {
        return [
            "name" => "打印机名称",
            "brand" => "打印机品牌",
            "url" => "打印机官网",
            "setting" => "打印机基础信息",
            "setting.times" => "打印机联数",
        ];
    }

    /**
     * 验证规则
     */
    public function check()
    {
        $validator = \Validator::make($this->all(), $this->newRules(), $this->messages(), $this->attributes());
        $error = $validator->errors()->first();
        if($error){
            return $this->resultErrorAjax($error);
        }
    }
}

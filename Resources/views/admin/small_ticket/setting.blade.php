@extends('admin.public.header')
@section('title',$title)
@section('listcontent')
    <div class="layui-form layuimini-form">
        @foreach($list as $item)
        <div class="layui-form-item">
            <label class="layui-form-label">{{$item->na}}</label>
            <div class="layui-input-block">
                @if($item->code == "small_ticket_id")
                <select name="small_ticket_id">
                    @foreach($pList as $info)
                        <option value="{{$info['id']}}" @if($item->va == $info['id']) selected @endif>{{$info['name']}}</option>
                    @endforeach
                </select>
                @elseif($item->code == "order_printer_type")
                <input type="hidden" name="order_printer_type[]" />
                <input type="checkbox" name="order_printer_type[]" value="1" title="下单打印" @if(in_array(1, explode(",",$item->va))) checked @endif >
                <input type="checkbox" name="order_printer_type[]" value="2" title="付款打印" @if(in_array(2, explode(",",$item->va))) checked @endif >
                <input type="checkbox" name="order_printer_type[]" value="3" title="确认收货打印" @if(in_array(3, explode(",",$item->va))) checked @endif >
                @elseif($item->code == "is_order_attr")
                <input type="radio" name="is_order_attr" value="1" title="开启" @if($item->va == "1") checked @endif>
                <input type="radio" name="is_order_attr" value="0" title="关闭" @if($item->va == "0") checked @endif >
                @else
                @endif
            </div>
        </div>
        @endforeach

        <div class="hr-line"></div>

        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn layui-btn-normal" id="saveBtn" lay-submit lay-filter="saveBtn">保存</button>
            </div>
        </div>

    </div>
@endsection

@section('listscript')
    <script type="text/javascript">
        layui.use(['iconPickerFa', 'form', 'layer', 'upload'], function () {
            var iconPickerFa = layui.iconPickerFa,
                form = layui.form,
                layer = layui.layer,
                upload = layui.upload,
                $ = layui.$;

            //监听提交
            form.on('submit(saveBtn)', function(data){
                $("#saveBtn").addClass("layui-btn-disabled");
                $("#saveBtn").attr('disabled', 'disabled');
                $.ajax({
                    url:'/admin/small_ticket/setting',
                    type:'post',
                    data:data.field,
                    dataType:'JSON',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success:function(res){
                        if(res.code==0){
                            layer.msg(res.message,{icon: 1},function (){
                                location.reload();
                            });
                        }else{
                            layer.msg(res.message,{icon: 2},function (){
                                location.reload();
                            });
                            $("#saveBtn").removeClass("layui-btn-disabled");
                            $("#saveBtn").removeAttr('disabled');
                        }
                    },
                    error:function (data) {
                        layer.msg(res.message,{icon: 2});
                        $("#saveBtn").removeClass("layui-btn-disabled");
                        $("#saveBtn").removeAttr('disabled');
                        location.reload();
                    }
                });
            });
        });
    </script>
@endsection
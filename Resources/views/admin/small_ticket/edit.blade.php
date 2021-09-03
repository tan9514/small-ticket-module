@extends('admin.public.header')
@section('title',$title)
@section('listcontent')
    <div class="layui-form layuimini-form">
        @if(isset($info->id))
        <input type="hidden" name="id" value="{{$info->id}}" />
        @endif

        <div class="layui-form-item">
            <label class="layui-form-label required">打印机名称</label>
            <div class="layui-input-block">
                <input type="text" name="name" lay-verify="required" lay-reqtext="打印机名称不能为空" placeholder="请输入打印机名称" value="{{$info->name ?? ''}}" class="layui-input" />
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label required">打印机品牌</label>
            <div class="layui-input-block">
                <select name="brand" lay-filter="brand">
                    @foreach($brandList as $k => $brand)
                        <option value="{{$brand['name']}}" @if(isset($info->brand) && $info->brand == $brand['name']) selected @endif>{{$brand['name']}}</option>
                    @endforeach
                </select>
                <div id="toUrl" style="font-size: 10px; margin-top: 5px;"></div>
            </div>
        </div>

        <div id="showHtml"></div>

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

            var brandObj = eval('<?php echo json_encode($brandList);?>');
            var oldType = "{{$oldType}}";
            // 初始渲染
            var brandValue = $("select[name='brand'] option:selected").val();
            if(brandValue.length > 0){
                selectSwitch(brandValue);
            }
            // 监听下拉选择
            form.on('select(brand)', function(data){
                selectSwitch(data.value);
            });
            function selectSwitch(value){
                switch (value) {
                    case "365云打印":
                        setShowHtml(0);
                        break;
                    case "易联云":
                        setShowHtml(1);
                        break;
                    case "飞鹅":
                        setShowHtml(2);
                        break;
                    default:
                        return false;
                }
            }
            function setShowHtml(key){
                $("#toUrl").html("<input type='hidden' name='url' value='"+brandObj[key].url+"' /><a style='color: #00a0e9' href='"+brandObj[key].url+"' target='_blank'>"+brandObj[key].name+"</a>")

                let htm = "";
                if(brandObj[key].hasOwnProperty("type")){
                    let sel = '<div class="layui-form-item">';
                    sel += '<label class="layui-form-label required">打印机类型</label>';
                    sel += '<div class="layui-input-block">';
                    sel += '<select name="type">';
                    for (let i in brandObj[key].type){
                        if(brandObj[key].type[i] === oldType){
                            sel += '<option value="'+brandObj[key].type[i]+'" selected >'+brandObj[key].type[i]+'</option>';
                        }else{
                            sel += '<option value="'+brandObj[key].type[i]+'">'+brandObj[key].type[i]+'</option>';
                        }
                    }
                    sel += "</select>";
                    sel += '</div>';
                    sel += '</div>';
                    htm += sel;
                }

                var param = brandObj[key].param;
                $.each(param, function (k, v){
                    let inp = '<div class="layui-form-item">';
                    inp += '<label class="layui-form-label required">'+v.la+'</label>';
                    inp += '<div class="layui-input-block">';
                    inp += '<input type="text" name="setting['+k+']" lay-verify="required" lay-reqtext="'+v.la+'不能为空" placeholder="请输入'+v.la+'" value="'+v.va+'" class="layui-input" />';
                    inp += '</div>';
                    inp += '</div>';
                    htm += inp;
                })

                $("#showHtml").html(htm);
                form.render();
            }

            //监听提交
            form.on('submit(saveBtn)', function(data){
                $("#saveBtn").addClass("layui-btn-disabled");
                $("#saveBtn").attr('disabled', 'disabled');
                $.ajax({
                    url:'/admin/small_ticket/edit',
                    type:'post',
                    data:data.field,
                    dataType:'JSON',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success:function(res){
                        if(res.code==0){
                            layer.msg(res.message,{icon: 1},function (){
                                parent.location.reload();
                            });
                        }else{
                            layer.msg(res.message,{icon: 2});
                            $("#saveBtn").removeClass("layui-btn-disabled");
                            $("#saveBtn").removeAttr('disabled');
                        }
                    },
                    error:function (data) {
                        layer.msg(res.message,{icon: 2});
                        $("#saveBtn").removeClass("layui-btn-disabled");
                        $("#saveBtn").removeAttr('disabled');
                    }
                });
            });
        });
    </script>
@endsection
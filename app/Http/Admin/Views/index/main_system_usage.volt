<div class="layui-card layui-text">
    <div class="layui-card-header">{{ locale._('system_usage') }}</div>
    <div class="layui-card-body">
        <table class="layui-table">
            <colgroup>
                <col width="40%">
                <col>
            </colgroup>
            <tbody>
            <tr>
                <td>{{ locale._('disk_usage') }}</td>
                <td>{{ locale._('disk_usage_x',{'total':server_info.disk.total,'used':server_info.disk.usage}) }}</td>
            </tr>
            <tr>
                <td>{{ locale._('memory_usage') }}</td>
                <td>{{ locale._('memory_usage_x',{'total':server_info.memory.total,'used':server_info.memory.usage}) }}</td>
            </tr>
            <tr>
                <td>{{ locale._('load_average') }}</td>
                <td>{{ server_info.cpu[0] }} {{ server_info.cpu[1] }} {{ server_info.cpu[2] }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

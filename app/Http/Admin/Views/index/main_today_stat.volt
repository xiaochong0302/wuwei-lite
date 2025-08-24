<div class="layui-card kg-stats">
    <div class="layui-card-header">{{ locale._('daily_stats') }}</div>
    <div class="layui-card-body">
        <div class="layui-row layui-col-space10">
            <div class="layui-col-md4">
                <div class="kg-stat-card">
                    <div class="count">{{ '%0.2f'|format(today_stat.total_sales) }}</div>
                    <div class="name">{{ locale._('stat_total_sales') }}</div>
                </div>
            </div>
            <div class="layui-col-md4">
                <div class="kg-stat-card">
                    <div class="count">{{ today_stat.new_users }}</div>
                    <div class="name">{{ locale._('stat_new_users') }}</div>
                </div>
            </div>
            <div class="layui-col-md4">
                <div class="kg-stat-card">
                    <div class="count">{{ today_stat.vip_users }}</div>
                    <div class="name">{{ locale._('stat_vip_users') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

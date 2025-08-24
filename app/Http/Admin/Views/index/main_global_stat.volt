<div class="layui-card kg-stats">
    <div class="layui-card-header">{{ locale._('site_stats') }}</div>
    <div class="layui-card-body">
        <div class="layui-row layui-col-space10">
            <div class="layui-col-md4">
                <div class="kg-stat-card">
                    <div class="count">{{ global_stat.user_count }}</div>
                    <div class="name">{{ locale._('stat_users') }}</div>
                </div>
            </div>
            <div class="layui-col-md4">
                <div class="kg-stat-card">
                    <div class="count">{{ global_stat.vip_count }}</div>
                    <div class="name">{{ locale._('stat_vip_users') }}</div>
                </div>
            </div>
            <div class="layui-col-md4">
                <div class="kg-stat-card">
                    <div class="count">{{ global_stat.course_count }}</div>
                    <div class="name">{{ locale._('stat_courses') }}</div>
                </div>
            </div>
            <div class="layui-col-md4">
                <div class="kg-stat-card">
                    <div class="count">{{ global_stat.package_count }}</div>
                    <div class="name">{{ locale._('stat_packages') }}</div>
                </div>
            </div>
            <div class="layui-col-md4">
                <div class="kg-stat-card">
                    <div class="count">{{ global_stat.review_count }}</div>
                    <div class="name">{{ locale._('stat_reviews') }}</div>
                </div>
            </div>
            <div class="layui-col-md4">
                <div class="kg-stat-card">
                    <div class="count">{{ global_stat.comment_count }}</div>
                    <div class="name">{{ locale._('stat_comments') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

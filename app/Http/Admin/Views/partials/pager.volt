{% if pager.total_items > 0 and pager.last != pager.first %}
    <div class="kg-pager">
        <div class="layui-box layui-laypage layui-laypage-default">
            <a href="{{ pager.first }}">{{ locale._('pager_first') }}</a>
            <a href="{{ pager.previous }}">{{ locale._('pager_prev') }}</a>
            <a href="{{ pager.next }}">{{ locale._('pager_next') }}</a>
            <a href="{{ pager.last }}">{{ locale._('pager_last') }}</a>
        </div>
    </div>
{% endif %}

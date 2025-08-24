{% if pager.total_items > 0 and pager.last != pager.first %}
    <div class="kg-pager">
        <div class="layui-box layui-laypage layui-laypage-default">
            <a href="javascript:" data-target="{{ pager.target }}" data-url="{{ pager.first }}">{{ locale._('pager_first') }}</a>
            <a href="javascript:" data-target="{{ pager.target }}" data-url="{{ pager.previous }}">{{ locale._('pager_prev') }}</a>
            <a href="javascript:" data-target="{{ pager.target }}" data-url="{{ pager.next }}">{{ locale._('pager_next') }}</a>
            <a href="javascript:" data-target="{{ pager.target }}" data-url="{{ pager.last }}">{{ locale._('pager_last') }}</a>
        </div>
    </div>
{% endif %}

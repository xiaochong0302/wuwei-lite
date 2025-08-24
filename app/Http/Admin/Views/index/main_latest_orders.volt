<div class="layui-card layui-text">
    <div class="layui-card-header">{{ locale._('latest_orders') }}</div>
    <div class="layui-card-body">
        <table class="layui-table">
            <colgroup>
                <col>
                <col>
                <col>
                <col>
                <col width="10%">
            </colgroup>
            <thead>
            <tr>
                <th>{{ locale._('order_product') }}</th>
                <th>{{ locale._('order_amount') }}</th>
                <th>{{ locale._('order_status') }}</th>
                <th>{{ locale._('create_time') }}</th>
                <th>{{ locale._('actions') }}</th>
            </tr>
            </thead>
            <tbody>
            {% for order in latest_orders %}
                {% set show_url = url({'for':'admin.order.show','id':order.id}) %}
                <tr>
                    <td>{{ order.subject }}</td>
                    <td>{{ order.amount|human_price }}</td>
                    <td>{{ order_status(order.status) }}</td>
                    <td>{{ date('Y-m-d H:i',order.create_time) }}</td>
                    <td class="center">
                        <a class="layui-btn layui-btn-sm" href="{{ show_url }}">{{ locale._('details') }}</a>
                    </td>
                </tr>
            {% endfor %}
            </tbody>
        </table>
    </div>
</div>

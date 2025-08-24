{% set order_sh_url = url({'for':'admin.order.status_history','id':order.id}) %}

<fieldset class="layui-elem-field layui-field-title">
    <legend>{{ locale._('order_info') }}</legend>
</fieldset>
<br>
<table class="kg-table layui-table">
    <tr>
        <th>{{ locale._('order_product') }}</th>
        <th>{{ locale._('order_no') }}</th>
        <th>{{ locale._('order_amount') }}</th>
        <th>{{ locale._('payment_type') }}</th>
        <th>{{ locale._('order_status') }}</th>
        <th>{{ locale._('create_time') }}</th>
    </tr>
    <tr>
        <td>{{ item_info(order) }}</td>
        <td>{{ order.sn }}</td>
        <td>{{ order.amount|human_price }}</td>
        <td>{{ payment_type(order.payment_type) }}</td>
        <td><a class="kg-status-history" href="javascript:" title="{{ locale._('status_timeline') }}" data-url="{{ order_sh_url }}">{{ order_status(order.status) }}</a></td>
        <td>{{ date('Y-m-d H:i',order.create_time) }}</td>
    </tr>
</table>
<br>
<div class="kg-center">
    <button class="layui-btn layui-btn-primary kg-back">{{ locale._('back') }}</button>
</div>

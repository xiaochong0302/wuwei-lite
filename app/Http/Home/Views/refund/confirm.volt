{% extends 'templates/layer.volt' %}

{% block content %}

    {%- macro item_info(confirm) %}
        {% if confirm.item_type == 1 %}
            {% set course = confirm.item_info.course %}
            <div class="order-item">
                <p><label class="key colon">{{ locale._('sale_item_course') }}</label><span class="value">{{ course.title }}</span></p>
                <p><label class="key colon">{{ locale._('refund_expiry') }}</label><span class="value">{{ date('Y-m-d H:i:s',course.refund_expiry_time) }}</span></p>
                <p><label class="key colon">{{ locale._('refund_amount') }}</label><span class="value price">{{ course.refund_amount|human_price }}</span>
                    <span class="rate">({{ 100 * course.refund_rate }}%)</span></p>
            </div>
        {% elseif confirm.item_type == 2 %}
            {% set courses = confirm.item_info.courses %}
            {% for course in courses %}
                <div class="order-item">
                    <p><label class="key colon">{{ locale._('sale_item_course') }}</label><span class="value">{{ course.title }}</span></p>
                    <p><label class="key colon">{{ locale._('refund_expiry') }}</label><span class="value">{{ date('Y-m-d H:i:s',course.refund_expiry_time) }}</span></p>
                    <p><label class="key colon">{{ locale._('refund_amount') }}</label><span class="value price">{{ course.refund_amount|human_price }}</span>
                        <span class="rate">({{ 100 * course.refund_rate }}%)</span></p>
                </div>
            {% endfor %}
        {% endif %}
    {%- endmacro %}

    <table class="layui-table kg-table">
        <tr>
            <td>{{ locale._('refund_items') }}</td>
            <td>{{ locale._('origin_price') }}</td>
            <td>{{ locale._('service_fee') }} ({{ confirm.service_rate }}%)</td>
            <td>{{ locale._('refund_amount') }}</td>
        </tr>
        <tr>
            <td>{{ item_info(confirm) }}</td>
            <td><span class="price">{{ order.amount|human_price }}</span></td>
            <td><span class="price">{{ confirm.service_fee|human_price }}</span></td>
            <td><span class="price">{{ confirm.refund_amount|human_price }}</span></td>
        </tr>
    </table>
    <br>
    {% if confirm.refund_amount > 0 %}
        <form class="layui-form" method="POST" action="{{ url({'for':'home.refund.create'}) }}">
            <div class="layui-form-item">
                <textarea class="layui-textarea" name="apply_note" placeholder="{{ locale._('refund_reason_tips') }}" lay-verify="required"></textarea>
            </div>
            <div class="layui-form-item center">
                <button class="layui-btn layui-bg-red" lay-submit="true" lay-filter="go">{{ locale._('confirm_refund') }}</button>
                <input type="hidden" name="order_sn" value="{{ order.sn }}">
            </div>
        </form>
    {% endif %}

{% endblock %}

{% block inline_js %}

    <script>
        layui.use(['jquery', 'layer', 'helper'], function () {
            var helper = layui.helper;
            var index = parent.layer.getFrameIndex(window.name);
            var title = helper.locale('request_refund');
            parent.layer.title(title, index);
            parent.layer.iframeAuto(index);
        });
    </script>

{% endblock %}

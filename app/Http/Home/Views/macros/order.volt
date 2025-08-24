{%- macro item_info(order) %}
    {% if order.item_type == 1 %}
        {% set course = order.item_info.course %}
        <div class="order-item">
            <p>{{ locale._('sale_item_course') }}：{{ course.title }}</p>
            <p>
                <label class="key colon">{{ locale._('regular_price') }}</label><span class="value price">{{ course.regular_price|human_price }}</span>
                <label class="key colon">{{ locale._('vip_price') }}</label><span class="value price">{{ course.vip_price|human_price }}</span>
            </p>
            <p>
                <label class="key colon">{{ locale._('study_expiry') }}</label><span class="value">{{ date('Y-m-d',course.study_expiry_time) }}</span>
                <label class="key colon">{{ locale._('refund_expiry') }}</label><span class="value">{{ course.refund_expiry > 0 ? date('Y-m-d',course.refund_expiry_time) : 'N/A' }}</span>
            </p>
        </div>
    {% elseif order.item_type == 2 %}
        {% set courses = order.item_info.courses %}
        {% for course in courses %}
            <div class="order-item">
                <p>{{ locale._('sale_item_course') }}：{{ course.title }}</p>
                <p>
                    <label class="key colon">{{ locale._('regular_price') }}</label><span class="value price">{{ course.regular_price|human_price }}</span>
                    <label class="key colon">{{ locale._('vip_price') }}</label><span class="value price">{{ course.vip_price_human_price }}</span>
                </p>
                <p>
                    <label class="key colon">{{ locale._('study_expiry') }}</label><span class="value">{{ date('Y-m-d',course.study_expiry_time) }}</span>
                    <label class="key colon">{{ locale._('refund_expiry') }}</label><span class="value">{{ course.refund_expiry > 0 ? date('Y-m-d',course.refund_expiry_time) : 'N/A' }}</span>
                </p>
            </div>
        {% endfor %}
    {% elseif order.item_type == 3 %}
        {% set vip = order.item_info.vip %}
        <div class="order-item">
            <p><label class="key colon">{{ locale._('vip_plan_duration') }}</label><span class="value">{{ order.subject }}</span></p>
            <p><label class="key colon">{{ locale._('price') }}</label><span class="value price">{{ vip.price|human_price }}</span></p>
        </div>
    {% endif %}
{%- endmacro %}

{%- macro status_history(items) %}
    {% for item in items %}
        <p><label class="key colon">{{ order_status(item.status) }}</label><span class="value">{{ date('Y-m-d H:i:s',item.create_time) }}</span></p>
    {% endfor %}
{%- endmacro %}

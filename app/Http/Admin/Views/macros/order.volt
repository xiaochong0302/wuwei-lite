{%- macro item_info(order) %}
    {% if order.item_type == 1 %}
        {% set course = order.item_info['course'] %}
        <div class="kg-order-item">
            <p>{{ locale._('sale_item_course') }}：{{ course['title'] }}</p>
            <p>
                <span>{{ locale._('regular_price') }}：{{ course['regular_price']|human_price }}</span>
                <span>{{ locale._('vip_price') }}：{{ course['vip_price']|human_price }}</span>
            </p>
            <p>
                <span>{{ locale._('study_expiry') }}：{{ date('Y-m-d',course['study_expiry_time']) }}</span>
                <span>{{ locale._('refund_expiry') }}：{{ date('Y-m-d',course['refund_expiry_time']) }}</span>
            </p>
        </div>
    {% elseif order.item_type == 2 %}
        {% set courses = order.item_info['courses'] %}
        {% for course in courses %}
            <div class="kg-order-item">
                <p>{{ locale._('sale_item_course') }}：{{ course['title'] }}</p>
                <p>
                    <span>{{ locale._('regular_price') }}：{{ course['regular_price']|human_price }}</span>
                    <span>{{ locale._('vip_price') }}：{{ course['vip_price']|human_price }}</span>
                </p>
                <p>
                    <span>{{ locale._('study_expiry') }}：{{ date('Y-m-d',course['study_expiry_time']) }}</span>
                    <span>{{ locale._('refund_expiry') }}：{{ date('Y-m-d',course['refund_expiry_time']) }}</span>
                </p>
            </div>
        {% endfor %}
    {% elseif order.item_type == 3 %}
        {% set vip = order.item_info['vip'] %}
        <div class="kg-order-item">
            <p>{{ locale._('sale_item_vip') }}：{{ vip['title'] }}</p>
            <p>
                <span>{{ locale._('price') }}：{{ vip['price']|human_price }}</span>
                <span>{{ locale._('expiry') }}：{{ date('Y-m-d',vip['expiry_time']) }}</span>
            </p>
        </div>
    {% endif %}
{%- endmacro %}

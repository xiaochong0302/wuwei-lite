<form class="layui-form kg-form" method="POST" action="{{ update_url }}">
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('regular_price') }}</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="regular_price" value="{{ course.regular_price }}" lay-verify="number">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('vip_price') }}</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="vip_price" value="{{ course.vip_price }}" lay-verify="number">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('study_period') }}</label>
        <div class="layui-input-block">
            <select name="study_expiry">
                <option value="">{{ locale._('select') }}</option>
                {% for value in study_expiry_options %}
                    <option value="{{ value }}" {% if value == course.study_expiry %}selected="selected"{% endif %}>{{ locale._('month_x',['x':value]) }}</option>
                {% endfor %}
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">{{ locale._('refund_period') }}</label>
        <div class="layui-input-block">
            <select name="refund_expiry">
                <option value="">{{ locale._('select') }}</option>
                {% for value in refund_expiry_options %}
                    <option value="{{ value }}" {% if value == course.refund_expiry %}selected="selected"{% endif %}>{{ locale._('day_x',['x':value]) }}</option>
                {% endfor %}
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
            <button id="sale-submit" class="layui-btn" lay-submit="true" lay-filter="go">{{ locale._('submit') }}</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">{{ locale._('back') }}</button>
        </div>
    </div>
</form>

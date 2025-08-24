{% extends 'templates/layer.volt' %}

{% block content %}

    <form class="layui-form">
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('math_question') }}</label>
            <div class="layui-input-block">
                <img id="img-captcha" class="pointer" title="Click to refresh" width="200" height="50">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ locale._('math_answer') }}</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="rand">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="true" lay-filter="captcha">{{ locale._('submit') }}</button>
                <button type="reset" class="layui-btn layui-btn-primary">{{ locale._('reset') }}</button>
            </div>
        </div>
    </form>

    <div class="layui-hide">
        <input type="hidden" name="email" value="{{ request.get('email') }}">
        <input type="hidden" name="ticket">
    </div>

{% endblock %}

{% block include_js %}

    <script>
        layui.use(['jquery', 'form', 'layer', 'helper'], function () {

            var $ = layui.jquery;
            var form = layui.form;
            var layer = layui.layer;
            var helper = layui.helper;
            var index = parent.layer.getFrameIndex(window.name);

            var showCaptchaImage = function () {
                $.get('/api/verify/captcha', function (res) {
                    $('#img-captcha').attr('src', res.captcha.content);
                    $('input[name=ticket]').val(res.captcha.ticket);
                });
            };

            $('#img-captcha').on('click', function () {
                showCaptchaImage();
            });

            form.on('submit(captcha)', function (data) {
                var submit = $(this);
                var email = $('input[name=email]').val();
                var ticket = $('input[name=ticket]').val();
                var rand = $('input[name=rand]').val();
                submit.attr('disabled', 'disabled').addClass('layui-btn-disabled');
                parent.layui.$('#cv-submit-btn').removeAttr('disabled').removeClass('layui-btn-disabled');
                parent.layui.$('#cv-captcha-ticket').val(ticket);
                parent.layui.$('#cv-captcha-rand').val(rand);
                $.ajax({
                    type: 'POST',
                    url: '/api/verify/code',
                    data: {
                        email: email,
                        ticket: ticket,
                        rand: rand,
                    },
                    success: function () {
                        layer.msg(helper.locale('send_verify_code_ok'), {icon: 1});
                        setTimeout(function () {
                            parent.layer.close(index);
                        }, 1500);
                    }, error: function () {
                        submit.removeAttr('disabled').removeClass('layui-btn-disabled');
                    }
                });
                return false;
            });

            showCaptchaImage();

        });
    </script>

{% endblock %}

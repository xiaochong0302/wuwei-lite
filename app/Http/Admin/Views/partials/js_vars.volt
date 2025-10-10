<div id="js-locale" class="layui-hide">{{ js_locale|json_encode }}</div>

<script>
    window.locale = JSON.parse(document.querySelector('#js-locale').textContent);
</script>

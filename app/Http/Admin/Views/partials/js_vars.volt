<input type="hidden" id="js-locale" value='{{ js_locale|json_encode }}'></input>

<script>
    window.locale = JSON.parse(document.querySelector('#js-locale').value);
</script>
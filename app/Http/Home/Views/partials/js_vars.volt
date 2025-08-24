<input type="hidden" id="js-locale" value='{{ js_locale|json_encode }}'></input>

<script>

    window.user = {
        id: '{{ auth_user.id }}',
        name: '{{ auth_user.name }}',
        avatar: '{{ auth_user.avatar }}',
        locked: '{{ auth_user.locked }}',
        vip: '{{ auth_user.vip }}',
    };

    window.contact = {
        enabled: '{{ contact_info.enabled }}',
        twitter: '{{ contact_info.twitter }}',
        facebook: '{{ contact_info.facebook }}',
        youtube: '{{ contact_info.youtube }}',
        reddit: '{{ contact_info.reddit }}',
        linkedin: '{{ contact_info.linkedin }}',
        email: '{{ contact_info.email }}',
        phone: '{{ contact_info.phone }}',
        address: '{{ contact_info.address }}',
    };

    window.locale = JSON.parse(document.querySelector('#js-locale').value);

</script>

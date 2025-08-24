<div class="layui-main">
    <div class="row nav">
        {% for nav in navs.bottom %}
            <a href="{{ nav.url }}" target="{{ nav.target }}">{{ nav.name }}</a>
        {% endfor %}
    </div>
    <div class="row copyright">
        {% if site_info.copyright %}
            <span>&copy; {{ site_info.copyright }}</span>
        {% endif %}
        <a href="{{ app_info.link }}" title="{{ app_info.name }}" target="_blank">Powered by {{ app_info.alias }} {{ app_info.version }}</a>
    </div>
    {% if contact_info.enabled == 1 %}
        <div class="row contact">
            {% if contact_info.twitter %}
                {% set link_url = 'https://www.twitter.com/%s'|format(contact_info.twitter) %}
                <a class="twitter" href="{{ link_url }}" title="twitter" target="_blank"><span class="iconfont icon-twitter"></span></a>
            {% endif %}
            {% if contact_info.facebook %}
                {% set link_url = 'https://www.facebook.com/%s'|format(contact_info.facebook) %}
                <a class="facebook" href="{{ link_url }}" title="facebook" target="_blank"><span class="iconfont icon-facebook"></span></a>
            {% endif %}
            {% if contact_info.youtube %}
                {% set link_url = 'https://www.youtube.com/@%s'|format(contact_info.youtube) %}
                <a class="youtube" href="{{ link_url }}" title="youtube" target="_blank"><span class="iconfont icon-youtube"></span></a>
            {% endif %}
            {% if contact_info.reddit %}
                {% set link_url = 'https://www.reddit.com/user/%s'|format(contact_info.reddit) %}
                <a class="reddit" href="{{ link_url }}" title="reddit" target="_blank"><span class="iconfont icon-reddit"></span></a>
            {% endif %}
            {% if contact_info.linkedin %}
                {% set link_url = 'https://www.linkedin.com/in/%s'|format(contact_info.linkedin) %}
                <a class="linkedin" href="{{ link_url }}" title="linkedin" target="_blank"><span class="iconfont icon-linkedin"></span></a>
            {% endif %}
            {% if contact_info.email %}
                {% set link_url = 'mailto:%s'|format(contact_info.email) %}
                <a class="email" href="{{ link_url }}" title="{{ contact_info.email }}"><span class="iconfont icon-email"></span></a>
            {% endif %}
            {% if contact_info.phone %}
                <a class="phone" href="javascript:" title="{{ contact_info.phone }}"><span class="iconfont icon-phone"></span></a>
            {% endif %}
            {% if contact_info.address %}
                {% set link_url = 'https://www.google.com/maps/search/?q=%s'|format(contact_info.address) %}
                <a class="location" href="{{ link_url }}" title="{{ contact_info.address }}" target="_blank"><span class="iconfont icon-location"></span></a>
            {% endif %}
        </div>
    {% endif %}
</div>

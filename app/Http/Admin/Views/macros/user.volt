{%- macro edu_role_info(role) %}
    {% if role.id == 1 %}
        {{ locale._('edu_role_student') }}
    {% elseif role.id == 2 %}
        {{ locale._('edu_role_teacher') }}
    {% endif %}
{%- endmacro %}

{%- macro admin_role_info(role) %}
    {% if role.id > 0 %}
        {{ role.name }}
    {% else %}
        N/A
    {% endif %}
{%- endmacro %}

{%- macro client_type(value) %}
    {% if value == 1 %}
        PC
    {% elseif value == 2 %}
        H5
    {% elseif value == 3 %}
        APP
    {% else %}
        N/A
    {% endif %}
{%- endmacro %}

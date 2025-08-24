{%- macro join_type(value) %}
    {% if value == 1 %}
        {{ locale._('cu_join_type_free') }}
    {% elseif value == 2 %}
        {{ locale._('cu_join_type_trial') }}
    {% elseif value == 3 %}
        {{ locale._('cu_join_type_purchase') }}
    {% elseif value == 4 %}
        {{ locale._('cu_join_type_vip') }}
    {% elseif value == 5 %}
        {{ locale._('cu_join_type_manual') }}
    {% elseif value == 6 %}
        {{ locale._('cu_join_type_teacher') }}
    {% else %}
        N/A
    {% endif %}
{%- endmacro %}

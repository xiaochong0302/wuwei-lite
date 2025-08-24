{% if course.teacher %}
    {% set teacher = course.teacher %}
    <div class="sidebar">
        <div class="layui-card">
            <div class="layui-card-header">{{ locale._('course_teacher') }}</div>
            <div class="layui-card-body">
                {% set teacher_url = url({'for':'home.teacher.show','id':teacher.id,'name':teacher.name}) %}
                <div class="sidebar-user-card" title="{{ teacher.about }}">
                    <div class="avatar">
                        <img src="{{ teacher.avatar }}!avatar_160" alt="{{ teacher.name }}">
                    </div>
                    <div class="info">
                        <div class="name layui-elip">
                            <a href="{{ teacher_url }}" target="_blank">{{ teacher.name }}</a>
                        </div>
                        <div class="title layui-elip">{{ teacher.title|default(locale._('title_educator')) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{% endif %}

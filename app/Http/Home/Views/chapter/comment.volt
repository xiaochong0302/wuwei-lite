{% set comment_list_url = url({'for':'home.chapter.comments','id':chapter.id}) %}
{% set comment_create_url = url({'for':'home.comment.create'}) %}

<div class="comment-form" id="comment-form">
    <form class="layui-form" method="POST" action="{{ comment_create_url }}">
        <textarea class="layui-textarea" id="comment-content" name="content" placeholder="{{ locale._('join_discussion_tips') }}" lay-verify="required"></textarea>
        <div class="footer" id="comment-footer" style="display:none;">
            <div class="toolbar"></div>
            <div class="action">
                <button class="layui-btn layui-btn-sm" lay-submit="true" lay-filter="add_comment">{{ locale._('submit') }}</button>
                <button class="layui-btn layui-btn-sm layui-btn-primary" id="comment-cancel" type="button">{{ locale._('cancel') }}</button>
            </div>
        </div>
        <input type="hidden" name="chapter_id" value="{{ chapter.id }}">
    </form>
</div>

<div class="comment-list" id="comment-list" data-url="{{ comment_list_url }}"></div>

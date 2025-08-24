layui.use(['jquery', 'layer', 'helper'], function () {

    var $ = layui.jquery;
    var layer = layui.layer;
    var helper = layui.helper;

    /**
     * 发布评价
     */
    $('.btn-add-review').on('click', function () {
        var title = helper.locale('add_review');
        var url = $(this).data('url');
        layer.open({
            type: 2,
            title: title,
            content: [url, 'no'],
            area: ['640px', '380px'],
        });
    });

    /**
     * 修改评价
     */
    $('.btn-edit-review').on('click', function () {
        var title = helper.locale('edit_review');
        var url = $(this).data('url');
        layer.open({
            type: 2,
            title: title,
            content: [url, 'no'],
            area: ['640px', '380px'],
        });
    });

    /**
     * 订单详情
     */
    $('.btn-order-info').on('click', function () {
        var title = helper.locale('order_info');
        var url = $(this).data('url');
        layer.open({
            type: 2,
            title: title,
            content: [url, 'no'],
            area: '960px',
            offset: '200px',
        });
    });

    /**
     * 退款详情
     */
    $('.btn-refund-info').on('click', function () {
        var title = helper.locale('refund_info');
        var url = $(this).data('url');
        layer.open({
            type: 2,
            title: title,
            content: [url, 'no'],
            area: '960px',
            offset: '200px',
        });
    });

});

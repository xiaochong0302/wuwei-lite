layui.use(['jquery', 'rate'], function () {

    var $ = layui.jquery;
    var rate = layui.rate;
    var $rating = $('input[name=rating]');

    rate.render({
        elem: '#rating',
        value: $rating.val(),
        choose: function (value) {
            $rating.val(value);
        }
    });

});
